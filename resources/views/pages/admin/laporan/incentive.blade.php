@extends('layout.admin.main')

@section('title')
    Kelola User || {{ Auth::user()->name }}
@endsection

@section('content')
<div class="mb-3">
    <form id="filter-form" class="d-flex align-items-center mb-3">
    <div class="me-2">
        <label for="from" class="form-label">Dari Tanggal</label>
        <input type="date" name="from" id="from" class="form-control">
    </div>
    <div class="me-2">
        <label for="to" class="form-label">Sampai Tanggal</label>
        <input type="date" name="to" id="to" class="form-control">
    </div>
    <div class="mt-4">
        <button type="button" class="btn btn-primary" onclick="filterTable()">Filter</button>
        {{-- <button type="button" class="btn btn-secondary ms-2" onclick="resetFilter()">Reset</button> --}}
    </div>
</form>
</div>

<div id="printable-area">
    <div class="card mt-3 shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Laporan Incentive {{ Auth::user()->division }}</h5>

            <!-- Tombol Cetak -->
            <button class="btn btn-warning d-print-none" onclick="window.print()">
                <i class="bx bxs-printer"></i> Cetak
            </button>
        </div>



<!-- CSS PRINT -->
<style>
@media print {
    body * {
        visibility: hidden;
    }

    #printable-area, #printable-area * {
        visibility: visible;
    }

    #printable-area {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background-color: #fff;
        padding: 20px;
        font-size: 12pt;
        line-height: 1.5;
    }

    /* Elemen yang tidak ingin dicetak */
    .d-print-none,
    nav,
    footer,
    .btn,
    .no-print {
        display: none !important;
    }

    /* Hilangkan efek visual tidak penting */
    .card, .table, .border, .shadow {
        box-shadow: none !important;
        background-color: white !important;
        border-color: #000 !important;
    }

    * {
        color: #000 !important;
    }
 table {
        width: 100%;
        border-collapse: collapse !important;
        table-layout: fixed;
    }

    th, td {
        border: 1px solid #000 !important;
        padding: 6px !important;
        font-size: 11pt !important;
        vertical-align: top !important;
        word-wrap: break-word;
        text-align: left;
    }

    th {
        background-color: #f2f2f2 !important;
        font-weight: bold;
    }

    tr {
        page-break-inside: avoid;
    }
}

</style>

        
        <div class="container">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>Tanggal</th>
                            <th>No. IT</th>
                            <th>Total Incentive team</th>
                            <th>Divisi</th>
                            <th>Nama</th>
                            <th>Incentive</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="incentive-table-body">
                        @foreach ($data as $index => $d)
                            @php
                                $rowSpan = 0;
                                $divisionData = [];

                                $divisionMap = [
                                    'Sales Enginer' => ['penerima' => 'penerimase', 'nominal' => 'se'],
                                    'Aplication Service' => ['penerima' => 'penerimaap', 'nominal' => 'as'],
                                    'Administration' => ['penerima' => 'penerimaadm', 'nominal' => 'adm'],
                                    'Manager' => ['penerima' => 'penerimamng', 'nominal' => 'mng'],
                                ];

                                // Hitung jumlah total baris untuk rowspan dan simpan data
                                foreach ($divisionMap as $division => $fields) {
                                    $penerimaList = json_decode($d->{$fields['penerima']} ?? '[]', true);
                                    $totalPenerima = is_array($penerimaList) ? count($penerimaList) : 0;

                                    if ($totalPenerima > 0) {
                                        $divisionData[] = [
                                            'name' => $division,
                                            'nominal' => $d->{$fields['nominal']} ?? 0,
                                            'penerima' => $penerimaList,
                                        ];
                                        $rowSpan += $totalPenerima;
                                    }
                                }

                                $printed = false;
                            @endphp

                            @foreach ($divisionData as $division)
                                @php
                                    $nominalPerOrang = count($division['penerima']) > 0
                                        ? $division['nominal'] / count($division['penerima'])
                                        : 0;
                                @endphp

                                @foreach ($division['penerima'] as $item)
                                    <tr class="data-row" data-index="{{ $index }}">
                                        @if (!$printed)
                                            <td rowspan="{{ $rowSpan }}">{{ $loop->parent->parent->iteration }}</td>
                                            <td rowspan="{{ $rowSpan }}" class="tanggal">{{ $d->created_at->format('d M Y') }}</td>
                                            <td rowspan="{{ $rowSpan }}">{{ $d->no_it }}</td>
                                            <td rowspan="{{ $rowSpan }}" class="it-value">Rp. {{ number_format($d->it ?? 0, 0, ',', '.') }}</td>

                                            <!-- <td rowspan="{{ $rowSpan }}">Rp. {{ number_format($d->it ?? 0, 0, ',', '.') }}</td> -->
                                            @php $printed = true; @endphp
                                        @endif
                                        <td>{{ $division['name'] }}</td>
                                        <td>
                                            @php
                                                $user = \App\Models\User::find($item['id']);
                                                echo $user ? $user->name : 'Tidak Diketahui';
                                            @endphp
                                        </td>
                                        <td>Rp. {{ number_format($nominalPerOrang, 0, ',', '.') }}</td>
                                        <td>{{ isset($item['dibayar']) && $item['dibayar'] == "1" ? 'Sudah' : 'Belum Dibayarkan' }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach

                        <!-- Total Incentive (JS akan update nilai ini) -->
                        <tr>
                            <td colspan="3" class="text text-center">TOTAL</td>
                            <td id="total-nominal">Rp. 0,00</td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>

            </div>

            @if(request('from') && request('to'))
                <p class="text-muted">Data dari tanggal <strong>{{ request('from') }}</strong> sampai <strong>{{ request('to') }}</strong></p>
            @endif

        </div>
    </div>
</div>
<script>
    function extractCurrencyFromText(text) {
        const match = text.match(/Rp\.\s?([\d.,]+)/);
        if (!match) return 0;
        return parseFloat(match[1].replace(/\./g, '').replace(',', '.')) || 0;
    }

    function formatCurrency(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(number);
    }

function filterTable() {
    const fromInput = document.getElementById('from').value;
    const toInput = document.getElementById('to').value;
    const rows = document.querySelectorAll('#incentive-table-body tr.data-row');
    const allGroups = document.querySelectorAll('#incentive-table-body tr');

    let fromDate = fromInput ? new Date(fromInput) : null;
    let toDate = toInput ? new Date(toInput) : null;

    // Hapus semua penyesuaian tanggal (jangan +1 hari atau -1 hari)
    // Normalisasi jam agar akurat dalam perbandingan
    if (fromDate) fromDate.setHours(0, 0, 0, 0);
    if (toDate) toDate.setHours(23, 59, 59, 999);

    let visibleIndexes = new Set();

    allGroups.forEach(row => {
        const tanggalCell = row.querySelector('.tanggal');
        if (!tanggalCell) return;

        const rowDate = new Date(tanggalCell.textContent.trim());
        rowDate.setHours(12, 0, 0, 0); // Pastikan berada di tengah hari (menghindari zona waktu)

        const index = row.getAttribute('data-index');

        const show = (!fromDate || rowDate >= fromDate) &&
                     (!toDate || rowDate <= toDate);

        if (show) {
            visibleIndexes.add(index);
        }
    });

    // Tampilkan hanya baris yang termasuk dalam indeks yang cocok
    allGroups.forEach(row => {
        const index = row.getAttribute('data-index');
        row.style.display = visibleIndexes.has(index) ? '' : 'none';
    });

    updateTotalIT(visibleIndexes);
}

    function updateTotalIT(indexes) {
        let total = 0;
        const allRows = document.querySelectorAll('#incentive-table-body tr');

        allRows.forEach(row => {
            const index = row.getAttribute('data-index');
            const itCell = row.querySelector('.it-value');

            if (itCell && indexes.has(index)) {
                total += extractCurrencyFromText(itCell.textContent.trim());
            }
        });

        document.getElementById('total-nominal').textContent = formatCurrency(total);
    }

    function resetFilter() {
        document.getElementById('from').value = '';
        document.getElementById('to').value = '';
        const allRows = document.querySelectorAll('#incentive-table-body tr');
        const allIndexes = new Set();

        allRows.forEach(row => {
            row.style.display = '';
            const index = row.getAttribute('data-index');
            if (index) allIndexes.add(index);
        });

        updateTotalIT(allIndexes);
    }

    // Hitung total awal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const rows = document.querySelectorAll('#incentive-table-body tr');
        const indexSet = new Set();

        rows.forEach(row => {
            const index = row.getAttribute('data-index');
            if (index) indexSet.add(index);
        });

        updateTotalIT(indexSet);
    });
</script>

@endsection
