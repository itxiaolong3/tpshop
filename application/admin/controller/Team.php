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

use app\admin\logic\OrderLogic;
use app\common\model\Order;
use app\common\model\TeamActivity;
use app\common\model\TeamFollow;
use app\common\model\TeamFound;
use app\common\logic\MessageFactory;
use think\AjaxPage;
use think\Loader;
use think\Db;
use think\Page;

class Team extends Base
{
	public function index()
	{
        $team_activityModel=Db::name('team_activity');
        $count=$team_activityModel->count();
        $Page = new AjaxPage($count, 10);
        $List = $team_activityModel->where('deleted',0)->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show();
        foreach ($List as $k=>$v){
            if ($v['team_type']==0){
                $List[$k]['team_type_desc']='分享团';
            }else if($v['team_type']==1){
                $List[$k]['team_type_desc']='佣金团';
            }else if ($v['team_type']==2){
                $List[$k]['team_type_desc']='抽奖团';
            }
            $List[$k]['team_type']=$v['team_type'];
        }
        $this->assign('list', $List);
        $this->assign('page', $show);// 赋值分页输出
        $this->assign('pager', $Page);
        return $this->fetch();
	}

	/**
	 * 拼团详情
	 * @return mixed
	 */
	public function info()
	{
	    $getid=I('GET.team_id',0);
        $info = array();
        if($getid){
            $info = M('team_activity')->where('team_id='.$getid)->find();
            $this->assign('teamActivity',$info);
        }

        return $this->fetch();
	}

	/**
	 * 保存
	 * @throws \think\Exception
	 */
	public function save(){
        $data = I('post.');
        $team_activityValidate = Loader::validate('team');
        if (empty($data['team_id'])) {
            if (!$team_activityValidate->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '添加失败', 'result' => $team_activityValidate->getError()];
            } else {
                $r = D('team_activity')->add($data);
                if ($r !== false) {
                    $return = ['status' => 1, 'msg' => '添加成功', 'result' => $team_activityValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '添加失败，数据库未响应', 'result' => ''];
                }
            }
        }else{
            if (!$team_activityValidate->scene('edit')->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '编辑失败', 'result' => $team_activityValidate->getError()];
            } else {
                $r = D('team_activity')->where('team_id=' . $data['team_id'])->save($data);
                if ($r !== false) {
                    $return = ['status' => 1, 'msg' => '编辑成功', 'result' => $team_activityValidate->getError()];
                } else {
                    $return = ['status' => 0, 'msg' => '编辑失败，数据库未响应', 'result' => ''];
                }
            }
        }
        $this->ajaxReturn($return);
	}

	/**
	 * 删除拼团
	 */
	public function delete(){
        $getid=I('GET.team_id',0);
        if (empty($getid)){
            $return = ['status' => 0, 'msg' => '请选择删除记录', 'result' => ''];
        }else{
            $delre=D('team_activity')->where('team_id',$getid)->save(array('deleted'=>1));
            if ($delre){
                $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
            }else{
                $return = ['status' => 0, 'msg' => '删除失败', 'result' => ''];
            }
        }
        $this->ajaxReturn($return);
	}

	/**
	 * 确认拼团
	 * @throws \think\Exception
	 */
	public function confirmFound(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 拼团退款
	 */
	public function refundFound(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 拼团抽奖
	 */
	public function lottery(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 拼团订单
	 */
	public function team_list()
	{
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 拼团订单详情
	 * @return mixed
	 */
	public function team_info()
	{
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	//拼团订单
	public function order_list(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	/**
	 * 团长佣金
	 */
	public function bonus(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	public function doBonus(){
	header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}
}
