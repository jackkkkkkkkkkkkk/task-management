<?php

namespace App\Livewire;

use Illuminate\Validation\ValidationException;
use Livewire\Component;
use App\Models\Task;

class TaskManager extends Component
{
    public    $tasks     = [
        'in_progress' => [],
        'postponed'   => [],
        'done'        => [],
    ];
    public    $newTask   = [
        'title'       => '',
        'description' => '',
        'status'      => Task::STATUS_DOING,
        'deadline'    => '',
        'priority'    => Task::PRIORITY_LOW,
    ];
    protected $listeners = [
        'loadTasks' => 'loadTasks',
    ];

    public function mount()
    {
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks['in_progress'] = Task::where('status', Task::STATUS_DOING)->get();
        $this->tasks['postponed'] = Task::where('status', Task::STATUS_POSTPONED)->get();
        $this->tasks['done'] = Task::where('status', Task::STATUS_DONE)->get();
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        try {
            $task = Task::find($taskId);
            if ($task) {
                $task->status = $newStatus;
                $task->save();
            }
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\Log::debug($e);
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Failed to create task: ' . $e->getMessage(),
            ]);
        }
    }

    public function createTask()
    {
        try {
            $this->validate([
                'newTask.title'       => 'required|string|max:255',
                'newTask.description' => 'nullable|string',
                'newTask.deadline'    => 'required|date',
                'newTask.status'      => 'required',
            ]);
            Task::create($this->newTask);
            $this->resetNewTask();
        } catch (ValidationException $e) {
            \Illuminate\Support\Facades\Log::debug($e);
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e);
            $this->dispatch('notify', [
                'type'    => 'error',
                'message' => 'Failed to create task: ' . $e->getMessage(),
            ]);
        }
    }

    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->delete();
            $this->loadTasks();
            $this->dispatch('task-deleted', ['task' => $task]);
        }
    }

    public function resetNewTask()
    {
        $this->newTask = [
            'title'       => '',
            'description' => '',
            'status'      => Task::STATUS_DOING,
        ];
    }

    public function render()
    {
        $this->loadTasks();

        return view('livewire.task-manager');
    }
}
