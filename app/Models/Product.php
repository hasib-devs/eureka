<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $guarded = ['id'];

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
            'full_description' => $this->full_description,
            'status' => $this->status,
            'regular_price' => $this->regular_price,
        ];
    }

    // Category.php
    public function products()
    {
        return $this->belongsToMany(self::class);
    }

    /**
     * Get the user that owns the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Normalise the main-image column into a plain list of filenames.
     * Current records store a single filename; older ones may hold a JSON array.
     */
    private function normalizedImageList(): array
    {
        $images = is_array($this->image) ? $this->image : json_decode((string) $this->image, true);
        if (! is_array($images)) {
            $images = ! empty($this->image) ? [$this->image] : [];
        }

        return array_values(array_filter($images, fn ($i) => ! empty($i)));
    }

    /**
     * The single hero/featured image shown large at the top of the gallery.
     */
    public function getHeroImageUrlAttribute(): string
    {
        $images = $this->normalizedImageList();

        return ! empty($images[0])
            ? asset('uploads/product/'.$images[0])
            : asset('frontend/images/placeholder.png');
    }

    /**
     * Thumbnail-strip images: the gallery images the admin selected
     * (product_images), plus any extra main-image entries from legacy records.
     * The hero image is excluded so it never appears twice.
     */
    public function getGalleryImageUrlsAttribute(): Collection
    {
        $hero = $this->hero_image_url;
        $urls = collect();

        foreach (array_slice($this->normalizedImageList(), 1) as $img) {
            if (! empty($img)) {
                $urls->push(asset('uploads/product/'.$img));
            }
        }

        foreach ($this->images as $img) {
            if (! empty($img->name) && ($img->section ?? 'gallery') === 'gallery') {
                $urls->push(asset('uploads/product/'.$img->name));
            }
        }

        return $urls->reject(fn ($url) => $url === $hero)->unique()->values();
    }

    /**
     * Lifestyle/banner images with their carousel captions, in upload order.
     */
    public function getLifestyleImagesAttribute(): Collection
    {
        return $this->images
            ->where('section', 'lifestyle')
            ->filter(fn ($img) => ! empty($img->name))
            ->map(fn ($img) => (object) [
                'url' => asset('uploads/product/'.$img->name),
                'tag' => $img->tag,
                'caption' => $img->caption,
            ])
            ->values();
    }

    /**
     * Structured specifications (label/value pairs) stored as JSON in `spec`.
     */
    public function getSpecsAttribute(): array
    {
        $specs = json_decode((string) $this->spec, true);
        if (! is_array($specs)) {
            return [];
        }

        return array_values(array_filter($specs, fn ($row) => is_array($row) && ! empty($row['label']) && ! empty($row['value'])));
    }

    /**
     * The uploaded product video URL, but ONLY when the stored file is actually
     * a video. A stray non-video file (e.g. an image mistakenly saved to the
     * video column) returns null so the frontend never renders a broken player.
     */
    public function getPlayableVideoUrlAttribute(): ?string
    {
        $video = $this->video;
        if (empty($video)) {
            return null;
        }

        $path = parse_url($video, PHP_URL_PATH) ?: $video;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (! in_array($ext, ['mp4', 'webm', 'ogg', 'ogv', 'mov', 'm4v'], true)) {
            return null;
        }

        return str_starts_with($video, 'http')
            ? $video
            : asset('uploads/product/video/'.$video);
    }

    /**
     * Get the download product for the product.
     */
    public function downloads()
    {
        return $this->hasMany(DownloadProduct::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get all of the tags for the product.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get all of the categories for the product.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Get all of the categories for the product.
     */
    public function categories2()
    {
        return $this->belongsToMany(Category::class)->where('status', 1);
    }

    /**
     * Get all of the sub_categories for the product.
     */
    public function sub_categories()
    {
        return $this->belongsToMany(SubCategory::class);
    }

    public function mini_categories()
    {
        return $this->belongsToMany(miniCategory::class);
    }

    public function extra_categories()
    {
        // Pivot table was shipped as the (non-conventional) plural name
        // `extra_mini_category_products`; pin it so Eloquent doesn't guess the
        // singular default and 500 on a missing table.
        return $this->belongsToMany(ExtraMiniCategory::class, 'extra_mini_category_products');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaing_products');
    }

    /**
     * Get all of the sizes for the product.
     */
    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    /**
     * Get all of the colors for the product.
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    /**
     * Get all of the colors for the product.
     */
    public function attributes_values()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_product')
            ->with('attributes');
    }

    /**
     * Get the order details for the product.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function campaingProduct()
    {
        return $this->hasMany(CampaingProduct::class);
    }

    /**
     * Get the comments for the product.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function userDownloadProducts()
    {
        return $this->belongsToMany(User::class);
    }
}
