<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Date: 2018-01-31
 * 预售控制器
 */

namespace app\admin\controller;

use app\common\model\Order;
use think\Loader;
use think\Db;
use app\common\model\PreSell as PreSellModel;
use think\Page;

class PreSell extends Base
{
	public function index()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 预售详情
	 * @return mixed
	 */
	public function info()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 保存
	 */
	public function save()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 删除
	 */
	public function delete(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	public function succeed()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	public function fail()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	public function finish()
	{
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}
}
