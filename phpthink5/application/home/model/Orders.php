<?php

namespace app\home\model;

use think\Model;

class Orders extends Model
{
   	public function Or()
   	{
   		return $this->hasOne('Detailsorder','uid','id');
   	}
}
