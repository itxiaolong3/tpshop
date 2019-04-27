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

use app\common\logic\Integral;
use think\Loader;
use think\db;

class IntegralMall extends Base
{
    public function index()
    {
        $inc_type =  I('get.inc_type','integral');
        $conf_list = Db::name("config")->where("inc_type='{$inc_type}'")->select();
        foreach($conf_list as $key=>$val){
            $confArr[$val['name']] = $val['value'];
            if($val['name']=='expired_time'){
                $confArr[$val['name']] = explode(",",$val['value']);
            }
        }
        return $this->fetch("index",["confArr"=>$confArr]);
    }

    /*
	 * 修改积分相关配置
	 */
    public function handle()
    {
        $param = I('post.');
        $list[] = ['name'=>'is_integral_expired','value'=>$param['is_integral_expired'],'inc_type'=>"{$param['inc_type']}"];
        if($param['is_integral_expired'] == 2) {
            $expired_time = $param['month'].",".$param['day'];
            $list[] = ['name'=>'expired_time','value'=>$expired_time,'inc_type'=>"{$param['inc_type']}"];
        }
        $list[] = ['name' => 'is_use_integral', 'value' => $param['is_use_integral'], 'inc_type' => "{$param['inc_type']}"];
        $integralValidate = Loader::validate('integral');
        $returnConsumeIntegral =  $integralValidate->checkIntegral($param['consume_integral'],'',$param,'consume_integral');
        $returnRegIntegral =  $integralValidate->checkIntegral($param['reg_integral'],'',$param,'reg_integral');
        $returnInviteIntegral =  $integralValidate->checkIntegral($param['invite_integral'],'',$param,'invite_integral');
        $returnInviteeIntegral =  $integralValidate->checkIntegral($param['invitee_integral'],'',$param,'invitee_integral');
        $returnPointMinLimit =  $integralValidate->checkIntegral($param['point_min_limit'],'',$param,'point_min_limit');
        $returnPointRate =  $integralValidate->checkIntegral($param['point_rate'],'',$param,'point_rate');
        $returnPointUsePercent =  $integralValidate->checkIntegral($param['point_use_percent'],'',$param,'point_use_percent');
        if($returnConsumeIntegral !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnConsumeIntegral, 'result' => '']);
        }else {
            $list[] = ['name' => 'is_consume_integral', 'value' => $param['is_consume_integral'], 'inc_type' => "{$param['inc_type']}"];
            $list[] = ['name' => 'consume_integral', 'value' => $param['consume_integral'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnRegIntegral !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnRegIntegral, 'result' => '']);
        }else {
            $list[] = ['name' => 'is_reg_integral', 'value' => $param['is_reg_integral'], 'inc_type' => "{$param['inc_type']}"];
            $list[] = ['name' => 'reg_integral', 'value' => $param['reg_integral'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnInviteIntegral !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnInviteIntegral, 'result' => '']);
        }else {
            $list[] = ['name' => 'invite', 'value' => $param['invite'], 'inc_type' => "{$param['inc_type']}"];
            $list[] = ['name' => 'invite_integral', 'value' => $param['invite_integral'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnInviteeIntegral !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnInviteeIntegral, 'result' => '']);
        }else {
            $list[] = ['name' => 'invitee_integral', 'value' => $param['invitee_integral'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnPointMinLimit !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnPointMinLimit, 'result' => '']);
        }else {
            $list[] = ['name' => 'is_point_min_limit', 'value' => $param['is_point_min_limit'], 'inc_type' => "{$param['inc_type']}"];
            $list[] = ['name' => 'point_min_limit', 'value' => $param['point_min_limit'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnPointRate !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnPointRate, 'result' => '']);
        }else {
            $list[] = ['name' => 'is_point_rate', 'value' => $param['is_point_rate'], 'inc_type' => "{$param['inc_type']}"];
            $list[] = ['name' => 'point_rate', 'value' => $param['point_rate'], 'inc_type' => "{$param['inc_type']}"];
        }
        if($returnPointUsePercent !== true){
            $this->ajaxReturn(['status' => 0, 'msg' => $returnPointUsePercent, 'result' => '']);
        }else {
            $list[] = ['name'=>'is_point_use_percent','value'=>$param['is_point_use_percent'],'inc_type'=>"{$param['inc_type']}"];
            $list[] = ['name'=>'point_use_percent','value'=>$param['point_use_percent'],'inc_type'=>"{$param['inc_type']}"];
        }
        foreach($list as $key=>$val){
            $confInfo = Db::name("config")->where("name='{$val['name']}' and inc_type='{$param['inc_type']}'")->find();
            if(!empty($confInfo)){
                Db::name("config")->where("id={$confInfo['id']}")->update($list[$key]);
            }else{
                Db::name("config")->insert($val);
            }
        }
        $return_arr = array('msg' => '更新数据成功！','status' => 1, 'result' => '');
        $this->ajaxReturn($return_arr);
    }
}