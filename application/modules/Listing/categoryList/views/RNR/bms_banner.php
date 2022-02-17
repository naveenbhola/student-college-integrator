<?php 
$style = "";
$id = "";
global $isShortlistWidgetVisible;
if($isShortlistWidgetVisible) {
	$style = "style='margin-top:257px'";
	$id = "id='shortListNextTuple' isAnimated='false'";
	$isShortlistWidgetVisible = false;
}

?>
<div <?=$style?> <?=$id?> class="inner-banners">
	<?php

		global $criteriaArray;
		$bannerProperties = array('pageId' => 'RNR_CATEGORY', 'pageZone' => $pageZone,'shikshaCriteria' => $criteriaArray);
		$this->load->view('common/banner',$bannerProperties); ?>
</div>