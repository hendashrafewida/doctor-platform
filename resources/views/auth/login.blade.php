<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <title>Login | Light Able Admin & Dashboard Template</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="Light Able admin and dashboard template offer a variety of UI elements and pages, ensuring your admin panel is both fast and effective." />
  <meta name="author" content="phoenixcoded" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon" />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}" >
  <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}" >
  <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}" >
  <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}" >
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link" >
  <link rel="stylesheet" href="{{ asset('assets/css/style-preset.css') }}" >
  <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}?v={{ filemtime(public_path('assets/css/style-rtl.css')) }}">

  <style>
    * { font-family: 'Cairo', 'Public Sans', sans-serif; }
    .auth-sidecontent {
      background: linear-gradient(135deg, rgba(8, 56, 107, 0.92), rgba(13, 92, 184, 0.68));
      position: relative;
      overflow: hidden;
    }
    .auth-sidecontent::before {
      content: "";
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at top left, rgba(255,255,255,0.28), rgba(255,255,255,0));
    }
    .auth-sidefooter {
      position: relative;
      z-index: 1;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      padding: 2.5rem;
    }
    .auth-sidecontent .text-white-50,
    .auth-sidecontent .text-white-50:hover {
      color: rgba(255,255,255,0.8) !important;
    }
  </style>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-theme="light" data-pc-sidebar-caption="true" data-pc-direction="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" data-pc-theme="light">
  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>

  <div class="auth-main v2">
    <div class="bg-overlay bg-dark"></div>
    <div class="auth-wrapper">
      <div class="auth-sidecontent">
        <div class="auth-sidefooter">
          <img src="{{ asset('assets/images/logo-dark.svg') }}" class="img-brand img-fluid" alt="images" />
          <hr class="mb-3 mt-4" />
          <div class="row">
            <div class="col my-1">
              <p class="m-0 text-white-50">Made with ♥ by Team <a href="https://themeforest.net/user/phoenixcoded" target="_blank" class="text-white-50">Phoenixcoded</a></p>
            </div>
            <div class="col-auto my-1">
              <ul class="list-inline footer-link mb-0">
                <li class="list-inline-item"><a href="{{ url('/') }}" class="text-white-50">Home</a></li>
                <li class="list-inline-item"><a href="https://pcoded.gitbook.io/light-able/" target="_blank" class="text-white-50">Documentation</a></li>
                <li class="list-inline-item"><a href="https://phoenixcoded.support-hub.io/" target="_blank" class="text-white-50">Support</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="auth-form">
        <div class="card my-5 mx-3">
          <div class="card-body">
            <h4 class="f-w-500 mb-1">Login with your email</h4>
            <p class="mb-3">Don't have an Account? <a href="#" class="link-primary ms-1">Create Account</a></p>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" novalidate>
              @csrf

              <div class="mb-3">
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" id="floatingInput" placeholder="Email Address" required autocomplete="email" autofocus>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="floatingInput1" placeholder="Password" required autocomplete="current-password">
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-flex mt-1 justify-content-between align-items-center">
                <div class="form-check">
                  <input class="form-check-input input-primary" type="checkbox" name="remember" id="customCheckc1" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label text-muted" for="customCheckc1">Remember me?</label>
                </div>
                <a href="#">
                  <h6 class="text-secondary f-w-400 mb-0">Forgot Password?</h6>
                </a>
              </div>

              <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>

            <div class="saprator my-3">
              <span>Or continue with</span>
            </div>

            <div class="text-center">
              <ul class="list-inline mx-auto mt-3 mb-0">
                <li class="list-inline-item">
                  <a href="https://www.facebook.com/" class="avtar avtar-s rounded-circle bg-facebook" target="_blank">
                    <i class="fab fa-facebook-f text-white"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://twitter.com/" class="avtar avtar-s rounded-circle bg-twitter" target="_blank">
                    <i class="fab fa-twitter text-white"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://myaccount.google.com/" class="avtar avtar-s rounded-circle bg-googleplus" target="_blank">
                    <i class="fab fa-google text-white"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/fonts/custom-font.js') }}"></script>
  <script src="{{ asset('assets/js/pcoded.js') }}?v={{ filemtime(public_path('assets/js/pcoded.js')) }}"></script>
  <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
</body>
</html>
