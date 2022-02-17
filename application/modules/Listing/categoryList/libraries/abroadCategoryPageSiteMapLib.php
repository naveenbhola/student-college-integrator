<?php
class abroadCategoryPageSiteMapLib {

	private $CI;
	
	function __construct() {
		$this->CI =& get_instance();
	}

	function getAbroadURLForSiteMap($type){
		switch ($type) {
			case 'examAcceptingAbroadCategoryPage':
				return $this->_getCategoryPageURL();
				break;

			case 'universitiesInCountryPage':
				return $this->_getUniversitiesInCountryPageURL();
				break;

			default : 
				return '';
				break;
		}
	}

	private function _getCategoryPageURL(){
		global $examAcceptedPagePattern,$listOfValidExamAcceptedCPCombinations,$listOfValidScoresForExamAcceptedCPCombinations;
		$this->abroadCategoryPageRequest = $this->CI->load->library('categoryList/abroadCategoryPageRequest');

		$this->abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
		foreach ($listOfValidExamAcceptedCPCombinations as $exam => $applicableCourses) {
			$courses = $applicableCourses['coursesApplicable'];
			foreach ($courses as  $course => $isAcceptingScore) {
				$examMaster = $this->abroadCommonLib->getAbroadExamsMasterList();
				$examInfo = reset(array_filter(array_map(function($a)use($exam){if($a['exam'] == $exam) return $a;},$examMaster)));

				$dataArray = array(
									'examId' =>$examInfo['examId'],
									'examName' => $exam,
									'examAcceptingCourseName' => $course,
									'createExamCategoryPageUrlFlag' => true,
									'examScore' => array()
								);
				$this->abroadCategoryPageRequest->setData($dataArray);
				$urls[] = $this->abroadCategoryPageRequest->getURL();

				if($isAcceptingScore){
					foreach ($listOfValidScoresForExamAcceptedCPCombinations[$exam] as $lowerLimit => $upperLimit) {
						$dataArray['examScore'] = array($lowerLimit,$upperLimit);
						$this->abroadCategoryPageRequest->setData($dataArray);
						$urls[] = $this->abroadCategoryPageRequest->getURL();
		
					}		
				}
			}
		}
		return $urls;
	}

	private function _getUniversitiesInCountryPageURL(){

		$this->CI->load->model('categoryList/abroadcategorypagemodel');
        $this->abroadCategoryPageModelObj  = new abroadcategorypagemodel();
        $result = $this->abroadCategoryPageModelObj->getUniversityIdForSiteMap();
        $this->AbroadCategoryPageRequest = $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        $countryData = $this->AbroadCategoryPageRequest->getSeoInfoForCountryPage(1);
        $urls[] = $countryData['url'];
        foreach ($result as $key => $value) {
        	$countryData = $this->AbroadCategoryPageRequest->getSeoInfoForCountryPage($value['countryId']);
        	$urls[] = $countryData['url'];
        }
        return $urls;
	}
}
?>
