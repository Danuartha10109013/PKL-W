@extends('layout.admin.main')
@section('title')
    Dashboard || SBM {{Auth::user()->name}}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Account Settings /</span> Account</h4>

    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3">
          <li class="nav-item">
            <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Account</a>
          </li>
         
        </ul>
        <div class="card mb-4">
          <h5 class="card-header">Profile Details</h5>
          <!-- Account -->
          <form action="{{route('profile.update')}}" id="formAccountSettings" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type="text" name="user_id" value="{{$data->id}}" hidden>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img
              src="{{ asset('storage/'.$data->profile) }}"
              alt="user-avatar"
              class="d-block rounded"
              height="100"
              width="100"
              id="uploadedAvatar"
          />          
              <div class="button-wrapper">
                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                  <span class="d-none d-sm-block">Upload new photo</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                  <input
                    type="file"
                    id="upload"
                    class="account-file-input"
                    hidden
                    accept="image/png, image/jpeg"
                    name="profile"
                  />
                </label>
                <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                  <i class="bx bx-reset d-block d-sm-none"></i>
                  <span class="d-none d-sm-block">Reset</span>
                </button>

                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 2MB</p>
              </div>
            </div>
          </div>
          <hr class="my-0" />
          <div class="card-body">
              <div class="row">
                <div class="mb-3 col-md-6">
                  <label for="firstName" class="form-label">Full Name</label>
                  <input
                    class="form-control"
                    type="text"
                    id="firstName"
                    name="fullname"
                    value="{{$data->name}}"
                    autofocus
                  />
                </div>
                
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="email"
                    value="{{$data->email}}"
                    placeholder="Email Baru"
                  />
                </div>

                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">Username</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="username"
                    value="{{$data->username}}"
                    placeholder="Username baru"
                  />
                </div>
                
                <div class="mb-3 col-md-6">
                  <label for="email" class="form-label">New Password</label>
                  <input
                    class="form-control"
                    type="text"
                    id="email"
                    name="password"
                    value=""
                    placeholder="new password here"
                  />
                </div>

              </div>
              <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
              </div>
            </form>
          </div>
          <!-- /Account -->
        </div>
       
      </div>
    </div>
  </div>
@endsection