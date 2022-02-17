<?php
	$js = array('myshortlist_cms', 'common', 'studyAbroadCMS');
	$headerComponents = array(
        'css'   =>  array('headerCms', 'myshortlist_cms'),
        'js'    =>  $js,
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'Myshortlist Enterprise',
        'taburl' 	=> site_url('/MyShortlistCMS/index'),
        'jsFooter' => array('scriptaculous'),   
        'metaKeywords'  =>'',
		'title' => 'Myshortlist Notification Center',
        'isOldTinyMceNotRequired' => 1
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>
