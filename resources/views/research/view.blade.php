@extends('layouts.app')

@section('title', 'View Research')

@section('content')
<div class="container">
    <h2>{{ $research->research_title }}</h2>
    <p><strong>Author:</strong> {{ $research->author }}</p>
    <p><strong>Date:</strong> {{ $research->date }}</p>
    <p><strong>Location:</strong> {{ $research->location }}</p>
    <p><strong>Subject Area:</strong> {{ $research->subject_area }}</p>

    <h4>Research File</h4>
    @if ($research->file_path && \Illuminate\Support\Facades\Storage::exists(str_replace('storage/', 'public/', $research->file_path)))
        <iframe src="{{ asset($research->file_path) }}" width="100%" height="600px"></iframe>
    @else
        <p>No file available.</p>
    @endif

    <a href="{{ route('research') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
