<?php
if($userDataArray['avtarurl'] != "") {
    $avtarImg = getImageUrlBySize($userDataArray['avtarurl'], "small");
} else {
    $avtarImg = SHIKSHA_HOME."/public/images/trackingMIS/user.png";
}

$this->load->view('trackingMIS/header', array("avtarImg" => $avtarImg));
$this->load->view('trackingMIS/leftMenu', array("avtarImg" => $avtarImg));

$ajaxBasedActions = array(
    'response',
    'registration',
    'traffic',
    'engagement'
);

if ( in_array(strtolower($actionName), $ajaxBasedActions) ) {
    $this->load->view('trackingMIS/nationalListings/skeleton');
} else { // Unused now - all actions are ajax based
    $this->load->view('trackingMIS/nationalListings/trendsContainer');
    $this->load->view('trackingMIS/nationalListings/scripts'); // contains the bootstrap dropdown handler code
}

$this->load->view('trackingMIS/footer');

