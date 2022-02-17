<?php 
$tempJsArray = array('myShiksha','user');   
$headerComponents = array(
	'css'   =>      array('campus-representative'),
	'js' => array('common','facebook','ajax-api','imageUpload','campusAmbassador','CalendarPopup','onlinetooltip','CAValidations'),
	'jsFooter'=>    $tempJsArray,
	'title' =>      ($headerTitle !='') ? $headerTitle : 'Campus Connect' ,
	'product'       =>'campusAmbassador',
	'showBottomMargin' => false,
	'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
	'showUI'=>false,
	'invisibleGNB'=> true
);
$this->load->view('common/header', $headerComponents);
//$this->load->view('CA/autoSuggestorForInstitute');  
$this->load->view('common/calendardiv');
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
	$j = jQuery.noConflict();
</script>