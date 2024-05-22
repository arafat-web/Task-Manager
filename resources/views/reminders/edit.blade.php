@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Reminder</h1>

    <form action="{{ route('reminders.update', $reminder->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $reminder->title }}" required>
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="5">{{ $reminder->description }}</textarea>
            @error('description')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $reminder->date }}">
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" name="time" id="time" class="form-control" value="{{ $reminder->time }}">
            @error('time')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Reminder</button>
    </form>
</div>
@endsection
