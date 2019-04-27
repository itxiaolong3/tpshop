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
 * Date: 2018-6-27
 */
namespace app\common\model;
use think\Model;
use think\Db;
class MessageNotice extends Model
{
    public function userMessage()
    {
        return $this->hasMany('userMessage', 'message_id', 'message_id');
    }

    public function getSendTimeTextAttr($value, $data)
    {
        return time_to_str($data['send_time']);
    }
    public function getHomeUrlAttr($value, $data)
    {
        return '';
    }
    public function getFinishedAttr($value, $data)
    {
        $return_flag =  false;
        switch ($data['mmt_code']) {
            // 优惠券
            case 'coupon_will_expire_notice':
            case 'coupon_use_notice':
            case 'coupon_get_notice':
                $return_arr = Db::name('coupon')->field('use_end_time,status')->where('id', $data['prom_id'])->find();
                if (time() > $return_arr['use_end_time'] or $return_arr['status'] == 2) {
                    $return_flag =  true;
                }
                break;
            default:
                $return_flag = false;
                break;
        }
        return $return_flag;
    }
    public function getMobileUrlAttr($value, $data)
    {
        return '';
    }
    public function getOrderTextAttr($value, $data)
    {
        return '';
    }
    public function getStartTimeAttr($value, $data)
    {
        return true;
    }
}
