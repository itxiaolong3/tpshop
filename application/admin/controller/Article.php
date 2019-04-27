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
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-09
 */
namespace app\admin\controller;

use think\Page;
use think\Db;
use app\admin\logic\ArticleCatLogic;

class Article extends Base {

    public function categoryList(){
        $ArticleCat = new ArticleCatLogic(); 
        $cat_list = $ArticleCat->article_cat_list(0, 0, false);
        $this->assign('cat_list',$cat_list);
        return $this->fetch('categoryList');
    }

    public function category()
    {
        $ArticleCat = new ArticleCatLogic();
        $act = I('get.act', 'add');
        $cat_id = I('get.cat_id/d');
        $parent_id = I('get.parent_id/d');
        if ($cat_id) {
            $cat_info = M('article_cat')->where('cat_id=' . $cat_id)->find();
            $parent_id = $cat_info['parent_id'];
            $this->assign('cat_info', $cat_info);
        }
        $cats = $ArticleCat->article_cat_list(0, $parent_id, true);
        $this->assign('act', $act);
        $this->assign('cat_select', $cats);
        return $this->fetch();
    }
    
    public function articleList(){
        $Article =  M('Article'); 
        $res = $list = array();
        $p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
        $size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
        
        $where = " 1 = 1 ";
        $keywords = trim(I('keywords'));
        $keywords && $where.=" and title like '%$keywords%' ";
        $cat_id = I('cat_id',0);
        $cat_id && $where.=" and cat_id = $cat_id ";
        $res = $Article->where($where)->order('article_id desc')->page("$p,$size")->select();
        $count = $Article->where($where)->count();// 查询满足要求的总记录数
        $pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
        //$page = $pager->show();//分页显示输出
        
        $ArticleCat = new ArticleCatLogic();
        $cats = $ArticleCat->article_cat_list(0,0,false);
        if($res){
        	foreach ($res as $val){
        		$val['category'] = $cats[$val['cat_id']]['cat_name'];
        		$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
        		$list[] = $val;
        	}
        }
        $this->assign('cats',$cats);
        $this->assign('cat_id',$cat_id);
        $this->assign('list',$list);// 赋值数据集
        $this->assign('pager',$pager);// 赋值分页输出        
		return $this->fetch('articleList');
    }
    
    public function article(){
        $ArticleCat = new ArticleCatLogic();
 		$act = I('GET.act','add');
        $info = array();
        $info['publish_time'] = time()+3600*24;
        if(I('GET.article_id')){
           $article_id = I('GET.article_id');
           $info = M('article')->where('article_id='.$article_id)->find();
        }
        $cats = $ArticleCat->article_cat_list(0,$info['cat_id']);
        $this->assign('cat_select',$cats);
        $this->assign('act',$act);
        $this->assign('info',$info);
        return $this->fetch();
    }
    
    
    public function categoryHandle()
    {
    	$data = I('post.');

        $result = $this->validate($data, 'ArticleCategory.'.$data['act'], [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }
        
        if ($data['act'] == 'add') {
            $r = M('article_cat')->add($data);
        } elseif ($data['act'] == 'edit') {
        	$cat_info = M('article_cat')->where("cat_id",$data['cat_id'])->find();
        	if($cat_info['cat_type'] == 1 && $data['parent_id'] > 1){
        		$this->ajaxReturn(['status' => -1, 'msg' => '可更改系统预定义分类的上级分类']);
        	}
        	$r = M('article_cat')->where("cat_id",$data['cat_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	if($data['cat_id']<9){
        		$this->ajaxReturn(['status' => -1, 'msg' => '系统默认分类不得删除']);
        	}
        	if (M('article_cat')->where('parent_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '还有子分类，不能删除']);
        	}
        	if (M('article')->where('cat_id', $data['cat_id'])->count()>0)
        	{
        		$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
        	}
        	$r = M('article_cat')->where('cat_id', $data['cat_id'])->delete();
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        } 
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }
    
    public function aticleHandle()
    {
        $data = I('post.');
        $data['publish_time'] = strtotime($data['publish_time']);
        $result = $this->validate($data, 'Article.'.$data['act'], [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }
        $where['cat_id']=$data['cat_id'];
        $where['title']=$data['title'];
        if($data['act']=='edit'){
            $where['article_id']=array('neq',$data['article_id']);
        }
        $find = db('article')->where($where)->select();
        if($find){
            $this->ajaxReturn(['status' => 0, 'msg' => '此分类下已存在该标题']);
        }
        if ($data['act'] == 'add') {
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time(); 
            $r = M('article')->add($data);
        } elseif ($data['act'] == 'edit') {
            $r = M('article')->where('article_id='.$data['article_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	$r = M('article')->where('article_id='.$data['article_id'])->delete(); 	
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
            
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }
    
    //友情链接部分
    public function link(){
    	$act = I('GET.act','add');
    	$this->assign('act',$act);
    	$link_id = I('GET.link_id');
    	$link_info = array();
    	if($link_id){
    		$link_info = M('friend_link')->where('link_id='.$link_id)->find();
    		$this->assign('info',$link_info);
    	}
    	return $this->fetch();
    }
    
    public function linkList(){
    	$Ad =  M('friend_link');
        $p = $this->request->param('p');
    	$res = $Ad->order('orderby')->page($p.',10')->select();
    	if($res){
    		foreach ($res as $val){
    			$val['target'] = $val['target']>0 ? '开启' : '关闭';
    			$list[] = $val;
    		}
    	}
    	$this->assign('list',$list);// 赋值数据集
    	$count = $Ad->count();// 查询满足要求的总记录数
    	$Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
    	$show = $Page->show();// 分页显示输出
        $this->assign('pager',$Page);
    	$this->assign('page',$show);// 赋值分页输出
    	return $this->fetch();
    }
    
    public function linkHandle(){
        $data = I('post.');
    	if($data['act'] == 'del'){
    		$r = M('friend_link')->where(['link_id'=>$data['link_id']])->delete();
    		if($r) exit(json_encode(1));
    	}
    	if($r){
    		$this->success("操作成功",U('Admin/Article/linkList'));
    	}else{
            $this->error("操作失败");
    	}
    }
    
    public function  addEdit(){
        $data = I('post.');
        $result = $this->validate($data,'FriendLink.'.$data['act'], [], true);
        if(true !== $result){
            // 验证失败 输出错误信息
            $validate_error = '';
            foreach ($result as $key =>$value){
                $validate_error .=$value.',';
            }
            $this->ajaxReturn(['status'=>-1,'msg'=>$validate_error]);
        }
        if($data['link_id']){
            $link_id=$data['link_id'];
            unset($data['link_id']);
            $res = Db::name('friend_link')->where(['link_id'=>$link_id])->save($data);
        }else{
            $res = Db::name('friend_link')->insert($data);
        }
        if($res){
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Article/linkList')]);
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'操作失败']);
        }
    }
    //友情链接部分结束
    //轮播图部分
    //显示轮播图编辑页
    public function banner(){
        $act = I('GET.act','add');
        $this->assign('act',$act);
        $link_id = I('GET.link_id');
        $link_info = array();
        if($link_id){
            $link_info = M('banner')->where('b_id='.$link_id)->find();
            $this->assign('info',$link_info);
        }
        return $this->fetch();
    }
    //轮播图列表
    public function bannerlist(){
        $Ad =  M('banner');
        $p = $this->request->param('p');
        $res = $Ad->order('orderby')->page($p.',10')->select();
        if($res){
            foreach ($res as $val){
                //$val['target'] = $val['target']>0 ? '开启' : '关闭';
                $list[] = $val;
            }
        }
        $this->assign('list',$list);// 赋值数据集
        $count = $Ad->count();// 查询满足要求的总记录数
        $Page = new Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();// 分页显示输出
        $this->assign('pager',$Page);
        $this->assign('page',$show);// 赋值分页输出
        return $this->fetch();
    }
    //删除轮播图
    public function bannerdel(){
        $data = I('post.');
        if($data['act'] == 'del'){
            $r = M('banner')->where(['b_id'=>$data['link_id']])->delete();
            if($r) exit(json_encode(1));
        }
        if($r){
            $this->success("操作成功",U('Admin/Article/bannerlist'));
        }else{
            $this->error("操作失败");
        }
    }
    //处理编辑或新增轮播图逻辑
    public function  addEditbanner(){
        $data = I('post.');
        if (empty($data['b_name'])){
            $this->ajaxReturn(['status'=>-1,'msg'=>'请填轮播图名称']);
        }else if (empty($data['b_img'])){
            $this->ajaxReturn(['status'=>-1,'msg'=>'请上传轮播图']);
        }
        if($data['link_id']){
            $link_id=$data['link_id'];
            unset($data['link_id']);
            $res = Db::name('banner')->where(['link_id'=>$link_id])->save($data);
        }else{
            $res = Db::name('banner')->insert($data);
        }
        if($res){
            $this->ajaxReturn(['status'=>1,'msg'=>'操作成功','url'=>U('Admin/Article/bannerlist')]);
        }else{
            $this->ajaxReturn(['status'=>-1,'msg'=>'操作失败']);
        }
    }
    public function agreement(){
    	$agreement = db('system_article')->select();
    	$this->assign('agreement',$agreement);
    	return $this->fetch();
    }

    public function edit_agreement(){
    	$doc_id = I('doc_id');
    	if(IS_POST){
    		$data = I('post.');
    		db('system_article')->where('doc_id',$doc_id)->save($data);
    		$this->success('更新成功!');
    	}
    	if(empty($doc_id)) $this->error('参数错误');
    	$info = db('system_article')->where('doc_id',$doc_id)->find();
    	if(empty($info)) $this->error('该协议不存在');
    	$this->assign('info',$info);
    	return $this->fetch();
    }
    
}