<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:52:"./application/admin/view/promotion\select_goods.html";i:1540260088;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-capable" content="yes">
<!-- Apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link href="/public/static/css/main.css" rel="stylesheet" type="text/css">
<link href="/public/static/css/page.css" rel="stylesheet" type="text/css">
<link href="/public/static/font/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="/public/static/font/css/font-awesome-ie7.min.css">
<![endif]-->
<link href="/public/static/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/public/static/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<style type="text/css">html, body { overflow: visible;}</style>
<script type="text/javascript" src="/public/static/js/jquery.js"></script>
<script type="text/javascript" src="/public/static/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="/public/static/js/layer/layer.js"></script><!-- 弹窗js 参考文档 http://layer.layui.com/-->
<script type="text/javascript" src="/public/static/js/admin.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="/public/static/js/common.js"></script>
<script type="text/javascript" src="/public/static/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/public/static/js/jquery.mousewheel.js"></script>
<script src="/public/js/myFormValidate.js"></script>
<script src="/public/js/myAjax2.js"></script>
<script src="/public/js/global.js"></script>
    <script type="text/javascript">
    function delfunc(obj){
    	layer.confirm('确认删除？', {
    		  btn: ['确定','取消'] //按钮
    		}, function(){
    		    // 确定
   				$.ajax({
   					type : 'post',
   					url : $(obj).attr('data-url'),
   					data : {act:'del',del_id:$(obj).attr('data-id')},
   					dataType : 'json',
   					success : function(data){
						layer.closeAll();
   						if(data.status==1){
                            layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                location.href = '';
//                                $(obj).parent().parent().parent().remove();
                            });
   						}else{
   							layer.msg(data, {icon: 2,time: 2000});
   						}
   					}
   				})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    function selectAll(name,obj){
    	$('input[name*='+name+']').prop('checked', $(obj).checked);
    }

    function get_help(obj){

		window.open("http://www.tp-shop.cn/");
		return false;

        layer.open({
            type: 2,
            title: '帮助手册',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: $(obj).attr('data-url'),
        });
    }

    function delAll(obj,name){
    	var a = [];
    	$('input[name*='+name+']').each(function(i,o){
    		if($(o).is(':checked')){
    			a.push($(o).val());
    		}
    	})
    	if(a.length == 0){
    		layer.alert('请选择删除项', {icon: 2});
    		return;
    	}
    	layer.confirm('确认删除？', {btn: ['确定','取消'] }, function(){
    			$.ajax({
    				type : 'get',
    				url : $(obj).attr('data-url'),
    				data : {act:'del',del_id:a},
    				dataType : 'json',
    				success : function(data){
						layer.closeAll();
    					if(data == 1){
    						layer.msg('操作成功', {icon: 1});
    						$('input[name*='+name+']').each(function(i,o){
    							if($(o).is(':checked')){
    								$(o).parent().parent().remove();
    							}
    						})
    					}else{
    						layer.msg(data, {icon: 2,time: 2000});
    					}
    				}
    			})
    		}, function(index){
    			layer.close(index);
    			return false;// 取消
    		}
    	);
    }

    /**
     * 全选
     * @param obj
     */
    function checkAllSign(obj){
        $(obj).toggleClass('trSelected');
        if($(obj).hasClass('trSelected')){
            $('#flexigrid > table>tbody >tr').addClass('trSelected');
        }else{
            $('#flexigrid > table>tbody >tr').removeClass('trSelected');
        }
    }
    /**
     * 批量公共操作（删，改）
     * @returns {boolean}
     */
    function publicHandleAll(type){
        var ids = '';
        $('#flexigrid .trSelected').each(function(i,o){
//            ids.push($(o).data('id'));
            ids += $(o).data('id')+',';
        });
        if(ids == ''){
            layer.msg('至少选择一项', {icon: 2, time: 2000});
            return false;
        }
        publicHandle(ids,type); //调用删除函数
    }
    /**
     * 公共操作（删，改）
     * @param type
     * @returns {boolean}
     */
    function publicHandle(ids,handle_type){
        layer.confirm('确认当前操作？', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    // 确定
                    $.ajax({
                        url: $('#flexigrid').data('url'),
                        type:'post',
                        data:{ids:ids,type:handle_type},
                        dataType:'JSON',
                        success: function (data) {
                            layer.closeAll();
                            if (data.status == 1){
                                layer.msg(data.msg, {icon: 1, time: 2000},function(){
                                    location.href = data.url;
                                });
                            }else{
                                layer.msg(data.msg, {icon: 2, time: 2000});
                            }
                        }
                    });
                }, function (index) {
                    layer.close(index);
                }
        );
    }
</script>  

</head>
<style>
    .te_le .dataTables_paginate{float: left;}
    .bot{float: right;padding: 15px 0}
</style>
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div class="page" style="padding: 0px 1% 0 1%;">
    <!-- 操作说明 -->
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>商品列表</h3>
                <h5>(共<?php echo $page->totalRows; ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <form class="navbar-form form-inline" id="search-form2" action="<?php echo U('Promotion/search_goods',array('tpl'=>'select_goods')); ?>" method="get">
                <input name="prom_id" type="hidden" value="<?php echo \think\Request::instance()->param('prom_id'); ?>">
                <input name="prom_type" type="hidden" value="<?php echo \think\Request::instance()->param('prom_type'); ?>">
                <div class="sDiv">
                    <div class="sDiv2" style="margin-right: 10px;border:none;">
                        <select name="cat_id" id="cat_id">
                            <option value="">所有分类</option>
                            <?php if(is_array($categoryList) || $categoryList instanceof \think\Collection || $categoryList instanceof \think\Paginator): if( count($categoryList)==0 ) : echo "" ;else: foreach($categoryList as $k=>$v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php if($v[id] == \think\Request::instance()->param('cat_id')): ?>selected<?php endif; ?>><?php echo $v['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <div class="sDiv2" style="margin-right: 10px;border:none;">
                        <select name="brand_id" id="brand_id">
                            <option value="">所有品牌</option>
                            <?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>
                                <option value="<?php echo $v['id']; ?>" <?php if($v[id] == \think\Request::instance()->param('brand_id')): ?>selected<?php endif; ?>><?php echo $v['name']; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <div class="sDiv2" style="margin-right: 10px;border:none;">
                        <select name="intro">
                            <option value="0">全部</option>
                            <option value="is_new">新品</option>
                            <option value="is_recommend">推荐</option>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <!--<select name="status" class="select">-->
                            <!--<option value="">活动状态</option>-->
                        <!--</select>-->
                        <input size="30" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" class="qsbox" placeholder="商品名称或者关键词" type="text">
                        <input class="btn" value="搜索" type="submit">
                    </div>
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th abbr="article_title" axis="col3" class="" align="left">
                            <div style="text-align: left; width: 50px;" class="">选择</div>
                        </th>
                        <th abbr="article_time" axis="col6" class="" align="left">
                            <div style="text-align: left; width: 600px;" class="">商品名称</div>
                        </th>
                        <th abbr="ac_id" axis="col4" class="" align="left">
                            <div style="text-align: center; width: 80px;" class="">价格</div>
                        </th>
                        <th abbr="article_show" axis="col5" class="" align="center">
                            <div style="text-align: center; width: 80px;" class="">库存</div>
                        </th>
                        <th axis="col1" class=""  align="center">
                            <div style="text-align: center; width: 80px;">操作</div>
                        </th>
                        <th style="width:100%" axis="col7">
                            <div></div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
                <table>
                    <tbody>
                    <?php if(is_array($goodsList) || $goodsList instanceof \think\Collection || $goodsList instanceof \think\Paginator): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="" align="left">
                                <div style="text-align: left; width: 50px;">
                                    <input type="radio" name="goods_id" data-img="<?php echo goods_thum_images($list['goods_id'],160,160); ?>"
                                           data-id="<?php echo $list['goods_id']; ?>" data-name="<?php echo $list['goods_name']; ?>" data-count="<?php echo $list['store_count']; ?>" data-cost-price="<?php echo $list['cost_price']; ?>"
                                           data-price="<?php echo $list['shop_price']; ?>" <?php if($list['goods_id'] == \think\Request::instance()->param('goods_id')): ?>checked='checked'<?php endif; ?>/>
                                </div>
                            </td>
                            <td class="" align="left">
                                <div style="text-align: left; width: 600px;"><?php echo $list['goods_name']; ?></div>
                            </td>
                            <td class="" align="left">
                                <div style="text-align: center; width: 80px;"><?php echo $list['shop_price']; ?></div>
                            </td>
                            <td class="" align="left">
                                <div style="text-align: center; width: 80px;"><?php echo $list['store_count']; ?></div>
                            </td>
                            <td class="" align="center">
                                <div style="text-align: center; width: 80px; ">
                                    <a class="btn red" target="_blank" href="<?php echo U('Home/Goods/goodsInfo',['id'=>$list['goods_id']]); ?>"><i class="fa fa-search"></i>查看</a>
                                </div>
                            </td>
                            <td class="" style="width: 100%;" align="">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                        <?php if(!(empty($list[specGoodsPrice]) || (($list[specGoodsPrice] instanceof \think\Collection || $list[specGoodsPrice] instanceof \think\Paginator ) && $list[specGoodsPrice]->isEmpty()))): ?>
                            <tr style="display: none" id="spec_goods_id_<?php echo $list['goods_id']; ?>">
                                <td></td>
                                <td class="tl" colspan="5">
                                    <div style="height: auto;white-space:normal;">
                                        <?php if(is_array($list[specGoodsPrice]) || $list[specGoodsPrice] instanceof \think\Collection || $list[specGoodsPrice] instanceof \think\Paginator): $i = 0; $__LIST__ = $list[specGoodsPrice];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec): $mod = ($i % 2 );++$i;?>
                                            <a class="<?php if($spec[prom_id] == 0): ?>ncap-btn specBtn<?php else: ?>ncap-btn-dis<?php endif; ?>" data-item-id="<?php echo $spec['item_id']; ?>"
                                               data-key-name="<?php echo $spec['key_name']; ?>" data-store-count="<?php echo $spec['store_count']; ?>" data-price="<?php echo $spec['price']; ?>" data-spec-img="<?php echo $spec['spec_img']; ?>"
                                               title="<?php echo $spec['key_name']; ?>" ><?php echo $spec['key_name']; ?></a>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->
        <div class="te_le">
            <?php echo $page->show(); ?>
        </div>
        <div class="bot"><a onclick="select_goods();" class="ncap-btn-big ncap-btn-green">确认提交</a></div>
    </div>
</div>
<script>
    // 点击刷新数据
    $('.fa-refresh').click(function(){
        location.href = location.href;
    });
    $(document).ready(function(){
        $("input[type='radio']:checked").each(function(i,o){
            var goods_id = $(this).data('id');
            $('#spec_goods_id_'+goods_id).show();
        })
    });
    //商品对象
    function GoodsItem(goods_id, goods_name, store_count, goods_price, cost_price, goods_image,spec) {
        this.goods_id = goods_id;
        this.goods_name = goods_name;
        this.store_count = store_count;
        this.goods_price = goods_price;
        this.cost_price = cost_price;
        this.goods_image = goods_image;
        this.spec = spec;
    }
    //商品对象
    function GoodsSpecItem(item_id, key_name, store_count, price ,spec_img) {
        this.item_id = item_id;
        this.key_name = key_name;
        this.store_count = store_count;
        this.price = price;
        this.spec_img = spec_img;
    }
    //单选框选中事件
    $(function () {
        $(document).on("click", '#flexigrid input', function (e) {
            var goods_id = $(this).data('id');
            if($(this).is(':checked')){
                $('#spec_goods_id_'+goods_id).show();
            }else{
                $('#spec_goods_id_'+goods_id).hide();
            }
        })
    })
    //规格按钮点击事件
    $(function () {
        $(document).on("click", '.specBtn', function (e) {
            $(this).parent().find('a').css("color","#777").removeClass('ncap-btn-green');
            $(this).css("color","#FFF").addClass('ncap-btn-green');
        })
    })

    function select_goods()
    {
        var input = $("input[type='radio']:checked");
        if (input.length == 0) {
            layer.alert('请选择商品', {icon: 2}); //alert('请选择商品');
            return false;
        }
        var goods_id = input.data('id');
        var spec = $('#spec_goods_id_'+goods_id);
        var goodsItem = null;
        if(spec.length == 0){
            goodsItem = new GoodsItem(input.data('id'), input.data('name'),input.data('count'), input.data('price'),input.data('cost-price'),input.data('img'), null);
        }else{
            var spec_a = spec.find('.ncap-btn-green');
            if(spec_a.length == 0){
                layer.alert('请选择要参与活动的商品规格', {icon: 2}); //alert('请选择商品');
            }else{
                var goodsSpecItem = new GoodsSpecItem(spec_a.data('item-id'),spec_a.data('key-name'),spec_a.data('store-count'),spec_a.data('price'),spec_a.data('spec-img'));
                goodsItem = new GoodsItem(input.data('id'), input.data('name'), input.data('count'),input.data('price'),input.data('cost-price'), input.data('img'), goodsSpecItem);
            }
        }
        window.parent.call_back(goodsItem);
    }
</script>
</body>
</html>