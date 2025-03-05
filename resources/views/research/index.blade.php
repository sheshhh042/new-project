@extends('layouts.app')

@section('title', 'Research List')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">

    
    @if(Auth::user()->role == 'admin')
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createResearchModal">
            <i class="fas fa-plus"></i> Add Research
        </button>
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
                            <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editResearchModal{{ $research->id }}">
                                Edit
                            </button>
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

                <!-- Edit Research Modal -->
                <div class="modal fade" id="editResearchModal{{ $research->id }}" tabindex="-1" aria-labelledby="editResearchModalLabel{{ $research->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editResearchModalLabel{{ $research->id }}">Edit Research</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="{{ route('research.update', $research->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" name="date" class="form-control" id="date" value="{{ $research->date }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="research_title">Research Title</label>
                                        <input type="text" name="research_title" class="form-control" id="research_title" value="{{ $research->Research_Title }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="author">Author</label>
                                        <input type="text" name="author" class="form-control" id="author" value="{{ $research->Author }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" name="location" class="form-control" id="location" value="{{ $research->Location }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_area">Subject Area</label>
                                        <select name="subject_area" class="form-control" id="subject_area" required>
                                            <option value="" disabled>Select Subject Area</option>
                                            <optgroup label="Comptech">
                                                <option value="Comptech" {{ $research->subject_area == 'Comptech' ? 'selected' : '' }}>Comptech</option>
                                                <option value="Electronics" {{ $research->subject_area == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                            </optgroup>                          
                                            <optgroup label="Education">
                                                <option value="Education" {{ $research->subject_area == 'Education' ? 'selected' : '' }}>Education</option>
                                                <option value="(BSEd)-English" {{ $research->subject_area == '(BSEd)-English' ? 'selected' : '' }}>(BSEd)-English</option>
                                                <option value="(BSEd)-Filipino" {{ $research->subject_area == '(BSEd)-Filipino' ? 'selected' : '' }}>(BSEd)-Filipino</option>
                                                <option value="(BSEd)-Mathematics" {{ $research->subject_area == '(BSEd)-Mathematics' ? 'selected' : '' }}>(BSEd)-Mathematics</option>
                                                <option value="(BSEd)-Social Studies" {{ $research->subject_area == '(BSEd)-Social Studies' ? 'selected' : '' }}>(BSEd)-Social Studies</option>
                                            </optgroup>
                                            <optgroup label="Tourism">
                                                <option value="Tourism" {{ $research->subject_area == 'Tourism' ? 'selected' : '' }}>Tourism</option>
                                                <option value="Hospitality Management" {{ $research->subject_area == 'Hospitality Management' ? 'selected' : '' }}>Hospitality Management</option>
                                            </optgroup>                        
                                        </select>
                                    </div>
                                    <div class="form-group">
                                <label for="file">Upload File</label>
                                <input type="file" name="file" class="form-control-file" id="file" required>
                            </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Update Research</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No research found</td>
                </tr>
                @endif
            @endforelse
        </tbody>
    </table>
</div>

<!-- Create Research Modal -->
<div class="modal fade" id="createResearchModal" tabindex="-1" aria-labelledby="createResearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createResearchModalLabel">Add Research</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('research.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="research_title">Research Title</label>
                        <input type="text" name="research_title" class="form-control" id="research_title" required>
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" name="author" class="form-control" id="author" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" name="location" class="form-control" id="location" required>
                    </div>
                    <div class="form-group">
                        <label for="subject_area">Subject Area</label>
                        <select name="subject_area" class="form-control" id="subject_area" required>
                            <option value="" disabled selected>Select Subject Area</option>
                            <optgroup label="Comptech">
                                <option value="Comptech">Comptech</option>
                                <option value="Electronics">Electronics</option>
                            </optgroup>                          
                            <optgroup label="Education">
                                <option value="Education">Education</option>
                                <option value="(BSEd)-English">(BSEd)-English</option>
                                <option value="(BSEd)-Filipino">(BSEd)-Filipino</option>
                                <option value="(BSEd)-Mathematics">(BSEd)-Mathematics</option>
                                <option value="(BSEd)-Social Studies">(BSEd)-Social Studies</option>
                            </optgroup>
                            <optgroup label="Tourism">
                                <option value="Tourism">Tourism</option>
                                <option value="Hospitality Management">Hospitality Management</option>
                            </optgroup>                        
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="file">Upload File</label>
                        <input type="file" name="file" class="form-control-file" id="file" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Research</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Research Modal -->
<div class="modal fade" id="viewFile{{ $research->id }}" tabindex="-1" role="dialog">
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