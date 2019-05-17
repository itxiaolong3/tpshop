<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./application/admin/view/team\info.html";i:1540260088;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
    .err{color:#F00; display:none;}
</style>
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>促销管理 - 编辑拼团</h3>
                <h5>网站系统拼团活动详情页</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="handleposition" method="post" onsubmit="return false;">
        <input type="hidden" name="team_id" value="<?php echo $teamActivity['team_id']; ?>">
        <input type="hidden" id="goods_id" name="goods_id" value="<?php echo $teamActivity['goods_id']; ?>" autocomplete="off">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>拼团标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="act_name" value="<?php echo $teamActivity['act_name']; ?>" class="input-txt">
                    <span class="err" id="err_act_name"></span>
                    <span class="err" id="err_team_id"></span>
                    <p class="notic">请填写拼团标题</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>发放类型</label>
                </dt>
                <dd class="opt ctype">
                    <?php if(is_array(\think\Config::get('TEAM_TYPE')) || \think\Config::get('TEAM_TYPE') instanceof \think\Collection || \think\Config::get('TEAM_TYPE') instanceof \think\Paginator): $i = 0; $__LIST__ = \think\Config::get('TEAM_TYPE');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$type): $mod = ($i % 2 );++$i;?>
                        <input name="team_type" class="team_type" type="radio" value="<?php echo $key; ?>" <?php if($teamActivity['team_type'] == $key): ?>checked='checked'<?php endif; ?>><label><?php echo $type; ?></label>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </dd>
            </dl>
            <dl class="row commission">
                <dt class="tit">
                    <label><em>*</em>团长佣金</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="bonus" value="<?php echo $teamActivity['bonus']; ?>"  onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt">
                    <span class="err" id="err_bonus"></span>
                    <p class="notic">拼团成功后，开团人能获得的佣金<br/>必须是0.01~1000000之间的数字(单位：元)</p>
                </dd>
            </dl>
            <dl class="row luck">
                <dt class="tit">
                    <label><em>*</em>抽奖限量</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="stock_limit" value="<?php echo $teamActivity['stock_limit']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt">
                    <span class="err" id="err_stock_limit"></span>
                    <p class="notic">中奖人数(单位：人)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>成团有效期</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="time_limit" value="<?php echo $teamActivity['time_limit_hours']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt">
                    <span class="err" id="err_goods_num"></span>
                    <p class="notic">开团后有效时间范围(单位：小时)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>需要成团人数</label>
                </dt>
                <dd class="opt">
                    <input type="text"  name="needer" value="<?php echo $teamActivity['needer']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')"  class="input-txt">
                    <span class="err" id="err_needer"></span>
                    <p class="notic">需要多少人拼团才能成功(单位：人)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>购买限制数</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="buy_limit" value="<?php echo $teamActivity['buy_limit']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')"  class="input-txt">
                    <span class="err" id="err_buy_limit"></span>
                    <p class="notic">限制购买商品个数,0为不限制(单位：个),抽奖团限购数为1</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>虚拟销售基数</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="virtual_num" value="<?php echo $teamActivity['virtual_num']; ?>" onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')"  class="input-txt">
                    <span class="err" id="err_virtual_num"></span>
                    <p class="notic">虚拟购买商品数(单位：个)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>分享标题</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="share_title" value="<?php echo $teamActivity['share_title']; ?>" class="input-txt">
                    <span class="err" id="err_share_title"></span>
                    <p class="notic">请填写分享标题</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>分享描述</label>
                </dt>
                <dd class="opt">
                    <textarea placeholder="请输入分享描述" name="share_desc" rows="6" class="tarea"><?php echo $teamActivity['share_desc']; ?></textarea>
                    <p class="notic">拼团描述介绍</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>分享图片</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a id="img_a"  class="nyroModal" rel="gal" href="<?php echo $teamActivity['share_img']; ?>">
                                <i id="img_i" class="fa fa-picture-o" onMouseOver="layer.tips('<img src=<?php echo $teamActivity['share_img']; ?>>',this,{tips: [1, '#fff']});" onMouseOut="layer.closeAll();"></i>
                            </a>
                        </span>
                        <span class="type-file-box">
                            <input type="text" id="imagetext" name="share_img" value="<?php echo $teamActivity['share_img']; ?>" class="type-file-text">
                            <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
                            <input class="type-file-file" onClick="GetUploadify(1,'share_img','activity','img_call_back')" size="30" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
                        </span>
                    </div>
                    <span class="err" id="err_share_img"></span>
                    <p class="notic">请上传图片格式文件</p>
                </dd>
            </dl>
            <div class="flexigrid">
                <div class="hDiv">
                    <div class="hDivBox">
                        <table cellspacing="0" cellpadding="0">
                            <thead>
                            <tr>
                                <th align="center">
                                    <div style="text-align: center; width: 250px;" class="">规格</div>
                                </th>
                                <th align="center">
                                    <div style="text-align: center; width: 50px;" class="">库存</div>
                                </th>
                                <th align="center">
                                    <div style="text-align: center; width: 100px;" class="">商城价格（元）</div>
                                </th>
                                <th align="center">
                                    <div style="text-align: center; width: 150px;" class="">拼团价格（元）</div>
                                </th>
                                <th class="handle" align="left">
                                    <div style="text-align: center; width: 150px;">操作</div>
                                </th>
                                <th style="width:100%" axis="col7">
                                    <div></div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tDiv">
                    <div class="tDiv2">
                        <a onclick="selectGoods()">
                            <div class="fbutton">
                                <div title="添加商品" class="add">
                                    <span><i class="fa fa-plus"></i>添加商品</span>
                                </div>
                            </div>
                        </a>
                        <div class="fbutton_right" style="margin-left: 5px;vertical-align: top;">
                            <div class="fbutton_span">
                                <input type="text" id="goods_name" name="goods_name" readonly="readonly" value="<?php echo $teamActivity['goods_name']; ?>" class="input-txt" autocomplete="off">
                            </div>
                        </div>
                        <div class="fbutton_right" style="margin-left: 5px;vertical-align: top;">
                            <div class="fbutton_span">
                                <span class="err" id="err_team_goods_item"></span>
                            </div>
                        </div>

                    </div>
                    <div class="tDiv2" style="margin-top: 2px;">
                        <span class="err" id="err_combination_goods" style="font-size: 12px;height: 20px;margin-left: 400px;vertical-align:top">选择商品</span>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="bDiv" style="height: auto;">
                    <div id="flexigrid">
                        <table>
                            <tbody id="selected_group_goods">
                            <?php if(is_array($teamActivity['team_goods_item']) || $teamActivity['team_goods_item'] instanceof \think\Collection || $teamActivity['team_goods_item'] instanceof \think\Paginator): $item_key = 0; $__LIST__ = $teamActivity['team_goods_item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_item): $mod = ($item_key % 2 );++$item_key;?>
                                <tr class="bd-line">
                                    <input name="team_goods_item[<?php echo $item_key-1; ?>][goods_id]" value="<?php echo $goods_item['goods_id']; ?>" type="hidden"/>
                                    <input name="team_goods_item[<?php echo $item_key-1; ?>][item_id]" value="<?php echo $goods_item['item_id']; ?>" type="hidden"/>
                                    <td> <div style="text-align: center; width: 250px;"><?php echo $goods_item['spec_goods_price']['key_name']; ?></div> </td>
                                    <td>
                                        <div style="text-align: center; width: 50px;">
                                            <?php if($goods_item['item_id'] == 0): ?>
                                                <?php echo $teamActivity['goods']['store_count']; else: ?>
                                                <?php echo $goods_item['spec_goods_price']['store_count']; endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="text-align: center; width: 100px;">
                                            <?php if($goods_item['item_id'] == 0): ?>
                                                <?php echo $teamActivity['goods']['shop_price']; else: ?>
                                                <?php echo $goods_item['spec_goods_price']['price']; endif; ?>
                                        </div>
                                    </td>
                                    <td> <div style="text-align: center; width: 150px;"><input name="team_goods_item[<?php echo $item_key-1; ?>][team_price]" value="<?php echo $goods_item['team_price']; ?>" type="text"/></div></td>
                                    <td class="handle">
                                        <div style="text-align: center; width: 100px;">
                                            <a class="btn red delete_tr" href="javascript:void(0)">删除</a>
                                        </div>
                                    </td>
                                    <td style="width: 100%;">
                                        <div>&nbsp;</div>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--分页位置-->
                <div class="dataTables_paginate paging_simple_numbers"><ul class="pagination">    </ul>
                </div>
            </div>
            <div class="bot"><a onclick="verifyForm()" class="ncap-btn-big ncap-btn-green">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        initType();
    });
    $(document).on("click", '#submit', function (e) {
        $('#submit').attr('disabled',true);
        verifyForm();
    })
    $(document).on("click", ".team_type", function (e) {
        initType();
    })
    $(document).on("click", ".delete_tr", function (e) {
        $(this).parent().parent().parent().remove();
        if($('#selected_group_goods').find('tr').length == 0){
            $('#goods_name').removeAttr('readonly').val('').attr('readonly', 'readonly');
        }
    })
    function initType(){
        var type = $("input[name='team_type']:checked").val();
        var commission = $('.commission');
        var luck = $('.luck');
        switch(parseInt(type))
        {
            case 0:
                commission.hide();
                luck.hide();
                break;
            case 1:
                commission.show();
                luck.hide();
                break;
            case 2:
                commission.hide();
                $("input[name='buy_limit']").val(1);
                luck.show();
                break;
            default:
                commission.hide();
                luck.hide();
        }
    }
    function verifyForm(){
        $('span.err').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo U('Team/save'); ?>",
            data: $('#handleposition').serialize(),
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.msg, {
                        icon: 1,
                        time: 1000
                    }, function(){
                        location.href = "<?php echo U('Team/index'); ?>";
                    });
                } else {
                    layer.msg(data.msg, {icon: 2,time: 1000});
                    $.each(data.result, function (index, item) {
                        $('#err_' + index).text(item).show();
                    });
                }
            }
        });
    }
    function selectGoods(){
        var url = "<?php echo U('Promotion/search_goods',array('tpl'=>'select_goods_item','prom_type'=>6,'prom_id'=>$teamActivity['team_id'])); ?>";
        layer.open({
            type: 2,
            title: '选择商品',
            shadeClose: true,
            shade: 0.2,
            area: ['75%', '75%'],
            content: url,
        });
    }
    function call_back(goodsItem){
        var html = '';
        if(goodsItem.spec != null){
            //有规格
            $.each(goodsItem.spec, function (i, o) {
                html += '<tr class="bd-line"> ' +
                        '<input name="team_goods_item['+i+'][goods_id]" value="'+goodsItem.goods_id+'" type="hidden"/> ' +
                        '<input name="team_goods_item['+i+'][item_id]" value="'+ o.item_id +'" type="hidden"/> ' +
                        '<td> <div style="text-align: center; width: 250px;">'+o.key_name+'</div> </td> ' +
                        '<td> <div style="text-align: center; width: 50px;"> '+ o.store_count+ ' </div> </td> ' +
                        '<td> <div style="text-align: center; width: 100px;"> '+ o.price +' </div> </td> ' +
                        '<td> <div style="text-align: center; width: 150px;"><input name="team_goods_item['+i+'][team_price]" value="1.00" type="text"/></div></td> ' +
                        '<td class="handle"> <div style="text-align: center; width: 100px;"> <a class="btn red delete_tr" href="javascript:void(0)">删除</a> </div> </td> ' +
                        '<td style="width: 100%;"> <div>&nbsp;</div> </td> ' +
                        '</tr>';
            });

        }else{
            html = '<tr class="bd-line"> ' +
                    '<input name="team_goods_item[0][goods_id]" value="'+goodsItem.goods_id+'" type="hidden"/> ' +
                    '<input name="team_goods_item[0][item_id]" value="0" type="hidden"/> ' +
                    '<td> <div style="text-align: center; width: 250px;"> -- </div> </td> ' +
                    '<td> <div style="text-align: center; width: 50px;"> '+ goodsItem.store_count+ ' </div> </td> ' +
                    '<td> <div style="text-align: center; width: 100px;"> '+ goodsItem.goods_price +' </div> </td> ' +
                    '<td> <div style="text-align: center; width: 150px;"><input name="team_goods_item[0][team_price]" value="1.00" type="text"/></div></td> ' +
                    '<td class="handle"> <div style="text-align: center; width: 100px;"> <a class="btn red delete_tr" href="javascript:void(0)">删除</a> </div> </td> ' +
                    '<td style="width: 100%;"> <div>&nbsp;</div> </td> ' +
                    '</tr>';
        }
        $('#goods_id').val(goodsItem.goods_id);
        $('#goods_name').val(goodsItem.goods_name);
        $('#selected_group_goods').empty().html(html);
        $('.selected-group-goods').show();
        layer.closeAll('iframe');
    }
    function img_call_back(fileurl_tmp)
    {
        $("#imagetext").val(fileurl_tmp);
        $("#img_a").attr('href', fileurl_tmp);
        $("#img_i").attr('onmouseover', "layer.tips('<img src="+fileurl_tmp+">',this,{tips: [1, '#fff']});");
    }

</script>
</body>
</html>