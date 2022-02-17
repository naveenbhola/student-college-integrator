<?php

class AllCoursesRepository {
	function __construct($request) {
        $this->CI 		  = & get_instance();
        $this->request    = $request;
        $this->solrClient = $this->CI->load->library('search/Solr/SolrClient');
        $this->allCoursesPageLib = $this->CI->load->library('nationalCategoryList/AllCoursesPageLib');
        
        // get course repository with all dependencies loaded
        $this->CI->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $this->courseRepository = $courseBuilder->getCourseRepository();
    }

    function getFiltersAndCourses($instituteId) {
    	$solrResults = $this->getDataFromSolr($instituteId);
    	//solrResults => numOfCourses, filters, courseIds

        if($solrResults['numOfCourses'] == 0 || empty($solrResults['courseIds'])) {
            // _p('ZERO RESULT'); die;
        }

        //get details of institutes to show on tuple
        $data['institutes'] = array();
        if(!empty($solrResults['courseIds'])){
            $data['courseData'] = $this->getCourseData($solrResults['courseIds'],true);
        }
        //get details to help render view easily
        $data['institutes'] = $data['courseData']['institutes'];
        $data['totalCourseCount'] = $solrResults['numOfCourses'];
        $data['filters'] = $solrResults['filters'];
        $data['fieldAlias'] = $solrResults['fieldAlias'];
        $data['selectedFilters'] = $solrResults['selectedFilters'];
        if(isMobileRequest()){
            $selectedStream = (array)$this->request->getStream();
            if(!empty($selectedStream)){
                $data['selectedFilters']['stream'][$selectedStream[0]] = 'abc';
            }
        }
        
        unset($solrResults);
        return $data;
    }

    function getDataFromSolr($instituteId) {
        $solrRequestData = array();
        $solrRequestData['requestType'] = 'all_courses';
        $solrRequestData['popularCourses'] = $this->allCoursesPageLib->getPopularCourses($instituteId);
        if($this->request->isStreamClosedSearch()) {
            $solrRequestData['facetCriterion']['type'] = 'all_courses';
            $solrRequestData['facetCriterion']['id'] = 'with_stream';
        }
        else {
            $solrRequestData['facetCriterion']['type'] = 'all_courses';
            $solrRequestData['facetCriterion']['id'] = 'default';
        }

        //set filters to be applied
        $solrRequestData['filters'] = $this->request->getAppliedFilters();
        $solrRequestData['filters']['institute'][] = $instituteId;
        if($this->request->isStreamClosedSearch()) {
        	$solrRequestData['filters']['stream'] = $this->request->getStream();
        }
        $solrRequestData['sort_by'] = "view_count";
        if($this->request->getRequestFrom() == 'filterBucket' && isMobileRequest()){
            $solrRequestData['getFiltersOnly'] = true;
        }

        //get page details
		$solrRequestData['pageLimit'] = $this->request->getPageLimit();
		if(isMobileRequest()){
			$solrRequestData['pageLimit'] = $this->request->getPageLimit();
		}
		$solrRequestData['pageNum'] = $this->request->getCurrentPageNum();
        $solrRequestData['getFilters'] = $this->request->getFiltersFlag();
        $solrRequestData['getParentFilters'] = $this->request->getParentFiltersFlag();

        $solrResults = $this->solrClient->getFiltersAndCourses($solrRequestData);
        return $solrResults;
    }

    function getCourseData($courses,$returnCourseCount = false) {
        $finalResult = array();
        $courseCount =0 ;
    	$courseObjs['courseData'] = $this->courseRepository->findMultiple($courses, array('basic', 'location', 'eligibility'));

        foreach ($courses as $key => $value) {
            if(!empty($courseObjs['courseData'][$value])){
                $courseId = $courseObjs['courseData'][$value]->getId();
                if(!empty($courseId)){
                    $finalResult['courseData'][$value] = $courseObjs['courseData'][$value];
                    $courseCount++;
                }
            }
        }
        if($returnCourseCount){
            return array('institutes'=>$finalResult, 'numOfCourses' => $courseCount);
        }
        return $finalResult;
    }

    function getPageHeading($appliedFilters, $instituteObj, $instituteNameWithLocation, $secondaryName, $currentYear) {
        $appliedFilterCount = 0;
        $pageHeading = '';
        $seoTitle = '';
        $seoDescription = '';
        $this->CI->load->builder('listingBase/ListingBaseBuilder');
        $listingBase = new ListingBaseBuilder();

        if(!empty($appliedFilters['stream']) && count($appliedFilters['stream']) == 1) {
            $streamRepository = $listingBase->getStreamRepository();  
            $appliedFilterCount++;
            $streamId = reset($appliedFilters['stream']);
            $streamObjs = $streamRepository->findMultiple($appliedFilters['stream']);
            $filterName = $streamObjs[$streamId]->getName();    
        }

        if(!empty($appliedFilters['base_course']) && count($appliedFilters['base_course']) == 1) {
            $baseCourseRepository = $listingBase->getBaseCourseRepository();  
            $appliedFilterCount++;
            $baseCourseId = reset($appliedFilters['base_course']);
            $baseCourseObjs = $baseCourseRepository->findMultiple($appliedFilters['base_course']);
            $filterName = $baseCourseObjs[$baseCourseId]->getName();
        }
        $target = '';
        if(!isMobileRequest()) {
            $target = '_blank';
        }
        $collegeName = $instituteNameWithLocation;
        $collegeNameWithUrl = "<a class='name-course' href='".$instituteObj->getURL()."' target='".$target."'>".$collegeName." </a> Courses & Fees ".$currentYear;
        $collegeNameWithAdmissionUrl = "<a href='".$instituteObj->getAllContentPageUrl('admission')."' target='".$target."'>".$instituteObj->getName()." admission</a>";
        $pageHeading =  "<a class='name-course' href='".$instituteObj->getURL()."' target='".$target."'>".$collegeName." </a>".' '.$filterName.' Courses & Fees '.$currentYear;
        $pageHeadingWithoutUrl = $collegeName.' '.$filterName.' Courses & Fees '.$currentYear;
        
        $seoTitle = htmlentities($instituteNameWithLocation)." ".$filterName." Courses, Fees & Fee Structure ". $currentYear;
        
       if(!empty($secondaryName)){
            $seoDescription  = "Get ".htmlentities($instituteNameWithLocation)." ".$filterName." Courses & Fees of ".htmlentities($instituteNameWithLocation)." (".$secondaryName.") for ".$currentYear.". Find Fee Structure along with Placement Reviews, Cutoff & Eligibility";
        }
        else{
            $seoDescription  = "Get ".htmlentities($instituteNameWithLocation)." ".$filterName." Courses & Fees of ".htmlentities($instituteNameWithLocation)." for ".$currentYear.". Find Fee Structure along with Placement Reviews, Cutoff & Eligibility";
        }

        $seoScriptedText = "Get fees, placement reviews, exams required, cutoff & eligibility for all courses.";
        
        if($appliedFilterCount != 1) {
            $pageHeading = $collegeNameWithUrl;
            
            $seoTitle = '';
            $seoDescription = '';
            $pageHeadingWithoutUrl = '';
        }
       
        return array('pageHeading' => $pageHeading, 
                     'seoTitle' => $seoTitle, 
                     'seoDescription' => $seoDescription, 
                     'seoScriptedText' => $seoScriptedText,
                     'pageHeadingWithoutUrl' => $pageHeadingWithoutUrl
                    );
    }
}