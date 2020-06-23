<?php

namespace app\home\model;

use think\Model;

class Detailsorder extends Model
{
   	public function Or()
   	{
   		return $this->hasOne('Orders','id','uid');
   	}

   	public function Orders()
   	{
   		return $this->hasMany('Orders','id','uid');
   	}

   	protected function scopeWhere($query,$name,$name1,$name2)
   	{
   		$query->where($name,$name1,$name2);
   	}
}
