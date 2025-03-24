@extends('layouts.app')

@section('title', 'Recently Deleted Research')

@section('content')
    <!-- Success Message -->
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="table-primary text-center">
                <tr>
                    <th>Book ID</th>
                    <th>Research Title</th>
                    <th>Author</th>
                    <th>Date of Approval</th>
                    <th>Location</th>
                    <th>Subject Area</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($researches as $research)
                    <tr>
                        <td>{{ $research->reference_id }}</td>
                        <td>{{ $research->Research_Title }}</td>
                        <td>{{ $research->Author }}</td>
                        <td>{{ $research->date }}</td>
                        <td>{{ $research->Location }}</td>
                        <td>{{ $research->subject_area }}</td>
                        <td>
                            <!-- Restore Button -->
                            <form action="{{ route('research.restore', $research->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-undo"></i> Restore
                                </button>
                            </form>

                            <!-- Permanent Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $research->id }}">
                                <i class="fas fa-trash"></i> Delete Permanently
                            </button>
                        </td>
                    </tr>

                    <!-- Permanent Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $research->id }}" tabindex="-1"
                        aria-labelledby="deleteModalLabel{{ $research->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $research->id }}">
                                        <i class="fas fa-exclamation-triangle"></i> Confirm Permanent Delete
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to permanently delete the research titled
                                    <strong>{{ $research->research_title }}</strong>? This action cannot be undone.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('research.permanentDelete', $research->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Yes, Delete Permanently
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No recently deleted research found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
