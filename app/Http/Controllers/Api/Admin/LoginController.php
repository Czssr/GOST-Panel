<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends BaseController
{

    public function login(Request $request)
    {
        $token = Auth::guard('admin')->attempt(['username' => $request->username, 'password' => $request->password]);
        if ($token) {
            if (Auth::guard('admin')->user()->user_id) {
                if (Auth::guard('admin')->user()->status !== 1) {
                    return $this->error('您的账号已被禁用');
                }
            }
            return $this->setStatusCode(200)->success('bearer ' . $token);
        }
        return $this->error('账号或密码错误', 400);
    }


    public function info(Request $request) {
        $data = Auth::user();
        return $this->success($data);
    }


    public function logout(Request $request) {
        Auth::logout();
        return $this->success();
    }


    public function resetPassword(Request $request) {
        Auth::user()->password = Hash::make($request->password);
        return $this->success(Auth::user()->save());
    }

}
