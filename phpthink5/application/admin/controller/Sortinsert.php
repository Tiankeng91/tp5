<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;  //登陆需要
use app\admin\model\Sorts;   //数据库
use think\validata; //  验证模块
class Sortinsert extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

        $name = session::get('admin');
        //是否登陆
        if (empty($name) == true) {
            return $this->fetch('Admin/login');
        }

        $this->assign('name',$name);

        $sort = new Sorts();

        $data = $sort->selects();

        $this->assign('sort',$data);

        return $this->fetch('Admin/sort-insert');
    }
    
    //添加分类
    public function insert()
    {   
        //值 
        $SortInsert = $_POST['SortInsert'];

        // 准备验证的值
        $data = [
            'SortInsert' => $SortInsert,
        ];
        //去验证
        $SortInserts = $this->validate($data,'Sort');
        if (true !== $SortInserts) {
            //错误提示
            return $SortInserts;
        }else{
        //添加数据
        $sort = new Sorts([
            'name' => $SortInsert,
            'pid' => '0',
            'gra' => '1'
        ]);
        $sort->save();
        return '添加成功';
        }
    }

    //子分类添加
    public function data()
    {
        //值
        $name = $_POST['name'];
        $id = $_POST['id'];
        //验证值
        $data = [
            'SortInsert' => $name,
        ];
        //去验证
        $sorts = $this->validate($data,'Sort');
        //输出错误验证
        if (true !== $sorts) {
            return $sorts;
        }
        //搜索父类
        $sort = Sorts::get($id);
        //添加
        // $sor = new Sorts([
        //     'name' => $name,
        //     'pid' => $sort['id'],
        //     'gra' => $sort['gra'] + 1
        // ]);
        $sor = new Sorts;
        $sor->name = $name;
        $sor->pid = $sort['id'];
        $sor->gra = $sort['gra'] + 1;
        //输出
        $sor->save();

        return Sorts::get(['name'=>$name]);


    }
}
