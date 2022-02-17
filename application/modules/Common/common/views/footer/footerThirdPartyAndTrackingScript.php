<?php echo TrackingCode::SCANSmartPixel($googleRemarketingParams); ?>
<?php //if(!in_array($product, array('SearchV2', 'home'))){echo TrackingCode::vizury();} ?>
<?php //if(!in_array($product, array('SearchV2', 'home'))){ echo TrackingCode::SCANAudienceBuildingPixel(); }?>
<?php //echo TrackingCode::FBConvertedAudiencePixel();?>
<?php //echo TrackingCode::GoogleConvertedAudiencePixel(); ?>

<?php
if(!empty($zopimInstituteId) && !empty($zopimScriptTag)){
	echo $zopimScriptTag;
}

//$this->load->view('common/footer/crazyegg');

//if(isset($FB_Google_Pixel_Script_required) && $FB_Google_Pixel_Script_required) {
//	$this->load->view('common/fb-google-pixel');
//}

$this->load->view('common/footer/dbTrackingForGaParamsCode'); 

//if(!in_array($product, array('SearchV2', 'home'))){
//	$this->load->view('common/footer/fbds');
//} 
?>
