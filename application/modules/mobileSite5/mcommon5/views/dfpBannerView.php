<?php 
	global $DFP_BANNER_CONFIG_MOBILE;
	if($bannerPosition == 'footer' && empty($DFP_BANNER_CONFIG_MOBILE[$dfpData['parentPage']][$bannerPosition]))
	{
		$currentConfig = $DFP_BANNER_CONFIG_MOBILE['shiksha'][$bannerPosition];
	}
	else if(!empty($bannerPosition)) {
		$currentConfig = $DFP_BANNER_CONFIG_MOBILE[$dfpData['parentPage']][$bannerPosition];
	    if(empty($currentConfig))
	    {
	        //$currentConfig = $DFP_BANNER_CONFIG_MOBILE['DFP_Others'][$bannerPosition];
	    }
	}
?>
<?php if(!empty($currentConfig)) {
		/*$margin = '10px';
		if($bannerPosition == 'LAA1' || $bannerPosition == 'AON1')
		{
			$margin = '-10px';
		}
		if(in_array($bannerPosition, array('LAA1','LAA','AON','AON1')))
		{
			$classHtml = 'ht-dfp';
		}*/
	?>
	<div class="dfp-wraper <?=$classHtml;?>">
	  <div class="dfp-add">  
	    <div id="<?=$currentConfig['elementId']?>" class="dfp-f-h" style="margin: <?=$margin?> auto 10px;">
	    </div>
	  </div>
	</div>
	<script>
		googletag.cmd.push(function() { googletag.display('<?=$currentConfig['elementId']?>'); });
	</script>
<?php } ?>
