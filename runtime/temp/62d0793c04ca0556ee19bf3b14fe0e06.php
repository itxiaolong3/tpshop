<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./application/admin/view/article\question.html";i:1556610303;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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

    .btn {
        display: inline-block;
        padding: 3px 12px;
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

    .ys-btn-close {
        position: relative !important;
        top: -12px;
        left: -16px;
        width: 18px;
        height: 18px;
        border: 1px solid #ccc;
        line-height: 18px;
        text-align: center;
        display: inline-block;
        border-radius: 50%;
        z-index: 1;
        background-color: #fff;
        cursor: pointer;
    }
</style>
<body style="background-color: #FFF; overflow: auto;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page" style="padding-top: 0px">
    <form method="post" id="form">
        <input type="hidden" name="id" value="<?php echo $goods_type['id']; ?>">
        <div class="ncap-form-default tab_div_1">
            <!--题目-->
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>题目标题:</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $goods_type['name']; ?>" name="name" class="input-txt" style="width:200px;"/>
                    <span class="err" id="err_name">题目标题不能为空!!</span>
                </dd>
            </dl>

            <!-- 答案列表s-->
            <div class="flexigrid">
                <div class="hDiv">
                    <div class="hDivBox">
                        <table cellpadding="0" cellspacing="0">
                            <thead>
                            <tr>
                                <th>
                                    <div style="text-align: center; width: 100px;">备选答案</div>
                                </th>
                                <th>
                                    <div style="text-align: center; width: 100px;">是否设为答案</div>
                                </th>
                                <th>
                                    <div style="text-align: center; width: 60px;">操作</div>
                                </th>
                                <th axis="col6">
                                    <div></div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="tDiv">
                    <div class="tDiv2">
                        <div class="fbutton">
                            <div class="add" title="新增备选答案" id="add_attribute"><span><i class="fa fa-plus"></i>新增答案</span></div>
                        </div>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div class="bDiv" style="height: auto;margin-bottom:20px;min-height:100px;">
                    <table class="table-bordered" cellpadding="0" cellspacing="0">
                        <tbody id="attribute_list">
                        <?php if(is_array($goods_type['goods_attribute']) || $goods_type['goods_attribute'] instanceof \think\Collection || $goods_type['goods_attribute'] instanceof \think\Paginator): $attribute_key = 0; $__LIST__ = $goods_type['goods_attribute'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$attribute): $mod = ($attribute_key % 2 );++$attribute_key;?>
                            <tr>
                                <input name="attribute[<?php echo $attribute_key-1; ?>][attr_id]" type="hidden" value="<?php echo $attribute['attr_id']; ?>">
                                <td>
                                    <div style="text-align: center; width: 100px;">
                                        <input type="text" class="w80" name="attribute[<?php echo $attribute_key-1; ?>][attr_name]" value="<?php echo $attribute['attr_name']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <div style="text-align: center; width: 100px;">
                                        <input type="hidden" name="attribute[<?php echo $attribute_key-1; ?>][is_answer]" value="<?php echo $attribute['is_answer']; ?>">
                                        <?php if($attribute['is_answer'] == 1): ?>
                                            <span class="yes is_attr_index"><i class="fa fa-check-circle"></i>是</span>
                                            <?php else: ?>
                                            <span class="no is_attr_index"><i class="fa fa-ban"></i>否</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="handle-s">
                                    <div style="text-align: center; width: 60px;"><a href="javascript:void(0);" data-id="<?php echo $attribute['attr_id']; ?>" class="btn red delete_attribute"><i class="fa fa-trash-o"></i>删除</a></div>
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
            <!-- 答案列表s-->
        </div>

        <div class="ncap-form-default">
            <div class="bot"><a id="submit" class="ncap-btn-big ncap-btn-green" href="JavaScript:void(0);">确认提交</a></div>
        </div>
    </form>
</div>

<!--添加答案模板-->
<table id="spec_attribute_div" style="display: none">
    <tbody>

    </tbody>
</table>
<!--添加题目模板end -->
<script>
    //添加答案
    $(document).on('click', '#add_attribute', function () {
        var attribute_list = $('#attribute_list');
        var attribute_length = attribute_list.find('tr').length;
        var attribute_item_div = '<tr data-index='+attribute_length+'> <td> <div style="text-align: center; width: 100px;">' +
                '<input type="text" class="w80" name="attribute['+attribute_length+'][attr_name]" value=""></div> </td> ' +
                '<td> <div style="text-align: center; width: 100px;"><input type="hidden" name="attribute['+attribute_length+'][is_answer]" value="0">' +
                '<span class="no is_attr_index"><i class="fa fa-check-circle"></i>否</span></div> </td><td class="handle-s"> ' +
                '<div style="text-align: center; width: 60px;"><a href="javascript:void(0);" class="btn red delete_attribute">' +
                '<i class="fa fa-trash-o"></i>删除</a></div> </td> <td style="width: 100%;"> <div>&nbsp;</div> </td> </tr>';
        attribute_list.append(attribute_item_div);
    });
    //删除答案
    $(document).on('click', '.delete_attribute', function () {
        var obj = $(this);
        if (obj.data('id') > 0) {
            layer.open({
                content: '确认删除已存在的答案吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    layer.close(index);
                    $.ajax({
                        type: "POST",
                        url: '/index.php?m=Admin&c=Article&a=deleteAttribute',
                        data: {attr_id: obj.data('id')},
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 1) {
                                obj.parent().parent().parent().remove();
                            } else {
                                layer.open({icon: 2, content: data.msg});
                            }
                        }
                    });
                }
                ,btn2: function(index, layero){
                    layer.close(index);
                }
                ,cancel: function(){
                    //右上角关闭回调
                    layer.close();
                }
            });

        } else {
            obj.parent().parent().parent().remove();
        }
    });
    $(document).on('click', '#submit', function () {
        $.ajax({
            type: "POST",
            url: '/index.php?m=Admin&c=Article&a=saveType',
            data: $('#form').serialize(),
            dataType: "json",
            success: function (data) {
                if(data.status == 1){
                    window.parent.save_type_call_back(data.type_id);
                }else{
                    layer.open({icon: 2, content: data.msg});
                }
            }
        });
    });
    //答案是否显示
    $(document).on('click', '.is_attr_index', function () {
        if($(this).hasClass('no')){
            $(this).removeClass('no').addClass('yes').html("<i class='fa fa-check-circle'></i>是");
            $(this).parent().find('input').val(1);
        }else{
            $(this).removeClass('yes').addClass('no').html("<i class='fa fa-ban'></i>否");
            $(this).parent().find('input').val(0);
        }
    });

</script>
</body>
</html>