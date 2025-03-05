@extends('layouts.app')

@section('title', 'Edit Research')

@section('content')
<h1 class="mb-0">Edit Research</h1>
<hr/>
<div class="container">
    <form action="{{ route('research.update', $research->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" id="date" value="{{ $research->date }}" required>
            @error('date')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="Research_Title" class="form-label">Research Title</label>
            <input type="text" name="Research_Title" class="form-control" id="Research_Title" value="{{ $research->Research_Title }}" required>
            @error('Research_Title')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="Author" class="form-label">Author</label>
            <input type="text" name="Author" class="form-control" id="Author" value="{{ $research->Author }}" required>
            @error('Author')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="Location" class="form-label">Location</label>
            <input type="text" name="Location" class="form-control" id="Location" value="{{ $research->Location }}" required>
            @error('Location')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="subject_area" class="form-label">Subject Area</label>
            <select name="subject_area" class="form-control" id="subject_area" required>
                <option value="Comptech">Comptech</option>
                <option value="Electronics">Electronics</option>
                <option value="Education">Education</option>
                <option value="BSEd-English">(BSEd)-English</option>
                <option value="BSEd-Filipino">(BSEd)-Filipino</option>
                <option value="BSEd-Mathematics">(BSEd)-Mathematics</option>
                <option value="BSEd-Social Studies">(BSEd)-Social Studies</option>
                <option value="Tourism">Tourism</option>
                <option value="Hospitality Management">Hospitality Management</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="file_path" class="form-label">File Path</label>
            <input type="text" name="file_path" class="form-control" id="file_path" value="{{ $research->file_path }}" required>
            @error('file_path')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">Update Research</button>
        </div>
    </form>
</div>
@endsection