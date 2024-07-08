@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-3 rounded mb-4">
            <h2>Upcoming Routines</h2>
            <a href="{{ route('routines.create') }}" class="btn btn-primary">Add Routine</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3>Daily Routines</h3>
                        <div class="kanban-column">
                            @forelse($upcomingDailyRoutines as $routine)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $routine->title }}</h5>
                                        <p class="card-text">{{ $routine->description }}</p>
                                        <p class="card-text"><strong>Days:</strong>
                                            {{ implode(', ', json_decode($routine->days, true) ?? []) }}</p>
                                        <p class="card-text"><strong>Time:</strong> {{ $routine->start_time }} -
                                            {{ $routine->end_time }}</p>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-warning"><i
                                                    class="bi bi-pencil"></i></a>
                                            <form action="{{ route('routines.destroy', $routine->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No upcoming daily routines.</p>
                            @endforelse
                            <div class="mt-3">
                                <a href="{{ route('routines.showDaily') }}" class="btn btn-secondary">View All Daily
                                    Routines</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3>Weekly Routines</h3>
                        <div class="kanban-column">
                            @forelse($upcomingWeeklyRoutines as $routine)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $routine->title }}</h5>
                                        <p class="card-text">{{ $routine->description }}</p>
                                        <p class="card-text"><strong>Weeks:</strong>
                                            {{ implode(', ', json_decode($routine->weeks, true) ?? []) }}</p>
                                        <p class="card-text"><strong>Time:</strong> {{ $routine->start_time }} -
                                            {{ $routine->end_time }}</p>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('routines.edit', $routine->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('routines.destroy', $routine->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No upcoming weekly routines.</p>
                            @endforelse
                            <div class="mt-3">
                                <a href="{{ route('routines.showWeekly') }}" class="btn btn-secondary">View All Weekly
                                    Routines</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3>Monthly Routines</h3>
                        <div class="kanban-column">
                            @forelse($upcomingMonthlyRoutines as $routine)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $routine->title }}</h5>
                                        <p class="card-text">{{ $routine->description }}</p>
                                        <p class="card-text"><strong>Months:</strong>
                                            {{ implode(
                                                ', ',
                                                array_map(function ($month) {
                                                    return DateTime::createFromFormat('!m', $month)->format('F');
                                                }, json_decode($routine->months, true) ?? []),
                                            ) }}
                                        </p>
                                        <p class="card-text"><strong>Time:</strong> {{ $routine->start_time }} -
                                            {{ $routine->end_time }}</p>
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('routines.edit', $routine->id) }}"
                                                class="btn btn-warning">Edit</a>
                                            <form action="{{ route('routines.destroy', $routine->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>No upcoming monthly routines.</p>
                            @endforelse
                            <div class="mt-3">
                                <a href="{{ route('routines.showMonthly') }}" class="btn btn-secondary">View All Monthly
                                    Routines</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
