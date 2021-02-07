<?php

namespace App\Http\Controllers\Backend;

use App\Models\Assignment;
use App\Models\Spectrain;
use App\Models\Training;
use App\Models\Trainingres;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Session;
use Validator;
use DB;

class TrainingresController extends Controller
{
    //
    public function index()
    {
        $data = DB::table('trainingresult as r')
            ->select('r.*', 's.traintype as specsname', 's.training_needed as training_needed', 'u.name as karyawan_name', 's.standard')
            ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
            ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
            ->leftjoin('users as u', 'r.userID', '=', 'u.id')
            ->get();
        $datas = array(
            'title' => 'Training Result',
            'slug' => 'training_res',
            'data' => $data
        );
        return view('trainingresults.index', $datas);
    }

    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if ($request->ajax()) {
            $department = $request->department;
            $query_department = array([0 => $department]);
            //            $query_department = array([ 0 => $department ]);
            if ($userinfo['user_role'] == 1) {
                $data = DB::table('trainingresult as r')
                    ->select('r.*', 's.traintype as specsname', 's.training_needed as training_needed', 'u.name as karyawan_name', 's.standard', 'r.id')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'r.userID', '=', 'u.id')
                    ->get();


                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="trainingres/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->karyawan_name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm">Edit</a>';

                        $btn = $btn . ' <span  data-name="' . $row->karyawan_name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 4) {
                $data = DB::table('trainingresult as r')
                    ->select('r.*', 's.competency', 't.training_name', 's.standard', 'm.mediapath')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('media as m', 'r.certificateID', '=', 'm.id')
                    ->leftjoin('users as u', 'r.userID', '=', 'u.id')
                    ->where('r.userID', $userinfo['user_id'])
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('file_training', function ($row) {
                        if ($row->mediapath) {
                            $btn = '<a href="' . url($row->mediapath) . '" target="_blank" class="btn btn-primary btn-sm">Open Document</a>';
                        } else {
                            $btn = '-';
                        }
                        return $btn;
                    })
                    ->rawColumns(['file_training'])
                    ->make(true);
            } elseif ($userinfo['user_role'] == 2) {
                $data = DB::table('trainingresult as r')
                    ->select('r.*', 's.traintype as specsname', 's.training_needed as training_needed', 'u.name as karyawan_name', 's.standard', 's.competency', 't.training_name')
                    ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'r.userID', '=', 'u.id')
                    ->get();


                return Datatables::of($data)
                    ->make(true);
            }
        }
    }

    public function getalldoneplan(Request $request)
    {
        $data = Training::where(['status' => 3, 'spectraining_id' => $request->id])
            ->get();
        return json_encode($data);
    }

    public function getselectkaryawan(Request $request)
    {
        $training = Assignment::where('trainingID', $request->id)
            ->get();
        $user_array = [];
        foreach ($training as $train) {
            $user_array[] = $train->karyawanID;
        }
        $spectrains = DB::table('trainings as t')
            ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
            ->select('s.*')
            ->where('t.id', $request->id)
            ->first();

        $data = DB::table('users')->whereIn('id', $user_array)->get();

        $reponse['spectrains'] = $spectrains;
        $reponse['data'] = $data;
        return json_encode($reponse);
    }

    public function addTrainingRes(Request $request)
    {
        if ($request->isMethod('GET')) {
            //
            //spesifikasi training
            $specs = Spectrain::all();
            $trainingplan = Training::where('status', 3)->get();

            $data = array(
                'title' => 'Submit Training Result',
                'slug' => 'training_res',
                'specs' => $specs,
                'trainings' => $trainingplan,
            );

            return view('trainingresults.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createTraining($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                return redirect()->route('trainingres.index');
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back()
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function createTraining(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_training' => 'required',
            'nama_karyawan' => 'required',
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $training = DB::table('trainings as t')->where('t.id', $request->kode_training)
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->select('s.standard', 't.*')
                ->first();
            $data = new Trainingres();
            $data->specstrainID = $request->specstrainid;
            $data->trainingID = $request->kode_training;
            $data->userID = $request->nama_karyawan;
            $data->score = $request->training_score;
            $data->status = ($training->standard <= $data->score) ? 1 : 0;

            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function updateTrainingRes(Request $request, $id)
    {
        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1', '2'))) {
            return redirect('/home/dashboard');
        }
        if ($request->isMethod('GET')) {
            //
            // training result
            $result = DB::table('trainingresult as r')
                ->where('r.id', $id)
                ->select('r.*', 's.id as spectrainid', 's.standard as standard', 's.traintype as specsname', 's.training_needed as training_needed', 'u.name as karyawan_name', 't.training_name as training_name', 't.id as training_id')
                ->leftjoin('trainings as t', 'r.trainingID', '=', 't.id')
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->leftjoin('users as u', 'r.userID', '=', 'u.id')
                ->first();

            if ($result) {
                $specs = DB::table('spectrains as s')
                    ->get();

                $trainings = DB::table('trainings as t')
                    ->select('t.*')
                    ->where('t.spectraining_id', $result->spectrainid)
                    ->join('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->get();
                $assignment = DB::table('assignment as a')
                    ->where('trainingID', $result->trainingID)
                    ->get();

                foreach ($assignment as $assign) {
                    $karyawans[] = $assign->karyawanID;
                }

                $users = DB::table('users as s')
                    ->whereIn('id', $karyawans)
                    ->get();


                $data = array(
                    'title' => 'Update Training Result',
                    'slug' => 'training_res',
                    'data' => $result,
                    'specs' => $specs,
                    'trainings' => $trainings,
                    'users' => $users
                );

                return view('trainingresults.update', $data);
            } else {

                return redirect()->route('trainingres.index');
            }
        }

        if ($request->isMethod('POST')) {
            $response = $this->editTraining($request, $id);
            if ($response['response']['success']) {
                Session::flash('success', 'Data updated successfully');
                Session::flash('mode', 'success');
                return redirect()->route('trainingres.index');
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Something wrong, Data updated fail');
                    Session::flash('mode', 'error');
                    return redirect()->back()
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function editTraining(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_training' => 'required',
            'specstrainid' => 'required',
            //            'karyawan_id' => 'required',
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $training = DB::table('trainings as t')->where('t.id', $request->kode_training)
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->select('s.standard', 't.*')
                ->first();

            $data = Trainingres::find($id);
            $data->specstrainID = $request->specstrainid;
            $data->trainingID = $request->kode_training;
            $data->userID = $request->nama_karyawan;
            $data->score = $request->training_score;
            $data->status = ($training->standard <= $data->score) ? 1 : 0;

            if ($data->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function delete(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1'))) {
            return redirect('/home/dashboard');
        }

        $id = $request->id;
        if ($id != null) {
            if (Trainingres::where('id', $id)->delete()) {
                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
        } else {
            $return['success'] = false;
        }

        return $return;
    }
}
