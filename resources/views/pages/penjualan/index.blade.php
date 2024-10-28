@extends('layout.admin.main')
@section('title')
    Komisi Penjualan || SBM {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Komisi Penjualan</h5>
                            <p class="mb-4">
                                You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in your profile.
                            </p>

                            <a href="{{ route('pegawai.komisi.add') }}" class="btn btn-sm btn-outline-primary">Tambah Komisi Penjualan</a>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img
                                src="{{ asset('vendor/assets/img/illustrations/man-with-laptop-light.png') }}"
                                height="140"
                                alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Daftar Komisi Penjualan</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Job Card</th>
                        <th>No PO</th>
                        <th>Seling Price</th>
                        <th>Bottom Price</th>
                        <th>GP</th>
                        <th>IT</th>
                        <th>Sales Enginer</th>
                        <th>Aplication Service</th>
                        <th>Administration</th>
                        <th>Manager</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($komisis as $komisi)
                        <tr>
                          <td>{{$loop->iteration}}</td>
                            <td>{{ $komisi->no_jobcard }}</td>
                            <td>{{ $komisi->no_po }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                              <a href="" class="btn btn-success ml-2 mb-2"><i class="bx bxs-edit-alt"></i></a>
                              <a href="" class="btn btn-warning ml-2 mb-2"><i class="bx bxs-printer"></i></a>
                              <a href="" class="btn btn-danger ml-2 mb-2"><i class="bx bxs-trash"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
