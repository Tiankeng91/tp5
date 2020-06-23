<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Index extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        // return '321';

        $name = Session::get('admin');

        if (empty($name) == true) {
            
            return $this->fetch('Admin/login');
        }else{
            $data = Session::get('admin');
            
            $this->assign('name',$data);

            return $this->fetch('index/index');
        }


        // if(!Session('names')){
        // }else{
        //     $data = Session::get('name');
        //     // return $data;
        //     $this->assign('name',$data);

        //     return $this->fetch('index/index');
        // }


    }


}
