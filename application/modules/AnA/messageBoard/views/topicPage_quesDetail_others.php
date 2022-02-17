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
	$userImageURL = ($userImageURL != '')?$userImageURL:'/public/images/photoNotAvailable.gif';
	$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
	if($fromOthersTopic == 'discussion' || $fromOthersTopic == 'eventAnA' ||$fromOthersTopic == 'review' ||$fromOthersTopic == 'announcement')
	  $levelString = 'levelP';
	else
	  $levelString = 'level';
?>
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />
<!--Start_MainAnswer-->
<div id="oldestFirstDiv"></div>
<div id="latestFirstDiv"></div>
<div id="highestRatedDiv">
<?php
$questionAskedBy = $main_message['userId'];
$topic_messages = $topic_messages[0];
$mainAnsId = 0;
//var_dump($topic_messages);
if(isset($topic_messages) && is_array($topic_messages)): 
	foreach($topic_messages as $message):
		$noOfCommentsInThread = 0;
		$flagForReplies = 0;
		$commentCountDisplayed = 0;
		$abusedComment = 0;
		if($message['parentId']==$message['threadId']){
		  $mainAnsId = $message['msgId'];
		  continue;
		}
		$commentMessage = array();
		$commentMessage[0] = $message;
		foreach($commentMessage as $key => $temp): 
			$commentUserId = $temp['userId'];
			$msgId = $temp['msgId'];
			$childCount = $temp['childCount'];
			$noOfCommentsInThread++;
			$ansDivClass="";	
			$subContainerClass="";
			$subComment = false;
			$marginLeftForComment = 'style="margin-left:0%"';
			$userAnswerForQuestionDetail= '';
			$temp['userImage'] = ($temp['userImage']=='')?'/public/images/photoNotAvailable.gif':$temp['userImage'];
			$parentDisplayName = isset($message[$noOfCommentsInThread-2]['displayname'])?$message[$noOfCommentsInThread-2]['displayname']:'';
			$parentUserId = isset($message[$noOfCommentsInThread-2]['userId'])?$message[$noOfCommentsInThread-2]['userId']:0;
			$parentSortFlag = '0';
			$subComment = true;
			$subContainerClass="subContainer";
			$ansDivClass='subContainerAnswer';
			$marginLeftForComment = 'padding-left:10%';
			$digUpClass = 'digUpMsg_0';
			if($temp['digUp'] > 0){
				$digUpClass = 'digUpMsg_Green';
			}
			$digDownClass = 'digDownMsg_0';
			if($temp['digDown']	> 0){
				$digDownClass = 'digDownMsg_Red';
			}
			$styleImage = ($temp['parentDisplayName'] != '')?'ana_ico2.gif':'ana_ico1.gif';
?>
<div id="tuple_<?=$msgId?>">
<div style="display:none;" id="abuseFormText">
</div>


<?php $this->load->library('AnAConfig');?>

<!-- Block to start the Replies container (when it is the first comment to an answer )-->
<?php
if((($temp['status'] == 'live') && ($temp['displayname'] != ''))||(($temp['status'] == 'abused')&& ($isCmsUser == 1) && ($temp['displayname'] != '')) ||(($temp['status'] == 'abused') && ($temp['displayname'] != '')) || (in_array($userId,AnAConfig::$userIds) && $temp['status'] == 'deleted')  ): //Start of main if statement.  
 ?>
<div class="aqAns" style="display:block" id="completeMsgContent<?php echo $msgId; ?>">
  	<div class="lineSpace_10">&nbsp;</div>
	<div class="<?php echo $ansDivClass; ?> <?php echo 'wdh100'; ?>">

		<?php if(($temp['status'] == 'abused') && ($isCmsUser == 0)){ ?>
			<div class="Fnt11" style="padding-top:5px;padding-bottom:5px;">
				This entity has been removed on account of abuse reports.
			</div>

		<?php } else { ?>

		<div class="tar fcdGya Fnt11">Posted: <?php echo date('m-j-Y, h:i A',strtotime($temp['creationDate'])); ?></div>
		<div class="lineSpace_5">&nbsp;</div>

		<!-- Block Start to display the User image -->
		<div class="imgBx">
		  <?php if($userId == $commentUserId){ ?>
			<img id="<?php echo 'userProfileImageForAnswer'; echo $msgId.rand(0, 10000);?>" src="<?php echo getSmallImage($temp['userImage']);?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$temp['displayname']; ?>');"/>
		  <?php }else{ ?>
			<img src="<?php echo getSmallImage($temp['userImage']);?>" style="cursor:pointer;" onClick="window.location=('<?php echo $userProfile.$temp['displayname']; ?>');"/>
		  <?php } ?>
		<?php if($showCurrentStudentStatus && $commentUserId==$ownerUserId){ ?><div class="current-student-patch" style="clear: both;float: left;margin-top: 5px;">Current Student</div><?php } ?>
		</div>
		<!-- Block End to display the User image -->

		<div class="cntBx">
			<div class="wdh100 float_L">
                                <div class="lineSpace_16" style="background: url('/public/images/<?php echo $styleImage;?>') no-repeat scroll left 2px transparent; padding-left:25px;">
				      <!-- Block Start for User name, posted date, user level -->
				      <div style="line-height:20px;margin-right:5%;">
					    <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['userId']; ?>');" ><a href="<?php echo $userProfile.$temp['displayname']; ?>">
							    <?php echo $temp['firstname'].' '.$temp['lastname'];?></a>,  
					    </span><?php echo $levelVCard[$commentUserId]['level'];?>
					    <?php if(($userId == $commentUserId)){?><span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"> <img src="/public/images/fU.png" /></span>&nbsp; <?php } ?>
					    <!--<?php if($levelVCard[$commentUserId]['level'] != NULL){ ?> <span class='forA'><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php echo getTheRatingStar($levelVCard[$commentUserId][$levelString],'detailPageComment'); ?></a></span><?php } ?>-->
					    <?php if ($temp['parentDisplayName'] != ''){ ?>
					      &nbsp;<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['parentDisplayId']; ?>');" ><a href="<?php echo $userProfile.$temp['parentDisplayName'];?>">@<?php echo $temp['parentFirstName'].' '.$temp['parentLastName'];?></a></span>
					    <?php } ?>
					    <!-- Block Start for Answer/Comment Text -->
					    <div style="padding-bottom:10px;line-height:18px;" id="msgTxtContent<?php echo $msgId; ?>" class="lineSpace_18">
					      <?php 
						      $temp['msgTxt'] = html_entity_decode(html_entity_decode($temp['msgTxt'],ENT_NOQUOTES,'UTF-8'));
						      echo formatQNAforQuestionDetailPage($temp['msgTxt'],300);
					      ?>
					    </div>

					    <!-- Block End for Answer/Comment -->

				      </div>
				      <!-- Block End for User name, posted date, user level -->

				      <!-- Block Start for In Reply to section -->
				      <?php if ($temp['parentDisplayName'] != ''){ ?>
                                          <div class="ana_replyTb">
					      <span class="float_L Fnt11 mr10" >In reply to </span>
					      <a class="float_L Fnt11 ana_uTbn plusSign" id="up_<?php echo $msgId; ?>" style="background-position:5px 0;text-decoration:none;color:#86868e;" onClick="showParentComments('<?php echo $msgId?>');" href="javascript:void(0);"  id="parentReplyButtonPlus<?php echo $msgId?>"><?php echo $temp['parentFirstName'].' '.$temp['parentLastName'];?>'s comment</a>
					      <a class="float_L Fnt11 ana_uTb closedocument" id="down_<?php echo $msgId; ?> style="background-position:5px 0;text-decoration:none;color:#86868e;display:none;" onClick="showParentComments('<?php echo $msgId?>');" href="javascript:void(0);" id="parentReplyButtonMinus<?php echo $msgId?>"><?php echo $temp['parentFirstName'].' '.$temp['parentLastName'];?>'s comment</a>
					      &nbsp;<span id="parentReplyWait<?php echo $msgId?>"></span>
					  </div>
					  <div class="Fnt11 ana_uAnsBx" style="display:none;overflow:auto;" id="parentReply<?php echo $msgId?>"></div>
				      <?php } ?>
				      <!-- Block End for In Reply to section -->

				      <!-- Block Start for Bottom navigation links like Digup, dig down, report abuse -->
				      <div class="wdh100" style="margin-top:5px;">

						      <!-- Block Start for Dig up and Dig down -->
						      <?php if(($fromOthersTopic == 'review' && $temp['userId'] != $userId) || ($fromOthersTopic == 'discussion' && $temp['parentDisplayName'] =='')): ?>
						      
						      <div class="float_L">
								      <table cellspacing='0' cellpadding='0' border='0'>
								      <tr>
									<td colspan='2'>
									      <span >
									
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
										<a href="javascript:void(0);" id='up_<?php echo $msgId; ?>' onMouseOver = "showLikeDislike(0,'<?php echo $msgId; ?>','comment');" onMouseOut = "hideLikeDislike(0,'<?php echo $msgId; ?>');" onClick="<?php if($fromOthersTopic == 'discussion'){?> updateDig(this,'<?php echo $msgId; ?>',1,'comment','','','','discussion','<?php echo $tupdtrackingPageKeyId;?>'); <?php }else{?> updateDig(this,'<?php echo $msgId; ?>',1,'comment','','','','','<?php echo $tupdctrackingPageKeyId;?>');  <?php }?> trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?php echo $userHasVotedUp; ?>" style="color:#000;text-decoration:none"><?php echo $temp['digUp']; ?></a>
										<a href="javascript:void(0);" id='down_<?php echo $msgId; ?>' onMouseOver = "showLikeDislike(1,'<?php echo $msgId; ?>','comment');" onMouseOut = "hideLikeDislike(1,'<?php echo $msgId; ?>');" onClick="<?php if($fromOthersTopic == 'discussion'){?> updateDig(this,'<?php echo $msgId; ?>',0,'comment','','','','discussion','<?php echo $tdowndtrackingPageKeyId;?>'); <?php }else{?> updateDig(this,'<?php echo $msgId; ?>',0,'comment','','','','','<?php echo $tdowndctrackingPageKeyId;?>');  <?php }?> trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="<?php echo $userHasVotedDown; ?>" style="color:#000;text-decoration:none"><?php echo $temp['digDown']; ?></a>
									      </span>
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

						      <!-- Block Start for Reply/Delete link -->
						      <?php if($temp['userId']!=$userId && $checkForDiscussionStatus['result']!='accepted'){ ?>
						      <div class="float_L Fnt11" style="valign:top;">
							      <div>
								      <span id="replyLink<?php echo $msgId;?>" ><a class="ana_rArw" href="javascript:void(0);" onClick="$('commentDisplaySection<?php echo $msgId;?>').style.display='block';showAnswerCommentForm('<?php echo $msgId;?>',<?=$rtrackingPageKeyId?>); return false;">Reply</a></span>
							      </div>
						      </div>
						      <?php }else if($isCmsUser != 1 && $temp['digUp']==0 && $temp['digDown']==0 && $temp['userId']==$userId){ ?>
						      <div class="float_R Fnt11" style="valign:top;">
							      <div>
								      <span id="deleteLink<?php echo $msgId;?>" ><a href="javascript:void(0);" onClick="deleteCafeEntity('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>','Comment');return false;">Delete</a></span>
							      </div>
						      </div>
						      <?php } ?>
						      <!-- Block End for Reply/Delete link -->

						      <!-- Block Start for Report Abuse link -->
						      <?php if($temp['userId']!=$userId){ ?>
					
						      <div class="float_R Fnt11" style="valign:top;">
                                                      <?php  if(in_array($userId,AnAConfig::$userIds)){
								if($temp['status']!='abused' && $temp['status']!='deleted' ){
							 ?>
	                                                            <div class="float_L Fnt11"><a href="javascript:void(0);" onclick="hideComment('<?php echo $fromOthersTopic;?>','<?php echo $temp['msgId']; ?>');">Hide</a>&nbsp;<span style="color:#cacaca">|</span>&nbsp;</div>
							<?php }else{ ?>
                                                                    <div class="float_L Fnt11"><a href="javascript:void(0);" onclick="unhideComment('<?php echo $fromOthersTopic;?>','<?php echo $temp['msgId']; ?>');">Unhide</a>&nbsp;<span style="color:#cacaca">|</span>&nbsp;</div>
                                                    <?php }} ?>
							      <div class="float_R">
									      <?php if($temp['reportedAbuse']==0){ 
										if(!(($isCmsUser == 1)&&($temp['status']=='abused'))){ 
										if($temp['parentDisplayName'] != ''){ $entityTypeComment = $fromOthersTopic." Reply"; } else { $entityTypeComment = $fromOthersTopic." Comment";}
									      ?>
									      <?php
									      if($fromOthersTopic== 'discussion')
									      {
									      		if($entityTypeComment=='discussion Reply')
									      				$abuseTrackingpageKeyID=$radrtrackingPageKeyId;
									      		else if($entityTypeComment=='discussion Comment')
									      				$abuseTrackingpageKeyID=$radctrackingPageKeyId;
									      }
									      else if($fromOthersTopic== 'announcement')
									      {
									      		if($entityTypeComment=='announcement Reply')
									      				$abuseTrackingpageKeyID=$raartrackingPageKeyId;
									      		else if($entityTypeComment=='announcement Comment')
									      				$abuseTrackingpageKeyID=$raactrackingPageKeyId;
									      }

									      ?>
									      <span id="abuseLink<?php echo $msgId;?>" ><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $msgId; ?>','<?php echo $commentUserId;?>','<?php echo $temp['parentId'];?>','<?php echo $threadId; ?>','<?php echo $entityTypeComment; ?>','<?php echo $eventId; ?>','<?php echo $articleId; ?>',<?=$abuseTrackingpageKeyID?>);return false;">Report&nbsp;Abuse</a></span>
									      <?php }}else{ ?>
									      <span id="abuseLink<?php echo $msgId;?>" >Reported as inappropriate</span>
									      <?php } ?>
							      </div>
						      </div>
						      <?php } ?>
						      <!-- Block End for Report Abuse link -->

					      <div class="clear_B">&nbsp;</div>
				      </div> 
				      <!-- Block End for Bottom navigation links like Digup, dig down, report abuse -->
				      <?php if($childCount > 0): ?>
				      <div class="pl33"><img src="/public/images/upArw1.png"></div>
				      <div class="fbkBx" id="viewComments<?php echo $msgId;?>">
					<div class="subContainerAnswer">
					 <a href="javascript:void(0)" onclick="javascript:getReplyDetails('<?php echo $msgId;?>', '<?php echo $userId;?>','','<?php echo $radrtrackingPageKeyId;?>');return false;" class="fbxVw Fnt11">View <?=$childCount>1?'All':''?> <span id="replyAnswerCount<?php echo $msgId;?>"><?=$childCount?></span> <?=$childCount>1?'Replies':'Reply'?></a>
					</div>
				      </div>
				      <?php endif; ?>
				      
				      

				      <!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
				      <div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $msgId; ?>">&nbsp;</div>
				      <!-- Block End for Confirm message like for displaying confirmation messge for dig up -->

				      <!-- Block Start for displaying the Comment reply form -->
				      <div style="display:none;margin-top:0px;margin-bottom:5px;" id="commentDisplaySection<?php  echo $msgId; ?>">
				      <!--div class="pl33" style='padding-left:33px;'><img src="/public/images/upArw.png" /></div-->
				      <?php
					    $tempType = $fromOthersTopic;
					    $dataArray = array('showMention'=>true,'type'=> $fromOthersTopic,'userId'=>$userId,'userImageURL'=>$userImageURL,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 0,'detailPageUrl' =>'','functionToCall' => $functionToCall, 'fromOthers' => $tempType, 'msgId' => $msgId, 'mainAnsId' => $mainAnsId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'messageToShow'=>'Write a reply to '.$temp['displayname'].'...', 'commenterDisplayName'=>$temp['firstname'].' '.$temp['lastname'], 'commentParentId'=>$temp['msgId']);
					    $dataArray['dctrackingPageKeyId']=$dctrackingPageKeyId;
					    $inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
					    echo "<div>".$inlineFormHtml."</div>";
				      ?>

				      </div>
				      <!-- Block End for displaying the Comment reply form -->
				      <div id="repliesContainer<?php echo $msgId;?>"></div>
				</div>
			</div>
		</div>	

		<?php } ?>
		<div style="line-height:5px;clear:both">&nbsp;</div>

		<!-- Block Start for Delete option to the CMS user -->
		<!--<?php if($isCmsUser == 1): ?>
		  <?php if($temp['status']!='abused'){ ?>	
		      &nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteCommentFromCMS('<?php echo $msgId; ?>','<?php echo $threadId; ?>','<?php echo $temp['userId']; ?>');return false;"><span class="cssSprite_Icons" style="background-position:-301px -72px;padding-left:18px">&nbsp;</span>Delete</a>
		      <?php }else{ ?>
		      <span style="background-position:-301px -72px;padding-left:18px">Deleted</span>
		  <?php } ?>
		<?php endif; ?>	-->
		<!-- Block End for Delete option to the CMS user -->

		<!--Block Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $msgId;?>">
		</div>
		<!--Block End_AbuseForm-->
	</div>
</div>
<!--End_MainAnswer-->

<?php
	else: //Else for main if statement
?>

<!-- Start of answer/comment deleted place -->
<div class="subContainer" style="padding-bottom:10px;padding-top:10px;">
	<div class="subContainerAnswer">
		This entity has been removed on account of abuse reports.
	</div>
</div>
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


<?php endforeach; // This is end for nested foreach ?>

<div class="dottedLineMsg" style="clear:both">&nbsp;</div>
</div>

<?php
	endforeach; //This is end of main foreach.
endif;
?>
</div>