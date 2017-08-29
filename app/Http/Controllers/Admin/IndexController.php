<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Support\Facades\Crypt;
use \Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class IndexController extends CommonController
{
    public function index()
    {
        return view('admin.index');
    }

    public function info()
    {
        return view('admin.info');
    }

    //更改密碼
    public function pass()
    {
        if($input = Input::all()){

            $rules =[
                'password' => 'required|between:6,20|confirmed', //必填 + 長度 + 確認
            ];

            $message =[
                'password.required' => '新密碼不能為空',
                'password.between' => '新密碼必須在6~20位之間!',
                'password.confirmed' => '新密碼和確認密碼不一致!',
            ];

            $validator = Validator::make($input,$rules,$message);

            if($validator->passes()){
                $user = User::first();
                $_password = Crypt::decrypt($user->user_pass);
                if($input['password_o'] == $_password){
                    $user->user_pass = Crypt::encrypt($input['password']);
                    $user->update();
                    return back()->with('errors','密碼更改成功!');
                }else{
                    return back()->with('errors','原密碼輸入錯誤!');
                }

            }else{
                return back()->withErrors($validator);
            }

        }else{
            return view('admin.pass');
        }
    }
}
