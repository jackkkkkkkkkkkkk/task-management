<?php

namespace App\Observers;

use App\Events\HighPriorityTaskEvent;
use App\Events\LowPriorityTaskEvent;
use App\Models\Task;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        if ($task->priority == Task::PRIORITY_UP) {
            broadcast(new HighPriorityTaskEvent($task, 'created'))->toOthers();
        } else {
            broadcast(new LowPriorityTaskEvent($task, 'created'))->toOthers();
        }
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        if ($task->priority == Task::PRIORITY_UP) {
            broadcast(new HighPriorityTaskEvent($task, 'updated'))->toOthers();
        } else {
            broadcast(new LowPriorityTaskEvent($task, 'updated'))->toOthers();
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
