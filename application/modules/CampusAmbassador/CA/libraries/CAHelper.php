<?php

/**
 * 
 * @author rahul
 *
 */
class CAHelper
{

		function __construct()
		{
				$this->CI = & get_instance();
		}		

		/**
		 * @param array $caData
		 * @param bool $edit
		 * @return array containing data for CA_ProfileTable and CA_MainCourseTable
		 */
		
		function reArrangeData($caData , $edit = false) {
			$returnData = array();
			$profileData = array();
	        $this->CI->load->builder("nationalCourse/CourseBuilder");
			$courseBuilder                = new CourseBuilder();
			$courseRepo                   = $courseBuilder->getCourseRepository(); 
	
	     
			// rearranging data for different tables
			if(!$edit) {
				$mainCourseData = array();
					
				$profileData['imageURL'] = (!empty($caData['profileImage']))?$caData['profileImage']:'';
				//$profileData['aboutMe'] = $caData['about_me'];
				$profileData['userId'] = $caData['userId'];
				//$profileData['isOfficial'] = ($caData['is_official'] == 'on')?'Yes':'No';
				/*if($profileData['isOfficial'] == "Yes") {
					$profileData['officialInstituteId'] = $caData['suggested_institutes_official'];
					$profileData['officialCourseId'] = $caData['course_official'];
					$profileData['officialInstituteLocId'] = $caData['location_official'];
					$profileData['officialDesignation'] = $caData['designation'];
					$officialFromArray = explode('/',$caData['fromDateOfficial']);
					$caData['fromDateOfficial'] = $officialFromArray[1].'/'.$officialFromArray[0].'/'.$officialFromArray[2];
					$officialToArray = explode('/',$caData['toDateOfficial']);
					$caData['toDateOfficial'] = $officialToArray[1].'/'.$officialToArray[0].'/'.$officialToArray[2];
					$profileData['officialDateFrom'] = date('Y-m-d H:i:s',strtotime($caData['fromDateOfficial']));
					$profileData['officialDateTo'] = date('Y-m-d H:i:s',strtotime($caData['toDateOfficial']));
				}*/
				$profileData['linkedInURL'] = $caData['linkedInURL'];
				$profileData['facebookURL'] = $caData['facebookURL'];
				$profileData['profileStatus'] = 'draft';
				$profileData['displayName'] = $caData['quickfirstname_ForCA'].' '.$caData['quicklastname_ForCA'];
				$profileData['studentEmail'] = $caData['quickStudentEmail'];
				$profileData['mainAddress'] = $caData['quickAddress_ForCA'];
				$profileData['hasASmartphone'] = $caData['has_a_smartphone'];
					
				$qualificationCount = count($caData['suggested_institutes']);

				// creating separate row data for each qualification
				for ($qualCount = 0 ; $qualCount < $qualificationCount ; $qualCount++) {
					$tempArray = array();
					$tempArray['caId'] = $caData['userId'];
					$tempArray['instituteId'] = $caData['suggested_institutes'][$qualCount];
					$tempArray['courseId'] = $caData['course'][$qualCount];
					$checkPos = strpos($caData['location'][$qualCount], '_');
					if(empty($checkPos)){
						$data['location'] = $caData['location'][$qualCount];
					}
				
					$locationArr = explode('_', $caData['location'][$qualCount]);
					$stateId = $locationArr[0];
					$cityId = $locationArr[1];
					$localityId = $locationArr[2];
					$courseObj  = $courseRepo->find($tempArray['courseId'],array('basic','location'));
					$locations  = $courseObj->getLocations();
					foreach ($locations as $listingLocationId => $locObj) {
						$objStateId    = $locObj->getStateId();
						$objCityId     = $locObj->getCityId();
						$objLocalityId = $locObj->getLocalityId();
						if($objStateId == $stateId && $objCityId == $cityId && $objLocalityId == $localityId){
							$data['location'] = $listingLocationId;		
							break;
						}
					}
					if(!isset($data['location'])){
			        	echo "error";
			        	return;
			        }
					$tempArray['locationId'] = $data['location'];
					$tempArray['yearOfGrad'] = isset($caData['yearOfGrad'][$qualCount]) ? $caData['yearOfGrad'][$qualCount] : 0;
					$tempArray['semester'] = isset($caData['semester'][$qualCount]) ? $caData['semester'][$qualCount] : 0;
					$tempArray['status'] = 'draft';
					$mainCourseData[$qualCount] = $tempArray;
				}

			}else {
				// in case of edit , only changes CA_ProfileTable
				if(!empty($caData['profileImage'])){
					$profileData['imageURL'] = $caData['profileImage'];
				}
				$profileData['linkedInURL'] = $caData['linkedInURL'];
				$profileData['facebookURL'] = $caData['facebookURL'];
				$profileData['profileStatus'] = 'draft';
				$profileData['displayName'] = $caData['quickfirstname_ForCA'].' '.$caData['quicklastname_ForCA'];
				$profileData['id'] = $caData['uniqueId'];
			}
		
			$returnData['CA_ProfileTable'] = $profileData;
			if(!empty($mainCourseData)) {
				$returnData['CA_MainCourseMappingTable'] = $mainCourseData;
			}
		
			return $returnData;
		}		
		
		function rearrangeBadgeInsAndEducation($campusambassador){
				$badgeName = '';$insName = '';$courseName='';
				$courseTimeOfficialArr = array();
				$courseTimeCurrentArr = array();
				foreach($campusambassador[0] as $key=>$value){
				  foreach($value['mainEducationDetails'] as $k=>$v){
				    if($v['isCurrentlyPursuing'] == 'Yes'){
				      $courseTimeCurrentArr[$v['courseId']] = strtotime($v['from']);
				    }else{
				      $courseTimeOfficialArr[$v['courseId']] = strtotime($v['from']);
				    }
				  }
				}
				
				$maxCurrentIndex = array_keys($courseTimeCurrentArr, max($courseTimeCurrentArr));
				foreach($campusambassador[0] as $key=>$value){
				    foreach($value['mainEducationDetails'] as $k=>$v){
				      if($v['isCurrentlyPursuing'] == 'Yes'){
				      if($maxCurrentIndex[0]==$v['courseId']){
					$badgeName  = 'Current Student';
					$insName = $v['insName'];
					$courseName  = $v['courseName'];
				      }
				     }
				    }
				}
				
				if($badgeName=='None' || $badgeName==''){
						foreach($campusambassador[0] as $key=>$value){
						    foreach($value['mainEducationDetails'] as $k=>$v){
							$badgeName = $value['officialBadge'];
							$insName = $value['officailInsName'];
							$courseName  = $value['officailCourseName'];
						     }
						}
				}
				if($badgeName=='None' || $badgeName==''){
				  $maxAlumniIndex = array_keys($courseTimeOfficialArr, max($courseTimeOfficialArr));
				  foreach($campusambassador[0] as $key=>$value){
				    foreach($value['mainEducationDetails'] as $k=>$v){
				      if($maxAlumniIndex[0]==$v['courseId']){
					$badgeName  = $v['badge'];
					$insName = $v['insName'];
					$courseName  = $v['courseName'];
				      }    
				    }
				  }
				}
				$result['badge'] = $badgeName;
				$result['insName'] = $insName;
				$result['courseName'] = $courseName;
				return $result;

		}
		
		function getQuestionCountForCourses($courseIds){
			$this->CI->load->library('listing/cache/ListingCache');
			$this->listingcache = $this->CI->listingcache;

			$this->CI->load->model('CA/cadiscussionmodel');
	        $this->CADiscussionModel = new CADiscussionModel();
	       
	        //$courseArr = array(111548,206763,112272,112272); // for testing only pass course array
	        $coursesQuestionsFromCache = array();
	        $coursesQuestionsFromCache = $this->listingcache->getMultipleCoursesQuestionCount($courseIds);
	        
            $foundInCache = array_keys($coursesQuestionsFromCache);
            $courseArr = array_diff($courseIds, $foundInCache);
	        
	        if(count($courseArr) > 0) {
	            $courseStr = implode(',',$courseArr);
	            $res = $this->CADiscussionModel->_isCAOnCourses($courseStr);
	            if(count($res) > 0) {
	                foreach($res as $key => $value) {  //$key is courseId
	                	$k1 = 0;
	                	$campusRepString = '';
	                	foreach ($value as $k => $v) {
	                		if($k1 != 0){
	                			$campusRepString .= ',';
	                		}
	                		$campusRepString .= $v['userId'];
	                		$k1++;
	                	}
	                    $res = $this->CADiscussionModel->getQuestionCountOnCourses($key,$campusRepString);
	                    $totalQustion[$key] = $res['totalQuestions'];
	                }   
	            }
	        }
	        
	        foreach ($courseArr as $courseId) {
	        	if(!empty($totalQustion[$courseId])) {
	        		$questionCount = $totalQustion[$courseId];
	        	} else {
	        		$questionCount = 0;
	        	}
	        	$this->listingcache->storeQuestionCountForCourse($questionCount, $courseId);
	        }

	        $coursesQuestions = array();
            foreach($courseIds as $courseId) {
                if(isset($coursesQuestionsFromCache[$courseId])) {
                    $coursesQuestions[$courseId] = $coursesQuestionsFromCache[$courseId];
                }
                else if(isset($totalQustion[$courseId])) {
                    $coursesQuestions[$courseId] = $totalQustion[$courseId];
                }
            }

	        return $coursesQuestions;
		}
	function prepareBeaconTrackData($programIdMappingData,$programId,$userId){
		$beaconTrackData = array(
            'pageIdentifier' => 'campusAmbassadorForm',
            'pageEntityId'   => $programId, // No Page entity id for this one
        );
		$hierarchy = array();
		if(isset($programIdMappingData['entityType']) && $programIdMappingData['entityType'] !=''){	
			if($programIdMappingData['entityType']=='stream'){
				$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $programIdMappingData['entityId'],
			        'substreamId' => 0,
			        'specializationId' => 0
					);
	        }elseif($programIdMappingData['entityType'] == 'substream'){
	        	$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
				$listingBaseBuilder   = new ListingBaseBuilder();
				$subStremRepoObj = $listingBaseBuilder->getSubstreamRepository();
				$subStremObjs = $subStremRepoObj->find($programIdMappingData['entityId']);
	        	$beaconTrackData['extraData']['hierarchy'] = array(
					'streamId' => $subStremObjs->getPrimaryStreamId(),
			        'substreamId' => $programIdMappingData['entityId'],
			        'specializationId' => 0
					);
	        }elseif($programIdMappingData['entityType']=='baseCourse'){
	        	$beaconTrackData['extraData']['baseCourseId'] = $programIdMappingData['entityId'];	
	        }
	    }

	    $beaconTrackData['extraData']['viewedUserId'] = $userId;
        $beaconTrackData['extraData']['countryId'] = 2;
		return $beaconTrackData;
	}
}
?>
