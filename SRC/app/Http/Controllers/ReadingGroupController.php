<?php

namespace App\Http\Controllers;

use App\Models\ReadingGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReadingGroupController extends Controller
{
    /**
     * Display a listing of reading groups
     */
    public function index(Request $request)
    {
        $query = ReadingGroup::with(['creator', 'members'])
            ->public()
            ->active()
            ->withCount('members');

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $readingGroups = $query->paginate(12);

        $categories = ReadingGroup::distinct()->pluck('category');

        return view('reading-groups.index', compact('readingGroups', 'categories'));
    }

    /**
     * Show the form for creating a new reading group
     */
    public function create()
    {
        return view('reading-groups.create');
    }

    /**
     * Store a newly created reading group
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|max:50',
            'max_members' => 'required|integer|min:2|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_public' => 'boolean',
            'rules' => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'name', 
            'description', 
            'category', 
            'max_members', 
            'is_public'
        ]);
        $data['creator_id'] = Auth::id();

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('reading-groups', 'public');
            $data['cover_image_path'] = $path;
        }

        // Process rules
        if ($request->rules) {
            $rules = array_filter(array_map('trim', explode("\n", $request->rules)));
            $data['rules'] = $rules;
        }

        $readingGroup = ReadingGroup::create($data);

        // Add creator as admin
        $readingGroup->members()->attach(Auth::id(), [
            'role' => 'admin',
            'joined_at' => now()
        ]);

        // Update gamification
        Auth::user()->addExperience(50); // 50 XP for creating reading group

        return redirect()->route('reading-groups.show', $readingGroup)
            ->with('success', 'Reading group created successfully! +50 XP');
    }

    /**
     * Display the specified reading group
     */
    public function show(ReadingGroup $readingGroup)
    {
        $readingGroup->load(['creator', 'members', 'discussions.user']);

        $isMember = $readingGroup->hasMember(Auth::id());
        $canModerate = $readingGroup->canModerate(Auth::id());

        // Get recent discussions for this group
        $discussions = $readingGroup->discussions()
            ->with(['user', 'book'])
            ->latest()
            ->take(10)
            ->get();

        return view('reading-groups.show', compact(
            'readingGroup', 
            'isMember', 
            'canModerate', 
            'discussions'
        ));
    }

    /**
     * Show the form for editing the reading group
     */
    public function edit(ReadingGroup $readingGroup)
    {
        // Check if user is admin
        if (!$readingGroup->isAdmin(Auth::id())) {
            abort(403, 'Only group admins can edit the group');
        }

        return view('reading-groups.edit', compact('readingGroup'));
    }

    /**
     * Update the specified reading group
     */
    public function update(Request $request, ReadingGroup $readingGroup)
    {
        // Check if user is admin
        if (!$readingGroup->isAdmin(Auth::id())) {
            abort(403, 'Only group admins can edit the group');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'category' => 'required|string|max:50',
            'max_members' => 'required|integer|min:2|max:100',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_public' => 'boolean',
            'is_active' => 'boolean',
            'rules' => 'nullable|string|max:1000',
        ]);

        $data = $request->only([
            'name', 
            'description', 
            'category', 
            'max_members', 
            'is_public',
            'is_active'
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image
            if ($readingGroup->cover_image_path) {
                Storage::disk('public')->delete($readingGroup->cover_image_path);
            }
            
            $path = $request->file('cover_image')->store('reading-groups', 'public');
            $data['cover_image_path'] = $path;
        }

        // Process rules
        if ($request->rules) {
            $rules = array_filter(array_map('trim', explode("\n", $request->rules)));
            $data['rules'] = $rules;
        }

        $readingGroup->update($data);

        return redirect()->route('reading-groups.show', $readingGroup)
            ->with('success', 'Reading group updated successfully!');
    }

    /**
     * Remove the specified reading group
     */
    public function destroy(ReadingGroup $readingGroup)
    {
        // Check if user is admin
        if (!$readingGroup->isAdmin(Auth::id())) {
            abort(403, 'Only group admins can delete the group');
        }

        // Delete cover image
        if ($readingGroup->cover_image_path) {
            Storage::disk('public')->delete($readingGroup->cover_image_path);
        }

        $readingGroup->delete();

        return redirect()->route('reading-groups.index')
            ->with('success', 'Reading group deleted successfully!');
    }

    /**
     * Join a reading group
     */
    public function join(ReadingGroup $readingGroup)
    {
        // Check if group is full
        if ($readingGroup->isFull()) {
            return redirect()->back()->with('error', 'This reading group is full');
        }

        // Check if already a member
        if ($readingGroup->hasMember(Auth::id())) {
            return redirect()->back()->with('error', 'You are already a member of this group');
        }

        // Add user as member
        $readingGroup->members()->attach(Auth::id(), [
            'role' => 'member',
            'joined_at' => now()
        ]);

        // Update gamification
        Auth::user()->addExperience(10); // 10 XP for joining group

        return redirect()->back()->with('success', 'Successfully joined the reading group! +10 XP');
    }

    /**
     * Leave a reading group
     */
    public function leave(ReadingGroup $readingGroup)
    {
        // Check if user is a member
        if (!$readingGroup->hasMember(Auth::id())) {
            return redirect()->back()->with('error', 'You are not a member of this group');
        }

        // Check if user is the creator
        if ($readingGroup->creator_id === Auth::id()) {
            return redirect()->back()->with('error', 'Group creator cannot leave. Transfer ownership or delete the group');
        }

        // Remove user from group
        $readingGroup->members()->detach(Auth::id());

        return redirect()->back()->with('success', 'Successfully left the reading group');
    }

    /**
     * Update member role (admin only)
     */
    public function updateMemberRole(Request $request, ReadingGroup $readingGroup, User $user)
    {
        // Check if current user is admin
        if (!$readingGroup->isAdmin(Auth::id())) {
            abort(403, 'Only group admins can update member roles');
        }

        // Check if user is a member
        if (!$readingGroup->hasMember($user->id)) {
            return redirect()->back()->with('error', 'User is not a member of this group');
        }

        $request->validate([
            'role' => 'required|in:member,moderator,admin'
        ]);

        $readingGroup->members()->updateExistingPivot($user->id, [
            'role' => $request->role
        ]);

        return redirect()->back()->with('success', 'Member role updated successfully');
    }

    /**
     * Remove member from group (admin only)
     */
    public function removeMember(ReadingGroup $readingGroup, User $user)
    {
        // Check if current user is admin
        if (!$readingGroup->isAdmin(Auth::id())) {
            abort(403, 'Only group admins can remove members');
        }

        // Check if user is a member
        if (!$readingGroup->hasMember($user->id)) {
            return redirect()->back()->with('error', 'User is not a member of this group');
        }

        // Cannot remove the creator
        if ($readingGroup->creator_id === $user->id) {
            return redirect()->back()->with('error', 'Cannot remove the group creator');
        }

        $readingGroup->members()->detach($user->id);

        return redirect()->back()->with('success', 'Member removed successfully');
    }
}