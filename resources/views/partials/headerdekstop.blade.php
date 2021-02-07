<?php $userinfo = Session::get('userinfo'); ?>
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="index.html">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="{{ URL::to('images/logo-cls.png') }}" style="width: 130px" alt="homepage" class="dark-logo"/>
                </b>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="d-flex justify-content-end navbar-collapse pr-1">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->

            <div class="header-button d-flex justify-content-end">
                <div class="account-wrap">
                    <div class="account-item clearfix js-item-menu">
{{--                            <div class="image">--}}
{{--                                <img src="images/icon/avatar-01.jpg" alt="John Doe">--}}
{{--                            </div>--}}
                        <div class="content text-right">
                            <a class="js-acc-btn" href="#">{{$userinfo['username']}}</a>
                        </div>
                        <div class="account-dropdown js-dropdown">
                            <div class="info clearfix">
{{--                                    <div class="image">--}}
{{--                                        <a href="admin_profile.php">--}}
{{--                                            <img src="images/icon/avatar-01.jpg" alt="John Doe">--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
                                <div class="content">
                                    <h5 class="name">
                                        <a href="{{route('getprofile')}}">{{$userinfo['username']}}</a>
                                    </h5>
                                    <span class="email">What a good day, {{$userinfo['username']}}</span>
                                </div>
                            </div>
                            <div class="account-dropdown__body">
                                <div class="account-dropdown__item">
                                    <a href="{{route('getprofile')}}">
                                        <i class="zmdi zmdi-settings"></i>Setting</a>
                                </div>
                            </div>
                            <div class="account-dropdown__footer">
                                <a href="{{route('logout')}}">
                                    <i class="zmdi zmdi-power"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
