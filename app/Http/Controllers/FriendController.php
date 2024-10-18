<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{

    public function store(User $user)
    {
        auth()->user()->follow()->attach($user->id);
        return back(); //
    }

    public function destroy(User $user)
    {
        auth()->user()->follow()->detach($user->id);
        return back(); //
    }
}
