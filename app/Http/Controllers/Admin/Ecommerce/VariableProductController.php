<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VariableProductController extends Controller
{
    /**
     * List every variable product.
     */
    public function index()
    {
        $products = Product::where('is_variable', true)
            ->with('categories', 'colors')
            ->latest('id')
            ->paginate(15);

        return view('admin.e-commerce.variable-product.index', compact('products'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $colors = DB::table('colors')->latest('id')->get(['id', 'name', 'slug', 'code']);

        return view('admin.e-commerce.variable-product.form', compact('categories', 'colors'));
    }

    /**
     * Store a new variable product: the reduced field set plus one row per
     * colour, each carrying its own image, price and stock.
     */
    public function store(Request $request)
    {
        $this->validateVariable($request);

        $variations = $this->collectVariations($request);
        if (empty($variations)) {
            return back()->withInput()->withErrors([
                'colors' => 'Add at least one colour, each with a price and an image.',
            ]);
        }

        [$videoName, $videoTName] = $this->handleVideoUpload($request);
        $linky = $this->youtubeEmbed($request->yvideo);

        $first = $variations[0];

        $product = Product::create([
            'user_id' => auth()->id(),
            'slug' => rand(pow(10, 5 - 1), pow(10, 15) - 1),
            'title' => $request->title,
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'full_description' => $request->full_description,
            'shipping_concierge' => $request->shipping_concierge,
            'warranty_returns' => $request->warranty_returns,
            'spec' => $this->buildSpecJson($request),
            'video' => $videoName,
            'video_thumb' => $videoTName,
            'yvideo' => $linky,
            'regular_price' => $first['price'],      // card "from" price = first colour
            'discount_price' => null,
            'quantity' => array_sum(array_column($variations, 'qnty')),
            'image' => $first['image'],              // hero = first colour image
            'status' => $request->filled('status'),
            'is_aproved' => $request->filled('status'),
            'is_shown_on_homepage' => $request->filled('is_shown_on_homepage'),
            'is_variable' => true,
            'type' => 0,
            'shipping_charge' => 1,
        ]);

        $product->categories()->sync($request->categories, []);
        $this->syncSubCategories($request, $product);
        $this->syncVariations($product, $variations);
        $this->saveLifestyleImages($request, $product);

        return redirect()->to(routeHelper('variable-products'))
            ->with('success', 'Variable product created.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Product $variable_product)
    {
        $product = $variable_product;
        abort_unless($product->is_variable, 404);

        $categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $colors = DB::table('colors')->latest('id')->get(['id', 'name', 'slug', 'code']);

        $colors_product = DB::table('color_product')
            ->join('colors', 'colors.id', '=', 'color_product.color_id')
            ->where('color_product.product_id', $product->id)
            ->select('color_product.*', 'colors.name', 'colors.code', 'colors.slug')
            ->get();

        return view('admin.e-commerce.variable-product.form', compact('product', 'categories', 'colors', 'colors_product'));
    }

    /**
     * Update a variable product.
     */
    public function update(Request $request, Product $variable_product)
    {
        $product = $variable_product;
        abort_unless($product->is_variable, 404);

        $this->validateVariable($request);

        $variations = $this->collectVariations($request);
        if (empty($variations)) {
            return back()->withInput()->withErrors([
                'colors' => 'Add at least one colour, each with a price and an image.',
            ]);
        }

        [$videoName, $videoTName] = $this->handleVideoUpload($request, $product);
        $linky = $this->youtubeEmbed($request->yvideo);

        $first = $variations[0];

        $product->update([
            'title' => $request->title,
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'full_description' => $request->full_description,
            'shipping_concierge' => $request->shipping_concierge,
            'warranty_returns' => $request->warranty_returns,
            'spec' => $this->buildSpecJson($request),
            'video' => $videoName,
            'video_thumb' => $videoTName,
            'yvideo' => $linky ?? $request->yvideo,
            'regular_price' => $first['price'],
            'discount_price' => null,
            'quantity' => array_sum(array_column($variations, 'qnty')),
            'image' => $first['image'],
            'status' => $request->filled('status'),
            'is_aproved' => $request->filled('status'),
            'is_shown_on_homepage' => $request->filled('is_shown_on_homepage'),
        ]);

        $product->categories()->sync($request->categories);
        $this->syncSubCategories($request, $product, true);

        // Replace the colour set with the submitted rows.
        DB::table('color_product')->where('product_id', $product->id)->delete();
        $this->syncVariations($product, $variations);

        $this->saveLifestyleImages($request, $product);
        foreach ($request->input('existing_lifestyle', []) as $imageId => $fields) {
            $product->images()->where('id', $imageId)->update([
                'tag' => $fields['tag'] ?? null,
                'caption' => $fields['caption'] ?? null,
            ]);
        }

        return redirect()->to(routeHelper('variable-products'))
            ->with('success', 'Variable product updated.');
    }

    /**
     * Delete a variable product and its stored files.
     */
    public function destroy(Product $variable_product)
    {
        $product = $variable_product;
        abort_unless($product->is_variable, 404);

        foreach ($product->images as $image) {
            if ($image->name && file_exists(public_path('uploads/product/'.$image->name))) {
                unlink(public_path('uploads/product/'.$image->name));
            }
        }
        DB::table('color_product')->where('product_id', $product->id)->delete();
        $product->images()->delete();
        $product->categories()->detach();
        $product->delete();

        return redirect()->to(routeHelper('variable-products'))
            ->with('success', 'Variable product deleted.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function validateVariable(Request $request): void
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'categories' => 'required|array',
            'categories.*' => 'integer',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'shipping_concierge' => 'nullable|string',
            'warranty_returns' => 'nullable|string',
            'spec_labels' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'colors' => 'required|array|min:1',
            'colors.*' => 'nullable|integer',
            'color_prices' => 'nullable|array',
            'color_quantits' => 'nullable|array',
            'color_images.*' => 'nullable|image',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v',
            'video_thumb' => 'nullable|image',
            'remove_video' => 'nullable|boolean',
            'lifestyle_images' => 'nullable|array',
            'lifestyle_images.*' => 'nullable|image',
            'lifestyle_tags' => 'nullable|array',
            'lifestyle_captions' => 'nullable|array',
            'existing_lifestyle' => 'nullable|array',
        ], [
            'color_images.*.uploaded' => 'One of the colour images failed to upload — please try a smaller file.',
        ]);
    }

    /**
     * Build the ordered variation list, moving any newly uploaded colour images.
     * A row is kept only when it has a colour and an image (new or existing).
     */
    private function collectVariations(Request $request): array
    {
        $variations = [];

        foreach ($request->input('colors', []) as $idx => $colorId) {
            if (! $colorId) {
                continue;
            }

            $image = $request->input("existing_color_images.$idx");
            if ($file = $request->file("color_images.$idx")) {
                $image = $this->moveUpload($file);
            }
            if (! $image) {
                continue; // no image for this colour — skip it
            }

            $variations[] = [
                'color_id' => (int) $colorId,
                'price' => (float) $request->input("color_prices.$idx", 0),
                'qnty' => (int) $request->input("color_quantits.$idx", 0),
                'image' => $image,
            ];
        }

        return $variations;
    }

    private function syncVariations(Product $product, array $variations): void
    {
        foreach ($variations as $v) {
            DB::table('color_product')->insert([
                'color_id' => $v['color_id'],
                'product_id' => $product->id,
                'qnty' => $v['qnty'],
                'price' => $v['price'],
                'image' => $v['image'],
            ]);
        }
    }

    private function syncSubCategories(Request $request, Product $product, bool $isUpdate = false): void
    {
        if (empty($request->get('sub_categories'))) {
            if ($isUpdate) {
                $product->sub_categories()->sync([]);
            }

            return;
        }

        $product->sub_categories()->sync($request->sub_categories);
    }

    private function handleVideoUpload(Request $request, ?Product $product = null): array
    {
        $videoName = $product?->video;
        $videoTName = $product?->video_thumb;

        if ($file = $request->file('video')) {
            $videoName = $this->moveUpload($file, 'uploads/product/video');
        } elseif ($product && $request->boolean('remove_video')) {
            if ($product->video && file_exists(public_path('uploads/product/video/'.$product->video))) {
                unlink(public_path('uploads/product/video/'.$product->video));
            }
            $videoName = null;
        }

        if ($thumb = $request->file('video_thumb')) {
            $videoTName = $this->moveUpload($thumb, 'uploads/product/video');
        } elseif ($product && $request->boolean('remove_video')) {
            $videoTName = null;
        }

        return [$videoName, $videoTName];
    }

    private function moveUpload($file, string $dir = 'uploads/product'): string
    {
        $name = Carbon::now()->toDateString().'-'.uniqid().'.'.$file->getClientOriginalExtension();
        if (! file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
        $file->move(public_path($dir), $name);

        return $name;
    }

    private function youtubeEmbed(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $provider = parse_url($url);
        if (empty($provider['host'])) {
            return null;
        }
        $base = trim(ltrim($provider['host'], 'www.'), '.com');
        if ($base !== 'youtu.be' && $base !== 'youtube') {
            return null;
        }

        $youtube_id = null;
        if (preg_match('/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i', $url, $m)) {
            $youtube_id = $m[count($m) - 1];
        }
        if (preg_match('/youtu.be\/([a-zA-Z0-9_-]+)\??/i', $url, $m)) {
            $youtube_id = $m[count($m) - 1];
        }

        return $youtube_id ? 'https://www.youtube.com/embed/'.$youtube_id.'?rel=0&enablejsapi=1' : null;
    }

    private function buildSpecJson(Request $request): ?string
    {
        $labels = $request->input('spec_labels', []);
        $values = $request->input('spec_values', []);
        $specs = [];

        foreach ($labels as $i => $label) {
            $label = trim((string) $label);
            $value = trim((string) ($values[$i] ?? ''));
            if ($label !== '' && $value !== '') {
                $specs[] = ['label' => $label, 'value' => $value];
            }
        }

        return $specs ? json_encode($specs) : null;
    }

    private function saveLifestyleImages(Request $request, Product $product): void
    {
        foreach ($request->file('lifestyle_images', []) as $key => $image) {
            if (! $image) {
                continue;
            }
            $name = $this->moveUpload($image);
            $product->images()->create([
                'name' => $name,
                'section' => 'lifestyle',
                'tag' => $request->input("lifestyle_tags.$key"),
                'caption' => $request->input("lifestyle_captions.$key"),
            ]);
        }
    }
}
