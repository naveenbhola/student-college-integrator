<?php

class RankingPageManager {
	
	private $rankingPageCommmonLib;
	private $rankingModel;
	private $skipStatusCheck = "false";
	
	public function __construct($rankingPageCommmonLib, $rankingModel){
		if(!empty($rankingModel) && !empty($rankingPageCommmonLib)){
			$this->rankingModel 		 = $rankingModel;
			$this->rankingPageCommmonLib = $rankingPageCommmonLib;	
			$this->CI = &get_instance();
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
	public function getRankingPage($rankingPageId = NULL, $publisherId = null, $availablePublisherIds = array()){
		$status = array('live');
		$source_id = '';
		$rankingPage = false;
		if(!empty($rankingPageId)){
			if($this->skipStatusCheck == "true"){
				$status = array("live", "disable", "draft");
			}
			$rankingPage = $this->getRankingPageDetails($rankingPageId, $publisherId, $availablePublisherIds);//_p($rankingPage);die;
		}
		return $rankingPage;
	}
	
	private function getRankingPageDetails($rankingPageId = NULL , $publisherId, $availablePublisherIds) {
		$status = array('live'); 
		$source_id = "";
		$returnData = array('ranking_page_details' => false, 'ranking_page_data' => false);
		if(empty($rankingPageId)){
			return $returnData;
		}
		$rankingPageDetails 	= array();
		$rankingPageDataDetails = array();
		
		$params = array();
		$params['id'] 	  = $rankingPageId;
		$params['status'] = $status;
		
		// if($this->CI->enableRankingPageCache || 1) {
		$data = $this->rankingPageCommmonLib->getRankingPagesUsingCache($params);
		// }
        
		if(!empty($data) && is_array($data)){
			$rankingPageDetails = $data[0];
		}

		if(empty($publisherId)) {
			$publisherId = $rankingPageDetails['default_publisher'];
			if(!in_array($publisherId, $availablePublisherIds)) {
				$publisherId = reset($availablePublisherIds);
			}
		}

		if(!empty($rankingPageDetails)){
			$params                    = array();
			$params['ranking_page_id'] = $rankingPageId;
			$params['publisherId']     = $publisherId;
			if($source_id) {
				$params['source_id'] = $source_id;
			}
			$rankingPageData = $this->rankingModel->getRankingPagesData($params);
			$rankingConfig = $this->CI->config->item('rankingConfig');
			// prepare ranking page details data
			$finalRankingPageData = array();
			// $instituteIds         = array();
			foreach($rankingPageData['results'] as $rankingPageDataRow)
			{
				$dataArr = array("source_id" => $rankingPageDataRow['source_id'],
						 		 "rank" => $rankingPageDataRow['rank']);
				// _p($dataArr); die;
				if(!isset($finalRankingPageData[$rankingPageDataRow['course_id']]['sourceRank']))
				{
					$rankRow                                                = array();
					$rankRow['id']                                          = $rankingPageDataRow['id'];
					$rankRow['ranking_page_id']                             = $rankingPageDataRow['ranking_page_id'];
					$rankRow['institute_id']                                = $rankingPageDataRow['institute_id'];
					$rankRow['course_id']                                   = $rankingPageDataRow['course_id'];
					// $rankRow['course_alt_text']                             = $rankingPageDataRow['course_alt_text'];
					
					$finalRankingPageData[$rankingPageDataRow['course_id']] = $rankRow;
				}
				//check for NIRF
				if($params['publisherId'] == $rankingConfig['NIRF_Publisher_Id'] && ($params['ranking_page_id'] == $rankingConfig['MBA_Ranking_Page_Id'] || $params['ranking_page_id'] == $rankingConfig['EXECUTIVE_MBA_Ranking_Page_Id'])  && $dataArr['rank'] > 50) {
					if($dataArr['rank'] > 50 && $dataArr['rank'] < 76) {
						$dataArr['rank'] = "51-75";
					}
					else if($dataArr['rank'] > 75 && $dataArr['rank'] < 101) {
						$dataArr['rank'] = "76-100";
					}
				}
				// $instituteIds[$rankingPageDataRow['institute_id']] = $rankingPageDataRow['institute_id'];
				$finalRankingPageData[$rankingPageDataRow['course_id']]['sourceRank'][$rankingPageDataRow['source_id']] = $dataArr;
			}
		}
		// _p($finalRankingPageData); die;
		$returnData['ranking_page_details'] 	= $rankingPageDetails;
		$returnData['ranking_page_data']    	= $finalRankingPageData;
		$returnData['sourceId']    		= $rankingPageData['sourceId'];
		$returnData['rankingPageSourceData']  	= $rankingPageData['rankingPageSourceData'];
		// $returnData['instituteIds']  	= $instituteIds;
		return $returnData;
	}
	
	/**
	 *@method: Returns ranking page sources data for given ranking page id
	 *@params: 1. Ranking page id
	 *	   2. Status
	 *@return: Array containing ranking page source data
	 *@author: Romil
	 */
	public function getRankingSourceData($rankingPageId, $status, $source_id = 0){
		
		// return false if no ranking page id is provided
		if(!$rankingPageId)
			return false;
		
		return $this->rankingModel->getRankingPageSources($rankingPageId, $status, $source_id);
	}
}