<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laravel SB Admin 2">
    <meta name="author" content="Alejandro RH">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png">

    @stack('css')
    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            font-size: 16px;
            width: 100%;
        }
    
        .select2-container--default .select2-selection--single {
            padding-top: 5px;
            border-color: #CED4DA;
        }
    
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #51585E;
        }
    
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            margin-top: 5px;
        }
    </style>
</head>
<body id="page-top">
    <div id="loading" style="display: none; margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.8;">
        <p style="position: absolute; color: White; top: 50%; left: 45%;">
            <img src="{{ asset('img/ajax-loader.gif') }}">
        </p>
    </div>

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
                <div class="sidebar-brand-icon">
                    MWT
                </div>
                <div class="sidebar-brand-text mx-2">Portal</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Nav::isRoute('home') }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span></a>
            </li>

            <li class="nav-item {{ Nav::isRoute('part.index') }}">
                <a class="nav-link" href="{{ route('part.index') }}">
                    <i class="fa fa-archive" aria-hidden="true"></i>
                    <span>{{ __('Master Part') }}</span></a>
            </li>

            <li class="nav-item {{ Nav::isRoute('customer.index') }}">
                <a class="nav-link" href="{{ route('customer.index') }}">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>{{ __('Master Customer') }}</span></a>
            </li>

            <li class="nav-item {{ Nav::isRoute('gate.index') }}">
                <a class="nav-link" href="{{ route('gate.index') }}">
                    <i class="fa fa-road" aria-hidden="true"></i>
                    <span>{{ __('Master Gate') }}</span></a>
            </li>

            <li class="nav-item {{ Nav::isRoute('statusout.index') }}">
                <a class="nav-link" href="{{ route('statusout.index') }}">
                    <i class="fa fa-info" aria-hidden="true"></i>
                    <span>{{ __('Master Status Out') }}</span></a>
            </li>

            <li class="nav-item {{ Nav::isRoute('stock-fg.index') }}">
                <a class="nav-link" href="{{ route('stock-fg.index') }}">
                    <i class="fa fa-check-square" aria-hidden="true"></i>
                    <span>{{ __('Stock Finish Good') }}</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('Settings') }}
            </div>

            {{-- <!-- Nav Item -->
            <li class="nav-item {{ Nav::isRoute('basic.index') }}">
                <a class="nav-link" href="{{ route('basic.index') }}">
                    <i class="fas fa-fw fa-plus"></i>
                    <span>{{ __('Basic CRUD') }}</span>
                </a>
            </li> --}}

            <!-- Nav Item - Profile -->
            <li class="nav-item {{ Nav::isRoute('profile') }}">
                <a class="nav-link" href="{{ route('profile') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('Profile') }}</span>
                </a>
            </li>

            {{-- <!-- Nav Item - About -->
            <li class="nav-item {{ Nav::isRoute('about') }}">
                <a class="nav-link" href="{{ route('about') }}">
                    <i class="fas fa-fw fa-hands-helping"></i>
                    <span>{{ __('About') }}</span>
                </a>
            </li>

            <!-- Nav Item -->
            <li class="nav-item {{ Nav::isRoute('blank') }}">
                <a class="nav-link" href="{{ route('blank') }}">
                    <i class="fas fa-fw fa-book"></i>
                    <span>{{ __('Blank Page') }}</span>
                </a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <body onload="tampilkanWaktuheader()">
                        <small id="waktu-header"></small>
                    </body>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <figure class="img-profile rounded-circle avatar font-weight-bold" data-initial="{{ Auth::user()->name[0] }}"></figure>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{route('laratrust.roles.index')}}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Role Managament') }}
                                </a>
                                <a class="dropdown-item" href="{{route('logs')}}">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('App Logs') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @stack('notif')
                    @yield('main-content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Portal MWT 2023</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function tampilkanWaktuheader() {
            let waktu = new Date();
            let hari = waktu.getDay();
            let tanggal = waktu.getDate();
            let tanggalpad = tanggal.toString().padStart(2, "0");
            let bulan = waktu.getMonth();
            let tahun = waktu.getFullYear();
            let jam = waktu.getHours();
            let jampad = jam.toString().padStart(2, "0");
            let menit = waktu.getMinutes();
            let menitpad = menit.toString().padStart(2, "0");
            let detik = waktu.getSeconds();
            let detikpad = detik.toString().padStart(2, "0");

            // Daftar nama hari dan bulan
            let daftarHari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            let daftarBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            // Tampilkan hasil dalam bentuk teks
            let hasil = daftarHari[hari] + ", " + tanggalpad + " " + daftarBulan[bulan] + " " + tahun + "  " + jampad + ":" + menitpad + ":" + detikpad;
            // Masukkan hasil ke dalam elemen dengan id="waktu"
            document.getElementById("waktu-header").innerHTML = hasil;

            // Setel agar fungsi tampilkanWaktuheader dipanggil setiap 1 detik
            setTimeout(tampilkanWaktuheader, 1000);
        }
        $('.select2').select2({
            placeholder: "Select"
        });
    </script>
    @stack('js')
</body>
</html>
