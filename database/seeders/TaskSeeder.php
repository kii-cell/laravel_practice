<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->truncate();
        DB::table('tasks')->insert(
            [
                'title' => 'シーダーで作ったタスク',
                'content' => 'この内容はシーダーによって自動で入りました',
                'deadline_at' => Carbon::now(),
                'support_at' => Carbon::now(),
                'priority' => 1,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),


            ]
        );
    }
}
