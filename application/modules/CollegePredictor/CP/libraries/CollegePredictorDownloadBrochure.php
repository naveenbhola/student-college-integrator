<?php
class CollegePredictorDownloadBrochure{

	function __construct()
	{
		$this->CI = & get_instance();
	}

	function checkBrochureOnCourseInstituteAndUniversity($mainObj){
		$this->listingcommonlib = $this->CI->load->library('listingCommon/ListingCommonLib');
		$allCourseIds = array();$checkForDownloadBrochure= array();
		foreach($mainObj as $index=>$obj){
			if($obj->getShikshaCourseId() > 0){
				$allCourseIds[] = $obj->getShikshaCourseId();
			}
		}
		if(!empty($allCourseIds)){
			$checkForDownloadBrochure = $this->listingcommonlib->checkForDownloadBrochure($allCourseIds);
		}
		return $checkForDownloadBrochure;
	}

}
