@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 shadow-sm p-3 rounded bg-white">Edit File</h2>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ route('files.update', $file->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">File Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ $file->name }}"
                            required>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Choose File (Leave blank to keep current file)</label>
                        <input type="file" name="file" id="file" class="form-control">
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">File Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="project" {{ $file->type == 'project' ? 'selected' : '' }}>Project</option>
                            <option value="docs" {{ $file->type == 'docs' ? 'selected' : '' }}>Docs</option>
                            <option value="txt" {{ $file->type == 'txt' ? 'selected' : '' }}>Text</option>
                            <option value="code" {{ $file->type == 'code' ? 'selected' : '' }}>Code</option>
                            <option value="image" {{ $file->type == 'image' ? 'selected' : '' }}>Image</option>
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Update File</button>
                </form>
            </div>
        </div>
    </div>
@endsection
