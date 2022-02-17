<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev:: $: Revision of last commit
$Author: pankajt $: Author of last commit
$Date: 2010-09-10 06:08:12 $: Date of last commit

Shiksha.php controller for Shiksha Home Page.

$Id: shiksha.php,v 1.275 2010-09-10 06:08:12 pankajt Exp $:

*/
class shiksha extends MX_Controller {
	function init() {
		$this->load->helper(array('shikshaUtility','form', 'url','image','shiksha_url'));
		$this->load->library('ajax');
	}

    public function jgbscampaign()
    {
        $this->load->view('JgbsCampaign');
    }

	private function shikshaMainHomePageStuff(){
		$featuredCategories = array(
			'Management' => array('id' => '3','caption'=>'Management'),
			'IT' => array('id' => '10','caption'=>'IT'),
			'Retail' => array('id' => '11','caption'=>'Retail'),
			'Hospitality' => array('id' => '6','caption'=>'Hospitality'),
			'Banking' => array('id' => '4','caption'=>'Banking'),
			'Medical' => array('id' => '5','caption'=>'Medical'),
			'Engineering' => array('id' => '2','caption'=>'Engineering'),
			'Media' => array('id' => '7','caption'=>'Media'),
			'Arts' => array('id' => '9','caption'=>'Arts'),
			'Animation' => array('id' => '12','caption'=>'Animation'),
			'Professional' => array('id' => '8','caption'=>'Professional Courses')
		);
		$featuredCoursesList = array(
                                'Call Center Training' => 'http://professionals.shiksha.com/getCategoryPage/colleges/professionals/All/All/BPO-Call-Center-Training',
                                'Clinical Research' => 'http://medicine.shiksha.com/getCategoryPage/colleges/medicine/All/All/Clinical-Research',
                                'Spoken English' => 'http://arts.shiksha.com/getCategoryPage/colleges/arts/All/All/Languages',
                                'Pilot Training' => '/search/index?keyword=Pilot+Training&location=&searchType=course&cat_id=-1&countOffsetSearch=25&startOffSetSearch=0&subCategory=-1&subLocation=-1&cityId=-1&cType=-1&courseLevel=-1&subType=&showCluster=-1&channelId=home_page',
		);
		global $homePageMap;
		global $homePageData;
		$this->init();
		$displayData = array();
		$countryId = 1;
		$categoryId = 1;
		$start = 0;
		$rows = 3;
		$keyValue = $homePageMap['SHIKSHA_HOME_PAGE'];
		$appId = 1;
		$displayData = array();

		$notificationRows = 5 ;
		$notifications = $this->getEvents($appId, $categoryId, $countryId, $start, $notificationRows,$keyValue,3, $allCategoryId);
		$displayData['notifications'] = $notifications;
		//echo"<pre>";var_dump($notifications);echo"</pre>";


		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$subCategoryList = $categoryClient->getSubCategories(1,1);
		$displayData['subCategories'] = $subCategoryList;
		$categoryClient = new Category_list_client();
		$categoryList = $categoryClient->getCategoryTree($appId, 1);
		foreach($categoryList as $temp) {
			$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
		}
		$displayData['completeCategoryTree'] = json_encode($categoryForLeftPanel);
		$displayData['categoryList'] = json_encode($categoryList );
		$displayData['homePageData'] = $homePageData;
		$Validate = $this->checkUserValidation();
		$displayData['validateuser'] = $Validate;
		$displayData['featuredCategories'] = $featuredCategories;
		$displayData['featuredCoursesList'] = $featuredCoursesList;
		$categoryId = 3 ; //Management CategoryId
		$displayData['featuredColleges'] = $this->getFeaturedColleges($appId, $categoryId, $countryId, $start, 4, $keyValue);

		$blogs = array();
		$blog_client = new Blog_client();
		$blogs = $blog_client->getPopularBlogsForHomePage($appId,5);
		$displayData['blogs'] = $blogs;
		return $displayData;
	}
	function index() {
		$partner = '';
		$partnerFlag = false;
		$resetPage = (isset($_GET['resetPage']) && ($_GET['resetPage'] == '1')) ? true : false;
		$this->showHome($partner, $partnerFlag, $resetPage);
		return;
/*
		$displayData = $this->shikshaMainHomePageStuff();
		$displayData['partnerFlag'] = false;
		$displayData['trackForPages'] = true;
		$this->load->view('home/shiksha/homepage',$displayData);
		//$this->load->view('home/shiksha/homepage',$displayData);
		$this->load->view('common/header',$displayData);
*/
	}

	function partner($partner='naukri') {
		$partnerFlag = true;
		$this->showHome($partner, $partnerFlag);
		return;
		$displayData = $this->shikshaMainHomePageStuff();
		$displayData['partnerFlag'] = true;
		$displayData['partner'] = $partner;
		$this->load->view('home/shiksha/homepage',$displayData);
	}

	function testprep1($examId,$examName,$type,$country = '2', $city = '1',$countryName) {
		$appId = 1;
		global $homePageMap;
		$keyValue = $homePageMap['ENTRANCE_EXAM_PREPARATION_PAGE'];
		$this->load->library('listing_client');
		$this->load->library('Blog_client');
		$ListingClientObj = new Listing_client();
		$blog_client = new Blog_client();
		$requiredExamInstitutes = $ListingClientObj->getInstitutesForExam($appId, $examId, 'required', 0, CATEGORY_HOME_PAGE_COLLEGES_COUNT,1,$country,$city, $keyValue);
		$prepExamInstitutes = $ListingClientObj->getInstitutesForExam($appId, $examId, 'testprep',0, CATEGORY_HOME_PAGE_COLLEGES_COUNT,1,$country,$city, $keyValue);
		$examDetails = $blog_client->getBlogInfo($appId,$examId);
		$Data = $examDetails[0];
		if($Data['parentId'] == 0) {
			$parentExamCategories = $blog_client->getExamParents($appId, $Data['boardId']);
		} else {
			$parentExamCategories = $blog_client->getExamsForParent($appId, $Data['parentId']);
		}
		$totalRelatedArticles = count($parentExamCategories);
		if($totalRelatedArticles < 20) {
			$relatedArticles = $blog_client->getRelatedBlogs($appId, $Data['boardId'], 0,10 - $totalRelatedArticles, 1);
			$parentExamCategories = array_merge($parentExamCategories, $relatedArticles);
		}
		$displayData['institutesaccept'] = $requiredExamInstitutes;
		$displayData['institutestestprep']= $prepExamInstitutes;
		if(isset($countryName))
		$displayData['countryName']= $countryName;
		else
		$displayData['countryName']= 'india';
		if(isset($city))
		$displayData['selectedCity']= $city;

		$displayData['blogs']= array('results'=> $parentExamCategories);

		$categoryData['page'] = "ENTRANCE_EXAM_PREPARATION_PAGE";
		$exams = $blog_client->getExams($appId);
		$displayData['examcategory']= $exams;
		$displayData['pageName']= 'ENTRANCE_EXAM_PREPARATION_PAGE';
		$displayData['pagetype']= $type;
		$displayData['examName']= $examName;
		$displayData['examId']= $examId;
		$displayData['countryId']= $countryId;
		$displayData['cityId']= $cityId;
		$Validate = $this->checkUserValidation();
		$displayData['validateuser'] = $Validate;
		$displayData['categoryData'] = $categoryData;
		$this->load->view('home/category/ANIMATION_PAGE' ,$displayData);
	}

	function testprep_category_page($url)
	{
		$appId = 1;
		//validate user and load libraries going to be used
		$validate_user = $this->checkUserValidation();
		$this->init();
		$this->load->library('category_list_client');
		$this->load->library('url_manager');
		$this->load->helper('seo_tags');

		//extract params from url
		$url_params = $this->url_manager->get_testprep_params_from_url($url);
		$page = ($url_params['page_num'] == NULL || $url_params['page_num'] == '' ? 1 : $url_params['page_num']);
		$city_name = $url_params['city_name'];
		$blog_acronym = $url_params['blog_acronym'];
		$course_type = ($url_params['course_type'] == NULL || $url_params['course_type'] == '' ? 'All' : $url_params['course_type']);
		$pagetype = ($url_params['page_type'] == NULL || $url_params['page_type'] == '' ? '' : $url_params['page_type']);

		//find out IDs of city and blog
		$category_client = new Category_list_client();
		$cityId = (($city_name == NULL or $city_name == "") ? -2 : $category_client->getCityId($appId, $city_name));
		$blogId = $category_client->getBlogId($appId, $blog_acronym);

		// load city_id from cookie
		if($cityId == -2)
		{
			$city_cookie = $_COOKIE['userCityPreference'];
			if($city_cookie != FALSE){
				$location_array = explode(':::', $city_cookie);
				$cityIdInCookie = $location_array[0];
			} else {
				$cityIdInCookie = FALSE;
			}
		}
		//get city data to populate location overlay. show location overlay first if city is neither in URL nor in Cookie
		$tier1_cities = $category_client->getCitiesInTier($appId, 1, 2);
		$tier2_cities = $category_client->getCitiesInTier($appId, 2, 2);
		$city_cookie_set = TRUE;
		if($cityIdInCookie === '') $cityId = -1;
		if($cityId == -2)
		{
			if($cityIdInCookie == FALSE)
			{
				$city_cookie_set = FALSE;
				$temp_data = array();
				$temp_data['cityTier1'] = $tier1_cities;
				$temp_data['cityTier2'] = $tier2_cities;
				$temp_data['city_cookie_set'] = $city_cookie_set;
				$temp_data['params'] = array('blog_id' => $blogId);
				$this->load->view('categoryList/testprep_category_page', $temp_data);
				return;
			}
			else
			{
				$cityId = $cityIdInCookie;
				$city_name = $this->category_list_client->getCityName($cityId);
				if($cityId != -1)
				{
					$url = $this->url_manager->get_testprep_url('', $blog_acronym, $city_name, '', '');
					header("location:$url");
					exit;
				}
			}
		}

		//get city list
		$city_details = $category_client->getDetailsForCityId($appId,$cityId);

		//get exam categories for populating subcategory dropdown, category title and category image
		$exam_categories = $this->listing_client->get_exam_categories($blogId);

		//a map to show AnA and articles section which dosnt have a direct mapping with exam categories
		$blog_category_map = $category_client->get_blog_category_map();
		$category_id = $blog_category_map[$exam_categories['parent_blog_id']];
		if($category_id != NULL) $category_details = $category_client->getCategoryDetailsById($appID, $category_id);
		$category_url = $category_details['urlName'];
		global $categoryMap;
		$categoryData['displayName'] = $categoryMap[$category_url]['displayName'];

		//find all listings
		$this->load->library('listing_client');
		$listing_client = new Listing_client();
		$listings = $listing_client->get_testprep_listings($blogId, $cityId, $course_type);

		//get banners and category sponser listings
		$banners = $listings['cat_sponser_banners'];
		$listings = $this->testprep_unique_merge($listings['cat_sponser_listings'], $listings['main_listings'], $listings['paid_minus_main_listings'], $listings['free_listings']);

		//sort if most viewed clicked
		if($pagetype == 'most-viewed'){
			usort($listings, "Shiksha::compare");
		}


		//extract full info like instt name and course name for the given page
		$start = ($page - 1)*TESTPREP_LISTINGS_PER_PAGE;
		$end = min($page*TESTPREP_LISTINGS_PER_PAGE, count($listings)) - 1;
		$count = count($listings);
		$result = array();
		for($i = $start ; $i <= $end ; $i++)
		{
			$listing_detail = array();
			$listing_detail['institute_id'] = $listings[$i]['institute_id'];
			$listing_detail['name'] = $listing_client->get_institute_name($listings[$i]['institute_id']);
			$listing_detail['city_name'] = $listings[$i]['city_name'];
			$listing_detail['type'] = $listings[$i]['type'];
			$listing_detail['courses'] = array();
			$listing_detail['view_count'] = $listings[$i]['view_count'];

			$course_count = 2;
			switch($listings[$i]['type']){
				case 'free': $course_count = 1;break;
				case 'main': $course_count = 2;break;
				case 'paid_minus_main': $course_count = 2;break;
				case 'sponsered': $course_count = 100;break;
			}

			$course_ids = explode(",",$listings[$i]['course_ids']);
			$c = 0;
			foreach($course_ids as $id){
				$c++;
				if($c > $course_count) break;
				array_push($listing_detail['courses'], array(
                    'course_id' => $id,
                    'course_name' => $listing_client->get_course_name($id)
				));
			}
			array_push($result, $listing_detail);
		}


		//article widget
		$blogs = $this->getBlogs($appId, $category_id, 2 , $start, 3 ,'college', $category_id);

		//AnA widget
		$user_id = isset($validate_user[0]['userid'])?$validate_user[0]['userid']:0;
		$this->load->model('QnAModel');
		$response = $this->QnAModel->getDataForGlobalAnAWidget($user_id, $category_id, 1, 0, 5, 1);
		$data['validateuser'] = $validate_user;
		$data['category_id'] = $category_id;
		$data['editorialBinQuestions'] = $response;
		$data['defaultCategoryIdForGlobalAnAWidget'] = $category_id;


		//populating data to be sent to the view
		$data['blog_category_map'] = $blog_category_map;
		$data['listings'] = $result;
		$data['banner'] = $banners[0];
		$data['exam_categories'] = $exam_categories;
		$data['cityTier1'] = $tier1_cities;
		$data['cityTier2'] = $tier2_cities;
		$data['city_name'] = $cityId == -1 ? 'All Cities' : $city_details['city_name'];
		$data['params'] = array('blog_id'=>$blogId, 'city_id'=>$cityId, 'course_type'=>$course_type, 'pagetype' => $pagetype, 'blog_acronym' => $blog_acronym, 'city_name' => $city_name);
		$data['city_cookie_set'] = $city_cookie_set;
		$data['product'] = 'testprep';
		$data['pagination_data'] = array('start'=>$start + 1, 'end'=>$end + 1, 'count'=>$count, 'page'=>$page, 'total_pages'=>( ceil($count/TESTPREP_LISTINGS_PER_PAGE) ) );
		$data['categoryData'] = $categoryData;
		$data['blogs'] = $blogs;

		//load the view
		$this->load->view('categoryList/testprep_category_page',$data);
	}

	/*
	 * Function to change location in cookie when clicked on location overlay on testprep category page
	 */
	function change_location($blog_id, $city_id = -1)
	{
		$this->load->helper('cookie');
		$this->load->library(array('url_manager','category_list_client'));

		$blog_acronym = $this->category_list_client->getBlogAcronym($blog_id);
		$city_name = $this->category_list_client->getCityName($city_id);

		if($city_id == -1) {$city_id = ''; $city_name = '';}
		setcookie('userCityPreference', "$city_id:::$city_name:::India",0,'/', COOKIEDOMAIN);
		$_COOKIE['userCityPreference'] = "$city_id:::$city_name:::India";

		$url = $this->url_manager->get_testprep_url('', $blog_acronym, $city_name, '', '');
		header("location:$url",TRUE,301);
		exit;
	}

	private static function compare($a, $b)
	{
		if($a['view_count'] == $b['view_count']) return 0;
		return ($a['view_count'] > $b['view_count']) ? -1 : 1;
	}

	private function testprep_unique_merge($cat_sponsers, $main_instts, $paid_minus_main_instts, $free_instts)
	{
		foreach ($cat_sponsers as $listing) {
			$this->remove_matching_institutes($main_instts, $listing);
			$this->remove_matching_institutes($paid_minus_main_instts, $listing);
			$this->remove_matching_institutes($free_instts, $listing);
		}

		foreach ($main_instts as $listing) {
			$this->remove_matching_institutes($free_instts, $listing);
		}

		return array_merge($cat_sponsers, $main_instts, $paid_minus_main_instts, $free_instts);
	}

	private function remove_matching_institutes(&$arr, $value) {
		$ret_arr = array();
		foreach ($arr as $el) {
			if ($el['institute_id'] != $value['institute_id'])
			array_push($ret_arr, $el);
		}
		$arr = $ret_arr;
	}

	function take_test($acronym)
	{
		$validateuser = $this->checkUserValidation();
		$this->load->library(array('listing_client', 'category_list_client'));
		$blog_id = $this->category_list_client->getBlogId(1, $acronym);
		$blog_title = $this->category_list_client->getBlogTitle($blog_id);
		$bannerurl = $this->listing_client->get_online_test_banner($blog_id);
		$url = $this->url_manager->get_testprep_url('',$acronym,'','','');
		if($validateuser == "false") header("location: $url");
		$this->load->view('common/mock_test', array('validateuser'=>$validateuser, 'bannerurl' => $bannerurl, 'blog_id' => $blog_id, 'acronym' => $acronym, 'url' => $url, 'blog_title' => $blog_title));
	}

	function load_test($acronym)
	{
		$validateuser = $this->checkUserValidation();
		$this->load->view('common/topcoaching', array('validateuser'=>$validateuser, 'acronym' => $acronym));
	}

	function userresponse($key,$verifyflag)
	{
		$this->init();
		$value = $key .'|' .$verifyflag;
		setcookie('userresponse',$value,0,'/');
		$this->index();
	}

	function getBlogsForTestPrep($examId)
	{
		$appId = 1;
		$this->load->library('Blog_client');
		$blog_client = new Blog_client();
		$examDetails = $blog_client->getBlogInfo($appId,$examId);
		$Data = $examDetails[0];
		if($Data['parentId'] == 0) {
			$parentExamCategories = $blog_client->getExamParents($appId, $Data['boardId']);
		} else {
			$parentExamCategories = $blog_client->getExamsForParent($appId, $Data['parentId']);
		}
		$totalRelatedArticles = count($parentExamCategories);
		if($totalRelatedArticles < 20) {
			$relatedArticles = $blog_client->getRelatedBlogs($appId, $Data['boardId'], 0,10 - $totalRelatedArticles, 1);
			$parentExamCategories = array_merge($parentExamCategories, $relatedArticles);
		}
		echo json_encode(array('results'=> $parentExamCategories));
	}

	function getCoursesForTestPrep($examId,$institutetype,$start,$count,$country,$city) {
		global $homePageMap;
		$keyValue = $homePageMap['ENTRANCE_EXAM_PREPARATION_PAGE'];
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		$requiredExamCourses = $ListingClientObj->getCoursesForExam(1,$examId,$institutetype,$start,$count,1,$country,$city, $keyValue);
		echo (json_encode($requiredExamCourses));
	}

	function _course_lists($selected = NULL,$flag = FALSE) {
		$array = array('B.A.','B.A.(Hons)','B.Sc','B.Sc(Gen)','B.Sc(Hons)','B.E./B.Tech','B.Des','B.Com','BBA/BBM/BBS','B.Ed','BCA/BCM','BVSc','BHM','BJMC','BDS','B.Pharma','B.Arch','MBBS','LLB','Diploma');
		$string = '';
		for ($i = 0; $i < count($array);$i++)  {
			if ($selected != NULL) {
				if ($selected == $array[$i] ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}
			$string .='<option '.$selected_string.' value="'.$array[$i].'">'.$array[$i].'</option>';
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}

	public function getHomePageBlogs($appId, $categoryId, $countryId, $keyValue, $parentId = 1, $start = 0, $blogRows = 5){
		global $homePageMap;
		$blogs = $this->getBlogs($appId, $categoryId, $countryId, $start, $blogRows, $homePageMap[$keyValue], $parentId);
		echo json_encode($blogs);
	}

	public function getHomePageMsgBoards($appId, $categoryId, $keyValue,$start = 0, $msgBoardsRows = 3,$countryId){
		global $homePageMap;
		$msgBoards = $this->getMsgBoards($appId, $categoryId, $homePageMap[$keyValue], $start, $msgBoardsRows,$countryId);
		echo json_encode($msgBoards);
	}

	public function getHomePageEvents($appId, $categoryId, $countryId,$keyValue, $start=0, $eventRows=5, $parentId =1,$cityId=1, $eventType = 0){
		global $homePageMap;
		$events = $this->getEvents($appId,$categoryId,$countryId, $start,$eventRows, $homePageMap[$keyValue], $eventType, $parentId,$cityId);
		echo json_encode($events);
	}

	private function getEvents($appId, $categoryId, $countryId, $start, $eventsRows,$keyValue, $fromOthers=0, $parentId =1,$cityId=1){
		$this->load->library('event_cal_client');
		$objEvents = new Event_cal_client();
		$events = $objEvents->getEventsForHomePage($appId,$countryId,$categoryId, $start, $eventsRows,$keyValue, $fromOthers, $parentId,$cityId);
		return is_array($events) ? array_pop($events) : array();
	}

	private function getMsgBoards($appId, $categoryId, $keyValue, $start, $msgBoardsRows, $countryId){
		$this->load->library('message_board_client');
		$objMsgBoard = new Message_board_client();
		$msgBoards = $objMsgBoard->getTopicsForHomePage($appId, $categoryId, $keyValue, $start, $msgBoardsRows, $countryId);
		return is_array($msgBoards) ? array_pop($msgBoards) : array();
	}

	private function getDataForCountryPage($appId, $categoryId, $countryId, $start, $blogRows,$keyValue, $parentId,$cache = 1){
		$this->load->library('listing_client');
		$objBlog = new Listing_client();
		$blogs = $objBlog->getDataForCountryPage($appId, $categoryId, $countryId, $start, $blogRows,$keyValue, $parentId,$cache);
		$blogs = json_decode($blogs,true);
		//print_r($blogs['faq'][0][0]);
		//print_r(json_decode($blogs,true));
		//        return is_array($blogs) ? array_pop($blogs) : array();
		return $blogs;
	}
	private function getBlogs($appId, $categoryId, $countryId, $start, $blogRows,$keyValue, $parentId,$cache = 1){
		$this->load->library('blog_client');
		$objBlog = new Blog_client();
		$blogs = $objBlog->getBlogsForHomePages($appId, $categoryId, $countryId, $start, $blogRows,$keyValue, $parentId,$cache);
		return is_array($blogs) ? array_pop($blogs) : array();
	}

	private function getScholarships($appId, $categoryId, $countriesForCollege, $start, $scholarshipCount, $keyValue, $relaxFlag=0,$cityId = 1){
		$this->load->library('keyPagesClient');
		$objKeyPagesClient = new keyPagesClient();
		$scholarshipList = $objKeyPagesClient->getScholarshipsForHomePageS($appId, $categoryId, $countriesForCollege, $start, $scholarshipCount, $keyValue, $relaxFlag,$cityId);
		return is_array($scholarshipList) ? $scholarshipList[0] : array();
	}

	private function getColleges($appId, $countryId, $categoryId, $indexOf) {
		$addCourseData = array();
		$addCourseData['countryId'] = $countryId;
		$addCourseData['catId'] = $categoryId;
		$addCourseData['indexOf'] = $indexOf;
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		$response = $ListingClientObj->getInstituteListIndexed($appId,$addCourseData);
		return $response;
	}

	private function getCourses($appId,$categoryId, $countryId, $start, $count, $keyValue, $relaxFlag=true) {
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client($appId,$categoryId, $countryId, $start, $count, $keyValue);
		$courses = $ListingClientObj->getCoursesForHomePageS($appId,$categoryId, $countryId, $start, $count, $keyValue, $relaxFlag);
		return $courses;
	}

	function getFeaturedCollegesForCategory($categoryId, $countryId = 1, $cityId ='', $key = 'SHIKSHA_HOME_PAGE', $start = 0, $count = 4) {
		show_404();die;
		global $homePageMap;
		$appId = 1;
		$keyValue = $homePageMap[$key];
		error_log($categoryId.'CATEGORYID'.$countryId.'COUNTRYID'.$cityId.'CITYID');
		echo json_encode($this->getFeaturedColleges($appId, $categoryId, $countryId, $start, $count, $keyValue, $cityId));
	}


	function getFeaturedCollegesForCountry($categoryId, $countryId = 1, $cityId ='', $key = 'SHIKSHA_HOME_PAGE', $start = 0, $count = 4)
	{
		$appId = 1;
		error_log($categoryId.'CATEGORYID'.$countryId.'COUNTRYID'.$cityId.'CITYID');
		$this->load->library('listing_client');
		$listingClient = new listing_client();
		if($cityId == 0)
		$cityId = '';
		echo json_encode(array('result' => $listingClient->getListingsForNaukriShiksha($appId,$categoryId,0,$countryId,$cityId,'','','',$start,$count,'countrypage',3,0)));
	}

	function getCourseList($instituteId)
	{
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		echo json_encode($ListingClientObj->getCourseList('1',$instituteId,'"live"'));
	}

	function getFeaturedCoursesForCategory($categoryId,$countryId = 1,$key,$cityId = '',$start = 0,$count = CATEGORY_HOME_PAGE_COLLEGES_COUNT)
	{
		global $homePageMap;
		$appId = 1;
		$courses = $this->getCoursesForHomePages($appId, $categoryId, $countryId, $start, $count,$homePageMap[$key], $cityId,  false);
		echo json_encode($courses);
	}

	private function getFeaturedColleges($appId, $categoryId, $countryId, $start, $count, $keyValue, $cityId='', $relaxFlag) {
		error_log($appId.'   '. $categoryId.'     '. $countryId.'     '. $start.'     '. $count.'       '. $keyValue.' '. $cityId.'    '. $relaxFlag);
		$this->load->library('keyPagesClient');
		if($cityId == "null") {
			$cityId = "";
		}
		$relaxFlag = false;
		$objKeyPagesClient = new keyPagesClient();
		$collegeList = $objKeyPagesClient->getInstitutesForHomePageS($appId, $categoryId, $countryId, $start, $count, $keyValue, $cityId, $relaxFlag);
		return ($collegeList);
	}

	/* Will return cities containing colleges for a country */
	function getNonEmptyCitiesForCountry($appID, $countryId, $categoryId =1, $testPrep = 0) {
		$this->load->library('listing_client');
		$listingClient = new Listing_client();
		$flagExam = ($testPrep == 0) ? "false" : "true";
		//if($categoryId == 1)
		echo json_encode($listingClient->getCitiesWithCollege($appID,$countryId));
		//else
		//echo json_encode($listingClient->getCitiesWithCollegeInCategory($appID,$categoryId,$countryId, $flagExam ));
	}

	function getCollegesForExam($examArticleId, $collegeType, $start=0, $count=8,$country = 1,$city = 1){
		global $homePageMap;
		$keyValue = $homePageMap['ENTRANCE_EXAM_PREPARATION_PAGE'];
		$appId = 1;
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		$examInstitutes = $ListingClientObj->getInstitutesForExam($appId, $examArticleId, $collegeType, $start, $count,1,$country,$city, $keyValue );
		echo json_encode($examInstitutes);
	}

	private function getCoursesForHomePages($appId, $categoryId, $countryId, $start, $count, $keyValue, $cityId=1, $relaxFlag = true){
		$this->init();
		$this->load->library('listing_client');
		$ListingClientObj = new Listing_client();
		error_log("ashsishshshsh ".$relaxFlag);
		error_log($categoryId.'asd'. $countryId.'qwq'. $start.'sads'. $count. 'asdq'.$keyValue. 'sda'.$cityId);
		$courses = $ListingClientObj->getCoursesForHomePageS($appId,$categoryId, $countryId, $start, $count, $keyValue ,$cityId, $relaxFlag);
		/*
		 echo "<pre>";
		 print_r($joinGroupInfo);
		 echo "</pre>";
		 */
		return $courses;
	}


	public function showHome($partner ='', $partnerFlag = false, $resetPage = false) {
		if(!empty($_REQUEST['encodedMail']) && !empty($_REQUEST['mailerUnsubscribe'])) {
			$objmailerClient = $this->load->library('mailer/MailerClient');
			$result = $objmailerClient->autoLogin(1,$_REQUEST['encodedMail']);
			setcookie('user',$result,0,'/',COOKIEDOMAIN);			
			$redirectUrl = SHIKSHA_HOME."/userprofile/edit?unscr=5";
			$redirectUrl = Modules::run('mailer/Mailer/processRedirectUrl', $redirectUrl,$result,'');
			$redirectUrl = $redirectUrl['redirectUrl'];
			header( "Location: $redirectUrl" );
			exit();
		}
		
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$this->init();
		$displayData                  = array();
		$displayData['partner']       = $partner;
		$displayData['partnerFlag']   = $partnerFlag;
		$displayData['trackForPages'] = true;
		//$displayData['trackForPages'] = true;
        $displayData['loadJQUERY'] 	  = 'YES';
		$Validate 					  = $this->checkUserValidation();
		$displayData['validateuser']  = $Validate;

		if(($Validate == "false" )||($Validate == "")) {
			$displayData['logged'] = "No";
			$displayData['GA_userLevel'] = 'Non-Logged In';
		} else {
			$displayData['logged'] = "Yes";
			$displayData['GA_userLevel'] = 'Logged In';
		}
        
        $data_article 				   = array();
		//check for new search page
		
		$homepagemiddlepanelcache  = "HomePageRedesignCache/middlepanel.html";
		$displayData['resetPage'] = $resetPage;

		if(!(file_exists($homepagemiddlepanelcache) && (time() - filemtime($homepagemiddlepanelcache))<=7200) || $resetPage){
			/*data call required for homepagemiddle panel starts*/	
			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$this->load->config('common/newGNBconfig');
			$this->load->model('home/homepagemodel');
			$this->load->helper('home/homepage');
  			$featuredCollegeData = $this->homepagemodel->getFeaturedCollegeBanners($resetPage);
			$displayData['featuredColleges'] = generateTargetUrl($featuredCollegeData);
			if(LOG_HOMEPAGE_PERFORMANCE_DATA)
				error_log("Section: Load featuredColleges | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);

			$time_start = microtime_float(); $start_memory = memory_get_usage();
			$displayData['featuredArticles'] = $this->homepagemodel->getFeaturedArticles($resetPage);
			if(LOG_HOMEPAGE_PERFORMANCE_DATA)
				error_log("Section: Load featuredArticles | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);
			$displayData['featuredArticles'] = rearrangeTheArticles($displayData['featuredArticles']);
			
        } 
        
        $cacheLib = $this->load->library('cacheLib');
		$cntKey = md5('nationalHomepageCounters_json');
		$hpCounterResult = $cacheLib->get($cntKey);
		if($hpCounterResult != 'ERROR_READING_CACHE'){
			$hpCounterResult = json_decode($hpCounterResult, true);
			$displayData['hpCounterResult'] = $hpCounterResult;
		}
		/**
		 * If marketing form registration is to be triggered
		 */ 
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$marketingFormRegistrationId                  = $_REQUEST['mfmrg'];
		$this->load->model('mailer/marketingFormmailermodel');
		$marketingFormRegistrationData                = $this->marketingFormmailermodel->getMarketingFormRegistrationData($marketingFormRegistrationId);
		$displayData['marketingFormRegistrationData'] = $marketingFormRegistrationData;
		if(LOG_HOMEPAGE_PERFORMANCE_DATA)
			error_log("Section: Load marketingFormRegistrationData | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);

		$displayData['loadCareerWidget']              = true;
		$displayData['widgetForPage'] = "HOMEPAGE_DESKTOP";

		$dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_HomePage');
        $displayData['dfpData']  = $dfpObj->getDFPData($Validate, $dpfParam);

		// The page view tracking information
		$displayData['beaconTrackData'] = array(
			'pageIdentifier' => 'homePage',
			'pageEntityId'   => 0,
			'extraData'      => array(
				'countryId'	=> 2,
				'childPageIdentifier'=>'homePage'
			)
		);
		$displayData['suggestorPageName'] = "all_tags";
		$displayData['GA_Tap_On_What_Question'] = 'QUESTION_CTA_SHIKSHAHOMEPAGE_DESKAnA';
		$displayData['GA_currentPage'] = 'SHIKSHAHOMEPAGE_DESKAnA';
		$this->load->view('home/homepageRedesign/homepage_main', $displayData);
		if(LOG_HOMEPAGE_PERFORMANCE_DATA)
				error_log("Section: Load showHome() | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, LOG_HOMEPAGE_PERFORMANCE_DATA_FILE_NAME);
	}

	function populateDesiredCourseDropDown ($categoryId) {
		//load category list client
		$this->load->library(array('category_list_client','LDB_Client'));
		$ldbObj = new LDB_Client();
		$categoryClient = new Category_list_client();
		if($categoryId==-1) {
			$course_list = $categoryClient->getTestPrepCoursesList(1);
		} else if($categoryId==3) {
			$management_saved_courses = json_decode($ldbObj->sgetCourseList($appId,3),true);
			$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
			$course_list = array_merge($management_saved_courses,$distance_course);
		} else {
			$course_list = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1,$categoryId),true);
		}
		//print_r($course_list);
		if($categoryId==3) {
			foreach($course_list as $management_course) {
				usort($management_course, "cmp");
				if ($management_course['ParentId'] == 1)
				// check made for distance MBA, set it as local
				if ( $management_course['CourseReach'] == 'national') {
					if ($management_course['CourseLevel1'] == 'UG') {
						$string1 .='<option CourseReach="national" CourseLevel1="UG"
                title="'.$management_course['CourseName'].'"  '.$selected_string.'
                groupid="'.$management_course['groupId'].'"
                categoryid="'.$management_course['CategoryId'].'"
                value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
                option>';
					} else if ($management_course['CourseLevel1'] == 'PG') {
						$string1 .='<option CourseReach="national" CourseLevel1="PG"
                title="'.$management_course['CourseName'].'"  '.$selected_string.'
                groupid="'.$management_course['groupId'].'"
                categoryid="'.$management_course['CategoryId'].'"
                value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
                option>';
					}
					// check made for distance MBA, set it as local
				} else if($management_course['CourseReach'] == 'local')  {
					$string1 .='<option CourseReach="local" CourseLevel1 = "'.$management_course['CourseLevel1'].'"
                title="'.$management_course['CourseName'].'"  '.$selected_string.'
                groupid="'.$management_course['groupId'].'"
                categoryid="'.$management_course['CategoryId'].'"
                value="'.$management_course['SpecializationId'].'">'.$management_course['CourseName'].'</
                option>';
				}
				$string = $string1;
			}
		}
		if($categoryId!=3) {
			//make course list drop down
	  foreach ($course_list as $groupId => $value) {
	  	//foreach ($itcourseslist as $groupId => $value) {
	  	$string2 = $groupName = '';
	  	usort($value, "cmp");
	  	foreach ($value as $finalArray) {
	  		if (strtolower($finalArray['SpecializationName']) == 'all' && strtolower($finalArray['CourseName']) != 'all')
	  		if ( $finalArray['CourseReach'] == 'national') {
	  			if ($finalArray['CourseLevel1'] == 'UG') {
	  				// change Distance BCA's course reach as local
	  				if ($finalArray['CourseName'] == 'Distance BCA') {
	  					$string2 .='<option CourseReach="local" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				} else {
	  					$string2 .='<option CourseReach="national" CourseLevel1="UG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				}
	  			} else if ($finalArray['CourseLevel1'] == 'PG') {
	  				// change Distance MCA 's course reach as local
	  				if ($finalArray['CourseName'] == 'Distance MCA') {
	  					$string2 .='<option CourseReach="local" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				} else {
	  					$string2 .='<option CourseReach="national" CourseLevel1="PG"
						title="'.$finalArray['CourseName'].'"  '.$selected_string.'
						groupid="'.$finalArray['groupId'].'"
						categoryid="'.$finalArray['CategoryId'].'"
						value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
						option>';
	  				}
	  			}
	  		} else if($finalArray['CourseReach'] == 'local')  {
	  			$string2 .='<option CourseReach="local"
				title="'.$finalArray['CourseName'].'"  '.$selected_string.'
				groupid="'.$finalArray['groupId'].'"
				categoryid="'.$finalArray['CategoryId'].'"
				value="'.$finalArray['SpecializationId'].'">'.$finalArray['CourseName'].'</
				option>';
	  		}
	  	}
	  	$groupName =  $finalArray['groupName'];
	  	$CourseLevel1 = $finalArray['CourseLevel1'] ;
	  	$level = $finalArray['CourseLevel'] ;
	  	if($groupName != '') {
	  		$string .= '<optgroup label="'. $groupName .'">'. $string2
	  		.'</optgroup>';
	  	} else {
	  		$string .=$string2;
	  	}
	  }
		}
		if ( $categoryId == '-1')
		{
			$string = '';
			foreach ($course_list as $key=>$value)
			{
				foreach($value as $index=>$main)
				{
					$string1 .= '<option CourseReach="local" title="'.$main['child']['blogTitle'].'" value="'.$main['child']['blogId'].'">'.$main['child']['acronym'].'</
				option>';
				}
				$string .=  "<optgroup label='". $main['title'] ."'>". $string1."</optgroup>";
				$string1 = "";
			}
		}
		echo "<option value=''> Desired Course </option>".$string;
	}
	function cmp($a, $b)
	{
		$a = $a['CourseName'];
		$b = $b['CourseName'];
		if(substr($a,0,1) == "."){
			return 1;
		}
		if(substr($b,0,1) == "."){
			return -1;
		}
		return (strcmp($a,$b) < 0) ? -1 : 1;
	}
	function loadAjaxForm($type) {

		/*In shiksha recat project we have changed the registration form for national site, so making code to work only for abroad site */
		if(!($type == 'abroadtab' || $type == 'abroadmap')){
			return;
		}

		// if($type == 'flavoured'){
		// 	$displayData = $this->renderFlavouredArticles();
		// 	$string1 = $this->load->view('home/homePageLeftFlavorPanel',$displayData,true);
		// 	$string2 = $this->load->view('home/homePageLeftLatestUpdatesPanel',$displayData,true);
		// 	echo $string1.$string2;
		// 	return;
		// }
		// $Validate = $this->checkUserValidation();
		// $data = $this->loadHomepageRegistrationData($Validate);
		// $data1 = $this->studyAbroad('studyAbroad',$Validate);
		// $data['Validatelogged'] = $Validate;
		// $data = array_merge($data,$data1);
		//$this->output->cache(10080);
		
		if($type == 'localcourse') {
			// echo $this->load->view('/home/homepageRegistration/user_form_homepage_localcourses.php',$data);
		} else if($type == 'pgcourse') {
			// echo $this->load->view('/home/homepageRegistration/user_form_homepage_pg.php',$data);
		} else if($type == 'ugcourse') {
			// echo $this->load->view('/home/homepageRegistration/user_form_homepage_ug_courses.php',$data);
		} else if($type == 'locationlayer') {
			// echo $this->load->view('/home/homepageRegistration/homePageLocationLayer.php',$data);
		} else if($type == 'abroad') {
			echo  $this->load->view('home/homepageRegistration/generalMarketingPage', $data);
		} else if($type == 'india') {
			// echo  $this->load->view('home/homePageLeadFormFields',$data);
		} else if($type == 'homepageform'){
			// echo $this->load->view('home/homePageLeftLeadForm',$data);
		} else if($type == 'abroadtab'){
			echo $this->load->view('home/homepageRedesign/homepage_right_abroad_tab');
		} else if($type == 'abroadmap'){
			echo $this->load->view('home/homepageRedesign/homepage_abroad_map');
		} else if($type == 'indiatab'){
			// echo $this->load->view('home/homepageRedesign/homepage_india_tab');
		} else if($type == 'careers' && CAREER_PRODUCT_FLAG===true ){
			// echo $this->load->view('home/homepageRedesign/careerHomePageWidget');
		}else {
			// echo $this->load->view('/home/homepageRegistration/user_form_homepage_localcourses.php',$data);
		}
	}
	function loadHomepageRegistrationData () {
	  	global $logged;
	  	// Load desired libraries
	  	$this->load->library(array('LDB_Client','category_list_client','listing_client','MY_sort_associative_array'));
	    // Validate user
	  	$validity = $userStatus = $this->checkUserValidation();
        $Validate = $userStatus;
        if($Validate == "false"){
        	$userid = '';
        }
        else{
        	$userid = $Validate['0']['userid'];
        	$displayname = $Validate['0']['displayname'];
        }
        if(($validity == "false" )||($validity == "")) {
        	$logged = "No";
        } else {
        	$logged = "Yes";
        }
		$data = array();
		$thisUrl = $_SERVER['REQUEST_URI'];
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		if(isset($_POST['user_name']) && !empty($_POST['user_name'])) {
			$userName = isset($_POST['user_name']) ? $_POST['user_name']:'';
			$userEmail = isset($_POST['user_email']) ? $_POST['user_email']:'';
			$userInterest = isset($_POST['user_interest']) ? $_POST['user_interest']:'';
			$userPassword = isset($_POST['user_password']) ? $_POST['user_password']:'';
			$userContactno = isset($_POST['user_contactno']) ? $_POST['user_contactno']:'';
		}
		$data['prefix'] = "";
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$cityListTier2 = $categoryClient->getCitiesInTier($appId,2,2);
		$cityListTier3 = $categoryClient->getCitiesInTier($appId,0,2);
        $categoryList = $categoryClient->getCategoryList(1,1);
        $catgeories = array();
        foreach($categoryList as $category) {
            $categories[$category['categoryID']] = $category['categoryName'];
        }
        asort($categories);
        $data['categories'] = $categories;
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
		$data['userName'] = $userName;
		$data['userEmail'] = $userEmail;
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['userContactno'] = $userContactno;
		$data['logged'] = $logged;
		$data['userData'] = $userStatus;
		$data['validateuser'] = $validity;
		$data['select_city_list'] = $this->_select_city_list();
		/* end new lib api */
		$data1= $this->_marketing_page($userid);
		$data = array_merge($data1,(array)$data);
		return $data;
	  }
	private function _select_city_list() {
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();
		$cityListTier1 = $categoryClient->getCitiesInTier($appId,1,2);
		$this->load->library('MY_sort_associative_array');
		$sorter = new MY_sort_associative_array;
		$finalArray = array();
		foreach ($cityListTier1 as $list) {
			if ($list['stateId'] == '-1') {
				$finalArray['virtualCity'][] = json_decode($categoryClient->getCitiesForVirtualCity(1,$list['cityId']),true);
			} else {
				$finalArray['metroCity'][] = $list;
			}
		}
		$string ='';
		$finalArray['virtualCity']  = $sorter->sort_associative_array($finalArray['virtualCity'], 'city_name');
		foreach ($finalArray['virtualCity'] as $list) {
			foreach ($list as $key) {
				if ($key['virtualCityId'] == $key['city_id']) {
					$string .='<OPTGROUP LABEL="'.$key['city_name'].'">';
					foreach ($finalArray['virtualCity'] as $list1) {
						$list1  = $sorter->sort_associative_array($list1, 'city_name');
						foreach ($list1 as $key1) {
							if ($key1['virtualCityId'] != $key1['city_id']) {
				    if ($key['city_id'] == $key1['virtualCityId']) {
				    	$string .='<OPTION title="'.$key1['city_name'].'" value="'.$key1['countryId'] . ':' . $key1['state_id'] . ':' . $key1['city_id'].'">'.$key1['city_name'].'</OPTION>';
				    }
							}
						}
					}
				}
			}
		}
		$string .='<OPTGROUP LABEL="Metro Cities">';
		$finalArray['metroCity'] = $sorter->sort_associative_array($finalArray['metroCity'] , 'cityName');
		foreach ($finalArray['metroCity'] as $key1) {
			$string .='<OPTION title="'.$key1['cityName'].'" value="'.$key1['countryId'] . ':' . $key1['stateId'] . ':' . $key1['cityId'].'">'.$key1['cityName'].'</OPTION>';
		}
		$ldbObj = new LDB_Client();
		$listing_client = new listing_client();
		$country_state_city_list = json_decode($ldbObj->sgetCityStateList(12),true);
		foreach($country_state_city_list as $list)
		{
			if($list['CountryId'] == 2)
			{
				foreach($list['stateMap'] as $list2)
				{
					$string .='<OPTGROUP LABEL="'.$list2['StateName'].'">';
					foreach($list2['cityMap'] as $list3)
					{

						$string .='<OPTION title="'.$list3['CityName'].'" value="'.$list['CountryId'] . ':' . $list2['StateId'] . ':' . $list3['CityId'].'">'.$list3['CityName'].'</OPTION>';
					}
				}
			}
		}
		return $string;
	}
	private function _marketing_page($userid)
	{
		$data = array();
		$this->load->library(array('LDB_Client','category_list_client','listing_client','MY_sort_associative_array'));
		$appId = 1;
		$ldbObj = new LDB_Client();
		$listing_client = new listing_client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		$cityListTier = $data['country_state_city_list'];
		$course_list = json_decode($ldbObj->sgetCourseList($appId,3),true);
		$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
		$data['distance_course'] = $distance_course;
		$userCompleteDetails = '';
		if($userid != '')
		$userCompleteDetails = $ldbObj->sgetUserDetails($appId,$userid);
		$userDataToShow = $this->makeUserData($userCompleteDetails);
		$countryCityStateMaps = $this->getCityStatesFromMap($cityListTier);
		$data['countriesList'] = $countryCityStateMaps['countries'];
		$data['statesList'] = $countryCityStateMaps['states'];
		$data['citiesList'] = $countryCityStateMaps['cities'];
		$data['userDataToShow'] = $userDataToShow;
		$data['newDesiredCourse_list'] = $course_list;
		$data['userCompleteDetails'] = $userCompleteDetails;
		$data['CitiesWithCollege'] = $listing_client->getCitiesWithCollege(1,2);
		// flag for JSB9 Tracking
		$data['trackForPages'] = true;
		$data['other_exm_list'] = $this->_other_exm_list();
		$data['work_exp_combo'] = $this->_work_exp_combo();
		if($userid != '') {
			$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
			$TimeOfStart =
			$userCompleteDetails[0]['PrefData'][0]['TimeOfStart'];
			$data['when_you_plan_start'] = $this->_when_you_plan_start($TimeOfStart,FALSE);
			$array_ug_courses = $userCompleteDetails[0]['EducationData'];
			$PrefData = $userCompleteDetails[0]['PrefData'];
			foreach ($PrefData as $value) {
				if (count($value['LocationPref']) > 0) {
					$data['LocalityCityValue'] = $value['LocationPref'][0]['CountryId'].":".$value['LocationPref'][0]['StateId'].":".$value['LocationPref'][0]['CityId'].":".$value['LocationPref'][0]['LocalityId'];
					$data['LocalityCityName'] = $value['LocationPref'][0]['LocalityName'];
				}
			}
			$name_ug_course = '';
			$ug_marks = '';
			$CourseCompletionDate = '';
			foreach ($array_ug_courses as $data_ug) {
				if ($data_ug['Level'] == 'UG') {
					$name_ug_course = $data_ug['Name'];
					$ug_marks = $data_ug['Marks'];
					$CourseCompletionDate = $data_ug['CourseCompletionDate'];
					$ug_city_id = $data_ug['City'];
					$ug_institute_id = $data_ug['InstituteId'];
					$ug_institute_name = trim($data_ug['institute_name']);
				}
			}
			$data['course_lists'] = $this->_course_lists($name_ug_course,FALSE);
			$data['ug_marks'] = $ug_marks;
			$data['CourseCompletionDate'] = $CourseCompletionDate;
			$data['ug_city_id'] = $ug_city_id;
			$data['ug_institute_id'] = $ug_institute_id;
			$data['ug_institute_name'] = $ug_institute_name;
		} else {
			$data['when_you_plan_start'] = $this->_when_you_plan_start();
			$data['course_lists'] = $this->_course_lists();
		}
		return $data;
	}
	function makeUserData($userCompleteDetails)
	{
		$userCompleteDetails = $this->make_array(json_decode($userCompleteDetails,true));
		$userprefarray = $userCompleteDetails[0]['PrefData'][0];
		$usereduarray = $userCompleteDetails[0]['EducationData'];
		$userlocpref = $userCompleteDetails[0]['PrefData'][0]['LocationPref'];
		$statearray = array();
		$cityarray = array();

		$userarray = array('name'=>isset($userCompleteDetails[0]['firstname']) ? $userCompleteDetails[0]['firstname']:'',
		   'email'=>isset($userCompleteDetails[0]['email']) ? $userCompleteDetails[0]['email']:'',
		   'emailenable'=>(isset($userCompleteDetails[0]['email']) && trim($userCompleteDetails[0]['email']) != '') ? 'disabled':'',
		   'mobile'=>(isset($userCompleteDetails[0]['mobile']) && trim($userCompleteDetails[0]['mobile']) != '') ? $userCompleteDetails[0]['mobile']:'',
		   'cityid'=>(isset($userCompleteDetails[0]['city']) && trim($userCompleteDetails[0]['city']) > 0) ? $userCompleteDetails[0]['city']:'',
		   'experience'=>(isset($userCompleteDetails[0]['experience'])) ? $userCompleteDetails[0]['experience']:'',
		  'AICTE' => (isset($userprefarray['DegreePrefAICTE']) && $userprefarray['DegreePrefAICTE'] == 'yes') ? 'checked' :'',
		  'UGC' => (isset($userprefarray['DegreePrefUGC']) && trim($userprefarray['DegreePrefUGC']) == 'yes') ? 'checked' :'',
		  'International' => (isset($userprefarray['DegreePrefInternational']) && $userprefarray['DegreePrefInternational'] == 'yes') ? 'checked' :'',
		  'Anydegree' => (isset($userprefarray['DegreePrefAny']) && $userprefarray['DegreePrefAny'] == 'yes') ? 'checked' :'',
		  'fulltime' => (isset($userprefarray['ModeOfEducationFullTime']) && $userprefarray['ModeOfEducationFullTime'] == 'yes') ? 'checked' :'',
		  'parttime' => (isset($userprefarray['ModeOfEducationPartTime']) && $userprefarray['ModeOfEducationPartTime'] == 'yes') ? 'checked' :'',
		  'distance' => (isset($userprefarray['ModeOfEducationDistance']) && $userprefarray['ModeOfEducationDistance'] == 'yes') ? 'checked' :'',
		);
		for($i=0;$i<count($userlocpref);$i++)
		{
			if($userlocpref[$i]['CityId'] == -1  || $userlocpref[$i]['CityId'] == 0)
			$statearray[] = $userlocpref[$i]['StateId'];
			else
			{
				if($userlocpref[$i]['CityId'] != NULL)
				$cityarray[] = $userlocpref[$i]['CityId'];
			}
		}

		$userarray['statearray'] = $statearray;
		$userarray['cityarray'] = $cityarray;
		for($j = 0;$j<count($usereduarray);$j++)
		{
			if($usereduarray[$j]['Level'] == 'Competitive exam')
			{
				$arrcolname = $usereduarray[$j]['Name'];
				$userarray[$arrcolname] = $usereduarray[$j]['Marks'];
			}
			else
			{
				$completiondate = getdate($usereduarray[$j]['CourseCompletionDate']);
				$arrcolname = $usereduarray[$j]['Level'];
				$userarray[$arrcolname.'details'] = $usereduarray[$j]['Name'];
				$userarray[$arrcolname.'ongoing'] = '';
				$userarray[$arrcolname.'completed'] = '';
				if($usereduarray[$j]['OngoingCompletedFlag'] == '0') {
					$userarray[$arrcolname.'completed'] = 'checked';
				} else {
					$userarray[$arrcolname.'ongoing'] = 'checked';
				}
				$userarray[$arrcolname.'city'] = $usereduarray[$j]['City'];
				$userarray[$arrcolname.'institute'] = $usereduarray[$j]['InstituteId'];
				$userarray[$arrcolname.'marks'] = $usereduarray[$j]['Marks'];
				$userarray[$arrcolname.'completionmonth'] = $completiondate['mon'];
				$userarray[$arrcolname.'completionyear'] = $completiondate['year'];
			}
		}
		return json_encode($userarray);
	}
	function make_array($an_array) {
		$return_array = array();
		foreach ($an_array as $key => $val) break;
		$return_array[] = $val;
		return $return_array;
	}
	private function getCityStatesFromMap($countryStateCityMap) {
		$cities = $states = $countries = array();
		foreach($countryStateCityMap as $country) {
			$countryName = $country['CountryName'];
			$countryId = $country['CountryId'];
			$countries[$countryId]['name'] = $countryName;
			$countries[$countryId]['value'] = $countryId .':0:0'; // For TUserLocationPref
			$statesForCountry = $country['stateMap'];
			foreach($statesForCountry as $state) {
				$stateName = $state['StateName'];
				$stateId = $state['StateId'];
				if (!empty($stateId)) {
					$states[$stateId]['name'] = $stateName;
					$states[$stateId]['value'] = $countryId .':'. $stateId .':0';
				}
				$citiesForState = $state['cityMap'];
				foreach($citiesForState as $city) {
					$cityName = $city['CityName'];
					$cityId = $city['CityId'];
					$cityTier = $city['Tier'];
					$cities['tier'. $cityTier][$cityId]['name'] = $cityName;
					$cities['tier'. $cityTier][$cityId]['value'] = $countryId  .':'. $stateId .':'. $cityId;
				}
			}
		}
		return array('cities' => $cities, 'states' => $states, 'countries' => $countries);
	}
	function _other_exm_list($selected = NULL,$flag = FALSE) {
		$array = array('Management-Label','CAT','MAT','XAT','UGAT','Engineering-Label','IITJEE','GATE','International Exams-Label','TOEFL','IELTS','GRE','GMAT');
		$string = '';
		for ($i = 0; $i < count($array);$i++)  {
			if (strpos($array[$i], 'Label')) {
				$new_array = explode('-', $array[$i]);
				$string .='<OPTGROUP LABEL="'.$new_array[0].'">';
			} else {
				if ($selected != NULL) {
					if ($selected == $array[$i] ) {
						$selected_string = "selected";
					} else {
						$selected_string = "";
					}
				}
				$string .='<OPTION '.$selected_string.' value="'.$array[$i].'">'.$array[$i].'</OPTION>';
			}
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	function _work_exp_combo($selected = NULL,$flag = FALSE) {
		$string = '';
		$string .='<option '.$selected_string.' value="-1">No Experience</option>';
		for ($i = 0; $i <= 10;$i++)  {
			if ($selected != NULL) {
				if ($selected == $i ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}

			if ($i == 0) {
				$string .='<option '.$selected_string.' value="'.$i.'">< 1 year</option>';
			} elseif ($i == 10) {
				$string .='<option '.$selected_string.' value="'.$i.'">> 10 years</option>';
			} else {
				$string .='<option '.$selected_string.' value="'.$i.'">'.$i.' - '.($i+1).' years</option>';
			}
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	function _when_you_plan_start($selected = NULL,$flag = FALSE) {
		$array= array(
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+1)) => 'This Year’s Academic Season',
		date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Next Year’s Academic Season',
		//date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y")+2)) => 'Within next two years',
		'0000-00-00 00:00:00' => 'Not Sure',
		);
		$string = '';
		foreach ($array as $key => $value)  {
			if ($selected != NULL) {
				if ($selected == $key ) {
					$selected_string = "selected";
				} else {
					$selected_string = "";
				}
			}
			$string .='<option '.$selected_string.' value="'.$key.'">'.$value.'</option>';
		}
		if ($flag) {
			return $array;
		} else {
			return $string;
		}
	}
	function studyAbroad($pagename='studyAbroad',$url='',$validity) {
		$this->load->library(array('LDB_Client','category_list_client','listing_client','MY_sort_associative_array'));
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
		} else {
			$logged = "Yes";
		}
		$data = array();
		$userName = '';
		$userEmail = '';
		$userInterest = -1;
		$userPassword = '';
		$backPageUrl = $url;
		$data['logged'] = $logged;
		$data['userName'] = $userName;
		$data['userEmail'] = $userEmail;
		$data['userInterest'] = $userInterest;
		$data['userPassword'] = $userPassword;
		$data['logged'] = $logged;
		$data['userData'] = $validity;
		$data['extraPram'] = $extraPram;
		$data['backPage'] = $backPageUrl;

		$data['pageName'] = 'studyAbroad';
		// load data for courses list
		$this->load->library('Category_list_client');
		$category_list_client = new Category_list_client();
		$categoryList = $category_list_client->getCategoryList(1,1);
		$catgeories = array();
		foreach($categoryList as $category) {
			$categories[$category['categoryID']] = $category['categoryName'];
		}
		asort($categories);
		$data['categories'] = $categories;

		$regions= json_decode($category_list_client->getCountriesWithRegions(1), true);
		$data['regions'] = $regions;
		$data['course_lists'] = $this->_course_lists();

		$cityListTier1 = $category_list_client->getCitiesInTier($appId,1,2);
		$cityListTier2 = $category_list_client->getCitiesInTier($appId,2,2);
		$cityListTier3 = $category_list_client->getCitiesInTier($appId,0,2);
		$data['cityTier2'] = $cityListTier2;
		$data['cityTier3'] = $cityListTier3;
		$data['cityTier1'] = $cityListTier1;
		$data['allCategories'] = $categoryList;
		$ldbObj = new LDB_Client();
		$data['country_state_city_list'] = json_decode($ldbObj->sgetCityStateList(12),true);
		return $data;
	}
	function renderFlavouredArticles() {
		$this->load->library('blog_client');
		$appId = 1;
		$blogs = array();
		$blog_client = new Blog_client();
		$criteria = array();
		$orderBy = 'creationDate desc';
		
		$countOffset = 10;
		$startOffset = 0;
		$allArticles = $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset,'homepage');
		$allArticles = $allArticles[0]['results'];
		if(is_array($allArticles)) {
			$allArticles = $allArticles['articles'];
		} else {
			$allArticles = array();
		}
		$countOffset = 40;
		$criteria = array('blogType'=> 'news');
		$newsArticles= $blog_client->getArticlesForCriteria($appId, $criteria, $orderBy, $startOffset, $countOffset,'homepage');
		$newsArticles=json_encode($newsArticles[0]['results'],true);
		$displayData['newsArticles'] = $newsArticles;
		$displayData['allArticles'] = $allArticles;
		return $displayData;
	}

	/**
	 * purpose to get incomplete online forms in a floating widget on all pages of website
	 * Author: Aman Varshney
	 * @return encode html of the widget
	 */
	function floatingWidgetOnlineForms($product,$subCategoryId){
		/* Adding XSS cleaning (Nikita) */
		$product = $this->security->xss_clean($product);
		$subCategoryId = $this->security->xss_clean($subCategoryId);
		
		global $usersForPaytmTesting;    // this is defined in shikshaConstants

		// to get UserId
		$userInfo = $this->checkUserValidation(); 
		
		$isUserLoggedIn = false; 
 		$userId = 0; 

 		// get default Coupon Code
 		$defaultCouponCode = DEFAULT_COUPON_CODE;
		
 		// check the widget state from cookie
		//if(isset($_COOKIE['floatingOnlineFormWidget']) && !empty($_COOKIE['floatingOnlineFormWidget']))
		if($this->input->cookie('floatingOnlineFormWidget')) {
			$displayData['formState'] = $this->input->cookie('floatingOnlineFormWidget', true);
		}else{
			$displayData['formState'] = 'open';
		}

		$displayData['subCategoryId']        = $subCategoryId;
		if($subCategoryId == MBA_SUBCAT_ID)
			$displayData['onlineFormHomePageNewUrl'] 	= SHIKSHA_HOME.'/mba/resources/application-forms';
		elseif($subCategoryId == ENGINEERING_SUBCAT_ID)
			$displayData['onlineFormHomePageNewUrl'] 	= SHIKSHA_HOME.'/college-admissions-engineering-online-application-forms';
		
 		if($userInfo != 'false'){ 
 			// if user is loggedIn then get userId
 		        $isUserLoggedIn = true; 
 		        $userId = $userInfo[0]['userid']; 
 		}else{
 			if(($product == 'nationallistings' || $product == 'ranking' || $product == 'RNRCategoryPage') && ($subCategoryId == MBA_SUBCAT_ID || $subCategoryId == ENGINEERING_SUBCAT_ID)){
 			//if user is not loggedIn. Show default coupon code
				$isUserLoggIn                        = false;
				$displayData['isUserLoggedIn']       = $isUserLoggedIn;
				$displayData['couponCode']           = $defaultCouponCode;
		
				$bufferOutput                        = $this->load->view('floatingWidgetOnlineForms',$displayData,true);
				//return array('html'                =>$bufferOutput,'totalSlides'=>$totalSlides); 
				
				
        		if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){
                	// do the new stuff related to paytm functionality
                	echo json_encode(array("result_html" => $bufferOutput));
        		}
     		}
				exit;
 		} 

 		// loading online form model
		$this->load->model('Online/onlineparentmodel');
		$onlineModel         = $this->load->model('Online/onlinemodel');    
		
        // to get Coupon Code userwise
        $couponLib = $this->load->library("common/CouponLib");
        $couponCode = $couponLib->getUserCoupon($userId);

        //if user has couponcode then get encoded coupon code for url
        if(!empty($couponCode)){
			$encodedCouponCode                = $couponLib->encodeCouponCode($couponCode);
			$displayData['encodedCouponCode'] = $encodedCouponCode;
			$displayData['userId'] = $userId;
        }

        // to get Institute Array
		$this->load->helper('listing/listing'); 
		$this->load->builder('ListingBuilder','listing'); 
		$listingBuilder      = new ListingBuilder; 
		$instituteRepository = $listingBuilder->getInstituteRepository();


	 	$courseInstituteMapping = array();
	 	$courseIds =array();
        if($userId){
        	// api to get Incomplete Online Forms List
            $incompleteOnlineForms = $onlineModel->getIncompleteOnlineFormsByUserId($userId);
        }

       

        if(!empty($incompleteOnlineForms)){
        	foreach($incompleteOnlineForms as $row)
	        {
	            $courseInstituteMapping[$row['instituteId']] = $row['courseId'];
	            $courseIds[] = $row['courseId'];
	        }

	        $onlineFormLib = $this->load->library("Online/OnlineFormUtilityLib");
			$completeStatusPercentage = $onlineFormLib->getOnlineFormStatus($courseIds , $userId);
	       
			$courses                                              = $instituteRepository->findWithCourses($courseInstituteMapping);
			$displayData['recommendations']                       = $courses;
			$displayData['onlineFormData']                        = $incompleteOnlineForms;
			$displayData['completeStatusPercentage']              = $completeStatusPercentage;
			$totalIncompleteForms                                 = count($incompleteOnlineForms);
			$displayData['totalIncompleteForms']                  = $totalIncompleteForms;
			
			$totalSlides                                          = ceil($totalIncompleteForms/2); 
			$displayData['totalSlides']                           = $totalSlides;
			
			//$displayData['onlineFormHomePageNewUrl']              = SHIKSHA_HOME.'/college-admissions-online-mba-application-forms';

			//added by akhter
			//get dashboard config for online form
			$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
			$displayData['institutes_autorization_details_array'] = $this->national_course_lib->getOnlineFormAllCourses();
        }else{
        	if(!(($product == 'nationallistings' || $product == 'ranking' || $product == 'RNRCategoryPage') && ($subCategoryId == MBA_SUBCAT_ID || $subCategoryId == ENGINEERING_SUBCAT_ID))){
        		exit;
        	}
        }

 		
		$displayData['isUserLoggedIn']                        = $isUserLoggedIn;
		$displayData['couponCode']                            = $couponCode;
		$displayData['defaultCouponCode']                     = $defaultCouponCode;

		// payTM Flag
		if(OF_PAYTM_INTEGRATION_FLAG == 1 || in_array($userId,$usersForPaytmTesting)){
        	$displayData['payTMFlag'] = true;
		}else{
			$displayData['payTMFlag'] = false;
		}

	    $bufferOutput = $this->load->view('floatingWidgetOnlineForms',$displayData,true);
	    
	    //return array('html'=>$bufferOutput,'totalSlides'=>$totalSlides); 
	    echo json_encode(array("result_html" => $bufferOutput));
	     
	     exit;
	}

	function sendShareCodeToUser(){
		$userId            = $this->input->post('userId');
		$encodedCouponCode = $this->input->post('encodedCouponCode');

		$cacheLib = $this->load->library("listing/cache/ListingCache");
		$getLastShareCouponCode = $cacheLib->getShareCouponsUser($userId); 
		
		if(!empty($getLastShareCouponCode)){
			
		//compare current date and getLastShareCoupon date
			$couponDate     = new DateTime($getLastShareCouponCode);
			$currentDate    = new DateTime();
			$interval       = $couponDate->diff($currentDate);
			$daysDifference = $interval->days;		

			if($daysDifference > 0){
				$cacheLib->storeShareCouponsUser($userId); 
				
				// send mail and sms to users
				$couponLib = $this->load->library("common/CouponLib");
				$couponLib->sendEmailSMSToUser($userId);
			}
		}else{
				$cacheLib->storeShareCouponsUser($userId); 
				
				// send mail and sms to users
				$couponLib = $this->load->library("common/CouponLib");
				$couponLib->sendEmailSMSToUser($userId);
		}
		

		echo "1";

	}

	public function getExamsStreamWise(){
		$stream = $this->input->post("stream");
		if(empty($stream)){
			return;
		}
		$examMainLib = $this->load->library('examPages/ExamMainLib');
		$data = $examMainLib->getExamPagesByStream($stream);
		$html = "<option disabled='disabled' selected='selected'></option>";
		uasort($data,function($a,$b){if($a['name'] < $b['name']) {return -1;}else{return 1;}});

		foreach($data as $examPageId => $examDetails){
			$html .= "<option url='/".$examDetails['url']."' value='".$examDetails['name']."'>".$examDetails['name']."</option>";
    	}
    	
    	echo $html;
	}

	/**
	 * To get active exam page list by given category
	 * @author Romil Goel <romil.goel@shiksha.com>
	 * @date   2015-06-29
	 * @return [html]
	 */
	function getExamsCategoryWise(){
		$category = $this->input->post("category");
		if(empty($category)){
			return;
		}

		$ExamPageLib             = $this->load->library('examPages/ExamPageLib');
		$categoriesWithExamNames = $ExamPageLib->getCategoriesWithExamNames();
		$categoryWithExamNames   = $categoriesWithExamNames[$category];
		$html                    = "<option disabled='disabled' selected='selected'></option>";
		uksort($categoryWithExamNames,function($a,$b){return strcasecmp($a,$b);});
		
    	foreach($categoryWithExamNames as $examName=>$examDetails){
			$html .= "<option url='".$examDetails['url']."' value='".$examName."'>".$examName."</option>";
    	}
    	
    	echo $html;
	}

	function dbTrackingForGAParams() {
		return;
		$data['currentUrl'] = $this->input->post('currentUrl');
		$data['gaString'] 	= $this->input->post('gaString');
		$data['source'] 	= $this->input->post('source');
		
		$listingScriptsModel = $this->load->model('listingscriptsmodel');
		$listingScriptsModel->dbTrackingForGAParams($data);
	}

	function getHomepageFeaturedTextAds($outputType = 'json') {
		$this->load->library('homepage/Homepageslider_client');
		$slider_object = new Homepageslider_client();
		$displayData['featuredTextAds'] = $slider_object->getHomePageCmsData('featured');
		if($outputType == 'json') {
			echo json_encode($this->load->view('home/homepageRedesign/homepageFeaturedTextAds', $displayData, true));
		}
		else {
			$this->load->view('home/homepageRedesign/homepageFeaturedTextAds', $displayData);
		}
	}

	/**
	 * [trackBannerCtrHomepage will track CTR for the url corresponding to an id]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2015-10-27
	 * @param  [type]     $bannerId    [description]
	 * @param  [type]     $redirectUrl [description]
	 */
	function trackBannerCtrHomepage($bannerId, $redirectUrl) {
		if(isset($bannerId) && is_numeric($bannerId)) {
			$HomepageCmsModel = $this->load->model('home/homepagecmsmodel');
			$targetUrl = $HomepageCmsModel->checkAndUpdateBannerCtr($bannerId);
			if(!empty($targetUrl))
			{
				redirect($targetUrl, 'location');
			}
			else
			{
				show_404();	
			}
		}
		else {
			show_404();
		}
	}

	/*
	 * [refreshHomepageCache updates homepage cache]
	 * @author Ankit Garg <g.ankit@shiksha.com>
	 * @date   2015-12-21
	 */
	function removeMiddlePanelCache() {
		$blogClient = $this->load->library('blogs/blog_client');
		$this->load->library('homepage/Homepageslider_client');
		$HomepagesliderClient = Homepageslider_client::getInstance();
		//for removing middle panel cache
		$blogClient->getHomePageFeaturedArticles(true);
		$blogClient->getHomePageFeatureAndNewsArticle('feature',10,'homepageFeatureArticle',true);
		//for removing cover banner and featured articles cache
		$HomepagesliderClient->getHomePageCmsData('banner', true);
		$HomepagesliderClient->getHomePageCmsData('featured', true);
		echo "Cache Updated successfully";
	}

	function getCustomLocalityCityList() {
		$jsVersion = $_REQUEST['v'];
		$etag = md5($jsVersion);
		// always send headers
		header("Etag: $etag"); 
		// exit if not modified
		if (@trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) { 
		    header("HTTP/1.1 304 Not Modified"); 
		    exit; 
		}
		header('Content-Type: application/javascript');
		$CustomCoursesList = Modules::run('registration/Forms/getAllCustomCourse');
	 	$CustomLocCityList = Modules::run('registration/Forms/allCustomLocCityList',$CustomCoursesList);
	 	if(isset($CustomCoursesList)) {
			echo "var allCustomCoursesList = $CustomCoursesList;";
		} else {
			echo "var allCustomCoursesList = [];";
		}
		
		if(isset($CustomLocCityList)) {
			echo "var CustomCourses = $CustomLocCityList;";
		} else {
			echo "var CustomCourses = [];";
		}
		exit;
	}
	function testETags() {
		$jsVersion = $_REQUEST['v'];
		$etag = md5($jsVersion);
		// always send headers
		$expireDate = gmdate("D, d M Y H:i:s", time() + 12000);
        header("Expires: $expireDate GMT"); //future time
        header("Pragma: cache");
		header("Cache-Control: public");
		header("Etag: $etag"); 
		// exit if not modified
		if (@trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) { 
		    header("HTTP/1.1 304 Not Modified"); 
		    exit; 
		}
		header('Content-Type: application/javascript');
		$CustomCoursesList = Modules::run('registration/Forms/getAllCustomCourse');
	 	$CustomLocCityList = Modules::run('registration/Forms/allCustomLocCityList',$CustomCoursesList);
	 	if(isset($CustomCoursesList)) {
			echo "var allCustomCoursesList = $CustomCoursesList;";
		} else {
			echo "var allCustomCoursesList = [];";
		}
		
		if(isset($CustomLocCityList)) {
			echo "var CustomCourses = $CustomLocCityList;";
		} else {
			echo "var CustomCourses = [];";
		}
		exit;
	}

	function team() {
		$displayData['validateuser']  = $this->checkUserValidation();

		//below code used for beacon tracking
        $displayData['trackingpageIdentifier'] = 'managementTeamPage';
        $displayData['trackingcountryId']=2;
        //loading library to use store beacon traffic inforamtion
        $this->tracking=$this->load->library('common/trackingpages');
        $this->tracking->_pagetracking($displayData);

        $this->benchmark->mark('dfp_data_start');
        $dfpObj   = $this->load->library('common/DFPLib');
        $dpfParam = array('parentPage'=>'DFP_Team');
        $displayData['dfpData']  = $dfpObj->getDFPData($displayData['validateuser'], $dpfParam);
        $this->benchmark->mark('dfp_data_end');

		$this->load->view('common/managementTeam',$displayData);
	}

/* In case of desktop- those articles which have been marked as latest update from cms 
in case of mobile- articles on the basis of recency. */

	function getRecentArticles($limit,$pageName,$articleNew=''){
		$this->load->model('blogs/articlemodel');
		$displayData['numberOfArticlesDisplayed'] = $limit;
		if($pageName == 'homepage'){
			$displayData['latestArticles'] = $this->articlemodel->getRecentArticles($limit);
			$this->load->view('home/homepageRedesign/homepage_v3/latestArticles',$displayData);
		}
		if($pageName == 'homepageMobile'){
			$displayData['latestArticles'] = $this->articlemodel->getLatestUpdatedArticles($limit);
			if($articleNew == 1){
				$this->load->view('mcommon5/homepageWidgets/latestArticlesWidget_test',$displayData);
			}else{
				$this->load->view('mcommon5/homepageWidgets/latestArticlesWidget',$displayData);	
			}
			
		}
	}

	function getExpertFoldInner(){

		$Validate 					  = $this->checkUserValidation();
		$displayData = array();
		if(($Validate == "false" )||($Validate == "")) {
			$displayData['GA_userLevel'] = 'Non-Logged In';
		} else {
			$displayData['GA_userLevel'] = 'Logged In';
		}
		$displayData['GA_Tap_On_What_Question'] = 'QUESTION_CTA_SHIKSHAHOMEPAGE_DESKAnA';
		$displayData['GA_currentPage'] = 'SHIKSHAHOMEPAGE_DESKAnA';
		echo $this->load->view('home/homepageRedesign/homepage_v3/expertFoldInner',$displayData);
	}

	function getTestimonials(){
		$this->load->library('cacheLib');
		$cacheLib = new cacheLib();
		$key = 'testimonialsHomePage';
		if($cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->load->model('home/homepagecmsmodel');
			$displayData['data'] = $this->homepagecmsmodel->getTestimonialData('getTestimonials');
			$cacheLib->store($key, $displayData , -1);
		}
		else{
			$displayData = $cacheLib->get($key);
		}
		$this->load->view('home/homepageRedesign/homepage_v3/testimonials',$displayData);
	}

	function getMarketingFoldInner(){
		$displayData['data'] = $this->getMarketingFold();
		echo $this->load->view('home/homepageRedesign/homepage_v3/marketingFoldInner',$displayData);
	}

	function getMarketingFold(){
		$this->load->library('cacheLib');
		$cacheLib = new cacheLib();
		$key = 'marketingFoldHomePage';
		if($cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->load->model('home/homepagecmsmodel');
			$displayData = $this->homepagecmsmodel->getMarketingFoldData('makeLive');
			$cacheLib->store($key, $displayData , -1);
		}
		else{
			$displayData = $cacheLib->get($key);
		}
		return $displayData;
	}

	//this is used to clear cache data based on keys
	function clearCache(){
		$cacheObj = $this->load->library('examPages/cache/ExamPageCache');
		$keyList = array('testimonialsHomePage',md5('homepageFeaturedWidgetData'),md5('homepageArticleWidgetData'),'examUrls','listOfExams','HierarchiesWithExamNames','marketingFoldHomePage','featuredExams_GNB',md5('getInstituteForTabs'),'_otherKey','_ftrClgBnr',md5('autoModerationKeywordData'));
		foreach ($keyList as $index => $key) {
			$cacheObj->clearCacheByKey($key);
		}
		echo 'Done';
	}	

	function enableShikshaAssistant(){
   		setcookie("SAab", 2,time()+36000,'/',COOKIEDOMAIN);
   		header("Location: ".SHIKSHA_HOME);
    }

    function getSocialSharingLayer(){
    	$fromWhere          = $this->input->post('fromWhere',true);
    	$data['shareUrl']   = $this->input->post('shareUrl',true);
    	$data['h1Text']     = $this->input->post('h1Text',true);
    	$data['fromWhere']  = ($fromWhere == 'mobile') ? 'mobile' : 'desktop';
    	$data['title']      = 'Share';
    	$data['position']   = 'header';
    	if($fromWhere == 'mobile'){
    		echo $this->load->view('mcommon5/SocialSharingContainer',$data);
    	}else{
    		echo $this->load->view('common/SocialSharingContainer',$data);
    	}
    }
}
