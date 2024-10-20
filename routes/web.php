<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\GoogleLoginController;
use App\Http\Controllers\TemperatureController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])
    ->name('login.google');

Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])
    ->name('login.google.callback');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');


    //ユーザー検索
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/users/search', [UserController::class, 'search'])->name('users.search');



    // フォローリクエスト関連のルート
    Route::post('/friend/request/{user}', [UserController::class, 'requestApproval'])->name('friend.request');
    Route::post('/friend/approve/{friend}', [UserController::class, 'approveRequest'])->name('friend.approve');
    Route::delete('/friend/{user}', [UserController::class, 'destroy'])->name('friend.destroy');


    //いいね関連
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'like'])->name('chats.like');
    Route::delete('/tweets/{tweet}/dislike', [LikeController::class, 'dislike'])->name('chats.dislike');


    //ユーザーフォロー
    Route::post('/friend/{user}', [FriendController::class, 'store'])->name('friend.store');
    Route::delete('/friend/{user}', [FriendController::class, 'destroy'])->name('friend.destroy');



    //ブロック関連
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    Route::post('/block/{user}', [BlockController::class, 'store'])->name('block.store');
    Route::delete('/block/{user}', [BlockController::class, 'destroy'])->name('block.destroy');

    Route::get('/temperature/count/{user}', [TemperatureController::class, 'show'])->name('temperature.show');

    // ユーザー一覧を表示するルート
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');


    // 選択されたユーザーとのチャット画面を表示するルート
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');

    // メッセージ送信
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    //チャット数カウント
    Route::get('/chat/count/{user}', [ChatController::class, 'chatCount'])->name('chat.count');
});

require __DIR__ . '/auth.php';