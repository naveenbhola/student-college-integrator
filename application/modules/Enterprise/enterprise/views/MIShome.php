<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MIS Home-Page</title>

<!-- Auto-refresh every 60 Seconds -->
<meta http-equiv="refresh" content="60"/>

<?php
$headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
                                                                'js'	=>	array('common','enterprise','CalendarPopup','prototype'),
                          					'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'tabName'	=>	'',
                                                                'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
							);
		$this->load->view('cms_core/headerCMS', $headerComponents);
?>
</head>


<?php $this->load->view('enterprise/footer'); ?>