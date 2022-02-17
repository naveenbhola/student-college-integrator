<?php
	$threadId = $main_message['msgId'];
	echo "<script>"; 
	echo "var threadId = '".$threadId."'; \n";		
	echo "</script>";
	$textBoxWidth = "width:470px;";
	if($_COOKIE['client']<=1024)
		$textBoxWidth = "width:335px;";
	
	if($_COOKIE['client']<=800)
		$textBoxWidth = "width:210px;";

	if(!isset($fromOthers))	
		$fromOthers = 'user';
	
	$userProfile = site_url('getUserProfile').'/';
	$isCmsUser =0;
	if((is_array($validateuser))&&(strcmp($validateuser[0]['usergroup'],'cms') == 0)){	
		$isCmsUser = 1;
	}
	$url = site_url("messageBoard/MsgBoard/replyMsg");
	$maximumCommentAllowed = 4;
	$functionToCall = isset($functionToCall)?$functionToCall:'-1';
	$entityTypeShown = isset($entityTypeShown)?$entityTypeShown:'';
	$articleId = isset($articleId)?$articleId:0;
	$eventId = isset($eventId)?$eventId:0;
?>
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />
<!--Start_MainAnswer-->
<?php
$questionAskedBy = $main_message['userId'];
if(isset($topic_messages) && is_array($topic_messages)): 
	foreach($topic_messages as $message):
		$noOfCommentsInThread = 0;
		$flagForReplies = 0;
// 		echo "<pre>"; print_r($message); echo "</pre>";
		foreach($message as $key => $temp): 
			$commentUserId = $temp['userId'];
			$msgId = $temp['msgId'];
			$noOfCommentsInThread++;
			$dotCount = substr_count($temp['path'],'.');
			//if(($temp['status'] == 'live') && ($temp['displayname'] != '')): //Start of main if statement.  
				$ansDivClass="";	
				$subContainerClass="";
				$subComment = false;
				$marginLeftForComment = 'style="margin-left:0%"';
				$userAnswerForQuestionDetail= '';
				if(($dotCount == 1) && ($userId == $commentUserId)){
					$userAnswerForQuestionDetail="userAnswerForQuestionDetail";
				}
				if($dotCount > 1){
					$sortFlag = isset($message[$noOfCommentsInThread-2]['sortFlag'])?$message[$noOfCommentsInThread-2]['sortFlag']:0;
					if($sortFlag == '18'){
					  $parentDisplayName = isset($main_message['listingTitle'])?$main_message['listingTitle']:'';
					  $parentSortFlag = $sortFlag;
					}
					else{
					  $parentDisplayName = isset($message[$noOfCommentsInThread-2]['displayname'])?$message[$noOfCommentsInThread-2]['displayname']:'';
					  $parentUserId = isset($message[$noOfCommentsInThread-2]['userId'])?$message[$noOfCommentsInThread-2]['userId']:0;
					  $parentSortFlag = '0';
					}
					$subComment = true;
					$subContainerClass="subContainer";
					$ansDivClass='subContainerAnswer';
					$marginLeftForComment = 'padding-left:'.(($dotCount -1) * 5).'%';
				}
				$digUpClass = 'digUpMsg_0';
				if($temp['digUp'] > 0){
					$digUpClass = 'digUpMsg_Green';
				}
				$digDownClass = 'digDownMsg_0';
				if($temp['digDown']	> 0){
					$digDownClass = 'digDownMsg_Red';
				}
				
				$flagForSelectBestAnswerLink = (($fromOthers == 'user') && ($userId == $questionAskedBy) && (!$subComment) && ($bestAnsFlagForThread == 0))?true:false;
?>
<?php
	$mainAnsId = $message[0]['msgId'];
	$commentsText = (((count($message)-1) == 1) || ((count($message)-1) == 0))?'<span id ="replyCountForAnswer'.$mainAnsId.'">'.(count($message)-1).'</span> Reply':'<span id ="replyCountForAnswer'.$mainAnsId.'">'.(count($message)-1).'</span> Replies';
	if(($noOfCommentsInThread == 1) && ($dotCount > 1)): // if statement for start of 2nd page in pagination.
		$divIdForReplies = 'repliesContainer'.$mainAnsId;
		$commentsText = ((count($message) == 1) || (count($message) == 0))?'<span id ="replyCountForAnswer'.$mainAnsId.'">'.count($message).'</span> Reply':'<span id ="replyCountForAnswer'.$mainAnsId.'">'.count($message).'</span> Replies';
?>
<div style="padding:5px 0px;">
		<a href="javascript:void(0);" onClick="javascript:showHideRepliesDiv(this,'<?php echo $divIdForReplies; ?>');return false;" class="plusIcons" id="replyContainerLink<?php echo $mainAnsId; ?>" <?php if(count($message) < 2){ echo 'style="display:none;"'; } ?>><?php echo $commentsText; ?></a>
</div>
<?php endif; ?>
		<div style="display:none;" id="abuseFormText">
		</div>
<?php
	if(($dotCount > 1) && ($flagForReplies == 0)) {
	$flagForReplies = 1;
?>
		<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:none;">
		<!-- Div for replies of answers.This is done to make the replies answer. -->
		<div id="replyPlace<?php echo $mainAnsId;  ?>"></div>

<?php
	}
	if((($temp['status'] == 'live') && ($temp['displayname'] != ''))||(($temp['status'] == 'abused')&& ($isCmsUser == 1) && ($temp['displayname'] != ''))): //Start of main if statement.  
 ?>
<div class="<?php echo $subContainerClass; ?>" style="padding-bottom:10px;<?php echo $marginLeftForComment; ?>" id="completeMsgContent<?php echo $msgId; ?>">
	<div class="<?php echo $ansDivClass; ?>">
		<?php if($subComment): ?>
		<div style="line-height:20px;width:100%">
			<?php if(trim($parentDisplayName) != ''){ 
			 if($parentSortFlag=='18'){ ?>
			Reply To <b><?php echo $parentDisplayName; ?></b>
			<?php }else{
			?>
			Reply To <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $parentUserId; ?>');" ><a href="<?php echo $userProfile.$parentDisplayName; ?>"><?php echo $parentDisplayName; ?></a></span>
			<?php }} ?>
		</div>
		<div style="line-height:5px">&nbsp;</div>
		<?php endif; ?>
		<div>
			<div style="display:block;width:100%;float:left;position:relative">
				<div class="float_R" style="width:50%;line-height:20px">
					<?php
						if(($fromOthers == 'user') && ($temp['bestAnsFlag'] == 1) && ($noOfCommentsInThread == 1)):
						$flagForSelectBestAnswerLink = false;
					?>
						<span class="bestStar">Chosen as Best Answer</span>
					<?php endif; ?>
					<?php if($flagForSelectBestAnswerLink): ?>
						<a href="javascript:void(0);" onClick="showBestAnsOverLay(<?php echo $msgId; ?>,<?php echo $threadId; ?>,<?php echo $commentUserId; ?>);" class="blankStar">Select as Best Answer</a>
					<?php endif; ?>&nbsp;
				</div>			
				<div style="line-height:20px;margin-right:50%">
					<?php if(($dotCount == 1) && ($userId == $commentUserId)){ 
								echo "Your answer -<span class='fcGya'> posted ".$temp['creationDate']."</span>";
					 		}else{
							  if($temp['sortFlag']=="18"){
							?>
							By <b><?php echo $main_message['listingTitle']; ?></b> - <span class="Fnt11 fcOrg">Verified by </span>Shiksha
							<br/><span class='fcGya'>posted <?php echo $temp['creationDate']; ?></span>
							<?php  }else{
							?>
							By <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['userId']; ?>');" ><a href="<?php echo $userProfile.$temp['displayname']; ?>">
									<?php echo $temp['displayname']; ?></span>
								</a> -<span class='fcGya'> posted <?php echo $temp['creationDate']; ?></span>
						    <?php }} ?>
					</div>
				<div style="line-height:5px;clear:both">&nbsp;</div>
			</div>
			<div style="line-height:5px;clear:both">&nbsp;</div>
			<div style="padding-bottom:10px;position:relative;line-height:18px;" id="msgTxtContent<?php echo $msgId; ?>" class="<?php echo $userAnswerForQuestionDetail; ?>">
				<?php 
					$temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
					echo formatQNAforQuestionDetailPage($temp['msgTxt'],30);
				?>
			</div>
		</div>	
		<div class="bottomNavigationLink">
			<ol style="height:25px">
				<?php if($fromOthers == 'user'): ?>
				<li style="width:110px">
					<span class="digImgMsg">
						<a href="javascript:void(0);" onClick="updateDig(this,'<?php echo $msgId; ?>',1);return false;" class="<?php echo $digUpClass; ?>"><?php echo $temp['digUp']; ?></a>
						<a href="javascript:void(0);" onClick="updateDig(this,'<?php echo $msgId; ?>',0);return false;" class="<?php echo $digDownClass; ?>"><?php echo $temp['digDown']; ?></a>
					</span>
				</li>
				<?php endif; ?>
				<?php if($dotCount == 1):
						$divIdForReplies = 'repliesContainer'.$mainAnsId;
				?>
				<li style="width:120px;">
					<div style="position:relative;top:6px;">
						<a href="javascript:void(0);" onClick="javascript:showHideRepliesDiv(this,'<?php echo $divIdForReplies; ?>');return false;" id="replyContainerLink<?php echo $mainAnsId; ?>" class="plusIcons" <?php if(count($message) < 2){ echo 'style="display:none;"'; } ?>><?php echo $commentsText; ?></a>&nbsp;
					</div>
				</li> 
				<?php endif; ?>
				<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user')):
				?>
				<li style="width:120px"><div style="position:relative;top:6px;">
							<span class="grayFont bld">[&nbsp;<a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $msgId; ?>);return false;" class="bld">Edit Answer</a>&nbsp;]</span></div>
				</li>
				<?php endif; ?>
				<li style="float:right">
					<div style="position:relative;top:6px;">
						<?php if(($dotCount < $maximumCommentAllowed) && ($closeDiscussion == 0) && ($isCmsUser != 1) && (($userId != $commentUserId) || ($fromOthers != 'user'))): ?>
							<a href="javascript:void(0);" onClick="reply_form(<?php echo $msgId; ?>);return false;">Reply</a>
							&nbsp; |	
						<?php endif;?>  &nbsp;
							<?php if($temp['reportedAbuse']==0){ 
							  if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ 
							?>
							<span id="abuseLink<?php echo $msgId;?>"><a href="javascript:void(0);" onClick="report_abuse('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>');return false;">Report&nbsp;inappropriate&nbsp;content</a></span>
							<?php }}else{ ?>
							<span id="abuseLink<?php echo $msgId;?>">Reported as inappropriate</span>
							<?php } ?>
					</div>
				</li>
			</ol>
			<div style="line-height:10px;clear:both">&nbsp;</div>
		</div> 
		<div style="line-height:1px;clear:both">&nbsp;</div>
		<div style="padding-bottom:10px">
		<?php if($isCmsUser == 1): ?>
		  <?php if($temp['status']!='abused'){ ?>	
		    <a href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete</a>
		  <?php }else{ ?>
		    <span style="background-position:-301px -72px;padding-left:18px">Deleted</span>
		  <?php } ?>
		<?php endif; ?>	
		</div>		
		<div class="showMessages" style="display:none;" id="confirmMsg<?php  echo $msgId; ?>">&nbsp;</div>
		<div style="line-height:10px">&nbsp;</div>
		<!--Start_ReplyForm-->
		<div style="display:none;" class="formReplyBrder" id="replyForm<?php echo $msgId;?>">
			<?php  
			if($isCmsUser == 0):
			echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubComment('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
			?>
			<div>
				<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $msgId; ?>"><span class="OrgangeFont">Reply to</span> <?php if($temp['sortFlag']=="18"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; }?></div>
				<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user')): ?>
					<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $msgId; ?>"><span class="fontSize_12p">Your Answer - </span><span class="grayFont">Posted <?php  echo $temp['creationDate']; ?></span> </div>
				<?php endif; ?>
			<div>
				<textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this)" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="2000" minlength="2" required="true" rows="5" style="width:98%;" validateSingleChar='true'></textarea>
			</div>
			<div>
				<table width="100%" cellpadding="0" border="0">
				<tr>
				<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of 2000 character</td>
				<script>
				  if(document.getElementById('replyText<?php echo $msgId; ?>').offsetWidth > 450)
				    document.write("<td><div align='right'><span align='right'>Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
				  else
				    document.write("<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
				</script>
				</tr></table>
			</div>
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div></div>
			<div style="padding:10px 0 5px 0">Type in the characters you see in picture below:</div>
			<div>
				<img src="/public/images/blankImg.gif" onabort="reloadCaptcha(this.id)" onClick="reloadCaptcha(this.id)" id="secimg<?php echo $msgId; ?>" align="absmiddle" /> &nbsp; &nbsp;
				<input type="text" type="text" name="seccode<?php echo $msgId; ?>" id="seccode<?php echo $msgId; ?>" validate="validateSecurityCode" caption="Security Code" maxlength="5" minlength="5" required="true" size="5" />
			</div>
			<div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div></div>
			<input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php if($temp['sortFlag']=="18"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" />
			<input type="hidden" name="sortFlag<?php echo $msgId; ?>" value="<?php echo $temp['sortFlag'];?>" />
			<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
			<input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
			<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
			<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
			<input type="hidden" name="displaynameId<?php echo $msgId; ?>" value="<?php echo $temp['userId']; ?>" />
			<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
			<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
			<?php if($fromOthers == "group" || $fromOthers == "school") 
			$onClick = "checkcommentmem(this.form,'".$pageKeySuffixForDetail."SUBMITCOMMENT','".$fromOthers."');return false;"; 
            else 
            $onClick = ''; 
			?>
			<div style="padding-top:10px"><input type="Submit" value="Submit" onclick = "<?php echo $onClick ?>" class="submitGlobal" id="submitButton<?php echo $msgId; ?>" /> &nbsp; <input type="button" value="Cancel" class="cancelGlobal" onClick="hidereply_form('<?php echo $msgId; ?>','ForHiding');" /></div>
			</form>
		</div>
		<?php endif; ?>
		</div>
		<!--Start_ReplyForm-->
		<!--Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
		</div>
		<!--End_AbuseForm-->
	</div>
</div>
<!--End_MainAnswer-->

<?php if($dotCount >= 2) { ?>
	<!-- Div for replies of replies as the place for answers replies are placed above -->
	<div id="replyPlace<?php echo $msgId;  ?>"></div>
<?php } ?>
<?php if (count($message) == 1) { ?>
	<!-- Place for replies if the number of replies are less. -->
	<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:none;">	
		<div id="replyPlace<?php echo $msgId;  ?>"></div>
	</div>
<?php } ?>

<?php
	else: //Else for main if statement
?>
		<!-- Start of answer/comment deleted place -->
		<div class="subContainer" style="padding-bottom:10px;padding-top:10px;padding-left:<?php echo (($dotCount -1) * 5);?>%;">
			<div class="subContainerAnswer">
				This entity has been removed on account of report abuse.
			</div>
		</div>
		<div style="padding-bottom:10px;">
			<?php if($dotCount == 1):
					$divIdForReplies = 'repliesContainer'.$mainAnsId;
			?>
				<a href="javascript:void(0);" onClick="javascript:showHideRepliesDiv(this,'<?php echo $divIdForReplies; ?>');return false;" id="replyContainerLink<?php echo $mainAnsId; ?>" class="plusIcons" <?php if(count($message) < 2){ echo 'style="display:none;"'; } ?>><?php echo $commentsText; ?></a>
			<?php endif; ?>
		</div>
		<!-- End of answer/comment deleted place -->
<?php
	endif; // End of main if statement.
?>
<?php if((count($message) == $noOfCommentsInThread) && ($dotCount >= 2) && ($flagForReplies == 1)) { ?>
	<!-- This is end of repliesContainer div -->	
	</div>
<?php } ?>
<?php endforeach; // This is end for nested foreach ?>
<div class="dottedLineMsg" style="clear:both">&nbsp;</div>
<?php
	endforeach; //This is end of main foreach.
endif;
?>
<script>
var closeDiscussion = '<?php echo $closeDiscussion; ?>';
var bestAnsFlagForThread = '<?php echo $bestAnsFlagForThread; ?>';
</script>
