<!DOCTYPE html>
<html>
    <head>
        <title>Training Report</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    <body>
        <style type="text/css">
            table tr td,
            table tr th{
                font-size: 12pt;
            }
        </style>
        <div class = "float-md-right">
            <p>Date : {{date('d/m/Y')}}</p>
        </div>
        <div class="card-body card-block">
            <center>
                <h1>Training Report</h1>
            </center>
            <h5><strong>User Detail</strong></h5>

            <table class='table table-borderless'>
                <tr>
                    <td>Name</td>
                    <td>:</td>
                    <td>{{$user->name}}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{$user->nik}}</td>
                </tr>
                <tr>
                    <td>Department</td>
                    <td>:</td>
                    <td>{{$user->department_name}}</td>
                </tr>
                <tr>
                    <td>Position</td>
                    <td>:</td>
                    <td>{{$user->position_name}}</td>
                </tr>
            </table>
            <hr>
            <h5><strong>Training Result</strong></h5>
            <table class='table table-bordered'>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Training Name</th>
                    <th>Training Location</th>
                    <th>Standard Competency</th>
                    <th>Score</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @php $i=1 @endphp
                @foreach($result as $res)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{$res->training_name}}</td>
                        <td>{{$res->training_location}}</td>
                        <td>{{$res->standard}}</td>
                        <td>{{$res->score}}</td>
                        <td>
                            @if($res->status == 1)
                                Lulus
                            @else
                                Tidak Lulus
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>
