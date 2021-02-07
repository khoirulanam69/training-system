<?php

namespace App\Http\Controllers\Backend;

use App\Models\Assignment;
use App\Models\Training;
use App\Models\Trainingreq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Session;
use Validator;
use DB;

class AssignmentController extends Controller
{

    public function index()
    {

        $datas = array(
            'title' => 'List Penugasan',
            'slug' => 'assignment'
        );
        return view('assignments.index', $datas);
    }

    public function datatable(Request $request)
    {
        $userinfo = Session::get('userinfo');
        $userid = $userinfo['user_id'];
        if ($request->ajax()) {
            $data = DB::table('assignment as a')
                ->select(
                    'm.mediapath',
                    'm.urlvideo',
                    'a.*',
                    's.traintype as specsname',
                    's.competency as competency',
                    's.standard as standard',
                    't.kode_training as kode_training',
                    't.start_date as start_date',
                    't.duration as duration',
                    't.trainer_name as trainer_name',
                    't.training_location as location'
                )
                ->where('a.karyawanID', $userid)
                ->join('trainings as t', 'a.trainingID', '=', 't.id')
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->leftjoin('module as mo', 'a.trainingID', '=', 'mo.trainingID')
                ->leftjoin('media as m', 'mo.mediaID', '=', 'm.id')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('filemodule', function ($row) {
                    $btn = (!empty($row->mediapath)) ? '<a href="' . url($row->mediapath) . '" target="_blank" class="btn btn-primary btn-sm">Download File</a>' : 'No Module';
                    return $btn;
                })
                ->rawColumns(['filemodule'])
                ->make(true);
        }
    }

    public function addassignment(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->karyawanid as $value) {
                $data = new Assignment();
                $data->trainingID = $request->trainingID;
                $data->karyawanID = $value;
                $data->save();
            }
            if ($data->id) {
                $return['data'] = $data;
                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
            return json_encode($return);
        }
    }

    public function hrdaddassignment(Request $request)
    {
        if ($request->ajax()) {
            foreach ($request->karyawanid as $value) {
                $training_req = Trainingreq::where('trainingID', $request->trainingID)
                    ->where('requestby', $value)
                    ->whereIn('status', array('1', '5'))->first();
                if (!$training_req) {
                    $data = new Trainingreq();
                    $data->trainingID = $request->trainingID;
                    $data->requestby = $value;
                    $data->status = 5;
                    $data->requestdate = date("Y-m-d");
                    $data->save();
                }
            }
            if ($data->id) {
                $return['data'] = $data;
                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
            return json_encode($return);
        }
    }
}
