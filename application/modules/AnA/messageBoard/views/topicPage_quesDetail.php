<?php
	$replyContext = 'addComment';
	$newCheck = "";
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
$totalMessages = 0;
if(isset($topic_messages) && is_array($topic_messages)): 
	echo "<div id='mainAnswersContainerDiv'>";	//Added by Ankur on 4 July	
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
					if($closeAnswerDiv) echo "</div>"; //Added by Ankur on 4 July	
					echo "<div id='answerContainerDiv$msgId' $ansStyle>"; 
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
		<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:block;">
		<!-- Div for replies of answers.This is done to make the replies answer. -->
		<div class="aqAns" <?php echo $ansStyle; ?> >
			<div class="wdh100">
				<div class="cntBx">
					<div class="wdh100 float_L">
					  <div class="pl33"><img src="/public/images/upArw1.png" /></div>

<!-- Block start for View All Comments Link -->
<?php if($closeDiscussion == 1){ ?>
<div class="fbkBx" id="viewComments<?php echo $mainAnsId; ?>">
        <?php $divIdForReplies = 'repliesContainer'.$mainAnsId; ?>
        <div class="<?php echo $ansDivClass; ?>">
          <a href="javascript:void(0)" onClick="javascript:showRepliesDiv(this,'<?php echo $mainAnsId; ?>');return false;" class="fbxVw Fnt11">View All <span id="replyAnswerCount<?php echo $mainAnsId;?>"><?php echo count($message) -1;?></span> <?php if((count($message) -1) > 1) { echo "Comments";}else{ echo "Comment";} ?></a>
        </div>
</div>
<?php }else{ ?>

<?php if((count($message) -1) > 5){ ?>
<div class="fbkBx" style="display:<?php if((count($message) -1) > 5) echo "block";else echo "none";?>;" id="viewComments<?php echo $mainAnsId; ?>">
	<?php $divIdForReplies = 'repliesContainer'.$mainAnsId; ?>
	<div class="<?php echo $ansDivClass; ?>">
	  <a href="javascript:void(0)" onClick="javascript:showRepliesDiv(this,'<?php echo $mainAnsId; ?>');return false;" class="fbxVw Fnt11">View All <span id="replyAnswerCount<?php echo $mainAnsId;?>"><?php echo count($message) -1;?></span> Comments</a>
	</div>
</div>
<?php } } ?>
<!-- Block End for View All Comments Link -->

<?php
	}


	if((($temp['status'] == 'live') && ($temp['displayname'] != ''))||(($temp['status'] == 'abused')&& ($isCmsUser == 1) && ($temp['displayname'] != '')) ||(($temp['status'] == 'abused')&& ($dotCount > 1) && ($temp['displayname'] != ''))): //Start of main if statement.  
 ?>
<?php if($closeDiscussion == 1){ ?>
        <div class="<?php if($dotCount==1) echo 'aqAns';else echo 'fbkBx'; ?>" style="display:<?php if(((count($message) - ($commentCountDisplayed+$abusedComment))>0)&&($dotCount>1)) echo "none";else echo "block";?>; <?php if($ansBGStyle!='') echo $ansBGStyle;?>" id="completeMsgContent<?php echo $msgId; ?>">
<?php }else{ ?>
<div class="<?php if($dotCount==1) echo 'aqAns';else echo 'fbkBx'; ?>" style="display:<?php if(((count($message) - ($commentCountDisplayed+$abusedComment))>5)&&($dotCount>1)) echo "none";else echo "block";?>; <?php if($ansBGStyle!='') echo $ansBGStyle;?>" id="completeMsgContent<?php echo $msgId; ?>">
<?php } ?>
  	<?php if($dotCount==1){ ?><div class="lineSpace_10">&nbsp;</div> <?php } ?>
	<div class="<?php echo $ansDivClass; ?> <?php if($dotCount==1) echo 'wdh100'; ?>">
		<!-- Block Start for Best Answer/Your Answer display . -->
		<?php if($dotCount==1){ ?>
			<?php
				if(($fromOthers == 'user') && ($temp['bestAnsFlag'] == 1) && ($noOfCommentsInThread == 1)){
				$flagForSelectBestAnswerLink = false;
			?>
				<div class="float_R"><span class="bestStar">Best Answer</span></div>			
				<div style="line-height:5px;clear:both">&nbsp;</div>
			<?php }else if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user')){
			  ?>
			  <div><span class="Fnt11 bld">Your Answer</span></div>
			  <div style="line-height:5px;clear:both">&nbsp;</div>
			  <?php } 
		  } ?>
		<!-- Block End for Best Answer/Your Answer display . -->

		<?php if(($temp['status'] == 'abused')&& ($dotCount > 1) && ($isCmsUser == 0)){ ?>
			<div class="Fnt11" style="padding-top:5px;padding-bottom:5px;">
				This entity has been removed on account of abuse reports.
			</div>
		<?php $commentCountDisplayed--;$abusedComment++; } else { ?>

		<!-- Block Start to display the User image -->
		<div class="imgBx">
		  <?php if($userId == $commentUserId){ ?>
			<img id="<?php if($dotCount>1)echo 'userProfileImageForComment';else echo 'userProfileImageForAnswer'; echo $msgId.rand(0, 10000);?>" src="<?php if($dotCount>1)echo getTinyImage($temp['userImage']); else echo getSmallImage($temp['userImage']);?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$temp['displayname']; ?>');"/>
		  <?php }else{ ?>
			<img src="<?php if($dotCount>1)echo getTinyImage($temp['userImage']); else echo getSmallImage($temp['userImage']);?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$temp['displayname']; ?>');"/>
		  <?php } ?>
		</div>
		<!-- Block End to display the User image -->

		<div class="cntBx">
			<div class="wdh100 float_L">
				<!-- Block Start for User name, posted date, user level -->
				<div style="line-height:20px;<?php if($dotCount==1) echo 'margin-right:30%'; ?>">
					<?php   if($temp['sortFlag']=="180000"){
							?>
							<b><?php echo $main_message['listingTitle']; ?></b> - <span class="Fnt11 fcOrg">Verified by </span>Shiksha
							<span class='fcGya'> <?php echo $temp['creationDate']; ?></span>
							<?php  }else{
							?>
							<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['userId']; ?>');" ><strong><a href="<?php echo $userProfile.$temp['displayname']; ?>" <?php if($dotCount>1)echo "class='Fnt11'"; ?>>
									<?php echo $temp['firstname'].' '.$temp['lastname']; ?></a></strong> 
								</span>
								<?php if(($dotCount<=1)&&($levelVCard[$commentUserId]['level'] != NULL)){echo ', '.$levelVCard[$commentUserId]['level'];} ?>
								<?php if(($userId == $commentUserId)){?><span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp; <?php } ?>
								<?php if(($dotCount<=1)&&($levelVCard[$commentUserId]['level'] != NULL)){ ?> <!--span class='forA'><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php //echo getTheRatingStar($levelVCard[$commentUserId]['level']); ?></a></span--><?php } ?>
								<?php if($dotCount==1){ ?>
									<span class='fcGya'> <?php echo $temp['creationDate']; ?></span>
									
									<!-- Block Start for Edit Answer -->
									<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user') && ($temp['digUp']==0) && ($temp['digDown']==0) && (count($message)<=1) && ($temp['bestAnsFlag'] != 1)):
									?>
									<span class="grayFont Fnt11" style="margin-left:15px;">[&nbsp;<a href="javascript:void(0);" onClick="javascript:showEditAnswerForm(<?php echo $msgId; ?>);return false;" >edit</a>&nbsp;]</span>
									<input type='hidden' id='suggestion1InstituteId' value="<?php if($answerSuggestions[$msgId][0][0]) echo $answerSuggestions[$msgId][0][0];?>" />
									<input type='hidden' id='suggestion1InstituteTitle' value="<?php if($answerSuggestions[$msgId][0][1]) echo ($answerSuggestions[$msgId][0][1]);?>" />
									<input type='hidden' id='suggestion2InstituteId' value="<?php if($answerSuggestions[$msgId][1][0]) echo $answerSuggestions[$msgId][1][0];?>" />
									<input type='hidden' id='suggestion2InstituteTitle' value="<?php if($answerSuggestions[$msgId][1][1]) echo $answerSuggestions[$msgId][1][1];?>" />
									<input type='hidden' id='suggestion3InstituteId' value="<?php if($answerSuggestions[$msgId][2][0]) echo $answerSuggestions[$msgId][2][0];?>" />
									<input type='hidden' id='suggestion3InstituteTitle' value="<?php if($answerSuggestions[$msgId][2][1]) echo $answerSuggestions[$msgId][2][1];?>" />
									<?php endif; ?>
									<!-- Block End for Edit Answer -->

								<?php } else { ?>
									<span style="padding-bottom:10px;line-height:18px;" id="msgTxtContent<?php echo $msgId; ?>" class="<?php echo $userAnswerForQuestionDetail; ?> lineSpace_18 Fnt11">
										<?php 
											$temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
											echo formatQNAforQuestionDetailPage($temp['msgTxt'],2500);
										?>
									</span>
								<?php } ?>
  
						    <?php } ?>
				</div>
				<!-- Block End for User name, posted date, user level -->

				<!-- Block Start for Answer/Comment Text -->
				<?php if($dotCount==1){ ?>
				<div style="padding-bottom:10px;line-height:18px;" id="msgTxtContent<?php echo $msgId; ?>" class="lineSpace_18">
					<?php 
						$temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
						echo formatQNAforQuestionDetailPage($temp['msgTxt'],300);
					?>
				</div>
				<?php } else { ?>
				  <div class="float_L fcdGya Fnt11"><?php echo $temp['creationDate']; ?></div>
				  <div class="float_R">
					  <?php if($userId!=$commentUserId ){ if($temp['reportedAbuse']==0){ 
						if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ 
					  ?>
					  <span id="abuseLink<?php echo $msgId;?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>',<?=$raansctrackingPageKeyId?>);return false;">Report&nbsp;Abuse</a></span>
					  <?php }}else{ ?>
					  <span id="abuseLink<?php echo $msgId;?>" class="Fnt11">Reported as inappropriate</span>
					  <?php }} ?>
				  </div>
				  <div class="clear_B">&nbsp;</div>				
				<?php } ?>
				<!-- Block End for Answer/Comment -->
				

				<!-- Block Start for Bottom navigation links like Digup, dig down, report abuse -->
				<div class="wdh100">

						<!-- Block Start for Dig up and Dig down -->
						<?php if($fromOthers == 'user' && $dotCount == 1): ?>
						<?php $this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$msgId,'subCatID'=>$subCatID)); ?>
                                                <?php if(isset($expertArray[$commentUserId]['instituteName']) && $expertArray[$commentUserId]['instituteName']!=''){ ?>
                                                <div style="font-size:11px;margin-top:10px;margin-bottom:10px;border-bottom:1px solid #ececec;border-top:1px solid #ececec;padding:8px 0; overflow:hidden">
                                                        <span class="flLt">About me:</span>
                            <div style="color:#747474; margin-left:60px;">
									<?php if(isset($expertArray[$commentUserId]['designation']) && $expertArray[$commentUserId]['designation']!='') echo $expertArray[$commentUserId]['designation'].", ";
                                                                        if(isset($expertArray[$commentUserId]['aboutCompany']) && $expertArray[$commentUserId]['aboutCompany']!='') echo $expertArray[$commentUserId]['aboutCompany'].", ";
                                                                        if(isset($expertArray[$commentUserId]['highestQualification']) && $expertArray[$commentUserId]['highestQualification']!='') echo $expertArray[$commentUserId]['highestQualification'];
                                                                        if(isset($expertArray[$commentUserId]['instituteName']) && $expertArray[$commentUserId]['instituteName']!='') echo ", ".$expertArray[$commentUserId]['instituteName'];
                                                                        ?>
                                                                        <?php if(isset($expertArray[$commentUserId]['signature']) && $expertArray[$commentUserId]['signature']!=''){ ?><br/><i><?php echo $expertArray[$commentUserId]['signature']; ?></i><?php } ?>
                                                        </div>
                                                </div>
                                                <?php } ?>

						<div class="float_L">
								<table cellspacing='0' cellpadding='0' border='0'>
								<tr>
								  <td colspan='2'>
									<span >
                                                                            <?php
                                                                           $flagForBestAnswerWithDigUp = (($userId==$questionAskedBy)&&(!$temp['bestAnsFlag']))?true:false;
                                                                           
                                                                           ?>

									<?php if($main_message['listingTitle'] != ""){ ?>
				                                        <p> 
                                				           Did you find this answer useful: 
				                                           <a href="javascript: void(0);" onClick="updateDig(this,'<?=$msgId?>',1,'<?php echo $threadId?>','<?php echo $commentUserId?>','<?php echo $flagForBestAnswerWithDigUp; ?>','answer','listingPage');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">Yes</a> 
                                				           <span class="pipe">|</span> 
				                                           <a href="javascript: void(0);" onClick="updateDig(this,'<?=$msgId?>',0,'<?php echo $threadId?>','<?php echo $commentUserId?>','<?php echo $flagForBestAnswerWithDigUp; ?>','answer','listingPage');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;">No</a> 
                                				        </p> 
									<?php }else{ ?>
									<?php if($temp['hasUserVotedUp']) {
										$userHasVotedUp = 'aqIcn Fnt11 upVote-actve-icn';
										$userHasVotedDown = 'aqIcn Fnt11 dwnVote-disable-icn';
									}else if($temp['hasUserVotedDown']){
										$userHasVotedUp = 'aqIcn Fnt11 upVote-disable-icn';
										$userHasVotedDown = 'aqIcn Fnt11 dwnVote-actve-icn';
									}else{
										$userHasVotedUp = 'aqIcn rUp Fnt11';
										$userHasVotedDown = 'aqIcn rDn Fnt11';
									}?>
                         						  <a href="javascript:void(0);" id="up_<?php echo $msgId; ?>" onMouseOver = "showLikeDislike(0,'<?php echo $msgId; ?>');" onMouseOut = "hideLikeDislike(0,'<?php echo $msgId; ?>');" onClick="updateDig(this,'<?php echo $msgId; ?>',1,'<?php echo $threadId?>','<?php echo $commentUserId?>','<?php echo $flagForBestAnswerWithDigUp; ?>','','',<?=$tupanstrackingPageKeyId?>);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?php echo $userHasVotedUp; ?>" style="color:#000;text-decoration:none"><?php echo $temp['digUp'];?></a>
									  <a href="javascript:void(0);" id="down_<?php echo $msgId; ?>" onMouseOver = "showLikeDislike(1,'<?php echo $msgId; ?>');" onMouseOut = "hideLikeDislike(1,'<?php echo $msgId; ?>');" onClick="updateDig(this,'<?php echo $msgId; ?>',0,'','','','','',<?=$tdownanstrackingPageKeyId?>);trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class=" <?php echo $userHasVotedDown; ?>" style="color:#000;text-decoration:none"><?php echo $temp['digDown']; ?></a>
									<?php } ?>

									</span>
									<!-- Block Start for Select Best Answer Link . -->
									<?php /*if($dotCount==1){ ?>
										<?php if($flagForSelectBestAnswerLink): ?>
											<span class="Fnt11 quesAnsBullets" style="padding-left: 10px;">
											<a href="javascript:void(0);" onClick="showBestAnsOverLay(<?php echo $msgId; ?>,<?php echo $threadId; ?>,<?php echo $commentUserId; ?>);">Select as Best Answersssss</a>
											</span>
										<?php endif; ?>&nbsp;
									<?php } */ ?>
									<!-- Block End for Select Best Answer Link. -->

								  </td>
								</tr>
								<tr>
								  <td width='50%'>
									<div id="likeDiv<?php echo $msgId; ?>" style="display:block;visibility:hidden;"></div></td><td><div id="dislikeDiv<?php echo $msgId; ?>" style="display:block;visibility:hidden;"></div>
								  </td>
								</tr>
								</table>
						</div>
						<?php endif; ?>
						<!-- Block End for Dig up and Dig down -->

						<!-- Block Start for Report Abuse link -->
						<?php if($dotCount == 1){ ?>
						<div class="float_R" style="valign:top;">
							<div>
									<?php if($userId!=$commentUserId  ){ if($temp['reportedAbuse']==0){ 
									  if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ 
									?>
									<span id="abuseLink<?php echo $msgId;?>" ><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeShown; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>',<?=$raanstrackingPageKeyId?>);return false;">Report&nbsp;Abuse</a></span>
									<?php }}else{ ?>
									<span id="abuseLink<?php echo $msgId;?>" >Reported as inappropriate</span>
									<?php }} ?>
							</div>
						</div>
						<?php } ?>
						<!-- Block End for Report Abuse link -->

					<div class="clear_B">&nbsp;</div>
				</div> 
				<!-- Block End for Bottom navigation links like Digup, dig down, report abuse -->

				<!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
				<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $msgId; ?>">&nbsp;</div>
				<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->

			</div>
		</div>	

		<?php } ?>
		<div style="line-height:<?php if($dotCount==1) echo "5";else echo "1"; ?>px;clear:both">&nbsp;</div>

		<!-- Block Start for Delete option to the CMS user -->
		<?php if($isCmsUser == 1): ?>
		  <?php if($temp['status']!='abused'){ ?>	
			<?php if($dotCount == 1){ ?>
			&nbsp;&nbsp;
			<span id="<?php echo $msgId; ?>_dcfc_txt" style="background-position:-301px -72px;padding-left:18px;display: none;">Deleted</span>
			<a id="<?php echo $msgId; ?>_dcfc" href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>','answer');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete</a>
			<?php }else{ ?>
			<span id="<?php echo $msgId; ?>_dcfc_txt" style="background-position:-301px -72px;padding-left:18px;display: none;">Deleted</span>
			&nbsp;&nbsp;<a id="<?php echo $msgId; ?>_dcfc" href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>','comment');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete</a>
			<?php } ?>
		      <?php }else{ ?>
		      <span style="background-position:-301px -72px;padding-left:18px">Deleted</span>
		  <?php } ?>
		<?php endif; ?>	
		<!-- Block End for Delete option to the CMS user -->

		<!--Block Start_ReplyForm in case of Edit answer only -->
		<?php if(($dotCount == 1) && ($userId == $commentUserId) && ($fromOthers == 'user') && ($temp['digUp']==0) && ($temp['digDown']==0) && (count($message)<=1)){ ?>
			<div style="display:none;margin-left:68px; width:81.5%;margin-top:10px;margin-bottom:10px;" class="formReplyBrder" id="replyForm<?php echo $msgId;?>">
	
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
				<?php $this->load->view('messageBoard/autoSuggestorForInstitute');  ?>
				<div style="padding-top:10px"><input type="button" value="Submit" onclick = "<?php echo $onClick ?> if(validateFields($('answerFormToBeSubmitted<?=$msgId?>')) != true){return false;} else { disableReplyFormButton('<?=$msgId?>')} new Ajax.Request('<?=$url?>/<?=$msgId?>',{onSuccess:function(request){ addSubCommentForQues('<?=$msgId?>',request.responseText,'<?=$mainAnsId?>','<?=$functionToCall?>'); }, evalScripts:true, parameters:Form.serialize($('answerFormToBeSubmitted<?=$msgId?>'))}); return false;" class="orange-button" id="submitButton<?=$msgId; ?>" />
				&nbsp; <a href="javascript:void(0);" onClick="hidereply_formForQues('<?php echo $msgId; ?>','ForHiding');" >Cancel</a></div>
			  </div>
			</form>
	
			</div>
		<?php } ?>
		<!--Block End_ReplyForm-->

		<!--Block Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
		</div>
		<!--Block End_AbuseForm-->
	</div>
</div>
<!--End_MainAnswer-->

<!-- Block Start for Reply place. This is required since in this block we will add any reply/comment by the user -->
<?php if((count($message) == $noOfCommentsInThread) && ($dotCount >= 2) && ($flagForReplies == 1)) { ?>
	<div id="replyPlace<?php echo $mainAnsId;  ?>"></div>
<?php } ?>
<!-- Block End for Reply place. This is required since in this block we will add any reply/comment by the user -->

<!-- Block start to Show Post comment form -->
<?php if((($commentCountDisplayed+1+$abusedComment)==count($message))){ 
		
		if(count($message)==1){ ?>
			<div id="<?php echo 'repliesContainer'.$mainAnsId; ?>" style="display:block;">
			<div class="aqAns" <?php echo $ansStyle; ?> >
				<div class="wdh100">
					<div class="cntBx">
						<div class="wdh100 float_L">
						  <div class="pl33"><img src="/public/images/upArw1.png" /></div>
						  <!-- Div for replies of answers.This is done to make the replies answer. -->
						  <div id="replyPlace<?php echo $mainAnsId;  ?>"></div>
		<?php } ?>
		<div class="fbkBx" id="replyCommentForm<?php echo $mainAnsId;?>">
			<?php  
			echo $this->ajax->form_remote_tag( array('url'=>$url.'/'.$mainAnsId,'before' => 'if(validateFields(this) != true){return false;} else { disableReplyFormButton('.$mainAnsId.')}','success' => 'javascript:newDiscussionForm('.$mainAnsId.',request.responseText,'.$mainAnsId.',\''.$functionToCall.'\',\''.$newCheck.'\',\''.$replyContext.'\');'));
			?>
			<div class="subContainerAnswer">
			  <div style='display:block;'>

				<textarea name="replyCommentText<?php echo $mainAnsId; ?>_hide" class="ftBx" id="replyCommentText<?php echo $mainAnsId; ?>_hide" style="height:18px;width:98%;" onclick="try{ showAnswerCommentForm('<?php echo $mainAnsId; ?>','<?=$ansctrackingPageKeyId?>'); }catch (e){}" validateSingleChar='true'>Write a comment...</textarea>

			  </div>
			
			<!--Start_For Show and Hide-->

			<div style="display:none;overflow:hidden;" id="hiddenCommentFormPart<?php echo $mainAnsId; ?>">
				<div class="imgBx">
					<div class="wdh100">
					  <img id="userProfileImageForComment<?php echo $mainAnsId;?>" align='left' valign='top' src="<?php echo ($userImageURL != '')?getTinyImage($userImageURL):getTinyImage('/public/images/photoNotAvailable.gif');?>" />
					</div>
					<?php if($userId>0){ if($userImageURL != '' && (!strpos($userImageURL,'photoNotAvailable.gif'))){ ?>
					  <div class="Fnt11 txt_align_c"><a href="#" onClick="return showUploadMyImage('updateNameImageOverlay','Change your profile photo here');"><span id="uploadImageTextForComment<?php echo $mainAnsId;?>">Change</span></a></div>
					<?php }else{?>
					  <div class="Fnt11 txt_align_c"><a href="#" onClick="return showUploadMyImage('updateNameImageOverlay','Upload your profile photo here');"><span id="uploadImageTextForComment<?php echo $mainAnsId;?>">Upload</span></a></div>
					<?php }}?>
				</div>
			    <div class="cntBx">
					<div class="float_L wdh100">                                                            
						<div class="wdh100 float_L">
							<div><textarea name="replyCommentText<?php echo $mainAnsId; ?>" onkeyup="textKey(this); checkForNameMention(event,this,'replyCommentText<?php echo $mainAnsId; ?>','true');" class="ftxArea" id="replyCommentText<?php echo $mainAnsId; ?>" validate="validateStr" caption="Comment" maxlength="2500" minlength="3" required="true" rows="3" style="height:15px;" validateSingleChar='true'></textarea></div>
							<div class="Fnt10 fcdGya"><span id="replyCommentText<?php echo $mainAnsId; ?>_counter">0</span> out of 2500 characters</div>
							<div class="errorPlace Fnt11" style="display:none;"><div class="errorMsg" id="replyCommentText<?php echo $mainAnsId; ?>_error"></div></div>
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
								<input type="Submit" class="orange-button" value="Submit" onclick = "<?php echo $onClick ?>" id="submitButton<?php echo $mainAnsId; ?>"/>&nbsp;&nbsp;
								<a href="javascript:void(0);" onClick="hideAnswerCommentForm('<?php echo $mainAnsId; ?>',true);">Cancel</a>
							</div>
							<div class="clear_B">&nbsp;</div>
						</div>
					</div>
			    </div>
	      
			    <div class="errorPlace" style="display:block"><div class="errorMsg" id="seccode<?php echo $mainAnsId; ?>_error"></div></div>
			    <input type="hidden" name="displayname<?php echo $mainAnsId; ?>" value="<?php if($temp['sortFlag']=="180000"){echo $main_message['listingTitle']; }else{echo $temp['displayname']; } ?>" id="displayname<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="sortFlag<?php echo $mainAnsId; ?>" value="<?php echo $temp['sortFlag'];?>" id="sortFlag<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="threadid<?php echo $mainAnsId; ?>" value="<?php echo $threadId; ?>" id="threadid<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="dotCount<?php echo $mainAnsId; ?>" value="<?php echo $dotCount; ?>" id="dotCount<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="fromOthers<?php echo $mainAnsId; ?>" value="<?php echo $fromOthers; ?>" id="fromOthers<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="mainAnsId<?php echo $mainAnsId; ?>" value="<?php echo $mainAnsId; ?>" id="mainAnsId<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="displaynameId<?php echo $mainAnsId; ?>" value="<?php echo $temp['userId']; ?>" id="displaynameId<?php echo $mainAnsId; ?>" />
			    <input type="hidden" name="actionPerformed<?php echo $mainAnsId; ?>" id="actionPerformed<?php echo $mainAnsId; ?>" value="addComment" />
			    <input type="hidden" name="functionToCall<?php echo $mainAnsId; ?>" id="functionToCall<?php echo $mainAnsId; ?>" value="<?php echo $functionToCall; ?>" />
			    <input type="hidden" name="userProfileImage<?php echo $mainAnsId; ?>" id="userProfileImage" value="<?php echo ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';?>" />
			    <input type="hidden" name="mentionedUsers<?php echo $mainAnsId; ?>" value="" id="mentionedUsers<?php echo $mainAnsId; ?>"/>
	      		<input type="hidden" name="tracking_keyid" id="tracking_keyid<?php echo $mainAnsId; ?>" value="<?=$ansctrackingPageKeyId?>">
			</div>
			</form>
		  </div>
		</div>
		<?php if(count($message)==1){ ?>
		  </div>	</div>	</div>	</div>
		  </div>
		<?php } ?>
		

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
	</div>	</div>	</div>
	</div>
<?php } ?>

<!-- Start Know a Better Answer Block at the end of each Answer -->
<?php 
if((($commentCountDisplayed+1+$abusedComment)==count($message))&&((($dotCount==1) && ($temp['status'] == 'live'))||($dotCount>1))){
		if(($userId != $main_message['userId']) && ($isCmsUser==0) && ($closeDiscussion==0) && ($alreadyAnswer == 0)){ ?>
		<div style="line-height:10px;clear:both">&nbsp;</div>
		<div style="padding-bottom:10px;">
		    <span style="background-position:-301px -72px;"><b>Know a better answer? <a href="javascript:void(0);" onClick="<?php if(checkIfCourseTabRequired($subCatID)===TRUE) { ?>showAnswerFormNewWithCPGSHeader<?php } else { ?>showAnswerFormNew<?php }?>('<?php if($alreadyAnswer==0){echo $main_message['msgId']; }else {echo 0;}?>',<?=$anstrackingPageKeyId?>);" <!--onClick="setAnswerFocus('<?php //echo $main_message['msgId'];?>');" --> Click here to post.</a></b></span>
		</div>		
		<?php } else { ?>
		  <div style="line-height:10px;clear:both">&nbsp;</div>
		<?php }
} ?>
<!-- End Know a Better Answer Block at the end of each Answer -->


<?php endforeach; // This is end for nested foreach ?>

<div class="dottedLineMsg" style="clear:both">&nbsp;</div>

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
