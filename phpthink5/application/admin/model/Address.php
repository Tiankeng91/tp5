<?php

namespace app\admin\model;

use think\Model;

class Address extends Model
{
    protected function scopeThinkphp($query)
    {
        $query->order('id', 'desc');
    }
}
