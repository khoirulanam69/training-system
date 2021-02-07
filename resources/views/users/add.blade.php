@extends('layouts.master')

@section('title')
    TWS Citra | Create User
@endsection

@section('content')
<?php $userinfo = Session::get('userinfo');?>
<form class="form-horizontal form-material" action="{{route('user.addUser')}}" method="post" enctype="multipart/form-data">
<div class="row">
    <!-- Column -->
    <div class="col-lg-4 col-xlg-3 col-md-5">
        <div class="card">
            <div class="card-body">
                <center class="m-t-30 position-relative">
                    <div class="container-photo">
                        <label for="photo" class=" form-control-label">
                            <img class="img-preview" width="250px" src="" alt="User Profile Picture" onerror="imgError(this)" >
                            <span class="time-line-profile-selector"><i class="fas fa-camera fa-2x mt-3"></i></span>
                        </label>
                    </div>
                    <input type="file" id="photo" name="photo" class="form-control-file add-photo">
                    <h4 class="card-title m-t-10" id="show_name"></h4>
                    <h6 class="card-subtitle" id="show_email"></h6>
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
                            <input type="hidden" name="id" placeholder="" class="form-control" required="true">
                            <input type="text" id="name" name="name" onInput="onInput('name')" placeholder="e.g. John" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="example-email" class="col-md-12">Email</label>
                        <div class="col-md-12">
                            <input type="email" id="email" name="email" onInput="onInput('email')" placeholder="e.g. Johnepp@mail.com" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="date_birth" class=" form-control-label">Date of Birth</label>
                        </div>
                        <div class="col-sm-12">
                            <input type="date" id="birth" name="dateBirth" placeholder="10-30-2000" class="form-control" required="true">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-12">Role</label>
                        <div class="col-sm-12">
                            <select name="roleID" id="roleID" class="form-control select2" required="true">
                                <option value="">Please select one</option>
                                @foreach($roles as $role)
                                    <option value="<?=$role->id?>"><?=$role->name?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="foradmin">
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="departemen" class=" form-control-label">Departemen</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select id="departmentID" name="departmentID" class="form-control select2" required>
                                    <option selected="" value=""> Please Select One </option>
                                    @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="position" class=" form-control-label">Positions</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <select id="position" name="position" class="form-control select2" required>
                                    <option selected="" value=""> Please Select Above First </option>
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="date_hired" class=" form-control-label">Date Hired</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="date" id="hired" name="dateHired" placeholder="e.g. 12-30-2010" class="form-control" required='true'>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="nik" class=" form-control-label">NIK</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" name="nik" placeholder="e.g. 10293857388" class="form-control" required="true">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col col-md-3">
                                <label for="npwp" class=" form-control-label">NPWP</label>
                            </div>
                            <div class="col-12 col-md-9">
                                <input type="text" name="npwp" placeholder="e.g. 5968493829" class="form-control" required="true">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Address</label>
                        <div class="col-md-12">
                            <textarea name="address" id="address" rows="9" placeholder="e.g. Jl.Madura No. 10, Malang" class="form-control" required="true"></textarea>
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
                                    <option value="<?=$key?>"><?=$sts?></option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-submit btn-success ladda-button btn-block">Add</button>
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

        $('#roleID').on("change",function (){

            if($(this).prop('value') != 1){
                $('.foradmin').removeClass('d-none');
                $('.foradmin').find('option[name!="allposition_check"]').each(function (index,elem) {
                    $(elem).prop('required',true);
                })
            }else{
                $('.foradmin').addClass('d-none');
                $('.foradmin').find(':input').each(function (index,elem) {
                    $(elem).prop('required',false);
                })
            }
        });

        $(document).ready(function () {
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
                if(select2_arrays == '') {
                    $('#position').find('option[value = "" ]').html('No Position Avaialble');
                }else{
                    $('#position').find('option[value = "" ]').html('Please Select an Option');
                }
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
        function onInput($id) {
            var x = document.getElementById($id).value;
            document.getElementById("show_"+$id).innerHTML = x;
        }
    </script>
@endsection
