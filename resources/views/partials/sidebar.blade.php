<?php
$segment =  Request::segment(1);
$sub_segment =  Request::segment(2);
?>

<?php $userinfo = Session::get('userinfo'); ?>
<?php
$plan_array = ['training_plan','training_score','assignment','training_req','training_res','training_module','certificate','reports', 'matriks'];
?>
<aside class="left-sidebar menu-sidebar d-none d-lg-block">
    <div class="d-flex no-block nav-text-box align-items-center">
        <span><img style="max-width: 150px;" src="{{ URL::to('images/logo-cls-light.png') }}" alt="Logo PT. CLS" /></span>
        <a class="waves-effect waves-dark ml-auto hidden-sm-down" href="javascript:void(0)"><i class="fas fa-bars" style="font-size: 20px"></i></a>
        <a class="nav-toggler waves-effect waves-dark ml-auto hidden-sm-up" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
    </div>
    <!-- Sidebar scroll-->
    <div class="menu-sidebar__content js-scrollbar1 ps ps--active-y">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav navbar-sidebar">
            <ul id="sidebarnav list-unstyled navbar__list">
                <li>
                    <a href="{{route('training.dashboard')}}" class="waves-effect waves-dark <?= active(['home/dashboard'])?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>
                @if($userinfo['user_role'] == "1")
                <li>
                    <a href="{{route('user.index')}}" class="waves-effect waves-dark <?= active(['home/user', 'home/user/*'])?>">
                        <i class="fas fa-chart-bar"></i>
                        <span class="hide-menu">Employee</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow <?= active(['home/training', 'home/training/*', 'home/trainingres', 'home/trainingres/*', 'home/module', 'home/module/*', 'home/report', 'home/certificate', 'home/certificate/*', 'home/trainingreq', 'home/matriks', 'home/matriks/*'])?>" href="#">
                        <i class="fas fa-copy"></i>Training</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'max-height: 20em;' : '')}}">
                        <li>
                            <a href="{{route('training.index')}}" class="<?= active(['home/training', 'home/training/*'])?>">Plan</a>
                        </li>
                        <li>
                            <a href="{{route('trainingres.index')}}" class="<?= active(['home/trainingres', 'home/trainingres/*'])?>">Penilaian</a>
                        </li>
                        <li>
                            <a href="{{route('module.index')}}" class="<?= active(['home/module', 'home/module/*'])?>">Modul</a>
                        </li>
                        <li>
                            <a href="{{route('reports.index')}}" class="<?= active(['home/report'])?>">Laporan</a>
                        </li>
                        <li>
                            <a href="{{route('certificate.index')}}" class="<?= active(['home/certificate', 'home/certificate/*'])?>">Sertifikat</a>
                        </li>
                        <li>
                            <a href="{{route('trainingreq.index')}}" class="<?= active(['home/trainingreq'])?>">Request</a>
                        </li>
                        <li>
                            <a href="{{route('matriks.index')}}" class="<?= active(['home/matriks', 'home/matriks/*'])?>">Matriks</a>
                        </li>
                    </ul>
                </li>
                @endif


                @if($userinfo['user_role'] == "2")
                <li>
                    <a href="{{route('user.index')}}" class="waves-effect waves-dark <?= active(['home/user'])?>" aria-expanded="false">
                        <i class="fas fa-chart-bar"></i>
                        <span class="hide-menu">Employee</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow <?= active(['home/training', 'home/training/*', 'home/trainingres', 'home/trainingres/*', 'home/module', 'home/module/*', 'home/report', 'home/certificate', 'home/certificate/*', 'home/trainingreq', 'home/matriks', 'home/matriks/*'])?>" href="#">
                        <i class="fas fa-copy"></i>Training</a>
                    <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'max-height: 20em;' : '')}}">
                        <li>
                            <a href="{{route('training.index')}}" class="<?= active(['home/training', 'home/training/*'])?>">Plan</a>
                        </li>
                        <li>
                            <a href="{{route('trainingres.index')}}" class="<?= active(['home/trainingres', 'home/trainingres/*'])?>">Penilaian</a>
                        </li>
                        <li>
                            <a href="{{route('module.index')}}" class="<?= active(['home/module', 'home/module/*'])?>">Modul</a>
                        </li>
                        <li>
                            <a href="{{route('reports.index')}}" class="<?= active(['home/report'])?>">Laporan</a>
                        </li>
                        <li>
                            <a href="{{route('certificate.index')}}" class="<?= active(['home/certificate'])?>">Sertifikat</a>
                        </li>
                        <li>
                            <a href="{{route('trainingreq.index')}}" class="<?= active(['home/trainingreq'])?>">Request</a>
                        </li>
                    </ul>
                </li>
                @endif


                @if($userinfo['user_role'] == "3")
                <li>
                    <a href="{{route('trainingreq.index')}}" class="waves-effect waves-dark <?= active(['home/trainingreq'])?>" aria-expanded="false">
                        <i class="fas fa-chart-bar"></i>
                        <span class="hide-menu">Request</span>
                    </a>
                </li>
                <li class="has-sub">
                    <a class="js-arrow <?= active(['home/report'])?>" href="#">
                        <i class="fas fa-copy"></i>Training</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'max-height: 20em;' : '')}}">
                            <li>
                                <a href="{{route('reports.index')}}" class="<?= active(['home/report'])?>">Laporan</a>
                            </li>
                        </ul>
                </li>
                @endif


                @if($userinfo['user_role'] == "4")
                <li class="has-sub">
                    <a class="js-arrow <?= active(['home/assignment', 'home/trainingres', 'home/trainingreq'])?>" href="#">
                        <i class="fas fa-copy"></i>Training</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5" style="{{(in_array($slug,$plan_array)? 'max-height: 20em;' : '')}}">
                            <li>
                                <a href="{{route('assignment.index')}}" class="<?= active(['home/assignment'])?>">Penugasan</a>
                            </li>
                            <li>
                                <a href="{{route('trainingres.index')}}" class="<?= active(['home/trainingres'])?>">Hasil Training</a>
                            </li>
                            <li>
                                <a href="{{route('trainingreq.index')}}" class="<?= active(['home/trainingreq'])?>">Request</a>
                            </li>
                        </ul>
                </li>
                @endif

                <?php
                    $master_arr = ['position','spectrains','department'];
                ?>
                @if($userinfo['user_role'] == 1)
                    <li class="has-sub">
                        <a class="js-arrow <?= active(['home/position', 'home/position/*', 'home/spectrain', 'home/spectrain/*', 'home/department', 'home/department/*'])?>" href="#">
                            <i class="fas fa-desktop"></i>Master</a>
                        <ul class="list-unstyled navbar__sub-list js-sub-list pl-5 " style="{{(in_array($slug,$master_arr)? 'max-height: 20em;' : '')}}">
                            <li>
                                <a href="{{route('position.index')}}" class="<?= active(['home/position', 'home/position/*'])?>">Jabatan</a>
                            </li>
                            <li>
                                <a href="{{route('spectrain.index')}}" class="<?= active(['home/spectrain', 'home/spectrain/*'])?>">Spesifikasi Training</a>
                            </li>
                            <li>
                                <a href="{{route('department.index')}}" class="<?= active(['home/department', 'home/department/*'])?>">Departemen</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
