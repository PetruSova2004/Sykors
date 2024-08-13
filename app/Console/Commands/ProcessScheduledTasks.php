<?php

namespace App\Console\Commands;

use App\Mail\TaskStatusMail;
use App\Models\Task;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessScheduledTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled tasks';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        $tasks = Task::query()
            ->where('execution_date', '<=', now())
            ->where('status_id', Task::STATUS_IN_QUEUE)
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks to process.');
        } else {
            $this->info('Processing ' . $tasks->count() . ' tasks.');

            $processedTasks = [];
            foreach ($tasks as $task) {
                $this->info('Processing task ID: ' . $task->id);
                try {
                    $response = Http::get($task->url, $task->parameters);

                    if ($response->successful()) {
                        $task->status_id = Task::STATUS_COMPLETED;
                        $task->status_description = 'Task completed successfully';
                        $this->info('Task ID ' . $task->id . ' completed successfully.');
                    } else {
                        $task->status_id = Task::STATUS_CANCELED;
                        $task->status_description = 'Task failed';
                        $task->exception = "Default Error Message";
                        $this->info('Task ID ' . $task->id . ' failed.');
                        Log::error('Task failed', ['task_id' => $task->id, 'exception' => $task->exception]);
                    }
                } catch (Exception $e) {
                    $task->status_id = Task::STATUS_CANCELED;
                    $task->status_description = 'Task failed with exception';
                    $task->exception = $e->getMessage();
                    Log::error('Task failed', ['task_id' => $task->id, 'exception' => $e->getMessage()]);
                    $this->info('Task ID ' . $task->id . ' failed with exception: ' . $e->getMessage());
                } finally {
                    $task->save();
                    $processedTasks[] = $task;
                }
            }

            if (!empty($processedTasks)) {
                Mail::to('test@test.com')->send(new TaskStatusMail($processedTasks));
            }
        }
    }}
