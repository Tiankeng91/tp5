<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\home\model\Sorts; 
use app\home\model\Goods as goods;
use app\home\model\Detailsgoods;
use app\home\model\Charts;

use think\Session;
class Index extends Controller
{

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

			foreach($sorts2 as $k=>$v)
			{	
				$sorts3 = Sorts::scope('Where','pid','=',$v['id'])->select();

				$sorts2[$k]['child2'] = $sorts3;
			}

		}

		//分类传输
		$this->assign('sort',$sorts);

		//商品显示----------------------
    	$goods = Sorts::all();

    	//判断有没有商品
    	foreach($goods as $v)
    	{
    		$goods2 = goods::where('uid',$v['id'])->select();

    		if ($goods2 != null) {
    			
				//有商品
				foreach($goods2 as $v)
				{
					//判断是否上架
					if ($v['state'] == 2) {
						//上架商品的分类id
						$goods3[] = $v['uid'];
					}else{
						//没有商品
						$goods3[] = '';
					}
				}    			

    		}else{
    			$goods3[] = '';
    		}
    	}
    	//分类
		$good = Sorts::all($goods3);

		foreach($good as $k => $v)
		{
			$go = Sorts::get($v['id']);
			//商品详情
			$good2 = $go->sort; 

			//二维数组
			$good[$k]['good'] = $good2;

			foreach($good2 as $k => $v)
			{	
				//三维。 商品详情
				$good2[$k]['good2'] = Detailsgoods::all(['pid' => $v['id']]);
			}		
		}   	
		$this->assign('good',$good);

		$Charts = Charts::all();
		$this->assign('chart',$Charts);
        return $this->fetch('admin/index');
    }

    //用户显示
    public function login()
    {
		$names =Session::get('names');

		if (is_null($names) == false) {
			return $names;
		}else{
			return '1';
		}    	
    }


    //用户退出
    public function exit()
    {
    	Session(null);

     	//查询父类。 成1级分类
		$sorts = Sorts::scope('Where','pid','=','0')->select();

		//遍历
		foreach($sorts as $k => $v)
		{	
			//查询子类
			$sorts2 = Sorts::scope('Where','pid','=',$v['id'])->select();

			//组装到1级分类。 成2级分类
			$sorts[$k]['child'] = $sorts2;
			
			foreach($sorts2 as $k=>$v)
			{	
				$sorts3 = Sorts::scope('Where','pid','=',$v['id'])->select();

				$sorts2[$k]['child2'] = $sorts3;
			}

		}

		//分类传输
		$this->assign('sort',$sorts);

		//商品显示----------------------
    	$goods = Sorts::all();

    	//判断有没有商品
    	foreach($goods as $v)
    	{
    		$goods2 = goods::where('uid',$v['id'])->select();

    		if ($goods2 != null) {
    			
				//有商品
				foreach($goods2 as $v)
				{
					//判断是否上架
					if ($v['state'] == 2) {
						//上架商品的分类id
						$goods3[] = $v['uid'];
					}else{
						//没有商品
						$goods3 = [];
					}
				}    			

    		}else{
    			$goods3[] = '';
    		}
    	}
    	//分类
		$good = Sorts::all($goods3);

		foreach($good as $k => $v)
		{
			$go = Sorts::get($v['id']);
			//商品详情
			$good2 = $go->sort; 

			//二维数组
			$good[$k]['good'] = $good2;

			foreach($good2 as $k => $v)
			{	
				//三维。 商品详情
				$good2[$k]['good2'] = Detailsgoods::all(['pid' => $v['id']]);
			}		
		}   	
		$this->assign('good',$good);

        return $this->fetch('admin/index');
    }
 
}
