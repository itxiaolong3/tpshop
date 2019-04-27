<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * ============================================================================
 */

namespace app\admin\validate;

use think\Validate;

/**
 * Description of Article
 *
 * @author Administrator
 */
class WechatNews extends Validate
{
    //验证规则
    protected $rule = [
        'title'     => 'require|checkUtf8Max:64',
        'content'   => 'require|checkContent',
        'author'    => 'checkUtf8Max:8',
        'digest'    => 'checkUtf8Max:120',
        'content_source_url' => 'url',
        'thumb_url' => 'require'
    ];

    //错误消息
    protected $message = [
        'title' => '标题不能为空',
        'title.checkUtf8Max' => '标题最大64字符',
        'content'  => '内容不能为空',
        'author.checkUtf8Max' => '作者最大8字符',
        'digest.checkUtf8Max' => '摘要最大120字符',
        'content_source_url.url' => '原文链接的格式不正确',
        'thumb_url' => '封面必须上传'
    ];

    protected function checkEmpty($value)
    {
        if (is_string($value)) {
            $value = trim($value);
        }
        if (empty($value)) {
            return false;
        }
        return true;
    }

    protected function checkContent($value)
    {
        $value = strip_tags($value);
        $value = str_replace('&nbsp;', '', $value);
        $value = trim($value);
        if (empty($value)) {
            return false;
        }
        return true;
    }

    protected function checkUtf8Max($value, $max)
    {
        if (mb_strlen($value, 'UTF8') > $max) {
            return false;
        }
        return true;
    }
}
