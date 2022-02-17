<?php

class articleAbroadWidgets extends MX_Controller
{
    function __construct() {
	parent::__construct();
    }
    /*
    Function to fetch Articles from Backend based on a Algo (priority-wise). This will return the Article json 
    */
    function getArticlesForCoursePage($article_param)
    {
	$courseObj 	= $article_param[0];
	$catId		= $article_param[1];
	$subCatId	= $article_param[2];
	$articlesCount	= $article_param[3];
	$includeGuides 	= $article_param['includeGuides'];
	$ldbcourseName	= '';
	$displayData = array();
	$this->load->helper('string');
	//_p($courseObj);
	//Code to get Desired course id - start
	$this->load->builder('LDBCourseBuilder','LDB');
	$ldbCourseBuilder = new LDBCourseBuilder;
	$ldbRepository = $ldbCourseBuilder->getLDBCourseRepository();
	if($courseObj->getDesiredCourseId()!='' && $courseObj->getDesiredCourseId()>0)
	{
	    $ldbCourseObj = $ldbRepository->find($courseObj->getDesiredCourseId());
	    if(!($ldbCourseObj->getSpecialization() == 'All' || $ldbCourseObj->getSpecialization() == 'ALL'|| $ldbCourseObj->getSpecialization() == '')){
		    $ldbcourseName = $ldbCourseObj->getCourseName() ." ". $ldbCourseObj->getSpecialization();
	    }else{
		    $ldbcourseName = $ldbCourseObj->getCourseName();
	    }
	    if($ldbCourseObj->getCourseLevel1() == 'PG' && $ldbCourseObj->getScope() == 'abroad'){
		    $ldbcourseName = $ldbCourseObj->getCourseName() . " (" . $ldbCourseObj->getCourseLevel1() . ")" ;
	    }
	}
	//Code to get Desired course id - end
	$displayData['articlesData'] = $this->getArticles($courseObj, $ldbcourseName, $catId, $subCatId, $articlesCount, $includeGuides);
	if($includeGuides === true){
	    return $displayData['articlesData'];
	}
	$this->load->view('studyAbroadArticlesWidget.php', $displayData);
    }
    
    public function getArticles($courseObj, $ldbcourseName, $catId, $subCatId, $articlesCount, $includeGuides)
    {

	$courseId = $courseObj->getId();
	$courseName = $courseObj->getName();
	$countryId = $courseObj->getMainLocation()->getCountry()->getId();
	$desiredCourseId = $courseObj->getDesiredCourseId();
	$courseLevel = $courseObj->getCourseLevel1Value();
	$this->load->model('articlesabroadwidgetsmodel');
	
	$data = array();
	$result = array();
	$fetchedArticles = 0;
	$idArr = array();
	$widgetHeading = 'Popular Articles on Studying Abroad';
	
	$counter_for_heading = 0;
	$data = $this->articlesabroadwidgetsmodel->getArticlesByDesiredCourseAndCountry($desiredCourseId, $countryId, $articlesCount,$includeGuides);
	foreach($data as $art)
	{
	    $result[$fetchedArticles] = $art;
	    $fetchedArticles++;
	    $counter_for_heading++;
	    $idArr[] = $art['content_id'];
	    if($fetchedArticles >= $articlesCount)
		break;
	}
	if($counter_for_heading == $articlesCount)
	{
	    $widgetHeading = 'Popular Articles on '.$ldbcourseName.' in '.$courseObj->getMainLocation()->getCountry()->getName();
	}
	
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByCourseLevelCategorySubCategoryAndCountry($courseLevel, $catId, $subCatId, $countryId, $articlesCount, $idArr,$includeGuides);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
		$counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseName.' in '.$courseObj->getMainLocation()->getCountry()->getName();
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByCourseLevelCategoryAndCountry($courseLevel, $catId, $countryId, $articlesCount, $idArr,$includeGuides);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
		$counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseName.' in '.$courseObj->getMainLocation()->getCountry()->getName();
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByDesiredCourse($desiredCourseId, $articlesCount, $idArr,$includeGuides);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
		$counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$ldbcourseName;
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByCourseLevelCategoryAndSubCategory($courseLevel, $catId, $subCatId, $articlesCount, $idArr,$includeGuides);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
            $counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseName;
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByCourseLevelAndCategory($courseLevel, $catId, $articlesCount, $idArr);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
            $counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseName;
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $data = $this->articlesabroadwidgetsmodel->getArticlesByCountry($countryId, $articlesCount, $idArr);
	    foreach($data as $art)
	    {
		$result[$fetchedArticles] = $art;
		$fetchedArticles++;
		$counter_for_heading++;
		$idArr[] = $art['content_id'];
		if($fetchedArticles >= $articlesCount)
		    break;
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseObj->getMainLocation()->getCountry()->getName();
	    }
	}
	if($fetchedArticles < $articlesCount)
	{
	    $counter_for_heading = 0;
	    $this->load->library('studyAbroadHome/studyAbroadHomepageLibrary');
	    $studyAbroadHomepageLibrary = new studyAbroadHomepageLibrary();
	    $data = $studyAbroadHomepageLibrary->getArticles();
	    foreach($data as $art)
	    {
		if(!in_array($art['content_id'], $idArr))
		{
		    $result[$fetchedArticles] = $art;
		    $fetchedArticles++;
		    $counter_for_heading++;
		    if($fetchedArticles >= $articlesCount)
			break;
		}
	    }
	    if($counter_for_heading == $articlesCount)
	    {
		$widgetHeading = 'Popular Articles on '.$courseObj->getMainLocation()->getCountry()->getName();
	    }
	}
	foreach($result as $key=>$row){
		$result[$key]['download_link'] = MEDIAHOSTURL.$result[$key]['download_link'];
		$result[$key]['contentImageURL'] = MEDIAHOSTURL.$result[$key]['contentImageURL'];
		$result[$key]['contentURL'] = SHIKSHA_STUDYABROAD_HOME.$result[$key]['contentURL'];
	}
	return json_encode(array($result,$widgetHeading));
    }
    
    function getListingWidgetsOnArticles($blogId)
    {
        
        $studyAbroadWidgetCacheLib = $this->load->library('studyAbroadArticleWidget/studyAbroadArticleWidgetCacheLib');
        $dataFromCache = $studyAbroadWidgetCacheLib->getListingWidgetsDataOnArticles($blogId);
        if(empty($dataFromCache)){
            $displayData = array();
            $viewLoadFlag = 0;
            $categoryPageRequest 	= $this->load->library('categoryList/AbroadCategoryPageRequest');
            $this->load->builder('categoryList/CategoryBuilder');	
            $categoryBuilder 	= new CategoryBuilder;
            $categoryRepository 	= $categoryBuilder->getCategoryRepository();
            $this->load->builder('categoryList/AbroadCategoryPageBuilder');
            $categoryPageBuilder	= new AbroadCategoryPageBuilder;
            $categoryPageRepository = $categoryPageBuilder->getCategoryPageRepository();
            $categoryPageRepository->setRequest($categoryPageRequest);
            $this->load->model('articlesabroadwidgetsmodel');
            $articleDesiredCourseId = $this->articlesabroadwidgetsmodel->getArticleDesiredCourse($blogId);
            $articleCountry 	= $this->articlesabroadwidgetsmodel->getArticleCountry($blogId);
            $articleArr 		= $this->articlesabroadwidgetsmodel->getArticleCourseMappingData($blogId);
            if(is_array($articleArr))
            {
                $articleCourseLevel	= $articleArr[0];
                $articleSubCat	= $articleArr[1];
                $articleCat		= $articleArr[2];
            }
            if(is_array($articleDesiredCourseId) && $articleDesiredCourseId[0]['ldb_course_id']!='' && $articleDesiredCourseId[0]['ldb_course_id']>0)
            {
                $ldbCourseId 	= $articleDesiredCourseId[0]['ldb_course_id'];
                $this->load->builder('LDBCourseBuilder','LDB');
                $ldbCourseBuilder 	= new LDBCourseBuilder;
                $ldbRepository 	= $ldbCourseBuilder->getLDBCourseRepository();	   
                $ldbCourseObj 	= $ldbRepository->find($ldbCourseId);
                $ldbcourseName 	= $ldbCourseObj->getCourseName();
                $specialization 	= $ldbCourseObj->getSpecialization();
                $specialization 	= strtolower($specialization);

                if(!($specialization == 'all' || $specialization == ''))
                {
                $ldbcourseName = $ldbcourseName." ".$specialization; 
                }
                $displayData['widgetHeading'] = $ldbcourseName;

                //Check for Country
                $params['LDBCourseId'] 	= $ldbCourseId;
                $params['courseLevel'] 	= "";
                $params['categoryId'] 	= 1;
                $params['subCategoryId'] 	= 1;   

                $displayData['finalArray'] = $this->prepareCourseWidget($params ,$articleCountry,$categoryPageRequest,$categoryPageRepository,true);
                $viewLoadFlag=1;
            }


            else if($articleSubCat>0 && $articleCat>0 && $articleCourseLevel!='')
            {
                $categoryObject  		  = $categoryRepository->find($articleCat);	    
                $parentCatName 		  = $categoryObject->getName();
                $displayData['widgetHeading'] = $parentCatName;

                if($articleCourseLevel!='')
                {
                $params['courseLevel'] 		= strtolower($articleCourseLevel);		
                $displayData['widgetHeading'] 	= $articleCourseLevel.' of '.$parentCatName;
                }
                else
                {
                $params['courseLevel']	= "";
                }
                $params['categoryId'] 	= $categoryObject->getParentId();
                $params['subCategoryId'] 	= $articleSubCat;
                $params['LDBCourseId'] 	= 1;

                $displayData['finalArray'] 	= $this->prepareCourseWidget($params ,$articleCountry,$categoryPageRequest,$categoryPageRepository,false);
                $viewLoadFlag=1;
            }

            else
            {  
                $displayData['finalArray'] = $this->prepareUniversityWidget($params , $articleCountry ,$categoryPageRequest);

            }
            $dataToBeSavedToCache['displayData'] = $displayData;
            $dataToBeSavedToCache['viewLoadFlag'] = $viewLoadFlag;
            $studyAbroadWidgetCacheLib->saveListingWidgetsDataOnArticles($blogId,$dataToBeSavedToCache);
        }
        else{
            $displayData = $dataFromCache['displayData'];
            $viewLoadFlag = $dataFromCache['viewLoadFlag'];
        }
        switch ($viewLoadFlag)
        {
            case 0:
            $this->load->view('studyAbroadListingUnivesityWidget', $displayData);
            break;
            case 1:
            $this->load->view('studyAbroadListingCourseWidget', $displayData);
            break;
        }
    }
    
    
    
    function prepareUniversityWidget($params , $articleCountry ,$categoryPageRequest)
    {
	$countryName =  array('3'=>'USA', '4'=>'UK', '5'=>'Australia', '8'=>'Canada');
	$finalArray = array();
	
	    if(is_array($articleCountry))
	    {	foreach ($articleCountry as $countryId)
		{   $params['countryId'] = array($countryId);   
		    if(array_key_exists($countryId,$countryName))
		    {
			unset($countryName[$countryId]);    
		    }
		    else
		    {
			array_pop($countryName);
		    }
		    $finalArray['url'][] 	= $categoryPageRequest->getURLForCountryPage($countryId);
		    $finalArray['location'][] 	= $this->articlesabroadwidgetsmodel->getCountryNameById($countryId);
		    //$finalArray['univ_count'][]	= $this->articlesabroadwidgetsmodel->getUniversityCountForCountry($countryId);
		    $finalArray['countryId'][]	= $countryId;
		}
	    }
	    
	    if(count($finalArray['url'])<4)
	    {
		foreach ($countryName as $countryId => $country)
		{
		    $finalArray['url'][] 	= $categoryPageRequest->getURLForCountryPage($countryId);
		    $finalArray['location'][] 	= $country;
		    //$finalArray['univ_count'][] = $this->articlesabroadwidgetsmodel->getUniversityCountForCountry($countryId);
		    $finalArray['countryId'][]	= $countryId;
		}		
	    }
	    $finalArray['all_univ_count'] = $this->articlesabroadwidgetsmodel->getUniversityCountForCountry($finalArray['countryId']);
	    foreach ($finalArray['countryId'] as $key => $value) {
            $finalArray['univ_count'][] = $finalArray['all_univ_count'][$value];
        }
        unset($finalArray['all_univ_count']);
        unset($finalArray['countryId']);
	    
	    return $finalArray;
    }
  
    
    function prepareCourseWidget($params ,$articleCountry,$categoryPageRequest,$categoryPageRepository,$isLDBCourse){
		
    	$this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

		$countryName =  array('3'=>'USA', '4'=>'UK', '5'=>'Australia', '8'=>'Canada');
		$finalArray = array();
		global $certificateDiplomaLevels;
		foreach($certificateDiplomaLevels as $level){
			if(strtolower($params['courseLevel']) == strtolower($level)){
				$params['courseLevel'] = 'certificate - diploma';
			}
		}
	    if(is_array($articleCountry)){
			foreach ($articleCountry as $countryId){
				$params['countryId'] = array($countryId);   
				if(array_key_exists($countryId,$countryName)){
					unset($countryName[$countryId]);    
				}else{
					array_pop($countryName);
				}
				$categoryPageRequest->setData($params);		    		    
				$seoData 		= $categoryPageRequest->getSeoInfo();
				$titlesArr 		= explode('|',$seoData['title']);
				$titlesArr 		= explode('-',$titlesArr[0]);
				$finalStr = '';
				if($isLDBCourse){
					$finalStr		=trim($titlesArr[0]);
				} else {
					$count = count($titlesArr); 
					for ($i=0;$i<($count-1);$i++){
						$finalStr .= ($finalStr=='')?$titlesArr[$i]:" ".$titlesArr[$i];
					}
					$finalStr = trim($finalStr);
				}
				$finalArray['title'][] 		= $finalStr;
				$finalArray['url'][] 		= $categoryPageRequest->getUrl();
				//$finalArray['location'][] 		= $this->articlesabroadwidgetsmodel->getCountryNameById($countryId);
				if((int)$countryId > 0){
				$countryObj = $locationRepository->findCountry($countryId);
				$finalArray['location'][] 	= $countryObj->getName();	
				}
				$finalArray['college_count'][] 	= $categoryPageRepository->getUniversityCountForCategoryPage();
			}
	    }
	    
	    if(count($finalArray['url'])<4){
			foreach ($countryName as $countryId => $country){
				$params['countryId'] 	= array($countryId);
				$categoryPageRequest->setData($params);
				$seoData 			= $categoryPageRequest->getSeoInfo();
				$titlesArr 			= explode('-',$seoData['title']);
				$finalStr 			= '';
				if($isLDBCourse){
					$titlesArr 		= explode('|',$seoData['title']);
					$titlesArr 		= explode('-',$titlesArr[0]);
					$finalStr		=trim($titlesArr[0]);
				}else{
					$count = count($titlesArr); 
					for ($i=0;$i<($count-1);$i++){
						$finalStr .= ($finalStr=='')?$titlesArr[$i]:" ".$titlesArr[$i];
					}
					$finalStr = trim($finalStr);
				}
				$finalArray['title'][] 		= $finalStr;
				$finalArray['location'][] 		= $country;
				$finalArray['url'][] 		= $categoryPageRequest->getUrl(); 
				$finalArray['college_count'][] 	= $categoryPageRepository->getUniversityCountForCategoryPage();
			}		
	    }
	    return $finalArray;
    }
}
