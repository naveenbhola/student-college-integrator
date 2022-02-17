<?php

class ListingPageLib {
    
    private $CI = '';
    
    function __construct(){
        $this->CI = &get_instance();
    }
    
    public function addCourseToRecentViewedCourses($courseId){
		$cookieTime = time()+60*60*24*30;
        if(!isset($_COOKIE['recentcourses'])) //case if user is first time visitor
        {
            $recent = json_encode(array($courseId));
            setcookie('recentcourses',$recent,$cookieTime,'/',COOKIEDOMAIN);
        }else{
			$recentCourseArray = json_decode($_COOKIE['recentcourses']);
			//if user is visiting course first time
			if(!in_array($courseId,$recentCourseArray)){
				array_push($recentCourseArray,$courseId);
			}else{
				//if user is visiting same course again then remove it first and then add at last.
				$key = array_search($courseId,$recentCourseArray,true);
				unset($recentCourseArray[$key]);
				array_push($recentCourseArray,$courseId);
			}
			$recentCourseArray = array_slice($recentCourseArray,-10,10);
			$recent = json_encode($recentCourseArray);
			setcookie('recentcourses',$recent,$cookieTime,'/',COOKIEDOMAIN);   
        }
    }
    
    public function getRecentCourseIds(){
        if(isset($_COOKIE['recentcourses'])){
            $recentCourses = json_decode($_COOKIE['recentcourses']);
	    	$recentCourses = array_reverse($recentCourses,false);
        }else{
            $recentCourses = array();
        }
        return $recentCourses;
    }
    
    public function getRecentCoursesData()
    {
        $recentTab = array();
        $recentCourses = $this->getRecentCourseIds();
        $recentCourses = array_filter($recentCourses,'is_numeric');
        if(count($recentCourses)>=3)
        {
            $recentTab['showRecentTab'] = true;
            
            $this->CI->load->builder('listing/ListingBuilder');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepo = $listingBuilder->getAbroadCourseRepository();
            $universityRepo = $listingBuilder->getUniversityRepository();
			/****************** Invalid Argument CASE TRACKING ******************/
					if(!is_array($recentCourses) || count($recentCourses) == 0) {												
						$msg = "\r\n Recent Courses from Cookie: ".implode(",", $recentCourses);
                        $msg .= "\r\n Session Id = ".sessionId()."\r\n";						
						
						$mailheaders = 'From: amit.kuksal@shiksha.com' . "\r\n" .
						'Reply-To: amit.kuksal@shiksha.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						
						mail("satech@shiksha.com", "getRecentCoursesData:: Invalid Argument in AbroadCourseRepository::findMultiple - Course IDs must be non-empty array of integer values",$msg, $mailheaders);
						$recentTab['showRecentTab'] = false;
						return $recentTab;
					}
			/****************** Invalid Argument CASE TRACKING ******************/
			
            $recentCoursesObj = $abroadCourseRepo->findMultiple($recentCourses);
			if(count(array_keys($recentCoursesObj))>0){
				$cookieTime = time()+60*60*24*30;
				$recent = json_encode(array_keys($recentCoursesObj));
				setcookie('recentcourses',$recent,$cookieTime,'/',COOKIEDOMAIN);
			}
			
			
            $finalRecentCourseData = array();
            $abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
            foreach($recentCoursesObj as $courseObj){
				if($courseObj instanceof AbroadCourse){
					/****************** Invalid Argument CASE TRACKING ******************/
					if(!is_numeric($courseObj->getUniversityId()) || $courseObj->getUniversityId() < 1) {
						$msg = "Course Id: ".$courseObj->getId(). "\r\nUniv Id: ".$courseObj->getUniversityId(). "\r\n". "\r\n";
						$msg .= "/var/www/html/shiksha/application/modules/mobileSiteSA/listingPage/libraries/listingPageLib.php". "\r\n". "\r\n";
						$msg .= "\r\n Recent Courses from Cookie: ".implode(",", $recentCourses);
                        $msg .= "\r\n Session Id = ".sessionId()."\r\n\r\n";
						$msg .= "Course Obj: ".serialize($courseObj);
						
						$mailheaders = 'From: amit.kuksal@shiksha.com' . "\r\n" .
						'Reply-To: amit.kuksal@shiksha.com' . "\r\n" .
						'X-Mailer: PHP/' . phpversion();
						
						mail("satech@shiksha.com", "getRecentCoursesData:: Invalid Argument in UniversityRepository",$msg, $mailheaders);
						continue;
					}
					/****************** Invalid Argument CASE TRACKING ******************/

					$universityObj = $universityRepo->find($courseObj->getUniversityId());
				}else{
					continue;
				}
                // temp array to store data
                $temp = array();
                $temp['university_name'] = $courseObj->getUniversityName();
                $temp['country_name'] = $courseObj->getMainLocation()->getCountry()->getName();
                $temp['state_name'] = $courseObj->getMainLocation()->getState()->getName();
                $temp['city_name'] = $courseObj->getMainLocation()->getCity()->getName();
                $temp['university_url'] = $universityObj->getURL();
                
                $universityMedia = $universityObj->getMedia();
                foreach($universityMedia as $mediaObj){
                    if($mediaObj->getType() == 'photo'){
                        $temp['university_image'] = $mediaObj->getThumbURL('300x200');
                        break;
                    }
                }
                
                $feesCurrency = $courseObj->getTotalFees()->getCurrency();
                $feesValue = $courseObj->getTotalFees()->getValue();
                $temp['first_year_fees'] = $abroadListingCommonLib->convertCurrency($feesCurrency,1,$feesValue);
                if($temp['first_year_fees']){
                    $temp['first_year_fees'] = $abroadListingCommonLib->getIndianDisplableAmount($temp['first_year_fees'],2);
                }
                foreach ($courseObj->getEligibilityExams() as $examObj){
                    if($examObj->getId() != -1){
                        if($examObj->getCutoff() == "N/A"){
                            $cutOffText = "Accepted";
                        }else{
                            $cutOffText = $examObj->getCutoff();
                        }
                        $temp['eligibility_exam'][] = array('name' => $examObj->getName(),
                                                             'cutoff' => $cutOffText);
                        
                        // break to allow only two eligibility exams at front-end
                        if(count($temp['eligibility_exam']) == 2){
                            break;
                        }
                    }
                }
                $temp['course_name'] = htmlentities($courseObj->getName());
                $temp['course_url'] = $courseObj->getURL();
				$temp['course_duration'] = $courseObj->getDuration()->getDisplayValue();
                $finalRecentCourseData[] = $temp;
            }
            $recentTab['finalRecentCourseData'] = $finalRecentCourseData;    
            }else{
                $recentTab['showRecentTab'] = false;
            }
        return $recentTab;
        
    }
    
    function prepareDataForUniversityFindCourseWidget($courseListByStream){
		$formatedDataByCourseLevel = array();
		$formatedDataByStream      = array();
		$totalCount = 0;
		foreach($courseListByStream as $stream=>$courseLevel){
			$formatedDataByStream[$stream] = array();
			foreach($courseLevel as $courseLevelName=>$courseDetails){
				if(count($courseDetails['courses'])>0){
					$mergedCourseIds = array_merge(array_keys($courseDetails['courses']),$formatedDataByStream[$stream]);
					$formatedDataByStream[$stream] = $mergedCourseIds;
					$formatedDataByCourseLevel[$courseLevelName][$stream] = array_keys($courseDetails['courses']);
					$totalCount+= count($courseDetails['courses']);
				}
				ksort($formatedDataByCourseLevel[$courseLevelName]);
				if(empty($formatedDataByCourseLevel[$courseLevelName])){
					unset($formatedDataByCourseLevel[$courseLevelName]);
				}
			}
			ksort($formatedDataByStream);
			if(empty($formatedDataByStream[$stream])){
				unset($formatedDataByStream[$stream]);
			}
		}	
		$formatedDataByCourseLevel = $this->sortCourseLevel($formatedDataByCourseLevel);
		return array('dataByCourseLevel'=>$formatedDataByCourseLevel,'dataByStream'=>$formatedDataByStream,'courseCount'=>$totalCount);
    }
    
    function sortCourseLevel($courseLevelList){
		$courseLevelOrder = array(
			'Bachelors' 		=>  1,
			'Masters' 		=>  2,
			'PhD'   		=>  3,
			'Certificate - Diploma' =>  4
		);
        uksort($courseLevelList,
			function ($a,$b) use($courseLevelOrder){
				if($courseLevelOrder[$a] > $courseLevelOrder[$b]){
					return 1;
				}else{
					return -1;
				}
			}
		);
		return $courseLevelList;	
    }
    
    function getActionTypeForRecoLayerBySourcePage($sourcePage){
		$action = "";
		$widget = "";
		$sourceForDownload = '';
		switch($sourcePage){
			case "course":
			case "response_abroad_mobile_course_page_link":
				$widgetForDownload = 'alsoViewed';
				$sourceForDownload = 'course';
				break;
		
			case "category":
			case "response_abroad_mobile_category_page":
				$widgetForDownload = 'alsoViewed';
				$sourceForDownload = 'category';
				break;
			
			case "shortlist":
			case "shortlist_page":
			case "response_abroad_mobile_shortlistPage":
				$widgetForDownload = 'alsoViewed';
				$sourceForDownload = 'shortlist_page';
				break;
			case "searchPage_mob":
			case "response_abroad_mobile_searchPage":
				$widgetForDownload = 'alsoViewed';
				$sourceForDownload = 'searchPage_mob';
				break;
			default:
				$widgetForDownload = 'alsoViewed';
				$sourceForDownload = 'recommendation';
				break;
		}
		return array('widgetName'=>$widgetForDownload,'sourcePage'=>$sourceForDownload);
    }
    
}    
?>
