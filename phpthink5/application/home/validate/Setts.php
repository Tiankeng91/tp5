<?php
namespace app\home\validate;

use think\Validate;

class Setts Validate
{
    protected $rule = [
        ['pwd','require|alphaNum|max:6','付款码不能为空|付款码必须是数字与字母|付款码不能超过6位']
    ];

}