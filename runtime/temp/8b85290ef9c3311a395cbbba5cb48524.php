<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./application/admin/view/team\team_list.html";i:1558171402;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
<script type="text/javascript" src="/public/static/js/layer/laydate/laydate.js"></script>

<body style="background-color: rgb(255, 255, 255); overflow: auto; cursor: default; -moz-user-select: inherit;">
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>拼团列表</h3>
                <h5>商城拼团拼单查询及管理</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div id="explanation" class="explanation" style=" width: 99%; height: 100%;">
        <div id="checkZoom" class="title"><i class="fa fa-lightbulb-o"></i>
            <h4 title="提示相关设置操作时应注意的要点">操作提示</h4>
            <span title="收起提示" id="explanationZoom" style="display: block;"></span>
        </div>
        <ul>
            <li>查看操作可以查看拼团成员, 包括拼团成员订单信息, 拼团详情等</li>
            <li>未成团的拼单可执行退款操作</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>拼团拼单列表</h3>
                <h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <form class="navbar-form form-inline" method="get" action="<?php echo U('Team/team_list'); ?>" target="_self">
                <div class="sDiv">
                    <div class="sDiv2">
                        <input type="text" size="30" name="team_id" value="<?php echo \think\Request::instance()->param('team_id'); ?>" class="qsbox"  placeholder="拼团ID">
                    </div>
                    <div class="sDiv2">
                        <input type="text" size="30" id="add_time_begin" name="add_time_begin" value="<?php echo $add_time_begin; ?>" class="qsbox"  placeholder="开团开始时间">
                    </div>
                    <div class="sDiv2">
                        <input type="text" size="30" id="add_time_end" name="add_time_end" value="<?php echo $add_time_end; ?>" class="qsbox"  placeholder="开团开始时间">
                    </div>
                    <div class="sDiv2">
                        <select name="status" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                            <option value="">所有拼单</option>
                            <option value="0" <?php if(\think\Request::instance()->param('status') === 0): ?>selected="selected"<?php endif; ?>>待开团</option>
                            <option value="1" <?php if(\think\Request::instance()->param('status') == 1): ?>selected="selected"<?php endif; ?>>待成团</option>
                            <option value="2" <?php if(\think\Request::instance()->param('status') == 2): ?>selected="selected"<?php endif; ?>>已成团</option>
                            <option value="3" <?php if(\think\Request::instance()->param('status') == 3): ?>selected="selected"<?php endif; ?>>未成团</option>
                        </select>
                    </div>
                    <!--<div class="sDiv2">-->
                        <!--<input type="text" size="30" name="order_sn" class="qsbox" value="<?php echo \think\Request::instance()->param('order_sn'); ?>" placeholder="拼主订单编号">-->
                    <!--</div>-->
                    <div class="sDiv2">
                        <input type="submit" class="btn" value="搜索">
                    </div>
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th axis="col0">
                            <div style="width: 24px;"><i class="ico-check"></i></div>
                        </th>
                        <th align="left" axis="col3" class="">
                            <div style="text-align: left; width: 140px;" class="">拼主</div>
                        </th>
                        <th align="center" axis="col5" class="">
                            <div style="text-align: center; width: 200px;" class="">拼团标题</div>
                        </th>
                        <th align="center" axis="col5" class="">
                            <div style="text-align: center; width: 200px;" class="">拼团商品</div>
                        </th>
                        <th align="center" axis="col5" class="">
                            <div style="text-align: center; width: 80px;" class="">拼团类型</div>
                        </th>
                        <th align="left" axis="col4" class="">
                            <div style="text-align: center; width: 200px;" class="">订单编号</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">订单交易状态</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 80px;" class="">已拼人员</div>
                        </th>
                        <th align="left" axis="col1" class="handle">
                            <div style="text-align: center; width: 150px;">操作</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="bDiv" style="height: auto;">
            <div id="flexigrid">
                <table>
                    <tbody>
                    <?php if(empty($teamFound) || (($teamFound instanceof \think\Collection || $teamFound instanceof \think\Paginator ) && $teamFound->isEmpty())): ?>
                        <tr data-id="0">
                            <td class="no-data" align="center" axis="col0" colspan="50">
                                <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                            </td>
                        </tr>
                        <?php else: if(is_array($teamFound) || $teamFound instanceof \think\Collection || $teamFound instanceof \think\Paginator): $i = 0; $__LIST__ = $teamFound;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$found): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td class="sign" axis="col0">
                                    <div style="width: 24px;"><i class="ico-check"></i></div>
                                </td>
                                <td align="left" axis="col3" class="">
                                    <div style="text-align: left; width: 140px;" class=""><?php echo $found[nickname]; ?></div>
                                </td>
                                <td align="left" axis="col4" class="">
                                    <div style="text-align: center; width: 200px;" class="">
                                        <a class="open" href="<?php echo U('Team/info',['team_id'=>$found[team_id]]); ?>" target="blank">
                                            <?php echo $found[team_activity][act_name]; ?><i class="fa fa-external-link " title="拼团详情"></i>
                                        </a>
                                    </div>
                                </td>
                                <!--<td align="center" axis="col5" class="">-->
                                    <!--<div style="text-align: center; width: 200px;" class="">-->
                                        <!--<a class="open" href="<?php echo U('Mobile/Team/info',['team_id'=>$found[team_id]]); ?>" target="blank">-->
                                            <!--<?php echo $found[order_goods][goods_name]; ?><i class="fa fa-external-link " title="手机拼团详情页"></i>-->
                                        <!--</a>-->
                                    <!--</div>-->
                                <!--</td>-->
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 80px;" class=""><?php echo $found[team_activity][team_type_desc]; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 200px;" class=""><?php echo $found[order][order_sn]; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 80px;" class=""><?php echo $found[order][pay_status_detail]; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 80px;" class=""><?php echo $found[join]; ?>人</div>
                                </td>
                                <td align="left" axis="col1" class="handle">
                                    <div style="text-align: left; ">
                                        <?php if($found[team_activity][team_type] == 0 OR $found[team_activity][team_type] == 1): if($found[order][order_status] == 0): if($found[Surplus] <= 0): ?>
                                                    <a href="javascript:void(0)" class="btn green confirm_found" data-found-id="<?php echo $found[found_id]; ?>"><i class="fa fa-check"></i>确认拼团</a>
                                                    <?php else: ?>
                                                    <a class="btn" style="background-color: #D870AD;color: #FFF;box-shadow: none;">还差<?php echo $found[Surplus]; ?>人</a>
                                                <?php endif; elseif($found[order][order_status] == 3): else: ?>
                                                <a class="btn" style="background-color: #FB6E52;color: #FFF;box-shadow: none;">已确认</a>
                                            <?php endif; endif; if($found[order][order_status] == 3 AND $found[team_activity][is_lottery] != 1): ?>
                                            <a class="btn" style="background-color: #FB6E52;color: #FFF;box-shadow: none;">已取消</a>
                                        <?php endif; if($found[order][pay_status] == 1 AND $found[status] == 3 AND $found[order][order_status] == 0): ?>
                                            <a href="javascript:void(0)" class="btn red refund_found" data-found-id="<?php echo $found[found_id]; ?>"><i class="fa fa-jpy"></i>退款</a>
                                        <?php endif; if($found[team_activity][team_type] == 2): if($found[team_activity][is_lottery] == 1): ?>
                                                <a class="btn belize-hole" href="<?php echo U('Mobile/Team/lottery',['team_id'=>$found[team_id]]); ?>" ><i class="fa fa-play"></i>已开奖</a>
                                                <?php else: ?>
                                                <a class="btn belize-hole" href="<?php echo U('Mobile/Team/lottery',['team_id'=>$found[team_id]]); ?>" ><i class="fa fa-pause"></i>等待抽奖</a>
                                            <?php endif; endif; if($found[order][order_status] >= 1 AND $found[team_activity][team_type] == 1): ?>
                                            <a class="btn green" href="<?php echo U('Admin/Team/bonus',array('found_id'=>$found['found_id'])); ?>"><i class="fa fa-gavel"></i>团长佣金</a>
                                        <?php endif; ?>
                                        <a class="btn green" href="<?php echo U('Team/team_found',['found_id'=>$found[found_id]]); ?>" ><i class="fa fa-list-alt"></i>拼团成员</a>
                                    </div>
                                </td>
                                <td style="width: 100%;">
                                    <div></div>
                                </td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo $page; ?></div>
                </div>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->

    </div>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $('#add_time_begin').layDate();
        $('#add_time_end').layDate();
    });

    // 点击刷新数据
    $('.fa-refresh').click(function(){
        location.href = location.href;
    });
    $('.ico-check ' , '.hDivBox').click(function(){
        $('tr' ,'.hDivBox').toggleClass('trSelected' , function(index,currentclass){
            var hasClass = $(this).hasClass('trSelected');
            $('tr' , '#flexigrid').each(function(){
                if(hasClass){
                    $(this).addClass('trSelected');
                }else{
                    $(this).removeClass('trSelected');
                }
            });
        });
    });

    //确认拼团
    $(function () {
        $(document).on("click", '.confirm_found', function (e) {
            var found_id = $(this).data('found-id');
            layer.open({
                content: '确认拼团将把该拼团下的订单都确认，该操作不可逆，确定要执行吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    layer.close(index);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo U('Team/confirmFound'); ?>",//+tab,
                        data: {found_id: found_id},
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                                    window.location.reload();
                                });
                            } else {
                                layer.msg(data.msg, {icon: 2, time: 2000});
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
        })
    })
    //拼团退款
    $(function () {
        $(document).on("click", '.refund_found', function (e) {
            var found_id = $(this).data('found-id');
            layer.open({
                content: '退款将把该拼团下的订单都提交退款至平台，该操作不可逆，确定要执行吗？'
                ,btn: ['确定', '取消']
                ,yes: function(index, layero){
                    layer.close(index);
                    $.ajax({
                        type: "POST",
                        url: "<?php echo U('Team/refundFound'); ?>",//+tab,
                        data: {found_id: found_id},
                        dataType: 'json',
                        success: function (data) {
                            if (data.status == 1) {
                                layer.msg(data.msg, {icon: 1, time: 2000}, function(){
                                    window.location.reload();
                                });
                            } else {
                                layer.msg(data.msg, {icon: 2, time: 2000});
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
        })
    })

</script>
</body>
</html>