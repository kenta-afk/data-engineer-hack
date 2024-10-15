<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // データベースから全てのユーザーを取得
        $users = User::all();

        // usersビューにユーザーのデータを渡す
        return view('users.index', compact('users'));
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
}
