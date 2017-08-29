<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Links;
use \Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class LinksController extends CommonController
{
    //get.  admin/links 全部友情連結列表
    public function index()
    {
        $data = Links::orderBy('link_order', 'asc')->get();

        return view('admin.links.index', compact('data'));
    }

    public function changeOrder()
    {

        $input = Input::all();
        $links = Links::find($input['link_id']);
        $links->link_order = $input['link_order'];
        $re = $links->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'友情連結排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'友情連結排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //get.  admin/links/create 新增分類
    public function create()
    {

        return view('admin/links/add');
    }

    //post.  admin/links 新增友情連結提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'link_name' => 'required',
            'link_url' => 'required',
        ];

        $message =[
            'link_name.required' => '友情連結名稱不能為空',
            'link_url.required' => '友情連結網址不能為空',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            $re = Links::create($input);
            if($re){
                return redirect('admin/links');
            }else{
                return back()->with('errors','新增失敗，請稍後重試!');
            }

        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/links/{links}/edit 編輯友情連結
    public function edit($link_id)
    {
        $field = Links::find($link_id);

        return view('admin.links.edit', compact('field'));
    }

    //put. admin/links/{links} 更新友情連結
    public function update($link_id)
    {
        $input = Input::except('_token', '_method');
        $re = Links::where('link_id',$link_id)->update($input);
        if($re){
            return redirect('admin/links');
        }else{
            return back()->with('errors','修改失敗，請稍後重試!');
        }
    }

    //get. admin/links/{links} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/links/{links} 刪除友情連結
    public function destroy($link_id)
    {
        $re = Links::where('link_id', $link_id)->delete();

        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'友情連結刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'友情連結刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }
}
