<?php

namespace app\home\model;

use think\Model;

class Address extends Model
{
    //地址，排序
     protected function scopeAdds($query,$id)
     {
     	$query->where('pid',$id)->order('state', 'desc');
     }
}
