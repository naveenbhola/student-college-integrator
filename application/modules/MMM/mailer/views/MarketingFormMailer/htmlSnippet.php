<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog','ajax-api'),
	'jsFooter'         => array('scriptaculous','utils'),
	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'tabName'	=>	'Mass Mailer',
	'taburl' => site_url('mailer/Mailer'),
	'metaKeywords'	=>''
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    //$this->load->view('enterprise/cmsTabs',$cmsUserInfo);
    $this->load->view('common/calendardiv');
?>
</head>
<body>
	
<div class="mar_full_10p" style="margin-top:15px; min-height:600px;">
<?php
$this->load->view('mailer/left-panel-top');
?>  	
	
	
<div style='padding:10px; padding-top:0; min-height: 400px;'>
	
	<div class="OrgangeFont fontSize_18p bld" style="padding-bottom:10px; float:left;"><strong>
        HTML Snippet: <span style='color:#666;'><?php echo $form['name']; ?></span>
    </strong></div>
	
	<div class="clearFix"></div>
	
	
	
	
	<div style='background:#F6F6F6; padding:20px; margin-top: 20px;'>
	<h1 style="font-size: 16px; color:#222;"><?php echo htmlentities("<input type='hidden' name='mfid' value='".$form['fid']."' />"); ?></h1>
	</div>
	
	<div style="font-size: 15px; margin-top: 30px;">
	<a href='/mailer/MarketingFormMailer/listForms'>Go back to form list</a>
	</div>
</div>    
<?php $this->load->view('common/footer');?>
