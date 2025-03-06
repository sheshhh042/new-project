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
                @forelse($researches as $research)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $research->Research_Title }}</td>
                        <td>{{ $research->Author }}</td>
                        <td>{{ $research->date }}</td>
                        <td>{{ $research->Location }}</td>
                        <td>{{ $research->subject_area }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#viewModal{{ $research->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>

                            <!-- Admin Options -->
                            @if(Auth::user()->role == 'admin')
                                <!-- Edit Button with Icon -->
                                <a href="{{ route('research.edit', $research->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>

                                <!-- Delete Button with Icon (Triggers Modal) -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $research->id }}">
                                    <i class="fas fa-trash"></i> Delete
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $research->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $research->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $research->id }}">
                                                    <i class="fas fa-exclamation-triangle"></i> Confirm Delete
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete this research:
                                                <strong>{{ $research->Research_Title }}</strong>?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('research.destroy', $research->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif
                        </td>
                    </tr>
                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal{{ $research->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $research->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewModalLabel{{ $research->id }}">View Research</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <iframe src="{{ asset(str_replace('public/', 'storage/', $research->file_path)) }}" style="width:100%; height:500px;" frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No research found</td>
                    </tr>
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
