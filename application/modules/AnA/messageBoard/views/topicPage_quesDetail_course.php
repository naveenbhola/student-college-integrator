<?php
	$replyContext = "addComment";
	$check = "";
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
	$userImageURL = ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';
?>
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />
<div id="copyofAnswerDivs"></div>
<!--Start_MainAnswer-->
<?php
$questionAskedBy = $main_message['userId'];
$allAnswersList = '';
$closeAnswerDiv = false;
$totalMessage = 0;
$totalAnswers = count($topic_messages);
if(isset($topic_messages) && is_array($topic_messages)):
	echo "<div id='mainAnswersContainerDiv' >";	//Added by Ankur on 4 July	
	foreach($topic_messages as $message):
		$noOfCommentsInThread = 0;
		$flagForReplies = 0;
		$commentCountDisplayed = 0;
		$abusedComment = 0;
		$totalMessages++;
		foreach($message as $key => $temp): 
			$commentUserId = $temp['userId'];
			$msgId = $temp['msgId'];
			$noOfCommentsInThread++;
			$dotCount = substr_count($temp['path'],'.');
				$ansDivClass="";	
				$subContainerClass="";
				$subComment = false;
				$marginLeftForComment = 'style="margin-left:0%"';
				$userAnswerForQuestionDetail= '';
				$temp['userImage'] = ($temp['userImage']=='')?'/public/images/photoNotAvailable.gif':$temp['userImage'];
				//Added by Ankur on 4 July
				$ansStyle = ""; $ansBGStyle = "";
				if($temp['sortFlag']==90000){ $ansStyle=" style='background-color:#fff;' ";	$ansBGStyle = " background-color:#fff; ";}//In case of Expert, Best Answer, Your answer etc
				if($dotCount <= 1){ 
					$mainAnswerId = $msgId;
					if($closeAnswerDiv) echo "</div>"; //Added by Ankur on 4 July	
					if($totalAnswers != $totalMessages) {
						echo "<div id='answerContainerDiv$msgId' class='qna-box' style='margin-top:8px ;border-bottom:1px dashed #DADADA'>";
					} else {
						echo "<div id='answerContainerDiv$msgId' class='qna-box' style='margin-top:8px ;'>";
					}
					$closeAnswerDiv = true;
					echo "<input type='hidden' id='sortFlag$msgId' value='".$temp['sortFlag']."'>";
					echo "<input type='hidden' id='rating$msgId' value='".($temp['digUp']-$temp['digDown'])."'>";
					echo "<input type='hidden' id='created$msgId' value='$msgId'>";
					echo "<input type='hidden' id='reputation$msgId' value='".$temp['reputation']."'>";
					$allAnswersList .= $msgId.",";
				}

				if($dotCount > 1){
					$commentCountDisplayed++;
					$sortFlag = isset($message[$noOfCommentsInThread-2]['sortFlag'])?$message[$noOfCommentsInThread-2]['sortFlag']:0;
					if($sortFlag == '180000'){
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
					$marginLeftForComment = 'padding-left:10%';
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
	endif; 
?>

		<div style="display:none;" id="abuseFormText">
		</div>

<!-- Block to start the Replies container (when it is the first comment to an answer )-->
<?php
	if(($dotCount > 1) && ($flagForReplies == 0)) {
	$flagForReplies = 1;
?>

<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $msgId; ?>">&nbsp;</div>

<div class="comment-section"  id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:block;">

	<!-- Block start for View All Comments Link -->
	<?php if($closeDiscussion == 1){ ?>
	<div class="comment-sub-section" id="viewComments<?php echo $mainAnsId; ?>">
	        <?php $divIdForReplies = 'repliesContainer'.$mainAnsId; ?>
	        <div class="<?php echo $ansDivClass; ?>">
	          <a href="javascript:void(0)" onClick="javascript:showRepliesDiv(this,'<?php echo $mainAnsId; ?>','','newQuestionDetailPage');return false;" class="fbxVw Fnt11">View All <span id="replyAnswerCount<?php echo $mainAnsId;?>"><?php echo count($message) -1;?></span> <?php if((count($message) -1) > 1) { echo "Comments";}else{ echo "Comment";} ?></a>
	        </div>
	</div>
	<?php }else{ ?>
	
	<?php if((count($message) -1) > 5){ ?>
	<div class="comment-sub-section" style="display:<?php if((count($message) -1) > 5) echo "block";else echo "none";?>;" id="viewComments<?php echo $mainAnsId; ?>">
		<?php $divIdForReplies = 'repliesContainer'.$mainAnsId; ?>
		<div class="<?php echo $ansDivClass; ?>">
		  <a href="javascript:void(0)" onClick="javascript:showRepliesDiv(this,'<?php echo $mainAnsId; ?>','','newQuestionDetailPage');return false;" class="fbxVw Fnt11">View All <span id="replyAnswerCount<?php echo $mainAnsId;?>"><?php echo count($message) -1;?></span> Comments</a>
		</div>
	</div>
	<?php } } ?>
	<!-- Block End for View All Comments Link -->

	<?php
		}
	 ?>


<?php if((($temp['status'] == 'live') && ($temp['displayname'] != ''))||(($temp['status'] == 'abused')&& ($isCmsUser == 1) && ($temp['displayname'] != '')) ||(($temp['status'] == 'abused')&& ($dotCount > 1) && ($temp['displayname'] != ''))):?>		
		
				<?php 
					$temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
				?>	
				<?php if($dotCount==1): ?>	
						<!-- Question section  -->
						<p class="ans-text" id="msgTxtContent<?php echo $msgId; ?>"><?php echo formatQNAforQuestionDetailPage($temp['msgTxt'],300);?></p>
		                <div class="user-details clear-width">
		                <?php 
		                	$displayName = (!empty($temp['lastname']))?$temp['firstname'].' '.$temp['lastname'] : $temp['firstname'];
		                ?>
		                    <p class="flLt">Posted by: <span><?php echo $displayName; ?></span> 
			                <?php if(isset($badges[$commentUserId]) && !empty($badges[$commentUserId])):?>
		                    <span class="white-btn">
		                    <?php if($badges[$commentUserId] == 'CurrentStudent'):?>
		                    <?php echo "Current Student"?>
		                    <?php elseif($badges[$commentUserId] == 'Alumni'):?>
		                    Alumni
		                    <?php elseif($badges[$commentUserId] == 'Official'):?>
		                    Official
		                    <?php endif;?>
		                    </span> 
		                    <?php endif;?>
		                    <?php echo $temp['creationDate'];?></p>
							<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user') && ($temp['digUp']==0) && ($temp['digDown']==0) && (count($message)<=1) && ($temp['bestAnsFlag'] != 1)):
							?>                    
			                    <a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $msgId; ?>);return false;" class="flRt">Edit</a>
								<input type='hidden' id='suggestion1InstituteId' value="<?php if($answerSuggestions[$msgId][0][0]) echo $answerSuggestions[$msgId][0][0];?>" />
								<input type='hidden' id='suggestion1InstituteTitle' value="<?php if($answerSuggestions[$msgId][0][1]) echo ($answerSuggestions[$msgId][0][1]);?>" />
								<input type='hidden' id='suggestion2InstituteId' value="<?php if($answerSuggestions[$msgId][1][0]) echo $answerSuggestions[$msgId][1][0];?>" />
								<input type='hidden' id='suggestion2InstituteTitle' value="<?php if($answerSuggestions[$msgId][1][1]) echo $answerSuggestions[$msgId][1][1];?>" />
								<input type='hidden' id='suggestion3InstituteId' value="<?php if($answerSuggestions[$msgId][2][0]) echo $answerSuggestions[$msgId][2][0];?>" />
								<input type='hidden' id='suggestion3InstituteTitle' value="<?php if($answerSuggestions[$msgId][2][1]) echo $answerSuggestions[$msgId][2][1];?>" />
			                    
		                    <?php endif;?>
		                    
		                    
							  <?php if($userId!=$commentUserId ){ if($temp['reportedAbuse']==0){ 
								if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ 
							  ?>
							  	<span id="abuseLink<?php echo $msgId;?>" class="flRt"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>','<?php echo $raanstrackingPageKeyId;?>');return false;">Report&nbsp;Abuse</a></span>
							  <?php }}else{ ?>
							  	<span id="abuseLink<?php echo $msgId;?>" class="flRt">Reported as inappropriate</span>
							  <?php }} ?>	
							  
							  	<?php if($isCmsUser == 1): ?>
		  						<?php if($temp['status']!='abused'): ?>
		  							<span class="flRt" id="<?php echo $msgId; ?>_dcfc_txt" style="display: none;">Deleted</span>
									<a class="flRt" id="<?php echo $msgId; ?>_dcfc" href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>','answer');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete &nbsp;&nbsp;</a>
		  						<?php else:?>
		  							<span class="flRt">Deleted</span>
		  						<?php endif;?>
		  					<?php endif;?>	
		                </div>
		                
		                <?php if($temp['bestAnsFlag'] == 1):?>
									<span class="bestStar">Best Answer</span>
						<?php endif;?>		 
						               
		                <p class="useful-links">Did you find this answer useful: 
		                	<a href="javascript: void(0);" onClick="updateDig(this,'<?=$msgId?>',1,'<?php echo $threadId?>','<?php echo $commentUserId?>','<?php echo $flagForBestAnswerWithDigUp; ?>','answer','listingPage','<?php echo $tupanstrackingPageKeyId;?>');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">Yes</a>
		                	<span class="pipe">|</span>
		                	<a href="javascript: void(0);" onClick="updateDig(this,'<?=$msgId?>',0,'<?php echo $threadId?>','<?php echo $commentUserId?>','<?php echo $flagForBestAnswerWithDigUp; ?>','answer','listingPage','<?php echo $tdownanstrackingPageKeyId;?>');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">No</a>
		                </p>
	                
	                	<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $msgId; ?>">&nbsp;</div>
	                	
		                <?php if($fromOthers == 'user'): ?>
		                	<?php $this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$msgId,'subCatID'=>$subCatID)); ?>
		                	
								<?php if($flagForSelectBestAnswerLink): ?>
									<span class="Fnt11" style="display:block;margin-top:3px;">
									<a href="javascript:void(0);" onClick="showBestAnsOverLay(<?php echo $msgId; ?>,<?php echo $threadId; ?>,<?php echo $commentUserId; ?>);">Select as Best Answer</a>
									</span>
								<?php endif; ?>
		                <?php endif;?>
		                
		                <!-- Answer edit form starts here -->
							<div style="display:none;margin-left:68px; width:81.5%;margin-top:10px;margin-bottom:10px;" class="formReplyBrder" id="replyForm<?php echo $msgId;?>">
								<?php  
						
								//echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubCommentForQues('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
								?>
								<form novalidate action="<?=$url?>/<?=$msgId?>" onsubmit="return false;" method="post" id="answerFormToBeSubmitted<?=$msgId?>">
								<div>
									<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $msgId; ?>"><span class="OrgangeFont">Reply to</span> <?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; }?></div>
									<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user')): ?>
										<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $msgId; ?>"><span class="fontSize_12p">Your Answer - </span><span class="grayFont">Posted <?php  echo $temp['creationDate']; ?></span> </div>
									<?php endif; ?>
								<div>
									<textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyText<?php echo $msgId; ?>', 'true','true');" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="<?php echo ANSWER_CHARACTER_LENGTH; ?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH; ?>" required="true" rows="5" style="width:98%; height: 15px;" validateSingleChar='true'></textarea>								</div>
								<div>
									<table width="100%" cellpadding="0" border="0">
									<tr>
									<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of <?php echo ANSWER_CHARACTER_LENGTH; ?> character</td>
									<script>
									  if(document.getElementById('replyText<?php echo $msgId; ?>').offsetWidth > 450)
										document.write("<td><div align='right'><span align='right'>Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
									  else
										document.write("<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
									</script>
									</tr></table>
								</div>
								<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div></div>
								<input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" />
								<input type="hidden" name="sortFlag<?php echo $msgId; ?>" value="<?php echo $temp['sortFlag'];?>" />
								<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
								<input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
								<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
								<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
								<input type="hidden" name="displaynameId<?php echo $msgId; ?>" value="<?php echo $temp['userId']; ?>" />
								<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
								<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
								<input type="hidden" name="EmentionedUsers<?php echo $msgId; ?>" value="" id="EmentionedUsers<?php echo $msgId; ?>"/>
								<?php if($fromOthers == "group" || $fromOthers == "school") 
								$onClick = "checkcommentmem(this.form,'".$pageKeySuffixForDetail."SUBMITCOMMENT','".$fromOthers."');return false;"; 
								else 
								$onClick = ''; 
								?>
								<?php //$this->load->view('messageBoard/autoSuggestorForInstitute');  ?>
								<div style="padding-top:10px"><input type="button" value="Submit" onclick = "<?php echo $onClick ?> if(validateFields($('answerFormToBeSubmitted<?=$msgId?>')) != true){return false;} else { disableReplyFormButton('<?=$msgId?>')} new Ajax.Request('<?=$url?>/<?=$msgId?>',{onSuccess:function(request){ addSubCommentForQues('<?=$msgId?>',request.responseText,'<?=$mainAnsId?>','<?=$functionToCall?>'); }, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$msgId?>'))}); return false;" class="orange-button" id="submitButton<?=$msgId; ?>" />
								&nbsp; <a href="javascript:void(0);" onClick="hidereply_formForQues('<?php echo $msgId; ?>','ForHiding');" >Cancel</a></div>
							  </div>
							</form>
					
							</div>
		                <!-- Answer edit form ends here -->
						<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
						</div>		                
		                
	            <?php else:?>
	                   <?php if($commentCountDisplayed  > 5 || $closeDiscussion == 1):?>
	                   		<?php $commentStyle = "display:none !important";?>
	                   	<?php else:?>
	                   		<?php $commentStyle = "";?>	
	                   <?php endif;?>
	                   <!-- Comment section  -->
	        			<?php if(($temp['status'] == 'abused')&& ($dotCount > 1) && ($isCmsUser == 0)): ?> 
	        					<div class="Fnt11" style="padding-top:5px;padding-bottom:5px;">
	        					This entity has been removed on account of abuse reports.
	        					</div>
	        					<?php  $commentCountDisplayed--;$abusedComment++;?>
	        			<?php else:?>
	        					<?php $flagForBestAnswerWithDigUp = (($userId==$questionAskedBy)&&(!$temp['bestAnsFlag']))?true:false;?>
	        			<?php endif;?>	      
	        			
	        			      
	                    <div class="comment-sub-section answer_<?php echo $mainAnswerId; ?>" style="margin-top:15px; <?php echo $commentStyle;?>"  >
	                        <p class="comment-text" id="msgTxtContent<?php echo $msgId; ?>">
	                        <?php 
									$temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
									echo formatQNAforQuestionDetailPage($temp['msgTxt'],2500);
							?>
	                        </p>
           				  <?php 
		                	$displayName = (!empty($temp['lastname']))?$temp['firstname'].' '.$temp['lastname'] : $temp['firstname'];
		                ?>	                        
	                        <div class="user-details clear-width" style="margin-bottom:0">
	                            <p class="flLt">Posted by: <span><?php echo $displayName; ?></span></p>
	                            <p class="flRt"><?php echo $temp['creationDate'];?></p>
	                            <div class="clearFix spacer5"></div>
								<?php if($isCmsUser == 1): ?>
								  <?php if($temp['status']!='abused'): ?>	
										<span id="<?php echo $msgId; ?>_dcfc_txt" style="background-position:-301px -72px;padding-left:18px;display: none;">Deleted</span>
										&nbsp;&nbsp;<a id="<?php echo $msgId; ?>_dcfc" href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>','comment');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete</a>
								  <?php else:?>
									  <span style="background-position:-301px -72px;padding-left:18px">Deleted</span>
								  <?php endif;?>
							    <?php endif;?>
							    
							                             
								<?php if($userId!=$commentUserId ){ if($temp['reportedAbuse']==0){ 
									if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ ?>
					  					<a class="flRt" id="abuseLink<?php echo $msgId;?>" href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>','<?php echo $raansctrackingPageKeyId;?>');return false;">Report&nbsp;Abuse</a>
					  				<?php }}else{ ?>
					  					<span class="flRt" id="abuseLink<?php echo $msgId;?>" >Reported as inappropriate</span>
					  			<?php }} ?>	                            
	                        </div>
	                        <div class="clearFix"></div>
	                    </div>	            
	                    
	                    <div class="clearFix"></div>
                <?php endif;?>
					
				<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->
                
		<!--Block Start_ReplyForm in case of Edit answer only -->
		<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user') && ($temp['digUp']==0) && ($temp['digDown']==0) && (count($message)<=1)): ?>
			<div style="display:none;margin-left:68px; width:60.5%;margin-top:10px;margin-bottom:10px;" class="formReplyBrder" id="replyForm<?php echo $msgId;?>">
				<?php  
				if($isCmsUser == 0 && !$doNoShowAnswerForm):
				//echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript:addSubCommentForQues('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\');'));
				?>
				<form novalidate action="<?=$url?>/<?=$msgId?>" onsubmit="return false;" method="post" id="answerFormToBeSubmitted<?=$msgId?>">
				<div>
					<div class="bld" style="padding-bottom:5px" id="messageForReply<?php echo $msgId; ?>"><span class="OrgangeFont">Reply to</span> <?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; }?></div>
					<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user')): ?>
						<div style="padding-bottom:5px;display:none;" id="messageForEdit<?php echo $msgId; ?>"><span class="fontSize_12p">Your Answer - </span><span class="grayFont">Posted <?php  echo $temp['creationDate']; ?></span> </div>
					<?php endif; ?>
				<div>
					<textarea name="replyText<?php echo $msgId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyText<?php echo $msgId; ?>', 'true','true');" class="textboxBorder mar_left_10p" id="replyText<?php echo $msgId; ?>" validate="validateStr" caption="Answer" maxlength="<?php echo ANSWER_CHARACTER_LENGTH; ?>" minlength="<?php echo ANSWER_MIN_CHARACTER_LENGTH; ?>" required="true" rows="5" style="width:98%; height: 15px;" validateSingleChar='true'></textarea>
				</div>
				<div>
					<table width="100%" cellpadding="0" border="0">
					<tr>
					<td><span id="replyText<?php echo $msgId; ?>_counter">0</span> out of <?php echo ANSWER_CHARACTER_LENGTH; ?> character</td>
					<script>
					  if(document.getElementById('replyText<?php echo $msgId; ?>').offsetWidth > 450)
						document.write("<td><div align='right'><span align='right'>Make sure your answer follows <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
					  else
						document.write("<td><div align='right'><span align='right'>Shiksha <a href='javascript:void(0);' onclick='return popitup(\"<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline\");'>Community Guidelines</a>&nbsp;</span></div></td>");
					</script>
					</tr></table>
				</div>
				<div class="errorPlace" style="display:block"><div class="errorMsg" id="replyText<?php echo $msgId; ?>_error"></div></div>
				<input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" />
				<input type="hidden" name="sortFlag<?php echo $msgId; ?>" value="<?php echo $temp['sortFlag'];?>" />
				<input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" />
				<input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" />
				<input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" />
				<input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" />
				<input type="hidden" name="displaynameId<?php echo $msgId; ?>" value="<?php echo $temp['userId']; ?>" />
				<input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
				<input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
				<input type="hidden" name="EmentionedUsers<?php echo $msgId; ?>" value="" id="EmentionedUsers<?php echo $msgId; ?>"/>
				<?php if($fromOthers == "group" || $fromOthers == "school") 
				$onClick = "checkcommentmem(this.form,'".$pageKeySuffixForDetail."SUBMITCOMMENT','".$fromOthers."');return false;"; 
				else 
				$onClick = ''; 
				?>
				
				<div style="padding-top:10px"><input type="button" value="Submit" onclick = "<?php echo $onClick ?> if(validateFields($('answerFormToBeSubmitted<?=$msgId?>')) != true){return false;} else { disableReplyFormButton('<?=$msgId?>')} new Ajax.Request('<?=$url?>/<?=$msgId?>',{onSuccess:function(request){ addSubCommentForQues('<?=$msgId?>',request.responseText,'<?=$mainAnsId?>','<?=$functionToCall?>'); }, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$msgId?>'))}); return false;" class="orange-button" id="submitButton<?=$msgId; ?>" />
				&nbsp; <a href="javascript:void(0);" onClick="hidereply_formForQues('<?php echo $msgId; ?>','ForHiding');" >Cancel</a></div>
			  </div>
			</form>
			<?php endif; ?>
			</div>
			<?php endif;?>
		
		<!--Block End_ReplyForm-->

		<!--Block Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
		</div>
		<!--Block End_AbuseForm-->
<!--End_MainAnswer-->

<!-- Block Start for Reply place. This is required since in this block we will add any reply/comment by the user -->
<?php if((count($message) == $noOfCommentsInThread) && ($dotCount >= 2) && ($flagForReplies == 1)) { ?>
	<div class="comment-section" id="replyPlace<?php echo $mainAnsId;  ?>"  style="margin-left: 0px !important"></div>
<?php } ?>
<!-- Block End for Reply place. This is required since in this block we will add any reply/comment by the user -->
<!-- Block start to Show Post comment form -->
 <?php if((($commentCountDisplayed+1+$abusedComment)==count($message)) && !$doNoShowAnswerForm){ 
		
		if(count($message)==1){	 ?>
		    
			<div class="comment-section" id="replyPlace<?php echo $mainAnsId;  ?>" ></div>
		
		<?php } ?>
		<div class="cmnt-pointer"></div>
		<div class="fbkBx" id="replyCommentForm<?php echo $msgId;?>" style="width:98% ">
			<?php  
			echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$msgId.'/campusTab_quesDetail','before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$msgId.')}','success' => 'javascript: newDiscussionForm('.$msgId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\',\''.$check.'\',\''.$replyContext.'\');'));
			?>
			<div class="subContainerAnswer">
			  <div style='display:block;'>
				<textarea name="replyCommentText<?php echo $msgId; ?>_hide" class="ftBx" id="replyCommentText<?php echo $msgId; ?>_hide" style="height:18px;width:98%;" onclick="try{ showAnswerCommentForm('<?php echo $msgId; ?>','<?=$ansctrackingPageKeyId?>'); }catch (e){}" validateSingleChar='true'>Write a comment...</textarea>
			  </div>
			
			<!--Start_For Show and Hide-->

			<div style="display:none;overflow:hidden;" id="hiddenCommentFormPart<?php echo $msgId; ?>">
			    <div class="cntBx">
					<div class="float_L wdh100">                                                            
						<div class="wdh100 float_L">
							<div><textarea name="replyCommentText<?php echo $msgId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyCommentText<?php echo $msgId; ?>','true');" class="ftxArea" id="replyCommentText<?php echo $msgId; ?>" validate="validateStr" caption="Comment" maxlength="2500" minlength="3" required="true" rows="3" style="height:15px;" validateSingleChar='true'></textarea></div>
							<div class="Fnt10 fcdGya"><span id="replyCommentText<?php echo $msgId; ?>_counter">0</span> out of 2500 characters</div>
							<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyCommentText<?php echo $msgId; ?>_error"></div></div>
							<div class="lineSpace_1">&nbsp;</div>                                                                                                                                
							<!-- Code for Facebook integration -->
							<?php /*$isChecked = 'checked';
							      if(isset($_COOKIE['facebookCheck']) &&  $_COOKIE['facebookCheck'] == 'no'){
								$isChecked = ''; } */
							?>
							<!--<div class="Fnt11">
							    <input id="facebookCheck<?php //echo $msgId; ?>" name="facebookCheck<?php //echo $msgId; ?>" value="true" type="checkbox" <?php //echo $isChecked; ?> style="position:relative;top:2px;left:-4px" onClick="setFacebookCheck('<?php //echo $msgId; ?>');"/>Also post this on my Facebook wall
							</div>-->
							<!-- Code End for Facebook integration -->
							<div class="float_L lineSpace_22 Fnt11 fcdGya">Make sure your answer follows <a href="javascript:void(0);" onclick='return popitup("<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/communityGuideline");'>Community Guidelines</a>
								<span style="margin-left:40px;margin-top:2px;display:none;" id="loaderDiv"><img src="/public/images/working.gif" /></span>
							</div>
							<?php if($fromOthers == "group" || $fromOthers == "school") 
							  $onClick = "checkcommentmem(this.form,'".$pageKeySuffixForDetail."SUBMITCOMMENT','".$fromOthers."');return false;"; 
							else 
							  $onClick = ''; 
							?>
							<div class="float_R pr10 lineSpace_22">
								<input type="Submit" class="orange-button" value="Submit" onclick = "<?php echo $onClick ?>" id="submitButton<?php echo $msgId; ?>"/>&nbsp;&nbsp;
								<a href="javascript:void(0);" onClick="hideAnswerCommentForm('<?php echo $msgId; ?>',true);">Cancel</a>
							</div>
							<div class="clear_B">&nbsp;</div>
						</div>
					</div>
			    </div>
			    <div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $msgId; ?>_error"></div></div>
			    <input type="hidden" name="displayname<?php echo $msgId; ?>" value="<?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" id="displayname<?php echo $msgId; ?>" />
			    <input type="hidden" name="sortFlag<?php echo $msgId; ?>" value="<?php echo $temp['sortFlag'];?>" id="sortFlag<?php echo $msgId; ?>" />
			    <input type="hidden" name="threadid<?php echo $msgId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $msgId; ?>" />
			    <input type="hidden" name="dotCount<?php echo $msgId; ?>" value="<?php echo $dotCount; ?>" id="dotCount<?php echo $msgId; ?>" />
			    <input type="hidden" name="fromOthers<?php echo $msgId; ?>" value="<?php echo $fromOthers; ?>" id="fromOthers<?php echo $msgId; ?>" />
			    <input type="hidden" name="mainAnsId<?php echo $msgId; ?>" value="<?php echo $mainAnsId; ?>" id="mainAnsId<?php echo $msgId; ?>" />
			    <input type="hidden" name="displaynameId<?php echo $msgId; ?>" value="<?php echo $temp['userId']; ?>" />
			    <input type="hidden" name="actionPerformed<?php echo $msgId; ?>" id="actionPerformed<?php echo $msgId; ?>" value="addComment" />
			    <input type="hidden" name="functionToCall<?php echo $msgId; ?>" id="functionToCall<?php echo $msgId; ?>" value="<?php echo $functionToCall; ?>" />
			    <input type="hidden" name="userProfileImage<?php echo $msgId; ?>" id="userProfileImage" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" />
			    <input type="hidden" name="mentionedUsers<?php echo $msgId; ?>" value="" id="mentionedUsers<?php echo $msgId; ?>"/>
	      
			</div>
			</form>
		  </div>
		</div>
		

<?php } ?>
<!-- Block End of Post Comment form -->



<?php
	else: //Else for main if statement
?>
<!-- Start of answer/comment deleted place -->
<?php if($dotCount == 1){ ?>
  <div class="subContainer" style="padding-bottom:10px;padding-top:10px;">
	  <div class="subContainerAnswer">
		  This entity has been removed on account of abuse reports.
	  </div>
  </div>
<?php } ?>
<!-- End of answer/comment deleted place -->
<?php
	endif; // End of main if statement.
?>

<?php if((count($message) == $noOfCommentsInThread) && ($dotCount >= 2) && ($flagForReplies == 1)) { ?>
	<!-- This is end of repliesContainer div -->	
	</div>
	
<?php } ?>



<?php endforeach; // This is end for nested foreach ?>


<?php
	if($closeAnswerDiv && $totalMessages==count($topic_messages)) echo "</div>"; //Added by Ankur on 4 July	
	endforeach; //This is end of main foreach.
	echo "</div>";	//Added by Ankur on 4 July	
endif;

?>
<script>
var closeDiscussion = '<?php echo $closeDiscussion; ?>';
var bestAnsFlagForThread = '<?php echo $bestAnsFlagForThread; ?>';
var allAnswersList = '<?php echo $allAnswersList;?>';
var filterURL = '<?php echo $filterURL;?>';
</script>
