<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
  
// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;
//前台------------------------------
Route::get('/','home/Index/index');									//首页
Route::post('/home/index','home/Index/login');						//首页用户名显示
Route::get('/home/index/exit','home/Index/exit');					//首页用户名退出

Route::post('/home/select','home/Select/index');					//商品搜索
Route::get('/home/select','home/Select/select');					//商品搜索2
Route::get('/home/select2','home/Select/select2');					//商品搜索3

Route::get('/home/good','home/Good/index');							//商品
Route::post('/home/atonce','home/Good/atonce');						//立即购买
Route::post('/home/carts','home/Good/carts');						//加入购物车
Route::get('/home/carts/index','home/Good/MyCart');					//购物车页面
Route::post('/home/carts/puls','home/Good/puls');					//购物车页面商品数量加
Route::post('/home/carts/Delete','home/Good/Delete');				//购物车页面商品删除

Route::get('/home/login','home/Login/index');						//前端登陆页面
Route::post('/home/login/user','home/Login/login');					//前端登陆
Route::post('/home/login/code','home/Login/codes');					//前端登陆验证码刷新

Route::get('/home/login/post','home/Login/post');					//前端注册页面
Route::post('/hoem/login/post/add','home/Login/add');				//前端注册
Route::post('/hoem/login/post/code','home/Login/code');				//前端注册验证码刷新

Route::get('/home/sett','home/Sett/index');							//结算页面
Route::post('/home/sett/address','home/Sett/address');				//结算地址添加
Route::post('/home/sett/addressx','home/Sett/addressx');			//结算地址默认选择
Route::post('/home/sett/delet','home/Sett/delet');					//结算地址删除
Route::post('/home/sett/defaults','home/Sett/defaults');			//结算地址默认地址修改
Route::post('/home/sett/sett','home/Sett/sett');					//结算
Route::post('/home/sett/sett/payment','home/Sett/payment');			//支付码注册


Route::get('/home/center','home/Center/index');						//个人中心页面
Route::get('/home/center/order','home/Center/order');				//个人中心订单详情
Route::post('/home/center/state','home/Center/state');				//个人中心订单收货
Route::get('/home/center/center','home/Center/center');				//个人中心
Route::post('/home/center/name','home/Center/name');				//个人中心名字修改
Route::post('/home/center/pwd','home/Center/pwd');					//个人中心密码修改
Route::post('/home/center/payment','home/Center/payment');			//支付码注册与修改
Route::post('/home/center/price','home/Center/price');				//充值
//后台-------------------------------
Route::get('/admin','admin/Index/index');
Route::post('/admin/logn','admin/Login/index');  					//登陆
Route::post('/admin/sign','admin/login/sign');						//登陆操作

Route::get('/admin/user','admin/User/index');						//用户		
Route::get('/admin/user-insert','admin/Userinsert/index');			//用户添加显示
Route::post('/admin/user-insert/insert','admin/Userinsert/insert');	//用户添加
Route::post('/admin/delete','admin/User/delete');					//用户删除
Route::post('/admin/edit','admin/User/edit');						//用户修改
Route::post('/admin/line','admin/User/line');						//修改用户状态
Route::get('/admin/select','admin/User/select');					//用户搜索
Route::post('/admin/address','admin/User/address');					//用户地址

Route::get('/admin/sort-insert','admin/Sortinsert/index');				//添加顶级分类
Route::post('/admin/sort-insert-insert','admin/Sortinsert/insert');		//顶级分类添加
Route::get('/admin/sort','admin/Sort/index');							//分类
Route::post('/admin/sort-data','admin/Sortinsert/data');				//子分类添加
Route::get('/admin/sorts','admin/Sort/aa');								//分类分页
Route::get('/admin/sort/top','admin/Sort/top');							//分类第一页
Route::get('/admin/sort/bottom','admin/Sort/bottom');					//分页最一页
// Route::get('/admin/sortss','admin/Sort/search');						//分类搜索
Route::post('/admin/deletes','admin/Sort/deletes');						//分类删除
Route::post('/admin/sort/edit','admin/Sort/edit');						//分类修改

Route::get('/admin/goods','admin/Goods/index');							//商品
Route::get('/admin/goods-insert','admin/Goodsinsert/index');			//商品添加
Route::post('/admin/goods/insert','admin/Goodsinsert/insert');			//添加商品
Route::post('/admin/goods/details','admin/Goods/details');				//商品详情表
Route::post('/admin/goods/edits','admin/Goods/edits');					//商品修改准备
Route::post('/admin/goods/edit','admin/Goods/edit');					//商品修改开始
Route::post('/admin/goods/edit/state','admin/Goods/editState');			//商品修改完成显示商品状态
Route::post('/admin/goods/deletes','admin/Goods/delete');				//商品删除
Route::post('/admin/goods/line','admin/Goods/line');					//商品状态效果
Route::post('/admin/goods/line/state','admin/Goods/lineState');			//商品状态显示
Route::post('/admin/goods/select','admin/Goods/select');				//商品搜索

// Route::get('/admin/order','admin/Order/index');							//购物车
// Route::post('/admin/order/details','admin/Order/details');				//购物车详情表
// Route::post('/admin/order/edit','admin/Order/edit');					//购物车地址修改
// Route::post('/admin/order/line','admin/Order/line');					//购物车状态修改
// Route::post('/admin/order/select','admin/Order/select');				//购物车查找

Route::get('/admin/orders','admin/Orders/index'); 						//真。订单
Route::post('/admin/orders','admin/Orders/details'); 					//真。详情
Route::post('/admin/orders/line','admin/Orders/line'); 					//真。订单修改
Route::post('/admin/orders/select','admin/Orders/select');				//订单查找
Route::post('/admin/orders/deletes','admin/Orders/deletes');			//订单删除

Route::get('/admin/chart','admin/Chart/index');							//轮播图
Route::get('/admin/chart/add','admin/Chart/add');						//轮播图添加
Route::post('/admin/chart/insert','admin/Chart/insert');				//轮播图确定添加
Route::post('/admin/chart/deletes','admin/Chart/deletes');				//轮播图删除
