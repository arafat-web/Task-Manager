@extends('layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container">
        <h2>Welcome to your Dashboard</h2>
        <p>This is your dashboard where you can manage your tasks, routines, notes, and files.</p>
        
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $tasksCount }}</strong> tasks pending.</p>
                        <a href="{{ route('projects.index') }}" class="btn btn-primary mt-auto">View Tasks</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Routines</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $routinesCount }}</strong> routines scheduled today.</p>
                        <a href="{{ route('routines.index') }}" class="btn btn-primary mt-auto">View Routines</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Notes</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $notesCount }}</strong> notes saved.</p>
                        <a href="{{ route('notes.index') }}" class="btn btn-primary mt-auto">View Notes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Files</h5>
                        <p class="card-text flex-grow-1">You have <strong>{{ $filesCount }}</strong> files.</p>
                        <a href="{{ route('files.index') }}" class="btn btn-primary mt-auto">View Files</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Recent Tasks</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($recentTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $task->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $task->status == 'to_do' ? 'To Do' : 'In Progress' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Today's Routines</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($todayRoutines as $routine)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $routine->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $routine->frequency }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Recent Notes</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($recentNotes as $note)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $note->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Upcoming Reminders</h5>
                        <ul class="list-group flex-grow-1">
                            @foreach($upcomingReminders as $reminder)
                                <li class="list-group-item d-flex justify-content-between align-items-center {{ $reminder->date->isToday() ? 'bg-warning' : ($reminder->date->isPast() ? 'bg-danger' : 'bg-success') }}">
                                    {{ $reminder->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $reminder->date->format('M d') }} {{ $reminder->time ? $reminder->time->format('H:i') : '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
