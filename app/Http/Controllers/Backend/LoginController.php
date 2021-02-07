<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\JsonResponse;
use Session;
use Auth;
use DB;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    private function login(Request $request)
    {
        $data = [
            'request' => $request->all(),
            'response' => [
                'message' => 'An error occured',
                'error' => [],
            ],
            'status' => false,
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $infos = User::with('role', 'position', 'department', 'profpic')
                ->where('email', $request->email)
                ->where('status', 1)
                ->first();
            if ($infos) {
                $info['user_id'] = $infos->id;
                $info['username'] = $infos->name;
                $info['user_role'] = $infos->roleID;
                $info['user_role_name'] = $infos->role->name;
                $info['email'] = $infos->email;
                $info['phone'] = $infos->phone;
                $info['birthdate'] = $infos->datebirth;
                $info['address'] = $infos->address;
                $info['created_at'] = ($infos->created_at != null) ? $infos->created_at->toDateTimeString() : null;
                $info['updated_at'] = ($infos->created_at != null) ? $infos->updated_at->toDateTimeString() : null;
                // $info['profpic'] = $infos->profpic['imagepath'];
                $info['last_activity'] = $infos->last_activity;

                $data['status'] = true;
                $data['response']['data']['userinfo'] = $info;
                $data['response']['message'] = 'Login success.';
            } else {
                $data['response']['message'] = 'Validation error.';
                $data['response']['error'] = array(['User not active']);
            }
        } else {
            $data['response']['message'] = 'Validation error.';
            $data['response']['error'] = array(['Email / Password not found']);
        }
        return $data;
    }

    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {
            if (Session::get('userinfo') == "") {
                return view('login');
            } else {
                return redirect('/home/dashboard');
            }
        }

        if ($request->isMethod('POST')) {
            $response = $this->login($request);
            if ($response['status']) {
                Session::put('userinfo', $response['response']['data']['userinfo']);
                return redirect()->route('login');
            } else {
                return redirect()->back()
                    ->withErrors($response['response']['error']);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }
}
