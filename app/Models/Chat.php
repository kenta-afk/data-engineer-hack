<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['message', 'sender_id', 'receiver_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function liked()
    {
        // 多対多のリレーションを定義
        return $this->belongsToMany(User::class, 'user_chat', 'chat_id', 'user_id')->withTimestamps();
    }
}
