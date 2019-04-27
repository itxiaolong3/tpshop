<?php
/**
 * tpshop
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 商业用途务必到官方购买正版授权, 使用盗版将严厉追究您的法律责任。
 * ============================================================================
 * Author: lhb
 */

namespace app\admin\controller;

use think\Db;
use think\Page;
use think\AjaxPage;
use app\common\model\WxNews;
use app\common\model\WxReply;
use app\common\model\WxTplMsg;
use app\common\model\WxMaterial;
use app\common\logic\wechat\WechatUtil;
use app\common\logic\WechatLogic;

class Wechat extends Base
{
    private $wx_user;

    function __construct()
    {
        parent::__construct();
        $this->wx_user = Db::name('wx_user')->find();
    }

    public function index()
    {
        $wx_user = $this->wx_user;
        header("Location:".U('Wechat/setting',['id'=>$wx_user['id']]));
        exit;
    }

    public function setting()
    {
        $id = I('get.id');
        if (!empty($id)) {
            $wechat = Db::name('wx_user')->where(array('id' => $id))->find();
            if (!$wechat) {
                $this->error("公众号不存在");
                exit;
            }
            if (IS_POST) {
                $post_data = input('post.');
                $post_data['web_expires'] = 0;
                $row = Db::name('wx_user')->where(array('id' => $id))->update($post_data);
                $row && exit($this->success("修改成功"));
                exit($this->error("修改失败"));
            }
            $apiurl = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php?m=Home&c=Weixin&a=index';

            $this->assign('wechat', $wechat);
            $this->assign('apiurl', $apiurl);
        } else {
            //不存在ID则添加
            $exist = $this->wx_user;
            if ($exist[0]['id'] > 0) {
                $this->error('只能添加一个公众号噢');
                exit;
            }
            if (IS_POST) {
                $data = input('post.');
                $data['token'] = get_rand_str(6, 1, 0);
                $data['create_time'] = time();
                $row = Db::name('wx_user')->insertGetId($data);
                if ($row) {
                    $this->success('添加成功', U('Admin/Wechat/setting', array('id' => $row)));
                } else {
                    $this->error('操作失败');
                }
                exit;
            }
        }
        return $this->fetch();
    }

    public function menu()
    {
        $wechat = $this->wx_user;
        if (empty($wechat)) {
            $this->error('请先在公众号配置添加公众号，才能进行微信菜单管理', U('Admin/Wechat/index'));
        }
        if (IS_POST) {
            $post_menu = input('post.menu/a');
            //查询数据库是否存在
            $menu_list = Db::name('wx_menu')->where(array('token' => $wechat['token']))->getField('id', true);
            foreach ($post_menu as $k => $v) {
                $v['token'] = $wechat['token'];
                if (in_array($k, $menu_list)) {
                    //更新
                    Db::name('wx_menu')->where(array('id' => $k))->save($v);
                } else {
                    //插入
                    Db::name('wx_menu')->where(array('id' => $k))->add($v);
                }
            }
            $this->success('操作成功,进入发布步骤', U('Admin/Wechat/pub_menu'));
            exit;
        }
        //获取最大ID
        //$max_id = Db::name('wx_menu')->where(array('token'=>$wechat['token']))->field('max(id) as id')->find();
        $max_id = DB::query("SHOW TABLE STATUS WHERE NAME = '__PREFIX__wx_menu'");
        $max_id = $max_id[0]['auto_increment'] ? $max_id[0]['auto_increment'] : $max_id[0]['Auto_increment'];

        //获取父级菜单
        $p_menus = Db::name('wx_menu')->where(array('token' => $wechat['token'], 'pid' => 0))->order('id ASC')->select();
        $p_menus = convert_arr_key($p_menus, 'id');
        //获取二级菜单
        $c_menus = Db::name('wx_menu')->where(array('token' => $wechat['token'], 'pid' => array('gt', 0)))->order('id ASC')->select();
        $c_menus = convert_arr_key($c_menus, 'id');
        $this->assign('p_lists', $p_menus);
        $this->assign('c_lists', $c_menus);
        $this->assign('max_id', $max_id ? $max_id - 1 : 0);
        return $this->fetch();
    }


    /*
     * 删除菜单
     */
    public function del_menu()
    {
        $id = I('get.id');
        if(!$id){
            exit('fail');
        }
        $row = Db::name('wx_menu')->where(array('id'=>$id))->delete();
        $row && Db::name('wx_menu')->where(array('pid'=>$id))->delete(); //删除子类
        if($row){
            exit('success');
        }else{
            exit('fail');
        }
    }

    /*
     * 生成微信菜单
     */
    public function pub_menu()
    {
//        $menu = array();
//        $menu['button'][] = array(
//            'name'=>'测试',
//            'type'=>'view',
//            'url'=>'http://wwwtp-shhop.cn'
//        );
//        $menu['button'][] = array(
//            'name'=>'测试',
//            'sub_button'=>array(
//                array(
//                    "type"=> "scancode_waitmsg",
//                    "name"=> "系统拍照发图",
//                    "key"=> "rselfmenu_1_0",
//                    "sub_button"=> array()
//                )
//            )
//        );

        //获取父级菜单
        $p_menus = Db::name('wx_menu')->where(array('pid' => 0))->order('id ASC')->select();
        $p_menus = convert_arr_key($p_menus, 'id');
        if (!count($p_menus) > 0) {
            $this->error('没有菜单可发布', U('Wechat/menu'));
        }

        $post = $this->convert_menu($p_menus);
        $wechatObj = new WechatUtil($this->wx_user);
        if ($wechatObj->createMenu($post) === false) {
            $this->error($wechatObj->getError());
        }

        $this->success('菜单已成功生成', U('Wechat/menu'));
    }

    //菜单转换
    private function convert_menu($p_menus)
    {
//        $key_map = array(
//            'scancode_waitmsg'=>'rselfmenu_0_0',
//            'scancode_push'=>'rselfmenu_0_1',
//            'pic_sysphoto'=>'rselfmenu_1_0',
//            'pic_photo_or_album'=>'rselfmenu_1_1',
//            'pic_weixin'=>'rselfmenu_1_2',
//            'location_select'=>'rselfmenu_2_0',
//        );
        $new_arr = array();
        $count = 0;
        foreach($p_menus as $k => $v){
            $new_arr[$count]['name'] = $v['name'];

            //获取子菜单
            $c_menus = Db::name('wx_menu')->where(['pid'=>$k])->select();

            if($c_menus){
                foreach($c_menus as $kk=>$vv){
                    $add = array();
                    $add['name'] = $vv['name'];
                    $add['type'] = $vv['type'];
                    // click类型
                    if($add['type'] == 'click'){
                        $add['key'] = $vv['value'];
                    }elseif($add['type'] == 'view'){
                        $add['url'] = $vv['value'];
                    }else{
                        $add['key'] = $vv['value'];
                    }
                    $add['sub_button'] = array();
                    if($add['name']){
                        $new_arr[$count]['sub_button'][] = $add;
                    }
                }
            }else{
                $new_arr[$count]['type'] = $v['type'];
                // click类型
                if($new_arr[$count]['type'] == 'click'){
                    $new_arr[$count]['key'] = $v['value'];
                }elseif($new_arr[$count]['type'] == 'view'){
                    //跳转URL类型
                    $new_arr[$count]['url'] = $v['value'];
                }else{
                    //其他事件类型
                    $new_arr[$count]['key'] = $v['value'];
                }
            }
            $count++;
        }

        return array('button'=>$new_arr);
    }

    /**
     * 自动回复的菜单
     */
    private function auto_reply_menu()
    {
        return [
            WxReply::TYPE_KEYWORD => ['menu' => '关键词自动回复', 'url' => url('auto_reply', ['type' => WxReply::TYPE_KEYWORD])],
            WxReply::TYPE_DEFAULT => ['menu' => '消息自动回复', 'url' => url('auto_reply_edit', ['type' => WxReply::TYPE_DEFAULT])],
            WxReply::TYPE_FOLLOW  => ['menu' => '关注时自动回复', 'url' => url('auto_reply_edit', ['type' => WxReply::TYPE_FOLLOW])]
        ];
    }

    /**
     * 自动回复展示
     */
    public function auto_reply()
    {
        $type = input('type', WxReply::TYPE_KEYWORD);
        $types = $this->auto_reply_menu();
        if (!key_exists($type, $types)) {
            $this->error("标签 $type 不存在");
        }
        $this->assign('type', $type);
        $this->assign('types', $types);

        if ($type == WxReply::TYPE_KEYWORD) {
            $p = input('p');
            $num = 10;
            $condition = ['type' => $type];
            $replies = WxReply::where($condition)->with('wxKeywords')->order('id', 'asc')->page($p, $num)->select();
            $count = WxReply::where($condition)->count();
            $page = new Page($count, $num);
            $this->assign('page', $page);
            $this->assign('replies', $replies);
            return $this->fetch('auto_replies');

        } else {
            $this->redirect('auto_reply_edit', ['type' => $type]);
        }
    }

    /**
     * 自动回复编辑页面
     */
    public function auto_reply_edit()
    {
        $id = input('id/d');
        $type = input('type', WxReply::TYPE_KEYWORD);
        $types = $this->auto_reply_menu();
        if (!key_exists($type, $types)) {
            $this->error("标签 $type 不存在");
        }
        $this->assign('type', $type);
        $this->assign('types', $types);

        if ($type == WxReply::TYPE_KEYWORD) {
            if ($id && !$reply = WxReply::get(['id' => $id, 'type' => $type])) {
                $this->error('该自动回复不存在');
            }
        } else {
            $reply = WxReply::get(['type' => $type]);
        }

        if  ( ! empty($reply)) {
            if ($reply->msg_type == WxReply::MSG_NEWS) {
                $news = WxMaterial::get($reply->material_id, 'wxNews');
                $this->assign('news', $news);
            }
            $this->assign('reply', $reply);
        }

        return $this->fetch();
    }

    /**
     * 新增自动回复
     */
    public function add_auto_reply()
    {
        $type = input('msg_type');
        $data = input('post.');

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->addAutoReply($type, $data);
        $this->ajaxReturn($return);
    }

    /**
     * 更新自动回复
     */
    public function update_auto_reply()
    {
        $type = input('msg_type');
        $id = input('id/d', 0);
        $data = input('post.');

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->updateAutoReply($type, $id, $data);
        $this->ajaxReturn($return);
    }

    /**
     * 删除自动回复
     */
    public function delete_auto_reply()
    {
        $id = input('id/d', 0);

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->deleteAutoReply($id);
        $this->ajaxReturn($return);
    }

    /**
     * 粉丝详细列表
     */
    public function fans_list()
    {
        $keyword = input('keyword');
        $p = input('p/d');
        $num = 10;
        $logic = new WechatLogic;
        $return = $logic->getFanList($p, $num, $keyword);
        if ($return['status'] != 1) {
            $this->error($return['msg'], null, '', 100);
        }

        $texts = WxMaterial::all(['type' => WxMaterial::TYPE_TEXT]);
        $page  = new Page($return['result']['total'], $num);

        $this->assign('page', $page);
        $this->assign('texts', $texts);
        $this->assign('user_list', $return['result']['list']);
        return $this->fetch();
    }

    public function fan_info()
    {
        $openid = I('get.id');
        $wechatObj = new WechatUtil($this->wx_user);
        $list = $wechatObj->getFanInfo($openid);
        if ($list === false) {
            $this->error($wechatObj->getError());
        }

        $list['tags'] = $wechatObj->getFanTagNames($list['tagid_list']);
        if ($list['tags'] === false) {
            $this->error($wechatObj->getError());
        }

        $this->assign('list', $list);
        return $this->fetch();
    }

    /**
     * 处理发送的消息
     */
    public function send_text_msg()
    {
        $msg = I('post.msg');//内容
        $to_all = I('post.to_all', 0);//个体or全体
        $openids = I('post.openids');//个体id

        $wechatObj = new WechatUtil($this->wx_user);
        if ($to_all) {
            $result = $wechatObj->sendMsgToAll(0, 'text', $msg);
        } else {
            $result = $wechatObj->sendMsg($openids, 'text', $msg);
        }

        if ($result === false) {
            return $this->ajaxReturn(['status'=>0,'msg'=>$wechatObj->getError()]);
        }

        return $this->ajaxReturn(['status'=>1,'msg'=>'已发送！']);
    }

    /**
     * 素材管理
     */
    public function materials()
    {
        $tab = input('tab', 'news');
        $tabs = [
            'news' => '图文素材',
            'text' => '文本素材'
        ];
        if (!key_exists($tab, $tabs)) {
            $this->error("标签 $tab 不存在");
        }

        $p = input('p', 0);
        $num = 10;
        if ($tab == 'news') {
            $materials = WxMaterial::where(['type' => $tab])->with('wxNews')->order('update_time', 'desc')->page($p, $num)->select();
        } else {
            $materials = WxMaterial::where(['type' => $tab])->order('update_time', 'desc')->page($p, $num)->select();
        }

        $count = WxMaterial::where(['type' => $tab])->count();
        $page  = new Page($count, $num);

        $this->assign('page', $page);
        $this->assign('list', $materials);
        $this->assign('tab', $tab);
        $this->assign('tabs', $tabs);
        return $this->fetch('materials_'.$tab);
    }

    /**
     * 异步请求图文消息
     */
    public function ajax_news()
    {
        $p = input('p', 0);
        $num = 9;
        $materials = WxMaterial::where(['type' => WxMaterial::TYPE_NEWS])->with('wxNews')->order('update_time', 'desc')->page($p, $num)->select();
        $count = WxMaterial::where(['type' => WxMaterial::TYPE_NEWS])->count();
        $page  = new AjaxPage($count, $num);

        $this->assign('page', $page);
        $this->assign('list', $materials);
        return $this->fetch();
    }

    /**
     * 单图文素材编辑
     */
    public function news_edit()
    {
        $material_id = input('material_id/d');
        $news_id = input('news_id/d');

        if ($news_id) {
            if (!$news = WxNews::get(['id' => $news_id, 'material_id' => $material_id])) {
                $this->error('该图文素材不存在');
            }
            $this->assign('info', $news);
        }

        return $this->fetch();
    }

    /**
     * 删除素材
     */
    public function delete_news()
    {
        $material_id = input('material_id/d');

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->deleteNews($material_id);

        return $this->ajaxReturn($return);
    }

    /**
     * 删除多图文中的单图文
     */
    public function delete_single_news()
    {
        $news_id = input('news_id/d');

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->deleteSingleNews($news_id);

        return $this->ajaxReturn($return);
    }

    /**
     * 新增或更新单图文素材
     */
    public function handle_news()
    {
        $material_id = input('material_id/d');//为0新增多素材，否则更新多素材
        $news_id = input('news_id/d', 0);//为0新增单素材，否则更新单素材，此时material_id不为0
        $data = input('post.');

        $result = $this->validate($data, 'WechatNews', [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }

        $logic = new WechatLogic;
        $return = $logic->createOrUpdateNews($material_id, $news_id, $data);
        return $this->ajaxReturn($return);
    }

    /**
     * 发送图文素材消息
     */
    public function send_news_msg()
    {
        $material_id = input('material_id');
        $to_all = input('to_all', 0);//个体or全体
        $openids = input('openids');//个体id

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->sendNewsMsg($material_id, $openids, $to_all);
        return $this->ajaxReturn($return);
    }

    /**
     * 编辑文本素材
     */
    public function text_edit()
    {
        $material_id = input('material_id/d');
        if ($material_id) {
            if (!$text = WxMaterial::get(['id' => $material_id, 'type' => WxMaterial::TYPE_TEXT])) {
                $this->error('该文本素材不存在');
            }
            $this->assign('info', $text);
        }

        return $this->fetch();
    }

    /**
     * 新增或更新文本素材
     */
    public function handle_text()
    {
        $material_id = input('material_id/d');//为0新增素材，否则更新素材
        $data = input('post.');

        $logic = new WechatLogic;
        $return = $logic->createOrUpdateText($material_id, $data);
        return $this->ajaxReturn($return);
    }

    /**
     * 删除文本素材
     */
    public function delete_text()
    {
        $material_id = input('material_id/d');

        $logic = new WechatLogic($this->wx_user);
        $return = $logic->deleteText($material_id);

        return $this->ajaxReturn($return);
    }

    /**
     * 模板消息
     */
    public function template_msg()
    {
        $logic = new WechatLogic;
        $tpls = $logic->getDefaultTemplateMsg();

        $template_sns = get_arr_column($tpls, 'template_sn');
        $user_tpls = WxTplMsg::all(['template_sn' => ['in', $template_sns]]);
        $user_tpls = convert_arr_key($user_tpls, 'template_sn');

        $this->assign('tpls', $tpls);
        $this->assign('user_tpls', $user_tpls);
        return $this->fetch();
    }

    /**
     * 设置模板消息
     */
    public function set_template_msg()
    {
        $template_sn = input('template_sn');
        $is_use = input('is_use/d');
        $remark = input('remark');

        $data = [];
        !is_null($is_use) && $data['is_use'] = $is_use;
        !is_null($remark) && $data['remark'] = $remark;

        $logic = new WechatLogic;
        $return = $logic->setTemplateMsg($template_sn, $data);
        $this->ajaxReturn($return);
    }

    /**
     * 重置模板消息
     */
    public function reset_template_msg()
    {
        $template_sn = input('template_sn');

        $logic = new WechatLogic;
        $return = $logic->resetTemplateMsg($template_sn);

        $this->ajaxReturn($return);
    }
}