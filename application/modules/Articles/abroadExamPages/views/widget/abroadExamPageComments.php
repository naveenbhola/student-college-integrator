<?php
    foreach($commentData['data'] as $comment)
    {
        if($comment['userId']>0)
        {
            $userName = $commentsUserData[$comment['userId']]['userName'];
            $imageUrl = $commentsUserData[$comment['userId']]['imageUrl'];
            $admittedStr = $commentsUserData[$comment['userId']]['admittedStr'];
            $profilePageUrl = $commentsUserData[$comment['userId']]['profilePageUrl'];
            $linkOpen = '<a href="'.$profilePageUrl.'">';
            $imgLinkOpen = '<a href="'.$profilePageUrl.'" class="pp-img">';
            $linkClose = $imgLinkClose = '</a>';
        }else{
            $linkOpen = '';
            $linkClose = '';
            $imgLinkOpen = '<a class="pp-img">';
            $imgLinkClose = '</a>';
            $userName = $comment['userName'];
            $imageUrl = IMGURL_SECURE.'/public/images/studyAbroadCounsellorPage/profileDefaultNew1_s.jpg';
            $admittedStr='';
        }
?>
    <div class="posted-comment clearwidth">
        <div class="comment-detail clearwidth">
            <div class="clearfix head-info">
                <?php echo $imgLinkOpen; ?><img src="<?php echo $imageUrl; ?>"><?php echo $imgLinkClose; ?>
                <div class="flLt text-head">
                    <div class="clearfix">
                        <?php echo $linkOpen; ?><span class="flLt bold" style="color: #008489;"><?php echo $userName;?></span><?php echo $linkClose; ?>
                        <span class="flRt font-11"><?php echo date("d M'y, g:i a",strtotime($comment['commentTime']));?></span>
                    </div>
                    <span class="lbl-aspirent"><?php echo $admittedStr; ?></span>
                </div>
            </div>
        </div>
        <p class = "commentText"><?php echo (formatQNAforQuestionDetailPage(htmlentities($comment['commentText']))); ?></p>
        <div class="clearwidth font-14" style="margin-bottom:0;" >
            <a href="Javascript:void(0);" onclick = "showReplyBox(this);" class="replyLink flLt">Reply to <?=htmlentities($userName)?></a>
            <?php if($comment['userEligibleForCommentDeletion']) { ?>
                <a href="Javascript:void(0);" onclick = "deleteComment(this,'comment');" class="flRt">Delete Comment</a>
            <?php } ?>
            <ul style = "display:none;" class = "replyBox clearwidth">
                <li style="margin:10px 0;">
                    <input type = "hidden" class="parentId" value = "<?=($comment['commentId'])?>" />
                    <input type = "hidden" class="userId" value = "<?=($comment['userId'])?>" />
                    <input type = "hidden" class="userEmailId" value = "<?=($comment['emailId'])?>" />
                    <textarea id="commentBox_<?=$comment['commentId']?>" value="Add your comment" default="Add your reply" validate="validateStr" caption="reply" maxlength="2500" minlength="3" required="true" onfocus="if($j(this).val() == 'Add your reply') { $j(this).val('');}" onblur=" if($j(this).val() == '') {$j(this).val('Add your reply');}" name="textarea" class="replyTextarea comment-area">Add your reply</textarea>
                    <div id="commentBox_<?=$comment['commentId']?>_error" class="errorMsg" style="display:none;margin-top: 3px; display: inline;"></div>
                </li>
                <li style="margin:10px 0;text-align:right">
                    <a href="Javascript:void(0);" onclick = "hideReplyBox(this);">Cancel</a> <a href="Javascript:void(0);" onclick = "submitComment(this);studyAbroadTrackEventByGA('ABROAD_EXAM_PAGE', 'submitYourComment');" class="button-style" style="padding:6px 25px; margin-left:8px; font-weight:bold">Reply</a>
                </li>
            </ul>
        </div>
        <?php $this->load->view('abroadExamPages/widget/abroadExamPageCommentReply',array('replies'=>$comment['replies'])); ?>
    </div>
<?php } ?>
