<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:40:"./template/mobile/rainbow/team\info.html";i:1540260093;s:52:"E:\tpshop\template\mobile\rainbow\public\header.html";i:1540260093;s:53:"E:\tpshop\template\mobile\rainbow\public\top_nav.html";i:1540260093;s:54:"E:\tpshop\template\mobile\rainbow\public\wx_share.html";i:1540260093;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo $team_activity['share_title']; ?>--<?php echo $tpshop_config['shop_info_store_title']; ?></title>
    <link rel="stylesheet" href="/template/mobile/rainbow/static/css/style.css">
    <link rel="stylesheet" type="text/css" href="/template/mobile/rainbow/static/css/iconfont.css"/>
    <script src="/template/mobile/rainbow/static/js/jquery-3.1.1.min.js" type="text/javascript" charset="utf-8"></script>
    <!--<script src="/template/mobile/rainbow/static/js/zepto-1.2.0-min.js" type="text/javascript" charset="utf-8"></script>-->
    <script src="/template/mobile/rainbow/static/js/style.js" type="text/javascript" charset="utf-8"></script>
    <script src="/template/mobile/rainbow/static/js/mobile-util.js" type="text/javascript" charset="utf-8"></script>
    <script src="/public/js/global.js"></script>
    <script src="/template/mobile/rainbow/static/js/layer.js"  type="text/javascript" ></script>
    <script src="/template/mobile/rainbow/static/js/swipeSlide.min.js" type="text/javascript" charset="utf-8"></script>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo (isset($tpshop_config['shop_info_store_ico']) && ($tpshop_config['shop_info_store_ico'] !== '')?$tpshop_config['shop_info_store_ico']:'/public/static/images/logo/storeico_default.png'); ?>" media="screen"/>
</head>
<body class="[body]">

<style>
    .plus span.disable{
        cursor: default;
        color: #e9e9e9;
    }
    .shop-top-under .shulges .choic-sel a.disable {
        cursor: not-allowed;
        padding: .21333rem .46933rem;
        border: 1px dashed #DEDEDE;
        margin-right: .21333rem;
        font-size: .68267rem;
        color: #DEDEDE;
        margin-bottom: .42667rem;
        display: block;
        float: left;
    }
    .plusshopcar-buy .dis{
        background: #ebebeb;
        color: #999;
        cursor: not-allowed;
        pointer-events:none;
    }
</style>
<div class="he_sustain">
    <div class="classreturn loginsignup detail">
        <div class="content">
            <div class="ds-in-bl return">
                <a href="javascript:history.back(-1)"><img src="/template/mobile/rainbow/static/images/return.png" alt="返回"></a>
            </div>
            <div class="ds-in-bl search center">
                <span class="sxp">商品</span>
                <span>详情</span>
                <span>评论</span>
            </div>
            <!--<a class="btn-share-ico" id="share_button" href="javascript:;"></a>-->
        </div>
    </div>
</div>
<!--顶部隐藏菜单-s-->
<div class="flool up-tpnavf-wrap tpnavf [top-header]">
    <div class="footer up-tpnavf-head">
    	<div class="up-tpnavf-i"> </div>
        <ul>
            <li>
                <a class="yello" href="<?php echo U('Index/index'); ?>">
                    <div class="icon">
                        <i class="icon-shouye iconfont"></i>
                        <p>首页</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Goods/categoryList'); ?>">
                    <div class="icon">
                        <i class="icon-fenlei iconfont"></i>
                        <p>分类</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('Cart/index'); ?>">
                    <div class="icon">
                        <i class="icon-gouwuche iconfont"></i>
                        <p>购物车</p>
                    </div>
                </a>
            </li>
            <li>
                <a href="<?php echo U('User/index'); ?>">
                    <div class="icon">
                        <i class="icon-wode iconfont"></i>
                        <p>我的</p>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
<script type="text/javascript">
$(function(){	
	$(window).scroll(function() {		
		if($(window).scrollTop() >= 1){ 
			$('.tpnavf').hide()
		}
	})
})
</script>
<!--顶部隐藏菜单-e-->
<div class="xq_details action-detail">
    <div class="banner ban1 detailban">
        <div class="mslide" id="slideTpshop">
            <ul>
                <?php if(is_array($team_activity['goods']['goods_images']) || $team_activity['goods']['goods_images'] instanceof \think\Collection || $team_activity['goods']['goods_images'] instanceof \think\Paginator): if( count($team_activity['goods']['goods_images'])==0 ) : echo "" ;else: foreach($team_activity['goods']['goods_images'] as $key=>$pic): ?>
                    <li><a href="javascript:void(0)"><img src="<?php echo $pic[image_url]; ?>"></a></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
        </div>
        <!--
        <div class="jump-message">
            <div class="litsbe">
                <div class="yigsmrecan">
                    <img class="juminm" src="../images/mq.jpg"/>
							<span class="juminn">
								最新订单来自重庆的<em class="jumname">美沁</em>,<em>1</em>秒前
							</span>
                </div>
            </div>
        </div>
        -->
    </div>
    <div class="de_font p">
        <div class="thirty">
            <div class="fl">
                <span class="similar-product-text"><?php echo $team_activity[goods_name]; ?></span>
            </div>
            <div class="scunde p">
                <p class="red">￥<span class="team_price"><?php echo $team_activity['team_goods_item'][0]['team_price']; ?></span><a class="attengro"><?php echo $team_activity['front_status_desc']; ?></a></p>

                <p class="shdicc">市场价格：<span class="linethr"><?php echo $team_activity['goods']['market_price']; ?></span></p>
                <p class="shdicc"><?php echo $team_activity['share_desc']; ?></p>
            </div>
        </div>
    </div>
    <div class="floor list7 g7">
        <div class="myhearders myorder actino-her ma-to-20" style="height: 1.92rem;">
            <div class="scgz descgz">
                <ul>
                    <li>
                        <a href="javascript:void(0);">
                            <img src="/template/mobile/rainbow/static/images/ag.png">

                            <p>品质保障</p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <img src="/template/mobile/rainbow/static/images/ah.png">

                            <p>放心物流</p>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <img src="/template/mobile/rainbow/static/images/ai.png">

                            <p>贴心服务</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
            <div class="yixgp have_founds" style="display: none">
                <div class="maleri30">
                    <span>以下小伙伴正在发起团购，您可以直接参与</span>
                </div>
            </div>
            <div class="lesgcan have_founds" style="display: none">
                <div class="maleri30" id="found_list">
                </div>
            </div>
        <div class="shartogete ma-to-20">
            <div class="maleri30">
                <h2>拼团规则</h2>
                <ul class="forneu">
                    <li>
                        <span class="aroundu">1</span>
                        <span>选择商品</span>
                    </li>
                    <li>
                        <span class="aroundu">2</span>
                        <span>开团/参团</span>
                    </li>
                    <li>
                        <span class="aroundu">3</span>
                        <span>邀请好友</span>
                    </li>
                    <li>
                        <span class="aroundu">4</span>
                        <span>人满成团</span>
                    </li>
                </ul>
                <p class="fohe"><i class="action-al"></i></p>
            </div>
        </div>
        <div class="hs_acion">
            <div class="maleri30">
                <p>1.开团：在商城内选择喜欢的商品，点击“去开团”，付款成功后即为开团成功；</p>

                <p>2.参团：进入朋友分享的页面，点击“立即参团”，付款后即为参团成功，若多人同时支付，按先支付成功的用户获得参团资格；</p>

                <p>3.成团：在开团或参团之后,可以点击“分享出去”，在有效时间凑齐成团人数即拼团成功；</p>

                <p>4.组团失败：在有效时间内未凑齐人数，即为组团失败，此时商城会将原款分别退回；</p>
            </div>
        </div>
    </div>
</div>
<!--详情-s-->
<div class="xq_details" style="display: none;">
    <div class="spxq-ggcs">
        <ul>
            <li class="red">商品详情</li>
            <li>规格参数</li>
        </ul>
    </div>
    <div class="sg">
        <div class="spxq p">
            <?php echo htmlspecialchars_decode($team_activity['goods']['goods_content']); ?>
        </div>
    </div>
    <div class="sg" style="display: none;">
        <div class="spxq p">
            <table class="de_table" border="1" bordercolor="#cbcbcb" style="border-collapse:collapse;">
                <tr>
                    <th colspan="2">主体</th>
                </tr>
                <?php if(is_array($team_activity['goods']['goods_attr']) || $team_activity['goods']['goods_attr'] instanceof \think\Collection || $team_activity['goods']['goods_attr'] instanceof \think\Paginator): $i = 0; $__LIST__ = $team_activity['goods']['goods_attr'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$goods_attr): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $goods_attr['goods_attribute']['attr_name']; ?></td>
                        <td><?php echo $goods_attr['attr_value']; ?></td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
        </div>
    </div>
</div>
<!--详情-e-->
<div class="xq_details" style="display: none;">
    <div class="spxq-ggcs comment_de p">
        <ul>
            <!--1 全部 2好评 3 中评 4差评-->
            <li class="red">全部评价 <br/><span ctype="1"><?php echo $team_activity['goods']['comment_statistics']['total_sum']; ?></span></li>
            <li>好评 <br /><span ctype="2"><?php echo $team_activity['goods']['comment_statistics']['high_sum']; ?></span></li>
            <li>中评 <br /><span ctype="3"><?php echo $team_activity['goods']['comment_statistics']['center_sum']; ?></span></li>
            <li>差评 <br /><span ctype="4"><?php echo $team_activity['goods']['comment_statistics']['low_sum']; ?></span></li>
            <li>有图 <br /><span ctype="5"><?php echo $team_activity['goods']['comment_statistics']['img_sum']; ?></span></li>
        </ul>
    </div>
    <div class="tab-con-wrapper my_comment_list"></div>
</div>
<div class="podee actionfooer">
    <div class="cart-concert-btm p">
        <div class="fl">
            <ul>
                <li>
                    <a href="<?php echo U('Index/index'); ?>">
                        <em class="ico ico-index6"></em>
                        <p>首页</p>
                    </a>
                </li>
                <li>
                    <a href="javascript:collect_goods(<?php echo $team_activity['goods_id']; ?>);" id="favorite">
                        <em class="ico ico-heart <?php if($collect > 0): ?>ico-heart-h<?php endif; ?>" ></em>
                        <p>收藏</p>
                    </a>
                </li>
                <li>
                    <?php if((!empty($tpshop_config['basic_im_choose'])) && ($tpshop_config['basic_im_choose'] == 1)): ?>
                        <!--im客服-->
                        <a class="kf" href="<?php echo U('supplier/index',array('goods_id'=>$goods['goods_id'])); ?>">
                            <em class="ico ico-kf6"></em>
                            <p>客服</p>
                        </a>
                        <?php elseif((!empty($tpshop_config['basic_im_choose'])) && ($tpshop_config['basic_im_choose'] == 2)): ?>
                        <!--小能客服-->
                        <a href="javascript:;">
                            <em class="ico ico-kf6"></em>
                            <p>客服</p>
                        </a>
                        <?php else: ?>
                        <!--早先客服-->
                        <a href="tel:<?php echo $tpshop_config['shop_info_phone']; ?>">
                            <em class="ico ico-kf6"></em>
                            <p>客服</p>
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <div class="fr">
            <ul>
                <li class="o">
                    <a href="<?php echo U('Mobile/Goods/goodsInfo',['id'=>$team_activity[goods_id]]); ?>">
                        单独购买<br/>
                        ￥<em><?php echo $team_activity['goods'][shop_price]; ?></em>
                    </a>
                </li>
                <li class="r lottery" style="display: none">
                    <a href="<?php echo U('Mobile/Team/lottery',['team_id'=>$team_activity['team_id']]); ?>">查看中奖名单
                        <br/>
                        ￥<em class="team_price"><?php echo $team_activity['team_goods_item'][0]['team_price']; ?></em>
                    </a>
                </li>
                <li class="r no_lottery">
                    <a href="javascript:void(0);" class="choise_num">
                        <em><?php echo $team_activity['needer']; ?></em>人团<br/>
                        ￥<em class="team_price"><?php echo $team_activity['team_goods_item'][0]['team_price']; ?></em>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!--点赞弹窗-s-->
<div class="alert">
    <img src="/template/mobile/rainbow/static/images/hh.png"/>

    <p>您已经赞过了！</p>
</div>
<!--点赞弹窗-e-->

<!--所选弹窗-s-->
<form name="buy_goods_form" method="post" id="buy_goods_form">
    <input type="hidden" name="goods_id" value="<?php echo $team_activity['goods_id']; ?>"/>
    <input type="hidden" name="item_id" value="<?php echo (\think\Request::instance()->param('item_id') ?: 0); ?>"/>
    <input type="hidden" name="spec_goods_price" value='<?php echo json_encode($team_activity['goods']['spec_goods_price'],true); ?>' disabled>
    <input type="hidden" name="team_id" value="<?php echo $team_activity['team_id']; ?>" disabled/>
    <input type="hidden" name="is_lottery" value="<?php echo $team_activity['is_lottery']; ?>" disabled/>
    <div class="choose_shop_aready p" id="choose_spec">
        <div class="shop-top-under p">
            <div class="maleri30">
                <div class="shopprice">
                    <div class="img_or fl"><img src="<?php echo goods_thum_images($team_activity['goods_id'],146,146); ?>"></div>
                    <div class="fon_or fl">
                        <h2 class="similar-product-text"><?php echo $team_activity[goods_name]; ?></h2>

                        <div class="price_or"><span>￥</span><span class="team_price"><?php echo $team_activity['team_goods_item'][0]['team_price']; ?></span>(<span><?php echo $team_activity['needer']; ?></span>人团)<span id="item_front_status_desc"><?php echo $team_activity['front_status_desc']; ?></span></div>
                        <div class="dqkc_or"><span>当前库存：</span><span class="stock" id="spec_store_count"><?php echo $team_activity['goods']['store_count']; ?></span></div>
                    </div>
                    <div class="price_or fr">
                        <i class="xxgro" id="choose_spec_close"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="shop-top-under p">
            <div class="maleri30">
                <div class="shulges p">
                    <p>数量</p>

                    <div class="plus">
                        <span class="mp_minous">-</span>
                        <span class="mp_mp"> <input type="tel" class="num buyNum" id="number" residuenum="<?php echo $team_activity['goods']['store_count']; ?>" name="goods_num" value="1" min="1" max="<?php echo $team_activity['goods']['store_count']; ?>" onblur="altergoodsnum(0)"></span>
                        <span class="mp_plus">+</span>
                    </div>
                </div>
                <?php if(is_array($team_activity['goods']['spec']) || $team_activity['goods']['spec'] instanceof \think\Collection || $team_activity['goods']['spec'] instanceof \think\Paginator): $i = 0; $__LIST__ = $team_activity['goods']['spec'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec): $mod = ($i % 2 );++$i;?>
                    <div class="shulges p choicsel spec">
                        <p><?php echo $spec['name']; ?></p>
                        <!-------商品属性值-s------->
                        <?php if(is_array($spec['spec_item']) || $spec['spec_item'] instanceof \think\Collection || $spec['spec_item'] instanceof \think\Paginator): $i = 0; $__LIST__ = $spec['spec_item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_item): $mod = ($i % 2 );++$i;?>
                            <div class="plus choic-sel">
                                <a id="goods_spec_a_<?php echo $spec_item['id']; ?>" class="spec_item" title="<?php echo $spec_item['item']; ?>" data-item-id="<?php echo $spec_item['id']; ?>"
                                <?php if(is_array($team_activity['goods']['spec_image']) || $team_activity['goods']['spec_image'] instanceof \think\Collection || $team_activity['goods']['spec_image'] instanceof \think\Paginator): $i = 0; $__LIST__ = $team_activity['goods']['spec_image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$spec_image): $mod = ($i % 2 );++$i;if($spec_image['spec_image_id'] == $spec_item['id']): ?>
                                        data-img-src="<?php echo $spec_image['src']; ?>"
                                    <?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                >
                                <input id="goods_spec_<?php echo $spec_item['id']; ?>" type="radio" style="display:none;" name="goods_spec[<?php echo $spec['name']; ?>]" value="<?php echo $spec_item['id']; ?>" disabled/><?php echo $spec_item['item']; ?></a>
                            </div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <!-------商品属性值-e-------->
                    </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
        <div class="plusshopcar-buy p">
            <a class="pb_buy dis_btn" href="javascript:void(0);" onClick="addTeamOrder();">立即购买</a>
        </div>
    </div>
</form>
<!--所选弹窗-e-->
<!--&lt;!&ndash;分享弹窗-s&ndash;&gt;-->
<!--<div class="share-bottom-wrap" id="share_bottom">-->
    <!--&lt;!&ndash; 百度分享 Button BEGIN &ndash;&gt;-->
    <!--<div class="bdsharebuttonbox" data-tag="share_1">-->
        <!--<a class="ico-share-wechat" data-cmd="weixin"></a>-->
        <!--<a class="ico-share-qq" data-cmd="sqq"></a>-->
        <!--<a class="ico-share-kj" data-cmd="qzone" href="#"></a>-->
        <!--<a class="ico-share-weibo" data-cmd="tsina"></a>-->
    <!--</div>-->
    <!--&lt;!&ndash; 百度分享 Button END &ndash;&gt;-->
    <!--<i class="xxgro" id="share_bottom_close"></i>-->
<!--</div>-->
<!--&lt;!&ndash;分享弹窗-e&ndash;&gt;-->
<div class="mask-filter-div" style="display: none;"></div>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
 //如果微信分销配置正常, 商品详情分享内容中的"图标"不显示, 则检查域名否配置了https, 如果配置了https,分享图片地址也要https开头
 var httpPrefix = "http://<?php echo \think\Request::instance()->server('SERVER_NAME'); ?>";
<?php if(ACTION_NAME == 'goodsInfo'): ?>
var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Goods&a=goodsInfo&id=<?php echo $goods[goods_id]; ?>"; //默认分享链接
	var ShareImgUrl = httpPrefix+"<?php echo goods_thum_images($goods[goods_id],100,100); ?>"; // 分享图标
	var ShareTitle = "<?php echo (isset($goods['goods_name']) && ($goods['goods_name'] !== '')?$goods['goods_name']:$tpshop_config['shop_info_store_title']); ?>"; // 分享标题
	var ShareDesc = "<?php echo (isset($goods['goods_remark']) && ($goods['goods_remark'] !== '')?$goods['goods_remark']:$tpshop_config['shop_info_store_desc']); ?>"; // 分享描述
<?php elseif(ACTION_NAME == 'info'): ?>
	var ShareLink = "<?php echo $team['bd_url']; ?>"; //默认分享链接
	var ShareImgUrl = "<?php echo $team['bd_pic']; ?>"; //分享图标
	var ShareTitle = "<?php echo $team[share_title]; ?>"; //分享标题
	var ShareDesc = "<?php echo $team[share_desc]; ?>"; //分享描述
<?php elseif(ACTION_NAME == 'found'): ?>
var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Team&a=found&id=<?php echo $teamFound[found_id]; ?>"; //默认分享链接
	var ShareImgUrl = "<?php echo $team[bd_pic]; ?>"; //分享图标
	var ShareTitle = "<?php echo $team[share_title]; ?>"; //分享标题
	var ShareDesc = "<?php echo $team[share_desc]; ?>"; //分享描述
<?php elseif(ACTION_NAME == 'my_store'): ?>
	var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Distribut&a=my_store"; 
	var ShareImgUrl = httpPrefix+"<?php echo $tpshop_config['shop_info_store_logo']; ?>"; 
	var ShareTitle = "<?php echo $share_title; ?>"; 
	var ShareDesc = httpPrefix+"/index.php?m=Mobile&c=Distribut&a=my_store}"; 
<?php else: ?>
	var ShareLink = httpPrefix+"/index.php?m=Mobile&c=Index&a=index"; //默认分享链接
	var ShareImgUrl = httpPrefix+"<?php echo $tpshop_config['shop_info_wap_home_logo']; ?>"; //分享图标
	var ShareTitle = "<?php echo $tpshop_config['shop_info_store_title']; ?>"; //分享标题
	var ShareDesc = "<?php echo $tpshop_config['shop_info_store_desc']; ?>"; //分享描述
<?php endif; ?>

var is_distribut = getCookie('is_distribut'); // 是否分销代理
var user_id = getCookie('user_id'); // 当前用户id
// 如果已经登录了, 并且是分销商
if(parseInt(is_distribut) == 1 && parseInt(user_id) > 0)
{									
	ShareLink = ShareLink + "&first_leader="+user_id;									
}

$(function() {
	if(isWeiXin() && parseInt(user_id)>0){
		$.ajax({
			type : "POST",
			url:"/index.php?m=Mobile&c=Index&a=ajaxGetWxConfig&t="+Math.random(),
			data:{'askUrl':encodeURIComponent(location.href.split('#')[0])},		
			dataType:'JSON',
			success: function(res)
			{
				//微信配置
				wx.config({
				    debug: false, 
				    appId: res.appId,
				    timestamp: res.timestamp, 
				    nonceStr: res.nonceStr, 
				    signature: res.signature,
				    jsApiList: ['onMenuShareTimeline', 'onMenuShareAppMessage','onMenuShareQQ','onMenuShareQZone','hideOptionMenu'] // 功能列表，我们要使用JS-SDK的什么功能
				});
			},
			error:function(res){
				console.log("wx.config error:");
				console.log(res);
				return false;
			}
		}); 

		// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在 页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready 函数中。
		wx.ready(function(){
		    // 获取"分享到朋友圈"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareTimeline({
		        title: ShareTitle, // 分享标题
		        link:ShareLink,
		        desc: ShareDesc,
		        imgUrl:ShareImgUrl // 分享图标
		    });

		    // 获取"分享给朋友"按钮点击状态及自定义分享内容接口
		    wx.onMenuShareAppMessage({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
		    });
			// 分享到QQ
			wx.onMenuShareQQ({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});	
			// 分享到QQ空间
			wx.onMenuShareQZone({
		        title: ShareTitle, // 分享标题
		        desc: ShareDesc, // 分享描述
		        link:ShareLink,
		        imgUrl:ShareImgUrl // 分享图标
			});

		   <?php if(CONTROLLER_NAME == 'User'): ?> 
				wx.hideOptionMenu();  // 用户中心 隐藏微信菜单
		   <?php endif; ?>	
		});
	}
});

function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
</script>
<!--微信关注提醒 start-->
<?php if(\think\Session::get('subscribe') == 0): ?>
<button class="guide" style="display:none;" onclick="follow_wx()">关注公众号</button>
<style type="text/css">
.guide{width:0.627rem;height:2.83rem;text-align: center;border-radius: 8px ;font-size:0.512rem;padding:8px 0;border:1px solid #adadab;color:#000000;background-color: #fff;position: fixed;right: 6px;bottom: 200px;z-index: 99;}
#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
#guide{display:none;position:absolute;top:5px;z-index:19999;}
#guide img{width: 70%;height: auto;display: block;margin: 0 auto;margin-top: 10px;}
div.layui-m-layerchild h3{font-size:0.64rem;height:1.24rem;line-height:1.24rem;}
.layui-m-layercont img{height:8.96rem;width:8.96rem;}
</style>
<script type="text/javascript">
  //关注微信公众号二维码	 
function follow_wx()
{
	layer.open({
		type : 1,  
		title: '关注公众号',
		content: '<img src="<?php echo $wechat_config['qr']; ?>">',
		style: ''
	});
}
  
$(function(){
	if(isWeiXin()){
		var subscribe = getCookie('subscribe'); // 是否已经关注了微信公众号		
		if(subscribe == 0)
			$('.guide').show();
	}else{
		$('.guide').hide();
	}
})
 
</script>
<?php endif; ?>
<!--微信关注提醒  end-->
<script type="text/javascript">
    var form = $("#buy_goods_form");
    var team_id = form.find("input[name='team_id']").val();
    var is_lottery = form.find("input[name='is_lottery']").val();
    var spec_goods_price = $.parseJSON(form.find("input[name='spec_goods_price']").val());//规格库存价格
    var team_price,stock,time_limit;
    var commentType = 1;// 默认评论类型
    $(document).ready(function () {
        lottery_view();
        if(is_lottery == 0){
            initSpec();
        }
        ajaxComment(commentType, 1);// ajax 加载评价列表
        getTeamFounds();
    });
    function initSpec() {
        var item_id = form.find("input[name='item_id']").val();
        if (!$.isEmptyObject(spec_goods_price)) {
            $.each(spec_goods_price, function (i, o) {
                if ((item_id > 0 && o.prom_id == item_id) || (team_id == o.prom_id)) {
                    var spec_key_arr = o.key.split("_");
                    $.each(spec_key_arr, function (index, item) {
                        var spec_radio = $("#goods_spec_" + item);
                        var goods_spec_a = $("#goods_spec_a_" + item);
                        spec_radio.attr("checked", "checked");
                        goods_spec_a.addClass('red');
                    })
                    return false;
                }
            })
        }
        initGoodsPrice();
    }
    //添加评团订单
    function addTeamOrder(){
        var spec = $('.spec');
        if(spec.length > 0 && spec.length != spec.find('.red').length){
            layer.open({content:'请选择规格',time: 2});
            return false;
        }
        var goods_num = $("input[name='goods_num']").val();
        if (goods_num <= 0) {
            layer.open({content:'请至少购买一份~',time: 2});
            return false;
        }
        $.ajax({
            type : "POST",
            url:"<?php echo U('Mobile/Team/addOrder'); ?>",
            dataType:'json',
            data: $('#buy_goods_form').serialize(),
            success: function(data){
                if(data.status == 1){
                    location.href = "/index.php?m=Mobile&c=Team&a=order&order_id=" + data.result.order_id; // 跳转到结算页
                }else{
                    layer.open({content:data.msg,time: 2,end:function(){
                        if(!$.isEmptyObject(data.result)){
                            if(!$.isEmptyObject(data.result.url)){
                                location.href = data.result.url;
                                return false;
                            }
                        }
                    }});
                }
            }
        });
    }
    /**
     * 加载更多评论
     */
    function ajaxComment(commentType, page) {
        var goods_id = $("input[name='goods_id']").val();
        $.ajax({
            type: "GET",
            url: "/index.php?m=Mobile&c=goods&a=ajaxComment&goods_id=" + goods_id + "&commentType=" + commentType + "&p=" + page,//+tab,
            success: function (data) {
                $(".my_comment_list").empty().append(data);
            }
        });
    }
    $(function () {
        //顶部导航切换
        $('.detail .search span').click(function () {
            $(this).addClass('sxp').siblings().removeClass('sxp');
            var a = $('.detail .search span').index(this);
            $('.xq_details').eq(a).show().siblings('.xq_details').hide();
        });
    });
    //点击收藏商品
    function collect_goods(goods_id){
        var ids = new Array();
        ids.push(goods_id);
        $.ajax({
            type : "GET",
            dataType: "json",
            url:"/index.php?m=mobile&c=goods&a=collect_goods",//+tab,
            data: {goods_ids:ids},
            success: function(data){
                layer.open({content:data.msg, time:2});
                if(data.status == '1'){
                    //收藏点亮
                    $('#favorite').find('em').addClass('ico-heart-h');
                }
            }
        });
    }

    $(function () {
        ////内部导航切换
        $('.spxq-ggcs ul li').click(function () {
            $(this).addClass('red').siblings().removeClass('red');
            var sg = $('.spxq-ggcs ul li').index(this);
            $('.sg').eq(sg).show().siblings('.sg').hide()
        });

        //内部导航随鼠标滑动显示隐藏
        var h1 = $('.detail').height();
        var h2 = $('.detail').height() + $('.spxq-ggcs').height();
        var ss = $(document).scrollTop();//上一次滚轮的高度
        $(window).scroll(function () {
            var s = $(document).scrollTop();////本次滚轮的高度

            if (s < h1) {
                $('.spxq-ggcs').removeClass('po-fi');
            }
            if (s > h1) {
                $('.spxq-ggcs').addClass('po-fi');
            }
            if (s > h2) {
                $('.spxq-ggcs').addClass('gizle');
                if (s > ss) {
                    $('.spxq-ggcs').removeClass('sabit');
                } else {
                    $('.spxq-ggcs').addClass('sabit');
                }
                ss = s;
            }
        });
    });

    //查看商品详情
    $(function () {
        $('.seedeadei').click(function () {
            $('.xq_details').eq(0).hide();
            $('.xq_details').eq(1).show();
            $('body').animate({scrollTop: 0}, 0);
            $('.detail').find('.center').find('span').eq(1).addClass('sxp');
            $('.detail').find('.center').find('span').eq(0).removeClass('sxp');
        })
    });
    //评论
    $(function () {
        $('.tbv').click(function () {
            $('.xq_details').eq(0).hide();
            $('.xq_details').eq(2).show();
            $('body').animate({scrollTop: 0}, 0);
            $('.detail').find('.center').find('span').eq(2).addClass('sxp');
            $('.detail').find('.center').find('span').eq(0).removeClass('sxp');
        })
    });

    //所选
    $(function () {
        $('.choise_num').click(function () {
            cover();
            $('#choose_spec').show();
            $('.podee').hide();
        })
        $('#choose_spec_close').click(function () {
            undercover();
            $('#choose_spec').hide();
            $('.podee').show();
        })
    });
    //加减数量
    $(function () {
        $('.mp_minous').click(function () {
            var inputs = $(this).siblings('.mp_mp').find('input');
            var val = inputs.val();
            if (val > 1) {
                val--;
                inputs.val(val);
            }else if(val == 1){
                $(this).parent().find('.mp_minous').addClass('disable');
                inputs.val(val);
            }else{
                layer.open({content:'请至少购买一份~',time: 2});
            }
        })
        $('.mp_plus').click(function () {
            var inputs = $(this).siblings('.mp_mp').find('input');
            var val = inputs.val();
            val++;
            inputs.val(val);
            if(val > 1){
                $(this).parent().find('.mp_minous').removeClass('disable');
            }
        })

        $(document).on("blur", '#goods_num', function (e) {
            initDecrement();
        })
    });
    function initDecrement(){
        var goods_num = $("input[name='goods_num']");
        var goods_num_val = parseInt(goods_num.val());
        if(goods_num_val <= 0){
            layer.open({content:'请至少购买一份~',time: 2});
            $(this).val($(this).attr('value'));
        }else if(goods_num_val == 1){
            $(this).parent().find('.mp_minous').addClass('disable');
        }else{
            $(this).parent().find('.mp_minous').removeClass('disable');
        }
    }
    //点赞
    function hde() {
        setTimeout(function () {
            $('.alert').hide();
        }, 1200)
    }

    function clickful(id) {
        var i = true;
        if ($(id).find('.btn-like-icon').hasClass('like-red')) {
            $('.alert').show(200);
            $('.alert').animate({opacity: "1"}, 600, hde());
            i = false;
        }
        if (i) {
            $(id).find('.btn-like-icon').addClass('like-red');
            $(id).find('.like').addClass('like_ani');
            var num = $(id).find('.assess-btns-num');
            var data = num.attr('data');
            data = ++data;
            num.attr('data', data);
            num.html("(" + data + ")");
        }
    }
    //点击切换规格
    $(document).on('click', '.spec_item', function () {
        //切换选择
        if(!$(this).hasClass('disable')){
            $(this).addClass('red').parent().siblings().find('a').removeClass('red');
        }
        var spec_item_img_src = $(this).data('img-src');
        if (spec_item_img_src != '') {
            $('#zoomimg').attr('src', spec_item_img_src);
        }
        if(!$(this).hasClass('disable')){
            $(this).siblings().removeClass('hover');
            $(this).addClass('hover');
            $(this).parent().parent().find('input').removeAttr('checked');
            $(this).children('input').attr('checked', 'checked');
            $('.team-pies').hide();
            //商品价格库存显示
            var spec = $('.spec');
            if(spec.length == 0 || spec.length == spec.find('.red').length){
                initGoodsPrice();
            }
        }
    })
    //拼团规则
    $('.fohe i').click(function () {
        if ($(this).hasClass('action-am')) {
            $(this).removeClass('action-am');
            $('.hs_acion').animate({height: '0', opacity: 'show'}, 'normal', function () {
                $('.hs_acion').hide();
            });
        } else {
            $(this).addClass('action-am');
            $('.hs_acion').animate({height: '10.02667rem', opacity: 'show'}, 'normal', function () {
                $('.hs_acion').show();
            });
        }
    })

    // 倒计时
    setInterval(activityTime, 1000);
    function activityTime() {
        $('.team-found').each(function(i,o){
            var end_time = $(o).find('.rest_time').data('end-time');
            var end_time_text = getOverTimeText(end_time);
            $(o).find('.rest_time').text(end_time_text);
        })
    }
    function getOverTimeText(timeStrip){
        var end_time = parseInt(timeStrip);
        var end_time_date = formatDate(end_time*1000);
        return GetRTime(end_time_date);
    }

    //初始化商品价格库存
    function initGoodsPrice() {
        var goods_id = $('input[name="goods_id"]').val();
        if (!$.isEmptyObject(spec_goods_price)) {
            var goods_spec_arr = [];
            $("input[name^='goods_spec']").each(function () {
                if($(this).attr('checked') == 'checked'){
                    goods_spec_arr.push($(this).val());
                }
            });
            if(goods_spec_arr.length == 0){
                return false;
            }
            var spec_key = goods_spec_arr.sort(sortNumber).join('_');  //排序后组合成 key
            var spec_goods_price_item = search_spec_goods_price(spec_key);
            var item_id = spec_goods_price_item['item_id'];
            $('input[name=item_id]').val(item_id);
        }
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {goods_id: $('input[name="goods_id"]').val(), item_id:$('input[name="item_id"]').val()},
            url: "<?php echo U('Mobile/Team/ajaxCheckTeam'); ?>",
            success: function (data) {
                if (data.status == 1) {
                    var goods = data.result.team_goods_item.goods;
                    var spec_goods = data.result.team_goods_item.spec_goods_price;
                    team_price = data.result.team_goods_item.team_price;
                    if(spec_goods){
                        stock = spec_goods.store_count;
                    }else{
                        stock = goods.store_count;
                    }
                    goods_activity_theme();
                    $('#item_front_status_desc').empty().html(data.result.team_goods_item.team_activity.front_status_desc);
                }else{
                    $('#item_front_status_desc').empty().html('未参与活动');
                }
            }
        });
    }
    //获取可拼单列表
    function getTeamFounds() {
        var goods_id =  $('input[name=goods_id]').val();
        $.ajax({
            type: 'post',
            dataType: 'json',
            data: {goods_id: goods_id},
            url: "<?php echo U('Mobile/Team/ajaxTeamFound'); ?>",
            success: function (data) {
                if (data.status == 1) {
                    var founds = data.result.teamFounds;
                    var html = '', end_time,url;
                    for(var i = 0;i < founds.length;i++){
                        end_time = parseInt(founds[i].found_time) + parseInt(founds[i].time_limit);
                        url = "/index.php?m=Mobile&c=Team&a=found&id=" + founds[i].found_id;
                        html += '<div class="box-lesc team-found"> <div class="diff_img"> <img src="'+founds[i].head_pic+'"/> ' +
                                '</div> <div class="diff_lrzy"> <ul class="f_name_add"> <li class="nameli">' +
                                '<span>'+founds[i].nickname+'</span></li> <li class="addci"><span>'+founds[i].address_region+'</span>' +
                                '</li> </ul> <ul class="f_crou_tim"> <li class="red"><span>还差<em>'+founds[i].surplus+'</em>人成团</span>' +
                                '</li> <li class="koes"><span>剩余<em class="rest_time" data-end-time="'+end_time+'">00:00:00</em>结束</span>' +
                                '</li> </ul> </div> <div class="diff_ct"> <a href="'+url+'">去参团 <i class="w-re"></i></a> </div> </div>';
                    }
                    if(html != ''){
                        $('.have_founds').show();
                        $('#found_list').empty().append(html);
                    }else{
                        $('.have_founds').hide();
                    }
                }
            }
        });
    }
    function lottery_view(){
        if(is_lottery ==1){
            $('.lottery').show();
            $('.no_lottery').hide();
        }else{
            $('.lottery').hide();
            $('.no_lottery').show();
        }
    }
    //商品价格库存显示
    function goods_activity_theme(){
        lottery_view();
        $('.team_price').html(team_price);
        $('.stock').html(stock);
        $('#number').attr('residuenum',stock);
        if (stock <= 0) {
            $('.dis_btn').addClass('dis');
            $('.buyNum').val(stock);
        } else {
            $('.dis_btn').removeClass('dis');
            $('.buyNum').val(1);
        }
    }
    function sortNumber(a, b) {
        return a - b;
    }

</script>
<!--<script>-->
    <!--//分享-->
    <!--$(function () {-->
        <!--$('#share_button').click(function () {-->
            <!--cover();-->
            <!--$('#share_bottom').addClass('share-bottom-show');-->
        <!--})-->
        <!--$('#share_bottom_close').click(function () {-->
            <!--undercover();-->
            <!--$('#share_bottom').removeClass('share-bottom-show');-->
        <!--})-->
    <!--});-->
    <!--function setShareConfig(id,config){-->
        <!--config.bdText = share_desc;-->
        <!--config.bdDesc = share_title;-->
        <!--config.bdUrl = bd_url;-->
        <!--config.bdPic = bd_pic;-->
        <!--return config;-->
    <!--}-->
    <!--window._bd_share_config = {-->
        <!--common : {-->
            <!--//此处放置通用设置-->
            <!--onBeforeClick:setShareConfig,-->
            <!--bdText : "",-->
            <!--bdDesc : "",-->
            <!--bdUrl : "",-->
            <!--bdPic : ""-->
        <!--},-->
        <!--share : [{-->
            <!--"bdSize" : 32-->
        <!--}],-->
        <!--image : [{-->
            <!--viewType : 'list',-->
            <!--viewPos : 'top',-->
            <!--viewColor : 'black',-->
            <!--viewSize : '32',-->
            <!--viewList : ['weixin','sqq','qzone','tsina']-->
        <!--}],-->
        <!--selectShare : [{-->
            <!--"bdselectMiniList" : ['weixin','sqq','qzone','tsina']-->
        <!--}]-->
    <!--}-->
    <!--with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];-->
<!--</script>-->
</body>
</html>
