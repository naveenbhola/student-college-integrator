<?php
function getPageHeadLine($page_headline = array()){
	$pageHeadline = "";
	if(!empty($page_headline)){
		$pageHeadline .= "Top " . $page_headline['page_name'] . " institutes";
		if(!empty($page_headline['location'])){
			$pageHeadline .= " in <span>" . $page_headline['location'] . "</span>";
		}
		if(!empty($page_headline['exam'])){
			$pageHeadline .= " accepting " . $page_headline['exam'];
		}	
	}
	return $pageHeadline;
}

$mainRankingPageData = array();
if(!empty($ranking_page)){
	$mainRankingPageData  = $ranking_page->getRankingPageData();
}

$locationResults = array();
$examResults     = array();
if(!empty($partial_results)){
	$locationResults = $partial_results['location'];
	$examResults 	 = $partial_results['exam'];
	if(!empty($locationResults) || !empty($examResults)){
		if(!empty($mainRankingPageData)){
		?>
			<div class="confirmation-msg" style="margin:10px 0px 20px 0px; border-bottom:solid 1px #E8E8E8;">
				Besides the ones listed above, here are a few more suggestions:
			</div>
			<!--<h3 class="suggestion-title">Besides the ones listed above, here are a few more suggestions:</h3>-->
		<?php
		} else {
			?>
			<!--<h3 class="suggestion-title">Here are a few suggestions matches your search:</h3>-->
			<?php
		}
	}
	if(!empty($locationResults)){
		$locationRankingPage  = $locationResults['ranking_page'];
		$locationPageHeadLine = getPageHeadLine($locationResults['page_heading']);
		$locationPageRequest  = $locationResults['page_request'];
		$locationPageData = array();
		$locationPageData['ranking_page'] 			= $locationRankingPage;
		$locationPageData['heading'] 	  			= $locationPageHeadLine;
		$locationPageData['request_object'] 		= $locationPageRequest;
		$locationPageData['main_request_object'] 	= $page_request;
		$locationPageData['filters'] 				= $filters;
		$locationPageData['resultType'] 			= "location";
		$locationPageData['sorter'] 				= NULL;
		$locationPageData['total_results'] 			= $locationResults['total_results'];
		$locationPageData['max_rows'] 				= $locationResults['max_rows'];
		$locationPageData['offset_reach'] 			= $locationResults['offset_reach'];
		
		$this->load->view('ranking/ranking_subheading', $locationPageData);
		$this->load->view('ranking/ranking_table_container', $locationPageData);
	}
	
	if(!empty($examResults)){
		$examRankingPage  = $examResults['ranking_page'];
		$examPageHeadLine = getPageHeadLine($examResults['page_heading']);
		$examPageRequest  = $examResults['page_request'];
		$examPageData = array();
		$examPageData['ranking_page'] 			= $examRankingPage;
		$examPageData['heading'] 	  			= $examPageHeadLine;
		$examPageData['request_object'] 		= $examPageRequest;
		$examPageData['main_request_object'] 	= $page_request;
		$examPageData['filters'] 				= $filters;
		$examPageData['resultType'] 			= "exam";
		$examPageData['sorter'] 				= NULL;
		$examPageData['total_results'] 			= $examResults['total_results'];
		$examPageData['max_rows'] 				= $examResults['max_rows'];
		$examPageData['offset_reach'] 			= $examResults['offset_reach'];
		
		$this->load->view('ranking/ranking_subheading', $examPageData);
		$this->load->view('ranking/ranking_table_container', $examPageData);
	}
}

$pageRequestArray = array();
$examPageRequestArray = array();
$locationPageRequestArray = array();

if(!empty($page_request)){
	$pageRequestArray 	  		= convertRequestObjectIntoArray($page_request);
}
if(!empty($examResults['page_request'])){
	$examPageRequestArray 		= convertRequestObjectIntoArray($examResults['page_request']);	
}
if(!empty($locationResults['page_request'])){
	$locationPageRequestArray 	= convertRequestObjectIntoArray($locationResults['page_request']);
}
?>
<script type="text/javascript">
	var pageRequest 		= eval(<?php echo json_encode($pageRequestArray); ?>);
	var examPageRequest 	= eval(<?php echo json_encode($examPageRequestArray); ?>);
	var locationPageRequest = eval(<?php echo json_encode($locationPageRequestArray); ?>);
	Ranking.setPageRequest(pageRequest);
	Ranking.setExamPageRequest(examPageRequest);
	Ranking.setLocationPageRequest(locationPageRequest);
</script>