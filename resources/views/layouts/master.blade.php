@php
    $segment1 = Request::segment(2);
    $segment2 = Request::segment(3);
    if ($segment2) {
        $active = $segment2;
    } else {
        $active = $segment1;
    }
@endphp

<!doctype html>
<html lang="en">
@include('partials.header')
<body class="skin-default-dark">
<div id="main-wrapper">
    @include('partials.headermobile')
    @include('partials.sidebar')
    <div class="page-container">
        <!-- HEADER DESKTOP-->
        @include('partials.headerdekstop')
        <!--END HEADER DESKTOP-->

        <!-- MAIN CONTENT-->
        <div class="main-content">
            <div class="row justify-content-between page-titles">
                <div class="col-xs-2 align-self-center">
                    <h4 class="text-themecolor text-capitalize">{{ $segment1 }}</h4>
                </div>
                <div class="col-xs-3 align-self-center text-right">
                    <div class="d-flex justify-content-end align-items-center">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="javascript:void(0)">Home</a>
                            </li>
                                <li class="breadcrumb-item {{ (($active == $segment1 ? 'active' : '')) }}">{{ $segment1 }}</li>
                            @if ($segment2)
                                <li class="breadcrumb-item {{ ($active == $segment2 ? 'active' : '')}}">{{ $segment2 }}</li>
                            @endif
                        </ol>
                    </div>
                </div>
            </div>
            <div class="section__content section__content--p30">
                <div class="container-fluid">
                    <div class="row m-t-5">
                        <div class="col-lg-12">
                            <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                                <div class="au-card-title" style="background-image:url('{{ URL::to('images/bg-title-01.jpg')}}');">
                                    <div class="bg-overlay bg-overlay--blue"></div>
                                    <h3>
                                        <i class="zmdi zmdi-account-calendar"></i>{{$title}}
                                    </h3>
                                </div>
                                <div class="au-task js-list-load">
                                    <div class="card-body card-block">
                                        @if ($message = Session::get('success'))
                                            <div class="alert alert-success alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @endif

                                        @if ($message = Session::get('error'))
                                            <div class="alert alert-danger alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @endif
                                        @if (count($errors) > 0)
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-danger alert-dismissible fade show" id="formMessage" role="alert">
                                                    {{ $error }}
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                        @yield('content')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($segment1 == 'certificate' && $segment2==null)
                    <div class="row m-t-5">
                        <div class="col-lg-12">
                            <div class="au-card au-card--no-shadow au-card--no-pad m-b-40">
                                <div class="au-card-title" style="background-image:url('{{ URL::to('images/bg-title-01.jpg')}}');">
                                    <div class="bg-overlay bg-overlay--blue"></div>
                                    <h3>
                                        <i class="zmdi zmdi-account-calendar"></i>Karyawan yang sudah mendapat sertifikat
                                    </h3>
                                </div>
                                <div class="au-task js-list-load">
                                    <div class="card-body card-block">
                                        @yield('content_certificate')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- END MAIN CONTENT-->
        <!-- END PAGE CONTAINER-->
    </div>
</div>
<!-- Main JS-->
<script>
    $(document).ready(function () {
        $('.select2').select2({});
    });

    function imgError(image) {
        image.onerror = "";
        image.src = "{{URL::to('images/big_image_800x600.gif')}}";
        return true;
    }

    function readURL(input,target) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(target).attr('src',e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    </script>
<script src="{{ URL::to('js/main.js')}}"></script>
@yield('jsInject')
@yield('modal')
</body>
</html>
