<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./application/admin/view/team\index.html";i:1558143453;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
				<h3>拼团管理</h3>
				<h5>网站系统拼团活动审核与管理</h5>
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
			<li>拼团管理, 由平台设置管理.</li>
		</ul>
	</div>
	<div class="flexigrid">
		<div class="mDiv">
			<div class="ftitle">
				<h3>拼团活动列表</h3>
				<h5>(共<?php echo $pager->totalRows; ?>条记录)</h5>
                <!--<div class="fbutton" style="margin-left: 30px;">-->
                    <!--<a href="http://help.tp-shop.cn/Index/Help/info/cat_id/5/id/496.html" target="_blank">-->
                        <!--<div class="" title="帮助">-->
                            <!--<span>帮助</span>-->
                        <!--</div>-->
                    <!--</a>-->
                <!--</div>-->
			</div>
			<a href=""><div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div></a>
		</div>
		<div class="hDiv">
			<div class="hDivBox">
				<table cellspacing="0" cellpadding="0">
					<thead>
					<tr>
						<th class="sign" axis="col0">
							<div style="width: 24px;"><i class="ico-check"></i></div>
						</th>
						<th align="left" abbr="article_title" axis="col3" class="">
							<div style="text-align: left; width: 50px;" class="">拼团ID</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 100px;" class="">拼团标题</div>
						</th>
						<th align="left" abbr="ac_id" axis="col4" class="">
							<div style="text-align: center; width: 240px;" class="">拼团商品</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">拼团类型</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">成团有效期</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">需要成团人数</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">购买限制数</div>
						</th>
						<th align="center" abbr="article_time" axis="col6" class="">
							<div style="text-align: center; width: 80px;" class="">是否启动</div>
						</th>
						<th align="left" axis="col1" class="handle">
							<div style="text-align: center; width: 240px;">操作</div>
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
				<a href="<?php echo U('Team/info'); ?>">
					<div class="fbutton">
						<div title="添加拼团" class="add">
							<span><i class="fa fa-plus"></i>添加拼团</span>
						</div>
					</div>
				</a>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="bDiv" style="height: auto;">
			<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
				<table>
					<tbody>
					<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$team): $mod = ($i % 2 );++$i;?>
						<tr>
							<td class="sign">
								<div style="width: 24px;"><i class="ico-check"></i></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 50px;"><?php echo $team['team_id']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: left; width: 100px;"><?php echo getSubstr($team['act_name'],0,20); ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 240px;">
										<?php echo getSubstr($team['goods_name'],0,20); ?>
								</div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $team['team_type_desc']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $team['time_limit_hours']; ?>小时</div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $team['needer']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;"><?php echo $team['buy_limit']; ?></div>
							</td>
							<td align="left" class="">
								<div style="text-align: center; width: 80px;">
									<?php if($team[status] == 1): ?>
										<span class="yes" onClick="changeTableVal('team_activity','team_id','<?php echo $team['team_id']; ?>','status',this,'是','否')" ><i class="fa fa-check-circle"></i>是</span>
										<?php else: ?>
										<span class="no" onClick="changeTableVal('team_activity','team_id','<?php echo $team['team_id']; ?>','status',this,'是','否')" ><i class="fa fa-ban"></i>否</span>
									<?php endif; ?>
								</div>
							</td>
							<td align="left" class="handle">
								<div style="text-align: left; width: 240px; max-width:240px;">
									<?php if($team[team_type] == 2 AND $team[is_lottery] == 0): ?>
										<a class="btn blue gift_button" data-team-id="<?php echo $team['team_id']; ?>"><i class="fa fa-gift"></i>抽奖</a>
									<?php endif; if($team[team_type] == 2 AND $team[is_lottery] == 1): ?>
										<a class="btn blue" href="<?php echo U('Mobile/Team/lottery',['team_id'=>$team['team_id']]); ?>" ><i class="fa fa-file-text-o"></i>中奖名单</a>
									<?php endif; ?>
									<a class="btn blue" href="<?php echo U('Team/team_list',array('team_id'=>$team['team_id'])); ?>" ><i class="fa fa-list"></i>订单</a>
									<a class="btn blue" href="<?php echo U('Team/info',array('team_id'=>$team['team_id'])); ?>"><i class="fa fa-pencil-square-o"></i>编辑</a>
									<a class="btn red" data-url="<?php echo U('Team/delete'); ?>" data-id="<?php echo $team['team_id']; ?>" onclick="delfun(this)"><i class="fa fa-trash-o"></i>删除</a>
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
		<?php echo $page; ?> </div>
</div>
<script>
	$(document).ready(function(){
		// 表格行点击选中切换
		$('#flexigrid > table>tbody >tr').click(function(){
			$(this).toggleClass('trSelected');
		});

		// 点击刷新数据
		$('.fa-refresh').click(function(){
			location.href = location.href;
		});
	});

	function delfun(obj) {
		layer.confirm('确认删除？有关拼团将会失效', {
					btn: ['确定', '取消'] //按钮
				}, function () {
					$.ajax({
						type: 'post',
						url: $(obj).attr('data-url'),
						data: {team_id: $(obj).attr('data-id')},
						dataType: 'json',
						success: function (data) {
							layer.closeAll();
							if (data.status == 1) {
								layer.msg(data.msg, {icon: 1});
								$(obj).parent().parent().parent().remove();
							} else {
								layer.msg(data.msg, {icon: 2, time: 2000});
							}
						}
					})
				}, function (index) {
					layer.close(index);
				}
		);
	}

	//抽奖
	$(function () {
		$(document).on("click", '.gift_button', function (e) {
			var team_id = $(this).data('team-id');
			layer.open({
				content: '确认抽奖将从该拼团活动下拼团成功的订单里抽取，并把中奖的订单确认，未中奖的订单都执行退款操作,并且结束该活动。该操作不可逆，确定要执行吗？'
				,btn: ['确定', '取消']
				,yes: function(index, layero){
					layer.close(index);
					$.ajax({
						type: "POST",
						url: "<?php echo U('Team/lottery'); ?>",//+tab,
						data: {team_id: team_id},
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