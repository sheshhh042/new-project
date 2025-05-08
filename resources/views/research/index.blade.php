@extends('layouts.app')

@section('title', 'All Research List')
@section('content')

    @section('styles')
        <style>
            /* Search Suggestions Styling */
            #suggestionsDropdown {
                max-height: 300px;
                overflow-y: auto;
                border: 1px solid rgba(0, 0, 0, .15);
                border-radius: 0.25rem;
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
                position: absolute;
                z-index: 1000;
                width: calc(100% - 80px);
                /* Adjust based on your search button width */
            }

            #suggestionsDropdown .dropdown-item {
                padding: 0.5rem 1.5rem;
                white-space: normal;
                word-wrap: break-word;
            }

            #suggestionsDropdown .dropdown-item.active,
            #suggestionsDropdown .dropdown-item:hover {
                background-color: #f8f9fa;
                color: #212529;
                cursor: pointer;
            }

            /* Make sure the search form has relative positioning */
            #searchForm {
                position: relative;
            }
        </style>
    @endsection

    <div class="d-flex align-items-center justify-content-between mb-3">
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('research.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Research
            </a>
        @endif
    </div>

    <!-- Search Form -->
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
    <div class="mt-3 mb-4">
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
            <thead class="table-primary text-center">
                <tr>
                    <!-- <th>Book ID</th> -->
                    <th>Research Title</th>
                    <th>Author</th>
                    <th>Date of Approval</th>
                    <th>Location</th>
                    <th>Subject Area</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($researches as $research)
                    <tr>
                        <!-- <td>{{ $research->reference_id }}</td> -->
                        <td>{{ $research->Research_Title }}</td>
                        <td>{{ $research->Author }}</td>
                        <td>{{ $research->date }}</td>
                        <td>{{ $research->Location }}</td>
                        <td>{{ $research->subject_area }}</td>
                        <td>

                            <!-- View button remains unchanged -->
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#viewPDFModal{{ $research->id }}"
                                onclick="viewPDF('{{ asset('storage/' . $research->file_path) }}', {{ $research->id }})">
                                <i class="fas fa-eye"></i>
                            </button>

                            <!-- Admin Options - Minimal three-dot dropdown -->
                            @if(Auth::user()->role == 'admin')
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-link btn-sm text-secondary p-0" type="button"
                                        id="dropdownMenuButton{{ $research->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $research->id }}">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('research.edit', $research->id) }}">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $research->id }}">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>

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
        document.addEventListener("DOMContentLoaded", function () {
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
            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => fetchSuggestions(this.value), 300);
            });

            searchInput.addEventListener('focus', function () {
                if (this.value.length >= 2) {
                    fetchSuggestions(this.value);
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !suggestionsDropdown.contains(e.target)) {
                    hideDropdown();
                }
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', function (e) {
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
        // Function to view PDF in modal
        window.viewPDF = function (filePath, researchId) {
            const pdfViewer = document.getElementById('pdfViewer' + researchId);
            pdfViewer.src = filePath;
        };

    </script>
@endsection