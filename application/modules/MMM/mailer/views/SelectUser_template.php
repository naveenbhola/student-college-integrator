<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles','searchCriteria'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','prototype','ajax-api','searchCriteria'),
	'jsFooter'	=> array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=> 'Mass Mailer',
	'taburl'	=> site_url('mailer/Mailer'),
	'metaKeywords'	=> ''
);
$this->load->view('enterprise/headerCMS', $headerComponents);
//$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
$this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
	var calMain = new CalendarPopup("calendardiv");
</SCRIPT>

<script type="text/javascript">
	window.onload = function() {
		$('use_userset').checked = 'checked';
		$('select_userset_tmp').style['display'] = '';
		$('upload_csv_select_user_tmp').style['display'] = 'none';
		$('clk_one_error').innerHTML = '';
	}
</script>

<div id="dataLoaderPanel" style="position:absolute;display:none">
	<img src="/public/images/loader.gif"/>
</div>
<div class="mar_full_10p" style="margin-top:15px;">

	<?php $this->load->view('mailer/left-panel-top'); ?>
	
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px;"><strong>Mailer User List</strong></div>
	
	<div class="r2_2 errorMsg" id= "clk_one_error">
	<?php
		if ($template_validation_error == 'TRUE_TEMPLATE') {
			echo "Your CSV file is not matched with selected Template variables, choose a correct CSV or the correct CSV.";
		} else if ($email_validation_error =='TRUE_EMAIL') {
			echo "Your CSV file contains invalid email id, choose a correct CSV or the correct CSV.";
		} else if ($empty_array_error == 'TRUE_SET') {
			echo "Your Search Criteria returned zero result, choose different userset combinations.";
		} else if ($email_size_error == 'EMAIL_COUNT_EXCEEDS') {
			echo "Upload failed. Please upload a maximum of 25,000 email ids only.";
		} else if ($error == 'TRUE') {
			echo "Some Error occured try again.";
		}
	?>
	</div>
	<div class="clear_L"></div>
	<div id="add_new_user_set"></div>
	<form id="formForTestmail_Template" enctype="multipart/form-data" action="/mailer/Mailer/handle_userlist/" method="POST" >
		<table style="font-size:13px; margin-bottom: 10px;">
			<tr>
				<td width="200"><input type="radio" id="use_userset" name="user_list_template" value="use_userset" onclick="view_user_list_template(this)">Select Userset </td>
				<?php 
					if(!(in_array($temp_id, $newsletterTemplateIdsArray))) { 
				?>
				<td width="550"><input type="radio" id="upload_csv" name="user_list_template" value="upload_csv" onclick="view_user_list_template(this)"> Upload CSV </td>
				<?php 
					}
				?>
			</tr>
		</table>
		<!-- LISTING Start -->
		<div id="select_userset_tmp" style="display:none;" >
			<div class="row">
				<?php $this->load->view('mailer/SelectUserset'); ?>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>
		<!-- LISTING End -->
		<!-- UPLOAD CSV Form Start -->
		<div id="upload_csv_select_user_tmp" style="display:none;">
			<div class="lineSpace_10">&nbsp;</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<fieldset>
					<div style="display:inline;float:left;width:100%">
						<div class="r1_1 bld">Upload CSV:&nbsp;&nbsp;</div>
						<div class="r2_2">
							<input type="file" name="c_csv" id="c_csv" size="5" style="" />
						</div>
						<div class="clear_L"></div>
						<div class="row errorPlace" style="margin-top:2px;">
							<div class="r1_1">&nbsp;</div>
							<div class="clear_L"></div>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="r1_1 bld">&nbsp;</div>
			<div class="r2_2">
				<button class="btn-submit19" type="button" onclick="$('formForTestmail_Template').action='/index.php/mailer/Mailer/handle_userlist/';validate_user_listing();" value="" style="width:100px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Next</p></div>
				</button>

				<button class="btn-submit19" type="button" onclick="$('formForTestmail_Template').action='/index.php/mailer/Mailer/handle_userlist/';validate_user_download_listing();" value="" style="width:160px">
					<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Download Invalid Email</p></div>
				</button>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
		</div>
		<!-- UPLOAD CSV Form End -->
		<input type="hidden" name="edit_form_mode" value="<?php echo $edit_form_mode; ?>" />
		<input type="hidden" name="sums_data" value="<?php echo $sumsData; ?>" />
		<input type="hidden" name="temp_id" value="<?php echo $temp_id; ?>" />
		<input type="hidden" name="templateType"  value="<?php echo $templateType; ?>">
		<input type="hidden" id="download_check" name="download_check" value="0" />
		<input type="hidden" id="user_count" name="user_count" value="0" />
	</form>
	<?php $this->load->view('mailer/left-panel-bottom'); ?>
</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
$j = jQuery.noConflict();
</script>
<?php //$this->load->view('enterprise/footer'); ?>
