<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Sorts; 
use app\home\model\Goods as goods;
use app\home\model\Detailsgoods;
use app\home\model\Orders;
use app\home\model\Detailsorder;
use app\home\model\Users;
use think\Session;
class Good extends Controller
{

	//商品
    public function index()
    {   

    	$id = $_GET['id'];

    	$good = goods::all($id);

    	foreach($good as $k => $v)
    	{	
            if ($v['state'] != 2) {
                $good = '';
                $image = '';
            }else{

        		$good2 = Detailsgoods::all(['pid'=>$v['id']]);

        		$good[$k]['goods'] = $good2;

        		//商品详情图片
        		foreach($good2 as $v)
        		{
        			$image = $v['image'];
        		}
            }

    	}

    	$this->assign('good',$good);

    	$this->assign('image',explode(',/',$image));

        return $this->fetch('admin/goods');
    }


    //确定购买
    public function atonce()
    {
        $id = $_POST['id'];

        //商品数量
        $sl = $_POST['number2'];
        //商品总价
        $zongprice = $_POST['zongprice'];

        $sl2 = $_POST['number'];
        
        $name = Session::get('names');
        $user = Users::get(['name'=>$name]);
        if (empty($name) == true) {
            return 'name';
        }       

        $good = goods::get($id);
        //商品价格
        $price = $good->Goo['price'];

        //判断购物车里面有没有一样的        
        $Detailsorder = Detailsorder::get(['pid'=>$id]);
        if (empty($Detailsorder) != true) {
            //有，去添加数量价格，
            $Detailsorder->number = $Detailsorder['number'] + intval($sl);
            $Detailsorder->zongprice = $Detailsorder['zongprice'] + intval($zongprice);
            $Detailsorder->pidnumber = $Detailsorder['pidnumber'] - intval($sl2);
            $Detailsorder->save();

            return Detailsorder::get(['pid'=>$id]);
        }
        $order = new Detailsorder;
        $order->image = $good['imageName'];
        $order->name = $good['name'];
        $order->price = $price;
        $order->number = $sl;
        $order->zongprice = $zongprice;
        $order->pidnumber = $sl2;
        $order->pid = $good['id'];
        $order->uid = $user['id'];
        $order->save();

        $add = Detailsorder::all(function($query){
            $query->order('id', 'desc');
        });

        return $add[0];

    }


    //购物车
    public function carts()
    {       
        $id = $_POST['id'];

        $name = Session::get('names');
        $user = Users::get(['name'=>$name]);
        if (empty($name) == true) {
            return 'name';
        }       

        $good = goods::get($id);

        //商品数量
        $sl = $_POST['number2'];
        //商品价格
        $price = $good->Goo['price'];
        //商品总价
        $zongprice = $_POST['zongprice'];

        $sl2 = $_POST['number'];
        
        //判断购物车里面有没有一样的        
        $Detailsorder = Detailsorder::get(['pid'=>$id]);
        if (empty($Detailsorder) != true) {
            //有，去添加数量价格，
            $Detailsorder->number = $Detailsorder['number'] + intval($sl);
            $Detailsorder->zongprice = $Detailsorder['zongprice'] + intval($zongprice);
            $Detailsorder->pidnumber = $Detailsorder['pidnumber'] - intval($sl2);
            return $Detailsorder->save();

            // return Detailsorder::get(['pid'=>$id]);
        }
        $order = new Detailsorder;
        $order->image = $good['imageName'];
        $order->name = $good['name'];
        $order->price = $price;
        $order->number = $sl;
        $order->zongprice = $zongprice;
        $order->pidnumber = $sl2;
        $order->pid = $good['id'];
        $order->uid = $user['id'];
        return $order->save();

        // $add = Detailsorder::all(function($query){
        //     $query->order('id', 'desc');
        // });

        // return $add;
        // foreach($add as $v)
        // {
        //     var_dump($v['name']);
        // }
    }

    //购物车页面
    public function MyCart()
    {   
        //用户
        $name = Session::get('names');

        $user = Users::get(['name'=>$name]);
        //根据用户获取商品详情
        $Detailsorder = Detailsorder::all(['uid'=>$user['id']]);

        //判断订单是否为空
        if (empty($Detailsorder) != true) {
            //根据商品详情获取商品id
            foreach($Detailsorder as $v)
            {
                $data = Detailsorder::get($v['id']);

                $arr[] = $data['id'];
            }
        }else{
            //订单商品id为空
            $arr[] = '';
        }

        //商品
        $order = Detailsorder::all($arr);

        $this->assign('order',$order);

        return $this->fetch('admin/cart');
    }



    //购物车删除
    public function Delete()
    {
        $id = $_POST['id'];
        //订单详情
        $data = Detailsorder::get($id);
        //删除购物车
        return $data->delete();

    }

    //确定购物车
    public function puls()
    {
        //购物车id + 购物数量
        $number = $_POST['number'];
        //转数组
        $number2 = explode(",", $number);
        //查询购物车
        $Detailsorder = Detailsorder::get($number2[0]);
        //商品
        $good = goods::get($Detailsorder['pid']);
        //确定 修改购物车数量 价钱 商品剩余
        $Detailsorder->number = $number2[1];
        $Detailsorder->zongprice = intval($number2[1]) * intval($Detailsorder['price']);
        $Detailsorder->pidnumber = intval($good->Goo['number']) - intval($number2[1]);
        return $Detailsorder->save();


    }


}
