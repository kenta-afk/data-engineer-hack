<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {

        $users = User::with('followers2')->get();
        

        

        
        return view('users.index', compact('users'));
    }
    public function destroy(User $follower)
    {
        // 認証ユーザーがフォローを解除
        auth()->user()->followers()->detach($follower->id);

        // リダイレクトする
        return redirect()->route('users.index')->with('success', 'フォローを解除しました。');
    }
    // 検索機能
    public function search(Request $request)
    {
        // 検索キーワードを取得
        $keyword = $request->input('keyword');

        // キーワードに基づいてユーザーを検索
        if (!empty($keyword)) {
            // キーワードがある場合
            $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(10);
        } else {
            // キーワードがない場合は全ユーザーを取得
            $users = User::paginate(10);
        }

        // users.search ビューにデータを渡す
        return view('users.search', compact('users'));
    }



    
    public function requestApproval(User $user)
    {
        // リクエストがすでに存在するか確認
        if (Friend::where('follow_id', $user->id)
                ->where('follower_id', auth()->id())
                ->exists()) {
            return back()->with('error', 'すでにリクエストを送信済みです。');
        }

        // リクエストを新規作成
        Friend::create([
            'follow_id' => $user->id,
            'follower_id' => auth()->id(),
            'status' => 'pending',
        ]);

        return back()->with('success', '友達リクエストを送信しました。');
    }

    public function approveRequest(Friend $friend)
    {
        // リクエストのステータスを承認済みに更新
        $friend->update(['status' => 'approved']);
        return back()->with('success', '友達リクエストを承認しました。');
    }

    public function show(User $user)
    {
        // プロフィールページを表示
        return view('profile.show', compact('user'));
    }
}
