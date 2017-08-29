<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Navs;
use \Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class NavsController extends CommonController
{
    //get.  admin/navs 全部自定義導航列表
    public function index()
    {
        $data = Navs::orderBy('nav_order', 'asc')->get();

        return view('admin.navs.index', compact('data'));
    }

    public function changeOrder()
    {

        $input = Input::all();
        $navs = Navs::find($input['nav_id']);
        $navs->nav_order = $input['nav_order'];
        $re = $navs->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'自定義導航排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'自定義導航排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //get.  admin/navs/create 新增分類
    public function create()
    {

        return view('admin/navs/add');
    }

    //post.  admin/navs 新增自定義導航提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'nav_name' => 'required',
            'nav_url' => 'required',
        ];

        $message =[
            'nav_name.required' => '導航名稱不能為空',
            'nav_url.required' => '導航網址不能為空',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            $re = Navs::create($input);
            if($re){
                return redirect('admin/navs');
            }else{
                return back()->with('errors','新增失敗，請稍後重試!');
            }

        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/navs/{navs}/edit 編輯自定義導航
    public function edit($nav_id)
    {
        $field = Navs::find($nav_id);

        return view('admin.navs.edit', compact('field'));
    }

    //put. admin/navs/{navs} 更新自定義導航
    public function update($nav_id)
    {
        $input = Input::except('_token', '_method');
        $re = Navs::where('nav_id',$nav_id)->update($input);
        if($re){
            return redirect('admin/navs');
        }else{
            return back()->with('errors','修改失敗，請稍後重試!');
        }
    }

    //get. admin/navs/{navs} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/navs/{navs} 刪除自定義導航
    public function destroy($nav_id)
    {
        $re = Navs::where('nav_id', $nav_id)->delete();

        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'自定義導航刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'自定義導航刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }
}
