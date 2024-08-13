<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $tasks;

    /**
     * Create a new message instance.
     *
     * @param array $tasks
     */
    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->subject('Task Status Updated')
            ->view('emails.task_status')
            ->with(['tasks' => $this->tasks]);
    }
}
