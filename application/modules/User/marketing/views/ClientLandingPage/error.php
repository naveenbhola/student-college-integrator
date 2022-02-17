<?php
$headerComponents = array(
	'css'	=> array('headerCms','raised_all','mainStyle','footer','cal_style','mmm_styles'),
	'js'	=> array('mailer','common','tooltip','enterprise','home','CalendarPopup','prototype','scriptaculous','discussion','events','listing','blog','footer','ajax-api'),
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
	
	<div style='background:#FFEBEB; padding:20px;'>
	<h1 style="font-size: 16px; color:red;"><?php echo $formId ? "Error in updating form" : "Error in creating form"; ?></h1>
	<div class="clearFix"></div>
	
	<div style="font-size: 13px; margin-top:10px; color:#444;">
    This form could not be <?php echo $formId ? "updated" : "created"; ?> due to the following errors:
	<ul style="margin-top: 15px;">
		<?php foreach($errors as $error) { ?>
			<li style="list-style-type: square; margin-left: 20px; margin-bottom: 10px; color:#FA2723;"><?php echo $error; ?></li>
		<?php } ?>
	</ul>
	</div>
	
	<div style="font-size: 15px; margin-top: 40px;">
	<?php if($formId) { ?>	
		<a href='/mailer/MarketingFormMailer/editForm/<?php echo $formId; ?>'>Update form again</a>
	<?php } else { ?>
		<a href='/mailer/MarketingFormMailer/createForm'>Create a new form</a>
	<?php } ?>
	<br /><br />
	<a href='/mailer/MarketingFormMailer/listForms'>Go to form list</a>
	</div>
	</div>
</div>    
<?php $this->load->view('common/footer');?>
