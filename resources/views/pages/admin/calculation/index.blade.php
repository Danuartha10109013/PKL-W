@extends('layout.admin.main')

@section('title')
    Kelola Presentase || {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Daftar Persentase</h5>
        </div>
        
        <div class="container">
            <button class="btn btn-success mb-3" title="Tambah Data Baru" data-bs-toggle="modal" data-bs-target="#addModal">
                +
            </button>
            <!-- Modal Tambah -->
            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form id="addForm">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Persentase</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body row g-2">
                            <div class="col-md-4">
                                <input type="text" name="it" class="form-control percent-input" data-name="it" placeholder="Incentive Team" required>

                            </div>
                            <div class="col-md-4">
                                <input type="text" name="se" class="form-control percent-input" data-name="se" placeholder="Sales Engineer" required>

                            </div>
                            <div class="col-md-4">
                                <input type="text" name="as" class="form-control percent-input" data-name="as" placeholder="Application Service" required>

                            </div>
                            <div class="col-md-4">
                                <input type="text" name="adm" class="form-control percent-input" data-name="adm" placeholder="Administration" required>

                            </div>
                            <div class="col-md-4">
                                <input type="text" name="mng" class="form-control percent-input" data-name="mng" placeholder="Manager" required>

                            </div>
                        </div>
                        <p id="percent-error" class="text-danger mt-2" style="display:none;"></p>
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>

            </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Incentive Team</th>
                            <th>Sales Enginer</th>
                            <th>Aplication Service</th>
                            <th>Administration</th>
                            <th>Manager</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="it">
                                    {{ $d->it }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="se">
                                    {{ $d->se }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="as">
                                    {{ $d->as }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="adm">
                                    {{ $d->adm }}
                                </td>
                                <td contenteditable="true" class="editable" data-id="{{ $d->id }}" data-field="mng">
                                    {{ $d->mng }}
                                </td>
                                <td>
                                    <input
                                        type="checkbox"
                                        class="active-checkbox"
                                        data-id="{{ $d->id }}"
                                        {{ $d->active ? 'checked' : '' }}
                                    />
                                    <td>
                                        <button class="btn btn-sm btn-danger ml-3" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $d->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </td>
                                    <!-- Modal Delete -->
                                    <div class="modal fade" id="deleteModal{{ $d->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $d->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title text text-white" id="deleteModalLabel{{ $d->id }}">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Apakah Anda yakin ingin menghapus data ini?
                                        </div>
                                        <div class="modal-footer">
                                            <form action="{{ route('direktur.calculation.destroy', $d->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                        </div>
                                    </div>
                                    </div>


                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        .editable {
            cursor: pointer;
            background-color: #f8f9fa;
        }

        .editable:focus {
            outline: 2px solid #007bff;
            background-color: #fff;
        }
    </style>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.28/dist/sweetalert2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const editableCells = document.querySelectorAll('.editable');

            editableCells.forEach(cell => {
                let originalContent = '';

                // Save the original content when cell is focused
                cell.addEventListener('focus', function () {
                    originalContent = this.textContent;
                });

                // Handle saving the updated value when focus is lost
                cell.addEventListener('blur', function () {
                    const newValue = this.textContent.trim();
                    const id = this.dataset.id;
                    const field = this.dataset.field;

                    if (newValue !== originalContent) {
                        fetch(`/direktur/calculation/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                id: id,
                                field: field,
                                value: newValue
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Perubahan berhasil disimpan.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            // Kembalikan nilai asli
                            cell.textContent = originalContent;

                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message || 'Terjadi kesalahan saat menyimpan.',
                                icon: 'error',
                                confirmButtonText: 'Coba Lagi'
                            });
                        }
                    })

                        .catch(() => {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyimpan.',
                                icon: 'error',
                                confirmButtonText: 'Coba Lagi'
                            });
                        });
                    }
                });
            });
        });
    </script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll('.active-checkbox');

    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function () {
        const currentId = this.dataset.id;
        const isActive = this.checked ? 1 : 0;

        // Kirim request update status aktif
        fetch(`/direktur/calculation/active`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({
            id: currentId,
            active: isActive
          })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            Swal.fire({
              icon: this.checked ? 'success' : 'info',
              title: this.checked ? 'Diaktifkan' : 'Dinonaktifkan',
              text: this.checked ? 'Status berhasil diaktifkan.' : 'Status berhasil dinonaktifkan.',
              timer: 2000,
              showConfirmButton: false
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Gagal!',
              text: data.message || 'Gagal mengubah status.',
            });
            // Revert state jika gagal
            this.checked = !this.checked;
          }
        })
        .catch(() => {
          Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: 'Terjadi kesalahan saat menyimpan.',
          });
          this.checked = !this.checked;
        });
      });
    });
  });
</script>


<script>
    const percentInputs = document.querySelectorAll('.percent-input');
    const submitBtn = document.querySelector('#addForm button[type="submit"]');
    const percentError = document.getElementById('percent-error');
    let formValues = {};

    percentInputs.forEach(input => {
        input.addEventListener('input', function () {
            let val = this.value.replace('%', '').replace(',', '.');
            val = val.replace(/[^0-9.]/g, '');

            if (val !== '') {
                const floatVal = parseFloat(val);
                if (!isNaN(floatVal)) {
                    this.value = floatVal + '%';
                    formValues[this.dataset.name] = floatVal / 100;
                }
            } else {
                this.value = '';
                formValues[this.dataset.name] = 0;
            }

            validateTotal();
        });
    });

    function validateTotal() {
        const total = Object.values(formValues).reduce((a, b) => a + b, 0);

        if (total > 1) {
            percentError.style.display = 'block';
            percentError.textContent = `Total tidak boleh melebihi 100%. Saat ini: ${(total * 100).toFixed(1)}%`;
            submitBtn.disabled = true;
        } else {
            percentError.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    // Tambahkan juga sebelum submit:
    document.getElementById('addForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const jsonData = {
            _token: '{{ csrf_token() }}'
        };

        for (const [key, val] of Object.entries(formValues)) {
            jsonData[key] = val || 0;
        }

        fetch('/direktur/calculation/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(jsonData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data berhasil ditambahkan.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    document.getElementById('addForm').reset();
                    percentInputs.forEach(i => i.value = '');
                    formValues = {};
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                    modal.hide();
                    location.reload(); // bisa diganti DOM update
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Terjadi kesalahan saat menambah data.',
                });
            }
        })
        .catch(() => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal menghubungi server.',
            });
        });
    });
</script>

@endsection