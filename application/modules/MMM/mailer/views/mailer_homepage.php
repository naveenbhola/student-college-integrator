<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Mass Mailer Module - Shiksha.com</title>
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
	<div class="mar_full_10p" style="margin-top: 15px;">
    		<!--div style="margin-left:1px">
        		<div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Mass Mailer</div>
        		<div class="grayLine"></div>
        		<div class="lineSpace_10">&nbsp;</div>
			</div-->
		<?php
		$this->load->view('mailer/left-panel-top');
		?>
		
		<form method="POST" id="frmhomeTemplate" action="">
			<div class="row">
				<div style="display:inline;float:left;width:100%">
				<input type="hidden" id ="selectedTmpId" name="selectedTmpId" value="-1" />
				<div class="clear_L"></div>
				</div>
			</div>
			</form>
    		<form method="POST" id="frmSelectTemplate" action="">
            <input id="" type="hidden" value="<?php echo $templateType; ?>" name="templateType" />
    		<div id="userresults">
    		<?php
    		$this->load->view('mailer/all_templates',$response);
    		?>
    		</div>
    		</form>
    		<div class="lineSpace_35">&nbsp;</div>
			
	<?php
	$this->load->view('mailer/left-panel-bottom');
	?>
	</div>
<!--End_Center-->
<div style="line-height:50px;clear:left;">&nbsp;</div>
<?php //$this->load->view('enterprise/footer'); ?>

