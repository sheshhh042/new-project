@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Search Analytics Dashboard</h1>
                <p class="mb-0">Track and analyze user search behavior</p>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="timeRangeDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-calendar-alt me-2"></i> Last 30 Days
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="timeRangeDropdown">
                    <li><a class="dropdown-item time-range" href="#" data-range="7">Last 7 Days</a></li>
                    <li><a class="dropdown-item time-range" href="#" data-range="30">Last 30 Days</a></li>
                    <li><a class="dropdown-item time-range" href="#" data-range="90">Last 90 Days</a></li>
                    <li><a class="dropdown-item time-range" href="#" data-range="365">Last Year</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item time-range" href="#" data-range="all">All Time</a></li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Searches</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSearches) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-search fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Unique Search Terms</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($mostSearchedKeywords) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Top Search Term</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $topTerm->keyword ?? 'N/A' }}</div>
                                @isset($topTerm)
                                    <div class="mt-1 text-xs text-gray-500">{{ number_format($topTerm->total) }} searches</div>
                                @endisset
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Terms Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center bg-white">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-search me-1"></i> Most Searched Terms
                </h6>
                <div>
                    <button class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-download me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($searches->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-search display-4 text-muted mb-3"></i>
                        <h4 class="text-muted">No search data available</h4>
                        <p class="text-muted">Search activity will appear here once users start searching</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Search Term</th>
                                    <th width="15%">Searches</th>
                                    <th width="15%">Trend</th>
                                    <th width="15%">First Searched</th>
                                    <th width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($searches as $search)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="icon-circle bg-primary mr-3">
                                                    <i class="fas fa-search text-white"></i>
                                                </div>
                                                <div>
                                                    <span class="font-weight-bold">{{ $search->keyword }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-weight-bold">{{ number_format($search->total) }}</td>
                                        <td>
                                            @if($search->trend > 0)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-arrow-up"></i> {{ abs($search->trend) }}%
                                                </span>
                                            @elseif($search->trend < 0)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-arrow-down"></i> {{ abs($search->trend) }}%
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-minus"></i> Steady
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($search->first_searched_at)
                                                {{ \Carbon\Carbon::parse($search->first_searched_at)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
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
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="small text-muted">
                            Showing {{ $searches->firstItem() }} to {{ $searches->lastItem() }} of {{ $searches->total() }} entries
                        </div>
                        <div>
                            <nav aria-label="Page navigation">
                                <ul class="pagination mb-0">
                                    @if($searches->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $searches->previousPageUrl() }}" rel="prev">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    @endif

                                    @foreach($searches->getUrlRange(1, $searches->lastPage()) as $page => $url)
                                        @if($page == $searches->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    @if($searches->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $searches->nextPageUrl() }}" rel="next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 0.35rem;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            }
            
            .card-header {
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table thead th {
                border-bottom: none;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .icon-circle {
                width: 2.5rem;
                height: 2.5rem;
                border-radius: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .badge {
                padding: 0.35em 0.65em;
                font-size: 0.75em;
                font-weight: 600;
                border-radius: 0.25rem;
            }
            
            .border-left-primary {
                border-left: 0.25rem solid #4e73df !important;
            }
            
            .border-left-info {
                border-left: 0.25rem solid #36b9cc !important;
            }
            
            .border-left-success {
                border-left: 0.25rem solid #1cc88a !important;
            }
            
            .page-item.active .page-link {
                background-color: #4e73df;
                border-color: #4e73df;
            }
            
            .page-link {
                color: #4e73df;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            // Time range dropdown functionality
            document.addEventListener('DOMContentLoaded', function() {
                const dropdownItems = document.querySelectorAll('.dropdown-item[data-range]');
                
                dropdownItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const range = this.getAttribute('data-range');
                        const dropdownButton = document.getElementById('timeRangeDropdown');
                        
                        // Update button text
                        let text = '';
                        switch(range) {
                            case '7': text = 'Last 7 Days'; break;
                            case '30': text = 'Last 30 Days'; break;
                            case '90': text = 'Last 90 Days'; break;
                            case '365': text = 'Last Year'; break;
                            case 'all': text = 'All Time'; break;
                        }
                        
                        dropdownButton.innerHTML = `<i class="fas fa-calendar-alt me-2"></i> ${text}`;
                        
                        // Here you would typically make an AJAX call to update the data
                        // For now, we'll just log the selection
                        console.log(`Selected time range: ${range} days`);
                        
                        // Example of how you might implement the AJAX call:
                        /*
                        fetch(`/analytics?range=${range}`)
                            .then(response => response.json())
                            .then(data => {
                                // Update your UI with the new data
                            });
                        */
                    });
                });
            });
        </script>
    @endpush
@endsection