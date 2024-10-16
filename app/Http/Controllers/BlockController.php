<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    public function store(User $user)
    {
        $user->blocked()->attach(auth()->id());
        DB::table('friends')
            ->where(function ($query) use ($user) {
                // 自分がフォローしている相手か、自分をフォローしている相手か
                $query->where('follow_id', auth()->id())
                    ->where('follower_id', $user->id)
                    ->orWhere(function ($query) use ($user) {
                        $query->where('follower_id', auth()->id())
                            ->where('follow_id', $user->id);
                    });
            })
            ->delete();

        return back();
    }



    public function destroy(User $user)
    {
        $user->blocked()->detach(auth()->id());
        return back();
    }
}
