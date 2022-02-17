<?php
if($userDataArray['avtarurl'] != "") {
    $avtarImg = getImageUrlBySize($userDataArray['avtarurl'], "small");
} else {
    $avtarImg = SHIKSHA_HOME."/public/images/trackingMIS/user.png";
}

$this->load->view('trackingMIS/header', array("avtarImg" => $avtarImg));
$this->load->view('trackingMIS/leftMenu', array("avtarImg" => $avtarImg));

$this->load->view('trackingMIS/appTrendsContainer');

$this->load->view('trackingMIS/footer');
?>