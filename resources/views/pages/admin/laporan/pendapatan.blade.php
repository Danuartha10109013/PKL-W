@extends('layout.admin.main')

@section('title')
    Laporan Pendapatan || {{ Auth::user()->name }}
@endsection

@section('content')
<style>
@media print {
    
    @page {
        size: A4 portrait;
        margin: 3cm 3cm 3cm 4cm; /* top right bottom left */
    }


    body {
        font-family: Arial, sans-serif;
        font-size: 11pt;
        margin: 0;
        padding: 0;
        background: #fff !important;
        color: #000;
    }

    .btn, .d-print-none, nav, aside, footer {
        display: none !important;
    }

    .container, .card, .card-body {
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
        border: none !important;
    }

    .card-header {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        margin-bottom: 20px;
        border: none !important;
    }

    .print-table {
        overflow: visible !important;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table th,
    table td {
        border: 1px solid #000;
        padding: 6px 10px;
        text-align: left;
    }

    table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    h3, h4, h5, p {
        margin: 0;
    }
}
</style>



    <div class="card">
       <div class="card-header">
            <strong>Pendapatan</strong> (Gross Price - Incentive Team)
            <a href="#" onclick="window.print()" class="btn btn-warning float-end">
                <i class="bx bx-printer"></i>
            </a>
        </div>

        <div class="card-body">
            <div class="table print-table">
                <table class="table table-striped table responsive"> 
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Date</th>
                            <th>Job Card</th>
                            <th>Incentive</th>
                            <th>Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>

                            <td>{{$loop->iteration}}</td>
                            <td>{{$d->created_at->format('d M Y')}}</td>
                            <td>{{$d->no_jobcard}}</td>
                            <td>{{$d->no_it}}</td>
                            <td>Rp. {{ number_format($d->gp - $d->it, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection