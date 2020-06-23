<?php

namespace app\admin\model;

use think\Model;

class Orderbi extends Model
{
    //
    public function Orderbi()
    {
    	return $this->hasMany('Detailsorderbi','pid','id');
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
