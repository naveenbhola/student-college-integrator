<?php if(isset($userGroup) ) {
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
	}else if($type=='announcement'){
	    $message = 'Write a comment and express your opinion...';
	    $answerText = 'Comment';
	    $fromOthersValue = 'announcement';
	}


 //Code to compensate impact on Listing AnA.This $check variable will be used later to differentiate.

$requestUrl = (string)$_SERVER['REQUEST_URI'];
$httpRefereral = (string)$_SERVER['HTTP_REFERER'];
$check = false;
$requestCheck = strpos($requestUrl,"listinganatab");
$check = $requestCheck;
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getOlderWallDataForListings");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getCommentSection");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}
if($requestCheck == false){
$requestCheck = strpos($requestUrl,"getAnswerForm");
$httpCheck = strpos($httpRefereral,"listinganatab");
$check = $requestCheck&&$httpCheck;
}

$showMention = isset($showMention)?$showMention:false;
?>

<!--Start_Inline_Comment-->
<div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" >
<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
	<!-- Start of form -->
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	//echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId,'before' => 'try{ checkTextElementOnTransition(document.getElementById(\'replyText'.$threadId.'\'),\'focus\');if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')} }catch (e) {}','success' => $callBackFunction));
	?>
	<form novalidate action="<?=$url?>/<?=$threadId?>" onsubmit="return false;" method="post" id="answerFormToBeSubmitted<?=$threadId?>">
		<div>
			<?php if(!isset($anstrackingPageKeyId)){ $anstrackingPageKeyId=0;}?>
		  <textarea name="replyText<?php echo $threadId; ?>_hide" class="ftBx" id="replyText<?php echo $threadId; ?>_hide" value="<?php echo $message;?>" default="<?php echo $message;?>"  style="height:<?php echo $tHeight;?>;width:98%;"  onclick="try{ showHomepageAnswerForm('<?php echo $threadId; ?>',<?=$anstrackingPageKeyId?>); }catch (e){}" validateSingleChar='true' ><?php echo $message;?></textarea>
		</div>

		<!--Start_For Show and Hide-->
		<div style="display:none;padding-left:10px;line-height:20px;" id="hiddenFormPart<?php echo $threadId; ?>">
			<div class="Fnt11 wdh100" style="margin-left:45px;">Your <?php echo $answerText?></div>
			<div class="imgBx">
				<div class="wdh100">
				  <img id="userProfileImageForComment<?php echo $threadId; ?>" align='left' valign='top' src="<?php echo ($userImageURL != '')?getTinyImage($userImageURL):getTinyImage('/public/images/photoNotAvailable.gif');?>" />
				</div>
			</div>
			<div class="cntBx">
			
				<div class="float_L wdh100">
					<div class="wdh100 float_L">
						<div>
						<?php if($showMention){ ?>
							<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyText<?php echo $threadId; ?>','true');" id="replyText<?php echo $threadId; ?>" class="ftxArea" validate="validateStr" caption="Answer" maxlength="<?php echo $msgLength;?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH;?>" required="true" rows="1" style="height: 15px; " validateSingleChar='true'></textarea>
						<?php }else{ ?>
							<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this);" id="replyText<?php echo $threadId; ?>" class="ftxArea" validate="validateStr" caption="Answer" maxlength="<?php echo $msgLength;?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH;?>" required="true" rows="1" style="height: 56px;" validateSingleChar='true'></textarea>
						<?php } ?>
						</div>
						<div class="Fnt10 fcdGya"><span id="replyText<?php echo $threadId; ?>_counter">0</span> out of <?php echo $msgLength;?> characters</div>
						<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div></div>
						
						<!-- Code for Facebook integration -->

						<!-- Code End for Facebook integration -->
						<div class="float_L lineSpace_22 Fnt11 fcdGya">Make sure your <?php echo $answerText?> follows <a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a></div>
					</div>
				</div>
			</div>
			<div class="clear_B">&nbsp;</div>

			<input id="suggestedInstitutes<?=$threadId?>" name="suggestedInstitutes<?=$threadId?>" type="hidden" value='' /> 
			<div id="suggestionHolder<?=$threadId?>" style="display:block;">
			</div>

			<div class="float_R pr10 lineSpace_22">
				<input type="button" class="orange-button" value="Submit" onclick = "disableReplyFormButton('<?=$threadId?>'); <?php echo $onClick ?> try{ checkTextElementOnTransition(document.getElementById('replyText<?=$threadId?>'),'focus');if(validateFields($('answerFormToBeSubmitted<?=$threadId?>')) != true){enableReplyFormButton('<?=$threadId?>');  adjustAutoSuggestorOnError('<?=$threadId?>');  return false;} }catch (e) {}; if(makeAnswerAjax){ makeAnswerAjax = false; new Ajax.Request('/messageBoard/MsgBoard/replyMsg/<?=$threadId?>',{onSuccess:function(request){<?=$callBackFunction?>}, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$threadId?>'))});} return false;" id="submitButton<?php echo $threadId; ?>"/>&nbsp;&nbsp;
				<a href="javascript:void(0);" onClick="makeAnswerAjax = true; hideHomepageAnswerForm('<?php echo $threadId; ?>',true);">Cancel</a>
			</div>
            <div class="clear_B">&nbsp;</div>

			<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $threadId; ?>_error"></div></div>
			<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" />
			<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />
			<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="<?php echo $fromOthersValue;?>" id="fromOthers<?php echo $threadId; ?>" />
			<input type="hidden" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" id="actionPerformed<?php echo $threadId; ?>"/>
			<input type="hidden" name="mentionedUsers<?php echo $threadId; ?>" value="" id="mentionedUsers<?php echo $threadId; ?>"/>
			<input type="hidden" name="tracking_keyid" id="tracking_keyid<?php echo $threadId;?>" value=''>
		</div>
		<?php //echo $hiddenURL;?>
		<!--<input type="hidden" name="hiddenCode" value="<?php //echo $hiddenCode;?>" />-->
	</form>
	<!-- End of form -->
</div>
<!--End_Inline_Comment-->
<?php } ?>
