@extends('layouts.master')

@section('title')
    TWS Citra | Update Module
@endsection

@section('content')
    <form action="{{route('module.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
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
        <div id="fileUpload" class="mt-4 row form-group">
            <div class="col col-md-3">
                <label for="filemodule" class=" form-control-label">File</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="file" id="filemodule" name="filemodule" class="form-control-file">
            </div>
        </div>
        <div id="linkUpload" class="row form-group">
            <div class="col col-md-3">
                <label for="urlvideo" class=" form-control-label">URL Video Youtube</label>
            </div>
            <div class="col-12 col-md-9">
                @if ($data->urlvideo != "")
                    <input type="text" id="urlvideo" name="urlvideo" value="{{$data->urlvideo}}" class="form-control">
                @else
                    <input type="text" id="urlvideo" name="urlvideo" placeholder="e.g. https://www.youtube.com/watch?v=e-OfSWE9uV4" class="form-control">
                @endif
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
        });

        function getAllUndoneplan (e) {
            var data = e.params.data;

            $.ajax({
                method: 'post',
                dataType: 'json',
                url: '{{route('module.getallundoneplan')}}',
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
            }).fail(function(res){
                console.log("Failed");
                console.log(res);
            });
        }
    </script>
@endsection
