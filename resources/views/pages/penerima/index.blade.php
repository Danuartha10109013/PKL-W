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
                            <th>NAMA CUSTOMER</th>
                            <th>No. PO</th>
                            <th>No. JO</th>
                            <th>{{$division}}</th>
                        </tr>
                    </thead>
                    <tbody id="incentive-table-body">
                    @foreach ($data as $index => $d)
                        @php
                            $divisionMap = [
                                'Sales Enginer' => 'penerimase',
                                'Aplication Service' => 'penerimaas',
                                'Administration' => 'penerimaadm',
                                'Manager' => 'penerimamng',
                            ];

                            $status = 'Tidak Ditemukan';
                            $bayar = 'Belum Dibayar';

                            $userDivision = Auth::user()->division;
                            $userId = Auth::user()->id;

                            if (isset($divisionMap[$userDivision])) {
                                $field = $divisionMap[$userDivision];
                                $dataJson = json_decode($d->$field ?? '[]', true);

                                if (is_array($dataJson)) {
                                    foreach ($dataJson as $item) {
                                        if (isset($item['id']) && $item['id'] == $userId) {
                                            $status = isset($item['status']) && $item['status'] == 1 ? 'Terkonfirmasi' : 'Belum dikonfirmasi';
                                            $bayar = isset($item['dibayar']) && $item['dibayar'] == 1 ? 'Sudah Dibayar' : 'Belum Dibayar';
                                            break;
                                        }
                                    }
                                }
                            }


                            $amount = match($userDivision) {
                                'Sales Enginer' => $d->se,
                                'Aplication Service' => $d->as,
                                'Administration' => $d->adm,
                                'Manager' => $d->mng,
                                default => 0,
                            };
                            // dd($penerimaCount);
                            if($penerimaCount == 0){
                                $formattedAmount = 'Rp. 0' ;
                                
                            }else{
                                $formattedAmount = 'Rp. ' . number_format($amount / $penerimaCount, 2, ',', '.');

                            }
                            $showButtons = $index === 0 ? '' : 'd-none';

                        @endphp

                        <tr class="data-row" data-index="{{ $index }}" data-status="{{ $status }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="tanggal">{{ $d->created_at->format('d M Y') }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $d->profile) }}" width="35px" alt="">
                                {{ $d->no_it }}
                            </td>
                            <td>{{ $d->customer_name }}</td>
                            <td>{{ $d->no_po }}</td>
                            <td>{{ $d->no_jo }}</td>
                            <td class="amount-cell">{{ $formattedAmount }}

                                <div class="status-cell mt-2">
                                    @if ($bayar === 'Sudah Dibayar')
                                        <p class="text-success text-left">{{ $bayar }}</p>
                                        @if ($status === 'Belum dikonfirmasi')
                                            <a href="{{ route('penerima.incentive.confirmation', ['id' => Auth::user()->id , 'inId' => $d->id]) }}" class="btn btn-primary mt-2 {{ $showButtons }}">Konfirmasi Dibayar</a>
                                        @endif
                                    @elseif ($bayar === 'Belum Dibayar')
                                        <strong>Status:</strong> {{ $bayar }} <br>
                                        <a href="#" class="btn btn-danger mb-2 mt-2 {{ $showButtons }}" data-bs-toggle="modal" data-bs-target="#catatanModal{{ $d->id }}">
                                            Kirim Catatan
                                        </a>

                                        <div class="modal fade {{ $showButtons }}" id="catatanModal{{ $d->id }}" tabindex="-1" aria-labelledby="catatanModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form action="{{ route('penerima.incentive.catatan', ['id' => Auth::user()->id , 'inId' => $d->id]) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="catatanModalLabel">Kirim Catatan</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="catatan" class="form-label">Catatan</label>
                                                                <textarea class="form-control" name="catatan" id="catatan" rows="4" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-primary">Kirim</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        
                                    @else
                                        <p class="text-muted text-center">{{ $status }}</p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    <tr><td colspan="7"></td></tr>
                    <tr><td colspan="7"></td></tr>
                    <tr>
                        <td colspan="6" class="text text-center">TOTAL</td>
                        <td>Rp. {{ number_format($sum, 2, ',', '.') }}</td>
                    </tr>
                </tbody>

                {{-- JavaScript untuk menampilkan tombol pada baris selanjutnya jika sebelumnya sudah dibayar --}}
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const rows = document.querySelectorAll('.data-row');
                    if (rows.length < 2) return;

                    const firstRow = rows[0];
                    const firstStatus = firstRow.getAttribute('data-status');

                    for (let i = 1; i < rows.length; i++) {
                        const row = rows[i];
                        const cell = row.querySelector('.status-cell');

                        if (firstStatus !== 'Terkonfirmasi') {
                            cell.innerHTML = '<p class="text-warning text-center">Menunggu konfirmasi data sebelumnya</p>';
                        } else {
                            const hiddenButtons = cell.querySelectorAll('.d-none');
                            hiddenButtons.forEach(btn => btn.classList.remove('d-none'));
                        }
                    }
                });
                </script>


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
    const fromDateInput = document.getElementById('from').value;
    const toDateInput = document.getElementById('to').value;
    const rows = document.querySelectorAll('.data-row');

    const fromDate = fromDateInput ? new Date(fromDateInput) : null;
    const toDate = toDateInput ? new Date(toDateInput) : null;
    if (fromDate) fromDate.setHours(0, 0, 0, 0);
    if (toDate) toDate.setHours(23, 59, 59, 999);

    let total = 0;

    rows.forEach(row => {
        const tanggalCell = row.querySelector('.tanggal');
        const amountCell = row.querySelector('.amount-cell');
        if (!tanggalCell || !amountCell) return;

        const rowDate = new Date(tanggalCell.textContent.trim());

        let show = true;
        if (fromDate && rowDate < fromDate) show = false;
        if (toDate && rowDate > toDate) show = false;

        row.style.display = show ? '' : 'none';

        if (show) {
            const text = amountCell.textContent.trim();
            const match = text.match(/Rp\.?\s?([\d.,]+)/);
            if (match) {
                total += parseFloat(match[1].replace(/\./g, '').replace(',', '.')) || 0;
            }
        }
    });

    const totalCell = document.querySelector('#total-nominal');
    if (totalCell) {
        totalCell.textContent = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(total);
    }
}
</script>

@endsection
