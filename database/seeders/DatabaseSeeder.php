<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\MemoSeeder;
use Database\Seeders\TaskSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // 他のSeederもここで呼び出します

        //$this->call(UserSeeder::class); // UserSeeder を呼び出す記述を追加

        // \App\Models\User::factory(10)->create(); // ダミーユーザーをファクトリで作る場合の例
        //$this->call(OtherSeeder::class);

        // User::factory(10)->create();

        /*User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

        /**
         * アプリケーションのデータベースにデータを入れます。
         */

        //$this->call(PostSeeder::class); // PostSeeder を実行するように指示

        $this->call(TaskSeeder::class);
    }
}
