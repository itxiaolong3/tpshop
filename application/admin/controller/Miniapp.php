<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 当燃
 * 拼团控制器
 * Date: 2016-06-09
 */

namespace app\admin\controller;

use app\common\logic\saas\MiniappLogic;
use app\common\logic\saas\wechat\MiniApp3rd;
use app\common\logic\saas\wechat\Wx3rdPlatform;
use app\common\model\saas\AppService;
use app\common\model\saas\Miniapp as MiniappModel;
use app\common\model\saas\MiniappTemplate;
use app\common\model\saas\UserMiniapp;
use think\Loader;
use think\Db;
use think\Page;

class Miniapp extends Base
{

	private $appService;
	private $saas;

	public function _initialize()
	{
		$saas_cfg = $GLOBALS['SAAS_CONFIG'];
		$service_id = $saas_cfg['service_id'];
		$AppService = new AppService();
//		$service_id = 5;
//		dump($service_id);
		$this->appService = $AppService->where('service_id', $service_id)->find();
		$this->saas = $GLOBALS['SAAS'];
	}

	public function index()
	{
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']]);
		$this->assign('miniapp', $miniapp);
		$this->assign('saas', $this->saas);
		$this->assign('app_service', $this->appService);
		return $this->fetch();
	}

	public function release_manage(){
		//, 'is_auth' => 1
//		halt($this->appService);
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']]);
		if (!$miniapp) {
			$this->error('小程序不存在', 'admin/Miniapp/index');
		}
		if($miniapp['is_auth'] != 1){
			$this->error('小程序未绑定', 'admin/Miniapp/index');
		}

		$logic = new MiniappLogic();
		$return = $logic->getVersionsInfo($miniapp);
		if ($return['status'] != 1) {
			$this->error($return['msg']);
		}

		$this->assign($return['result']);
		$this->assign('miniapp', $miniapp);
		return $this->fetch();
	}

	/**
	 * 选择模板页
	 */
	public function template()
	{
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']], 'appService');
		if (!$miniapp) {
			$this->error('小程序不存在', 'admin/Miniapp/index');
		}
		if($miniapp['is_auth'] != 1){
			$this->error('小程序未绑定', 'admin/Miniapp/index');
		}
		if (!$templates = MiniappTemplate::all(['is_on_sale'=> 1, 'app_id' => $this->appService->app_id])) {
			$this->error('暂无模板可使用，联系客服');
		}

		$this->assign('miniapp', $miniapp);
		$this->assign('templates', $templates);
		return $this->fetch();
	}

	/**
	 * 设置体验者页
	 */
	public function tester()
	{
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']]);
		if (!$miniapp) {
			$this->error('小程序不存在', 'admin/Miniapp/index');
		}
		if($miniapp['is_auth'] != 1){
			$this->error('小程序未绑定', 'admin/Miniapp/index');
		}

		$this->assign('miniapp', $miniapp);
		return $this->fetch();
	}

	/**
	 * 设置小程序是否可见（可访问）
	 */
	public function set_visit_status()
	{
		$status = input('status');
		$logic = new MiniappLogic;
		$return = $logic->setVisitStatus($this->appService['miniapp_id'], $this->appService['user_id'], $status);
		$this->ajaxReturn($return);
	}
	/**
	 * 获取体验二维码图片
	 */
	public function test_qrcode()
	{
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']]);
		if (!$miniapp) {
			exit('小程序尚不存在');
		}
		if($miniapp['is_auth'] != 1){
			exit('小程序未绑定');
		}
		$miniApp3rd = new MiniApp3rd($miniapp);
		$content = $miniApp3rd->getTestQrcode();
		if ($content === false) {
			exit($miniApp3rd->getError());
		}
		header('Content-type: image/jpeg');
		exit($content);
	}
	/**
	 * 提交审核页
	 */
	public function audit()
	{
		$miniapp = MiniappModel::get(['user_id' => $this->appService['user_id'], 'miniapp_id' => $this->appService['miniapp_id']]);
		if (!$miniapp) {
			$this->error('小程序不存在', 'admin/Miniapp/index');
		}
		if($miniapp['is_auth'] != 1){
			$this->error('小程序未绑定', 'admin/Miniapp/index');
		}

		if (!UserMiniapp::get(['user_id' => $this->appService['user_id'], 'status' => UserMiniapp::STATUS_TEST])) {
			$this->error('体验版本不存在，不能提交审核');
		}

		$miniApp3rd = new MiniApp3rd($miniapp);
		$categories = $miniApp3rd->getCategory();
		if ($categories === false) {
			$this->error($miniApp3rd->getError());
		}

		//该服务分类不能控制，只能每次拉取的时候更新
		$miniapp->save(['categories' => $categories]);

		//废弃审核失败的
		if ($userMiniapp = UserMiniapp::get(['user_id' => $this->appService['user_id'], 'status' => UserMiniapp::STATUS_AUDIT_FAIL])) {
			$userMiniapp->save(['status' => UserMiniapp::STATUS_ABANDON]);//废弃
		}

		$this->assign('categories', $categories);
		return $this->fetch('audit1');
	}
	/**
	 * 发布小程序
	 */
	public function release_miniapp()
	{
		$logic = new MiniappLogic;
		$return = $logic->releaseMiniapp($this->appService['miniapp_id'], $this->appService['user_id']);
		$this->ajaxReturn($return);
	}
	/**
	 * 提交小程序模板
	 */
	public function commit_template()
	{
		$data = input('post.');
		$return = (new MiniappLogic)->commitMiniapp($this->appService['miniapp_id'], $this->appService['user_id'], $data);
		$this->ajaxReturn($return);
	}
	public function set_tester()
	{
		$operate = input('tester_op');
		$wechatId = input('wechat_id');

		$return = (new MiniappLogic)->bindTester($this->appService['miniapp_id'], $this->appService['user_id'], $wechatId, $operate);
		$this->ajaxReturn($return);
	}

	/**
	 * 获取授权链接
	 */
	public function auth_url()
	{
		$wx3rd = Wx3rdPlatform::getInstance();
		$auth_url = $wx3rd->getAuthUrl();
		if ($auth_url === false) {
			$this->ajaxReturn(['status' => -1, 'msg' => $wx3rd->getError()]);
		}
		$this->ajaxReturn(['status' => 1, 'msg' => '获取成功', 'result' => $auth_url]);
	}
	/**
	 * 提交审核
	 */
	public function submit_audit()
	{
		$data = input('post.');
		$categories = explode(',', $data['categories']);
		$data['first_id'] = $categories[0];
		$data['second_id'] = isset($categories[1]) ? $categories[1] : '';
		$data['third_id'] = isset($categories[2]) ? $categories[2] : '';
		unset($data['categories']);

		$return = (new MiniappLogic)->submitAudit($this->appService['miniapp_id'], $this->appService['user_id'], $data);
		$this->ajaxReturn($return);
	}

}
