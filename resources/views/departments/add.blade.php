@extends('layouts.master')

@section('title')
    TWS Citra | Create Department
@endsection

@section('content')
    <form action="{{route('department.add')}}" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="name" class=" form-control-label">Name</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="name" name="name" placeholder="" class="form-control" required="true">
            </div>
        </div>
        <div class="row form-group">
            <div class="col col-md-3">
                <label for="date_birth" class=" form-control-label">Code</label>
            </div>
            <div class="col-12 col-md-9">
                <input type="text" id="code" name="code" placeholder="" class="form-control" required="true">
            </div>
        </div>
        <hr>
        <div class="row form-group">
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-submit btn-primary ladda-button btn-block">Create</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
    <script>

    </script>
@endsection