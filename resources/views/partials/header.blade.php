<head>

    <link rel="shortcut icon" href="{{ URL::to('favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{ URL::to('favicon.ico')}}" type="image/x-icon">
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Fontfaces CSS-->
    <link href="{{ URL::to('css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="{{ URL::to('vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="{{ URL::to('vendor/animsition/animsition.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/wow/animate.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/css-hamburgers/hamburgers.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/slick/slick.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('vendor/perfect-scrollbar/perfect-scrollbar.css')}}" rel="stylesheet" media="all">
    {{-- <link href="{{ URL::to('vendor/DataTables/datatables.min.css')}}" rel="stylesheet" media="all">
    <link href="{{ URL::to('https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css')}}" rel="stylesheet" media="all"> --}}

    <!-- Main CSS-->
    <link href="{{ URL::to('css/theme.css')}}" rel="stylesheet" media="all">


    <!-- Custom CSS -->
    <link href="{{ URL::to('css/style.css')}}" rel="stylesheet" />

{{--JavaScript--}}
<!-- Jquery JS-->
    <script src="{{ URL::to('vendor/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap JS-->
    <script src="{{ URL::to('vendor/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{ URL::to('vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <script src="{{ URL::to('vendor/slick/slick.min.js')}}">
    </script>
    <script src="{{ URL::to('vendor/wow/wow.min.js')}}"></script>
    <script src="{{ URL::to('vendor/animsition/animsition.min.js')}}"></script>
    <script src="{{ URL::to('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js')}}">
    </script>
    <script src="{{ URL::to('vendor/counter-up/jquery.waypoints.min.js')}}"></script>
    <script src="{{ URL::to('vendor/counter-up/jquery.counterup.min.js')}}">
    </script>
    <script src="{{ URL::to('vendor/circle-progress/circle-progress.min.js')}}"></script>
    <script src="{{ URL::to('vendor/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{ URL::to('vendor/chartjs/Chart.bundle.min.js')}}"></script>
    <script src="{{ URL::to('vendor/select2/select2.min.js')}}"></script>
    <script src="{{ URL::to('vendor/DataTables/datatables.min.js')}}"></script>
{{--    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>--}}
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<!-- Custom CSS -->
    <style>
        @yield('cssInject')
    </style>
<!-- Optional JavaScript -->
    <title>@yield('title')</title>
</head>
