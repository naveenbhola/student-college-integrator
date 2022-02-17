<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles'),
	//'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api'),
	//'jsFooter'         => array('scriptaculous','utils'),
	'jsFooter'         => array(),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>'',
	'noextra_js'=>true
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
<SCRIPT LANGUAGE="JavaScript">
    var calMain = new CalendarPopup("calendardiv");
</SCRIPT>
</head>
<body>
	<div id="dataLoaderPanel" style="position:absolute;display:none">
		<img src="/public/images/loader.gif"/>
	</div>
	<div class="mar_full_10p" style="margin-top:15px; min-height:600px;">
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		
		<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px;"><strong>Composite User Set</strong></div>
		
		<form id="formForTestmail_Template">
		<?php
			$this->load->view('mailer/SelectUserset');
		?>
		</form>
<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>