<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:49:"./application/admin/view/user\ajaxindexforjg.html";i:1555913220;}*/ ?>
<div id="flexigrid" cellpadding="0" cellspacing="0" border="0">
    <table>
        <tbody>
        <?php if(is_array($userList) || $userList instanceof \think\Collection || $userList instanceof \think\Paginator): $i = 0; $__LIST__ = $userList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
            <tr data-id="<?php echo $list['user_id']; ?>">
                <td class="sign">
                    <div style="width: 24px;"><i class="ico-check"></i></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 40px;height: 70px;line-height: 70px;"><?php echo $list['mechanism_id']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 150px;height: 70px;line-height: 70px;"><?php echo $list['company_name']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 120px;height: 70px;line-height: 70px;"><?php echo $list['social_code']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 80px;height: 70px;line-height: 70px;"><?php echo $list['username']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 80px;height: 70px;line-height: 70px;"><?php echo $list['phone']; ?></div>
                </td>

                <td align="left" class="">
                    <div style="text-align: center; width: 180px;height: 70px;line-height: 70px;"><?php echo $list['idcard']; ?></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 80px;height: 70px;line-height: 70px;"><a href="<?php echo $list['yinyep_img']; ?>" target="_blank"><img src="<?php echo $list['yinyep_img']; ?>" width="60px" height="60px"/></a></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 80px;height: 70px;line-height: 70px;"><a href="<?php echo $list['idcard_img']; ?>" target="_blank"><img src="<?php echo $list['idcard_img']; ?>" width="60px" height="60px"/></a></div>
                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 60px;height: 70px;line-height: 70px;">
                        <?php if($list['auditing'] == 0): ?>待审核
                            <?php elseif($list['auditing'] == 1): ?>已审核
                            <?php else: ?>不通过
                        <?php endif; ?>
                    </div>

                </td>
                <td align="left" class="">
                    <div style="text-align: center; width: 120px;height: 70px;line-height: 70px;"><?php echo date('Y-m-d H:i',$list['addtime']); ?></div>
                </td>
                <td align="center" class="handle">
                    <div style="text-align: center; width: 200px; max-width:250px;height:70px;display: flex;align-items: center;">
                        <a class="btn blue" href="<?php echo U('Admin/user/add_mechanism',array('mechanism_id'=>$list['mechanism_id'])); ?>"><i class="fa fa-pencil-square-o"></i>详情/修改</a>
                        <?php if(($list['auditing'] == 0) or ($list['auditing'] == 2)): ?>
                            <a class="btn blue" href="javascript:void(0);" onclick="pass(<?php echo $list['mechanism_id']; ?>)"><i class="fa fa-pencil-square-o"></i>通过</a>
                            <?php elseif($list['auditing'] == 1): ?>
                            <a class="btn blue" href="javascript:void(0);" onclick="refus(<?php echo $list['mechanism_id']; ?>)"><i class="fa fa-pencil-square-o"></i>拒绝</a>
                            <?php else: endif; ?>

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
    function pass(id) {
        // 通过按钮
        layer.confirm('确认执行通过操作？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            console.log(id,'已通过')
            $.ajax({
                type: 'post',
                url: "<?php echo U('Admin/User/sh_mechanism'); ?>",
                data: {id :id,type:1},
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        window.location.reload();
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            })
        }, function () {
            console.log(id,'取消通过')
            layer.closeAll();
        });
    }
    function refus(id) {
        // 通过按钮
        layer.confirm('确认执行拒绝操作？', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            console.log(id,'已拒绝')
            $.ajax({
                type: 'post',
                url: "<?php echo U('Admin/User/sh_mechanism'); ?>",
                data: {id :id,type:0},
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.status == 1) {
                        window.location.reload();
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            })
        }, function () {
            console.log(id,'取消拒绝')
            layer.closeAll();
        });
    }
</script>