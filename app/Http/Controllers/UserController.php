<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request) {
        $data = $request->user;

        if(!$data['name'] || !$data['mail'] || !$data['pass'] || !$data['repass']) {
            $result['code'] = 101;
            print_r(json_encode($result));
            return;
            //required fields are not filled
        }

        if($data['pass'] != $data['repass']) {
            $result['code'] = 102;
            print_r(json_encode($result));
            return;
            //passwords don`t match
        }

        if(!filter_var($data['mail'], FILTER_VALIDATE_EMAIL)) {
            $result['code'] = 103;
            print_r(json_encode($result));
            return;
            //incorrect email
        }

        $duplicate = User::select('name')
            ->where('mail', $data['mail'])
            ->get();

        if(count($duplicate)) {
            $result['code'] = 104;
            print_r(json_encode($result));
            return;
            //account with same email already exists
        }

        User::create([
            'name' => $data['name'],
            'mail' => $data['mail'],
            'role_id' => 1,
            'password' => $data['pass']
        ]);

        $result['code'] = 120;
        print_r(json_encode($result));
    }

    public function log_in(Request $request) {
        $data = $request->user;

        if(!$data['mail'] || !$data['pass']) {
            $result['code'] = 101;
            print_r(json_encode($result));
            return;
            //required fields are not filled
        }

        $match = User::select('users.id', 'users.name', 'users.password', 'roles.name as role')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->where('users.mail', $data['mail'])
            ->first();

        if(!$match) {
            $result['code'] = 102;
            print_r(json_encode($result));
            return;
            //no account with such email
        }

        if($match['password'] != $data['pass']) {
            $result['code'] = 103;
            print_r(json_encode($result));
            return;
            //incorrect password
        }

        session()->put('user_id', $match['id']);
        session()->put('name', $match['name']);
        session()->put('role', $match['role']);
        $result['code'] = 120;
        print_r(json_encode($result));
    }

    public function log_out() {
        session()->flush();
        print_r(json_encode('success'));
    }
}
