<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: yhj
 * Date: 2018-7-6
 */
namespace app\common\logic;


/**
 * 消息工厂类
 * Class CatsLogic
 * @package admin\Logic
 */
class MessageFactory
{
    /**
     * @param $message|商品实例
     * @return MessageNoticeLogic|MessageActivityLogic|MessageLogisticsLogic|MessagePrivateLogic
     */
    public function makeModule($message)
    {
        switch ($message['category']) {
            case 0:
                return new MessageNoticeLogic($message);
            case 1:
                return new MessageActivityLogic($message);
            case 2:
                return new MessageLogisticsLogic($message);
            case 3:
                return new MessagePrivateLogic($message);
        }
    }

    /**
     * 检测是否符合消息工厂类的使用
     * @param $category |消息类型
     * @return bool
     */
    public function checkMessageCategory($category)
    {
        if (in_array($category, array_values([0, 1, 2, 3]))) {
            return true;
        } else {
            return false;
        }
    }

}
