<?php 

	$userProfile = site_url('getUserProfile').'/';
	$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
	$userGroup = isset($validateuser[0]['usergroup'])?$validateuser[0]['usergroup']:'normal';
	$entityTypeShown = isset($entityTypeShown)?$entityTypeShown:'';
?>
<div style="display:none;" id="abuseFormText">
</div>
<?php foreach($userAnswer as $temp){ 
	if(is_array($temp['question']) && is_array($temp['answer'])){ 
	$viewText = ($temp['question']['viewCount'] <= 1)?$temp['question']['viewCount'].' view':$temp['question']['viewCount'].' views';
	$noOfAnswerText = ($temp['question']['msgCount'] <= 1)?$temp['question']['msgCount'].' answer':$temp['question']['msgCount'].' answers';
?>
<div class="lineSpace_10">&nbsp;</div>
<div class="raised_lgraynoBG">
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
		<div class="boxcontent_lgraynoBG">
			<div style="padding:0 10px">
				<div>
					<a href="<?php echo $temp['question']['url']; ?>" class="fontSize_12p"><?php echo isset($temp['question']['msgTxt'])?insertWbr($temp['question']['msgTxt'],30):''; ?></a>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div style="line-height:22px" class="grayFont">

					Asked by <span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $temp['question']['userid'];?>');" ><a href="<?php echo $userProfile.$temp['question']['questionOwner']; ?>"><?php echo $temp['question']['questionOwner']; ?></a></span>
					<a href="/shikshaHelp/ShikshaHelp/upsInfo" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none"><?php echo $temp['question']['level']; ?></a>
					 <?php echo $temp['question']['creationDate']; ?>, <?php echo $viewText; ?>, <?php echo $noOfAnswerText; ?>
				</div>
				<div class="lineSpace_10">&nbsp;</div>						
				<div class="OrgangeFont fontSize_13p bld"><?php echo $userDisplayName; ?>'s Answer</div>
				<div class="lineSpace_2">&nbsp;</div>
				<div class="fontSize_12p">
						<?php echo insertWbr($temp['answer']['msgTxt'],30); ?>
				</div>									
				<div class="lineSpace_10">&nbsp;</div>
				<div class="row">			
				<div style="width:49%" class="float_L">
				<?php 
					$questionUrl = $temp['question']['url'].'/askHome#gRep';
					if($temp['question']['status'] != 'closed') { 
						if($userId != 0){
				?>
							<a href="<?php echo $questionUrl; ?>" class="fontSize_12p bld">Reply</a>
				<?php 
						}else{
				?>	
							<a href="#" onClick="javascript:showuserLoginOverLay(this,'ASK_USERQNA_RIGHTPANEL_REPLY','redirect','<?php echo base64_encode($questionUrl); ?>');return false;" class="fontSize_12p bld">Reply</a>
				<?php 
						}
					}else{ 
				?>
				<span class="fontSize_12p"><strong>Closed Question</strong></span>
				<?php } ?>		
				</div>
				<div style="width:50%" class="float_L txt_align_r">
					  <?php if($userId!=$viewedUserId){ if($temp['answer']['reportedAbuse']==0){ 
						if(!(($userGroup == 'cms')&&($temp['answer']['status']=='abused'))){ 
					  ?>
					  <span id="abuseLink<?php echo $temp['answer']['msgId'];?>" ><a href="javascript:void(0);" onClick="report_abuse_overlay('<?php echo $temp['answer']['msgId']; ?>','<?php echo $viewedUserId;?>','<?php echo $temp['question']['msgId']; ?>','<?php echo $temp['question']['msgId']; ?>','<?php echo "Answer"; ?>',0,0);return false;">Report&nbsp;Abuse</a></span>
					  <?php }}else{ ?>
					  <span id="abuseLink<?php echo $temp['answer']['msgId'];?>" >Reported as inappropriate</span>
					  <?php }} ?>
				</div>
				<div class="clear_L"></div>
				</div>
			<!--Start_AbuseForm-->
			<div style="display:none;" class="formResponseBrder" id="abuseForm<?php echo $temp['answer']['msgId'];?>">
			</div>
			<!--End_AbuseForm-->
			</div>

			<div id="confirmMsg<?php  echo $temp['answer']['msgId']; ?>" class="errorMsg mar_left_10p"></div>
		</div> 
	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<?php  } }  ?>
