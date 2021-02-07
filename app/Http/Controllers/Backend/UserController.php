<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Position;
use App\Models\Department;
use App\Models\Spectrain;
use Exception;
use App\Imports\UsersImport;
use Excel;
use Session;
use Validator;
use DataTables;
use DB;

class UserController extends Controller
{

    public function index()
    {
        $userinfo = Session::get('userinfo');

        if (!in_array($userinfo['user_role'], array('1', '2'))) {
            return redirect('/home/dashboard');
        }

        $position = Position::all();

        $data = array(
            'title' => 'User List',
            'slug' => 'users',
            'position' => $position
        );
        return view('users.index ', $data);
    }

    public function updateUser(Request $request, $id)
    {
        $userinfo = Session::get('userinfo');
        if ($id == $userinfo['user_id']) {
            $data['title'] = 'Update My profile';
        } else {
            $data['title'] = 'User Update';
            if ($userinfo['user_role'] != 1) {
                return redirect('/home/dashboard');
            }
        }
        if ($request->isMethod('GET')) {
            $position = Position::all();
            $spectrain = Spectrain::all();
            $department = Department::all();
            $roles = DB::table('roles')->get();

            $user = DB::table('users as u')
                ->where('u.id', $id)
                ->join('roles as r', 'u.roleID', '=', 'r.id')
                ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
                ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
                ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
                ->select('u.*', 'm.mediapath', 'r.name as role_name', 'd.name as department_name', 'd.code as department_code', 'p.name as position_name')
                ->first();

            if (!property_exists($user, 'mediapath')) {
                //                $media = $res = new \stdClass();
                $user->mediapath = null;
            }

            $data['slug'] = 'users';
            $data['data'] = $user;
            $data['roles'] = $roles;
            $data['positions'] = $position;
            $data['spectrains'] = $spectrain;
            $data['departments'] = $department;
            $data['media'] = $user->mediapath;
            //return $data;
            return view('users.update', $data);
        }

        if ($request->isMethod('POST')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $id,
                'photo' => 'image|nullable'
            ]);

            if ($validator->fails()) {
                Session::flash('error', 'Something wrong, Data created fail');
                Session::flash('mode', 'error');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $response = $this->editUser($request);
            }
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                return redirect()->back();
            } else {
                if ($response['response']['error']) {
                } else {
                    Session::flash('error', 'Something wrong, Data created fail');
                    Session::flash('mode', 'error');
                    return redirect()->back();
                }
            }
        }
    }

    public function detailUser(Request $request, $id)
    {
        if ($request->isMethod('GET')) {

            $user = DB::table('users as u')
                ->where('u.id', $id)
                ->join('roles as r', 'u.roleID', '=', 'r.id')
                ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
                ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
                ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
                ->select('u.*', 'm.mediapath', 'r.name as role_name', 'd.name as department_name', 'd.code as department_code', 'p.name as position_name')
                ->first();

            $media = DB::Table('media')
                ->where('id', $user->mediaID)
                ->select('media.mediapath')
                ->first();

            if (!$media) {
                $media = $res = new \stdClass();
                $media->mediapath = null;
            }

            $roles = DB::table('roles')
                ->get();

            $data = array(
                'title' => 'User Detail',
                'slug' => 'users',
                'flag' => false,
                'data' => $user,
                'media' => $media
            );

            return view('users.view', $data);
        }
    }

    public function getallkaryawan()
    {
        $data = User::where('roleID', 4)
            ->get();
        return json_encode($data);
    }

    public function addUser(Request $request)
    {
        if ($request->isMethod('GET')) {
            $position = Position::all();
            $spectrain = Spectrain::all();
            $department = Department::all();
            $roles = DB::table('roles')->select('roles.*')->get();
            //
            $data = array(
                'title' => 'User Add',
                'slug' => 'users',
                'roles' => $roles,
                'positions' => $position,
                'spectrains' => $spectrain,
                'departments' => $department,
            );

            return view('users.add', $data);
        }

        if ($request->isMethod('POST')) {
            $response = $this->storeUser($request);
            if ($response['response']['success']) {
                Session::flash('success', 'Data created successfully');
                Session::flash('mode', 'success');
                return redirect()->route('user.index');
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

    public function delete(Request $request)
    {
        $id = $request->id;
        if ($id != null) {
            $return['success'] = true;
            $user = DB::table('users as u')->where('u.id', $id)
                ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
                ->select('u.*', 'm.mediapath')->first();
            if ($user->mediapath) {
                unlink(public_path($user->mediapath));
            }
            if (User::where('id', $id)->delete()) {
                //Delete images
                $return['success'] = true;
            } else {
                $return['success'] = false;
            }
        } else {
            $return['success'] = false;
        }

        return $return;
    }

    private function storeUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'photo' => 'image|nullable'
        ]);

        if ($validator->fails()) {
            $return['response']['message'] = 'create user error.';
            $return['response']['success'] = false;
            $return['response']['error'] = $validator;
        } else {
            $data = new User();

            if ($request->role != 1) {
                $data->jabatanID = $request->position;
                $data->dateHired = $request->dateHired;
                $data->departmentID = $request->departmentID;
                $data->softskill = ($request->softskill) ? serialize($request->softskill) : serialize([]);
                $data->hardskill = ($request->hardskill) ? serialize($request->hardskill) : serialize([]);
                $data->nik = $request->nik;
                $data->npwp = $request->npwp;
            }
            $data->name = ucwords($request->name);
            $data->phone = $request->phone;
            $data->address = $request->address;
            $data->dateBirth = $request->dateBirth;

            if ($request->password) {
                $data->password = bcrypt($request->password);
            } else {
                $data->password = bcrypt('12345');
            }
            $data->roleID = $request->roleID;
            $data->email = $request->email;
            $data->status = $request->status;

            //For File Upload
            if ($request->hasFile($request->input('photo'))) {
                $user_profile = time() . '.' . $request->file('photo')->getClientOriginalExtension();
                $input_m['mediaoriginalname'] = $user_profile;
                $input_m['mediatype'] = $request->file('photo')->getClientOriginalExtension();
                $input_m['userid'] = 1;

                if ($newFilePath = $request->file('photo')->move(public_path('uploads/user_image'), $user_profile)) {
                    $input_m['mediapath'] = "/uploads/user_image/" . $user_profile;
                    $mediaid = DB::table('media')
                        ->insertGetId($input_m);
                    $data['mediaid'] = $mediaid;
                }
            }

            //Insert Data
            if ($data->save()) {
                $return['response']['message'] = 'create user success.';
                $return['response']['success'] = true;
            } else {
                $return['response']['message'] = 'Create user error.';
                $return['response']['success'] = false;
            }

            return $return;
        }
    }

    private function editUser(Request $requested)
    {
        $id = $requested['id'];
        $user = DB::table('users as u')->where('u.id', $id)
            ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')->select('u.*', 'm.mediapath')->first();

        $data['name'] = $requested['name'];
        $data["roleID"] = $requested['roleID'];
        $data['dateBirth'] = $requested['dateBirth'];
        $data["email"] = $requested['email'];
        $data["status"] = $requested['status'];
        $data['address'] = $requested['address'];

        if ($requested->password) {
            $data["password"] = bcrypt($requested['password']);
        } else {
            $data["password"] = bcrypt('12345');
        }

        //Jika bukan super admin

        if ($requested['roleID'] != 1) {
            $data["nik"] = $requested['nik'];
            $data['npwp'] = $requested['npwp'];
            $data['dateHired'] = $requested['dateHired'];
            $data["departmentID"] = $requested['departmentID'];
            $data["softskill"] = ($requested['softskill']) ? serialize($requested['softskill']) : serialize([]);
            $data["hardskill"] = ($requested['hardskill']) ? serialize($requested['hardskill']) : serialize([]);
            $data['jabatanID'] = $requested['position'];
        }

        //For File Upload
        if ($requested->hasFile($requested->input('photo'))) {

            $user_profile = time() . '.' . $requested->file('photo')->getClientOriginalExtension();
            $input_m['mediaoriginalname'] = $user_profile;
            $input_m['mediatype'] = $requested->file('photo')->getClientOriginalExtension();
            $input_m['userid'] = 1;

            if ($newFilePath = $requested->file('photo')->move(public_path('uploads/user_image'), $user_profile)) {
                // $input_m['mediapath']
                if ($user->mediapath) {
                    unlink(public_path($user->mediapath));
                }
                $input_m['mediapath'] = "/uploads/user_image/" . $user_profile;
                $mediaid = DB::table('media')
                    ->insertGetId($input_m);
                $data['mediaid'] = $mediaid;
            }
        }

        //Update Data
        $query = DB::table('users')->where('id', $id)->update($data);
        if ($query) {
            $return['response']['message'] = 'Update user success.';
            $return['response']['success'] = true;
        } else {
            $return['response']['message'] = 'Update user error.';
            $return['response']['success'] = false;
        }

        return $return;
    }

    public function datatable(Request $request)
    {

        if ($request->ajax()) {

            $data = DB::table('users')
                ->join('roles', 'users.roleID', '=', 'roles.id')
                ->select('users.*', 'roles.name as rolename')
                ->where('users.roleID', '>', '1')
                ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $userinfo = Session::get('userinfo');
                    if ($userinfo['user_role'] == 1) {
                        $btn = '<a href="/home/user/edit/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editUser">Edit</a>';

                        $btn = $btn . ' <span data-name="' . $row->name . '" data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteUser">Delete</span>';
                    } else {
                        $btn = '<a href="/home/user/view/' . $row->id . '" data-toggle="tooltip" data-name="' . $row->name . '"  data-id="' . $row->id . '" data-original-title="Detail" class="btn btn-primary btn-sm editUser">Detail</a>';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getProfile(Request $request)
    {
        $userinfo = Session::get('userinfo');
        if ($request->isMethod('GET')) {
            $userID = $userinfo['user_id'];
            $user = DB::table('users as u')
                ->where('u.id', $userID)
                ->join('roles as r', 'u.roleID', '=', 'r.id')
                ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
                ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
                ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
                ->select('u.*', 'm.mediapath', 'r.name as role_name', 'd.name as department_name', 'd.code as department_code', 'p.name as position_name')
                ->first();

            $data = array(
                'title' => 'My Profile',
                'slug' => 'users',
                'flag' => true,
                'data' => $user
            );
            return view('users.view', $data);
        }
    }

    public function updateProfile(Request $request)
    {
        $userinfo = Session::get('userinfo');
        if ($request->isMethod('GET')) {
            $userID = $userinfo['user_id'];
            $user = DB::table('users as u')
                ->where('u.id', $userID)
                ->join('roles as r', 'u.roleID', '=', 'r.id')
                ->leftjoin('media as m', 'u.mediaID', '=', 'm.id')
                ->leftjoin('departments as d', 'u.departmentID', '=', 'd.id')
                ->leftjoin('positions as p', 'u.jabatanID', '=', 'p.id')
                ->select('u.*', 'm.mediapath', 'r.name as role_name', 'd.name as department_name', 'd.code as department_code', 'p.name as position_name')
                ->first();

            $data = array(
                'title' => 'My Profile',
                'slug' => 'users',
                'data' => $user
            );
            return view('users.view', $data);
        } elseif ($request->isMethod('GET')) {
        }
    }

    public function import_excel(Request $request)
    {
        $userinfo = Session::get('userinfo');

        if ($userinfo['user_role'] == 1) {

            // validasi
            $validator = Validator::make($request->all(), [
                'excel' => 'required|mimes:xls,xlsx'
            ]);

            if ($validator->fails()) {
                $return['response']['success'] = false;
                $return['response']['error'] = $validator;

                $response = $return;
                Session::flash('error', 'Ada sesuatu yang salah, Gagal mengimport data');
                Session::flash('mode', 'error');
                return redirect()->route('user.index')
                    ->withErrors($response['response']['error']);
            } else {
                // Menangkap file excel
                $file = $request->file('excel');
                // Membuat nama file unik
                $nama_file = rand() . $file->getClientOriginalName();
                // Uplod ke folder /uploads/training_plan di dalam folder public
                if ($newFilePath = $request->file('excel')->move(public_path('/uploads/user_excel_temp'), $nama_file)) {
                    try {
                        Excel::import(new UsersImport, public_path('/uploads/user_excel_temp/' . $nama_file));
                    } catch (Exception $e) {
                        unlink(public_path('/uploads/user_excel_temp/' . $nama_file));
                        Session::flash('error', 'Gagal import data. ' . $e->getMessage());
                        Session::flash('mode', 'error');
                        return redirect()->route('user.index');
                    }
                    // Menghapus file yang sudah diproses
                    unlink(public_path('/uploads/user_excel_temp/' . $nama_file));
                    // notifikasi dengan session
                    Session::flash('success', 'Data data berhasil diimport');
                    Session::flash('mode', 'success');
                    // alihkan halaman kembali
                    return redirect()->route('user.index');
                }
            }
        }
    }

    // public function import_excel(Request $request)
    // {
    //     $userinfo = Session::get('userinfo');

    //     if($userinfo['user_role'] == 1) {

    //         // validasi
    //         $validator = Validator::make($request->all(), [
    //             'excel' => 'required'
    //         ]);

    //         if ($validator->fails()) {
    //             $return['response']['message'] = 'Import fail.';
    //             $return['response']['success'] = false;
    //             $return['response']['error'] = $validator;

    //         }else{

    //             if($request->hasFile($request->input('excel'))){

    //                 // import data
    //                 $path = $request->file('excel')->getRealPath();

    //                 $data = Excel::load($path)->get();

    //                 if($data->count()){

    //                     foreach ($data as $key => $value) {

    //                         $cek = User::where('email',$value['email'])->count();

    //                         if($cek == 0){

    //                             $jabatan = Position::where('name',$value['position'])
    //                                 ->select('positions.*')->first();

    //                             if($jabatan){
    //                                 $jabatanID = $jabatan->id;
    //                                 $departmentID = $jabatan->departmentID;
    //                             }else{
    //                                 $jabatanID = 0;
    //                                 $departmentID = 0;
    //                             }

    //                             $role = DB::table('roles')
    //                                 ->where('name',$value['role'])
    //                                 ->select('id')
    //                                 ->first();

    //                             if($role){
    //                                 $roleID = $role->id;
    //                             }else{
    //                                 $roleID = 0;
    //                             }

    //                             $softskill = explode(',',$value['softskill']);
    //                             $hardskill = explode(',',$value['hardskill']);

    //                             if(count($softskill)>0){
    //                                 $softskill = serialize($softskill);
    //                             }else{
    //                                 $softskill = null;
    //                             }

    //                             if(count($hardskill)>0){
    //                                 $hardskill = serialize($hardskill);
    //                             }else{
    //                                 $hardskill = null;
    //                             }



    //                             $arr = [
    //                                 'name'     => $value['name'],
    //                                 'email'    => $value['email'],
    //                                 'phone'    => $value['phone'],
    //                                 'password' => Hash::make($value['password']),
    //                                 'departmentID' => $departmentID,//tidak ada di $value//
    //                                 'jabatanID'    => $jabatanID,
    //                                 'roleID'    => $roleID,
    //                                 'datebirth'    => $value['birthdate']->format('Y-m-d'),
    //                                 'nik'    => $value['nik'],
    //                                 'npwp'    => $value['npwp'],
    //                                 'softskill'    => $softskill,
    //                                 'hardskill'    => $hardskill,
    //                                 'address'    => $value['address'],
    //                             ];

    //                             if(!empty($arr)){

    //                                 User::insert($arr);

    //                             }
    //                         }else{

    //                             $jabatan = Position::where('name',$value['position'])
    //                                 ->select('positions.*')->first();

    //                             if($jabatan){
    //                                 $jabatanID = $jabatan->id;
    //                                 $departmentID = $jabatan->departmentID;
    //                             }else{
    //                                 $jabatanID = 0;
    //                                 $departmentID = 0;
    //                             }

    //                             $role = DB::table('roles')
    //                                 ->where('name',$value['role'])
    //                                 ->select('id')
    //                                 ->first();

    //                             if($role){
    //                                 $roleID = $role->id;
    //                             }else{
    //                                 $roleID = 0;
    //                             }

    //                             $softskill = explode(',',$value['softskill']);
    //                             $hardskill = explode(',',$value['hardskill']);

    //                             if(count($softskill)>0){
    //                                 $softskill = serialize($softskill);
    //                             }else{
    //                                 $softskill = null;
    //                             }

    //                             if(count($hardskill)>0){
    //                                 $hardskill = serialize($hardskill);
    //                             }else{
    //                                 $hardskill = null;
    //                             }



    //                             $arr = [
    //                                 'name'     => $value['name'],
    //                                 'phone'    => $value['phone'],
    //                                 'password' => Hash::make($value['password']),
    //                                 'departmentID' => $departmentID,//tidak ada di $value//
    //                                 'jabatanID'    => $jabatanID,
    //                                 'roleID'    => $roleID,
    //                                 'datebirth'    => $value['birthdate']->format('Y-m-d'),
    //                                 'nik'    => $value['nik'],
    //                                 'npwp'    => $value['npwp'],
    //                                 'softskill'    => $softskill,
    //                                 'hardskill'    => $hardskill,
    //                                 'address'    => $value['address'],
    //                             ];

    //                             if(!empty($arr)){

    //                                 User::where('email',$value['email'])
    //                                 ->update($arr);

    //                             }
    //                         }
    //                     }
    //                 }

    //                 $return['response']['message'] = 'create user success.';
    //                 $return['response']['success'] = true;


    //             }

    //         }

    //         return $return;
    //     }
    // }
}
