<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view/goods\type_list.html";i:1546652454;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>商品模型</h3>
                <h5>商品模型列表</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style="color: rgb(44, 188, 163); background-color: rgb(237, 251, 248); width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>商品模型是用来规定某一类商品共有规格和属性的集合，其中规格会影响商品价格，同一个商品不同的规格价格会不同，而属性仅仅是商品的属性特质展示</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>模型列表</h3>
                <h5>(共<?php echo $page->totalRows; ?>条记录)</h5>

                <!--<div class="fbutton">-->
                    <!--<a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/33.html" target="_blank">-->
                        <!--<div class="add" title="帮助">-->
                            <!--<span>帮助</span>-->
                        <!--</div>-->
                    <!--</a>-->
                <!--</div>-->
            </div>
            <form action="<?php echo U('Goods/type_list'); ?>" class="navbar-form form-inline" method="get">
                <div class="sDiv">
                    <div class="sDiv2">
                        <input size="30" name="name" class="qsbox" placeholder="模型名称" type="text" value="<?php echo \think\Request::instance()->param('name'); ?>">
                        <input class="btn" value="搜索" type="submit">
                    </div>
                </div>
            </form>
            <a href="javascript:location.reload();">
                <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            </a>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th align="left" abbr="article_title" axis="col3" class="">
                            <div style="text-align: left; width: 100px;" class="">ID</div>
                        </th>
                        <th align="left" abbr="ac_id" axis="col4" class="">
                            <div style="text-align: left; width: 150px;" class="">模型名称</div>
                        </th>
                        <th align="center" axis="col1" class="handle">
                            <div style="text-align: center; width: 350px;">操作</div>
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
                <div class="fbutton">
                    <a class="type_info">
                        <div class="add" title="新增商品模型">
                            <span><i class="fa fa-plus"></i>新增商品模型</span>
                        </div>
                    </a>
                </div>
            </div>
            <div style="clear:both"></div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid">
                <table>
                    <tbody>
                    <?php if(is_array($goods_type_list) || $goods_type_list instanceof \think\Collection || $goods_type_list instanceof \think\Paginator): $i = 0; $__LIST__ = $goods_type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_type): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td align="left" class="">
                                <div style="text-align: left; width: 100px;"><?php echo $goods_type['id']; ?></div>
                            </td>
                            <td align="left" class="">
                                <div style="text-align: left; width: 150px;"><?php echo $goods_type['name']; ?></div>
                            </td>
                            <td align="center" class="handle">
                                <div style="text-align: center; width: 270px; max-width:350px;">
                                    <a href="javascript:void(0)" data-id="<?php echo $goods_type['id']; ?>" class="btn blue type_info"><i class="fa fa-pencil-square-o"></i>编辑</a>
                                    <a href="javascript:void(0)" data-id="<?php echo $goods_type['id']; ?>" class="btn red delete_type" ><i class="fa fa-trash-o"></i>删除</a>
                                </div>
                            </td>
                            <td align="" class="" style="width: 100%;">
                                <div>&nbsp;</div>
                            </td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->
        <?php echo $page->show(); ?>
    </div>
</div>
<script>
    $(document).ready(function () {
        // 点击刷新数据
        $('.fa-refresh').click(function () {
            location.href = location.href;
        });
    });
    $(document).on('click', '.delete_type', function () {
        var type_id = $(this).data('id');
        delete_type(type_id);
    });
    $(document).on('click', '.type_info', function () {
        var type_id = $(this).data('id');
        add_edit_type(type_id);
    });

    function delete_type(type_id) {
        $.ajax({
            type: "POST",
            url: '/index.php?m=Admin&c=Goods&a=deleteType',
            data: {id: type_id},
            dataType: "json",
            success: function (data) {
                if(data.status == 1){
                    layer.open({icon: 1, content: data.msg, time: 1000,end:function(){
                        location.reload();
                    }});
                }else{
                    layer.open({icon: 2, content: data.msg, time: 1000});
                }
            }
        });
    }

    function add_edit_type(type_id) {
        var url = '/index.php?m=Admin&c=Goods&a=type';
        if(type_id){
            url += '&id='+type_id;
        }
        layer.open({
            type: 2,
            title: '添加/编辑商品模型',
            shadeClose: true,
            shade: 0.2,
            area: ['1065px', '664px'],
            content: url,
        });
    }

    function save_type_call_back()
    {
        layer.closeAll();
        location.reload();
    }
</script>
</body>
</html>