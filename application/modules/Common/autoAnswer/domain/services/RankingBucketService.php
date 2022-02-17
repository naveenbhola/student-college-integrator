<?php

class RankingBucketService extends AbstractBucketService{

	private $request;
	private $CI;

	function __construct($request){

		global $bucketsConfig;
		$this->request = $request;
		$this->bucketPattern = $bucketsConfig[BUCKET_RANKING];
		$this->match = false;
		$this->CI = & get_instance();
		$this->lib = $this->CI->load->library('autoAnswer/AutoAnsweringLib');
	}

	function isApplicable(){
		
		$this->match = $this->findPattern($this->bucketPattern, $this->request);

		return $this->match;
	}

	/*
	1. contains best/top keyword
	2. contains institute/college keyword
	3. contains a Stream level tag(or it's child)
	*/
	function isCriteriaSatisfied($seedTag){

		global $autoAnsweringSynonyms;

		$input = $this->request->getRequestText();
		$bucketCriteriaSatisfied = false;
		if(!empty($seedTag) && $this->contains($input, $autoAnsweringSynonyms['top']) && $this->contains($input, $autoAnsweringSynonyms['institute'])){
			$bucketCriteriaSatisfied = true;
		}
		
		return $bucketCriteriaSatisfied;
	}

	function getReponse(){

		$this->taggingLib          = $this->CI->load->library('Tagging/TaggingLib');
		$this->tagsCategoryMapping = $this->CI->config->item("TAG_CATEGORY_MAPPING");

		// check for bucket level criteria matching
		$input         = $this->request->getRequestText();
		$tags          = $this->taggingLib->showTagSuggestions(array($input));
		$objectiveTags = $tags['objective'];

		$autoAnsweringModel      = $this->CI->load->model("autoAnswer/autoansweringmodel");
		$tagData                 = $autoAnsweringModel->fetchTagsInfo($objectiveTags);
		$streamTags              = $this->lib->getStreamLevelTag($tagData);
		$bucketCriteriaSatisfied = $this->isCriteriaSatisfied($streamTags);

		if(!$bucketCriteriaSatisfied){
			error_log("Ranking criteria not satisfied");
			return NO_RESPONSE;
		}

		$states = array();
		$cities = array();
		foreach ($tagData as $tagRow) {
			if($tagRow['tag_entity'] == 'City'){
				$cities[] = $tagRow['tags'];
			}

			if($tagRow['tag_entity'] == 'State'){
				$states[] = $tagRow['tags'];
			}
		}

		$prominentCity = "";
		$prominentState = "";
		if(!empty($cities)){
			$prominentCity = $cities[0];
		}
		if(!empty($states)){
			$prominentState = $states[0];
		}

		if(!empty($prominentCity)){
			$prominentCity = $autoAnsweringModel->getCityIdByName($prominentCity);
		}
		if(!empty($prominentState)){
			$prominentState = $autoAnsweringModel->getStateIdByName($prominentState);
		}

		$this->categorypagemodel  = $this->CI->load->model('categoryList/categorypagemodel');
		$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
		$this->rankingURLManager  	= RankingPageBuilder::getURLManager();
		$rankingUrlData = array();

		foreach ($streamTags as $key => $seedTag) {
			
			$subCategoryId = 0;
			if($this->tagsCategoryMapping[$seedTag['seedTag']['id']])
				$subCategoryId = $this->tagsCategoryMapping[$seedTag['seedTag']['id']];

			_p($seedTag);
			if(empty($subCategoryId))
				continue;

			_p($subCategoryId);
			$filters                  = array();
			$filters['subcategoryId'] = $subCategoryId;
			$filters['ldbCourseId']   = 0;
			if($prominentCity)
				$filters['cityId'] = $prominentCity;
			else{
				$filters['cityId']        = 0;
				
				if($prominentState)
					$filters['stateId']       = $prominentState;
				else
					$filters['stateId']       = 0;
			}


			_p($filters);
			$data = $this->categorypagemodel->getNonZeroRankingPages($filters, 1);
			if(!empty($data))
				$rankingUrlData[] = $data[0];
		}

		if(empty($rankingUrlData)){
			error_log("No ranking entry found");
			return NO_RESPONSE;
		}
		
		$urls = array();
		$responseText = "";
		foreach ($rankingUrlData as $key => $data) {

			$rankingPageRepository = RankingPageBuilder::getRankingPageRepository();
			$rankingPage           = $rankingPageRepository->find($data['ranking_page_id']);
			$pageIdentifier        = $data['ranking_page_id']."-".$data['country_id']."-".$data['state_id']."-".$data['city_id']."-".$data['exam_id'];
			$request               = $this->rankingURLManager->getRankingPageRequest($pageIdentifier);
			$urls                  = $this->rankingURLManager->buildURL($request, 'urltitle', 1);

			$responseText .= ($key+1).".) ";
			$responseText .= $urls['title']."<br/>";
			$responseText .= "<ul>";

			$rankingRows = $rankingPage->getRankingPageData();
			$rankingRows = array_slice($rankingRows, 0, 5);

			foreach ($rankingRows as $rankingRow) {
				$responseText .= "<li><a href='".$rankingRow->getInstituteURL()."'>".$rankingRow->getInstituteName()."</a></li>";
			}
			$responseText .= "</ul>";
			$responseText .= $urls['title']." - ".SHIKSHA_HOME.$urls['url'];
		}

		_p($urls);

		
		// foreach ($urls as $key => $value) {
		// 	$responseText .= $value['title']." - ".$value['url'];
		// }

		if(!$responseText)
			$responseText = NO_RESPONSE;

	    return $responseText;

	}
}
?>