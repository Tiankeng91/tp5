<?php

namespace app\admin\model;

use think\Model;

class Goods extends Model
{
    //一对一模型
    public function Goo()
    {
    	return $this->hasOne('Detailsgoods','pid','id');
    }

	//搜索商品名
    protected function scopeLike($query,$name1,$name2,$name3)
    {
        $query->where($name1,$name2,$name3);
    }
    //搜索商品名+状态
    protected function scopeLikes($query,$name1,$name2,$name3,$name4,$name5,$name6)
    {
        $query->where($name1,$name2,$name3)->where($name4,$name5,$name6);
    }

    protected function scopeLikess($query,$name1,$name2,$name3,$name4,$name5,$name6,$name7,$name8,$name9)
    {
        $query->where($name1,$name2,$name3)->where($name4,$name5,$name6)->where($name7,$name8,$name9);
    }

    public function sort()
    {
        return $this->hasOne('sorts','id','uid');
    }
}
