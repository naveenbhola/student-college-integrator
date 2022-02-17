<?php 
	$HeadingOfReply = 'Answer';
	if($fromOthers != 'user'){
		$HeadingOfReply = 'Comment';
	}
	
	$textBoxWidth = "width:470px;";
	if($_COOKIE['client']<=1024)
		$textBoxWidth = "width:335px;";
	
	if($_COOKIE['client']<=800)
		$textBoxWidth = "width:210px;";
	
	$userProfile = site_url('getUserProfile').'/';        
	$entityTypeShown = isset($entityTypeShown)?$entityTypeShown:'';
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	$ansDivClass="";	
	$subContainerClass="";
	$marginLeftForComment = '';
	if($subComment){
		$subContainerClass="subContainer";
		$ansDivClass='subContainerAnswer';
		$marginLeftForComment = 'padding-left:'.(($dotCount -1) * 5).'%';
	}
	$userAnswerForQuestionDetail = ($subComment)?'':'userAnswerForQuestionDetail';

   if(($flag==1) && (($actionToBePerformed == 'addComment') || ($actionToBePerformed == 'addAnswer'))): ?>
<div class="fbkBx" id="completeMsgContent<?php echo $msgId; ?>" <?php if(isset($isWall) && $isWall==1 && $actionToBePerformed == 'addComment') echo "style='width:507px;'";?>>
	<div class="<?php echo $ansDivClass; ?> wdh100">

			<div class="imgBx">
			    <img id="userProfileImageForComment<?php echo $msgId.rand(0, 10000);?>" src="<?php echo getTinyImage($userProfileImage);?>" style="cursor:pointer;" onClick="window.location=('<?php echo site_url('getUserProfile').'/'.$displayName; ?>');" />
			</div>

		<div style="margin:0 5px 0 45px;">
				<div>
				  <span class="Fnt11" onmouseover="showUserCommonOverlayForCard(this,'<?php echo $displayNameId; ?>');" ><a href=""><?php echo ($firstName!='' && $lastName!='')?($firstName.' '.$lastName):$displayName; ?></a></span>
				  <span title="click to change your display name"  style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp;
				  <?php if($immediateParentName != ''){ ?><span class="Fnt11"><a href="<?php echo $userProfile.$immediateParentName; ?>">@<?php echo $immediateParentName; ?></a></span><?php } ?>
				  <span style="padding-bottom:10px;position:relative" id="msgTxtContent<?php echo $msgId; ?>" class="<?php echo $userAnswerForQuestionDetail; ?> lineSpace_18 Fnt11">
					  <?php 
							  $text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
							  echo formatQNAforQuestionDetailPage($text,500);
					  ?>
				  </span>
				</div>
				<div class="float_L fcdGya Fnt11">a few secs ago <?php if($immediateParentName != ''){ ?><span> in reply to <?php echo $immediateParentName; ?></span><?php } ?></div>
<!--				<div class="float_R">
					<span id="abuseLink<?php echo $msgId;?>"><a class="Fnt11" href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $parentId;?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>',0,0);return false;">Report&nbsp;Abuse</a></span>
				</div>-->
				<div class="clear_B">&nbsp;</div>				

				<div style="line-height:1px;clear:both">&nbsp;</div>
				<div class="showMessages" style="display:none;" id="confirmMsg<?php  echo $msgId; ?>"></div>
				<!--Start_ReplyForm-->
				<div style="display:none;" class="formReplyBrder" id="replyForm<?php echo $msgId;  ?>">
				<?php  
					echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubCommentForQues('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
				?>
				<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $msgId; ?>"><span class="OrgangeFont">Reply to</span> <?php echo $displayName; ?></div>
				<?php if($dotCount == 1){ ?>
					<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $msgId; ?>"><span class="fontSize_12p">Your Answer - </span><span class="grayFont">Posted <?php  echo $temp['creationDate']; ?></span> </div>
				<?php } ?>
				<div>
					<textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="2000" minlength="2" required="true" rows="5" style="width:98%;" validateSingleChar='true'></textarea>
				</div>
				<div>
					<table width="100%" cellpadding="0" border="0">
					<tr>
					<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of 2000 character</td>
					<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a>&nbsp;</span></div></td>
					</tr></table>
				</div>
				<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div></div>
				<input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php echo $displayName; ?>" />
				<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
				<input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
				<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
				<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
				<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
				<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
				<div style="padding-top:10px"><input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $msgId; ?>" /> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_formForQues('<?php echo $msgId; ?>','ForHiding');" /></div>
				</form>
				</div>
				<!--Start_ReplyForm-->
				<!--Start_AbuseForm-->
				<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
				</div>
				<!--End_AbuseForm-->
		</div>
	</div>
</div>
<?php if(!$subComment){ ?>
	<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:none;">
		<div id="replyPlace<?php echo $mainAnsId;  ?>"></div>
	</div>
<?php } ?>

		<?php 
				if(!$subComment): 
		?>
<!-- 		<div class="dottedLineMsg" style="clear:both">&nbsp;</div> -->
<?php 
				endif;
		elseif(($actionToBePerformed == 'editAnswer') && ($status === 'Edited')): //condition for edit.
			$text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
			echo 'EDITEDBYSHIKSHAUSER!*#$#@!$'.formatQNAforQuestionDetailPage($text,500);
		else:
			echo $status;
		endif;
?>
