<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:49:"./application/admin/view/integral_mall\index.html";i:1545268493;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<style type="text/css">
    .ncap-form-default dl.row{ border-color: #fff; }
    .row{margin-bottom: 20px;}
    .opt{margin-left: 16%; margin-top: 20px;}
    .son{margin-left: 18%;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>积分商城管理</h3>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span></div>
        <ul>
            <li>输入的积分必须为整数</li>
        </ul>
    </div>
    <form method="post" enctype="multipart/form-data" id="handleposition" name="form1" action="<?php echo U('IntegralMall/handle'); ?>">
        <input type="hidden" name="inc_type" value="integral">
        <div class="ncap-form-default">
            <dl class="row">
                <dd class="opt">
                    <span style="color: #ff0000;">*</span>   积分过期设置
                </dd>
                <dd class="opt">
                    <input type="radio" name="is_integral_expired" value="1" <?php if($confArr['is_integral_expired'] == 1): ?>checked<?php endif; ?> >    一直有效，永不过期
                </dd>
                <dd class="opt">
                    <input type="radio" name="is_integral_expired" value="2" <?php if($confArr['is_integral_expired'] == 2): ?>checked<?php endif; ?> >
                    每年  &nbsp;
                    <select name="month" id="month" onchange="changeData()">
                        <?php $__FOR_START_1556__=1;$__FOR_END_1556__=13;for($i=$__FOR_START_1556__;$i < $__FOR_END_1556__;$i+=1){ ?>
                            <option value="<?php echo $i; ?>" <?php if($confArr['expired_time'][0] == $i): ?>selected<?php endif; ?>><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                    &nbsp;月  &nbsp;
                    <select name="day" id="day">
                        <?php $__FOR_START_5884__=1;$__FOR_END_5884__=32;for($i=$__FOR_START_5884__;$i < $__FOR_END_5884__;$i+=1){ ?>
                            <option value="<?php echo $i; ?>" <?php if($confArr['expired_time'][1] == $i): ?>selected<?php endif; ?> ><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                    &nbsp;日，凌晨0：00，清零之前的所有积分
                </dd>
            </dl>
            <!--<dl class="row">-->
                <!--<dd class="opt">-->
                    <!--积分赠送规则-->
                <!--</dd>-->
                <!--&lt;!&ndash;<dd class="opt">&ndash;&gt;-->
                    <!--&lt;!&ndash;<input type="checkbox" name="is_consume_integral" value="1" <?php if($confArr['is_consume_integral'] == 1): ?>checked<?php endif; ?> >      商城内每消费1元，赠送 <input type="text" name="consume_integral" size="8" value="<?php echo $confArr['consume_integral']; ?>"> 积分&ndash;&gt;-->
                <!--&lt;!&ndash;</dd>&ndash;&gt;-->
                <!--<dd class="opt">-->
                    <!--<input type="checkbox" name="is_reg_integral" value="1" <?php if($confArr['is_reg_integral'] == 1): ?>checked<?php endif; ?>>      首次注册登录，可获得  <input type="text" name="reg_integral" size="8" value="<?php echo $confArr['reg_integral']; ?>"> 积分-->
                <!--</dd>-->
                <!--<dd class="opt">-->
                    <!--<input type="checkbox" name="invite" value="1" <?php if($confArr['invite'] == 1): ?>checked<?php endif; ?>>      每成功邀请1位好友注册，邀请人可获得  <input type="text" name="invite_integral" size="8" value="<?php echo $confArr['invite_integral']; ?>"> 积分，被邀请人可获得 <input type="text" name="invitee_integral" size="8" value="<?php echo $confArr['invitee_integral']; ?>"> 积分-->
                <!--</dd>-->
                <!--<span class="err" id="err_give"></span>-->
            <!--</dl>-->
            <dl class="row">
                <dd class="opt">
                    <span style="color: #ff0000;">*</span>   积分交易抵扣规则
                </dd>
                <dd class="opt">
                    <input type="radio" name="is_use_integral" value="0" <?php if($confArr['is_use_integral'] == 0): ?>checked<?php endif; ?> >     不能使用积分
                </dd>
                <dd class="opt">
                    <input type="radio" name="is_use_integral" value="1" <?php if(($confArr['is_use_integral'] == 1) or ($confArr['is_use_integral'] == '')): ?>checked<?php endif; ?>>     可以使用积分
                </dd>
                <dd class="opt son">
                    <input type="checkbox" name="is_point_min_limit" value="1" <?php if($confArr['is_point_min_limit'] == 1): ?>checked<?php endif; ?>>     积分小于 <input type="text" name="point_min_limit" size="8" value="<?php echo $confArr['point_min_limit']; ?>"> 时 ，不能使用积分
                </dd>
                <dd class="opt son">
                    <input type="checkbox" name="is_point_rate" value="1"
                    <?php 
                        if($confArr["is_point_rate"]==1 || !isset($confArr)){
                            echo "checked";
                        }
                     ?>
                    >     消费时，积分可抵扣订单金额，每 <!--<input type="text" name="point_rate" size="8" value="<?php echo $confArr['point_rate']; ?>">-->
                    <select name="point_rate">
                        <option value="">请选择</option>
                        <option value="1" <?php if($confArr['point_rate'] == 1): ?>selected<?php endif; ?> >1</option>
                        <option value="10" <?php if(($confArr['point_rate'] == 10) or ($confArr['point_rate'] == '')): ?>selected<?php endif; ?>>10</option>
                        <option value="100" <?php if($confArr['point_rate'] == 100): ?>selected<?php endif; ?>>100</option>
                    </select>
                    积分抵扣1元
                    <span style="color: #e30000">   &nbsp;&nbsp;（注：此项若不勾选，则不能使用积分）</span>
                </dd>
                <dd class="opt son">
                    <input type="checkbox" name="is_point_use_percent" value="1" <?php if($confArr['is_point_use_percent'] == 1): ?>checked<?php endif; ?> >     每笔消费，抵扣比例最多不能超过该笔订单应付金额的 <input type="text" name="point_use_percent" size="8" value="<?php echo $confArr['point_use_percent']; ?>"> %
                    <span style="color: #e30000">   &nbsp;&nbsp;（注：范围在1-100之间，没勾选则可100%抵扣）</span>
                </dd>
                <span class="err" id="err_deductible"></span>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" onclick="verifyForm()" class="ncap-btn-big ncap-btn-green" >确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    function verifyForm(){
        $.ajax({
            type: "POST",
            url: "<?php echo U('Admin/IntegralMall/handle'); ?>",
            data: $('#handleposition').serialize(),
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
            },
            success: function (data) {
                if (data.status == 1) {
                    layer.msg(data.msg, {icon: 1, time: 2500}, function(){
                        location.reload();
                    });
                } else {
                    layer.msg(data.msg, {icon: 2,time: 2500});
                }
            }
        });
    }
    function changeData(){
        var month = $("#month").val();
        var date = new Date();
        var nowMonth = date.getMonth();
        //当前月份大于2，则用下一年的月份
        if(nowMonth > 2){
            var nowYear = date.getFullYear() + 1;
        }else {
            var nowYear = date.getFullYear();
        }
        var new_date = new Date(nowYear,month,1);
        var date_count =   (new Date(new_date.getTime()-1000*60*60*24)).getDate();
        var dateStr = '';
        for(var i= 1;i<=date_count;i++){
            dateStr += '<option value="'+i+'">'+i+'</option>';
        }
        $("#day").html(dateStr);
    }
</script>
</body>
</html>