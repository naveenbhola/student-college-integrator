<?php if(isset($userGroup)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
	$type = (isset($type))?$type:'question';
	$tHeight = (isset($tHeight))?$tHeight:'18px';

	$msgLength = ANSWER_CHARACTER_LENGTH;
	if($type=='question' || $type=='answer' || $type=='rating' || $type=='bestanswer'){
	    if($ansCount ==0){
		    $message = 'Be the first one to answer...';
	    }else{
		    $message = 'Know a better answer, write it here...';
	    }
	    $answerText = 'Answer';
	    $fromOthersValue = 'user';
	}else if($type=='discussion'){
	    $message = 'Write a comment and express your opinion...';
	    $answerText = 'Comment';
	    $fromOthersValue = 'discussion';
	}


 //Code to compensate impact on Listing AnA.This $check variable will be used later to differentiate.


$showMention = isset($showMention)?$showMention:false;
?>

<!--Start_Inline_Comment-->
<!----<div id="inlineAnswerFormCntainer<?php //echo $threadId; ?>" >-->
<?php $style = ($quesDetailPage)?"style='display:none; margin-top:20px' ":" ";?>
<div id="hiddenFormPart<?=$threadId?>" <?php echo $style;?>>
		
        <div style="clear:left">
			<div class="cmnt-pointer"></div>
			<div style="display:none;" id="commentDiv<?=$threadId?>"></div>
		</div>
        
<div style="width:507px; margin-bottom:12px" class="fbkBx">
<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
	<!-- Start of form -->
	
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	//echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId,'before' => 'try{ checkTextElementOnTransition(document.getElementById(\'replyText'.$threadId.'\'),\'focus\');if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')} }catch (e) {}','success' => $callBackFunction));
	?>
	<form novalidate action="<?=$url?>/<?=$threadId?>" onsubmit="return false;" method="post" id="answerFormToBeSubmitted<?=$threadId?>" >

		<!--Start_For Show and Hide-->
		<div id="hiddenFormPart_<?=$threadId?>" style="display: block; overflow: hidden;">
			<div class="cntBx" style="margin:5px">
				<div>
					<?php if($showMention){ ?>
					<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyText<?php echo $threadId; ?>','true');" id="replyText<?php echo $threadId; ?>" class="ftxArea" validate="validateStr" caption="Answer" maxlength="<?php echo $msgLength;?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH;?>" required="true" rows="1" style="height:20px; font-size:13px; color:#333 !important" validateSingleChar='true'></textarea>
						<?php }else{ ?>
					<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this);" id="replyText<?php echo $threadId; ?>" class="ftxArea" validate="validateStr" caption="Answer" maxlength="<?php echo $msgLength;?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH;?>" required="true" rows="1" style="height: 56px; font-size:13px; color:#333 !important" validateSingleChar='true'></textarea>
						<?php } ?>
				</div>
                <div class="clearFix spacer5"></div>
                <div class="flLt">
				<div class="Fnt10 fcdGya"><span id="replyText<?php echo $threadId; ?>_counter">0</span> out of <?php echo $msgLength;?> characters</div>
                <div class="spacer5 clearFix"></div>
				<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div></div>
				<!-- Code for Facebook integration -->
				<!-- Code End for Facebook integration -->
                <div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $threadId; ?>_error"></div></div>
                <div class="flLt Fnt11 fcdGya" style="margin-top:3px">Kindly follow our <a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a></div>
                </div>
                
                <div class="flRt" style="padding-top:5px">
				<input type="button" class="orange-button" value="Submit" onclick = "disableReplyFormButton('<?=$threadId?>'); <?php echo $onClick ?> try{ checkTextElementOnTransition(document.getElementById('replyText<?=$threadId?>'),'focus');if(validateFields($('answerFormToBeSubmitted<?=$threadId?>')) != true){enableReplyFormButton('<?=$threadId?>');  adjustAutoSuggestorOnError('<?=$threadId?>');  return false;} }catch (e) {}; if(makeAnswerAjax){ makeAnswerAjax = false; new Ajax.Request('/messageBoard/MsgBoard/replyMsg/<?=$threadId?>',{onSuccess:function(request){<?=$callBackFunction?>}, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$threadId?>'))});} return false;" id="submitButton<?php echo $threadId; ?>"/>&nbsp;&nbsp;
				<a href="javascript:void(0);" onClick="makeAnswerAjax = true; hideHomepageAnswerForm('<?php echo $threadId; ?>',true);">Cancel</a>
			    		
			</div>
				</div>
			</div>
			
            <div class="clearFix spacer5"></div>

			
			<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" />
			<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />
			<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="<?php echo $fromOthersValue;?>" id="fromOthers<?php echo $threadId; ?>" />
			<input type="hidden" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" id="actionPerformed<?php echo $threadId; ?>"/>
			<input type="hidden" name="mentionedUsers<?php echo $threadId; ?>" value="" id="mentionedUsers<?php echo $threadId; ?>"/>
			<input type="hidden" name="tracking_keyid" id="tracking_keyid_<?php echo $threadId;?>" value=''>


		<?php //echo $hiddenURL;?>
		<!--<input type="hidden" name="hiddenCode" value="<?php //echo $hiddenCode;?>" />-->
	</form>
	<!-- End of form -->
</div>
</div>
<!--End_Inline_Comment-->
<?php } ?>
