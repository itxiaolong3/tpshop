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
 */

namespace app\admin\validate;

use think\Validate;

/**
 * Description of Article
 *
 * @author Administrator
 */
class Navigation extends Validate
{
    //验证规则
    protected $rule = [
        'name'      => 'require',
        'url'       => 'require',
        'sort'      => 'require|number',
    ];
    
    //错误消息
    protected $message = [
        'name.require'      => '导航名称不能为空',
        'cat_id.checkName'  => '所属分类必须选择',
        'sort.require'      => '排序不能为空',
        'sort.number'       => '排序值错误',
        'url.require'       => '链接地址不能为空',
        'url.url'           => '链接格式错误'
    ];
    
    //验证场景
    protected $scene = [
        'edit' => ['name', 'url', 'sort'],
        'add'  => ['name', 'url', 'sort'],
    ];

}
