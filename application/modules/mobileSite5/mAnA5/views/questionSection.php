<?php
$questionPosteddFromUserId = $detailPageData['question'][$questionId]['userId'];
$answerPostedByUsers       = $detailPageData['answerPostedByUsers'];
if (getTempUserData('mobile_post_suc_msg'))
{ 
    if(trim(getTempUserData('mobile_post_suc_msg')) === 'SUQ'){
        $msgToBeShown = 'You can not answer your own question.';
    }else if(trim(getTempUserData('mobile_post_suc_msg')) === 'MTOA')
    {
        $msgToBeShown = 'You can not answer more than once to same question.';
    }else if(trim(getTempUserData('mobile_post_suc_msg')) === 'CAMPUSREPEXISTS')
    {
        $msgToBeShown = 'You can not answer on this question.';
    }else if(trim(getTempUserData('mobile_post_suc_msg')) === 'NOREP')
    {
        $msgToBeShown = 'You can not answer because you have zero Reputation Index.';
    }else if(trim(getTempUserData('mobile_post_suc_msg')) === 'SAMEANS')
    {
        $msgToBeShown = 'Please don\'t copy/ paste the answers.';
    }else{
        $msgToBeShown = getTempUserData('mobile_post_suc_msg');
    }
?>    
<section class="top-msg-row">
        <div class="thnx-msg">
            <i class="icon-tick"></i>
                <p>
                <?php echo $msgToBeShown; ?>
                </p>
        </div>
	<div style="clear:both"></div>
</section>
<?php 
}
?>
<section class="content-wrap2 clearfix">
    <article style="padding: 1em 0.625em 0.625em;position: relative;">
        <h2 class="camp-connect-head">
                <strong><?php echo $courseObj->getName();?></strong>
                <p><a href="<?php echo $insObj->getURL()?>"><?php echo $instituteName;?></a></p>
        </h2>
        
        <div class="camp-details">
            <div class="ques-icon">Q</div>
            <div class="camp-qna-details">
                <p><?php echo $detailPageData['question'][$questionId]['msgTxt'];?></p>
                <p class="posted-info clearfix">
                    <span><label>Posted by:</label> <?php echo $detailPageData['question'][$questionId]['postedByUser'];?></span>
                    <span><?php echo $detailPageData['question'][$questionId]['postedAt'];?> </span>
                </p>
                <p style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;display:none;" id="success<?php echo $questionId;?>_msg"></p>
                <?php
                if($userIdOfLoginUser!=$questionPosteddFromUserId && !in_array($userIdOfLoginUser, $answerPostedByUsers) && $userGroup!='cms' && $detailPageData['status'] !='closed'){
                ?>
                <?php if(!$doNoShowAnswerForm){ ?><a class="button blue small _rply_btn" id="_rply_btn<?php echo $questionId;?>" href="javascript:void(0);" style="margin-top:2px" onclick="showReplyCommentBox(this,'Answer this question','<?php echo $questionId;?>');"><span>Answer this question</span></a><?php } ?>
                <?php
                }
                ?>
                <?php /*if(isset($_COOKIE['mobile_post_suc_msg'])){ ?>
                    <p  style="background:#fefbcd; padding:2px 5px; width:auto; font-size:12px;margin-top: 5px;" id="answerSuccessMsg">
                        <?php echo $_COOKIE['mobile_post_suc_msg'];?>
                    </p>
                <?php }*/ ?>
                <?php
                $data['viewType'] = 'reply';
                $data['buttonName'] = 'Answer this question';
                $data['msgId'] = $questionId;
                $data['pageName'] = 'QuestionDetailPage';
                $data['trackingPageKeyId']=$atrackingPageKeyId;
                $this->load->view('mAnA5/campusRep/commentReplyPage',$data);?>
                <div class="clearfix"></div>
            </div>
        </div>
    </article>
</section>
