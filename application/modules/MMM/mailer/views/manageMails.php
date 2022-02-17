<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style'),
	//'js'	=> array('mailer','common','enterprise','home','CalendarPopup','prototype','discussion','events','listing','blog'),
	'js'	=> array('mailer','common','enterprise','CalendarPopup','ajax-api'),
	//'jsFooter'         => array('scriptaculous','utils'),
	'jsFooter'         => array(),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>'',
	'noextra_js'=>true,
	'title'=>'Mass Mailer Module - Shiksha.com'
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
	<div class="mar_full_10p" style="margin-top: 15px;">
		<?php
		$this->load->view('mailer/left-panel-top');

		if(!empty($mailers)) {
			$this->load->view('mailer/manageMailsContent');
		} else {
			echo "No Mailers Found in Draft or Scheduled status";
		}
		?>	
    	<div class="lineSpace_35">&nbsp;</div>
			
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
	<div style="line-height:50px;clear:left;">&nbsp;</div>
</body>
</html>
