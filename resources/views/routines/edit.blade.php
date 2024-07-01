@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white">Edit Routine</h2>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ route('routines.update', $routine->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ $routine->title }}" required>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ $routine->description }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="frequency" class="form-label">Frequency</label>
                        <select name="frequency" id="frequency" class="form-select" required>
                            <option value="daily" {{ $routine->frequency == 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="weekly" {{ $routine->frequency == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ $routine->frequency == 'monthly' ? 'selected' : '' }}>Monthly
                            </option>
                        </select>
                        @error('frequency')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3" id="days" style="{{ $routine->frequency == 'daily' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Days</label>
                        @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}"
                                    id="{{ $day }}"
                                    {{ in_array($day, json_decode($routine->days, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3" id="weeks"
                        style="{{ $routine->frequency == 'weekly' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Weeks</label>
                        @for ($i = 1; $i <= 52; $i++)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="weeks[]" value="{{ $i }}"
                                    id="week{{ $i }}"
                                    {{ in_array($i, json_decode($routine->weeks, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="week{{ $i }}">Week
                                    {{ $i }}</label>
                            </div>
                        @endfor
                    </div>
                    <div class="mb-3" id="months"
                        style="{{ $routine->frequency == 'monthly' ? '' : 'display: none;' }}">
                        <label class="form-label">Select Months</label>
                        @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="months[]" value="{{ $index + 1 }}"
                                    id="month{{ $index + 1 }}"
                                    {{ in_array($index + 1, json_decode($routine->months, true) ?? []) ? 'checked' : '' }}>
                                <label class="form-check-label" for="month{{ $index + 1 }}">{{ $month }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="form-control"
                            value="{{ $routine->start_time }}" required>
                        @error('start_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="form-control"
                            value="{{ $routine->end_time }}" required>
                        @error('end_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update Routine</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const frequencyElement = document.getElementById('frequency');
            const daysElement = document.getElementById('days');
            const weeksElement = document.getElementById('weeks');
            const monthsElement = document.getElementById('months');

            function updateVisibility() {
                const value = frequencyElement.value;
                daysElement.style.display = 'none';
                weeksElement.style.display = 'none';
                monthsElement.style.display = 'none';

                if (value === 'daily') {
                    daysElement.style.display = 'block';
                } else if (value === 'weekly') {
                    weeksElement.style.display = 'block';
                } else if (value === 'monthly') {
                    monthsElement.style.display = 'block';
                }
            }

            frequencyElement.addEventListener('change', updateVisibility);
            updateVisibility(); // Call on load to set initial visibility
        });
    </script>
@endsection
