<?php 
	if(isset($userGroup) && (strcmp($userGroup,'cms') !== 0)) {
	$detailPageUrl = isset($detailPageUrl)?$detailPageUrl:-1;
	if($ansCount ==0){
		$message = 'Be the first one to answer...';
	}else{
		$message = 'Know an answer to this question? Type it here.';
	}
	if(isset($enterpriseView) && (strcmp($enterpriseView,'true') == 0)){
		$message = 'Answer the question to get connected with the user.';
	}

?>
<!--Start_Inline_Comment-->
<div id="inlineAnswerFormCntainer<?php echo $threadId; ?>" >
<input type ="hidden" id="detailPageUrl<?php echo $threadId; ?>" value="<?php echo $detailPageUrl; ?>" />
	<!-- Start of form -->
	<?php
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$threadId,'before' => 'disableReplyFormButton('.$threadId.'); try{ if(validateFields(this) != true){ enableReplyFormButton('.$threadId.'); return false;} else { disableReplyFormButton('.$threadId.')} }catch (e) {}','success' => $callBackFunction));
	?>
		<div style="background:#eef7fe;padding:4px">
			<div class="float_L" style="width:83px;line-height:24px;padding-top:4px;">&nbsp;<b>Your Answer</b></div>
			<div style="margin-left:86px">
				<div class="float_L row">
					<div style="margin-right:7px;">
						<textarea name="replyText<?php echo $threadId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p inlineWidgetFormClass" id="replyText<?php echo $threadId; ?>" validate="validateStr" caption="Answer" maxlength="<?php echo ANSWER_CHARACTER_LENGTH; ?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH; ?>" required="true" onfocus="try{ showAnswerForm('<?php echo $threadId; ?>',58,'<?php echo $message; ?>'); }catch (e){}" style="height:20px;width:96%;"  validateSingleChar='true'><?php echo $message; ?></textarea>
					</div>
					<!--Start_For Show and Hide-->
					<div style="display:none;padding-left:10px;overflow:hidden;line-height:20px;" id="hiddenFormPart<?php echo $threadId; ?>">
						<div style="padding-top:1px">
							<table width="100%" cellpadding="0" border="0">
							<tr>
							<td><span id="replyText<?php echo $threadId; ?>_counter">0</span> out of <?php echo ANSWER_CHARACTER_LENGTH; ?> character</td>
							<script>
							  if(document.getElementById('replyText<?php echo $threadId; ?>').offsetWidth > 450)
							    document.write("<td><div align='right'><span align='right'>Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
							  else
							    document.write("<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
							</script>
							</tr></table>
						</div>
						<div class="errorPlace" style="display:block;line-height:17px;">
							<div class="errorMsg" id="replyText<?php echo $threadId; ?>_error"></div>
						</div>
						<div class="errorPlace" style="display:block;line-height:17px;">
							<div class="errorMsg" id="seccode<?php echo $threadId; ?>_error"></div>
						</div>
						<input type="hidden" name="threadid<?php echo $threadId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $threadId; ?>" />
						<input type="hidden" name="secCodeIndex" value="seccodeForInlineAnswer" id="secCodeIndex" />	
						<input type="hidden" name="fromOthers<?php echo $threadId; ?>" value="user" id="fromOthers<?php echo $threadId; ?>" />
						<input type="hidden" name="actionPerformed<?php echo $threadId; ?>" value="addAnswer" id="actionPerformed<?php echo $threadId; ?>" />
						<div style="padding:6px 0 13px 0">
							<input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $threadId; ?>" />
							<span><a href="javascript:void(0);" onClick="hideAnswerForm('<?php echo $threadId; ?>',20,'<?php echo $message; ?>',true);">Cancel</a></span>
						</div>
					</div>
					<!--End_For Show and Hide-->
				</div>				
			</div>
			<div class="withClear clear_L">&nbsp;</div>
		</div>		
	</form>
	<!-- End of form -->
</div>
<!--End_Inline_Comment-->
<?php } ?>
