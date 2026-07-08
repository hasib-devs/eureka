<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductReviewController extends Controller
{
    /**
     * Store a review submitted from the product page. Reviews publish
     * immediately (no moderation); buyers get a Verified badge.
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'reviewer_name' => 'required|string|max:100',
            'title' => 'nullable|string|max:150',
            'review' => 'required|string|max:5000',
            'photos' => 'nullable|array|max:5',
            'photos.*' => 'image',
        ]);

        $files = [null, null, null, null, null];
        foreach (array_slice($request->file('photos', []), 0, 5) as $i => $photo) {
            $name = time().'_'.Str::random(6).'.'.$photo->getClientOriginalExtension();
            $photo->move(public_path('uploads/reviews'), $name);
            $files[$i] = $name;
        }

        $review = Review::create([
            'user_id' => Auth::id(),
            'reviewer_name' => $request->reviewer_name,
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->review,
            'file' => $files[0],
            'file2' => $files[1],
            'file3' => $files[2],
            'file4' => $files[3],
            'file5' => $files[4],
        ]);

        $avg = Review::where('product_id', $product->id)->avg('rating');
        Product::where('id', $product->id)->update(['review' => round($avg, 1)]);

        return response()->json([
            'alert' => 'success',
            'message' => 'Thank you — your signature has been posted.',
            'review' => $this->presentReview($review),
        ]);
    }

    /**
     * Mark a review helpful — once per session per review.
     */
    public function helpful(Request $request, Review $review)
    {
        $voted = $request->session()->get('helpful_reviews', []);

        if (! in_array($review->id, $voted)) {
            $review->increment('helpful_count');
            $voted[] = $review->id;
            $request->session()->put('helpful_reviews', $voted);
        }

        return response()->json([
            'alert' => 'success',
            'helpful_count' => $review->fresh()->helpful_count,
        ]);
    }

    /**
     * Shape a review the way the product page's review list consumes it.
     */
    public static function presentReview(Review $review): array
    {
        $photos = collect([$review->file, $review->file2, $review->file3, $review->file4, $review->file5])
            ->filter()
            ->map(fn ($file) => asset('uploads/reviews/'.$file))
            ->values()
            ->all();

        return [
            'id' => $review->id,
            'name' => $review->reviewer_name ?: optional($review->user)->name ?: 'Anonymous',
            'rating' => (int) $review->rating,
            'title' => $review->title,
            'text' => $review->body,
            'date' => $review->created_at->toDateString(),
            'photos' => $photos,
            'helpful' => (int) $review->helpful_count,
            'verified' => static::isVerifiedBuyer($review),
        ];
    }

    /**
     * A reviewer is a verified buyer when their account has a non-cancelled
     * order containing this product.
     */
    protected static function isVerifiedBuyer(Review $review): bool
    {
        if (! $review->user_id) {
            return false;
        }

        return Order::where('user_id', $review->user_id)
            ->where('status', '!=', 2)
            ->whereHas('orderDetails', fn ($q) => $q->where('product_id', $review->product_id))
            ->exists();
    }
}
