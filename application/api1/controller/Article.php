<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/26
 * Time: 20:02
 */

namespace app\api\controller;


use think\Db;
use think\Page;

class Article extends Base
{
    /**
     * 文章内列表页
     */
    public function articleList(){
        $article_cat = M('ArticleCat')->field("cat_id,cat_name")->select();
        $article = M('Article');
        $cat_id = I('cat_id/d',0);
        if(!$cat_id){
            $cat_id = get_arr_column($article_cat,"cat_id");
        }
        $count = $article->where(['cat_id'=>["in", $cat_id]])->count();
        $Page = new Page($count, 10);
        $article_list = $article->where(['cat_id'=>["in", $cat_id]])->field("article_id,title,content,publish_time,description,thumb")
            ->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($article_list as $k => $v){
            $article_list[$k]['thumb'] = url_add_domain($v['thumb']);
        }
        $data = [
            'article_cat' => $article_cat,
            'article_list' => $article_list
        ];
        return returnOk($data);
    }
    /**
     * 文章内容页
     */
    public function detail(){
        $article_id = I('article_id/d',1);
        $article = Db::name('article')->where("article_id", $article_id)->find();
        if($article){
            $parent = Db::name('article_cat')->where("cat_id",$article['cat_id'])->find();
            $this->assign('cat_name',$parent['cat_name']);
            $this->assign('article',$article);
        }
        return $this->fetch();
    }

}