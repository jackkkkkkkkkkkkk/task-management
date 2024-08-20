<?php

namespace App\Jobs;

use App\Events\TaskWasSaved;
use App\Models\Task;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCriticalTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $event;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Task $task
     *
     * @return void
     */
    public function __construct(Task $task, $event)
    {
        $this->task = $task;
        $this->event = $event;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        broadcast(new TaskWasSaved($this->task, $this->event))->toOthers();
    }
}
