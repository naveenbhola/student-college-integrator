<?php
	$innerDiv = '';
	$displayContent = '';
	$isOverlay = "false";
	if($display == 'none'){
		$innerDiv = 'class="mar_full_10p" style="padding:10px 0;"';
		$displayContent = 'style="display:none;"';
		$isOverlay = "true";
	}
  
?>
<div id="shareThisQuestionContainer" <?php echo $displayContent; ?>>
	<div <?php echo $innerDiv; ?>>
		<?php
			$userInfoArray = explode('|',$validateuser[0]['cookiestr']);
			$email = $userInfoArray[0];
			echo $this->ajax->form_remote_tag( array(
						'url' => '/search/sendMail',
						'update' => '',
						'before' => 'if(!validateEmailForm()){return false;}',
						'success' => 'javascript:mailSentForShareQuestion(request.responseText,\''.$isOverlay.'\')'
					)
				);
			$NameOFUser = (trim($validateuser[0]['firstname']) != '')?$validateuser[0]['firstname']:$validateuser[0]['displayname'];
			$entityType = isset($entityType)?$entityType:'question';
			$extraParams = base64_encode(serialize(array('NameOFUser' => $NameOFUser,'threadId' => $threadId,'msgTxt' => $msgTxt,'entityType' => $entityType)));
			if($entityType == 'question' || $entityType == 'user'){
			  $displayMsg = "Enter email addresses separated by comma or semicolon to send this question to friends who might know the answer.";
			  $buttonClass = "spirit_middle shik_shareQuestionButton";
			  $subjectVal = "$NameOFUser has shared a question with you on Shiksha Cafe";
			}
			else{
			  $displayMsg = "Enter email addresses separated by comma or semicolon to send this ".$entityType." to friends who might like to comment.";
			  $buttonClass = "spirit_middle shik_shareQuestionButton";
			  $strDis = "a";
			  if($entityType=='announcement' || $entityType=='eventAnA') $strDis = "an";
			  $subjectVal = "$NameOFUser has shared $strDis $entityType with you on Shiksha Cafe";
			}
		?>
			<div><?php echo $displayMsg;?></div>
			<div style="margin:8px 0">
				<textarea name="searchEmailAddr" id="emailIdsForShare" style="width:95%;height:50px" class="shik_bgTextArea"></textarea>
			</div>
			<div id="emailIdsForShare_error" class="errorMsg"></div>
			<div style="line-height:5px;">&nbsp;</div>
			<div>
				<div class="float_L" style="width:132px;height:22px"><input type="Submit" id="emailIdsForShareButton" value="&nbsp;" class="<?php echo $buttonClass;?>" /></div>
				<div class="float_L txt_align_c" style="width:54px;height:21px;line-height:22px"><a href="javascript:void(0);" onClick="try { document.getElementById('emailIdsForShare').value='';hideOverlay(); } catch(e) {}">Cancel</a></div>
				<div class="clear_L">&nbsp;</div>
			</div>
			<input type="hidden" name="extraParams" value="<?php echo $extraParams; ?>" />
			<input type="hidden" name="fromAddress" id="fromAddressForShare" value="<?php echo $email; ?>"/>
			<input type="hidden" name="subject" id="subjectForShare" value="<?php echo $subjectVal;?>"/>
			<input type="hidden" name="listingTypeForMail" value="qnaShareQuestion"/>
			<input type="hidden" name="listingUrlForMail" value="<?php echo $urlForQuestion; ?>"/>
		</form>
		<div class="showMessages" style="margin-top:10px;display:none;" id="confirmMsg_forShare"></div>
	</div>
</div>