<?php if(count($replies)>5){ ?>
<div class="show-all-replies clearwidth" style="margin-top:10px;">
    <a href="javascript:void(0)" onclick="showAllReplies(this);">
        View All
        <span><?=(count($replies))?></span>
        Replies
    </a>
</div>
<?php }

foreach($replies as $index => $reply)
{
    if($reply['userId']>0)
    {
        $userName = $commentsUserData[$reply['userId']]['userName'];
        $imageUrl = $commentsUserData[$reply['userId']]['imageUrl'];
        $admittedStr = $commentsUserData[$reply['userId']]['admittedStr'];
        $profilePageUrl = $commentsUserData[$reply['userId']]['profilePageUrl'];
        $linkOpen = '<a href="'.$profilePageUrl.'">';
        $imgLinkOpen = '<a href="'.$profilePageUrl.'" class="pp-img">';
        $linkClose = $imgLinkClose = '</a>';
    }else{
        $linkOpen = '';
        $linkClose = '';
        $imgLinkOpen = '<a class="pp-img">';
        $imgLinkClose = '</a>';
        $userName = $reply['userName'];
        $imageUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
        $admittedStr='';
    }
    $display = ($index > 4 ?'style="display:none;"':''); ?>
<div class="comment-reply clearwidth" <?=($display)?>>
    <p class="clearfix">
        <input type="hidden" class = "replyId" value = "<?=($reply['commentId'])?>">
        <input type="hidden" class = "userId" value = "<?=($reply['userId'])?>" />
        <input type="hidden" class = "userEmailId" value = "<?=($reply['emailId'])?>" />
        <div class="clearfix head-info">
            <?php echo $imgLinkOpen; ?><img src="<?php echo $imageUrl; ?>"><?php echo $imgLinkClose; ?>
            <div class="flLt text-head">
                <div class="clearfix">
                    <?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName;?></span><?php echo $linkClose; ?>
                    <span class="flRt font-12"><?php echo date("d M'y, g:i a",strtotime($reply['commentTime']));?></span>
                </div>
                <span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
            </div>
        </div>
    </p>
    <p class="commentText">
    <?php echo (formatQNAforQuestionDetailPage(htmlentities($reply['commentText']))); ?>
    </p>
    <?php if($reply['userEligibleForCommentDeletion']) { ?>
    <p>
        <a class="flRt font-14" onclick="deleteComment(this,'reply');" href="javascript:void(0);">Delete Reply</a>
    </p>
    <?php } ?>
</div>
<?php } ?>
