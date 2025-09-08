<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // キュー対応したい場合
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Task;

class TaskRemindMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userName;
    public $tasks;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $tasks)
    {
        $this->userName = $userName;
        $this->tasks = $tasks; // タスク配列
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【サイト名】翌日対応期限のタスクがあります',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task_remind', // 後で作成する Blade ファイル
            with: [
                'name' => $this->userName,
                'tasks' => $this->tasks,
            ],
        );
    }
}
