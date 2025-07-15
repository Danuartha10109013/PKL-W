@extends('layout.admin.main')

@section('title')
    Kelola User || {{ Auth::user()->name }}
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Terjadi kesalahan:</strong>
        <ul class="mb-0 mt-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <div class="mb-3">
        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="bx bx-user-plus"></i> Buat User Baru
        </a>
    </div>


    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Daftar User</h5>
        </div>
        <div class="container">
            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{asset('storage/'.$d->profile)}}" width="35px" alt="">
                                {{ $d->name }}</td>
                            <td>{{ $d->username }}</td>
                            <td>{{ $d->email }}</td>
                            <td>
                                {{ $d->role == '0' ? 'Direktur' : ($d->role == '1' ? 'Admin' : 'Penerima') }}
                                @if ($d->role == '2')
                                    {{$d->division}}
                                @endif
                            </td>
                            
                            <td>{{ $d->active == '1' ? 'Aktif' : 'Tidak Aktif' }}</td>
                            <td>
                                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $d->id }}">
                                    <i class="bx bxs-edit-alt"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteUserModal-{{ $d->id }}">
                                    <i class="bx bxs-trash"></i>
                                </a>
                            </td>
                        </tr>
    
                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUserModal-{{ $d->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('direktur.k-user.update', $d->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nama</label>
                                                <input type="text" class="form-control" id="name" name="name" value="{{ $d->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Username</label>
                                                <input type="text" class="form-control" id="username" name="username" value="{{ $d->username }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="{{ $d->email }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="role" class="form-label">Role</label>
                                                <select class="form-select" id="role" name="role">
                                                    <option value="0" {{ $d->role == '0' ? 'selected' : '' }}>Direktur</option>
                                                    <option value="1" {{ $d->role == '1' ? 'selected' : '' }}>Admin</option>
                                                    <option value="2" {{ $d->role == '2' ? 'selected' : '' }}>Penerima</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="active" class="form-label">Status</label>
                                                <select class="form-select" id="active" name="active">
                                                    <option value="1" {{ $d->active == '1' ? 'selected' : '' }}>Aktif</option>
                                                    <option value="0" {{ $d->active == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password Baru</label>
                                                <input type="password" class="form-control" id="password" name="password"   >
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

    
                        <!-- Delete User Modal -->
                        <div class="modal fade" id="deleteUserModal-{{ $d->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteUserModalLabel">Hapus User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin menghapus user <strong>{{ $d->name }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('direktur.k-user.destroy', $d->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
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
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('direktur.k-user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Profile Image Input -->
                        <div class="mb-3 text-center">
                            <label class="form-label" for="profileImage" style="display: none;">Profile Image</label>
                            <div id="profileImageContainer" style="cursor: pointer; display: inline-block; width: 100px; height: 100px; border-radius: 50%; border: 2px dashed #007bff; overflow: hidden;">
                                <img id="imagePreview" src="#" alt="Image Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            </div>
                            <input type="file" id="profileImage" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(event)">
                        </div>
                        <!-- Other Form Fields -->
                        <div class="mb-3">
                            <label for="newName" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="newName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="newUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="newUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="newEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="newEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="newRole" class="form-label">Role</label>
                            <select class="form-select" id="roleSelect" name="role">
                                <option value="" selected disabled>--Pilih Role--</option>
                                <option value="0">Direktur</option>
                                <option value="1">Admin</option>
                                <option value="2">Penerima</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="divisionGroup" style="display: none;">
                            <label for="divisionSelect" class="form-label">Division</label>
                            <select class="form-select" id="divisionSelect" name="division">
                                <option value="" selected disabled>--Pilih Divisi--</option>
                                <option value="Sales Enginer">Sales Enginer</option>
                                <option value="Aplication Service">Aplication Service</option>
                                <option value="Administration">Administration</option>
                                <option value="Manager">Manager</option>
                            </select>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {
                                const roleSelect = document.getElementById("roleSelect");
                                const divisionGroup = document.getElementById("divisionGroup");
                        
                                roleSelect.addEventListener("change", function () {
                                    if (this.value === "2") {
                                        divisionGroup.style.display = "block";
                                    } else {
                                        divisionGroup.style.display = "none";
                                    }
                                });
                            });
                        </script>
                                                
                        <div class="mb-3">
                            <label for="newActive" class="form-label">Status</label>
                            <select class="form-select" id="newActive" name="active">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="min: 8 Character" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" placeholder="min: 8 Character" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to handle the click event on the circular div
        document.getElementById('profileImageContainer').onclick = function() {
            document.getElementById('profileImage').click();
        }

        // Function to preview the image
        function previewImage(event) {
            const imagePreview = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block'; // Show the image
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.src = '#';
                imagePreview.style.display = 'none'; // Hide the image if no file is selected
            }
        }
    </script>



@endsection
