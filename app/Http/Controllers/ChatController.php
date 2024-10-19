<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ChatController extends Controller
{
    // ユーザー一覧を表示
    public function index()
    {
        // 全てのユーザーを取得
        $users = User::all();

        return view('chat.index', compact('users'));
    }


    // 選択したユーザーとのチャットを表示
    public function show(User $user)
    {
        // 認証ユーザーと選択されたユーザーとのチャット履歴を取得
        $chats = Chat::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->get();

        // 認証ユーザーと指定ユーザーの間の最初のチャット日を取得
        $firstChatDate = Chat::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->min('created_at');

        // 初回のチャット日からの経過日数を計算
        $daysSinceFirstChat = $firstChatDate ? Carbon::parse($firstChatDate)->diffInDays(Carbon::now()) : null;

        // チャット数と経過日数で answer を計算
        $chatCount = $chats->count();
        $answer = floor(50 - $daysSinceFirstChat + $chatCount);

        // ビューにデータを渡す
        return view('chat.show', [
            'chats' => $chats,
            'receiverId' => $user->id,
            'daysSinceFirstChat' => $daysSinceFirstChat,
            'answer' => $answer,
        ]);
    }

    // メッセージ送信
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|integer|exists:users,id',
        ]);

        Chat::create([
            'message' => $request->message,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
        ]);
        return redirect()->back();
    }

    // 特定のユーザーとのチャット数を取得
    public function chatCount(User $user)
    {
        $chatCount = Chat::where(function ($query) use ($user) {
            $query->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', auth()->id());
        })->count();

        return view('chat.count', [
            'chatCount' => $chatCount,
            'receiverId' => $user->id
        ]);
    }
}
