@extends('layouts.app')

@section('title', 'Research List - ' . $department)

@section('content')
<div class="d-flex align-items-center justify-content-between">
</div>
<hr/>
@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    {{ session()->get('success') }}
</div>
@endif
@if(session()->has('error'))
<div class="alert alert-danger" role="alert">
    {{ session()->get('error') }}
</div>
@endif
<form action="{{ route('research.search') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control" placeholder="Search for research..."
            value="{{ request('keyword') }}" required>
        <div class="input-group-append">
            <button class="btn btn-primary" type="submit">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </div>

    <!-- Filter Dropdown -->
    <div class="mt-2 d-flex align-items-center">
        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle d-flex align-items-center" type="button"
                id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter mr-1"></i> Year
            </button>
            <div class="dropdown-menu dropdown-menu-right small" aria-labelledby="filterDropdown">
                @for($year = 2019; $year <= 2025; $year += 2)
                    @php $nextYear = $year + 1; @endphp
                    <a class="dropdown-item px-3 py-2 text-sm"
                        href="{{ route('research.search', ['filter' => $year . '-' . $nextYear]) }}">
                        <i class="fas fa-calendar-alt mr-2"></i> {{ $year }}-{{ $nextYear }}
                    </a>
                @endfor
            </div>
        </div>
    </div>
</form>

<table class="table table-hover">
    <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Research Title</th>
            <th>Author</th>
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
            <td>{{ $research->date }}</td>
            <td>{{ $research->Research_Title }}</td>
            <td>{{ $research->Author }}</td>
            <td>{{ $research->Location }}</td>
            <td>{{ $research->subject_area }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#viewModal{{ $research->id }}">
                    View
                </button>

                <!-- Admin Options -->
                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('research.edit', $research->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('research.destroy', $research->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                @endif
            </td>
        </tr>
        <!-- View Modal -->
        <div class="modal fade" id="viewModal{{ $research->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $research->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel{{ $research->id }}">View Research</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{ asset(str_replace('public/', 'storage/', $research->file_path)) }}" style="width:100%; height:500px;" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <tr>
            <td colspan="7" class="text-center">No Research found</td>
        </tr>
        @endif
    </tbody>
</table>
@endsection

@section('scripts')
<script>
    function confirmDelete() {
        return confirm('Are you sure you want to delete this research?');
    }
</script>
@endsection
