@extends('layouts.app')

@section('title', 'View Research')

@section('content')
<div class="container">
    <h2>{{ $research->Research_Title }}</h2>
    <p><strong>Author:</strong> {{ $research->Author }}</p>
    <p><strong>Date:</strong> {{ $research->date }}</p>
    <p><strong>Location:</strong> {{ $research->Location }}</p>
    <p><strong>Subject Area:</strong> {{ $research->subject_area }}</p>

    <h4>Research File</h4>
    @if ($research->file_path)
        <iframe src="{{ asset('storage/' . $research->file_path) }}" width="100%" height="600px"></iframe>
    @else
        <p>No file available.</p>
    @endif

    <a href="{{ route('research') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
