@extends('layout.admin.main')
@section('title')
    Add Komisi Penjualan || SBM {{ Auth::user()->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 order-0">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        Add Komisi Penjualan
                    </div>
                    <div class="row">
                        <form action="" method="POST">
                            @csrf
                            {{-- search --}}
                            <div class="navbar-nav align-items-start mb-3">
                                <div class="nav-item d-flex align-items-center">
                                    <i class="bx bx-search fs-4 lh-0"></i>
                                    <input
                                        type="text"
                                        name="job_card_search"
                                        class="form-control border-0 shadow-none"
                                        placeholder="Search Job Card..."
                                        aria-label="Search..."
                                    />
                                </div>
                            </div>

                            {{-- Floating label for name --}}
                            <p class="mt-3">Floating label</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <div class="form-floating">
                                            <input
                                                type="text"
                                                name="name"
                                                class="form-control"
                                                id="floatingInput"
                                                placeholder="John Doe"
                                                aria-describedby="floatingInputHelp"
                                            />
                                            <label for="floatingInput">Name</label>
                                            <div id="floatingInputHelp" class="form-text">
                                                We'll never share your details with anyone else.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Additional form field --}}
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <div class="form-floating">
                                            <input
                                                type="text"
                                                name="additional_field"
                                                class="form-control"
                                                id="additionalField"
                                                placeholder="Additional Info"
                                            />
                                            <label for="additionalField">Additional Info</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>     
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
