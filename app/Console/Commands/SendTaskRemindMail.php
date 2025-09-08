<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTaskRemindMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --date: 処理対象日（任意）指定
     */
    protected $signature = 'task:remind {--date=}';

    /**
     * The console command description.
     */
    protected $description = '翌日対応期限のタスクを担当者にリマインドメール送信します';

    public function handle()
    {
        $this->info('リマインドバッチを開始します。');

        // 基準日を取得（引数があればそちらを使用）
        $baseDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::today();

        $targetDate = $baseDate->copy()->addDay();

        $this->info("対象タスクの日付: {$targetDate->toDateString()}");

        // 対象タスクの抽出
        $tasks = Task::with('user')
            ->whereIn('status', [1, 2])
            ->whereDate('deadline_at', $targetDate)
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('翌日対応期限のタスクはありません。');
            return Command::SUCCESS;
        }

        // タスクごとにメール送信（ユーザー単位にまとめてもOK）
        foreach ($tasks->groupBy('user_id') as $userId => $userTasks) {
            $user = $userTasks->first()->user;
            if (!$user || !$user->email) continue;

            Mail::to($user->email)->queue(new \App\Mail\TaskRemindMail($user->name, $userTasks));

            $this->info("メール送信完了: {$user->email} / タスク件数: " . count($userTasks));
        }


        $this->info('リマインドバッチが完了しました。');
        return Command::SUCCESS;
    }
}
