<?php
$bannerURL = "";
$bannerLandingURL = "";
if (! empty ( $banner_details )) {
	$bannerURL = $banner_details ['file_path'];
	$bannerLandingURL = $banner_details ['landing_url'];
}
?>
<script type="text/javascript">
	var $rankingPage = {};
	$rankingPage.rankingPageBannerURL = "<?php echo $bannerURL;?>";
	$rankingPage.rankingPageBannerLandingURL = "<?php echo $bannerLandingURL;?>";
</script>
