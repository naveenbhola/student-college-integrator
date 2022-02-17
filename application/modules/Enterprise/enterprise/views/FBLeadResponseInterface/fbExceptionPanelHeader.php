<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer'),
        'js'    =>  array('common','enterprise'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  'fb_lead_mapping',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );

$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>


