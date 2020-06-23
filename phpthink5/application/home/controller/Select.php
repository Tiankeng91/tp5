<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Sorts; 
use app\home\model\Goods as goods;
use app\home\model\Detailsgoods;

class Select extends Controller
{
    //搜索
    public function index()
    {
        //值
        $name = $_POST['name'];
        //模糊搜索
        $goods = goods::scope('Like','name','like','%'.$name.'%')->select();

        foreach($goods as $k=>$v)
        {       
            //商品详情
            $goods[$k]['goods'] = Detailsgoods::all(['pid'=>$v['id']]);
        }

        $this->assign('goods',$goods);

        return $this->fetch('admin/select');
    }

    //搜索商品
    public function select()
    {   
        $id = $_GET['id'];

        //搜索分类
        $good = Sorts::all($id);

        //去获取这个分类下面所以分类的id
        $data = $this->aa($good);

        //搜索
        $goods = Sorts::all($data);

        //判断
        foreach($goods as $v)
        {   
            $g = Sorts::get($v['id']);

            //查看分类下面的商品
            $go = goods::where('uid',$v['id'])->select();

            //判断分类下面的商品是否为空
            if($go != null){

                //不为空 遍历
                foreach($go as $v)
                {
                    //判断是否上架
                    if ($v['state'] == 2) {

                        //数组
                        $arr[] = $v['id'];

                    //下架或者没有上架的商品
                    }else{

                        //数组
                        $arr[] = '';
                    }
                }

            //商品为空
            }else{
                $arr[] = '';
            }

        }

        //搜索商品
        $datas = goods::all($arr);

        //遍历
        foreach($datas as $k => $v)
        {   

            //商品详情  二维数组
            $datas[$k]['goods'] = Detailsgoods::all(['pid'=>$v['id']]);

        }

        //
        $this->assign('goods',$datas);

        return $this->fetch('admin/select');
    }

    //商品搜索2
    public function select2()
    {
        $id = $_GET['id'];

        $good = Sorts::all($id);

        $goo = Sorts::get($id);

        $data = $this->a($good,$goo['pid']);

        $sort = Sorts::all($data);

        foreach($sort as $v)
        {

            //查看分类下面的商品
            $go = goods::where('uid',$v['id'])->select();

            if ($go != null) {
                foreach($go as $v)
                {
                    if ($v['state'] == 2) {
                        $arr[] = $v['id'];
                    }else{
                        $arr[] = '';
                    }
                }
            }else{
                $arr[] = '';
            }
        }

        //搜索商品
        $datas = goods::all($arr);
        //遍历
        foreach($datas as $k => $v)
        {   
            $datas[$k]['goods'] = Detailsgoods::all(['pid'=>$v['id']]);
        }
        //商品
        $this->assign('goods',$datas);

        return $this->fetch('admin/select');
    }


    //分类循环  获取顶级分类下面所以分类的id
    public function aa($good,$pid=0)
    {   

        //遍历
        foreach($good as $k=>$v)
        {   
            //静态数组
            static $arr = array();

            if ($v['pid'] == $pid) {

                $arr[] = $v['id'];

                $this->aa(Sorts::all(),$v['id']);
            }
        }
        return $arr;
    }


    //二维分类循环
    public function a($good,$pid)
    {   

        static $arr = array();

        foreach($good as $v)
        {

            if ($v['pid'] == $pid) {

                $arr[] = $v['id'];

                $this->a(Sorts::all(),$v['id']);
            }
        }
        return $arr;
    }
}
