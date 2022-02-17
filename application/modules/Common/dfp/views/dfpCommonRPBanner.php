<?php
global $dfpConfig;
if(!empty($dfpData)) {
?>
<div id="rightPanelDFPBanner">
	<div id="<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['opt_div'];?>" style="height:<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['height'];?>px; width:<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['width'];?>px;padding-top:15px;margin: 0 auto;">
	<script>
		googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['opt_div']?>"); });
	</script>
	</div>
</div>
<?php } ?>

