<?php
class MobileHomepageConfig extends MX_Controller {
    public $getTabsContentByCategory = array();
    public $mobileHomepageModel;
    function init() {
	$this->load->helper(array('form', 'url','date','image','shikshaUtility'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client'));
        $this->userStatus = $this->checkUserValidation();
	$this->getTabsContentByCategory = $this->category_list_client->getTabsContentByCategory();
	$this->load->model('mobilehomepagemodel');
	$this->mobileHomepageModel = new mobilehomepagemodel();
    }
    
    public function popularCourseAndExamWidget($param = '') {
	$this->init();
	global $mbaExamPageLinks;
	global $engineeringExamPageLinks;
	$cmsPageArr = array();
	
	$cmsUserInfo = $this->cmsUserValidation();
	$cmsPageArr['prodId'] = 803;
	$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
	$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
	//$cmsPageArr['SHIKSHA_HOME'] =  SHIKSHA_HOME;
	
	$limits = array(
			'courses'      => array('min' => 3, 'max' => 5),
			'rightLinks'   => array('min' => 3, 'max' => 7),
			'toolLinks'    => array('min' => 1, 'max' => 4),
			'popularExams' => array('min' => 1, 'max' => 3)
			);
	$cmsPageArr['limits'] =  $limits;
	
	$cmsPageArr['configData'] = array();
	
	if($param == 'saved')
	    $cmsPageArr['configSaved'] = 'yes';
	else
	    $cmsPageArr['configSaved'] = 'no';
	//Get the exam list from config
	$examList = array();
	foreach($mbaExamPageLinks[1] as $mbaExam)
	{
	    $examList[] = $mbaExam;
	}
	foreach($mbaExamPageLinks[2] as $mbaExam)
	{
	    $examList[] = $mbaExam;
	}
	foreach($engineeringExamPageLinks[1] as $enggExam)
	{
	    $examList[] = $enggExam;
	}
	foreach($engineeringExamPageLinks[2] as $enggExam)
	{
	    $examList[] = $enggExam;
	}
	$cmsPageArr['configData']['examList'] = $examList;
	//_p($examList);
	
	//Change the structure of SubCat Array
	$allSubCategoryArray = array();
	$allCategoryArray = array();
	$i=0;
	foreach ($this->getTabsContentByCategory as $catArray){
	    $catId = $catArray['id'];
	    $catName = $catArray['name'];
	    $allCategoryArray[$catId] = $catName;
	    foreach ($catArray['subcats'] as $subCategoryArray){
		$allSubCategoryArray[$i] = array();
		$allSubCategoryArray[$i]['subCatName'] = $subCategoryArray['name'];
		$allSubCategoryArray[$i]['subCatId'] = $subCategoryArray['id'];
		$allSubCategoryArray[$i]['URL'] = $subCategoryArray['url'];
		$allSubCategoryArray[$i]['catId'] = $catId;
		$allSubCategoryArray[$i]['catName'] = $catName;
		$i++;
	    }
	}
	uasort($allSubCategoryArray, function ($a, $b) {
	    if ($a['subCatName']==$b['subCatName']) return 0;
	    return strtolower ($a['subCatName']) > strtolower ($b['subCatName']) ? 1 : -1;
	});
	$cmsPageArr['configData']['allSubCategories'] = $allSubCategoryArray;
	$cmsPageArr['configData']['allCategories'] = $allCategoryArray;
	
	//$popularCourses = $this->mobileHomepageModel->getPopularCourseListForPreview($limits['courses']['max']);
	//_p($popularCourses);die;
	//if(empty($popularCourses))
	//{
	    $popularCourses = $this->mobileHomepageModel->getPopularCourseList($limits['courses']['max']);
	    //$cmsPageArr['showingLiveConfig'] = 'yes';
	//}
	$cmsPageArr['configData']['popularCourses'] = $popularCourses;
	$cmsPageArr['configData']['courseCount'] = count($popularCourses);
	//_p($popularCourses);die;
	if(!empty($popularCourses))
	{
	    $popularCourseIdsArray = array();
	    foreach($popularCourses as $course)
	    {
		$popularCourseIdsArray[] = $course['id'];
	    }
	    $popularCourseIds = implode(',', $popularCourseIdsArray);
	    $rightSideMainLinksArray        = $this->mobileHomepageModel->getRightSideMainLinks($popularCourseIdsArray, $limits['rightLinks']['max']);
	    //_p($rightSideMainLinksArray);
	    $rightSideMainLinks = array();
	    foreach($rightSideMainLinksArray as $rightTopLinks)
	    {
		$rightSideMainLinks['popCourseId-'.$rightTopLinks['popularCourseId']][] = $rightTopLinks;
	    }
	    //_p($rightSideMainLinks);
	    
	    $rightSideStudentToolLinksArray = $this->mobileHomepageModel->getStudentToolLinks($popularCourseIdsArray, $limits['toolLinks']['max']);
	    $rightSideStudentToolLinks = array();
	    foreach($rightSideStudentToolLinksArray as $rightToolLinks)
	    {
		$rightSideStudentToolLinks['popCourseId-'.$rightToolLinks['popularCourseId']][] = $rightToolLinks;
	    }
	    
	    $popularExamsArray              = $this->mobileHomepageModel->getPopularExamList($popularCourseIdsArray, $limits['popularExams']['max']);
	    $popularExams = array();
	    foreach($popularExamsArray as $popExam)
	    {
		$popularExams['popCourseId-'.$popExam['popularCourseId']][] = $popExam;
	    }
	    //_p($rightSideMainLinks);
	    $popularCoursesData = array();
	    $totalSumOfRightLinksAndTools = array();
	    $courseLoop = 0;
	    foreach($popularCourses as $course)
	    {
		$temp = $course;
		$temp['rightSideMainLinks'] = $rightSideMainLinks['popCourseId-'.$course['id']];
		$temp['rightSideStudentToolLinks'] = $rightSideStudentToolLinks['popCourseId-'.$course['id']];
		$temp['popularExams'] = $popularExams['popCourseId-'.$course['id']];
		$totalSumOfRightLinksAndTools[$courseLoop] = 0;
		$totalSumOfRightLinksAndTools[$courseLoop] += count($rightSideMainLinks['popCourseId-'.$course['id']]);
		$totalSumOfRightLinksAndTools[$courseLoop] += count($rightSideStudentToolLinks['popCourseId-'.$course['id']]);
		$temp['totalSumOfRightLinksAndTools'] = (($totalSumOfRightLinksAndTools[$courseLoop]>0)?$totalSumOfRightLinksAndTools[$courseLoop]:4);
		$popularCoursesData[] = $temp;
		$courseLoop++;
	    }
	}
	//$cmsPageArr['previewCookie'] = $_COOKIE['homepageConfigPreviewSeen'];
	$cmsPageArr['configData']['popularCoursesData'] = $popularCoursesData;
	//_p($cmsPageArr['configData']['popularCoursesData']);die;
	//_p($cmsPageArr);die;
	$this->load->view('MobileHomepageConfig/popularCourseAndExamWidget', $cmsPageArr);
    }
    
    public function addEditPopularCourseAndExamWidgetConfig() {
	//_p($_POST);
	//die('code died');
	
	if(isset($_POST['configSubmit']))
	{
	    global $mbaExamPageLinks;
	    global $engineeringExamPageLinks;
	   
	    $examList = array();
	    foreach($mbaExamPageLinks[1] as $mbaExam)
	    {
		$examList[$mbaExam['name']] = $mbaExam;
	    }
	    foreach($mbaExamPageLinks[2] as $mbaExam)
	    {
		$examList[$mbaExam['name']] = $mbaExam;
	    }
	    foreach($engineeringExamPageLinks[1] as $enggExam)
	    {
		$examList[$enggExam['name']] = $enggExam;
	    }
	    foreach($engineeringExamPageLinks[2] as $enggExam)
	    {
		$examList[$enggExam['name']] = $enggExam;
	    }
	    
	    $this->load->model('mobilehomepagemodel');
	    $this->mobileHomepageModel = new mobilehomepagemodel();
	    $this->mobileHomepageModel->disableAllPopularCourse();
	    
	    foreach($_POST['catSubCategory'] as $block => $values)
	    {
		if($values!='')
		{
		    $catSubcat = explode('#', $values);
		    //insert data into mobile_homepage_popular_course table
		    $data = array();
		    $data['name']     = $_POST['courseDisplayName'][$block];
		    $data['subcatId'] = $catSubcat[1];
		    $data['parentId'] = $catSubcat[0];
		    $data['bgcolor']  = $_POST['courseDisplayColor'][$block];
		    $data['status']   = 'live';
		    $data['createdDate'] = date('Y-m-d H:i:s');
		    $popularCourseId = $this->mobileHomepageModel->addPopularCourse($data);
		    
		    //insert data into mobile_homepage_right_main_links table for Main Links
		    foreach($_POST['rightLink_text'][$block] as $key => $text)
		    {
			$data = array();
			$data['name']            = $text;
			$data['URL']             = $_POST['rightLink_url'][$block][$key];
			$data['type']            = $_POST['rightLink_type'][$block][$key];
			$data['menu']            = 'top';
			$data['popularCourseId'] = $popularCourseId;
			$this->mobileHomepageModel->addRightSideLinksData($data);
		    }
		    
		    //insert data into mobile_homepage_right_main_links table for Tool Links
		    foreach($_POST['rightTool_text'][$block] as $key => $text)
		    {
			$data = array();
			$data['name']            = $text;
			$data['URL']             = $_POST['rightTool_url'][$block][$key];
			$data['type']            = $_POST['rightTool_type'][$block][$key];
			$data['menu']            = 'tool';
			$data['popularCourseId'] = $popularCourseId;
			$this->mobileHomepageModel->addRightSideToolsData($data);
		    }
		    
		    //insert data into mobile_homepage_popular_exam table
		    foreach($_POST['popularExam_text'][$block] as $key => $text)
		    {
			$data = array();
			$data['name']            = $text;
			$data['params']          = $examList[$_POST['popularExam_exam'][$block][$key]]['param'];
			$data['examName']        = $_POST['popularExam_exam'][$block][$key];
			$data['popularCourseId'] = $popularCourseId;
			$this->mobileHomepageModel->addPopularExamsData($data);
		    }
		}
	    }
	}
	redirect('/enterprise/MobileHomepageConfig/popularCourseAndExamWidget/saved', 'location', 301);
    }
    public function publishNewPopularCourses()
    {
	if(isset($_POST['configPublish']))
	{
	    //$this->load->model('mobilehomepagemodel');
	    //$this->mobileHomepageModel = new mobilehomepagemodel();
	    //$this->mobileHomepageModel->disableAllPopularCourse();
	    //$this->mobileHomepageModel->publishAllNewPopularCourses();
	    
	    $this->load->library('Common/cacheLib');
	    $this->cacheLib = new cacheLib();
	    $ckey = 'MobileHomepagePopularCourseConfig';
	    $this->cacheLib->clearCacheForKey($ckey);
            //setcookie('homepageConfigPreviewSeen','done', time() - 3600 ,'/',COOKIEDOMAIN);
	}
    }
}
?>
