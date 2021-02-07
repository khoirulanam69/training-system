<?php

namespace App\Http\Controllers\Backend;

use App\Models\Assignment;
use App\Models\Spectrain;
use function GuzzleHttp\Promise\queue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\Trainingreq;
use DataTables;
use Session;
use Validator;
use DB;

class TrainingreqController extends Controller
{
    public function index()
    {
        $specs = Spectrain::all();
        $datas = array(
            'title' => 'Training Request List',
            'slug' => 'training_req',
            'specs' => $specs,
        );
        return view('trainingreqs.index', $datas);
    }

    public function datatable(Request $request)
    {
        $userinfo = Session::get('userinfo');
        $userid = $userinfo['user_id'];
        if ($request->ajax()) {
            // admin : 1, manajer : 3, hrd : 2, karyawan : 4
            if ($userinfo['user_role'] == 4) {
                $trainingreq = DB::table('trainingreq')
                    ->where('requestby', $userid)
                    ->get();
                $req_arry = array();
                foreach ($trainingreq as $req) {
                    $req_arry[] = $req->trainingID;
                }

                $data = Training::select('trainings.*', 'u.traintypeid', 'u.traintype as traintype', 'u.grade', 'u.competency as competency', 'u.standard as standard')
                    ->Join('spectrains as u', 'spectraining_id', '=', 'u.id')
                    //                    ->where('approved_status',1)
                    ->get();
                $i = 0;
                foreach ($data as $train) {
                    if (in_array($train->id, $req_arry)) {
                        $status = Trainingreq::select('status')
                            ->where('trainingID', $train->id)
                            ->first();
                        $data[$i]->request_status = $status->status;
                    } else {
                        $data[$i]->request_status = 0;
                    }

                    $i++;
                }
                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = "";
                        $btn = $btn . ' <span data-toggle="modal" data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-target="#detailModal" data-original-title="Detail" class="btn btn-primary btn-sm detail">Detail</span>';
                        if ($row->request_status == 0) {
                            $btn = '<span  data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-original-title="Request" class="btn btn-success btn-sm request">Request</span>';
                        } else if ($row->request_status == 1) {
                            $btn = $btn . ' <span  data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-status = 4 data-original-title="Cancel" class="btn btn-danger btn-sm cancel">Batal</span>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 3) {
                $data = DB::table('trainingreq as r')
                    ->select(
                        'r.*',
                        'u.name as karyawan_name',
                        's.grade as grade',
                        's.competency as competency',
                        't.kode_training as kode_training',
                        't.training_name as training_name',
                        't.duration as duration',
                        't.trainer_name as trainer_name',
                        't.training_location as training_location',
                        't.start_date as start_date',
                        's.standard as standard'
                    )
                    ->leftjoin('users as u', 'r.requestby', '=', 'u.id')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->whereIn('r.status', array('1', '5'))
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = "";
                        $btn = $btn . ' <span data-toggle="modal" data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-target="#detailModal" data-original-title="Detail" class="btn btn-primary btn-sm detail">Detail</span>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 2) {
                $data = DB::table('trainingreq as r')
                    ->select(
                        'r.*',
                        'u.name as karyawan_name',
                        's.grade as grade',
                        's.competency as competency',
                        't.kode_training as kode_training',
                        't.training_name as training_name',
                        't.duration as duration',
                        't.trainer_name as trainer_name',
                        't.training_location as training_location',
                        't.start_date as start_date',
                        's.standard as standard'
                    )
                    ->leftjoin('users as u', 'r.requestby', '=', 'u.id')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->whereIn('r.status', array('1', '2', '3', '4', '5'))
                    ->get();

                return Datatables::of($data)
                    //                    ->addIndexColumn()
                    //                    ->addColumn('action', function($row){
                    //                        $btn = "";
                    //                        $btn = $btn.' <span data-toggle="modal" data-name="'.$row->training_name.'" data-id="'.$row->id.'" data-target="#detailModal" data-original-title="Detail" class="btn btn-primary btn-sm detail">Detail</span>';
                    //                        return $btn;
                    //                    })
                    //                    ->rawColumns(['action'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 1) {
                $data = DB::table('trainingreq as r')
                    ->select('r.*', 'u.name as karyawan_name', 't.training_name', 'd.name as department_name', 'p.name as position_name', 'r.status', 's.competency')
                    ->leftjoin('users as u', 'r.requestby', '=', 'u.id')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
                    ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
                    ->get();

                return Datatables::of($data)
                    ->make(true);
            }
        }
    }

    public function updatestatus(Request $request)
    {
        $userinfo = Session::get('userinfo');
        if ($userinfo) {
            $trainingid = $request->id;
            $userid = $userinfo['user_id'];
            if ($request->status == 1) {
                $training = Trainingreq::where('trainingID', $trainingid)
                    ->where('requestby', $userid)
                    ->first();
                if (!$training) {
                    $data = new Trainingreq();
                    $data->trainingID = $trainingid;
                    $data->requestby = $userid;
                    $data->requestdate = date("Y-m-d");
                    $data->status = 1;

                    if ($data->save()) {
                        $return['success'] = true;
                    } else {
                        $return['success'] = false;
                    }
                } else {
                    $return['success'] = false;
                }

                $return['success'] = false;
            }

            if ($request->status == 2) {
                $data = Trainingreq::where('id', $trainingid)
                    ->first();
                $data->status = $request->status;
                $data->acceptby = $userid;

                if ($data->save()) {
                    $assign = new Assignment();
                    $assign->trainingID = $data->trainingID;
                    $assign->karyawanID = $data->requestby;
                    if ($assign->save()) {
                        $return['success'] = true;
                    } else {
                        $return['success'] = false;
                    }
                } else {
                    $return['success'] = false;
                }
            }

            if ($request->status == 3) {
                $data = Trainingreq::where('id', $trainingid)
                    ->first();
                if ($data) {
                    $data->status = $request->status;

                    if ($data->save()) {
                        $return['success'] = true;
                    } else {
                        $return['success'] = false;
                    }
                } else {
                    $return['success'] = false;
                }
            }

            if ($request->status == 4) {
                $data = Trainingreq::where('trainingID', $trainingid)
                    ->where('requestby', $userid)
                    ->first();
                if ($data) {
                    $data->status = $request->status;

                    if ($data->save()) {
                        $return['success'] = true;
                    } else {
                        $return['success'] = false;
                    }
                } else {
                    $return['success'] = false;
                }
            }
        }
    }
}
