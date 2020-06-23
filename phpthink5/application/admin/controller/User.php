<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;
use app\admin\model\Users; 
use app\admin\model\Address;
class User extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        //用户登陆
        $name = Session::get('admin');

        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        //传输用户登陆
        $this->assign('name',$name);
        //显示10个用户
        $user = Users::paginate(10);
        //分页
        $page = $user->render();
        //传输分页
        $this->assign('page', $page);
        //传输
        $this->assign('user',$user);
        //定义
        $res = [1=>'在线',2=>'禁用'];
        $data = [1=>'后台会员','普通会员'];
        //传输定义
        $this->assign('res',$res);
        $this->assign('data',$data);
        //视图
        return $this->fetch('Admin/user');


    }
    //用户删除
    public function delete()
    {
        // return '123';
        //传过来的id
        $id = $_POST['id'];
        //根据id删除
        $user = Users::get($id)->delete();

    }

    //用户修改
    public function edit()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $pwd = $_POST['pwd'];

        //查询数据
        $users = Users::get($id);
        //登陆用户
        $names = Session::get('names');

        //验证
        $result = $this->validate(
            [
                'name'=>$name,
                'pwd'=>$pwd,
            ],
            [
                'name'  => 'max:10',
                'pwd'  => 'alphaNum|max:18',
            ],
            [
                'name.max' => '名字不能超过10个字符',
                'pwd.alphaNum' => '密码必须是数字与字母',
                'pwd.max' => '密码不能超过18个字符',
            ]
        ); 

        //判断为空 赋值
        if (empty($name) == true) {
            $name = $users['name'];
        }
        if (empty($pwd) == true) {
            $pwd = $users['pwd'];
        }

        //判断验证
        if (true !== $result) {
            return $result;
        //判断是否为登陆用户
        }elseif ($users['name'] != $names) { //不是
            $user = Users::get($id);
            $user->name = $name;
            $user->pwd = $pwd;
            $user->save();
            //返回修改后
            // return $user=Users::get($id);
            return '1';
        }else{                                 //是
            $user = Users::get($id);
            $user->name = $name;
            $user->pwd = $pwd;
            $user->save();

            Session::set('admin',$name);

            //返回修改后
            // return $user=Users::get($id);      
            return '2';  
        }

        // var_dump($pwd);
        // $data = [
        //     'name'=> $name,
        //     'pwd'=>$pwd,
        // ];
        //去验证
        // $user = $this->validate($data,'Users');
        // if(true !== $user){
        //     // 验证失败 输出错误信息
        //     return $user;
        // }


        
    }

    //用户状态
    public function line()
    {
        $id = $_POST['id'];
        // return $id;
        $user = Users::get($id);

        if ($user['line'] == '1') {
            $user->line = '2';
            $user->save();
            return '禁用';
        }elseif ($user['line'] == '2') {
            $user->line = '1';
            $user->save();
            return '在线';
        }
    }

    //搜索
    public function select()
    {   
        //传过来的值
        $select_name = $_GET['name'];
        $select_value = $_GET['select'];


        $name = Session::get('admin');
        //传输用户登陆
        $this->assign('name',$name);

        //判断去搜索
        if ($select_value == '0') {
            //只搜索用户名显示10个
            $user = Users::scope('Like','name','like','%'.$select_name.'%')->paginate(10);   
            //传输
            $this->assign('user',$user);         
        }else{
            //用户名+状态
            $user = Users::scope('Likes','name','like','%'.$select_name.'%','line','=',$select_value)->paginate(10); 
            //传输
            $this->assign('user',$user);          
        }

        //传已经选择好的值，用来显示上一次选择好的值
        $this->assign('select_name',$select_name);
        $this->assign('select_value',$select_value);
        
        //分页
        $page = $user->render();
        //传输分页
        $this->assign('page', $page);

        //定义
        $res = [0 =>'--请选择--',1=>'在线',2=>'禁用'];
        $data = [1=>'后台会员','普通会员'];
        //传输定义
        $this->assign('res',$res);
        $this->assign('data',$data);
        //视图
        return $this->fetch('Admin/userselect');

    }

    //地址
    public function address()
    {
        // return '123';
        $id = $_POST['id'];

        $user = Users::get($id);

        //详情地址
        $address = $user->address;

        return $address;

    }

}
