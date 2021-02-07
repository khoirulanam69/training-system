<?php

namespace App\Http\Controllers\Backend;

use App\Models\Media;
use App\Models\Spectrain;
use App\Models\Training;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Trainingres;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DataTables;
use DB;
use Validator;
use Session;

class CertificateController extends Controller
{
    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if ($request->ajax()) {
            if ($userinfo['user_role'] == 1) {
                $data = DB::table('trainingresult as m')
                    ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'm.userID', '=', 'u.id')
                    ->leftjoin('media as md', 'm.certificateID', '=', 'md.id')
                    ->WhereNotNull('certificateID')
                    ->select('m.*', 't.training_name', 's.competency', 'md.mediapath', 'u.name as karyawan_name')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('file_training', function ($row) {
                        if (!empty($row->mediapath)) {
                            $btn = '<a href="' . url($row->mediapath) . '" target="_blank" class="btn btn-primary btn-sm">Open Document</a>';
                        } else {
                            $btn = '-';
                        }
                        return $btn;
                    })
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="certificate/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->training_name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

                        $btn = $btn . ' <span  data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                        return $btn;
                    })
                    ->rawColumns(['action', 'file_training'])
                    ->make(true);
            }

            if ($userinfo['user_role'] == 2) {
                $data = DB::table('trainingresult as m')
                    ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'm.userID', '=', 'u.id')
                    ->leftjoin('media as md', 'm.certificateID', '=', 'md.id')
                    ->WhereNotNull('certificateID')
                    ->select('m.*', 't.training_name', 's.competency', 'md.mediapath', 'u.name as karyawan_name')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('file_training', function ($row) {
                        if (!empty($row->mediapath)) {
                            $btn = '<a href="' . url($row->mediapath) . '" target="_blank" class="btn btn-primary btn-sm">Open Document</a>';
                        } else {
                            $btn = '-';
                        }
                        return $btn;
                    })
                    ->rawColumns(['file_training'])
                    ->make(true);
            }
        }
    }

    public function noCertificateDatatable(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($request->ajax()) {
            if ($userinfo['user_role'] == 1) {
                $data = DB::table('trainingresult as m')
                    ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'm.userID', '=', 'u.id')
                    ->where('m.certificateID', null)
                    ->where('m.status', 1)
                    ->select('m.*', 't.training_name', 's.competency', 'u.name as karyawan_name')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="certificate/add/' . $row->id . '" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i> Buat Sertifikat</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            if ($userinfo['user_role'] == 2) {
                $data = DB::table('trainingresult as m')
                    ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                    ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                    ->leftjoin('users as u', 'm.userID', '=', 'u.id')
                    ->where('m.certificateID', null)
                    ->where('m.status', 1)
                    ->select('m.*', 't.training_name', 's.competency', 'u.name as karyawan_name')
                    ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<a href="/certificate/add/' . $row->id . '" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i> Buat Sertifikat</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        }
    }

    public function index()
    {
        $datas = array(
            'title' => 'Certificate List',
            'slug' => 'certificate',
        );
        return view('certificates.index', $datas);
    }

    public function addCertificate(Request $request, $id)
    {
        if ($request->isMethod('GET')) {
            $data = DB::table('trainingresult as m')
                ->join('trainings as t', 'm.trainingID', '=', 't.id')
                ->join('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->join('users as u', 'm.userID', '=', 'u.id')
                ->where('m.id', $id)
                ->get();

            // $trainings = Training::where('status', '3')->get();
            // $users = User::where('id', $id)->get();
            $data = array(
                'title' => 'Create Certificate',
                'slug' => 'certificate',
                'data' => $data
            );

            return view('certificates.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createCertificate($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                return redirect()->route('certificate.index');
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

    private function createCertificate(Request $request)
    {

        //        $input = $request->all();
        //        $messages = [
        //            'trainingID.unique'    => 'Training ID and Karyawan Name combination must be unique',
        //        ];
        //        $validator = Validator::make($request->all(), [
        //            'trainingID' => Rule::unique('certificates')->where(function ($query) use ($input) {
        //                return $query->where('userID', $input['nama_karyawan']);
        //            }),
        //        ],$messages);

        $validator = Validator::make($request->all(), [
            'trainingID' => 'required',
            'nama_karyawan' => 'required',
        ]);

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $data = Trainingres::where('trainingID', $request->trainingID)
                ->where('userID', $request->nama_karyawan)
                ->first();

            //For File Upload
            if (!empty($data->id)) {
                if ($request->hasFile($request->input('filecertificate'))) {
                    $user_profile = time() . '.' . $request->file('filecertificate')->getClientOriginalExtension();
                    $input_m['mediaoriginalname'] = $user_profile;
                    $input_m['mediatype'] = $request->file('filecertificate')->getClientOriginalExtension();

                    if ($newFilePath = $request->file('filecertificate')->move(public_path('uploads/certificate'), $user_profile)) {
                        $input_m['mediapath'] = "/uploads/certificate/" . $user_profile;
                        $mediaid = DB::table('media')
                            ->insertGetId($input_m);
                        $data['certificateid'] = $mediaid;
                    }
                }

                if ($data->save()) {
                    $return['response']['success'] = true;
                } else {
                    $return['response']['success'] = false;
                    $return['response']['error'] = true;
                }
            } else {
                $return['response']['success'] = false;
                $return['response']['error'] = true;
            }
        }
        return $return;
    }

    public function getallundoneplan(Request $request)
    {
        $data = Training::where('spectraining_id', $request->id)
            ->where('status', '3')
            ->get();
        return json_encode($data);
    }

    public function getselectkaryawan(Request $request)
    {
        $trainingID = $request->id;
        $user_array = array();
        $training = Trainingres::where('trainingID', $request->id)
            ->where('status', 1)
            ->get();

        foreach ($training as $train) {
            $user_array[] = $train->userID;
        }

        if (count($user_array) > 0) {
            $data = DB::table('users')->whereIn('id', $user_array)->get();
            return json_encode($data);
        }
    }

    public function updateCertificate(Request $request, $id)
    {

        if ($request->isMethod('GET')) {

            $certificate = DB::table('trainingresult as m')
                ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->leftjoin('media as md', 'm.certificateID', '=', 'md.id')
                ->leftjoin('users as u', 'm.userID', '=', 'u.id')
                ->select('m.*', 't.training_name', 's.competency', 'md.mediapath', 't.spectraining_id', 'u.name as karyawan_name')
                ->where('m.id', $id)
                ->WhereNotNull('certificateID')
                ->first();
            if ($certificate->id) {
                $spectrains = Spectrain::all();
                $trainings = Training::where('spectraining_id', $certificate->spectraining_id)
                    ->get();
                $karyawans = DB::table('assignment as a')
                    ->where('a.trainingID', $certificate->trainingID)
                    ->leftjoin('users as u', 'a.karyawanID', '=', 'u.id')
                    ->select('u.*')
                    ->get();

                $data = array(
                    'title' => 'Update Certificate',
                    'slug' => 'certificate',
                    'data' => $certificate,
                    'specstrains' => $spectrains,
                    'trainings' => $trainings,
                    'karyawans' => $karyawans
                );
                return view('certificates.update', $data);
            } else {
                return redirect()->back();
            }
        }

        if ($request->isMethod('POST')) {
            $input = $request->all();
            $messages = [
                'trainingID.unique'    => 'Training ID and Karyawan Name Combination must Unique',
            ];
            $validator = Validator::make($request->all(), [
                'trainingID' => Rule::unique('certificates')->where(function ($query) use ($input) {
                    return $query->where('userID', $input['karyawan_name']);
                }),
            ], $messages);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $update = $this->editCertificate($request);

                if ($update['response']['success']) {
                    Session::flash('success', 'Data updated successfully');
                    Session::flash('mode', 'success');
                    return redirect()->back();
                } else {
                    Session::flash('error', 'Something wrong, Data updated fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function editCertificate(Request $request)
    {

        $id = $request->id;
        $data = Trainingres::find($id);
        if ($request->hasFile($request->input('filecertificate'))) {
            $user_profile = time() . '.' . $request->file('filecertificate')->getClientOriginalExtension();
            $input_m['mediaoriginalname'] = $user_profile;
            $input_m['mediatype'] = $request->file('filecertificate')->getClientOriginalExtension();

            if ($newFilePath = $request->file('filecertificate')->move(public_path('uploads/certificate'), $user_profile)) {
                $input_m['mediapath'] = "/uploads/certificate/" . $user_profile;
                $mediaid = DB::table('media')
                    ->insertGetId($input_m);
                $data->certificateID = $mediaid;
            }
        }

        if ($data->save()) {
            $return['response']['success'] = true;
        } else {
            $return['response']['success'] = false;
        }

        return $return;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $data = Trainingres::find($id);
        $data->certificateID = null;
        if ($data) {
            $return['success'] = true;
            if ($data->save()) {
                Media::where('id', $data->mediaID)->delete();
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
