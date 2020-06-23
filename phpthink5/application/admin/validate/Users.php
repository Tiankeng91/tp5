<?php
namespace app\admin\validate;

use think\Validate;

class Users extends Validate
{
    protected $rule = [
        ['name','require|max:10','用户名不能为空|用户名不能超过10个字符'],
        ['pwd','require|alphaNum|max:18','密码不能为空|密码必须是数字与字母|密码不能超过18位'],
    ];

}