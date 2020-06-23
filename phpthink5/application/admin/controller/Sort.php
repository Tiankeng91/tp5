<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;  
use think\validata; //  验证模块
use app\admin\model\Sorts;      //模型数据库
class Sort extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //用户登陆
        $name = Session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }
        //传输用户登陆
        $this->assign('name',$name);

        //new模型
        $sort = new Sorts;
        //模型方法
        $data = $sort->selects();
        //显示10行
        $this->assign('sort',array_slice($data,0,10));
        //定义一个静态数组
        static $ar = array();
        //获取分页数。  向上除整(统计数量(数组))除于显示页数
        $a = ceil(count($data) / 10);
        //循环赋值给静态数组，这里让字数分成数组
        for ($i=1; $i <=$a ; $i++) { 
            $ar[] = $i;
        }
        //分页5个
        $this->assign('ar',array_slice($ar,0,5));
        //视图
        return $this->fetch('Admin/sort');
    }

    //分页
    public function aa()
    {   
        //用户登陆
        $name = Session::get('admin');
        //传输用户登陆
        $this->assign('name',$name);

        //值
        $id = $_GET['id'] - 1;
        
        $sort = new Sorts;
        //方法
        $data = $sort->selects();
        //判断点击第二页输出
        if ($id != 0) {
            //跳过的数值
            $i = $id * 10;
            //输出跳过10个，显示10行
            $this->assign('sort',array_slice($data,$i,10));
        }else{
            //显示第一页
            $this->assign('sort',array_slice($data,$id,10));
        }
        //数组
        static $ar = array();
        //数量
        $a = ceil(count($data) / 10);
        //数页
        for ($i=1; $i <=$a ; $i++) { 
            $ar[] = $i;
        }
        //判断
        if ($id != 0) {
            //不是点击第一页显示后面页数
            $i = $id - 1;
            //比如点击3，输出跳过2，显示5个
            $this->assign('ar',array_slice($ar,$i,5));
        }else{
            //正常显示5个分页
            $this->assign('ar',array_slice($ar,0,5));
        }   
        //视图
        return $this->fetch('Admin/sorts');
    }

    //第一页
    public function top()
    {
        //用户登陆
        $name = Session::get('admin');
        //传输用户登陆
        $this->assign('name',$name);

        // return '123';
        $sort = new Sorts;
        //模型方法
        $data = $sort->selects();

        //显示行数
        $this->assign('sort',array_slice($data,0,10));

        //数组
        static $ar = array();
        //数量
        $a = ceil(count($data) / 10);
        //赋值数组
        for ($i=1; $i <=$a ; $i++) { 
            $ar[] = $i;
        }
        //行数
        $this->assign('ar',array_slice($ar,0,5));

        //视图
        return $this->fetch('Admin/sorts');

    }

    //最后一页
    public function bottom()
    {
        //用户登陆
        $name = Session::get('admin');
        //传输用户登陆
        $this->assign('name',$name);

        $sort = new Sorts;
        //模型方法
        $data = $sort->selects();

        //获取最后页
        $aa = ceil(count($data) / 10 - 1);
        $i = $aa * 10;

        //显示行数
        $this->assign('sort',array_slice($data,$i,10));

        //数组
        static $ar = array();
        //数量
        $a = ceil(count($data) / 10);
        //赋值数组
        for ($i=1; $i <=$a ; $i++) { 
            $ar[] = $i;
        }
        //行数
        $this->assign('ar',array_slice($ar,0,5));

        //视图
        return $this->fetch('Admin/sorts');
    }

    // 分类搜索 
    public function search()
    {
        // return '123';
    }

    //分类删除
    public function deletes()
    {   
        //值
        $id = $_POST['id'];
        //查询当前数值
        $data = Sorts::get($id);

        // return $data;
        // return $data->sor;

        if (is_null($data->sor) != true) {
            return 2;
         //判断，顶级分类    
        }elseif ($data['pid']=='0') {
            //查看子类
            $datas = Sorts::where('pid',$id)->find();
            //判断有子类
            if (is_null($datas) != true) {
                return 0;
            }
            //没有去进行删除
            return $data->delete();
        }else{
            //查看子类的子类
            $datas = Sorts::where('pid',$data['id'])->find();
            //判断有子类
            if (is_null($datas) != true) {
                return 0;
            }
            //删除
            return $data->delete();            
        }

       
 


    }
    //分类修改
    public function edit()
    {   
        //id
        $id = $_POST['id'];
        //修改的值
        $name = $_POST['name'];
        //准备验证
        $data = [   
            'SortInsert' => $name,
        ];
        //验证
        $SortInserts = $this->validate($data,'Sort');
        if (true !== $SortInserts) {
            //错误提示
            return $SortInserts;
        }else{
            //修改
            $user = Sorts::get($id);
            $user->name = $name;
            $user->save();
            // return $user;
            //返回修改好
            return Sorts::get($id);
            //返回等级视图
            // if ($user['gra'] !=0) {
            //     $a =  str_repeat('-',$user['gra']-1);
            //     return '|'.' '.$a.' ';    
            // }
        }

    }
}
