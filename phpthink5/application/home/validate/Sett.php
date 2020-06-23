<?php
namespace app\home\validate;

use think\Validate;

class Sett extends Validate
{
    protected $rule = [
        ['name','require|max:10','收货人名不能为空|收货人名不能超过10个字符'],
        ['region','require|max:4','所在地址区不能为空|所在地址区名不能超过4个字符(如:广州)'],
        ['address','require|max:20','详细地址不能为空|详细地址不能超过20个字符'],
        ['phone','require|alphaNum|length:11','手机号不能为空|手机号必须是数字|手机号必须为11个字符'],
    ];

}