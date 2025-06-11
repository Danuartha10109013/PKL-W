<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
    <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
      <i class="bx bx-menu bx-sm"></i>
    </a>
  </div>

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Search -->
    <div class="navbar-nav align-items-center">
      <div class="nav-item d-flex align-items-center">
        PT. BERSAMA SAHABAT MAKMUR 
      </div>
    </div>
    <!-- /Search -->

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Place this tag where you want the button to render. -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

      @if (Auth::user()->role == 1)
      <li class="nav-item dropdown mr-5">
        <a class="nav-link position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell fs-4"></i>
          <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;">
            ●
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notifDropdown" style="width: 300px;">
          <li class="dropdown-header fw-bold">Notifikasi Jobcard</li>
          <li><hr class="dropdown-divider"></li>
          <div id="notifItems" class="px-2 py-1"></div>
        </ul>
      </li>


        <script>
          document.addEventListener("DOMContentLoaded", function () {
            fetch('/pegawai/komisi/notifikasi-jobcard')
              .then(response => response.json())
              .then(data => {
                const badge = document.getElementById("notifBadge");
                const notifItems = document.getElementById("notifItems");

                if (data.count > 0) {
                  badge.style.display = "inline";

                  data.data.forEach(item => {
                    const li = document.createElement("li");
                    li.classList.add("dropdown-item");
                    li.innerHTML  = item.pesan;
                    notifItems.appendChild(li);
                  });
                } else {
                  const li = document.createElement("li");
                  li.classList.add("dropdown-item", "text-muted");
                  li.textContent = "Tidak ada notifikasi baru.";
                  notifItems.appendChild(li);
                }
              })
              .catch(err => {
                console.error("Gagal mengambil notifikasi:", err);
              });
          });
        </script>

      @endif

      <!-- User -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar avatar-online">
            <img src="{{ asset('storage/'.Auth::user()->profile) }}"  alt class="w-px-40 h-auto rounded-circle" />
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
          <li>
            <a class="dropdown-item" href="#">
              <div class="d-flex">
                <div class="flex-shrink-0 me-3">
                  <div class="avatar avatar-online">
                    <img src="{{ asset('storage/'.Auth::user()->profile) }}" alt class="w-px-40 h-auto rounded-circle" />
                  </div>
                </div>
                <div class="flex-grow-1">
                  <span class="fw-semibold d-block">{{Auth::user()->name}}</span>
                  <small class="text-muted">
                    {{
                        Auth::user()->role == 0 ? 'Direktur' :
                        (Auth::user()->role == 1 ? 'Admin' :
                        (Auth::user()->role == 2 ? 'Penerima Incentive' : 'Tidak Dikenal'))
                    }}
                  </small>

                 
                </div>
              </div>
            </a>
          </li>
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{route('profile',Auth::user()->id)}}">
              <i class="bx bx-user me-2"></i>
              <span class="align-middle">My Profile</span>
            </a>
          </li>
          
          <li>
            <div class="dropdown-divider"></div>
          </li>
          <li>
            <a class="dropdown-item" href="{{route('logout')}}">
              <i class="bx bx-power-off me-2"></i>
              <span class="align-middle">Log Out</span>
            </a>
          </li>
        </ul>
      </li>
      <!--/ User -->
    </ul>
  </div>