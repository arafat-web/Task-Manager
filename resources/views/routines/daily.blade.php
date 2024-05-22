@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Daily Routines</h1>

    <div class="row">
        @forelse($dailyRoutines as $routine)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $routine->title }}</h5>
                        <p class="card-text">{{ $routine->description }}</p>
                        <p class="card-text"><strong>Days:</strong> {{ implode(', ', json_decode($routine->days, true) ?? []) }}</p>
                        <p class="card-text"><strong>Time:</strong> {{ $routine->start_time }} - {{ $routine->end_time }}</p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('routines.edit', $routine->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('routines.destroy', $routine->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this routine?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No daily routines found.</p>
        @endforelse
    </div>
</div>
@endsection
