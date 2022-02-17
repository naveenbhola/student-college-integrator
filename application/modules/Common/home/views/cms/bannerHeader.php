<?php
$headerComponents = array(
        'css'   			=>  array('headerCms','mainStyle','footer','cal_style', 'common_new', 'homepage_banner_cms'),
        'js'    			=>  array('common', 'CalendarPopup', 'homepagecms', 'imageUpload'),
        'displayname'		=> (isset($validateuser[0]['displayname']) ? $validateuser[0]['displayname']:""),
        'tabName'   		=>  'Homepage Banner CMS',
        'taburl' 			=> site_url('/home/HomePageCMS/index/'),
		'title'				=> 'Homepage CMS Pannel',
        'metaKeywords'  	=>'',
        );
?>
<link href='<?=SITE_PROTOCOL;?>fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	
<?php
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');