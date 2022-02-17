<?php

class RankingPageManager {
	
	private $rankingPageCommmonLib;
	private $rankingModel;
	private $skipStatusCheck = "false";
	
	public function __construct($rankingPageCommmonLib, $rankingModel){
		if(!empty($rankingModel) && !empty($rankingPageCommmonLib)){
			$this->rankingModel 		 = $rankingModel;
			$this->rankingPageCommmonLib = $rankingPageCommmonLib;	
		}
		if(!empty($_REQUEST['skipstatuscheck']) && $_REQUEST['skipstatuscheck'] == "true"){
			$this->skipStatusCheck = $_REQUEST['skipstatuscheck'];
		}
	}
	
	/**
	 *@method: Returns complete information about ranking page
	 *@return: Array with two keys
	 *1. ranking_page_details: This will have complete information about ranking page like name, id, category etc but not courses related to this page.
	 *2. ranking_page_data: This list will have all the courses in this ranking page with details like course alt text, rank etc
	 *@example: 
	 *@Array (
			[ranking_page_details] => Array (
					[id] => 12
					[category_id] => 3
					.....
					.....
				),
			[ranking_page_data] => Array (
				[0] => Array (
					[id] => 8
                    [institute_id] => 307
                    [course_id] => 1653
                    [course_alt_text] => PGP
                    ......
                    ......
				)
		)
	*/
	public function getRankingPage($rankingPageId = NULL, $status = array('live')){
		$rankingPage = false;
		if(!empty($rankingPageId)){
			if($this->skipStatusCheck == "true"){
				$status = array("live", "disable", "draft");
			}
			$rankingPage = $this->getRankingPageDetails($rankingPageId, $status);	
		}
		return $rankingPage;
	}
	
	private function getRankingPageDetails($rankingPageId = NULL, $status = array('live')) {
		$returnData = array('ranking_page_details' => false, 'ranking_page_data' => false);
		if(empty($rankingPageId)){
			return $returnData;
		}
		$rankingPageDetails 	= array();
		$rankingPageDataDetails = array();
		
		$params = array();
		$params['id'] 	  = $rankingPageId;
		$params['status'] = $status;
		$data = $this->rankingModel->getRankingPages($params);
		
		if(!empty($data) && is_array($data)){
			$rankingPageDetails = $data[0];
		}
		
		if(!empty($rankingPageDetails)){
			$params = array();
			$params['ranking_page_id'] 		= $rankingPageId;
			$rankingPageData = $this->rankingModel->getRankingPageData($params);
			foreach($rankingPageData as $data){
				$tempRankingPageRow = $data;
				$rankingPageDataDetails[] = $tempRankingPageRow;
			}
		}
		$returnData['ranking_page_details'] = $rankingPageDetails;
		$returnData['ranking_page_data'] 	= $rankingPageDataDetails;
		
		return $returnData;
	}
}