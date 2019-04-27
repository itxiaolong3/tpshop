<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/24
 * Time: 10:24
 */

namespace app\common\model;

use think\Model;

class WxNews extends Model
{
    public function wxMaterial()
    {
        return $this->belongsTo('WxMaterial', 'material_id', 'id');
    }

    public function getContentDigestAttr($value, $data)
    {
        return mb_substr(trim(strip_tags(htmlspecialchars_decode($data['content']))), 0, 54, 'UTF-8');
    }
}