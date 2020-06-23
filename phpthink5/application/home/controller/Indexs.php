<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Sorts; 
use app\home\model\Goods as goods;
use app\home\model\Detailsgoods;
class Index extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {	
    	//查询父类。 成1级分类
		$sorts = Sorts::scope('Where','pid','=','0')->select();

		//遍历
		foreach($sorts as $k => $v)
		{	
			//查询子类
			$sorts2 = Sorts::scope('Where','pid','=',$v['id'])->select();

			//组装到1级分类。 成2级分类
			$sorts[$k]['child'] = $sorts2;

			foreach($sorts2 as $k => $v)
			{	
				//查询子类
				$sorts3 = Sorts::scope('Where','pid','=',$v['id'])->select();
				//组装到2级。 成3级分类
				$sorts2[$k]['child2'] = $sorts3;	
			}
		}

		//分类传输
		$this->assign('sort',$sorts);



		//随机商品准备--------------------
		//查询所有
		$ar = goods::all();
		//定义数组
		static $arr = array();
		//遍历
		foreach($ar as $v)
		{	
			if ($v['state'] == 2) {
				//把id传入数组
				$arr[] = $v['id']; 
			}else{
				$arr[] = '';
			}
		}
		//随机数1⃣组
		$a = array_rand($arr,3);

		//随机一组遍历
		foreach($a as $k=>$v)
		{	
			//使用随机数获取数组的值
			$array[] = $arr[$v];
		}

		//搜索用随机数
		$good = goods::all($array);
		//遍历
		foreach($good as $k=>$v)
		{
			//二维数组，用来显示详细信息
			$good[$k]['goods'] = Detailsgoods::all(['pid' => $v['id']]);
		}

		//随机商品数组
		$this->assign('good',$good);


		//商品显示----------------------

		//分类
		$goods = Sorts::all();

		//获取分类id
		foreach($goods as $v)
		{
			$goodsArr[] = $v['id'];
		}

		//随机数
		$goodSort = array_rand($goodsArr,5);

		//获取随机分类id
		foreach($goodSort as $k => $v)
		{
			$goodSort2[] = $goodsArr[$v];
		}
		//搜索随机分类
		$goods2 = Sorts::all($goodSort2);
		
		//判断分类存在		
		foreach($goods2 as $v)
		{	
			//分类下的商品
			$goods3 = goods::where('uid',$v['id'])->select();

			//判断有没有商品
			if ($goods3 != null) {
				//有商品
				foreach($goods3 as $v)
				{
					//判断是否上架
					if ($v['state'] == 2) {
						//上架商品的分类id
						$goods4[] = $v['uid'];
					}else{
						//没有商品
						$goods4 = [];
					}
				}
			}
		}

		//判断获取的分类id是否空
		if (empty($goods4) != true) {
			//搜索分类
			$goo = Sorts::all($goods4);

			foreach($goo as $k => $v)
			{	
				//
				$go = Sorts::get($v['id']);

				//商品详情
				$goo2 = $go->sort;

				//二维数组
				$goo[$k]['sort'] = $goo2;
				//
				foreach($goo2 as $k => $v)
				{
					//三维数组
					$goo2[$k]['sort2'] = Detailsgoods::all(['pid' => $v['id']]);
				}
			}	

		//为空
		}else{
			//判断所以分了
			foreach($goods as $v)
			{
				$goods5 = goods::where('uid',$v['id'])->select();

				if ($goods5 != null) {

					foreach($goods5 as $v)
					{	

						if ($v['state'] == 2) {
							
							$goods6[] = $v['uid'];
						}else{
							$goods6[] = '';
						}
					}
				}
			}


			$goo3 = Sorts::all($goods6);

			foreach($goo3 as $k => $v)
			{	

				$go2 = Sorts::get($v['id']);

				$goo4 = $go2->sort;

				$goo3[$k]['sort'] = $goo4;

				foreach($goo4 as $k => $v)
				{
					$goo4[$k]['sort2'] = Detailsgoods::all(['pid'=> $v['id']]);
				}
			}

			$this->assign('sorts',$goo3);
		}


		//轮播图 chart

		return $this->fetch('admin/index');

    }


    //搜索功能
    public function select()
    {
		//商品搜索-----------------------------
		$name = $_POST['name'];

		$goods = goods::scope('Like','name','like','%'.$name.'%')->select();

		foreach($goods as $k=>$v)
		{	
			$goods[$k]['goods'] = Detailsgoods::all(['pid'=>$v['id']]);
		}
		$this->assign('goods',$goods);

    	//获取分类方法
    	$sorts = $this->sortRand();

    	$this->assign('sort',$sorts);

    	//获取随机方法
    	$goods = $this->goodRand();

    	$this->assign('sorta',$goods);

    	return $this->fetch('admin/select');

    }


    //商品搜索2
    public function selects()
    {	
    	//传的id
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

    	//获取分类方法
    	$sorts = $this->sortRand();

    	$this->assign('sort',$sorts);

    	//获取随机方法
    	$goods = $this->goodRand();

    	$this->assign('sorta',$goods);


    	return $this->fetch('admin/select');
    }

    //商品搜索2
    public function selects1()
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

    	//获取分类方法
    	$sorts = $this->sortRand();

    	$this->assign('sort',$sorts);

    	//获取随机方法
    	$goods = $this->goodRand();

    	$this->assign('sorta',$goods);

    	return $this->fetch('admin/select');
    }


    //商品搜索3
    public function selects2()
    {	
    	//id
    	$id = $_GET['id'];

    	//分类
    	$goods = Sorts::all($id);

    	//判断商品
    	foreach($goods as $v)
    	{
    		//商品
    		$data = goods::where('uid',$v['id'])->select();

    		//商品是否空
    		if ($data != null) {
    			//
    			foreach($data as $v)
    			{
    				//上架
    				if ($v['state'] == 2) {
    					$arr[] = $v['id'];
    				}
    			}
    		}else{
    			$arr[] = '';
    		}
    	}

    	//商品
    	$good = goods::all($arr);

    	//二维数组
    	foreach($good as $k => $v)
    	{
    		$good[$k]['goods'] = Detailsgoods::all(['pid'=>$v['id']]);
    	}

    	//商品
    	$this->assign('goods',$good);

    	//获取分类方法
    	$sorts = $this->sortRand();

    	$this->assign('sort',$sorts);

    	//获取随机方法
    	$goods = $this->goodRand();

    	$this->assign('sorta',$goods);

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


    //搜索分类
    public function sortRand()
    {
		$sorts = Sorts::scope('Where','pid','=','0')->select();

		//遍历
		foreach($sorts as $k => $v)
		{	
			//查询子类
			$sorts2 = Sorts::scope('Where','pid','=',$v['id'])->select();

			//组装到1级分类。 成2级分类
			$sorts[$k]['child'] = $sorts2;

			foreach($sorts2 as $k => $v)
			{	
				//查询子类
				$sorts3 = Sorts::scope('Where','pid','=',$v['id'])->select();
				//组装到2级。 成3级分类
				$sorts2[$k]['child2'] = $sorts3;	
			}
		}

		return $sorts;

    }

    //随机商品
    public function goodRand()
    {
 		$sort = Sorts::all();

		//遍历
		foreach($sort as $v)
		{
			$so = Sorts::get($v['id']);
			//判断底下是否有商品
			if (is_null($so->sor) != true) {
				$sor[] = $v['id'];
			}else{
				$sor[] = '';
			}
		}
		//顺机分类
		foreach(array_rand($sor,3) as $k=>$v)
		{
			$s[] = $sor[$v];
		}
		//查找
		$sorta = Sorts::all($s);   	

		return $sorta;
    }
}
