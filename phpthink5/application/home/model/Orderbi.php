<?php

namespace app\home\model;

use think\Model;

class Orderbi extends Model
{
    public function Orderbi()
    {
    	return $this->hasMany('Detailsorderbi','pid','id');
    }
}
