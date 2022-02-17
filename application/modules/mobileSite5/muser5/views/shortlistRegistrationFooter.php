<?php $this->load->view('/mcommon5/footerLinks');?>

<div data-role="page" id="preferredStudyLocations" data-enhance="false"></div>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("ajax-api"); ?>"></script>

<script>var total_selected_mobile=0;</script>

<?php
	$mobile_details = json_decode($_COOKIE['ci_mobile_capbilities'],true);
	$screenWidth = $mobile_details['resolution_width'];
	$screenHeight = $mobile_details['resolution_height'];
?>
<input type="hidden" value="<?php echo $screenWidth;?>" id="screenwidth" />
<input type="hidden" value="<?php echo $screenHeight;?>" id="screenheight" />
<input type='hidden' id='isMR' name='isMR' value='YES' />
<input type='hidden' id='odbRedirectBaseUrl' name="odbRedirectBaseUrl" value='<?php echo base64_encode(base_url()); ?>' />
<input type="hidden" value="<?php echo base64_encode(MOBILE_ODB_VERIFICATION);?>" id="odbMode" name="odbMode">
<input type="hidden" name="actionPoint" id="actionPoint" value="<?php echo $actionPoint; ?>">

<?php
	$footerComponent = array('doNotLoadImageLazyLoad'=>'true');
	$this->load->view('/mcommon5/footer',$footerComponent);
?>