<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // DBファサードを使うために追記
use Illuminate\Support\Carbon; // 日時を入れるためによく使います

class PostSeeder extends Seeder
{
    /**
     * データベースのデータをここで入れます。
     */
    public function run(): void
    {
        DB::table('posts')->truncate();
        // posts テーブルにデータを一つ入れる例 (DBファサードを使用)
        DB::table('posts')->insert(
            [
                'title' => 'シーダーで作った記事',
                'body' => 'この内容はシーダーによって自動で入りました',
                'published_at' => Carbon::now(), // 今の日時を入れる
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
            // ,...
        );

        // もっとたくさんのダミーデータを入れたいときは「ファクトリ」という機能を使うと便利です（後述）
        // \App\Models\Post::factory()->count(10)->create(); // ダミー記事を10個作る例
    }
}
