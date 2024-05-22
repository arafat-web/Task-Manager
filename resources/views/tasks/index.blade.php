@extends('layouts.app')

@section('content')
<style>
    .kanban-column {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        height: 100%;
    }

    .kanban-list {
        min-height: 400px;
        background-color: #e9ecef;
        border-radius: 5px;
        padding: 10px;
    }

    .kanban-item {
        cursor: move;
    }

    .kanban-item.invisible {
        opacity: 0.4;
    }
</style>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $project->name }} - Tasks</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="kanban-column">
                <h2 class="text-primary">To Do</h2>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="to_do">Add Task</button>
                <div class="kanban-list" id="to_do">
                    @foreach($tasks['to_do'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="kanban-column">
                <h2 class="text-warning">In Progress</h2>
                <button type="button" class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="in_progress">Add Task</button>
                <div class="kanban-list" id="in_progress">
                    @foreach($tasks['in_progress'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-warning btn-sm">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="kanban-column">
                <h2 class="text-success">Completed</h2>
                <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createTaskModal" data-status="completed">Add Task</button>
                <div class="kanban-list" id="completed">
                    @foreach($tasks['completed'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" draggable="true">
                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-success btn-sm">View</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaskModalLabel">Create Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" name="due_date" id="due_date" class="form-control">
                            @error('due_date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority</label>
                            <select name="priority" id="priority" class="form-select" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <input type="hidden" name="status" id="task_status">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const kanbanItems = document.querySelectorAll('.kanban-item');
        const kanbanLists = document.querySelectorAll('.kanban-list');
        const createTaskModal = document.getElementById('createTaskModal');
        const taskStatusInput = document.getElementById('task_status');

        createTaskModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var status = button.getAttribute('data-status'); // Extract info from data-* attributes
            taskStatusInput.value = status;
        });

        kanbanItems.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
        });

        kanbanLists.forEach(list => {
            list.addEventListener('dragover', handleDragOver);
            list.addEventListener('drop', handleDrop);
        });

        function handleDragStart(e) {
            e.dataTransfer.setData('text/plain', e.target.dataset.id);
            setTimeout(() => {
                e.target.classList.add('invisible');
            }, 0);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('invisible');
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();
            const id = e.dataTransfer.getData('text');
            const draggableElement = document.querySelector(`.kanban-item[data-id='${id}']`);
            const dropzone = e.target.closest('.kanban-list');
            dropzone.appendChild(draggableElement);

            const status = dropzone.id;

            updateTaskStatus(id, status);
        }

        function updateTaskStatus(id, status) {
            fetch(`/tasks/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status })
            }).then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update task status');
                }
                return response.json();
            }).then(data => {
                console.log('Task status updated:', data);
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    });
</script>
@endsection
