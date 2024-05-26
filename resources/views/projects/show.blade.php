@extends('layouts.app')
@section('title')
    {{ $project->name }} - Project Details
@endsection
@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white text-center"> {{ $project->name }}</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $project->name }}</h5>
                <p class="card-text">{{ $project->description }}</p>
                <p class="card-text"><strong>Start Date:</strong> {{ $project->start_date }}</p>
                <p class="card-text"><strong>End Date:</strong> {{ $project->end_date }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                <p class="card-text"><strong>Budget:</strong> ${{ $project->budget }}</p>

                <h5 class="mt-4">Project Progress</h5>
                @php
                    $totalTasks = $project->tasks->count();
                    $completedTasks = $project->tasks->where('status', 'completed')->count();
                    $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                @endphp
                <div class="progress mb-4">
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">{{ round($progress) }}%</div>
                </div>

                <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Projects</a>
            </div>
        </div>
    </div>
@endsection
