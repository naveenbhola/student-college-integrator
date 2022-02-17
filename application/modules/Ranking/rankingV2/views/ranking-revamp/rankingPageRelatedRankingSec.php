<div class="ranking-wrapper clear-width hid">
	<div class="related-ranking-sec clear-width">
		<h2>Other Related Rankings</h2>
		<div class="related-rnk-widget">
			<?php 
				$widgetSpecilisationHeadline = "";
				$widgetLocationHeadline      = "";
				$widgetExamHeadline          = "";
				if(!$page_headline){
					$commonHeadline              = "Top ranking";
					switch ($rankingPageId) {
						case 1:
							$widgetSpecilisationHeadline = $commonHeadline." MBA/PGDM colleges for";
							break;
						case 6:
							$widgetSpecilisationHeadline = $commonHeadline." Engineering colleges for";
							break;
						case 4:
							$widgetSpecilisationHeadline = $commonHeadline." Part time MBA colleges for";
							break;
						case 5:
							$widgetSpecilisationHeadline = $commonHeadline." Executive MBA colleges for";
							break;
						default:
							$widgetSpecilisationHeadline = $commonHeadline." colleges for";
							break;
					}
					$selectedCityFilter = $filtersChecks['selectedLocation']->getName();
					if($selectedCityFilter == 'All'){
						$widgetExamHeadline          .= $commonHeadline." ".$page_headline['page_name']." colleges in India accepting";
					}else{
						$widgetExamHeadline          .= $commonHeadline." ".$page_headline['page_name']." colleges in ".$selectedCityFilter." accepting";
					}
					$widgetLocationHeadline      .= $commonHeadline." ".$page_headline['page_name']." colleges in";
				}

				$specializationFilterResults = '';
				if(is_array($filters['specialization'])){
					if($rankingPageRequest->getSpecializationId()){ 	// Child page
						$specializationFilterResults = array(array($filters['specialization']['parentUrl']));
					}else{
						$specializationFilterResults = array($filters['specialization']['childrenUrl']);
					}
				}
				if(gettype(reset($filters['specialization'])) == "array"){
					$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageRelatedRankingWidgets',array('filterResult'=>$specializationFilterResults,'showFilters'=>$filtersChecks['showSpecializationFilters'],'widgetHeadline'=>$widgetSpecilisationHeadline)); 
				} 
				if($filtersChecks['showExamFilters'] == true){
					$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageRelatedRankingWidgets',array('filterResult'=>$filters['exam'],'showFilters'=>$filtersChecks['showExamFilters'],'widgetHeadline'=>$widgetExamHeadline)); 
				}	
				$this->load->view(RANKING_PAGE_MODULE.'/ranking-revamp/widgets/rankingPageRelatedRankingWidgets',array('cityFilters'=>$filters['city'],'useCityFilter'=>$filtersChecks['useCityFilter'],'stateFilters'=>$filters['state'],'widgetType'=>'location','widgetHeadline'=>$widgetLocationHeadline)); 
			?>
		</div>
	</div>
</div>
<div class="page-break clear-width"></div>