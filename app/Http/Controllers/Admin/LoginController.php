<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Input;

require_once 'resources/org/code/code.class.php';

class LoginController extends CommonController
{

    public function login()
    {
        if($input = Input::all()){
            $code = new \Code;
            $_code = $code->get();

            if(strtoupper($input['code']) != $_code){
              return back()->with('msg','驗證碼錯誤!');
            }
            $user = User::first();
            if($user->user_name != $input['user_name'] || Crypt::decrypt($user->user_pass) != $input['user_pass']){
                return back()->with('msg','帳號或密碼錯誤!');
            }
            session(['user'=>$user]);
//            dd(session('user'));
//            echo '登入成功';
            return redirect('admin/index');
        }else{

            return view('admin.login');
        }

    }

    public function code()
    {
        $code = new \Code;
        $code->make();
    }

    public function quit()
    {
        session(['user' => null]);
        return redirect('admin/login');

    }


    public function crypt()
    {
        //類似md5，laravel的加密方法
        $str = '123456';
        echo Crypt::encrypt($str);
    }

}
