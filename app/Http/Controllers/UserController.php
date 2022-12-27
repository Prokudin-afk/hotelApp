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

        Post::create([
            'name' => $data['name'],
            'mail' => $data['mail'],
            'role_id' => 1,
            'password' => $data['pass']
        ]);

        $result['code'] = 120;
        print_r(json_encode($result));
        return;
    }

    public function log_out() {
        session()->flush();
        print_r(json_encode('success'));
    }
}
