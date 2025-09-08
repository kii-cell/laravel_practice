<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ここにユーザーを作成する処理を書きます

        // デフォルト認証（メールアドレス）でログインできるユーザーを作成
        User::create(
            [
                'name' => 'テストユーザー2',
                'email' => 'test2@example.com', // 認証に使うメールアドレスを設定
                'password' => Hash::make('password2'), // パスワードをハッシュ化して保存
                // 他に必要なデフォルトカラムがあればここに追加 (例: email_verified_at など)
            ],
            // ...
        );

        // 複数のユーザーを作成したい場合は、createメソッドを複数回呼び出したり、ループを使ったりします。
        // User::create([...]);
        // User::factory()->count(10)->create(); // ファクトリを使えばダミーデータを複数簡単に作れます
    }
}
