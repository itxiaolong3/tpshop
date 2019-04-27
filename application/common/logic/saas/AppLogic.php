<?php
/**
 * tpshop
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * @Author: lhb
 */
namespace app\common\logic\saas;

use app\common\model\saas\AppService;
use app\common\model\saas\Miniapp;

class AppLogic
{
    /**
     * 绑定小程序
     */
    public function bindMiniapp($userId, $serviceId, $miniappId)
    {
        if (!$appService = AppService::get(['service_id' => $serviceId, 'user_id' => $userId])) {
            return ['status' => -1, 'msg' => '应用服务不存在'];
        }
        if ($appService->end_time <= time() || $appService->status != AppService::STATUS_NORMAL) {
            return ['status' => -1, 'msg' => '应用服务已过期'];
        }
        if ($appService->miniapp_id) {
            return ['status' => -1, 'msg' => '该应用已绑定过小程序'];
        }
        if (!$miniapp = Miniapp::get(['user_id' => $userId, 'miniapp_id' => $miniappId, 'is_auth' => 1])) {
            return ['status' => -1, 'msg' => '指定小程序不存在'];
        }
        if (AppService::get(['miniapp_id' => $miniappId, 'status' => AppService::STATUS_NORMAL])) {
            return ['status' => -1, 'msg' => '小程序已被绑定过'];
        }

        $appService->save(['miniapp_id' => $miniappId]);
        $miniapp->save(['service_id' => $serviceId]);

        return ['status' => 1, 'msg' => '绑定成功'];
    }

    /**
     * 解绑小程序
     */
    public function unbindMiniapp($serviceId)
    {
        if (!$appService = AppService::get(['service_id' => $serviceId])) {
            return ['status' => -1, 'msg' => '应用服务不存在'];
        }
        if (!$appService->miniapp_id) {
            return ['status' => -1, 'msg' => '应用没关联过小程序'];
        }
        if (!$miniapp = Miniapp::get(['miniapp_id' => $appService->miniapp_id, 'is_auth' => 1])) {
            return ['status' => -1, 'msg' => '指定小程序不存在'];
        }

        $appService->save(['miniapp_id' => 0]);
        $miniapp->save(['service_id' => 0]);

        return ['status' => 1, 'msg' => '解绑成功'];
    }

}