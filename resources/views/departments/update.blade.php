@extends('layouts.master')

@section('title')
    TWS Citra | Update Department
@endsection

@section('content')
    <form action="{{route('department.update',$data->id)}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="name" class=" form-control-label">Name</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="hidden" name="id" class="form-control" required="true" value="<?=$data->id?>" >
                <input type="text" id="name" name="name" placeholder="" class="form-control" required="true" value="<?=$data->name?>" >
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="date_birth" class=" form-control-label">Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="code" name="code" placeholder="" class="form-control" required="true" value="<?=$data->code?>">
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

    </script>
@endsection