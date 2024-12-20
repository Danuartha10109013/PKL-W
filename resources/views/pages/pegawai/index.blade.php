@extends('layout.admin.main')

@section('title', "Dashboard || SBM {{ Auth::user()->name }}")

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Total User Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $total_user = \App\Models\User::count();
                        @endphp
                        <h5>Total Users</h5>
                        <h3>{{ $total_user }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Incentive Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $total_incentive = \App\Models\KomisiM::count();
                        @endphp
                        <h5>Total Incentives</h5>
                        <h3>{{ $total_incentive }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Jobcard Card -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @php
                            $total_jobcard = \App\Models\KomisiM::count();
                        @endphp
                        <h5>Total Jobcards</h5>
                        <h3>{{ $total_jobcard }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- Bar Chart for Selling Price Growth -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Selling Price Growth (Monthly)</h5>
                    </div>
                    <div class="card-body text-center d-flex justify-content-center ">
                        <canvas id="sellingPriceChart"></canvas>
                    </div>
                    
                </div>
            </div>

            <!-- Pie Chart for Sales Distribution -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Sales Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $sales_distribution = \App\Models\KomisiM::select('sales_name', \DB::raw('count(*) as total'))
                                ->groupBy('sales_name')
                                ->get();
                        @endphp
                    <div class="card-body text-center d-flex justify-content-center ">

                        <canvas id="salesDistributionChart"></canvas>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Selling Price Growth Chart
        const sellingPriceLabels = @json(array_keys($sellingPriceData->toArray())); // Month labels
        const sellingPriceValues = @json(array_values($sellingPriceData->toArray())); // Data points

        const sellingPriceCtx = document.getElementById('sellingPriceChart').getContext('2d');
        const sellingPriceChart = new Chart(sellingPriceCtx, {
            type: 'bar',
            data: {
                labels: sellingPriceLabels,
                datasets: [{
                    label: 'Total Selling Price',
                    data: sellingPriceValues,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Sales Distribution Chart
        const salesDistributionCtx = document.getElementById('salesDistributionChart').getContext('2d');
        const salesDistributionChart = new Chart(salesDistributionCtx, {
            type: 'pie',
            data: {
                labels: @json($sales_distribution->pluck('sales_name')), // Sales names
                datasets: [{
                    data: @json($sales_distribution->pluck('total')), // Data points
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false
            }
        });
    </script>
@endsection
