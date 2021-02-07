@extends('layouts.master')

@section('title')
TWS Citra | Update User
@endsection

@section('content')
<?php $userinfo = Session::get('userinfo');?>
<form class="form-horizontal form-material" action="{{route('user.updateUser',$data->id)}}" method="post" enctype="multipart/form-data">
<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30 position-relative">
                    <div class="container-photo">
                        <label for="photo" class=" form-control-label">
                            @if ($data->mediapath)
                                <img class="img-preview img-circle" width="150px" src="<?=$data->mediapath?>" alt="User Profile Picture" onerror="imgError(this)" >
                            @else
                                <img class="img-preview" width="250px" src="<?=$data->mediapath?>" alt="User Profile Picture" onerror="imgError(this)" >
                            @endif
                            <span class="time-line-profile-selector"><i class="fas fa-camera fa-2x mt-3"></i></span>
                        </label>
                    </div>
                    <input type="file" id="photo" name="photo" class="form-control-file edit-photo">
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
                    <div class="form-group">
                        <label class="col-md-12">Full Name</label>
                        <div class="col-md-12">
                            <input type="hidden" name="id" placeholder="" class="form-control" required="true" value="<?=$data->id?>">
                            <input type="text" id="name" name="name" placeholder="e.g. John" class="form-control" required="true" value="<?=ucwords($data->name)?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="example-email" class="col-md-12">Email</label>
                        <div class="col-md-12">
                            <input type="email" name="email" placeholder="e.g. Johnepp@mail.com" class="form-control" required="true" value="<?=$data->email?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="date_birth" class=" form-control-label">Date of Birth</label>
                        </div>
                        <div class="col-sm-12">
                            <input type="date" id="birth" name="dateBirth" placeholder="10-30-2000" class="form-control" value="{{$data->datebirth}}"" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12">Role</label>
                        <div class="col-sm-12">
                            <select name="roleID" id="roleID" class="form-control form-control-line">
                                @foreach($roles as $role)
                                    @if($data->roleID == $role->id)
                                        <option value="<?=$role->id?>" selected><?=$role->name?></option>
                                    @else
                                        <option value="<?=$role->id?>"><?=$role->name?></option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <?php
                        if ($data->roleID != 1) {
                            $required = 'required';
                        }else{
                            $required = '';
                        }
                    ?>
                    <div class="foradmin {{($data->roleID == 1)? "d-none":""}}">
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="departemen" class=" form-control-label">Departemen</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select name="departmentID" id="departmentID" class="form-control select2" required={{$required}}>
                                    <option>Please select an option</option>
                                    @if($data->roleID == 1)
                                        @foreach($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}} - {{$department->code}}</option>
                                        @endforeach
                                    @else
                                        @foreach($departments as $department)
                                            @if($data->departmentID == $department->id)
                                                <option value="{{$department->id}}" selected>{{$department->name}} - {{$department->code}}</option>
                                            @else
                                                <option value="{{$department->id}}">{{$department->name}} - {{$department->code}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="position" class=" form-control-label">Positions</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select name="position" id="position" class="form-control select2" {{$required}}>
                                    <option value="">Please select an option</option>
                                    @if($data->roleID == 1)

                                    @else
                                        @foreach($positions as $position)
                                            @if($data->jabatanID == $position->id)
                                                <option value=<?=$position->id?> selected><?=$position->name?> - <?=$position->code?></option>
                                            @else
                                                <option value=<?=$position->id?>><?=$position->name?> - <?=$position->code?></option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="date_hired" class=" form-control-label">Date Hired</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="date" id="hired" name="dateHired" placeholder="e.g. 12-30-2010" class="form-control" {{$required}} value="<?=$data->dateHired?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="nik" class=" form-control-label">NIK</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" name="nik" placeholder="e.g. 10293857388" class="form-control" {{$required}} value="<?=$data->nik?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="npwp" class=" form-control-label">NPWP</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" name="npwp" placeholder="e.g. 5968493829" class="form-control" {{$required}} value="<?=$data->npwp?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Address</label>
                        <div class="col-md-12">
                            <textarea name="address" id="address" rows="9" placeholder="e.g. Jl.Madura No. 10, Malang" class="form-control" required="true">{{$data->address}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col col-md-3">
                            <label for="password" class=" form-control-label">Password<br/><small>default 12345</small></label>
                        </div>
                        <div class="col-md-12">
                            <input type="password" id="password" name="password" class="form-control d-none">
                            <input type="checkbox" id="password_check" name="password_check" value="1">
                            Change Password
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12">Status</label>
                        <div class="col-sm-12">
                            <select name="status" class="form-control" required="true">
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
                    <div class="row form-group">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-submit btn-success ladda-button btn-block">Update</button>
                        </div>
                    </div>
                    {{csrf_field()}}
                </div>
            </div>
        </div>
        <!-- Column -->
    </div>
</form>

    <script>
        $('#roleID').on("change",function (){
            if($(this).prop('value') != 1){
                $('.foradmin').removeClass('d-none');
                $('.foradmin').find('input:not[type = "checkbox"]').each(function (index,elem) {
                    $(elem).prop('required',true);
                })
            }else{
                $('.foradmin').addClass('d-none');
                $('.foradmin').find('input:not[type = "checkbox"]').each(function (index,elem) {
                    $(elem).prop('required',false);
                })
            }
        });

        $("#password_check").on("change", function(){
            if($(this).prop('checked') == true){
                $("#password").removeClass("d-none");
                $("#password").prop('required',true);
            } else {
                $("#password").addClass("d-none");
                $("#password").prop('required',false);
            }
        });

        $("#photo").change(function() {
            readURL(this, '.img-preview');
        });

        $(document).ready(function() {

            function imgError(image) {
                image.onerror = "";
                image.src = "{{ URL::to('images/big_image_800x600.gif')}}";
                return true;
            }


            $('.select2').select2({

            });

            $('#position').select2({
                placeholder: "Select above first"
            });

            $('#departmentID').on("select2:select", function (e) { console.log(e.params.data); getAllPosition(e); });

        });

        function getAllPosition (e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('position.getallposition')}}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'id': data.id
                },
            }).done(function(res){
                var result = res.filter(function (el){
                    return el.departmentID  == data.id;
                });

                var select2_arrays = [];
                for(var i in result){
                    select2_arrays.push({
                        id:result[i].id,
                        text:result[i].name
                    });
                }
                $('#position').find('option[value != "" ]').remove();
                $('#position').find('option[value = "" ]').html('Please Select an Option');
                $('#position').val(null);
                $('#position').trigger('change');
                $('#position').select2({
                    data:select2_arrays
                });
            })
                .fail(function(res){
                    console.log("Failed");
                    console.log(res);
                });
        }

    </script>
@endsection
