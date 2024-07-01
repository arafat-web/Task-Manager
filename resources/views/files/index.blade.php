@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center bg-white shadow-sm p-3 rounded mb-4">
        <h2>Uploaded Files</h2>
        <a href="{{ route('files.create') }}" class="btn btn-primary">Upload File</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        @foreach($files as $file)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->name }}</h5>
                        <p class="card-text"><strong>Type:</strong> {{ $file->type }}</p>
                        <a href="{{ Storage::url($file->path) }}" target="_blank" class="btn btn-primary"> <i class="bi bi-download"></i> </a>
                        <a href="{{ route('files.edit', $file->id) }}" class="btn btn-warning"> <i class="bi bi-pencil-square"></i> </a>
                        <form action="{{ route('files.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"> <i class="bi bi-trash"></i> </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
