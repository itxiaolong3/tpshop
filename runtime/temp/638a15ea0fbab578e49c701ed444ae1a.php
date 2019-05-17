<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:48:"./template/mobile/rainbow/goods\ajaxComment.html";i:1540260093;}*/ ?>
<?php if($count > 0): ?>
  <div class="assess-flat " id="comList">
     <?php if(is_array($commentlist) || $commentlist instanceof \think\Collection || $commentlist instanceof \think\Paginator): if( count($commentlist)==0 ) : echo "" ;else: foreach($commentlist as $k=>$v): ?>
            <span class="assess-wrapper"  >
                <div class="assess-top">
                    <?php if($v['is_anonymous'] == 1): ?>
                        <span class="user-portrait"><img src="/template/mobile/rainbow/static/images/user68.jpg"></span>
                        <span class="user-name">匿名用户</span>
                    <?php else: ?>
                        <span class="user-portrait"><img src="<?php echo (isset($v['head_pic']) && ($v['head_pic'] !== '')?$v['head_pic']:'/template/mobile/rainbow/static/images/user68.jpg'); ?>"></span>
                        <span class="user-name"><?php echo $v['username']; ?></span>
                    <?php endif; ?>
                    <span class="assess-date"><?php echo date('Y-m-d H:i',$v['add_time']); ?></span>
                </div>
                <div class="assess-bottom">
                    <span class="comment-item-star">
                        <span class="real-star comment-stars-width<?php echo $v['goods_rank']; ?>"></span>
                    </span>
                    <p class="assess-content"><?php echo htmlspecialchars_decode($v['content']); ?></p>
                    <div class="product-img-module">
                        <!-- <a class="J_ping" report-eventid="MProductdetail_CommentPictureTab" report-pageparam="1725965683" href="/ware/newCommentDetailPicShow.action?commentId=<?php echo $v['comment_id']; ?>&amp;wareId=1725965683"> -->
                            <ul class="jd-slider-container gallery">
                                <?php if(is_array($v['img']) || $v['img'] instanceof \think\Collection || $v['img'] instanceof \think\Paginator): if( count($v['img'])==0 ) : echo "" ;else: foreach($v['img'] as $key=>$v2): ?>
                                    <li class="jd-slider-item product-imgs-li">
                                        <dd><a href="<?php echo $v2; ?>"><img src="<?php echo $v2; ?>" width="100px" heigth="100px"></a></dd>
                                    </li>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        <!-- </a> -->
                    </div>
                    <!--商家回复-s-->
                    <?php if(is_array($replyList[$v['comment_id']]) || $replyList[$v['comment_id']] instanceof \think\Collection || $replyList[$v['comment_id']] instanceof \think\Paginator): if( count($replyList[$v['comment_id']])==0 ) : echo "" ;else: foreach($replyList[$v['comment_id']] as $k=>$reply): ?>
                            <p class="pay-date"><?php echo $reply['username']; ?>回复：<?php echo $reply['content']; ?></p>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    <!--商家回复-e-->
                    <!--<p class="product-type">颜色：ML574VB 型号：38/235MM</p>-->
                </div>
            </span>
             <div class="assess-btns-box">
                 <div class="assess-btns">
                     <a class="assess-like-btn" id="<?php echo $v[comment_id]; ?>" data-comment-id="<?php echo $v['comment_id']; ?>" onclick="zan(this);">
                         <?php if($v['zan_userid'] != ''): ?>                     	                        
                          <i class="assess-btns-icon btn-like-icon like-grey <?php if(in_array($user_id,explode(',',$v['zan_userid']))): ?>like-red<?php endif; ?>""></i>
                         <?php else: ?>
                         <i class="assess-btns-icon btn-like-icon like-grey "></i>
                         <?php endif; ?>
                         <span class="assess-btns-num" id="span_zan_<?php echo $v['comment_id']; ?>" data="0"><?php echo $v['zan_num']; ?></span>
                         <i class="like">+1</i>
                     </a>
                    <a href="<?php echo U('Mobile/Order/comment_info',['comment_id'=>$v['comment_id']]); ?>" class="assess-reply-btn" <?php if($v['reply_num'] > 0): ?>href="<?php echo U('Mobile/Goods/reply',array('comment_id'=>$v['comment_id'])); ?>"<?php endif; ?>>
                        <i class="no-assess-btns-icon btn-reply-icon"></i>
                        <span class="assess-btns-num" id="comment_id<?php echo $v[comment_id]; ?>"><?php echo $v['reply_num']; ?></span>
                    </a>
                 </div>
             </div>
     <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
<?php else: ?>
     <script>
         $('.getmore').hide();
     </script>
    <!--没有内容时-s-->
    <div class="comment_con p">
        <div class="score enkecor">此处没有更多内容了</div>
    </div>
    <!--没有内容时-e-->
<?php endif; if(($count > $current_count) AND (count($commentlist) == $page_count)): ?>
     <div class="getmore" style="font-size:.32rem;text-align:center;color:#888;padding:.25rem .24rem .4rem; clear:both">
         <a href="javascript:void(0)" onClick="ajaxSourchSubmit();">点击加载更多</a>
     </div>
     <?php elseif(($count <= $current_count AND $count > 0)): ?>
        <div class="score enkecor">已显示完所有评论</div>
     <?php else: endif; ?>
<link href="/template/mobile/rainbow/static/css/photoswipe.css" rel="stylesheet" type="text/css">
<script src="/template/mobile/rainbow/static/js/klass.min.js"></script>
<script src="/template/mobile/rainbow/static/js/photoswipe.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var gallery_a = $(".gallery a");
        if(gallery_a.length > 0){
            $(".assess-wrapper .gallery a").photoSwipe({
                enableMouseWheel: false,
                enableKeyboard: false,
                allowUserZoom: false,
                loop:false
            });
        }
    });
     var page = <?php echo \think\Request::instance()->param('p'); ?>;
     function ajaxSourchSubmit() {
         page += 1;
         $.ajax({
             type: "GET",
             url: "<?php echo U('Mobile/Goods/ajaxComment',array('goods_id'=>$goods_id,'commentType'=>$commentType),''); ?>"+"/p/" + page,
             success: function (data) {
                 $('.getmore').hide();
                 if ($.trim(data) != ''){
                     $("#comList").append(data);
                 }
             }
         });
     }
     function ajax_sourch_submit_hide(){
         $('.getmore').hide();
     }

     //点赞
     function hde(){
         setTimeout(function(){
             $('.alert').hide();
         },1200)
     }

 </script>