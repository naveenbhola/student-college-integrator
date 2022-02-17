<?php 
	$userProfile = site_url('getUserProfile').'/';
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';
	$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
	$functionToCall = isset($functionToCall)?$functionToCall:'-1';
	$dataArray = array('showMention'=>true,'userId'=>$userId,'userImageURL'=>$userImageURLDisplay,'userGroup' =>$userGroup,'threadId' =>$threadId,'ansCount' => 0,'detailPageUrl' => '','functionToCall' => $functionToCall, 'fromOthers' => 'user', 'msgId' => $answerId, 'mainAnsId' => $answerId, 'dotCount'=>2 , 'displayname'=> $displayName, 'sortFlag'=>2, 'wall'=>1);
	$inlineFormHtml = $this->load->view('messageBoard/InlineForm_Homepage_Comment',$dataArray,true);
	$commentMsg = "View "; 
	if(count($ResultOfDetails) == 1) 
	  $commentMsg.= "<span id='replyAnswerCount".$answerId."'>".count($ResultOfDetails)."</span> comment";
	else 
	  $commentMsg.= "all <span id='replyAnswerCount".$answerId."'>".count($ResultOfDetails)."</span> comments";
?>
<!-- Block start for View All and Comments Link -->
<div style="margin-top:5px;">
	<div class="pl33"><img src="/public/images/upArw.png" /></div>
	<?php if(count($ResultOfDetails) >= 1){ ?>
	<div class="fbkBx" id="viewAllDiv<?php echo $answerId;?>" style="width:507px;display:<?php if(($focusForm=='false'))echo 'none';else echo 'block';?>;">
		<div>
			<div class="float_L wdh100">
					<div class="Fnt11">
					  <a href="javascript:void(0);" class="fbxVw Fnt11" onClick="showMyAnswerComments('<?php echo $answerId;?>')"><?php echo $commentMsg; ?></a>
					</div>
			</div>
			<s>&nbsp;</s>
		</div>
	</div>
	<?php } ?>
	<div id="commentDiv<?php echo $answerId;?>"  style="display:<?php if($focusForm=='true') echo 'none';else echo 'block';?>;">
	<?php
		foreach($ResultOfDetails as $ResultOfDetail){ 
		  $commentMsgId = $ResultOfDetail['msgId'];
		  $commentUserId = $ResultOfDetail['userId'];
		  $commentUserDisplayName = $ResultOfDetail['displayname'];
		  if(!isset($ResultOfDetail['lastname']) || $ResultOfDetail['lastname'] == '')
		  	$ResultOfDetails['lastname'] = '';
 		  $commentUserDisplayFullName = $ResultOfDetail['firstname'].' '.$ResultOfDetail['lastname'];
		  $commentText = $ResultOfDetail['msgTxt'];
		  $commentText = html_entity_decode(html_entity_decode($commentText,ENT_NOQUOTES,'UTF-8'));
		  $commentText = formatQNAforQuestionDetailPage($commentText,300);
		  $url = "/getTopicDetail/".$ResultOfDetail['threadId'];
	?>
		<div class="fbkBx" style="width:507px;">
			<div>
				<div class="float_L wdh100">
						<div class="imgBx">
							<img src="<?php echo getTinyImage($ResultOfDetail['userImage']); ?>" style="cursor:pointer;"/>
						</div>
						<div class="cntBx">
							<div class="wdh100 float_L">
								<div class="Fnt11" style="padding-top:2px;">
								  <span>
									  <?php if($commentUserId==$userId){ ?>
										<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $commentUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$commentUserDisplayName; ?>"><?php echo $commentUserDisplayFullName;?></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>
									  <?php }else{ ?>
										<span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $commentUserId; ?>');" ><a href="<?php echo $userProfile.$commentUserDisplayName; ?>"><?php echo $commentUserDisplayFullName; ?></a></span>&nbsp;
									  <?php } ?>
									  <?php echo $commentText;?>
								  </span>
								</div>
								<div class="float_L Fnt11 fcdGya" style="padding-top:2px;">
								  <span><?php echo makeRelativeTime($ResultOfDetail['creationDate']);?></span>
								</div>
								<div class="float_R Fnt11" style="valign:top;">
									<div>
											<?php if($userId!=$commentUserId){ if($ResultOfDetail['reportedAbuse']==0){ 
											  if(!(($userGroup == 'cms')&&($ResultOfDetail['status']=='abused'))){ 
											?>
											<span id="abuseLink<?php echo $commentMsgId;?>" ><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $commentMsgId; ?>','<?php echo $commentUserId;?>','<?php echo $answerId;?>','<?php echo $threadId; ?>','Comment','0','0','<?=$raansctrackingPageKeyId?>');return false;">Report&nbsp;Abuse</a></span>
											<?php }}else{ ?>
											<span id="abuseLink<?php echo $commentMsgId;?>" >Reported as inappropriate</span>
											<?php }} ?>
									</div>
								</div>
							</div>
						</div>
				</div>
				<!--Start_AbuseForm-->
				<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $commentMsgId;?>"></div>
				<!--End_AbuseForm-->
				<s>&nbsp;</s>
			</div>
		</div>
	<?php } ?>
	</div>
	<div id="replyPlace<?php echo $answerId;  ?>"></div>
</div>
<!-- Block End for View All and Comments Link -->
<?php
if( $inlineFormHtml!='')
	echo $inlineFormHtml;
?>

