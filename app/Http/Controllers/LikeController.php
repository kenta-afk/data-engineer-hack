<?php

namespace App\Http\Controllers;

use App\Models\Chat;

class LikeController extends Controller
{
    public function like(Chat $chat)
    {
        $chat->liked()->attach(auth()->id());
        return back();
    }

    public function dislike(Chat $chat)
    {
        $chat->liked()->detach(auth()->id());
        return back();
    }
}
