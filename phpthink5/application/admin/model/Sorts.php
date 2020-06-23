<?php

namespace app\admin\model;

use think\Model;

class Sorts extends Model
{
    //查询
	public function selects()
	{
		// return '123';
		//查询所有数据
		$data = $this->select();
		//调用方法（数据）
		return $this->se($data);
	}

	//即将使用递归方法(数据，定义子类=0，等级=0)
	public function se($data,$pid=0,$gra=0)
	{
		//定义保存的静态数组
		static $arr = array();
		//for循环
		foreach ($data as $k => $v) {
			//当第一循环是pid==0 因为上面已经设置pid==0
			if ($v['pid'] == $pid) {
				//定义等级
				$v['gra'] = $gra;
				//保存数据
				$arr[] = $v;
				//使用递归方法(数据，父类，等级+1)
				$this->se($data,$v['id'],$gra+1);
			}
		}
		//输出内容返回
		return $arr;
	}

	public function aa()
	{
		return '123';
	}

	//一对一关联模型
	public function sor()
	{
		return $this->hasOne('Goods','uid','id');
	}

}
