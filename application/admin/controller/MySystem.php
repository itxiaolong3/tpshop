<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-10-09
 */

namespace app\admin\controller;

use think\Db;

class MySystem extends Base
{
    public function system()
    {
        $config=Db::name('myconfig')->where('id',1)->find();
        $this->assign('config',$config);
        return $this->fetch();
    }
	/*
	 * 新增修改配置
	 */
    public function dealFenxiao()
    {
        $param = I('post.');
        $param['addtime']=date('Y-m-d H:i:s',time());
        Db::name('myconfig')->where('id',1)->save($param);
        echo json_encode(array('code'=>200,'msg'=>'修改成功','data'=>''));
        //$this->success("操作成功",U('Distribut/mySystem'));

    }
}