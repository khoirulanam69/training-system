@extends('layouts.master')

@section('title')
    TWS Citra | Create Spesification Training
@endsection

@section('content')

    <form action="{{route('spectrain.add')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="traintypeid" class=" form-control-label">Kode Tipe Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="traintypeid" placeholder="e.g. SPECS001" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label class=" form-control-label">Training Type</label>
            </div>
            <div class="col col-md-9">
                <div class="checkbox">
                    <label for="traintype" class="form-check-label ">
                        <input type="radio" name="traintype" value="softskill" > Soft Skill<br>
                        <input type="radio" name="traintype" value="hardskill" > Hardskill<br>
                    </label>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="departmentID" class=" form-control-label">Department</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="departmentID" name="departmentID" class="form-control select2">
                    <option disabled="" selected="" value=""> Please Select One </option>
                    @foreach($departments as $department)
                        <option value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="positions" class=" form-control-label">Positions</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="position" name="positionid[]" class="form-control select2">
                    <option disabled value=""> Please Select Above First </option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="grade" class=" form-control-label">Grade</label>
            </div>
            <div class="col-12 col-md-9">
                <input disabled value="-" type="text" name="inputGrade" placeholder="" class="form-control">
                <input id="_grade" name="grade" type="hidden">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="competency" class=" form-control-label">Kompetensi</label>
            </div>
            <div class="col-12 col-md-9">
                <input disabled value="-" type="text" name="inputCompetency" placeholder="" class="form-control">
                <input id="_competency" name="competency" type="hidden">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label class=" form-control-label">Aspek Penting</label>
            </div>
            <div class="col col-md-9">
                <div class="form-check">
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Safety" class="form-check-input">Safety
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Environment" class="form-check-input">Environment
                        </label>
                    </div>
                    <div class="checkbox">
                        <label for="important_aspect" class="form-check-label ">
                            <input type="checkbox" name="important_aspect[]" value="Quality" class="form-check-input">Quality
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
                <input type="text" id="training_needed" name="training_needed" placeholder="e.g. Komunikasi, Public Speaking" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="standard" class=" form-control-label">Standar Kompetensi</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="number" name="standard" placeholder="e.g. 99" class="form-control">
            </div>
        </div>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-dot-circle-o"></i>Create</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
    <script>
        $('#allposition_check').on("change", function () {
                if($(this).prop('checked') == true){
                    $("#departmentID").select2().next().hide();
                    $("#departmentID").prop('required',false);
                    $("#position").select2().next().hide();
                    $("#position").prop('required',false);
                }else{
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
            $('.select2').select2();

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
