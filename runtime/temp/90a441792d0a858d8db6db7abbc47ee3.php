<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:55:"./application/admin/view/promotion\prom_order_info.html";i:1552526151;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
    dd.opt table{
        width: 100%;
    }
    dd.opt tr{
        border: 1px solid #f4f4f4;
        padding: 8px;
    }
    dd.opt tr td{
        border: 1px solid #f4f4f4;
    }
</style>
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="/public/plugins/Ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/plugins/Ueditor/lang/zh-cn/zh-cn.js"></script>
<script src="/public/static/js/layer/laydate/laydate.js"></script>
<style type="text/css">
    html, body {
        overflow: visible;
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
                <h3>优惠促销管理 - 编辑商品促销活动</h3>
                <h5>网站系统编辑商品促销活动</h5>
            </div>
        </div>
    </div>
    <form class="form-horizontal" id="promotion"  method="post">
        <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
        <input type="hidden" name="act" value="<?php echo $act; ?>">
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>促销活动名称</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="name" id="name" value="<?php echo $info['name']; ?>" class="input-txt">
                    <p class="notic">促销活动名称</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>促销活动类型</label>
                </dt>
                <dd class="opt">
                    <select id="prom_type" name="type">
                        <option value="0" <?php if($info[type] == 0): ?>selected<?php endif; ?>>直接打折</option>
                        <option value="1" <?php if($info[type] == 1): ?>selected<?php endif; ?>>减价优惠</option>
                        <option value="2" <?php if($info[type] == 2): ?>selected<?php endif; ?>>满额送积分</option>
                        <option value="3" <?php if($info[type] == 3): ?>selected<?php endif; ?>>买就赠代金券</option>
                    </select>
                    <p class="notic">促销活动类型</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>需要满足的金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="money" id="money" value="<?php echo $info['money']; ?>"  onpaste="this.value=this.value.replace(/[^\d.]/g,'')" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt">
                    <p class="notic">单位:元</p>
                </dd>
            </dl>
            <dl class="row" id="expression_dl">
                <dt class="tit">
                    <label><em>*</em>折扣</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="expression" name="expression"  value="<?php echo $info['expression']; ?>" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" class="input-txt">
                    <p class="notic">% 折扣值(1-100 如果打9折，请输入90)</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>开始时间</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="start_time" name="start_time" value="<?php echo date('Y-m-d H:i:s', $info['start_time']); ?>"  class="input-txt">
                    <p class="notic">优惠开始时间</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>结束时间</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="end_time" name="end_time" value="<?php echo date('Y-m-d H:i:s', $info['end_time']); ?>" class="input-txt">
                    <p class="notic">优惠结束时间</p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>活动描述</label>
                </dt>
                <dd class="opt">
                    <textarea class="span12 ckeditor" placeholder="请输入活动介绍" id="post_content" name="description" rows="6"><?php echo $info['description']; ?></textarea>
                    <p class="notic">活动描述</p>
                </dd>
            </dl>
            <?php if($info['is_edit'] == 0): ?>
                <div class="bot"><a class="ncap-btn-big">确认提交</a></div>
            <?php else: ?>

            <dl class="row" txt="发布活动时，进行通知">
                <dt class="tit">站内信通知</dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="mmt_message_switch1" class="cb-enable selected">是</label>
                        <label for="mmt_message_switch0" class="cb-disable ">否</label>
                        <input id="mmt_message_switch1" name="mmt_message_switch" checked="checked" value="1" type="radio">
                        <input id="mmt_message_switch0" name="mmt_message_switch" value="0" type="radio">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            
                <div class="bot"><a onclick="verifyForm()" class="ncap-btn-big ncap-btn-green">确认提交</a></div>
            <?php endif; ?>
        </div>
    </form>
</div>
<script type="text/javascript">
    var url="<?php echo url('Admin/Ueditor/index',array('savePath'=>'activity')); ?>";
    var ue = UE.getEditor('post_content',{
        serverUrl :url,
        zIndex: 999,
        initialFrameWidth: "100%", //初化宽度
        initialFrameHeight: 350, //初化高度            
        focus: false, //初始化时，是否让编辑器获得焦点true或false
        maximumWords: 99999, removeFormatAttributes: 'class,style,lang,width,height,align,hspace,valign',//允许的最大字符数 'fullscreen',
        pasteplain:false, //是否默认为纯文本粘贴。false为不使用纯文本粘贴，true为使用纯文本粘贴
        autoHeightEnabled: true
    });
    var ajax_return_srtatus=1;
    function verifyForm(){
        if(ajax_return_srtatus==0){
            return false;
        }
        if (parseInt($('#money').val())<=0){
            ajax_return_srtatus=1;
            layer.msg('需要满足的金额必须大于0', {icon: 2,time: 1000});
            return;
        }
        var type = parseInt($("#prom_type").val());
        var expression = parseInt($("input[name='expression']").val());
        var money = parseInt($('#money').val());
        if(expression > money && type == 1){
            ajax_return_srtatus=1;
            layer.msg('需要满足的金额必须大于优惠金额', {icon: 2,time: 1000});
            return;
        }
        if (expression <=0 ||expression ==''){
            var msg = '';
            switch(type){
                case 0:{msg='折扣值输入错误！';break;}
                case 1:{msg='优惠金额输入错误!';break;}
                case 2:{msg='积分额度输入错误' ;break;}
            }
            ajax_return_srtatus=1;
            layer.msg(msg, {icon: 2,time: 1000});
            return
        }
        ajax_return_srtatus=0;
        $('span.err').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo U('Admin/Promotion/prom_order_save'); ?>",
            data: $('#promotion').serialize(),
            async:false,
            dataType: "json",
            error: function () {
                layer.alert("服务器繁忙, 请联系管理员!");
                ajax_return_srtatus=1
            },
            success: function (data) {
                console.log(data);
                if (data.status == 1) {
                    layer.msg(data.msg, {icon: 1, time: 1000}, function(){
                        location.href = "<?php echo U('Promotion/prom_order_list'); ?>";
                    });
                } else {
                    ajax_return_srtatus=1;
                    layer.msg(data.msg, {icon: 2,time: 2000});
                }
            }
        });
    }
    function selectGoods(){
        var goods_id = [];
        //过滤选择重复商品
        $('input[name*="goods_id"]').each(function(i,o){
            goods_id += $(o).val()+',';
        });
        var url = '/index.php?m=Admin&c=Promotion&a=search_goods&goods_id='+goods_id+'&t='+Math.random();
        layer.open({
            type: 2,
            title: '选择商品',
            shadeClose: true,
            shade: 0.3,
            area: ['70%', '80%'],
            content: url,
        });
    }

    $("#prom_type").on("change",function(){
        var type = parseInt($("#prom_type").val());
        var expression = '';
        switch(type){
            case 0:{
                expression = '<dt class="tit"><label><em>*</em>折扣</label></dt>'
                        + '<dd class="opt"><input type="text" name="expression" pattern="([1-9]\\d?|100)" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="" class="input-txt">'
                        + '<p class="notic">% 折扣值(1-100 如果打9折，请输入90)</p></dd>';
                break;
            }
            case 1:{
                expression = '<dt class="tit"><label><em>*</em>优惠金额</label></dt>'
                        + '<dd class="opt"><input type="text" name="expression" pattern="float" onkeyup="this.value=this.value.replace(/[^\\d.]/g,\'\')" value="" class="input-txt">'
                        + '<p class="notic">立减金额（元）</p></dd>';
                break;
            }
            case 2:{
                expression = '<dt class="tit"><label><em>*</em>积分</label></dt>'
                        + '<dd class="opt"><input type="text" name="expression" onkeyup="this.value=this.value.replace(/[^\\d]/g,\'\')" pattern="int" value="" class="input-txt">'
                        + '<p class="notic">订单送积分额度</p></dd>';
                break;
            }
            case 3:{
                expression = '<dt class="tit"><label><em>*</em>代金券</label></dt><dd class="opt"><select name="expression">'
                        + '<?php
                                   
                                $md5_key = md5("select * from __PREFIX__coupon where type=0 AND use_end_time > $template_now_time");
                                $result_name = $sql_result_v = S("sql_".$md5_key);
                                if(empty($sql_result_v))
                                {                            
                                    $result_name = $sql_result_v = \think\Db::query("select * from __PREFIX__coupon where type=0 AND use_end_time > $template_now_time"); 
                                    S("sql_".$md5_key,$sql_result_v,1);
                                }    
                              foreach($sql_result_v as $key=>$v): ?><option value="<?php echo $v['id']; ?>" <?php if($v[id] == $info[expression]): ?>selected<?php endif; ?>><?php echo $v['name']; ?></option><?php endforeach; ?></select>'
                        + '</dd>';
                break;
            }
            case 4:{
                expression = '';
                break;
            }
        }
        $("#expression_dl").html(expression);
    });
    $(function () {
        $("#promotion").validate({
            debug: false, //调试模式取消submit的默认提交功能
            focusInvalid: false, //当为false时，验证无效时，没有焦点响应
            onkeyup: false,
            submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form
                if($('input[name="group\[\]"]:checked').length ==0){
                    layer.alert('请选择一个适合用户范围', {icon: 2});
                    return ;
                }else{
                    form.submit();   //提交表单
                }
            },
            submitHandler: function(form){   //表单提交句柄,为一回调函数，带一个参数：form
                var start_time=Date.parse($('#start_time').val());
                var end_time=Date.parse($('#end_time').val());
                if(start_time>=end_time){
                    layer.msg('开始时间不得大于结束时间',{icon:2});
                    return false;
                }else{
                    form.submit();   //提交表单
                }
            },
            ignore:":button",	//不验证的元素
            rules:{
                name:{
                    required:true
                },
                money:{
                    required:true
                },
                expression:{
                    required:true
                },
                start_time:{
                    required:true
                },
                end_time:{
                    required:true
                },
            },
            messages:{
                name:{
                    required:"请填写名称"
                },
                money:{
                    required:"请填写金额"
                },
                expression:{
                    required:"请选择选项"
                },
                start_time:{
                    required:"请选择时间"
                },
                end_time:{
                    required:"请选择时间"
                },
            }
        });
    })
    $(document).ready(function(){
        $("#prom_type").trigger('change');
        $('input[name=expression]').val("<?php echo $info['expression']; ?>");

        laydate.render({
            elem: '#start_time',//绑定元素
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
            elem: '#end_time',//绑定元素
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

    })
</script>
</body>
</html>