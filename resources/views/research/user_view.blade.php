@extends('layouts.app')

@section('title', 'Research List')

@section('content')
<div class="d-flex align-items-center justify-content-between">
</div>
<hr/>
@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    {{ session()->get('success') }}
</div>
@endif
<table class="table table-hover">
    <thead class="table-primary">
    <tr>
                <th>#</th>
                <th>Research Title</th>
                <th>Author</th>
                <th>Date</th>
                <th>Location</th>
                <th>Subject Area</th>
                <th>Action</th>
            </tr>
    </thead>
    <tbody>
        @if ($researches->count() > 0)
        @foreach($researches as $research)
        <tr>
            <td>{{ $loop->iteration }}</td>       
            <td>{{ $research->Research_Title }}</td>
            <td>{{ $research->Author }}</td>
            <td>{{ $research->date }}</td>
            <td>{{ $research->Location }}</td>
            <td>{{ $research->subject_area }}</td>
            <td>
            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#viewFile{{ $research->id }}">
                                View
                            </button>
            </td>
        </tr>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No Research found</td>
        </tr>
        @endif
    </tbody>
</table>


<!-- View Research Modal -->
<<div class="modal fade" id="viewFile{{ $research->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Research File: {{ $research->research_title }}</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <iframe src="{{ asset('storage/' . $research->file_path) }}" width="100%" height="500px"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection