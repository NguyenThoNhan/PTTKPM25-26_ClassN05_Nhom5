<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Discussion;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    /**
     * Display the social feed (home page for social features)
     */
    public function feed()
    {
        $user = Auth::user();
        
        // Get recent discussions
        $discussions = Discussion::with(['user', 'book'])
            ->public()
            ->latest()
            ->paginate(10);
        
        // Get popular reading groups
        $popularGroups = ReadingGroup::with(['creator', 'members'])
            ->public()
            ->active()
            ->withCount('members')
            ->orderBy('members_count', 'desc')
            ->take(6)
            ->get();
        
        // Get user's reading groups
        $userGroups = $user->readingGroups()->with('creator')->get();
        
        // Get recent activities from followed users (simplified - just recent discussions)
        $recentActivities = Discussion::with(['user', 'book'])
            ->public()
            ->latest()
            ->take(5)
            ->get();

        return view('social.feed', compact(
            'discussions', 
            'popularGroups', 
            'userGroups', 
            'recentActivities'
        ));
    }

    /**
     * Display user profile
     */
    public function profile(User $user)
    {
        // Check if profile is public
        if (!$user->is_public_profile && $user->id !== Auth::id()) {
            abort(403, 'Profile is private');
        }

        $stats = $user->getProfileStats();
        $recentReviews = $user->reviews()->with('book')->latest()->take(5)->get();
        $recentDiscussions = $user->discussions()->with('book')->latest()->take(5)->get();
        $readingGroups = $user->readingGroups()->with('creator')->get();
        $badges = $user->badges()->get();

        return view('social.profile', compact(
            'user', 
            'stats', 
            'recentReviews', 
            'recentDiscussions', 
            'readingGroups', 
            'badges'
        ));
    }

    /**
     * Display user's reviews
     */
    public function reviews(User $user)
    {
        if (!$user->is_public_profile && $user->id !== Auth::id()) {
            abort(403, 'Profile is private');
        }

        $reviews = $user->reviews()
            ->with('book')
            ->latest()
            ->paginate(12);

        return view('social.reviews', compact('user', 'reviews'));
    }

    /**
     * Display user's discussions
     */
    public function discussions(User $user)
    {
        if (!$user->is_public_profile && $user->id !== Auth::id()) {
            abort(403, 'Profile is private');
        }

        $discussions = $user->discussions()
            ->with('book')
            ->latest()
            ->paginate(12);

        return view('social.user-discussions', compact('user', 'discussions'));
    }

    /**
     * Search users
     */
    public function searchUsers(Request $request)
    {
        $query = $request->get('q');
        $users = collect();

        if ($query) {
            $users = User::where('is_public_profile', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->withCount(['reviews', 'discussions', 'readingGroups'])
                ->paginate(20);
        }

        return view('social.search-users', compact('users', 'query'));
    }

    /**
     * Get user suggestions (AJAX)
     */
    public function getUserSuggestions(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('is_public_profile', true)
            ->where('name', 'like', "%{$query}%")
            ->take(5)
            ->get(['id', 'name', 'avatar_path']);

        return response()->json($users);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'bio' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:100',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
            'social_links.*' => 'url',
            'is_public_profile' => 'boolean',
            'reading_preferences' => 'nullable|array',
        ]);

        $data = $request->only([
            'bio', 
            'location', 
            'social_links', 
            'is_public_profile', 
            'reading_preferences'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar_path) {
                \Storage::disk('public')->delete($user->avatar_path);
            }
            
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar_path'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}