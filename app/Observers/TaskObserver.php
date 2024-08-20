<?php

namespace App\Observers;

use App\Jobs\ProcessCriticalTask;
use App\Jobs\ProcessOrdinaryTask;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        if ($task->priority == Task::PRIORITY_UP) {
            ProcessCriticalTask::dispatch($task, 'created')->onQueue('high');
        } else {
            ProcessOrdinaryTask::dispatch($task, 'created')->onQueue('low');
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->priority == Task::PRIORITY_UP) {
            ProcessCriticalTask::dispatch($task, 'updated')->onQueue('high');
        } else {
            ProcessOrdinaryTask::dispatch($task, 'updated')->onQueue('low');
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        //
    }
}
