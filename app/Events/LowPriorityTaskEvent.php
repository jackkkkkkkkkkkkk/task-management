<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowPriorityTaskEvent implements ShouldBroadcast
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

    public function broadcastOn(): Channel
    {
        return new Channel('tasks');
    }

    public function broadcastAs()
    {
        return "task.$this->event";
    }

    public function broadcastQueue()
    {
        return 'low';
    }
}
