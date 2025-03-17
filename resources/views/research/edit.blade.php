@extends('layouts.app')

@section('title', 'Edit Research')

@section('content')

    <hr />
    <div class="container">
        <form action="{{ route('research.update', $research->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="reference_id" class="form-label">Reference ID</label>
                <input type="text" name="reference_id" class="form-control" id="reference_id"
                    value="{{ $research->reference_id }}" required>
                @error('reference_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="month" name="date" class="form-control" id="date" required>
            </div>

            <div class="mb-3">
                <label for="research_title" class="form-label">Research Title</label>
                <input type="text" name="research_title" class="form-control" id="research_title"
                    value="{{ $research->research_title }}" required>
                <input type="hidden" name="old_research_title" value="{{ $research->research_title }}">
                @error('research_title')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="author" class="form-label">Author</label>
                <input type="text" name="author" class="form-control" id="author" value="{{ $research->author }}" required>
                <input type="hidden" name="old_author" value="{{ $research->author }}">
                @error('author')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" id="location" value="{{ $research->location }}"
                    required>
                @error('location')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="subject_area" class="form-label">Subject Area</label>
                <select name="subject_area" class="form-control" id="subject_area" required>
                    <option value="Comptech" {{ $research->subject_area == 'Comptech' ? 'selected' : '' }}>Comptech</option>
                    <option value="Electronics" {{ $research->subject_area == 'Electronics' ? 'selected' : '' }}>Electronics
                    </option>
                    <option value="Education" {{ $research->subject_area == 'Education' ? 'selected' : '' }}>Education
                    </option>
                    <option value="BSEd-English" {{ $research->subject_area == 'BSEd-English' ? 'selected' : '' }}>
                        BSEd-English</option>
                    <option value="BSEd-Filipino" {{ $research->subject_area == 'BSEd-Filipino' ? 'selected' : '' }}>
                        BSEd-Filipino</option>
                    <option value="BSEd-Mathematics" {{ $research->subject_area == 'BSEd-Mathematics' ? 'selected' : '' }}>
                        BSEd-Mathematics</option>
                    <option value="BSEd-Social Studies" {{ $research->subject_area == 'BSEd-Social Studies' ? 'selected' : '' }}>BSEd-Social Studies</option>
                    <option value="Tourism" {{ $research->subject_area == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                    <option value="Hospitality Management" {{ $research->subject_area == 'Hospitality Management' ? 'selected' : '' }}>Hospitality Management</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords</label>
                <input type="text" name="keywords" class="form-control" id="keywords"
                    placeholder="Enter up to 5 keywords, separated by commas" required>
            </div>

            <div class="mb-3">
                <label for="research_file" class="form-label">Upload Research File (PDF only)</label>
                <input type="file" name="research_file" class="form-control" id="research_file" accept=".pdf">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Research</button>
            </div>
        </form>
    </div>
@endsection