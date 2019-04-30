<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./application/admin/view/goods\_goods.html";i:1556418646;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<!--物流配置 css -start-->
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<style>
    ul.group-list {
        width: 96%;
        min-width: 1000px;
        margin: auto 5px;
        list-style: disc outside none;
    }

    .err {
        color: #F00;
        display: none;
    }

    ul.group-list li {
        white-space: nowrap;
        float: left;
        width: 150px;
        height: 25px;
        padding: 3px 5px;
        list-style-type: none;
        list-style-position: outside;
        border: 0px;
        margin: 0px;
    }

    .row .table-bordered td .btn, .row .table-bordered td img {
        vertical-align: middle;
    }

    .row .table-bordered td {
        padding: 8px;
        line-height: 1.42857143;
    }

    .table-bordered {
        width: 100%
    }

    .table-bordered tr td {
        border: 1px solid #f4f4f4;
    }

    .btn-success {
        color: #fff;
        background-color: #449d44;
        border-color: #398439 solid 1px;
    }

    .btn {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: 400;
        line-height: 1.42857143;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
    }

    .col-xs-8 {
        width: 66%;
    }

    .col-xs-4 {
        width: 33%;
    }

    .col-xs-4, .col-xs-8 {
        float: left;
    }

    .row .tab-pane h4 {
        padding: 10px 0;
    }

    .row .tab-pane h4 input {
        vertical-align: middle;
    }

    .table-striped > tbody > tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .ncap-form-default .title {
        border-bottom: 0
    }

    .ncap-form-default dl.row, .ncap-form-all dd.opt {
        /*border-color: #F0F0F0;*/
        border: none;
    }

    .ncap-form-default dl.row:hover, .ncap-form-all dd.opt:hover {
        border: none;
        box-shadow: inherit;
    }

    .addprine {
        display: inline;
    }

    .alisth {
        margin-top: 10px
    }

    .p_plus strong {
        cursor: pointer;
        margin-left: 4px;
    }

    .freight_template {
        font-size: 14px;
        display: inline-block;
        padding: 0px 10px;
    }

    .err {
        color: #F00;
        display: none;
    }
</style>
<!--物流配置 css -end-->
<!--以下是在线编辑器 代码 -->
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/plugins/Ueditor/lang/zh-cn/zh-cn.js"></script>
<!--以上是在线编辑器 代码  end-->
<body style="background-color: #FFF; overflow: auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商品设置</h3>
                <h5>商品基本信息设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <li><a href="javascript:void(0);" data-index='1' class="tab current"><span>通用信息</span></a></li>
                <li><a href="javascript:void(0);" data-index='2' class="tab"><span>商品相册</span></a></li>
                <li><a href="javascript:void(0);" data-index='3' class="tab"><span>商品模型</span></a></li>
                <li><a href="javascript:void(0);" data-index='5' class="tab"><span>积分折扣</span></a></li>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span></div>
        <ul>
            <li>请务必正确填写商品信息</li>
        </ul>
    </div>
    <!--表单数据-->
    <form method="post" id="addEditGoodsForm">
        <input type="hidden" name="goods_id" value="<?php echo $goods['goods_id']; ?>">
        <input type="hidden" name="__token__" value="<?php echo \think\Request::instance()->token(); ?>"/>
        <input type="hidden" value="<?php echo \think\Request::instance()->param('is_distribut'); ?>" name="is_distribut" disabled="disabled"/>
        <input type="hidden" value="<?php echo $level_cat['1']; ?>" name="level_cat_1" disabled="disabled"/>
        <input type="hidden" value="<?php echo $level_cat['2']; ?>" name="level_cat_2" disabled="disabled"/>
        <input type="hidden" value="<?php echo $level_cat['3']; ?>" name="level_cat_3" disabled="disabled"/>
        <input type="hidden" value="<?php echo (isset($goods['brand_id']) && ($goods['brand_id'] !== '')?$goods['brand_id']: 0); ?>" name="goods_brand_id" disabled="disabled"/>
        <!--通用信息-->
        <div class="ncap-form-default tab_div_1">
            <dl class="row">
                <dt class="tit"><em>*</em>
                    <label>商品名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['goods_name']; ?>" name="goods_name" class="input-txt"/>
                    <span class="err" id="err_goods_name"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit"><em>*</em>
                    <label>商品分类</label>
                </dt>
                <dd class="opt">
                    <select name="cat_id" id="cat_id" class="small form-control">
                        <option value="0">请选择商品分类</option>
                        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$v): ?>
                            <option value="<?php echo $v['id']; ?>"
                            <?php if($v['id'] == $level_cat['1']): ?>selected="selected"<?php endif; ?>
                            >
                            <?php echo $v['name']; ?>
                            </option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="cat_id_2" id="cat_id_2"
                            onChange="get_category(this.value,'cat_id_3','0');getCategoryBrandList(this.value)"
                            class="small form-control">
                        <option value="0">请选择商品分类</option>
                    </select>
                    <select name="cat_id_3" id="cat_id_3" class="small form-control">
                        <option value="0">请选择商品分类</option>
                    </select>
                    <span class="err" id="err_cat_id"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>商品简介</label>
                </dt>
                <dd class="opt">
                    <textarea rows="3" cols="80" name="goods_remark" class="input-txt"><?php echo $goods['goods_remark']; ?></textarea>
                    <span id="err_goods_remark" class="err"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>商品货号</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['goods_sn']; ?>" name="goods_sn" class="input-txt"/>
                    <span class="err" id="err_goods_sn"></span>
                    <p class="notic">如果不填会自动生成</p>
                </dd>
            </dl>
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>SPU</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" value="<?php echo $goods['spu']; ?>" name="spu" class="input-txt"/>-->
            <!--<span class="err" id="err_spu"></span>-->
            <!--</dd>-->
            <!--</dl>    -->
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>SKU</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" value="<?php echo $goods['sku']; ?>" name="sku" class="input-txt"/>-->
            <!--<span class="err" id="err_sku"></span>-->
            <!--</dd>-->
            <!--</dl> -->
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label>商品品牌</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<select name="brand_id" id="brand_id" class="small form-control">-->
                        <!--<option value="">所有品牌</option>-->
                        <!--<?php if(is_array($brandList) || $brandList instanceof \think\Collection || $brandList instanceof \think\Paginator): if( count($brandList)==0 ) : echo "" ;else: foreach($brandList as $k=>$v): ?>-->
                            <!--<option value="<?php echo $v['id']; ?>"-->
                            <!--<?php if($v['id'] == $goods['brand_id']): ?>selected="selected"<?php endif; ?>-->
                            <!--&gt;-->
                            <!--<?php echo $v['name']; ?>-->
                            <!--</option>-->
                        <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
                    <!--</select>-->
                <!--</dd>-->
            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label>供应商</label>
                </dt>
                <dd class="opt">
                    <select name="suppliers_id" id="suppliers_id" class="small form-control">
                        <option value="0">不指定供应商属于本店商品</option>
                        <?php if(is_array($suppliersList) || $suppliersList instanceof \think\Collection || $suppliersList instanceof \think\Paginator): if( count($suppliersList)==0 ) : echo "" ;else: foreach($suppliersList as $k=>$v): ?>
                            <option value="<?php echo $v['suppliers_id']; ?>"
                            <?php if($v['suppliers_id'] == $goods['suppliers_id']): ?>selected="selected"<?php endif; ?>
                            >
                            <?php echo $v['suppliers_name']; ?>
                            </option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit"><em>*</em>
                    <label>本店售价</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['shop_price']; ?>" name="shop_price" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <span class="err" id="err_shop_price"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit"><em>*</em>
                    <label>市场价</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['market_price']; ?>" name="market_price" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <span class="err" id="err_market_price"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>成本价</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['cost_price']; ?>" name="cost_price" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <span class="err" id="err_cost_price"></span>
                </dd>
            </dl>
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>佣金</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" value="<?php echo $goods['commission']; ?>" name="commission" class="t_mane"-->
            <!--onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>-->
            <!--<span class="err" id="err_commission"></span>-->

            <!--<p class="notic">用于分销的分成金额</p>-->
            <!--</dd>-->
            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label>图片上传</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                                <a id="img_a" target="_blank" class="nyroModal" rel="gal" href="<?php echo $goods['original_img']; ?>">
                                    <i id="img_i" class="fa fa-picture-o"
                                       onMouseOver="layer.tips('<img src=<?php echo $goods['original_img']; ?>>',this,{tips: [1, '#fff']});"
                                       onMouseOut="layer.closeAll();"></i>
                                </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="imagetext" name="original_img" value="<?php echo $goods['original_img']; ?>"
                                   class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'','goods','img_call_back')" size="30"
                                   title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err"></span>

                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>视频上传</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="type-file-box">
                            <input type="text" id="videotext" name="video" value="<?php echo $goods['video']; ?>"
                                   class="type-file-text">
                            <span id="video_button">
                                <?php if($goods['video']): ?>
                                    <input type="button" onclick="delupload()" value="删除视频" class="type-file-button">
                                    <?php else: ?>
                                    <input type="button" name="button" id="videobutton1" value="选择上传..."
                                           class="type-file-button">
                                    <input class="type-file-file"
                                           onClick="GetUploadify(1,'','goods','video_call_back','Flash')"
                                           size="30" title="点击按钮选择文件并提交表单后上传生效">
                                <?php endif; ?>
                            </span>
                        </span>
                    </div>
                    <span class="err"></span>

                    <p class="notic">请上传视频格式文件</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>音频上传</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="type-file-box">
                            <input type="text" id="musictext" name="music" value="<?php echo $goods['music']; ?>"
                                   class="type-file-text">
                            <span id="music_button">
                                <?php if($goods['video']): ?>
                                    <input type="button" onclick="deluploadformusic()" value="删除音频" class="type-file-button">
                                    <?php else: ?>
                                    <input type="button" name="button" id="musicbutton1" value="选择上传..."
                                           class="type-file-button">
                                    <input class="type-file-file"
                                           onClick="GetUploadify(3,'','goods','music_call_back','Music')"
                                           size="30" title="点击按钮选择文件并提交表单后上传生效">
                                <?php endif; ?>
                            </span>
                        </span>
                    </div>
                    <span class="err"></span>

                    <p class="notic">请上传音频格式文件</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>商品重量</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['weight']; ?>" name="weight" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <span class="err" id="err_weight"></span>

                    <p class="notic"> 务必设置商品重量, 用于计算物流费.以克为单位</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>商品体积</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['volume']; ?>" name="volume" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <span class="err" id="err_volume"></span>

                    <p class="notic"> 务必设置商品体积, 用于计算物流费.以立方米为单位</p>
                </dd>
            </dl>
            <dl class="row goods_shipping">
                <dt class="tit">
                    <label><em>*</em>是否包邮</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label id="is_free_shipping_label_1" for="is_free_shipping1"
                               class="cb-enable <?php if($goods['is_free_shipping'] == 1): ?>selected<?php endif; ?>">是</label>
                        <label id="is_free_shipping_label_0" for="is_free_shipping0"
                               class="cb-disable <?php if($goods['is_free_shipping'] == 0): ?>selected<?php endif; ?>">否</label>
                        <input id="is_free_shipping1" autocomplete="off" class="is_free_shipping"
                               name="is_free_shipping" value="1" type="radio"
                        <?php if($goods[is_free_shipping] == 1): ?> checked="checked"<?php endif; ?>
                        >
                        <input id="is_free_shipping0" autocomplete="off" class="is_free_shipping"
                               name="is_free_shipping" value="0" type="radio"
                        <?php if($goods[is_free_shipping] == 0): ?> checked="checked"<?php endif; ?>
                        >
                        <div class="freight_template" style="display: none;">
                            <span>运费模板</span>
                            <select name="template_id">
                                <option value="0">请选择运费模板</option>
                                <?php if(is_array($freight_template) || $freight_template instanceof \think\Collection || $freight_template instanceof \think\Paginator): $i = 0; $__LIST__ = $freight_template;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$template): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $template['template_id']; ?>"
                                    <?php if($template['template_id'] == $goods['template_id']): ?>
                                        selected="selected"
                                    <?php endif; ?>
                                    ><?php echo $template['template_name']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <?php if(empty($freight_template) || (($freight_template instanceof \think\Collection || $freight_template instanceof \think\Paginator ) && $freight_template->isEmpty())): ?><span style="color: red;">没有可选的运费模板，请前去<a
                                    href="<?php echo U('Freight/index'); ?>" target="_blank">添加</a></span><?php endif; ?>
                        </div>
                    </div>
                    <span class="err" id="err_is_free_shipping"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>总库存</label>
                </dt>
                <dd class="opt">
                    <?php if($goods[goods_id] > 0): ?>
                        <input type="text" value="<?php echo $goods['store_count']; ?>" class="t_mane" name="store_count"
                               onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                               onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                        <?php else: ?>
                        <input type="text" value="<?php echo $tpshop_config[basic_default_storage]; ?>" class="t_mane"
                               name="store_count"
                               onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                               onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    <?php endif; ?>
                    <span class="err" id="err_store_count"></span>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>讲师标签</label>
                </dt>
                <dd class="opt">
                    <!--<input type="text" value="<?php echo $goods['keywords']; ?>" name="keywords" class="input-txt"/>-->
                    <textarea type="text"  name="keywords" class="input-txt"><?php echo $goods['keywords']; ?></textarea>
                    <span class="err" id="err_keywords"></span>
                    <p class="notic">多个标签，用|号隔开，虚拟商品中讲师的三条介绍</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>商品说明</label>
                </dt>
                <dd class="opt">
                    <textarea type="text"  name="shuoming" class="input-txt"><?php echo $goods['shuoming']; ?></textarea>
                    <span class="err" id="err_explain"></span>
                    <p class="notic">介绍商品的检测意义或目的</p>
                </dd>
            </dl>
            <dl class="row">
            <dt class="tit">
            <label>是否为虚拟商品</label>
            </dt>
            <dd class="opt">
                <div class="onoff">
                    <label for="is_virtual1" class="cb-enable <?php if($goods['is_virtual'] == 1): ?>selected<?php endif; ?>">是</label>
                    <label for="is_virtual0" class="cb-disable <?php if($goods['is_virtual'] == 0): ?>selected<?php endif; ?>">否</label>
                    <input class="is_virtual" id="is_virtual1" name="is_virtual" value="1" type="radio"
                    <?php if($goods[is_virtual] == 1): ?> checked="checked"<?php endif; ?>
                    >
                    <input class="is_virtual" id="is_virtual0" name="is_virtual" value="0" type="radio"
                    <?php if($goods[is_virtual] == 0): ?> checked="checked"<?php endif; ?>
                    >
                </div>
            <span class="err" id="err_is_virtual"></span>
            <p class="notic"></p>
            </dd>
            </dl>
            <!--<dl class="row virtual" style="display: none;">-->
                <!--<dt class="tit">-->
                    <!--<label>虚拟商品有效期至</label>-->
                <!--</dt>-->
                <!--<dd class="opt virtual">-->
                    <!--<input id="virtual_indate" name="virtual_indate"-->
                           <!--value="<?php echo date('Y-m-d',(isset($goods[virtual_indate]) && ($goods[virtual_indate] !== '')?$goods[virtual_indate]:time())); ?>" class="input-txt"-->
                           <!--type="text">-->
                    <!--<span class="err" id="err_virtual_indate"></span>-->
                    <!--<p class="notic">虚拟商品可兑换的有效期，过期后商品不能购买，电子兑换码不能使用。</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row virtual" style="display: none;">-->
                <!--<dt class="tit">-->
                    <!--<label>虚拟商品购买上限</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="text" value="<?php echo $goods['virtual_limit']; ?>" class="t_mane" name="virtual_limit"-->
                           <!--onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"-->
                           <!--onpaste="this.value=this.value.replace(/[^\d.]/g,'')"-->
                           <!--onblur="checkInputNum(this.name,1,10,'',1);"/>-->
                    <!--<span class="err" id="err_virtual_limit"></span>-->
                    <!--<p class="notic">请填写1~10之间的数字，虚拟商品最高购买数量不能超过10个。</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>虚拟销售量</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" value="<?php echo $goods['virtual_sales_sum']; ?>" name="virtual_sales_sum" class="t_mane"-->
            <!--onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"-->
            <!--onblur="checkInputNum(this.name,0,9999999,'',1);" />-->
            <!--<span class="err" id="err_virtual_sales_sum"></span>-->
            <!--<p class="notic"> 虚拟销售量（请输入0~9999999）：虚拟销售量 + 真实销量</p>-->
            <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>虚拟收藏量</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<input type="text" value="<?php echo $goods['virtual_collect_sum']; ?>" name="virtual_collect_sum" class="t_mane"-->
            <!--onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"-->
            <!--onblur="checkInputNum(this.name,0,9999999,'',1);"/>-->
            <!--<span class="err" id="err_virtual_collect_sum"></span>-->
            <!--<p class="notic"> 虚拟收藏量（请输入0~9999999）：虚拟收藏量 + 真实收藏量</p>-->
            <!--</dd>-->
            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    <label>商品详情描述</label>
                </dt>
                <dd class="opt">
                    <textarea class="span12 ckeditor" id="goods_content" name="goods_content" title=""><?php echo $goods['goods_content']; ?></textarea>
                    <span class="err" id="err_goods_content"></span>
                </dd>
            </dl>
        </div>
        <!--通用信息-->
        <!-- 商品相册-->
        <div class="ncap-form-default tab_div_2" style="display:none;">
            <dl class="row">
                <div class="tab-pane" id="tab_goods_images">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>
                                <?php if(is_array($goods['goods_images']) || $goods['goods_images'] instanceof \think\Collection || $goods['goods_images'] instanceof \think\Paginator): $i = 0; $__LIST__ = $goods['goods_images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$image): $mod = ($i % 2 );++$i;?>
                                    <div style="width:100px; text-align:center; margin: 5px; display:inline-block;"
                                         class="goods_xc">
                                        <input type="hidden" value="<?php echo $image['image_url']; ?>" name="goods_images[]">
                                        <a onClick="" href="<?php echo $image['image_url']; ?>" target="_blank"><img width="100"
                                                                                                        height="100"
                                                                                                        src="<?php echo $image['image_url']; ?>"></a>
                                        <br>
                                        <a href="javascript:void(0)" onClick="ClearPicArr2(this,'<?php echo $image['image_url']; ?>')">删除</a>
                                    </div>
                                <?php endforeach; endif; else: echo "" ;endif; ?>

                                <div class="goods_xc"
                                     style="width:100px; text-align:center; margin: 5px; display:inline-block;">
                                    <input type="hidden" name="goods_images[]" value=""/>
                                    <a href="javascript:void(0);"
                                       onClick="GetUploadify(10,'','goods','call_back2');"><img
                                            src="/public/images/add-button.jpg" width="100" height="100"/></a>
                                    <br/>
                                    <a href="javascript:void(0)">&nbsp;&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </dl>
        </div>
        <!-- 商品相册-->
        <!-- 商品模型-->
        <div class="ncap-form-default tab_div_3" style="display:none;">
            <dl class="row">
                <div class="tab-pane" id="tab_goods_spec">
                    <table class="table table-bordered" id="goods_spec_table">
                        <tr>
                            <td>商品模型:</td>
                            <td>
                                <a class="btn" id="new_type_info"><i class="fa fa-plus"></i>新增</a>
                                <select name="goods_type" id="goods_type" class="form-control" style="width:200px;">
                                    <option value="0">选择商品模型</option>
                                    <?php if(is_array($goodsType) || $goodsType instanceof \think\Collection || $goodsType instanceof \think\Paginator): if( count($goodsType)==0 ) : echo "" ;else: foreach($goodsType as $k=>$vo): ?>
                                        <option value="<?php echo $vo['id']; ?>"
                                        <?php if($goods[goods_type] == $vo[id]): ?> selected="selected"<?php endif; ?>
                                        ><?php echo $vo['name']; ?></option>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                                <a class="btn" id="type_info"><i class="fa fa-pencil-square-o"></i>编辑</a>
                                <span class="err" id="err_item"></span>
                            </td>
                        </tr>
                    </table>
                    <div class="row">
                        <!-- ajax 返回规格-->
                        <div id="ajax_spec_data" class="col-xs-8"></div>
                        <div class="col-xs-4">
                            <table class="table table-bordered" id="goods_attr_table">
                                <tr>
                                    <td><b>商品属性</b>：</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </dl>
        </div>
        <!-- 商品模型-->
        <!--积分折扣-->
        <div class="ncap-form-default tab_div_5" style="display:none;">
            <!--<dl class="row">-->
            <!--<dt class="tit">-->
            <!--<label>价格阶梯</label>-->
            <!--</dt>-->
            <!--<dd class="opt">-->
            <!--<div class="alisth0" id="alisth_0">-->
            <!--单次购买个数达到<input type="text" class="input-number addprine" name="ladder_amount[]" <?php if($goods['price_ladder']): ?>value="<?php echo $goods['price_ladder'][0]['amount']; ?>"<?php endif; ?> onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;" >&nbsp;-->
            <!--价格<input type="text" class="input-number addprine" name="ladder_price[]"  <?php if($goods['price_ladder']): ?>value="<?php echo $goods['price_ladder'][0]['price']; ?>"<?php endif; ?> onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;" >-->
            <!--<a class="p_plus" href="javascript:;"><strong>[+]</strong></a>-->
            <!--</div>-->
            <!--<?php if(is_array($goods['price_ladder']) || $goods['price_ladder'] instanceof \think\Collection || $goods['price_ladder'] instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($goods['price_ladder']) ? array_slice($goods['price_ladder'],1,null, true) : $goods['price_ladder']->slice(1,null, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>-->
            <!--<div class="alisth">-->
            <!--单次购买个数达到<input type="text" class="input-number addprine" name="ladder_amount[]" value="<?php echo $vo['amount']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;" >&nbsp;-->
            <!--价格<input type="text" class="input-number addprine" name="ladder_price[]" value="<?php echo $vo['price']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')" style="width: 100px;" >-->
            <!--<a class="p_plus" onclick='$(this).parent().remove();'><strong>[-]</strong></a>-->
            <!--</div>-->
            <!--<?php endforeach; endif; else: echo "" ;endif; ?>-->
            <!--<span class="err" id="err_ladder_amount"></span>-->
            <!--<span class="err" id="err_ladder_price"></span>-->
            <!--</dd>-->
            <!--<script>-->
            <!--$(function(){-->
            <!--$('.p_plus').click(function() {-->
            <!--var html = "<div class='alisth'>"-->
            <!--+ "单次购买个数达到"-->
            <!--+ "<input type='text' class='input-number addprine' name='ladder_amount[]' style='width: 100px;' value=''/>"-->
            <!--+ "&nbsp;&nbsp;价格"-->
            <!--+ "<input type='text' class='input-number addprine' name='ladder_price[]' style='width: 100px;' value=''>"-->
            <!--+ "<a class='p_plus' onclick='$(this).parent().remove();'>&nbsp;<strong>[-]</strong></a>"-->
            <!--+ "</div>";-->
            <!--$('#alisth_0').after(html);-->
            <!--});-->
            <!--})-->
            <!--</script>-->
            <!--</dl>  -->

            <dl class="row">
                <dt class="tit">
                    <label>赠送积分</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['give_integral']; ?>" name="give_integral" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/> 订单完成后赠送积分
                    <span class="err" id="err_give_integral"></span>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>兑换积分</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods['exchange_integral']; ?>" name="exchange_integral" class="t_mane"
                           onKeyUp="this.value=this.value.replace(/[^\d.]/g,'')"
                           onpaste="this.value=this.value.replace(/[^\d.]/g,'')"/>
                    不得高于商品最低价格与兑换比的积，如果设置0，则不支持积分抵扣
                    <span class="err" id="err_exchange_integral"></span>
                </dd>
            </dl>

        </div>

        <div class="ncap-form-default">
            <div class="bot">
                <a href="JavaScript:void(0);" id="submit" class="ncap-btn-big ncap-btn-green">确认提交</a>
                <span class="err" id="err_goods_id"></span>
            </div>
        </div>
    </form>
    <!--表单数据-->
</div>
<div id="goTop"><a href="JavaScript:void(0);" id="btntop"><i class="fa fa-angle-up"></i></a><a
        href="JavaScript:void(0);" id="btnbottom"><i class="fa fa-angle-down"></i></a></div>
<script>
    /** 商品规格相关 js*/
    $(function () {
        // 商品模型切换时 ajax 调用  返回不同的属性输入框
        $(document).on("change", '#goods_type', function () {
            var goods_id = $("input[name='goods_id']").val();
            var type_id = $(this).val();
            get_goods_spec_select(goods_id, type_id);
            get_goods_attr_select(goods_id, type_id);
        })
    })

    //商品模型切换时，返回不同的规格输入框
    function get_goods_spec_select(goods_id, type_id) {
        $.ajax({
            type: 'post',
            data: {goods_id: goods_id, type_id: type_id},
            url: "<?php echo U('admin/Goods/ajaxGetSpecSelect'); ?>",
            success: function (data) {
                $("#ajax_spec_data").html('').append(data);
                ajaxGetSpecInput();	// 触发完  马上触发 规格输入框
            }
        });
    }

    //商品模型切换时，返回不同的属性输入框
    function get_goods_attr_select(goods_id, type_id) {
        $.ajax({
            type: 'GET',
            data: {goods_id: goods_id, type_id: type_id},
            url: "/index.php/admin/Goods/ajaxGetAttrInput",
            dataType: 'json',
            success: function (data) {
                var html = '';
                var item_html;
                $.each(data, function (index, item) {
                    item_html = '';
                    if (item.attr_values_to_array.length > 0) {
                        item_html += '<tr class="attr_' + item.attr_id + '"><td>' + item.attr_name + '</td> <td>' +
                            '<select name="attr_' + item.attr_id + '[]"><option value="0">无</option>';
                        if (item.attr_values_to_array.length > 0) {
                            $.each(item.attr_values_to_array, function (i, n) {
                                if (item.goods_attr != null && item.goods_attr.attr_value == n) {
                                    item_html += '<option selected="selected" value="' + n + '">' + n + '</option>';
                                } else {
                                    item_html += '<option value="' + n + '">' + n + '</option>';
                                }
                            })
                        }
                        item_html += '</select>';
                    } else {
                        item_html += '<tr class="attr_' + item.attr_id + '"><td>' + item.attr_name + '</td> <td>' +
                            '<input name="attr_' + item.attr_id + '[]" value="" />';
                    }

                    item_html += '</td>' + '</tr>';
                    html += item_html;
                });
                $("#goods_attr_table tr:gt(0)").remove();
                $("#goods_attr_table").append(html);
            }
        });
    }

    $(document).ready(function () {
        $('#virtual_indate').layDate();
        $("#goods_type").trigger('change'); // 触发商品规格
        initFreight();
        initIsVirtual();
        initCategory();
    });
    //提交
    $(function () {
        $(document).on("click", '#submit', function () {
            $('#submit').attr('disabled', true);
            var is_distribut = $("input[name='is_distribut']").val();
            var item_array = new Array();
            $("img[id^=item_img_]").parent("span[data-img_id]").prevAll("button[class='btn btn-success']").each(function (i, v) {
                item_array[i] = parseInt($(v).attr('data-item_id'));
            })//所有选中的item
            var item_img_array = new Array();
            $("button[class='btn btn-success']").next("span[data-img_id]").find("img[id^=item_img_][src!='/public/images/add-button.jpg']").each(function (index, value) {
                item_img_array[index] = parseInt($(value).attr('id').slice(9));
            })//所有选中item下面上传了图片的
            //判断：所有算中item上传的图片为空 或者所有选中item=所有选中的图片

            if (item_array.sort().toString() != item_img_array.sort().toString() && item_img_array.length != 0) {
                layer.alert("已选规格必须全部都传图或都不传图", {icon: 2, time: 2000});
                return;
            }
            $.ajax({
                type: "POST",
                url: "<?php echo U('Goods/save'); ?>",
                data: $("#addEditGoodsForm").serialize(),
                async: false,
                dataType: "json",
                error: function () {
                    layer.alert("服务器繁忙, 请联系管理员!");
                },
                success: function (data) {
                    if (data.status == 1) {
                        layer.msg(data.msg, {icon: 1, time: 2000}, function () {
                            if (is_distribut > 0) {
                                location.href = "<?php echo U('Distribut/goods_list'); ?>";
                            } else {
                                location.href = "<?php echo U('Goods/goodsList'); ?>";
                            }
                        });
                    } else {
                        $('#submit').attr('disabled', false);
                        $.each(data.result, function (index, item) {
                            $('span.err').show();
                            $('#err_' + index).text(item);
                        });
                        layer.msg(data.msg, {icon: 2, time: 3000});
                    }
                }
            });
        })
    })

    //选择分类
    $(function () {
        $(document).on("change", '#cat_id', function () {
            var v = $(this).val();
            get_category(v, 'cat_id_2', '0');
            if (v == 0) {
                $('#cat_id_2').empty().html("<option value='0'>请选择商品分类</option>");
            }
            $('#cat_id_3').empty().html("<option value='0'>请选择商品分类</option>");
        })
    })

    //规格批量填充
    $(function () {
        $(document).on("click", '#item_fill', function () {
            var item_price_fill = $("#item_price").val();
            var item_cost_price_fill = $("#item_cost_price").val();
            var item_commission_fill = $("#item_commission").val();
            var item_store_count_fill = $("#item_store_count").val();
            var item_sku_fill = $("#item_sku").val();
            $("input[name$='[price]']").val(item_price_fill);
            $("input[name$='[cost_price]']").val(item_cost_price_fill);
            $("input[name$='[commission]']").val(item_commission_fill);
            $("input[name$='[store_count]']").val(item_store_count_fill);
            $("input[name$='[sku]']").each(function (i, o) {
                if (item_sku_fill != '') {
                    $(this).val(item_sku_fill);
                    item_sku_fill++;
                } else {
                    $(this).val('');
                }
            })
        })
    })

    //虚拟和免邮事件
    $(function () {
        $(document).on("click", '.is_virtual', function () {
            initIsVirtual();
        })
        $(document).on("click", '.is_free_shipping', function (e) {
            initFreight();
        })
    })

    //初始化虚拟商品设置
    function initIsVirtual() {
        var goods_id = $("input[name='goods_id']").val();
        var is_virtual;
        if (goods_id > 0) {
            is_virtual = $("input[name='is_virtual']").val();
        } else {
            is_virtual = $("input[name='is_virtual']:checked").val();
        }
        if (is_virtual == 1) {
            $('.virtual').show();
            $('#is_free_shipping_label_1').trigger('click');
            initFreight();
            $(".goods_shipping").hide();
        } else {
            $('.virtual').hide();
            $(".goods_shipping").show();
        }
    }

    //初始化运费设置
    function initFreight() {
        var is_free_shipping = $("input[name='is_free_shipping']:checked").val();
        if (is_free_shipping == 0) {
            $('.freight_template').show();
        } else {
            $('.freight_template').hide();
        }
    }

    //编辑时默认选中某个商品分类
    function initCategory() {
        var level_cat_1 = $("input[name='level_cat_1']").val();
        var level_cat_2 = $("input[name='level_cat_2']").val();
        var level_cat_3 = $("input[name='level_cat_3']").val();
        if (level_cat_2 > 0) {
            get_category(level_cat_1, 'cat_id_2', level_cat_2);
        }
        if (level_cat_3 > 0) {
            get_category(level_cat_2, 'cat_id_3', level_cat_3);
            getCategoryBrandList(level_cat_2);
        }
    }

    var url = "<?php echo url('Admin/Ueditor/index',array('savePath'=>'goods')); ?>";
    var ue = UE.getEditor('goods_content', {
        toolbars: [[
            'fullscreen', 'source', '|', 'undo', 'redo', '|',
            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
            'directionalityltr', 'directionalityrtl', 'indent', '|',
            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
            'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
            'simpleupload', 'insertimage', 'emotion', 'scrawl', 'music', 'attachment', 'map', 'gmap', 'insertframe', 'insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
            'horizontal', 'date', 'time', 'spechars', 'snapscreen', 'wordimage', '|',
            'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', '|',
            'print', 'preview', 'searchreplace', 'drafts', 'help'
        ]],
        serverUrl: url,
        zIndex: 999,
        initialFrameWidth: "100%", //初化宽度
        initialFrameHeight: 300, //初化高度
        focus: false, //初始化时，是否让编辑器获得焦点true或false
        maximumWords: 99999, removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允许的最大字符数 'fullscreen',
        pasteplain: false, //是否默认为纯文本粘贴。false为不使用纯文本粘贴，true为使用纯文本粘贴
        autoHeightEnabled: true
    });

    // 上传商品图片成功回调函数
    function call_back(fileurl_tmp) {
        $("#original_img").val(fileurl_tmp);
        $("#original_img2").attr('href', fileurl_tmp);
    }

    // 上传商品相册回调函数
    function call_back2(paths) {
        var last_div = $(".goods_xc:last").prop("outerHTML");
        for (var i = 0; i < paths.length; i++) {
            $(".goods_xc:eq(0)").before(last_div);	// 插入一个 新图片
            $(".goods_xc:eq(0)").find('a:eq(0)').attr('href', paths[i]).attr('onclick', '').attr('target', "_blank");// 修改他的链接地址
            $(".goods_xc:eq(0)").find('img').attr('src', paths[i]);// 修改他的图片路径
            $(".goods_xc:eq(0)").find('a:eq(1)').attr('onclick', "ClearPicArr2(this,'" + paths[i] + "')").text('删除');
            $(".goods_xc:eq(0)").find('input').val(paths[i]); // 设置隐藏域 要提交的值
        }
    }

    //上传之后删除组图
    function ClearPicArr2(obj, path) {
        $.ajax({
            type: 'GET',
            url: "<?php echo U('Admin/Uploadify/delupload'); ?>",
            data: {action: "del", filename: path},
            success: function () {
                $(obj).parent().remove(); // 删除完服务器的, 再删除 html上的图片
            }
        });
        // 删除数据库记录
        $.ajax({
            type: 'GET',
            url: "<?php echo U('Admin/Goods/del_goods_images'); ?>",
            data: {filename: path},
            success: function () {
            }
        });
    }

    // 属性输入框的加减事件
    function addAttr(a) {
        var attr = $(a).parent().parent().prop("outerHTML");
        attr = attr.replace('addAttr', 'delAttr').replace('+', '-');
        $(a).parent().parent().after(attr);
    }

    // 属性输入框的加减事件
    function delAttr(a) {
        $(a).parent().parent().remove();
    }


    //插件切换列表
    $('.tab-base').find('.tab').click(function () {
        $('.tab-base').find('.tab').each(function () {
            $(this).removeClass('current');
        });
        $(this).addClass('current');
        var tab_index = $(this).data('index');
        $(".tab_div_1, .tab_div_2, .tab_div_3, .tab_div_4,.tab_div_5").hide();
        $(".tab_div_" + tab_index).show();
    });

    //上传图片回调事件
    function img_call_back(fileurl_tmp) {
        $("#imagetext").val(fileurl_tmp);
        $("#img_a").attr('href', fileurl_tmp);
        $("#img_i").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
    }

    //上传视频回调事件
    function video_call_back(fileurl_tmp) {
        $("#videotext").val(fileurl_tmp);
        $("#video_a").attr('href', fileurl_tmp);
        $("#video_i").attr('onmouseover', "layer.tips('<img src=" + fileurl_tmp + ">',this,{tips: [1, '#fff']});");
        if (typeof(fileurl_tmp) != 'undefined') {
            $('#video_button').html('<input type="button" onclick="delupload()" value="删除视频" class="type-file-button" >');
        }
    }
    //上传音频回调事件
    function music_call_back(fileurl_tmp) {
        $("#musictext").val(fileurl_tmp);
        $("#music_a").attr('href', fileurl_tmp);
        if (typeof(fileurl_tmp) != 'undefined') {
            $('#music_button').html('<input type="button" onclick="deluploadformusic()" value="删除音频" class="type-file-button" >');
        }
    }
    //品牌选项框
    function getCategoryBrandList(val) {
        if (val == 0) return false;
        var goods_brand_id = $("input[name='goods_brand_id']").val();
        $.ajax({
            'url': "<?php echo U('goods/getCategoryBrandList'); ?>",
            'data': {cart_id: val},
            'dataType': 'json',
            success: function (data) {
                if (data.status == 1) {
                    var html = '<option value="">所有品牌</option>';
                    for (var i = 0; i < data.result.length; i++) {
                        var bind_id = data.result[i].id;
                        if (goods_brand_id == bind_id) {
                            html += '<option value="' + bind_id + '" selected>' + data.result[i].name + '</option>'
                        } else {
                            html += '<option value="' + bind_id + '">' + data.result[i].name + '</option>'
                        }
                    }
                    $('#brand_id').empty().html(html);
                }
            }
        })
    }

    //删除上传图片事件
    function delupload() {
        $.ajax({
            url: "<?php echo U('Uploadify/delupload'); ?>",
            data: {url: $('#videotext').val()},
            success: function (data) {
                if (data == 1) {
                    layer.msg('删除成功！', {icon: 1});
                    $('#videotext').val('');
                    var html = '<input type="button" name="button" id="videobutton1" value="选择上传..." class="type-file-button"> <input class="type-file-file" onClick="GetUploadify(1,\'\',\'goods\',\'video_call_back\',\'Flash\')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击按钮选择文件并提交表单后上传生效">';
                    $('#video_button').html(html);
                } else {
                    layer.msg('删除失败', {icon: 2});
                }
            },
            error: function () {
                layer.msg('网络繁忙，请稍后再试!', {icon: 2});
            }
        })
    }
    //删除上传音频事件
    function deluploadformusic() {
        console.log($('#musictext').val(),'拼接的url')
        $.ajax({
            url: "<?php echo U('Uploadify/delupload'); ?>",
            data: {url: $('#musictext').val()},
            success: function (data) {
                console.log(data,'删除的回调')
                console.log($('#musictext').val(),'拼接的url')
                if (data == 1) {
                    layer.msg('删除成功！', {icon: 1});
                    $('#musictext').val('');
                    var html = '<input type="button" name="button" id="musicbutton1" value="选择上传..." class="type-file-button"> <input class="type-file-file" onClick="GetUploadify(1,\'\',\'goods\',\'music_call_back\',\'Music\')" size="30" hidefocus="true" nc_type="change_site_logo" title="点击按钮选择文件并提交表单后上传生效">';
                    $('#music_button').html(html);
                } else {
                    layer.msg('删除失败', {icon: 2});
                }
            },
            error: function () {
                layer.msg('网络繁忙，请稍后再试!', {icon: 2});
            }
        })
    }
    $(document).on('click', '#type_info', function () {
        var type_id = $('#goods_type').val();
        if (type_id > 0) {
            add_edit_type(type_id);
        } else {
            layer.msg('请选择商品模型', {icon: 2});
        }
    });
    $(document).on('click', '#new_type_info', function () {
        add_edit_type(0);
    });


    function add_edit_type(type_id) {
        var url = '/index.php?m=Admin&c=Goods&a=type';
        if (type_id) {
            url += '&id=' + type_id;
        }
        layer.open({
            type: 2,
            title: '添加/编辑商品模型',
            shadeClose: true,
            shade: 0.2,
            area: ['75%', '75%'],
            content: url,
            cancel: function () {
                $('#goods_type').trigger('change');
            }
        });
    }

    function get_goods_type_list(type_id) {
        $.ajax({
            type: 'post',
            data: {type_id: type_id},
            url: "<?php echo U('admin/Goods/ajaxGetGoodsTypeList'); ?>",
            success: function (data) {
                $("#goods_type").html(JSON.parse(data));
                var goods_id = $("input[name='goods_id']").val();
                get_goods_spec_select(goods_id, type_id);
                get_goods_attr_select(goods_id, type_id);
            }
        });

    }

    function save_type_call_back(type_id) {
        get_goods_type_list(type_id);
        layer.closeAll();
    }
</script>
</body>
</html>