@extends('layouts.app')

@section('title', '')

@section('content')

    <hr />
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="shadow-lg rounded p-5" style="background-color: #fff; width: 90%;"> <!-- Wide floating box -->
            <h2 class="text-center mb-4">Edit Research</h2>

            <form action="{{ route('research.update', $research->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Reference ID -->
                <div class="mb-3">
                    <label for="reference_id" class="form-label">Reference ID</label>
                    <input type="text" name="reference_id" class="form-control" id="reference_id"
                        value="{{ old('reference_id', $research->reference_id) }}" placeholder="Optional">
                    @error('reference_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date -->
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="month" name="date" class="form-control" id="date"
                        value="{{ old('date', $research->date) }}" required>
                    @error('date')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Research Title -->
                <div class="mb-3">
                    <label for="research_title" class="form-label">Research Title</label>
                    <input type="text" name="research_title" class="form-control" id="research_title"
                        value="{{ old('research_title', $research->Research_Title) }}" required>
                    @error('research_title')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Author -->
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" id="author"
                        value="{{ old('author', $research->Author) }}" required>
                    @error('author')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Location -->
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" id="location"
                        value="{{ old('location', $research->Location) }}" required>
                    @error('location')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Subject Area -->
                <div class="mb-3">
                    <label for="subject_area" class="form-label">Subject Area</label>
                    <select name="subject_area" class="form-control" id="subject_area" required>
                        <option disabled selected>Choose Subject Area</option>
                        @foreach(['Comptech', 'Electronics', 'Education', 'BSEd-English', 'BSEd-Filipino', 'BSEd-Mathematics', 'BSEd-Social Studies', 'Tourism', 'Hospitality Management'] as $department)
                            <option value="{{ $department }}"
                                {{ old('subject_area', $research->subject_area) == $department ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_area')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Keywords -->
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input type="text" name="keywords" class="form-control" id="keywords"
                        value="{{ old('keywords', $research->keywords) }}"
                        placeholder="Enter up to 5 keywords, separated by commas" required>
                    @error('keywords')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Research File -->
                <div class="mb-3">
                    <label for="research_file" class="form-label">Upload Research File (PDF only)</label>
                    @if($research->file_path)
                        <p>Current File: <a href="{{ asset('storage/' . $research->file_path) }}" target="_blank">View File</a></p>
                    @else
                        <p>No file uploaded.</p>
                    @endif
                    <input type="file" name="research_file" class="form-control" id="research_file" accept=".pdf">
                    @error('research_file')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Update Research</button>
                </div>
            </form>
        </div>
    </div>
@endsection