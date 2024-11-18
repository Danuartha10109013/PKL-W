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
            <table class="table table-striped table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>No Job Card</th>
                        <th>No PO</th>
                        <th>Bottom Price</th>
                        <th>GP</th>
                        <th>IT</th>
                        <th>Sales Engineer</th>
                        <th>Application Service</th>
                        <th>Administration</th>
                        <th>Manager</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($komisis as $komisi)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $komisi->no_jobcard }}</td>
                            <td>{{ $komisi->no_po }}</td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->bop, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->gp, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->it, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->se, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->as, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->adm, 0, ',', '.') }}</span></td>
                            <td><span style="white-space: nowrap;">Rp&nbsp;{{ number_format($komisi->mng, 0, ',', '.') }}</span></td>
                            <td class="d-flex justify-content-center">
                                <button class="btn btn-success btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $komisi->id }}"><i class="bx bxs-edit-alt"></i></button>
                                <a href="{{route('pegawai.komisi.print',$komisi->id)}}" class="btn btn-warning"><i class="bx bxs-printer"></i></a>
                                <button class="btn btn-danger btn-sm mx-1" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $komisi->id }}"><i class="bx bxs-trash"></i></button>
                            </td>
                        </tr>
                        
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $komisi->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel">Edit Komisi Penjualan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('pegawai.komisi.update', $komisi->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <!-- Add your input fields here, pre-filled with $komisi data -->
                                            <div class="mb-3">
                                                <label for="no_po" class="form-label">No PO</label>
                                                <input type="text" name="no_po" id="no_po" class="form-control" value="{{ $komisi->no_po }}">
                                            </div>
                                            <!-- Repeat for other fields as needed -->
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Print Modal -->
                        <div class="modal fade" id="printModal{{ $komisi->id }}" tabindex="-1" aria-labelledby="printModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="printModalLabel">Print Komisi Penjualan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to print this Komisi Penjualan?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="{{ route('pegawai.komisi.print', $komisi->id) }}" class="btn btn-warning">Print</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $komisi->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Delete Komisi Penjualan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete this Komisi Penjualan?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <form action="{{ route('pegawai.komisi.delete', $komisi->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
