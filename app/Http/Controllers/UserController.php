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
}
