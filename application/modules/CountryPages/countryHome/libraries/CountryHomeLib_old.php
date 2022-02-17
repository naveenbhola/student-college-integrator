<?php
/*
 * Library  : CountryPageHomeLib
 * Module   : CountryPageHome/countryPageHome
 * Purpose  : All re-usable functionality and model access.
 */
class CountryHomeLib {
    private $CI;
    private $countryHomeModel;
    private $cacheLib;
    private $studyAbroadHomepageModel;
    private $useCache = true;
    private $cacheTimeLimit = 90000;
    private $desiredCourses;
    private $abroadCommonLib;
    private $abroadListingCommonLib;
    private $categoryBuilder;
    private $categoryRepository;
    private $abroadCategoryPageLib;
    private $samodel ;
            function __construct(){
        $this->CI =& get_instance();
        $this->_setDependencies();
    }
    
    private function _setDependencies(){
        $this->CI->load->library('Common/cacheLib');
        $this->cacheLib = new cacheLib();
        $this->countryHomeModel         = $this->CI->load->model('countryHome/countryhomemodel');
        $this->abroadCommonLib          = $this->CI->load->library('listingPosting/AbroadCommonLib');
        $this->abroadListingCommonLib   = $this->CI->load->library('listing/AbroadListingCommonLib');
        $this->studyAbroadHomepageModel = $this->CI->load->model('studyAbroadHome/studyabroadhomepagemodel');
        $this->abroadCategoryPageLib    = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
        $this->samodel                  = $this->CI->load->model('blogs/sacontentmodel');
        
        //Listing repositories needed for various widgets
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 		= new ListingBuilder;
        $this->universityRepository = $listingBuilder->getUniversityRepository();
        $this->abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
	$this->CI->load->builder('CategoryBuilder','categoryList');
        $this->categoryBuilder = new CategoryBuilder;
        //load repository
    	$this->categoryRepository = $this->categoryBuilder->getCategoryRepository();
        // catgeory page request library
        $this->abroadCategoryPageRequest=$this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        
        
    }
    
    
    /*
     * Author   : Abhinav
     * Purpose  : To get Country from given URL string
     */
    public function getCountry($param){
        /*
        $formatedString = str_ireplace('','study-in',$param);
        $formatedString = preg_replace("/[^A-Za-z0-9]/",'',$formatedString);
        */
        $formatedString = str_ireplace('study-in','',$param);
        $formatedString = preg_replace("/[^A-Za-z]/",'',$formatedString);
        $formatedString = strtolower($formatedString);
        $locationBuilder = $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $abroadCountries = $locationRepository->getAbroadCountries();
        $abroadCountries = array_change_key_case($abroadCountries);
        if(array_key_exists($formatedString,$abroadCountries)){
            return $abroadCountries[$formatedString];
        }else{
            $countrySimilarity;
            foreach($abroadCountries as $key=>$value){
                similar_text($key,$formatedString,$countrySimilarity[$key]);
            }
            arsort($countrySimilarity);
            $result = reset(array_keys($countrySimilarity));
            if($countrySimilarity[$result] > 80){
                return $abroadCountries[$result];
            }else{
                if($formatedString == 'all'){
                    return $abroadCountries['all'];
                } else {
                    return false; // Code review comments as on 18-04-2015 for SA-1737 and SA-1745
                }
            }
        }
    }
    
    
    /*
     * Author   : Abhinav
     * Purpose  : To get recommended URL for CountryHome page URL
     */
    public function getCountryHomeUrl($countryObject = NULL){
        if($countryObject == NULL || !($countryObject instanceof Country)){
            return '';
        }
        $countryHomeUrl = '';
        $countryName = $countryObject->getName();
        
        if($countryName == 'All'){
            return $this->getAllCountryHomeUrl();
        }
        $countryHomeUrl = SHIKSHA_STUDYABROAD_HOME.'/'.seo_url_lowercase($countryName); // SA-1736
        return $countryHomeUrl;
    }
    
    /*
     * Author   : Abhinav
     * Purpose  : To get URL for All CountryHome page URL
     */
    public function getAllCountryHomeUrl(){
        $countryHomeUrl = SHIKSHA_STUDYABROAD_HOME.'/abroad-countries-countryhome';
        return $countryHomeUrl;
    }
    
    /*
     * Author   : Abhinav
     * Purpose  : To get SEO related data for CountryHome page
     */
    public function getCountryHomeSeoData($countryObject = NULL){
        if($countryObject == NULL || !($countryObject instanceof Country)){
            return array();
        }
        $seoData = array();
        $countryName = $countryObject->getName();
        if($countryName == 'All'){
            $seoData['canonicalUrl']    = $this->getAllCountryHomeUrl();
            $seoData['seoTitle']           = 'Best Study Abroad Countries | Shiksha.com';
            $seoData['seoDescription']     = 'Find the best study abroad countries for undergraduate and postgraduate programs on Shiksha.com. Get information on cities and program options.';
        }else{
            $seoData['canonicalUrl']    = $this->getCountryHomeUrl($countryObject);
            $seoData['seoTitle']           = 'Study in '.$countryName.' - Colleges, Courses, Eligibility, Cost & Visa Details';
            $seoData['seoDescription']     = 'Want to study in '.$countryName.' ? Find out about universities, courses, admission, fees, visa requirements, work permit, cost of living etc. in '.$countryName.'.';
        }
        
        
        return $seoData;
        
    }
    
    
    
    function getUniversityWidgetData($countryId, $courseId = 0){
        $limitOffset	= 0;
        $limitRowCount= 4;
        $result =array();
        
        $abroadCategoryPageLib = $this->CI->load->library('categoryList/AbroadCategoryPageLib');
		$queryData = array();
		if($courseId>0){
			$queryData['ldbCourseId'] = $courseId;
		}
        $resultTemp = $abroadCategoryPageLib->getCountryPageCourseDataForUniversities($countryId, $queryData);
        
		$result['totalCount'] = $this->countryHomeModel->getCountryUniversityCount($countryId);
        $abroadListingCommonLib = $this->CI->load->library('listing/abroadListingCommonLib');
		$courseViewCountArray = $abroadListingCommonLib->getViewCountForListingsByDays(array_keys($resultTemp['courselist']),'course',7);
        arsort($courseViewCountArray);
		$ranUniversityIds = array();
		foreach($courseViewCountArray as $id=>$count){
			$ranUniversityIds[] = $resultTemp['courselist'][$id];
			$ranUniversityIds = array_unique($ranUniversityIds);
			if(count($ranUniversityIds)==$limitRowCount){
				break;
			}
		}
    
        $result['viewAllUniversityPageUrl'] = $this->getViewAlluniversityCountryPageUrl($countryId,$resultTemp['totalCount']);
        
        $abroadUniversityRepository = $this->universityRepository;
		
        $universityArr = array();
        if(!empty($ranUniversityIds))
          $universityArr = $abroadUniversityRepository->findMultiple($ranUniversityIds);
        
        $universityData = array();
        foreach($universityArr as $universityObj)
        {
            $id = $universityObj->getId();
            $universityData[$id]["university_id"] 	= $id;
            $universityData[$id]["university_name"] 	= $universityObj->getName();
            $universityData[$id]["url"] 		= $universityObj->getURL();
            $universityData[$id]["university_type"] 	= $universityObj->getTypeOfInstitute();
            $universityData[$id]["establishment_year"] 	= $universityObj->getEstablishedYear();
            // course data
			$courseList = explode(',',$resultTemp['result'][$id]['courseList']);
			// top viewed course must be picked
			$courseList = $abroadListingCommonLib->getViewCountForListingsByDays($courseList,'course',21);
			arsort($courseList);
			$universityData[$id]["courseId"] = reset(array_keys($courseList));	
            // get location data
            $locations = $universityObj->getLocation();
            if($locations)
            {
                    $stateName 	= $locations->getState();
                    $cityName  	= $locations->getCity();
                    $country 	= $locations->getCountry();
                    $stateName 	= empty($stateName) ? "" : $locations->getState()->getName();
                    $cityName  	= empty($cityName) ? "" : $locations->getCity()->getName();
                    $country  	= empty($country) ? "" : $locations->getCountry()->getName();
            }
            $universityData[$id]["stateName"] 	= $stateName;
            $universityData[$id]["cityName"] 	= $cityName;
            $universityData[$id]["country"] 	= empty($country_id) ? $country : "";
            
            //Get First Photo for university
            $univPhotos = $universityObj->getPhotos();
            if(count($univPhotos))
            {
                $imgUrl = $univPhotos['0']->getThumbURL('300x200');
            } else {
                $imgUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
            }
            
            $universityData[$id]["photos"]      = $imgUrl;       
        }
		
		// get course Objects
		$courseData = $this->abroadCourseRepository->findMultiple(array_map(function($a){ return $a['courseId']; },$universityData));
		$courseFeeData = $this->abroadListingCommonLib->getCourseFeesDetails($courseData);
		$courseData = $this->abroadListingCommonLib->sortEligibilityExamForAbroadCourses($courseData);
		foreach($universityData as $univId=>$univData)
		{
			$universityData[$univId]['courseName'] = $courseData[$universityData[$univId]['courseId']]->getName();
			$universityData[$univId]['courseFee'] = $courseFeeData[$universityData[$univId]['courseId']]['toFormattedFeesIndianDisplayableFormat'];
			$exams = $courseData[$universityData[$univId]['courseId']]->getEligibilityExams();
			$count = 0;
			foreach($exams as $exam)
			{
				if($exam->getId() == -1)
				{
					continue;
				}
				if($count ==2){ break; }
				if($exam instanceof AbroadExam)
				{	$examScore = $exam->getCutOff();
					$universityData[$univId]['courseExams'][] = $exam->getName()." : ".($examScore == "N/A"?"Accepted":$examScore);
					$count++;
				}
			}
			$universityData[$univId]["url"] .="?refCourseId=".$universityData[$univId]['courseId'];
			$universityData[$univId]['courseLink'] = $courseData[$universityData[$univId]['courseId']]->getURL();
		}
		//_p($universityData);die;
        $result['universityData'] = $universityData;
        //_p($result);
        return $result;
    }
    
    private function getViewAlluniversityCountryPageUrl($countryId,$totalCount){
        $categoryPageRequest = $this->CI->load->library('categoryList/AbroadCategoryPageRequest');
        $countryPageUrl = $categoryPageRequest->getURLForCountryPage($countryId);
        return $countryPageUrl;
    }
    
    
    /*
     * function to process given list of widget to remove any widget that either
     * 1. need not be rendered (manually switched off)
     * 2. not available on a country
     * 3. sorted by order in which they appear
     * @params : reference to list of wigets
     */
    public function processWidgetList($countryPageWidgetPlacementList, $countryObj)
    {
        // loop across the widget list
        foreach ($countryPageWidgetPlacementList as $key => $item)
        {  // check if manually switched off
            if(!$item['status'])
            {  // remove from list
               unset($countryPageWidgetPlacementList[$key]);
            }
            else{
                // Prepare title for widgets
                if($key == 'countryHomeFilteredColleges'){
                // populate drop down having MBA,MS,BE-Btech.. needs to be in the title
                // .. prepare html, perform str_replace as per the config
                }
                else{
                    $countryPageWidgetPlacementList[$key]['title'] = str_replace('{country}',$countryObj->getName(),$countryPageWidgetPlacementList[$key]['title']);
                }
            }
            // .. add further processing here
    }
        
        // sort the array by their order/sequence ...
        uasort($countryPageWidgetPlacementList, function($a,$b){return $a['order'] > $b['order'];});
        // return processed widget list
        return $countryPageWidgetPlacementList;
    }
    

    public function getFeaturedCollegesData($countryId){
        $this->abroadcategorymodel = $this->CI->load->model("categoryList/abroadcategorypagemodel");
        $university_ids = $this->abroadcategorymodel->getFeaturedCollegesData($countryId);
        $university_ids = array_filter($university_ids,is_numeric);
        //select upto  4 universities randomly
        if(count($university_ids)>4){
            $keys = array_rand($university_ids,4);
            $random_ids = array();
            foreach($keys as $key){
                $random_ids[] = $university_ids[$key];
            }
            $university_ids = $random_ids;
        }
        
        if(empty($university_ids)){
            return array("universities"=>array(),"count"=>0);
        }
        $universities = $this->universityRepository->findMultiple($university_ids);
        $defaultPhotoUrl = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
        $processedUniversities = array();
        foreach($universities as $university){
            $processedUniversities[$university->getId()]['name'] = $university->getName();
            $pic = reset($university->getPhotos());
            if(!empty($pic)){
                $processedUniversities[$university->getId()]['photo'] = $pic->getThumbURL("300x200");
            }
            else{
                $processedUniversities[$university->getId()]['photo'] = $defaultPhotoUrl;
            }
            $locations = $university->getLocation();
            $address = '';
            if($locations)
            {
                    $state 	= $locations->getState();
                    $city  	= $locations->getCity();
                    $address.= empty($city) ? "" : $city->getName();
                    $address.= ($state->getName() =='') ? "" : ", ".$state->getName();
            }
            $processedUniversities[$university->getId()]['location'] = $address;
            $processedUniversities[$university->getId()]['type'] = $university->getTypeOfInstitute();
            $processedUniversities[$university->getId()]['year'] = $university->getEstablishedYear();
            $processedUniversities[$university->getId()]['url']  = $university->getURL();
        }
        return array("universities"=>$processedUniversities,"count"=>count($processedUniversities));
    }
    

    public function getPopularArticlesWidgetData($countryId) {
        $model = $this->CI->load->model('blogs/sacontentmodel');
	$articlesData = $model->getArticlesByPopularity($countryId, '12');
	return $articlesData;
    }
    
    /*
     * Author   : Abhinav
     * Purpose  : To check if widget data have data for any widgets
     * Behavior : If none of the widgets have data to show, redirect to all countries page
     * Return   : True if any of the widget have data
     */
    public function checkWidgetData($widgetData){
        foreach($widgetData as $key=>$value){
            if(!empty($value)){
                return true;
            }
        }
        $allCountryHomeUrl = $this->getAllCountryHomeUrl();
        redirect($allCountryHomeUrl,'location',302);
    }
    
    public function getDownloadWidgetData($countryId,$articleType, $articlesCount)
    {
        $articleModel = $this->CI->load->model('blogs/sacontentmodel');
        $guideData = $articleModel->getArticlesByCountryAndType($countryId,$articleType, $articlesCount);
        
        $guideIds = array();
        $finalResult = array();
        
        foreach($guideData as $dataobj)
        {
            if($dataobj['is_downloadable'] =='yes' && $dataobj['download_link']!=''){
                $guideIds[] = $dataobj['content_id'];
                $finalResult[$dataobj['content_id']] = $dataobj;
            }
        }
        if(count($guideIds)>0){
            $downloadData = $articleModel->downloadCountForGuide($guideIds);
            foreach($finalResult as $guideObj)
            {
                $guideObj['totalDownloadCount'] = ($downloadData[$guideObj['content_id']] >0)?$downloadData[$guideObj['content_id']]:0;
                $finalResult[$guideObj['content_id']] = $guideObj;
            }
            
            usort($finalResult,function($a,$b){
                if($a['totalDownloadCount'] == $b['totalDownloadCount']){
                    return 0;
                }else{
                    return ($a['totalDownloadCount'] < $b['totalDownloadCount'])? 1 : -1;
                }
            });
        }
        
        return $finalResult;
    }

    
    /*
     * function to get popular courses widget data
     * @params : country Object
     */
    public function getPopularCourseWidgetDataForCountry($countryObj)
    {
        // fetch the list of desired courses
        $this->desiredCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
        
        $params = array('desiredCourses' => $this->desiredCourses,'countryObj' => $countryObj);
        $data = $this->_getAndSaveCountryWisePopularCoursesInCache($params);

        return $data;			
    }
    
    
    /*
     * function to get popular course data from db & save in cache
     */
    public function _getAndSaveCountryWisePopularCoursesInCache($params)
    {
        $key = "studyAbroadCountryWisePopularCourses_".$params['countryObj']->getId();
        if( ($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
            // get popular courses by country
            $data = $this->studyAbroadHomepageModel->getCountryMapData(array($params['countryObj']->getName()),$params['desiredCourses']);
            // get categorypage data for these courses
            //$data = $this->_getCategoryPageDataForPopularCourses($data,$params['countryObj']);
            $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
        }
        else{
            $data = $this->cacheLib->get($key);
        }
        
        // get categorypage data for these courses
        $data = $this->_getCategoryPageDataForPopularCourses($data,$params['countryObj']);
        
        return $data;
    }
    
    /*
     * function to add course count, name, url, etc from catgeory page
     */
    private function _getCategoryPageDataForPopularCourses($data,$countryObj)
    {
        $data = $data[$countryObj->getName()]['topCourses'];
        // process course data
        foreach($data as  $key => $topCourses)
        {
            // params to set data in category apge request
            $catPageParams = array();
            if(isset($topCourses['subCategoryId']) && $topCourses['subCategoryId']>0){
                    $categoryObject  = $this->categoryRepository->find($topCourses['subCategoryId']);
                    $catPageParams['categoryId'] = $categoryObject->getParentId();
                    $catPageParams['courseLevel'] = strtolower($topCourses['courseLevel']);
                    if($catPageParams['courseLevel']==1){
                             $catPageParams['courseLevel'] = "";
                    }
                    $catPageParams['subCategoryId'] = $topCourses['subCategoryId'];
                    $catPageParams['countryId'] = array($countryObj->getId());
                    $catPageParams['LDBCourseId'] = 1;
            
                    $this->abroadCategoryPageRequest->setData($catPageParams);
                    $url = $this->abroadCategoryPageRequest->getUrl();
                    $name = $categoryObject->getName();
                    
                    $data[$key]['courseTitle']       = $topCourses['courseLevel']." in ".$name;
                    $data[$key]['categoryPageURL']  = $url;
                    //echo "<a onClick='studyAbroadTrackEventByGA(\"ABROAD_HOME_PAGE\", \"CountryMap_PopularCourses\" , \"$countryName\");' href='$url'>".$topCourses['courseLevel']." in ".$name."</a>";
            }
            else if(isset($topCourses['SpecializationId']) && $topCourses['SpecializationId']>0){
                    $catPageParams['countryId'] = array($countryObj->getId());
                    $catPageParams['LDBCourseId'] = $topCourses['SpecializationId'];
                    $catPageParams['courseLevel'] = "";
                    $catPageParams['categoryId'] = 1;
                    $catPageParams['subCategoryId'] = 1;
                    $this->abroadCategoryPageRequest->setData($catPageParams);
                    $url = $this->abroadCategoryPageRequest->getUrl();
                    
                    $data[$key]['courseTitle']       = $topCourses['CourseName'];
                    $data[$key]['categoryPageURL']  = $url;
                    //echo "<a onClick='studyAbroadTrackEventByGA(\"ABROAD_HOME_PAGE\", \"CountryMap_PopularCourses\" , \"$countryName\");' href='$url'>".$topCourses['CourseName']."</a>";
            }
            $popularCourseData = $this->countryHomeModel->getCoursesForPopularCourseWidget($catPageParams);
            $data[$key]['courseCount'] = $popularCourseData['tupleCount'];
            if($data[$key]['courseCount'] != 0)
            {
                // get fee & exam of all courses..
                if($catPageParams['courseLevel'] != "")
                {
                    $courseLevel = $catPageParams['courseLevel'];
                }
                else if(in_array($topCourses['CourseName'],array('MBA','MS')))
                {
                    $courseLevel = 'Masters';
                }
                else if($topCourses['CourseName'] == 'BE/Btech')
                {
                    $courseLevel = 'Bachelors';
                }
                $averageCalculationParams = array(
                                                  'courseLevel'=>$courseLevel,
                                                  'completeCourseList'=>$popularCourseData['courses']
                                                  );
                // get exam score & first year fees
                $avgFeesExam = $this->_getAverageFeesAndExamScore($averageCalculationParams);
                $data[$key]['avgFees']      = $avgFeesExam['avgFees'];
                $data[$key]['avgExamScore'] = $avgFeesExam['avgExam'];
            }
            else{
                unset($data[$key]);
            }
            //exam
        }// end for..
        //_p($data);
        return $data;
    }
    
    /*
     * function to get average of first year fee value for all courses
     */ 
    private function _getAverageFeesAndExamScore($params)
    {
        $courseLevel = $params['courseLevel'];
        // get course objects
        $completeCourseSet = $this->abroadCourseRepository->findMultiple($params['completeCourseList']);
        //_p($completeCourseSet);
        // init number of courses that had valid first year fees
        $validFeesCourseCount = $sumValidFeesCount = 0;
        $examSetToBeProcessed = array();
        $examScores =array();
        $caeRange = array('A'=>3,'B'=>2,'C'=>1);
        // exams to be processed for avg calculation
        
        switch(strtolower($courseLevel))
        {
            case 'bachelors'            : $examSetToBeProcessed = array('SAT'  => array(),'IELTS'=> array(),'TOEFL'=> array()); break;
            case 'masters'              : $examSetToBeProcessed = array('IELTS'=> array(),'TOEFL'=> array(),'GRE'  => array(),'GMAT' => array()); break;
            case 'phd'                  : $examSetToBeProcessed = array('GRE'  => array(),'GMAT' => array(),'TOEFL'=> array(),'IELTS'=> array()); break;
            case 'certificate - diploma': $examSetToBeProcessed = array('IELTS'=> array(),'TOEFL'=> array(),'SAT'  => array()); break;
            default                     : break;
        }
        
        // loop across each course
        foreach($completeCourseSet as $key => $courseObj)
        {
            // check total fees
            if($courseObj->getTotalFees()->getValue()>0)   //if fees exists
            {
                $validFeesCourseCount++;
                $fees = $courseObj->getTotalFees();     //get the fees
                $sumValidFeesCount += $this->abroadListingCommonLib->convertCurrency($fees->getCurrency(),1,$fees->getValue());   //take the sum of fees
            }
            //check exam score
            foreach($courseObj->getEligibilityExams() as $exam)
            {   // prepare cutoff
                if($exam->getName() == 'CAE')
                {
                    $examCutOff = $caeRange[$exam->getCutOff()];
                }
                else{
                    $examCutOff = $exam->getCutOff();
                }
                // push for processing..
                if(in_array($exam->getName(),array_keys($examSetToBeProcessed)))
                {
                    if($examCutOff != 'N/A')
                    {
                        array_push($examSetToBeProcessed[$exam->getName()],$examCutOff);
                    }
                }// end if
                else
                {
                    if($examCutOff != 'N/A')
                    {
                        $examSetToBeProcessed[$exam->getName()] = array($examCutOff);
                    }
                }
            }// end foreach exam
        } // end foreach course
        
        // process fees and calculate the average
        //$avgFees = round((float)($sumValidFeesCount / $validFeesCourseCount),-5,PHP_ROUND_HALF_UP)/100000;
        //$avgFees .= ($avgFees >1 ?" lakhs":" lakh");
        $avgFees = round((float)($sumValidFeesCount / $validFeesCourseCount));
        $avgFees = ($avgFees > 0)?$this->abroadListingCommonLib->getIndianDisplableAmount($avgFees, 2):'';//_p($avgFees);die;
        //process exam score average
        $examSummedScores = array();
        foreach($examSetToBeProcessed as $examNameKey => $examScore)
        {
            $totalRecords       = count($examScore);
            $sum                = array_sum($examScore);
            if($totalRecords > 0) // check if there were any records at all in this exam
            {
                $examSummedScores[] = array('examNameKey'=>$examNameKey, 'totalRecords'=>$totalRecords, 'sum'=>$sum);
            }
        }
        
        // pick the exam with highest priority
        $examScores = reset($examSummedScores);
       
        $avgExam = array('examName'=>$examScores['examNameKey'],
                         'avgScore'=>$examScores['sum']/$examScores['totalRecords']
                         );
        // rounding off exam scores based on exams..
        switch($avgExam['examName'])
        {
            
            case 'CAEL' :
            case 'GMAT' :   $avgExam['avgScore'] = round($avgExam['avgScore'],-1,PHP_ROUND_HALF_UP);
                            break;
            case 'IELTS':   $avgExam['avgScore'] = round($avgExam['avgScore'],1,PHP_ROUND_HALF_UP);
                            $diff = $avgExam['avgScore'] - (int)$avgExam['avgScore'];
                            if($diff != 0.5 && $diff != 0 ) 
                            {
                                $avgExam['avgScore'] += ($diff<0.5 ? 0.5 - $diff:1 - $diff);
                                $diff = 1 - $diff;
                            }
                            break;
            case 'CAE'  :   $avgExam['avgScore'] = round($avgExam['avgScore'],0,PHP_ROUND_HALF_UP);
                            $avgExam['avgScore'] = array_search((int)$avgExam['avgScore'],$caeRange);
                            break;
            case 'SAT'  : 
            case 'PTE'  : 
            case 'TOEFL':
            case 'GRE'  :
            default     :   $avgExam['avgScore'] = round($avgExam['avgScore'],0,PHP_ROUND_HALF_UP);
                            break;
        }

        // return averages
        return array('avgFees'=>$avgFees, 'avgExam'=>$avgExam);
    }

    public function getCountryCourseOrder($countryId){
        return $this->countryHomeModel->getCountryCourseOrder($countryId);
    }

    public function prepareDataForFeesAndAffordabilityWidget($countryId, $courseId){
        $this->CI->load->library("categoryList/AbroadCategoryPageRequest");
        $request = new AbroadCategoryPageRequest;
        $request->setData(array('countryId'=>array($countryId),'LDBCourseId'=>$courseId));
        $url = $request->getURL();
        $cheapest = $url.'?sortby=increasingfees';
        $scholarship = $url.'?moreoptions=scholarship';
        $public = $url.'?moreoptions=publiclyfunded';
        $this->categorypagemodel = $this->CI->load->model("categoryList/abroadcategorypagemodel");
        $courseCounts = $this->categorypagemodel->getLDBCourseCountsForCountries(array($countryId),array($courseId));
        $scholarshipCount = $this->getCountOfScholarshipUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
        $publicCount = $this->getCountOfPublicUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
        $graphData = $this->getGraphDataForFeesAndAffordabilityWidget($countryId,$courseId);
        return array(
            'cheapest'=>array('url'=>$cheapest,'count'=>(integer)reset(reset($courseCounts))),
            'scholarship'=>array('url'=>$scholarship,'count'=>$scholarshipCount),
            'public'=>array('url'=>$public,'count'=>$publicCount),
            'graphData'=>$graphData
        );
    }

    public function prepareDataForCountryOverviewWidget($countryId){
        $val = reset($this->countryHomeModel->getCountryOverviewWidgetData($countryId));
        $finalArray = array();
        if($val['visaArticleLink'] == ""){
            $finalArray['visa'] = false;
        }else{
            $finalArray['visa'] = array('complexity' => $val['visaProcessComplexity'],'timeline'=>$val['visaTimeline'],'description'=>$val['visaDescription'],'link'=>SHIKSHA_STUDYABROAD_HOME.$val['visaArticleLink']);
            if(!$val['visaFeeUnit']){
                $finalArray['visa']['fees'] = false;
            }else if($val['visaFeeUnit'] == 1){   // INR
                $finalArray['visa']['fees'] = 'Rs '.$this->abroadListingCommonLib->formatMoneyAmount(round($val['visaFeeAmount']),1,1).'/-';
            }else{
                $currencySymbol = $this->abroadListingCommonLib->getCurrencyCodeById($val['visaFeeUnit']);
                $convertedValue = $this->abroadListingCommonLib->convertCurrency($val['visaFeeUnit'],1,$val['visaFeeAmount']);
                $finalArray['visa']['fees'] = $currencySymbol.' '.$this->abroadListingCommonLib->formatMoneyAmount($val['visaFeeAmount'],$val['visaFeeUnit']).' = Rs '.$this->abroadListingCommonLib->formatMoneyAmount(round($convertedValue),1,1).'/-';
            }
        }
        if($val['partTimeWorkArticleLink'].$val['postStudyWorkArticleLink'] == ""){
            $finalArray['work'] = false;
        }else{
            $preLink = empty($val['partTimeWorkArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$val['partTimeWorkArticleLink'];
            $postLink = empty($val['postStudyWorkArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$val['postStudyWorkArticleLink'];
            $finalArray['work'] = array(
                'prestatus'=>$val['partTimeWorkStatus'],
                'prehours' => $val['partTimeWorkHours'], 
                'predays'=>$val['partTimeWorkDays'],
                'preDescription'=>$val['partTimeWorkDescription'],
                'preLink'=>$preLink,
                'poststatus'=>$val['postStudyWorkStatus'],
                'posthours' => $val['postStudyWorkHours'], 
                'postdays'=>$val['postStudyWorkDays'],
                'postDescription'=>$val['postStudyWorkDescription'],
                'postLink'=>$postLink
            );
        }
        if($val['economicArticleLink'].$val['popularSectorArticleLink'] == ""){
            $finalArray['economy'] = false;
        }else{
            $growthLink = empty($val['economicArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$val['economicArticleLink'];
            $sectorLink = empty($val['popularSectorArticleLink'])?'':SHIKSHA_STUDYABROAD_HOME.$val['popularSectorArticleLink'];
            $finalArray['economy'] = array(
                'percentage'=>$val['ecocnomicGrowthRate'],
                'growthDescription'=>$val['economicDescription'],
                'growthLink'=>$growthLink,
                'sectorDescription'=>$val['popularSector'],
                'sectorLink'=>$sectorLink
            );
        }
        return $finalArray;
    }

    public function getCountOfScholarshipUniversitiesByCountryIdDesiredCourse($countryId,$courseId){
        $key = 'scholarshipUnivCountByCountryLDBCourse'.$countryId."-".$courseId;
        $count = $this->cacheLib->get($key);
        if($this->useCache && $count != "ERROR_READING_CACHE" && $count){
            return $count;
        }
        $count = $this->categorypagemodel->getCountOfScholarshipUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
        $this->cacheLib->store($key,$count,$this->cacheTimeLimit);
        return $count;
    }

    public function getCountOfPublicUniversitiesByCountryIdDesiredCourse($countryId,$courseId){
        $key = 'publicUnivCountByCountryLDBCourse'.$countryId."-".$courseId;
        $count = $this->cacheLib->get($key);
        if($this->useCache && $count!="ERROR_READING_CACHE" && $count){
            return $count;
        }
        $count = $this->categorypagemodel->getCountOfPublicUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
        $this->cacheLib->store($key,$count,$this->cacheTimeLimit);
        return $count;
    }

    public function storeCountsForFeeAffordabilityWidget(){
        // $this->CI->benchmark->mark("start");
        $this->categorypagemodel = $this->CI->load->model("categoryList/abroadcategorypagemodel");
        $abroadCountries = $this->categorypagemodel->getCountriesHavingUniversities();
        $countryIds = array_map(function($ele){return $ele['country_id'];}, $abroadCountries);
        $ldbCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $courseIds = array_map(function($ele){return $ele['SpecializationId'];},$ldbCourses);
        error_log("::INFO:: Starting storage for ".count($countryIds)." countries for ".count($ldbCourses)." desired courses");
        $this->useCache = false;
        foreach($countryIds as $countryId){
            foreach($courseIds as $courseId){
                $this->getCountOfScholarshipUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
                $this->getCountOfPublicUniversitiesByCountryIdDesiredCourse($countryId,$courseId);
            }
        }
        // $this->CI->benchmark->mark("end");
        // echo "Time Taken : ".$this->CI->benchmark->elapsed_time("start","end");//  ~773 ms
        error_log("::INFO:: Completed storage of all countries and desired courses");
    }
	/*
	 * function to get Exam score widget data
	 */
	public function getEligibilityExamScoreWidgetData($countryId, $ldbCourseId) {


                $examMaster = $this->abroadCommonLib->getAbroadExamsMasterList('examId');
                $topExamsToBeShown = $this->abroadCategoryPageLib->getExamOrderByCountryAndLDBCourse(array("LDBCourseId" => $ldbCourseId,
                    "countryId" => $countryId));
                $topTwoExamsToBeShown = array_slice($topExamsToBeShown, 0, 2);
                $mostPopularExamArticles = $this->samodel->getMostPopularExamArticles($topTwoExamsToBeShown);
                $mostPopularExamArticlesDisplayData = $this->_getMostPopularExamArticleDisplayData($mostPopularExamArticles, 2, $examMaster);

                $listOfExamIds = array_values($topTwoExamsToBeShown);
                $listOfExams = array_filter(array_map(
                                function($a) use($listOfExamIds) {
                            if (in_array($a['examId'], $listOfExamIds)) {
                                return $a;
                            }
                        }, $examMaster));
                $graphData = $this->_getExamAverageGraphData($listOfExams, $countryId, $ldbCourseId);
                $result = array(
                    'contentSectionData' => $mostPopularExamArticlesDisplayData,
                    'graphSectionData' => $graphData
                );
                return $result;
    }

    public function _getMostPopularExamArticleDisplayData($examArticlesList,$numberOfArticlesToBeShownPerExam,$examMasterListByExamIDHash){
            $examMostPopularDisplayList=array();
            foreach ($examArticlesList as $examArticle){
                $examType=$examArticle['exam_type'];
                if(count($examMostPopularDisplayList[$examType])>=$numberOfArticlesToBeShownPerExam){
                    continue;
                }
                $examArticle['exam_name']=$examMasterListByExamIDHash[$examType]['exam'];
                if(!(is_array($examMostPopularDisplayList[$examType]))){
                    $examMostPopularDisplayList[$examType]=array();
                }
                array_push($examMostPopularDisplayList[$examType], $examArticle);
            }
            return $examMostPopularDisplayList;
        }
        /*
	 *  function to create graph data for exam score avg graph
	 *  @ param: details of exams for which graph is to be drawn, country id & ldbcourseid 
	 */
	private function _getExamAverageGraphData($listOfExams, $countryId, $ldbCourseId)
	{
		$maxBarSize = 100;
		if(count($listOfExams)==0){
			return false;
		}
		$abroadListingCache = $this->CI->load->library('listing/cache/AbroadListingCache');
		$graphData = array();
		// get avg value & total records for each exam in the list to be processed
		foreach($listOfExams as $exam)
		{
			$examAvgData = $abroadListingCache->getAverageExamScores($countryId."-".$ldbCourseId."-".$exam['examId']);
			if($examAvgData !== false)
			{
				if($exam['exam'] == "CAE"){
					$allGrades = explode(',',$exam['range']);
					$allGrades = array_flip(array_reverse($allGrades));
					$height = ($maxBarSize/count($allGrades))*($allGrades[$examAvgData['avg']]+1);
				}
				else{
					$height = ($maxBarSize/$exam['maxScore'])*$examAvgData['avg'];
				}
				$examAvgData['heightCSS'] = 'height:'.$height.'px !important;';
				$graphData[$exam['exam']] = $examAvgData;
			}
		}
		return $graphData;
	}
	
	public function checkIfFirstTimeVisitor()
    {
        $cookie_val = json_decode($_COOKIE['SACPFirstTimeVisitor'],TRUE);
        
        if($cookie_val['SACountryHomeFirstTimeVisitor'] == 1){
            $showGutterHelpText = 0;
        }
        else {
            $showGutterHelpText = 1;
            $cookie_val['SACountryHomeFirstTimeVisitor'] = 1;
            setcookie("SACPFirstTimeVisitor", json_encode($cookie_val), time()+60*60*24*30, "/", COOKIEDOMAIN);
        }
        return $showGutterHelpText;
    }

    public function getGraphDataForFeesAndAffordabilityWidget($countryId,$courseId){
        $lib = $this->CI->load->library("listing/cache/AbroadListingCache");
        $avgFirstYearFees = $lib->getAverage1stYearFees($countryId."-".$courseId);
        $avgLivingExpenses = $lib->getAverageLivingExpense($countryId);
        $avgTotal = $avgFirstYearFees['average'] + $avgLivingExpenses['average'];
        if($avgFirstYearFees['average'] < $avgLivingExpenses['average']){
            $avgFirstYearFees['ratio'] = $avgFirstYearFees['average']/$avgLivingExpenses['average'];
            $avgLivingExpenses['ratio'] = 1;
        }else{
            $avgFirstYearFees['ratio'] = 1;
            $avgLivingExpenses['ratio'] = $avgLivingExpenses['average'] / $avgFirstYearFees['average'];
        }
        $avgFirstYearFees['average'] = getShortIndianDisplableAmount($avgFirstYearFees['average']);
        $avgLivingExpenses['average'] = getShortIndianDisplableAmount($avgLivingExpenses['average']);
        $avgTotal = getShortIndianDisplableAmount($avgTotal);
        return array('firstYear' => $avgFirstYearFees, 'livingExpense'=>$avgLivingExpenses,'total'=>$avgTotal);
    }
	
	/*
	 * Purpose  : To Validate URL. If not recommended URL, redirect to recommended URL
	 * Params   : URL string
	 * Return   : Country Object for which URL was requested
	 */
    public function validateUrlAndGetCountryInfo($param){
        $countryObject = $this->getCountry($param);
        if(!$countryObject){
            if(rtrim(base_url(), "/") == SHIKSHA_STUDYABROAD_HOME){
                show_404_abroad();
            } else {
                show_404();
            }
        }
        $countryHomeUrl = $this->getCountryHomeUrl($countryObject);
        $courseId = $this->CI->input->get('course');
        if(trim(getCurrentPageURLWithoutQueryParams()) != $countryHomeUrl){
            if( intval($courseId) != 0 ){
                $countryHomeUrl .= '?course='.$courseId;
                redirect($countryHomeUrl,'location',301);
            } else {
                redirect($countryHomeUrl,'location',301);
            }
        }
        return $countryObject;
    }
	
	/*
	 * function to get "browse similar study destinations data"
	 * @param: array having countryId, ldbCourseId
	 */
	public function getRecommendedStudyDestinations($widgetParams)
	{
		if(!($widgetParams['countryId'] >0 && $widgetParams['courseId']>0)){
			return false;
		}
		$this->abroadListingModel = $this->CI->load->model('listing/abroadlistingmodel');
		$rawData = $this->abroadListingModel->getRecommendedCountryData($widgetParams['courseId'],$widgetParams['countryId'],4);
		$countrySEO = array();
		$locationBuilder = $this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		foreach($rawData as $data)
		{
			$abroadCountryObject = $locationRepository->findCountry($data['relatedCountry']);
            $countrySEO[$abroadCountryObject->getId()]= $this->getCountryHomeSeoData($abroadCountryObject);
			$countrySEO[$abroadCountryObject->getId()]['countryObj'] = $abroadCountryObject;
        }
		return $countrySEO;
	}
}
?>
