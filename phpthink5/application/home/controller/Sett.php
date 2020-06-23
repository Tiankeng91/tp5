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

class Sett extends Controller
{
    
    //显示
    public function index()
    {   
        //url的id
        $id = $_GET['id'];
        //去掉末尾
        $substr = substr($id,0,strlen($id)-1);

        //搜索购物车商品
        $orders = Detailsorder::all($substr);

        // foreach($orders as $k=>$v)
        // {
        //     // var_dump($v['name']);
        //     $orders[$k]['order'] = Detailsorder::all(['uid'=>$v['id']]);
        // }

        $this->assign('orders',$orders);

        $name = Session::get('names');

        $user = Users::get(['name'=>$name]);

        //地址，排序
        // $address = Address::all(function($query,$id=30){
        //     $query->where('pid',$id);->order('state', 'desc')
        //     // $query->where('pid',$user['id']);
        // });

        $address = Address::scope('Adds',$user['id'])->select();

        $this->assign('address',$address);

        //state的css值
        $data = [1=>'display: none',2=>''];
        //state的checked
        $datas = [1=>'',2=>'checked'];

        //没有数据的时候
        $this->assign('empty','<span class="empty">没有数据</span>');
        $this->assign("data",$data);
        $this->assign("datas",$datas);

        return $this->fetch('admin/sett');
    }


    //没有选择默认地址
    public function addressx()
    {
        // return '123';
        $address = Address::all();

        foreach($address as $v)
        {   
            // var_dump($v['id']);
            if ($v['state'] == '1') {
                $arr[] = $v['id'];
            }

        }

        // var_dump(count($arr));
        if (count($arr) == 1) {

            $address2 = Address::all(function($query){
                $query->order('id', 'desc');
            });

            // var_dump($address2);
            foreach($address2 as $v)
            {   
                // var_dump($v['name']);
                $add = Address::get($v['id']);
                $add->state = '2';
                if($add->save()){
                    return Address::get($v['id']);
                }
            }
        }
   
    }

    //地址添加
    public function address()
    {   
        //值
        $name = $_POST['name'];
        $region = $_POST['region'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $checkbox = $_POST['checkbox'];

        //验证
        $data=[
            'name'=>$name,
            'region'=>$region,
            'address'=>$address,
            'phone'=>$phone,
        ];

        //获取用户
        $session = Session::get('names');
        $user = Users::get(['name'=>$session]);

        //地址数量
        $count = count($user->address);

        //验证
        $setts = $this->Validate($data,'Sett');
        //错误
        if ($setts !== true) {
            return $setts;

        //地址数量
        }elseif($count == '5'){  

            return '地址数量上限，请删除一些';
        
        //判断是否默认
        }elseif ($checkbox == '2') {

            //获取所有地址
            $address = Address::all();

            //修改地址所有的state的值
            foreach($address as $v)
            {
                $add = Address::get($v['id']);
                $add->state = '1';
                $add->save();
            }               

            //添加地址
            $sett = new Address;
            $sett->pid = $user['id'];
            $sett->name = $name;
            $sett->region = $region;
            $sett->address= $_POST['address'];;
            $sett->phone= $phone;
            $sett->state= $checkbox;
            $sett->save();

            //获取新添加地址
            $address = Address::all(function($query){
                $query->order('id', 'desc');
            });

            return $address;

        //没有选择默认地址
        }else{

            $sett = new Address;
            $sett->pid = $user['id'];
            $sett->name = $name;
            $sett->region = $region;
            $sett->address=$address;
            $sett->phone=$phone;
            $sett->state=$checkbox;
            $sett->save();


            $address = Address::all(function($query){
                $query->order('id', 'desc');
            });

            return $address;
        }


    }

    //地址删除
    public function delet()
    {   
        //地址
        $id = $_POST['id'];
        $address = Address::get($id);

        //判断是否默认地址
        if ($address['state'] == '2') {

            //搜索数量2个
            $address2 = Address::all(function($query){
                $query->order('state', 'desc')->limit('2');
            });

            // var_dump(count($address2));
            //判断地址剩余量
            if (count($address2) == '2') {
                //删除
                $address->delete();
                //搜索现在地址下面一位
                $add = Address::get($address2[1]['id']);
                //修改它
                $add->state = '2';  

                if($add->save()){
                    //返回
                    return $address2[1];
                }

            //删除，没有地址了
            }else{
                $address->delete();
                return '没有数据';

            }

        //不是默认地址
        }else{

            return $address->delete();
        }

    }

    //修改默认地址
    public function defaults()
    {   
        //搜索默认地址修改它
        $data = Address::get(['state'=>'2']);
        $data->state = '1';
        $data->save();

        //值
        $id = $_POST['id'];

        //修改现在的地址为默认
        $address2 = Address::get($id);
        $address2->state = '2';
        $address2->save();

        //返回上一位默认地址。无刷新页面需要
        return $data;
    
    }

    //支付码注册
    public function payment()
    {
        // return '123';
        $pwd = $_POST['pwd'];
        $pwds = $_POST['pwds'];

        $result = $this->validate(
            [
                'pwd'=>$pwd,
                'pwds'=>$pwds,
            ],
            [
                'pwd'  => 'require|alphaNum|max:6',
                'pwds'  => 'require|alphaNum|max:6',
            ],
            [
                'pwd.require' => '注册支付码不能为空',
                'pwd.alphaNum' => '注册支付码必须是数字或者字母',
                'pwd.max' => '注册支付码不能超过6位',
                'pwds.require' => '确定支付码不能为空',
                'pwds.alphaNum' => '确定支付码必须是数字或者字母',
                'pwds.max' => '确定支付码不能超过6位',
            ]
        );

        if(true !== $result){

            return $result;
        }elseif ($pwd !== $pwds) {
            return '两个支付码不一致';
        }else{
            $name = Session::get('names');
            $user = Users::get(['name'=>$name]);
            $user->payment = $pwds;
            return $user->save();
        }
    }

    //支付提交
    public function sett()
    {    

        //支付码值
        $pwd = $_POST['pwd'];
        //用户
        $User = Session::get('names');
        $Users = Users::get(['name'=>$User]);

        //总价
        $price = $_POST['price'];


        if (empty($Users['payment']) == true) {
            return '1';
        }

        //验证支付码
        $result = $this->validate(
            [
                'pwd'=>$pwd,
            ],
            [
                'pwd'  => 'require|alphaNum|max:6',
            ],
            [
                'pwd.require' => '不能为空',
                'pwd.alphaNum' => '必须是数字或者字母',
                'pwd.max' => '不能超过6位',
            ]
        );
        if(true !== $result){
            // 验证失败 输出错误信息
            return $result;
        //判断支付码是否正确
        }elseif(!password_verify($pwd,$Users['pwd'])){
            return '付款码错误';
        }elseif(!($Users['price'] >= $price)){
            return '账号余额不足，请充值';
        }

        //地址id
        $id = $_POST['id'];
        //购物车id
        $arr = $_POST['arr'];

        //订单编号
        $date = date("YmdHis");
        //地址
        $address = Address::get($id);

        //订单添加
        $orderbi  = new orderbi;
        $orderbi->pid = $Users['id'];
        $orderbi->name = $date;
        $orderbi->date = date("Y/m/d,H/i/s");
        $orderbi->dates = date("Y/m/d,H/i/s");
        $orderbi->state = '2';
        $orderbi->names = $address['name'];
        $orderbi->address = $address['region'].'-'.$address['address'];
        $orderbi->phone = $address['phone'];
        $orderbi->price = $price;
        if($orderbi->save()){
            //订单详情商品
            foreach($arr as $v)
            {
                $orders = Detailsorder::get($v);

                $or['name'] = $orders['name'];
                $or['image'] = $orders['image'];
                $or['number'] = $orders['number'];
                $or['price'] = $orders['zongprice'];

                if($orderbi->Orderbi()->save($or)){
                    //删除购物车的物品
                    $orders->delete();
                    //商品
                    $good = goods::all(['id'=>$orders['pid']]);
                    foreach($good as $v)
                    {   
                        //修改商品数量
                        $goods = goods::get($v['id']);
                        $goods->Goo->number = $goods->Goo['number'] - $orders['number'];
                        $goods->Goo->save();          

                        //判断商品为0 下架
                        if ($goods->Goo['number'] == 0) {
                            $goods->state = '3';
                            $goods->save();
                        }

                    }
                    //用户钱计算
                    $Users->price = $Users['price'] - $orders['zongprice'];
                    $Users->save();
                }

            }

        }


    }

}
