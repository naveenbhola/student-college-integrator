<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: kabhinav $:  Author of last commit
$Date: 2010/09/22 06:05:17 $:  Date of last commit

This class provides the Category List web service
The category_list_client.php makes call to this server using XML RPC calls.

$Id: Category_list_server.php,v 1.41.30.4 2010/09/22 06:05:17 kabhinav Exp $:

*/

class Category_list_server extends MX_Controller {

	/*
	*	index function to recieve the incoming request
	*/

	function index(){

		//load XML RPC Libs
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
        $this->dbLibObj = DbLibCommon::getInstance('CategoryList');
		$this->db = $this->dbLibObj->getReadHandle();
		
		//Define the web services method
		$config['functions']['sgetCategoryCourseList'] = array('function' => 'Category_list_server.sgetCategoryCourseList');

		$config['functions']['getCategoryList'] = array('function' => 'Category_list_server.getCategoryList');

		$config['functions']['insertCategory'] = array('function' => 'Category_list_server.insertCategory');

		$config['functions']['enableCategory'] = array('function' => 'Category_list_server.enableCategory');

		$config['functions']['getCategoryTree'] = array('function' => 'Category_list_server.getCategoryTree');

		$config['functions']['getCountries'] = array('function' => 'Category_list_server.getCountries');

		$config['functions']['getZones'] = array('function' => 'Category_list_server.getZones');

		$config['functions']['getCategoryFeeds'] = array('function' => 'Category_list_server.getCategoryFeeds');

		$config['functions']['getCategoryFeedsForHomePage'] = array('function' => 'Category_list_server.getCategoryFeedsForHomePage');

		$config['functions']['getCategoryIdByURLName'] = array('function' => 'Category_list_server.getCategoryIdByURLName');

                $config['functions']['get_category_name'] = array('function' => 'Category_list_server.get_category_name');

                $config['functions']['getCityId'] = array('function' => 'Category_list_server.getCityId');

                $config['functions']['getCityName'] = array('function' => 'Category_list_server.getCityName');

                $config['functions']['getBlogId'] = array('function' => 'Category_list_server.getBlogId');

                $config['functions']['getBlogAcronym'] = array('function' => 'Category_list_server.getBlogAcronym');
				$config['functions']['getBlogParent'] = array('function' => 'Category_list_server.getBlogParent');

                $config['functions']['getBlogTitle'] = array('function' => 'Category_list_server.getBlogTitle');

                $config['functions']['get_testprep_menu_tree'] = array('function' => 'Category_list_server.get_testprep_menu_tree');

		$config['functions']['getCategoryIdByName'] = array('function' => 'Category_list_server.getCategoryIdByName');

		$config['functions']['getSubCategories'] = array('function' => 'Category_list_server.getSubCategories');

                $config['functions']['getCountyIdByURLName'] = array('function' => 'Category_list_server.getCountyIdByURLName');

		$config['functions']['sgetParentCategories'] = array('function' => 'Category_list_server.sgetParentCategories');

		$config['functions']['getSubCategoryIdByURLName'] = array('function' => 'Category_list_server.getSubCategoryIdByURLName');

		$config['functions']['getCityListS'] = array('function' => 'Category_list_server.getCityListS');
		$config['functions']['getNewCityList'] = array('function' => 'Category_list_server.getNewCityList');

		$config['functions']['getParentCatsOfLeafCats'] = array('function' => 'Category_list_server.getParentCatsOfLeafCats');

		$config['functions']['getCategoryBasedOnTier'] = array('function' => 'Category_list_server.getCategoryBasedOnTier');
		$config['functions']['getCityBasedOnTier'] = array('function' => 'Category_list_server.getCityBasedOnTier');	
		$config['functions']['getstateBasedontier'] = array('function' => 'Category_list_server.getstateBasedontier');
		$config['functions']['getSubcategoryBasedontier'] = array('function' => 'Category_list_server.getSubcategoryBasedontier');
		$config['functions']['getCountrydependOnTier'] = array('function' => 'Category_list_server.getCountrydependOnTier');

		$config['functions']['getListingsCategoriesInTier'] = array('function' => 'Category_list_server.getListingsCategoriesInTier');
		$config['functions']['getListingsCitiesInTier'] = array('function' => 'Category_list_server.getListingsCitiesInTier');
		$config['functions']['getCountriesInTier'] = array('function' => 'Category_list_server.getCountriesInTier');
		$config['functions']['getListingsCountriesInTier'] = array('function' => 'Category_list_server.getListingsCountriesInTier');
		$config['functions']['sgetCategoryDetailsById'] = array('function' => 'Category_list_server.sgetCategoryDetailsById');
		$config['functions']['getSubToParentCategoryMapping'] = array('function' => 'Category_list_server.getSubToParentCategoryMapping');

		$config['functions']['sGetDetailsForCityId'] = array('function' => 'Category_list_server.sGetDetailsForCityId');
		$config['functions']['sgetLocalitiesForCityId'] = array('function' => 'Category_list_server.sgetLocalitiesForCityId');

		$config['functions']['sgetAllLocalities'] = array('function' => 'Category_list_server.sgetAllLocalities');
		$config['functions']['sgetSpecializationForCategoryId'] = array('function' => 'Category_list_server.sgetSpecializationForCategoryId');
		$config['functions']['sgetCourseSpecializationForCategoryIdGroups'] = array('function' => 'Category_list_server.sgetCourseSpecializationForCategoryIdGroups');
		$config['functions']['sgetLocalitiesForZoneId'] = array('function' => 'Category_list_server.sgetLocalitiesForZoneId');
		$config['functions']['sgetZonesForCityId'] = array('function' => 'Category_list_server.sgetZonesForCityId');
		$config['functions']['sgetCityGroupInSameVirtualCity'] = array('function' => 'Category_list_server.sgetCityGroupInSameVirtualCity');
		$config['functions']['sgetCitiesForVirtualCity'] = array('function' => 'Category_list_server.sgetCitiesForVirtualCity');
		$config['functions']['getZonewiseLocalitiesForCityId'] = array('function' => 'Category_list_server.getZonewiseLocalitiesForCityId');
		$config['functions']['sgetCountriesWithRegions'] = array('function' => 'Category_list_server.sgetCountriesWithRegions');
		$config['functions']['getInstituteTypes'] = array('function' => 'Category_list_server.getInstituteTypes');
		$config['functions']['wsgetTestPrepCoursesList'] = array('function' => 'Category_list_server.wsgetTestPrepCoursesList');
		$config['functions']['wsgetblogNameCsvFromBlogIdCsv'] = array('function' => 'Category_list_server.wsgetblogNameCsvFromBlogIdCsv');
		$config['functions']['getCategoryCourses'] = array('function' => 'Category_list_server.getCategoryCourses');
		$config['functions']['getInstituteForTabs'] = array('function' => 'Category_list_server.getInstituteForTabs');
		
		
		$config['functions']['getShikshaCourseCategories'] = array('function' => 'Category_list_server.getShikshaCourseCategories');
		$config['functions']['getShikshaCourses'] = array('function' => 'Category_list_server.getShikshaCourses');
		$config['functions']['getShikshaMappedCourses'] = array('function' => 'Category_list_server.getShikshaMappedCourses');
		
		$config['functions']['getPopularCourses'] = array('function' => 'Category_list_server.getPopularCourses');
		$config['functions']['getSubCategoryCourses'] = array('function' => 'Category_list_server.getSubCategoryCourses');
		$config['functions']['setPopularCourses'] = array('function' => 'Category_list_server.setPopularCourses');
		$config['functions']['getCatSubcatList'] = array('function' => 'Category_list_server.getCatSubcatList');
		$config['functions']['getLdbMappedCourses'] = array('function' => 'Category_list_server.getLdbMappedCourses');
		$config['functions']['getCategoryPageHeaderText'] = array('function' => 'Category_list_server.getCategoryPageHeaderText');
		$config['functions']['setCategoryPageHeaderText'] = array('function' => 'Category_list_server.setCategoryPageHeaderText');
                $config['functions']['removeCategorypageWidgetsDataInfo'] = array('function' => 'Category_list_server.removeCategorypageWidgetsDataInfo');
        $config['functions']['setCategorypageWidgetsDataInfo'] = array('function' => 'Category_list_server.setCategorypageWidgetsDataInfo');
        $config['functions']['getSAWidgetArticles'] = array('function' => 'Category_list_server.getSAWidgetArticles');
        $config['functions']['saveSAWidgetContent'] = array('function' => 'Category_list_server.saveSAWidgetContent');
        $config['functions']['getCategorypageWidgetsDataInfo'] = array('function' => 'Category_list_server.getCategorypageWidgetsDataInfo');
        $config['functions']['getTabsContentByCategory'] = array('function' => 'Category_list_server.getTabsContentByCategory');
        $config['functions']['getLocalitiesByCity'] = array('function' => 'Category_list_server.getLocalitiesByCity');
        $config['functions']['getAllCitiesHavingLocalities'] = array('function' => 'Category_list_server.getAllCitiesHavingLocalities');
				

		//initialize
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}


	function sgetCategoryCourseList($request){
		$parameters = $request->output_parameters();
		$categoryId = $parameters['0'];
		if($categoryId!=0){
			$queryCmd = "SELECT tcsm.*, cgsm.*,cbt.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm,
				tCourseSpecializationMapping tcsm, categoryBoardTable cbt WHERE  tcsm.SpecializationId = csgm.courseSpecializationId
				AND csgm.groupId = cgsm.groupId AND cbt.boardId = tcsm.categoryId AND cgsm.status = 'live' and tcsm.Status='live'
				and tcsm.scope= 'india' AND cbt.boardId = ? ORDER BY cgsm.groupId";

			$query = $this->db->query($queryCmd, array($categoryId));
		}else{
		    	$queryCmd = "SELECT tcsm.*, cgsm.*,cbt.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm,
				tCourseSpecializationMapping tcsm, categoryBoardTable cbt WHERE  tcsm.SpecializationId = csgm.courseSpecializationId
				AND csgm.groupId = cgsm.groupId AND cbt.boardId = tcsm.categoryId AND cgsm.status = 'live' and tcsm.Status='live'
				and tcsm.scope= 'india' ORDER BY cgsm.groupId";

			$query = $this->db->query($queryCmd);
		}

		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
			array(
					'courseName'=>array($row->CourseName,'string'),
	                'courseId'=>array($row->SpecializationId,'string'),
					'categoryId'=>array($row->categoryId,'string'),
					'groupName'=>array($row->groupName,'string'),
					'categoryName'=>array($row->name,'string')
			),'struct'));//close array_push

		}

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	function getShikshaCourseCategories($request)
	{
		$parameters = $request->output_parameters();
		
		$this->load->model('categorylistmodel');
		
		$categories = $this->categorylistmodel->getShikshaCourseCategories();
		
		$msgArray = array();
	
		foreach ($categories as $category)
		{
			array_push($msgArray,array(
										array(
												'categoryId'   => array($category->id,'string'),
												'categoryName' => array($category->name,'string')
											 ),
											 'struct'
									  )
					   );

		}
		
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
		
	function getShikshaCourses($request)
	{
		$parameters = $request->output_parameters();
		$category_id = $parameters['0'];
		
		$this->load->model('categorylistmodel');
		
		$shiksha_courses = $this->categorylistmodel->getShikshaCourses($category_id);
		
		$msgArray = array();
	
		foreach ($shiksha_courses as $shiksha_course)
		{
			array_push($msgArray,array(
										array(
												'courseId'   => array($shiksha_course->shiksha_course_id,'string'),
												'courseName' => array($shiksha_course->shiksha_course_title,'string')
											 ),
											 'struct'
									  )
					   );
		}
		
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
		
	function getShikshaMappedCourses($request)
	{
		$parameters = $request->output_parameters();
		$course_id = $parameters['0'];
		
		$this->load->model('categorylistmodel');
		
		$shiksha_mapped_courses = $this->categorylistmodel->getShikshaMappedCourses($course_id);
		
		$msgArray = array();
	
		foreach ($shiksha_mapped_courses as $shiksha_mapped_course)
		{
			$all_shiksha_courses_in_category = $this->categorylistmodel->getShikshaCourses($shiksha_mapped_course->category_id);
			$shiksha_courses_in_category = array();
			foreach($all_shiksha_courses_in_category as $shiksha_course)
			{
				$shiksha_courses_in_category[$shiksha_course->shiksha_course_id] = $shiksha_course->shiksha_course_title;
			}
			
			array_push($msgArray,array(
										array(
												'courseId'   => array($shiksha_mapped_course->shiksha_course_id,'string'),
												'categoryId' => array($shiksha_mapped_course->category_id,'string'),
												'shikshaCourses' => array($shiksha_courses_in_category,'struct'),
											 ),
											 'struct'
									  )
					   );

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

    function get_category_name($request) {
        $params = $request->output_parameters();
        $category_id = $params[0];

        $query_cmd = "select name from categoryBoardTable where boardId=?";
        $query = $this->db->query($query_cmd, array($category_id));
        $results = $query->result_array();
        $name = $results[0]['name'];
        $response = array($name, 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getCityId($request)
    {
        $params = $request->output_parameters();
        $cityName = $params[0];
        $query = $this->db->query("select city_id from countryCityTable where city_name = ?", array($cityName));
        $results = $query->result_array();
        $response = array($results[0]['city_id'], 'int');
        return $this->xmlrpc->send_response($response);
    }

    function getCityName($request)
    {
        $params = $request->output_parameters();
        $cityId = $params[0];
        $query = $this->db->query("select city_name from countryCityTable where city_id = ?", array($cityId));
        $results = $query->result_array();
        $response = array($results[0]['city_name'], 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getBlogId($request)
    {
        $params = $request->output_parameters();
        $blogAcronym = $params[0];
        $query = $this->db->query("select blogId from blogTable where acronym = ? and blogType = 'exam' and status='live'", array($blogAcronym));
        $results = $query->result_array();
        $response = array($results[0]['blogId'], 'int');
        return $this->xmlrpc->send_response($response);
    }

    function getBlogAcronym($request)
    {
        $params = $request->output_parameters();
        $blogId = $params[0];
        $query = $this->db->query("select acronym from blogTable where blogId = ? ", array($blogId));
        $results = $query->result_array();
        $response = array($results[0]['acronym'], 'string');
        return $this->xmlrpc->send_response($response);
    }
	
	function getBlogParent($request)
    {
        $params = $request->output_parameters();
        $blogId = $params[0];
        $query = $this->db->query("select parentId from blogTable where blogId = ? ", array($blogId));
        $results = $query->result_array();
        $response = array($results[0]['parentId'], 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getBlogTitle($request)
    {
        $params = $request->output_parameters();
        $blogId = $params[0];
        $query = $this->db->query("select blogTitle from blogTable where blogId = ? ", array($blogId));
        $results = $query->result_array();
        $response = array($results[0]['blogTitle'], 'string');
        return $this->xmlrpc->send_response($response);
    }

    function get_testprep_menu_tree()
    {
        $query = $this->db->query("select blogId, acronym,blogTitle from blogTable b where b.parentId=0 and b.blogType='exam' and status='live' order by blogTitle");
        $results = $query->result_array();
        $blog_categories = array();
        foreach($results as $result)
        {
            $blog_id = $result['blogId'];
            $query = $this->db->query("select blogId, acronym,blogTitle from blogTable b where b.parentId=$blog_id and b.blogType='exam' and status='live' order by blogTitle");
            $results1 = $query->result_array();
            $cxarr = array();
            foreach($results1 as $result1)
            {
                $xarr = array(
                    array('blogId' => array($result1['blogId'], 'string'),
                    'acronym' => array($result1['acronym'], 'string'),
                    'blogTitle' => array($result1['blogTitle'], 'string'))
                    , 'struct');
                array_push($cxarr, $xarr);
            }
            $result['children'] = array($cxarr, 'array');
            $blog_categories[$blog_id] = array($result,'struct');
        }
        return $this->xmlrpc->send_response(array($blog_categories,'struct'));
    }

    function getCountriesInTier($request){

        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $tier=$parameters['1'];

        $queryCmd = 'select name,countryId from countryTable';
        log_message('debug', 'getCountryTable query cmd is ' . $queryCmd);

        $query = $this->db->query($queryCmd);
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'countryName'=>array($row->name,'string'),
                            'countryId'=>array($row->countryId,'string')
                            ),'struct'));//close array_push

        }
        $response = array($msgArray,'struct');

        return $this->xmlrpc->send_response($response);
    }

    function getListingsCountriesInTier($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $tier=$parameters['1'];
        $type=$parameters['2'];
        $typeId=$parameters['3'];


        switch($type){
            case 'course':
                $queryCmd = "select countryId, countryTable.name as countryName ".
                			"from countryTable where countryId in ".
                			"(select country_id from institute_location_table ilt, ".
                			"course_location_attribute cla where cla.course_id = ? ".
                			"AND cla.status = 'live' AND cla.institute_location_id = ilt.institute_location_id AND ".
                			"ilt.status = 'live' AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE')";

				$query = $this->db->query($queryCmd, array($typeId));
                break;
            case 'university':
                $queryCmd = "select cta.countryId,cta.name as countryName from ".ENT_SA_COUNTRY_TABLE_NAME." cta,university_location_table ult "
                                        ."where cta.countryId=ult.country_id and ult.status='".ENT_SA_PRE_LIVE_STATUS."' and ult.university_id = ?";

                $query = $this->db->query($queryCmd, array($typeId));
                break;
            case 'institute':
            default:
                $queryCmd = "select countryId, countryTable.name as countryName from countryTable where  countryId in ".
                			"(select country_id from institute_location_table where institute_location_table.status='live' ".
                			 "and institute_id = ? ) ";

				$query = $this->db->query($queryCmd, array($typeId));
                break;
        }
        // error_log('getCityBasedOnTier query cmd is ' . $queryCmd);

        
        $msgArray = array();
        foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'countryName'=>array($row->countryName,'string'),
						'countryId'=>array($row->countryId,'string')
					),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }
    function getListingsCategoriesInTier($request){        
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $tier=$parameters['1'];
        $type=$parameters['2'];
        $typeId=$parameters['3'];
        if($type == 'university'){
            $this->load->model('listingPosting/abroadcmsmodel');
            $abroadcmsmodel = new abroadcmsmodel();
            $resultArray = $abroadcmsmodel->getListingDetails($typeId,$type);
            $institutesArr = $resultArray[$typeId]['institutes'];
            $categories = "";
            foreach($institutesArr as $institute){
                $categories .= ",".substr($institute['categoryId'],1);
            }
            $categories = substr($categories,1);
            if($categories == ''){
                return $this->xmlrpc->send_response(array());
            }
        }

        switch($type){
            case 'course':
                $queryCmd = "select boardId, name from categoryBoardTable where boardId in (select parentId from categoryBoardTable, listing_category_table where category_id =  boardId and listing_type = 'course' and listing_type_id = ?) and enabled=0 and parentId = 1 and tier=?";

                $query = $this->db->query($queryCmd, array($typeId, $tier));
                break;
            case 'university':
                $queryCmd = "select boardId, name from categoryBoardTable where boardId in (".$categories.") and enabled=0 and parentId = 1 and tier = ?";

                $query = $this->db->query($queryCmd, array($tier));
                break;
            case 'institute':
            default:
                $queryCmd = "select boardId, name from categoryBoardTable where boardId in (select parentId from categoryBoardTable, listing_category_table where category_id =  boardId and listing_type = 'institute' and listing_type_id = ?) and enabled=0 and parentId = 1 and tier=?";

                $query = $this->db->query($queryCmd, array($typeId, $tier));
                break;
        }
        // error_log('getCategoryBasedOnTier query cmd is ' . $queryCmd);

        
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                array(
                    'categoryName'=>array($row->name,'string'),
                    'categoryId'=>array($row->boardId,'string')
                ),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
        
    }

    function getListingsCitiesInTier($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $tier=$parameters['1'];
		$countryId=$parameters['2'];
        $type=$parameters['3'];
        $typeId=$parameters['4'];

        if($countryId == 1 || $countryId =='') {
            $addCountryWhere = '';
        }
        else {
            $addCountryWhere = " and countryTable.countryId in (?) ";
        }

        $queryCmd = "select countryCityTable.countryId, countryTable.name, countryCityTable.city_name, countryCityTable.city_id from countryCityTable, countryTable where countryTable.countryId = countryCityTable.countryId and countryCityTable.tier=$tier $addCountryWhere ";

        switch($type){
            case 'course':
                $queryCmd = "select countryCityTable.countryId, countryTable.name, countryCityTable.city_name, ".
                			"countryCityTable.city_id from countryCityTable, countryTable, virtualCityMapping ".
                			"where countryTable.countryId = countryCityTable.countryId and countryCityTable.tier=? $addCountryWhere ".
                			"and virtualCityMapping.virtualCityId =  countryCityTable.city_id and virtualCityMapping.city_id in ".
                			"(select city_id from institute_location_table ilt, course_location_attribute cla ".
                			"where cla.course_id = ? AND cla.status = 'live' AND cla.institute_location_id = ilt.institute_location_id AND ".
                			"ilt.status = 'live' AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE') ";
                break;
            case 'institute':
            default:
                $queryCmd = "select countryCityTable.countryId, countryTable.name, countryCityTable.city_name, countryCityTable.city_id from countryCityTable, countryTable, virtualCityMapping where countryTable.countryId = countryCityTable.countryId and countryCityTable.tier=? $addCountryWhere and virtualCityMapping.virtualCityId =  countryCityTable.city_id and virtualCityMapping.city_id in (select city_id from institute_location_table where institute_location_table.status='live' and institute_id =?) ";
                break;
        }
        $params = array($tier, $countryId, $typeId);

        error_log('getCityBasedOnTier query cmd is ' . $queryCmd);

        $query = $this->db->query($queryCmd, $params);
        $msgArray = array();
        foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'cityName'=>array($row->city_name,'string'),
						'cityId'=>array($row->city_id,'string'),
						'countryName'=>array($row->name,'string'),
						'countryId'=>array($row->countryId,'string')
					),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }



	function getSubcategoryBasedontier($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$tier=$parameters['1'];
		$type=$parameters['2'];
		$typeId=$parameters['3'];
	
		switch($type){
		    case 'course':
			 $queryCmd = "select boardId, name from categoryBoardTable where boardId in (select boardId from categoryBoardTable, listing_category_table where category_id =  boardId and listing_type = 'course' and listing_type_id = ?) and enabled=0 and tier=?";

			 $query = $this->db->query($queryCmd, array($typeId, $tier));

			break;
		    case 'institute':
		    default:
			 $queryCmd = "select boardId, name from categoryBoardTable where boardId in (select boardId from categoryBoardTable, listing_category_table where category_id =  boardId and listing_type = 'institute' and listing_type_id = ?) and enabled=0 and tier=?";

			 $query = $this->db->query($queryCmd, array($typeId, $tier));
			break;
		}
		//error_log("query for subcategory".$queryCmd);
		

		$msgArray = array();
		foreach ($query->result() as $row){
		    array_push($msgArray,array(
			array(
			    'SubcategoryName'=>array($row->name,'string'),
			    'SubcategoryId'=>array($row->boardId,'string')
			),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
		
		
	}
	


	function getstateBasedontier($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$tier=$parameters['1'];
		$type=$parameters['2'];
		$typeId=$parameters['3'];
	
		switch($type){
		    case 'course':
			 $queryCmd = "select S.state_name,S.state_id from stateTable as S, ".
			 			 "countryCityTable as CC where CC.state_id = S.`state_id` and ".
			 			 "CC.city_id in (select city_id from institute_location_table ilt, ".
			 			 "course_location_attribute cla where cla.course_id = ? AND ".
			 			 "cla.status = 'live' AND cla.institute_location_id = ilt.institute_location_id AND ".
			 			 "ilt.status = 'live' AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE' ) and S.`tier` = ?";

			$query = $this->db->query($queryCmd, array($typeId, $tier));
			break;
		    case 'institute':
		    default:
			 $queryCmd = "select S.state_name,S.state_id from stateTable as S, countryCityTable as CC where CC.state_id = S.`state_id` and CC.city_id in (select city_id from institute_location_table where institute_location_table.status='live' and institute_id =?) and S.`tier` = ?";

			 $query = $this->db->query($queryCmd, array($typeId, $tier));
			 break;
		}
			
		

		$msgArray = array();
		foreach ($query->result() as $row){
		    array_push($msgArray,array(
			array(
			    'StateName'=>array($row->state_name,'string'),
			    'StateId'=>array($row->state_id,'string')
			),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	 	
	}



	function getCountrydependOnTier($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $tier=$parameters['1'];
        $type=$parameters['2'];
        $typeId=$parameters['3'];


        switch($type){
            case 'course':
                $queryCmd = "select countryId, countryTable.name as countryName ".
                			"from countryTable where countryId in (select country_id from ".
                			"institute_location_table ilt, course_location_attribute cla where ".
                			"cla.course_id = ? AND cla.status = 'live' AND ".
                			"cla.institute_location_id = ilt.institute_location_id AND ilt.status = 'live' ".
                			"AND cla.attribute_type = 'Head Office' AND cla.attribute_value = 'TRUE' ) and countryTable.tier = ? ";

				$query = $this->db->query($queryCmd, array($typeId, $tier));
                break;
            case 'university':
                $queryCmd = "select cta.countryId,cta.name as countryName from ".ENT_SA_COUNTRY_TABLE_NAME." cta,university_location_table ult "
                                        ."where cta.countryId=ult.country_id and ult.status='".ENT_SA_PRE_LIVE_STATUS."' and cta.tier= ? and ult.university_id= ? ";

                $query = $this->db->query($queryCmd, array($tier, $typeId));
                break;
            case 'institute':
            default:
                $queryCmd = "select countryId, countryTable.name as countryName from countryTable where  countryId in (select country_id from institute_location_table where institute_location_table.status='live' and institute_id = ?) and countryTable.tier = ?";

                $query = $this->db->query($queryCmd, array($typeId, $tier));
                break;
        }
        // error_log('getCountriesBasedOnTier query cmd is ' . $queryCmd);

        
        $msgArray = array();
        foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'countryName'=>array($row->countryName,'string'),
						'countryId'=>array($row->countryId,'string')
					),'struct'));//close array_push
        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

	/*
	*	Creates storage tables for a specified application ID
	*/
	function createStorageTables($appID){

		//connect DB
		//XXX to be done later
	}


	/*
	* 	method to return Category ID for a given URL Name
	*/


	/*
	* 	method to return Category ID for a given URL Name
	*/
	function getCategoryIdByURLName($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$urlName=$parameters['1'];

		$this->db->select('boardId')->from('categoryBoardTable')->where(array('urlName' => $urlName,'parentId'=>1));
		$query = $this->db->get();
		$msgArray = array();

		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}

		$response = array($row['boardId']);
		return $this->xmlrpc->send_response($response);
	}

	/*
	* 	method to return Category ID for a given URL Name
	*/
	function getCategoryIdByName($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$parentId=$parameters['1'];
		$subcategoryName=$parameters['2'];
		//connect DB
		$this->db->select('boardId')->from('categoryBoardTable')->where(array('name'=>$subcategoryName,'parentId'=>$parentId));
		$query = $this->db->get();
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}

		$response = array($row['boardId']);
		return $this->xmlrpc->send_response($response);
	}

	/*
	* 	method to return sub Category ID for a given parentID
	*/
	function getSubCategories($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$parentId=$parameters['1'];
                $flag = $parameters['2'];
                
		//connect DB
		$this->db->select(array('boardId','name','urlName'))->from('categoryBoardTable')->where(array('parentId'=>$parentId, 'flag' =>$flag))->order_by("name","asc"); ;
		$query = $this->db->get();                
		$msgArray = array();
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	* 	method to return country ID by URL Name
	*/
	function getCountyIdByURLName($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$urlName=$parameters['1'];
		//connect DB
		$this->db->select('countryId')->from('countryTable')->where(array('urlName'=>$urlName));
		$query = $this->db->get();
		$msgArray = array();

		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


	/*
	* 	Method to insert a category $appID,$categoryName,$parentID,$userID
	*/
	function insertCategory($request){
		//insert into categoryBoardTable (name,parentId,userId) values('Banking/Finance/Accounting',0,1);
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$name=$parameters['1'];
		$parentId=$parameters['2'];
		$userId=$parameters['3'];
		$this->db = $this->dbLibObj->getWriteHandle();
		$dataArr = array( "name" => $name,
						  "parentId" => $parentId,
						  "userId" => $userId);

		$this->db->insert('categoryBoardTable', $dataArr);

		// FIXED: DB Handle problem fixed
		$msgId=$this->db->insert_id();
		$response = array(
					array(
						'categoryID'=>array($msgId,'string')),
					'struct');
		return $this->xmlrpc->send_response($response);

	}


	/*
	*	enable/disable a given category in category table 0 is enable and 1 is disabled $appID,$category_id,$enable
	*/
	function enableCategory($request){
		//update categoryBoardTable set enabled=1 where boardId=9;
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$enableFlag=$parameters['1'];
		$boardId=$parameters['2'];
		$this->db = $this->dbLibObj->getWriteHandle();
		$queryCmd = 'update categoryBoardTable set enabled= ? where boardId= ? ';

		log_message('debug', 'enableCategory query cmd is ' . $queryCmd);

		$query = $this->db->query($queryCmd, array($enableFlag, $boardId));
		$msgId=$this->db->insert_id();
		$response = array(
					array(
						'categoryID'=>array($msgId,'string')),
					'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*	Get the category Tree list
	*/
	
	function getCategoryTree($request)
	{
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
		$orderBy = $parameters['1'];
		$flag = $parameters['2'];
	
		$queryCmd = 'SELECT name, boardId , parentId, urlName, flag FROM categoryBoardTable ';
		
		if($flag)
		{			
			switch($flag) {

                            case 'testprep' :
                                /*
                                 * In case of test prep, send only test prep main category and all its subcategories
                                */
                                $queryCmd .= 'WHERE flag = "testprep" OR parentId IN (SELECT boardId FROM categoryBoardTable WHERE flag = "testprep")';
                                break;
                            
                            case 'studyabroad' :
                                /*
                                 *  Lets return Old Abroad Subcategotries and their National Main Categories..
                                 */
                                $queryCmd .= 'WHERE (flag = "studyabroad" AND isOldCategory = "1") OR (`parentId` =1 AND `flag` IN ("national", "testprep"))';                                
                                break;
                            
                            case 'newStudyAbroad' :
                                /*
                                 *  Lets return New Abroad Subcategotries with their Main Categories..
                                 */
                                $queryCmd .= 'WHERE flag = "studyabroad" AND isOldCategory = "0"';
                                break;
                            
                            default :
                                /*
                                 * By default return all the Categories of the passed $flag var, National Categories / National Categories.
                                 */
                                $queryCmd .= 'WHERE (flag = "testprep" OR flag = "national")';
                                break;                            
                        }
		} else {
                    $queryCmd = 'SELECT name, boardId , parentId, urlName, flag FROM categoryBoardTable where flag IS NULL OR ! ( `flag` = "studyabroad" AND `isOldCategory` = "0" )';
                }
		
                if($orderBy)
                        {
                    $queryCmd .= ' ORDER BY parentId,priority,name';
                        }
                        else
                        {
                    $queryCmd .= ' ORDER BY priority,name';
                }
		
                // error_log("AMIT_QUERY = ".$queryCmd);
                
		// log_message('debug', 'getCategoryTree query cmd is ' . $queryCmd);
		
		$query = $this->db->query($queryCmd);
		$msgArray = array();
		
		foreach ($query->result() as $row)
		{
			$msgArray[] = array('categoryName'=>$row->name,'categoryID'=>$row->boardId,'urlName'=>$row->urlName,'parentId'=>$row->parentId,'flag'=>$row->flag);
		}

		$responseString = base64_encode(gzcompress(json_encode($msgArray)));
		$response = array($responseString,'string');

		return $this->xmlrpc->send_response($response);
	}
	
	function getCategoryCourses($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
        $queryCmd = 'select course_key_id,course_title,	category_id,snippet_type,show_subcategory,show_level,show_mode,`course_page_heading` AS heading,page_url from category_course_list where status = "active" order by course_key_id';
		log_message('debug', 'getCategoryCourses query cmd is ' . $queryCmd);
		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'course_key_id'=>array($row->course_key_id,'string'),
						'course_title'=>array($row->course_title,'string'),
						'category_id'=>array($row->category_id,'string'),
						'snippet_type'=>array($row->snippet_type,'string'),
						'search_combination'=>array($row->search_combination,'string'),
						'show_subcategory'=>array($row->show_subcategory,'string'),
						'show_level'=>array($row->show_level,'string'),
						'show_mode'=>array($row->show_mode,'string'),
						'course_page_heading'=>array($row->heading,'string'),
						'url'=>array($row->page_url,'string')
					),'struct'));//close array_push

        }
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}
	function getInstituteForTabs($request)
	{
	$queryCmd = 'select institute_id,position,logo_url,type,courseId from  popular_institutes where STATUS="live" order by position';
                error_log('popquery'.$queryCmd);
                error_log("check if here..popular_institutes being used.", 3, '/tmp/log_unused_table_'.date('y-m-d'));
		$query = $this->db->query($queryCmd);
                $institutes_array = array();
                $institutes_array_logos =array();
                $institutes_array_positions = array();
                $institutes_array_types = array();
                $institutes_courses = array();
                $msgArray = array();
		$all_courses = array();	
		$courses_results = array();	
                $i=0;

                foreach($query->result() as $row) {
			$institutes_array[] = $row->institute_id;  
                        $institutes_array_logos[$row->institute_id][$i] = $row->logo_url; 
                        $institutes_array_positions[$row->institute_id][$i] = $row->position; 
                        $institutes_array_types[$row->institute_id][$i] = $row->type; 
			$institutes_courses[$row->institute_id][$i] = $row->courseId;	

			if(!empty($row->courseId)) {
				$all_courses[] = $row->courseId;
			}
                        $i++;
                }

                if(count($institutes_array) >0) {
   				$this->load->builder("nationalInstitute/InstituteBuilder");
	    		$instituteBuilder = new InstituteBuilder();
	    		$instituteRepository = $instituteBuilder->getInstituteRepository();
    			$this->load->builder("nationalCourse/CourseBuilder");
				$courseBuilder = new CourseBuilder();
				$course_repository = $courseBuilder->getCourseRepository();
       
                $institutes_results = $instituteRepository->findMultiple($institutes_array);
		
		if(count($all_courses)>0) {
			$courses_results = $course_repository->findMultiple($all_courses);
		}
                $j=0;

		foreach ($institutes_array as $inst_id){
                        $institute = $institutes_results[$inst_id];
			$institute_id = $institute->getId();
			$institute_name = html_escape($institute->getName());
			$logo = $institutes_array_logos[$institute_id][$j];
			$course_type = $institutes_array_types[$institute_id][$j];
			$position = $institutes_array_positions[$institute_id][$j];
			if(!empty($institutes_courses[$institute_id][$j])) {
				$course_object = $courses_results[$institutes_courses[$institute_id][$j]];
				$detailurl  = $course_object->getURL();
			} else {
				$detailurl  = $institute->getURL();
			}
			array_push($msgArray,array(
				array(
                        'instituteName'=>array($institute_name,'string'),
                        'instituteId'=>array($institute_id,'string'),
                        'detailurl'=>array($detailurl,'string'),
				        'course_type'=>array($course_type,'string'),
				        'position'=>array($position,'string'),
			 'course_id'=>array($institutes_courses[$institute_id][$j],'string')
				),'struct')
			);//close array_push
			$j++;
		}

                }

                if(count($msgArray) == 0) {
			$msgArray = array('NO_DATA_FOUND');
		}

		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	function htmlButTags($str) {
        // Take all the html entities
        $caracteres = get_html_translation_table(HTML_ENTITIES);
        // Find out the "tags" entities
        $remover = get_html_translation_table(HTML_SPECIALCHARS);
        // Spit out the tags entities from the original table
        $caracteres = array_diff($caracteres, $remover);
        // Translate the string....
        $str = strtr($str, $caracteres);
        // And that's it!
        // oo now amps
        $str = preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,3};)/","&amp;" , $str);

        return $str;
    }

	/**
	 *
	 * Returns the zone-localuty mapping
	 * @return array
	 */
	function getZones($request){
		$queryCmd = 'SELECT tz.zoneId,tz.zoneName,lcm.localityId,lcm.localityName,lcm.cityId FROM localityCityMapping lcm JOIN tZoneMapping tz ON
					 lcm.zoneId = tz.zoneID WHERE lcm.status = 1';
//		error_log('getZones query cmd is ' . $queryCmd);

		$query = $this->db->query($queryCmd);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'localityId'=>array($row->localityId),
						'localityName'=>array($row->localityName),
						'cityId'=>array($row->cityId),
						'zoneId'=>array($row->zoneId),
						'zoneName'=>array($row->zoneName)
					)));//close array_push
		}
		$response = array(base64_encode(json_encode($msgArray)), 'string');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Get the country tree
	*/
	function getCountries($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];

		$queryCmd = 'select name,countryId,urlName from countryTable WHERE showOnRegistration = "YES"';
		log_message('debug', 'getCountryTable query cmd is ' . $queryCmd);

		$query = $this->db->query($queryCmd);
		$msgArray = array();
		//Modified for Shiksha performance task on 8 March
		/*foreach ($query->result() as $row){
			array_push($msgArray,array(
				array(
						'countryName'=>array($row->name,'string'),
						'countryID'=>array($row->countryId,'string'),
						'urlName'=>array($row->urlName,'string')
					),'struct'));//close array_push
		}
		$response = array($msgArray,'struct');*/
		$i=0;
		foreach ($query->result() as $row){
		      $msgArray[$i] = array('countryName'=>$row->name,'countryID'=>$row->countryId,'urlName'=>$row->urlName);
		      $i++;
		}
		$responseString = base64_encode(gzcompress(json_encode($msgArray)));
		$response = array($responseString,'string');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Get the category list for a given parent category
	*/
	function getCategoryList($request){
                //error_log('theeeeeeeeeeeeeee');
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$category_id=$parameters['1'];
                $flag = $parameters['2'];
                //error_log('theeeeeeeeeeeeeee1');
		$queryCmd = 'select * from categoryBoardTable where enabled=0 and parentId= ? ';
                $and_query = "";
                if($flag == 'testprep') {
			$and_query = " AND flag = 'testprep'"; 			
                } else if($flag == 'studyabroad') {
			$and_query = " AND ((flag = 'studyabroad' AND isOldCategory = '1') OR  (`parentId` =1 AND `flag` IN ('national', 'testprep')))";
                } else if($flag == 'newStudyAbroad'){
			$and_query = " AND flag = 'studyabroad' AND isOldCategory = '0'";
                } else {
                        $and_query = " AND (flag = 'testprep' OR flag = 'national')";
                }                  
                
                $queryCmd = $queryCmd.$and_query;
		log_message('debug', 'getCategoryList query cmd is ' . $queryCmd);
                error_log('theeeeeeeeeeeeeee'.$queryCmd);
		$query = $this->db->query($queryCmd, array($category_id));
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'categoryName'=>array($row->name,'string'),
						'topicCount'=>array('50','string'),
						'categoryID'=>array($row->boardId,'string')
					),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);
	}

	/*
	*	update feeds to alert
	*/
	function getCategoryFeeds($request){
		$parameters = $request->output_parameters();
		$appID=1;
		$startDate=$parameters['0'];
		$endDate=$parameters['1'];

		$queryCmd = $this->db->get('categoryBoardTable');
		$msgArray = array();
		foreach($queryCmd->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);

	}

	/*
	*	Get Category feeds For Home Page
	* 	return array of category with display name, url name and child ID's
	*/
	function getCategoryFeedsForHomePage($request){
		$parameters = $request->output_parameters();
		$appID=1;

		$queryCmd = $this->db->get('categoryBoardTable');
		$msgArray = array();
		foreach($queryCmd->result_array() as $row){
			//get child
			$queryCmdForChild = 'select boardId from categoryBoardTable where parentId=?';
			log_message('debug', 'getCategoryFeedsForHomePage Child query cmd is ' . $queryCmd);

			$queryChild = $this->db->query($queryCmdForChild, array($row['boardId']));
			$childIdArray = array();
			foreach ($queryChild->result_array() as $rowChild){
				array_push($childIdArray,array_values($rowChild));
			}

			//update it in row
			$row['childId'] = array($childIdArray,'array');

			array_push($msgArray,array(
					array(
						$row['boardId']=>array($row,'struct')
					),'struct'));//close array_push

		}

		$response = array($msgArray,'struct');

		return $this->xmlrpc->send_response($response);

             }


	function getSubCategoryIdByURLName($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$parentId=$parameters['1'];
		$subcategoryName=$parameters['2'];
                // Updated by Amit Kuksal on 24th Feb 2011 for Category Page revamp || Paasing the urlName information as an array if required..
                if(is_array($subcategoryName)) {
                    $subcategoryNameStr = implode("','", $subcategoryName);                
                    $where = "urlName in ('".$subcategoryNameStr."') AND parentId='".$parentId."'";
                } else
                    $where = array('urlName'=>$subcategoryName,'parentId'=>$parentId);

		//connect DB
		$this->db->select('boardId')->from('categoryBoardTable')->where($where);
		$query = $this->db->get();
		$msgArray = array(); $subcategoryNameStr = ""; $countFlag = 0;
		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
                        $subcategoryNameStr .= ($countFlag++ == 0 ? "" : "," ).$row['boardId']; // By Amit Kuksal on 24th Feb 2011 | If more results returned by the query.
		}

		$response = array($subcategoryNameStr);
		return $this->xmlrpc->send_response($response);
	}

	function getCityListS($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
        $cityFlag = $parameters['1'];
        switch($cityFlag) {
            case 'restrict':
                $whereString = '((countryId =2 and state_id !=-1) or (countryId!=2)) and city_name !=""';
		        $this->db->select('countryId, city_id, city_name')->from('countryCityTable')->where($whereString)->orderby('countryId','city_name');
                break;
            default:
		        $this->db->select('countryId, city_id, city_name')->from('countryCityTable')->orderby('countryId','city_name');
        }
		$query = $this->db->get();
		$msgArray = array();
		foreach ($query->result_array() as $row){
                        array_push($msgArray,array($row,'struct'));
                }
		$response = array($msgArray,'struct');
		//error_log_shiksha('getCityListS::'.print_r($response, true));
		return $this->xmlrpc->send_response($response);
	}

	function getNewCityList($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
        $cityFlag = $parameters['1'];
        $limit =  $parameters['2'];
        $allCountriesCmd =  'select countryId from countryTable where countryId > 1';

        $allCountries = $this->db->query($allCountriesCmd);
        $msgArray = array();
        foreach ($allCountries->result() as $row){
            if($row->countryId == 2){
                $queryCmd = "(select '2' as countryId , city_id, city_name, '1000' from countryCityTable where city_name in ('Mumbai','Bangalore','Pune','Hyderabad','Kolkata','Delhi','Chennai','Chandigarh','Ahemdabad','Indore','Kota','Jaipur','Lucknow','Coimbatore','Trivandrum','Bhopal','Bhubaneshwar','Dehradun','Cochin','Nagpur','Kanpur','Goa','Navi Mumbai','Trichy') and enabled = 0 ) union (select countryCityTable.countryId, countryCityTable.city_id, countryCityTable.city_name, count(*) as cnt from countryCityTable, institute_location_table where institute_location_table.status='live' and institute_location_table.city_id = countryCityTable.city_id and countryCityTable.countryId = ? and countryCityTable.city_name  not in ('Mumbai','Bangalore','Pune','Hyderabad','Kolkata','Delhi','Chennai','Chandigarh','Ahemdabad','Indore','Kota','Jaipur','Lucknow','Coimbatore','Trivandrum','Bhopal','Bhubaneshwar','Dehradun','Cochin','Nagpur','Kanpur','Goa','Navi Mumbai','Trichy') group by countryCityTable.city_id order by 4 desc limit ?)";

                $query = $this->db->query($queryCmd, array($row->countryId, (int)$limit));
            }
            else{
                $queryCmd = "select countryCityTable.countryId, countryCityTable.city_id, countryCityTable.city_name, count(*) as cnt from countryCityTable, institute_location_table where institute_location_table.status='live' and institute_location_table.city_id = countryCityTable.city_id and countryCityTable.countryId = ? group by countryCityTable.city_id order by 4 desc limit ?";

                $query = $this->db->query($queryCmd, array($row->countryId, (int)$limit));
            }
            error_log("cities".$queryCmd);
            
            foreach ($query->result_array() as $row){
                unset($row['cnt']);
                array_push($msgArray,array($row,'struct'));
            }
        }
		$response = array($msgArray,'struct');
		//error_log_shiksha('getCityListS::'.print_r($response, true));
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Get the category list for different tiers
	*/
	function getCategoryBasedOnTier($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$tier=$parameters['1'];

		$queryCmd = 'select boardId, name from categoryBoardTable where enabled=0 and parentId = 1 and tier=?';
		error_log('getCategoryBasedOnTier query cmd is ' . $queryCmd);

		$query = $this->db->query($queryCmd, array($tier));
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
					array(
						'categoryName'=>array($row->name,'string'),
						'categoryId'=>array($row->boardId,'string')
					),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	*	Get the category list for different tiers
	*/
	function getCityBasedOnTier($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$tier=$parameters['1'];
		$countryId=$parameters['2'];

        $params[] = $tier;
        if($countryId == 1 || $countryId ==''){
            $addCountryWhere = '';
        }
        else{
            $addCountryWhere = " and countryTable.countryId in (?) ";
            $params[] = $countryId;
        }

        $queryCmd = "select countryCityTable.countryId, countryTable.name, countryCityTable.city_name, countryCityTable.city_id, countryCityTable.state_id, stateTable.state_name from countryCityTable left join stateTable on countryCityTable.state_id=stateTable.state_id, countryTable where countryTable.countryId = countryCityTable.countryId and countryCityTable.tier=? $addCountryWhere ";
		error_log('getCityBasedOnTier query cmd is ' . $queryCmd);

		$query = $this->db->query($queryCmd, $params);
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
						'cityName'=>$row->city_name,
						'cityId'=>$row->city_id,
						'stateName'=>$row->state_name,
						'stateId'=>$row->state_id,
						'countryName'=>$row->name,
						'countryId'=>$row->countryId,
					));//close array_push

		}
		$response = array(json_encode($msgArray),'string');
		return $this->xmlrpc->send_response($response);
	}

	function sgetCategoryDetailsById($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$boardId=$parameters['1'];

		$this->db->select('name,urlName')->from('categoryBoardTable')->where(array('boardId' => $boardId));
		$query = $this->db->get();
		$msgArray = array();

		foreach ($query->result_array() as $row){
			array_push($msgArray,array($row,'struct'));
		}

		$response = array($row,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	* getSubToParentCategoryMapping functions returns all the subcategory-to-category mapping.
	*/
	function getSubToParentCategoryMapping($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$queryCmd = 'select boardId,parentId,name from categoryBoardTable where parentId != 1 and parentId != 0';
		$Result = $this->db->query($queryCmd);
		$categoryListArray = array();
		foreach ($Result->result_array() as $row){
			$categoryListArray[$row['boardId']] = array(array('parentId'=>$row['parentId'],'name' => $row['name']),'struct');
		}
		$response = array(
							array('Mapping'=>array($categoryListArray,'struct')
						),'struct');
		return $this->xmlrpc->send_response($response);
	}

    function sGetDetailsForCityId($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        if(empty($cityId)) return;
        $queryCmd = 'select cct.city_id, cct.city_name, cct.state_id, st.state_name, cct.countryId, cct.tier from countryCityTable cct left join stateTable st on st.state_id = cct.state_id WHERE  cct.city_id = ?';
        $Result = $this->db->query($queryCmd, array($cityId));
        $cityDetails = '';
        foreach ($Result->result_array() as $row){
            $cityDetails = json_encode($row);
        }
        return $this->xmlrpc->send_response($cityDetails);
    }

    function sgetLocalitiesForCityId($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        // Ravi :: Updating the logic of the cities for multiple cityIds. Changing  = to IN
        #$queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId ='. $this->db->escape($cityId);
        $cityId = trim($cityId, ',');
        if($cityId == '') return;
        $queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier  ,tzm.zoneName FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id LEFT JOIN tZoneMapping tzm ON tzm.zoneId = lcm.zoneId WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId IN (?)';
        $Result = $this->db->query($queryCmd, array($cityId));
		$localities = array();
		foreach ($Result->result_array() as $row){
			$localities[] = $row;
		}
        $response = json_encode($localities);
		return $this->xmlrpc->send_response($response);
	}

    function sgetAllLocalities($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0';
        $Result = $this->db->query($queryCmd);
		$localities = array();
		foreach ($Result->result_array() as $row){
			$localities[] = $row;
		}
        $response = json_encode($localities);
		return $this->xmlrpc->send_response($response);
	}

    function sgetSpecializationForCategoryId($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId =$parameters['1'];
        $queryCmd = 'SELECT * FROM tCourseSpecializationMapping WHERE categoryId = ? ORDER BY SpecializationId';
        $Result = $this->db->query($queryCmd, array($categoryId));
        $specializationMapping= array();
        foreach ($Result->result_array() as $row){
            if($row['ParentId'] == -1) {
                $specializationMapping[$row['SpecializationId']] = $row;
            } else {
                $specializationMapping[$row['ParentId']]['children'][] = $row;
            }
        }
        $response = json_encode($specializationMapping);
        return $this->xmlrpc->send_response($response);
    }

    function sgetCourseSpecializationForCategoryIdGroups($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $categoryId =$parameters['1'];
        $queryCmd = 'SELECT tcsm.*, cgsm.* FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm WHERE  tcsm.SpecializationId = csgm.courseSpecializationId AND csgm.groupId = cgsm.groupId AND cgsm.status = "live" and tcsm.Status="live" and cgsm.categoryId  = ? and tcsm.scope="india" ORDER BY cgsm.groupId';
        $Result = $this->db->query($queryCmd, array($categoryId));
        $specializationMapping= array();
        foreach ($Result->result_array() as $row){
            $specializationMapping[$row['groupId']][] = $row;
        }
        $response = json_encode($specializationMapping);
        return $this->xmlrpc->send_response($response);
    }

    function sgetZonesForCityId($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        // Ravi :: Updating the logic of the cities for multiple cityIds. Changing  = to IN
        #$queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId ='. $this->db->escape($cityId);
        $cityId = trim($cityId, ',');
        if($cityId == '') return;
        $queryCmd = 'SELECT zm.*, cct.*, lcm.zoneId FROM localityCityMapping lcm, countryCityTable cct, tZoneMapping zm where lcm.cityId = cct.city_id and lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId IN (?) and zm.zoneId=lcm.zoneId group by zm.zoneId';
        $Result = $this->db->query($queryCmd, array($cityId));
		$zones = array();
		foreach ($Result->result_array() as $row){
			$zones[] = $row;
		}
        $response = json_encode($zones);
		return $this->xmlrpc->send_response($response);
	}

    function sgetLocalitiesForZoneId($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $zoneId =$parameters['1'];
        // Ravi :: Updating the logic of the cities for multiple cityIds. Changing  = to IN
        #$queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId ='. $this->db->escape($cityId);
        $zoneId = trim($zoneId, ',');
        if($zoneId == '') return;
        $queryCmd = 'SELECT lcm.*, zm.zoneName, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm, countryCityTable cct, tZoneMapping zm where lcm.cityId = cct.city_id and lcm.status = "live" AND cct.enabled = 0 AND lcm.zoneId IN (?) and zm.zoneId=lcm.zoneId';
        $Result = $this->db->query($queryCmd, array($zoneId));
		$localities = array();
		foreach ($Result->result_array() as $row){
			$localities[] = $row;
		}
        $response = json_encode($localities);
		return $this->xmlrpc->send_response($response);
	}

    function sgetCityGroupInSameVirtualCity($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        // Ravi :: Updating the logic of the cities for multiple cityIds. Changing  = to IN
        #$queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId ='. $this->db->escape($cityId);
        $cityId = trim($cityId, ',');
        if($cityId == '') return;
        $queryCmd = "SELECT * from virtualCityMapping where city_id in (?) and city_id!=virtualCityId";
        $Result = $this->db->query($queryCmd, array($cityId));
		$virtualCityId=0;
        $cityList=array();
		foreach ($Result->result_array() as $row){
			$virtualCityId = $row['virtualCityId'];
		}
        if($virtualCityId!=0)
        {
            $queryCmd="select * from virtualCityMapping, countryCityTable where countryCityTable.city_id=virtualCityMapping.city_id and virtualCityId=? and virtualCityId!=virtualCityMapping.city_id";
            $Result = $this->db->query($queryCmd, array($virtualCityId));
		    foreach ($Result->result_array() as $row){
			    $cityList[] = $row;
            }
        }

        $response = json_encode($cityList);
		return $this->xmlrpc->send_response($response);
	}

    function sgetCitiesForVirtualCity($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        $cityId = trim($cityId, ',');
        if($cityId == '') return;
        $queryCmd = "SELECT * from virtualCityMapping, countryCityTable where countryCityTable.city_id=virtualCityMapping.city_id and virtualCityId in (?)";
        $Result = $this->db->query($queryCmd, array($cityId));
		$cities = array();
		foreach ($Result->result_array() as $row){
			$cities[] = $row;
		}
        $response = json_encode($cities);
		return $this->xmlrpc->send_response($response);
	}

	function getZonewiseLocalitiesForCityId($request) {
	    $parameters = $request->output_parameters();
	    $appID=$parameters['0'];
	    $cityId =$parameters['1'];
	    $cityId = trim($cityId, ',');
	    if($cityId == '' || !is_numeric($cityId)) return;
	    $queryCmd = 'SELECT lcm.*, cct.city_name, cct.countryId, cct.state_id, cct.tier  ,tzm.zoneName FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id LEFT JOIN tZoneMapping tzm ON tzm.zoneId = lcm.zoneId WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId IN (?) ORDER BY tzm.zoneName, lcm.localityName asc';
	    $Result = $this->db->query($queryCmd, array($cityId));
	    $localities = array();
	    foreach ($Result->result_array() as $row) {
		    if(!is_array($localities[$row['zoneId']])) {
			$localities[$row['zoneId']] = array();
		    }
		    if(!is_array($localities[$row['zoneId']][$row['cityId']])) {
			$localities[$row['zoneId']][$row['cityId']] = array();
		    }
		    if(!is_array($localities[$row['zoneId']][$row['cityId']][$row['localityId']])) {
			$localities[$row['zoneId']][$row['cityId']][$row['localityId']] = array();
		    }
		    $localities[$row['zoneId']][$row['cityId']][$row['localityId']] = $row;
	    }
	    $response = json_encode($localities);
	    return $this->xmlrpc->send_response($response);
	}


	function sgetCountriesWithRegions($request) {
	    $parameters = $request->output_parameters();
	    $appID=$parameters['0'];
        $queryCmd = 'SELECT tr.regionid, tr.regionname, ct.countryId, ct.name FROM tregion tr INNER JOIN tregionmapping trm ON tr.regionid = trm.regionid RIGHT JOIN countryTable ct ON trm.id = ct.countryId WHERE ct.countryId > 2 AND ct.showOnRegistration = "YES" ORDER BY  tr.regionname, ct.name';
	    $Result = $this->db->query($queryCmd);
	    $countries = array();
	    foreach ($Result->result_array() as $row) {
		    $countries[$row['regionid']][$row['countryId']] = $row;
	    }
	    $response = json_encode($countries);
	    return $this->xmlrpc->send_response($response);
	}

	function getInstituteTypes() {
		$instituteTypes = array("Academic Institute","Test Preparatory Institute","Multi Location Academic Institute","Multi Location Test Preparatory Institute");
		$response = json_encode($instituteTypes);
		return $this->xmlrpc->send_response($response);
	}

	function wsgetTestPrepCoursesList($request)
	{
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$queryCmd = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND parentId = 0 ";
		$Result = $this->db->query($queryCmd);
		$finalResultArray=array();
		foreach ($Result->result_array() as $row)
		{
			$queryfinal = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND parentId = ?";
			$query1 = $this->db->query($queryfinal, array($row['blogId']));
			foreach ($query1->result_array() as $finalrow)
			{
				$finalResultArray[$row['blogId']][] =	array('acronym'=>$row['acronym'],'title'=>$row['blogTitle'],'child'=>$finalrow);
			}
		}
		return $this->xmlrpc->send_response(json_encode($finalResultArray));
	}
	function wsgetblogNameCsvFromBlogIdCsv($request)
	{
        return; /*
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$csvblogid = $parameters['1'];
		$queryCmd = "SELECT acronym FROM blogTable WHERE blogType ='exam' AND status = 'live' AND blogId in (" .$csvblogid . ")";
		$Result = $this->db->query($queryCmd);
		$results = array();
		foreach ($Result->result_array() as $row)
		{
			$results[]  = $row['acronym'];
		}
		$finalResult = "";
		$finalResult = implode(",", $results);
		return $this->xmlrpc->send_response(json_encode($finalResult));
        */
	}
	
	function getPopularCourses($request)
	{
        return; /*
		$parameters = $request->output_parameters();
		$catIDs=$parameters['0'];
		$queryCmd = "SELECT ldbCourseID FROM `LDBCoursesToSubcategoryMapping` WHERE `categoryID` IN (".$catIDs.") AND  status='live' AND isPopularCourse = 1";
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
		$Result = $this->db->query($queryCmd);
		$results = array();
		foreach ($Result->result_array() as $row)
		{
			$results[]  = $row['ldbCourseID'];
		}
		return $this->xmlrpc->send_response(json_encode($results));
        */
	}
	
	function getSubCategoryCourses($request)
	{
        return; /*
		$parameters = $request->output_parameters();
		//error_log("query is ".print_r($parameters,true),3,'/home/amit/Desktop/log.txt');
		$subcategoryIDs=$parameters['0'];
		$onlyCourses=$parameters['1'];
		if($subcategoryIDs){
			$categoryClause =  "AND LDBCoursesToSubcategoryMapping.categoryID IN ( ".$subcategoryIDs." )";
		}
		if($onlyCourses){
			$onlyCourseClause =  "AND tCourseSpecializationMapping.SpecializationName = 'all'";
		}
                
                $ldbModel = $this->load->model('LDB/ldbcoursemodel');
                $restrictedCourses = "";
		$restrictedLDBCourses = $ldbModel->getGloballyRestrictedLDBCourses('no', 'live');
		foreach($restrictedLDBCourses as $key => $value) {			
			$restrictedCourses .= ($restrictedCourses == "" ? "" : ",").$value['ldb_course_id'];
		}
		
		if($restrictedCourses != "") {
			$restrictedLDBClause = " AND SpecializationId NOT IN (".$restrictedCourses.") ";
		}
                
		$queryCmd = "SELECT SpecializationId , SpecializationName , CourseName , LDBCoursesToSubcategoryMapping.categoryID,CourseLevel1,scope FROM tCourseSpecializationMapping , LDBCoursesToSubcategoryMapping WHERE ldbCourseID = SpecializationId ".$categoryClause.$onlyCourseClause." AND LDBCoursesToSubcategoryMapping.Status = 'live' AND tCourseSpecializationMapping.status = 'live' ".$restrictedLDBClause;
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
		$Result = $this->db->query($queryCmd);
		$results = array();
		foreach ($Result->result_array() as $row)
		{
			$tmpResult = array();
			$tmpResult['SpecializationId'] = $row['SpecializationId'];
			$tmpResult['SpecializationName'] = $row['SpecializationName'];
			if(!($tmpResult['SpecializationName'] == 'All' || $tmpResult['SpecializationName'] == 'ALL'|| $tmpResult['SpecializationName'] == '')){
				$tmpResult['CourseName'] = $row['CourseName'] ." ". $row['SpecializationName'];
			}else{
				$tmpResult['CourseName'] = $row['CourseName'];
			}
			if($row['CourseLevel1'] == 'PG' && $row['scope'] == 'abroad'){
				$tmpResult['CourseName'] = $tmpResult['CourseName'] . " (" . $row['CourseLevel1'] . ")" ;
			}
			$tmpResult['categoryID'] = $row['categoryID'];
			$results[$row['categoryID']][]  = $tmpResult;
		}
		return $this->xmlrpc->send_response(json_encode($results));
        */
	}
	
	function setPopularCourses($request)
	{
        return; /*
		$parameters = $request->output_parameters();
		$this->db = $this->dbLibObj->getWriteHandle();
		$selectedCourse=$parameters['0'];
		$unSelectedCourse=$parameters['1'];
		$queryCmd = "update `LDBCoursesToSubcategoryMapping` set isPopularCourse = 1 WHERE `ldbCourseID` IN (".$selectedCourse.") AND  status='live'";
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
		$this->db->query($queryCmd);
		$queryCmd = "update `LDBCoursesToSubcategoryMapping` set isPopularCourse = 0 WHERE `ldbCourseID` IN (".$unSelectedCourse.") AND  status='live'";
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
		$this->db->query($queryCmd);
		return 10;
        */
	}
	
	function getCatSubcatList($request)
	{
		$parameters = $request->output_parameters();
		$countryId=$parameters['0'];
		$instituteType=$parameters['1'];
		$type = 'national';
                $mainCatFlag = 'national';                
		if($instituteType == "Academic_Institute" || $instituteType == "1"){
			if($countryId > 2){
				$type = 'studyabroad';                                
			}
		}else{
			if($countryId > 2){
				$type = 'studyabroad';                                
			}
                        $mainCatFlag = 'testprep';
		}
		$queryCmd = "select A.name as parentName, A.boardId as parentId, B.name as catName, B.boardId as catId from categoryBoardTable A, categoryBoardTable B where A.parentId=1 and B.parentId = A.boardId and (A.flag = ? and B.flag = ?)";           
                
		$Result = $this->db->query($queryCmd, array($mainCatFlag, $type));
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/log.txt');
		$results = array();
		foreach ($Result->result_array() as $row)
		{
			$tmpResult = array();
			$tmpResult['catId'] = $row['catId'];
			$tmpResult['catName'] = $row['catName'];
			$tmpResult['parentName'] = $row['parentName'];
			$tmpResult['parentId'] = $row['parentId'];
			$results[$row['parentId']]['subcategories'][$tmpResult['catId']]  = $tmpResult;
			$results[$row['parentId']]['name'] = $row['parentName'];
		}
		//error_log("query is ".print_r($results,true),3,'/home/amit/Desktop/log.txt');
		return $this->xmlrpc->send_response(json_encode($results));
	}
	
	function getLdbMappedCourses($request)
	{
		$parameters = $request->output_parameters();
		$courseId=$parameters['0'];
		$queryCmd = "SELECT clientCourseToLDBCourseMapping.LDBCourseID, parentId, LDBCoursesToSubcategoryMapping.categoryID
					FROM clientCourseToLDBCourseMapping, categoryBoardTable, LDBCoursesToSubcategoryMapping
					WHERE clientCourseToLDBCourseMapping.clientCourseID = ? 
					AND categoryBoardTable.boardId = LDBCoursesToSubcategoryMapping.categoryID
					AND LDBCoursesToSubcategoryMapping.status = 'live'
					AND clientCourseToLDBCourseMapping.status = 'draft'
					AND LDBCoursesToSubcategoryMapping.ldbCourseID = clientCourseToLDBCourseMapping.LDBCourseID
					LIMIT 0, 20";
		$Result = $this->db->query($queryCmd, array($courseId));
		//error_log("query is ".print_r($Result->num_rows($Result),true),3,'/home/amit/Desktop/log.txt');
		if(!$Result->num_rows($Result)){
			$queryCmd = "SELECT clientCourseToLDBCourseMapping.LDBCourseID, parentId, LDBCoursesToSubcategoryMapping.categoryID
					FROM clientCourseToLDBCourseMapping, categoryBoardTable, LDBCoursesToSubcategoryMapping
					WHERE clientCourseToLDBCourseMapping.clientCourseID = ?
					AND categoryBoardTable.boardId = LDBCoursesToSubcategoryMapping.categoryID
					AND LDBCoursesToSubcategoryMapping.status = 'live'
					AND clientCourseToLDBCourseMapping.status = 'live'
					AND LDBCoursesToSubcategoryMapping.ldbCourseID = clientCourseToLDBCourseMapping.LDBCourseID
					LIMIT 0, 20";
			$Result = $this->db->query($queryCmd, array($courseId));
		}
		$results = array();
		foreach ($Result->result_array() as $row)
		{
			$tmpResult = array();
			$tmpResult['LDBCourseID'] = $row['LDBCourseID'];
			$tmpResult['parentId'] = $row['parentId'];
			$tmpResult['categoryID'] = $row['categoryID'];
			$results[] = $tmpResult;
		}
		//error_log("query is ".print_r($results,true),3,'/home/amit/Desktop/log.txt');
		return $this->xmlrpc->send_response(json_encode($results));
	}
	
	function getCategoryPageHeaderText($request)
	{
		$parameters = $request->output_parameters();
		$page_type = $parameters['0'];
		$type_id = $parameters['1'];
		$location_type = $parameters['2'];
		$location_id = $parameters['3'];

		$queryCmd = "SELECT `text`
					FROM `categoryPageHeaderText`
					WHERE `page_type` = ?
					AND `type_id` = ?
					AND `location_type` = ?
					AND `location_id` = ? and status='live'";

		$Result = $this->db->query($queryCmd, array($page_type, $type_id, $location_type, $location_id));
		$result = '';
		foreach ($Result->result_array() as $row)
		{
			$result  = $row['text'];
		}
		return $this->xmlrpc->send_response(json_encode($result));
	}
	
	function setCategoryPageHeaderText($request)
	{
		//error_log("hey...............");
		$parameters = $request->output_parameters();
		$page_type = $parameters['0'];
		$type_id = $parameters['1'];
		$location_type = $parameters['2'];
		$location_id = $parameters['3'];
		$text = $parameters['4'];
		$this->db = $this->dbLibObj->getWriteHandle();
		$queryCmd = "Update `categoryPageHeaderText` set status = 'history'
					WHERE `page_type` = ? 
					AND `type_id` = ? 
					AND `location_type` = ? 
					AND `location_id` = ?";
		//error_log("query is ".print_r($queryCmd,true),3,'/home/amit/Desktop/amit.log');
		$this->db->query($queryCmd, array($page_type, $type_id, $location_type, $location_id));

		$dataArr = array("page_type" 	 => $page_type,
						 "type_id"       => $type_id,
						 "location_id"   => $location_id,
						 "location_type" => $location_type,
						 "text"          => $text,
						 "status"        => "live");

		$this->db->insert("categoryPageHeaderText", $dataArr);

		return $this->xmlrpc->send_response(json_encode("success"));
	}

        function removeCategorypageWidgetsDataInfo($request) {
                $parameters = $request->output_parameters();
                $regionId = $parameters['0'];
                $countryId = $parameters['1'];
		$categoryID = $parameters['2'];
		$widgetType = $parameters['3'];
                
		$this->db = $this->dbLibObj->getWriteHandle();
                switch($widgetType) {
                    case '1': case '3':
                            $widget_type_value = 'quick_links';                            
                        break;

                    case '2': case '4':
                            $widget_type_value = 'latest_news';

                        break;
                    
                    case '7': case '8':
                            $widget_type_value = 'must_read';
                        break;
                    
                    default:
                            $widget_type_value = 'quick_links';
                            $widgetImageName = '';
                        break;
                }

		$queryCmd = "Update `articles_widgets_data` set status = 'history', lastModifiedAt = now() WHERE `categoryID` = ? AND `widgetType` = ? AND `status` = 'live'  AND `regionID` = ? AND `countryID` = ?";

        error_log("Removing data for '".$widget_type_value."' widget. Query: ".$queryCmd);
		$this->db->query($queryCmd, array($categoryID, $widget_type_value, $regionId, $countryId));
		return $this->xmlrpc->send_response(json_encode("success"));     
        }

        function setCategorypageWidgetsDataInfo($request) {
            return; /*
		$parameters = $request->output_parameters();
        $regionId = $parameters['0'] == "" ? '0' : $parameters['0'];
        $countryId = $parameters['1'] == "" ? '0' : $parameters['1'];
		$categoryID = $parameters['2'];
		$dataIDs = $parameters['3'];
		$widgetType = $parameters['4'];
		$this->db = $this->dbLibObj->getWriteHandle();
                switch($widgetType) {
                    case '1': case '3':
                            $widget_type_value = 'quick_links';
                            $widgetImageName = '';
                        break;
                    
                    case '2': case '4':
                            $widget_type_value = 'latest_news';
                            $widgetImageName = $parameters['5'];
                            // Need to check for already uploaded image with the previous set and user doesn't want to upload the image this time.
                            if($widgetImageName == "") {                                
                                $queryCmd = "SELECT imageName FROM `articles_widgets_data` WHERE `categoryID` = ? AND `widgetType` = ? AND `status` = 'live' AND imageName != ''";
                                // error_log("\n\n Image with previous set, query :'".$queryCmd,3,'/home/infoedge/Desktop/log.txt');
                                // error_log("\n\nIn enterprise.php file: ".print_r($dataIDs,true),3,'/home/infoedge/Desktop/log.txt');
                                $result = $this->db->query($queryCmd, array($categoryID, $widget_type_value));
                                if($result->num_rows()) {
                                    $row = $result->row();
                                    $widgetImageName = $row->imageName;
                                }
                            }
                            
                        break;

                        case '7': case '8':
                            $widget_type_value = 'must_read';
                            $widgetImageName = '';
                        break;
                        
                    default:
                            $widget_type_value = 'quick_links';
                            $widgetImageName = '';
                        break;
                }                

		$queryCmd = "Update `articles_widgets_data` set status = 'history', lastModifiedAt = now() WHERE `categoryID` = ? AND `widgetType` = ? AND `status` = 'live' AND `regionID` = ? AND `countryID` = ? ";
		$this->db->query($queryCmd, array($categoryID, $widget_type_value, $regionId, $countryId));
                // error_log("Updating data for '".$widget_type_value."' widget. Query: ".$queryCmd);
                $dataIDsArray = split(",", $dataIDs);                
                $totalDataCount = count($dataIDsArray);
                $insertedValues = "";
                
                for($i = 0; $i < $totalDataCount; $i++) {
                    $insertedValues .= "('".$regionId."', '".$countryId."', '".$categoryID."', '".$dataIDsArray[$i]."', '".$widget_type_value."', '".$widgetImageName."', '".($i + 1)."', now(), 'live')";

                    if($widgetImageName != "") // Only one entry as for one set there would be only one image.
                        $widgetImageName = "";
                    
                    if($i != ($totalDataCount -1))
                    $insertedValues .= ", ";
                }

		$queryCmd = "INSERT INTO `articles_widgets_data` ( `regionID`,  `countryID`, `categoryID`, `articleID`, `widgetType`, `imageName`, `displayOrder`, `lastModifiedAt`, `status`) VALUES ".$insertedValues;

                // error_log("\n\nIn enterprise.php file: ".print_r($queryCmd,true),3,'/home/infoedge/Desktop/log.txt');
                
                error_log("Setting data for '".$widget_type_value."' widget. Query: ".$queryCmd);
		$this->db->query($queryCmd);
		return $this->xmlrpc->send_response(json_encode("success"));            
        }

        function getCategorypageWidgetsDataInfo($request) {
		$parameters = $request->output_parameters();
                // $regionId = $parameters['0'];
                // $countryId = $parameters['1'];

                $regionId = $parameters['0'] == "" ? '0' : $parameters['0'];
                $countryId = $parameters['1'] == "" ? '0' : $parameters['1'];


		$categoryID = $parameters['2'];
		$widgetType = $parameters['3'];

                switch($widgetType) {
                    case '1': case '3':
                            $widget_type_value = 'quick_links';
                        break;
                    case '2': case '4':
                            $widget_type_value = 'latest_news';
                        break;
                    
                    case '7': case '8':
                            $widget_type_value = 'must_read';
                        break;
                        
                    default:
                            $widget_type_value = 'quick_links';
                        break;
                }                

		// $queryCmd = "SELECT group_concat( articleID ) as articles, imageName FROM `articles_widgets_data` WHERE `categoryID` ='".$categoryID."' AND `widgetType` = '".$widget_type_value."' AND `status` = 'live' AND `regionID` ='".$regionId."' AND `countryID` = '".$countryId."' GROUP BY categoryID";
                $queryCmd = "SELECT articleID as articles, imageName FROM `articles_widgets_data` WHERE `categoryID` = ? AND `widgetType` = ? AND `status` = 'live' AND `regionID` = ? AND `countryID` = ?";
                // SELECT articleID as articles, imageName FROM `articles_widgets_data` WHERE `categoryID` ='3' AND `widgetType` = 'latest_news' AND `status` = 'live' AND `regionID` ='7' AND `countryID` = '0'
                error_log("Getting data for '".$widget_type_value."' widget. Query: ".$queryCmd);

		$Result = $this->db->query($queryCmd, array($categoryID, $widget_type_value, $regionId, $countryId));
		$result = '';
		foreach ($Result->result_array() as $row)
		{                    
                    /*
                    $result  = $row['articles'];
                    if($result != "" && $row['imageName'] != "") {
                        $result .= "#"."http://".MEDIA_SERVER_IP."/mediadata/images/categoryPageWidgetsImages/".$row['imageName'];
                    }
                     * 
                     */
                    /*
                    $result  .= ($result == ""? "" : ",").$row['articles'];
                    if($row['imageName'] != "") {
                        $articleImage = "http://".MEDIA_SERVER_IP."/mediadata/images/categoryPageWidgetsImages/".$row['imageName'];
                    }
		}

                if($result != "" && $articleImage != "") {
                        $result .= "#".$articleImage;
                }

                // error_log("\n\n Get qry: ".print_r($result,true),3,'/home/infoedge/Desktop/log.txt'); // die;
		return $this->xmlrpc->send_response(json_encode($result));            
        */
        }
	function getSAWidgetArticles($request)
	{
		$parameters = $request->output_parameters();
		
		$location_type = $parameters['1'];
		$location_id = $parameters['0'];
		$widget = $parameters['3'];
		$category_id = $parameters['2'];

		$queryCmd = 'SELECT article_id, image_url,article_type
						FROM `studyAbroadPagesWidgets`
						WHERE location_id = ? 
						AND location_type = ? 
						AND widgetType = ?
						AND category_id = ? 
						AND STATUS = "live"
						ORDER BY article_type , article_position ASC';
		$Result = $this->db->query($queryCmd, array($location_id, $location_type, $widget, $category_id));
		$result = array();
		foreach ($Result->result_array() as $row)
		{
			$tmpResult = array();
			$tmpResult['article_id']  = $row['article_id'];
			$tmpResult['image_url']  = $row['image_url'];
			$result[$row['article_type']][]  = $tmpResult;
			
		}
		//error_log("query is ".print_r($result,true),3,'/home/amit/Desktop/amit.log');
		return $this->xmlrpc->send_response(json_encode($result));
	}
	
	
	function saveSAWidgetContent($request)
	{
		$parameters    = $request->output_parameters();
		$this->db      = $this->dbLibObj->getWriteHandle();
		$location_id   = $parameters['0'];
		$location_type = $parameters['1'];
		$category_id   = $parameters['2'];
		$widget        = $parameters['3'];
		$value         = $parameters['4'];
		$position      = $parameters['5'];
		$type          = $parameters['6'];
		$image         = $parameters['7'];
		
		$queryCmd = 'UPDATE `studyAbroadPagesWidgets` set status =  "history"
						WHERE location_id = ?
						AND location_type = ? 
						AND widgetType = ? 
						AND category_id = ? 
						AND article_position = ?
						AND article_type = ? ';
		$Result = $this->db->query($queryCmd, array($location_id, $location_type, $widget, $category_id, $position, $type));
	
		$dataArr          = array("widgetType" => $widget,
							"location_id"      => $location_id,
							"location_type"    => $location_type,
							"category_id"      => $category_id,
							"article_id"       => $value,
							"article_type"     => $type,
							"article_position" => $position,
							"image_url"        => $image,
							"status"           => "live");

		$Result = $this->db->insert("studyAbroadPagesWidgets", $dataArr);

		return $this->xmlrpc->send_response(json_encode($Result));
	}
	
	function getTabsContentByCategory($request){
		$result = array();
		/*$queryCmd = "SELECT lsm.ldbCourseID AS courseid, cbt.parentId catid, tsm.SpecializationName, tsm.CourseName coursename
					FROM `LDBCoursesToSubcategoryMapping` lsm, categoryBoardTable cbt, tCourseSpecializationMapping tsm
					WHERE isPopularCourse =1
					AND lsm.categoryID = boardID
					AND tsm.SpecializationId = lsm.ldbCourseID";
		$Result1 = $this->db->query($queryCmd);*/
		$queryCmd = "SELECT a.boardId as parentid,a.name as pname,b.boardId as childid,b.name as cname FROM `categoryBoardTable` a , categoryBoardTable b where a.parentId = 1 and b.flag = 'national' and b.parentId=a.boardId order by a.name,b.name";
		$Result2 = $this->db->query($queryCmd);
		foreach($Result2->result() as $c){
			$result[$c->parentid]['id'] = $c->parentid;
			$result[$c->parentid]['name'] = $c->pname;
			$result[$c->parentid]['url'] = '';
			$result[$c->parentid]['subcats'][$c->childid]['id'] =$c->childid;
			$result[$c->parentid]['subcats'][$c->childid]['name'] = $c->cname;
			$result[$c->parentid]['subcats'][$c->childid]['url'] = '';
		}
		
		/*foreach($Result1->result() as $c){
			$result[$c->catid]['popcourses'][$c->courseid]['id'] =$c->childid;
			$result[$c->catid]['popcourses'][$c->courseid]['name'] = (($c->SpecializationName=="All")||($c->SpecializationName=="ALL"))?$c->coursename:$c->SpecializationName;
			$result[$c->catid]['popcourses'][$c->courseid]['url'] = '';
		}*/
		$response =json_encode($result);
		return $this->xmlrpc->send_response($response);
	}

    function getLocalitiesByCity($request){
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $cityId =$parameters['1'];
        $cityId = trim($cityId, ',');
        if($cityId == '') return;
        $queryCmd = 'SELECT lcm.*, cct.city_name FROM localityCityMapping lcm LEFT JOIN countryCityTable cct ON lcm.cityId = cct.city_id WHERE lcm.status = "live" AND cct.enabled = 0 AND lcm.cityId IN (?)';
        $Result = $this->db->query($queryCmd, array($cityId));
		$localities = array();
		foreach ($Result->result_array() as $row){
			$localities[] = $row;
		}
        $response = json_encode($localities);
		return $this->xmlrpc->send_response($response);
	}

	function getAllCitiesHavingLocalities(){
        $queryCmd = 'SELECT cityId FROM localityCityMapping WHERE status = "live" and cityId > 0 group by cityId';
        $Result = $this->db->query($queryCmd);
		$cities = array();
		foreach ($Result->result_array() as $row){
			$cities[] = $row['cityId'];
		}
        $response = json_encode($cities);
		return $this->xmlrpc->send_response($response);
	}
}
?>
