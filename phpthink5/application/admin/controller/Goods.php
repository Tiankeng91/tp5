<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Sorts;
use app\admin\model\Goods as goodss;
use app\admin\model\Detailsgoods;
use app\admin\model\Detailsorder;
use think\Validate;
class Goods extends Controller
{
    public function index()
    {   
        //登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);
    
        //显示加分页
        $goods = goodss::paginate(10);

        $this->assign('goods',$goods);
        //分页
        $page = $goods->render();

        $this->assign('page',$page);

        //分类
        $sort = new Sorts();

        $sorts = $sort->selects();

        $this->assign('sort',$sorts);

        //定义
        $data = ['1'=>'不上架','2'=>'上架','3'=>'下架'];  

        $this->assign('data',$data);
        //视图
        return $this->fetch('Admin/goods');
    }
    //详情信息
    public function details()
    {
        //id值
        $id = $_POST['id'];
        //根据id查询
        $data = goodss::get($id);
        //查询关联数据库表
        return $data->Goo;

    }
    //详情显示图片
    public function edits()
    {   
        //值
        $id = $_POST['id'];
        //查询
        $data = goodss::get($id);
        //关联数据库
        $file = $data->Goo;
        //转换成数组
        $files = explode(",/", $file['image']);
        //拼数据库加关联数据库
        return $data['imageName'].',/'.$file['image'];
    }
    //修改
    public function edit()
    {
        //值
        $name = request()->post('file1');
        $price = request()->post('price');
        $names = request()->post('names');
        $number = request()->post('number');

        $file = request()->file('file2');
        $files = request()->file('image');

        $id = request()->post('div');

        //id
        $goods = goodss::get($id);

        //关联一对一
        $Goo = $goods->Goo;

        //判断商品图标
        if ($file != NULL) {
            //上传
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            //删除原来的
            unlink(ROOT_PATH . 'public' . DS . 'uploads' .'/'.$goods['imageName']);
            //获取名称
            $image = $info->getSaveName();
            //修改
            $goods->imageName = $image;
            //确定修改
            $goods->save();
            
        
        //修改名称            
        }
        if ($name != NULL) {

            $nameValidate = $this->validate(['name'  => $name],['name'  => 'max:15'],['name.max'=>'修改商品名称不能超过15子符']);            
            if (true !== $nameValidate) {
                return $nameValidate;
            }else{

                $goods->name = $name;

                $goods->save();
            }

        //修改商品价格
        }
        if($price != NULL) {
            $priceValidate = $this->validate(['price'  => $price],['price'  => 'max:8'],['price.max'=>'修改商品价格不能超过8子符']);

            if (true !== $priceValidate) {
                return $priceValidate;
            }else{
                $goods->Goo->price = $price;

                $goods->Goo->save();

            }

        //修改商品介绍
        }
        if ($names != NULL) {
            
            $nasesValidate = $this->validate(['name'  => $names],['name'  => 'max:30'],['name.max'=>'修改商品介绍不能超过30子符']);
            if(true !== $nasesValidate){
                // 验证失败 输出错误信息
                return $nasesValidate;
            }else{

                $goods->Goo->names = $names;

                $goods->Goo->save();

            }

        //修改商品数量
        }
        if($number != NULL){
            $numberValidate = $this->validate(['number'  => $number],['number'  => 'max:4'],['number.max'=>'修改商品数量不能超过4位数']);
            if(true !== $numberValidate){
                return $numberValidate;
            }else{
                $goods->Goo->number = $number;

                $goods->Goo->save();

           
                // $goods1[] = goodss::get($id);

                // return $goods1;
            }
            
        //修改多图片
        }
        if ($files != NULL) {
            //定义
            static $itm = array();

            //循环
            foreach($files as $v)
            {   
                //验证
                $filesValidate = $this->validate(['files'  => $v],['files'  => 'image'],['files.image'=>'修改商品图片必须是图片']);

                if (true !== $filesValidate) {
                    return $filesValidate;
                }else{

                    //上传
                    $k = $v->move(ROOT_PATH . 'public' . DS . 'uploads');
                    if ($k) {
                        //输入数组
                        $itm[] = $k->getSaveName();
                    }

                }

            } 
            //转字符串
            $str = implode(',/',$itm);    

            //循环删除
            foreach(explode(',/',$Goo['image']) as $v)
            {
                unlink(ROOT_PATH . 'public' . DS . 'uploads' .'/'.$v);
            }  

            //修改
            $goods->Goo->image = $str;
            
            //确定修改
            $goods->Goo->save();



        }
        
        //什么也不干返回2
        return goodss::get($id);
 
    }

    //商品为0的时候去显示状态
    public function editState()
    {

        $id = $_POST['id'];

        //商品详情
        $good = goodss::get($id);

        $goods = $good->Goo;
        //商品数量 == 0
        if ($goods['number'] == '0') {

            $good->state = '3';

            $good->save();

            return '1';
        }else{
            $good->state = '2';

            $good->save();

            return '2';
        }


    }


    //删除
    public function delete()
    {   
        //id值
        $id = $_POST['id'];
        //查询
        $goods = goodss::get($id);
        //查询关联
        $file = $goods->Goo;
        //把字符串转数组
        $goodss = explode(',/',$file['image']);
        // 确定删除表
        if ($goods->delete()) {
            //确定删除关联数据
            if ($goods->Goo->delete()) {
                //去循环删除关联数据的图片
                foreach($goodss as $v)
                {   
                    //删除图片
                    unlink(ROOT_PATH . 'public' . DS . 'uploads' .'/'.$v);
                }
                //删除商品图标
                unlink(ROOT_PATH . 'public' . DS . 'uploads' .'/'.$goods['imageName']);
                //删除成功
                return 0;
            }
        }else{
            //返回删除失败
            return $goods->getError();
        }


    }

    //商品数量为0修改
    public function lineState()
    {

        $goods = goodss::all();

        foreach($goods as $v)
        {
            //商品详情
            $good = goodss::get($v['id']);

            $goods1 = $good->Goo;
            //商品数量 == 0
            if ($goods1['number'] == '0') {

                $good->state = '1';

                $good->save();

                return goodss::get($v['id']);
            }
        }

    }


    //状态
    public function line()
    {   
        //id
        $id = $_POST['id'];

        //查询
        $goods = goodss::get($id);

        //判断商品数量
        if ($goods->Goo['number'] == '0') {
            return '0';

        //判断修改
        }elseif ($goods['state'] == '1') {
            $goods->state = '2';
            $goods->save();
            //返回已修改
            return goodss::get($id);
        }elseif ($goods['state'] == '2') {
            $goods->state = '3';
            $goods->save();
            return goodss::get($id);
        }elseif ($goods['state'] == '3') {
            $goods->state = '1';
            $goods->save();
            return goodss::get($id);
        }

        

    }

    //搜索
    public function select()
    {
        //值
        $goodsNames = $_POST['goods-names'];
        $goodsSelect = $_POST['goods-select'];
        $goodsSort = $_POST['goods-sort'];

        //搜索
        //判断分类选择空
        if ($goodsSort == 's') {
            //进行判断状态选择空，去搜索商品名
            if ($goodsSelect == 0) {

                $goods = goodss::scope('Like','name','like','%'.$goodsNames.'%')->paginate(10);

                $this->assign('goods',$goods);

            //或进行商品名和状态搜索 
            }else{
                $goods = goodss::scope('Likes','name','like','%'.$goodsNames.'%','state','=',$goodsSelect)->paginate(10);

                $this->assign('goods',$goods);
            }

        //状态为空
        }elseif ($goodsSelect == 0) {

            //分类为空
            if ($goodsSort == 's') {

                //商品名
                $goods = goodss::scope('Like','name','like','%'.$goodsNames.'%')->paginate(10);

                $this->assign('goods',$goods); 
            //商品名加分类
            }else{
                $goods = goodss::scope('Likes','name','like','%'.$goodsNames.'%','uid','=',$goodsSort)->paginate(10);

                $this->assign('goods',$goods);
            }           

        //商品名加状态加分类搜索
        }else{
            $goods = goodss::scope('Likess','name','like','%'.$goodsNames.'%','state','=',$goodsSelect,'uid','=',$goodsSort)->paginate(10);
            $this->assign('goods',$goods);
        }

        //传已经选择好的值，用来显示上一次选择好的值
        $this->assign('goodsNames',$goodsNames);
        // $this->assign('goodsSelect',$goodsSelect);

        //登陆
        $name = Session::get('admin');

        $this->assign('name',$name);

        //分类
        $sort = new Sorts();

        $sorts = $sort->selects();

        $this->assign('sort',$sorts);

        //分页
        $page = $goods->render();

        $this->assign('page',$page);
        //定义
        $data = ['1'=>'不上架','2'=>'上架','3'=>'下架'];  

        $this->assign('data',$data);
        //视图
        return $this->fetch('Admin/goods-select');
    }


}
