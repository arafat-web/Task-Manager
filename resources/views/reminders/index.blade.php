@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-3 rounded mb-4">
        <h2>Reminders</h2>
        <a href="{{ route('reminders.create') }}" class="btn btn-primary">Add Reminder</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @forelse($reminders as $reminder)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $reminder->title }}</h5>
                        <p class="card-text">{{ Str::limit($reminder->description, 150) }}</p>
                        <p class="card-text"><strong>Date:</strong> {{ $reminder->date }}</p>
                        <p class="card-text"><strong>Time:</strong> {{ $reminder->time }}</p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('reminders.edit', $reminder->id) }}" class="btn btn-warning"><i class="bi bi-pencil-square"></i> </a>
                            <form action="{{ route('reminders.destroy', $reminder->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this reminder?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No reminders found.</p>
        @endforelse
    </div>
</div>
@endsection
