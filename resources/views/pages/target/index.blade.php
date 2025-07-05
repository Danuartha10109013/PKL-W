@extends('layout.admin.main')
@section('title')
    Targeting || SBM {{ Auth::user()->name }}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h3 class="text-center" id="printHeader" style="display: none;">Laporan Target</h3>

    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center" id="cardHeader">
            <h4 class="mb-0 text-white">Target Pencapaian Bulanan</h4>
            <button class="btn btn-warning" onclick="window.print()"><i class="bx bxs-printer"></i></button>
        </div>
        <div class="card-body">
            <div class="row mt-3">
                <div class="col-md-6 text-center align-middle">
                    <h5 class="mb-3 mt-3">Progres Bulanan</h5>
                    <div class="progress mb-3" style="height: 30px;">
                        @php
                            $total = $dataPerBulan->last()->total_per_bulan ?? 0;
                            $prediksi = max($prediksiBulanDepan, 1);
                            $progress = ($total / $prediksi) * 100;
                        @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ min($progress, 100) }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            {{ number_format($progress, 2) }}%
                        </div>
                    </div>
                    <p>Target bulan depan: <strong>Rp {{ number_format($prediksiBulanDepan, 0, ',', '.') }}</strong></p>
                </div>
                <div class="col-md-6">
                    <canvas id="targetChart"></canvas>
                </div>
            </div>

            <h5 class="mt-4">Detail Pencapaian per Bulan</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Selling Price (Rp)</th>
                        <th>Moving Average</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dataPerBulan as $data)
                        @php
                            $hasMovingAvg = $data->moving_average !== null;
                            $selisih = $hasMovingAvg ? $data->total_per_bulan - $data->moving_average : null;
                            $status = $selisih >= 0 ? 'Tercapai' : 'Tidak Tercapai';
                            $keterangan = $selisih >= 0
                                ? "Performa melebihi target. Potensi keuntungan terhadap target sebesar Rp " . number_format(abs($selisih), 0, ',', '.')
                                : "Performa belum mencapai target. Potensi kerugian terhadap target sebesar Rp " . number_format(abs($selisih), 0, ',', '.');
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $data->bulan)->translatedFormat('F Y') }}</td>
                            <td>Rp {{ number_format($data->total_per_bulan, 0, ',', '.') }}</td>
                            <td>
                                @if($hasMovingAvg)
                                    Rp {{ number_format($data->moving_average, 0, ',', '.') }}
                                @else
                                    <em class="text-muted">Data Tidak Cukup</em>
                                @endif
                            </td>
                            <td>
                                @if($hasMovingAvg)
                                    <strong>{{ $status }}</strong>
                                @endif
                            </td>
                            <td>
                                @if($hasMovingAvg)
                                    {{ $keterangan }}
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
    canvas {
        display: block;
        width: 100%;
        height: 400px;
    }

    @media print {
        .btn-warning, #cardHeader {
            display: none;
        }

        #printHeader {
            display: block;
        }

        body * {
            visibility: hidden;
        }

        .card, .card * {
            visibility: visible;
            overflow: hidden;
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

        .card {
            page-break-after: always;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0/dist/chartjs-plugin-annotation.min.js"></script>

<script>
    Chart.register(window['chartjs-plugin-annotation']);

    const bulanLabels = @json($dataPerBulan->pluck('bulan')->map(fn($b) => \Carbon\Carbon::createFromFormat('Y-m', $b)->translatedFormat('F')));
    const totalSpData = @json($dataPerBulan->pluck('total_per_bulan'));
    const movingAverageData = @json($dataPerBulan->pluck('moving_average'));

    const arrows = totalSpData.map((val, idx, arr) => {
        if (idx === 0) return null;
        return val > arr[idx - 1] ? '↑' : val < arr[idx - 1] ? '↓' : '';
    });

    const pointLabels = arrows.map((arrow, idx) => ({
        x: bulanLabels[idx],
        y: totalSpData[idx],
        label: arrow
    }));

    const ctx = document.getElementById('targetChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [
                {
                    label: 'Realisasi Penjualan (Selling Price)',
                    data: totalSpData,
                    borderColor: 'green',
                    backgroundColor: 'rgba(0,128,0,0.1)',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 5,
                    pointBackgroundColor: 'green',
                },
                {
                    label: 'Target (Moving Average)',
                    data: movingAverageData,
                    borderColor: 'blue',
                    borderDash: [5, 5],
                    backgroundColor: 'rgba(0,0,255,0.1)',
                    tension: 0.4,
                    fill: false,
                    pointRadius: 5,
                    pointBackgroundColor: 'blue',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return `${ctx.dataset.label}: Rp ${ctx.parsed.y.toLocaleString('id-ID')}`;
                        }
                    }
                },
                annotation: {
                    annotations: pointLabels.map(point => point.label ? {
                        type: 'label',
                        xValue: point.x,
                        yValue: point.y,
                        content: [point.label],
                        color: point.label === '↑' ? 'green' : 'red',
                        backgroundColor: 'transparent',
                        font: { weight: 'bold', size: 16 },
                        position: 'top'
                    } : null).filter(a => a !== null)
                }
            },
            scales: {
                y: { title: { display: true, text: 'Nilai (Rp)' } },
                x: { title: { display: true, text: 'Bulan' } }
            }
        }
    });
</script>

@endsection
