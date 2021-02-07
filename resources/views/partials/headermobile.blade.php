<?php $userinfo = Session::get('userinfo');
$plan_array = ['training_plan','training_score','assignment','training_req','training_res','training_module','certificate'];
?>
<header class="header-mobile d-block d-lg-none">
    <div class="header-mobile__bar">
        <div class="container-fluid">
            <div class="header-mobile-inner">
                <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                </button>
            </div>
        </div>
    </div>
    <nav class="navbar-mobile">
        <div class="container-fluid">
            <ul class="navbar-mobile__list list-unstyled">
                <li class="{{ ($slug == 'dashboard' ? 'active' : '') }}">
                    <a class="js-arrow" href="{{ route('training.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                @if($userinfo['user_role'] == "1")
                    <li class="{{ ($slug == 'users' ? 'active' : '') }}">
                        <a href="{{route('user.index')}}">
                            <i class="fas fa-chart-bar"></i>Employee</a>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow " href="#">
                            <i class="fas fa-copy"></i>Training</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'display: block' : '')}}">
                            <li class="{{ ($slug == 'training_plan' ? 'active' : '') }}">
                                <a href="{{route('training.index')}}">Plan</a>
                            </li>
                            <li class="{{ ($slug == 'training_res' ? 'active' : '') }}">
                                <a href="{{route('trainingres.index')}}">Penilaian</a>
                            </li>
                            <li class="{{($slug == "training_module")? 'active' : '' }}">
                                <a href="{{route('module.index')}}">Modul</a>
                            </li>
                            <li class="{{ ($slug == 'reports' ? 'active' : '') }}">
                                <a href="{{route('reports.index')}}">Laporan</a>
                            </li>
                            <li class="{{($slug == "certificate")? 'active' : '' }}">
                                <a href="{{route('certificate.index')}}">Sertifikat</a>
                            </li>
                            <li class="{{ ($slug == 'training_req' ? 'active' : '') }}">
                                <a href="{{route('trainingreq.index')}}">Request</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if( $userinfo['user_role'] == "2")
                    <li class="{{ ($slug == 'users' ? 'active' : '') }}">
                        <a href="{{route('user.index')}}">
                            <i class="fas fa-chart-bar"></i>Employee</a>
                    </li>
                    <li class="has-sub">
                        <a class="js-arrow " href="#">
                            <i class="fas fa-copy"></i>Training</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'display: block' : '')}}">
                            <li class="{{ ($slug == 'training_plan' ? 'active' : '') }}">
                                <a href="{{route('training.index')}}">Plan</a>
                            </li>
                            <li class="{{ ($slug == 'training_res' ? 'active' : '') }}">
                                <a href="{{route('trainingres.index')}}">Penilaian</a>
                            </li>
                            <li class="{{($slug == "training_module")? 'active' : '' }}">
                                <a href="{{route('module.index')}}">Modul</a>
                            </li>
                            <li class="{{ ($slug == 'reports' ? 'active' : '') }}">
                                <a href="{{route('reports.index')}}">Laporan</a>
                            </li>
                            <li class="{{($slug == "certificate")? 'active' : '' }}">
                                <a href="{{route('certificate.index')}}">Sertifikat</a>
                            </li>
                            <li class="{{ ($slug == 'training_req' ? 'active' : '') }}">
                                <a href="{{route('trainingreq.index')}}">Request</a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if( $userinfo['user_role'] == "3")
                    <li class="{{ ($slug == 'training_req' ? 'active' : '') }}">
                        <a class="js-arrow" href="{{ route('trainingreq.index') }}">
                            <i class="fas fa-chart-bar"></i> Request
                        </a>
                    </li>
                @endif

                @if($userinfo['user_role']=='4')
                    <li class="has-sub">
                        <a class="js-arrow " href="#">
                            <i class="fas fa-copy"></i>Training</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'display: block' : '')}}">
                            <li class="{{ ($slug == 'assignment' ? 'active' : '') }}">
                                <a href="{{route('assignment.index')}}">Penugasan</a>
                            </li>
                            <li class="{{ ($slug == 'training_res' ? 'active' : '') }}">
                                <a href="{{route('trainingres.index')}}">Hasil Training</a>
                            </li>
                            <li class="{{ ($slug == 'training_req' ? 'active' : '') }}">
                                <a href="{{route('trainingreq.index')}}">Request</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <?php
                $master_arr = ['position','spectrains','department'];
                ?>
                @if($userinfo['user_role'] == 1)
                    <li class="has-sub">
                        <a class="js-arrow" href="#">
                            <i class="fas fa-desktop"></i>Master</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5 " style="{{ (in_array($slug,$master_arr)? 'display: block' : '') }}">
                            <li class="{{ ($slug == 'position' ? 'active' : '') }}">
                                <a href="{{route('position.index')}}">Jabatan</a>
                            </li>
                            <li class="{{ ($slug == 'spectrains' ? 'active' : '') }}">
                                <a href="{{route('spectrain.index')}}">Spesifikasi Training</a>
                            </li>
                            <li class="{{ ($slug == 'department' ? 'active' : '') }}">
                                <a href="{{route('department.index')}}">Departemen</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</header>
