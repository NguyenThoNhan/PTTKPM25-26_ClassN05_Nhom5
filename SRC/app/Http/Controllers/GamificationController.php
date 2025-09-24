<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Badge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamificationController extends Controller
{
    /**
     * Display the gamification dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $user->getReadingStats();
        $challenges = $user->getDailyChallenges();
        $badges = $user->badges()->get();
        $leaderboard = User::getLeaderboard(10);
        $userPosition = $user->getLeaderboardPosition();

        return view('gamification.dashboard', compact(
            'user', 'stats', 'challenges', 'badges', 'leaderboard', 'userPosition'
        ));
    }

    /**
     * Display leaderboard
     */
    public function leaderboard()
    {
        $leaderboard = User::getLeaderboard(50);
        $user = Auth::user();
        $userPosition = $user->getLeaderboardPosition();

        return view('gamification.leaderboard', compact('leaderboard', 'user', 'userPosition'));
    }

    /**
     * Display user's badges
     */
    public function badges()
    {
        $user = Auth::user();
        $userBadges = $user->badges()->get();
        $allBadges = Badge::all();
        
        // Get badges not yet earned
        $earnedBadgeIds = $userBadges->pluck('id')->toArray();
        $availableBadges = $allBadges->whereNotIn('id', $earnedBadgeIds);

        return view('gamification.badges', compact('user', 'userBadges', 'availableBadges'));
    }

    /**
     * Display daily challenges
     */
    public function challenges()
    {
        $user = Auth::user();
        $challenges = $user->getDailyChallenges();

        return view('gamification.challenges', compact('user', 'challenges'));
    }

    /**
     * Update daily challenge progress (AJAX)
     */
    public function updateChallenge(Request $request)
    {
        $request->validate([
            'challenge_key' => 'required|string',
            'increment' => 'integer|min:1|max:10'
        ]);

        $user = Auth::user();
        $increment = $request->input('increment', 1);
        
        $user->updateDailyChallenge($request->challenge_key, $increment);

        return response()->json([
            'success' => true,
            'message' => 'Challenge progress updated!'
        ]);
    }

    /**
     * Get user statistics (AJAX)
     */
    public function getStats()
    {
        $user = Auth::user();
        $stats = $user->getReadingStats();
        $challenges = $user->getDailyChallenges();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'challenges' => $challenges
        ]);
    }
}