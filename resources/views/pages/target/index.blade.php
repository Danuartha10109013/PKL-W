@extends('layout.admin.main')
@section('title')
    Targeting || SBM {{ Auth::user()->name }}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Title for Print (Visible during print) -->
    <h3 class="text-center" id="printHeader" style="display: none;">Laporan Target</h3>

    <div class="card">
        <!-- Card Header (Hidden during printing) -->
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" id="cardHeader">
            <h4 class="mb-0 text-white">Target Pencapaian Bulanan</h4>
            <button class="btn btn-warning" onclick="window.print()"><i class="bx bxs-printer"></i></button>
        </div>
        <div class="card-body">
            <div class="row mt-3">
                <!-- Progress -->
                <div class="col-md-6 text-center align-middle">
                    <h5 class="mb-3 mt-3">Progres Bulanan</h5>
                    <div class="progress mb-3" style="height: 30px;">
                        @php
                            $total = $dataPerBulan->last()->total_per_bulan ?? 0;
                            $prediksi = max($prediksiBulanDepan, 1); // Pastikan nilai minimal 1
                            $progress = ($total / $prediksi) * 100;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ min($progress, 100) }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($progress, 2) }}%
                        </div>
                    </div>
                    <p>Target bulan depan: <strong>Rp {{ number_format($prediksiBulanDepan, 0, ',', '.') }}</strong></p>
                </div>                    
                <!-- Grafik -->
                <div class="col-md-6">
                    <canvas id="targetChart"></canvas>
                </div>
            </div>

            <!-- Tabel Data Bulanan -->
            <h5 class="mt-4">Detail Pencapaian per Bulan</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                    <th>Bulan</th>
                    <th class="text text-left">
                        Total Seling Price (<span style="text-transform: none;">Rp</span>)
                    </th>
                    <th>Moving Average</th>
                </tr>

                </thead>
                <tbody>
                    @foreach($dataPerBulan as $data)
                    <tr>
                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->bulan)->translatedFormat('F Y') }}</td>
                        <td>Rp {{ number_format($data->total_per_bulan, 0, ',', '.') }}</td>
                        <td>
                            @if($data->moving_average)
                                Rp {{ number_format($data->moving_average, 0, ',', '.') }}
                            @else
                                <em class="text-muted">Data Tidak Cukup</em>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* Make sure the canvas element has a proper size */
    canvas {
        display: block;
        width: 100%;
        height: 400px; /* Set a fixed height for the chart */
    }

    /* Print Styles */
    @media print {
        /* Hide the print button during printing */
        .btn-warning {
            display: none;
        }

        /* Hide the card header */
        #cardHeader {
            display: none;
        }

        /* Show the print header */
        #printHeader {
            display: block;
        }

        /* Show only the content inside .card-body and the header */
        body * {
            visibility: hidden;
        }

        .card, .card * {
            visibility: visible;
            overflow: hidden;
            margin-top: -0.3em
        }

        .card-header {
            font-size: 18px; /* Adjust title size */
            text-align: center;
            margin-bottom: 20px;
        }

        .progress {
            display: block; /* Make sure the progress bar is visible */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f2f2f2;
        }

        /* Page break after the content */
        .card {
            page-break-after: always;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get data from Blade variables
    const bulanLabels = @json($dataPerBulan->pluck('bulan')->map(fn($b) => \Carbon\Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y'))); 
    const totalSpData = @json($dataPerBulan->pluck('total_per_bulan'));
    const movingAverageData = @json($dataPerBulan->pluck('moving_average'));

    // Create chart
    const ctx = document.getElementById('targetChart').getContext('2d');
    const targetChart = new Chart(ctx, {
        type: 'line', // Chart type (line chart)
        data: {
            labels: bulanLabels, // X-axis labels
            datasets: [
                {
                    label: 'Total SP', // Label for the first dataset
                    data: totalSpData, // Data for the first dataset
                    borderColor: 'rgba(75, 192, 192, 1)', // Line color for Total SP
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Fill color for Total SP
                    fill: true, // Fill the area under the line
                },
                {
                    label: 'Moving Average', // Label for the second dataset
                    data: movingAverageData, // Data for the second dataset
                    borderColor: 'rgba(255, 99, 132, 1)', // Line color for Moving Average
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Fill color for Moving Average
                    fill: true, // Fill the area under the line
                }
            ]
        },
        options: {
            responsive: false, // Make chart responsive
            animation: {
                duration: 0 // Disable animation (set duration to 0)
            },
            scales: {
                y: {
                    beginAtZero: true, // Start Y-axis from zero
                }
            }
        }
    });
</script>
@endsection
