<?php

namespace app\home\model;

use think\Model;

class Goods extends Model
{
    //
    public function Goo()
    {
    	return $this->hasOne('Detailsgoods','pid','id');
    }

    public function good()
    {
    	return $this->hasMany('Detailsgoods','pid','id');
    }

    protected function scopeLike($query,$name,$name1,$name2)
    {
    	$query->where($name,$name1,$name2)->where('state','=','2');
    }
}
