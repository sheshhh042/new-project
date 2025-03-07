@extends('layouts.app')

@section('title', 'Research List - ' . $department)

@section('content')

    <hr />
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
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#viewPDFModal{{ $research->id }}"
                                onclick="viewPDF('{{ asset('storage/' . $research->file_path) }}', {{ $research->id }})">
                                <i class="fas fa-eye"></i> View
                            </button>

                            <!-- Admin Options -->
                            @if(Auth::user()->role == 'admin')
                                <a href="{{ route('research.edit', $research->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
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
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
                    <!-- View PDF Modal -->
                    <div class="modal fade" id="viewPDFModal{{ $research->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="viewPDFModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewPDFModalLabel">View Research File</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <iframe id="pdfViewer{{ $research->id }}" src="" width="100%" height="600px"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-center">No research found</td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        function viewPDF(url, id) {
            document.getElementById('pdfViewer' + id).src = url;
        }
    </script>
@endsection
















