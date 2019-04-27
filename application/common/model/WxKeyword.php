<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28
 * Time: 17:44
 */

namespace app\common\model;

use think\Model;

class WxKeyword extends Model
{
    //关键字类型
    const TYPE_AUTO_REPLY = 'auto_reply';

    public function wxReply()
    {
        return $this->belongsTo('WxReply', 'pid', 'id');
    }
}