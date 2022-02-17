<?php
if($userDataArray['avtarurl'] != "") {
    $avtarImg = getImageUrlBySize($userDataArray['avtarurl'], "small");
} else {
    $avtarImg = SHIKSHA_HOME."/public/images/trackingMIS/user.png";
}

$this->load->view('trackingMIS/header', array("avtarImg" => $avtarImg));
$this->load->view('trackingMIS/leftMenu', array("avtarImg" => $avtarImg));

/*
 *	loading main page content..
 */
switch($misSource) {
	case 'studyAbroad':
							$this->load->view('trackingMIS/saMainContent');
							break;
	case 'UGC':
							$this->load->view('trackingMIS/saMainContent');
							break;
	case 'nationalListing':
							$this->load->view('trackingMIS/listingsMainContent');
							break;
	default :
							$this->load->view('trackingMIS/globalMainContent');
							break;
}


$this->load->view('trackingMIS/footer');
?>
