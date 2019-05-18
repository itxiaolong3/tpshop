<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./application/admin/view/team\order_list.html";i:1558167985;s:51:"E:\tpshop\application\admin\view\public\layout.html";i:1540260088;}*/ ?>
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
                <h3>拼团订单</h3>
                <h5>商城拼团列表查询及管理</h5>
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
            <li>点击查看操作将显示订单（包括订单物品）的详细信息</li>
            <li>点击取消操作可以取消订单（在线支付但未付款的订单和货到付款但未发货的订单）</li>
            <li>如果平台已确认收到买家的付款，但系统支付状态并未变更，可以点击收到货款操作(仅限于下单后7日内可更改收款状态)，并填写相关信息后更改订单支付状态</li>
        </ul>
    </div>
    <div class="flexigrid">
        <div class="mDiv">
            <div class="ftitle">
                <h3>拼团订单</h3>
                <h5>(共<?php echo $page->totalRows; ?>条记录)</h5>
            </div>
            <div title="刷新数据" class="pReload"><i class="fa fa-refresh"></i></div>
            <form class="navbar-form form-inline" method="post" action="<?php echo U('Team/order_list'); ?>" name="search-form2" id="search-form2">
                <input type="hidden" name="order_by" value="order_id">
                <input type="hidden" name="sort" value="desc">
                <input type="hidden" name="user_id" value="<?php echo \think\Request::instance()->param('user_id'); ?>">
                <!--用于查看拼单 包含了哪些订单-->
                <input type="hidden" value="<?php echo \think\Request::instance()->param('found_id'); ?>" name="found_id"/>
                <input type="hidden" name="prom_type" value="6">
                <input type="hidden" name="order_ids" value="">
                <div class="sDiv">
                    <div class="sDiv2">
                        <input type="text" size="30" id="start_time" name="start_time" value="<?php echo $start_time; ?>" class="qsbox" placeholder="下单开始时间">
                    </div>
                    <div class="sDiv2">
                        <input type="text" size="30" id="end_time" name="end_time" value="<?php echo $end_time; ?>" class="qsbox" placeholder="下单结束时间">
                    </div>
                    <div class="sDiv2">
                        <select name="pay_status" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                            <option value="">支付状态</option>
                            <option value="0" <?php if($pay_status === '0'): ?>selected<?php endif; ?>>未支付</option>
                            <option value="1"<?php if($pay_status == 1): ?>selected<?php endif; ?>>已支付</option>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <select name="pay_code" class="select sDiv3" style="margin-right:5px;margin-left:5px">
                            <option value="">支付方式</option>
                            <option value="余额支付" <?php if('余额支付' == $pay_name): ?>selected<?php endif; ?>>余额支付</option>
                            <option value="其他方式" <?php if('' == $pay_name && '' == $pay_code): ?>selected<?php endif; ?>>其他方式</option>
                            <option value="积分兑换" <?php if('积分兑换' == $pay_name): ?>selected<?php endif; ?>>积分兑换</option>
                            <option value="alipay" <?php if('alipay' == $pay_code): ?>selected<?php endif; ?>>支付宝支付</option>
                            <option value="weixin" <?php if('weixin' == $pay_code): ?>selected<?php endif; ?>>微信支付</option>
                            <option value="miniAppPay" <?php if('miniAppPay' == $pay_code): ?>selected<?php endif; ?>>微信小程序支付</option>
                            <option value="unionpay" <?php if('unionpay' == $pay_code): ?>selected<?php endif; ?>>银联在线支付</option>
                            <option value="tenpay" <?php if('tenpay' == $pay_code): ?>selected<?php endif; ?>>PC端财付通</option>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <select name="shipping_status" class="select  sDiv3">
                            <option value="-1" <?php if($shipping_status == -1): ?>selected<?php endif; ?>>发货状态</option>
                            <?php if(is_array(\think\Config::get('SHIPPING_STATUS')) || \think\Config::get('SHIPPING_STATUS') instanceof \think\Collection || \think\Config::get('SHIPPING_STATUS') instanceof \think\Paginator): if( count(\think\Config::get('SHIPPING_STATUS'))==0 ) : echo "" ;else: foreach(\think\Config::get('SHIPPING_STATUS') as $sk=>$svo): ?>
                                <option value="<?php echo $sk; ?>" <?php if($shipping_status == $sk): ?>selected<?php endif; ?>><?php echo $svo; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <select name="order_status" class="select sDiv3">
                            <option value="-1" <?php if($order_status == -1): ?>selected<?php endif; ?>>订单状态</option>
                            <?php if(is_array(\think\Config::get('ORDER_STATUS')) || \think\Config::get('ORDER_STATUS') instanceof \think\Collection || \think\Config::get('ORDER_STATUS') instanceof \think\Paginator): $k = 0; $__LIST__ = \think\Config::get('ORDER_STATUS');if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?>
                                <option value="<?php echo $k-1; ?>" <?php if($order_status == $k-1): ?>selected<?php endif; ?>><?php echo $v; ?></option>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <select name="key_type" class="select">
                            <option value="consignee">收货人</option>
                            <option value="order_sn">订单编号</option>
                        </select>
                    </div>
                    <div class="sDiv2">
                        <input type="text" size="30" name="keywords" class="qsbox" placeholder="搜索相关数据...">
                    </div>
                    <div class="sDiv2">
                         <input type="button" onclick="$('#search-form2').attr('action', '<?php echo U('Team/order_list'); ?>');$('#search-form2').submit();" class="btn" value="搜索">
                    </div>
                </div>
            </form>
        </div>
        <div class="hDiv">
            <div class="hDivBox" id="ajax_return">
                <table cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="sign" axis="col0">
                            <div style="width: 24px;"><i class="ico-check"></i></div>
                        </th>
                        <th align="left" axis="col3" class="">
                            <div style="text-align: left; width: 130px;" class="">订单编号</div>
                        </th>
                        <th align="left" axis="col4" class="">
                            <div style="text-align: left; width: 120px;" class="">收货人(名字:电话)</div>
                        </th>
                        <th align="center" axis="col5" class="">
                            <div style="text-align: center; width: 60px;" class="">总金额</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">应付金额</div>
                        </th>
                        <th align="left" axis="col3" class="">
                            <div style="text-align: center; width: 50px;" class="">团员</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">拼团状态</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">订单状态</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">支付状态</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">发货状态</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">支付方式</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 60px;" class="">配送方式</div>
                        </th>
                        <th align="center" axis="col6" class="">
                            <div style="text-align: center; width: 120px;" class="">下单时间</div>
                        </th>
                        <th align="center" axis="col1" class="handle">
                            <div style="text-align: center; width: 100px;">操作</div>
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
	        <div class="fbutton"> <a href="javascript:exportReport()">
	          <div class="add" title="选定行数据导出excel文件,如果不选中行，将导出列表所有数据">
	            <span><i class="fa fa-plus"></i>导出数据</span>
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
                    <?php if(empty($orderList) || (($orderList instanceof \think\Collection || $orderList instanceof \think\Paginator ) && $orderList->isEmpty())): ?>
                        <tr data-id="0">
                            <td class="no-data" align="center" axis="col0" colspan="50">
                                <i class="fa fa-exclamation-circle"></i>没有符合条件的记录
                            </td>
                        </tr>
                    <?php else: if(is_array($orderList) || $orderList instanceof \think\Collection || $orderList instanceof \think\Paginator): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?>
                            <tr data-order-id="<?php echo $order['order_id']; ?>">
                                <td class="sign" axis="col0">
                                    <div style="width: 24px;"><i class="ico-check"></i></div>
                                </td>
                                <td align="left"  axis="col3" class="">
                                    <div style="text-align: left; width: 130px;" class=""><?php echo $order['order_sn']; ?></div>
                                </td>
                                <td align="left" axis="col4" class="">
                                    <div style="text-align: left; width: 120px;" class=""><?php echo $order['consignee']; ?>:<?php echo $order['mobile']; ?></div>
                                </td>
                                <td align="center" axis="col5" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['goods_price']; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['order_amount']; ?></div>
                                </td>
                                <td align="left" axis="col3" class="">
                                    <div style="text-align: center; width: 50px;" class="">
                                        <?php if(empty($order[team_found]) || (($order[team_found] instanceof \think\Collection || $order[team_found] instanceof \think\Paginator ) && $order[team_found]->isEmpty())): ?>
                                            团员
                                            <?php else: ?>
                                            拼主
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class="">
                                        <?php if(empty($order[team_found]) || (($order[team_found] instanceof \think\Collection || $order[team_found] instanceof \think\Paginator ) && $order[team_found]->isEmpty())): ?>
                                            <?php echo $order[team_follow][status_desc]; else: ?>
                                            <?php echo $order[team_found][status_desc]; endif; ?>
                                    </div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['order_status_detail']; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['pay_status_detail']; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['shipping_status_detail']; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo (isset($order['pay_name']) && ($order['pay_name'] !== '')?$order['pay_name']:'其他方式'); ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 60px;" class=""><?php echo $order['shipping_name']; ?></div>
                                </td>
                                <td align="center" axis="col6" class="">
                                    <div style="text-align: center; width: 120px;" class=""><?php echo date('Y-m-d H:i:s',$order['add_time']); ?></div>
                                </td>
                                <td align="center" axis="col1" class="handle">
                                    <div style="text-align: center; ">
                                        <a class="btn green" href="<?php echo U('Admin/order/detail',array('order_id'=>$order['order_id'])); ?>"><i class="fa fa-list-alt"></i>查看订单</a>
                                        <a class="btn green"  href="<?php echo U('Admin/Team/info',array('team_id'=>$order[teamActivity][team_id])); ?>"><i class="fa fa-search"></i>拼团活动</a>
                                        <?php if($order['team_found']['found_id'] > 0): ?>
                                            <a class="btn green"  href="<?php echo U('Admin/Team/order_list',array('found_id'=>$order['team_found']['found_id'])); ?>"><i class="fa fa-list-alt"></i>拼团成员</a>
                                            <?php else: ?>
                                            <a class="btn green"  href="<?php echo U('Admin/Team/order_list',array('found_id'=>$order['team_follow']['found_id'])); ?>"><i class="fa fa-list-alt"></i>拼团成员</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td align="" class="" style="width: 100%;">
                                    <div>&nbsp;</div>
                                </td>
                            </tr>
                        <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-6 text-left"></div>
                    <div class="col-sm-6 text-right"><?php echo $pager->show(); ?></div>
                </div>
            </div>
            <div class="iDiv" style="display: none;"></div>
        </div>
        <!--分页位置-->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('#start_time').layDate();
        $('#end_time').layDate();
        
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
    });
    // 点击刷新数据
    $('.fa-refresh').click(function () {
        location.href = location.href;
    });
	$('#flexigrid > table>tbody >tr').click(function(){
		$(this).toggleClass('trSelected');
	});
	function exportReport(){
        var selected_ids = '';
        $('.trSelected' , '#flexigrid').each(function(i){
            selected_ids += $(this).data('order-id')+',';
        });
        if(selected_ids != ''){
            $('input[name="order_ids"]').val(selected_ids.substring(0,selected_ids.length-1));
        }
        $('#search-form2').attr('action', '<?php echo U('Order/export_order'); ?>');
		$('#search-form2').submit();
	}
</script>
</body>
</html>