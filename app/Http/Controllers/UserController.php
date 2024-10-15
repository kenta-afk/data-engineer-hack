<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // データベースから全てのユーザーを取得
        $users = User::with('followers')->get();

        // users.indexビューにユーザーのデータを渡す
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



    public function show(User $user)
    {
        return view('user.index', compact('user'));
    }
}
