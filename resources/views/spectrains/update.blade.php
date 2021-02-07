@extends('layouts.master')

@section('title')
    TWS Citra | Spesification Training Update
@endsection

@section('content')
    <form action="{{route('spectrain.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="traintypeid" class=" form-control-label">Training Type Id</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="hidden" name="id" placeholder="" class="form-control" required="true" value="<?=$data->id?>">
                <input type="text" id="name" name="traintypeid" placeholder="" class="form-control" required="true" value="<?=$data->traintypeid?>">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="traintype" class=" form-control-label">Train Type</label>
            </div>
            <div class="col-12 col-md-9">
                <div class="checkbox">
                    <label for="traintype" class="form-check-label ">
                        <input type="radio" name="traintype" {{($data->traintype == "softskill")? "checked" : ''}} value="softskill"> Soft Skill<br>
                        <input type="radio" name="traintype" {{($data->traintype == "hardskill")? "checked = true" : ''}} value="hardskill"> Hard Skill<br>
                    </label>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="departmentID" class=" form-control-label">Department</label>
            </div>
            <div class="col-12 col-md-9">
                @if($data->allposition != 1)
                    <select id="departmentID" name="departmentID" class="form-control select2">
                        <option disabled="" selected="" value=""> Please Select One </option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}" selected = "{{($selected_dept->id == $department->id)? true :false}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                @else
                    <select id="departmentID" name="departmentID" class="form-control d-none">
                        <option disabled="" selected="" value=""> Please Select One </option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                @endif
                <input type="checkbox" id="allposition_check" name="allposition_check" value="1" {{($data->allposition == 1)? "checked" : "" }}> All Position <br>
            </div>
        </div>
        <div id="dmposition" class="row form-group {{($data->allposition == 1)? "d-none":""}}">
            <div class="col col-md-3">
                <label for="positions" class=" form-control-label">Positions</label>
            </div>
            <div class="col-12 col-md-9">
                <select name="positionid[]" id="position" class="form-control select2">
                    @if($data->allposition != 1)
                        @foreach($positions as $pos)
                            <?php $selected = ""; ?>
                            @foreach($selected_pos as $sltpos)
                                @if($pos->id == $sltpos->id)
                                    <?php $selected = "selected";
                                    break;
                                    ?>
                                @endif
                            @endforeach
                            <option value="{{$pos->id}}}" {{$selected}}>{{$pos->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade" class=" form-control-label">Grade</label>
            </div>
            <div class="col-12 col-md-9">
                <input disabled value="{{$data->grade}}" type="text" name="inputGrade" placeholder="" class="form-control" required="">
                <input id="_grade" name="grade" type="hidden">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="competency" class=" form-control-label">Kompetensi</label>
            </div>
            <div class="col-12 col-md-9">
                <input disabled value="{{$data->competency}}" type="text" name="inputCompetency" placeholder="" class="form-control" required="">
                <input id="_competency" name="competency" type="hidden">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label class=" form-control-label">Aspek Penting</label>
                <?php
                    $aspect = unserialize($data->important_aspect);
                ?>
            </div>
            <div class="col col-md-9">
                <div class="form-check">
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Safety" class="form-check-input" {{(in_array("Safety",$aspect))? "Checked" :""}}>Safety
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Environment" class="form-check-input" {{(in_array("Environment",$aspect))? "Checked" :""}}>Environment
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Quality" class="form-check-input" {{(in_array("Quality",$aspect))? "Checked" :""}}>Quality
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_needed" class=" form-control-label">Training yang diperlukan</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="training_needed" name="training_needed" placeholder="" class="form-control" required="" value="{{$data->training_needed}}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="standard" class=" form-control-label">Standar Kompetensi</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="number" name="standard" placeholder="" min=0 class="form-control" required="" value="{{$data->standard}}">
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Update</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
    <script>
	let departmentId = {!! json_encode($departmentId) !!}
	$("#departmentID").val(departmentId);
        $('#allposition_check').on("change", function () {
            if($(this).prop('checked') == true){
                $('#dmposition').addClass('d-none');
                $("#departmentID").select2().next();
                $("#departmentID").prop('required',false);
                $("#position").select2().next().hide();
                $("#position").prop('required',false);
            }else{
                $('#dmposition').removeClass('d-none');
                $("#departmentID").select2().next().show();
                $("#departmentID").prop('required',true);
                $("#position").select2().next().show();
                $("#position").prop('required',true);
                $('#position').select2({
                    placeholder: "Select above first"
                });
            }
        })
        $(document).ready(function () {
            $('.select2').select2({
            });

            $('#position').select2({
                placeholder: "Select above first"
            });


            $('#departmentID').on("select2:select", function (e) { console.log(e.params.data); getAllPosition(e); });
            $('#position').on("select2:select", function (e) { console.log(e.params.data); setGradeCompetency(); });
        });

        let select2_arrays = [];
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

                for(var i in result){
                    select2_arrays.push({
                        id:result[i].id,
                        text:result[i].name,
                        grade:result[i].grade,
                        competency:result[i].competency
                    });
                }
                $('#position').find('option').remove();
                $('#position').val(null);
                $('#position').trigger('change');
                $('#position').select2({
                    data:select2_arrays
                });
                setGradeCompetency()
            })
            .fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }

        function setGradeCompetency(){
            let result = []
            for (let i = 0; i < select2_arrays.length; i++) {
                if(select2_arrays[i].id  == $('#position').val()){
                    result = [select2_arrays[i].grade, select2_arrays[i].competency]
                    break;
                }else{
                    result = ["-", "-"];
                }
            }
            console.log(result)
            $('input[name="inputGrade"]').val(result[0])
            $('#_grade').val(result[0])
            $('input[name="inputCompetency"]').val(result[1])
            $('#_competency').val(result[1])
        }
    </script>
@endsection
