@extends('layouts.app')

@section('title', 'Report')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Research by Department (Pie Chart) -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Research by Department</h6>
                    </div>
                    <div class="card-body">
                        @if($departments->isEmpty())
                            <p class="text-muted">No department research data available.</p>
                        @else
                            <canvas id="departmentChart" data-labels='@json(array_values($departments->pluck("subject_area")->toArray()))' data-data='@json(array_values($departments->pluck("total")->toArray()))'></canvas>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Most Searched Keywords -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Most Searched Keywords</h6>
                    </div>
                    <div class="card-body">
                        @if($mostSearchedKeywords->isEmpty())
                            <p class="text-muted">No search data available.</p>
                        @else
                            <ul>
                                @foreach($mostSearchedKeywords as $keyword)
                                    <li>{{ $keyword->keyword }} ({{ $keyword->total }} searches)</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Total Research by Department -->
            <div class="col-lg-12 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Total Research by Department</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th>Department</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $department)
                                    <tr>
                                        <td>{{ $department->subject_area }}</td>
                                        <td>{{ $department->total }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
                                    <td><strong>Total Research</strong></td>
                                    <td><strong>{{ $totalResearch }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-column align-items-center justify-content-center mt-4">
                @if(isset($totalResearch) && $totalResearch > 0)
                    <canvas id="totalResearchChart" width="100" height="100"></canvas>
                @else
                    <p class="text-muted">No research data available.</p>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/chart-setup.js')
    <script>
        var totalResearch = {{ $totalResearch }};
    </script>
@endsection
