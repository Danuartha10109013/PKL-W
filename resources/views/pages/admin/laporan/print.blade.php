<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Print Incentive Sales</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .table-responsive {
            margin: 20px;
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

        .text-right {
            text-align: right;
        }

        .no-border {
            border: none;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="table-responsive">
        <h2 style="text-align: center;">Incentive Sales Report</h2>
        <table class="table table-striped table-bordered mb-4">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>No. IT</th>
                    <th>NAMA CUSTOMER</th>
                    <th>No. PO</th>
                    <th>No. JO</th>
                    <th>SALES CODE</th>
                    <th>IT SALES</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <img src="{{asset('storage/'.$d->profile)}}" width="35px" alt="">
                        {{ $d->no_it }}
                    </td>
                    <td>{{ $d->customer_name }}</td>
                    <td>{{ $d->no_po }}</td>
                    <td>{{ $d->no_jo }}</td>
                    <td>SG</td>
                    <td>Rp. {{ number_format($d->se, 2, ',', '.') }}</td>
                </tr>
                @endforeach

                <!-- Empty Rows -->
                <tr>
                    <td colspan="7" class="no-border"></td>
                </tr>
                <tr>
                    <td colspan="7" class="no-border"></td>
                </tr>

                <!-- Total Row -->
                <tr>
                    <td><strong>TOTAL</strong></td>
                    <td colspan="5" class="text-right"></td>
                    <td><strong>Rp. {{ number_format($sum, 2, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
        @if($from && $to)
        <p class="text-muted">Laporan dari tanggal <strong>{{ $from }}</strong> sampai <strong>{{ $to }}</strong></p>
        @endif
    </div>


</body>
</html>
