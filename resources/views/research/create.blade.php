@extends('layouts.app')

@section('title', '')

@section('content')

    <hr />
    <!-- Adjusted box for a wider appearance -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="shadow rounded p-4" style="background-color: #fff; width: 75%;"> <!-- Increased width -->
            @if(session()->has('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session()->get('error') }}
                </div>
            @endif
            <h2 class="text-center mb-4">Add Research</h2>
            <form action="{{ route('research.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Book ID -->
                <div class="mb-3">
                    <label for="reference_id" class="form-label">Book ID</label>
                    <input type="text" name="reference_id" class="form-control" id="reference_id" placeholder="Optional">
                </div>

                <!-- Date -->
                <div class="mb-3">
                    <label for="date" class="form-label">Date</label>
                    <input type="month" name="date" class="form-control" id="date" required>
                </div>

                <!-- Research Title -->
                <div class="mb-3">
                    <label for="research_title" class="form-label">Research Title</label>
                    <input type="text" name="research_title" class="form-control" id="research_title" required>
                </div>

                <!-- Author -->
                <div class="mb-3">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" id="author" required>
                </div>

                <!-- Location -->
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" id="location" required>
                </div>

                <!-- Subject Area -->
                <div class="mb-3">
                    <label for="subject_area" class="form-label">Subject Area</label>
                    <select name="subject_area" class="form-control" id="subject_area" required>
                        @foreach($departments as $department)
                            <option value="{{ $department }}">{{ $department }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Keywords -->
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords</label>
                    <input type="text" name="keywords" class="form-control" id="keywords"
                        placeholder="Enter up to 5 keywords, separated by commas" required>
                </div>

                <!-- Upload File -->
                <div class="mb-3">
                    <label for="research_file" class="form-label">Upload Research File (PDF only)</label>
                    <input type="file" name="research_file" class="form-control" id="research_file" accept=".pdf" required>
                </div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection