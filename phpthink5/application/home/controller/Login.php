<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Validate;
use app\home\model\Users;

use think\Cache;
use think\Session;
class Login extends Controller
{
    
    //登陆页面(加减验证码)
    public function index()
    {      
        $one = rand(0, 99);
        $two = rand(0, 99);

        //存入缓存 300秒
        Cache::set('names',$one+$two,300);

        $this->assign('one',$one);

        $this->assign('two',$two);       

        return $this->fetch('admin/login');
    }

    //登陆
    public function login()
    {
        $name = $_POST['name'];

        $pwd = $_POST['pwd'];

        $code = $_POST['code'];

        //缓存
        $codes = Cache::get('names');

        $data = [
            'name'=>$name,
            'pwd'=>$pwd,
            'code'=>$code,
        ];

        //查询
        $names = Users::get(['name'=>$name]);

        $user = $this->validate($data,'Login');


        if(true !== $user){
            return $user;
        //判断用户名
        }elseif($name != $names['name']){
            return '用户名错误';

        //判断密码
        }elseif (!password_verify($pwd,$names['pwd'])) {
           return '密码错误';

        //判断验证码
        }elseif((Cache::get('names')) == null){

            return '验证码过期了，请刷新';

        //判断验证码是否一致
        }elseif($code != Cache::pull('names')){
            return '验证码错误不可用请刷新';
        }else{
            Session::set('names',$name);

            return '1';
        }

    }

    //注册页面（验证码）
    public function post()
    {   
        $one = rand(0, 99);
        $two = rand(0, 99);

        //存入缓存 300秒
        Cache::set('name',$one+$two,300);

        $this->assign('one',$one);
        $this->assign('two',$two);

        return $this->fetch('admin/login-post');
    }
    
    //注册
    public function add()
    {   
        //用户名
        $name = $_POST['name'];
        //密码
        $pwd = $_POST['pwd'];
        //确定密码
        $repwd = $_POST['repwd'];
        //验证码
        $code = $_POST['code'];

        //验证模块
        $data = [
            'name'=> $name,
            'pwd'=>$pwd,
            'repwd'=>$repwd,
            'code'=>$code,
        ];

        $user = $this->validate($data,'Users');

        if(true !== $user){
            // 验证失败 输出错误信息
            return $user;

        //判断俩次密码是否一样
        }elseif($pwd != $repwd){

            return '密码不一致';

        //判断验证码是否存在
        }elseif((Cache::get('name')) == null){

            return '验证码过期了，请刷新';

        //判断验证码是否一致
        }elseif($code != Cache::pull('name')){
            return '验证码错误并不可用，请刷新';
        }

        //所有用户
        $users = Users::all();

        //判断用户名是否重复
        foreach($users as $v)
        {
            if ($name == $v['name']) {
                return '用户名已占有，请重新输入新的用户名';
            }
        }

        // //密码加密
        $pwds = password_hash($pwd, PASSWORD_DEFAULT);

        //添加用户
        $useradd = new Users;
        $useradd->name=$name;
        $useradd->pwd=$pwds;
        $useradd->pid='2'; 
        return $useradd->save();

    }

    //注册验证码刷新
    public function code()
    {
        $one = rand(1,99);
        $two = rand(1,99);

        Cache::set('name',$one+$two,300);

        $data[] = $one;
        $data[] = $two;

        return $data;
    }

    //登陆验证码刷新
    public function codes()
    {
        $one = rand(1,99);
        $two = rand(1,99);

        Cache::set('names',$one+$two,300);

        $data[] = $one;
        $data[] = $two;

        return $data;
    }

}
