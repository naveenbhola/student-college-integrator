<?php if(isset($userGroup)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
	if($ansCount ==0){
		$message = 'Be the first one to answer...';
	}else{
		$message = 'Know an answer to this question? Type it here.';
	}?>
<!--Start_Inline_Comment-->
<div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" style="display:none;">
<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
	<!-- Start of form -->
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	//echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId,'before' => 'try{ checkTextElementOnTransition(document.getElementById(\'replyText'.$threadId.'\'),\'focus\');if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$threadId.')} }catch (e) {}','success' => $callBackFunction));
	?>
	<form novalidate action="<?=$url?>/<?=$threadId?>" onsubmit="return false;" method="post" id="answerFormToBeSubmitted<?=$threadId?>">
		<div class="ml24r80">
			<div class="rpybx">
				<div class="Fnt12 bld mb5 wdh100">&nbsp;<b>Your Answer</b></div>
				<div class="wdh100">
				<div class="imgBx">
					<div class="wdh100">
						<img id="userProfileImageForAnswer<?php echo $threadId;?>" align='left' valign='top' src="<?php echo ($loggedUserImageURL != '')?getSmallImage($loggedUserImageURL):'/public/images/photoNotAvailable.gif';?>"/>
					</div>
					<?php if($userId>0){ if($loggedUserImageURL != '' && (!strpos($loggedUserImageURL,'photoNotAvailable.gif'))){ ?>
					  <div class="txt_align_c Fnt12"><a href="#" onClick="return showUploadMyImage('updateNameImageOverlay','Change your profile photo here');"><span id="uploadImageTextForAnswer<?php echo $threadId;?>">Change</span></a></div>
					<?php }else{?>
					  <div class="txt_align_c Fnt12"><a href="#" onClick="return showUploadMyImage('updateNameImageOverlay','Upload your profile photo here');"><span id="uploadImageTextForAnswer<?php echo $threadId;?>">Upload</span></a></div>
					<?php }}?>
				</div>
  
				<div class="cntBx">
					<div class="wdh100 float_L">
					<div>
						<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyText<?php echo $threadId; ?>','true');" class="txArea" id="replyText<?php echo $threadId; ?>" validate="validateStr" caption="Answer" maxlength="<?php echo ANSWER_CHARACTER_LENGTH; ?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH; ?>" required="true" style="height:15px;width:96%;color:#565656;padding:3px;" value="<?php echo $message;?>" default="<?php echo $message;?>" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"  validateSingleChar='true'><?php echo $message;?></textarea>
					</div>
					<!--Start_For Show and Hide-->
					<div style="display:block;overflow:hidden;line-height:20px;" id="hiddenFormPart<?php echo $threadId; ?>">
						<div style="padding-top:1px">
						  <div class="Fnt10 fcdGya"><span id="replyText<?php echo $threadId; ?>_counter">0</span> out of <?php echo ANSWER_CHARACTER_LENGTH; ?> character</div>
						</div>
						<div class="errorPlace" style="display:block;line-height:17px;">
							<div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div>
						</div>
						<div class="lineSpace_2">&nbsp;</div>
						<!-- Code for Facebook integration -->
						<?php $isChecked = 'checked';
						      if(isset($_COOKIE['facebookCheck']) &&  $_COOKIE['facebookCheck'] == 'no'){
							$isChecked = ''; } 
						?>
						<!--<div class="Fnt11">
						    <input id="facebookCheck<?php //echo $threadId; ?>" name="facebookCheck<?php //echo $threadId; ?>" value="true" type="checkbox" <?php //echo $isChecked; ?> style="position:relative;top:2px;left:-4px" onClick="setFacebookCheck('<?php //echo $threadId; ?>');"/>Also post this on my Facebook wall
						</div>-->
						<!-- Code End for Facebook integration -->
						<input type="hidden" name="threadid<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" />
						<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />	
						<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="user" id="fromOthers<?php echo $threadId; ?>" />
						<input type="hidden" id="actionPerformed<?php echo $threadId; ?>" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" />
						<input type="hidden" name="mentionedUsers<?php echo $threadId; ?>" value="" id="mentionedUsers<?php echo $threadId; ?>"/>
						<input type="hidden" name="tracking_keyid" id="tracking_keyid<?php echo $threadId;?>" value=''>
						<div class="float_L lineSpace_22 fcdGya">
							<script>
							  if(document.getElementById('replyText<?php echo $threadId; ?>').offsetWidth > 450)
							    document.write("Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;");
							  else
							    document.write("Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;");
							</script>
						</div>
					</div>
					<!--End_For Show and Hide-->
				</div>
							
			</div>
			<div class="clear_B">&nbsp;</div>
			<s></s>

			<?php $this->load->view('messageBoard/autoSuggestorForInstitute');  ?>
			<div class="float_R pr10 lineSpace_22">
				<input type="button" value="Submit" class="orange-button" id="submitButton<?php echo $threadId; ?>"  onclick=" disableReplyFormButton('<?=$threadId?>'); try{ checkTextElementOnTransition(document.getElementById('replyText<?=$threadId?>'),'focus'); if(validateFields($('answerFormToBeSubmitted<?=$threadId?>')) != true){ enableReplyFormButton('<?=$threadId?>');  adjustAutoSuggestorOnError('<?=$threadId?>'); return false;} else { disableReplyFormButton('<?=$threadId?>')} }catch (e) {}; if(makeAnswerAjax){ makeAnswerAjax = false; new Ajax.Request('/messageBoard/MsgBoard/replyMsg/<?=$threadId?>',{onSuccess:function(request){<?=$callBackFunction?>}, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$threadId?>'))});} return false;" />
			</div>
			
			<div class="withClear clear_B">&nbsp;</div>
		</div>		
		</div>		
		</div>
		<?php //echo $hiddenURL;?>
		<!--<input type="hidden" name="hiddenCode" value="<?php //echo $hiddenCode;?>" />-->
	</form>
	<!-- End of form -->
</div>
<!--End_Inline_Comment-->
<?php } ?>
