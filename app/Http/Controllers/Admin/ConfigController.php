<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Config;
use \Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ConfigController extends CommonController
{
    //get.  admin/config 全部配置項列表
    public function index()
    {
        $data = Config::orderBy('conf_order', 'asc')->get();
        foreach($data as $k=>$v){
            switch ($v->field_type){
                case 'input':
                    $data[$k]->_html = '<input type="text" class="lg" name="conf_content[]" value="'.$v->conf_content.'">';
                    break;
                case 'textarea':
                    $data[$k]->_html = '<textarea type="text" class="lg" name="conf_content[]" >'.$v->conf_content.'</textarea>';
                    break;
                case 'radio':
                    //1|開啟, 0|關閉
                    $arr = explode(',', $v->field_value);
                    $str = '';
                    foreach($arr as $m => $n){
                        //1|開啟
                        $r = explode('|', $n);
                        $c = $v->conf_content==$r[0]? ' checked ' : '';
                        $str .= '<input type="radio" name="conf_content[]" value="'.$r[0].'"'.$c.'>' .$r[1].'　';
                    }
                    $data[$k]->_html = $str;
                    break;
            }
        }

        return view('admin.config.index', compact('data'));
    }

    public function changeContent()
    {
      $input =  Input::all();
        foreach ($input['conf_id'] as $k=>$v) {
            Config::where('conf_id', $v)->update(['conf_content' => $input['conf_content'][$k]]);
      }
      $this->putFile();
      return back()->with('errors', '配置項更新成功');
    }

    public function putFile()
    {
//       echo \Illuminate\Support\Facades\Config::get('web.web_count'); 前台不需要這麼長
       $config =  Config::pluck('conf_content', 'conf_name')->all();
       $path = base_path().'\config\web.php';
       $str = '<?php return '.var_export($config, true).';';

        file_put_contents($path, $str);
//       echo $path;
    }

    public function changeOrder()
    {

        $input = Input::all();
        $config = Config::find($input['conf_id']);
        $config->conf_order = $input['conf_order'];
        $re = $config->update();
        if($re){
            $data = [
                'status' => 0,
                'msg'    =>'配置項排序更新成功',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'配置項排序更新失敗，請稍後重試！',
            ];
        }
        return $data;
    }

    //get.  admin/config/create 新增分類
    public function create()
    {

        return view('admin/config/add');
    }

    //post.  admin/config 新增配置項提交
    public function store()
    {
        $input = Input::except('_token');

        $rules =[
            'conf_name' => 'required',
            'conf_title' => 'required',
        ];

        $message =[
            'conf_name.required' => '配置項名稱不能為空',
            'conf_title.required' => '配置項網址不能為空',
        ];

        $validator = Validator::make($input,$rules,$message);

        if($validator->passes()){

            $re = Config::create($input);
            if($re){
                return redirect('admin/config');
            }else{
                return back()->with('errors','新增失敗，請稍後重試!');
            }

        }else{
            return back()->withErrors($validator);
        }

    }

    //get. admin/config/{config}/edit 編輯配置項
    public function edit($conf_id)
    {
        $field = Config::find($conf_id);

        return view('admin.config.edit', compact('field'));
    }

    //put. admin/config/{config} 更新配置項
    public function update($conf_id)
    {
        $input = Input::except('_token', '_method');
        $re = Config::where('conf_id',$conf_id)->update($input);
        if($re){
            $this->putFile();
            return redirect('admin/config');
        }else{
            return back()->with('errors','修改失敗，請稍後重試!');
        }
    }

    //get. admin/config/{config} 顯示單個分類訊息
    public function show()
    {

    }

    //get. admin/config/{config} 刪除配置項
    public function destroy($conf_id)
    {
        $re = Config::where('conf_id', $conf_id)->delete();

        if($re){
            $this->putFile();
            $data = [
                'status' => 0,
                'msg'    =>'配置項刪除成功!',
            ];
        }else{
            $data = [
                'status' => 1,
                'msg'    =>'配置項刪除失敗，請稍後重試!',
            ];
        }
        return $data;
    }
}
