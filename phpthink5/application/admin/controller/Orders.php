<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Orderbi;
use app\admin\model\Detalisorderbi;

class Orders extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);

        $data =Orderbi::paginate(10);
        $this->assign('order',$data);

        //分页
        $page = $data->render();
        $this->assign('page',$page);

        $data = ['1'=>'待发货(-未付款-)','2'=>'待发货','3'=>'已发货','4'=>'完成'];
        $this->assign('data',$data);

       	return $this->fetch('Admin/orders');
    }

    public function details()
    {
    	$id = $_POST['id'];

    	$data = Orderbi::get($id);

    	return $data->Orderbi;
    }


    //状态修改
    public function line()
    {
        $id = $_POST['id'];
        $order = Orderbi::get($id);

        //判断
        if ($order['state'] == '1') {

            return '1';
            
        }elseif($order['state'] == '2'){

            $order->state = '3';

            $order->save();

            return '2';

        }elseif($order['state'] == '3'){
            return '3';
        }elseif($order['state'] == '4'){
            return '4';
        }
    }

    //订单查询
    public function select()
    {   

        $name = $_POST['order-names'];

        $state = $_POST['order-select'];
        

        if ($state == '0') {
            
            $order = Orderbi::scope('Like','name','like','%'.$name.'%')->paginate(10);

            $this->assign('order',$order);
            
        }else{

            $order = Orderbi::scope('Likes','name','like','%'.$name.'%','state','=',$state)->paginate(10);

            $this->assign('order',$order);
        }


        //登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);

        //分页
        $page = $order->render();

        $this->assign('page',$page);

        $data = ['1'=>'待发货(-未付款-)','2'=>'待发货','3'=>'已发货','4'=>'完成'];
        $this->assign('data',$data);

        return $this->fetch('Admin/orders-select');
    }

    //订单删除
    public function deletes()
    {
        $id = $_POST['id'];

        $order = Orderbi::get($id);
        return $order->delete();

    }
}
