<?php 
	global $DFP_BANNER_CONFIG_MOBILE;
	if(($bannerPosition == 'footer' || $bannerPosition == 'aboveCTA')  && empty($DFP_BANNER_CONFIG_MOBILE[$dfpData['parentPage']][$bannerPosition])){

		$currentConfig = $DFP_BANNER_CONFIG_MOBILE['shiksha'][$bannerPosition];
	}		
	else if(!empty($bannerPosition)) {
		$currentConfig = $DFP_BANNER_CONFIG_MOBILE[$dfpData['parentPage']][$bannerPosition];
	}
?>
<?php if(!empty($currentConfig)) {
	?>
	<amp-ad style="margin: 0 auto 10px;display: block;background: #ccc;" width="<?php echo $currentConfig['width'];?>" height="<?php echo $currentConfig['height'];?>"
	    type="doubleclick"
	    data-slot="<?php echo $currentConfig['slotId'];?>"
	    json='{"targeting":<?php echo json_encode($dfpData);?>}'>
	</amp-ad>
<?php } ?>