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
   if(($flag==1) && ($actionToBePerformed == 'addComment')): ?>
<div class="<?php echo $subContainerClass; ?>" style="padding-bottom:10px;<?php echo $marginLeftForComment; ?>" id="completeMsgContent<?php echo $msgId; ?>">
	<div class="<?php echo $ansDivClass; ?>">
		<?php if($subComment){ ?>
			<div style="line-height:20px;width:100%">
				<?php if($replyTosortFlag=='18'){ ?>
				Reply To <b><?php echo $replyToDisplayName; ?> </b>
				<?php }else{ ?>
				Reply To <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $replyToDisplayNameId; ?>');" ><a href=""><?php echo $replyToDisplayName; ?></a></span>
				<?php } ?>
			</div>
		<?php } ?>
		<div style="line-height:5px">&nbsp;</div>
		<div style="display:block;width:100%;float:left;position:relative">
			<div class="float_R" style="width:50%;line-height:20px">&nbsp;</div>	
			<div style="line-height:20px;margin-right:50%">
				By <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $displayNameId; ?>');" ><a href=""><?php echo $displayName; ?></a></span> ,a few secs ago
			</div>
			<div style="line-height:5px;clear:both">&nbsp;</div>
		</div>
		<div style="line-height:5px;clear:both">&nbsp;</div>
		<div style="padding-bottom:10px;position:relative" id="msgTxtContent<?php echo $msgId; ?>" class="<?php echo $userAnswerForQuestionDetail; ?>">
			<?php 
					$text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
					echo formatQNAforQuestionDetailPage($text,500);
			?>
		</div>
		<div class="bottomNavigationLink">
			<ol style="height:30px;width:100%">
				<?php if($fromOthers == 'user'): ?>
				<li style="width:110px; height:25px">
					<span class="digImgMsg" style="display:block;height:22px">
						<a style="display:block" href="javascript:void(0);" onClick="updateDig(this,'<?php echo $msgId; ?>',1);return false;" class="digUpMsg_0">0</a>
						<a style="display:block" href="javascript:void(0);" onClick="updateDig(this,'<?php echo $msgId; ?>',0);return false;" class="digDownMsg_0">0</a>
					</span>
				</li>
				<?php endif; ?>
				<?php  if($dotCount == 1): ?>
				 <li style="width:120px">
					<a href="javascript:void(0);" onClick="javascript:showHideRepliesDiv(this,'repliesContainer<?php echo $mainAnsId; ?>');return false;" class="doNotShowLink" id="replyContainerLink<?php echo $mainAnsId; ?>" style="font-weight:bold;display:none;"><span id ="replyCountForAnswer<?php echo $mainAnsId; ?>">0</span> Reply</a>
				</li> 
				<?php endif; ?>
				<?php if(($dotCount == 1) && ($fromOthers == 'user')){ ?>
				<li style="width:120px">
					<div style="position:relative;top:6px;">
						<span class="grayFont bld">[&nbsp;<a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $msgId; ?>);return false;" class="bld">Edit Answer</a>&nbsp;]</span>
					</div>
				</li>
				<?php } ?>
				<li style="float:right">
					<div style="position:relative;top:6px;">
						<?php if(($dotCount < $maximumCommentAllowed) && ($fromOthers != 'user')): ?>
							<a href="javascript:void(0);" onClick="reply_form(<?php echo $msgId; ?>);return false;">Reply</a>
							&nbsp; |
						<?php endif;?> &nbsp; 
							<span id="abuseLink<?php echo $msgId;?>"><a href="javascript:void(0);" onClick="report_abuse('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $parentId;?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>',0,0);return false;">Report&nbsp;inappropriate&nbsp;content</a></span>
					</div>
				</li>
			</ol>
			<div style="line-height:10px;clear:both">&nbsp;</div>
		</div>                        
		<div style="line-height:1px;clear:both">&nbsp;</div>
		<div class="showMessages" style="display:none;" id="confirmMsg<?php  echo $msgId; ?>"></div>
		<div style="line-height:10px">&nbsp;</div>
		<!--Start_ReplyForm-->
		<div style="display:none;" class="formReplyBrder" id="replyForm<?php echo $msgId;  ?>">
		<?php  
			echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubComment('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
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
		<div style="padding:10px 0 5px 0">Type in the characters you see in picture below:</div>
		<div>
			<img src="/public/images/blankImg.gif" onabort="reloadCaptcha(this.id)" onClick="reloadCaptcha(this.id)" id="secimg<?php echo $msgId; ?>" align="absmiddle" /> &nbsp; &nbsp;
			<input type="text" type="text" name="seccode<?php echo $msgId; ?>" id="seccode<?php echo $msgId; ?>" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" />
		</div>
		<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div></div>
		<input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php echo $displayName; ?>" />
		<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
		<input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
		<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
		<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
		<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
		<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
		<div style="padding-top:10px"><input type="Submit" value="Submit" class="submitGlobal" id="submitButton<?php echo $msgId; ?>" /> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_form('<?php echo $msgId; ?>','ForHiding');" /></div>
		</form>
		</div>
		<!--Start_ReplyForm-->
		<!--Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
		</div>
		<!--End_AbuseForm-->

	</div>
</div>
<?php if(!$subComment){ ?>
<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:none;">
	<div id="replyPlace<?php echo $msgId;  ?>"></div>
</div>
<?php }else{ ?>
	<div id="replyPlace<?php echo $msgId;  ?>"></div>
<?php } ?>
		<?php 
				if(!$subComment): 
		?>
		<div class="dottedLineMsg" style="clear:both">&nbsp;</div>
<?php 
				endif;
		elseif(($actionToBePerformed == 'editAnswer') && ($status === 'Edited')): //condition for edit.
			$text = html_entity_decode(html_entity_decode($text,ENT_NOQUOTES,'UTF-8'));
			echo 'EDITEDBYSHIKSHAUSER!*#$#@!$'.formatQNAforQuestionDetailPage($text,500);
		else: 
			echo $status;
		endif;
?>
