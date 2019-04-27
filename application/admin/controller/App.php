<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 */

namespace app\admin\controller;

use app\common\logic\saas\AppLogic;
use think\AjaxPage;

class App extends Base
{

    /**
     * 关联小程序
     */
    public function bind_miniapp()
    {
        $serviceId = input('service_id/d', 0);
        $miniappId = input('miniapp_id/d', 0);

        $appLogic = new AppLogic;
        $return = $appLogic->bindMiniapp($this->user_id, $serviceId, $miniappId);
        $this->ajaxReturn($return);
    }

    /**
     * 解绑小程序
     */
    public function unbind_miniapp()
    {
        $saas_cfg = $GLOBALS['SAAS_CONFIG'];
        $serviceId = $saas_cfg['service_id'];
        $appLogic = new AppLogic();
        $return = $appLogic->unbindMiniapp($serviceId);
        $this->ajaxReturn($return);
    }
}