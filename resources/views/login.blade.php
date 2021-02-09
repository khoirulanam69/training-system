<!doctype html>
<html lang="en">
<title>TWS Citra | Login</title>
@include('partials.header')
<body class="animsition">
    <div class="page-wrapper">
        <div class="page-content--bge5">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-6">
                        <span class="login-photo"></span>
                    </div>
                    <div class="col-md-6">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="login-content">
                                <div class="login-logo">
                                    <a href="{{route('login')}}">
                                        <img src="{{URL::to("images/logo-cls.png")}}" alt="TWS Citra Login">
                                    </a>
                                </div>
                                <div class="login-form">
                                    <form action="{{route('login')}}" method="post">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input class="au-input au-input--full" type="email" name="email" placeholder="Email">
                                        </div>
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input class="au-input au-input--full" type="password" name="password" placeholder="Password">
                                        </div>
                                        <button class="au-btn au-btn--block au-btn--green m-b-20" type="submit">Login</button>
                                        {{csrf_field()}}
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
        </div>
    </div>
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
<!-- <script src="https://code.jquery.com/jquery-3.3.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<!-- <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>

<!-- Main JS-->
<script src="{{ URL::to('js/main.js')}}"></script>
@yield('scripts')
<!-- Optional JavaScript -->
</body>
</html>
