<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Display reviews for a specific book.
     */
    public function index(Book $book)
    {
        $reviews = $book->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        $ratingStats = [
            'average' => round($book->averageRating(), 1),
            'total' => $book->totalReviews(),
            'distribution' => $book->ratingDistribution(),
        ];

        return view('reviews.index', compact('book', 'reviews', 'ratingStats'));
    }

    /**
     * Show the form for creating a new review.
     */
    public function create(Book $book)
    {
        // Check if user has already reviewed this book
        $existingReview = Review::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview)
                ->with('info', 'Bạn đã đánh giá cuốn sách này rồi. Bạn có thể chỉnh sửa đánh giá của mình.');
        }

        // Check if user has borrowed this book (for verified purchase)
        $hasBorrowed = $book->loans()
            ->where('user_id', Auth::id())
            ->where('status', 'returned')
            ->exists();

        return view('reviews.create', compact('book', 'hasBorrowed'));
    }

    /**
     * Store a newly created review.
     */
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if user has already reviewed this book
        $existingReview = Review::where('user_id', Auth::id())
            ->where('book_id', $book->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'Bạn đã đánh giá cuốn sách này rồi.');
        }

        // Check if user has borrowed this book
        $hasBorrowed = $book->loans()
            ->where('user_id', Auth::id())
            ->where('status', 'returned')
            ->exists();

        $data = [
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'rating' => $request->rating,
            'review_text' => $request->review_text,
            'is_verified_purchase' => $hasBorrowed,
        ];

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $review = Review::create($data);

        // Update gamification
        $user = Auth::user();
        $user->increment('total_reviews_written');
        $user->addExperience(30); // 30 XP for writing a review
        $user->updateDailyChallenge('write_review');

        return redirect()->route('books.show', $book)
            ->with('success', 'Cảm ơn bạn đã đánh giá cuốn sách! +30 XP');
    }

    /**
     * Show the form for editing a review.
     */
    public function edit(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền chỉnh sửa đánh giá này.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:2000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'rating' => $request->rating,
            'review_text' => $request->review_text,
        ];

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($review->images) {
                foreach ($review->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        $review->update($data);

        return redirect()->route('books.show', $review->book)
            ->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền xóa đánh giá này.');
        }

        // Delete images
        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $book = $review->book;
        $review->delete();

        return redirect()->route('books.show', $book)
            ->with('success', 'Đánh giá đã được xóa thành công!');
    }

    /**
     * Mark a review as helpful.
     */
    public function helpful(Review $review)
    {
        // For now, just increment helpful count
        // In a real app, you'd want to track which users marked it helpful
        $review->increment('helpful_count');

        // Update gamification for review author
        $review->user->increment('total_helpful_votes');
        $review->user->addExperience(5); // 5 XP for helpful vote
        $review->user->updateDailyChallenge('helpful_review');

        return response()->json([
            'success' => true,
            'helpful_count' => $review->helpful_count
        ]);
    }
}