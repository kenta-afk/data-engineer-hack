<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show(User $user)
    {
        // ユーザーがフォローしているユーザーのIDを取得
        $followedUserIds = $user->follows()->pluck('follow_id')->toArray();

        // フォロワーのうち、フォローしていないユーザーを取得
        $nonMutualFollowers = $user->followers()
            ->whereNotIn('follower_id', $followedUserIds)
            ->get();

        // ビューにデータを渡す
        return view('profile.show', ['user' => $user, 'nonMutualFollowers' => $nonMutualFollowers]);
    }


    public function index(User $user)
    {
        $users = User::with('followers')->get();

        return view('profile.show', compact('user'));
    }
}
