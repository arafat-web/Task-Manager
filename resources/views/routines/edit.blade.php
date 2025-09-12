@extends('layouts.app')

@section('title', 'Edit Routine')

@section('content')
<style>
    :root {
        --routine-primary: #8b5cf6;
        --routine-secondary: #a855f7;
        --routine-success: #10b981;
        --routine-warning: #f59e0b;
        --routine-danger: #ef4444;
        --routine-info: #3b82f6;
        --routine-light: #faf5ff;
        --routine-dark: #1e293b;
        --routine-gray: #64748b;
        --routine-border: #e2e8f0;
        --routine-shadow: rgba(0, 0, 0, 0.1);
        --routine-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .edit-header {
        background: linear-gradient(135deg, var(--routine-primary) 0%, var(--routine-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--routine-shadow-lg);
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--routine-border);
        box-shadow: 0 4px 6px -1px var(--routine-shadow);
        overflow: hidden;
        max-width: 700px;
        margin: 0 auto;
    }

    .form-card-body {
        padding: 2rem;
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--routine-shadow);
        border: 1px solid var(--routine-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--routine-gray);
        font-weight: 600;
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--routine-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-modern {
        border: 1px solid var(--routine-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--routine-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        outline: none;
    }

    .form-select-modern {
        border: 1px solid var(--routine-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-select-modern:focus {
        border-color: var(--routine-primary);
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        outline: none;
    }

    .frequency-section {
        background: var(--routine-light);
        border: 1px solid var(--routine-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-weight: 600;
        color: var(--routine-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.75rem;
        margin-top: 0.75rem;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        border: 1px solid var(--routine-border);
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .checkbox-item:hover {
        border-color: var(--routine-primary);
        background: rgba(139, 92, 246, 0.05);
    }

    .checkbox-item input[type="checkbox"]:checked + label {
        color: var(--routine-primary);
        font-weight: 600;
    }

    .action-buttons {
        background: var(--routine-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--routine-border);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-modern {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
    }

    .btn-modern.btn-primary {
        background: var(--routine-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--routine-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--routine-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--routine-dark);
        transform: translateY(-1px);
        color: white;
    }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="edit-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-edit me-3"></i>Edit Routine
                </h1>
                <p class="mb-0 opacity-75">Update your routine details and settings</p>
            </div>
            <a href="{{ route('routines.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Routines
            </a>
        </div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb-modern">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('routines.index') }}" style="color: var(--routine-primary);">Routines</a></li>
            <li class="breadcrumb-item active">Edit {{ Str::limit($routine->title, 30) }}</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('routines.update', $routine->id) }}" method="POST" id="routine-form">
                @csrf
                @method('PUT')

                <div class="form-card">
                    <div class="form-card-body">
                        <!-- Basic Information -->
                        <div class="form-group-modern">
                            <label for="title" class="form-label-modern">
                                <i class="fas fa-heading" style="color: var(--routine-primary);"></i>
                                Routine Title
                                <span style="color: var(--routine-danger);">*</span>
                            </label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-control-modern @error('title') is-invalid @enderror"
                                   value="{{ old('title', $routine->title) }}"
                                   placeholder="Enter a descriptive title for your routine..."
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <label for="description" class="form-label-modern">
                                <i class="fas fa-align-left" style="color: var(--routine-primary);"></i>
                                Description
                            </label>
                            <textarea id="description"
                                      name="description"
                                      class="form-control-modern @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Add more details about this routine...">{{ old('description', $routine->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Frequency Selection -->
                        <div class="form-group-modern">
                            <label for="frequency" class="form-label-modern">
                                <i class="fas fa-calendar-alt" style="color: var(--routine-primary);"></i>
                                Frequency
                                <span style="color: var(--routine-danger);">*</span>
                            </label>
                            <select id="frequency"
                                    name="frequency"
                                    class="form-select-modern @error('frequency') is-invalid @enderror"
                                    required>
                                <option value="daily" {{ old('frequency', $routine->frequency) === 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ old('frequency', $routine->frequency) === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ old('frequency', $routine->frequency) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            </select>
                            @error('frequency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Days Selection (for daily frequency) -->
                        <div class="frequency-section" id="days" style="{{ old('frequency', $routine->frequency) === 'daily' ? 'display: block;' : 'display: none;' }}">
                            <div class="section-title">
                                <i class="fas fa-calendar-day" style="color: var(--routine-primary);"></i>
                                Select Days
                            </div>
                            <div class="checkbox-group">
                                @foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    <div class="checkbox-item">
                                        <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}" id="{{ $day }}"
                                               {{ in_array($day, json_decode($routine->days, true) ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Weeks Selection (for weekly frequency) -->
                        <div class="frequency-section" id="weeks" style="{{ old('frequency', $routine->frequency) === 'weekly' ? 'display: block;' : 'display: none;' }}">
                            <div class="section-title">
                                <i class="fas fa-calendar-week" style="color: var(--routine-primary);"></i>
                                Select Weeks (1-52)
                            </div>
                            <div style="max-height: 300px; overflow-y: auto;">
                                <div class="row">
                                    <div class="col-md-6">
                                        @for ($i = 1; $i <= 26; $i++)
                                            <div class="checkbox-item mb-2">
                                                <input class="form-check-input" type="checkbox" name="weeks[]" value="{{ $i }}" id="week{{ $i }}"
                                                       {{ in_array($i, json_decode($routine->weeks, true) ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="week{{ $i }}">Week {{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                    <div class="col-md-6">
                                        @for ($i = 27; $i <= 52; $i++)
                                            <div class="checkbox-item mb-2">
                                                <input class="form-check-input" type="checkbox" name="weeks[]" value="{{ $i }}" id="week{{ $i }}"
                                                       {{ in_array($i, json_decode($routine->weeks, true) ?? []) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="week{{ $i }}">Week {{ $i }}</label>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Months Selection (for monthly frequency) -->
                        <div class="frequency-section" id="months" style="{{ old('frequency', $routine->frequency) === 'monthly' ? 'display: block;' : 'display: none;' }}">
                            <div class="section-title">
                                <i class="fas fa-calendar-alt" style="color: var(--routine-primary);"></i>
                                Select Months
                            </div>
                            <div class="checkbox-group">
                                @foreach (['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $index => $month)
                                    <div class="checkbox-item">
                                        <input class="form-check-input" type="checkbox" name="months[]" value="{{ $index + 1 }}" id="month{{ $index + 1 }}"
                                               {{ in_array($index + 1, json_decode($routine->months, true) ?? []) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="month{{ $index + 1 }}">{{ $month }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Time Selection -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="start_time" class="form-label-modern">
                                        <i class="fas fa-clock" style="color: var(--routine-primary);"></i>
                                        Start Time
                                        <span style="color: var(--routine-danger);">*</span>
                                    </label>
                                    <input type="time"
                                           id="start_time"
                                           name="start_time"
                                           class="form-control-modern @error('start_time') is-invalid @enderror"
                                           value="{{ old('start_time', $routine->start_time) }}"
                                           required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="end_time" class="form-label-modern">
                                        <i class="fas fa-clock" style="color: var(--routine-primary);"></i>
                                        End Time
                                        <span style="color: var(--routine-danger);">*</span>
                                    </label>
                                    <input type="time"
                                           id="end_time"
                                           name="end_time"
                                           class="form-control-modern @error('end_time') is-invalid @enderror"
                                           value="{{ old('end_time', $routine->end_time) }}"
                                           required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('routines.index') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-save"></i>
                            Update Routine
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const frequencySelect = document.getElementById('frequency');
    const daysSection = document.getElementById('days');
    const weeksSection = document.getElementById('weeks');
    const monthsSection = document.getElementById('months');

    frequencySelect.addEventListener('change', function() {
        // Hide all sections first
        daysSection.style.display = 'none';
        weeksSection.style.display = 'none';
        monthsSection.style.display = 'none';

        // Show relevant section based on selection
        switch(this.value) {
            case 'daily':
                daysSection.style.display = 'block';
                break;
            case 'weekly':
                weeksSection.style.display = 'block';
                break;
            case 'monthly':
                monthsSection.style.display = 'block';
                break;
        }
    });

    // Initialize on page load
    frequencySelect.dispatchEvent(new Event('change'));
});
</script>
@endpush
