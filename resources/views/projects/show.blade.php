@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Project Details</h2>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $project->name }}</h5>
                <p class="card-text">{{ $project->description }}</p>
                <p class="card-text"><strong>Start Date:</strong> {{ $project->start_date }}</p>
                <p class="card-text"><strong>End Date:</strong> {{ $project->end_date }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($project->status) }}</p>
                <p class="card-text"><strong>Budget:</strong> ${{ $project->budget }}</p>

                <h5 class="mt-4">Files</h5>
                <ul class="list-group">
                    @foreach($project->files as $file)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ basename($file->file_path) }}</a>
                            <form action="{{ route('projects.files.destroy', $file->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this file?')">Delete</button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('projects.files.store', $project->id) }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label for="file" class="form-label">Add File</label>
                        <input type="file" name="file" id="file" class="form-control" required>
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Upload File</button>
                </form>

                <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Projects</a>
            </div>
        </div>
    </div>
@endsection
