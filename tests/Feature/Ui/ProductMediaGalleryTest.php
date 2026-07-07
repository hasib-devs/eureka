<?php

use App\Models\Product;
use App\Models\ProductImage;

/**
 * Build an in-memory product with its gallery relation set manually so the
 * media accessors can be exercised without touching the database.
 */
function mediaProduct(?string $image, array $galleryNames = [], ?string $video = null): Product
{
    $product = (new Product)->forceFill([
        'title' => 'Test Lamp',
        'image' => $image,
        'video' => $video,
    ]);
    $product->id = 501;
    $product->setRelation('images', collect(array_map(
        fn ($name) => (new ProductImage)->forceFill(['name' => $name]),
        $galleryNames
    )));

    return $product;
}

it('shows the hero image and never repeats it in the thumbnail strip', function () {
    $product = mediaProduct('hero.jpg', ['g1.webp', 'g2.webp']);

    expect($product->hero_image_url)->toBe(asset('uploads/product/hero.jpg'));
    expect($product->gallery_image_urls->all())->toBe([
        asset('uploads/product/g1.webp'),
        asset('uploads/product/g2.webp'),
    ]);
    expect($product->gallery_image_urls->all())->not->toContain($product->hero_image_url);
});

it('excludes the hero image from the strip even when it is also a gallery row', function () {
    $product = mediaProduct('hero.jpg', ['hero.jpg', 'g1.webp']);

    expect($product->gallery_image_urls->all())->toBe([
        asset('uploads/product/g1.webp'),
    ]);
});

it('does not treat a non-video file in the video column as a playable video', function () {
    // Reproduces the reported bug: an image saved to products.video used to
    // render a broken black <video> tile next to the YouTube embed.
    $product = mediaProduct('hero.jpg', [], '2026-07-06-6a4c2fe441040.jpg');

    expect($product->playable_video_url)->toBeNull();
});

it('returns the video url when the stored file is a real video', function () {
    $product = mediaProduct('hero.jpg', [], 'clip.mp4');

    expect($product->playable_video_url)->toBe(asset('uploads/product/video/clip.mp4'));
});

it('falls back to a placeholder hero and empty strip when the product has no images', function () {
    $product = mediaProduct(null);

    expect($product->hero_image_url)->toBe(asset('frontend/images/placeholder.png'));
    expect($product->gallery_image_urls->all())->toBe([]);
});
