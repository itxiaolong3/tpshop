<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:48:"./application/admin/view/coupon\coupon_info.html";i:1556588778;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
c

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
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<style>
    .ncsc-default-table {
        line-height: 20px;
        width: 100%;
        border-collapse: collapse;
        clear: both;
    }
    .ncsc-default-table thead th {
        line-height: 20px;
        color: #777;
        background-color: #FFF;
        text-align: center;
        height: 20px;
        padding: 8px 0;
        border-bottom: solid 1px #DDD;
    }
    .ncsc-default-table tbody td {
        color: #777;
        background-color: #FFF;
        text-align: center;
        padding: 10px 0;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>优惠券管理 - 编辑优惠券</h3>
                <h5>网站系统优惠券管理</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="handleposition" method="post">
        <input type="hidden" name="id" value="<?php echo $coupon['id']; ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>优惠券名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="name" name="name" value="<?php echo $coupon['name']; ?>" class="input-txt">
                    <span class="err" id="err_name"></span>
                    <p class="notic">请填写优惠券名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>优惠券面额</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="money" name="money"  onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" value="<?php echo $coupon['money']; ?>" class="input-txt">
                    <span class="err" id="err_money"></span>
                    <p class="notic">优惠券可抵扣金额</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>消费金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="condition" name="condition" value="<?php echo $coupon['condition']; ?>" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="input-txt">
                    <span class="err" id="err_condition"></span>
                    <p class="notic">订单需满足的最低消费金额(必需为整数)才能使用</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>发放数量</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="createnum" name="createnum" value="<?php echo $coupon['createnum']; ?>" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" class="input-txt">
                    <span class="err" id="err_createnum"></span>
                    <p class="notic">发放数量限制(默认为0则无限制)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>发放类型</label>
                </dt>
                <dd class="opt ctype">
                    <!--<input name="type" type="radio" value="0" <?php if($coupon['type'] == 0): ?>checked<?php endif; ?> ><label>下单赠送</label>-->
                    <input name="type" type="radio" value="1" <?php if($coupon['type'] == 1): ?>checked<?php endif; ?> ><label>指定发放</label>
                    <input name="type" type="radio" value="2" <?php if($coupon['type'] == 2): ?>checked<?php endif; ?> ><label>免费领取</label>
                    <input name="type" type="radio" value="4" <?php if($coupon['type'] == 4): ?>checked<?php endif; ?> ><label>注册赠送</label>
                    <!--<input name="type" type="radio" value="3" <?php if($coupon['type'] == 3): ?>checked<?php endif; ?> ><label>线下发放</label>-->
                </dd>
            </dl>
            <dl class="row timed">
                <dt class="tit">
                    <label><em>*</em>发放起始日期</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="send_start_time" name="send_start_time" value="<?php echo date('Y-m-d H:i:s',$coupon['send_start_time']); ?>"  class="input-txt">
                    <span class="err" id="err_send_start_time"></span>
                    <p class="notic">发放起始日期</p>
                </dd>
            </dl>
            <dl class="row timed">
                <dt class="tit">
                    <label><em>*</em>发放结束日期</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="send_end_time" name="send_end_time" value="<?php echo date('Y-m-d H:i:s',$coupon['send_end_time']); ?>" class="input-txt">
                    <p class="notic">发放结束日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>使用起始日期</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="use_start_time" name="use_start_time" value="<?php echo date('Y-m-d H:i:s',$coupon['use_start_time']); ?>" class="input-txt">
                    <span class="err" id="err_use_start_time"></span>
                    <p class="notic">使用起始日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>使用结束日期</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="use_end_time" name="use_end_time" value="<?php echo date('Y-m-d H:i:s',$coupon['use_end_time']); ?>" class="input-txt">
                    <p class="notic">使用结束日期</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>可使用商品：</label>
                </dt>
                <dd class="opt">
                    <label>
                        <input type="radio" value="0" name="use_type" onclick="use_type_tab(0)" <?php if($coupon['use_type'] == 0): ?>checked<?php endif; ?>>全店通用</label>
                    <label>
                        <input type="radio" value="1" name="use_type" onclick="javascript:selectGoods();" <?php if($coupon['use_type'] == 1): ?>checked<?php endif; ?>>指定商品
                    </label>
                    <!--<label>-->
                        <!--<input type="radio" value="2" name="use_type" onclick="use_type_tab(2)" <?php if($coupon['use_type'] == 2): ?>checked<?php endif; ?>>指定分类-->
                    <!--</label>-->
                </dd>
            </dl>
            <dl id="goods_all_cate" style="display:<?php if($coupon[use_type] == 2): ?>;<?php else: ?>none;<?php endif; ?>">
                <dt class="tit"><em>*</em>限制商品分类使用：</dt>
                <dd class="opt">
                    <select name="cat_id1" id="cat_id1" onchange="get_category(this.value,'cat_id2','0');" class="valid">
                        <option value="0">请选择商品分类</option>
                        <?php if(is_array($cat_list) || $cat_list instanceof \think\Collection || $cat_list instanceof \think\Paginator): if( count($cat_list)==0 ) : echo "" ;else: foreach($cat_list as $k=>$v): ?>
                            <option value="<?php echo $v['id']; ?>" <?php if($v['id'] == $coupon['cat_id1']): ?>selected="selected"<?php endif; ?> >
                            <?php echo $v['name']; ?>
                            </option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <select name="cat_id2" id="cat_id2" onchange="get_category(this.value,'cat_id3','0');" class="valid">
                        <option value="0">请选择商品分类</option>
                    </select>
                    <select name="cat_id3" id="cat_id3" class="valid">
                        <option value="0">请选择商品分类</option>
                    </select>
                    <span class="err" id="err_cat_id1"></span>
                    <span class="err" id="err_cat_id2"></span>
                    <span class="err" id="err_cat_id3"></span>
                    <p class="hint">若不选表示不限制，否则请选择到指定三级分类</p>
                </dd>
            </dl>
            <dl id="enable_goods" style="display:<?php if($coupon[use_type] == 1): ?>;<?php else: ?>none;<?php endif; ?>">
                <dt class="tit">指定商品列表：</dt>
                <dd class="opt">
                    <table class="ncsc-default-table">
                        <thead>
                        <tr>
                            <th class="w80">商品ID</th>
                            <th class="w80">商品名称</th>
                            <th class="w80">价格</th>
                            <th class="w80">库存</th>
                            <th class="w80">操作</th>
                        </tr>
                        </thead>
                        <tbody id="goods_list">
                        <?php if(is_array($enable_goods) || $enable_goods instanceof \think\Collection || $enable_goods instanceof \think\Paginator): if( count($enable_goods)==0 ) : echo "" ;else: foreach($enable_goods as $key=>$vo): ?>
                            <tr>
                                <td style="display:none"><input type="checkbox" name="goods_id[]" class="goods_id" checked="checked" value="<?php echo $vo['goods_id']; ?>"/></td>
                                <td><?php echo $vo['goods_id']; ?></td>
                                <td><?php echo $vo['goods_name']; ?></td>
                                <td><?php echo $vo['shop_price']; ?></td>
                                <td><?php echo $vo['store_count']; ?></td>
                                <td class="nscs-table-handle">
                                    <span><a onclick="$(this).parent().parent().parent().remove();" class="btn-grapefruit"><i class="icon-trash"></i><p>删除</p></a></span>
                                </td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                    <span class="err" id="err_goods_id"></span>
                </dd>
            </dl>


            <dl class="row">
                <dt class="tit">
                    <label>状态</label>
                </dt>
                <dd class="opt">
                    <input name="status" type="radio" value="1" <?php if($coupon['status'] != 2): ?>checked<?php endif; ?> ><label>有效</label>
                    <input name="status" type="radio" value="2" <?php if($coupon['status'] == 2): ?>checked<?php endif; ?> ><label>无效</label>
                </dd>
            </dl>
                <div class="bot">
                    <?php if($coupon['use_start_time'] > time()): ?>
                        <a onclick="verifyForm();" class="ncap-btn-big ncap-btn-green">确认提交</a>
                    <?php else: ?>
                        <a class="ncap-btn-big">确认提交</a>
                    <?php endif; ?>
                </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $('.ctype ').find('input[type="radio"]').click(function(){
        if($(this).val() == 0 || $(this).val() == 4){
            $('.timed').hide();
        }else{
            $('.timed').show();
        }
    })

    $(document).ready(function(){
        $('.ctype ').find('input[type="radio"]:checked').trigger('click');

        laydate.render({
            elem: '#send_start_time',//绑定元素
            theme: 'molv', //主题
            type:'datetime', //控件选择类型
            format: 'yyyy-MM-dd HH:mm:ss', //自定义格式
            calendar: true, //显示公历节日
            min: '1970-01-01 00:00:00', //最小日期
            max: '2099-12-31 00:00:00', //最大日期
            // value: new Date(),//默认当前时间
            isInitValue: true,
            position : 'fixed', //定位方式
            zIndex: 99999999, //css z-index
        });
        laydate.render({
            elem: '#send_end_time',//绑定元素
            theme: 'molv', //主题
            type:'datetime', //控件选择类型
            format: 'yyyy-MM-dd HH:mm:ss', //自定义格式
            calendar: true, //显示公历节日
            min: '1970-01-01 00:00:00', //最小日期
            max: '2099-12-31 00:00:00', //最大日期
            // value: new Date(),//默认当前时间
            isInitValue: true,
            position : 'fixed', //定位方式
            zIndex: 99999999, //css z-index
        });

        laydate.render({
            elem: '#use_start_time',//绑定元素
            theme: 'molv', //主题
            type:'datetime', //控件选择类型
            format: 'yyyy-MM-dd HH:mm:ss', //自定义格式
            calendar: true, //显示公历节日
            min: '1970-01-01 00:00:00', //最小日期
            max: '2099-12-31 00:00:00', //最大日期
            // value: new Date(),//默认当前时间
            isInitValue: true,
            position : 'fixed', //定位方式
            zIndex: 99999999, //css z-index
        });
        laydate.render({
            elem: '#use_end_time',//绑定元素
            theme: 'molv', //主题
            type:'datetime', //控件选择类型
            format: 'yyyy-MM-dd HH:mm:ss', //自定义格式
            calendar: true, //显示公历节日
            min: '1970-01-01 00:00:00', //最小日期
            max: '2099-12-31 00:00:00', //最大日期
            // value: new Date(),//默认当前时间
            isInitValue: true,
            position : 'fixed', //定位方式
            zIndex: 99999999, //css z-index
        });


        <?php if($coupon['cat_id2'] > 0): ?>
            get_category("<?php echo $coupon['cat_id1']; ?>",'cat_id2',"<?php echo $coupon['cat_id2']; ?>");
        <?php endif; if($coupon['cat_id3'] > 0): ?>
                get_category("<?php echo $coupon['cat_id2']; ?>",'cat_id3',"<?php echo $coupon['cat_id3']; ?>");
        <?php endif; ?>
    })

    var ajax_return_status=1;
    function verifyForm(){
        if(ajax_return_status==0){
            return ;
        }
        ajax_return_status=0
        $('span.err').show();
        if ($('input[name="use_type"]:checked').val()==1){
            var goods =0;
            $('.goods_id').each(function(i,o){
                goods += 1;
            });
            if(goods<1){
                ajax_return_status=1;
                layer.alert("请选择活动商品");
                return;
            }
        }
        
        if ($('input[name="use_type"]:checked').val()==2){           
            if($('#cat_id3').val() == 0){
                ajax_return_status=1;
                layer.alert("请指定三级分类");
                return;
            }
        }
        
        
        $.ajax({
            type: "POST",
            url: "<?php echo U('Admin/Coupon/addEditCoupon'); ?>",
            data: $('#handleposition').serialize(),
            dataType: "json",
            success: function (data) {
                ajax_return_status=1;
                if (data.status == 1) {
                    layer.msg(data.msg, {icon: 1},function () {
                        location.href = "<?php echo U('Admin/Coupon/index'); ?>";
                    });
                } else {
                    layer.msg(data.msg, {icon: 2});
                    $.each(data.result, function (index, item) {
                        $('#err_' + index).text(item).show();
                    });
                }
            },
            error: function () {
                ajax_return_status=1;
                layer.alert("服务器繁忙, 请联系管理员!");
            },
        });
    }

    /**可使用商品**/
    //点击单选按钮
    function use_type_tab(v){
        if(v == 0){
            $('#goods_all_cate').hide();
            $('#enable_goods').hide();
            $('#goods_list').html('');
        }
        if(v == 1){
            $('#enable_goods').show()
            $('#goods_all_cate').hide();
        }
        if(v == 2){
            $('#goods_all_cate').show();
            $('#enable_goods').hide();
            $('#goods_list').html('');
        }
    }

    function selectGoods(){
        use_type_tab(1);
        var goods_id = [];
        //过滤选择重复商品
        $('.goods_id').each(function(i,o){
            goods_id += $(o).val()+',';
        });
        var url = '/index.php?m=admin&c=Promotion&a=search_goods&exvirtual=1&nospec=1&goods_id='+goods_id+'&t='+Math.random();
        layer.open({
            type: 2,
            title: '选择商品',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: url,
        });
    }
    function call_back(table_html)
    {
        layer.closeAll('iframe');
        var goods_list_html='';
        $.each(table_html, function (n, value) {
            goods_list_html += ' <tr>' +
                    '<td style="display:none"><input type="checkbox" name="goods_id[]" class="goods_id" checked="checked" value="'+value.goods_id+'"/></td>' +
                    '<td>'+value.goods_id+'</td><td>'+value.goods_name+'</td><td>'+value.goods_price+'</td>' +
                    '<td>'+value.store_count+'</td>' +
                    '<td class="nscs-table-handle"><span><a href="javascript:;" onclick="$(this).parent().parent().parent().remove();" class="btn-grapefruit"><i class="icon-trash"></i><p>删除</p></a></span></td>' +
                    '</tr>';
        });
        $('#goods_list').append(goods_list_html);
    }
</script>
</body>
</html>