<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // フォローしているユーザーとの関係
    public function follows()
    {
        return $this->belongsToMany(User::class, 'friends', 'follow_id', 'follower_id')
            ->withTimestamps();
    }

    // フォローされているユーザーとの関係
    public function followers()
    {
        return $this->belongsToMany(User::class, 'friends', 'follower_id', 'follow_id')
            ->withTimestamps()
            ->wherePivot('status', 'approved');  // statusがapprovedのフォロワーだけ取得
    }

    // 承認待ちのリレーション
    public function followers2()
    {
        return $this->belongsToMany(User::class, 'friends', 'follower_id', 'follow_id')
            ->withTimestamps()
            ->withPivot('status');  // ここでpivotにstatusを含める
    }

    // ブロックしているユーザーとの関係
    public function blocked()
    {
        return $this->belongsToMany(User::class, 'user_block', 'blocked_id', 'block_id')
            ->withTimestamps();
    }

    // 友達リクエスト
    public function receivedRequests()
    {
        return $this->hasMany(Friend::class, 'follower_id')->where('status', 'pending');
    }

    public function sentRequests()
    {
        return $this->hasMany(Friend::class, 'follow_id')->where('status', 'pending');
    }

    // チャットに対する「いいね」のリレーション
    public function likes()
    {
        return $this->belongsToMany(Chat::class)->withTimestamps();
    }

    // フォロー解除メソッド
    public function unfollow(User $user)
    {
        // フォロー解除（リレーションを削除）
        $this->follows()->detach($user->id);
    }

    
}
