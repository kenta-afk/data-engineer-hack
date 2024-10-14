<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// 🔽 2行追加
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔽 3ユーザ作成する
        User::create([
            'name' => 'ディオ・ブランドー',
            'email' => 'dio@example.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => '吉良吉影',
            'email' => 'kira@example.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'ジャイロ・ツェペリ',
            'email' => 'gyro@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
