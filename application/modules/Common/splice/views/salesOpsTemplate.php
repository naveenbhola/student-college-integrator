<?php
if($userDataArray['avtarurl'] != "") {
    $avtarImg = getImageUrlBySize($userDataArray['avtarurl'], "small");
} else {
    $avtarImg = SHIKSHA_HOME."/public/images/trackingMIS/user.png";
}

$this->load->view('splice/header', array("avtarImg" => $avtarImg));
$this->load->view('splice/leftMenu', array("avtarImg" => $avtarImg));
/*
 *	loading main page content..
 */
//$this->load->view('splice/saMainContent');
?>
<?php
if($source != 'dashboard'){
	$class = 'mainRightCol';
}
?>
<div class="right_col <?php echo $class;?>" role="main">
<?php
switch($source) {
	case 'manageMembers':
							if(count($groupDetails) > 0){
								$this->load->view('splice/addNewMember');
							}
							$this->load->view('splice/dataTable');							
							break;

	case 'addNewRequest':
							$this->load->view('splice/request/addNewRequest');
							break;

	case 'viewRequest' :
							$this->load->view('splice/dataTable');
							break;

	case 'dashboard' :		$this->load->view('splice/dashboard/dashboard');
							break;

	case 'requestTaskDetails' :
								$this->load->view('splice/requestTaskDetails/requestTaskDetails');
								break;
}
?>
</div>
<?php
$this->load->view('splice/footer');
?>
