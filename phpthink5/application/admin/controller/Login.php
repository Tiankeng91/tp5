<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;

use think\Session;
use app\admin\model\Users;

class Login extends Controller
{

    public function index()
    {   
        // 登录页传过来的值
        $name = $_POST['name'];
        $pwd = $_POST['pwd'];
        // //查看数据库用户有没有
        $Login = Users::get(['name' => $name]);

        $data = [
            'name'=> $name,
            'pwd'=>$pwd,
        ];
        //前往验证模块
        $user = $this->validate($data,'Users');
        if(true !== $user){
            // 验证失败 输出错误信息
            return $user;
        }elseif(empty($Login) == true){
            return '用户或者密码错误，请重新登陆';
        }elseif($Login['pid'] != '1'){
            return '该用户不是后台用户';
        }elseif($Login['line'] == '2'){
            return '用户已经禁用';
        }elseif ($Login['name'] == $name && $Login['pwd'] == $pwd){
            Session::set('admin',$name);
            return '1';
        }


    }

    //注销用户
    public function sign()
    {
        // return '123sadas';
        session(null);
        return $this->fetch('Admin/login');
    }

}
