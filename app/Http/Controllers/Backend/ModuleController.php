<?php

namespace App\Http\Controllers\Backend;

use App\Models\Media;
use App\Models\Spectrain;
use App\Models\Training;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Module;
use DataTables;
use DB;
use Validator;
use Session;
use Redirect;
use File;

class ModuleController extends Controller
{

    public function datatable(Request $request)
    {

        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1', '2'))) {
            return redirect('/home/dashboard');
        }

        if ($request->ajax()) {
            $data = DB::table('module as m')
                ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->leftjoin('media as md', 'm.mediaID', '=', 'md.id')
                ->select(array('m.*', 't.kode_training', DB::raw("CONCAT(t.kode_training,' - ',t.training_name) as training_name"), 's.competency', 'md.urlvideo', 'md.mediapath'))
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('file_training', function ($row) {
                    if ($row->mediapath != "") {
                        return '<a href="' . url($row->mediapath) . '" target="_blank" class="btn btn-primary btn-sm">Open Document</a>';
                    }
                    return '<span class="badge badge-pill badge-secondary">No File</span>';
                })
                ->addColumn('action', function ($row) {

                    $btn = '<a href="module/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->training_name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

                    $btn = $btn . ' <span  data-name="' . $row->training_name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm delete">Delete</span>';

                    return $btn;
                })
                ->rawColumns(['action', 'file_training'])
                ->make(true);
        }
    }

    public function index()
    {

        $datas = array(
            'title' => 'Module List',
            'slug' => 'training_module'
        );
        return view('modules.index', $datas);
    }

    public function addModule(Request $request)
    {
        if ($request->isMethod('GET')) {
            $trainings = Training::all();
            $spectrains = Spectrain::all();
            $data = array(
                'title' => 'Create Module',
                'slug' => 'module',
                'specstrains' => $spectrains,
                'trainings' => $trainings
            );

            return view('modules.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->createModule($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data berhasil dibuat');
                Session::flash('mode', 'success');
                return redirect()->route('module.index');
            } else {
                if ($response['response']['error']) {
                    Session::flash('error', 'Ada sesuatu yang salah, Data gagal dibuat');
                    Session::flash('mode', 'error');
                    return redirect()->back()
                        ->withErrors($response['response']['error'])
                        ->withInput();
                } else {
                    Session::flash('error', 'Ada sesuatu yang salah, Data gagal dibuat');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    private function createModule(Request $request)
    {
        $validator = Validator::make(
            $request->all(), 
            [
                'trainingID' => 'required|unique:module',
                'filemodule'         =>  'required_if:urlvideo,""',
                'urlvideo'       =>  'required_if:filemodule,""'
            ],
            [
                'trainingID.required' => 'Training id tidak boleh kosong',
                'trainingID.unique' => 'Modul sudah ada',
                'filemodule.required_if' => 'File module tidak boleh kosong',
                'urlvideo.required_if' => 'Url tidak boleh kosong'
            ]
        );

        if ($validator->fails()) {
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            if ($request->filemodule) {
                $data = new Module();
                $data->trainingID = $request->trainingID;

                //For File Upload
                if ($request->hasFile($request->input('filemodule'))) {
                    $user_profile = time() . '.' . $request->file('filemodule')->getClientOriginalExtension();
                    $input_m['mediaoriginalname'] = $user_profile;
                    $input_m['mediatype'] = $request->file('filemodule')->getClientOriginalExtension();

                    if ($request->urlvideo) {
                        $input_m['urlvideo'] = $request->urlvideo;
                    }
                    if ($newFilePath = $request->file('filemodule')->move(public_path('uploads/module'), $user_profile)) {
                        $input_m['mediapath'] = "/uploads/module/" . $user_profile;
                        $mediaid = DB::table('media')
                            ->insertGetId($input_m);
                        $data['mediaid'] = $mediaid;
                    }
                    if ($data->save()) {
                        $return['response']['success'] = true;
                    } else {
                        $return['response']['success'] = false;
                    }
                }
            } else {
                if ($request->urlvideo) {
                    $data = new Module();
                    $data->trainingID = $request->trainingID;
                    $input_m['urlvideo'] = $request->urlvideo;
                    $mediaid = DB::table('media')
                        ->insertGetId($input_m);
                    $data['mediaid'] = $mediaid;
                    if ($data->save()) {
                        $return['response']['success'] = true;
                    } else {
                        $return['response']['success'] = false;
                    }
                } else {
                    $return['response']['success'] = false;
                }
            }
        }
        return $return;
    }

    public function getallundoneplan(Request $request)
    {
        $data = Training::where('spectraining_id', $request->id)
            ->wherenotIn('id', function ($query) {
                $query->select('TrainingID')
                    ->from('module')->get();
            })
            ->whereIn('status', array('1', '2'))
            ->get();
        return json_encode($data);
    }

    public function updateModule(Request $request, $id)
    {

        if ($request->isMethod('GET')) {

            $module = DB::table('module as m')
                ->leftjoin('trainings as t', 'm.trainingID', '=', 't.id')
                ->leftjoin('spectrains as s', 't.spectraining_id', '=', 's.id')
                ->leftjoin('media as md', 'm.mediaID', '=', 'md.id')
                ->select('m.*', 't.training_name', 's.competency', 'md.mediatype', 'md.mediapath', 'md.urlvideo', 't.spectraining_id')
                ->where('m.id', $id)
                ->first();

            $spectrains = Spectrain::all();
            $trainings = Training::where('spectraining_id', $module->spectraining_id)
                ->get();

            $data = array(
                'title' => 'Update Module',
                'slug' => 'module',
                'data' => $module,
                'specstrains' => $spectrains,
                'trainings' => $trainings
            );

            return view('modules.update', $data);
        }

        if ($request->isMethod('POST')) {

            // return $request->all();

            $validator = Validator::make($request->all(), [
                'kode_training' => 'required',
            ]);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $update = $this->editModule($request);

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

    private function editModule(Request $request)
    {
        $id = $request->id;
        $data = Module::find($id);
        $media = Media::find($data->mediaID);

        if ($request->hasFile($request->input('filemodule'))) {
            unlink(public_path($media->mediapath));

            $data->trainingID = $request->kode_training;
            $user_profile = time() . '.' . $request->file('filemodule')->getClientOriginalExtension();
            $input_m['mediaoriginalname'] = $user_profile;
            $input_m['mediatype'] = $request->file('filemodule')->getClientOriginalExtension();
            if ($request->urlvideo) {
                $media->urlvideo = $request->urlvideo;
            }
            if ($newFilePath = $request->file('filemodule')->move(public_path('uploads/module'), $user_profile)) {
                $media->mediapath = "/uploads/module/" . $user_profile;
            }
            if ($media->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        } else {
            if ($request->urlvideo) {
                $media->urlvideo = $request->urlvideo;
            }
            if ($media->save()) {
                $return['response']['success'] = true;
            } else {
                $return['response']['success'] = false;
            }
        }
        return $return;
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $module = Module::find($id);
        if ($id != null) {
            $return['success'] = true;
            if (Module::where('id', $id)->delete()) {
                Media::where('id', $module->mediaID)->delete();
                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
        } else {
            $return['success'] = false;
        }

        return $return;
    }

    public function redirect(Request $request)
    {
        $callback = $request->query("cb");
        return Redirect::away($callback);
    }
}
