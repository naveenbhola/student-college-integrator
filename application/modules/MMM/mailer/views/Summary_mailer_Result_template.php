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

<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
</div>
<div class="mar_full_10p" style="margin-top:15px;">
	<?php
		$this->load->view('mailer/left-panel-top');
	?>
	
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:20px;"><strong>Mailer Successfully Saved</strong></div>
	<div style="font-size:15px;">
		<?php
		if($mailStatus == 'false') {
			echo '<b>' . $temp1_name . "</b> Mailer has been saved successfully and scheduled to send on " .$trans_start_date.".";
		} else {
			echo '<b>' . $temp1_name . "</b> Mailer has been saved successfully as Draft. You can schedule this mailer from Manage Mails Link";
		}
		?>
	</div>
	<?php
		$this->load->view('mailer/left-panel-bottom');
	?>
</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
