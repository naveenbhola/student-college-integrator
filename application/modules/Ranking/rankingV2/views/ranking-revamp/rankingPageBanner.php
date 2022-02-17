<div class="top-banner-cont" id="ranking_banner_cont">
<?php 
if($page_request->getStateId() > 1) {
	$keyword = "BMS_STATE_".$page_request->getStateId()."_RANKINGPAGE_".$page_request->getPageId();
}else{
	$keyword = $page_request->getPageId();
}

$criteriaArray = array(
		'country'  => 2,
		'city'     => $page_request->getCityId(),
		'keyword'  => $keyword
);
$bannerProperties = array(
		'pageId'=>'RANKING',
		'pageZone'=>'PUSHDOWN_TOP',
		'shikshaCriteria' => $criteriaArray
);
$this->load->view('common/banner',$bannerProperties);
?>
</div>