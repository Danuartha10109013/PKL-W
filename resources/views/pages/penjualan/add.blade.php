@extends('layout.admin.main')

@section('title')
    Add Komisi Penjualan || SBM {{ Auth::user()->name }}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 order-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Komisi Penjualan</h5>

                    <form action="{{route('pegawai.komisi.store')}}" method="POST">
                        @csrf

                        <!-- Search Job Card -->
                        <div class="mb-3">
                            <label for="jobCardSearch" class="form-label">Search Job Card</label>
                            <div class="input-group">
                                <input
                                    type="text"
                                    name="job_card_search"
                                    id="jobCardSearch"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Search Job Card..."
                                    list="jobCardSuggestions"
                                    autocomplete="off"
                                />
                                <datalist id="jobCardSuggestions"></datalist>
                                <button type="button" class="btn btn-primary" id="searchBtn">Search</button>
                            </div>
                        </div>

                        <!-- Job Card Details -->
                        <div id="jobCardDetailsForm" style="display: none;" class="mb-4">
                            <h6 class="card-subtitle mb-2 text-muted">Job Card Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jobCardNumberInput" class="form-label">Job Card Number</label>
                                        <input
                                            type="text"
                                            name="no_jobcard"
                                            id="jobCardNumberInput"
                                            class="form-control" readonly
                                        />
                                        <small id="jobcardWarning" class="text-danger d-none">
                                            Komisi untuk no jobcard ini sudah dibuat.
                                        </small>
                                    </div>
                                    
                                    
                                    
                                    
                                    <div class="mb-3">
                                        <label for="totalbop" class="form-label">Total BOP</label>
                                        <input
                                            type="text"
                                            name="totalbop"
                                            id="totalbop"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="totalsp" class="form-label">Total SP</label>
                                        <input
                                            type="text"
                                            name="totalsp"
                                            id="totalsp"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="totalbp" class="form-label">Total BP</label>
                                        <input
                                            type="text"
                                            name="totalbp"
                                            id="totalbp"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="po_date" class="form-label">PO Date</label>
                                        <input
                                            type="text"
                                            name="po_date"
                                            id="po_date"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_form" class="form-label">No Form</label>
                                        <input
                                            type="text"
                                            name="no_form"
                                            id="no_form"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="effective_date" class="form-label">Effective Date</label>
                                        <input
                                            type="text"
                                            name="effective_date"
                                            id="effective_date"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="po_received" class="form-label">PO Received</label>
                                        <input
                                            type="text"
                                            name="po_received"
                                            id="po_received"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="jobCardOtherDetailInput" class="form-label">Customer Name</label>
                                        <input
                                            type="text"
                                            name="customer_name"
                                            id="jobCardOtherDetailInput"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="kurs" class="form-label">Kurs</label>
                                        <input
                                            type="text"
                                            name="kurs"
                                            id="kurs"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="jobCardDescriptionInput" class="form-label">Date</label>
                                        <input
                                            type="text"
                                            name="date"
                                            id="jobCardDescriptionInput"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_po" class="form-label">No PO</label>
                                        <input
                                            type="text"
                                            name="no_po"
                                            id="no_po"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_revisi" class="form-label">No Revisi</label>
                                        <input
                                            type="text"
                                            name="no_revisi"
                                            id="no_revisi"
                                            class="form-control"
                                            readonly
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_revisi" class="form-label">No IT</label>
                                        <input
                                            type="text"
                                            name="no_it"
                                            id="no_revisi"
                                            class="form-control"
                                            required
                                        />
                                    </div>
                                <div class="mb-3">
                                    <label for="no_revisi" class="form-label">Pnerima Incentive Sales Enginer 3 </label>
                                    
                                    <select name="sales_name" 
                                    id="no_revisi"
                                    class="form-control"
                                    required>
                                        <option value="" selected disabled>--Pilih Penerima--</option>
                                        @php
                                            $penerima = \App\Models\User::where('role',2)->get()
                                        @endphp
                                        @foreach ($penerima as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="no_revisi" class="form-label">Pnerima Incentive Aplication Service ?</label>
                                    
                                    <select name="sales_name" 
                                    id="no_revisi"
                                    class="form-control"
                                    required>
                                        <option value="" selected disabled>--Pilih Penerima--</option>
                                        @php
                                            $penerima = \App\Models\User::where('role',2)->get()
                                        @endphp
                                        @foreach ($penerima as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="no_revisi" class="form-label">Pnerima Incentive Administration 3</label>
                                    
                                    <select name="sales_name" 
                                    id="no_revisi"
                                    class="form-control"
                                    required>
                                        <option value="" selected disabled>--Pilih Penerima--</option>
                                        @php
                                            $penerima = \App\Models\User::where('role',2)->get()
                                        @endphp
                                        @foreach ($penerima as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="no_revisi" class="form-label">Pnerima Incentive Manager 1</label>
                                    
                                    <select name="sales_name" 
                                    id="no_revisi"
                                    class="form-control"
                                    required>
                                        <option value="" selected disabled>--Pilih Penerima--</option>
                                        @php
                                            $penerima = \App\Models\User::where('role',2)->get()
                                        @endphp
                                        @foreach ($penerima as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="button-subbmit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    {{-- JavaScript to handle AJAX suggestions and detail fetching --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById('jobCardSearch').addEventListener('input', function () {
        let query = this.value;

        if (query.length > 1) {
            fetch(`/pegawai/komisi/jobcards/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let suggestions = document.getElementById('jobCardSuggestions');
                    suggestions.innerHTML = ''; // Kosongkan suggestion sebelumnya

                    if (data.error) {
                        alert(data.error);
                    } else {
                        data.forEach(item => {
                            let option = document.createElement('option');
                            option.value = item.no_jobcard;
                            suggestions.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error fetching job card data:', error));
        }
    });

    document.getElementById('jobCardSearch').addEventListener('change', function () {
        let selectedJobCard = this.value;

        fetch(`/pegawai/komisi/jobcards/details?no_jobcard=${selectedJobCard}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch job card details');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    // Tampilkan data ke form
                    document.getElementById('jobCardDetailsForm').style.display = 'block';
                    document.getElementById('jobCardNumberInput').value = data.no_jobcard;
                    document.getElementById('jobCardDescriptionInput').value = data.date;
                    document.getElementById('jobCardOtherDetailInput').value = data.customer_name;
                    document.getElementById('kurs').value = data.kurs;
                    document.getElementById('no_po').value = data.no_po;
                    document.getElementById('po_date').value = data.po_date;
                    document.getElementById('po_received').value = data.po_received;
                    document.getElementById('totalsp').value = data.totalsp;
                    document.getElementById('totalbop').value = data.totalbop;
                    document.getElementById('totalbp').value = data.totalbp;
                    document.getElementById('no_form').value = data.no_form;
                    document.getElementById('effective_date').value = data.effective_date;
                    document.getElementById('no_revisi').value = data.no_revisi;

                    // 🔁 Cek apakah komisi sudah dibuat untuk jobcard ini
                    fetch(`/cek-jobcard?no_jobcard=${data.no_jobcard}`)
                        .then(response => response.json())
                        .then(check => {
                            if (check.sudah) {
                                document.getElementById('jobcardWarning').classList.remove('d-none');
                                document.getElementById('button-subbmit').disabled = true;
                            } else {
                                document.getElementById('jobcardWarning').classList.add('d-none');
                                document.getElementById('button-subbmit').disabled = false;
                            }
                        })
                        .catch(error => {
                            console.error('Error checking jobcard:', error);
                            alert("Gagal memeriksa jobcard.");
                        });
                }
            })
            .catch(error => {
                console.error('Error fetching job card details:', error);
                alert("Terjadi kesalahan saat mengambil data jobcard.");
            });
    });
</script>
<div class="card mt-4">
    <div class="card-header">List Jobcard</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>NO</th>
                        <th>NO Jobcard</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="jobcard-table-body">
                    <!-- Data dari JS -->
                </tbody>
            </table>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetch('/pegawai/komisi/jobcards/load')
            .then(response => response.json())
            .then(data => {
                let tbody = document.getElementById('jobcard-table-body');
                tbody.innerHTML = '';

                data.forEach((item, index) => {
                    let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.no_jobcard}</td>
                            <td class="${item.status === 'Sudah dibuat' ? 'text-success' : 'text-danger'}">
                                ${item.status}
                            </td>
                        </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => {
                console.error('Gagal memuat data:', error);
                alert("Gagal mengambil data jobcard.");
            });
    });
</script>

@endsection
