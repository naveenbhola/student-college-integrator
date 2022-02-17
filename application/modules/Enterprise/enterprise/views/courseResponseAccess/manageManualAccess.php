<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','mainStyle','footer','searchCriteria', 'courseResponseAccess'),
        'js'    =>  array('common','enterprise','searchCriteria','courseResponseAccess'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<?php
        $this->load->view('common/calendardiv');
?>


<div class = "pageView" id = "page1" style="display: block;"> 
	<?php $this->load->view('courseResponseAccess/getCourseFromClient',$data); ?>
</div>

<div class = "pageView" id = "page2" style="display: none;"></div>

<div class = "pageView clp-response " id = "page3" style="display: none;"> 
        
</div>

<?php $this->load->view('enterprise/footer'); ?>
