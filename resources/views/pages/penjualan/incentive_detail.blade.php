@extends('layout.admin.main')

@section('title')
    Detail Incentive || {{ Auth::user()->name }}
@endsection

@section('content')
    <h3 class="text-center text-fw-bold">
        Incentive Detail {{$data->no_it}}
    </h3>

    <div class="container">
        @php
            $divisionMap = [
                'Sales Enginer' => ['penerima' => 'penerimase', 'nominal' => 'se'],
                'Aplication Service' => ['penerima' => 'penerimaap', 'nominal' => 'as'],
                'Administration' => ['penerima' => 'penerimaadm', 'nominal' => 'adm'],
                'Manager' => ['penerima' => 'penerimamng', 'nominal' => 'mng'],
            ];
        @endphp
        @foreach ($divisionMap as $division => $fields)
            <h4 >{{$loop->iteration}}. {{$division}}</h4>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered mb-4">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>Nama</th>
                                    <th>Status Pembayaran</th>
                                    <th>Bukti Pembayaran</th>
                                    <th>Status Penerima</th>
                                    <th>Bukti Terima</th>
                                    <th>Catatan</th>
                                    <th>Total Incentive team</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="incentive-table-body">
                                @php
                                    $penerimaList = json_decode($data->{$fields['penerima']} ?? '[]', true);
                                    $totalPenerima = is_array($penerimaList) ? count($penerimaList) : 0;
                                    $totalNominal = $data->{$fields['nominal']} ?? 0;
                                    $nominalPerOrang = $totalPenerima > 0 ? $totalNominal / $totalPenerima : 0;
                                @endphp
                                @foreach ($penerimaList as $item)
                                    <tr>
                                        @php
                                            $user = \App\Models\User::find($item['id']);
                                            $userName = $user ? $user->name : 'Tidak Diketahui';
                                        @endphp
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$userName}}</td>
                                        <td>
                                            {{ isset($item['dibayar']) && $item['dibayar'] == "1" ? 'Sudah' : 'Belum Dibayarkan' }}
                                        </td>
                                        <td>
                                            @if (isset($item['bukti_kirim']) && $item['bukti_kirim'])
                                                <img src="{{asset($item['bukti_kirim'])}}" width="80px" alt="bukti kirim">
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            {{ isset($item['status']) && $item['status'] == 1 ? 'Terkonfirmasi' : 'Belum dikonfirmasi' }} <br>
                                        </td>
                                        <td>
                                            @if (isset($item['bukti_terima']) && $item['bukti_terima'])
                                                <img src="{{asset($item['bukti_terima'])}}" width="80px" alt="bukti terima">
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($item['catatan']))
                                                <span class="text text-danger">{{ $item['catatan'] }}</span>
                                            @else
                                                <span class="text text-dark">N/A</span>
                                            @endif
                                        </td>
                                        <td>Rp. {{ number_format($nominalPerOrang, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($item['dibayar'] === "0" && Auth::user()->role == 1)
                                                <button class="btn btn-primary" title="Konfirmasi Pembayaran untuk {{$userName}}" data-bs-toggle="modal" data-bs-target="#konfirmasiModal{{$item['id']}}">
                                                    <i class="bx bx-check"></i>
                                                </button>

                                                    <!-- Modal Konfirmasi -->
                                                <div class="modal fade" id="konfirmasiModal{{$item['id']}}" tabindex="-1" aria-labelledby="modalLabel{{$item['id']}}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <form action="{{ route('pegawai.pegawai.incentive.dibayar', ['id' => $item['id'], 'inId' => $data->id]) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="modalLabel{{$item['id']}}">Konfirmasi Pembayaran</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Apakah Anda yakin ingin mengonfirmasi pembayaran untuk user ini?</p>
                                                                    
                                                                    <div class="mb-3">
                                                                        <label for="bukti_pembayaran_{{$item['id']}}" class="form-label">Upload Bukti Pembayaran</label>
                                                                        <input type="file" class="form-control" id="bukti_pembayaran_{{$item['id']}}" name="bukti_pembayaran" accept="image/*" required>
                                                                    </div>
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
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
