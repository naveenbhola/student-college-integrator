<?php 
	$userProfile = site_url('getUserProfile').'/';
	//Code Start to create VCard, User level, Category and Country arrays with UserId as Index
	$VCardArray = array();
	$userLevel = array();
    for($i=0;$i<count($levelVCard);$i++){
	  $userIdTemp = $levelVCard[$i]['userid'];
	  $VCardArray[$userIdTemp] = $levelVCard[$i]['vcardStatus'];
	  $userLevel[$userIdTemp] = $levelVCard[$i]['ownerLevel'];
	}
	foreach($ResultOfDetails as $ResultOfDetail){ 
	  $answerMsgId = $ResultOfDetail['msgId'];
	  $answerUserId = $ResultOfDetail['userId'];
	  $answerUserDisplayName = $ResultOfDetail['displayname'];
	  $answerUserDisplayFullName = $ResultOfDetail['firstname'].' '.$ResultOfDetail['lastname'];
	  $answerText = $ResultOfDetail['msgTxt'];
	  $answerVCardStatus = $VCardArray[$answerUserId];
	  $url = "/getTopicDetail/".$ResultOfDetail['threadId'];
	  $commentMsg = '';
	  if(isset($ResultOfDetail['commentCount'])){
		$commentMsg = ($ResultOfDetail['commentCount'] > 0)?'('.($ResultOfDetail['commentCount'].' people commented on this answer)'):'';
		if($ResultOfDetail['commentCount'] == 1) 
		  $commentMsg = '('.$ResultOfDetail['commentCount'].' person commented on this answer)';
	  }

?>
<div style="margin-top:10px;margin-bottom:8px;">
	<?php
		if($ResultOfDetail['bestAnsFlag']==1){
		$flagForSelectBestAnswerLink = false;
	?>
		<div class="float_L"><span class="bestStar">Best Answer</span></div>			
		<div style="line-height:5px;clear:both">&nbsp;</div>
	<?php 
		}else if($userId == $answerUserId){
	?>
		<div><span class="Fnt11 bld">Your Answer</span></div>
		<div style="line-height:5px;clear:both">&nbsp;</div>
	<?php } ?>

	<div class="ana_a fs11" >
		<?php if($answerUserId==$userId){ ?>
			  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayFullName;?></a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);"><img src="/public/images/fU.png" /></span>
		<?php }else{ ?>
			  <span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $answerUserId; ?>');}catch(e){}" style="width:30px;display:inline;"><a href="<?php echo $userProfile.$answerUserDisplayName; ?>"><?php echo $answerUserDisplayFullName;?></a></span>
		<?php } ?>
		<?php 
			  $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
			  $quesLength = strlen($answerText);
			  if($quesLength<=300){
				$answerText = formatQNAforQuestionDetailPage($answerText,300);
				echo "<span class='grayFont'>".$answerText."</span>";
				$this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$answerMsgId));				
			} 
			  else {
				  echo "<span class='grayFont' id='previewQues".$answerMsgId."'>".substr($answerText, 0, 297)."</span></a>";
				  echo "<span id='relatedQuesDiv".$answerMsgId."'>&nbsp;<FONT COLOR='#000000'>...</FONT> <a href='javascript:void(0);' id='relatedQuesHyperDiv".$answerMsgId."' onClick='showCompleteAnswerHomepage(".$answerMsgId.");'>more</a></span>";
				  $answerText = formatQNAforQuestionDetailPage($answerText,300);
				  echo "<span class='grayFont' id='completeRelatedQuesDiv".$answerMsgId."' style='display:none;'>".$answerText;
				  $this->load->view('messageBoard/showAnswerSuggestions',array('answerId'=>$answerMsgId));				
				  echo "</span>";
			  }
		?>
		<!-- Start Answer Date, Category and Country display section -->
		<div class="mtb5">
			<span class="Fnt11"><span class="grayFont"><?php  if($showDate == 'true')echo makeRelativeTime($ResultOfDetail['creationDate']); ?></span></span>
		</div>
		<!-- End Answer Date, Category and Country display section -->

		<!-- Block Start for Bottom navigation links like Digup, dig down, report abuse -->
		<div class="lineSpace_5">&nbsp;</div>
		<div class="wdh100">
				<!-- Block Start for Dig up and Dig down -->
				<div class="float_L" style="width:460px;">
						<table cellspacing='0' cellpadding='0' border='0'>
						<tr>
						  <td colspan='2'>
							<span >
							  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(0,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(0,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',1,'','','','','','<?=$tupanstrackingPageKeyId?>');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="aqIcn rUp Fnt11" style="color:#000;text-decoration:none"><?php echo $ResultOfDetail['digUp']; ?></a>
							  <a href="javascript:void(0);" onMouseOver = "showLikeDislike(1,'<?php echo $answerMsgId; ?>');" onMouseOut = "hideLikeDislike(1,'<?php echo $answerMsgId; ?>');" onClick="updateDig(this,'<?php echo $answerMsgId; ?>',0,'','','','','','<?=$tdownanstrackingPageKeyId?>');trackEventByGA('LinkClick','THUMB_RATING_CLICK');return false;" class="aqIcn rDn Fnt11" style="color:#000;text-decoration:none"><?php echo $ResultOfDetail['digDown']; ?></a>
							</span>
						  </td>
						  <td width="82%">
							  <span style="padding-left:10px;" class="Fnt11 quesAnsBullets"><a href="javascript:void(0);"  onClick="showCommentSection('<?php echo $answerMsgId; ?>',true,'<?php echo $ResultOfDetail['threadId']; ?>','<?=$ansctrackingPageKeyId?>');">Comment</a>&nbsp;</span>
							  <a href="javascript:void(0);" onClick="showCommentSection('<?php echo $answerMsgId; ?>',false,'<?php echo $ResultOfDetail['threadId']; ?>','<?=$ansctrackingPageKeyId?>','<?=$raansctrackingPageKeyId?>');" style="color:#707070;"><?php echo $commentMsg;?></a>						  
						  </td>
						</tr>
						<tr>
						  <td width='10%'>
							<div id="likeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div></td><td><div id="dislikeDiv<?php echo $answerMsgId; ?>" style="display:block;visibility:hidden;"></div>
						  </td>
						</tr>
						</table>
				</div>
				<!-- Block End for Dig up and Dig down -->
				<!-- Block Start for Report Abuse link -->
				<div class="float_R" style="valign:top;">
					<div>
							<?php if($userId!=$answerUserId){ if($ResultOfDetail['reportedAbuse']==0){ 
							  if(!(($isCmsUser == 1)&&($ResultOfDetail['status']=='abused'))){ 
							?>
							<span id="abuseLink<?php echo $answerMsgId;?>" class="Fnt11"><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $answerMsgId; ?>','<?php echo $answerUserId;?>','<?php echo $ResultOfDetail['threadId']; ?>','<?php echo $ResultOfDetail['threadId']; ?>','<?php echo "Answer"; ?>',0,0,'<?=$raanstrackingPageKeyId?>');return false;">Report&nbsp;Abuse</a></span>
							<?php }}else{ ?>
							<span id="abuseLink<?php echo $answerMsgId;?>" class="Fnt11">Reported as inappropriate</span>
							<?php }} ?>
					</div>
				</div>
				<!-- Block End for Report Abuse link -->

			<div class="clear_B">&nbsp;</div>
		</div> 
		<!-- Block End for Bottom navigation links like Digup, dig down, report abuse -->
		<!--Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $answerMsgId;?>"></div>
		<!--End_AbuseForm-->

		<!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
		<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?php  echo $answerMsgId; ?>">&nbsp;</div>
		<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->
	</div>
</div>
<!-- Block Start for displaying the Comment Section -->
<div style="display:none;margin-top:5px;margin-bottom:5px;padding-left:33px;" id="commentDisplaySection<?php  echo $answerMsgId; ?>">&nbsp;</div>
<!-- Block End for displaying the Comment Section -->
<?php } ?>
