<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DiscussionController extends Controller
{
    /**
     * Display a listing of discussions
     */
    public function index(Request $request)
    {
        $query = Discussion::with(['user', 'book'])
            ->public()
            ->latest();

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by book
        if ($request->has('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $discussions = $query->paginate(15);

        return view('discussions.index', compact('discussions'));
    }

    /**
     * Show the form for creating a new discussion
     */
    public function create(Request $request)
    {
        $book = null;
        if ($request->has('book_id')) {
            $book = Book::findOrFail($request->book_id);
        }

        return view('discussions.create', compact('book'));
    }

    /**
     * Store a newly created discussion
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'type' => 'required|in:general,book_discussion,reading_group',
            'book_id' => 'nullable|exists:books,id',
            'tags' => 'nullable|string',
        ]);

        $data = $request->only(['title', 'content', 'type', 'book_id']);
        $data['user_id'] = Auth::id();

        // Process tags
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_slice($tags, 0, 5); // Max 5 tags
        }

        $discussion = Discussion::create($data);

        // Update gamification
        Auth::user()->addExperience(20); // 20 XP for creating discussion

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion created successfully! +20 XP');
    }

    /**
     * Display the specified discussion
     */
    public function show(Discussion $discussion)
    {
        // Increment views
        $discussion->incrementViews();

        $discussion->load(['user', 'book', 'replies.user', 'replies.children.user']);
        
        // Get top-level replies with their children
        $replies = $discussion->topLevelReplies()
            ->with(['user', 'children.user'])
            ->orderBy('created_at')
            ->get();

        return view('discussions.show', compact('discussion', 'replies'));
    }

    /**
     * Show the form for editing the discussion
     */
    public function edit(Discussion $discussion)
    {
        // Check if user owns this discussion
        if ($discussion->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own discussions');
        }

        return view('discussions.edit', compact('discussion'));
    }

    /**
     * Update the specified discussion
     */
    public function update(Request $request, Discussion $discussion)
    {
        // Check if user owns this discussion
        if ($discussion->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own discussions');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'tags' => 'nullable|string',
        ]);

        $data = $request->only(['title', 'content']);

        // Process tags
        if ($request->tags) {
            $tags = array_map('trim', explode(',', $request->tags));
            $data['tags'] = array_slice($tags, 0, 5);
        }

        $discussion->update($data);

        return redirect()->route('discussions.show', $discussion)
            ->with('success', 'Discussion updated successfully!');
    }

    /**
     * Remove the specified discussion
     */
    public function destroy(Discussion $discussion)
    {
        // Check if user owns this discussion
        if ($discussion->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own discussions');
        }

        $discussion->delete();

        return redirect()->route('discussions.index')
            ->with('success', 'Discussion deleted successfully!');
    }

    /**
     * Toggle like for discussion
     */
    public function toggleLike(Discussion $discussion)
    {
        $liked = $discussion->toggleLike(Auth::id());

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $discussion->fresh()->likes_count
        ]);
    }

    /**
     * Pin/unpin discussion (admin only)
     */
    public function togglePin(Discussion $discussion)
    {
        // Check if user is admin
        if (!Auth::user()->can('is-admin')) {
            abort(403, 'Only admins can pin discussions');
        }

        $discussion->update(['is_pinned' => !$discussion->is_pinned]);

        return response()->json([
            'success' => true,
            'pinned' => $discussion->is_pinned
        ]);
    }

    /**
     * Lock/unlock discussion (admin only)
     */
    public function toggleLock(Discussion $discussion)
    {
        // Check if user is admin
        if (!Auth::user()->can('is-admin')) {
            abort(403, 'Only admins can lock discussions');
        }

        $discussion->update(['is_locked' => !$discussion->is_locked]);

        return response()->json([
            'success' => true,
            'locked' => $discussion->is_locked
        ]);
    }
}