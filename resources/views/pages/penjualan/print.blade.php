<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/x-icon" href="{{asset('PT. Bersama Sahabat Makmur Logo.png')}}" />

@if (Route::is('pegawai.komisi_c.print'))
        <title>Incentive Sales Costumer</title>
    @else
        <title>Incentive Sales</title>
    @endif
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .container {
        width: 90%;
        margin: auto;
    }

    .header-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        color: red;
    }

    .header-details {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
        font-size: 12px;
    }

    .details-group {
        display: flex;
        flex-direction: column;
    }

    .table-container {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .table-container th, .table-container td {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
        font-size: 12px;
    }

    .table-container th {
        background-color: #f0f0f0;
    }

    .table-container .right-align {
        text-align: right;
    }

    .total-row td {
        font-weight: bold;
    }

    .currency {
        white-space: nowrap;
    }

    /* Print styles */
    @media print {
        body, .container {
            margin: 0;
            width: 100%;
        }

        .table-container {
            page-break-inside: avoid;
        }

        .header-title {
            font-size: 14px;
        }

        .table-container th, .table-container td {
            font-size: 10px;
            padding: 4px;
        }
    }
</style>
</head>
<body>
<div class="container">
    @if (Route::is('pegawai.komisi_c.print'))
        <div class="header-title">INCENTIVE SALES (CUSTOMER)</div>
    @else
        <div class="header-title">INCENTIVE SALES</div>
    @endif

    <div class="header-title">No: {{$data->no}}</div>

    <div class="header-details">
        <div class="details-group">
            <span>Customer Name: {{$data->customer_name}}</span>
            <span>PO Number: {{$data->no_po}}</span>
            <span>PO Date: {{$data->date}}</span>
        </div>
        <div class="details-group">
            <span>JO Number: {{$data->no_jo}}</span>
            <span>JO Date: {{$data->jo_date}}</span>
            <span>Kurs: {{$data->kurs}}</span>
        </div>
    </div>

    <table class="table-container">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th colspan="2">SP</th>
                <th colspan="2">BoP</th>
                <th colspan="2">GP (IDR)</th>
                <th rowspan="2">TOTAL IT</th>
                <th colspan="4">PEMBAGIAN INCENTIVE TEAM</th>
            </tr>
            <tr>
                <th>IDR</th>
                <th>IDR</th>
                <th>USD</th>
                <th>IDR</th>
                <th>(SP - BoP)</th>
                <th>GP x 20%</th>
                <th>SALES ENGINEER</th>
                <th>APPLICATION SERVICE</th>
                <th>ADMINISTRATION</th>
                <th>MANAGER</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="12"></td>
            </tr>
            <tr>
                <td colspan="12"></td>
            </tr>
            <tr>
                <td colspan="12"></td>
            </tr>
            <tr>
                @php
                    $totalsp = \App\Models\JobcardM::where('no_jobcard',$data->no_jobcard)->value('totalsp');
                @endphp
                <td>1</td>
                <td class="currency">Rp {{$totalsp}}</td>
                
                <td class="currency"></td>
                <td class="currency"></td>
                <td class="currency">Rp {{$data->bop}}</td>
                <td class="currency">Rp {{$data->gp}}</td>
                <td class="currency">Rp {{$data->it}}</td>
                <td class="currency">Rp {{$data->it}}</td>
                <td class="currency">Rp. {{$data->se}}</td>
                <td class="currency">Rp {{$data->as}}</td>
                <td class="currency">Rp {{$data->adm}}</td>
                <td class="currency">Rp {{$data->mng}}</td>
            </tr>
            <tr>
                <td colspan="7" class="right-align">TOTAL</td>
                <td class="currency">Rp {{$data->it}}</td>
                <td class="currency">Rp {{$data->se}}</td>
                <td class="currency">Rp {{$data->as}}</td>
                <td class="currency">Rp {{$data->adm}}</td>
                <td class="currency">Rp {{$data->mng}}</td>
            </tr>
        </tbody>
    </table>
</div>
</body>
</html>
