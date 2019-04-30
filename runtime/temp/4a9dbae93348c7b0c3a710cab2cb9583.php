<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:44:"./application/admin/view/user\ajaxindex.html";i:1546823189;}*/ ?>
<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
    <table>
        <tbody>
        <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
            <tr data-id="<?php echo $list['user_id']; ?>">
                <td class="sign">
                    <div style="width: 24px;"><i class="ico-check"></i></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 40px;"><?php echo $list['user_id']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['nickname']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 50px;"><?php echo $level[$list['level']]; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 50px;"><?php echo $list['total_amount']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;"><?php echo $list['email']; if(($list['email_validated'] == 0) AND ($list['email'])): ?>
                            (未验证)
                        <?php endif; ?>
                    </div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo (isset($first_leader[$list[user_id]]['count']) && ($first_leader[$list[user_id]]['count'] !== '')?$first_leader[$list[user_id]]['count']:"0"); ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo (isset($second_leader[$list[user_id]]['count']) && ($second_leader[$list[user_id]]['count'] !== '')?$second_leader[$list[user_id]]['count']:"0"); ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo (isset($third_leader[$list[user_id]]['count']) && ($third_leader[$list[user_id]]['count'] !== '')?$third_leader[$list[user_id]]['count']:"0"); ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 80px;"><?php echo $list['mobile']; if(($list['mobile_validated'] == 0) AND ($list['mobile'])): ?>
                            (未验证)
                        <?php endif; ?>
                    </div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo $list['user_money']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo $list['pay_points']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;"><?php echo $list['deposit']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 120px;"><?php echo date('Y-m-d H:i',$list['reg_time']); ?></div>
                </td>
                <td align="center" class="handle">
                    <div style="text-align: center; width: 170px; max-width:250px;">
                        <a class="btn blue" href="<?php echo U('Admin/user/detail',array('id'=>$list['user_id'])); ?>"><i class="fa fa-pencil-square-o"></i>详情</a>
                        <a class="btn blue" href="<?php echo U('Admin/user/account_log',array('id'=>$list['user_id'])); ?>"><i class="fa fa-search"></i>资金</a>
                        <a class="btn blue" href="<?php echo U('Admin/user/address',array('id'=>$list['user_id'])); ?>"><i class="fa fa-steam"></i>收货地址</a>
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
<!--分页位置-->
<?php echo $pager->show(); ?>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
    $(document).ready(function(){
        // 表格行点击选中切换
        $('#flexigrid >table>tbody>tr').click(function(){
            $(this).toggleClass('trSelected');
        });
        $('#user_count').empty().html("<?php echo $pager->totalRows; ?>");
    });
    function delfun(obj) {
        // 删除按钮
        layer.confirm('确认删除？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.ajax({
                type: 'post',
                url: $(obj).attr('data-url'),
                data: {id : $(obj).attr('data-id')},
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        $(obj).parent().parent().parent().remove();
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            })
        }, function () {
        });
    }
</script>