<?php

class InstituteDetailLib{
	function __construct() {
		$this->CI =& get_instance();

		// load dependencies
		$this->institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");
		$this->listingCommonLib      = $this->CI->load->library("listingCommon/ListingCommonLib");
		$this->courseDetailLib       = $this->CI->load->library("nationalCourse/CourseDetailLib");
	}

	/**
	 * Method to get all direct or indirect(as specified) courses of a given institute/university
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-10-04
	 * @param  [type]     $listingId             [institute/univerity id]
	 * @param  [type]     $listingType           [institute/univerity]
	 * @param  string     $directOrAllCourses    [either 'all' or 'direct']
	 * @param  array      $excludeInstituteTypes [description]
	 * @return [type]                            [result containing list of courses]
	 */
	function getInstituteCourseIds($listingId, $listingType, $directOrAllCourses = 'all', $excludeInstituteTypes = array(), $excludeSatellite = true){
		// replacing old api with the new api
		$results = $this->getAllCoursesForInstitutes($listingId, $directOrAllCourses, $excludeSatellite);
		return $results;

		$childrenInstitutes     = array();
		$childrenInstitutesType = array();

		// get direct/indirect institute ids
		if($directOrAllCourses == 'all'){

			$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
			$childData = $this->institutepostingmodel->getChildData($listingId, $listingType, true, $excludeSatellite, $excludeInstituteTypes);

			// in case no child is present
			if(!is_array($childData[0])){
				$childData = array($childData);
			}

			$childrenInstitutes = array();
			foreach ($childData as $hierarchy) {
				$hierarchy = (array)$hierarchy;
				foreach ($hierarchy as $instituteIdStr) {
					$idArr = explode("_", $instituteIdStr);
					$childrenInstitutes[] = $idArr[1];
					$childrenInstitutesType[$idArr[1]] = $idArr[0];
				}
			}
			$childrenInstitutes = array_values(array_unique($childrenInstitutes));
		}
		else if($directOrAllCourses == 'direct'){
			$childrenInstitutes = array($listingId);	
		}

		// get all courses
		$institutemodel = $this->CI->load->model("nationalInstitute/institutemodel");
		$courses        = $institutemodel->getCoursesOfInstitutes($childrenInstitutes, 'name');

		$coursesList = array();
		foreach ($courses as &$value) {
			$value       = array_unique($value);
			$value       = array_values($value);
			$coursesList = array_merge($coursesList,$value);
		}
		$coursesList = array_values(array_unique($coursesList));

		// prepare result array
		$result                         = array();
		$result['courseIds']            = $coursesList;
		$result['instituteWiseCourses'] = $courses;
		$result['type']                 = $childrenInstitutesType;
		return $result;
	}

	public function getAllCoursesForMultipleInstitutes($instituteIds,$directOrAllCourses="all", $excludeSatellite = true, $disableCache = true){
		if(empty($instituteIds)) return;
		$this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');  
		if(!$disableCache) {
            $instituteCoursesFromCache = $this->CI->nationalinstitutecache->getInstituteCourses($instituteIds);
            $foundInCache = array_keys($instituteCoursesFromCache);
        	$remainingInstituteIds = array_diff($instituteIds,$foundInCache);
        }
        else {
        	$remainingInstituteIds = $instituteIds;
        }
        /*_P($remainingInstituteIds); die;*/
        if(empty($remainingInstituteIds) && !empty($instituteCoursesFromCache)) {
        	return $instituteCoursesFromCache;
        }
        
		$result = $this->institutedetailsmodel->getAllCoursesForInstitutesFromFlatTable($remainingInstituteIds, $excludeSatellite);
		$finalResult = array();
		$excludeHierachies = array();

		foreach ($result as $key => $value) {
			// DIRECT/ALL Courses
			if($directOrAllCourses == "direct" && $value['hierarchy_parent_id']!=$value['primary_parent_id']) continue;

			// EXCLUDE SATELLITE LIST and universities
			if($excludeSatellite  && $value['hierarchy_parent_id'] != $value['primary_parent_id'] && ( $value['primary_parent_type'] == "university" || $value['primary_is_satellite'] == 1)){
    			$excludeHierachies[$value['hierarchy_parent_id']][] = $value['primary_parent_id'];
    		}

    		// POPULATE THE DATA 
    		$finalResult[$value['hierarchy_parent_id']]['courseIds'][] = $value['course_id'];	
    		$finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']][] = $value['course_id'];
    		$finalResult[$value['hierarchy_parent_id']]['type'][$value['primary_parent_id']] = $value['listing_type'];

    		$finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']] = $this->getUniqueNonZeroElementsFromArrays($finalResult[$value['hierarchy_parent_id']]['instituteWiseCourses'][$value['primary_parent_id']]);

    		if($value['is_dummy']){
    			$finalResult[$value['hierarchy_parent_id']]['dummy'][] = $value['primary_parent_id'];
    		}
		}
		foreach ($finalResult as $hierarchyParentId => $data) {
			$finalResult[$hierarchyParentId]['courseIds'] = $this->getUniqueNonZeroElementsFromArrays($finalResult[$hierarchyParentId]['courseIds']);
			$finalResult[$hierarchyParentId]['instituteWiseCourses'] = array_filter($finalResult[$hierarchyParentId]['instituteWiseCourses']);
		}

		// _p($excludeHierachies);die;
		if(!empty($excludeHierachies)){
			$allHierarchies = array();
			foreach ($excludeHierachies as $hierarchyParentId => $data) {
				$allHierarchies = $this->getUniqueNonZeroElementsFromArrays($allHierarchies,$data);
			}
			$result = $this->institutedetailsmodel->getAllCoursesForInstitutesFromFlatTable($allHierarchies);
			$institutesToExcludeMapping = array();
			foreach ($result as $row) {
				$institutesToExcludeMapping[$row['hierarchy_parent_id']][] = $row['primary_parent_id'];
			}
			foreach ($excludeHierachies as $hierarchyParentId => $hierarchyInstitutes) {
				foreach ($hierarchyInstitutes as $instituteId) {
					if(!empty($institutesToExcludeMapping[$instituteId])){
						$excludeHierachies[$hierarchyParentId] = $this->getUniqueNonZeroElementsFromArrays($excludeHierachies[$hierarchyParentId],$institutesToExcludeMapping[$instituteId]);
					}
				}
			}

			foreach ($excludeHierachies as $hierarchyParentId => $institutes) {
				foreach ($institutes as $instituteId) {
					$finalResult[$hierarchyParentId]['courseIds'] = array_values(array_diff($finalResult[$hierarchyParentId]['courseIds'],(array)$finalResult[$hierarchyParentId]['instituteWiseCourses'][$instituteId]));
					unset($finalResult[$hierarchyParentId]['instituteWiseCourses'][$instituteId]);
					unset($finalResult[$hierarchyParentId]['type'][$instituteId]);
				}
			}
		}
		
		$instituteCourses = array();
		foreach ($instituteIds as $id) {
			$instituteCourses[$id] = (!empty($instituteCoursesFromCache[$id])) ? $instituteCoursesFromCache[$id] : $finalResult[$id];
		}
		foreach($remainingInstituteIds as $key){
			$instituteIdsToStore[$key] = 1;
		}
		if(!$disableCache) {
			$this->CI->nationalinstitutecache->storeInstituteCourses($finalResult,$instituteIdsToStore);
		}
		return $instituteCourses;
	}
	function getAllAffiliatedCoursesForUniversities($universityId, $universityScope = 'domestic'){
		$affiliatedCourseIds = $this->institutedetailsmodel->getAffiliatedCoursesForUniversity(array($universityId), $universityScope);
		return $affiliatedCourseIds;
	}
	private function getUniqueNonZeroElementsFromArrays($arr1,$arr2 = array()){
		$temp = array();
		foreach ($arr1 as $value) {
			$temp[$value] = $value;
		}
		foreach ($arr2 as $value) {
			$temp[$value] = $value;
		}
		unset($temp[0]);
		return array_values($temp);
	}


	 function getAllCoursesForInstitutes($instituteId,$directOrAllCourses="all", $excludeSatellite = true, $disableCache = true){
    	if(empty($instituteId)) return;
    	
		if(count($instituteId)>1)
		{
    		$result = $this->getAllCoursesForMultipleInstitutes($instituteId,$directOrAllCourses, $excludeSatellite, $disableCache);
    		return $result;
		}
		else
		{
			if(is_array($instituteId)){
				$result = $this->getAllCoursesForMultipleInstitutes($instituteId,$directOrAllCourses, $excludeSatellite, $disableCache);
				    return $result[$instituteId[0]];
			}else{
				$result = $this->getAllCoursesForMultipleInstitutes(array($instituteId),$directOrAllCourses, $excludeSatellite, $disableCache);
			    return $result[$instituteId];
			}
			
		}

    	$result = $this->institutedetailsmodel->getAllCoursesForInstitutesFromFlatTable(array($instituteId), $excludeSatellite);
    	$finalResult = array();
    	$excludeHierachies = array();
    	foreach ($result as $key => $value) {

			// DIRECT/ALL Courses
   			if($directOrAllCourses == "direct" && $value['hierarchy_parent_id']!=$value['primary_parent_id']) continue;

   			// EXCLUDE SATELLITE LIST
   			if($excludeSatellite  && $value['hierarchy_parent_id'] != $value['primary_parent_id'] && ( $value['primary_parent_type'] == "university" || $value['primary_is_satellite'] == 1)){
    			$excludeHierachies[] = $value['primary_parent_id'];
    		}

			// POPULATE THE DATA 
			$finalResult['courseIds'][] = $value['course_id'];	
			$finalResult['instituteWiseCourses'][$value['primary_parent_id']][] = $value['course_id'];
			$finalResult['type'][$value['primary_parent_id']] = $value['listing_type'];


			// REMOVE BLANK VALUES
    		$finalResult['instituteWiseCourses'][$value['primary_parent_id']] = array_filter(array_unique($finalResult['instituteWiseCourses'][$value['primary_parent_id']]));


    		if($value['is_dummy'])
    			$finalResult['dummy'][] = $value['primary_parent_id'];

    	}

		$finalResult['courseIds'] = array_filter(array_unique($finalResult['courseIds']));
		$finalResult['instituteWiseCourses'] = array_filter($finalResult['instituteWiseCourses']);


    	// EXCLUDING SATELLITE 
    	if(!empty($excludeHierachies)){
    		$result = $this->institutedetailsmodel->getAllCoursesForInstitutesFromFlatTable($excludeHierachies);
			foreach ($result as $key => $value) {
				$coursesIdsToExclude[] = $value['course_id'];
				unset($finalResult['instituteWiseCourses'][$value['primary_parent_id']]);
				unset($finalResult['type'][$value['primary_parent_id']]);
			}
			if(!empty($coursesIdsToExclude)){
				$finalResult['courseIds'] = array_values(array_diff($finalResult['courseIds'], $coursesIdsToExclude));		
			}
    	}
    	
    	
    	return $finalResult;
    	
    }

 

	/**
	 * Method to get all institutes of a given university
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2016-10-04
	 * @param  [type]     $listingId             [institute/univerity id]
	 * @param  [type]     $listingType           [institute/univerity]
	 * @param  array      $excludeInstituteTypes [description]
	 * @return [type]                            [result containing list of courses]
	 */
	function getUniversityInstituteIds_old($listingId){

		$childrenInstitutes     = array();
	
		$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');

		// Get all institutes/universities under this university
		// Note : do not include institutes under a 'college' type institute
		$childData = $this->institutepostingmodel->getChildData($listingId, 'university', true, true, array(), $excludeInstituteTypesChildren = array('college'));

		// in case no child is present
		if(!is_array($childData[0])){
			$childData = array($childData);
		}

		// seperate institutes from universities
		$childrenInstitutes = array();
		$childrenUniversities = array();
		foreach ($childData as $hierarchy) {
			$hierarchy = (array)$hierarchy;
			foreach ($hierarchy as $instituteIdStr) {
				$idArr = explode("_", $instituteIdStr);

				if($idArr[0] == 'institute'){
					$childrenInstitutes[] = $idArr[1];	
				}
				else if($idArr[0] == 'university'){
					$childrenUniversities[] = $idArr[1];
				}
			}
		}

		$childrenInstitutes   = array_values(array_unique($childrenInstitutes));
		$childrenUniversities = array_values(array_unique($childrenUniversities));
		
		// prepare result array
		$result                  = array();
		$result['instituteIds']  = $childrenInstitutes;
		$result['universityIds'] = $childrenUniversities;

		return $result;
	}

	function getUniversityInstituteIds($listingId, $preFetchedCourseIds = array()){

		$childrenInstitutes = array();

		if(empty($preFetchedCourseIds))
			$result = $this->getAllCoursesForInstitutes($listingId);
		else
			$result = $preFetchedCourseIds;

		// exclude the same institute from children list
		unset($result['type'][$listingId]);

		$childrenInstitutes = array_keys($result['type']);
		
		$childrenInstitutes = array_values(array_unique($childrenInstitutes));

		$childrenInstitutes = array_diff($childrenInstitutes, (array)$result['dummy']);

		$instituteTypes = $this->institutedetailsmodel->getInstituteTypes($childrenInstitutes);

		$collegeTypeInstitutes = array();
		foreach ($instituteTypes as $key => $value) {
			if($value == 'college')
				$collegeTypeInstitutes[] = $key;
		}

		$institutesToBeExcludedIds = array();
		if(!empty($collegeTypeInstitutes)){
			$institutesToBeExcluded = $this->institutedetailsmodel->getAllInstitutesForInstitutesFromFlatTable($collegeTypeInstitutes, 
				true);
			foreach ($institutesToBeExcluded as $value) {
				$institutesToBeExcludedIds[] = $value['primary_parent_id'];
			}
			$institutesToBeExcludedIds = array_values(array_unique($institutesToBeExcludedIds));
		}
		$finalInsittutesIds = array_diff($childrenInstitutes, $institutesToBeExcludedIds);
		$finalInsittutesIds = array_values($finalInsittutesIds);

		return $finalInsittutesIds;
	}

	public function getDescendantInstitutes($listingId, $listingType, $excludeInstituteTypes = array(), $excludeSatellite = true){
		$childrenInstitutes     = array();
		$childrenInstitutesType = array();

		$this->institutepostingmodel = $this->CI->load->model('nationalInstitute/institutepostingmodel');
		$childData = $this->institutepostingmodel->getChildData($listingId, $listingType, true, $excludeSatellite, $excludeInstituteTypes);

		$childrenInstitutes = array();
		foreach ($childData as $hierarchy) {
			$hierarchy = (array)$hierarchy;
			foreach ($hierarchy as $instituteIdStr) {
				$idArr = explode("_", $instituteIdStr);
				$childrenInstitutes[] = $idArr[1];
				$childrenInstitutesType[$idArr[1]] = $idArr[0];
			}
		}
		$childrenInstitutes = array_values(array_unique($childrenInstitutes));

		return array('institutes' => $childrenInstitutes,'instituteType' => $childrenInstitutesType);
	}

	function getFlagshipCourseId($listingId, $instituteLocationId){
		$directCourses = $this->institutedetailsmodel->getDirectCoursesForInstitute($listingId,$instituteLocationId );
		if(!empty($directCourses)){
			$orderOneCourses = $this->institutedetailsmodel->getOrderOneCourses($directCourses);

			if(!empty($orderOneCourses)){
				$orderOneCourses = $this->getCourseViewCount($orderOneCourses);
				$flaghshipCourse = reset(array_keys($orderOneCourses));
			} else {
				$directCourses = $this->getCourseViewCount($directCourses);
				$flaghshipCourse = reset(array_keys($orderOneCourses));
			}
		}
		return $flaghshipCourse;
	}

	function getInstitutePageCourseWidgetData($instituteObj, $instituteCurrentLocation, $isMultilocation = false, $popularCourseLimit = 5, $featuredCourseLimit = 5, $source='desktop', $preFetchedCourseIds = array() ,$disableCache = false){

		$this->CI->benchmark->mark('preparing_institute_course_widget_start');
		
		$categoryPageLinksLimit = 12;
		$rankingPageLinksLimit = 8;
		$categoryPageResultCount= 2;
		$result                 = array();
		$allCourseList          = array();
		$locationsMappedToCourse= array();
		$courseViewCount = array();

		$listingId = $instituteObj->getId();
		$listingType = $instituteObj->getType();
		// get all courses
		//if(empty($preFetchedCourseIds)){
			$courseList    = $this->getInstituteCourseIds($listingId, $listingType);

			$affiliatedCourseIds = $this->getAllAffiliatedCoursesForUniversities($listingId);
			$courseList = array_unique(array_merge($courseList['courseIds'], $affiliatedCourseIds));
		

		
		$allCourseList = $courseList;
		//_P($allCourseList);die();
		
	    if(empty($allCourseList)){
 			show_404();
 		}
		$instituteLocationId     = $instituteCurrentLocation->getLocationId();
		$eligibleLocationCourses = array();

		if($isMultilocation && $listingType != 'university'){
	
			$this->CI->benchmark->mark('preparing_institute_course_widget::get_courses_by_location_start');

			$eligibleLocationCourses     = $this->institutedetailsmodel->getCoursesHavingLocations($courseList, $instituteLocationId);
			$locationsMappedToCourse = $this->institutedetailsmodel->getUniqueCoursesLocations($courseList);

			$this->CI->benchmark->mark('preparing_institute_course_widget::get_courses_by_location_end');

			// foreach ($courseWiseLocations as $courseId => $locationIds) {
			// 	if(in_array($instituteLocationId, $locationIds)){
			// 		$eligibleLocationCourses[] = $courseId;
			// 	}
			// }

			$eligibleLocationCourses = array_values(array_unique($eligibleLocationCourses));

			$courseList    = $eligibleLocationCourses;
			$allCourseList = $eligibleLocationCourses;
		}

		// if(!$instituteCurrentLocation->isMainLocation()){

			
		// }
		
		$this->CI->benchmark->mark('preparing_institute_course_widget::get_sticky_courses_start');
		// get sticky courses
		$stickyCourses = $this->institutedetailsmodel->getInstituteStickyCourses($listingId, $listingType);

		$this->CI->benchmark->mark('preparing_institute_course_widget::get_sticky_courses_end');

		$this->CI->benchmark->mark('preparing_institute_course_widget::get_course_viewcount_start');
		// get view count of courses 
		$courseList = $this->getCourseViewCount($courseList);
		$this->CI->benchmark->mark('preparing_institute_course_widget::get_course_viewcount_end');
		$courseViewCount = $courseList;
		$sortedCourseList = array_keys($courseViewCount);
		// 1. popular courses
		$popularCourseList     = array();
		$popularStickyCourses  = array();
		$featuredCourseList    = array();
		$featuredStickyCourses = array();

		// if($stickyCourses['popular']){
		// 	foreach ($stickyCourses['popular'] as $value) {
		// 		$popularStickyCourses[] = $value['entityId'];
		// 	}
		// 	$popularStickyCourses = array_filter($popularStickyCourses);
		// }
		if($stickyCourses['featured']){
			foreach ($stickyCourses['featured'] as $value) {
				$featuredStickyCourses[] = $value['entityId'];
			}
			$featuredStickyCourses = array_filter($featuredStickyCourses);
		}

		// $popularStickyCourses = array_intersect($popularStickyCourses, array_keys($courseList));
		// $popularCourseList    = $popularStickyCourses;
		// $popularStickyCourses = array_slice($popularStickyCourses, 0, $popularCourseLimit);

		// // remove the courses that are put in sticky popular courses from course list
		// foreach ($popularStickyCourses as $value) {
		// 	unset($courseList[$value]);
		// }

		// $remainingPopularCourse = array_keys(array_slice($courseList, 0, ($popularCourseLimit-count($popularStickyCourses)), true));
		// $popularCourseList      = array_merge($popularCourseList, $remainingPopularCourse);
		// $popularCourseList		= array_slice($popularCourseList, 0, $popularCourseLimit);

		// // remove the courses that are put in popular courses from course list
		// foreach ($remainingPopularCourse as $value) {
		// 	unset($courseList[$value]);
		// }


		// if($source == 'desktop'){
		// 	// if only 1 course is left in remaining course list, then put it in popular bucktet
		// 	if(count($courseList) == 1){
		// 		$lastCourse = array_keys($courseList);
		// 		$popularCourseList[] = $lastCourse[0];
		// 		$courseList = array();
		// 	}
		// }

		$result['showMoreCoursesSection']  = count($courseList) > 0 ? true : false;
		$result['remainingCourses']        = array_keys($courseList);
		$result['locationsMappedToCourse'] = $locationsMappedToCourse;
		$tempCourseList = $courseList;

		$instituteHasPaidCourse = false;
		$allPaidCourses           = $this->institutedetailsmodel->checkPaidCourses($sortedCourseList);
		if(!empty($allPaidCourses)) {
			$instituteHasPaidCourse = true;
		}
		
		//flagshipCourse
		$flaghshipCourse = $this -> getFlagshipCourseId($listingId, $instituteLocationId);

		// 2. featured courses
		if($sortedCourseList){
			$featuredCourseList    = array_intersect( $featuredStickyCourses,$sortedCourseList);
			 if(sizeof($featuredCourseList) < $featuredCourseLimit ){
				$coursesTobeAdded = $featuredCourseLimit-sizeof($featuredCourseList);
			 	//add paid courses
			 	$sortedCourseList = array_diff($sortedCourseList,$featuredCourseList);
			 	$paidCourses 	= array_intersect($sortedCourseList, $allPaidCourses);
			 	$count = 0;
				foreach ($sortedCourseList as $key=>$value) {
					if(in_array($value, $paidCourses) && $count < $coursesTobeAdded){
						$featuredCourseList[]=$value;
						$count++;
					}
				}
				$count = 0;
				$coursesTobeAdded = $featuredCourseLimit-sizeof($featuredCourseList);
				if( $coursesTobeAdded >0){
					$sortedCourseList = array_diff($sortedCourseList,$featuredCourseList);
					foreach ($sortedCourseList as $key => $value) {
						if($count == $coursesTobeAdded){
							break;
						}
						$featuredCourseList[]=$value;
						$count++;
					}
				}
			}
		}
		$result['flaghshipCourse'] = $flaghshipCourse;
		if(empty($featuredCourseList)){
 			show_404();
 		}
		
		// if($source == 'desktop'){					
		// 	if(empty($featuredCourseList) && count($popularCourseList) == 6 && count($courseList) > 0){
		// 		$newRemainingCourses = array_slice($popularCourseList, -1, 1, false);
		// 		array_pop($popularCourseList);								
		// 		$tempCourseList      = $newRemainingCourses;				
		// 	}			
		// 	if(count($featuredCourseList)==3 && count($popularCourseList)==6){
		// 		// prevent 4th row
		// 		if(count($allCourseList)>9){
		// 			$newRemainingCourses = array_slice($featuredCourseList, -1, 1, false);
		// 			array_pop($featuredCourseList);
		// 			$tempCourseList[] = $newRemainingCourses;				
		// 		}
		// 	}
			// // if 6P + 3F, where only 2 are sticky, 3rd only paid, don't show all 9, unset paidCourses
			// if(count($featuredCourseList)==2 && count($popularCourseList)==6 && count($allCourseList)==9 && count($paidCourses)==1){
			// 	foreach ($paidCourses as $paidCourseId) {
			// 		$featuredCourseList[] = $paidCourseId;
			// 		unset($courseList[$paidCourseId]);
			// 		unset($tempCourseList[$paidCourseId]);
			// 	}
			// }
		//}

		// if($source == 'mobile' || $source == 'desktop'){			
		// 	$result['remainingCourses']        = array_keys($tempCourseList);
		// 	// unset($tempCourseList[$key]);
		// }
		$this->CI->benchmark->mark('preparing_institute_course_widget::get_all_courses_start');
		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder          = new CourseBuilder();
		$courseRepository = $builder->getCourseRepository();
		$popularCourseObjects = array();


		// 3. get all base courses and streams of all courses
		$baseCourseIds        = array();
		$baseCourseObjects    = array();
		$streamIds            = array();
		$streamObjects        = array();
		$countWiseStreams     = array();
		$countWiseSubStreams  = array();
		$countWiseBaseCourses = array();
		$subStreamInfo        = array();
		$baseCourseInfo       = array();
		$mbaCourseIds 		  = array();
		$specializationIds    = array();
		$subStreamIds	      = array();
		$coursesOfferedList	  = array();
		$baseCourseWiseCount = array();
        $streamWiseCount = array();
		$mbaBaseCourseId	  = 101;
		$show404 = false;

		$coursesOfferedList = array_merge($popularCourseList,$featuredCourseList);
		$coursesOfferedList = array_unique($coursesOfferedList);
		
	
			$cachedData = $this->CI->nationalinstitutecache->getInstituteCourseWidgetNew($listingId);
			$cachedData = json_decode($cachedData,true);
			if(empty($cachedData)){
				$show404 = true;
			}
			else{
				$baseCourseIds = $cachedData['baseCourseIds'];
				
				$specializationIds = $cachedData['specializationIds'];
				$subStreamIds = $cachedData['subStreamIds'];
				$streamIds = $cachedData['streamIds']; 
				$mbaCourseIds = $cachedData['mbaCourseIds'];
				$subStreamInfo = $cachedData['subStreamInfo'];
				foreach ($baseCourseIds as $key => $baseCourseId) {
					$countWiseBaseCourses[$baseCourseId]++;
				}
				foreach ($streamIds as $key => $stream) {
					$countWiseStreams[$stream]++;
				}
				foreach ($subStreamIds as $key => $subStreamId) {
					$countWiseSubStreams[$subStreamId]++;
				}
			}
		
		//_P($baseCourseIds);die();
		// getting all courses because of all base course/stream section
		
			$allCourses  = $courseRepository->findMultiple($featuredCourseList , array('basic'), false, false);	
		

		arsort($countWiseStreams);
		arsort($countWiseSubStreams);
		arsort($countWiseBaseCourses);
		$this->CI->benchmark->mark('preparing_institute_course_widget::get_all_courses_end');

		$this->CI->benchmark->mark('preparing_institute_course_widget::category_links_interlinking_start');

		$this->CI->load->library("nationalCategoryList/NationalCategoryPageLib");
		$nationalCategoryPageLib = new NationalCategoryPageLib();

		$this->CI->load->builder("listingBase/ListingBaseBuilder");
		$listingBaseBuilder = new ListingBaseBuilder();
		$baseCourseRepo = $listingBaseBuilder->getBaseCourseRepository();
		$streamRepo     = $listingBaseBuilder->getStreamRepository();	
		$subStreamRepo  = $listingBaseBuilder->getSubstreamRepository();	

		$categoryPageLinks = array();
		$streamObjects     = array();
		$subStreamObjects  = array();
		$baseCourseObjects = array();
		$cityId            = $instituteCurrentLocation->getCityId();
		$stateId           = $instituteCurrentLocation->getStateId();
		$cityName          = $instituteCurrentLocation->getCityName();
		$stateName          = $instituteCurrentLocation->getStateName();

		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder    = new LocationBuilder;
		$locationRepo = $locationBuilder->getLocationRepository();

		$cityObj = $locationRepo->findCity($cityId);
		$virtualCityId = $cityObj->getVirtualCityId();
		$cityTier = !empty($virtualCityId) ? 1 : $cityObj->getTier();

		if($baseCourseIds || $streamIds){
			if($baseCourseIds){
				$baseCourseObjects = $baseCourseRepo->findMultiple($baseCourseIds);
			}
			if($streamIds){
				$streamObjects = $streamRepo->findMultiple($streamIds);
			}
			if($subStreamIds){
				$subStreamObjects = $subStreamRepo->findMultiple($subStreamIds);
			}
		}

		

		$this->nationalinstitutecache = $this->CI->load->library('nationalInstitute/cache/NationalInstituteCache');  
		$categoryPageLinksCache = $this->nationalinstitutecache->getCategoryLinksForListings($listingId);

		//get category Page links from redis for listing id
		if(!empty($categoryPageLinksCache))
		{
			$categoryPageLinksCache = json_decode($categoryPageLinksCache);
			foreach ($categoryPageLinksCache as $categoryOb) {
				$categoryPageLinks[] = array('url' => $categoryOb->url,'title' => $categoryOb->title);
			}
		}
		else
		{
			//if category page links are not stored in redis, then get links from db and store it into redis

				// for stream
			foreach ($countWiseStreams as $streamId => $count) {

				if(count($categoryPageLinks) >= $categoryPageLinksLimit )
					continue;

				$url = $nationalCategoryPageLib->getUrlByParams(
													 array('stream_id'=>$streamId, 
														   'state_id' =>$stateId,
														   'city_id' => $cityId,
														   'min_result_count' => $categoryPageResultCount));	
				if($url){
					if(!$streamObjects[$streamId]){
						$streamObjects[$streamId] = $streamRepo->find($streamId);
					}
					$streamName = $streamObjects[$streamId]->getAlias() ? $streamObjects[$streamId]->getAlias() : $streamObjects[$streamId]->getName();
					$categoryPageLinks[] = array('url'=>$url, 'title' => $streamName.' colleges in '.$cityName);
				}
			}
			// for sub-stream
			foreach ($countWiseSubStreams as $substreamId => $count) {

				if(count($categoryPageLinks) >= $categoryPageLinksLimit )
					continue;

				$url = $nationalCategoryPageLib->getUrlByParams(
													 array('stream_id'=>$subStreamInfo[$substreamId]['stream'], 
													 	   'substream_id'=>$substreamId, 
														   'state_id' =>$stateId,
														   'city_id' => $cityId,
														   'min_result_count' => $categoryPageResultCount));	
				if($url){
					if(!$subStreamObjects[$substreamId]){
						$subStreamObjects[$substreamId] = $subStreamRepo->find($substreamId);
					}
					$subStreamName = $subStreamObjects[$substreamId]->getAlias() ? $subStreamObjects[$substreamId]->getAlias() : $subStreamObjects[$substreamId]->getName();
					$categoryPageLinks[] = array('url'=>$url, 'title' => $subStreamName.' colleges in '.$cityName);
				}
			}
			// for base courses
			foreach ($countWiseBaseCourses as $baseCourseId => $count) {

				if(count($categoryPageLinks) >= $categoryPageLinksLimit )
					continue;
				
				$url = $nationalCategoryPageLib->getUrlByParams(
													 array('base_course_id'=>$baseCourseId, 
														   'state_id' =>$stateId,
														   'city_id' => $cityId,
														   'min_result_count' => $categoryPageResultCount));	
				if($url){
					if(!$baseCourseObjects[$baseCourseId]){
						$baseCourseObjects[$baseCourseId] = $baseCourseRepo->find($baseCourseId);
					}
					$baseCourseName = $baseCourseObjects[$baseCourseId]->getAlias() ? $baseCourseObjects[$baseCourseId]->getAlias() : $baseCourseObjects[$baseCourseId]->getName();
					$categoryPageLinks[] = array('url'=>$url, 'title' => $baseCourseName.' colleges in '.$cityName);
				}
			}
			//store category Page Links of listings into redis
			$this->nationalinstitutecache->storeCategoryLinksForListings($listingId,json_encode($categoryPageLinks));
		}
		$this->CI->benchmark->mark('preparing_institute_course_widget::category_links_interlinking_end');
		// _p($categoryPageLinks);die;

		$this->CI->benchmark->mark('preparing_institute_course_widget::ranking_links_interlinking_start');
		$rankingPageLinks = array();

		$mainLocation = $instituteObj->getMainLocation();
		// show ranking links only on main location
		if($instituteCurrentLocation->getCityId() == $mainLocation->getCityId()){
			$rankingPageLinksCache = $this->nationalinstitutecache->getRankingLinksForListings($listingId);
			if(!empty($rankingPageLinksCache)){
				$rankingPageLinks = $rankingPageLinksCache->data;
			}
			else{
				$this->CI->load->builder('rankingV2/RankingPageBuilder');
				$rankingBuilder = new RankingPageBuilder();
				$rankingURLManager = $rankingBuilder->getURLManager();

				$temp = array();
				$rankingFetchCityId = empty($virtualCityId) ? $cityId : $virtualCityId;
				// for stream
				foreach ($countWiseStreams as $streamId => $count) {
					$temp[] = array('stream_id'=>$streamId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
				}
				if(!empty($temp)){
					$links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
					$rankingPageLinks = array_merge($rankingPageLinks,$links);
				}

				// for sub-stream
				$temp = array();
				foreach ($countWiseSubStreams as $substreamId => $count) {
					$temp[] = array('stream_id'=>$subStreamInfo[$substreamId]['stream'], 'substream_id'=>$substreamId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
				}
				if(!empty($temp)){
					$links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
					$rankingPageLinks = array_merge($rankingPageLinks,$links);
				}

				// for base courses
				$temp = array();
				foreach ($countWiseBaseCourses as $baseCourseId => $count) {
					$temp[] = array('base_course_id'=>$baseCourseId, 'state_id' =>$stateId,'city_id' => $rankingFetchCityId);
				}

				if(!empty($temp)){
					$links = $rankingURLManager->getRankingUrlsByMultipleParams($temp);
					$rankingPageLinks = array_merge($rankingPageLinks,$links);
				}

				// _p($cityTier);die;
				if(!in_array($cityTier,array(1,2))){
				    $rankingPageLinks = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'state');});
				}
				else{
				    $temp1 = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'city');});
				    $temp2 = array();
				    // if state name is not same as city name
				    if(strpos($cityName, $stateName) === false){
				    	$temp2 = array_filter($rankingPageLinks,function($ele){return ($ele['type'] == 'state');});
				    }
				    $rankingPageLinks = array_merge($temp1,$temp2);
				}

				$rankingPageLinks = array_slice($rankingPageLinks,0,8);
				$this->nationalinstitutecache->storeRankingLinksForListings($listingId,$rankingPageLinks);
			}		
			// _p($rankingPageLinks);die('aaa');
		}

		$this->CI->benchmark->mark('preparing_institute_course_widget::ranking_links_interlinking_end');
		
		// sort by name
		usort($baseCourseObjects, function($a, $b){
			return $a->getName() < $b->getName() ? -1 : 1;
		});
		usort($streamObjects, function($a, $b){
			return $a->getName() < $b->getName() ? -1 : 1;
		});

		$result['allCourses']            	= $allCourses;
		$result['popularCourseList']     	= $popularCourseList;
		$result['featuredCourseList']    	= $featuredCourseList;
		$result['baseCourseObjects']     	= $baseCourseObjects;
		$result['streamObjects']         	= $streamObjects;
		$result['specializationIds']     	= $specializationIds;
		$result['categoryPageLinks']     	= $categoryPageLinks;
		$result['rankingPageLinks']      	= $rankingPageLinks;
		if($show404){
			$result['totalCourseCount'] = 0;
		}else {
			$result['totalCourseCount']      	= count($allCourseList);
		}
		$result['coursesShownCount']     	= count($popularCourseList)+count($featuredCourseList);
		$result['mbaCourseIds']			 	= array_values(array_unique($mbaCourseIds));
		$result['courseViewCount']		 	= $courseViewCount;
		$result['instituteHasPaidCourse']	= $instituteHasPaidCourse;
		// _P($result['instituteHasPaidCourse']); die;
		$result['coursesOfferedList']	= $coursesOfferedList;	
		$result['baseCourseIds'] = $baseCourseIds;
		$result['streamIds'] = $streamIds;
		
		$this->CI->benchmark->mark('preparing_institute_course_widget_end');
		return $result;
	}

	function getCourseViewCount($courseList){

		$viewCount = $this->listingCommonLib->listingViewCount('course',$courseList);

		foreach ($courseList as $courseId) {
			if(!array_key_exists($courseId, $viewCount) || !$viewCount[$courseId]){
				$viewCount[$courseId] = 0;
			}
		}

		return $viewCount;
	}

	function getInstituteViewCount($instituteList){

		$viewCount = $this->listingCommonLib->listingViewCount('institute',$instituteList);

		foreach ($instituteList as $instituteId) {
			if(!array_key_exists($instituteId, $viewCount) || !$viewCount[$instituteId]){
				$viewCount[$instituteId] = 0;
			}
		}

		return $viewCount;
	}

	function formatLocationForMultilocationLayer($instituteLocations, $locationsMappedToCourse){

		$this->CI->benchmark->mark('preparing_location_layer_data_start');

		$data                         = array();
		$data['loctionsWithLocality'] = array();
		$data['otherLocations']       = array();
		$data['cityData']             = array();
		$count                        = 0;

		foreach($instituteLocations as $location){

			if(!in_array($location->getLocationId(), $locationsMappedToCourse))
				continue;

			if($location->getLocalityId()){
				$city = $location->getCityName();
				$data['loctionsWithLocality'][$location->getCityName()."_".$location->getCityId()][$location->getLocalityName()] = $location;
				$data['cityData'][$location->getCityId()]['name'] = $location->getCityName();
			}else{
				$data['otherLocations'][$location->getCityName()."_".$location->getCityId()] = $location;
			}
			$count++;
		}
		ksort($data['loctionsWithLocality']);
		ksort($data['otherLocations']);

		foreach ($data['loctionsWithLocality'] as $key => &$valueArr) {
			ksort($valueArr);
		}

		$data['hasLocation'] = $count > 0 ? true : false;
		$data['locationCount'] = $count;

		$this->CI->benchmark->mark('preparing_location_layer_data_end');
		return $data;
	}

	function prepareFacilitiesInformation($facilities,$facilityNameContent = array())
    {
        $facilitiesData = array();
        foreach ($facilities as $key => $value) {
            $facilityName = $facilities[$key]->getFacilityName();
            $facilityId = $facilities[$key]->getFacilityId();
            if($facilityId == 17 || empty($facilityId))
            {
                $childFacilityInfo = $facilities[$key]->getChildFacilities();
                foreach ($childFacilityInfo as $key => $value) {

                    $child_facilityName = $childFacilityInfo[$key]->getFacilityName();
                    $child_facilityId = $childFacilityInfo[$key]->getFacilityId();
                    $child_parent_id = $childFacilityInfo[$key] ->getParentFacilityId();
                    if(!empty($child_facilityName) && !empty($facilityNameContent) && in_array($child_facilityName, $facilityNameContent) && $child_parent_id == 17)
                    {
                        $facilitiesData[$child_facilityName] = array('facility_name' => $child_facilityName,'facility_id' => $child_facilityId, 'description' => $childFacilityInfo[$key]->getFacilityDescription(),'additionalInfo' => $childFacilityInfo[$key]->getAdditionalInfo(),'has_facility' => $childFacilityInfo[$key]->getFacilityStatus(),'parent_facility_name' => $childFacilityInfo[$key]->getParentFacilityName());    
                    }
                    else if(!empty($child_facilityName) && empty($facilityNameContent) && $child_parent_id == 17)
                    {
                        $facilitiesData[$child_facilityName] = array('facility_name' => $child_facilityName,'facility_id' => $child_facilityId, 'description' => $childFacilityInfo[$key]->getFacilityDescription(),'additionalInfo' => $childFacilityInfo[$key]->getAdditionalInfo(),'has_facility' => $childFacilityInfo[$key]->getFacilityStatus(),'parent_facility_name' => $childFacilityInfo[$key]->getParentFacilityName());    
                    }
                    
                }
            }
            else
            {
                if(!empty($facilityName) && !empty($facilityNameContent) && in_array($facilityName, $facilityNameContent))
                {
                    $facilitiesData[$facilityName] = array('facility_name' => $facilityName,'facility_id' => $facilityId, 'description' => $facilities[$key]->getFacilityDescription(),'additionalInfo' => $facilities[$key]->getAdditionalInfo(),'has_facility' => $facilities[$key]->getFacilityStatus(),'parent_facility_name' => $facilities[$key]->getParentFacilityName());   

                    $childFacilityInfo = $facilities[$key]->getChildFacilities();
                    
                    if(!empty($childFacilityInfo))
                        $facilitiesData[$facilityName]['childFacility'] = $this->prepareFacilitiesInformation($childFacilityInfo);    
                }
                else if(!empty($facilityName) && empty($facilityNameContent))
                {
                    $facilitiesData[$facilityName] = array('facility_name' => $facilityName,'facility_id' => $facilityId, 'description' => $facilities[$key]->getFacilityDescription(),'additionalInfo' => $facilities[$key]->getAdditionalInfo(),'has_facility' => $facilities[$key]->getFacilityStatus(),'parent_facility_name' => $facilities[$key]->getParentFacilityName());   

                    $childFacilityInfo = $facilities[$key]->getChildFacilities();
                    
                    if(!empty($childFacilityInfo))
                        $facilitiesData[$facilityName]['childFacility'] = $this->prepareFacilitiesInformation($childFacilityInfo);    
                }

                
            }
            
            
        }
        return $facilitiesData;
    }

    function getInstituteCurrentLocation($instituteObj,$cityId ='',$localityId = ''){
    	$this->CI->benchmark->mark('preparing_current_loc_start');

		$currentCityId     = !empty($_GET['city']) ? $this->CI->input->get("city") : $cityId;
		$currentLocalityId = !empty($_GET['locality']) ? $this->CI->input->get("locality") : $localityId;

		$currentLocation = '';
		if(empty($currentCityId) && empty($currentLocalityId))
		{
			$currentLocation = $instituteObj->getMainLocation();
		}
		else
		{
			foreach($instituteObj->getLocations() as $location)
			{
				$localityName = $location->getLocalityId()?$location->getLocalityName():"";
				$localityId   = $location->getLocalityId()?$location->getLocalityId():0;

				if($currentCityId!='' && $currentCityId == $location->getCityId())
				{
					$currentLocation = $location;
				    if($currentLocalityId == $localityId)
				    {
						$currentLocation = $location;
						break;
					}
				}
			}
		}
		
		if(empty($currentLocation)){
			$currentLocation = $instituteObj->getMainLocation();
		}

		$this->CI->benchmark->mark('preparing_current_loc_end');

		return $currentLocation;
    }
    function prepareGalleryData($photosObj,$videoObj,$currentLocationObj)
    {
        $this->CI->load->helper('image');

        $mediaPhoto = array();
        $mediaPhotoOrder = array();
        $currentLocationId = $currentLocationObj->getLocationId();
        $isMainLocation = $currentLocationObj->isMainLocation();
        $allowMedia = false;
        foreach ($photosObj as $photoKey => $photoValue) {
            $media_id = $photosObj[$photoKey]->getId();
            $media_url = $photosObj[$photoKey]->getUrl();
            $tagArray = $photosObj[$photoKey]->getTags();
            $media_title = $photosObj[$photoKey]->getTitle();
            $allowMedia = false;

            $locationIds = $photosObj[$photoKey]->getLocationIds();
            if(in_array($currentLocationId, $locationIds) || $locationIds[0] == 0)
            {
                $allowMedia = true;
            }

            if($allowMedia)
            {
                if(empty($tagArray))
                {
                    $mediaPhoto['Others'][] = array('name' => $tagValue['name'],'id' => $tagValue['id'],'type' => $tagValue['type'],'media_id'=>$media_id,'media_widget' => getImageVariant($media_url,5),'media_url'=>getImageVariant($media_url,8),'thumbnail_url' => getImageVariant($media_url,2),'media_title' => $media_title);
                    $mediaPhotoOrder['Others'] = empty($mediaPhotoOrder['Others']) ? 1 : $mediaPhotoOrder['Others'] + 1;
                }
                else
                {
                    foreach ($tagArray as $tagKey => $tagValue) {
                        if($tagValue['type'] == 'event')
                        {
                            $mediaPhoto['Event'][] = array('name' => $tagValue['name'],'id' => $tagValue['id'],'type' => $tagValue['type'],'media_id'=>$media_id,'media_widget' => getImageVariant($media_url,5),'media_url'=>getImageVariant($media_url,8),'thumbnail_url' => getImageVariant($media_url,2),'media_title' => $media_title);
                            $mediaPhotoOrder['Event'] = empty($mediaPhotoOrder['Event']) ? 1 : $mediaPhotoOrder['Event'] + 1;
                        }
                        else
                        {
                            $mediaPhoto[$tagValue['name']][] = array('name' => $tagValue['name'],'id' => $tagValue['id'],'type' => $tagValue['type'],'media_id'=>$media_id,'media_widget' => getImageVariant($media_url,5),'media_url'=>getImageVariant($media_url,8),'thumbnail_url' => getImageVariant($media_url,2),'media_title' => $media_title);    
                            $mediaPhotoOrder[$tagValue['name']] = empty($mediaPhotoOrder[$tagValue['name']]) ? 1 : $mediaPhotoOrder[$tagValue['name']] + 1;
                        }
                        
                    }
                }
            }
        }
        $totalTags = count($mediaPhotoOrder);
        $videoDataExist = false;
        foreach ($videoObj as $videoKey => $videoValue) {
            $media_id = $videoObj[$videoKey]->getId();
            $media_url = $videoObj[$videoKey]->getUrl();
            $media_title = $videoObj[$videoKey]->getTitle();
            $thumb_url = $videoObj[$videoKey]->getThumbUrl();
	    $uploaded_date = $videoObj[$videoKey]->getUploadedDate();

            $allowMedia = false;

        	$locationIds = $videoObj[$videoKey]->getLocationIds();
            if(in_array($currentLocationId, $locationIds) || $locationIds[0] == 0)
            {
                $allowMedia = true;
            }

            if($allowMedia)
            {
            	if(empty($thumb_url)) {
                    $videoId = substr($media_url, strpos($media_url, "www.youtube.com/v/") + 18);
                    $thumb_url = 'https://i1.ytimg.com/vi/'.$videoId.'/0.jpg';
                  }
                  
                $mediaVideo['Videos'][] = array('media_id' => $media_id , 'media_url' => $media_url ,'media_title' => $media_title,'thumb_url' => $thumb_url,'uploaded_date' => $uploaded_date);
                $videoDataExist = true;
            }
        }
        if(count($mediaPhotoOrder) == 1 && isset($mediaPhotoOrder['Others']))
        {
        	$mediaPhotoOrder['Photos'] = $mediaPhotoOrder['Others'];
        	$mediaPhoto['Photos'] = $mediaPhoto['Others'];
        	unset($mediaPhotoOrder['Others']);
        	unset($mediaPhoto['Others']);
        }
        arsort($mediaPhotoOrder);
        $sortArray = array();
        $numberOfPhotos = 0;
        $order = array();
        $totalPhotos = 0;

        foreach ($mediaPhotoOrder as $sortKey => $sortValue) {
            if(empty($numberOfPhotos) || $numberOfPhotos == $sortValue)
            {
                $order[] = $sortKey;    
            }
            else
            {
                sort($order);
                $sortArray =  array_merge($sortArray,$order);
                $order = array();
                $order[] = $sortKey;
            }
            $numberOfPhotos = $sortValue;
            $totalPhotos += $sortValue;
            
        }
        if(!empty($order) && count($order) > 0)
        {
            sort($order);
            $sortArray =  array_merge($sortArray,$order);

        }
        if(in_array('Others', $sortArray))
        {
        	$keyPos = array_search('Others', $sortArray);
        	unset($sortArray[$keyPos]);
        	$sortArray = array_values($sortArray);
        	array_push($sortArray, 'Others');
        }
        return array('photos' => array('order' => $sortArray,'list' => $mediaPhoto,'totalPhotos'=>$totalPhotos),'videos' => $mediaVideo,'totalTags' => $totalTags);

    }

    public function getCollegeCutOffData($instituteObj, $instituteHierarchy = array(), $affiliationUniversityData = array(), $baseCourseNames = array()){
    	// return array();
    	$instituteId = $instituteObj->getId();

    	$this->CI->load->config('nationalInstitute/CollegeCutoffConfig',True);
    	$collegesData = $this->CI->config->item('colleges','CollegeCutoffConfig');
    	$parentListingsIdsData = $this->CI->config->item('parentListingIds','CollegeCutoffConfig');
    	$idToCollegeMapping = $this->CI->config->item('idToCollegeMapping','CollegeCutoffConfig');
    	
    	$isChildInstitutePage = true;
    	if($parentListingsIdsData[$instituteId]){
    		$isChildInstitutePage = false;
    		$parentListingId = $instituteId;
    	}
    	else{
    		if(empty($instituteHierarchy)){
    			$instituteHierarchy = $this->getInstituteListingHierarchyDataNew(array($instituteId));
    		}
    		$instituteHierarchy = $instituteHierarchy[$instituteId];

    		foreach ($instituteHierarchy['university'] as $row) {
    			if(!empty($parentListingsIdsData[$row['listing_id']])){
    				$parentListingId = $row['listing_id'];
    				break;
    			}
    		}
    		
    		if(empty($parentListingId)){
    			foreach ($instituteHierarchy['institute'] as $row) {
    				if(!empty($parentListingsIdsData[$row['listing_id']])){
    					$parentListingId = $row['listing_id'];
    					break;
    				}
    			}

    			//including affiliated university check as well
    			if(empty($parentListingId)) {
		    		$affiliationUniversityData = reset($affiliationUniversityData); 
		    		if(!empty($affiliationUniversityData['id']) && $affiliationUniversityData['type'] == 'domestic') {
		    			$parentListingId = $affiliationUniversityData['id'];
		    		}
    			}
    		}
    	}
    	
    	$returnData = array();
    	if(!empty($parentListingId)){
    		$instituteBuilder = new InstituteBuilder();
    		$instituteRepo = $instituteBuilder->getInstituteRepository();
    		$parentListingObj = $instituteRepo->find($parentListingId);
    		$parentListingUrl = $parentListingObj->getURL();
    		$admissionsUrl = $parentListingObj->getAllContentPageUrl('admission');
    		if($isChildInstitutePage){
    			$allCoursesUrl = $instituteObj->getAllContentPageUrl('courses');
    		}
    		else{
    			$allCoursesUrl = $parentListingObj->getAllContentPageUrl('courses');
    		}		
    		$abbreviation = $idToCollegeMapping[$parentListingId];

    		if($isChildInstitutePage){
    			$cpmodel = $this->CI->load->model('CP/cpmodel');

    			$instituteWiseCourses = $this->getAllCoursesForInstitutes($instituteId,'direct');
    			$courseIds = $cpmodel->getCoursesHavingPredictors($abbreviation,$instituteWiseCourses['courseIds']);

    			if(!empty($courseIds)){
    				$this->CI->load->builder("nationalCourse/CourseBuilder");
    				$builder          = new CourseBuilder();
    				$courseRepository = $builder->getCourseRepository();
    				$courseObjs = $courseRepository->findMultiple($courseIds);

    				$courseData = array();
    				foreach ($courseObjs as $key => $row) {
    					$courseData[$key]['courseName'] = $row->getName();
    				}

    				$previewTextData = $collegesData[$abbreviation]['childCollegesPreviewText'];

    				$bips = implode(',', $baseCourseNames);
    				$courseNames = array();
    				foreach ($courseData as $row) {
    					$courseNames[] = $row['courseName'];
    				}

    				if(!empty($instituteObj->getShortName())){
    					$shortName = $instituteObj->getShortName();
    				}
    				else{
    					$shortName = $instituteObj->getName();
    				}
    				$search = array('<shortName>','<institute_name>','<courseCount>','<allCoursesUrl>','<admissionsUrl>','<parentListingUrl>','<bips>','<courseNames>');
    				$replace = array($shortName,$instituteObj->getName(),count($courseData), $allCoursesUrl,$admissionsUrl,$parentListingUrl,$bips,implode(', ',$courseNames));
    				foreach ($previewTextData as $row) {
    					$previewText[] =  str_replace($search,$replace,$row);
    				}
    		
    			}
    		}
    		else{
    			$previewTextData = $collegesData[$abbreviation]['previewText'];
    			$search = array('<institute_name>','<courseCount>','<allCoursesUrl>','<admissionsUrl>','<parentListingUrl>');
    			$replace = array($instituteObj->getName(),count($courseData), $allCoursesUrl,$admissionsUrl,$parentListingUrl);
    			foreach ($previewTextData as $row) {
    				$previewText[] =  str_replace($search,$replace,$row);
    			}
    		}

    		if(!empty($previewText)){
    			$returnData['previewText'] = $previewText;
    			$returnData['cutOffUrl'] = $instituteObj->getAllContentPageUrl('cutoff');
    		}
    	}
    	return $returnData;
    }

    public function getInstituteListingHierarchyDataNew($instituteIds,$excludeDummy = false){
    	if(empty($instituteIds)){
    		return;
    	}
    	$lib = $this->CI->load->library('nationalInstitute/InstitutePostingLib');
    	$instituteHierarchy = $lib->getInstituteParentHierarchyFromFlatTale($instituteIds);
    	// This code mean we expect hierarchy from top to bottom & but was returned opposite by api
    	foreach ($instituteHierarchy as $instId => $value) {
    	    if(count($value) > 1){
    	        $valuesToBeChecked = $value[0];
    	        if($valuesToBeChecked['listing_id'] == $instId){
    	            $instituteHierarchy[$instId] = array_reverse($instituteHierarchy[$instId]);
    	        }
    	    }
    	}
    	$finalResult = array();
    	foreach ($instituteIds as $instituteId) {
    		if(!empty($instituteHierarchy[$instituteId])){
    			foreach ($instituteHierarchy[$instituteId] as $value){
    				if($excludeDummy && !empty($value['is_dummy'])){
    					continue;
    				}
    				list($listing_type) = explode("_", $value['id']);
    				$listing_id = $value['listing_id'];
    				if($listing_id ==  $instituteId){
    				    $value['is_primary'] = 1;
    				}else{
    				    $value['is_primary'] = 0;
    				}
    				if($value['type'] == "university"){
    				    unset($finalResult[$instituteId][$listing_type]);
    				}
    				
    				if(!empty($value['is_satellite'])){
    				    unset($finalResult[$instituteId][$listing_type]);
    				    unset($finalResult[$instituteId]['university']);
    				} 
    				$finalResult[$instituteId][$listing_type][$listing_id] = $value;    
    			}
    		}
    	}
    	return $finalResult;
    }

    function getInstitutePageTopCardData($instituteObj, $courseViewCount, $allCoursesObjects, $isMobile = false, $currentLocationObj, $ampViewFlag=false){

    	$this->CI->benchmark->mark('preparing_top_card_data_start');

    	$result = array();

		if(empty($instituteObj) || !$instituteObj->getId())
    		return $result;

		$listingType             = $instituteObj->getType();
		$result['instituteName'] = $instituteObj->getName();

		$inlineData   = array();
		$detailedData = array();

		$ownership   = $instituteObj->getOwnership();
		$studentType = $instituteObj->getStudentType();
		$estbYear    = $instituteObj->getEstablishedYear();
		$instituteSpecificationType=ucfirst($instituteObj->getInstituteSpecificationType());

		$static_data = $this->CI->config->item('static_data');

		if($ownership){
			foreach ($static_data['ownernship'] as $value) {
				if($value['value'] == $ownership)
					$inlineData['ownership'] = $value['label'];
			}
		}

		if($estbYear){
			if($isMobile)
				$inlineData['estbYear'] = $estbYear;
			else
				$inlineData['estbYear'] = 'Established '.$estbYear;
		}

		if($studentType && $studentType != 'co-ed'){
			foreach ($static_data['student_type'] as $value) {
				if($value['value'] == $studentType)
					$inlineData['studentType'] = $value['label'];
			}
		}

    	$headerImage = $instituteObj->getHeaderImage($currentLocationObj->getLocationId());
    	
    	if($listingType == 'institute'){
	    	// get institute important data
	    	$instituteImportantData = array();
	    	if($instituteObj->isAutonomous()){
	    		$instituteImportantData['autonomous'] = "Autonomous Institute";
	    	}
	    	if($instituteObj->isNationalImportance()){
	    		$instituteImportantData['nationalImportance'] = "Institute of National Importance";
	    	}
	    }

	    // get affiliation details
	    if($listingType == 'institute'){

	    	$domesticAffiliation = array();
			$abroadAffiliation   = array();
			$affiliationData     = array();
	    	foreach ($allCoursesObjects as $key => $value) {
	    		$affiliation = $value->getAffiliations();
	    		if($affiliation['scope'] == 'domestic' && $affiliation['university_id']){
	    			$domesticAffiliation[] = $affiliation['university_id'];
	    		}
	    		else if($affiliation['scope'] == 'abroad' && $affiliation['university_id']){
	    			$abroadAffiliation[] = $affiliation['university_id'];
	    		}
	    	}
	    	$domesticAffiliation = array_values(array_unique($domesticAffiliation));
	    	$abroadAffiliation = array_values(array_unique($abroadAffiliation));

	    	if(!empty($domesticAffiliation)){
	    		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		        $instituteBuilder = new InstituteBuilder();
		        $instituteRepo = $instituteBuilder->getInstituteRepository();       
		        $coursePopularity = $this->getInstituteViewCount($domesticAffiliation);

		        $domesticAffiliationObjs = $instituteRepo->findMultiple(array_keys($coursePopularity), array('basic'), true);
		        foreach ($domesticAffiliationObjs as $value) {
		        	$name = $value->getName();
		        	$affiliationData[] = array("name" => $name, "url"=>$value->getURL(), "id"=>$value->getId(), "type"=>"domestic", "isDummy" => $value->isDummy());
				}
	    	}
	    	if(!empty($abroadAffiliation)){
	    		$this->CI->load->builder('ListingBuilder','listing');
	    		$listingBuilder = new ListingBuilder;
				$abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
				$abroadAffiliationObjs = $abroadUniversityRepository->findMultiple($abroadAffiliation);
				foreach ($abroadAffiliationObjs as $value) {
					$name = $value->getName();
					$affiliationData[] = array("name" => $name, "url"=>$value->getURL(), "id"=>$value->getId(), "type"=>"abroad", "isDummy"=>false);
				}
	    	}

	    	$instituteParentUniversities = $this->getInstituteListingHierarchyDataNew(array($instituteObj->getId()),true);
	    	$result['instituteParentData'] = $instituteParentUniversities;

	    	$affiliationLinks = array();
	    	foreach ($affiliationData as $value) {
	    		if($value['isDummy']){
	    			$affiliationLinks[] = htmlentities($value['name']);
	    		}
	    		else{
		    		if($isMobile && !$ampViewFlag){
		    			$affiliationLinks[] = "<a class='inst-n' href='".$value['url']."'>".htmlentities($value['name'])."</a>";
				}else if($isMobile && $ampViewFlag){
					$affiliationLinks[] = "<a href='".$value['url']."'>".htmlentities($value['name'])."</a>";
		    		}else{
		    			$affiliationLinks[] = "<a target='_blank' href='".$value['url']."'>".htmlentities($value['name'])."</a>";
				}
		    	}
	    	}
	    	$result['affiliationData'] = $affiliationData;
	    	
	    	if(!empty($affiliationLinks)){
	    		if($isMobile && !$ampViewFlag){
	    			$instituteImportantData['affiliation'] = "<b class='f400'>Affiliated to </b> ".$affiliationLinks[0];
	    		}else if($isMobile && $ampViewFlag){
					$instituteImportantData['affiliation'] = "<b>Affiliated to </b> ".$affiliationLinks[0];
				}else{
	    			$instituteImportantData['affiliation'] = "Affiliated to ".$affiliationLinks[0];
	    		}

				$extraLinks = "";
				if(count($affiliationLinks) > 1){
					if($isMobile && $ampViewFlag){
						$extraLinks .= '<span class="more-lnk-div">';
                                                $extraLinks .= " <a class='more-data-lnk link' on='tap:more-affiliation'>+".(count($affiliationLinks)-1)." more</a>";
						$extraLinks .= '<amp-lightbox class="" id="more-affiliation" layout="nodisplay" scrollable>';
                                        	$extraLinks .= '<div class="lightbox" on="tap:more-affiliation.close" role="button" tabindex="0">';
                                	        $extraLinks .= '<a class="cls-lightbox f25 color-f font-w6">&times;</a>';
                        	                $extraLinks .= '<div class="m-layer">';
                	                        $extraLinks .= '<div class="min-div color-w pad10">';
	                                        $extraLinks .= '<ul>';
					
					}else{
						$extraLinks .= '<span class="more-lnk-div">';
						$extraLinks .= " <a href='javascript:void(0);' class='more-data-lnk link'>+".(count($affiliationLinks)-1)." more</a>";
						$extraLinks .= '<div class="small-popup" style="display:none;">';
						$extraLinks .= '<ul>';
					}
					foreach ($affiliationLinks as $key=>$value) {
	    				if($key != 0)
	    					$extraLinks .= "<li>".$value."</li>";
	    			}
					if($isMobile && $ampViewFlag){
						$extraLinks .= '</ul>';
	                                        $extraLinks .= '</div>';
	                                        $extraLinks .= '</div>';
	                                        $extraLinks .= '</div>';
	                                        $extraLinks .= '</amp-lightbox>';
	                                        $extraLinks .= '</span>';
					}else{
						$extraLinks .= "</ul>";
	                                        $extraLinks .= "</div>";
	                                        $extraLinks .= "</span>";
					}	
				}
				
				$detailedData['affiliation'] = $extraLinks;
	    	}
	    	else{

	    		$instituteParentUniversities = $instituteParentUniversities[$instituteObj->getId()];
	    		// _p($instituteParentUniversities);die;
	    		if(!empty($instituteParentUniversities['university'])){
		    		foreach ($instituteParentUniversities['university'] as $id => $row) {
		    			$hierarchyUniversityId = $id;
		    		}
		    		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
			        $instituteBuilder = new InstituteBuilder();
			        $instituteRepo = $instituteBuilder->getInstituteRepository();
			        $universityObj = $instituteRepo->find($hierarchyUniversityId, array('basic'), true);
			        if($isMobile && !$ampViewFlag){
			        	$instituteImportantData['affiliation'] = "<b class='f400'>"
			        	.$instituteSpecificationType." of </b><a class='inst-n' href='".$universityObj->getURL()."'>".htmlentities($universityObj->getName())."</a>";
			        }else if($isMobile && $ampViewFlag){
					$instituteImportantData['affiliation'] = "<b>".$instituteSpecificationType." of </b><a class='inst-n' href='".$universityObj->getURL()."'>".htmlentities($universityObj->getName())."</a>";
			        }else{
			        	$instituteImportantData['affiliation'] = $instituteSpecificationType." of <a target='_blank' href='".$universityObj->getURL()."'>".htmlentities($universityObj->getName())."</a>";
			        }
	    		}
	    	}
	    }

	    // ugc approval and specification type for university
	    if($listingType == 'university'){
	    	// university approval
	    	$isUGCApproved = $instituteObj->isUGCApproved();
	    	if($isUGCApproved)
	    		$instituteImportantData['ugc_approved'] = "UGC Approved";

	    	// university Specification type
	    	$univSpecificationType = $instituteObj->getUniversitySpecificationType();
	    	if($univSpecificationType){
	    		foreach ($static_data['university_type'] as $value) {
	    			if($value['value'] == $univSpecificationType){
	    				$instituteImportantData['university_type_'.$univSpecificationType] = $value['label']." University";
	    				break;
	    			}
	    		}
	    	}
	    }

	    // accrediation details
    	$accreditation = $instituteObj->getAccreditation();
    	if($accreditation){
			$instituteImportantData['naac_accreditation'] = "Grade '".$accreditation."' accredited by NAAC";
    	}

    	// is AIU member
    	if($listingType == 'university'){
    		$isAIUMember = $instituteObj->isAIUMember();
    		if($isAIUMember){
    			$instituteImportantData['aiu_member'] = "Member of AIU";
    		}
    	}
    	

    	// ranking data
    	if($listingType == 'institute'){

	    	$this->CI->load->library("rankingV2/RankingCommonLibv2");
	    	$rankingCommonLib = new RankingCommonLibv2();
	    	$courseIds = array_keys($courseViewCount);
	    	$rankingData = $rankingCommonLib->getCoursesRankData($courseIds, true, 100);

	    	$this->CI->load->builder('RankingPageBuilder', RANKING_PAGE_MODULE);
			$rankingURLManager  = RankingPageBuilder::getURLManager();
			$rankingPageCachedUrl = array();
	    	foreach ($rankingData as $key => $value) {
	    		$pageIdentifier = $rankingData[$key]['ranking_page_id']."-2-0-0-0";
	    		if(empty($rankingPageCachedUrl[$pageIdentifier])){
					$RankingPageRequest = $rankingURLManager->getRankingPageRequest($pageIdentifier);
					$rankingPageCachedUrl[$pageIdentifier] = $rankingURLManager->buildURL($RankingPageRequest);
	    		}
	    		$rankingData[$key]['url']  = $rankingPageCachedUrl[$pageIdentifier]; 
	    	}

	    	if($rankingData) {
	    		$value = reset($rankingData);
	    		if($isMobile && !$ampViewFlag){
	    			$instituteImportantData['rank'] = "<b>Ranked ".$value['rank']."</b> for <a href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".htmlentities($value['source_name'].' '.$value['source_year']);
	    		}else if($isMobile && $ampViewFlag){
				$instituteImportantData['rank'] = "<b>Ranked ".$value['rank']."</b> for <a href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".htmlentities($value['source_name'].' '.$value['source_year']);
	    		}else{
	    			$instituteImportantData['rank'] = "Ranked ".$value['rank']." for <a target='_blank' href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".htmlentities($value['source_name'].' '.$value['source_year']);	
	    		}
	    	}

	    	if(count($rankingData) > 1){
	    		$extraLinks = "";
				if($isMobile && $ampViewFlag){
					$extraLinks .= '<span class="more-lnk-div">';
                                        $extraLinks .= "<a class='more-data-lnk link' on='tap:more-data'>+".(count($rankingData)-1)." more</a>";
                                        $extraLinks .= '<amp-lightbox class="" id="more-data" layout="nodisplay" scrollable>';
					$extraLinks .= '<div class="lightbox">';
					$extraLinks .= '<a class="cls-lightbox f25 color-f font-w6" on="tap:more-data.close" role="button" tabindex="0">&times;</a>';
					$extraLinks .= '<div class="m-layer">';
					$extraLinks .= '<div class="min-div color-w pad10">';
					$extraLinks .= '<p class="m-btm f14 color-3 font-w6">Course Rankings</p>';
                                        $extraLinks .= '<ul>';
				}else{
					$extraLinks .= '<span class="more-lnk-div">';
					$extraLinks .= " <a href='javascript:void(0);' class='more-data-lnk link'>+".(count($rankingData)-1)." more</a>";
					$extraLinks .= '<div class="small-popup" style="display:none;">';
					$extraLinks .= '<ul>';
				}
				$counter = 0;
				foreach ($rankingData as $key=>$value) {
					if($counter != 0){
						if($isMobile && !$ampViewFlag){
							$extraLinks .= "<li>Ranked ".$value['rank']." for <a href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".$value['source_name'].' '.$value['source_year']."</li>";
						}else if($isMobile && $ampViewFlag){
							$extraLinks .= "<li class='12 color-6 m-5btm'>Ranked ".$value['rank']." for <a href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".$value['source_name'].' '.$value['source_year']."</li>";
						}else{
							$extraLinks .= "<li>Ranked ".$value['rank']." for <a target='_blank' href='".SHIKSHA_HOME."/".$value['url']."'>".htmlentities($value['ranking_page_text'])."</a> by ".$value['source_name'].' '.$value['source_year']."</li>";
						}
					}
					$counter++;
				}
				if($isMobile && $ampViewFlag){
					$extraLinks .= '</ul>';
					$extraLinks .= '</div>';
					$extraLinks .= '</div>';
					$extraLinks .= '</div>';
					$extraLinks .= '</amp-lightbox>';
					$extraLinks .= '</span>';
				}else{
					$extraLinks .= '</ul>';
					$extraLinks .= "</div>";
					$extraLinks .= "</span>";
				}	
				$detailedData['rank'] = $extraLinks;
	    	}
	    }

    	// if header image exists get its variant otherwise use default image
    	if($headerImage && $headerImage->getUrl()){
    		$result['headerImage']['url'] = $headerImage->getUrl();
    		$result['headerImage']['type'] = "header";
    		$result['headerImage']['id'] = $headerImage->getId();
    		$result['headerImage']['title'] = $headerImage->getTitle();
    		$result['headerImage']['tags'] = $headerImage->getTags();
    	}
    	else{
    		$result['headerImage']['url'] = MEDIAHOSTURL."/public/images/instDefault.png";
    		$result['headerImage']['type'] = "default";

    	}

    	// $instituteImportantData = array_slice($instituteImportantData, 0, 4);
		$result['inlineData']             = $inlineData;
		$result['detailedData']           = $detailedData;
		$result['instituteImportantData'] = $instituteImportantData;

		$this->CI->benchmark->mark('preparing_top_card_data_end');
    	return $result;
    }

    function getUniversityPageCollegesWidgetData($universityId,$collegeLimit= 6, $preFetchedCourseIds = array()){
    	$this->CI->benchmark->mark('preparing_university_colleges_widget_data_start');
    	// load institute repo
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();

		$instituteCount  = 0;
		$data            = array();
		$showViewMoreCTA = 0;

		if(empty($universityId))
			return $data;

		$this->institutedetailmodel = $this->CI->load->model('nationalInstitute/institutedetailsmodel');
		$this->nationalcoursemodel = $this->CI->load->model('nationalCourse/nationalcoursemodel');
        $affiliationMapping = $this->institutedetailmodel->getAffiatedCoursesOfUniversity($universityId);

        $data['providesAffiliaion'] =  0;
        $data['constituentCollegeText'] = "Most viewed colleges and departments of";
        $affiliatedCoursesCount = count($affiliationMapping);
        if($affiliatedCoursesCount > 0) {
        	$this->CI->benchmark->mark('fetch_affiliated_colleges_count_start');
			$data['providesAffiliaion']        =  1;
			$affiliatedInstitutes              = $this->nationalcoursemodel->getInstituteIdsForCourses($affiliationMapping);
			$affiliatedInstitutesIds           = array_values(array_unique(array_values($affiliatedInstitutes)));
			
			$instituteObjs = $instituteRepo->findMultiple($affiliatedInstitutesIds);
	        $universityObj = $instituteRepo->find($universityId);

	        foreach ($instituteObjs as $key=>$obj) {
	        	if($obj->getId() == $universityId)
	        		unset($instituteObjs[$key]);
	        }

	        $sortedInstitutes = array();
	        foreach ($instituteObjs as $obj) {

	        	$instituteName = $obj->getShortName();
	            $instituteName = $instituteName ? $instituteName : $obj->getName();
	        	$sortedInstitutes[strtolower(trim($instituteName))] = $obj;
	        }

	        $affiliatedInstitutesCount = count($sortedInstitutes);
	        $data['affiliatedCollegesText'] = "To see the list of ".$affiliatedInstitutesCount." affiliated colleges offering ".$affiliatedCoursesCount." courses";
	        $data['constituentCollegeText'] = "Most viewed constituent colleges and departments of";
        	$this->CI->benchmark->mark('fetch_affiliated_colleges_count_end');
        }
        $data['tabText'] = "Affiliated Colleges";

		// get all  institutes under this university
    	$instituteIds = $this->getUniversityInstituteIds($universityId, $preFetchedCourseIds);

    	if(empty($instituteIds))
    		return $data;

    	// sort institutes by view count
		$instituteViewCountWise = $this->getInstituteViewCount($instituteIds);
		$instituteCount         = count($instituteViewCountWise);
    	
		$instituteViewCountWise = array_keys($instituteViewCountWise);
		$showViewMoreCTA        = count($instituteViewCountWise) > $collegeLimit ? 1 : 0;
		$topInstitutesForWidget = array_slice($instituteViewCountWise, 0, $collegeLimit);       

        // load objects of top institutes
        $topInstituteObjs = $instituteRepo->findMultiple($topInstitutesForWidget, array('basic'), true);

        // prepare final data
		$data['topInstituteData'] = $topInstituteObjs;
		$data['instituteCount']   = $instituteCount;
		$data['showViewMoreCTA']  = $showViewMoreCTA;
		$data['showViewMoreCTA']  = $showViewMoreCTA;
		$data['tabText']          = "Colleges";

		$this->CI->benchmark->mark('preparing_university_colleges_widget_data_end');
		// retur data
		return $data;
    }

    function getAllInstitutesOfUniversity($universityId){

		$data = array();

		if(empty($universityId))
			return $data;

		// get all  institutes under this university
    	$instituteIds = $this->getUniversityInstituteIds($universityId);

    	if(empty($instituteIds))
    		return $data;

    	// load institute repo
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();       

        // load objects of top institutes
        $instituteObjs = $instituteRepo->findMultiple($instituteIds);
        $universityObj = $instituteRepo->find($universityId);

        $sortedInstitutes = array();
        foreach ($instituteObjs as $obj) {

        	$instituteName = $obj->getShortName();
            $instituteName = $instituteName ? $instituteName : $obj->getName();
        	$sortedInstitutes[strtolower(trim($instituteName))] = $obj;
        }
        ksort($sortedInstitutes);

		$data['institutes']    = $sortedInstitutes;
		$data['universityObj'] = $universityObj;
		$data['instituteIds'] = $instituteIds;

        return $data;
    }

    function getInstitutesAffiliatedToUniversity($universityId){

    	$data = array();

		if(empty($universityId))
			return $data;

    	$this->institutedetailmodel = $this->CI->load->model('nationalInstitute/institutedetailsmodel');
    	$this->nationalcoursemodel = $this->CI->load->model('nationalCourse/nationalcoursemodel');
        
        $affiliationMapping = $this->institutedetailmodel->getAffiatedCoursesOfUniversity($universityId);

        if(empty($affiliationMapping))
    		return $data;

        $affiliatedInstitutes = $this->nationalcoursemodel->getInstituteIdsForCourses($affiliationMapping);

        if(empty($affiliatedInstitutes))
    		return $data;

        $affiliatedInstitutesIds = array_values(array_unique(array_values($affiliatedInstitutes)));

        // load institute repo
    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();       

        // load objects of top institutes
        $instituteObjs = $instituteRepo->findMultiple($affiliatedInstitutesIds);
        $universityObj = $instituteRepo->find($universityId);

        foreach ($instituteObjs as $key=>$obj) {
        	if($obj->getId() == $universityId)
        		unset($instituteObjs[$key]);
        }

        $sortedInstitutes = array();
        foreach ($instituteObjs as $obj) {

        	$instituteName = $obj->getShortName();
            $instituteName = $instituteName ? $instituteName : $obj->getName();
        	$sortedInstitutes[strtolower(trim($instituteName))] = $obj;
        }
        ksort($sortedInstitutes);

        $data['institutes']    = $sortedInstitutes;
		$data['universityObj'] = $universityObj;
		$data['instituteIds'] = $affiliatedInstitutesIds;

		return $data;
    }

    function getTopWidgetData($instituteObj,$cityString){
    	$static_data = $this->CI->config->item('static_data');
    	$instituteType = $instituteObj->getType();
    	$inlineData = array();

    	if(!empty($cityString)){
    	    $inlineData['city'] = trim($cityString,',');
    	}
    	
    	$establish_year = $instituteObj->getEstablishedYear();
    	if(!empty($establish_year)){
    	    $inlineData['establish_year'] = 'Established '.$establish_year;
    	}
    	if($instituteType == 'institute'){
    	    $autonomous = $instituteObj->isAutonomous();
    	    if(!empty($autonomous)){
    	        $inlineData['autonomous'] = "Autonomous Institute";
    	    }
    	    $nationalImportance = $instituteObj->isNationalImportance();
    	    if(!empty($nationalImportance)){
    	        $inlineData['nationalImportance'] = "Institute of National Importance";
    	    }
    	}
    	else if($instituteType == 'university'){
    	    $isUGCApproved = $instituteObj->isUGCApproved();
    	    if(!empty($isUGCApproved)){
    	        $inlineData['ugc_approved'] = "UGC Approved";
    	    }
    	    $univSpecificationType = $instituteObj->getUniversitySpecificationType();
    	    if(!empty($univSpecificationType)){
    	        foreach ($static_data['university_type'] as $value) {
    	            if($value['value'] == $univSpecificationType){
    	                $inlineData['university_type_'.$univSpecificationType] = $value['label']." University";
    	                break;
    	            }
    	        }
    	    }
    	}
    	$accreditation = $instituteObj->getAccreditation();
    	if(!empty($accreditation)){
    	    $inlineData['naac_accreditation'] = "Grade '".$accreditation."' accredited by NAAC";
    	}
    	return $inlineData;
    }

    function getAdmissionPageCoursesData($listingObj, $selectedStreamId='', $selectedCourseId = '',$preFetchedCourseIds = array()){
    	$result = array();
    	if(empty($preFetchedCourseIds))
    		$coursesData = $this->getInstituteCourseIds($listingObj->getId(), $listingObj->getType());
    	else
    		$coursesData =  $preFetchedCourseIds;

    	$this->coursedetailmodel = $this->CI->load->model("nationalCourse/coursedetailmodel");

    	$courseIds = $coursesData['courseIds'];

    	$courseIds = $this->coursedetailmodel->filterCoursesWithAdmissionDetails($courseIds);

    	if(empty($courseIds)){
    		return $result;
    	}

    	$popularCourseIds = $this->getCourseViewCount($courseIds);
    	$popularCourseIds = array_keys($popularCourseIds);
    	$mostPopularCourse = reset($popularCourseIds);

		$streamIdsCount        = array();
		$sortedStreamIds       = array();
		$courseCollegeGrouping = array();
		$otherCourses          = array();
		$mostPopularStream     = "";
		$filteredCourseIds     = array();

		$baseCourseMapping     = array();
		
		$admissionUrl = ($listingObj->getURL())."/admission";
		if($courseIds){
			if(!empty($selectedCourseId) && !in_array($selectedCourseId,$courseIds)){
				header("Location: $admissionUrl",TRUE,301);
			}
	    	$this->CI->load->builder("nationalCourse/CourseBuilder");
			$builder          = new CourseBuilder();
			$courseRepository = $builder->getCourseRepository();
			$courseObjects = $courseRepository->findMultiple($courseIds);

			foreach ($courseObjects as $courseObj) {
				$courseTypeInfo = $courseObj->getCourseTypeInformation();
				foreach ($courseTypeInfo as $courseTypeObj) {
					if($courseTypeObj){
						$hierarchy = $courseTypeObj->getHierarchies();
						foreach ($hierarchy as $hierarchyRow) {

							if($mostPopularCourse == $courseObj->getId())
								$mostPopularStream = $hierarchyRow['stream_id'];
						}
					}
				}
			}
			foreach ($courseObjects as $courseObj) {
				
				$courseStreams = array();
				// get stream ids
				$courseTypeInfo = $courseObj->getCourseTypeInformation();
				foreach ($courseTypeInfo as $courseTypeObj) {
					if($courseTypeObj){
						$hierarchy = $courseTypeObj->getHierarchies();
						foreach ($hierarchy as $hierarchyRow) {

							$streamIdsCount[$hierarchyRow['stream_id']]++;
							$courseStreams[] = $hierarchyRow['stream_id'];
						}
					}
				}
				if(empty($selectedStreamId))
					$selectedStreamId = $mostPopularStream;

				if(in_array($selectedStreamId, $courseStreams) || empty($selectedStreamId)){

					// group courses by offering college
					$offeringCollege = $courseObj->getOfferedByName();
					$offeringCollegeId = $courseObj->getOfferedById();
					if($offeringCollege)
						$courseCollegeGrouping[htmlentities($offeringCollege)."__".$offeringCollegeId][$courseObj->getName()."_".$courseObj->getId()] = $courseObj;
					else
						$otherCourses[$courseObj->getName()."_".$courseObj->getId()] = $courseObj;

					$filteredCourseIds[] = $courseObj->getId();
				}
				$baseCourseIdForCourse = $courseObj->getBaseCourse();
				if(!empty($baseCourseIdForCourse))
				{
					$baseCourseMapping[$courseObj->getId()] = $baseCourseIdForCourse['entry'];
				}
			}

			arsort($streamIdsCount);
			$sortedStreamIds = array_keys($streamIdsCount);
			
			if(!empty($selectedStreamId) && !in_array($selectedStreamId, $sortedStreamIds))
			{	
				header("Location: $admissionUrl",TRUE,301);
			}

			// sort the course college group alphabetically
			ksort($courseCollegeGrouping);
			foreach ($courseCollegeGrouping as &$value) {
				ksort($value);
			}

			ksort($otherCourses);
			if(empty($courseCollegeGrouping))
				$courseCollegeGrouping['other'] = $otherCourses;
			else if($otherCourses)
				$courseCollegeGrouping['other'] = $otherCourses;
		}

		$streamObjs = array();
		if($sortedStreamIds){
			$this->CI->load->builder("listingBase/ListingBaseBuilder");
			$listingBaseBuilder = new ListingBaseBuilder();
			$streamRepo     = $listingBaseBuilder->getStreamRepository();	
			$streamObjs = $streamRepo->findMultiple($sortedStreamIds);
		}

		if(!empty($selectedStreamId)){
			$mostPopularStream = $selectedStreamId;

			$popularCourseIds = $this->getCourseViewCount($filteredCourseIds);
	    	$popularCourseIds = array_keys($popularCourseIds);
	    	$mostPopularCourse = reset($popularCourseIds);
		}

		if(!empty($selectedCourseId))
			$mostPopularCourse = $selectedCourseId;

		$sortedStreams = $streamObjs;
		usort($sortedStreams, function($a, $b){
			return ($a->getName() < $b->getName()) ? -1 : 1;
		});

		$result['streams']               = $streamObjs;
		$result['sortedStreams']         = $sortedStreams;
		$result['courseObjects']         = $courseObjects;
		$result['courseCollegeGrouping'] = $courseCollegeGrouping;
		$result['mostPopularStream']     = $mostPopularStream;
		$result['mostPopularCourse']     = $mostPopularCourse;
		$result['baseCourseMapping']	 = json_encode($baseCourseMapping);

		return $result;

    }

    public function _checkForCommonRedirections($instituteObj, $listingId, $listingType, $ampViewFlag=false){
        $currentUrl = getCurrentPageURLWithoutQueryParams();

         /*If institute id does'nt exist, check whether the status of institute is deleted,
          if yes then 301 redirect to migrated institute page Or show 404 */
        $this->institutedetailsmodel = $this->CI->load->model('nationalInstitute/institutedetailsmodel');
        if(empty($instituteObj) || $instituteObj->getId() == ''){
               $newUrl = $this->institutedetailsmodel->checkForDeletedInstitute($listingId,$listingType);
               if(!empty($newUrl)){
					if( (strpos($newUrl, "http") === false) || (strpos($newUrl, "http") != 0) || (strpos($newUrl, SHIKSHA_HOME) === 0) || (strpos($newUrl,SHIKSHA_ASK_HOME_URL) === 0) || (strpos($newUrl,SHIKSHA_STUDYABROAD_HOME) === 0) || (strpos($newUrl,ENTERPRISE_HOME) === 0) ){
						header("Location: $newUrl",TRUE,301);
					}
					else{
					    header("Location: ".SHIKSHA_HOME,TRUE,301);
					}
                    exit;
               }else{
                    show_404();
                    exit(0);
               }
        }

         //check for dummy institute.If true, show 404 error 
        if($instituteObj->isDummy() == TRUE){
                show_404();
                exit(0);
        }

        if(!empty($instituteObj) && ($instituteObj->getId() != '')){

            $seo_url     = ($ampViewFlag)?$instituteObj->getAmpURL():$instituteObj->getURL();         
            $disable_url = $instituteObj->getDisableUrl();

            $queryParams = array();

            $queryParams = $_GET;
            
            if(!empty($queryParams) && count($queryParams) > 0)
            {
                $seo_url .= '?'.http_build_query($queryParams);
            }
        	$instUrl = ($ampViewFlag)?$instituteObj->getAmpURL():$instituteObj->getURL();

            //check if url is different from original url, 301 redirect to main url
            if(($currentUrl != $instUrl) || ($instituteObj->getType() != $listingType)){
                header("Location: $seo_url",TRUE,301);
                exit;
            }
           
            //Redirect to disabled url
            if($disable_url != ''){
                header("Location: $disable_url",TRUE,302);
                exit;
            }
        }

    }

    /*
    * Use $this->anarecommendationlib->getInstituteAnaCounts (cached) 
    *     for multiple institutes/universities
    */
    function getCountofQuestionsForListings($listingId,$listingType,$preFetchedCourseIds,$allInstitutesCourses)
    {
    	if(empty($listingId) || empty($listingType))
    	{
    		return 0;
    	}
    	if($listingType == 'university')
    	{
    		if(empty($preFetchedCourseIds))
	            $universityChildren = $this->getInstituteCourseIds($listingId,'university');
	        else
	            $universityChildren = $preFetchedCourseIds;
	        
	        $allInstitutes = array_keys($universityChildren['type']);
    	}
        else
        {
        	if(empty($allInstitutesCourses))
        	{
        		$allInstitutesCourses=$this->getAllCoursesForInstitutes($listingId);
        	}
	       	$allInstitutes=array_keys($allInstitutesCourses['type']);
	        if(empty($allInstitutes)){
	            $allInstitutes=array();
	        }
	        $allInstitutes[] = $listingId;
        }
        $result = $this->institutedetailsmodel->getQuestionCountForListing($allInstitutes);

        return !empty($result) ? $result : 0;
    }

    /*
    * Use $this->reviewrecommendationlib->getInstituteReviewCounts (cached) 
    *     for multiple institutes/universities
    */
    function getCountOfReviewsForListings($listingId,$listingType,$preFetchedCourseIds,$allInstitutesCourses)
    {
    	if(empty($listingId) || empty($listingType))
    	{
    		return 0;
    	}

    	$this->reviewmodel = $this->CI->load->model('ContentRecommendation/reviewrecommendationmodel');
    	if($listingType == 'university')
    	{
    		if(empty($preFetchedCourseIds)){
	            $universityChildren = $this->getInstituteCourseIds($listingId,'university');
	        }
	        else{
	            $universityChildren = $preFetchedCourseIds;
	        }
	        
	        $universityInstitutes = array_keys($universityChildren['type']);
	        $universityInstitutes[]=$listingId;
	        $filteredIds = $this->CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($universityInstitutes,'institute','course');
	    }
	    else if($listingType == 'institute')
	    {
	    	if(empty($allInstitutesCourses))
	    	{
	    		$allInstitutesCourses=$this->getAllCoursesForInstitutes($listingId);	
	    	}
	        $allInstitutes=array_keys($allInstitutesCourses['type']);
	        if(empty($allInstitutes)){
	            $allInstitutes=array();
	        }
	        $allInstitutes[] = $listingId;
	        $filteredIds = $this->CI->reviewrecommendationmodel->getListingsByListingsWithMinCourseReviews($allInstitutes,'institute','course');
	    }
	    $result = $this->institutedetailsmodel->getReviewCountForListing($filteredIds, $listingId);
	    return !empty($result) ? $result : 0;
    }
    /*
    * Use $this->articlerecommendationlib->getInstituteArticleCounts (cached) 
    *     for multiple institutes/universities
    */
    function getCountOfArticlesForListing($listingId,$listingType,$preFetchedCourseIds,$allInstitutesCourses)
    {
    	if(empty($listingId) || empty($listingType))
    	{
    		return 0;
    	}
    	if($listingType == 'university')
    	{
    		if(empty($preFetchedCourseIds))
            	$universityChildren = $this->getInstituteCourseIds($listingId,'university');
        	else
            	$universityChildren = $preFetchedCourseIds;
        
        	$universityChildrenId = array_keys($universityChildren['type']);	
        	$filteredIds = $universityChildrenId;
    	}
    	else
    	{
    		$allInstitutesCourses=$this->getAllCoursesForInstitutes($listingId);
	        $allInstitutes=array_keys($allInstitutesCourses['type']);
	        if(empty($allInstitutes)){
	            $allInstitutes=array();
	        }
	        $allInstitutes[]= $listingId;

	        $filteredIds = $allInstitutes;
    	}
    	$result = $this->institutedetailsmodel->getArticleCoutForListing($filteredIds);
	    return !empty($result) ? $result : 0;    	
    }

/*
	* All course data for compare widget on institute all X pages
	*/
	function getInstitutePageCompareWidgetData($listingId, $listingType = 'institute'){

		// get all courses
		$allCourseList = $this->getInstituteCourseIds($listingId, $listingType);
		$allCourseList   = $allCourseList['courseIds'];
		
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
	    $instituteBuilder = new InstituteBuilder();
	    $this->instituteRepo = $instituteBuilder->getInstituteRepository();
        $instituteObj = $this->instituteRepo->find($listingId,'full');
		
   		$instituteLocations  = $instituteObj->getLocations();
		$isMultilocation   = count($instituteLocations) > 1 ? true : false;

		
		if($isMultilocation && $listingType != 'university'){

			$currentLocationObj  = $this->getInstituteCurrentLocation($instituteObj);
			
			$instituteLocationId  = $currentLocationObj->getLocationId();
			
			$eligibleLocationCourses  = $this->institutedetailsmodel->getCoursesHavingLocations($allCourseList, $instituteLocationId);

			$eligibleLocationCourses = array_values(array_unique($eligibleLocationCourses));
			$allCourseList = $eligibleLocationCourses;

		}

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();
		// getting all courses because of all base course/stream section
		if($allCourseList){
			$allCourses  = $this->courseRepo->findMultiple($allCourseList, array('basic'), false, false);
		}

		foreach($allCourses as $key=>$value){
            if(!$value->getId()){
                unset($allCourses[$key]);
            }
        }
		foreach($allCourses as $key=>$courseObj){
        	$instituteName = $courseObj->getOfferedByShortName();
            $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
            $instituteName = $instituteName ? $instituteName : $instituteObj->getName();
            $allCourses[$key]->displayInstituteName=$instituteName;
		}
		return $allCourses;
    }

    function getSeoData($instituteObj, $questionCount = 0, $reviewCount = 0, $courseCount = 0,$currentLocation) {
    	
    	$seoTitle = $instituteObj->getSeoTitle();
    	$seoDescription = $instituteObj->getSeoDescription();

    	$metaDescriptionConfig = $this->CI->config->item('metaDescription');
    	$metaTitleConfig = $this->CI->config->item('metaTitleForUILP');
		$listingType = $instituteObj->getType();
		$secondaryName = $instituteObj->getSecondaryName();
		$city = $instituteObj->getMainLocation()->getCityName();

		$instituteNameData = getInstituteNameWithCityLocality($instituteObj->getName(),$listingType,$currentLocation->getCityName());
		$location = $currentLocation->getCityName();
		
		$currentYear = date('Y');
	    $currentMonth = date('m');
	    if($currentMonth > 10 ){
	        $currentYear = $currentYear + 1;
	    }

		//both title and description empty
    	if(empty($seoTitle) && empty($seoDescription)){
    		
			if(empty($secondaryName)){
				$metaDescriptionConfig = $metaDescriptionConfig['secondaryNameNotAvailable'];
			}
			else{
				$metaDescriptionConfig = $metaDescriptionConfig['secondaryNameAvailable'];
			}
		    	
		    $search = array('<reviewCount>', '<questionCount>', '<courseCount>', '<fullNameWithLocation>','<secondaryName>');
		    $replace = array($reviewCount, $questionCount, $courseCount, $instituteNameData['instituteString'],$secondaryName);
		    
		    //all available
		    if($questionCount > 0 && $reviewCount > 0 ) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['allAvailable']);
		    }
		    else if($questionCount > 0 &&  empty($reviewCount)) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['questions']);
		    }
		    else if($reviewCount > 0 && empty($questionCount)) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['reviews']);
		    }
		    else {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['noneAvailable']);
		    }

		    $title = $metaTitleConfig;
		    $search = array('<fullNameWithLocation>','<year>');
		    $replace = array($instituteNameData['instituteString'], $currentYear);
		    
		    $seoTitle = str_replace($search, $replace, $title);
    	}

    	//only description added using cms
    	else if(empty($seoTitle) && !empty($seoDescription)){

    		$title = $metaTitleConfig;
		    $search = array('<fullNameWithLocation>','<year>');
		    $replace = array($instituteNameData['instituteString'], $currentYear);
		    
		    $seoTitle = str_replace($search, $replace, $title);
    	}

    	//only title added using cms
    	else if(!empty($seoTitle) && empty($seoDescription)){
		   
			if(empty($secondaryName)){
				$metaDescriptionConfig = $metaDescriptionConfig['secondaryNameNotAvailable'];
			}
			else{
				$metaDescriptionConfig = $metaDescriptionConfig['secondaryNameAvailable'];
			}
		  
		    $search = array('<reviewCount>', '<questionCount>', '<courseCount>', '<fullNameWithLocation>','<secondaryName>');
		    $replace = array($reviewCount, $questionCount, $courseCount, $instituteNameData['instituteString'],$secondaryName);
		    
		    //all available
		    if($questionCount > 0 && $reviewCount > 0 ) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['allAvailable']);
		    }
		    else if($questionCount > 0 &&  empty($reviewCount)) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['questions']);
		    }
		    else if($reviewCount > 0 && empty($questionCount)) {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['reviews']);
		    }
		    else {
		        $seoDescription = str_replace($search, $replace, $metaDescriptionConfig['noneAvailable']);
		    }
    	}

        if(empty($instituteNameData['cityString'])){
        	$keywords = array($instituteObj->getName());
        }
        else{
        	$keywords = array($instituteObj->getName(),$instituteObj->getName().' '.$city);
        }
        
        
    	$keywords[] = $instituteObj->getName().' courses';
    	$keywords[] = $instituteObj->getName().' admission';
    	$keywords[] = $instituteObj->getName().' latest news';
    
        return array('title' => special_chars_replace($seoTitle), 'description' => special_chars_replace($seoDescription), 'keywords' => implode(',',$keywords));
    }

    function  getSeoDataForScholarshipPage(&$instituteObj){
    	$location = $instituteObj->getMainLocation()->getCityName();
    	$instituteNameData = getInstituteNameWithCityLocality($instituteObj->getName(),$listingType,$location);
    	$metaDescriptionConfig = $this->CI->config->item('metaDescription');
    	$metaTitleConfig = $this->CI->config->item('metaTitle');
    	$metaTitleConfig = $metaTitleConfig['ScholarshipPage'];
    	$metaDescriptionConfig = $metaDescriptionConfig['ScholarshipPage'];
    	$abbreviation = $instituteObj->getAbbreviation();
    	if(empty($abbreviation)){
    		$title = $metaTitleConfig['noAbbreviation'];
    		$description = $metaDescriptionConfig['noAbbreviation'];
    	}
    	else{
    		$title = strlen($instituteObj->getName()) > 30 ? $metaTitleConfig['abbreviationWithMoreChars'] : $metaTitleConfig['abbreviationWithLessChars'];
    	 	$description = strlen($instituteObj->getName()) > 30 ? $metaDescriptionConfig['abbreviationWithMoreChars'] : $metaDescriptionConfig['abbreviationWithLessChars'];
    	}
    	$search = array('<location>','<fullNameWithLocation>','<abbreviation>');
    	$replace = array($location,$instituteNameData['instituteString'],$abbreviation);
    	$seoData['seoTitle'] = str_replace($search, $replace, $title);
    	$seoData['seoDesc'] = str_replace($search, $replace, $description);
    	return $seoData;
    }

    function getInstitutesFromExams($examIds, $limit,$fromWhere = '') {
    	if(empty($examIds)) {
    		return;
    	}

    	//get courses from exams
    	$this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_DB_Cache_start');
    	$result = $this->courseDetailLib->getCoursesFromExams($examIds);
    	$this->CI->benchmark->mark('Bottom_Institute_Interlinking_From_Exam_DB_Cache_end');
    	
    	foreach ($result as $key1 => $courses) {
    		foreach ($courses as $key2 => $value) {
    			$courseIds[] = $value['course_id'];
    			$courseIdInsttIdMapping[$value['course_id']] = $value['institute_id'];
    		}
    	}
    	//get courses sorted on view count (365 days)
    	$this->CI->benchmark->mark('Bottom_Institute_Interlinking_View_Count_start');
    	$courseIds = array_keys($this->listingCommonLib->listingViewCount('course', $courseIds));
    	$this->CI->benchmark->mark('Bottom_Institute_Interlinking_View_Count_end');

    	$instituteIds = array(); $count = 0;
    	if($fromWhere == 'examPage')
    	{
    		foreach ($courseIds as $key => $courseId) {
	        	$insttId = $courseIdInsttIdMapping[$courseId];
	        	if(!isset($instituteIds[$insttId])) {
	        		$instituteIds[$insttId] = $courseId;	
	        		$count++;
	        	}
	        }
	        if(!empty($limit))	
	        {
	        	$instituteIds = array_slice($instituteIds, 0,$limit,true);
	        }
	        return array('instCourseMapping' => $instituteIds,'totalCount' => $count);
    	}
    	else
    	{	
    		foreach ($courseIds as $key => $courseId) {
	        	if($count == $limit) {
	        		break;
	        	}
	        	$insttId = $courseIdInsttIdMapping[$courseId];
	        	if(!isset($instituteIds[$insttId])) {
	        		$instituteIds[$insttId] = $insttId;
	        		$count++;
	        	}
	        }
	        return array_keys($instituteIds);
    	}
    }

    function getInstitutesFromBaseCourses($baseCourseIds, $limit) {
    	if(empty($baseCourseIds)) {
    		return;
    	}

    	$result = $this->institutedetailsmodel->getInstitutesFromBaseCourses($baseCourseIds, $limit);
    	foreach ($result as $key => $value) {
    		$instituteIds[] = $value['institute_id'];
    	}

    	return $instituteIds;
    }

    public function getInstituteTopCoursesByFilters($instituteIds, $filterEntityIds, $courseLimitPerInstitute) {
		$result = $this->institutedetailsmodel->getInstituteCoursesByFilters($instituteIds, $filterEntityIds);

		$institutesWithFilters = array();
		foreach ($result as $key => $value) {
			$courseIds[] = $value['course_id'];
			$courseIdInsttIdMapping[$value['course_id']][] = $value['institute_id'];

			$institutesWithFilters[] = $value['institute_id'];
		}

		//hit institutes for which no course was found above
		$result = array();
		foreach ($instituteIds as $key => $instituteId) {
			$result[$instituteId] = $this->getAllCoursesForInstitutes($instituteId);
			$courseCount[$instituteId] = count($result[$instituteId]['courseIds']);
		}
		
        foreach ($result as $instituteId => $value) {
        	if(!in_array($instituteId, $institutesWithFilters)) {
        		foreach ($value['courseIds'] as $key => $courseId) {
        			$courseIds[] = $courseId;
					$courseIdInsttIdMapping[$courseId][] = $instituteId;
	        	}
			}
    	}
    	
    	//get courses sorted on view count (365 days)
    	$courseIds = array_keys($this->listingCommonLib->listingViewCount('course', $courseIds));

    	$instituteIds = array(); $count = 0; $topCourseIds = array();

        foreach ($courseIds as $key => $courseId) {
        	$insttIds = $courseIdInsttIdMapping[$courseId];
        	foreach ($insttIds as $key => $insttId) {
        		if(count($topCourseIds[$insttId]) < $courseLimitPerInstitute) {
	        		$topCourseIds[$insttId][] = $courseId;
	        	}
        	}
        }
        
        return array('topCourses' => $topCourseIds, 'courseCount' => $courseCount);
	}

	function getSponsoredWidgetData($listingId, $instituteHasPaidCourse) {
		$sponsoredWidgetData = array();
		if(!$instituteHasPaidCourse) {
			$sponsoredWidgetFreeInstitutes = $this->CI->config->item('sponsoredWidgetFreeInstitutes');
			$sponsoredWidgetConfigData = $this->CI->config->item('sponsoredWidgetPaidData');
			if(!empty($sponsoredWidgetFreeInstitutes[$listingId]) && !empty($sponsoredWidgetConfigData[$sponsoredWidgetFreeInstitutes[$listingId]])) {
				$sponsoredWidgetData = $sponsoredWidgetConfigData[$sponsoredWidgetFreeInstitutes[$listingId]];
			}

		}
		return $sponsoredWidgetData;
	}

	function sendInstituteDigestMailForUser($userId, $instituteId){
		$this->CI->load->library("common/jobserver/JobManagerFactory");
		try {
		    $jobManager = JobManagerFactory::getClientInstance();
		    if ($jobManager) {
		    	if(!empty($userId)  && !empty($instituteId)){
		    		$mailerData['instituteId'] = $instituteId;
		    		$mailerData['userId'] = $userId;
		    		$jobManager->addBackgroundJob("InstituteDigestMailerQueue", $mailerData);
		    	}
		    }
		}catch (Exception $e) {
		    error_log("Unable to connect to rabbit-MQ");
		}
	}

	function isPaidInstitute($instituteIds){
		return $this->institutedetailsmodel->isPaidInstitute($instituteIds);
	}

	function getInstitutePaidStatus($instituteIds){ //$instituteIds should be array
		$this->nationalinstitutecache = $this->CI->load->library('nationalInstitute/cache/NationalInstituteCache'); 
		return $this->nationalinstitutecache->getInstitutePaidStatus($instituteIds);		
	}

	function getSeoDataForAllChildPages($displayData) {
		if($displayData['pageType'] == 'reviews') {
			$seoHeadingText= "Check latest reviews and ratings on placements, faculty, facilities submitted by students & alumni.";
		}

		if($displayData['pageType'] == 'admission') {
			$seoHeadingText="Find details of the admission process, cutoffs, eligibility & dates for all courses.";
		}

		if($displayData['pageType'] == 'articles') {
			$seoHeadingText= "Get latest news and notifications on placements, cutoff, admission, fees, ranking & eligibility.";
		}

		if($displayData['pageType'] == 'questions') {
			$seoHeadingText= "Get the latest answers on cutoff, courses, placements, admission, fees, ranking & eligibility. All answers have been submitted by students, alumni & experts.";
		}
		return $seoHeadingText;
	}

	function getCanonnicalUrl($listingId,$canonicalUrl = ''){
			global $AdmissonOfficeIdCanonical;
			$newIdForCanonical = $AdmissonOfficeIdCanonical[$listingId];
			if(!empty($newIdForCanonical) && $listingId != $newIdForCanonical){
				$instituteBuilder = new InstituteBuilder();
				$instituteRepo = $instituteBuilder->getInstituteRepository();
				$listingObj = $instituteRepo->find($newIdForCanonical);
				return $listingObj->getURL(); 
			}
			return $canonicalUrl;
	}

	function getAllAffiliatedInstitutesForUniversities($universityId, $universityScope = 'domestic'){
		$affiliatedInstituteIds = $this->institutedetailsmodel->getAffiliatedInstitutesForUniversity(array($universityId), $universityScope);
		return $affiliatedInstituteIds;
	}

	function getAllInstitutesInHierarchy($universityId){
		$allInstituteIds = $this->institutedetailsmodel->getAllInstitutesInHierarchy(array($universityId));
		return $allInstituteIds;
	}

} ?>
