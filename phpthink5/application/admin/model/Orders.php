<?php

namespace app\admin\model;

use think\Model;

class Orders extends Model
{
    //
   	public function order()
   	{
   		return $this->hasOne('Detailsorder','uid','id');
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
}
