<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ExtraMiniCategory;
use App\Models\miniCategory;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $query = Product::with('brand', 'categories');

        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        switch ($request->filter) {
            case 'inhouse':
                $query->where('type', '1');
                break;
            case 'low_stock':
                $query->where('quantity', '<', 6);
                break;
            case 'reached':
                $query->where('reach', '>', 0);
                break;
            case 'unapproved':
                $query->where('is_aproved', false);
                break;
            case 'approved':
                $query->where('is_aproved', true);
                break;
        }

        // ✅ CSV Export Trigger
        if ($request->has('export') && $request->export == 'csv') {
            $products = $query->get();

            $headers = [
                'id', 'title', 'description', 'availability', 'condition', 'price', 'link',
                'image_link', 'brand', 'google_product_category', 'fb_product_category',
                'quantity_to_sell_on_facebook', 'sale_price', 'sale_price_effective_date',
                'item_group_id', 'gender', 'color', 'size', 'age_group', 'material', 'pattern',
                'shipping', 'shipping_weight', 'gtin', 'video[0].url', 'video[0].tag[0]',
                'product_tags[0]', 'product_tags[1]', 'style[0]',
            ];

            $rows = [];

            foreach ($products as $product) {
                $rows[] = [
                    $product->id,
                    $product->title,
                    $product->short_description ?? '',
                    $product->quantity > 0 ? 'in stock' : 'out of stock',
                    'new',
                    $product->regular_price, // number_format($product->regular_price, 2) . ' Taka',
                    url('/product/'.$product->slug),
                    $product->image ? url('/uploads/product/'.$product->image) : '',
                    $product->brand->name ?? '',
                    '', // google_product_category
                    '', // fb_product_category
                    $product->quantity ?? '',
                    $product->regular_price, // number_format($product->regular_price, 2) . ' Taka',
                    '', // sale_price_effective_date
                    '', // item_group_id
                    '', // gender
                    $product->color ?? '',
                    $product->size ?? '',
                    '18-55', // age_group
                    $product->material ?? '',
                    $product->pattern ?? '',
                    '', // shipping
                    $product->shipping_weight ?? '',
                    $product->gtin ?? '',
                    '', '', '', '', '',
                ];
            }

            $filename = 'all_products_'.now()->format('Y-m-d_H-i-s').'.csv';

            $handle = fopen('php://temp', 'r+');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response()->make($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename={$filename}",
            ]);
        }

        $products = $query->latest()->paginate(10)->appends($request->query());

        $categories = Category::all();
        $brands = Brand::all();
        $statuses = ['1' => 'Active', '0' => 'Inactive'];

        return view('admin.e-commerce.product.index', compact('products', 'categories', 'brands', 'statuses'));
    }

    public function lowProduct()
    {
        $products = Product::where('quantity', '<', '6')->where('user_id', auth()->id())->paginate(10);
        $categories = Category::all();
        $brands = Brand::all();
        $statuses = ['1' => 'Active', '0' => 'Inactive'];

        return view('admin.e-commerce.product.index', compact('products', 'categories', 'brands', 'statuses'));
    }

    public function commetn_delte($id)
    {
        $comment = Comment::find($id);
        if ($comment->replies) {
            foreach ($comment->replies as $cc) {
                $cc->delete();
            }
        }
        $comment->delete();
        notify()->success('Comment deleted', 'Delete');

        return back();
    }

    public function rating_delte($id)
    {
        $comment = Review::find($id);
        $comment->delete();
        notify()->success('Rating deleted', 'Delete');

        return back();
    }

    public function rating_edit($id)
    {
        $rating = Review::find($id);

        return view('admin.e-commerce.product.rating', compact('rating'));
    }

    public function rating_update(Request $request)
    {
        $check = Review::find($request->id);
        $book = $request->file('report');
        if ($book) {
            $bookName = $this->upload($book);
        } else {
            $bookName = $check->file;
        }
        $book2 = $request->file('report2');
        if ($book2) {
            $bookName2 = $this->upload($book2);
        } else {
            $bookName2 = $check->file2;
        }
        $book3 = $request->file('report3');
        if ($book3) {
            $bookName3 = $this->upload($book3);
        } else {
            $bookName3 = $check->file3;
        }
        $book4 = $request->file('report4');
        if ($book4) {
            $bookName4 = $this->upload($book4);
        } else {
            $bookName4 = $check->file4;
        }
        $book5 = $request->file('report5');
        if ($book5) {
            $bookName5 = $this->upload($book5);
        } else {
            $bookName5 = $check->file5;
        }

        $check->update([
            'rating' => $request->rating,
            'file' => $bookName ?? '',
            'file2' => $bookName2 ?? '',
            'file3' => $bookName3 ?? '',
            'file4' => $bookName4 ?? '',
            'file5' => $bookName5 ?? '',
            'body' => $request->review,
        ]);

        notify()->success('For your awesome review, enjoy and shopping now', 'Thanks!!');

        return back();
    }

    public function upload($book)
    {
        $currentDate = Carbon::now()->toDateString();
        $bookName = $currentDate.'-'.uniqid().'.'.$book->getClientOriginalExtension();
        if (! file_exists('uploads/review')) {
            mkdir('uploads/review', 0777, true);
        }
        $book->move(public_path('uploads/review'), $bookName);

        return $bookName;
    }

    public function megCatProduct($id)
    {
        $category = Category::with(['products' => function ($query) {
            return $query->latest('id')->get();
        }])
            ->where('id', $id)
            ->firstOrFail();
        $products = $category->products;

        return view('admin.e-commerce.product.index', compact('products'));
    }

    public function subCatProduct($id)
    {
        $subCategory = SubCategory::with(['products' => function ($query) {
            return $query->latest('id')->get();
        }])
            ->where('id', $id)
            ->firstOrFail();
        $products = $subCategory->products;

        return view('admin.e-commerce.product.index', compact('products'));
    }

    public function minCatProduct($id)
    {
        $subCategory = miniCategory::with(['products' => function ($query) {
            return $query->latest('id')->get();
        }])
            ->where('id', $id)
            ->firstOrFail();
        $products = $subCategory->products;

        return view('admin.e-commerce.product.index', compact('products'));
    }

    public function exCatProduct($id)
    {
        $subCategory = ExtraMiniCategory::with(['products' => function ($query) {
            return $query->latest('id')->get();
        }])
            ->where('id', $id)
            ->firstOrFail();
        $products = $subCategory->products;

        return view('admin.e-commerce.product.index', compact('products'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function inhouseProduct()
    {
        $products = Product::where('type', '1')->with('brand')->latest('id')->paginate(10);
        $categories = Category::all();
        $brands = Brand::all();
        $statuses = ['1' => 'Active', '0' => 'Inactive'];

        return view('admin.e-commerce.product.index', compact('products', 'categories', 'brands', 'statuses'));
    }

    public function type()
    {
        return view('admin.e-commerce.product.type');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $type = 'normal';
        $categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $colors = DB::table('colors')->latest('id')->get(['id', 'name', 'slug', 'code']);
        $sizes = DB::table('sizes')->latest('id')->get(['id', 'name']);
        $tags = DB::table('tags')->latest('id')->get(['id', 'name']);
        $brands = DB::table('brands')->latest('id')->get(['id', 'name']);

        return view('admin.e-commerce.product.form', compact('categories', 'colors', 'sizes', 'tags', 'brands', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function inhouseCreate()
    {
        $type = 'inhouse';
        $categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $colors = DB::table('colors')->latest('id')->get(['id', 'name', 'slug', 'code']);
        $attributes = Attribute::all();
        $sizes = DB::table('sizes')->latest('id')->get(['id', 'name']);
        $tags = DB::table('tags')->latest('id')->get(['id', 'name']);
        $brands = DB::table('brands')->latest('id')->get(['id', 'name']);

        return view('admin.e-commerce.product.form', compact('categories', 'colors', 'sizes', 'tags', 'brands', 'type', 'attributes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->ptypen == 'inhouse') {
            $typen = 1;
        } else {
            $typen = 0;
        }
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'regular_price' => 'required|numeric',
            'dis_type' => 'nullable',
            'discount_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'categories' => 'required|array',
            'categories.*' => 'integer',
            'sub_categories.' => 'integer',
            'brand' => 'nullable|integer',
            'colors' => 'array',
            'spec_labels' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'image' => 'required|image',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v',
            'video_thumb' => 'nullable|image',
            'shipping_charge' => 'required|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'lifestyle_images' => 'nullable|array',
            'lifestyle_images.*' => 'nullable|image',
            'lifestyle_tags' => 'nullable|array',
            'lifestyle_captions' => 'nullable|array',
        ], [
            'image.uploaded' => 'The main image failed to upload — please try again with a smaller file.',
            'images.*.uploaded' => 'One of the gallery images failed to upload — please try again with a smaller file.',
            'lifestyle_images.*.uploaded' => 'One of the lifestyle images failed to upload — please try again with a smaller file.',
        ]);

        $video = $request->file('video');
        if ($video) {
            $currentDate = Carbon::now()->toDateString();
            $videoName = $currentDate.'-'.uniqid().'.'.$video->getClientOriginalExtension();
            if (! file_exists('uploads/product/video')) {
                mkdir('uploads/product/video', 0777, true);
            }
            $video->move(public_path('uploads/product/video'), $videoName);
        }
        $video_thumb = $request->file('video_thumb');
        if ($video_thumb) {
            $currentDate = Carbon::now()->toDateString();
            $videoTName = $currentDate.'-'.uniqid().'.'.$video_thumb->getClientOriginalExtension();
            if (! file_exists('uploads/product/video')) {
                mkdir('uploads/product/video', 0777, true);
            }
            $video_thumb->move(public_path('uploads/product/video'), $videoTName);
        }

        $image = $request->file('image');
        if ($image) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (! file_exists('uploads/product')) {
                mkdir('uploads/product', 0777, true);
            }

            $image->move(public_path('uploads/product'), $imageName);
        }

        if ($request->dis_type == 2) {
            $discount = ($request->regular_price / 100) * $request->discount_price;
            $discount_price = $request->regular_price - $discount;
        } elseif ($request->discount_price > 0) {
            $discount_price = $request->regular_price - $request->discount_price;
        } else {
            $discount_price = $request->discount_price;
        }
        if ($request->discount_price > 0) {
            $point = setting('Default_Point') * $discount_price;
        } else {
            $point = setting('Default_Point') * $request->regular_price;
        }
        if ($discount_price < 0) {
            $discount_price = null;
        }
        $linky = '';
        if ($request->yvideo) {
            $url = $request->yvideo;
            $provider = parse_url($request->yvideo);
            $domian = $provider['host'];
            $www = ltrim($domian, 'www.');
            $base = trim($www, '.com');
            if ($base == 'youtu.be' || $base == 'youtube') {
                $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
                $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
                if (preg_match($longUrlRegex, $url, $matches)) {
                    $youtube_id = $matches[count($matches) - 1];
                }

                if (preg_match($shortUrlRegex, $url, $matches)) {
                    $youtube_id = $matches[count($matches) - 1];
                }
                $linky = 'https://www.youtube.com/embed/'.$youtube_id.'?rel=0&enablejsapi=1';
            }
        }
        $product = Product::create([
            'user_id' => auth()->id(),
            'brand_id' => $request->brand,
            'slug' => rand(pow(10, 5 - 1), pow(10, 15) - 1),
            'title' => $request->title,
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'spec' => $this->buildSpecJson($request),
            'video' => $videoName ?? null,
            'video_thumb' => $videoTName ?? null,
            'yvideo' => $linky,
            'full_description' => $request->full_description,
            'buying_price' => $request->buying_price,
            'regular_price' => $request->regular_price,
            'discount_price' => $discount_price,
            'dis_type' => $request->dis_type,
            'point' => $point,
            'quantity' => $request->quantity,
            'image' => $imageName,
            'status' => $request->filled('status'),
            'is_aproved' => $request->filled('status'),
            'is_shown_on_homepage' => $request->filled('is_shown_on_homepage'),
            'type' => $typen,
            'shipping_charge' => $request->shipping_charge,
        ]);

        $product->categories()->sync($request->categories, []);

        if (! empty($request->get('sub_categories'))) {
            foreach ($request->sub_categories as $catid) {
                if ($catid != null) {
                    DB::table('product_sub_category')->insert(
                        [
                            'sub_category_id' => $catid,
                            'product_id' => $product->id,
                        ]
                    );
                }
            }
        }
        if (! empty($request->get('mini_categories'))) {
            foreach ($request->mini_categories as $catid) {
                if ($catid != null) {
                    DB::table('mini_category_product')->insert(
                        [
                            'mini_category_id' => $catid,
                            'product_id' => $product->id,
                        ]
                    );
                }
            }
        }

        if (! empty($request->get('extra_categories'))) {
            foreach ($request->extra_categories as $catid) {
                if ($catid != null) {
                    DB::table('extra_mini_category_products')->insert(
                        [
                            'extra_mini_category_id' => $catid,
                            'product_id' => $product->id,
                        ]
                    );
                }
            }
        }
        $product->tags()->sync($request->tags ?? []);
        $product->sizes()->sync($request->sizes ?? []);
        $i = 0;
        if (! empty($request->get('colors'))) {
            foreach ($request->colors as $colors) {
                DB::table('color_product')->insert([
                    'color_id' => $colors,
                    'product_id' => $product->id,
                    'qnty' => $request->color_quantits[$i] ?? 0,
                    'price' => $request->color_prices[$i] ?? 0,
                ]);
                $i++;
            }
        }
        $a = 0;
        if (! empty($request->get('attributes'))) {
            foreach ($request->get('attributes') as $attribute) {

                DB::table('attribute_product')->insert([
                    'attribute_value_id' => $attribute,
                    'product_id' => $product->id,
                    'qnty' => $request->attributes_quantits[$a] ?? 0,
                    'price' => $request->attribute_prices[$a] ?? 0,
                ]);
                $a++;
            }
        }

        $this->saveGalleryImages($request, $product);
        $this->saveLifestyleImages($request, $product);

        notify()->success('Product successfully added', 'Added');

        return redirect()->to(routeHelper('product'));
    }

    /**
     * Turn the spec label/value repeater rows into the JSON stored in `spec`.
     */
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

    /**
     * Store uploaded thumbnail-strip images against the product.
     */
    private function saveGalleryImages(Request $request, Product $product): void
    {
        foreach ($request->file('images', []) as $gallery) {
            $name = Carbon::now()->toDateString().'-'.uniqid().'.'.$gallery->getClientOriginalExtension();

            if (! file_exists('uploads/product')) {
                mkdir('uploads/product', 0777, true);
            }
            $gallery->move(public_path('uploads/product'), $name);

            $product->images()->create([
                'name' => $name,
                'section' => 'gallery',
            ]);
        }
    }

    /**
     * Store uploaded lifestyle/banner images with their carousel tag+caption.
     */
    private function saveLifestyleImages(Request $request, Product $product): void
    {
        foreach ($request->file('lifestyle_images', []) as $key => $image) {
            if (! $image) {
                continue;
            }
            $name = Carbon::now()->toDateString().'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (! file_exists('uploads/product')) {
                mkdir('uploads/product', 0777, true);
            }
            $image->move(public_path('uploads/product'), $name);

            $product->images()->create([
                'name' => $name,
                'section' => 'lifestyle',
                'tag' => $request->input("lifestyle_tags.$key"),
                'caption' => $request->input("lifestyle_captions.$key"),
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Product $product)
    {
        $colors_product = DB::table('color_product')
            ->select('*')
            ->join('colors', 'colors.id', '=', 'color_product.color_id')
            ->where('color_product.product_id', $product->id)
            ->get();
        $attributes = Attribute::all();

        return view('admin.e-commerce.product.show', compact('product', 'colors_product', 'attributes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Response
     */
    public function edit(Product $product)
    {
        $categories = DB::table('categories')->latest('id')->get(['id', 'name']);
        $colors = DB::table('colors')->latest('id')->get();
        $sizes = DB::table('sizes')->latest('id')->get(['id', 'name']);
        $tags = DB::table('tags')->latest('id')->get(['id', 'name']);

        $attributes = Attribute::all();
        $colors_product = DB::table('color_product')
            ->select('*')
            ->join('colors', 'colors.id', '=', 'color_product.color_id')
            ->where('color_product.product_id', $product->id)
            ->get();

        $brands = DB::table('brands')->latest('id')->get(['id', 'name']);

        return view('admin.e-commerce.product.form', compact(
            'colors_product',
            'categories',
            'attributes',
            'colors',
            'sizes',
            'tags',
            'brands',
            'product'
        ));
    }

    public function nColorDelete($cc, $pp)
    {
        DB::table('color_product')->where('color_id', $cc)->where('product_id', $pp)->delete();
        notify()->success('successfully deleted', 'Delete');

        return back();
    }

    public function nattrDelete($cc)
    {
        DB::table('attribute_product')->where('id', $cc)->delete();
        notify()->success('successfully deleted', 'Delete');

        return back();
    }

    public function idelte($id)
    {
        $image = ProductImage::find($id);
        if (file_exists('uploads/product/'.$image->name)) {
            unlink('uploads/product/'.$image->name);
        }
        $image->delete();
        notify()->success('successfully deleted', 'Delete');

        return back();

    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'regular_price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'quantity' => 'required|integer',
            'categories' => 'required|array',
            'categories.*' => 'required|integer',
            'brand' => 'nullable|integer',
            'colors' => 'array',
            'colors.*' => 'integer',
            'spec_labels' => 'nullable|array',
            'spec_values' => 'nullable|array',
            'image' => 'nullable|image',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,video/x-m4v',
            'video_thumb' => 'nullable|image',
            'remove_video' => 'nullable|boolean',
            'shipping_charge' => 'required|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image',
            'lifestyle_images' => 'nullable|array',
            'lifestyle_images.*' => 'nullable|image',
            'lifestyle_tags' => 'nullable|array',
            'lifestyle_captions' => 'nullable|array',
            'existing_lifestyle' => 'nullable|array',
        ], [
            'image.uploaded' => 'The main image failed to upload — please try again with a smaller file.',
            'images.*.uploaded' => 'One of the gallery images failed to upload — please try again with a smaller file.',
            'lifestyle_images.*.uploaded' => 'One of the lifestyle images failed to upload — please try again with a smaller file.',
        ]);

        $video = $request->file('video');
        if ($video) {
            $currentDate = Carbon::now()->toDateString();
            $videoName = $currentDate.'-'.uniqid().'.'.$video->getClientOriginalExtension();
            if (! file_exists('uploads/product/video')) {
                mkdir('uploads/product/video', 0777, true);
            }
            $video->move(public_path('uploads/product/video'), $videoName);
        } elseif ($request->boolean('remove_video')) {
            // Admin explicitly cleared the video — drop the stored file and value.
            if ($product->video && file_exists(public_path('uploads/product/video/'.$product->video))) {
                unlink(public_path('uploads/product/video/'.$product->video));
            }
            $videoName = null;
        } else {
            $videoName = $product->video;
        }
        $video_thumb = $request->file('video_thumb');
        if ($video_thumb) {
            $currentDate = Carbon::now()->toDateString();
            $videoTName = $currentDate.'-'.uniqid().'.'.$video_thumb->getClientOriginalExtension();
            if (! file_exists('uploads/product/video')) {
                mkdir('uploads/product/video', 0777, true);
            }
            $video_thumb->move(public_path('uploads/product/video'), $videoTName);
        } elseif ($request->boolean('remove_video')) {
            if ($product->video_thumb && file_exists(public_path('uploads/product/video/'.$product->video_thumb))) {
                unlink(public_path('uploads/product/video/'.$product->video_thumb));
            }
            $videoTName = null;
        } else {
            $videoTName = $product->video_thumb;
        }

        $image = $request->file('image');
        if ($image) {
            $currentDate = Carbon::now()->toDateString();
            $imageName = $currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (file_exists('uploads/product/'.$product->image)) {
                unlink('uploads/product/'.$product->image);
            }
            if (! file_exists('uploads/product')) {
                mkdir('uploads/product', 0777, true);
            }
            $image->move(public_path('uploads/product'), $imageName);
        } else {
            $imageName = $product->image;
        }
        if ($request->dis_type == 2) {
            $discount = ($request->regular_price / 100) * $request->discount_price;
            $discount_price = $request->regular_price - $discount;
        } elseif ($request->discount_price > 0) {
            $discount_price = $request->regular_price - $request->discount_price;
        } else {
            $discount_price = $request->discount_price;
        }
        if ($request->discount_price > 0) {
            $point = setting('Default_Point') * $discount_price;
        } else {
            $point = setting('Default_Point') * $request->regular_price;
        }
        if ($discount_price < 0) {
            $discount_price = null;
        }
        $linky = null;
        if ($request->yvideo) {
            $url = $request->yvideo;
            $provider = parse_url($request->yvideo);
            $domian = $provider['host'];
            $www = ltrim($domian, 'www.');
            $base = trim($www, '.com');
            if ($base == 'youtu.be' || $base == 'youtube') {
                $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
                $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
                if (preg_match($longUrlRegex, $url, $matches)) {
                    $youtube_id = $matches[count($matches) - 1];
                }

                if (preg_match($shortUrlRegex, $url, $matches)) {
                    $youtube_id = $matches[count($matches) - 1];
                }
                $linky = 'https://www.youtube.com/embed/'.$youtube_id.'?rel=0&enablejsapi=1';
            }
        }
        $product->update([
            'brand_id' => $request->brand,
            'title' => $request->title,
            'sku' => $request->sku,
            'short_description' => $request->short_description,
            'spec' => $this->buildSpecJson($request),
            'video' => $videoName ?? null,
            'video_thumb' => $videoTName ?? null,
            'yvideo' => $linky ?? $request->yvideo,
            'full_description' => $request->full_description,
            'regular_price' => $request->regular_price,
            'discount_price' => $discount_price,
            'dis_type' => $request->dis_type,
            'quantity' => $request->quantity,
            'point' => $point,
            'image' => $imageName,
            'status' => $request->filled('status'),
            'is_aproved' => $request->filled('status'),
            'is_shown_on_homepage' => $request->filled('is_shown_on_homepage'),
            'shipping_charge' => $request->shipping_charge,
        ]);

        $this->saveGalleryImages($request, $product);
        $this->saveLifestyleImages($request, $product);

        // Update tag/caption on lifestyle images that are already stored.
        foreach ($request->input('existing_lifestyle', []) as $imageId => $fields) {
            $product->images()
                ->where('id', $imageId)
                ->update([
                    'tag' => $fields['tag'] ?? null,
                    'caption' => $fields['caption'] ?? null,
                ]);
        }

        $product->categories()->sync($request->categories);

        if (! empty($request->get('sub_categories'))) {
            $product->sub_categories()->sync($request->sub_categories);
        }
        if (! empty($request->get('mini_categories'))) {
            $product->mini_categories()->sync($request->mini_categories);
        }
        if (! empty($request->get('extra_categories'))) {
            $product->extra_categories()->sync($request->extra_categories);
        }

        // The form no longer manages tags/sizes/attributes — only touch them
        // when a request explicitly submits them, so legacy data survives.
        if ($request->has('tags')) {
            $product->tags()->sync($request->tags ?? []);
        }
        if ($request->has('sizes')) {
            $product->sizes()->sync($request->sizes ?? []);
        }

        // Replace the whole color set so editing doesn't duplicate rows; the
        // form always re-submits the current rows.
        DB::table('color_product')->where('product_id', $product->id)->delete();
        $i = 0;
        if (! empty($request->get('colors'))) {
            foreach ($request->colors as $colors) {
                DB::table('color_product')->insert([
                    'color_id' => $colors,
                    'product_id' => $product->id,
                    'qnty' => $request->color_quantits[$i] ?? 0,
                    'price' => $request->color_prices[$i] ?? 0,
                ]);
                $i++;
            }
        }

        if ($request->has('attributes')) {
            DB::table('attribute_product')->where('product_id', $product->id)->delete();
            $a = 0;
            foreach ($request->get('attributes') as $attribute) {
                DB::table('attribute_product')->insert([
                    'attribute_value_id' => $attribute,
                    'product_id' => $product->id,
                    'qnty' => $request->attributes_quantits[$a] ?? 0,
                    'price' => $request->attribute_prices[$a] ?? 0,
                ]);
                $a++;
            }
        }
        notify()->success('Product successfully update', 'Update');

        return redirect()->to(routeHelper('product'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy(Product $product)
    {
        if ($product->image && file_exists('uploads/product/'.$product->image)) {
            unlink('uploads/product/'.$product->image);
        }
        foreach ($product->images as $image) {
            if (file_exists('uploads/product/'.$image->name)) {
                unlink('uploads/product/'.$image->name);
            }
            $image->delete();
        }
        foreach (['video', 'video_thumb'] as $videoField) {
            if ($product->$videoField && file_exists('uploads/product/video/'.$product->$videoField)) {
                unlink('uploads/product/video/'.$product->$videoField);
            }
        }
        foreach ($product->reviews as $review) {
            foreach (['file', 'file2', 'file3', 'file4', 'file5'] as $field) {
                if ($review->$field && file_exists('uploads/reviews/'.$review->$field)) {
                    unlink('uploads/reviews/'.$review->$field);
                }
            }
        }
        foreach ($product->downloads as $download) {
            if ($download->file != null) {
                if (file_exists('uploads/product/download/'.$download->file)) {
                    unlink('uploads/product/download/'.$download->file);
                }
            }
            $download->delete();
        }

        // Pivot tables have no cascading FK — clean them up explicitly.
        DB::table('color_product')->where('product_id', $product->id)->delete();
        DB::table('attribute_product')->where('product_id', $product->id)->delete();

        $product->delete();
        notify()->success('Product successfully deleted', 'Delete');

        return back();
    }

    /**
     * Duplicate a product with its media, specs, colors and categories.
     * The copy starts disabled and opens in the edit form; reviews, views
     * and order history are never copied.
     */
    public function duplicate(Product $product)
    {
        $copy = $product->replicate();
        $copy->title = $product->title.' (Copy)';
        $copy->slug = rand(pow(10, 5 - 1), pow(10, 15) - 1);
        $copy->status = 0;
        $copy->is_aproved = 0;
        $copy->is_shown_on_homepage = 0;
        $copy->reach = 0;
        $copy->review = null;
        // Copy the physical files so deleting one product never breaks the other.
        $copy->image = $this->copyMediaFile($product->image, 'uploads/product');
        $copy->video = $this->copyMediaFile($product->video, 'uploads/product/video');
        $copy->video_thumb = $this->copyMediaFile($product->video_thumb, 'uploads/product/video');
        $copy->save();

        $copy->categories()->sync($product->categories->pluck('id'));
        $copy->sub_categories()->sync($product->sub_categories->pluck('id'));

        foreach (DB::table('color_product')->where('product_id', $product->id)->get() as $row) {
            DB::table('color_product')->insert([
                'color_id' => $row->color_id,
                'product_id' => $copy->id,
                'qnty' => $row->qnty,
                'price' => $row->price,
            ]);
        }

        foreach ($product->images as $image) {
            $copy->images()->create([
                'name' => $this->copyMediaFile($image->name, 'uploads/product'),
                'section' => $image->section ?? 'gallery',
                'tag' => $image->tag,
                'caption' => $image->caption,
                'color_attri' => $image->color_attri,
            ]);
        }

        notify()->success('Product duplicated — review the copy and enable it when ready', 'Duplicated');

        return redirect()->to(routeHelper('product/'.$copy->id.'/edit'));
    }

    /**
     * Copy an uploaded file under a fresh unique name; returns the new
     * filename, or the original value when there is nothing to copy.
     */
    private function copyMediaFile(?string $file, string $dir): ?string
    {
        if (! $file || ! file_exists(public_path($dir.'/'.$file))) {
            return $file;
        }

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $newName = Carbon::now()->toDateString().'-'.uniqid().($ext ? '.'.$ext : '');
        copy(public_path($dir.'/'.$file), public_path($dir.'/'.$newName));

        return $newName;
    }

    // update product status
    public function status($id)
    {
        $product = Product::findOrFail($id);
        if ($product->is_aproved == 0) {
            $product->update([
                'status' => true,
                'is_aproved' => true,
            ]);
        } elseif ($product->status == 1) {
            $product->update([
                'status' => false,
                'is_aproved' => false,
            ]);
        } else {
            $product->update([
                'status' => true,
                'is_aproved' => true,
            ]);
        }
        notify()->success('Product status successfully updated', 'Update');

        return back();
    }

    // get active product
    public function activeProduct()
    {
        $products = Product::with('brand')->where('status', true)->latest('id')->get();

        return view('admin.e-commerce.product.active', compact('products'));
    }

    // get active product
    public function reachedProduct()
    {
        $products = Product::with('brand')->where('status', true)->where('reach', '>', '0')->orderBy('reach', 'DESC')->take('25')->get();

        return view('admin.e-commerce.product.active', compact('products'));
    }

    // get disable product
    public function unaprovedProduct()
    {
        $products = Product::with('brand')->where('is_aproved', false)->latest('id')->get();

        return view('admin.e-commerce.product.unapprove', compact('products'));
    }

    // get disable product
    public function disableProduct()
    {
        $products = Product::with('brand')->where('status', false)->latest('id')->get();

        return view('admin.e-commerce.product.disable', compact('products'));
    }

    // get sub category by category
    public function subCategory(Request $request)
    {
        $data = SubCategory::whereIn('category_id', $request->ids)->get(['id', 'name']);

        return response()->json($data);
    }

    // get sub category by category
    public function extraCategory(Request $request)
    {
        $data = ExtraMiniCategory::whereIn('mini_category_id', $request->ids)->get(['id', 'name']);

        return response()->json($data);
    }

    // get sub category by category
    public function miniCategory(Request $request)
    {
        $data = miniCategory::whereIn('category_id', $request->ids)->get(['id', 'name']);

        return response()->json($data);
    }

    public function imex()
    {
        return view('admin.e-commerce.product.bluk');
    }

    public function export()
    {
        return Excel::download(new ProductsExport, 'product.xlsx');
    }

    public function import(Request $request)
    {
        $import = Excel::import(new ProductsImport, $request->file('product'));
        if ($import) {
            notify()->success('Product Import', 'Update');

            return back();
        }

    }

    public function gallery()
    {
        $images = ProductImage::all();

        return view('admin.e-commerce.product.gallery',compact('images'));
    }
}
