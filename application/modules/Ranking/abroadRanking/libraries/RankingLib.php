<?php
/*
 * Library for study abroad ranking pages
 *
 */

class RankingLib {
    private $CI                 ;    
    private $rankingModel       ;
    private $listingBuilder     ;
    private $categoryBuilder    ;
    private $locationBuilder    ;
    private $ldbCourseBuilder   ;
    
    
    function __construct(){
        $this->CI =& get_instance();
        $this->_setDependecies();
    }
    
    function _setDependecies(){
        $this->rankingModel     = $this->CI->load->model('abroadRanking/rankingmodel');
        $this->CI->load->builder("ListingBuilder", "listing");
        $this->listingBuilder   = new ListingBuilder();
        $this->CI->load->builder('CategoryBuilder','categoryList');
        $this->categoryBuilder  = new CategoryBuilder();
        $this->CI->load->builder("LocationBuilder", "location");
        $this->locationBuilder  = new LocationBuilder();
        $this->CI->load->builder("LDBCourseBuilder", "LDB");
        $this->ldbCourseBuilder = new LDBCourseBuilder();
    }
    
    /*
     *
     *  TO DO TASK
     *  Proceed From here
     *  
     */
    
    public function getRankingData($rankingIds=array()){
        return $this->rankingModel->getRankingData($rankingIds);
    }
    
    public function getRankingUrl($rankingObj){//echo "<br/>in lib";
        $parentCategoryId = $rankingObj->getParentCategoryId();
        $subCategoryId = $rankingObj->getSubCategoryId();
        $ldbCourseId = $rankingObj->getLDBCourseId();
        $countryId = $rankingObj->getCountryId();
        $type = $rankingObj->getType();
        $rankingPageId = $rankingObj->getId();
        
        // compute country name
        if($countryId > 2){
            //$locationRepo = $this->locationBuilder->getLocationRepository();
            $countryName = $this->getCountryById($countryId)->getName();
        }else{
            $countryName = "Abroad";
        }
        
        $urlString = "top";
        if($type == "university"){
            $urlString .= "-universities-in-".strtolower($countryName);
        }else{
            //$ldbCourseRepo = $this->ldbCourseBuilder->getLDBCourseRepository();
            $ldbCourseName = $this->getLDBCourse($ldbCourseId)->getCourseName();
            if($subCategoryId == 0 && $parentCategoryId==0){
                // ldb course name in url since subcategory is not mapped and country name
                $urlString .= "-".strtolower($ldbCourseName)."-colleges-in-".strtolower($countryName);
            }elseif($subCategoryId == 0 && $parentCategoryId !=0)
            {
                $categoryObj = $this->getCategoryById($parentCategoryId);
                // course type in url
                $urlString .= "-colleges-for-".strtolower(str_replace(' ','-',$ldbCourseName))."-";
                // category name in url
                $urlString .= "of-".strtolower(str_replace(' ','-',$categoryObj->getName()))."-";
                // sub-category name in url and country name
                $urlString .= "-from-".strtolower($countryName);
                
            }
            else{
                //$categoryRepo = $this->categoryBuilder->getCategoryRepository();
                $subCategoryObj = $this->getCategoryById($subCategoryId);
                $categoryObj = $this->getCategoryById($subCategoryObj->getParentId());
                
                // course type in url
                $urlString .= "-colleges-for-".strtolower(str_replace(' ','-',$ldbCourseName))."-";
                // category name in url
                $urlString .= "of-".strtolower(str_replace(' ','-',$categoryObj->getName()))."-";
                // sub-category name in url and country name
                $urlString .= "in-".strtolower(str_replace(' ','-',$subCategoryObj->getName()))."-from-".strtolower($countryName);
            }
        }
        
        // adding identifier
        $urlString .= "-abroadranking".$rankingPageId;
        
        $urlString = SHIKSHA_STUDYABROAD_HOME."/".seo_url_lowercase($urlString);
        return $urlString;
    }
    
    public function getSeoInfo($rankingPageObject){
        $seoData = $rankingPageObject->getMetaData();
        $seoTitle = $seoData['seoTitle'];
        $seoDescription = $seoData['seoDescription'];
        if($seoTitle != "" && $seoDescription != ""){
            return $seoData;
        }
        
        // Logic to get country name
        if($rankingPageObject->getCountryId() > 2){
            $countryName = "".$this->getCountryById($rankingPageObject->getCountryId())->getName();
        }else{
            $countryName = "Abroad";
        }
        
        // Logic to compute courseName
        $ldbCourseId = $rankingPageObject->getLDBCourseId();
        $parentCategoryId = $rankingPageObject->getParentCategoryId();
        $subCategoryId = $rankingPageObject->getSubCategoryId();
        
        // if subCategory is not present, compute name of course as LDB courseName
        if($subCategoryId==0 && $parentCategoryId==0){
            //$courseName = $this->ldbCourseBuilder->getLDBCourseRepository()->find($ldbCourseId)->getCourseName();
            $courseName = $this->getLDBCourse($ldbCourseId)->getCourseName();
        }elseif($subCategoryId==0 && $parentCategoryId !=0){
            $categoryObj = $this->getCategoryById($parentCategoryId);
            $specializationName = $categoryObj->getName();
            $courseName = $categoryObj->getName();
            $courseLevel = $this->getLDBCourse($ldbCourseId)->getCourseName();
        }
        else{
            //$categoryRepo = $this->categoryBuilder->getCategoryRepository();
            $subCategoryObj = $this->getCategoryById($subCategoryId);
            $categoryObj = $this->getCategoryById($subCategoryObj->getParentId());
            $specializationName = $subCategoryObj->getName();
            $courseName = $categoryObj->getName();
            $courseLevel = $this->getLDBCourse($ldbCourseId)->getCourseName();
        }
        
        $type = $rankingPageObject->getType();
        // if seo title is blank, compute its default value and assign it back to seoData
        if($seoTitle == ""){
            $generatedTitleFlag = true;
            if($type == "university"){
                $seoTitle = "Top Universities in ".$countryName." | Study Abroad";
            }else{
                if($subCategoryId==0 && $parentCategoryId==0){
                    $seoTitle = "Top ".$courseName." Colleges in ".$countryName." | Study Abroad";
                }elseif($subCategoryId==0 && $parentCategoryId !=0)
                {
                    $seoTitle = "Top Colleges for ".$courseLevel." of ".$courseName." from ".$countryName." | Study Abroad";
                }
                else{
                    $seoTitle = "Top Colleges for ".$courseLevel." of ".$courseName." in ".$specializationName." from ".$countryName." | Study Abroad";
                }
            }
            $seoData['seoTitle'] = $seoTitle;
        }
        // if seo description is blank, compute its default value and assign it back to seoData
        if($seoDescription == ""){
            if($countryName =='Abroad'){
                $seoDescription = "View the globally top ranked ".$courseName." colleges. Select and compare colleges by fees, cutoff, and other parameters.";
            }else{
                $seoDescription = "View the top ranked ".$courseName." colleges in ".$countryName.". Select and compare colleges by fees, cutoff, and other parameters.";
            }
            
            $seoData['seoDescription'] = $seoDescription;
        }
        return $seoData;
    }
    /*
     * function to get category/subcategory from category/subcategory id (used in ranking page repository)
     */
    public function getCategoryById($category_id = 0)
    {
        if($category_id == 0)
        {
            return false;
        }
        $categoryRepository  = $this->categoryBuilder->getCategoryRepository();
        $category = $categoryRepository ->find($category_id);
        return $category;
    }
    /*
     * function to get location object from country id
     */
    public function getCountryById($country_id = 0)
    {
        if($country_id == 0)
        {
            return false;
        }
        $locationRepository  = $this->locationBuilder->getLocationRepository();
        $location = $locationRepository->findCountry($country_id);
        return $location;
    }
    /*
     * function to get LDB course Object
     */
    public function getLDBCourse($ldbCourseId = 0){
        if($ldbCourseId == 0){
            return false;
        }
        $ldbCourseRepo = $this->ldbCourseBuilder->getLDBCourseRepository();
        $ldbCourseObj = $ldbCourseRepo->find($ldbCourseId);
        return $ldbCourseObj;
    }

    public function getCurrencyDetailsForRankingPage(){  
        return $this->rankingModel->getCurrencyDetailsForRankingPage();
    }
    
    public function isAllCountryPage($country)
	{
		return ($country == 1)?true:false;
	}
        
    public function isSortAJAXCall()
	{
		return ($_REQUEST['SORTAJAX'] == 1);
	}    
        
    public function getSortingCriteria($rankingId)
	{
		if(!empty($this->sortingCriteria)){
			return $this->sortingCriteria;
		}
		
		if(isset($_COOKIE["saRankSortBy-".$rankingId])){
			$sortingCriteria = $_COOKIE["saRankSortBy-".$rankingId];
		}
		
		$sortingCriteria = trim($sortingCriteria);
		
		if($sortingCriteria != 'none') {
			$explode = explode("_" , $sortingCriteria);
			if(count($explode) > 1){
				$sortBy = $explode[0];
				$order 	= $explode[1];
				$exam	= $explode[2];
			}
		} else {
			$sortBy = 'none';
		}
		
		switch($sortBy){
			case 'fees':
				$sortingCriteria = array('sortBy' => 'fees', 'params' => array('order' => $order));
				break;
				
			case 'rank':
				$sortingCriteria = array('sortBy' => 'rank', 'params' => array('order' => $order));
				break;
				
			case 'exam':
				$sortingCriteria = array('sortBy' => 'exam', 'params' => array('order' => $order,'exam'=>$exam));
				break;
				
			case 'none':
				$sortingCriteria = array('sortBy' => 'none', 'params' => array('order' => 'none'));
				break;
		}
		
		if(!empty($sortingCriteria)) {
			$this->sortingCriteria = $sortingCriteria;
			return $sortingCriteria;
		}
	}
        
        
    public function checkExamValuesAlphabet($exam)
    {
        $listArray = array('9');
        return in_array($exam, $listArray);
    }

    /*
    * URL Validation and rediretion
    */
    public function validateRankingPageURL($recommendedUrl)
    {
        $userEnteredURL = getCurrentPageURLWithoutQueryParams(); 
       
        if($userEnteredURL != $recommendedUrl && $recommendedUrl != "") 
        {
            redirect($recommendedUrl, 'location', 301);
        }
    }
    public function isZeroResultPage($rankingPageObject){
        $rankingPageDataArr =  $rankingPageObject->getRankingPageData();
        foreach($rankingPageDataArr as $ranking=>$value)
        {
            if(!($value['university'] instanceof University) || ($value['university'] instanceof University && $value['university']->getId() == "")){
                continue;
            }else{
                return false;
            }
        }
        return true;
    }

    public function deletedUniversityRemoval($rankingPageDataArr,& $rankingPageObject,$rankingId)
    {
        $ifDeleted = 0; 
        foreach($rankingPageDataArr as $ranking=>$value)
        {
              if(!($value['university'] instanceof University) || ($value['university'] instanceof University && $value['university']->getId() == "")){
                  unset($rankingPageDataArr[$ranking]);
                  $ifDeleted =1;
              }
        }
        $rankingPageDataArr = array_values($rankingPageDataArr);
        $rankingPageObject->setRankingPageData($rankingPageDataArr);
        //refresh ranking cache
        if($ifDeleted)
        {
            $rankingPageObject = $this->rankingPageRepository->find($rankingId);
        }
    }

    public function setNextPrevUrls($rankingPageUrl,$pageNumber,&$displayData)
    {
        if($pageNumber==1 && $displayData['totalRankingTuplesCount']> RANKING_PAGE_FIRSTPAGE_COUNT){
            $displayData['relNext'] = $rankingPageUrl."-".($pageNumber+1);
        }elseif($displayData['totalRankingTuplesCount']- RANKING_PAGE_FIRSTPAGE_COUNT > (RANKING_PAGE_TUPLE_COUNT*$pageNumber))
        {
            $displayData['relNext'] = $rankingPageUrl."-".($pageNumber+1);  
        }
        
        if($pageNumber!=1)
        {
            $displayData['relPrev'] = $rankingPageUrl."-".($pageNumber-1);
        }
    }

    public function processCoursesFees($rankingPageData)
    {
        $rankingCoursesFeesData= array();
        $this->abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
        foreach($rankingPageData as $key=>$ranking)
        {
            $courseObj = $ranking['course'];
            $universityObj = $ranking['university'];
            if(!($courseObj instanceof AbroadCourse && $universityObj instanceof University))
            {
                if(!(($courseObj->getId() && $universityObj->getId())))
                {
                    continue;
                }
            }
            $fees = $courseObj->getTotalFees()->getValue();
            if($fees)
            {
                $feesCurrency   = $courseObj->getTotalFees()->getCurrency();
                $courseFees     = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
                if($courseFees>0)
                {
                    $courseFees = $this->abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);   
                }
                else{
                    $courseFees = "---";
                }
            }
            else
            {
                $courseFees = "---";
            }
            $courseFees = str_replace("Lac","Lakh",$courseFees);
            $rankingCoursesFeesData[$courseObj->getId()]= $courseFees;
        }

        return $rankingCoursesFeesData;
    }
} ?>
