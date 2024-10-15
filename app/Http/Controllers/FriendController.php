<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{

    public function store(User $user)
    {
        auth()->user()->follows()->attach($user->id);
        return back(); //
    }

    public function destroy(User $user)
    {
        auth()->user()->follows()->detach($user->id);
        return back(); //
    }

    public function show(User $user)
    {
        // Get the authenticated user
        $authenticatedUser = auth()->user();

        // Count mutual followers
        $mutualFollowersCount = $user->followers->filter(function ($follower) use ($authenticatedUser) {
            return $authenticatedUser->follows->contains($follower);
        })->count();

        return view('profile.show', [
            'user' => $user,
            'mutualFollowersCount' => $mutualFollowersCount,
        ]);
    }
}
