@extends('layouts.app')

@section('title', 'Create Research')

@section('content')
<h1 class="mb-0">Add Research</h1>
<hr/>
<div class="container">
    @if(session()->has('error'))
    <div class="alert alert-danger" role="alert">
        {{ session()->get('error') }}
    </div>
    @endif
    <form action="{{ route('research.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" id="date" required>
        </div>
        <div class="mb-3">
            <label for="research_title" class="form-label">Research Title</label>
            <input type="text" name="research_title" class="form-control" id="research_title" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" name="author" class="form-control" id="author" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control" id="location" required>
        </div>
        <div class="mb-3">
            <label for="subject_area" class="form-label">Subject Area</label>
            <select name="subject_area" class="form-control" id="subject_area" required>
                @foreach($departments as $department)
                    <option value="{{ $department }}">{{ $department }}</option>
                @endforeach
            </select>
        </div>

        <!-- File Upload Field -->
        <div class="mb-3">
            <label for="research_file" class="form-label">Upload Research File (PDF only)</label>
            <input type="file" name="research_file" class="form-control" id="research_file" accept=".pdf" required>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
@endsection
