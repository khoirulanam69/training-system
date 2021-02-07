@extends('layouts.master')

@section('title')
    TWS Citra | Dashboard
@endsection

@section('content')
    @if(count($users)>0)
    @if (count($users)==1)
    <div class="au-task-list js-scrollbar3">
        @else
        <div class="js-scrollbar3">
            @endif
            <div class="col-lg">
                <div class="card">
                    <div class="card-body">
                        <div class="steamline m-t-40">
                            @foreach($users as $user)
                                <div class="sl-item">
                                    <div class="sl-left">
                                        @if ($user->mediapath)
                                <img class="img-preview img-circle" width="150px" src="<?=$user->mediapath?>" alt="User Profile Picture" onerror="imgError(this)" >
                            @else
                                <img class="img-preview img-circle" width="250px" src="" alt="User Profile Picture" onerror="imgError(this)" >
                            @endif
                                    </div>
                                    <div class="sl-right">
                                        <div class="font-medium">
                                            {{$user->name}}
                                        </div>
                                        <div class="desc">{{date('d F Y', strtotime($user->datebirth))}}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
        <div class="au-task-list js-scrollbar3">
            <div class="au-task__item">
                <div class="au-task__item-inner">
                    <div class="au-message__item-text">
                        <div class="text">
                            <h3 class="name">Sorry, No one celebrate birthday today</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <script>
        $(document).ready(function() {

            function imgError(image) {
                image.onerror = "";
                image.src = "{{ URL::to('images/big_image_800x600.gif')}}";
                return true;
            }
        });
    </script>
@endsection
