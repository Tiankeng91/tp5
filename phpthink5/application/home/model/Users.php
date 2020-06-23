<?php

namespace app\home\model;

use think\Model;

class Users extends Model
{
    //
    public function address()
    {
    	return $this->hasMany('Address','pid','id');
    }
}
