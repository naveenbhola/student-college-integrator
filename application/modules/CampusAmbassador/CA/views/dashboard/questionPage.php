<?php if(count($result['result'])>0)
{   
     foreach($result['result'] as $result)
     {
	$postedUserCourse = 'Not Applicable';
	if($result->courseId>0){
		$course = $courseRepository->find($result->courseId);
	        if(is_object($course))
		{
			$postedUserCourse = html_escape($course->getName());
        	}
	}
?>
<li id="question_<?php echo $result->msgId;?>">
<div class="qna-col">
    <dl>
	<dt>
	    <div class="ques-icon">Q</div>
	    <div class="qna-details">
		<h3><?php echo $result->msgTxt;?></h3>
		<?php if(isset($result->description) && $result->description!=''){ ?>
		<p><span style="color: #999999;font-size: 11px;">More detail: </span><?php echo $result->description;?></p>
		<?php } ?>
		<p class="posted-info">
		Posted by: <?php echo $result->PostedBy;?><br />
		Course: <?php echo $postedUserCourse;?>
		</p>
	    </div>
	</dt>
	
	<dd id="reply_btn_<?php echo $result->msgId;?>" class="reply_all_btn">
	    <div class="qna-details">
		<a href="javascript:void(0);" onclick="replyQuestion(<?php echo $result->msgId;?>);" class="submit-btn reply-btn">Reply</a>
	    </div>
	</dd>
    </dl>
</div>

<!------------comment-box--------------->
<style>.disabled {pointer-events: none;cursor: default;}</style>
<form id="answerFormToBeSubmitted<?php echo $result->msgId;?>" method="post" onsubmit="return false;" action="<?php echo SHIKSHA_HOME;?>/messageBoard/MsgBoard/replyMsg/<?php echo $result->msgId;?>" novalidate="">
     
     <div class="submit-ans clear-width cmnt_box_all" style="display: none" id="comment_box_<?php echo $result->msgId;?>">
     <div class="submit-ans-child clear-width">
      
	<textarea validatesinglechar="true"  required="true" minlength="15" maxlength="2500" caption="Answer" validate="validateStr" class="ftxArea" id="replyText<?php echo $result->msgId;?>" onkeyup="textKey(this);" name="replyText<?php echo $result->msgId;?>"></textarea>
	<div style="display:none;" class="errorPlace Fnt11"><div id="replyText<?php echo $result->msgId;?>_error" class="errorMsg"></div></div>
	<div class="info-text clear-width">
	<p class="flLt char-text"><span id="replyText<?php echo $result->msgId;?>_counter">0</span> out of 2500 characters are entered<br>
Campus Representative <a onclick="return popitup(&quot;<?php echo SHIKSHA_HOME;?>/shikshaHelp/ShikshaHelp/campusRepGuideline&quot;,500,1030,&quot;yes&quot;,50,200);" href="javascript:void(0);">Guideline</a></p>	
	<a class="flRt submit-btn" id="submitButton<?php echo $result->msgId;?>" onclick="try{ $j('#submitButton'+<?php echo $result->msgId;?>).addClass('disabled'); if(validateFields($('answerFormToBeSubmitted<?php echo $result->msgId;?>')) != true){ $j('#submitButton'+<?php echo $result->msgId;?>).removeClass('disabled'); return false;}else{ new Ajax.Request('/messageBoard/MsgBoard/replyMsg/'+<?php echo $result->msgId;?>,{onSuccess:function(request){location.reload();$j('#submitButton'+<?php echo $result->msgId;?>).removeClass('disabled');}, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?php echo $result->msgId;?>'))});} }catch (e) {};" href="javascript:void(0);">Submit</a>	
	
	</div>		    		
	  <input type="hidden" value="<?php echo $result->msgId;?>" name="threadid<?php echo $result->msgId;?>">
	  <input type="hidden" value="seccodeForInlineAnswer" name="secCodeIndex">
	  <input type="hidden" value="user" name="fromOthers<?php echo $result->msgId;?>">
	  <input type="hidden" id="actionPerformed<?php echo $result->msgId;?>0" value="addAnswer" name="actionPerformed<?php echo $result->msgId;?>">
	  <input type="hidden" id="mentionedUsers<?php echo $result->msgId;?>" value="" name="mentionedUsers<?php echo $result->msgId;?>">

	  
	  </div>
     </div>
</form>

<!----------------------------------->
</li>
<?php }
}
?>
