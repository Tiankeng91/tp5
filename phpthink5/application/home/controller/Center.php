<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Users;
use app\home\model\Sorts; 
use app\home\model\Goods as goods;
use app\home\model\Detailsgoods;
use app\home\model\Orders;
use app\home\model\Detailsorder;
use app\home\model\Orderbi;
use app\home\model\Detailsorderbi;
use app\home\model\Address;
use think\Session;
use think\Validate;

class Center extends Controller
{
    //页面
    public function index()
    {	
        // return '123';
        $name = Session::get('names');

        $User = Users::get(['name'=>$name]);

        $Orderbi = Orderbi::all(['pid'=>$User['id']]);

        // foreach($Orderbi as $v)
        // {
        // 	var_dump($v['name']);
        // }
        $this->assign('order',$Orderbi);

        $data = ['1'=>'待发货(-未付款-)','2'=>'待发货','3'=>'已发货(-待收货-)','4'=>'确定收货(-完成-)'];
        $this->assign('data',$data);
        return $this->fetch('admin/center');
    }

    //订单详情页面
    public function order()
    {   
        $id = $_GET['id'];

        // $Orderbi = Orderbi::get($id);
        $Detailsorderbi = Detailsorderbi::all(['pid'=>$id]);

        $this->assign('Detailsorderbi',$Detailsorderbi);
        return $this->fetch('admin/centerOrder');
    }

    //订单收货
    public function state()
    {
        $id = $_POST['id'];
        //修改状态
        $Orderbi =Orderbi::get($id);
        $Orderbi->state = '4';
        return $Orderbi->save();
    }

    //个人中心页面
    public function center()
    {   
        $name = Session::get('names');

        $user =Users::all(['name'=>$name]);
        //地址
        foreach($user as $k=>$v)
        {
            $user[$k]['users'] = Address::all(function($query){
                $query->order('state', 'desc')->limit('1');
            });
        }
        $this->assign('user',$user);
        $this->assign('name',$name);
        return $this->fetch('admin/centerCenter');
    }

    //用户名修改
    public function name()
    {
        $id = $_POST['id'];
        $name =$_POST['name'];

        $user = Users::get($id);
        //验证
        $result = $this->validate(
            [
                'name'=>$name,
            ],
            [
                'name'  => 'require|max:6',
            ],
            [
                'name.require' => '不能为空',
                'name.max' => '不能超过10个字符',
            ]
        );
        if(true !== $result){
            // 验证失败 输出错误信息
            return $result;
        }elseif($name === $user['name']){
            return '修改的名字一致，修改失败';
        }

        $users = Users::all();

        foreach($users as $v)
        {
            if ($name === $v['name']) {
                return '用户名已拥有';
            }
        }

        //修改
        $user->name = $name;
        if($user->save()){
            //输入缓存
            Session::set('names',$name);

            return '1';
        }

    }

    //密码修改
    public function pwd()
    {
        $id = $_POST['id'];
        $pwd = $_POST['pwd'];
        $pwds = $_POST['pwds'];
          //验证
        $result = $this->validate(
            [
                'pwd'=>$pwd,
                'pwds'=>$pwds,
            ],
            [
                'pwd'  => 'require|alphaNum|max:18',
                'pwds'  => 'require|alphaNum|max:18',
            ],
            [
                'pwd.require' => '旧密码不能为空',
                'pwd.alphaNum' => '旧密码必须是数字与字母',
                'pwd.max' => '旧不能超过18个字符',
                'pwds.require' => '新密码不能为空',
                'pwds.alphaNum' => '新密码必须是数字与字母',
                'pwds.max' => '新密码不能超过18个字符',
            ]
        ); 

        $user =Users::get($id);
        if ($result !== true) {
            return $result;
        //数据库的密码和旧密码是否一致
        }elseif (!password_verify($pwd,$user['pwd'])) {
            return '旧密码不一致';
        }
        //修改
        $user->pwd = password_hash($pwds, PASSWORD_DEFAULT);
        return $user->save();

    }

    //支付码修改或者添加
    public function payment()
    {
        $id = $_POST['id'];
        $payment = $_POST['payment'];

        $User = Users::get($id); 

        $result = $this->validate(
            [
                'pwd'=>$payment,
            ],
            [
                'pwd'  => 'require|alphaNum|max:6',
            ],
            [
                'pwd.require' => '支付码不能为空',
                'pwd.alphaNum' => '支付码必须是数字与字母',
                'pwd.max' => '支付码不能超过6个字符',
            ]
        );


        if(true !== $result){
            return $result;
        }elseif($User['payment'] === $payment){
            return '与旧支付码一致，修改失败';
        }


        $User->payment = $payment;
        return $User->save();
    }

    //充值
    public function price()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        //验证
        $data = $this->Validate(
            [
                'name' => $name,
            ],
            [
                'name' => 'require|number|max:3',
            ],
            [
                'name.require' => '不能为空',
                'name.number' => '必须为数子',
                'name.max'=> '不能超过999元',
            ]
        );

        if(true !== $data){
            return $data;
        }
        //修改
        $User = Users::get($id);
        $User->price = $User['price'] + $name;
        return $User->save();
    }

}
