<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','modal-message','online-styles','common'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','tooltip','onlineFormEnterprise','ana_common','json2'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>'',
	'title' => 'Enterprise User Dashboard'
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<?php
$this->load->view('enterprise/cmsTabs');
?>
<a href="javascript:void(0);" onclick="importExternalOnlineFormInfo('<?php echo $_REQUEST[courseIdFormDatadashBoard];?>');">Import</a>
<?php
$this->load->view('enterprise/footer');
?>