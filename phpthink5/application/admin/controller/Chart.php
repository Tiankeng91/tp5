<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Sorts;
use app\admin\model\Goods as goodss;
use app\admin\model\Detailsgoods;
use app\admin\model\Detailsorder;
use app\admin\model\Charts as charts;
use think\Validate;
class Chart extends Controller
{
    //轮播图
    public function index()
    {
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);

        $charts = charts::paginate(10);
        //分页
        $page = $charts->render();
        $this->assign('charts', $charts);    
        $this->assign('page', $page);    

        return $this->fetch('Admin/chart');
    }

    //轮播图添加
    public function add()
    {
        //登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);

        $goods = goodss::all();

        $this->assign('goods',$goods);

        return $this->fetch('Admin/chartAdd');
    }

    //确定添加
    public function insert()
    {   
        //图片
        $file = request()->file('file2');
        //商品id
        $id = request()->post('chart-select');
        //验证
        $filesValidate = $this->validate(['files'  => $file],['files'  => 'image'],['files.image'=>'轮播图片必须是图片']);
        if (true !== $filesValidate) {
            return $filesValidate;
        }
        $goods = goodss::get($id);
        //上传图片
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
        //上传成功添加数据库
        if ($info) {
            //图片
            $image = $info->getSaveName();
            //添加
            $charts = new Charts;
            $charts->image = $image;
            $charts->pid = $id;
            $charts->name = $goods['name'];
            return $charts->save();
        }

    }

    //删除
    public function deletes()
    {
        $id = $_POST['id'];

        $charts = charts::get($id);

        if ($charts->delete()) {
            //删除图片
            unlink(ROOT_PATH . 'public' . DS . 'uploads' .'/'.$charts['image']);

            return '1';
        }
    }

}
