<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HighPriorityTaskEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    public      $event;

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task, $event)
    {
        $this->task = $task;
        $this->event = $event;
    }

    public function broadcastOn()
    {
        return new Channel('tasks');
    }

    public function broadcastAs()
    {
        return "task.$this->event";
    }

    public function broadcastQueue()
    {
        return 'high';
    }

    public function failed(\Exception $exception)
    {
        \Log::error('Failed to process task: ' . $this->task->id . '. Error: ' . $exception->getMessage());
    }
}
