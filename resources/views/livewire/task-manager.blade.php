<div class="container mx-auto p-4">
    <div x-data="{ open: false }" x-init="
    Livewire.on('task-created', () => {
        open = false;
        console.log('Task created event received');
    });
">
        <!-- Trigger Button -->
        <button @click="open = true" class="bg-blue-500 text-white px-4 py-2 rounded mb-1">Create New Task</button>

        <!-- Modal Background -->
        <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
            <!-- Modal Content -->
            <div @click.away="open = false" class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h2 class="text-xl font-semibold mb-4">Create a New Task</h2>
                <form wire:submit.prevent="createTask">
                    <div class="mb-4">
                        <label for="title" class="block font-medium text-gray-700">Task Title</label>
                        <input type="text" wire:model="newTask.title" id="title" class="w-full border p-2 rounded">
                        @error('newTask.title') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block font-medium text-gray-700">Description</label>
                        <textarea wire:model="newTask.description" id="description"
                                  class="w-full border p-2 rounded"></textarea>
                        @error('newTask.description') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="priority" class="block font-medium text-gray-700">Priority</label>
                        <select wire:model="newTask.priority" id="priority" class="w-full border p-2 rounded">
                            <option value="{{\App\Models\Task::PRIORITY_LOW}}" selected>Low</option>
                            <option value="{{\App\Models\Task::PRIORITY_MEDIUM}}">Medium</option>
                            <option value="{{\App\Models\Task::PRIORITY_UP}}">High</option>
                        </select>
                        @error('newTask.status') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="mb-4">
                        <label for="deadline" class="block font-medium text-gray-700">Deadline</label>
                        <input type="date" wire:model="newTask.deadline" id="deadline"
                               class="w-full border p-2 rounded">
                        @error('newTask.deadline') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end">
                        <button @click="open = false" type="button"
                                class="bg-gray-400 text-white px-4 py-2 rounded mr-2">Cancel
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <!-- In Progress Section -->
        <div class="bg-yellow-100 p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-yellow-700 mb-4">In Progress</h2>
            @forelse ($tasks['in_progress'] as $task)
                <div class="p-2 bg-white rounded-lg shadow mb-2 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $task->description }}</p>
                    </div>
                    <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                            class="border rounded-md p-1 text-gray-700">
                        <option
                            value="{{\App\Models\Task::STATUS_DOING}}" {{ $task->status === \App\Models\Task::STATUS_DOING ? 'selected' : '' }}>
                            In
                            Progress
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_POSTPONED}}" {{ $task->status === \App\Models\Task::STATUS_POSTPONED ? 'selected' : '' }}>
                            Postponed
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_DONE}}" {{ $task->status === \App\Models\Task::STATUS_DONE ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>
                </div>
            @empty
                <p class="text-gray-500">No tasks in progress.</p>
            @endforelse
        </div>

        <!-- Postponed Section -->
        <div class="bg-blue-100 p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Postponed</h2>
            @forelse ($tasks['postponed'] as $task)
                <div class="p-2 bg-white rounded-lg shadow mb-2 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $task->description }}</p>
                    </div>
                    <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                            class="border rounded-md p-1 text-gray-700">
                        <option
                            value="{{\App\Models\Task::STATUS_DOING}}" {{ $task->status === \App\Models\Task::STATUS_DOING ? 'selected' : '' }}>
                            In
                            Progress
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_POSTPONED}}" {{ $task->status === \App\Models\Task::STATUS_POSTPONED ? 'selected' : '' }}>
                            Postponed
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_DONE}}" {{ $task->status === \App\Models\Task::STATUS_DONE ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>
                </div>
            @empty
                <p class="text-gray-500">No postponed tasks.</p>
            @endforelse
        </div>

        <!-- Done Section -->
        <div class="bg-green-100 p-4 rounded-lg shadow">
            <h2 class="text-xl font-semibold text-green-700 mb-4">Done</h2>
            @forelse ($tasks['done'] as $task)
                <div class="p-2 bg-white rounded-lg shadow mb-2 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium">{{ $task->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ $task->description }}</p>
                    </div>
                    <select wire:change="updateTaskStatus({{ $task->id }}, $event.target.value)"
                            class="border rounded-md p-1 text-gray-700">
                        <option
                            value="{{\App\Models\Task::STATUS_DOING}}" {{ $task->status === \App\Models\Task::STATUS_DOING ? 'selected' : '' }}>
                            In
                            Progress
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_POSTPONED}}" {{ $task->status === \App\Models\Task::STATUS_POSTPONED ? 'selected' : '' }}>
                            Postponed
                        </option>
                        <option
                            value="{{\App\Models\Task::STATUS_DONE}}" {{ $task->status === \App\Models\Task::STATUS_DONE ? 'selected' : '' }}>
                            Done
                        </option>
                    </select>
                </div>
            @empty
                <p class="text-gray-500">No tasks completed.</p>
            @endforelse
        </div>

    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.Echo !== 'undefined') {
                window.Echo.channel('tasks')
                    .listen('.task.updated', (e) => {
                        console.log('Task updated:', e);
                        Livewire.dispatch('loadTasks');
                        toastr.info(e.task.title + ' Updated!');
                    });
                window.Echo.channel('tasks')
                    .listen('.task.created', (e) => {
                        console.log('Task created:', e);
                        Livewire.dispatch('loadTasks');
                        toastr.success(e.task.title + ' Created!');
                    });
            } else {
                console.error('Laravel Echo is not initialized');
            }
        });

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('notify', event => {
                const type = event.detail[0].type
                const message = event.detail[0].message
                toastr[type](message);

            });
        });
    </script>

@endpush
