<?php
$this->load->view('scholarshipHomePage/scholarshipHomePageHeader');
if($trackForPages){
	echo jsb9recordServerTime('SA_MOB_SCHOLARSHIP_HOMEPAGE', 1);
}
$this->load->view('scholarshipHomePage/scholarshipHomePageContent');
$this->load->view('scholarshipHomePage/scholarshipHomePageFooter');
?>