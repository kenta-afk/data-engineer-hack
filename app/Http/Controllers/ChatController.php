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
        // 認証ユーザーを取得
        $authUser = auth()->user();

        // 全てのユーザーを取得
        $users = User::all();

        // チャット数と経過日数で温度を計算するために各ユーザーとのデータを取得
        $userTemperatures = [];

        foreach ($users as $user) {
            // チャット履歴を取得
            $chats = Chat::where(function ($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })->get();

            // 最初のチャット日を取得
            $firstChatDate = Chat::where(function($query) use ($user) {
                $query->where('sender_id', auth()->id())
                    ->where('receiver_id', $user->id);
            })->orWhere(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })->min('created_at');

            // 経過日数を計算
            $daysSinceFirstChat = $firstChatDate ? Carbon::parse($firstChatDate)->diffInDays(Carbon::now()) : null;

            // チャット数を取得
            $chatCount = $chats->count();

            // チャット数と経過日数で温度を計算
            $temperature = $daysSinceFirstChat !== null ? floor(0 - $daysSinceFirstChat + $chatCount) : null;

            // 温度を -50℃から50℃に制限
            if ($temperature !== null) {
                $temperature = max(-50, min(50, $temperature));
            }

            // 計算結果をユーザーごとに保存
            $userTemperatures[$user->id] = $temperature;

            // 温度が -50℃ 以下の場合、相互フォローを解除
            if ($temperature <= -50) {
                $authUser->unfollow($user);
                $user->unfollow($authUser);
            }
        }

        // ビューにデータを渡す
        return view('chat.index', [
            'users' => $users,
            'userTemperatures' => $userTemperatures,  // 各ユーザーの温度データ
        ]);

        // 計算結果をユーザーごとに保存
        $userTemperatures[$user->id] = $temperature;
    }


    // 選択したユーザーとのチャットを表示
    public function show(User $user)
    {
        // 認証ユーザーを取得
        $authUser = auth()->user();
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
        $answer = floor(0 - $daysSinceFirstChat + $chatCount);

        // 温度を -50℃から50℃に制限
        $answer = max(-50, min(50, $answer));

        // 温度が -50℃ 以下の場合、相互フォローを解除
        if ($answer <= -50) {
            $authUser = auth()->user();
            $authUser->unfollow($user);
            $user->unfollow($authUser);
        }

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