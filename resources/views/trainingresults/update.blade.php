@extends('layouts.master')

@section('title')
    TWS Citra | Training Result
@endsection

@section('content')
    <form action="{{route('trainingres.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="specstrainid" class=" form-control-label">Spesifikasi Training</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="specstrainid" name="specstrainid" class="form-control select2">
                    <option value=""> Please Select One </option>
                    @foreach($specs as $spec)
                        <option value="{{$spec->id}}" {{($data->specstrainID == $spec->id)? "Selected" : ""}}> {{$spec->traintypeid}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="kode_training" class=" form-control-label">Kode Training {{$data->training_id}}</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="kode_training" name="kode_training" class="form-control select2">
                    <option value=""> Please Select One </option>
                    @foreach($trainings as $training)
                        <option value="{{$training->id}}"{{($data->training_id == $training->id)? "Selected" : ""}}> {{$training->kode_training}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_name" class=" form-control-label">Nama Training</label>
            </div>
            <div class="col-12 col-md-9">
		<input type="text" id="training_name" name="training_name" placeholder="" class="form-control" value="{{$data->training_name}}" readonly>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="standard_kompetensi" class=" form-control-label">Standard Kompetensi</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="standard_kompetensi" name="standard_kompetensi" placeholder="" class="form-control" readonly value="{{ $data->standard }}">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="nama_karyawan" class=" form-control-label">Nama Karyawan</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="nama_karyawan" name="nama_karyawan" class="form-control select2">
                    <option value=""> Please Select One </option>
                    @foreach($users as $user)
                        <option value="{{$user->id}}" selected> {{$user->name}} </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="training_score" class=" form-control-label">Nilai Training</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" name="training_score" placeholder="" class="form-control" value="{{$data->score}}" required="">
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

            $('#specstrainid').on("select2:select", function (e) {
                getAllDoneTraining(e);
		let specTrains = {!! json_encode($specs) !!}
            	let specTrainsArr = []
            	$.each(specTrains, (k,v)=>{
                    specTrainsArr[v.id] = v
            	})
		$('#standard_kompetensi').val(specTrainsArr[e.params.data.id].standard)
                $('#nama_karyawan').find('option[value!=""]').remove();
                $('#nama_karyawan').find('option[value=""]').text("Please Select Above First");
            });
            $('#kode_training').on("select2:select", function (e) {
                var data = e.params.data;
                $('#training_name').val(data.trainingname);
                getSelectedKaryawan(e);
            });
        });

        function getSelectedKaryawan(e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('trainingres.getselectkaryawan')}}',
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
                console.log(result);
                var select2_arrays = [];
                for(var i in result){
                    select2_arrays.push({
                        id:result[i].id,
                        text:result[i].name,
                    });
                }

                $('#nama_karyawan').find('option[value!=""]').remove();
                $('#nama_karyawan').find('option[value=""]').text("Please Select an Option");
                $('#nama_karyawan').val(null);
                $('#nama_karyawan').trigger('change');
                $('#nama_karyawan').select2({
                    data:select2_arrays
                });

            }).fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }

        function getAllDoneTraining (e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('trainingres.getalldoneplan')}}',
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

                $('#kode_training').find('option[value!=""]').remove();
                $('#kode_training').find('option[value=""]').text("Please Select an Option");
                $('#kode_training').val(null);
                $('#kode_training').trigger('change');
                $('#kode_training').select2({
                    data:select2_arrays
                });
            }).fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }
    </script>
@endsection