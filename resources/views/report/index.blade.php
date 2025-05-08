@extends('layouts.app')


@section('content')
    <div class="container-fluid px-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Report</h1>
            <div class="d-none d-sm-inline-block">
                <!--<span class="badge bg-primary text-white p-2">-->
                <!--    <i class="fas fa-calendar-alt fa-sm"></i> -->
                <!--    Last updated: {{ now()->format('M d, Y H:i') }}-->
                <!--</span>-->
            </div>
        </div>

        <!-- Summary Cards Row -->
        <div class="row mb-4">
            <!-- Total Research Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Research</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalResearch }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Departments</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $departments->count() }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-university fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Searched Term Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Top Searched Term</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $mostSearchedKeywords->first()->keyword ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-search fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Searches Card -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Searches</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $mostSearchedKeywords->sum('total') ?? 0 }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Research by Department (Pie Chart) -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie mr-2"></i>Research Distribution by Department
                        </h6>
                        <div class="dropdown no-arrow">
                            <!--<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" -->
                            <!--   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                            <!--    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>-->
                            <!--</a>-->
                            <!--<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" -->
                            <!--     aria-labelledby="dropdownMenuLink">-->
                            <!--    <a class="dropdown-item" href="#">Export as PNG</a>-->
                            <!--    <a class="dropdown-item" href="#">Export as CSV</a>-->
                            <!--</div>-->
                        </div>
                    </div>
                    <div class="card-body">
                        @if($departments->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-circle fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">No department research data available</p>
                            </div>
                        @else
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="departmentChart"
                                    data-labels='@json(array_values($departments->pluck("subject_area")->toArray()))'
                                    data-data='@json(array_values($departments->pluck("total")->toArray()))'></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                @foreach($departments as $department)
                                    <span class="mr-3">
                      
                                        {{ $department->subject_area }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Most Searched Terms Table -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-search mr-2"></i>Top Search Terms
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        @if($mostSearchedKeywords->isEmpty())
                            <div class="text-center py-4">
                                <i class="fas fa-search-minus fa-3x text-gray-300 mb-3"></i>
                                <p class="text-muted">No search data available</p>
                            </div>
                        @else
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-hover table-borderless mb-0">
                                    <thead class="bg-light sticky-top">
                                        <tr>
                                            <th>Search Term</th>
                                            <th class="text-right">Total Searches</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mostSearchedKeywords as $term)
                                            <tr>
                                                <td>
                                                    <span class="font-weight-bold">{{ $term->keyword }}</span>
                                                </td>
                                                <td class="text-right">
                                                    {{ $term->total }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Research Data Table -->
        <div class="card shadow mb-4">
            <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table mr-2"></i>Detailed Research by Department
                </h6>
                <div class="dropdown no-arrow">
                    <!--<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" -->
                    <!--   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                    <!--    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>-->
                    <!--</a>-->
                    <!--<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" -->
                    <!--     aria-labelledby="dropdownMenuLink">-->
                    <!--    <a class="dropdown-item" href="#">Export as Excel</a>-->
                    <!--    <a class="dropdown-item" href="#">Print</a>-->
                    <!--</div>-->
                </div>
            </div>
            <div class="card-body">
                @if($departments->isEmpty())
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-3x text-gray-300 mb-3"></i>
                        <p class="text-muted">No department research data available</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Department</th>
                                    <th class="text-right">Research Count</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr>
                                        <td>
                                            <i class="fas fa-fw fa-university text-gray-400 mr-2"></i>
                                            {{ $department->subject_area }}
                                        </td>
                                        <td class="text-right">
                                            {{ $department->total }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right">{{ $totalResearch }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/chart-setup.js')
@endsection