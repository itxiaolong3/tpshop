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
 * Author: 聂晓克      
 * Date: 2017-12-14
 */
namespace app\admin\controller;
use app\common\logic\ActivityLogic;

use think\Db;

class Block extends Base{

	public function index(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	//自定义页面列表页
	public function pageList(){
            header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	public function ajaxGoodsList(){
            header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }


    //商品列表板块参数设置
    public function goods_list_block(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    //新闻列表 浏览
    public function get_news_list(){
        $data=I('post.');
        $num=I('post.num',2); 
        $ids=$data['news'];

        if($ids){
            $ids = substr($ids,0,strlen($ids)-1);
            $ids="(".$ids.")";
            $ids_arr = explode(',', $ids);
            $where_news['article_id'] = ['in', $ids_arr];
        }
        $where_news['publish_time'] = ['elt',time()];
        $where_news['is_open'] = 1;
        $list = Db::view('news')
            ->view('newsCat','cat_name','newsCat.cat_id=news.cat_id','left')
            ->where($where_news)
            ->order('publish_time DESC')
            ->limit(0,$num)
            ->select();

        $html='';
        foreach ($list as $k => $v) {
            $html.='<li><a href="'.'/api/news/news_detail.html?news_id='.$v['article_id'].'"><div class="carlist-img fl">';
            $html.='<img src="'.$v['thumb'].'"></div>';
            $html.='<div class="carlist-txt fr"><b>'.$v['title'].'</b>';
            $html.='<p>'.$v['description'].'</p>';
            $html.='<span><em>'.$v['cat_name'].'</em><img src="/public/static/images/icon-fire.png">';
            $html.='<i>'.date("Y-m-d",$v['publish_time']).'</i></span></div></a></li>';
        }
        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$html]);
    }

    //ajax获取新闻 修改
    public function ajaxNewsList(){

        $page = input('page/d',1);
        $cat_id = input('cat');
        if($cat_id){
            $where['cat_id'] = $cat_id;
        }
        $where['publish_time'] = ['elt',time()];
        $where['is_open'] = 1;
        $count_new=Db::name('news')->where($where)->count();
        if($cat_id){
            unset($where['cat_id']);
            $where['news.cat_id'] = $cat_id;
        }

        $list= Db::view('news')
            ->view('newsCat','cat_name','newsCat.cat_id=news.cat_id','left')
            ->where($where)
            ->order('publish_time DESC')
            ->page($page,10)
            ->select();

        $html='';
        foreach ($list as $k => $v) {
            $html.='<ul class="p-goods-item">';
            $html.='<li class="pi-li0"><input type="checkbox" value="'.$v['article_id'].'" /></li>';
            $html.='<li class="pi-li1">'.$v['article_id'].'</li>';
            $html.='<li class="pi-li2">'.$v['title'].'</li>';
            if($v['thumb']){
                $html.='<li class="pi-li3"><img src="'.$v['thumb'].'" alt="" /></li>';
            }else{
                $html.='<li class="pi-li3"></li>';
            }
            $html.='<li class="pi-li4">'.$v['cat_name'].'</li>';
            $html.='<li class="pi-li4">'.date("Y-m-d",$v['publish_time']).'</li>';
            $html.='</ul>';
        }
        
        $count_new=ceil($count_new/10);

        $result['html']=$html;
        $result['count_new']=$count_new;
        $this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$result]);
    }

	/**
	 * 查门店列表,默认3个后台编缉显示
	 */
	public function shopList(){
		$where['deleted'] = 0;
		$where['shop_status'] = 1;
//        $shop = new \app\common\model\Shop();
//        $shop_list = $shop->with('shop_images')->where($where)->limit(3)->select();
		$shop_list = Db::name('shop')->field('shop_id,shop_name,province_id,city_id,district_id,shop_address,longitude,latitude,deleted,shop_desc')->where($where)->limit(3)->select();
		$this->ajaxReturn(['status' => 1, 'msg' => '成功', 'result' =>$shop_list]);
	}
    
	/*
	*保存编辑完成后的信息
	*/
	public function add_data(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	//设置首页
	public function set_index(){
        header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}

	//删除页面
	public function delete(){
		$id=I('post.id');
		if($id){
            if(I('post.role')){
                $r = D('industry_template')->where('id', $id)->delete();
            }else{
                $r = D('mobile_template')->where('id', $id)->delete();
            }
    		exit(json_encode(1));
		}
	}

	
	//获取秒杀活动数据
	public function get_flash(){
            header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
	}


    //添加行业模板及风格入口页
    public function template_class(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }
    function filter_data($list){
        $data = [];
        foreach ($list as $k => $v) {
            if($v['parent_id']==0){
                $v['level']=0;
                $data[] = $v;
                foreach($list as $kk => $vv) {
                    if($v['id'] == $vv['parent_id']){
                        $vv['level']=1;
                        $data[] = $vv;
                    }
                }
            }
        }
        return $data;
    }

    //添加页面
    public function class_info(){
        if(I('id')){
            $info=Db::name('template_class')->where('id='.I('id'))->find();
            $this->assign('info',$info);
        }
        if(I('parent_id')){
            $info['parent_id'] = input('parent_id/d', 0);
            $this->assign('info',$info);
        }
        $list=Db::name('template_class')->where('parent_id=0')->order('sort_order DESC')->select();
        $this->assign('list',$list);
        $act=I('get.act');
        $this->assign('act',$act);
        return $this->fetch();
    }

    //添加行业及风格处理
    public function class_handle(){
        $data=I('post.');
        if(empty($data['name']) && ($data['act']=='add' || $data['act']=='edit')){
            $this->ajaxReturn(['status' => -1, 'msg' => '名称不能为空','result' => 1]);
        }
        // 行业时，没有父节点 提交的是type 还是class_type ?
        if($data['type'] == 1 || $data['class_type'] == 1){
            $data['parent_id'] = 0;
        }
        if($data['act']=='add'){
            $data['add_time']=time();
            $res=Db::name('template_class')->add($data);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }
        if($data['act']=='edit'){
            $param['add_time']=time();
            $param['parent_id']=$data['parent_id'];
            $param['name']=$data['name'];
            $param['sort_order']=$data['sort_order'];

            $res=Db::name('template_class')->where('id='.$data['id'])->save($param);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }elseif($data['act']=='del'){
            $id = input('cat_id/d', 0);
            $res=Db::name('template_class')->delete($id);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => 1]);
            }
        }
        $this->ajaxReturn(['status' => 0, 'msg' => '失败','result' => 0]);
    }

    //我的模板展示(用户)
    public function templateList(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    //行业模板展示(系统模板)
    public function templateList2(){
		header("Content-type: text/html; charset=utf-8");
exit("请联系客服查看是否支持此功能");
    }

    public function get_style(){
        $industry_id = input('post.industry_id/d');//行业id
        $style_id = input('post.style_id/d');//风格id
        //所有行业名称
        $industry_list = Db::name('template_class')->field('id as industry_id,name')->where('parent_id=0')->order('sort_order desc')->select();//行业
        if(!$industry_id){
            $industry_id = $industry_list[0]['industry_id'];
        }
        // 所有风格名称
        $style_list = Db::name('template_class')->where('parent_id',$industry_id)->field('id as style_id,name')->order('sort_order desc')->select();
        // 风格展示条件
        $where['industry_id'] = $industry_id;
        if($style_id){
            $where['style_id'] = $style_id;
        }
        // 所有风格展示
        $template_list = Db::name('industry_template')->where($where)->order('id DESC')->select();
        $result['industry_id'] = $industry_id;
        $result['style_id'] = $style_id;
        $result['industry_list'] = $industry_list;
        $result['style_list'] = $style_list;
        $result['template_list'] = $template_list;
        //halt($result);
        $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => $result]);
    }
    public function select_style(){
        $industry_id = input('post.industry_id/d');//行业id
        // 所有风格名称
        $style_list = Db::name('template_class')->where('parent_id',$industry_id)->field('id ,name')->order('sort_order desc')->select();
        $this->ajaxReturn(['status' => 1, 'msg' => '成功','result' => $style_list]);
    }

    public function add_template(){
        //$data=I('post.');
        //halt($data);
        $id=I('post.id');
        $data=Db::name('industry_template')->where('id',$id)->find();
        $data['add_time']=time();
        $data['type']=1;
        unset($data['id']);
        $re = Db::name('mobile_template')->where('style_id', $data['style_id'])->find();
        if($re){
            $this->ajaxReturn(['status' => -1, 'msg' => '该模板已加入！']);
        }else{
            $res=Db::name('mobile_template')->add($data);
            if($res){
                $this->ajaxReturn(['status' => 1, 'msg' => '成功']);
            }
        }
        $this->ajaxReturn(['status' => -1, 'msg' => '模板加入失败']);

    }

    public function creatimg(){
        return $this->fetch();
    }

    /**
     * 删除，多余的组件数据
     * http://192.168.0.146:1001/Admin/Block/del_timeid?id=157&timeid=1
     */
    public function del_timeid(){
        $id = input('id/d');
        $timeid = input('timeid');
        if(!$id || !$timeid){
            echo 'id or timeid empty';
        }else{
            $data=Db::name('mobile_template')->where('id',$id)->find();
            if($data){
                echo 'find id=',$id;dump($data);
                $block_info = htmlspecialchars_decode($data['block_info']);
                $arr = json_decode($block_info,256);
                $flag = false;
                foreach($arr as $k=>$v){
                    if($k == $timeid){
                        unset($arr[$k]);
                        echo 'delete ',$timeid,'<br>';
                        $flag = true;
                    }
                }
                //dump($arr);
                $str = htmlspecialchars(json_encode($arr));
                $str =str_replace('\\/','/',$str);
                //dump($str);
                if($flag){
                    $save_data['block_info'] = $str;
                    $re = Db::name('mobile_template')->where('id',$id)->save($save_data);
                    echo 'save info:';dump($re);
                }else{
                    echo 'not find timeid=',$timeid;
                }
            }else{
                echo 'not find id=',$id;
            }
        }
    }
}
?>