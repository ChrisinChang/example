<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use \Validator;

class CategoryController extends CommonController
{
    //get.  admin/category 全部分類列表
    public function index()
    {
        $categorys = (new Category)->tree();
//        $categorys = Category::tree();
//        $data = $this->getTree($categorys,'cate_name','cate_id', 'cate_pid');
        return view('admin.category.index')->with('data', $categorys);
    }

    public function changeOrder()
    {
        $input = Input::all();
        $cate = Category::find($input['cate_id']);
        $cate->cate_order = $input['cate_order'];
        $re = $cate->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'分類排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'分類排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //get.  admin/category/create 新增分類
    public function create()
    {
        $data = Category::where('cate_pid', 0)->get();
        return view('admin.category.add',compact('data'));
    }

    //post.  admin/category 新增分類提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'cate_name' => 'required',
        ];

        $message =[
            'cate_name.required' => '分類名稱不能為空',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

        $re = Category::create($input);
        if($re){
           return redirect('admin/category');
        }else{
            return back()->with('errors','新增失敗，請稍後重試!');
        }

        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/category/{category}/edit 編輯分類
    public function edit($cate_id)
    {
        $field = Category::find($cate_id);
        $data = Category::where('cate_pid', 0)->get();
        return view('admin.category.edit', compact('field','data'));
    }

    //put. admin/category/{category} 更新分類
    public function update($cate_id)
    {
        $input = Input::except('_token', '_method');
        $re = Category::where('cate_id',$cate_id)->update($input);
        if($re){
            return redirect('admin/category');
        }else{
            return back()->with('errors','修改失敗，請稍後重試!');
        }
    }

    //get. admin/category/{category} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/category/{category} 刪除單個分類
    public function destroy($cate_id)
    {
        $re = Category::where('cate_id', $cate_id)->delete();
        Category::where('cate_pid', $cate_id)->update(['cate_pid'=>0]);//有子分類情況，把子分類變成主分類
        if($re){
            $data = [
              'status' => 0,
              'msg'    =>'分類刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'分類刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }


}
