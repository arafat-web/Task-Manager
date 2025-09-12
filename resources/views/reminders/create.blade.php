@extends('layouts.app')

@section('title', 'Create Reminder')

@section('content')
<style>
    :root {
        --reminder-primary: #667eea;
        --reminder-secondary: #764ba2;
        --reminder-success: #10b981;
        --reminder-warning: #f59e0b;
        --reminder-danger: #ef4444;
        --reminder-info: #3b82f6;
        --reminder-light: #f8fafc;
        --reminder-dark: #1e293b;
        --reminder-gray: #64748b;
        --reminder-border: #e2e8f0;
        --reminder-shadow: rgba(0, 0, 0, 0.1);
        --reminder-shadow-lg: rgba(0, 0, 0, 0.15);
    }

    .create-header {
        background: linear-gradient(135deg, var(--reminder-primary) 0%, var(--reminder-secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px var(--reminder-shadow-lg);
    }

    .form-card {
        background: white;
        border-radius: 12px;
        border: 1px solid var(--reminder-border);
        box-shadow: 0 4px 6px -1px var(--reminder-shadow);
        overflow: hidden;
    }

    .form-card-body {
        padding: 2rem;
    }

    .breadcrumb-modern {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 4px var(--reminder-shadow);
        border: 1px solid var(--reminder-border);
    }

    .breadcrumb-modern .breadcrumb {
        margin: 0;
        background: none;
        padding: 0;
    }

    .breadcrumb-modern .breadcrumb-item + .breadcrumb-item::before {
        content: "›";
        color: var(--reminder-gray);
        font-weight: 600;
    }

    .form-group-modern {
        margin-bottom: 1.5rem;
    }

    .form-label-modern {
        font-weight: 600;
        color: var(--reminder-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control-modern {
        border: 1px solid var(--reminder-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        background: white;
        width: 100%;
    }

    .form-control-modern:focus {
        border-color: var(--reminder-primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .form-select-modern {
        border: 1px solid var(--reminder-border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        background: white;
        transition: all 0.2s ease;
        width: 100%;
    }

    .form-select-modern:focus {
        border-color: var(--reminder-primary);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .recurring-section {
        background: var(--reminder-light);
        border: 1px solid var(--reminder-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-weight: 600;
        color: var(--reminder-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-buttons {
        background: var(--reminder-light);
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--reminder-border);
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
        background: var(--reminder-primary);
        color: white;
    }

    .btn-modern.btn-primary:hover {
        background: var(--reminder-secondary);
        transform: translateY(-1px);
        color: white;
    }

    .btn-modern.btn-secondary {
        background: var(--reminder-gray);
        color: white;
    }

    .btn-modern.btn-secondary:hover {
        background: var(--reminder-dark);
        transform: translateY(-1px);
        color: white;
    }

    .priority-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.75rem;
    }

    .priority-option {
        position: relative;
    }

    .priority-radio {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
    }

    .priority-label {
        display: block;
        padding: 0.75rem;
        border: 2px solid var(--reminder-border);
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .priority-radio:checked + .priority-label {
        border-color: var(--reminder-primary);
        background: var(--reminder-primary);
        color: white;
    }

    .priority-label.urgent { border-color: var(--reminder-danger); color: var(--reminder-danger); }
    .priority-label.high { border-color: #fd7e14; color: #fd7e14; }
    .priority-label.medium { border-color: var(--reminder-warning); color: var(--reminder-warning); }
    .priority-label.low { border-color: var(--reminder-gray); color: var(--reminder-gray); }

    .priority-radio:checked + .priority-label.urgent { background: var(--reminder-danger); }
    .priority-radio:checked + .priority-label.high { background: #fd7e14; }
    .priority-radio:checked + .priority-label.medium { background: var(--reminder-warning); }
    .priority-radio:checked + .priority-label.low { background: var(--reminder-gray); }
</style>

<div class="container-fluid">
    <!-- Header -->
    <div class="create-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">
                    <i class="fas fa-plus-circle me-3"></i>Create New Reminder
                </h1>
                <p class="mb-0 opacity-75">Set up a new reminder to stay on track with your tasks</p>
            </div>
            <a href="{{ route('reminders.index') }}" class="btn btn-light btn-lg">
                <i class="fas fa-arrow-left me-2"></i>Back to Reminders
            </a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('reminders.store') }}" method="POST" id="reminder-form">
                @csrf

                <div class="form-card">
                    <div class="form-card-body">
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group-modern">
                                    <label for="title" class="form-label-modern">
                                        <i class="fas fa-heading" style="color: var(--reminder-primary);"></i>
                                        Reminder Title
                                        <span style="color: var(--reminder-danger);">*</span>
                                    </label>
                                    <input type="text"
                                           id="title"
                                           name="title"
                                           class="form-control-modern @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}"
                                           placeholder="Enter a descriptive title for your reminder..."
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group-modern">
                                    <label for="description" class="form-label-modern">
                                        <i class="fas fa-align-left" style="color: var(--reminder-primary);"></i>
                                        Description
                                    </label>
                                    <textarea id="description"
                                              name="description"
                                              class="form-control-modern @error('description') is-invalid @enderror"
                                              rows="4"
                                              placeholder="Add more details about this reminder...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Date and Time -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="date" class="form-label-modern">
                                        <i class="fas fa-calendar" style="color: var(--reminder-primary);"></i>
                                        Date
                                    </label>
                                    <input type="date"
                                           id="date"
                                           name="date"
                                           class="form-control-modern @error('date') is-invalid @enderror"
                                           value="{{ old('date') }}">
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="time" class="form-label-modern">
                                        <i class="fas fa-clock" style="color: var(--reminder-primary);"></i>
                                        Time
                                    </label>
                                    <input type="time"
                                           id="time"
                                           name="time"
                                           class="form-control-modern @error('time') is-invalid @enderror"
                                           value="{{ old('time') }}">
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Priority Selection -->
                        <div class="form-group-modern">
                            <label class="form-label-modern">
                                <i class="fas fa-exclamation-triangle" style="color: var(--reminder-primary);"></i>
                                Priority Level
                                <span style="color: var(--reminder-danger);">*</span>
                            </label>
                            <div class="priority-options">
                                @foreach($priorities as $key => $label)
                                    <div class="priority-option">
                                        <input type="radio"
                                               id="priority_{{ $key }}"
                                               name="priority"
                                               value="{{ $key }}"
                                               class="priority-radio"
                                               {{ old('priority') === $key ? 'checked' : '' }}
                                               required>
                                        <label for="priority_{{ $key }}" class="priority-label {{ $key }}">
                                            {{ $label }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('priority')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category and Location -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="category" class="form-label-modern">
                                        <i class="fas fa-folder" style="color: var(--reminder-primary);"></i>
                                        Category
                                    </label>
                                    <input type="text"
                                           id="category"
                                           name="category"
                                           class="form-control-modern @error('category') is-invalid @enderror"
                                           value="{{ old('category') }}"
                                           list="categories"
                                           placeholder="Work, Personal, Meeting...">
                                    <datalist id="categories">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">
                                        @endforeach
                                    </datalist>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group-modern">
                                    <label for="location" class="form-label-modern">
                                        <i class="fas fa-map-marker-alt" style="color: var(--reminder-primary);"></i>
                                        Location
                                    </label>
                                    <input type="text"
                                           id="location"
                                           name="location"
                                           class="form-control-modern @error('location') is-invalid @enderror"
                                           value="{{ old('location') }}"
                                           placeholder="Meeting room, address...">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div class="form-group-modern">
                            <label for="tags" class="form-label-modern">
                                <i class="fas fa-tags" style="color: var(--reminder-primary);"></i>
                                Tags
                            </label>
                            <input type="text"
                                   id="tags"
                                   name="tags"
                                   class="form-control-modern @error('tags') is-invalid @enderror"
                                   value="{{ old('tags') }}"
                                   placeholder="urgent, important, meeting (comma separated)">
                            <small class="form-text text-muted mt-1">Separate multiple tags with commas</small>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Recurring Options -->
                        <div class="recurring-section">
                            <div class="section-title">
                                <i class="fas fa-repeat" style="color: var(--reminder-primary);"></i>
                                Recurring Options
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox"
                                       id="is_recurring"
                                       name="is_recurring"
                                       value="1"
                                       class="form-check-input"
                                       {{ old('is_recurring') ? 'checked' : '' }}>
                                <label for="is_recurring" class="form-check-label fw-semibold">
                                    Make this reminder recurring
                                </label>
                            </div>

                            <div id="recurring-options" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="recurrence_type" class="form-label-modern">
                                                <i class="fas fa-calendar-alt" style="color: var(--reminder-primary);"></i>
                                                Repeat Every
                                            </label>
                                            <select id="recurrence_type"
                                                    name="recurrence_type"
                                                    class="form-select-modern @error('recurrence_type') is-invalid @enderror">
                                                <option value="daily" {{ old('recurrence_type') === 'daily' ? 'selected' : '' }}>Daily</option>
                                                <option value="weekly" {{ old('recurrence_type') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                                                <option value="monthly" {{ old('recurrence_type') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                                <option value="yearly" {{ old('recurrence_type') === 'yearly' ? 'selected' : '' }}>Yearly</option>
                                            </select>
                                            @error('recurrence_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-modern">
                                            <label for="recurrence_interval" class="form-label-modern">
                                                <i class="fas fa-hashtag" style="color: var(--reminder-primary);"></i>
                                                Interval
                                            </label>
                                            <div class="input-group">
                                                <input type="number"
                                                       id="recurrence_interval"
                                                       name="recurrence_interval"
                                                       class="form-control-modern @error('recurrence_interval') is-invalid @enderror"
                                                       value="{{ old('recurrence_interval', 1) }}"
                                                       min="1"
                                                       max="365">
                                                <span class="input-group-text" id="interval-unit" style="background: var(--reminder-light); border-color: var(--reminder-border);">day(s)</span>
                                            </div>
                                            @error('recurrence_interval')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        <a href="{{ route('reminders.index') }}" class="btn-modern btn-secondary">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button type="submit" class="btn-modern btn-primary">
                            <i class="fas fa-save"></i>
                            Create Reminder
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
    const recurringCheckbox = document.getElementById('is_recurring');
    const recurringOptions = document.getElementById('recurring-options');
    const recurrenceType = document.getElementById('recurrence_type');
    const intervalUnit = document.getElementById('interval-unit');

    // Toggle recurring options
    recurringCheckbox.addEventListener('change', function() {
        recurringOptions.style.display = this.checked ? 'block' : 'none';
    });

    // Update interval unit text based on recurrence type
    recurrenceType.addEventListener('change', function() {
        const type = this.value;
        let unit = 'day(s)';

        switch(type) {
            case 'daily':
                unit = 'day(s)';
                break;
            case 'weekly':
                unit = 'week(s)';
                break;
            case 'monthly':
                unit = 'month(s)';
                break;
            case 'yearly':
                unit = 'year(s)';
                break;
        }

        intervalUnit.textContent = unit;
    });

    // Show recurring options if checkbox is checked on page load
    if (recurringCheckbox.checked) {
        recurringOptions.style.display = 'block';
    }

    // Update interval unit on page load
    recurrenceType.dispatchEvent(new Event('change'));
});
</script>
@endpush
