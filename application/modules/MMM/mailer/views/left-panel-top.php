<?php

if(empty($userGroupData)) {
	$userGroupData = $this->userGroupData;
}
if(empty($mailerTabs)) {
	$mailerTabs = $this->mailerTabs;
}
$tab_ids = explode(",", $userGroupData['tab_ids']);
?>
<div>
	<div class="float_L" style="width:200px; height:300px;">
		<div class="raised_green_h">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_green_h" style="font-variant-position:200px">
				<?php
				foreach($tab_ids as $tab_id) { 
					if(!empty($mailerTabs[$tab_id])) { ?>
					<div <?php if($mailerTabs[$tab_id]['tab_url'] == $thisUrl) { echo 'class="active"'; } ?> style="line-height:20px;padding-left:10px"><a href="<?php echo $mailerTabs[$tab_id]['tab_url'];?>" ><?php  echo $mailerTabs[$tab_id]['tab_name']; ?></a></div>
					<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<?php 
					}
				}
				?>
				<!-- <div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer" id="old_tmp" value="old_tmp" >Old MAIL Templates </a></div>
				<div style="line-height:20px;padding-left:10px"><a href="/mailer/Mailer/EditTemplate" id="new_tmp" value="new_tmp"  >New MAIL Template </a></div>
				<div style="line-height:20px;padding-left:10px"><a href="/mailer/Mailer/MisReportDisplay" >MAIL MIS Report </a></div>
				<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/SmsOldTemplate" id="old_tmp" value="old_tmp" >Old SMS Templates </a></div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/EditTemplateSms" id="old_tmp" value="old_tmp" >New SMS Template </a></div>
				<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/responseSms" id="response_sms_tmp" value="response_sms_tmp" >Response SMS Template </a></div>
				<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/encryptCsv" id="old_tmp" value="old_tmp" >Encrypt Csv </a></div>
				
				<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/UserSets" id="old_tmp" value="old_tmp" >User Sets</a></div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/Mailer/CompositeUserSets" id="old_tmp" value="old_tmp" >Composite User Set</a></div> -->
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
		
		<!-- <div class="raised_green_h" style="margin-top:20px;">
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_green_h" style="height:200px">
				<div style="line-height:10px;padding-left:10px">&nbsp;</div>
				<div style="line-height:20px;padding-left:10px" ><a href="/mailer/MarketingFormMailer/listForms" id="old_tmp" value="old_tmp" >Form Mailers</a></div>
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div> -->
	</div>
	<div style="margin-left:10px;float:left;width:77%;">
