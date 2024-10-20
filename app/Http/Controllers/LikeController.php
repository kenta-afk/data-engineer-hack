<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function like(Request $request)
    {
        $chatId = $request->input('chat_id');
        $chat = Chat::find($chatId);

        if ($chat) {
            $chat->liked()->attach(auth()->id());
        }

        return back();
    }

    public function dislike(Request $request)
    {
        $chatId = $request->input('chat_id');
        $chat = Chat::find($chatId);

        if ($chat) {
            $chat->liked()->detach(auth()->id());
        }

        return back();
    }

    public function show()
    {
        // 🔽 liked のデータも合わせて取得するよう修正
        $chat = Chat::with(['user', 'liked'])->latest()->get();

        return view('chat.show', compact('chats'));
    }
}
