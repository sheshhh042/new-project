@extends('layouts.app')

@section('title', 'Search Analytics')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header with Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-gradient-info text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-search display-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Total Searches</h6>
                                        <h3 class="mb-0">{{ number_format($totalSearches) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-list-ol display-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Unique Terms</h6>
                                        <h3 class="mb-0">{{ number_format($uniqueTerms) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm bg-gradient-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-trophy display-6"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">Top Term</h6>
                                        <h3 class="mb-0">{{ $topTerm->keyword ?? 'N/A' }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card with Table -->
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-search-heart me-2 text-primary"></i>
                            Most Searched Terms
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="timeRangeDropdown" data-bs-toggle="dropdown">
                                <i class="bi bi-calendar-range me-1"></i> Last 30 Days
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Last 7 Days</a></li>
                                <li><a class="dropdown-item" href="#">Last 30 Days</a></li>
                                <li><a class="dropdown-item" href="#">Last 90 Days</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">All Time</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($searches->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-search display-4 text-muted"></i>
                                <h4 class="mt-3 text-muted">No search data found</h4>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Search Term</th>
                                            <th>Total Searches</th>
                                            <th>Trend</th>
                                            <th class="text-end pe-4">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($searches as $search)
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                                                            <i class="bi bi-search"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $search->keyword }}</h6>
                                                            <small class="text-muted">First searched: {{ $search->first_searched_at->format('M d, Y') }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                                                        {{ number_format($search->total) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($search->trend > 0)
                                                        <span class="text-success">
                                                            <i class="bi bi-graph-up-arrow"></i> {{ abs($search->trend) }}%
                                                        </span>
                                                    @elseif($search->trend < 0)
                                                        <span class="text-danger">
                                                            <i class="bi bi-graph-down-arrow"></i> {{ abs($search->trend) }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">
                                                            <i class="bi bi-dash-lg"></i> Steady
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary ms-2" data-bs-toggle="tooltip" title="Export">
                                                        <i class="bi bi-download"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    @if($searches->hasPages())
                    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Showing {{ $searches->firstItem() }} to {{ $searches->lastItem() }} of {{ $searches->total() }} entries
                        </div>
                        <div>
                            {{ $searches->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0d6efd 100%);
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
        }
        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
        .table th {
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }
    </style>

    <script>
        // Enable tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })
    </script>
@endsection