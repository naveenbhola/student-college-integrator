<?php if(isset($userGroup) && (strcmp($userGroup,'cms') !== 0)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
?>
<!--Start_Inline_Comment-->
<form action="" method="post" id="commentForm<?php echo $threadId; ?>">

	<div class="topic-textbox" id="inlineAnswerFormCntainer<?php echo $threadId; ?>">
	  
	    <textarea name="replyText<?php echo $threadId; ?>" id="replyText<?php echo $threadId; ?>" validate="validateStr" caption="Comment" maxlength="2500" minlength="3" rows="3" validateSingleChar='true' placeholder="<?php echo $placeholder;?>" class="_commentTextArea" data-threadId="<?php echo $threadId; ?>"></textarea>

	    <div class="errorPlace">
	   		<div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div>
	   	</div>

	    <div class="limit-nums">
	      <div class="cmntBox">
	        <strong id="replyText<?php echo $threadId; ?>_counter">0</strong> out of <strong>2500</strong>  characters
           <div class="alignLeft">
	         <p class="flwup-txt">Make sure your answers follows</p>
	         <p class="guideline-link"><a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline" target="_blank">Community Guidelines</a></p>
	      </div>
          </div>
          <div class="alignright">
	         <a class="topic-btn _cmtSubmitBtn disabled" id="submitComment<?php echo $threadId; ?>" data-threadId="<?php echo $threadId; ?>">Submit</a>
	      </div>
	      </div>
	   	<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" />
		<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />	
		<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="blog" id="fromOthers<?php echo $threadId; ?>" />
		<input type="hidden" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" id="actionPerformed<?php echo $threadId; ?>"/>
		<input type="hidden" name="functionToCall" id="functionToCall" value="incrementCommentCount()" />
		<input type="hidden" name="userProfileImage<?php echo $threadId; ?>" id="userProfileImage<?php echo $threadId; ?>" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable_v1.gif';?>" />
		<input type="hidden" id="tracking_keyid<?php echo $threadId; ?>" name="tracking_keyid" value="<?=$trackingPageKeyId?>">
		<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
		<input type ="hidden" id="page<?php echo $threadId; ?>" value="<?php echo $page; ?>" />
	</div>
</form>
<?php } ?>
