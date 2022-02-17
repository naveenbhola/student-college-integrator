<?php if(isset($userGroup)){?>
<!--reply box on comment -->
<form action="" method="post" id="replyForm<?php echo $msgId;?>">
<div class="topic-textbox inner-rply wdh100 flLt hide _rplyBox" id="replyBox_<?php echo $msgId;?>">
   	<textarea name="replyCommentText<?php echo $msgId; ?>" id="replyCommentText<?php echo $msgId; ?>" placeholder="<?php echo $placeholder;?>" validate="validateStr" caption="Reply" maxlength="2500" minlength="3"validateSingleChar='true' class="_replyTextArea"></textarea>
   	<div class="errorPlace">
	   	<div class="errorMsg" id="replyCommentText<?php echo $msgId; ?>_error"></div>
	</div>
   	<div class="limit-nums">
   	<div class="cmntBox">
   	  <strong id="replyCommentText<?php echo $msgId; ?>_counter">0</strong> out of <strong>2500</strong>  characters	
   	</div>

	      <div class="alignright">
	        <div class="_replyCancel cancelBtn" data-msgId="<?php echo $msgId; ?>"><a id="cancel<?php echo $msgId; ?>" href="javascript:void(0);">Cancel</a></div>
	         <a class="topic-btn _replySubmitBtn" id="submitComment<?php echo $msgId; ?>" data-msgId="<?php echo $msgId; ?>">Submit</a>
	      </div>
   	</div>
	  
</div>

<input type="hidden" name="displayname<?php echo $msgId; ?>" id="displayname<?php echo $msgId; ?>" value="<?php echo $displayname;  ?>" />
<input type="hidden" name="sortFlag<?php echo $msgId; ?>" id="sortFlag<?php echo $msgId; ?>" value="<?php echo $sortFlag;?>" />
<input type="hidden" name="threadid<?php echo $msgId; ?>" id="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
<input type="hidden" name="dotCount<?php echo $msgId; ?>" id="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
<input type="hidden" name="fromOthers<?php echo $msgId; ?>" id="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" id="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
<input type="hidden" name="displaynameId<?php echo $msgId; ?>" id="displaynameId<?php echo $msgId; ?>" value="<?php echo $userId; ?>" />
<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
<input type="hidden" name="userProfileImage<?php echo $msgId; ?>" id="userProfileImage<?php echo $msgId; ?>" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable_v1.gif';?>" />
<input type="hidden" name="immediateParentId<?php echo $msgId; ?>" id="immediateParentId<?php echo $msgId; ?>" value="<?php echo $commentParentId;?>" />
<input type="hidden" name="immediateParentName<?php echo $msgId; ?>" id="immediateParentName<?php echo $msgId; ?>" value="<?php echo $commenterDisplayName;?>" />
<input type="hidden" name="isWall<?php echo $msgId; ?>" id="isWall<?php echo $msgId; ?>" value="<?php echo $isWall;?>" />
<input type="hidden" name="mentionedUsers<?php echo $msgId; ?>" value="" id="mentionedUsers<?php echo $msgId; ?>"/>
<input type='hidden' name='tracking_keyid' id="tracking_keyid<?php echo $msgId; ?>" value="<?=$trackingPageKeyId?>">
<input type ="hidden" id="page<?php echo $msgId; ?>" value="<?php echo $page; ?>" />
<input type ="hidden" id="totalReply<?php echo $msgId; ?>" value="<?php echo $totalReply; ?>" />
</form>
<!--end reply box on comment -->
<?php }?>