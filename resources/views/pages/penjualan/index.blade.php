@extends('layout.admin.main')
@section('title')
    Komisi Penjualan || SBM {{Auth::user()->name}}
@endsection
@section('content')
{{-- <div class="container-xxl flex-grow-1 container-p-y"> --}}
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
          <div class="card">
            <div class="d-flex align-items-end row">
              <div class="col-sm-7">
                <div class="card-body">
                  <h5 class="card-title text-primary">Komisi Penjualan</h5>
                  <p class="mb-4">
                    You have done <span class="fw-bold">72%</span> more sales today. Check your new badge in
                    your profile.
                  </p>
    
                  <a href="{{route('pegawai.komisi.add')}}" class="btn btn-sm btn-outline-primary">Tambah Komisi enjualan</a>
                </div>
              </div>
              <div class="col-sm-5 text-center text-sm-left">
                <div class="card-body pb-0 px-0 px-md-4">
                  <img
                    src="{{asset('vendor')}}/assets/img/illustrations/man-with-laptop-light.png"
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
{{-- </div> --}}
@endsection