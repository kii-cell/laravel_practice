<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class DailyMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '毎日決まったメッセージを表示します。';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('日次メッセージバッチを開始します。');

        $currentDate = Carbon::today()->format('Y年m月d日');

        // ここに簡単な処理を記述します。
        $this->info($currentDate . ' のメッセージです: Laravelバッチは快適です！');

        $this->info('日次メッセージバッチが完了しました。');

        return Command::SUCCESS;
    }
}
