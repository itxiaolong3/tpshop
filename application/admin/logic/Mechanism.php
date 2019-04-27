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
 * Author: 当燃
 * Date: 2015-09-09
 */

namespace app\admin\logic;
use think\Loader;
use think\Model;
use think\Db;

class Mechanism extends Model
{

    /**
     * 获取指定机构信息
     * @param $uid int 用户UID
     * @param bool $relation 是否关联查询
     *
     * @return mixed 找到返回数组
     */
    public function detail($uid, $relation = true)
    {
        $user = M('user_mechanism')->where(array('mechanism_id' => $uid))->relation($relation)->find();
        return $user;
    }

    /**
     * 添加或者机构信息
     * @param int $uid
     * @param array $data
     * @return array
     */
    public function addOrupdateMechanism($data = array())
    {
        if($data['mechanism_id']){
            $mechanism_id=$data['mechanism_id'];
            unset($data['mechanism_id']);
            $res = Db::name('user_mechanism')->where(['mechanism_id'=>$mechanism_id])->save($data);
        }else{
            $res = Db::name('user_mechanism')->insert($data);
        }
        if($res){
            return array(1, "机构信息修改成功");
        }else{
            return array(0, "机构信息修改失败");
        }
    }

}