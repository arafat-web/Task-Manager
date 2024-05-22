@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome to your Dashboard</h1>
        <p>This is your dashboard where you can manage your tasks, routines, notes, and calendar events.</p>
        
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Tasks</h5>
                        <p class="card-text">You have <strong>{{ $tasksCount }}</strong> tasks pending.</p>
                        <a href="#" class="btn btn-primary">View Tasks</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Routines</h5>
                        <p class="card-text">You have <strong>{{ $routinesCount }}</strong> routines scheduled today.</p>
                        <a href="#" class="btn btn-primary">View Routines</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Notes</h5>
                        <p class="card-text">You have <strong>{{ $notesCount }}</strong> notes saved.</p>
                        <a href="#" class="btn btn-primary">View Notes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Calendar Events</h5>
                        <p class="card-text">You have <strong>{{ $calendarEventsCount }}</strong> events scheduled this week.</p>
                        <a href="#" class="btn btn-primary">View Calendar</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Recent Tasks</h5>
                        <ul class="list-group">
                            @foreach($recentTasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $task->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $task->status }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Today's Routines</h5>
                        <ul class="list-group">
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Recent Notes</h5>
                        <ul class="list-group">
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Upcoming Events</h5>
                        <ul class="list-group">
                            @foreach($upcomingEvents as $event)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $event->title }}
                                    <span class="badge bg-primary rounded-pill">{{ $event->start_time->format('M d') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
