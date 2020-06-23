<?php
namespace app\admin\validate;
use think\Validate;

class Sort extends Validate
{
	protected $rule = [
		['SortInsert','require|max:10','不能为空|不能超过10个字符'],
	];
}