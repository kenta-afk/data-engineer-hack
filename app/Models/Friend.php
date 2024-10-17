<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $fillable = ['follow_id', 'follower_id', 'status'];

    // リレーション：フォローされたユーザー
    public function follow()
    {
        return $this->belongsTo(User::class, 'follow_id');
    }

    // リレーション：フォローしたユーザー
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }
}
