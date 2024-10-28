<!DOCTYPE html>

<!-- =========================================================
* Custom Login Page - PT. Bersama Sahabat Makmur
============================================================== -->

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{asset('vendor')}}/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Login || PT. Bersama Sahabat Makmur</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('PT. Bersama Sahabat Makmur Logo.png')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap"
      rel="stylesheet"
    />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('vendor')}}/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="{{asset('vendor')}}/assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="{{asset('vendor')}}/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('vendor')}}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{asset('vendor')}}/assets/vendor/css/pages/page-auth.css" />

    <!-- Custom Styling -->
    <style>
      body {
        background: linear-gradient(135deg, rgba(0, 0, 255, 0.1), rgba(255, 0, 0, 0.1));
      }
      .card {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      }
      .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, border-color 0.3s ease;
      }
      .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
      }
      .form-label {
        color: #007bff;
      }
      .app-brand-logo img {
        width: 80px;
        border-radius: 50%;
        border: 2px solid #ff0000;
      }
      .alert-box {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
      }
    </style>

    <!-- Helper JS -->
    <script src="{{asset('vendor')}}/assets/vendor/js/helpers.js"></script>
  </head>

  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class=" justify-content-center d-flex flex-column align-items-center mb-4">
                <a href="index.html" class="-link gap-2 text-center">
                  <span class="-logo demo">
                    <img src="{{asset('PT. Bersama Sahabat Makmur Logo.png')}}" width="20%" alt="Company Logo">
                  </span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2 text-center text-primary">PT. Bersama Sahabat Makmur 👋</h4>
              <p class="mb-4 text-center">Please sign-in to your account</p>

              <form id="formAuthentication" class="mb-3" action="{{route('login-proses')}}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email or Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="username"
                    placeholder="Enter your email or username"
                    autofocus
                  />
                </div>
                @error('username')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>

                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="••••••••••"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  @error('password')
                  <small class="text-danger">{{ $message }}</small>
                  @enderror
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /Login -->
        </div>
      </div>
    </div>

    <!-- Custom Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const loginButton = document.querySelector('button[type="submit"]');
        
        @if ($message = Session::get('success'))
          Swal.fire({
            title: 'Success!',
            text: '{{ $message }}',
            icon: 'success',
            confirmButtonColor: '#007bff'
          });
        @endif

        @if ($message = Session::get('failed'))
          Swal.fire({
            title: 'Error!',
            text: '{{ $message }}',
            icon: 'error',
            confirmButtonColor: '#ff0000'
          });
        @endif
      });
    </script>

    <!-- Core JS -->
    <script src="{{asset('vendor')}}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{asset('vendor')}}/assets/vendor/js/bootstrap.js"></script>
  </body>
</html>
