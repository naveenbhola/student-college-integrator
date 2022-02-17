<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
	'jsFooter'	=> array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=> 'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
//$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
$this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
	var calMain = new CalendarPopup("calendardiv");
</SCRIPT>

<div id="dataLoaderPanel" style="position: absolute; display: none">
	<img src="/public/images/loader.gif" />
</div>
<div class="mar_full_10p" style="margin-top:15px;">
	<?php $this->load->view('mailer/left-panel-top'); ?>
	<?php $sumsDataArr = json_decode(base64_decode($sumsData),true); ?>
	
	<div class="OrgangeFont fontSize_18p bld">
		<strong>Schedule Mailer</strong>
	</div>
	
	<div style='background: #fff; border-bottom:1px dashed #ddd; padding:10px 0 15px 0; color:#222;'>
	<div class="txt_align_r fontSize_14p bld" style="width: 250px; float:left;"><strong>List Details: &nbsp;</strong></div>
	<div class="fontSize_14p bld" style="float:left;">
		<strong><?php echo  $totalUsersInCriteria. " Users"; ?></strong>
	</div>
	<div class="clear_L"></div>
	<?php if($sumsDataArr['BaseProdRemainingQuantity']) { ?>
		<div class="lineSpace_10">&nbsp;</div>
		<div class="txt_align_r fontSize_14p bld" style="width: 250px; float:left;"><strong>Number Left in Subscription: &nbsp;</strong></div>
		<div class="fontSize_14p bld" style="float:left;">
			<strong><?php echo $sumsDataArr['BaseProdRemainingQuantity'];?></strong>
		</div>
	<?php } ?>
	<div class="clear_L"></div>
	</div>
	
	<div class="lineSpace_10">&nbsp;</div>
	<div class="clear_L"></div>
	<div id="radio_unselect_error1" style="color: red;">
	<?php
		if ($error == 'TRUE') {
			echo "No. Subscription should be more than no. of users.";
		}
		if(!empty($mailerDetails)) {
			$action = "/mailer/Mailer/updateMailerDetails";
		} else {
			$action = "/mailer/Mailer/Summary_List_template";
		}
	?>
	</div>
	<div class="lineSpace_10">&nbsp;</div>
	<form id="formForSummaryList_Template" action="<?php echo $action;?>" method="POST" >
		<input type="hidden" name="edit_form_mode" value="<?php echo $edit_form_mode; ?>" />
		<input type="hidden" name="sumsData" value="<?php echo $sumsData; ?>" />
		<input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
		<input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
		<input type="hidden" name="criteria" value="<?php echo $criteria; ?>" />
		<input type="hidden" id="mailStatus" name="mailStatus" value="" />
		<input type="hidden" name="mailerId" id="mailerId" value="<?=$mailerDetails['id']?>" />
		<input type="hidden" name="totalUsersInCriteria" id="totalUsersInCriteria" value="<?=$totalUsersInCriteria?>" />
		<input type="hidden" name="numUsers" id="numUsers" value="<?=$numUsers?>" />

		<?php
		$allmails = 'checked="checked"';$limitmails = '';
		if($totalUsersInCriteria == $numUsers) {
			$allmails = 'checked="checked"';
		} else {
			$limitmails = 'checked="checked"';
		}
		?>
		<div class="txt_align_r float_L fontSize_12p" style="width: 250px;  font-size:13px; color:#333; padding-top:4px;">No. of Mails: &nbsp;</div>
		<div class="float_L" style="padding-top:4px;">
			<input type="radio" id="mails_limit_all" name="mails_limit" onclick="select_user_limit(this)" value="<?php echo $totalUsersInCriteria; ?>" <?=$allmails?> /> All &nbsp;&nbsp;
		</div>
		<div class="float_L" style="padding-top:4px;">
			<input type="radio" id="mails_limit_no" name="mails_limit" onclick="select_user_limit(this)" value="<?php echo $numUsers; ?>" <?=$limitmails?>/>
		</div>
		<div class="float_L">
			<input type="text" id="mails_limit_text" disabled="true" name="mails_limit_text" style="border:1px solid #ccc; padding:5px 2px; font-size: 13px;" align="absmiddle" placeholder="Specify mailer count" />
		</div>
		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L fontSize_12p" style="width: 250px;  font-size:13px; color:#333; padding-top:4px;">Send Date: &nbsp;</div>
			
			<div style="float:left; font-size:13px;">
				<?php
					$immediately = 'checked="checked"';$later ='';
					$scheduleDate = date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));
					if(!empty($mailerDetails)) {
						if($mailerDetails['mailerScheduleTime'] < time()) {
							$immediately = 'checked="checked"';
						}  else {
							$later = 'checked="checked"';
							$scheduleDate = date("Y-m-d", $mailerDetails['mailerScheduleTime']);
							$scheduleHour = (int)date("H", $mailerDetails['mailerScheduleTime']);
							$scheduleMinute = (int)date("i", $mailerDetails['mailerScheduleTime']);
						} 
					}
				?>
				<input type="radio" name="mailSchedule" <?=$immediately?> onclick="changeMailSchedule(this)" value="immediately" /> Immediately
				<div class="lineSpace_10">&nbsp;</div>
				<input type="radio" name="mailSchedule" <?=$later?> id="mailSchedule_later" onclick="changeMailSchedule(this)" value="later" /> Later on &nbsp;
				<input type="text"  disabled="true" readonly id="subs_start_date" name="trans_start_date" value="<?php echo $scheduleDate;?>" onclick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');" style="height: 18px; border:1px solid #ccc; padding:5px 2px; font-size: 13px; width:70px;" >
				<img src="/public/images/eventIcon.gif" style="cursor: pointer" align="absmiddle" id="sd" onClick="calMain.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
				&nbsp;
				<select name="mailScheule_hours" id="mailScheule_hours" disabled="true">
					<option value=''>Hour</option>
					<?php
					for($i=0;$i<10;$i++) {
						$hourchecked = '';
						if($i == $scheduleHour) {
							$hourchecked ='selected="selected"';
						}
						echo "<option value='0".$i."' ".$hourchecked.">0".$i."</option>";
					}
					for($i=10;$i<24;$i++) {
						$hourchecked = '';
						if($i == $scheduleHour) {
							$hourchecked ='selected="selected"';
						}
						echo "<option value='".$i."' ".$hourchecked.">".$i."</option>";
					}
					?>
				</select>
				
				<select name="mailScheule_minutes" id="mailScheule_minutes" disabled="true">
					<option value=''>Min</option>
					<?php
					for($i=0;$i<10;$i++) {
						$minutechecked = '';
						if($i == $scheduleMinute) {
							$minutechecked ='selected="selected"';
						}
						echo "<option value='0".$i."' ".$minutechecked.">0".$i."</option>";
					}
					for($i=10;$i<60;$i++) {
						$minutechecked = '';
						if($i == $scheduleMinute) {
							$minutechecked ='selected="selected"';
						}
						echo "<option value='".$i."' ".$minutechecked.">".$i."</option>";
					}
					?>
				</select>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top: 2px;">
				<div class="r1_1" style="width:240px;">&nbsp;</div>
				<div class="r2_2 errorMsg" id="mailSchedule_error"></div>
				<div class="clear_L"></div>
			</div>
		</div>
		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L fontSize_12p" style="width: 250px; font-size:13px; color:#333; padding-top:4px;">Mailer Name:&nbsp;&nbsp;</div>
			<div class="r2_2">
				<input required="true" profanity="true" validate="validateStr" type="text" name="temp1_name" id="temp1_name" size="30" maxlength="125" minlength="2" caption="Mailer Name" style="height: 18px; border:1px solid #ccc; padding:5px 2px; font-size: 13px; width:250px;" value="<?=$mailerDetails['mailerName']?>" />
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top: 2px;">
				<div class="r1_1" style="width:240px;">&nbsp;</div>
				<div class="r2_2 errorMsg" id="temp1_name_error"></div>
				<div class="clear_L"></div>
			</div>
		</div>
        <div class="clear_L"></div>
        <div class="lineSpace_10">&nbsp;</div>
        <div>
            <div class="txt_align_r float_L fontSize_12p" style="width: 250px; font-size:13px; color:#333; padding-top:4px;">Sender Name:&nbsp;&nbsp;</div>
            <div class="r2_2">
                <input required="true" profanity="true" validate="validateStr" type="text" name="sender_name" id="sender_name" size="30" maxlength="125" minlength="2" caption="Sender Name" style="height: 18px; border:1px solid #ccc; padding:5px 2px; font-size: 13px; width:250px;" value="<?=$mailerDetails['sendername']?>" />
            </div>
            <div class="clear_L"></div>
            <div class="row errorPlace" style="margin-top: 2px;">
                <div class="r1_1" style="width:240px;">&nbsp;</div>
                <div class="r2_2 errorMsg" id="sender_name_error"></div>
                <div class="clear_L"></div>
            </div>
        </div>

		<div class="clear_L"></div>
		<div class="lineSpace_10">&nbsp;</div>
		<div>
			<div class="txt_align_r float_L fontSize_12p" style="width:250px; font-size:13px; color:#333; padding-top:4px;">Sender Email id:&nbsp;&nbsp;</div>
			<div class="r2_2">
				<?php $this->load->view('mailer/senderEmailSelect'); ?>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top: 2px;">
				<div class="r1_1" style="width:240px;">&nbsp;</div>
				<div class="r2_2 errorMsg" id="userFeedbackEmail_error"></div>
				<div class="clear_L"></div>
			</div>
		</div>
		<div class="clear_L"></div>
		<div class="lineSpace_20">&nbsp;</div>
		
		<div style="background:#f0f0f0; text-align: center; padding:5px 0">
			

			<button class="btn-submit19" type="button" onclick="return formForSummaryList_Template('draft');" value="" style="width: 130px">
				<div class="btn-submit19">
					<p style="padding: 15px 8px 15px 5px; color: #FFFFFF; font-size: 10px" class="btn-submit20">Save Mailer as Draft</p>
				</div>
			</button>

			<button class="btn-submit19" type="button" onclick="return formForSummaryList_Template('false');" value="" style="width: 100px">
				<div class="btn-submit19">
					<p style="padding: 15px 8px 15px 5px; color: #FFFFFF; font-size: 10px" class="btn-submit20">Schedule Mailer</p>
				</div>
			</button>
		
			
		</div>
	</form>
	<?php $this->load->view('mailer/left-panel-bottom'); ?>
	<script>
		<?php if(!empty($later)) { ?>
			$('mailSchedule_later').click();
		<?php } ?>
		<?php if(!empty($limitmails)) { ?>
			$('mails_limit_no').click();
		<?php } ?>
		addOnBlurValidate(document.getElementById('formForSummaryList_Template'));
	</script>
</div>
<!--End_Center-->
<div style="line-height: 50px; clear: left;">&nbsp;</div>
