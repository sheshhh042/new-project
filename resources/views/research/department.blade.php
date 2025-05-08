@extends('layouts.app')

@section('title', 'Research List - ' . $department)

@section('content')

    <hr />
    <!-- Search & Filter Section -->
    <form id="searchForm" action="{{ route('research.search') }}" method="GET" class="mb-4 position-relative">
        <div class="input-group">
            <input type="text" id="searchInput" name="keyword" class="form-control" placeholder="Search for research..."
                value="{{ request('keyword') }}" autocomplete="off" aria-autocomplete="list">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>
        <div id="suggestionsDropdown" class="list-group shadow"
            style="display:none; position:absolute; z-index:1000; width:100%;"></div>
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

    @if(session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('success') }}
        </div>
    @endif


    <div class="table-responsive">
        <table class="table table-hover text-center">
            <thead class="table-primary">
                <tr>
                    <!-- <th>Book ID</th> -->
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
                            <!-- <td>{{ $research->reference_id }}</td> -->
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
                        <td colspan="8" class="text-center">No research found</td> <!-- Update colspan to 8 -->
                    </tr>
                @endif
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
    document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('searchInput');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');
    let timeoutId;

    // Show suggestions dropdown
    function showDropdown() {
        suggestionsDropdown.style.display = 'block';
    }

    // Hide suggestions dropdown
    function hideDropdown() {
        suggestionsDropdown.style.display = 'none';
    }

    // Fetch suggestions from server
    async function fetchSuggestions(query) {
        if (!query || query.length < 2) {
            hideDropdown();
            return;
        }

        try {
            const response = await fetch(`/research/suggestions?q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();
            
            if (suggestions.length > 0) {
                renderSuggestions(suggestions);
                showDropdown();
            } else {
                hideDropdown();
            }
        } catch (error) {
            console.error('Error:', error);
            hideDropdown();
        }
    }

    // Render suggestions in dropdown
    function renderSuggestions(suggestions) {
        suggestionsDropdown.innerHTML = '';
        
        suggestions.forEach(suggestion => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action';
            item.textContent = suggestion;
            
            item.addEventListener('click', (e) => {
                e.preventDefault();
                searchInput.value = suggestion;
                hideDropdown();
                document.getElementById('searchForm').submit();
            });
            
            suggestionsDropdown.appendChild(item);
        });
    }

    // Event listeners
    searchInput.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fetchSuggestions(this.value), 300);
    });

    searchInput.addEventListener('focus', function() {
        if (this.value.length >= 2) {
            fetchSuggestions(this.value);
        }
    });

    // Hide dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
            hideDropdown();
        }
    });

    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = suggestionsDropdown.querySelectorAll('.list-group-item');
        if (!items.length) return;

        let activeIndex = -1;
        items.forEach((item, index) => {
            if (item.classList.contains('active')) {
                activeIndex = index;
                item.classList.remove('active');
            }
        });

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const newIndex = activeIndex < items.length - 1 ? activeIndex + 1 : 0;
            items[newIndex].classList.add('active');
        } 
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const newIndex = activeIndex > 0 ? activeIndex - 1 : items.length - 1;
            items[newIndex].classList.add('active');
        } 
        else if (e.key === 'Enter') {
            e.preventDefault();
            const activeItem = suggestionsDropdown.querySelector('.list-group-item.active');
            if (activeItem) {
                searchInput.value = activeItem.textContent;
                hideDropdown();
                document.getElementById('searchForm').submit();
            }
        }
    });
});
</script>
@endsection