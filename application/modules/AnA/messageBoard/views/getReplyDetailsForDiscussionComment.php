<?php
//_p($childDetails);
foreach($childDetails as $reply)
{
?>
<div class="fbkBx" id="completeMsgContent<?=$reply['msgId']?>">
	<div class="subContainerAnswer ">	
	<!-- Block Start to display the User image -->
	<div class="imgBx">
		<img src="<?=$reply['picUrl']!=''?getTinyImage($reply['picUrl']):'/public/images/photoNotAvailable_t.gif'?>" style="cursor:pointer;" onclick="window.location=('<?php echo SHIKSHA_HOME?>/getUserProfile/<?=$reply['displayName']?>');">
	</div>
	<!-- Block End to display the User image -->
	<div class="cntBx">
		<div class="wdh100 float_L">
			<!-- Block Start for User name, posted date, user level -->
			<div style="line-height:20px;">
				<span onmouseover="showUserCommonOverlayForCard(this,'<?=$reply['userId']?>');"><strong><a href="<?php echo SHIKSHA_HOME?>/getUserProfile/<?=$reply['displayName']?>" class="Fnt11"><?=$reply['firstname'].' '.$reply['lastname']?></a></strong></span>
				<span style="padding-bottom:10px;line-height:18px;" id="msgTxtContent3162815" class=" lineSpace_18 Fnt11"><?=$reply['msgTxt']?></span>
			</div>
			<!-- Block End for User name, posted date, user level -->
			<!-- Block Start for Answer/Comment Text -->
			<div class="float_L fcdGya Fnt11"><?php echo date('m-j-Y, h:i A',strtotime($reply['creationDate'])); ?></div>
				<div class="float_R">
					<span id="abuseLink<?=$reply['msgId']?>" class="Fnt11"><a href="javascript:void(0);" onclick="report_abuse_overlay('<?=$reply['msgId']?>','<?=$reply['userId']?>','<?=$reply['parentId']?>','<?=$reply['threadId']?>','discussion Reply','0','0','<?php echo $trackingPageKeyId;?>');return false;">Report&nbsp;Abuse</a></span>
				</div>
			<div class="clear_B">&nbsp;</div>				
			<!-- Block End for Answer/Comment -->
			<!-- Block Start for Confirm message like for displaying confirmation messge for dig up -->
			<div class="showMessages" style="display:none;margin-top:5px;margin-bottom:5px;" id="confirmMsg<?=$reply['msgId']?>">&nbsp;</div>
			<!-- Block End for Confirm message like for displaying confirmation messge for dig up -->
		</div>
	</div>	
	<div style="line-height:1px;clear:both">&nbsp;</div>
		<!--Block Start_AbuseForm-->
		<div style="display:none;" class="formResponseBrder" id="abuseForm<?=$reply['msgId']?>">
		</div>
		<!--Block End_AbuseForm-->
	</div>
</div>
<?php
}
?>
