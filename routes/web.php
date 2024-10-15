<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/friend/{user}', [FriendController::class, 'store'])->name('friend.store');
    Route::delete('/friend/{user}', [FriendController::class, 'destroy'])->name('friend.destroy');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::get('/users', [UserController::class, 'index'])->name('user.index');
    

    // ユーザー一覧を表示するルート
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

    // 選択されたユーザーとのチャット画面を表示するルート
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');

    // メッセージ送信
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

});

require __DIR__ . '/auth.php';
