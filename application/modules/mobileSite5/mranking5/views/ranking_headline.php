<?php
$pageHeadline = "";
if(!empty($page_headline)){
	$pageHeadline .= "Top <span id='rankingDataCount'></span>" . $page_headline['page_name'] . " Colleges";
	if(!empty($page_headline['location'])){
		$pageHeadline .= " in <span>" . $page_headline['location'] . "</span>";
	}
	if(!empty($page_headline['exam'])){
		$pageHeadline .= " accepting " . $page_headline['exam'];
	}
	echo $pageHeadline;
	//echo '<br/><a data-transition="slide" data-rel="dialog" data-inline="true" href="#rankingCourseDiv" class="change-loc" id="lId" style="margin-left:0px;margin-top:5px;font-size:0.8em;padding-top:2px;padding-bottom:2px;">View ranking for other courses <i class="icon-right"></i></a>';
	
	$ranking_table_data = array();
	$ranking_table_data['ranking_page'] 	= $ranking_page;
	$ranking_table_data['filters'] 	    	= $filters;
	$ranking_table_data['sorter'] 	    	= $sorter;
	$ranking_table_data['result_type'] 	= $resultType;
	$ranking_table_data['request_object'] 	= $request_object;
	//$this->load->view('ranking_sorter', $ranking_table_data);
	
	$categoryFilters = $filters['category'];
	$catFilterData = array();
	$catFilterData['category_filters'] = $categoryFilters;
	$cityTempFilters 	= $filters['city'];
	$stateTempFilters 	= $filters['state'];
}
?>
<input id="mobileRankingPageName" name="mobileRankingPageName" value="<?=$page_headline['page_name']?>" type="hidden">