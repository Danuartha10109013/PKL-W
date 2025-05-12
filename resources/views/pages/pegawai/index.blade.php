@extends('layout.admin.main')

@section('title', 'Dashboard || SBM ' . Auth::user()->name)


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
                        <h5>Incentive Distribution</h5>
                    </div>
                    <div class="card-body">
                        @php

                            // Decode JSON dari masing-masing kolom
                            $penerima_se_raw = json_decode(\App\Models\KomisiM::value('penerimase'), true);
                            $penerima_as_raw = json_decode(\App\Models\KomisiM::value('penerimaap'), true);
                            $penerima_adm_raw = json_decode(\App\Models\KomisiM::value('penerimaadm'), true);
                            $penerima_amng_raw = json_decode(\App\Models\KomisiM::value('penerimamng'), true);

                            // Ambil hanya ID dari nested array
                            $penerima_se_ids = collect($penerima_se_raw)->pluck('id')->toArray();
                            $penerima_as_ids = collect($penerima_as_raw)->pluck('id')->toArray();
                            $penerima_adm_ids = collect($penerima_adm_raw)->pluck('id')->toArray();
                            $penerima_amng_ids = collect($penerima_amng_raw)->pluck('id')->toArray();

                            // Hitung user berdasarkan ID yang cocok
                            $count_se = \App\Models\User::where('role', 2)->where('division', 'Sales Enginer')->whereIn('id', $penerima_se_ids)->count();
                            $count_as = \App\Models\User::where('role', 2)->where('division', 'Aplication Service')->whereIn('id', $penerima_as_ids)->count();
                            $count_adm = \App\Models\User::where('role', 2)->where('division', 'Administration')->whereIn('id', $penerima_adm_ids)->count();
                            $count_amng = \App\Models\User::where('role', 2)->where('division', 'Manager')->whereIn('id', $penerima_amng_ids)->count();

                            // Siapkan data untuk chart
                            $sales_distribution_chart = [
                                'Sales Engineer' => $count_se,
                                'Application Service' => $count_as,
                                'Administration' => $count_adm,
                                'Manager' => $count_amng,
                            ];
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
{{-- <canvas id="salesDistributionChart" width="400" height="400"></canvas> --}}

<script>
    
    const salesDistributionCtx = document.getElementById('salesDistributionChart').getContext('2d');
    const salesDistributionChart = new Chart(salesDistributionCtx, {
        type: 'pie',
        data: {
            labels: @json(array_keys($sales_distribution_chart)),
            datasets: [{
                data: @json(array_values($sales_distribution_chart)),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
</script>

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
</script>

@endsection
