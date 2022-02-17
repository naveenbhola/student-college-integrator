<?php

class RankingEnterprisev2 extends MX_Controller {

	public function fetchInstituteCourses($instituteId = NULL){
		$resultSet = array();
		if(!empty($instituteId)){
			$rankingCommonLib = $this->load->library(RANKING_PAGE_MODULE.'/rankingCommonLibv2');
			$resultSet = $rankingCommonLib->getInstituteCourses($instituteId);
		}
		echo json_encode($resultSet);
	}

	public function getRankingPageData($rankingPageId){
		if(empty($rankingPageId)){
			return array();
		}
		$rankingCommonLib = $this->load->library(RANKING_PAGE_MODULE.'/rankingCommonLibv2');
		return $rankingCommonLib->getRankingPageData($rankingPageId);
	}

}