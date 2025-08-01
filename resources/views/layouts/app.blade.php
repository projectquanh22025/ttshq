<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>User Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="/assets/css/pages/login/login-2.css" rel="stylesheet" />
    <link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" />
    <link href="/assets/css/style.bundle.css" rel="stylesheet" />
    <link rel="shortcut icon" href="/assets/media/logos/favicon.ico" />
</head>
<body class="header-fixed header-mobile-fixed subheader-enabled page-loading">
    <div class="d-flex flex-column flex-root">
        <div class="login login-2 d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
            <div class="login-aside order-2 order-lg-1 d-flex flex-row-auto position-relative overflow-hidden">
                <div class="d-flex flex-column-fluid flex-column justify-content-between py-9 px-7 py-lg-13 px-lg-35">
                    <a href="#" class="text-center pt-2">
                        <img src="/assets/media/logos/logo.png" class="max-h-75px" alt="Logo" />
                    </a>
                    <div class="d-flex flex-column-fluid flex-column flex-center">
                        {{-- Chỉ chỗ này có nội dung --}}
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="content order-1 order-lg-2 d-flex flex-column w-100 pb-0" style="background-color: #B1DCED;">
                <div class="d-flex flex-column justify-content-center text-center pt-lg-40 pt-md-5 pt-sm-5 px-lg-0 pt-5 px-7">
                    <h3 class="display4 font-weight-bolder my-7 text-dark">Amazing Wireframes</h3>
                    <p class="font-weight-bolder font-size-h2-md font-size-lg text-dark opacity-70">User Experience & Interface Design</p>
                </div>
                <div class="content-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center"
                    style="background-image: url(/assets/media/svg/illustrations/login-visual-2.svg);">
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/plugins/global/plugins.bundle.js"></script>
    <script src="/assets/js/scripts.bundle.js"></script>
</body>
</html>
