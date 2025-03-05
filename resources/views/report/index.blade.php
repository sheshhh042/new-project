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
                            <canvas id="departmentChart"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Total Research Chart
            var totalCanvas = document.getElementById('totalResearchChart');
            if (totalCanvas) {
                var ctxTotal = totalCanvas.getContext('2d');
                new Chart(ctxTotal, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total Research'],
                        datasets: [{
                            data: [{{ $totalResearch }}],
                            backgroundColor: ['rgba(54, 162, 235, 0.2)'],
                            borderColor: ['rgba(54, 162, 235, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        return 'Total Research: ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Department Research Chart
            var deptCanvas = document.getElementById('departmentChart');
            if (deptCanvas) {
                var ctxDepartment = deptCanvas.getContext('2d');
                new Chart(ctxDepartment, {
                    type: 'pie',
                    data: {
                        labels: @json($departments->pluck('subject_area')),
                        datasets: [{
                            label: 'Total Research',
                            data: @json($departments->pluck('total')),
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(199, 199, 199, 0.2)',
                                'rgba(83, 102, 255, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(199, 199, 199, 1)',
                                'rgba(83, 102, 255, 1)',
                                'rgba(255, 99, 132, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: {
                                callbacks: {
                                    label: function (tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
