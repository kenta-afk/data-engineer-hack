<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{

    public function store(User $user)
    {
        auth()->user()->friends()->attach($user->id);
        return back(); //
    }

    public function destroy(User $user)
    {
        auth()->user()->friends()->detach($user->id);
        return back(); //
    }
}
