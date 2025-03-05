@extends('layouts.app')

@section('title', 'Research List')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    @if(Auth::user()->role == 'admin')
        <a href="{{ route('research.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Research
        </a>
    @endif
</div>

<!-- Search & Filter Section -->
<form action="{{ route('research.search') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Search for research..." required>
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>
    <div class="mt-2">
        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i> Filter by Year
            </button>
            <div class="dropdown-menu" aria-labelledby="filterDropdown">
                @foreach(range(date('Y') - 6, date('Y')) as $year)
                    <a class="dropdown-item" href="{{ route('research.search', ['filter' => $year]) }}">{{ $year }}</a>
                @endforeach
            </div>
        </div>
    </div>
</form>

<!-- Success Message -->
@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session()->get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Research Table -->
<div class="table-responsive">
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
                        <a href="{{ asset(str_replace('public/', 'storage/', $research->file_path)) }}" class="btn btn-primary btn-sm" target="_blank">
                            View
                        </a>
                        @if(Auth::user()->role == 'admin')
                            <a href="{{ route('research.edit', $research->id) }}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <form action="{{ route('research.destroy', $research->id) }}" method="POST" class="d-inline-block delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No research found</td>
                </tr>
                @endif
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    // Delete Confirmation with Notification
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (confirm('Are you sure you want to delete this research?')) {
                    this.submit();
                    alert('Research deleted successfully!');
                }
            });
        });
    });
</script>
@endsection
