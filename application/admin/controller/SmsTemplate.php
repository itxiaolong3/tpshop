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
 * Author: wangqh
 * Date: 2015-09-09
 *  短信平台短信模板管理
 */
namespace app\admin\controller; 
use think\Controller;
use think\Db;
use think\Page;

class SmsTemplate extends Base {

    public  $send_scene;
    
    public function _initialize() {
        parent::_initialize();
        
        // 短信使用场景
        $send_scene = C('SEND_SCENE');
        $sms_platform = tpCache('sms.sms_platform');
        
        //如果是天瑞短信, 则需要对变量重新处理
        if($sms_platform == 2){
            foreach ($send_scene as $k=>$v){
                $sms_conent= $v[1];
                preg_match_all('#(\$\{[a-zA-Z_]*\})#',$sms_conent, $result);
                $params = $result[1];
                foreach ($params as $sk=>$sv){
                    $sms_conent = str_replace($sv, '{'.($sk+1).'}' , $sms_conent);
                }
                $send_scene[$k][1] = $sms_conent;
            }
        }
        
        $this->send_scene = $send_scene; 
        
        $this->assign('send_scene', $send_scene);  
        
    }
    
    public function index(){
        
        $smsTpls = M('sms_template')->select();
		$this->assign('smsTplList',$smsTpls);
		$this->assign('k','list');
        return $this->fetch("sms_template_list");
       
    }
    
    /**
     * 短信发送日志
     * @return \think\mixed
     */
    public function sms_log(){
    
        $mobile = I("mobile/s","");
         
        $mobile ? $condition['mobile'] = ['like', "%$mobile%"] : false;
        
        $count = Db::name("SmsLog")->where($condition)->count();
         
        $page  = new Page($count);
        $smsLogList = Db::name("SmsLog")->where($condition)->limit($page->firstRow,$page->listRows)->order("add_time desc")->select();
        
        $show = $page->show();
        
        $this->assign('smsLogList',$smsLogList);
        $this->assign('page',$show);// 赋值分页输出
    	$this->assign('pager',$page);
        
        return $this->fetch("sms_log");
         
    }
    
    /**
     * 添加修改编辑  短信模板
     */
    public  function addEditSmsTemplate(){
        
        $id = I('tpl_id/d');
        $model = M("sms_template");
        
        if(IS_POST)
        {    
            $data = I('post.');
            $data['add_time'] = time();
            //echo "add_time : ".$model->add_time;
            //exit;
            if($id){
                $model->update($data);
            }else{
                $id = $model->save($data);
            }
            $this->success("操作成功!!!",U('Admin/SmsTemplate/index'));
            exit;
        } 
         
        if($id){
            //进入编辑页面
            $smsTemplate = $model->where("tpl_id" , $id)->find(); 
            $this->assign("smsTpl" , $smsTemplate );
            $sceneName = $this->send_scene[$smsTemplate['send_scene']][0];
            $sendscene = $smsTemplate['send_scene'];
            $this->assign("send_name" , $sceneName );
            $this->assign("send_scene_id" , $sendscene );
        }else{
            //进入添加页面
            //查找已经添加了的短信模板
            $scenes = $model->getField("send_scene" , true);
            $filterSendscene = array();
            //过滤已经添加过滤的短信模板
            foreach ($this->send_scene as $key => $value){
                if(!in_array($key, $scenes)){
                    $filterSendscene[$key] = $value;
                }
            }
        }
         
        
        $this->assign("send_scene" , $filterSendscene );
        return $this->fetch("_sms_template");
    }
    
    /**
     * 删除订单
     */
   public function delTemplate(){
       
       $model = M("sms_template");
       $row = $model->where('tpl_id ='.$_GET['id'])->delete();
       $return_arr = array();
       if ($row){
           $return_arr = array('status' => 1,'msg' => '删除成功','data'  =>'',);   //$return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);
       }else{
           $return_arr = array('status' => -1,'msg' => '删除失败','data'  =>'',);  
       } 
       return $this->ajaxReturn($return_arr);
       
   }

}