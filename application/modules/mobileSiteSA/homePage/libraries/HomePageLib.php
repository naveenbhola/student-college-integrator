<?php

class HomePageLib {
    
    private $CI = '';
    
    function __construct(){
        $this->CI = &get_instance();
        $this->homepagemodel = $this->CI->load->model('homePage/homepagemodel');
        $this->abroadCategoryPageRequest = $this->CI->load->library('categoryList/abroadCategoryPageRequest');
    }
    
    
    /*
     *  Author : Rahul Bhatnagar
     *  Function : Get countries of all courses in a particular ldb course or category.
     *  Params : Either LDB ID or Category ID. For categoryId, optionally provide courseLevel as well.
     */
//    public function getCountryDataForCourseSelectionWidget($ldbCourseId, $categoryId, $courseLevel='Bachelors'){
//        $dbResult = $this->homepagemodel->getCountriesForCourse($ldbCourseId, $categoryId, $courseLevel);
//        $urlData = array();
//        if($ldbCourseId == 1){
//            $urlData['categoryId']=$categoryId;
//            $urlData['courseLevel'] = $courseLevel;
//        }
//        elseif($categoryId == 1){
//            $urlData['LDBCourseId'] = $ldbCourseId;
//        }
//
//        $countries = array();
//        foreach($dbResult as $result){
//            $countries[$result['country_id']]['id'] = $result['country_id'];
//            $countries[$result['country_id']]['name'] = $result['name'];
//            $urlData['countryId'] = array($result['country_id']);
//            $this->abroadCategoryPageRequest->setData($urlData);
//            $countries[$result['country_id']]['url'] = $this->abroadCategoryPageRequest->getURL();     // Get this using abroadCategoryPageRequest in shiksha.
//        }
//
//        // Now we need to segregate the top 6 countries from the rest.
//        $importantCountryArray = array(
//            'australia'=>5,
//            'canada'=>8,
//            'germany'=>9,
//            'new zealand'=>7,
//            'uk'=>4,
//            'usa'=>3
//        );
//        $impCountries = array();
//        foreach($importantCountryArray as $impCountry){
//            if(array_key_exists($impCountry,$countries)){
//                $impCountries[$impCountry] = $countries[$impCountry];
//            }
//        }
//        if($ldbCourseId == 1){
//            $impCountries = array_slice($impCountries,0,4); //Get only upto 4 important countries for the second case.
//        }
//
//        //Sort the countries alphabetically
//        usort($countries,function($a,$b){
//            if($a['name'] > $b['name']) return 1;
//            return 0;
//        });
//        $countrySelectionLayerHeaders = array(
//            'ldb'=>array(
//                1508 => 'MBA',
//                1509 => 'MS',
//                1510 => 'B.E / B.Tech'
//            ),
//            'category' => array(
//                239 => 'Business',
//                240 => 'Engineering',
//                241 => 'Computers',
//                242 => 'Science',
//                243 => 'Medicine',
//                244 => 'Humanities',
//                245 => 'Law'
//            )
//        );
//        $type = ($ldbCourseId == 1)?"category":"ldb";
//        $countrySelectionLayerTitle = ($ldbCourseId == 1)?$courseLevel." In ".$countrySelectionLayerHeaders['category'][$categoryId]:$countrySelectionLayerHeaders['ldb'][$ldbCourseId];
//
//        return array("importantCountries"=>$impCountries,"countryList"=>$countries,"type" =>$type,"courseLevel" => $courseLevel,'countrySelectionLayerTitle' => $countrySelectionLayerTitle);
//    }
    
    public function getTrendingCourses(){
        $dateToCheckFor = date("Y-m-d");
        $trendingCourse = array();
        $upperLimit = 30;
        $lowerLimit = 0;
        $this->CI->load->builder('listing/ListingBuilder');
        $listingBuilder = new ListingBuilder;
		$abroadCourseRepo = $listingBuilder->getAbroadCourseRepository();
        $universityRepo = $listingBuilder->getUniversityRepository();
        $dateCheckCounter = 0;
        while(true){
            $result = $this->homepagemodel->getTrendingCourse($dateToCheckFor,$lowerLimit,$upperLimit);
            $courseIds = array();
            foreach ($result['data'] as $resultData){
                $courseIds[] = $resultData['listingId'];
            }
			$courseIds = array_filter($courseIds,'is_numeric');
            if(count($courseIds) > 0){
                $trendingCourse = ($trendingCourse + $abroadCourseRepo->findMultiple($courseIds));
                if(count($trendingCourse) >= 10){ // when course is equal to 10 is obtained
                    $trendingCourse = array_slice($trendingCourse,0,10,TRUE);
                    break;
                }else{ 
                if($result['rows_available'] > $upperLimit){ // data for same date is still available then go for it
                    $lowerLimit = $upperLimit;
                    $upperLimit = $upperLimit + 15;
                }else{ // otherwise go to get data for previous date
                    $dateToCheckFor = date("Y-m-d",  strtotime($dateToCheckFor.'-1 days'));
                    $lowerLimit = 0;
                    $upperLimit = 15;
                }
                }
            }else{
                if((++$dateCheckCounter) == 10){
                    break;
                }
                $dateToCheckFor = date("Y-m-d",  strtotime($dateToCheckFor.'-1 days'));
                $lowerLimit = 0;
                $upperLimit = 15;
            }
            
        }
        $univs = array_map(function($a){ return $a->getUniversityId(); },$trendingCourse);
	if(!empty($univs))
	        $univs = $universityRepo->findMultiple($univs);
        $finalTrendingCourseData = array();
        $abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        foreach($trendingCourse as $courseObj){			
			/****************** Invalid Argument CASE TRACKING ******************/
			if(!is_numeric($courseObj->getUniversityId()) || $courseObj->getUniversityId() < 1) {
				$msg = "Course Id: ".$courseObj->getId(). "\r\nUniv Id: ".$courseObj->getUniversityId(). "\r\n". "\r\n";
				$msg .= "/var/www/html/shiksha/application/modules/mobileSiteSA/homePage/libraries/HomePageLib.php". "\r\n". "\r\n";
				$msg .= "\r\n Trending Courses from DB: ".implode(",", $courseIds);
				$msg .= "\r\n Session Id = ".sessionId()."\r\n\r\n";
				$msg .= "Course Obj: ".serialize($courseObj);
				
				$mailheaders = 'From: amit.kuksal@shiksha.com' . "\r\n" .
				'Reply-To: amit.kuksal@shiksha.com' . "\r\n" .
				'X-Mailer: PHP/' . phpversion();
				
				mail("satech@shiksha.com", "getTrendingCourses:: Invalid Argument in UniversityRepository",$msg, $mailheaders);
				continue;
			}
			/****************** Invalid Argument CASE TRACKING ******************/
				
            // University Object to get Media data and SEO URL
			if((integer)$courseObj->getUniversityId() > 0){								
				/****************** Invalid Argument CASE TRACKING ******************/
				if(!is_numeric($courseObj->getUniversityId()) || $courseObj->getUniversityId() < 1) {
					$msg = "Course Id: ".$courseObj->getId(). "\r\nUniv Id: ".$courseObj->getUniversityId(). "\r\n". "\r\n";
					$msg .= "/var/www/html/shiksha/application/modules/mobileSiteSA/homePage/libraries/HomePageLib.php". "\r\n". "\r\n";
					$msg .= "Course Obj: ".serialize($courseObj);
					
					$mailheaders = 'From: amit.kuksal@shiksha.com' . "\r\n" .
					'Reply-To: amit.kuksal@shiksha.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
					
					mail("amit.kuksal@shiksha.com", "getTrendingCourses:: Invalid Argument in UniversityRepository",$msg, $mailheaders);					
				}
				/****************** Invalid Argument CASE TRACKING ******************/
				
				//$universityObj = $universityRepo->find($courseObj->getUniversityId());
			}else{
				continue;
			}
            // temp array to store data
            $temp = array();
            $temp['university_name'] = $courseObj->getUniversityName();
            $temp['country_name'] = $courseObj->getMainLocation()->getCountry()->getName();
            $temp['state_name'] = $courseObj->getMainLocation()->getState()->getName();
            $temp['city_name'] = $courseObj->getMainLocation()->getCity()->getName();
            $temp['university_url'] = $univs[$courseObj->getUniversityId()]->getURL();
            
            $universityMedia = $univs[$courseObj->getUniversityId()]->getMedia();
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
            // assigning data in finalTrendingCourse array
            $finalTrendingCourseData[] = $temp;
        }
        
        return $finalTrendingCourseData;
    }
    
    function getCountStats(){
			$this->CI->load->library('studyAbroadHome/studyAbroadHomepageLibrary');
			$studyAbroadHomepageLibrary = new studyAbroadHomepageLibrary();
			$data = $studyAbroadHomepageLibrary->getCountStats();
			return $data;
		}

	/*
     * function to get top 10 guides based on popularity count(descending order)
     */
    public function getGuidesForHomePage($guideCount = 10)
    {
		$this->contentpagemodel = $this->CI->load->model('contentPage/contentpagemodel');
		$contentType = array("guide","applyContent","examContent");
        $result = $this->contentpagemodel->getPopularContent($contentType, 10);
        return $result;
    }  
    public function getApplyContent()
    {
            $this->CI->load->library('applyContent/ApplyContentLib');
            $applyContentLib          = new ApplyContentLib();

            $this->CI->config->load('abroadApplyContentConfig');
            $applyContentTypes        = $this->CI->config->item("applyContentMasterList");      
            $dataArray                = array_keys($applyContentTypes);
            $data                     = $applyContentLib->getApplyContentHomePageUrl($dataArray);
            
            return $data;
    }    
}
