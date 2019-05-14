<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./application/admin/view/system\cash.html";i:1552467485;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>提现设置</h3>
                <h5>网站全局内容基本选项设置</h5>
            </div>
            <ul class="tab-base nc-row">
                <?php if(is_array($group_list) || $group_list instanceof \think\Collection || $group_list instanceof \think\Paginator): if( count($group_list)==0 ) : echo "" ;else: foreach($group_list as $k=>$v): ?>
                    <li><a href="<?php echo U('System/index',['inc_type'=> $k]); ?>" <?php if($k==$inc_type): ?>class="current"<?php endif; ?>><span><?php echo $v; ?></span></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span id="explanationZoom" title="收起提示"></span></div>
        <ul>
            <!--<li>系统平台全局设置,包括基础设置、购物、短信、邮件、水印和分销等相关模块。</li>-->
        </ul>
    </div>
    <form method="post" enctype="multipart/form-data" name="form1" action="<?php echo U('System/handle'); ?>">
        <input type="hidden" name="inc_type" value="cash">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>提现配置</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="switch1" class="cb-enable  <?php if($config['cash_open'] == 1): ?>selected<?php endif; ?>">开启</label>
                        <label for="switch0" class="cb-disable <?php if($config['cash_open'] == 0): ?>selected<?php endif; ?>">关闭</label>
                        <input type="radio" onclick="$('#switch_on_off').show();"  id="switch1"  name="cash_open" value="1" <?php if($config['cash_open'] == 1): ?>checked="checked"<?php endif; ?>>
                        <input type="radio" onclick="$('#switch_on_off').hide();" id="switch0" name="cash_open" value="0" <?php if($config['cash_open'] == 0): ?>checked="checked"<?php endif; ?> >
                    </div>
                    <p class="notic">是否开启提现功能</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="reg_integral">手续费比例</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" id="service_ratio" name="service_ratio" value="<?php echo $config['service_ratio']; ?>" class="input-txt" type="text"> %
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：默认是百分比，如填1就是 代表每笔提现，收取提现金额1%的手续费）</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="reg_integral">最低手续费</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" id="min_service_money" name="min_service_money" value="<?php echo $config['min_service_money']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单笔手续费计算出来小于该值时，则取该值）</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="reg_integral">最高手续费</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" id="max_service_money" name="max_service_money" value="<?php echo $config['max_service_money']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单笔手续费计算出来大于该值时，则取该值,为0时则不限）</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="distribut_min">最低提现额</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" name="min_cash" id="min_cash" value="<?php echo $config['min_cash']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单笔最低提现额）</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="distribut_min">最高提现额</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" name="max_cash" id="max_cash" value="<?php echo $config['max_cash']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单笔最高提现额,必须大于0）</p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="distribut_min">每日累计提现额</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" name="count_cash" id="count_cash" value="<?php echo $config['count_cash']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单人每日累计提现额达到该值时，本日将不支持继续提现,为0时则不限）</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="distribut_min">每日累计提现次数</label>
                </dt>
                <dd class="opt">
                    <input onKeyUp="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" pattern="^\d{1,}$" name="cash_times" id="cash_times" value="<?php echo $config['cash_times']; ?>" class="input-txt" type="text">
                    <span class="err">只能输入整数</span>

                    <p class="notic">（注：单人每日累计提现次数达到该值时，本日将不支持继续提现,为0时则不限）</p>
                </dd>
            </dl>



            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" onclick="fromsubmit();">确认提交</a></div>
        </div>
    </form>
</div>
<script type="text/javascript">

    function fromsubmit(){
        var min_cash    =   parseInt($('#min_cash').val());   //每次最低提现额
        var max_cash    =   parseInt($('#max_cash').val());   //每次最高提现额
        var cash_times  =   parseInt($('#cash_times').val()); //提现次数
        var count_cash  =   parseInt($('#count_cash').val()); //每日累计提现额
        var service_ratio   =   parseInt($('#service_ratio').val());
        var min_service_money   =   parseInt($('#min_service_money').val());
        var max_service_money   =   parseInt($('#max_service_money').val());

        var cash_open = $("input[name='cash_open']:checked").val();
        if (cash_open == 1) {
            if(isNaN(min_cash) || isNaN(max_cash) || isNaN(cash_times) || isNaN(count_cash) || isNaN(service_ratio) || isNaN(min_service_money) || isNaN(max_service_money)){
                layer.msg('选项不能为空且必须为数字!', {icon: 2,time: 1000});
                return false;
            }
            if (service_ratio <= 0) {
                layer.msg('手续费比例必须大于0!', {icon: 2,time: 1000});
                return false;
            }
            if (service_ratio >= 100) {
                layer.msg('手续费比例必须小于100!', {icon: 2,time: 1000});
                return false;
            }
            if (min_service_money > 0 && min_service_money >= min_cash) {

                layer.msg('最低手续费必须小于最低提现额!', {icon: 2,time: 1000});
                return false;
            }
            if (min_service_money >= max_service_money && min_service_money > 0) {
                layer.msg('最低手续费不得大于最高手续费!', {icon: 2,time: 1000});
                return false;
            } else {
                if(min_service_money < 0 || max_service_money < 0){
                    layer.msg('最低或最高手续费不得小于0!', {icon: 2,time: 1000});
                    return false;
                }
            }

            if(min_cash >= max_cash && min_cash > 0){
                layer.msg('最低提现额必须小于最高提现额!', {icon: 2,time: 1000});
                return false;
            } else {
                if(min_cash < 0 || max_cash < 0){
                    layer.msg('最低或最高提现额不得小于0!', {icon: 2,time: 1000});
                    return false;
                }

            }
            if (max_cash <= 0) {

                layer.msg('最高提现额必须大于0!', {icon: 2,time: 1000});
                return false;
            }

            if (count_cash > 0 && max_cash > 0 && cash_times > 0) {
                if((max_cash * cash_times) > count_cash){
                    layer.msg('提现次数及最高提现额运算结果不得大于每日累计提现额度!', {icon: 2,time: 1000});
                    return false;
                }
            }

            if(count_cash < 0){
                layer.msg('每日累计提现额不能小于0!', {icon: 2,time: 1000});
                return false;
            }   
        }
     
        document.form1.submit();
    }

</script>
<div id="goTop">
    <a href="JavaScript:void(0);" id="btntop">
        <i class="fa fa-angle-up"></i>
    </a>
    <a href="JavaScript:void(0);" id="btnbottom">
        <i class="fa fa-angle-down"></i>
    </a>
</div>
</body>
</html>