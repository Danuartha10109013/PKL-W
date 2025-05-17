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
        <button type="button" class="btn btn-secondary ms-2" onclick="resetFilter()">Reset</button>
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
                            <th>IT SALES</th>
                        </tr>
                    </thead>
                    <tbody id="incentive-table-body">
                        @foreach ($data as $index => $d)
                        <tr class="data-row" data-index="{{ $index }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="tanggal">{{ $d->created_at->format('d M Y') }}</td>
                            <td>
                                {{ $d->no_it }}</td>

                            <td>

                                @php
                                    $divisionMap = [
                                        'Sales Enginer' => ['penerima' => 'penerimase', 'nominal' => 'se'],
                                        'Aplication Service' => ['penerima' => 'penerimaap', 'nominal' => 'as'],
                                        'Administration' => ['penerima' => 'penerimaadm', 'nominal' => 'adm'],
                                        'Manager' => ['penerima' => 'penerimamng', 'nominal' => 'mng'],
                                    ];
                                @endphp

                                @foreach ($divisionMap as $division => $fields)
                                    @php
                                        $penerimaList = json_decode($d->{$fields['penerima']} ?? '[]', true);
                                        $totalPenerima = is_array($penerimaList) ? count($penerimaList) : 0;
                                        $totalNominal = $d->{$fields['nominal']} ?? 0;
                                        $nominalPerOrang = $totalPenerima > 0 ? $totalNominal / $totalPenerima : 0;
                                    @endphp

                                    @if ($totalPenerima > 0)
                                        <h5 class="nominal-value">{{ $division . ' Total => Rp. ' . number_format($d->{$fields['nominal']} ?? 0, 0, ',', '.') }}</h5>

                                        <ul>
                                            @foreach ($penerimaList as $item)
                                                @php
                                                    $user = \App\Models\User::find($item['id']);
                                                    $userName = $user ? $user->name : 'Tidak Diketahui';
                                                @endphp
                                                <li>
                                                    <strong>Nama Penerima: </strong> {{ $userName }} => 
                                                    1 / {{ $totalPenerima }} = Rp. {{ number_format($nominalPerOrang, 0, ',', '.') }} <br>
                                                    <strong>Catatan: </strong><span class="text text-danger">{{$item['catatan']}}</span><br>
                                                    <strong>Status: </strong> {{ isset($item['status']) && $item['status'] == 1 ? 'Terkonfirmasi' : 'Belum dikonfirmasi' }} <br>
                                                    <strong>Pembayaran: </strong>{{ isset($item['dibayar']) && $item['dibayar'] == "1" ? 'Sudah' : 'Belum Dibayarkan' }} <br>
                                                    <!-- Tombol untuk membuka modal -->
                                                    @if ($item['dibayar'] === "0" && Auth::user()->role == 1)
                                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{$item['id']}}">
                                                        Konfirmasi
                                                    </button>

                                                    <!-- Modal Konfirmasi -->
                                                    <div class="modal fade" id="konfirmasiModal{{$item['id']}}" tabindex="-1" aria-labelledby="modalLabel{{$item['id']}}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                    <form action="{{ route('pegawai.pegawai.incentive.dibayar', ['id' => $item['id'], 'inId' => $d->id]) }}" method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="modalLabel{{$item['id']}}">Konfirmasi Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                        Apakah Anda yakin ingin mengonfirmasi pembayaran untuk user ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Konfirmasi</button>
                                                        </div>
                                                    </div>
                                                    </form>
                                                    </div>
                                                    </div>
                                                    @endif

                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach


                            </td>
                            
                        </tr>
                        @endforeach

                        
                        <tr>
                            <td colspan="3" class="text text-center">TOTAL</td>
                            <td id="total-nominal">Rp. 0,00</td>
                        </tr>

                        <script>
    function extractCurrencyFromText(text) {
        // Cari angka setelah "Rp." lalu ubah ke float
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

    document.addEventListener('DOMContentLoaded', function () {
        let total = 0;

        document.querySelectorAll('.nominal-value').forEach(el => {
            total += extractCurrencyFromText(el.textContent.trim());
        });

        // Tampilkan ke elemen <td id="total-nominal">
        const totalCell = document.getElementById('total-nominal');
        if (totalCell) {
            totalCell.textContent = formatCurrency(total);
        }
    });
</script>

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
    function filterTable() {
        const fromDate = new Date(document.getElementById('from').value);
        const toDate = new Date(document.getElementById('to').value);
        const rows = document.querySelectorAll('#incentive-table-body tr');

        rows.forEach(row => {
            const tanggalCell = row.querySelector('.tanggal');
            if (!tanggalCell) return;

            const rowDate = new Date(tanggalCell.textContent.trim());
            const show =
                (!isNaN(fromDate.getTime()) ? rowDate >= fromDate : true) &&
                (!isNaN(toDate.getTime()) ? rowDate <= toDate : true);

            row.style.display = show ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('from').value = '';
        document.getElementById('to').value = '';
        filterTable();
    }
</script>
@endsection
