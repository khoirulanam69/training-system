@extends('layouts.master')

@section('title')
    TWS Citra | Update Certificate
@endsection

@section('content')
    <form action="{{route('certificate.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="kode_training" class=" form-control-label">Training</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="kode_training" name="kode_training" class="form-control select2">
                    @foreach($trainings as $train)
                        <option value="{{$train->id}}" {{($data->trainingID == $train->id)? "Selected" : ""}}>{{$train->training_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="karyawan_name" class=" form-control-label">Nama Karyawan</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="karyawan_name" name="karyawan_name" class="form-control select2" disabled>
                    <option value=""> Please Select an Option </option>
                    @foreach($karyawans as $kar)
                        <option value="{{$kar->id}}" {{($data->userID == $kar->id)? "Selected" : ""}}>{{$kar->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="filecertificate" class=" form-control-label">File</label>
            </div>
            <div class="col-12 col-md-9">
                <a href="{{url($data->mediapath)}}" target="_blank">Open Document</a>
                <input type="file" id="filecertificate" name="filecertificate" class="form-control-file">
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Submit</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
    <script>
        $(document).ready(function () {

            $('#spectrainID').on("select2:select", function (e) {
                $('#training_name').val("");
                getAllUndoneplan(e);
            });

            $('#kode_training').on("select2:select", function (e) {
                var data = e.params.data;
                $('#training_name').val(data.trainingname);
                $('#standard_kompetensi').val(data.trainingname);
                getSelectedKaryawan(e);
            });
        });

        function getSelectedKaryawan(e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('certificate.getselectkaryawan')}}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'id': data.id
                },
            }).done(function(res){
                var result = res.filter(function (el){
                    return el.id;
                });
                var select2_arrays = [];
                for(var i in result){
                    select2_arrays.push({
                        id:result[i].id,
                        text:result[i].name,
                    });
                }

                $('#karyawan_name').find('option[value!=""]').remove();
                $('#karyawan_name').find('option[value=""]').text("Please Select an Option");
                $('#karyawan_name').val(null);
                $('#karyawan_name').trigger('change');
                $('#karyawan_name').select2({
                    data:select2_arrays
                });

            }).fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }

        function getAllUndoneplan (e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('certificate.getallundoneplan')}}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'id': data.id
                },
            }).done(function(res){
                var result = res.filter(function (el){
                    return el.spectraining_id == data.id;
                });

                var select2_arrays = [];
                for(var i in result){
                    select2_arrays.push({
                        id:result[i].id,
                        text:result[i].kode_training,
                        'trainingname':result[i].training_name
                    });
                }
                console.log(select2_arrays);
                $('#kode_training').find('option[value!=""]').remove();

                if(select2_arrays.length !=0){
                    $('#kode_training').find('option[value=""]').text("Please Select an Option");
                }else {
                    $('#kode_training').find('option[value=""]').text("No data");
                }
                $('#kode_training').val(null);
                $('#kode_training').trigger('change');
                $('#kode_training').select2({
                    data:select2_arrays
                });
                $('#karyawan_name').find('option[value!=""]').remove();
                $('#karyawan_name').find('option[value=""]').text("Please Select Above First");
            }).fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }
    </script>
@endsection
