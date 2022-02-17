<div style="display:none">
		<img id='beacon_img' src="<?php echo IMGURL_SECURE ;?>/public/images/blankImg.gif" width=1 height=1 >
</div>
<?php
	$footerComponents = array(
		'pages'=>array('contentUserRegistrationPanel'),
		'trackingPageKeyIdForReg' => 491,
		'commonJSV2'=>true,
		'loadLazyJSFile'=>false,
		'js'=> array("commonSA","registrationSA","contentSA"),
		//'jqueryUIJsAsync'=>false
	);
	$this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
    var BEACON_URL ='<?php echo BEACON_URL.'/';?>';
</script>

