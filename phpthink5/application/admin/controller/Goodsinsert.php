<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\admin\model\Sorts;
use app\admin\model\Goods;
use think\Validate;
use think\Image;
class Goodsinsert extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        $this->assign('name',$name);
        //分类
        $sort = new Sorts();

        $data = $sort->selects();

        $this->assign('sort',$data);

        return $this->fetch('Admin/goods-insert');
    }
    //添加准备商品
    public function insert()
    {
        //商品名称
        $name = request()->post('file1');
        //商品属于类型
        $select = request()->post('file3');
        //商品图标
        $file = request()->file('file2');

        //商品价格
        $price = request()->post('price');

        //商品介绍
        $names = request()->post('names');

        //商品数量
        $number = request()->post('number');

        //商品多图片
        $files = request()->file('image');

        // $files = request()->file('file_input');

        //验证
        $data = [
            'name'=>$name,
            'file2'=>$file,
            'file3'=>$select,
            'price'=>$price,
            'names' => $names,
            'number' => $number,
            'files4'=>$files,
        ]; 

        $datas = $this->validate($data,'Goods');

        // return $files;
        //返回错误信息
        if(true !== $datas){
            return $datas;
        }elseif(count($files) >= 6){
            return '图片数量不能超过5个';
        }else{
            return $this->add($name,$select,$file,$price,$names,$number,$files);
            
            //判断商品添加的分类是否也没有子类
            //查询此时分类信息
            // $selects = Sorts::get($select);
            // //判断是否是顶级分类
            // if ($selects['pid'] == '0') {
            //     //查询有没有子类
            //     $datas = Sorts::where('pid',$select)->find();
            //    //判断是否是顶级
            //     if (is_null($datas) != true) {
            //         return '商品为顶级，请在子类添加商品';
            //     }
            //     //不是可以添加商品
            //     return $this->add($name,$select,$file,$price,$names,$number,$files);
            // }else{
            //     //判断自己有没有子类
            //     $datas = Sorts::where('pid',$select)->find();
            //     //判断有子类去子类添加
            //     if (is_null($datas) != true) {
            //         return '商品有子类，请在子类添加商品';
            //     }
            //     //没有子类，在自己的类下添加
            //     return $this->add($name,$select,$file,$price,$names,$number,$files);       
            // } 

        }

    }
    //确定添加方法（商品名称，商品分类，图片，价格，介绍，数量，多图片）
    public function add($name,$select,$file,$price,$names,$number,$files)
    {
            //图片上传
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            //定义空数组
            static $itm = array();
            //多图片上传
            foreach($files as $v)
            {

                $filesValidate = $this->validate(['files'  => $v],['files'  => 'image'],['files.image'=>'商品图片必须是图片']);
                    if (true !== $filesValidate) {
                        return $filesValidate;
                    }else{
                    $k = $v->move(ROOT_PATH . 'public' . DS . 'uploads');

                    if ($k) {
                        $itm[] = $k->getSaveName();
                    }
                }
            }
            //数组转字符串
            $str = implode(',/',$itm);

            //判断
            if ($info) {
                //成功，图片名
                $image = $info->getSaveName();
                //添加数据库
                $goods = new Goods([
                    'name' => $name,
                    'imageName' => $image,
                    'uid' => $select,
                    'date' => date("Y/m/d,H/i/s"),
                    'dates' => date("Y/m/d,H/i/s")
                ]);
                //确定添加进行一对一关联添加
                if ($goods->save()) {
                    $Goo['names'] = $names;
                    $Goo['image'] = $str;
                    $Goo['price'] = $price;
                    $Goo['number'] = $number;
                    $goods->Goo()->save($Goo);
                    return 1;
                }


            }else{
                //返回错误信息
                return $file->getError();
            }

    }


}
