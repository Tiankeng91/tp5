<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Orders;
use app\admin\model\Detailsorder;

class Address extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //登陆
        $name = Session::get('names');

        $this->assign('name',$name);

        return $this->fetch('Admin/address');
    }


}
