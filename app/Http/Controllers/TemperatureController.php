<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Models\Temperature;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TemperatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

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

        // Temperature テーブルのエントリを取得、または作成
        $temperature = DB::table('temperature')
            ->where('sender_id', auth()->id())
            ->where('receiver_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', auth()->id());
            })
            ->first();

        // レコードが存在しない場合、新規作成
        if (!$temperature) {
            DB::table('temperature')->insert([
                'sender_id' => auth()->id(),
                'receiver_id' => $user->id,
                'history' => 0, // 初期値は0
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $temperature = DB::table('temperature')
                ->where('sender_id', auth()->id())
                ->where('receiver_id', $user->id)
                ->orWhere(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', auth()->id());
                })
                ->first();
        }

        // 新しいチャットがある場合、history を +1
        if ($chats->count() > 0) {
            DB::table('temperature')
                ->where('id', $temperature->id)
                ->increment('history', 1); // チャットごとに +1
        }

        // 再度 temperature レコードを取得して最新の値を反映
        $updatedTemperature = DB::table('temperature')->find($temperature->id);

        // 経過日数に応じて history を減少 (-1)
        if ($daysSinceFirstChat !== null) {
            DB::table('temperature')
                ->where('id', $updatedTemperature->id)
                ->decrement('history', $daysSinceFirstChat); // 経過日数ごとに -1
        }

        // 再度最新の値を取得して answer を計算
        $finalTemperature = DB::table('temperature')->find($temperature->id);

        // answer = 最新の history + 50
        $answer = ($finalTemperature->history ?? 0) + 50;
        dd($answer);
        // ビューにデータを渡す
        return view('chat.index', [
            'chats' => $chats,
            'receiverId' => $user->id,
            'daysSinceFirstChat' => $daysSinceFirstChat,
            'answer' => $answer, // 最新の history + 50 を表示
        ]);
    }





    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
