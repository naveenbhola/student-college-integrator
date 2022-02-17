<?php
if($userDataArray['avtarurl'] != "") {
    $avtarImg = MEDIA_SERVER.getImageUrlBySize($userDataArray['avtarurl'], "small");
} else {
    $avtarImg = SHIKSHA_HOME."/public/images/trackingMIS/user.png";
}

$this->load->view('trackingMIS/header', array("avtarImg" => $avtarImg,"includeUpdatedJS"=>$includeUpdatedJS));
if($skipNavigationSASales!==true){
	$this->load->view('trackingMIS/leftMenu', array("avtarImg" => $avtarImg));
}
/*
 *	loading main page content..
 */
switch($misSource) {
	case 'studyAbroad':
		$this->load->view('trackingMIS/saMainContent');
		break;
	case 'CD':
	case 'Content-Delivery':
		$this->load->view('trackingMIS/cdMainContent');
		break;
	case 'nationalListing':
		$this->load->view('trackingMIS/listingsMainContent');
		break;
	case 'ldb':
		$this->load->view('trackingMIS/ldbMainContent');
		break;

	case 'shiksha' :
	case 'national':	
		$this->load->view('trackingMIS/globalMainContent');
		break;
	case 'SASALES':	
		$this->load->view('trackingMIS/saSales/saSalesMainContent');
		break;						

	case 'pbt':
		$this->load->view('trackingMIS/pbtMainContent');
		break;

	case 'experimentalResponses':
		$this->load->view('trackingMIS/experimentalResponses');
		break;

	case 'shikshaMIS':
		if($validRequest === true){
			$this->load->view('trackingMIS/MISMainContent');
		}		
		break;

	case 'assistantMIS':
		if($validRequest === true){
			$this->load->view('trackingMIS/sassistantMainContent');
		}		
		break;

	default :
		$this->load->view('trackingMIS/globalMainContent');
		break;
}

	switch ($misSource) 
	{
		case 'SASALES':
			$this->load->view('trackingMIS/saSales/footer');
			break;
		
		default:
			$this->load->view('trackingMIS/footer');
			break;
	}
?>
