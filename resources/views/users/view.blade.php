@extends('layouts.master')

@section('title')
    TWS Citra | View User
@endsection

@section('content')
    <?php $userinfo = Session::get('userinfo');?>
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <center class="m-t-30"> <img class="img-preview img-circle" width="150" src="<?=$data->mediapath?>" alt="User Profile Picture" onerror="imgError(this)" >
                        <h4 class="card-title m-t-10">{{ ucwords($data->name) }}</h4>
                        <h6 class="card-subtitle"><?=$data->email?></h6>
                    </center>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <!-- Tab panes -->
                <div class="card-body">
                    <form class="form-horizontal form-material">
                        <div class="form-group">
                            <label class="col-md-12">Full Name</label>
                            <div class="col-md-12">
                                <input type="text" value="<?=ucwords($data->name)?>" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-email" class="col-md-12">Email</label>
                            <div class="col-md-12">
                                <input type="email" value="<?=$data->email?>" class="form-control form-control-line" name="example-email" id="example-email" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Date of Birth</label>
                            <div class="col-md-12">
                                <input type="text" value="<?=$data->datebirth?>" class="form-control form-control-line" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12">Role</label>
                            <div class="col-sm-12">
                                <select name="roleID" id="roleID" class="form-control form-control-line" disabled>
                                    <option value="<?=$data->roleID?>" selected><?=$data->role_name?></option>
                                </select>
                            </div>
                        </div>
                        <div class="foradmin {{($data->roleID == 1)? "d-none":""}}">
                            <hr>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="departemen" class=" form-control-label">Departemen</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="departmentID" id="departmentID" class="form-control select2"  disabled>
                                            <option value="">{{$data->department_name}} - {{$data->department_code}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="position" class=" form-control-label">Positions</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <select name="position" id="position" class="form-control select2" disabled>
                                        <option value="">{{$data->position_name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="date_hired" class=" form-control-label">Date Hired</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="date" id="hired" name="dateHired" placeholder="e.g. 12-30-2010" class="form-control"  value="<?=$data->dateHired?>" readonly>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="nik" class=" form-control-label">NIK</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text" name="nik" placeholder="e.g. 10293857388" class="form-control"  value="<?=$data->nik?>" readonly>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label for="npwp" class=" form-control-label">NPWP</label>
                                </div>
                                <div class="col-12 col-md-9">
                                    <input type="text"  name="npwp" placeholder="e.g. 5968493829" class="form-control"  value="<?=$data->npwp?>" readonly>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label class=" form-control-label">Softskills</label>
                                </div>
                                <?php
                                if(empty($data->softskill)){
                                    $softskill = array();
                                }else{
                            $softskill = unserialize($data->softskill);
                            if(gettype($softskill) != "array"){
                            $softskill = [];
                            }
                                }
                                ?>
                                <div class="col col-md-9">
                                    <div class="form-check">
                                        <div class="checkbox">
                                            <label for="checkbox1" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" id="checkbox1" name="softskill[]" value="softskill1"
                                                       class="form-check-input"@if($data->roleID != 1) @if(in_array('softskill1',$softskill)) checked @endif @endif>Option 1
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="checkbox2" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" id="checkbox2" name="softskill[]" value="softskill2"
                                                       class="form-check-input" @if($data->roleID != 1) @if(in_array('softskill2',$softskill)) checked @endif @endif> Option 2
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="checkbox3" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" id="checkbox3" name="softskill[]" value="softskill3"
                                                       class="form-check-input" @if($data->roleID != 1) @if(in_array('softskill3',$softskill)) checked @endif @endif> Option 3
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3">
                                    <label class=" form-control-label">Hardskills</label>
                                </div>
                                <?php
                                if(empty($data->hardskill)){
                                    $hardskill = array();
                                }else{
                            $hardskill = unserialize($data->hardskill);
                            if(gettype($hardskill) != "array"){
                            $hardskill = [];
                              }
                                }
                                ?>
                                <div class="col col-md-9">
                                    <div class="form-check">
                                        <div class="checkbox">
                                            <label for="checkbox1" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" name="hardskill[]" value="hardskill1"
                                                       class="form-check-input"@if($data->roleID != 1) @if(in_array('hardskill1',$hardskill)) checked @endif @endif>Option 1
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="checkbox2" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" name="hardskill[]" value="hardskill2"
                                                       class="form-check-input"@if($data->roleID != 1) @if(in_array('hardskill2',$hardskill)) checked @endif @endif> Option 2
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label for="checkbox3" class="form-check-label ">
                                                <input disabled ="true" type="checkbox" name="hardskill[]" value="hardskill3"
                                                       class="form-check-input"@if($data->roleID != 1) @if(in_array('hardskill3',$hardskill)) checked @endif @endif> Option 3
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Address</label>
                            <div class="col-md-12">
                                <textarea rows="5" class="form-control form-control-line" disabled>{{$data->address}}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-12">Status</label>
                            <div class="col-sm-12">
                                <select name="status" class="form-control form-control-line" disabled>
                                    <?php
                                    $status = array(
                                        'Not Active' => 0,
                                        'Active' => 1
                                    );
                                    ?>
                                    @foreach($status as $sts => $key)
                                        <option value="<?=$key?>" @if($data->status == $key) selected @endif ><?=$sts?></option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @if($userinfo['user_role']==2 && !$flag)

                        @elseif($userinfo['user_id'] == $data->id)
                        <div class="form-group">
                            <div class="col-sm-3">
                                <a href="{{route('user.updateUser',$userinfo['user_id'])}}" class="btn btn-success ladda-button btn-block">Edit Profile</a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>

    <script>

    </script>
@endsection
