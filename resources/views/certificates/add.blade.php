@extends('layouts.master')

@section('title')
    TWS Citra | Add Certificate
@endsection

@section('content')
{{-- @foreach ($users as $user) --}}
{{-- {{dd($data[0])}} --}}
{{-- @endforeach --}}
    <form action="{{route('certificate.add', $data[0]->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="kode_training" class=" form-control-label">Training</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="kode_training" name="trainingID" class="form-control select2" >
                    <option value="{{$data[0]->trainingID}}" selected>{{$data[0]->kode_training}} - {{$data[0]->training_name}}</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="nama_karyawan" class=" form-control-label">Nama Karyawan</label>
            </div>
            <div class="col-12 col-md-9">
                <select id="nama_karyawan" name="nama_karyawan" class="form-control select2" >
                    <option value="{{$data[0]->userID}}" selected>{{$data[0]->name}}</option>
                </select>
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="filecertificate" class=" form-control-label">File</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="file" id="filecertificate" name="filecertificate" class="form-control-file" required>
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

            $('#kode_training').on("select2:select", function (e) {
                var data = e.params.data;
                console.log(data);
                $('#training_name').val(data.trainingname);
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
    </script>
@endsection
