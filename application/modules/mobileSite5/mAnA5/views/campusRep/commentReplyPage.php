<form id="answerFormToBeSubmitted<?php echo $msgId;?>" method="post" onsubmit="return false;" action="<?php echo SHIKSHA_HOME;?>/messageBoard/MsgBoard/replyMsg/<?php echo $msgId;?>" novalidate="">
     
     <div style="display: none" id="comment_box_<?php echo $msgId;?>" class="cmnt_bx">
     <div>
      <article class="comment-sec clearfix" data-enhance=false>
	<i class="sprite pointer"></i>
	<textarea validatesinglechar="true"  required="true" minlength="15" maxlength="2500" <?php if($viewType === 'reply'){?> caption="Answer" <?php }else{?> caption="Comment" <?php }?> validate="validateStr" class="comment-area _comment_box" id="replyText<?php echo $msgId;?>" onkeyup="textKey(this);" name="replyText<?php echo $msgId;?>"></textarea>
	
	<p style="font-size:12px"><span id="replyText<?php echo $msgId;?>_counter" class="_startCoun">0</span> out of 2500 characters</p>
	<div style="display:none;" class="errorPlace Fnt11" id="errorPlace<?php echo $msgId;?>"><div id="replyText<?php echo $msgId;?>_error" class="errorMsg"></div></div>
	<!--<p>Kindly follow our <a href="#">Community Guidelines</a></p>-->
	<a class="button blue small flLt" id="submitButton<?php echo $msgId;?>" onclick="postReplyCommnet(document.getElementById('answerFormToBeSubmitted<?php echo $msgId;?>'),'<?php echo $msgId;?>','<?php echo $trackingPageKeyId;?>');" href="javascript:void(0);">Comment</a><a href="javascript:void(0);" onclick="hideReplyCommentBox('<?php echo $msgId;?>');" style="float:left; margin:15px 0 0 15px; cursor: pointer;">Cancel</a>
	</article>
        <?php if($viewType === 'reply'){?>
	
	<input type="hidden" value="<?php echo $msgId;?>" name="threadid<?php echo $msgId;?>" id="threadid<?php echo $msgId;?>">
	<input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex" id="secCodeIndex<?php echo $msgId;?>">
	<input type="hidden" value="user" name="fromOthers<?php echo $msgId;?>" id="fromOthers<?php echo $msgId;?>">
	<input type="hidden" id="actionPerformed<?php echo $msgId;?>" value="addAnswer" name="actionPerformed<?php echo $msgId;?>">
	<input type="hidden" id="mentionedUsers<?php echo $msgId;?>" value="" name="mentionedUsers<?php echo $msgId;?>">
	<input type="hidden" id="buttonName<?php echo $msgId;?>" value="<?php echo $buttonName;?>">
	<input type="hidden" id="pageName<?php echo $msgId;?>" value="<?php echo $pageName;?>">
	
	<?php }else if($viewType == 'comment'){?>
	
	<input type="hidden" value="<?php echo $threadId;?>" name="threadid<?php echo $msgId;?>" id="threadid<?php echo $msgId;?>">
	<input type="hidden" value="user" name="fromOthers<?php echo $msgId;?>" id="fromOthers<?php echo $msgId;?>">
	<input type="hidden" value="<?php echo $msgId;?>" name="mainAnsId<?php echo $msgId;?>" id="mainAnsId<?php echo $msgId;?>">
	<input type="hidden" value="addComment" name="actionPerformed<?php echo $msgId;?>" id="actionPerformed<?php echo $msgId;?>">
	<input type="hidden" id="buttonName<?php echo $msgId;?>" value="<?php echo $buttonName;?>">
	<input type="hidden" id="q_url<?php echo $msgId;?>" value="<?php echo $q_url;?>">
	<input type="hidden" id="pageName<?php echo $msgId;?>" value="<?php echo $pageName;?>">
	
	<?php }?>
	<input type="hidden" id="tracking_keyid<?php echo $msgId;?>" name="tracking_keyid" val="<?php echo $trackingPageKeyId;?>">
	  
	  </div>
     </div>
</form>