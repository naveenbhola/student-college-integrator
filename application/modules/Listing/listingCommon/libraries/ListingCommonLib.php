<?php
class ListingCommonLib {

	function init() {
		$this->CI =& get_instance();
		$this->dao = $this->CI->load->model('listingCommon/listingcommonmodel');

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();


	    $this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();

	}
	

	/**
	* Function to get the Customized E-Brouchure Data(If present at course level, the course Brouhure else Primary Parent Brouchure)
	* @param : $listingType : institute,university(national),course
	* @param : Course Id integer (MANDATORY)
	* @param : Course Object(Optional)(o/p of find / findMultiple )
			 : Made Assumption that either Course Object for All Ids will be passed or for none will be passed
	*
	* @return : Array With Course Broucure Information
	*
	* Usage 1 : $this->lib->getCustomizedBrochureData('course',2364);
	* Output : Array
	*				(
	*			    [url] => google.com2
	*			    [year] => 2016
	*			    [size] => 12
	*			)
	*
	* Usage 2 : $this->lib->getCustomizedBrochureData('course',array(2364,123));
	* Output : Array
	*		(
	*		    [2364] => Array
	*		        (
	*		            [url] => http://shikshatest02.infoedge.com:80/mediadata/pdf/1469446808phpcqrAkg.pdf
	*		            [year] => 2016
	*		            [size] => 
	*		        )
	*
	*		    [250251] => Array
	*		        (
	*		            [url] => google.com2
	*		            [year] => 2016
	*		            [size] => 12
	*		        )
	*
	*		)
	*
	*/
	function getCustomizedBrochureData($listingType = 'course', $listingId = 0 ,$listingObject = null){
		$this->init();
		$brochureData = array();
		if(!empty($listingId)){
			if(is_array($listingId)){
				$brochureData = $this->_fetchBrochureForMultipleListings($listingType, $listingId,$listingObject);
			}else{
				$brochureData = $this->_fetchBrochureForSingleListings($listingType, $listingId, $listingObject);
			}	
		}
		return $brochureData;
	}


	
	/**
	* Function to get the view count for the Listing(Last 1 year)[ONLY FOR NATIONAL]
	* @param $listingType String(institute, course, university)
	* @param $listingsIds Array Of Integer
	*
	* @return Array with key as listingId , view count as listingType
	*
	* Usage: Calling = $this->lib->viewCountLastOneYear('institute',array(772,486,307));
	*		 Output = 	Array
	*					(
	*					    [307] => 3526
	*					    [486] => 22
	*					    [772] => 21
	*					)
	*
	*/

	public function listingViewCount($listingType='',$listingIds=array(),$durationInDays="365"){
		$this->init();
		if(empty($listingIds) || $listingIds == null || $listingType == ""){
			return array();
		}
		$type = array();
		if($listingType == "institute" || $listingType == "university"){

			$hashKey = "institutes_view_count";
			$type = array('institute_free','institute_paid');
		}else if($listingType == "course"){

			$hashKey    = "courses_view_count";
			$type = array("course_free","course_paid");
		}
		if(empty($type)) return array();

		// get view count data from redis
		$redis_client = PredisLibrary::getInstance();
		$viewCount = $redis_client->getMembersOfHashByFieldNameWithValue($hashKey,$listingIds);
		arsort($viewCount);

		// $viewCount = $this->dao->fetchListingViewCount($listingIds, $type,$durationInDays);
	
		return $viewCount;
	}


	/**
	* Function to get the Last Modification date for the listing(Only from National) -> Data Fetched from listings_main
	* @param $listingType String(institute, course, university)
	* @param $listingsIds Array Of Integer
	*
	* @return Array with key as listingId , view count as listingType
	*
	* Usage: Calling = $this->lib->getNationalListingsLastModifyDate('institute',array(772,486,307));
	*		 Output = 	Array
	*					(
	*					    [307] => 2016-09-29 20:56:39
	*					    [486] => 2008-03-28 02:29:50
	*					    [772] => 2014-02-07 13:08:09
	*					)
	*
	*
	*/
	public function getNationalListingsLastModifyDate($listingType = '',$listingIds = array()){

		$this->init();
		if(empty($listingIds) || $listingIds == null || $listingType == ""){
			return array();
		}
		//National University
		if($listingType == "university"){
			$listingType = "university_national";
		}
		$data = $this->dao->getLastModificationDate($listingType,$listingIds);
		
		return $data;
	}

	/**
	*	Helper Function for fetching institute Brouchure Data
	*/
	private function _fetchInstituteBrochure($instituteId = 0, $instituteObject=null){

		if($instituteId > 0){
			if(!isset($instituteObject)){
				$instituteObject = $this->instituteRepo->find($instituteId);
				$brochureData = array();
				$brochureData['url'] = $instituteObject->getBrochureURL();
				$brochureData['year'] = $instituteObject->getBrochureYear();
				$brochureData['size'] = $instituteObject->getBrochureSize();
				return $brochureData;
			}
		}else{
			return null;
		}
	}

	/**
	*	Helper Function for fetching course Brouchure Data
	*/
	private function _fetchCourseBrochure($courseId = 0, $courseObject=null){
		
		if($courseId > 0){
			if(!isset($courseObject)){
				$courseObject = $this->courseRepo->find($courseId);
				$courseObjectId = $courseObject->getId();
				if(empty($courseObjectId)) {
					return null;
				}
				$brochureData = array();
				$brochureData['url']  = (!$courseObject->isBrochureAutoGenerated()) ? $courseObject->getBrochureURL() : null;
				$brochureData['year'] = $courseObject->getBrochureYear();
				$brochureData['size'] = $courseObject->getBrochureSize();
		
				if(!isset($brochureData['url']) || empty($brochureData['url'])){
					$instituteId = $courseObject->getInstituteId();
					$brochureData = $this->_fetchInstituteBrochure($instituteId);
					if(empty($brochureData['url'])) {
						$brochureData['url'] = $courseObject->getBrochureURL();
						$brochureData['year'] = $courseObject->getBrochureYear();
						$brochureData['size'] = $courseObject->getBrochureSize();
					}
				}
				return $brochureData;
			}
		}else{
			return null;
		}
	}

		/**
	*	Helper Function for fetching course Brouchure Data
	*/
	private function _fetchBrochureForSingleListings($listingType, $listingId, $listingObject){

		$brochureData = array();
		switch ($listingType) {
			case 'course':
				$brochureData = $this->_fetchCourseBrochure($listingId, $listngObject);
				break;
			case 'institute':
			case 'university':
				$brochureData = $this->_fetchInstituteBrochure($listingId, $listngObject);
				break;
			
		}
		return $brochureData;
	}

	/**
	*	Helper Function for fetching course Brouchure Data
	*/
	private function _fetchBrochureForMultipleListings($listingType, $listingId,$listingObject){
		
		$brochureData = array();
		switch ($listingType) {
			case 'course':
				$brochureData = $this->_fetchCourseMultipleBrochure($listingId,$listingObject);
				break;
			case 'institute':
			case 'university':
				$brochureData = $this->_fetchInstituteMultipleBrochure($listingId,$listingObject);
				break;

		}
		return $brochureData;
	}


	/**
	*	Helper Function for fetching course Brouchure Data
	*/
	private function _fetchInstituteMultipleBrochure($listingIds,$listingObject=null){
        $listingIds = array_filter($listingIds);
        if(empty($listingIds)) return array();

        if(!empty($listingObject) && is_array($listingObject)){
			$instituteObjectArray = $listingObject;
		}
		else{
			$instituteObjectArray = $this->instituteRepo->findMultiple($listingIds);
		}

		$result = array();
		foreach ($instituteObjectArray as $key => $instituteObject) {
			$instituteId = $instituteObject->getId();
			if(empty($instituteId)) {
				continue;
			}
			$brochureData = array();
			$brochureData['url'] = $instituteObject->getBrochureURL();
			$brochureData['year'] = $instituteObject->getBrochureYear();
			$brochureData['size'] = $instituteObject->getBrochureSize();
			if(!empty($brochureData['url'])) {
				$result[$key] = $brochureData;
			} else {

			$result[$key] = null;
			}
		}
		return $result;
	}


	/**
	*	Helper Function for fetching course Brouchure Data
	*/
	private function _fetchCourseMultipleBrochure($listingIds,$listingObject){
	
		if(!empty($listingObject) && is_array($listingObject)){
			$courseObjectsArray = $listingObject;
		}
		else{
			$courseObjectsArray = $this->courseRepo->findMultiple($listingIds);	
		}
		
		foreach ($listingIds  as $listingId) {
			$courseObject = $courseObjectsArray[$listingId];
			$result[$listingId] = null;
			if(!is_object($courseObject)) {
				continue;
			}
			$courseObjectId = $courseObject->getId();
			if(empty($courseObjectId)) {
				continue;
			}
			$brochureData = array();
			$brochureData['url'] = $courseObject->getBrochureURL();
			$brochureData['year'] = $courseObject->getBrochureYear();
			$brochureData['size'] = $courseObject->getBrochureSize();
			if(!empty($brochureData['url']) && !$courseObject->isBrochureAutoGenerated()){
				$result[$listingId] = $brochureData;		
			}
			else{
				$remainList[$courseObject->getInstituteId()] = $listingId;
			}
		}
	
		if(!empty($remainList)){
			$remainListData = $this->_fetchInstituteMultipleBrochure(array_keys($remainList));
			foreach ($remainList as $instituteId => $courseId) {
				$result[$courseId] = $remainListData[$instituteId];

				//fallback where brochure is not present at institute then lookup at course autogenerated
				if(empty($result[$courseId]['url'])) {
					$courseObject = $courseObjectsArray[$listingId];
					if(!is_object($courseObject)) {
						continue;
					}
					$courseObjectId = $courseObject->getId();
					if(empty($courseObjectId)) {
						continue;
					}
					$brochureData = array();
					$brochureData['url'] = $courseObject->getBrochureURL();
					$brochureData['year'] = $courseObject->getBrochureYear();
					$brochureData['size'] = $courseObject->getBrochureSize();
					if(!empty($brochureData['url'])){
						$result[$courseId] = $brochureData;		
					}
				}
			}
		}


		return $result;
	}

	/*
		Output 
		Array
			(
			    [250162] => 1
			    [251085] => 
			)
	 */
	public function checkForDownloadBrochure($courseIds){
		$coursesWithBrochures = $this->getCustomizedBrochureData('course', $courseIds);
		$returnData = array();
		foreach ($coursesWithBrochures as $courseId => $brochureData) {
			$returnData[$courseId] = (empty($brochureData)) ? false : true;
		}
		return $returnData;
	}


	public function createTocForContent($instituteId, $content) {
		$this->CI =& get_instance();
		$this->listingcommonmodel = $this->CI->load->model('listingCommon/listingcommonmodel');
		$this->domDocumentLib = $this->CI->load->library('DomDocumentLib');

		switch ($content) {
			case 'admission':
				$wikiContentArr = $this->listingcommonmodel->getAdmissionWikiContent($instituteId);
				break;
			
			default:
				$wikiContentArr = $this->listingcommonmodel->getAdmissionWikiContent($instituteId);
				break;
		}

        foreach ($wikiContentArr as $listingId => $wikiContent) {
        	error_log("\n TOC generation going on for institute - ".$listingId, 3, "/tmp/log_toc_generation.log");
            if (empty($wikiContent)) {
                continue;
            }

            $wikiConvertedValue = array();

            $wikiResult = $this->domDocumentLib->getTagsInDynamicHtmlContent($wikiContent, "admission", 'h2', 'table');
			
            //insert toc and updated html string in database
            switch ($content) {
				case 'admission':
					$this->listingcommonmodel->updateAndInsertAdmissionWikiData($listingId, $wikiResult);
					break;
				
				default:
					$this->listingcommonmodel->updateAndInsertAdmissionWikiData($listingId, $wikiResult);
					break;
			}
        }
	}
}