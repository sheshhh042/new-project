@extends('layouts.app')

@section('title', ' All Research List')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('research.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Research
            </a>
        @endif
    </div>

    <!-- Search & Filter Section -->

    <form id="searchForm" action="{{ route('research.search') }}" method="GET" class="mb-4 position-relative">
        <div class="input-group mb-3"> <!-- Added mb-3 for spacing below the input -->
            <input type="text" id="searchInput" name="keyword" class="form-control" placeholder="Search for research..."
                value="{{ request('keyword') }}" required autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>

        <!-- Search History Dropdown -->
        <div id="searchHistoryDropdown" class="dropdown-menu w-100"
            style="position: absolute; z-index: 1000; max-height: 90px; overflow-y: auto; display: none;">
            @foreach($searchHistories as $history)
                <div class="d-flex justify-content-between align-items-center px-3">
                    <a href="#" class="dropdown-item search-history-item" data-keyword="{{ $history->keyword }}">
                        {{ $history->keyword }}
                        <span class="badge badge-primary badge-pill float-right">{{ $history->count }}</span>
                    </a>
                    <button class="delete-history-btn border-0 p-0" data-id="{{ $history->id }}"
                        style="color: red; font-size: 18px; cursor: pointer;">
                        <i class="fas fa-times"></i> <!-- Font Awesome "X" icon -->
                    </button>
                </div>
            @endforeach
        </div>
    </form>

    <!-- Filter Dropdown -->
    <div class="mt-3 mb-4"> <!-- Added mt-3 for spacing above and mb-4 for spacing below -->
        <div class="dropdown">
            <button class="btn btn-secondary btn-sm dropdown-toggle d-flex align-items-center" type="button"
                id="filterDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter mr-1"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right small" aria-labelledby="filterDropdown">
                @for($year = 2010; $year <= 2025; $year += 2)
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
            <thead class="table-primary text-center"> <!-- Add the text-center class here -->
                <tr>
                    <th>Book ID</th>
                    <th>Research Title</th>
                    <th>Author</th>
                    <th>Date of Approval</th>
                    <th>Location</th>
                    <th>Subject Area</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center"> <!-- Add the text-center class here -->
                @forelse($researches as $research)
                    <tr>
                        <td>{{ $research->reference_id }}</td>
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
        function viewPDF(url, id) {
            document.getElementById('pdfViewer' + id).src = url;
        }
    </script>

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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('searchInput');
            const searchHistoryDropdown = document.getElementById('searchHistoryDropdown');

            // Show the dropdown when the search input is focused
            searchInput.addEventListener('focus', function () {
                searchHistoryDropdown.style.display = 'block';
            });

            // Hide the dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!searchInput.contains(event.target) && !searchHistoryDropdown.contains(event.target)) {
                    searchHistoryDropdown.style.display = 'none';
                }
            });

            // Handle click on search history items
            document.querySelectorAll('.search-history-item').forEach(item => {
                item.addEventListener('click', function (event) {
                    event.preventDefault();
                    const keyword = this.getAttribute('data-keyword');
                    searchInput.value = keyword; // Populate the search bar with the clicked keyword
                    document.getElementById('searchForm').submit(); // Submit the form
                });
            });

            // Handle delete button click
            document.querySelectorAll('.delete-history-btn').forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const id = this.getAttribute('data-id');

                    if (confirm('Are you sure you want to delete this search history?')) {
                        fetch(`/search-history/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json',
                            },
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to delete search history.');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert(data.success);
                                    location.reload(); // Reload the page to update the search history
                                } else {
                                    alert('Failed to delete search history.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while deleting the search history.');
                            });
                    }
                });
            });
        });
    </script>
@endsection