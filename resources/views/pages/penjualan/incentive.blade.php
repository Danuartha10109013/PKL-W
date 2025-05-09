@extends('layout.admin.main')

@section('title')
    Kelola User || {{ Auth::user()->name }}
@endsection

@section('content')
<div class="mb-3">
    <form action="{{ route('direktur.komisi') }}" method="GET" class="d-flex align-items-center">
        <div class="me-2">
            <label for="from" class="form-label">Dari Tanggal</label>
            <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
        </div>
        <div class="me-2">
            <label for="to" class="form-label">Sampai Tanggal</label>
            <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Filter</button>
        </div>
    </form>
</div>


    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Laporan Komisi Penjualan</h5>
            <a href="{{ route('direktur.komisi.print', ['from' => request('from'), 'to' => request('to')]) }}" class="btn btn-warning">
                <i class="bx bxs-printer"></i>
            </a>
            
        </div>
        
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
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->created_at->format('d M Y') }}</td>
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
                                        <h5>{{ $division . ' Total => Rp. ' . number_format($d->{$fields['nominal']} ?? 0, 0, ',', '.') }}</h5>

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
                                                    <strong>Status:</strong> {{ isset($item['status']) && $item['status'] == 1 ? 'Terkonfirmasi' : 'Belum dikonfirmasi' }} <br>
                                                    <strong>Pembayaran:</strong>{{ isset($item['dibayar']) && $item['dibayar'] == "1" ? 'Sudah' : 'Belum Dibayarkan' }} <br>
                                                    <!-- Tombol untuk membuka modal -->
                                                    @if ($item['dibayar'] === "0")
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
                            <td colspan="6" class="text text-center">TOTAL</td>
                            
                            <td>Rp. {{ number_format($sum, 2, ',', '.') }}</td>
                        </tr>
    
                    </tbody>
                </table>
            </div>

            @if(request('from') && request('to'))
                <p class="text-muted">Data dari tanggal <strong>{{ request('from') }}</strong> sampai <strong>{{ request('to') }}</strong></p>
            @endif

        </div>
    </div>

@endsection
