<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Validate;
use app\admin\model\Users;
class Userinsert extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    //显示添加用户模块
    public function index()
    {
        
        // return '123';
        $name = session::get('admin');   //获取用户
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);    //传输用户

        //视图
        return $this->fetch('Admin/userinsert');
    }


    //添加用户
    public function insert()
    {
        //获取的内容
        $name = $_POST['name'];
        $pwd = $_POST['pwd'];
        // return '123';

        //传输内容
        $data = [
            'name'=> $name,
            'pwd'=>$pwd,
        ];

        // $validate = Validate('Users');

        // if(!$validate->check($data)){
        //     return $validate->getError();
        //     // $this->error($validate->getError());
        // }
        //前往验证模块
        $user = $this->validate($data,'Users');
        if(true !== $user){
            // 验证失败 输出错误信息
            return $user;
        }

        //添加用户
        $users = new Users;
        $users->name= $name;
        $users->pwd= $pwd;
        $users->pid= '1';
        $users->save();
        return '成功插入';
     
    }


}
