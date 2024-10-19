<?php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

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
        $chats = Chat::where(function($query) use ($user) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', auth()->id());
        })->get();

        return view('chat.show', [
            'chats' => $chats,
            'receiverId' => $user->id
        ]);
    }

    // メッセージ送信
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required|integer|exists:users,id',
        
             'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーション   

        ]);
            // 新しいチャットメッセージを作成
    $chat = new Chat();
    $chat->sender_id = auth()->id();
    $chat->receiver_id = $request->receiver_id;

    // メッセージがある場合はセット
    if ($request->message) {
        $chat->message = $request->message;
    }


 // 画像がアップロードされた場合
 if ($request->hasFile('image')) {
    $path = $request->file('image')->store('images', 'public'); // 画像をストレージに保存
    $chat->image_path = $path; // 画像のパスを保存
}

        Chat::create([
            'message' => $request->message,
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
        ]);

        return redirect()->back();
    

   

    $chat->save();

    return redirect()->back();
}

public function up()
{
    Schema::table('chats', function (Blueprint $table) {
        $table->string('image_path')->nullable(); // 画像のパスを保存するカラムを追加
    });
}

}
?>