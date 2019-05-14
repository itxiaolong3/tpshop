<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./application/admin/view/user\level.html";i:1557805812;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<body style="background-color: #FFF; overflow: auto;">
<div id="toolTipLayer" style="position: absolute; z-index: 9999; display: none; visibility: visible; left: 95px; top: 573px;"></div>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="javascript:history.back();" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>会员等级管理 - 编辑会员等级</h3>
                <h5>网站系统会员等级管理</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="handleposition" method="post">
        <input type="hidden" name="act" value="<?php echo $act; ?>">
        <input type="hidden" name="level_id" value="<?php echo $info['level_id']; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="level_name"><em>*</em>等级名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="level_name" value="<?php echo $info['level_name']; ?>" id="level_name" class="input-txt">
                    <span class="err" id="err_level_name"></span>
                    <p class="notic">设置会员等级名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="level_name"><em>*</em>用户身份</label>
                </dt>
                <dd class="opt">
                    <select name="level" id="suppliers_id" class="small form-control">
                        <option value="0" <?php if($info['level'] == 0): ?>selected="selected"<?php endif; ?>>普通用户</option>
                        <option value="1" <?php if($info['level'] == 1): ?>selected="selected"<?php endif; ?>>vip会员</option>
                        <option value="2" <?php if($info['level'] == 2): ?>selected="selected"<?php endif; ?>>推广人</option>
                        <option value="3" <?php if($info['level'] == 3): ?>selected="selected"<?php endif; ?>>代言人</option>
                    </select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">消费金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="amount" value="<?php echo $info['amount']; ?>" id="amount" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_amount"></span>
                    <p class="notic">达到该等级，会员一次性需充值金额,单位：元</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">配卡数量</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="cardnum" value="<?php echo $info['cardnum']; ?>" id="cardnum" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_cardnum"></span>
                    <p class="notic">该等级所配送的卡数量</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">分享有礼一级分佣比例(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="shareone" value="<?php echo $info['shareone']; ?>" id="shareone" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_shareone"></span>
                    <p class="notic">一级分佣比例</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">分享有礼二级分佣比例(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="sharetwo" value="<?php echo $info['sharetwo']; ?>" id="sharetwo" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_sharetwo"></span>
                    <p class="notic">二级分佣比例</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">零售有礼一级分佣比例(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="goodone" value="<?php echo $info['goodone']; ?>" id="goodone" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_goodone"></span>
                    <p class="notic">一级分佣比例</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="amount">零售有礼二级分佣比例(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="goodtwo" value="<?php echo $info['goodtwo']; ?>" id="goodtwo" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">
                    <span class="err" id="err_goodtwo"></span>
                    <p class="notic">二级分佣比例</p>
                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label for="amount">保证金</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="text" name="deposit" value="<?php echo $info['deposit']; ?>" id="deposit" class="input-txt" onkeyup="this.value=/^\d+\.?\d{0,2}$/.test(this.value) ? this.value : ''">-->
                    <!--<span class="err" id="err_deposit"></span>-->
                    <!--<p class="notic">达到该等级，经销商以上升级所需缴纳保证金,单位：元</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <!--<dl class="row">-->
                <!--<dt class="tit">-->
                    <!--<label for="discount"><em>*</em>折扣率</label>-->
                <!--</dt>-->
                <!--<dd class="opt">-->
                    <!--<input type="text" name="discount" value="<?php echo $info['discount']; ?>" id="discount" class="input-txt" onkeyup="this.value=this.value.replace(/[^\d]/g,'')">-->
                    <!--<span class="err" id="err_discount"></span>-->
                    <!--<p class="notic">折扣率单位为百分比，如输入90，表示该会员等级的用户可以以商品原价的90%购买</p>-->
                <!--</dd>-->
            <!--</dl>-->
            <dl class="row">
                <dt class="tit">
                    等级描述
                </dt>
                <dd class="opt">
                    <textarea  name="describe" class="tarea" rows="6"><?php echo $info['describe']; ?></textarea>
                    <span class="err" id="err_describe"></span>
                    <p class="notic">会员等级描述信息</p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="verifyForm()" class="ncap-btn-big ncap-btn-green" id="submitBtn">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function verifyForm(){
        $('span.err').show();
        $.ajax({
            type: "POST",
            url: "<?php echo U('Admin/User/levelHandle'); ?>",
            data: $('#handleposition').serialize(),
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.msg, {icon: 1});
                    location.href = "<?php echo U('Admin/User/levelList'); ?>";
                } else {
                    layer.msg(data.msg, {icon: 2});
                    $.each(data.result, function (index, item) {
                        $('#err_' + index).text(item).show();
                    });
                }
            }
        });
    }
</script>
</body>
</html>