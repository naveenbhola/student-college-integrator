<?php
/**
 * MultipleMarketingPageServer Application Server
 *
 * This calss serves as a multiple marketing pages server
 *
 * @package Enterprise
 */

class MultipleMarketingPageServer extends MX_Controller {

	/**
	 * index function to recieve the incoming request.
	 *
	 * @access	public
	 * @return	void
	 */

	public function index(){

		//load XML RPC Libs
		$this->load->library(array('xmlrpc','xmlrpcs','MultipleMarketingPageConfig'));
		$this->load->helper('url');
        $this->dbLibObj = DbLibCommon::getInstance('Marketing');
		//Define the web services method
		$config['functions']['addNewMarketingPage'] = array('function' => 'MultipleMarketingPageServer.addNewMarketingPage');
		$config['functions']['marketingPageDetails'] = array('function' => 'MultipleMarketingPageServer.marketingPageDetails');
		$config['functions']['getmarketingPageContents'] = array('function' => 'MultipleMarketingPageServer.getmarketingPageContents');
		$config['functions']['savemarketingPageContents'] = array('function' => 'MultipleMarketingPageServer.savemarketingPageContents');
		
		$config['functions']['saveMarketingPageMailer'] = array('function' => 'MultipleMarketingPageServer.saveMarketingPageMailer');
		
		
        $config['functions']['savemarketingPageName'] = array('function' => 'MultipleMarketingPageServer.savemarketingPageName');
		$config['functions']['saveDestinationURL'] = array('function' => 'MultipleMarketingPageServer.saveDestinationURL');
        $config['functions']['getCourseSpecializationForAllCategoryIdGroup'] = array('function' =>'MultipleMarketingPageServer.getCourseSpecializationForAllCategoryIdGroup');
        $config['functions']['saveMMPageCourses'] = array('function' => 'MultipleMarketingPageServer.saveMMPageCourses');
        $config['functions']['getCourselistForApage'] = array('function' => 'MultipleMarketingPageServer.getCourselistForApage');
        $config['functions']['marketingPageDetailsById'] = array('function' => 'MultipleMarketingPageServer.marketingPageDetailsById');
        $config['functions']['getTestPrepCoursesListForApage'] = array('function' => 'MultipleMarketingPageServer.getTestPrepCoursesListForApage');
        $config['functions']['getStudyAbroadCoursesListForApage'] = array('function' => 'MultipleMarketingPageServer.getStudyAbroadCoursesListForApage');
        $config['functions']['checkPageName'] = array('function' => 'MultipleMarketingPageServer.checkPageName');
        //initialize config and serve the request
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);


	}
	/**
	 * This method adds new marketing page entry in the database.
	 *
	 * @access	public
	 * @return void
	 */

	public function addNewMarketingPage() {
        $dbHandle = $this->getDbHandler('write');
		// $arrResults = $dbHandle->query("select max(page_id) as current_page_id from marketing_page_master");
		// $results = $arrResults->result();
		// $current_page_id = $results[0]->current_page_id+2;
		// $page_url = "/marketing/Marketing/form/pageID/".$current_page_id;
		
		//add new marketing page row into table
		$insertQuery = "insert into marketing_page_master (page_url,page_type,page_name,destination_url,header_text,banner_zone_id,banner_text,form_heading)values ('','indianpage','','','','','','')";
        error_log($insertQuery."-------");
		$dbHandle->query($insertQuery);
		
		// change in code due to multi master db movement
		$current_page_id = $dbHandle->insert_id();
		$page_url = "/marketing/Marketing/form/pageID/".$current_page_id;
		$updateQuery = "update marketing_page_master set page_url = ?  where page_id = ? ";
		$dbHandle->query($updateQuery, array($page_url, $current_page_id));
        
        //return $this->xmlrpc->send_response(array($current_page_id,'struct'));
        $response = array(
                'current_page_id' => $current_page_id
                );
        return $this->xmlrpc->send_response(json_encode($response));
	}

    public function checkPageName($request){
        $parameters = $request->output_parameters();
        error_log(print_r($parameters,true));
        $dbHandle = $this->getDbHandler();
        $arrResults = $dbHandle->query("select page_name from marketing_page_master where page_name =?", array($parameters[0]));
        $results = $arrResults->result();
        if(count($results) > 0)
            $response = array('response' => 'true');
        else
            $response = array('response' => 'false');

        return $this->xmlrpc->send_response(json_encode($response));
    }
	/**
	 * This method retrieves the list of marketing pages.
	 *
	 * @access	public
	 * @return array
	 */
	public function marketingPageDetails($request) {

		$parameters = $request->output_parameters();
		$start = $parameters[0];
		$count = $parameters[1];
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler();
		$course_count_array = $dbHandle->query('select page_id, count(course_id) as count from marketing_pageid_courses_mapping group by page_id');
	    $course_count_result = $course_count_array->result();
		
		$arrResults = $dbHandle->query("select SQL_CALC_FOUND_ROWS a.page_id, page_url, page_name, destination_url, banner_url, header_text,
									    banner_zone_id, banner_text, form_heading, subheading, b.course_type,
										m.id as mailer_id,m.attachment_url
										from marketing_page_master a
										left join  marketing_pageid_courses_mapping b on a.page_id = b.page_id
										left join  marketing_page_mailer m on (a.page_id = m.page_id and m.status = 'live')
										where (a.status ='live' or (mmp_type='customized' and a.status !='history'))
										group by page_id
										order by page_id desc LIMIT $start, $count");

		$results = $arrResults->result();
		unset($arrResults);
		$pageDetails = array() ;
		$count_array = array();
		if(!empty($course_count_result) && is_array($course_count_result)) {
			foreach($course_count_result as $row){
				$count_array[]=$row;
			}
		}
		unset($course_count_result);
		if(!empty($results) && is_array($results)) {
			foreach($results as $row) {
				array_push($pageDetails,array(array("page_id"=>$row->page_id,"page_url"=>$row->page_url,"page_name"=>$row->page_name,"destination_url"=>$row->destination_url,"banner_url"=>$row->banner_url,"header_text"=>$row->header_text,"banner_zone_id"=>$row->banner_zone_id,'banner_text'=>$row->banner_text,"form_heading"=>$row->form_heading,"subheading"=>$row->subheading,'type'=>$row->course_type,'mailer_id'=>$row->mailer_id,'attachment_url'=>$row->attachment_url),'struct'));
			}
		}
		unset($results);
		$pageDetails['count_list'] = json_encode($count_array);
		
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$pageDetails['totalRows'] = $row->totalRows;
		}
		return $this->xmlrpc->send_response(array($pageDetails,'struct'));

	}
    /**
	 * This method retrieves the list of marketing pages.
	 *
	 * @access	public
	 * @return array
	 */
	public function marketingPageDetailsById($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler();
		
		if(!($parameters['0'] > 0)) {
		    return array();
		}
		
		$arrResults = $dbHandle->query("select count(course_id) as count from marketing_pageid_courses_mapping where page_id=? group by page_id", array($parameters['0']));
		$count = $arrResults->result();
		$count = $count[0]->count;
		error_log('count is'.$count);
		$arrResults = $dbHandle->query("select page_url,page_type,banner_url,destination_url,header_text,banner_zone_id,banner_text,form_heading,subheading from marketing_page_master where (status='live' or (mmp_type='customized' and status !='history')) and page_id=?", array($parameters['0']));
		$results = $arrResults->result();
		$pageDetails = array() ;
		if(!empty($results) && is_array($results)) {
			$pageDetails = array('page_url'=>$results['0']->page_url, 'count_courses'=>$count,'page_type'=>$results['0']->page_type, 'banner_url'=>$results['0']->banner_url,'destination_url'=>$results['0']->destination_url,'header_text'=>$results['0']->header_text,'banner_zone_id'
			=>$results['0']->banner_zone_id,'banner_text'=>$results['0']->banner_text,'form_heading'=>$results['0']->form_heading,'subheading'=>$results['0']->subheading);
		}
		return $this->xmlrpc->send_response(array($pageDetails,'struct'));

	}
	/**
	 * This method retrieves details for a particular marketing page.
	 *
	 * @access	public
	 * @return array
	 */
	public function getmarketingPageContents($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler();
		//get latest page_id from table
		$arrResults = $dbHandle->query("select a.page_url,a.page_id,a.display_on_page,a.background_url,a.background_image,a.pixel_codes,a.form_heading,a.subheading,m.subject,m.content,m.attachment_url,m.attachment_name,m.download_confirmation_message,a.submitButtonText, a.header_image from marketing_page_master a left join marketing_page_mailer m on (m.page_id = a.page_id and m.status = 'live') where a.page_id=?", array($parameters['0']));

		$results = $arrResults->result();
		$pageDetails = array() ;
		if(!empty($results) && is_array($results)) {
				array_push($pageDetails,array(array("page_url"=>$results[0]->page_url, "page_id"=>$results[0]->page_id,"background_url"=>$results[0]->background_url,"form_heading"=>$results[0]->form_heading,"subheading"=>$results[0]->subheading,"background_image"=>$results[0]->background_image, "display_on_page"=>$results[0]->display_on_page,"pixel_codes"=>$results[0]->pixel_codes,"subject"=>$results[0]->subject,"content"=>$results[0]->content,"attachment_url"=>$results[0]->attachment_url,'attachment_name'=>$results[0]->attachment_name,'download_confirmation_message' => $results[0]->download_confirmation_message,"submitButtonText"=>$results[0]->submitButtonText,"header_image"=>$results[0]->header_image),'struct'));
		}
		return $this->xmlrpc->send_response(array($pageDetails,'struct'));

	}
	/**
	 * This method saves the details of a selected marketing page.
	 *
	 * @access	public
	 * @return array
	 */

	public function savemarketingPageContents($request) {
		//get request parameter
		
		$parameters = $request->output_parameters();
                $dbHandle = $this->getDbHandler('write');
		if(!empty($parameters['6'])) {
		    $update_query = "UPDATE marketing_page_master ".
		                "SET banner_url=?, header_text=?".
				",banner_zone_id=?,banner_text=? ".
				",form_heading=?,subheading=?,background_url=?, ".
				"background_image=?,pixel_codes=?, ".
				"submitButtonText=? ,".
				"header_image=? ".
				"header_image=? ".
				"where page_id=?";
				$arrResult = $dbHandle->query($update_query,array($parameters['6'],$parameters['1'],$parameters['2'],$parameters['3'],$parameters['4'],$parameters['5'],$parameters['7'],$parameters['8'],$parameters['9'],$parameters['10'], $parameters['11'],$parameters['0']));
		} else {			
		// load data base shiksha (default data base)
		    $update_query = "UPDATE marketing_page_master set header_text=? ".
		    ",banner_zone_id=?,banner_text=?,form_heading=?,subheading=?,background_url=?".
		    ",background_image=?,pixel_codes=?, submitButtonText=?, header_image=? where page_id=?";
		    $arrResult = $dbHandle->query($update_query,array($parameters['1'],$parameters['2'],$parameters['3'],$parameters['4'],$parameters['5'],$parameters['7'],$parameters['8'],$parameters['9'],$parameters['10'], $parameters['11'], $parameters['0']));
		}
		//error_log($update_query);
		//$arrResults = array_push($arrResults,array(array($arrResult,'struct')));
		return $this->xmlrpc->send_response(array($arrResult,'struct'));
	}
	
	public function saveMarketingPageMailer($request)
	{
		//get request parameter
		$parameters = $request->output_parameters();
		
		$dbHandle = $this->getDbHandler('write');
		
		$select_query = "select * from marketing_page_mailer where page_id=? and status = 'live'";
		$query = $dbHandle->query($select_query, array($parameters['0']));
		$result = $query->row_array();
		
		$update_query = "update marketing_page_mailer set status = 'history' where page_id=?";
		$dbHandle->query($update_query, array($parameters['0']));
		
		$data = array(
			'page_id' => $parameters['0'],
			'subject' => $parameters['1'],
			'content' => $parameters['2'],
			'attachment_url' => $result['attachment_url'],
			'attachment_name' => $result['attachment_name'],
			'download_confirmation_message' => $parameters['6'],
			'status' => 'live'
		);
		
		$update_attachment = $parameters['5'];
		if($update_attachment == 'yes') {
			$data['attachment_url'] = $parameters['3'];
			$data['attachment_name'] = $parameters['4'];
		}
		
		$arrResult = $dbHandle->insert('marketing_page_mailer',$data);
		return $this->xmlrpc->send_response(array($arrResult,'struct'));
	}
	
	
	/**
	 * This method saves the new name of a marketing page.
	 *
	 * @access	public
	 * @return array
	 */
	public function savemarketingPageName($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		$update_query = "update marketing_page_master set page_name=?, display_on_page = ? where page_id=?";
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler('write');
		$arrResult = $dbHandle->query($update_query, array($parameters['1'], $parameters['2'], $parameters['0']));
		//$arrResults = array_push($arrResults,array(array($arrResult,'struct')));
		return $this->xmlrpc->send_response(array($arrResult,'struct'));

	}
	/**
	 * This method saves the new destination URL a marketing page.
	 *
	 * @access	public
	 * @return array
	 */
	public function saveDestinationURL($request) {
	   //get request parameter
		$parameters = $request->output_parameters();
		$update_query = "update marketing_page_master set destination_url=? where page_id=?";
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler('write');
		$arrResult = $dbHandle->query($update_query, array($parameters['1'], $parameters['0']));
		//$arrResults = array_push($arrResults,array(array($arrResult,'struct')));
		return $this->xmlrpc->send_response(array($arrResult,'struct'));

	}
	/**
	 * This method list exhaustive list of couses from all categories.
	 *
	 * @access	public
	 * @return array
	 */
	public function getCourseSpecializationForAllCategoryIdGroup($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		//error_log($parameters['0']);
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler();
		$query = "select course_id from marketing_pageid_courses_mapping where course_type!='management' and page_id=?";
		$arrResult = $dbHandle->query($query, array($parameters['0']))->result();
		//error_log($query);
		$courseids = "";
		if(!empty($arrResult) && is_array($arrResult)) {
		  foreach($arrResult as $row) {
		  	$courseids = $courseids." ".$row->course_id;
		  }
		}
		$courseids = str_replace(" ",",",trim($courseids));
		if(!empty($courseids)) {
		    $select_query = 'SELECT tcsm.*, cgsm.*, cbtbl.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm, categoryBoardTable
cbtbl WHERE  tcsm.SpecializationId = csgm.courseSpecializationId AND csgm.groupId = cgsm.groupId AND cgsm.status = "live" and tcsm.Status="live" and cgsm.categoryId  = cbtbl.boardId and
tcsm.scope="india" and tcsm.SpecializationName = "All" and cbtbl.boardId not in (3,14) and csgm.courseSpecializationId not in('.$courseids.') ORDER BY cgsm.categoryId';
		} else {
			$select_query = 'SELECT tcsm.*, cgsm.*, cbtbl.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm, categoryBoardTable
cbtbl WHERE  tcsm.SpecializationId = csgm.courseSpecializationId AND csgm.groupId = cgsm.groupId AND cgsm.status = "live" and tcsm.Status="live" and cgsm.categoryId  = cbtbl.boardId and
tcsm.scope="india" and tcsm.SpecializationName = "All" and cbtbl.boardId not in (3,14) ORDER BY cgsm.categoryId';

		}
		$arrResult = $dbHandle->query($select_query);
		$specializationMapping= array();
        foreach ($arrResult->result_array() as $row){
            $specializationMapping[$row['categoryId']][] = $row;
        }
        //error_log("response is".$specializationMapping);
        $response = json_encode($specializationMapping);
        //error_log("response is".$response);
        return $this->xmlrpc->send_response($response,'struct');
	}

	/**
	 * saves selected courses for a selected page
	 *
	 * @access	public
	 * @return	array
	 */
	public function saveMMPageCourses($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		//load data base
		$dbHandle = $this->getDbHandler('write');
		if(!empty($parameters['2'])) {
		$update_query = "update marketing_page_master set page_type=? where page_id=?";
		$dbHandle->query($update_query, array($parameters['2'], $parameters['0']));
		}
		//error_log('courses'.$parameters['1']);
		$delete_query = "delete from marketing_pageid_courses_mapping where page_id=?";
        $dbHandle->query($delete_query, array($parameters['0']));
        if($parameters['2']=='indianpage') {
        $courses = split('-',$parameters['1']);
        $management_courses = split(' ',$courses['1']);
        $general_courses =    split(' ',$courses['0']);
        } else {
        	$general_courses = split(' ',$parameters['1']);
        }
        $count = count($general_courses);
        if(!empty($general_courses[0]) && is_array($general_courses)) {
        for($index=0;$index<$count;$index++) {
                
            $groupid = $this->_getGroupIdForMMPCourse($general_courses[$index]);

        	if($parameters['2']=='indianpage') {
                $insertQuery = "insert into marketing_pageid_courses_mapping(page_id,course_id,course_type,groupid) values (?,?,'nonmanagement',?)";
                } else {
                $insertQuery = "insert into marketing_pageid_courses_mapping(page_id,course_id,course_type,groupid) values (?,?,".$dbHandle->escape($parameters['2']).",?)";
                }

        	$dbHandle->query($insertQuery, array($parameters['0'], $general_courses[$index], $groupid));

        }
        }
	   $count = count($management_courses);
	   if(!empty($management_courses[0]) && is_array($management_courses)  && $parameters['2']=='indianpage') {
        for($index=0;$index<$count;$index++) {
			$groupid = $this->_getGroupIdForMMPCourse($management_courses[$index]);
        	$insertQuery = "insert into marketing_pageid_courses_mapping(page_id,course_id,course_type,groupid) values (?,?,'management',?)";
		    $dbHandle->query($insertQuery, array($parameters['0'], $management_courses[$index], $groupid));

        }
	   }
	}
	
	private function _getGroupIdForMMPCourse($courseId)
	{
		$dbHandle = $this->getDbHandler();
		$groupidsql = "select groupid from cmp_coursegroupmapping where courseid=?";
		$resArr = $dbHandle->query($groupidsql, array($courseId))->result();
		$groupid = $resArr[0]->groupid;
		return $groupid;
	}

	/**
	 * This method list list of couses for a page based on category or group
	 *
	 * @access	public
	 * @return array
	 */
	public function getCourselistForApage($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$dbHandle = $this->getDbHandler();
		$query = "select course_id from marketing_pageid_courses_mapping where course_type!='management' and page_id=?";
		$arrResult = $dbHandle->query($query, array($parameters['0']))->result();
		$courseids = "";
		if(!empty($arrResult) && is_array($arrResult)) {
		  	foreach($arrResult as $row) {
		  		$courseids = $courseids." ".$row->course_id;
		  	}
		}
		$courseids = str_replace(" ",",",trim($courseids));
	    $query = "select course_id from marketing_pageid_courses_mapping where course_type='management' and page_id=?";
		$arrResult = $dbHandle->query($query, array($parameters['0']))->result();
		$management_courseids = "";
		if(!empty($arrResult) && is_array($arrResult)) {
		  foreach($arrResult as $row) {
		  	$management_courseids = $management_courseids." ".$row->course_id;
		  }
		}
		$specializationMapping= array();
		if(!empty($courseids)) {
			$courseIdsArray = explode(',', $courseids);
			if($parameters['1']=='category')  {
				$select_query = 'SELECT tcsm.*, cgsm.*, cbtbl.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm, categoryBoardTable cbtbl WHERE  tcsm.SpecializationId = csgm.courseSpecializationId AND csgm.courseSpecializationId in (?) AND csgm.groupId = cgsm.groupId AND cgsm.status = "live" and tcsm.Status="live" and cgsm.categoryId  = cbtbl.boardId and tcsm.scope="india" ORDER BY cgsm.categoryId';
				$order = "categoryId";
			} else if($parameters['1']=='group')  {
				$select_query = 'SELECT tcsm.*, cgsm.*, cbtbl.name FROM categoryGroupSpecializationMaster cgsm, courseSpecializationGroupMapping csgm, tCourseSpecializationMapping tcsm, categoryBoardTable cbtbl WHERE  tcsm.SpecializationId = csgm.courseSpecializationId AND csgm.courseSpecializationId in (?) AND csgm.groupId = cgsm.groupId AND cgsm.status = "live" and tcsm.Status="live" and cgsm.categoryId  = cbtbl.boardId and tcsm.scope="india" ORDER BY cgsm.groupName';
				$order = 'groupId';
		    }
		    $arrResult = $dbHandle->query($select_query, array($courseIdsArray));
		    if(!empty($arrResult)) {
				foreach ($arrResult->result_array() as $row){
					$specializationMapping[$row[$order]][] = $row;
				}
			}
		}
		$response = json_encode(array('courses_list'=>$specializationMapping,'course_id'=>$courseids,'management_courseids'=>$management_courseids));
		if($parameters['1']=='group') {
			$response = json_encode(array('courses_list'=>$specializationMapping,'management_courseids'=>$management_courseids));
		}
        error_log("dede two".print_r($response,true));
		return $this->xmlrpc->send_response($response,'struct');
	}

    /**
	 * This method list list of courses for testprep page
	 *
	 * @access	public
	 * @return array
	 */
	public function getTestPrepCoursesListForApage($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$appID=$parameters['0'];
		$dbHandle = $this->getDbHandler();
		$query = "select course_id from marketing_pageid_courses_mapping where course_type='testpreppage' and page_id=?";
		$arrResult = $dbHandle->query($query, array($parameters['1']))->result();
		$courseids = "";
		if(!empty($arrResult) && is_array($arrResult)) {
		  foreach($arrResult as $row) {
		  	$courseids = $courseids." ".$row->course_id;
		  }
		}
		$courseids = str_replace(" ",",",trim($courseids));
		$queryCmd = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND parentId = 0 ";
		//error_log($queryCmd);
		$Result = $dbHandle->query($queryCmd);
		$finalResultArray=array();
		foreach ($Result->result_array() as $row)
		{
			if($parameters['3'] == 'saved_list' && !empty($courseids)) {
			       $queryfinal = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND blogId in (".$courseids.")"." AND parentId =?";
			} else if($parameters['3'] =='complete_list' && !empty($courseids)) {
				    $queryfinal = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND blogId not in (".$courseids.")"." AND parentId =?";
			} 	else if($parameters['3'] =='complete_list' && empty($courseids)) {
		            $queryfinal = "SELECT blogId,acronym,blogTitle FROM blogTable WHERE blogType ='exam' AND status = 'live' AND parentId =?";
			}
			
			if($queryfinal) {
				$query1 = $dbHandle->query($queryfinal, array($row['blogId']));
				error_log($queryfinal);
				foreach ($query1->result_array() as $finalrow)
				{
					$finalResultArray[$row['blogId']][] =	array('acronym'=>$row['acronym'],'title'=>$row['blogTitle'],'child'=>$finalrow);
				}
			}
		}

	    $response = json_encode(array('courses_list'=>$finalResultArray,'course_id'=>$courseids));
		return $this->xmlrpc->send_response($response,'struct');
	}

    /**
	 * This method list list of courses for testprep page
	 *
	 * @access	public
	 * @return array
	 */
	public function getStudyAbroadCoursesListForApage($request) {
		//get request parameter
		$parameters = $request->output_parameters();
		// load data base shiksha (default data base)
		$appID=$parameters['0'];
		$dbHandle = $this->getDbHandler();
		$query = "select course_id from marketing_pageid_courses_mapping where course_type='abroadpage' and page_id=?";
		$arrResult = $dbHandle->query($query, array($parameters['1']))->result();
		$courseids = "";
		if(!empty($arrResult) && is_array($arrResult)) {
		  foreach($arrResult as $row) {
		  	$courseids = $courseids." ".$row->course_id;
		  }
		}
		$courseids = str_replace(" ",",",trim($courseids));
		if($parameters['3'] == 'saved_list' && !empty($courseids)) {
			$queryCmd = 'select * from categoryBoardTable where enabled=0 and parentId=1 and boardId in ( ? )';
		} else if($parameters['3'] =='complete_list' && !empty($courseids)) {
		   	$queryCmd = 'select * from categoryBoardTable where enabled=0 and parentId=1 and boardId not in ( ? )';
		} else if($parameters['3'] =='complete_list' && empty($courseids)) {
		   	$queryCmd = 'select * from categoryBoardTable where enabled=0 and parentId=1';
		}
        if($queryCmd) {
 
            if($parameters['4'] == 'newabroad') {
	 			$queryCmd = $queryCmd." AND ((flag = 'studyabroad' AND isOldCategory = '0'))";
            } else {
             	$queryCmd = $queryCmd." AND ((flag = 'studyabroad' AND isOldCategory = '1') OR  (`parentId` = 1 AND `flag` IN ('national', 'testprep')))";
            }
            if(!empty($courseids)){
            	$courseIdsArray = explode(',', $courseids);
            	$query = $dbHandle->query($queryCmd, array($courseIdsArray));
            } else {
		    	$query = $dbHandle->query($queryCmd);
            }
			$msgArray = array();
			foreach ($query->result() as $row){
				array_push($msgArray,array(
						array(
							'categoryName'=>array($row->name,'string'),
							'topicCount'=>array('50','string'),
							'categoryID'=>array($row->boardId,'string')
						),'struct'));//close array_push

			}
        }
	    $response = json_encode(array('courses_list'=>$msgArray,'course_id'=>$courseids));
		return $this->xmlrpc->send_response($response,'struct');
	}
	/**
	 * This method adds new marketing page entry in the database.
	 *
	 * @access	public
	 * @return  object
	 */
	private function getDbHandler($operation='read') {
		//connect DB
		//$dbConfig = array('hostname'=>'localhost');
		//$this->multiplemarketingpageconfig->getDbConfig($appID,$dbConfig);
        //return $this->load->database($dbConfig,TRUE);
        return $this->_loadDatabaseHandle($operation);

	}

}


?>
