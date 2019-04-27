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
 * Author: IT宇宙人
 * Date: 2015-09-09
 */

namespace app\admin\logic;

use think\Model;
use think\db;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class GoodsLogic extends Model
{

    /**
     * 获得指定分类下的子分类的数组     
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    public function goods_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0)
    {
                global $goods_category, $goods_category2;                
                $sql = "SELECT * FROM  __PREFIX__goods_category ORDER BY parent_id , sort_order ASC";
                $goods_category = DB::query($sql);
                $goods_category = convert_arr_key($goods_category, 'id');
                
                foreach ($goods_category AS $key => $value)
                {
                    if($value['level'] == 1)
                        $this->get_cat_tree($value['id']);                                
                }
                return $goods_category2;               
    }
    
    /**
     * 获取指定id下的 所有分类      
     * @global type $goods_category 所有商品分类
     * @param type $id 当前显示的 菜单id
     * @return 返回数组 Description
     */
    public function get_cat_tree($id)
    {
        global $goods_category, $goods_category2;          
        $goods_category2[$id] = $goods_category[$id];    
        foreach ($goods_category AS $key => $value){
             if($value['parent_id'] == $id)
             {                 
                $this->get_cat_tree($value['id']);  
                $goods_category2[$id]['have_son'] = 1; // 还有下级
             }
        }               
    }
    
    
    /**
     * 移除指定$parent_id_path 分类以及下的所有分类
     * @global type $cat_list 所有商品分类
     * @param type $parent_id_path 指定的id
     * @return 返回数组 Description
     */
    public function remove_cat($cat_list,$parent_id_path)
    {
        foreach ($cat_list AS $key => $value){
             if(strstr($value['parent_id_path'],$parent_id_path))
             {                 
                 unset($cat_list[$value['id']]); 
             }
        }     
        return $cat_list;
    }
    
    /**
     * 改变或者添加分类时 需要修改他下面的 parent_id_path  和 level 
     * @global type $cat_list 所有商品分类
     * @param type $parent_id_path 指定的id
     * @return 返回数组 Description
     */
    public function refresh_cat($id)
    {            
        $GoodsCategory = M("GoodsCategory"); // 实例化User对象
        $cat = $GoodsCategory->where("id = $id")->find(); // 找出他自己
        // 刚新增的分类先把它的值重置一下
        if($cat['parent_id_path'] == '')
        {
            ($cat['parent_id'] == 0) && Db::execute("UPDATE __PREFIX__goods_category set  parent_id_path = '0_$id', level = 1 where id = $id"); // 如果是一级分类               
            Db::execute("UPDATE __PREFIX__goods_category AS a ,__PREFIX__goods_category AS b SET a.parent_id_path = CONCAT_WS('_',b.parent_id_path,'$id'),a.level = (b.level+1) WHERE a.parent_id=b.id AND a.id = $id");                
            $cat = $GoodsCategory->where("id = $id")->find(); // 从新找出他自己
        }        
        
        if($cat['parent_id'] == 0) //有可能是顶级分类 他没有老爸
        {
            $parent_cat['parent_id_path'] =   '0';   
            $parent_cat['level'] = 0;
        }
        else{
            $parent_cat = $GoodsCategory->where("id = {$cat['parent_id']}")->find(); // 找出他老爸的parent_id_path
        }        
        $replace_level = $cat['level'] - ($parent_cat['level'] + 1); // 看看他 相比原来的等级 升级了多少  ($parent_cat['level'] + 1) 他老爸等级加一 就是他现在要改的等级
        $replace_str = $parent_cat['parent_id_path'].'_'.$id;                
        Db::execute("UPDATE `__PREFIX__goods_category` SET parent_id_path = REPLACE(parent_id_path,'{$cat['parent_id_path']}','$replace_str'), level = (level - $replace_level) WHERE  parent_id_path LIKE '{$cat['parent_id_path']}%'");        
    }

    /**
     *  给指定商品添加属性 或修改属性 更新到 tp_goods_attr
     * @param int $goods_id  商品id
     * @param int $goods_type  商品类型id
     */
    public function saveGoodsAttr($goods_id,$goods_type)
    {  
        $GoodsAttr = M('GoodsAttr');
        //$Goods = M("Goods");
                
         // 属性类型被更改了 就先删除以前的属性类型 或者没有属性 则删除        
        if($goods_type == 0)  
        {
            $GoodsAttr->where('goods_id = '.$goods_id)->delete(); 
            return;
        }
        
            $GoodsAttrList = $GoodsAttr->where('goods_id = '.$goods_id)->select();
            
            $old_goods_attr = array(); // 数据库中的的属性  以 attr_id _ 和值的 组合为键名
            foreach($GoodsAttrList as $k => $v)
            {                
                $old_goods_attr[$v['attr_id'].'_'.$v['attr_value']] = $v;
            }            
                              
            // post 提交的属性  以 attr_id _ 和值的 组合为键名    
            $post_goods_attr = array();
            $post = I("post.");
            foreach($post as $k => $v)
            {
                $attr_id = str_replace('attr_','',$k);
                if(!strstr($k, 'attr_') || strstr($k, 'attr_price_'))
                   continue;                                 
               foreach ($v as $k2 => $v2)
               {                      
                   $v2 = str_replace('_', '', $v2); // 替换特殊字符
                   $v2 = str_replace('@', '', $v2); // 替换特殊字符
                   $v2 = trim($v2);
                   
                   if(empty($v2))
                       continue;
                   
                   $tmp_key = $attr_id."_".$v2;
                   $post_attr_price = I("post.attr_price_{$attr_id}");
                   $attr_price = $post_attr_price[$k2]; 
                   $attr_price = $attr_price ? $attr_price : 0;
                   if(array_key_exists($tmp_key , $old_goods_attr)) // 如果这个属性 原来就存在
                   {   
                       if($old_goods_attr[$tmp_key]['attr_price'] != $attr_price) // 并且价格不一样 就做更新处理
                       {                       
                            $goods_attr_id = $old_goods_attr[$tmp_key]['goods_attr_id'];                         
                            $GoodsAttr->where("goods_attr_id = $goods_attr_id")->save(array('attr_price'=>$attr_price));                       
                       }
                   }
                   else // 否则这个属性 数据库中不存在 说明要做删除操作
                   {
                       $GoodsAttr->add(array('goods_id'=>$goods_id,'attr_id'=>$attr_id,'attr_value'=>$v2,'attr_price'=>$attr_price));                       
                   }
                   unset($old_goods_attr[$tmp_key]);
               }
                
            }     
            // 没有被 unset($old_goods_attr[$tmp_key]); 掉是 说明 数据库中存在 表单中没有提交过来则要删除操作
            foreach($old_goods_attr as $k => $v)
            {                
               $GoodsAttr->where('goods_attr_id = '.$v['goods_attr_id'])->delete(); // 
            }                       

    }
    
    /**
     * 获取 规格的 笛卡尔积
     * @param $goods_id 商品 id     
     * @param $spec_arr 笛卡尔积
     * @return string 返回表格字符串
     */
    public function getSpecInput($goods_id, $spec_arr)
    {
        // <input name="item[2_4_7][price]" value="100" /><input name="item[2_4_7][name]" value="蓝色_S_长袖" />        
        /*$spec_arr = array(         
            20 => array('7','8','9'),
            10=>array('1','2'),
            1 => array('3','4'),
            
        );  */        
        // 排序
        foreach ($spec_arr as $k => $v)
        {
            $spec_arr_sort[$k] = count($v);
        }
        asort($spec_arr_sort);        
        foreach ($spec_arr_sort as $key =>$val)
        {
            $spec_arr2[$key] = $spec_arr[$key];
        }
     
        
         $clo_name = array_keys($spec_arr2);         
         $spec_arr2 = combineDika($spec_arr2); //  获取 规格的 笛卡尔积                 
                       
         $spec = M('Spec')->getField('id,name'); // 规格表
         $specItem = M('SpecItem')->getField('id,item,spec_id');//规格项
         $keySpecGoodsPrice = M('SpecGoodsPrice')->where('goods_id = '.$goods_id)->getField('key,key_name,price,store_count,bar_code,sku,cost_price,commission');//规格项
                          
       $str = "<table class='table table-bordered' id='spec_input_tab'>";
       $str .="<tr>";
        $str_fill = "<tr>";
       // 显示第一行的数据
       foreach ($clo_name as $k => $v) 
       {
           $str .=" <td><b>{$spec[$v]}</b></td>";
           $str_fill .=" <td><b></b></td>";
       }
        $str .="<td><b>购买价</b></td>
               <td><b>成本价</b></td>
               <td><b>佣金</b></td>
               <td><b>库存</b></td>
               <td><b>SKU</b></td>
               <td><b>操作</b></td>
             </tr>";
        if(count($spec_arr2) > 0){
            $str_fill .='<td><input id="item_price" value="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)" onpaste="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)"></td>
               <td><input id="item_cost_price" value="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)" onpaste="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)"></td>
               <td><input id="item_commission" value="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)" onpaste="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)"></td>
               <td><input id="item_store_count" value="0" onkeyup="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)" onpaste="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)"></td>
               <td><input id="item_sku" value="" onkeyup="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)" onpaste="this.value=this.value.replace(/[^\d.]/g,&quot;&quot;)"></td>
               <td><button id="item_fill" type="button" class="btn btn-success">批量填充</button></td>
             </tr>';
            $str .= $str_fill;
        }
       // 显示第二行开始
       foreach ($spec_arr2 as $k => $v) 
       {
            $str .="<tr>";
            $item_key_name = array();
            foreach($v as $k2 => $v2)
            {
                $str .="<td>{$specItem[$v2][item]}</td>";
                $item_key_name[$v2] = $spec[$specItem[$v2]['spec_id']].':'.$specItem[$v2]['item'];
            }   
            ksort($item_key_name);            
            $item_key = implode('_', array_keys($item_key_name));
            $item_name = implode(' ', $item_key_name);
            
			$keySpecGoodsPrice[$item_key][price] ? false : $keySpecGoodsPrice[$item_key][price] = 0; // 价格默认为0
			$keySpecGoodsPrice[$item_key][store_count] ? false : $keySpecGoodsPrice[$item_key][store_count] = 0; //库存默认为0
			$keySpecGoodsPrice[$item_key][cost_price] ? false : $keySpecGoodsPrice[$item_key][cost_price] = 0; //成本价默认为0
			$keySpecGoodsPrice[$item_key][commission] ? false : $keySpecGoodsPrice[$item_key][commission] = 0; //佣金默认为0
            $str .="<td><input name='item[$item_key][price]' value='{$keySpecGoodsPrice[$item_key][price]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
           $str .="<td><input name='item[$item_key][cost_price]' value='{$keySpecGoodsPrice[$item_key][cost_price]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
           $str .="<td><input name='item[$item_key][commission]' value='{$keySpecGoodsPrice[$item_key][commission]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")' /></td>";
            $str .="<td><input name='item[$item_key][store_count]' value='{$keySpecGoodsPrice[$item_key][store_count]}' onkeyup='this.value=this.value.replace(/[^\d.]/g,\"\")' onpaste='this.value=this.value.replace(/[^\d.]/g,\"\")'/></td>";            
            $str .="<td><input name='item[$item_key][sku]' value='{$keySpecGoodsPrice[$item_key][sku]}' /><input type='hidden' name='item[$item_key][key_name]' value='$item_name' /></td>";
            $str .="<td><button type='button' class='btn btn-default delete_item'>无效</button></td>";
            $str .="</tr>";
       }
        $str .= "</table>";
       return $str;   
    }
    
    /**
     *  获取选中的下拉框
     * @param type $cat_id
     */
    function find_parent_cat($cat_id)
    {
        if($cat_id == null) 
            return array();
        
        $cat_list =  M('goods_category')->getField('id,parent_id,level');
        $cat_level_arr[$cat_list[$cat_id]['level']] = $cat_id;

        // 找出他老爸
        $parent_id = $cat_list[$cat_id]['parent_id'];
        if($parent_id > 0)
             $cat_level_arr[$cat_list[$parent_id]['level']] = $parent_id;
        // 找出他爷爷
        $grandpa_id = $cat_list[$parent_id]['parent_id'];
        if($grandpa_id > 0)
             $cat_level_arr[$cat_list[$grandpa_id]['level']] = $grandpa_id;
        
        // 建议最多分 3级, 不要继续往下分太多级
        // 找出他祖父
        $grandfather_id = $cat_list[$grandpa_id]['parent_id'];
        if($grandfather_id > 0)
             $cat_level_arr[$cat_list[$grandfather_id]['level']] = $grandfather_id;
        
        return $cat_level_arr;      
    }

    /**
     * 获取排好序的品牌列表
     * @param int $cat_id
     * @return mixed
     */
    function getSortBrands($cat_id=0)
    {
        $brandList = S('getSortBrands');
        if(!empty($brandList)){
            return $brandList;
        }
        $brand_where=[];
        if ($cat_id){
            $brand_where['cat_id|parent_cat_id'] = $cat_id;  //查找分类下的品牌，没值就查找全部
        }
        $brandList =  M("Brand")->cache(true)->where($brand_where)->select();
        $brandIdArr =  M("Brand")->cache(true)->where($brand_where)->where("name in (select `name` from `".C('database.prefix')."brand` group by name having COUNT(id) > 1)")->getField('id,cat_id');
        $goodsCategoryArr = M('goodsCategory')->cache(true)->where("level = 1")->getField('id,name');
        $nameList = array();
        foreach($brandList as $k => $v)
        {

            $name = getFirstCharter($v['name']) .'  --   '. $v['name']; // 前面加上拼音首字母
            if(array_key_exists($v[id],$brandIdArr) && $v[cat_id]) // 如果有双重品牌的 则加上分类名称
                    $name .= ' ( '. $goodsCategoryArr[$v[cat_id]] . ' ) ';

             $nameList[] = $v['name'] = $name;
             $brandList[$k] = $v;
        }
        array_multisort($nameList,SORT_STRING,SORT_ASC,$brandList);

        return $brandList;
    }
    /**
     * 获取地址
     * @return array
     */
    function getRegionList()
    {
        $res = S('getRegionList');
        if(!empty($res))
            return $res;
        $parent_region = M('region')->field('id,name')->where(array('level'=>1))->cache(true)->select();
        $ip_location = array();
        $city_location = array();
        foreach($parent_region as $key=>$val){
            $c = M('region')->field('id,name')->where(array('parent_id'=>$parent_region[$key]['id']))->order('id asc')->cache(true)->select();
            $ip_location[$parent_region[$key]['name']] = array('id'=>$parent_region[$key]['id'],'root'=>0,'djd'=>1,'c'=>$c[0]['id']);
            $city_location[$parent_region[$key]['id']] = $c;
        }
        $res = array(
            'ip_location'=>$ip_location,
            'city_location'=>$city_location
        );
        S('getRegionList',$res);
        return $res;
    }
    function getAreaList()
    {
        $res = S('getAreaList');
        if(!empty($res))
            return $res;
        $parent_region = Db::name('region')->field('id,name')->where(array('level'=>2))->cache(true)->select();
        $res = [];
        foreach($parent_region as $key=>$val){
            $res[$val['id']] = Db::name('region')->field('id,name')->where(array('parent_id'=>$parent_region[$key]['id']))->order('id asc')->cache(true)->select();
        }
        S('getAreaList',$res);
        return $res;
    }

    /**
     * 获取排好序的分类列表
     * @param string $level  //需要获取第几级分类
     * @return mixed
     */
    function getSortCategory()
    {
        $categoryList = S('categoryList');
        if($categoryList)
        {
            return $categoryList;
        }
        $categoryList =  M("GoodsCategory")->cache(true)->getField('id,name,parent_id,level');
        $nameList = array();
        foreach($categoryList as $k => $v)
        {
            $name = getFirstCharter($v['name']) .' '. $v['name']; // 前面加上拼音首字母
            $nameList[] = $v['name'] = $name;
            $categoryList[$k] = $v;
        }
       array_multisort($nameList,SORT_STRING,SORT_ASC,$categoryList);

        S('categoryList',$categoryList);
        return $categoryList;
    }

    
    /**
     * @方法：将数据格式转换成树形结构数组
     * @param array $items 要进行转换的数组
     * return array $items 转换完成的数组
     */
    function getCatTree(Array $items) {
    	$tree = array();
    	foreach ($items as $item)
    	if (isset($items[$item['parent_id']])) {
    		$items[$item['parent_id']]['son'][] = &$items[$item['id']];
    	} else {
    		$tree[] = &$items[$item['id']];
    	}
    	return $tree;
    }
    
    /**
     * * 将树形结构数组输出
     * @param $items    要输出的数组
     * @param int $deep 顶级父节点id
     * @param int $type_id 已选中项
     * @return string
     */
    function exportTree($items, $deep = 0, $type_id = 0){
    	foreach ($items as $item) {
    		$select .= '<option value="' . $item['id'] . '" ';
    		$select .= ($type_id == $item['id']) ? 'selected="selected">' : '>';
    		if ($deep > 0) $select .= str_repeat('&nbsp;', $deep*4);
    		$select .= '&nbsp;&nbsp;'.htmlspecialchars(addslashes($item['name'])).'</option>';
    		if (!empty($item['son'])){
    			$select .= $this->exportTree($item['son'], $deep+1,$type_id);
    		}
    	}
    	return $select;
    }
    /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $goods_id 商品id
     */
    public function afterSave($goods_id)
    {
        $item_img = I('item_img/a');
        // 商品货号
        $goods_sn = "TP".str_pad($goods_id,7,"0",STR_PAD_LEFT);
        Db::name('goods')->where("goods_id = $goods_id and goods_sn = ''")->save(array("goods_sn"=>$goods_sn)); // 根据条件更新记录

        // 商品图片相册  图册
        $goods_images = I('goods_images/a');
        if(count($goods_images) > 1)
        {
            array_pop($goods_images); // 弹出最后一个
            $goodsImagesArr = M('GoodsImages')->where("goods_id = $goods_id")->getField('img_id,image_url'); // 查出所有已经存在的图片

            // 删除图片
            foreach($goodsImagesArr as $key => $val)
            {
                if(!in_array($val, $goods_images)) M('GoodsImages')->where("img_id = {$key}")->delete();
            }
            // 添加图片
            foreach($goods_images as $key => $val)
            {
                if($val == null)  continue;
                if(!in_array($val, $goodsImagesArr))
                {
                    $data = array('goods_id' => $goods_id,'image_url' => $val);
                    M("GoodsImages")->insert($data); // 实例化User对象
                }
            }
        }
        // 查看主图是否已经存在相册中
        $original_img = I('original_img');
        $c = M('GoodsImages')->where("goods_id = $goods_id and image_url = '{$original_img}'")->count();

        //@modify by wangqh fix:删除商品详情的图片(相册图刚好是主图时)删除的图片仍然在相册中显示. 如果主图存物理图片存在才添加到相册 @{
        $deal_orignal_img = str_replace('../','',$original_img);
        $deal_orignal_img= trim($deal_orignal_img,'.');
        $deal_orignal_img= trim($deal_orignal_img,'/');
        if($c == 0 && $original_img && file_exists($deal_orignal_img)) //@}
        {
            M("GoodsImages")->add(array('goods_id'=>$goods_id,'image_url'=>$original_img));
        }
        delFile(UPLOAD_PATH."goods/thumb/$goods_id"); // 删除缩略图

        // 商品规格价钱处理
        $goods_item = I('item/a'); // 这里没有传market_price
        $eidt_goods_id = I('goods_id',0);
        $market_price = Db::name('goods')->where("goods_id = $goods_id")->value('market_price');
        if ($goods_item) {
            $keyArr = '';//规格key数组
            foreach ($goods_item as $k => $v) {
                $keyArr .= $k.',';
                // 批量添加数据
                $v['price'] = trim($v['price']);
                $v['store_count'] = trim($v['store_count']); // 记录商品总库存
                $v['sku'] = trim($v['sku']);
                $data = [
                    'goods_id' => $goods_id,
                    'key' => $k,
                    'key_name' => $v['key_name'],
                    'price' => $v['price'],
                    'store_count' => $v['store_count'],
                    'sku' => $v['sku'],
                    'cost_price'=>$v['cost_price'],
                    'commission'=>$v['commission'],
                ];
                $specGoodsPrice = Db::name('spec_goods_price')->where(['goods_id' => $data['goods_id'], 'key' => $data['key']])->find();
                if ($item_img) {
                    $spec_key_arr = explode('_', $k);
                    foreach ($item_img as $key => $val) {
                        if (in_array($key, $spec_key_arr)) {
                            $data['spec_img'] = $val;
                            break;
                        }
                    }
                }
                if($specGoodsPrice){
                    Db::name('spec_goods_price')->where(['goods_id' => $goods_id, 'key' => $k])->update($data);
                }else{
                    Db::name('spec_goods_price')->insert($data);
                }

                if(!empty($specGoodsPrice) && $v['store_count'] != $specGoodsPrice['store_count'] && $eidt_goods_id>0){
                    $stock = $v['store_count'] - $specGoodsPrice['store_count'];
                }else{
                    $stock = $v['store_count'];
                }
                //记录库存日志
                update_stock_log(session('admin_id'),$stock,array('goods_id'=>$goods_id,'goods_name'=>I('goods_name'),'spec_key_name'=>$v['key_name']));
                // 修改商品后购物车的商品价格也修改一下
                M('cart')->where("goods_id = $goods_id and spec_key = '$k'")->save(array(
                    'market_price' => $market_price, //$v['market_price'], //市场价
                    'goods_price' => $v['price'], // 本店价
                    'member_goods_price' => $v['price'], // 会员折扣价
                ));
            }
            if($keyArr){
                Db::name('spec_goods_price')->where('goods_id',$goods_id)->whereNotIn('key',$keyArr)->delete();
            }
        }else{
            Db::name('spec_goods_price')->where(['goods_id' => $goods_id])->delete();
        }

        // 商品规格图片处理
        if(I('item_img/a'))
        {
            M('SpecImage')->where("goods_id = $goods_id")->delete(); // 把原来是删除再重新插入
            foreach (I('item_img/a') as $key => $val)
            {
                if($val != ''){
                    M('SpecImage')->insert(array('goods_id'=>$goods_id ,'spec_image_id'=>$key,'src'=>$val));
                }
            }
        }
        refresh_stock($goods_id); // 刷新商品库存
    }
}