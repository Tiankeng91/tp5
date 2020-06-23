<?php
namespace app\admin\validate;
use think\Validate;

class Goods extends Validate
{
	protected $rule = [
		['name','require|max:15','商品名称不能为空|不能超过15个字符'],
		['file2','require|image','商品图片不能为空|商品图片必须是图片'],
		['file3','require','商品类型不能为空'],
		['price','require|number|max:8','价钱不能为空|价格必须是数字|价钱不能超过8个字符'],
		['names','require|max:30','介绍不能为空|不能超过30个字符'],
		['number','require|max:4','商品数量不能为空|商品数量不能超过4位数'],
		['files4','require','商品详情图片不能为空'],
	];
}