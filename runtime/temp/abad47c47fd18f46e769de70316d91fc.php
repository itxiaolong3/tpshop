<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./template/pc/rainbow/cart\header_cart_list.html";i:1540260094;}*/ ?>
<?php if(empty($cartList) || (($cartList instanceof \think\Collection || $cartList instanceof \think\Paginator ) && $cartList->isEmpty())): ?>
    <!--为空时-s-->
    <div class="empty-c">
        <span class="ma"><i class="c-i oh"></i>亲，购物车中没有商品哟~</span>
    </div>
    <!--为空时-e-->
    <?php else: ?>
    <!--现有商品时-->
    <div class="mn-c-m">
        <div class="mn-c-box">

            <?php if(is_array($cartList) || $cartList instanceof \think\Collection || $cartList instanceof \think\Paginator): $i = 0; $__LIST__ = $cartList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cart): $mod = ($i % 2 );++$i;?>
                <div class="c-store">
                    <div class="c-store-tt"><?php echo date("Y-m-d H:i:s",$cart['add_time']); ?></div>
                    <div class="c-sale-b" style="display:none">  <!-- 临时屏蔽 -->
                        <span class="i">[满减]</span>满299元减50元
                    </div>
                    <div class="c-item clearfix">
                        <a href="javascript:void(0);" class="del js_delete"
                           onclick="header_cart_del(<?php echo $cart['id']; ?>),ajax_side_cart_list();">×</a>
                        <a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$cart[goods_id])); ?>" class="goods-pic fl">
                            <img src="<?php echo goods_thum_images($cart['goods_id'],50,50); ?>" alt="" title="<?php echo $cart[goods_name]; ?>">
                        </a>
                        <div class="goods-cont fl">
                            <a href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$cart[goods_id])); ?>" class="goods-name"><?php echo $cart[goods_name]; ?></a>
                            <p class="num fl"> * <?php echo $cart[goods_num]; ?>件</p>
                            <p class="red fr">￥<?php echo $cart[member_goods_price]; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="mn-c-total">
                <div class="c-t">
                    <p class="t-n fl"><span class="red" id="total_qty"><?php echo $cartPriceInfo[goods_num]; ?></span>件</p>
                    <p class="t-p red fr"><em>￥</em><span id="total_pay"><?php echo $cartPriceInfo[total_fee]; ?></span></p>
                </div>
                <a href="<?php echo U('Home/Cart/index'); ?>" class="c-btn">去购物车结算 &gt;&gt;</a>
            </div>
        </div>
        <!--现有商品时-->
<?php endif; ?>
<script>
    $(".cart_quantity").text('<?php echo $cartPriceInfo[goods_num]; ?>'); // 购物车的总数量
</script>