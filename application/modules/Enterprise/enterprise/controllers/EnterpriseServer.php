<?php

class EnterpriseServer extends MX_Controller {

    function index(){

        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library(array('enterpriseconfig','subscription_client','sums_product_client'));
        $this->load->helper('date');

        $this->dbLibObj = DbLibCommon::getInstance('Enterprise');
        
        /* Start: Shashwat CMS Server code */
        
        $config['functions']['supdateCmsItem'] = array('function' => 'EnterpriseServer.supdateCmsItem');
        $config['functions']['addSpotlightEvent'] = array('function' => 'EnterpriseServer.addSpotlightEvent');
        $config['functions']['getSpotlightEvents'] = array('function' => 'EnterpriseServer.getSpotlightEvents');
        $config['functions']['getPopularInstitutes']=array('function'=>'EnterpriseServer.getPopularInstitutes');
        $config['functions']['setPopularInstitutes']=array('function'=>'EnterpriseServer.setPopularInstitutes');
        $config['functions']['getCompanyLogo']=array('function'=>'EnterpriseServer.getCompanyLogo');
        $config['functions']['addLogoListing']= array('function'=>'EnterpriseServer.addLogoListing');
        $config['functions']['setCompanyLogo']=array('function'=>'EnterpriseServer.setCompanyLogo');
        $config['functions']['delCompanyLogo']=array('function'=>'EnterpriseServer.delCompanyLogo');
        $config['functions']['checkDeleteLogo']=array('function'=>'EnterpriseServer.checkDeleteLogo');
        $config['functions']['modCompanyLogo']=array('function'=>'EnterpriseServer.modCompanyLogo');
        $config['functions']['sgetItems'] = array('function' => 'EnterpriseServer.sgetItems');
        $config['functions']['sgetKeyPages'] = array('function' => 'EnterpriseServer.sgetKeyPages');
        $config['functions']['supdateAssignNewInstitute'] = array('function' => 'EnterpriseServer.supdateAssignNewInstitute');
        $config['functions']['supdateOldInstitute'] = array('function' => 'EnterpriseServer.supdateOldInstitute');
        $config['functions']['sEditUpdateCourse'] = array('function' => 'EnterpriseServer.sEditUpdateCourse');
        $config['functions']['sremoveInstiLogoCMS'] = array('function' => 'EnterpriseServer.sremoveInstiLogoCMS');
        $config['functions']['sremoveFeaturedPanelLogo'] = array('function' => 'EnterpriseServer.sremoveFeaturedPanelLogo');
        $config['functions']['sremoveCourseMediaCMS'] = array('function' => 'EnterpriseServer.sremoveCourseMediaCMS');
        $config['functions']['sEditNotification'] = array('function' => 'EnterpriseServer.sEditNotification');
        $config['functions']['sRemoveNotificationDoc'] = array('function' => 'EnterpriseServer.sRemoveNotificationDoc');
        $config['functions']['getNotificationEvents'] = array('function' => 'EnterpriseServer.getNotificationEvents');
        
        $config['functions']['sGetHeaderTabs'] = array('function' => 'EnterpriseServer.sGetHeaderTabs');
        $config['functions']['saddEnterpriseUser'] = array('function' => 'EnterpriseServer.saddEnterpriseUser');
        $config['functions']['sgetInstituteList'] = array('function' => 'EnterpriseServer.sgetInstituteList');
        $config['functions']['sgetCitiesWithCollege'] = array('function' => 'EnterpriseServer.sgetCitiesWithCollege');
        $config['functions']['supdateScholarship'] = array('function' => 'EnterpriseServer.supdateScholarship');
        $config['functions']['sRemoveScholarshipDoc'] = array('function' => 'EnterpriseServer.sRemoveScholarshipDoc');
        /****** Different Product APIs *********/
        $config['functions']['sgetEventDetailCMS'] = array('function' => 'EnterpriseServer.sgetEventDetailCMS');
        $config['functions']['sgetPopularTopicsCMS'] = array('function' => 'EnterpriseServer.sgetPopularTopicsCMS');
        $config['functions']['getSearchSubCategories'] = array('function' => 'EnterpriseServer.getSearchSubCategories');
        $config['functions']['getEnterpriseUserDetails'] = array('function' => 'EnterpriseServer.getEnterpriseUserDetails');
        $config['functions']['updateEnterpriseUserDetails'] = array('function' => 'EnterpriseServer.updateEnterpriseUserDetails');
        $config['functions']['changePassword'] = array('function' => 'EnterpriseServer.changePassword');
        $config['functions']['updateUserGroup'] = array('function' => 'EnterpriseServer.updateUserGroup');
        $config['functions']['getViewCountForUserFedListings'] = array('function' => 'EnterpriseServer.getViewCountForUserFedListings');
        $config['functions']['sgetMediaData'] = array('function' => 'EnterpriseServer.sgetMediaData');
        $config['functions']['sgetcountofMedia'] = array('function' => 'EnterpriseServer.sgetcountofMedia');
        $config['functions']['sdeleteMediaData'] = array('function' => 'EnterpriseServer.sdeleteMediaData');
        $config['functions']['getReportedChangesForBlogs'] = array('function' => 'EnterpriseServer.getReportedChangesForBlogs');
        $config['functions']['getReportedChangesById'] = array('function' => 'EnterpriseServer.getReportedChangesById');
        $config['functions']['saddMainCollegeLink'] = array('function' => 'EnterpriseServer.saddMainCollegeLink');
        $config['functions']['sgetListingsByClient'] = array('function' => 'EnterpriseServer.sgetListingsByClient');
        $config['functions']['sgetMainInstitutesByClient'] = array('function' => 'EnterpriseServer.sgetMainInstitutesByClient');
        $config['functions']['scancelSubscription'] = array('function' => 'EnterpriseServer.scancelSubscription');
        $config['functions']['scheckUniqueTitle'] = array('function' => 'EnterpriseServer.scheckUniqueTitle');
        $config['functions']['supdateMainCollegeLink'] = array('function' => 'EnterpriseServer.supdateMainCollegeLink');
        $config['functions']['getKeyPageId'] = array('function' => 'EnterpriseServer.getKeyPageId');
        $config['functions']['sfetchListingForm'] = array('function' => 'EnterpriseServer.sfetchListingForm');
        $config['functions']['sgetListingsByClientAndType'] = array('function' => 'EnterpriseServer.sgetListingsByClientAndType');
        $config['functions']['checkAndConsumeActualSubscription'] = array('function' => 'EnterpriseServer.checkAndConsumeActualSubscription');
        $config['functions']['insertCreditCardDetails'] = array('function' => 'EnterpriseServer.insertCreditCardDetails');
        $config['functions']['getCreditTransactionStatus'] = array('function' => 'EnterpriseServer.getCreditTransactionStatus');
        $config['functions']['toConsumeActualSubscriptionCheck'] = array('function' => 'EnterpriseServer.toConsumeActualSubscriptionCheck');
        $config['functions']['getAbuseList'] = array('function' => 'EnterpriseServer.getAbuseList');
        $config['functions']['updateStatusAbuseList'] = array('function' => 'EnterpriseServer.updateStatusAbuseList');
        $config['functions']['republishEntity'] = array('function' => 'EnterpriseServer.republishEntity');
        $config['functions']['sendMailForAbuseActivity'] = array('function' => 'EnterpriseServer.sendMailForAbuseActivity');
        $config['functions']['getQuestionsPostedForEnterpriseUser'] = array('function' => 'EnterpriseServer.getQuestionsPostedForEnterpriseUser');
        $config['functions']['getQuestionlogInfo'] = array('function' => 'EnterpriseServer.getQuestionlogInfo');
        $config['functions']['deleteQuestionInfoInLog'] = array('function' => 'EnterpriseServer.deleteQuestionInfoInLog');
        $config['functions']['updateTitle'] = array('function' => 'EnterpriseServer.updateTitle');
        $config['functions']['sAddTVCUser'] = array('function' => 'EnterpriseServer.sAddTVCUser');
        
        $config['functions']['getExperts'] = array('function' => 'EnterpriseServer.getExperts');
        $config['functions']['actionExpert'] = array('function' => 'EnterpriseServer.actionExpert');
        $config['functions']['removeExpertProfilePic'] = array('function' => 'EnterpriseServer.removeExpertProfilePic');
        
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    //Server API to update CMS's Page-<Product> Table
    function supdateCmsItem($request)
    {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];

		$updateData = $parameters['1'];
		$updateKeyPage = $parameters['2'];

		$item_type = $updateData['item_type'];

                if($item_type == "msgboard")
                {
                    $categoryId = $updateData['categoryId'];
                    $topicId = $updateData['topicId'];
                }
                else
                {
		    $item_id = $updateData['item_id'];
                }

		$totalKeyPages = $updateData['totalKeyPages'];

                //connect DB
                $dbHandle = $this->_loadDatabaseHandle('write');

		$query = '';

		switch($item_type)
                {
		case "blog":
		{
		    $exceptionCmd = '';
		    $runInsertCommand= true;
		    if(isset($updateKeyPage['52_updateLatestUpdate']) && $updateKeyPage['52_updateLatestUpdate']==false){
    			$exceptionCmd = " AND KeyId!=52 ";
    			$runInsertCommand= false;
		    }
                    //Query Command for Deleting all existing rows for given EventID from Page-Blog (OR Article) Table
	                $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageBlogTable).' WHERE BlogId = ? '.$exceptionCmd;
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));
                    
                    $i = -1;
                    foreach($updateKeyPage as $key => $value)
                    {
                        $tempVar = split('_', $key);
                        if($tempVar[0] == $i) continue;
                        $i = $tempVar[0];
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '' && $key_id!=52)
                        {			    			
                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageBlogTable).'  (KeyId,BlogId,StartDate,EndDate) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE KeyId = ?';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date, $key_id));
                        }
			else if($key_id==52 && $runInsertCommand){
                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageBlogTable).'  (KeyId,BlogId,StartDate,EndDate) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE KeyId = ?';
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date, $key_id));
			}
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "msgboard":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-MessageBoard Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageMsgBoardTable).' WHERE CategoryId = ? AND TopicId = ? ';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($categoryId, $topicId));

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageMsgBoardTable).'  (KeyId,CategoryId,TopicId,StartDate,EndDate) VALUES (?,?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id,$categoryId,$topicId,$start_date,$end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "admission":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-Admission Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageAdmissionTable).' WHERE AdmitId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageAdmissionTable).'  (KeyId,AdmitId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id,$item_id,$start_date,$end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "scholarship":
		{
                    //Query Command for Deleting all existing rows for given EventID from Page-Scholarship Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageScholarshipTable).' WHERE ScholId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageScholarshipTable).'  (KeyId,ScholId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id,$item_id,$start_date,$end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
                }
                case "event":
                {
                    //Query Command for Deleting all existing rows for given EventID from Page-Event Table
                    $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageEventsTable).' WHERE EventId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageEventsTable).'  (KeyId,EventId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id,$item_id,$start_date,$end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
                }
		case "course":
		{
                    //Query Command for Deleting all existing rows for given CourseId from Page-Course Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageCourseColTable).' WHERE CourseId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, $item_id);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageCourseColTable).'  (KeyId,CourseId,StartDate,EndDate) VALUES ( ?, ?, ?, ?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "institute":
		{
                    //Query Command for Deleting all existing rows for given CollegeId from Page-College Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageCollegeTable).' WHERE CollegeId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, $item_id);

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageCollegeTable).'  (KeyId,CollegeId,StartDate,EndDate) VALUES ( ?, ?, ?, ?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
		case "network":
		{
                    //Query Command for Deleting all existing rows for given CollegeId from Page-Network Table
	            $deletionQuery= 'DELETE FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageNetworkTable).' WHERE CollegeId = ?';
                    $deletionQueryStatus = $dbHandle->query($deletionQuery, array($item_id));

                    for($i=1;$i<=$totalKeyPages;$i++)
                    {
                      if(isset($updateKeyPage[''.$i.'_key_id']) && $updateKeyPage[''.$i.'_key_id'] != '')
                      {
                        $key_id = $updateKeyPage[''.$i.'_key_id'];
                        $start_date = $updateKeyPage[''.$i.'_start_date'];
                        $end_date = $updateKeyPage[''.$i.'_end_date'];

                        if($key_id != '')
                        {

                            $queryCmd = 'INSERT INTO '.$dbHandle->escape_str($this->enterpriseconfig->pageNetworkTable).'  (KeyId,CollegeId,StartDate,EndDate) VALUES (?,?,?,?) ';
                            log_message('debug', 'Insert query cmd is ' . $queryCmd);
                            $query = $dbHandle->query($queryCmd, array($key_id, $item_id, $start_date, $end_date));
                        }
                      }
                      else
                      {
                          continue;
                      }
                    }
                    break;
		}
                default:
                        $response = array(array('QueryStatus'=>$query,'int'),array('Wrong_Product_Type'=>$item_type,'string'),'struct');
                        error_log("Response Array==> ".print_r($response,TRUE));
                        return $this->xmlrpc->send_response($response);
                }

		$response = array(array('QueryStatus'=>$query,'int'),'struct');
		return $this->xmlrpc->send_response($response);
    }


	//Server API to get <Product>IDs and other details for some Key_ID from Page<Product>Db
    function sgetItems($request)
    {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];

		$updateData = $parameters['1'];
		$key_id = $updateData['key_id'];
		$item_type = $updateData['item_type'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		switch($item_type) {
		case "blog":
		{

			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageBlogTable).' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'BlogId'=>array($row->BlogId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "msgboard":
		{

			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageMsgBoardTable).' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'MsgBoardId'=>array($row->MsgBoardId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "event":
		{

			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageEventsTable).' WHERE KeyId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($key_id));

			$productIdsArray = array();
			foreach ($query->result() as $row){
				array_push($productIdsArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'EventId'=>array($row->EventId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		default:
			$response = array(array('Wrong_Product_Type'=>$item_type,'string'),'struct');
			return $this->xmlrpc->send_response($response);

		}

	        $response = array($productIdsArray,'struct');
	        return $this->xmlrpc->send_response($response);
	}


    //Server API to get Key-Pages for a item_id [and topicId in case of msgboard]
    function sgetKeyPages($request)
    {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];

		$updateData = $parameters['1'];
        $fromWhere = $parameters['2'];
		$item_type = $updateData['item_type'];

                if($item_type == "msgboard")
                {
                    $categoryId = $updateData['categoryId'];
                    $topicId = $updateData['topicId'];
                }
                else
                {
		    $item_id = $updateData['item_id'];
                }

		//connect DB
        $op = 'read';
        if($fromWhere == 'cms'){
            $op = 'write';
        }
		$dbHandle = $this->_loadDatabaseHandle($op);

		switch($item_type) {
		case "blog":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageBlogTable).' WHERE BlogId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "msgboard":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageMsgBoardTable).' WHERE CategoryId = ? AND TopicId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($categoryId, $topicId));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "admission":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageAdmissionTable).' WHERE AdmitId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "scholarship":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageScholarshipTable).' WHERE ScholId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "event":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageEventsTable).' WHERE EventId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;

		}
		case "course":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageCourseColTable).' WHERE CourseId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "institute":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageCollegeTable).' WHERE CollegeId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		case "network":
		{
			$queryCmd = 'SELECT * FROM '.$dbHandle->escape_str($this->enterpriseconfig->pageNetworkTable).' WHERE CollegeId = ?';
			log_message('debug', 'query cmd is ' . $queryCmd);
			$query = $dbHandle->query($queryCmd, array($item_id));

			$keyPageArray = array();
			foreach ($query->result() as $row){
				array_push($keyPageArray,array(
						array(
							'ItemType'=>array($item_type,'string'),
							'KeyId'=>array($row->KeyId,'string'),
							'StartDate'=>array($row->StartDate,'string'),
							'EndDate'=>array($row->EndDate,'string')
						     ),'struct')
					  );
			}
			break;
		}
		default:
			$response = array(array('Wrong_Product_Type'=>$item_type,'string'),'struct');
			return $this->xmlrpc->send_response($response);
		}
	        $response = array($keyPageArray,'struct');
	        return $this->xmlrpc->send_response($response);
	}


    function makeApcCityMap(){
        $appID = 12;
	    $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'select * from countryCityTable';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $counter = 0;
        $msgArray = array();

        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

        foreach ($query->result() as $row){
            $key = "city_".$row->city_id;
            $val = $row->city_name;
            $cacheLibObj->store($key, $val);
        }
    }


    function makeApcCountryMap(){
	$dbHandle = $this->_loadDatabaseHandle();
        $appID = 12;
        $queryCmd = 'select * from countryTable';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        $counter = 0;
        $msgArray = array();

        $this->load->library('cacheLib');
        $cacheLibObj = new cacheLib();

        foreach ($query->result() as $row){
            $key = "country_".$row->countryId;
            $val = $row->name;
            $cacheLibObj->store($key, $val);
        }
     }


    function supdateAssignNewInstitute($request)
    {
        $parameters = $request->output_parameters();
        $appID      = $parameters['0'];
        $courseId   = $parameters['1'];
        $newInstId  = $parameters['2'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd      = ' UPDATE course_details SET institute_id = ? WHERE course_id = ? ';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query         = $dbHandle->query($queryCmd, array($newInstId, $courseId));
        $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');

        $queryCmd          = ' UPDATE institute_courses_mapping_table SET institute_id = ? WHERE course_id = ?';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query             = $dbHandle->query($queryCmd, array($newInstId, $courseId));
        $courseMapResponse = array(array('QueryStatus'=>$query,'int'),'struct');

        return $this->xmlrpc->send_response($courseMapResponse);
	}



    function supdateOldInstitute($request)
    {
       $parameters = $request->output_parameters();
       $appID      = $parameters['0'];
       $formVal    = $parameters['1'];

       //connect DB
       $dbHandle = $this->_loadDatabaseHandle('write');

       if ($formVal['dataFromCMS']=="dataFromCMS") {
          $moderated = "moderated";
       } else {
          $moderated = "unmoderated";
       }

             $old_institute_id = $formVal['old_institute_id'];

             $data =array();
             $data = array(
                'institute_name'               =>$formVal['institute_name'],
                'short_desc'                   =>$formVal['institute_desc'],
                'establish_year'               =>$formVal['establish_year'],
                'no_of_students'               =>$formVal['no_of_students'],
                'no_of_international_students' =>$formVal['no_of_int_students'],
                'long_description'             =>$formVal['institute_desc'],
                'certification'                =>$formVal['affiliated_to']
             );

             $where = "institute_id = $old_institute_id";

             $queryCmd = $dbHandle->update_string('institute',$data,$where);

             log_message('debug', 'query cmd is ' . $queryCmd);
             error_log("Updating institute Table ".$queryCmd);
             $query = $dbHandle->query($queryCmd);

             $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id= ? AND listing_type="institute"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($old_institute_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }

        $data =array();
        $format = 'DATE_ATOM';
             $data = array(
                'listing_type_id'  =>$old_institute_id,
                'listing_title'    =>$formVal['institute_name'],
                'short_desc'       =>$formVal['institute_desc'],
                'listing_type'     =>'institute',
                'hiddenTags'       =>$formVal['hiddenTags'],
                'contact_email'    =>$formVal['contact_email'],
                'contact_name'     =>$formVal['contact_name'],
                'contact_cell'     =>$formVal['contact_cell'],
                'last_modify_date' =>standard_date($format,time()),
                'moderation_flag'  => $moderated,
                'url'              => $formVal['url']
             );

             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
             error_log($queryCmd);
             $query = $dbHandle->query($queryCmd);

             $response = array
             (array
             ('QueryStatus' =>$query,
             'institute_id' =>$old_institute_id,
             'listing_id'   =>$changeListingId,
             'type_id'      =>$old_institute_id,
             'listing_type' =>"institute",
          ),
          'struct'
       );

             //Query Command for Deletion then Insertion in the institute_location_table

             $deleteQueryCmd = "DELETE FROM institute_location_table WHERE institute_id= ?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    error_log($deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($old_institute_id));


           for($i = 0; $i < $formVal['numoflocations']; $i++){
                //Query Command for Insert in the course category Table
			 $data =array();
			 $data = array(
						  'institute_id'=>$old_institute_id,
						  'city_id'=>$formVal['city_id'.$i],
						  'country_id'=>$formVal['country_id'.$i],
						  'address'=>$formVal['address'.$i]
							  );
			 $queryCmd = $dbHandle->insert_string('institute_location_table',$data);

                log_message('debug', 'query cmd is ' . $queryCmd);
                $query = $dbHandle->query($queryCmd);
            }

        return $this->xmlrpc->send_response($response);

	}


     function sremoveInstiLogoCMS($request)
    {
		$parameters = $request->output_parameters();
		$appID = $parameters['0'];
	        $instituteId = $parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
	        $queryCmd = ' UPDATE institute SET logo_link=NULL WHERE institute_id= ?';
		log_message('debug', 'query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($instituteId));
                $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');

                //return $this->xmlrpc->send_response($response);
                return $this->xmlrpc->send_response($instiResponse);
	}

    function sremoveFeaturedPanelLogo($request)
    {
        $parameters  = $request->output_parameters();
        $appID       = $parameters['0'];
        $instituteId = $parameters['1'];

		//connect DB
        $dbHandle      = $this->_loadDatabaseHandle('write');
        $queryCmd      = ' UPDATE institute SET featured_panel_link=NULL WHERE institute_id= ? ';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query         = $dbHandle->query($queryCmd, array($instituteId));
        $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');
        return $this->xmlrpc->send_response($instiResponse);
	}


    function sremoveCourseMediaCMS($request){
        $parameters    = $request->output_parameters();
        $appID         = $parameters['0'];
        $removeData    = $parameters['1'];
        
        $userid        = $removeData['userid'];
        $usergroup     = $removeData['usergroup'];
        $courseId      = $removeData['courseId'];
        $mediaType     = $removeData['mediaType'];
        $courseMediaId = $removeData['courseMediaId'];
        $listingType   = $removeData['listingType'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

        if($listingType == 'course'){
        switch($mediaType){
           case 'photo':
                $queryCmd = 'DELETE FROM course_photos_table WHERE course_id= ? AND photo_id= ?';
                break;
           case 'video':
                $queryCmd = 'DELETE FROM course_videos_table WHERE course_id= ? AND video_id= ?';
                break;
           case 'document':
                $queryCmd = 'DELETE FROM course_doc_table WHERE course_id= ? AND doc_id= ?';
                break;
            }
        }

        if($listingType == 'institute'){
        switch($mediaType){
           case 'photo':
                $queryCmd = 'DELETE FROM institute_photos_table WHERE institute_id= ? AND photo_id= ?';
                break;
           case 'video':
                $queryCmd = 'DELETE FROM institute_videos_table WHERE institute_id= ? AND video_id= ?';
                break;
           case 'document':
                $queryCmd = 'DELETE FROM institute_doc_table WHERE institute_id= ? AND doc_id= ?';
                break;
            }
        }
		log_message('debug', 'query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($courseId, $courseMediaId));
        $instiResponse = array(array('QueryStatus'=>$query,'int'),'struct');

        return $this->xmlrpc->send_response($instiResponse);
	}


        function sEditUpdateCourse($request)
        {
            $parameters = $request->output_parameters();
            $appID = $parameters['0'];
            $formVal = $parameters['1'];
	    $eligibility = $parameters['2'];
	    $tests = $parameters['3'];


       //connect DB
       $dbHandle = $this->_loadDatabaseHandle('write');

      if ($formVal['dataFromCMS']=="dataFromCMS") {
          $moderated = "moderated";
      } else {
          $moderated = "unmoderated";
      }

      if (!isset($formVal['sourceUrl']) || (strlen($formVal['sourceUrl'])<=5)) {
          $formVal['sourceUrl'] = $formVal['url'];
      }
      $old_institute_id = $formVal['old_institute_id'];

      //Intermediate Course Duration
      $tempDuration = preg_replace('/[^A-Za-z0-9\-\/\.]/', ' ', $formVal['duration']);
      $intermediateDuration = exec("./duration.sh '".$tempDuration."'");
      if(strlen($intermediateDuration) <= 0){
          $intermediateDuration = $formVal['duration'];
      }


            //Query Command for Insert in the course details Table
			 $data =array();
			 $data = array(
						  'courseTitle'=>$formVal['courseTitle'],
						  'overview'=>$formVal['overview'],
                                                  'duration'=>$formVal['duration'],
                                                  'intermediateDuration'=>$intermediateDuration,
						  'objective'=>$formVal['objective'],
						  'contents'=>$formVal['contents'],
						  'eligibility'=>$formVal['eligibility'],
						  'selection_criteria'=>$formVal['selection_criteria'],
						  'scholarshipText'=>$formVal['scholarships'],
						  'placements'=>$formVal['placements'],
                                                  'hostel_facility'=>$formVal['hostel_facility'],
                                                  'fees'=>$formVal['fees'],
                                                  'course_type'=>$formVal['courseType'],
                                                  'course_level'=>$formVal['courseLevel'],
                                                  'course_strength'=>" ",
						  'tests_required'=>$formVal['tests_required'],
						  'start_date'=>$formVal['startDate'],
						  'end_date'=>$formVal['endDate'],
						  'emails_for_testimonials'=>$formVal['invite_emails']
                                               );

                                               $where = 'course_id = '.$formVal["update_course_id"].'';

                                               $queryCmd = $dbHandle->update_string('course_details',$data,$where);



            log_message('debug', 'query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
            //			error_log("Query Execution Status ".$query);

            $course_id = $formVal['update_course_id'];

            $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id= ? AND listing_type="course"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($course_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }


            //Query Command for Insert in the Listing Main Table
            $data =array();
            $format = 'DATE_ATOM';
            $data = array(
                    'listing_type_id'=>$course_id,
                    'listing_title'=>$formVal['courseTitle'],
                    'short_desc'=>$formVal['overview'],
                    'hiddenTags'=>$formVal['hiddenTags'],
                    'listing_type'=>'course',
                    'last_modify_date'=>standard_date($format,time()),
                    'moderation_flag' => $moderated,
                    'sourceURL' => $formVal['sourceUrl']
                    );
             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
             $query = $dbHandle->query($queryCmd);


            $response = array(array
                    ('QueryStatus'=>$query,
                     'Course_id'=>$course_id,
                     'Listing_id'=>$changeListingId,
                     'type_id'=>$course_id,
                     'listing_type'=>"course",
                    ),
                    'struct'
                 );

             //Query Command for Deletion in the course_eligibility_table

             $deleteQueryCmd = "DELETE FROM course_eligibility_table WHERE course_id= ?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($course_id));

error_log(print_r($eligibility,true));

            if(isset($eligibility) && count($eligibility) > 0){
                foreach($eligibility as $key=>$val){
	$data =array();
	$data = array(
			'course_id'=>$course_id,
			'eligibility_criteria'=>$key,
			'eligibility_criteria_values'=>$val
		     );
	$queryCmd = $dbHandle->insert_string('course_eligibility_table',$data);

$queryCmd .= "on duplicate key update eligibility_criteria_values =  ?";

                    error_log($queryCmd);
                    $query = $dbHandle->query($queryCmd, array($val));
                }
            }


            //Query Command for Deletion of REQUIRED EXAMS
            $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='course' and typeId=? and typeOfMap='required' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmd);
            $query = $dbHandle->query($deleteQueryCmd, array($course_id));

            $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='course' and listingTypeId=? and typeOfMap='required' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
            $query = $dbHandle->query($deleteQueryCmdOther, array($course_id));


            $this->load->model('ExamModel','',$dbConfig);
            if(isset($formVal['tests_required']) && strlen($formVal['tests_required']) > 0 )
            {
                $examsArr = explode(",",$formVal['tests_required']);
                $this->ExamModel->makeEntityExamsMapping($course_id, $examsArr,'course','required');
            }
            if($formVal['tests_required_other'] == 'true'){
                $examData =array();
                $examData['listingType'] = 'course';
                $examData['listingTypeId'] = $course_id;
                $examData['typeOfMap'] = 'required';
                $examData['exam_name'] = $formVal['tests_required_exam_name'];
                $examData['exam_desc'] = $formVal['tests_required_exam_desc'];
                $examData['numOfCentres'] = 0;
                $newExamId = $this->ExamModel->insertOtherExam($examData);
            }

            //Query Command for Deletion of TESTPREP EXAMS
            $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='course' and typeId=? and typeOfMap='testprep' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmd);
            $query = $dbHandle->query($deleteQueryCmd, array($course_id));

            $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='course' and listingTypeId=? and typeOfMap='testprep' ";
            log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
            $query = $dbHandle->query($deleteQueryCmdOther, array($course_id));


            if(isset($formVal['tests_preparation']) && strlen($formVal['tests_preparation']) > 0 )
            {
                $examsArr = explode(",",$formVal['tests_preparation']);
                $this->ExamModel->makeEntityExamsMapping($course_id, $examsArr,'course','testprep');
            }
            if($formVal['tests_preparation_other'] == 'true'){
                $examData =array();
                $examData['listingType'] = 'course';
                $examData['listingTypeId'] = $course_id;
                $examData['typeOfMap'] = 'testprep';
                $examData['exam_name'] = $formVal['tests_preparation_exam_name'];
                $examData['exam_desc'] = $formVal['tests_preparation_exam_desc'];
                $examData['numOfCentres'] = 0;
                $newExamId = $this->ExamModel->insertOtherExam($examData);
            }

            return $this->xmlrpc->send_response($response);
        }


        function supdateScholarship($request)
        {
        $parameters = $request->output_parameters();
        $appId = $parameters['0']['0'];
        $formVal = $parameters['1'];
        $eligibility = $parameters['2'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        if ($formVal['dataFromCMS']=="dataFromCMS") {
            $moderated = "moderated";
        } else {
            $moderated = "unmoderated";
        }

        $data =array();
        $data = array(
                'scholarship_name'      =>$formVal['scholarship_name'],
                'short_desc'            =>$formVal['short_desc'],
                'num'                   =>$formVal['num'],
                'levels'                =>$formVal['levels'],
                'address_line1'         =>$formVal['address_line1'],
                'address_line2'         =>$formVal['address_line2'],
                'city_id'               =>$formVal['city_id'],
                'country_id'            =>$formVal['country_id'],
                'zip'                   =>$formVal['zip'],
                'application_procedure' =>$formVal['application_procedure'],
                'selection_process'     =>$formVal['selection_process'],
                'segment'               =>$formVal['segment'],
                'other_segment'         =>$formVal['other_segment'],
                'value'                 =>$formVal['value'],
                'period_of_awards'      =>$formVal['period_of_awards'],
                'institution'           =>$formVal['institution'],
                'institute_id'          =>$formVal['institute_id'],
                'contact_email'         =>$formVal['contact_email'],
                'contact_name'          =>$formVal['contact_name'],
                'contact_cell'          =>$formVal['contact_cell'],
                'contact_fax'           =>$formVal['contact_fax'],
                'address'               =>$formVal['contact_address'],
                'last_date_submission'  =>$formVal['last_date_submission']
                );
                $where = 'scholarship_id = '.$formVal["update_schol_id"].'';

                $queryCmd = $dbHandle->update_string('scholarship',$data,$where);


        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd);
        //			error_log("Query Execution Status ".$query);

        $scholarship_id = $formVal["update_schol_id"];


        $listingIdQuery = 'SELECT listing_id FROM listings_main WHERE listing_type_id= ? AND listing_type="scholarship"';
             log_message('debug', 'query cmd is ' . $listingIdQuery);
             $query = $dbHandle->query($listingIdQuery, array($scholarship_id));
             $changeListingId=0;
        foreach ($query->result() as $row){
           $changeListingId = $row->listing_id;
        }


        //Query Command for Insert in the Listing Main Table
        $data =array();
        $format = 'DATE_ATOM';
        $data = array(
                'listing_type_id'=>$scholarship_id,
                'listing_title'=>$formVal['scholarship_name'],
                'short_desc'=>$formVal['short_desc'],
                'listing_type'=>'scholarship',
                'requestIP'=>$formVal['requestIP'],
                'last_modify_date'=>standard_date($format,time()),
                'moderation_flag' => $moderated
                );
             $where = "listing_id = $changeListingId";

             $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
        $query = $dbHandle->query($queryCmd);

        $response = array(array
                ('QueryStatus'=>$query,
                 'scholarship_id'=>$scholarship_id,
                 'Listing_id'=>$changeListingId,
                 'type_id'=>$scholarship_id,
                 'listing_type'=>"scholarship",
                ),
                'struct'
                );

        //Query Command for Deletion in the scholarship_category_table

        $deleteQueryCmd = "DELETE FROM scholarship_category_table WHERE scholarship_id= ?";
        log_message('debug', 'query cmd is ' . $deleteQueryCmd);
        $query = $dbHandle->query($deleteQueryCmd, array($scholarship_id));

        $catArr = array();
        if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
            $categories = $formVal['category_id'];
            $catArr = explode(',',$categories);
            $numOfCats = count($catArr);
            for($i = 0; $i < $numOfCats ; $i++){
                //Query Command for Insert in the course category Table
                $queryCmd = "INSERT into scholarship_category_table (scholarship_id,category_id) values ( ?, ?) on duplicate key update category_id = ?";
                log_message('debug', 'query cmd is ' . $queryCmd);
                $query = $dbHandle->query($queryCmd, array($scholarship_id, $catArr[$i], $catArr[$i]));
            }
        }

        //Query Command for Insert new Institute-Schol mappings
        if ($formVal['numoflocations']>0) {
            for ($i = 0;$i<$formVal['numoflocations'];$i++) {
                $queryCmd = 'INSERT into institute_scholarship_mapping_table (institute_id,scholarship_id) values ( ?, ?)  on duplicate key update institute_id= ?';
                log_message('debug', 'query cmd is ' . $queryCmd);
                $query = $dbHandle->query($queryCmd, array($formVal['institute_id'.$i], $scholarship_id, $formVal['institute_id'.$i]));
            }
        }


        //Query Command for Deletion in the scholarship_eligibility_table

        $deleteQueryCmd = "DELETE FROM scholarship_eligibility_table WHERE scholarship_id= ?";
                    log_message('debug', 'query cmd is ' . $deleteQueryCmd);
                    $query = $dbHandle->query($deleteQueryCmd, array($scholarship_id));

        if(isset($eligibility) && count($eligibility) > 0){
            foreach($eligibility as $key=>$val){
                $data =array();
                $data = array(
                        'scholarship_id'=>$scholarship_id,
                        'eligibility_criteria'=>$key,
                        'eligibility_criteria_values'=>$val
                        );
                $queryCmd = $dbHandle->insert_string('scholarship_eligibility_table',$data);
                //$queryCmd .= " on duplicate key update eligibility_criteria_values =  '".$val."'";
                $query = $dbHandle->query($queryCmd);
            }
        }
        return $this->xmlrpc->send_response($response);
    }

/****************************************************************
DIFFERENT PRODUCTS APIs
****************************************************************/
	/*
	* XXX common lib method
	*/
	function getBoardChilds($categoryId){
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		$categoryIdArray = array();
		$categoryIdString='';
		if($dbHandle == ''){
			log_message('error','getRecentEvent can not create db handle');
		}

		$queryCmd = ' SELECT t1.boardId AS lev1, t2.boardId as lev2, t3.boardId as lev3, t4.boardId as lev4 FROM categoryBoardTable AS t1 '.
				 'LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId '.
				 'LEFT JOIN categoryBoardTable AS t3 ON t3.parentId = t2.boardId '.
				 'LEFT JOIN categoryBoardTable AS t4 ON t4.parentId = t3.boardId WHERE t1.boardId = ?';

		log_message('debug', 'get board child query is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($categoryId));
		foreach ($query->result() as $row){

			if(!array_key_exists($row->lev1,$categoryIdArray) && !empty($row->lev1)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev1]=$row->lev1;
				$categoryIdString .= $row->lev1;

			}
			if(!array_key_exists($row->lev2,$categoryIdArray) && !empty($row->lev2)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev2]=$row->lev2;
				$categoryIdString .= $row->lev2;

			}
			if(!array_key_exists($row->lev3,$categoryIdArray) && !empty($row->lev3)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev3]=$row->lev3;
				$categoryIdString .= $row->lev3;

			}
			if(!array_key_exists($row->lev4,$categoryIdArray) && !empty($row->lev4)){
				if(strlen($categoryIdString)>0){
					$categoryIdString .= ' , ';
				}
				$categoryIdArray[$row->lev4]=$row->lev4;
				$categoryIdString .= $row->lev4;

			}
		}
		if(strlen($categoryIdString)==0){
			$categoryIdString .= $categoryId;
		}
		return $categoryIdString;
	}

/** EVENT **/

	/*
	* This function returns the event detail for a event ID
	*/
	function sgetEventDetailCMS($request){

		$parameters = $request->output_parameters();
		$appId=$parameters['0'];
		$eventId=$parameters['1'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		$queryCmd = 'select v.*,e.event_title,e.boardId subCategoryId,c.name subCategoryName,c.parentId categoryId,e.description,e.user_id,e.status_id,e.event_url,e.privacy,d.*, (select name from categoryBoardTable where boardId=c.parentId) categoryName from categoryBoardTable c,event e, event_venue v, event_date d where e.venue_id=v.venue_id and e.event_id=d.event_id and c.boardId=e.boardId and e.event_id=?';

		log_message('debug', 'getEventDetail query cmd is ' . $queryCmd);

		$query = $dbHandle->query($queryCmd, array($eventId));
		//will only have one row
		$response=array();
		foreach ($query->result_array() as $row){
			$response = array($row,'struct');
		}
		return $this->xmlrpc->send_response($response);
	}
/** EVENT END **/

/** FORUMS **/

	/*
	*	Get the popular topics across all board's for a given country
	*/
	function sgetPopularTopicsCMS($request){

		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$categoryId=$this->getBoardChilds($parameters['1']);
		$startFrom=$count=$parameters['2'];
		$count=$parameters['3'];
                $searchCriteria1=trim($parameters['4']);
                $searchCriteria2=trim($parameters['5']);
                $filter1=trim($parameters['6']);
		$showReportedAbuse=$parameters['7'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

                if($searchCriteria1!=''){
                   $addSearch1 = 'AND messageTable.msgTitle LIKE "%'.$dbHandle->escape_like_str($searchCriteria1).'%"';
                }else{
                   $addSearch1 = '';
                }

                if($searchCriteria2!=''){
                   $addSearch2 = 'AND t.displayname LIKE "%'.$dbHandle->escape_like_str($searchCriteria2).'%"';
                }else{
                   $addSearch2 = '';
                }

                if($filter1!=''){
                   if($filter1 == 1){
                        $addFilter1 = '';
                     }else{
                        $addFilter1 = 'AND messageTable.countryId = '.$dbHandle->escape($filter1);
                     }
                }else{
                   $addFilter1 = '';
                }

		if($showReportedAbuse=="true"){
					$queryCmd = 'select SQL_CALC_FOUND_ROWS m1.boardId,m1.threadId,m1.msgTitle,m1.msgTxt,m1.creationDate,m1.viewCount popularityView,t.displayname,t.userid userId,t.lastlogintime,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from messageTable m1,(select distinct boardId,threadId from messageTable where abuse>0 and status=0) m2, tuser t,userPointSystem upv where m1.boardId=m2.boardId and m1.threadId=m2.threadId and m1.parentId=0 and m1.userId=t.userid and m1.userId=upv.userId '. $addSearch1.' '.$addSearch2.' '.$addFilter1.' order by popularityView desc,creationDate asc LIMIT  ? , ? ';

		}else{
				$queryCmd = 'select SQL_CALC_FOUND_ROWS m1.boardId,m1.threadId,m1.msgTitle,m1.msgTxt,m1.creationDate,m1.viewCount popularityView,t.displayname,t.userid userId,t.lastlogintime,t.avtarimageurl userImage,(select level from userPointLevel where minLimit<=upv.userPointValue limit 1) level from messageTable m1,(select distinct boardId,threadId from messageTable where abuse=0 and status=0) m2, tuser t,userPointSystem upv where m1.boardId=m2.boardId and m1.threadId=m2.threadId and m1.parentId=0 and m1.userId=t.userid and m1.userId=upv.userId '. $addSearch1.' '.$addSearch2.' '.$addFilter1.' order by popularityView desc,creationDate asc LIMIT  ? , ? ';

		}

		log_message('debug', 'getPopularTopics query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array((int)$startFrom, (int)$count));
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
							array(
								'Count'=>array($row->count,'string'),
								'msgTitle'=>array($row->msgTitle,'string'),
								'msgThread'=>array($row->threadId,'string'),
								'msgTxt'=>array($row->msgTxt,'string'),
								'boardId'=>array($row->boardId,'string'),
                                                                'creationDate'=>array($row->creationDate,'string'),
								'DisplayName'=>array($row->displayname,'string'),
								'UserLevel'=>array($row->level,'string'),
								'userImage' => array($row->userImage,'string'),
								'popularityView' =>array($row->popularityView,'string'),
								'viewCount' => array($row->viewCount,'int'),
								'userId' => array($row->userId,'int')
							),'struct')
				   );//close array_push

		}
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		$totalRows = 0;
		foreach ($query->result() as $row) {
			$totalRows = $row->totalRows;
		}
		$mainArr = array();
		array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalCount'=>array($totalRows,'string'),
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

/** FORUMS END **/

	function sGetHeaderTabs($request)
	{
	   $parameters = $request->output_parameters();
	   $appID = $parameters['0'];
	   $userGroup = $parameters['1'];
           $userid = $parameters['2'];
	    
	   $smartModel = $this->load->model('smart/smartmodel');
       $mailerModel = $this->load->model('mailer/mailermodel');
	   $dbHandle = $this->_loadDatabaseHandle();
	   $queryCmd = "select * from usergroupTabs where usergroup = ?";
	   $query = $dbHandle->query($queryCmd, array($userGroup));
       if($userid == 1563){
           $tabs = '46,101,780,800,815,1023,1042';
           $selectedTab = '46';
       }
       elseif(($userGroupInfo = $mailerModel->isUserinGroup($userid)) && (($userGroup == 'user') || ($userGroup == 'cms' && ($userid == 31 || $userid == 11715822) ))) {
           $tabs = '25';
           $selectedTab = '25';
       }elseif($userid == 34){
           $tabs = $query->first_row()->tabs;
           $tabs=str_replace(',25','',$tabs);
           $selectedTab = 28;
       }elseif($userid == LMS_PORTING_USER_ID){
           // $tabs = LMS_PORTING_TAB_ID.','.PORTING_MIS_TAB_ID.','.LMS_PORTING_CUSTOM_LOCATION_ID.','.ADMIN_REPORT_TAB_ID;
           $tabs = LMS_PORTING_TAB_ID.','.PORTING_MIS_TAB_ID.','.ADMIN_REPORT_TAB_ID.','.OAF_PORTING_TAB_ID.','.OAF_MIS_PORTING_TAB_ID.','.PORTING_UPLOAD_CSV_TAB_ID;
           $selectedTab = LMS_PORTING_TAB_ID;
       }elseif($smartModel->canUserAccessSmartInterface($userid)){
           $tabs = SMART_DASHBOARD_TAB_ID.','.SMART_REPORT_TAB_ID.','.SMART_CLIENT_EXPECTATION_TAB_ID.','.SMART_CLIENT_LOGIN_TAB_ID.','.SMART_SODETAILS_TAB_ID.','.ACCESS_CLIENT_LISTING_DETAILS_TAB_ID;
           $selectedTab = SMART_DASHBOARD_TAB_ID;
       }
       else{
           $tabs = $query->first_row()->tabs;
           $tabs=str_replace(',25','',$tabs);
           $tabs=str_replace(',28','',$tabs);
           $tabs=str_replace(',46','',$tabs);
           $tabs=str_replace(',101','',$tabs);
	       $tabs=str_replace(',783','',$tabs);
           $selectedTab = $query->first_row()->selected_tab;
       }
        $response = array();
        $removeTabList = array(56);  // hide Fat Footer tab

        if ($userGroup == 'enterprise')
        {
            $naukriQuery = "select client_id from naukri_leads_subscription where client_id = ?";
            $result = $dbHandle->query($naukriQuery,array($userid))->row_array();
            if (!empty($result))
            {
                $tabs = $tabs.',1047';
            }
           
        }

        // access of CTP Response Delivery Criteria provided to shivani.puri@shiksha.com, amrita.warrier@shiksha.com, sheeuli@shiksha.com
        $rdcAccessUsers = array(1703074, 7953901, 1713947);
        if(in_array($userid, $rdcAccessUsers)) { 
            $tabs .= ',1050';
        }
    
        if(!empty($tabs)) {
            $tabsArray = array();
            $tabsArray = explode(',', $tabs);
	        $queryCmd = "select * from tabNames where tabId in (?) and tabId <> 8 order by tabOrder";
	        $query = $dbHandle->query($queryCmd, array($tabsArray));
	        $arrTabs = array();
	        foreach ($query->result() as $row)	   {

                if(in_array($row->tabId,$removeTabList)){
                    continue;
                }

	            array_push ($arrTabs, array(
		 		array(
				      'tabId' => array($row->tabId,'string'),
				      'tabName' => array($row->tabName,'string'),
				      'tabUrl' => array($row->tabUrl,'string')
				   ),'struct')
			    );
	        }
	  
    	    array_push($response,array(
	            array(
		            'selectedTab'=>array($selectedTab,'string'),
		            'tabs' => array($arrTabs,'struct')
	            ),'struct')
	        );
        }
       $response1 = array($response,'struct');
	   return $this->xmlrpc->send_response($response1);
    }


       //Server API for Enterprise User Registration
       function saddEnterpriseUser($request)
       {
           $parameters = $request->output_parameters();
           $userData = $parameters['0'];

           $appId = $userData['appId'];
           $userid = $userData['userid'];
           $sumsUserId = $userData['sumsUserId'];
           $busiCollegeName = $userData['busiCollegeName'];
           $busiType = $userData['busiType'];
           $contactAddress = $userData['contactAddress'];
           $pincode = $userData['pincode'];
           $categories = $userData['categories'];
           $executiveName = $userData['executiveName'];
	       $newsletteremail = $userData['vianewsletteremail'];
           
           //connect DB
           $dbHandle = $this->_loadDatabaseHandle('write');
           $data = array (
           			"userId"=>$userid,
           			"businessCollege"=>$busiCollegeName,
           			"businessType"=>$busiType,
           			"contactAddress"=>$contactAddress,
           			"pincode"=>$pincode,
           			"categories"=>$categories,
           			"executiveName"=>$executiveName
           			);

            if($userid > 0 && is_numeric($userid)) {

                $queryCheck = 'SELECT count(*) as number FROM shiksha.enterpriseUserDetails where userId = ?';
                $query = $dbHandle->query($queryCheck, array($userid));
                foreach ($query->result() as $row){
                    $number = $row->number;
                }

                if($number == 0){
               	    $queryCmd = $dbHandle->insert_string('enterpriseUserDetails',$data);
               	    error_log($queryCmd);
               	    $query = $dbHandle->query($queryCmd);
                }
                else {
                    return $this->xmlrpc->send_response(NULL);
                }
            } 
            else {
                return $this->xmlrpc->send_response(NULL);
            }

                // The Free Listing Derived Product to be name 'Free Trial and Basic Listings' Only
		        $prodClient = new Sums_product_client();
                $result = $prodClient->getFreeDerivedId($appId);

		        $dervdProdId = $result['derivedProdId'];
                $param['derivedProdId'] = $dervdProdId;
                $param['derivedQuantity'] = 1000; // Changing free bronze quantity to 1000 from 1 for listing revamp stage-1
           	    $param['clientUserId'] = $userid;
                $param['sumsUserId'] = $sumsUserId;
           	$param['subsStartDate'] = date(DATE_ATOM);
		error_log("Array to addFreeSubscription ".print_r($param,true));
           	//$param['subsEndDate'] = date(DATE_ATOM,mktime(0, 0, 0, date("m")+1, date("d"), date("Y")));
                $objSumsClient = new Subscription_client();
                $respSubs = $objSumsClient->addFreeSubscription(1,$param);

                $response = array($respSubs,'struct');
           	error_log(print_r($response,true).'ReSPONSE');
            
        if(!$newsletteremail){
            $userProfileLib = $this->load->library('userProfile/UserProfileLib');
            $userProfileLib->userUnsubscribeMapping($userid,'true',5);
            error_log("####user_unsubscribe_mapping updated ".print_r($userid,true));
        }
				
        return $this->xmlrpc->send_response($response);
       }

	function sgetInstituteList($request)
	{
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$city_id = $parameters['1'];
                $usergroup = $parameters['2'];
                $userid = $parameters['3'];
                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();

                if(($usergroup=='cms') && ($userid < 1000)){
                        $addUserid = '';
                    }else{
                        $addUserid = 'AND I.username = ?';
                    }

		if($city_id <=0){
                    $queryCmd = 'SELECT * from institute I, institute_location_table L, listings_main M WHERE I.institute_id = L.institute_id AND M.status = "live" AND M.listing_type="institute" AND M.listing_type_id=I.institute_id '.$addUserid.' group by I.institute_id ORDER BY TRIM(I.institute_name)';
		}
		else{
                    $queryCmd = 'SELECT * from institute I, institute_location_table L, listings_main M WHERE I.institute_id = L.institute_id AND L.city_id ='.$dbHandle->escape($city_id).' AND M.status = "live" and M.listing_type="institute" AND M.listing_type_id=I.institute_id '.$addUserid.'  group by I.institute_id ORDER BY TRIM(I.institute_name)';
		}
		error_log($queryCmd);
		log_message('debug', 'query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($userid));
		$counter = 0;
		$msgArray = array();
		foreach ($query->result() as $row){
			array_push($msgArray,array(
						array(
							'instituteID'=>array($row->institute_id,'string'),
							'instituteName'=>array($row->institute_name,'string'),
							'cityId'=>array($row->city_id,'string'),
							'countryId'=>array($row->country_id,'string')
						     ),'struct'));//close array_push

		}
		$response = array($msgArray,'struct');
		return $this->xmlrpc->send_response($response);
	}


        function sgetCitiesWithCollege($request)
        {
            $parameters = $request->output_parameters();
            $appId = $parameters['0'];
            $countryId = $parameters['1'];
            $usergroup = $parameters['2'];
            $userid = $parameters['3'];
            //connect DB
            $dbHandle = $this->_loadDatabaseHandle();

            if(($usergroup=='cms') && ($userid < 1000)){
                $addUserid = '';
            }else{
                $addUserid = "AND M.username = ".$dbHandle->escape($userid);
            }

            $queryCmd = "select distinct(L.city_id),city_name from institute I,institute_location_table L,countryCityTable C,listings_main M where I.institute_id=L.institute_id AND L.country_id = ? AND L.city_id = C.city_id AND M.listing_type_id=I.institute_id AND M.listing_type='institute' AND M.status ='live' $addUserid order by trim(city_name)";
            error_log("Listing Server : ".$queryCmd);
            $query = $dbHandle->query($queryCmd, array($countryId));
            $counter = 0;
            $msgArray = array();
            foreach ($query->result() as $row)
            {
	      array_push($msgArray,array(
		 array(
		    'cityID'=>array($row->city_id,'string'),
		    'cityName'=>array($row->city_name,'string')
		 ),'struct'));//close array_push

	   }
	   $response = array($msgArray,'struct');
	   return $this->xmlrpc->send_response($response);
       }

       function getSearchSubCategories($request)
       {
	  $parameters= $request->output_parameters();
	  $dbHandle = $this->_loadDatabaseHandle();

	  $queryCmd = "select boardId,name from categoryBoardTable where parentId = 1";
	  $result = $dbHandle->query($queryCmd);
	  $arrCategories = array();
	  $arrCategories['foreign'] = array("Foreign Education",'string');
	  $arrCategories['testprep'] = array("Test Prepartion",'string');

	  foreach ($result->result() as $row)
	  {
	     $arrCategories["Category-".$row->boardId]= array($row->name,'string');
	  }
	  $response = array ($arrCategories,'struct');
	  return $this->xmlrpc->send_response($response);

       }

       function sEditNotification($request)
       {
           $parameters = $request->output_parameters();
           $appId = $parameters['0'];
           $formVal = $parameters['1'];
           $eligibility = $parameters['2'];
           //connect DB
           $dbHandle = $this->_loadDatabaseHandle('write');
           $data = array(
               'admission_notification_name'=>$formVal['admission_notification_name'],
               'short_desc'=>$formVal['short_desc'],
               'admission_year'=>$formVal['admission_year'],
               'application_brochure_start_date'=>$formVal['application_brochure_start_date'],
               'application_brochure_end_date'=>$formVal['application_brochure_end_date'],
               'application_end_date'=>$formVal['application_end_date'],
               'application_procedure'=>$formVal['application_procedure'],
               'fees'=>$formVal['fees'],
               'contact_email'=>$formVal['contact_email'],
               'contact_name'=>$formVal['contact_name'],
               'address' =>$formVal['contact_address'],
               'contact_fax' =>$formVal['contact_fax'],
               'contact_email' => $formVal['contact_email'],
               'contact_cell'=>$formVal['contact_cell'],
               'entrance_exam'=>$formVal['entrance_exam']
           );
           $where = "admission_notification_id = ".$formVal['admission_notification_id'];
           $queryCmd = $dbHandle->update_string('admission_notification',$data,$where);
           $query = $dbHandle->query($queryCmd);

           $format = 'DATE_ATOM';
           $data = array(
               'listing_title'=>$formVal['admission_notification_name'],
               'short_desc'=>$formVal['short_desc'],
               'threadId'=>$formVal['threadId'],
               'last_modify_date'=>standard_date($format,time()),
               'requestIP'=>$formVal['requestIP']
           );

           $where = "listing_type_id=".$formVal['admission_notification_id']." and listing_type='notification'";
           $queryCmd = $dbHandle->update_string('listings_main',$data,$where);
           $query = $dbHandle->query($queryCmd);
           $response = array($query,int);
           $response = array(array
           ('QueryStatus'=>$query,
           'type_id'=>$formVal['admission_notification_id'],
           'listing_type'=>"notification",
           'title'=>$formVal['admission_notification_name']),'struct');

           $queryCmd = "delete from admission_notification_category_table where admission_notification_id = ?";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           $catArr = array();
           if(isset($formVal['category_id']) && $formVal['category_id'] != ""){
               $categories = $formVal['category_id'];
               $catArr = explode(',',$categories);
               $numOfCats = count($catArr);
               for($i = 0; $i < $numOfCats ; $i++){
                   $queryCmd = "INSERT into admission_notification_category_table (admission_notification_id,category_id) values (?,?) on duplicate key update category_id =?";
                   $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id'], $catArr[$i], $catArr[$i]));
               }
           }

           $queryCmd = "delete from institute_examinations_mapping_table where admission_notification_id = ?";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           $queryCmd = 'INSERT into institute_examinations_mapping_table (institute_id,admission_notification_id) values (?,?)';
           $query = $dbHandle->query($queryCmd, array($formVal['institute_id'], $formVal['admission_notification_id']));

           $admission_not_id = $formVal['admission_notification_id'];

           $deleteQueryCmd = "DELETE FROM listingExamMap WHERE type='notification' and typeId=? and typeOfMap='required' ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmd);
           $query = $dbHandle->query($deleteQueryCmd, array($admission_not_id));

           $deleteQueryCmdOther = "DELETE FROM othersExamTable WHERE listingType='notification' and listingTypeId=? and typeOfMap='required' ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmdOther);
           $query = $dbHandle->query($deleteQueryCmdOther, array($admission_not_id));

           $deleteQueryCmdCenter = "DELETE FROM other_exam_centres_table WHERE other_exam_id in (select other_exam_id from othersExamTable where listingType='notification' and listingTypeId=? and typeOfMap='required') ";
           log_message('debug', 'query cmd is ' . $deleteQueryCmdCenter);
           $query = $dbHandle->query($deleteQueryCmdCenter, array($admission_not_id));


           if($formVal['entrance_exam'] == "yes"){
               $this->load->model('ExamModel','',$dbConfig);
               if(isset($formVal['tests_required']) && strlen($formVal['tests_required']) > 0 )
               {
                   $examsArr = explode(",",$formVal['tests_required']);
                   $this->ExamModel->makeEntityExamsMapping($admission_not_id, $examsArr,'notification','required');
               }
               if($formVal['tests_required_other'] == 'true'){
                   $examData =array();
                   $examData['listingType'] = 'notification';
                   $examData['listingTypeId'] = $admission_not_id;
                   $examData['typeOfMap'] = 'required';
                   $examData['exam_name'] = $formVal['exam_name'];
                   $examData['exam_date'] = $formVal['exam_date'];
                   $examData['exam_desc'] = $formVal['exam_desc'];
                   $examData['exam_duration'] = $formVal['exam_duration'];
                   $examData['exam_timings'] = $formVal['exam_timings'];
                   $examData['numOfCentres'] = $formVal['numOfCentres'];
                   for($i = 0 ; $i < $formVal['numOfCentres']; $i++)
                   {
                       $examData['address_line1'.$i] = $formVal['address_line1'.$i];
                       $examData['address_line2'.$i] = $formVal['address_line2'.$i];
                       $examData['city_id'.$i] = $formVal['city_id'.$i];
                       $examData['country_id'.$i] = $formVal['country_id'.$i];
                       $examData['zip'.$i] = $formVal['zip'.$i];
                   }
                   $newExamId = $this->ExamModel->insertOtherExam($examData);
               }
           }


           $queryCmd = "delete from admission_notification_eligibility_table where admission_notification_id = ? ";
           $query = $dbHandle->query($queryCmd, array($formVal['admission_notification_id']));
           if(isset($eligibility) && count($eligibility) > 0){
               foreach($eligibility as $key=>$val){
                   $data =array();
                   $data = array(
                       'admission_notification_id'=>$formVal['admission_notification_id'],
                       'eligibility_criteria'=>$key,
                       'eligibility_criteria_values'=>$val
                   );
                   $queryCmd = $dbHandle->insert_string('admission_notification_eligibility_table',$data);
                   $query = $dbHandle->query($queryCmd);
               }
           }
           return $this->xmlrpc->send_response($response);
       }

       function sRemoveNotificationDoc($request)
       {
			$parameters = $request->output_parameters();
			//connect DB
			$dbHandle = $this->_loadDatabaseHandle('write');
			$docId = $parameters['1'];
			$notificationId = $parameters['2'];
			$response = array();
			$queryCmd = "delete from admission_notification_doc_table where doc_id = ? and admission_notification_id= ?";
			$result = $dbHandle->query($queryCmd, array($docId, $notificationId));
			$response = array($query,'int');
			return $this->xmlrpc->send_response($response);
       }

       function sRemoveScholarshipDoc($request){
		$parameters = $request->output_parameters();
                error_log("Schol DOC remove SERVER RECEIVED ARRAY ".print_r($parameters,true));
		$appID = $parameters['0'];
                $removeData = $parameters['1'];

                $scholarshipId = $removeData['scholarshipId'];
                $docId = $removeData['docId'];


                //connect DB
                $dbHandle = $this->_loadDatabaseHandle('write');

                $queryCmd = 'DELETE FROM scholarship_doc_table WHERE scholarship_id=? AND doc_id=?';
		log_message('debug', 'query cmd is ' . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($scholarshipId, $docId));
                $scholResponse = array(array('QueryStatus'=>$query,'int'),'struct');

                return $this->xmlrpc->send_response($scholResponse);
	}
       function getNotificationEvents($request)
       {
       		$parameters = $request->output_parameters();
			$dbHandle = $this->_loadDatabaseHandle();
			$notificationId = $parameters['1'];
			$response = array();
			$queryCmd = "select event_id from event where listing_type_id = ? and listingType = 'notification'";
			error_log($queryCmd);
			$result = $dbHandle->query($queryCmd, array($notificationId));

			foreach($result->result() as $row)
			{
				array_push($response,array($row->event_id));
			}
			$response = array($response,'struct');
			return $this->xmlrpc->send_response($response);
       }

       function getEnterpriseUserDetails($request)
       {
           $parameters = $request->output_parameters();
	   $dbHandle = $this->_loadDatabaseHandle();
           $userId = $parameters['1'];

           $queryCmd = "select CAST((select country from shiksha.tuser t where t.userid=?) AS SIGNED INTEGER) as countryId";
           error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));
           if ($result->result() != NULL) {
               $countryId = $result->first_row()->countryId;
           }

           $queryCmd = "select CAST((select city from shiksha.tuser t where t.userid=?) AS SIGNED INTEGER) as cityId";
           error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));
           if ($result->result() != NULL) {
               $cityId = $result->first_row()->cityId;
           }

           if($countryId != NULL && $countryId !=0){
               $countryClause = "(select name from shiksha.countryTable where countryId=".$dbHandle->escape($countryId).") as countryName";
           }else{
               $countryClause = "(select country from shiksha.tuser where shiksha.tuser.userid=".$dbHandle->escape($userId).") as countryName";
           }

           if($cityId != NULL && $cityId !=0){
               $cityClause = "(select city_name from shiksha.countryCityTable where city_id=".$dbHandle->escape($cityId).") as cityName";
           }else{
               $cityClause = "(select city from shiksha.tuser where shiksha.tuser.userid=".$dbHandle->escape($userId).") as cityName";
           }

           $queryCmd = "select t.userid, t.displayname,t.email,t.city,t.country,t.mobile,t.firstname,e.businessCollege,e.businessType,e.contactAddress,e.pincode,e.categories,$cityClause,$countryClause from tuser t, enterpriseUserDetails e where t.userid = e.userId and t.userid = ?";
           error_log_shiksha($queryCmd);
           $result = $dbHandle->query($queryCmd, array($userId));

           $response = array (
               "userId"=>array($result->first_row()->userid,'string'),
               "displayname"=>array($result->first_row()->displayname,'string'),
               "email"=>array($result->first_row()->email,'string'),
               "city"=>array($result->first_row()->city,'string'),
               "country"=>array($result->first_row()->country,'string'),
               "businessCollege"=>array($result->first_row()->businessCollege,'string'),
               "businessType"=>array($result->first_row()->businessType,'string'),
               "contactName"=>array($result->first_row()->firstname,'string'),
               "contactAddress"=>array($result->first_row()->contactAddress,'string'),
               "pincode"=>array($result->first_row()->pincode,'string'),
               "mobile"=>array($result->first_row()->mobile,'string'),
               "cityName"=>array($result->first_row()->cityName,'string'),
               "countryName"=>array($result->first_row()->countryName,'string'),
               "categories"=>array($result->first_row()->categories,'string')
           );
           $response = array($response,'struct');
           return $this->xmlrpc->send_response($response);
       }


       function updateEnterpriseUserDetails($request)
       {
			$parameters = $request->output_parameters();
			$vals = $parameters['1'];
			$dbHandle = $this->_loadDatabaseHandle('write');

			$format = DATE_ATOM;
			$data = array(
					'mobile'=>$vals['mobile'],
					'country'=>$vals['country'],
					'city'=>$vals['city'],
					'firstname'=>$vals['contactName'],
					'lastModifiedOn'=>date($format,time())
			);
			$where = "userid=".$vals['userId'];
			$queryCmd = $dbHandle->update_string('tuser',$data,$where);
			$query = $dbHandle->query($queryCmd);

			$data = array(
					'contactAddress'=>$vals['contactAddress'],
					'pincode'=>$vals['pincode'],
					'categories'=>$vals['categories']
				);
			$where = "userId=".$vals['userId'];
			$queryCmd = $dbHandle->update_string('enterpriseUserDetails',$data,$where);
			$query = $dbHandle->query($queryCmd);
			$response = array("query"=>array($query,'int'));
			return $this->xmlrpc->send_response($response);
       }

       function changePassword($request)
       {
       		$parameters = $request->output_parameters();
			$vals = $parameters['1'];
			$dbHandle = $this->_loadDatabaseHandle('write');
			$format = DATE_ATOM;
			$data = array(
					'ePassword'=>sha256($vals['newPassword'])
			);
			$where = "userid=".$vals['userId']." and ePassword='".sha256($vals['oldPassword'])."'";
			$queryCmd = $dbHandle->update_string('tuser',$data,$where);
			$query = $dbHandle->query($queryCmd);
            $usermodel = $this->load->model('user/usermodel');
            $usermodel->trackPasswordChange($vals['userId']);
			$response = array($query,'int');
			return $this->xmlrpc->send_response($response);
       }

       function updateUserGroup($request)
       {
	       	$parameters = $request->output_parameters();
		$dbHandle = $this->_loadDatabaseHandle('write');
                $data = unserialize(base64_decode($parameters['1']));
                $data['usergroup']="enterprise";
                $where = "userid=".$data['userid'];
	       	$queryCmd = $dbHandle->update_string('tuser',$data,$where);
	       	$query = $dbHandle->query($queryCmd);
	       	$response = array($query,'int');
	       	return $this->xmlrpc->send_response($response);
       }

       function getViewCountForUserFedListings($request)
       {
       		$parameters = $request->output_parameters();
	       	$userId = $parameters['1'];
		$dbHandle = $this->_loadDatabaseHandle();
	       	$queryCmd = "select sum(viewCount) views from listings_main where username = ? and status ='live'";
	       	$query = $dbHandle->query($queryCmd, array($userId));

			$view_count = (int) $query->first_row()->views;

	       	$response = array($view_count,'int');

	       	return $this->xmlrpc->send_response($response);
       }
	function sgetcountofMedia($request)
{

			$parameters = $request->output_parameters();
			//connect DB
			$dbHandle = $this->_loadDatabaseHandle();
			$typeofmedia = $parameters['1'];
			$startDate = $parameters['3'];
			$endDate = $parameters['4'];
			$response = array();
			$queryCmd = "select userid as userid from tuser where usergroup not in('cms','enterprise')";
			if($startDate != 0 && $endDate != 0){
			    $queryCmd .= " and usercreationDate between " .$dbHandle->escape($startDate) ." and ".$dbHandle->escape($endDate);
            }
			$query = $dbHandle->query($queryCmd);
			$count = $query->num_rows();
			return $this->xmlrpc->send_response($count);

}


	function sdeleteMediaData($request)
       {
            $parameters   = $request->output_parameters();
            //connect DB
            $dbHandle     = $this->_loadDatabaseHandle('write');
            $type         = $parameters['1'];
            $userids      = $parameters['2'];
            $response     = array();
            $userIdsArray = array();
            $userIdsArray = explode(',', $userids);

            $queryCmd = "insert into tuser_deleted(select *, ? from tuser where userid in( ? ))";
            $query    = $dbHandle->query($queryCmd, array($type,$userIdsArray));
            if($dbHandle->affected_rows() > 0) {
                if($type == "user") {
                    $queryCmd = "delete from tuser where userid in( ? )";
                }
                if($type == "image") {
                    $queryCmd = "update tuser set avtarimageurl = '/public/images/photoNotAvailable.gif' where userid in( ? )";
                }
                $query = $dbHandle->query($queryCmd, array($userIdsArray));
               	if($dbHandle->affected_rows() > 0)
				    $response = 1;
				else
				    $response = 0;
				return $this->xmlrpc->send_response($response);
            }
            $response = 0;
			return $this->xmlrpc->send_response($response);
       }

	function sgetMediaData($request)
       {
            $parameters  = $request->output_parameters();
            //connect DB
            $dbHandle    = $this->_loadDatabaseHandle();
            $typeofmedia = $parameters['1'];
            $start       = $parameters['2'];
            $count       = $parameters['3'];
            $startDate   = $parameters['4'];
            $endDate     = $parameters['5'];
            $response    = array();

			$queryCmd = "select userid,email,displayname,avtarimageurl,usercreationdate from tuser where usergroup not in('cms','enterprise')";

            if($startDate != 0 && $endDate != 0){
                $queryCmd .= " and usercreationDate between ".$dbHandle->escape($startDate)." and ".$dbHandle->escape($endDate);
            }
            $queryCmd .= "limit ? , ? ";
            $query = $dbHandle->query($queryCmd, array($start, $count));
            $msgArray = array();
            foreach ($query->result_array() as $row){
                array_push($msgArray,array($row,'struct'));
            }
            $response = array($msgArray,'struct');
			return $this->xmlrpc->send_response($response);
       }

    function getReportedChangesForBlogs($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $type = $parameters['1'];
        $typeId = $parameters['2'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'SELECT trc.*, bt.blogTitle, bt.blogType from tReportChanges trc INNER JOIN blogTable bt on bt.blogId = trc.listing_type_id WHERE  trc.listing_type = ?';
        if(strlen($typeId) > 0){
            $addWhere = " and trc.listing_type_id = ".$dbHandle->escape($typeId);
        }
        $queryCmd  .= " $addWhere";
        $query = $dbHandle->query($queryCmd, array($type));
        $counter = 0;
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'email'=>array($row->email,'string'),
                            'blogTitle'=>array($row->blogTitle,'string'),
                            'blogType'=>array($row->blogType,'string'),
                            'comments'=>array($row->comment,'string'),
                            'type'=>array($row->listing_type,'string'),
                            'typeId'=>array($row->listing_type_id,'string'),
                            'submit_date'=>array($row->submit_date,'string')
                            ),'struct'));//close array_push

        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function getReportedChangesById($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $changeId = $parameters['1'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'SELECT * from tReportChanges trc WHERE  trc.id = ?';
        $query = $dbHandle->query($queryCmd, array($changeId));
        $counter = 0;
        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'id'=>array($row->id,'string'),
                            'email'=>array($row->email,'string'),
                            'comments'=>array($row->comment,'string'),
                            'type'=>array($row->listing_type,'string'),
                            'typeId'=>array($row->listing_type_id,'string'),
                            'submit_date'=>array($row->submit_date,'string')
                            ),'struct'));//close array_push

        }
        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

function saddMainCollegeLink($request)
{
    $parameters = $request->output_parameters();
    //connect DB
    $dbHandle   = $this->_loadDatabaseHandle('write');
    $keyId      = $this->getKeyPageId($parameters[1],$parameters[2],$parameters[3],$parameters[4],$parameters[5],$parameters[6]);

    $paramsArray   = array();
    $paramsArray[] = $keyId;
    $paramsArray[] = $parameters[7];
    $paramsArray[] = $parameters[9];
    $paramsArray[] = $parameters[10];
    $paramsArray[] = $parameters[8];

    if($parameters[8] == 'university'){
	   $queryCmd="select count(*) count from ".$dbHandle->escape_str($this->enterpriseconfig->pageCollegeTable)." where KeyId=? and listing_type_id=? and StartDate >=? and EndDate <=? and EndDate>now() and listing_type=? and status= ? ";
       $paramsArray[] = ENT_SA_PRE_LIVE_STATUS;
    }else{
	   $queryCmd="select count(*) count from ".$dbHandle->escape_str($this->enterpriseconfig->pageCollegeTable)." where KeyId=? and listing_type_id=? and StartDate >=? and EndDate <=? and EndDate>now() and listing_type=? and status='live'";
    }
    
    $query = $dbHandle->query($queryCmd, $paramsArray);
    unset($paramsArray);
	foreach($query->result() as $row)
	{
		$count = $row->count;
		if($count >= 1)
		{
			$response = array(
					array(
						'result'=>-1,
						'error'=>'Already set for given criteria.'
					     ),
					'struct');
			return $this->xmlrpc->send_response($response);
		}
	}

    $paramsArray   = array();
    $paramsArray[] = $keyId;
    $paramsArray[] = $parameters[7];
    $paramsArray[] = $parameters[9];
    $paramsArray[] = $parameters[10];
    $paramsArray[] = $parameters[11];
    $paramsArray[] = $parameters[8];

	if($parameters[8] == 'university'){
	    $queryCmd = "INSERT INTO ".$this->enterpriseconfig->pageCollegeTable."  (KeyId,listing_type_id,StartDate,EndDate,subscriptionId,listing_type,status) VALUES (?,?,?,?,?,?,?) ";
        $paramsArray[] = ENT_SA_PRE_LIVE_STATUS;
	}else{
	    $queryCmd = "INSERT INTO ".$this->enterpriseconfig->pageCollegeTable."  (KeyId,listing_type_id,StartDate,EndDate,subscriptionId,listing_type) VALUES (?,?,?,?,?,?) ";
	}
    
    log_message('debug', 'Insert query cmd is ' . $queryCmd);
    $query             = $dbHandle->query($queryCmd, $paramsArray);
    $mainCollegeLinkId = $dbHandle->insert_id();
    $response          = array(
                			array(
                				'result'=>$mainCollegeLinkId,
                			     ),
                		'struct');
    unset($paramsArray);
	return $this->xmlrpc->send_response($response);

    }

function sgetListingsByClient($request)
{
    $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $userArr = $parameters['1'];
        $userid = $userArr['userid'];
        $startFrom=$userArr['startFrom'];
        $countOffset=$userArr['countOffset'];
	$countryRequested=$userArr['countryRequested'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();

	if($countryRequested == 'abroad'){
	    $queryCmd = "SELECT lm.listing_id,lm.listing_type,lm.listing_type_id,lm.listing_title,lm.expiry_date,lm.submit_date,lm.last_modify_date from listings_main lm ";
	    $queryCmd .= "where lm.username = ? and lm.status='".ENT_SA_PRE_LIVE_STATUS."' ";
	    $queryCmd .= "and lm.listing_type in ('university') ";
	    $queryCmd .= "ORDER BY lm.listing_type desc,lm.last_modify_date desc LIMIT ".$startFrom.','.$countOffset;
	}else{
	    $queryCmd = "SELECT distinct lm.listing_id,lm.listing_type,lm.listing_type_id,lm.listing_title,lm.expiry_date,lm.submit_date,lm.last_modify_date from listings_main lm, institute_location_table ilt ";
	    $queryCmd .= "where lm.username = ? and lm.status='live' and lm.listing_type_id=ilt.institute_id ";
	    $queryCmd .= "and ilt.country_id = 2 and lm.listing_type in ('institute') and lm.status=ilt.status ";
	    $queryCmd .= "ORDER BY lm.listing_type desc,lm.last_modify_date desc LIMIT ".$startFrom.",".$countOffset;
	}
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd, array($userid));

        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'listing_id'=>array($row->listing_id,'string'),
                            'listing_type'=>array($row->listing_type,'string'),
                            'listing_type_id'=>array($row->listing_type_id,'string'),
                            'listing_title'=>array($row->listing_title,'string'),
                            'expiry_date'=>array($row->expiry_date,'string'),
                            'submit_date'=>array($row->submit_date,'string'),
                            'last_modify_date'=>array($row->last_modify_date,'string')
                            ),'struct'));//close array_push
        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

function sgetMainInstitutesByClient($request)
{
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $userArr = $parameters['1'];
        $userid = $userArr['userid'];
	$countryRequested = $userArr['countryRequested'];
        if($userid == "") {
            return $this->xmlrpc->send_response("");
        }

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
	if($countryRequested == 'abroad'){
	    $queryCmd = "SELECT lm.listing_type_id, lm.listing_title FROM listings_main lm ";
	    $queryCmd .= " WHERE lm.status = '".ENT_SA_PRE_LIVE_STATUS."' AND lm.listing_type = 'university' AND lm.username = ?";
	}else{
	    $queryCmd = "SELECT lm.listing_type_id, lm.listing_title FROM listings_main lm,institute_location_table ilt ";
	    $queryCmd .= " WHERE lm.status = 'live' AND lm.listing_type = 'institute' AND lm.username = ? ";
	    $queryCmd .= " AND lm.listing_type_id=ilt.institute_id AND ilt.country_id = 2 AND ilt.status=lm.status ";
	}
        error_log('query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd, array($userid));

        if($query->num_rows($queryCmd) <=0) {
            return $this->xmlrpc->send_response("");
        }
        
        $instituteIds = "";
        foreach ($query->result() as $row){
            $instituteArray[$row->listing_type_id]['title'] = $row->listing_title;
            $instituteIds .= ($instituteIds == "" ? "" : ",").$row->listing_type_id;
        }

        if($countryRequested == 'abroad'){
	    $sql = "SELECT `id`, `KeyId`, `listing_type_id` as `CollegeId`, `StartDate`, `EndDate`, `subscriptionId`  FROM `PageCollegeDb` WHERE `listing_type_id` in (".$instituteIds.") AND `status` = '".ENT_SA_PRE_LIVE_STATUS."' AND EndDate > NOW() ";
	}else{
	    $sql = "SELECT `id`, `KeyId`, `listing_type_id` as `CollegeId`, `StartDate`, `EndDate`, `subscriptionId`  FROM `PageCollegeDb` WHERE `listing_type_id` in (".$instituteIds.") AND `status` = 'live'  AND EndDate > NOW() ";
	}
        $query = $dbHandle->query($sql);
        if($query->num_rows($sql) <=0) {
            return $this->xmlrpc->send_response("");
        }
        
        $keyArray = array();
        foreach ($query->result() as $row){
            $instituteInfoArray[$row->CollegeId]['title'] = $instituteArray[$row->CollegeId]['title'];
            $instituteInfoArray[$row->CollegeId]['key'][] = array('id' => $row->id, 'KeyId' => $row->KeyId, 'StartDate' => date("d/m/Y", strtotime($row->StartDate)), "EndDate" => date("d/m/Y", strtotime($row->EndDate)));
            array_push($keyArray, $row->KeyId);
        }

        $keyArray = array_unique($keyArray);

        $this->load->builder('CategoryBuilder','categoryList');
        $this->load->builder('LocationBuilder','location');
        $categoryBuilder = new CategoryBuilder;
        $locationBuilder = new LocationBuilder;
        $categoryRepository = $categoryBuilder->getCategoryRepository();
        $locationRepository = $locationBuilder->getLocationRepository();

        $queryCmd = "SELECT `keyPageId`, `countryId`, `stateId`, `cityId`, `subCategoryId`, `categoryId` FROM `tPageKeyCriteriaMapping` WHERE `keyPageId` in (".implode(',', $keyArray).")";

	$query = $dbHandle->query($queryCmd);
        $keyInfoArray = array();
        foreach ($query->result() as $row){
            $keyInfoArray[$row->keyPageId]['countryName'] = $locationRepository->findCountry($row->countryId)->getName();

            if($row->stateId != 0) {
                $keyInfoArray[$row->keyPageId]['stateName'] = $locationRepository->findState($row->stateId)->getName();
            }

            if($row->cityId != 0) {
                $cityObj = $locationRepository->findCity($row->cityId);
                $keyInfoArray[$row->keyPageId]['cityName'] = $cityObj->getName();
                if(!isset($keyInfoArray[$row->keyPageId]['stateName']) || $keyInfoArray[$row->keyPageId]['stateName'] == "") {
                    $keyInfoArray[$row->keyPageId]['stateName'] = $locationRepository->findState($cityObj->getStateId())->getName();
                }
            }

            if(!isset($keyInfoArray[$row->keyPageId]['stateName']) || $keyInfoArray[$row->keyPageId]['stateName'] == "") {
               $keyInfoArray[$row->keyPageId]['stateName'] = "--";
            }

            if($row->categoryId != 0) {
                $category = $categoryRepository->find($row->categoryId);
                $keyInfoArray[$row->keyPageId]['catName'] = $category->getName();
            }

            if($row->subCategoryId != 0) {
                $subCategory = $categoryRepository->find($row->subCategoryId);
                $keyInfoArray[$row->keyPageId]['subCatName'] = $subCategory->getName();
                if(!isset($keyInfoArray[$row->keyPageId]['catName']) || $keyInfoArray[$row->keyPageId]['catName'] == "") {
                    $keyInfoArray[$row->keyPageId]['catName'] = $categoryRepository->find($subCategory->getParentId())->getName();
                }
            }
        }
        
        $responseArray[] = $instituteInfoArray;
        $responseArray[] = $keyInfoArray;
        $response = array($responseArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

function scancelSubscription($request)
{
	$parameters = $request->output_parameters();
	//connect DB
	$dbHandle = $this->_loadDatabaseHandle('write');
	$queryCmd="update ".$this->enterpriseconfig->pageCollegeTable." set status='deleted' where subscriptionId=?";
	$query = $dbHandle->query($queryCmd, array($parameters[1]));
	$response = array(
			array(
				'result'=>1,
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);

}
	private function makeListingExamsMapping($listingId, $exams, $listing){
		$this->load->model('ArticleModel');
		$this->ArticleModel->makeEntityExamsMapping($listingId, $exams,$listing);
            }

            function scheckUniqueTitle($request)
            {
                $parameters = $request->output_parameters();
                $appID = $parameters['0'];
                $title = $parameters['1']['title'];

                //connect DB
                $dbHandle = $this->_loadDatabaseHandle();
                $queryCmd = 'select institute_id from institute where institute_name = ?';
                log_message('debug', 'query cmd is ' . $queryCmd);
                $arrResults = $dbHandle->query($queryCmd, array($title));
                if(count($arrResults->result_array())>0)
                {
                    $instiResponse = array(array('result'=>1,'int'),'struct');
                }else{
                    $instiResponse = array(array('result'=>0,'int'),'struct');
                }

                return $this->xmlrpc->send_response($instiResponse);
            }

function supdateMainCollegeLink($request)
{
	$parameters = $request->output_parameters();
	$id = $parameters[0];
	$updateArray = $parameters[1];
	//connect DB
	$dbHandle = $this->_loadDatabaseHandle('write');
	$update = "";
			$i=0;
			foreach($updateArray as $key=>$val)
			{
				if($i==0)
				{
				$update.=" ".$key."='".$val."'";
				}
				else
				{
					$update.=", ".$key."='".$val."'";
				}
				$i++;
			}
	$queryCmd="update ".$this->enterpriseconfig->pageCollegeTable." set $update where Id=?";
	$query=$dbHandle->query($queryCmd, array($id));
	$response = array(
			array(
				'result'=>1,
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);
}

function getKeyPageId($testprepCat,$countryId,$cityId,$stateId,$categoryId,$subcategoryId)
{
    $keyId=0;
    $flag='shiksha';
    if($testprepCat!=0)
    {
        $flag='testprep';
        $categoryId=$testprepCat;
    }
	//connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    $queryCmd="select * from tPageKeyCriteriaMapping where countryId=? and cityId=? and stateId=? and subCategoryId=? and categoryId=? and flag=?";

    $query=$dbHandle->query($queryCmd, array($countryId, $cityId, $stateId, $subcategoryId, $categoryId, $flag));

    if(count($query->result_array())>0)
    {
        foreach($query->result_array() as $row)
        {
            $keyId=$row['keyPageId'];
        }
    }
    else
    {
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd="insert into tPageKeyCriteriaMapping (countryId,cityId,stateId,categoryId,subCategoryId,flag) values (?,?,?,?,?,?)";

    $query=$dbHandle->query($queryCmd, array($countryId, $cityId, $stateId, $categoryId, $subcategoryId, $flag));
        $keyId = $dbHandle->insert_id();
    }
    return $keyId;
}



function insertCreditCardDetails($request)
{
    $parameters = $request->output_parameters();
    $flag = $parameters[0];
    $userId = $parameters[1];
    $json = $parameters[2];
    $paymentId = $parameters[3];
    $partPaymentId = $parameters[4];
    $transactionId = $parameters[5];
    $PartiallypaidAmount = $parameters[6];
    $gateway = $parameters[7];
    $randNum = rand(10000000,99999999);
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
    while(1) {
        $queryCmd="select * from SUMS.CreditCardLogs where randomNum='".mysql_escape_string($randNum)."'";
        $query=$dbHandle->query($queryCmd);
        $count = 0;
        foreach ($query->result() as $row) {
            $count = 1;
        }
        if($count == 1) {
            $randNum = $randNum.rand(0,9);
        }else {
            break;
        }
    }


    $randNum = $transactionId."-".$paymentId."-".$randNum;

    
    $queryCmd="insert into SUMS.CreditCardLogs values('','".mysql_escape_string($randNum)."',".$dbHandle->escape($flag).",".$dbHandle->escape($userId).",'".mysql_escape_string($json)."',NOW(),NOW(),?,?,?,?,'NotAcknowledged',?)";
    
    
    $query=$dbHandle->query($queryCmd, array($paymentId, $partPaymentId, $randNum, $PartiallypaidAmount, $gateway));
    $q1 = "select max(id) as creditlog_id from SUMS.CreditCardLogs";
    $q2=$dbHandle->query($q1);
    foreach ($q2->result_array() as $row) {
        $credit_log_id = $row['creditlog_id'];
    }
    $response = array(
            array(
                'rand'=>$randNum,
                'credit_log_key'=>$credit_log_id
                ),
            'struct');
    return $this->xmlrpc->send_response($response);
}



function getCreditTransactionStatus($request)
{
    $parameters = $request->output_parameters();
	$TxnNum = $parameters[0];
	//connect DB
	$dbHandle = $this->_loadDatabaseHandle();
    $queryCmd="SELECT * FROM SUMS.CreditCardLogs C,SUMS.Payment P  where TxnId='".mysql_escape_string($TxnNum)."' AND P.Payment_Id = C.paymentId";

    
    $query=$dbHandle->query($queryCmd);
    
    $status = "";
    $paymentId = "";
    $partPaymentId = "";
    $Transaction_Id = "";
    $creditCardLogsId = "";
    if(count($query->result_array())>0)
    {
        foreach($query->result_array() as $row)
        {
            $status=$row['flag'];
            $partPaymentId=$row['partPaymentId'];
            $paymentId=$row['paymentId'];
	    $Transaction_Id =$row['Transaction_Id'];
	    $creditCardLogsId =$row['id'];
        }
    }
	$response = array(
			array(
				'status'=>$status,
				'paymentId'=>$paymentId,
				'partPaymentId'=>$partPaymentId,
				'Transaction_Id'=>$Transaction_Id,
				'creditCardLogsId'=>$creditCardLogsId
			     ),
			'struct');
	return $this->xmlrpc->send_response($response);
}


        function sgetListingsByClientAndType($request)
{
    $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $userArr = $parameters['1'];
        $userid = $userArr['userid'];
        $listingType = $userArr['listingType'];
        $startFrom=$userArr['startFrom'];
        $countOffset=$userArr['countOffset'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = 'SELECT listing_id,listing_type,listing_type_id,listing_title,expiry_date,submit_date,last_modify_date from listings_main where listings_main.username = ? and listing_type= ? and status in ("live","draft","queued") group by listing_type_id ORDER BY listing_type, version desc LIMIT ? , ?';
        log_message('debug', 'query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd, array($userid, $listingType, (int)$startFrom, (int)$countOffset));

        $msgArray = array();
        foreach ($query->result() as $row){
            array_push($msgArray,array(
                        array(
                            'listing_id'=>array($row->listing_id,'string'),
                            'listing_type'=>array($row->listing_type,'string'),
                            'listing_type_id'=>array($row->listing_type_id,'string'),
                            'listing_title'=>array($row->listing_title,'string'),
                            'expiry_date'=>array($row->expiry_date,'string'),
                            'submit_date'=>array($row->submit_date,'string'),
                            'last_modify_date'=>array($row->last_modify_date,'string')
                            ),'struct'));//close array_push
        }

        $response = array($msgArray,'struct');
        return $this->xmlrpc->send_response($response);
    }

    function checkAndConsumeActualSubscription($request){
        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $listings = unserialize(base64_decode($parameters['1']));
        $audit = unserialize(base64_decode($parameters['2']));
        $editedBy = $audit['editedBy'];
        $toConsumeArr = $audit['toConsumeArr'];
        $updateStatus = $audit['updateStatus'];

        $this->load->model('ListingModel');
	$dbHandle = $this->_loadDatabaseHandle('write');

        $this->load->library('Subscription_client');
        $subsObj = new Subscription_client();

        $resultSet= array();

        $numListings = count($listings);
        for($i = 0; $i < $numListings ; $i++){

            if( ($updateStatus[$i]['version']>0) && ($toConsumeArr[$i]['consumptionFlag']=='Yes') ){
                $query = 'select subscriptionId, username from listings_main where listing_type= ? and listing_type_id= ? and status in ("live") order by version desc limit 1';
                $result = $dbHandle->query($query, array($listings[$i]['type'], $listings[$i]['typeId']));
                if ($result->result() != NULL) {
                    $subscriptionId = $result->first_row()->subscriptionId;
                    $clientId = $result->first_row()->username;
                    $resp = $subsObj->consumeSubscription($appId,$subscriptionId,'-1',$clientId,$editedBy,'-1',$listings[$i]['typeId'],$listings[$i]['type'],'-1','-1');

                    array_push($resultSet,array(
                        'subscriptionId'=>$subscriptionId,
                        'listing_type'=>$listings[$i]['type'],
                        'listing_type_id'=>$listings[$i]['typeId']));
                    }
                }
            }
            $response = array(base64_encode(serialize($resultSet)),'string');
            return $this->xmlrpc->send_response($response);
        }

        function toConsumeActualSubscriptionCheck($request){
        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $listings = unserialize(base64_decode($parameters['1']));

        $this->load->model('ListingModel');
	$dbHandle = $this->_loadDatabaseHandle();

        $resultSet= array();

        $numListings = count($listings);
        for($i = 0; $i < $numListings ; $i++){
            $query = 'select count(*) as num, subscriptionId from listings_main where listing_type= ? and listing_type_id= ? and status="live" group by subscriptionId';
            $result = $dbHandle->query($query, array($listings[$i]['type'], $listings[$i]['typeId']));
            $count=0;
	    $subscriptionId=0;
            if ($result->result() != NULL) {
                $count = $result->first_row()->num;
		$subscriptionId=$result->first_row()->subscriptionId;
            }

            $query = 'select subscriptionId from listings_main where listing_type= ? and listing_type_id= ? and status="draft" order by listing_id desc';
            $result = $dbHandle->query($query, array($listings[$i]['type'], $listings[$i]['typeId']));
	    $newsubscriptionId=0;
            if ($result->result() != NULL) {
		$newsubscriptionId=$result->first_row()->subscriptionId;
            }

            if(($count==0)||(($newsubscriptionId!=$subscriptionId)&&($newsubscriptionId!=0))){
                $consumptionFlag = 'Yes';
            }else{
                $consumptionFlag = 'No';
            }

            array_push($resultSet,array(
                'consumptionFlag'=>$consumptionFlag,
                'listing_type'=>$listings[$i]['type'],
                'listing_type_id'=>$listings[$i]['typeId']));
            }
            $response = array(base64_encode(serialize($resultSet)),'string');
            return $this->xmlrpc->send_response($response);
        }
function getQuestionsPostedForEnterpriseUser($request){
			$parameters = $request->output_parameters();
			$appId=$parameters['0'];
			$userId=$parameters['1'];
			$startOffset=$parameters['2'];
			$countOffset=$parameters['3'];

			$this->load->model('ListingModel');
			$dbHandle = $this->_loadDatabaseHandle();
			$this->load->model('QnAModel');
			$responseString = $this->QnAModel->getQuestionsForEnterpriseUser($dbHandle,$appId,$userId,$startOffset,$countOffset);
			$response = array($responseString,'string');
			return $this->xmlrpc->send_response($response);
		}
        function getUserLevel($userId,$moduleName){
		$dbHandle = $this->_loadDatabaseHandle();
                $queryCmd = "SELECT Level FROM userPointLevelByModule upl, userpointsystembymodule ups WHERE upl.module = ups.moduleName and upl.module = ? and ups.userId = ? and ups.userPointValueByModule > upl.minLimit LIMIT 1";
                $query = $dbHandle->query($queryCmd, array($moduleName, $userId));
		$res = $query->result_array();
                return $res[0][Level];

        }

         function getCategoryBasedQuestion($userId){
                $appId = 12;
		$dbHandle = $this->_loadDatabaseHandle();
                $userExpertize = array();
                $userQuestion = array();
                    $queryCmd = "select mc1.categoryId,count(*) answerCount, c1.name, c1.boardId from messageTable m1, messageCategoryTable mc1, categoryBoardTable c1 where m1.userId=? and m1.msgId not in (select threadId  from messageDiscussion) and m1.parentId in (select msgId from qnaMasterQuestionTable ) and m1.fromOthers='user' and m1.status IN ('live','closed') and m1.threadId = mc1.threadId and m1.parentId!=0 and m1.mainAnswerId=0 and mc1.categoryId=c1.boardId and c1.parentId = 1 group by c1.name order by answerCount desc limit 2";
 		$query = $dbHandle->query($queryCmd, array($userId));
 		foreach ($query->result_array() as $rowTemp)
 		array_push($userExpertize,array($rowTemp,'struct'));
                foreach($userExpertize as $categoryId){
                    $queryCmd = "select mt.msgTxt,mt.threadId from messageTable as mt join messageCategoryTable mct on (mct.threadId = mt.threadId) and mct.categoryId= ? and mt.fromOthers='user' and mt.status IN ('live','closed')and mt.parentId=0 and mt.msgId not in (select threadId  from messageDiscussion) and mt.msgId in (select msgId from qnaMasterQuestionTable ) and userId!=? order by mct.threadId desc limit 0,2";
                    $query = $dbHandle->query($queryCmd, array($categoryId[0][categoryId],$userId));
                    foreach ($query->result_array() as $rowTemp1)
                    array_push($userQuestion,array($rowTemp1,'struct'));
                    return $userQuestion;

               }

               return true;
       }


       function updateTitle($request){
                $parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$msgId=$parameters['2'];
		$questionUserId=$parameters['3'];
		$msgTitle=addslashes($parameters['4']);
		$msgDescription=$parameters['5'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
              //  $this->load->model('UserPointSystemModel');
               // $res  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);
               $queryCmdUserGrp = "select usergroup from tuser where userid=?";
               $queryUserGrp = $dbHandle->query($queryCmdUserGrp, array($userId));
               $resUserGrp = $queryUserGrp->row();
               $queryCmdDesc = "select qetg.msgTitle from questionEditTitleLog as qetg where qetg.msgId=?";
               $query = $dbHandle->query($queryCmdDesc, array($msgId));
               $rowNum     = $query->num_rows();


                    if(!$rowNum){
                    $queryCmd1 = 'insert into questionEditTitleLog (`msgId`,`msgTitle`,`userId`,`questionUserId`,`flag`,`msgDesc`)values (?,?,?,?,"live",?)';
                    $query1 = $dbHandle->query($queryCmd1, array($msgId, $msgTitle, $userId, $questionUserId, $msgDescription));
                    }else{
                    //$queryCmd1 = 'update questionEditTitleLog set `msgTitle`="'.$msgTitle.'",`userId`="'.$userId.'",`flag`="live",`editDate`= NOW() where `msgId`="'.$msgId.'"';
                    $queryCmd1 = 'update questionEditTitleLog set `msgTitle`=?,`flag`="live",`editDate`= NOW() where `msgId`=?';
                    $query1 = $dbHandle->query($queryCmd1, array($msgTitle, $msgId));
                    }
                    $queryCmd2 = 'update messageTable set msgTxt=? where msgId=?';
                    $query2 = $dbHandle->query($queryCmd2, array($msgTitle, $msgId));

                    $queryCmd3 = 'insert into messageDiscussion (`threadId`,`description`)values (?,?)';
                    $query3 = $dbHandle->query($queryCmd3, array($msgId, $msgDescription));
                    $this->load->model('UserPointSystemModel');
                    $action1= $this->UserPointSystemModel->tuserReputationPointEntry($userId, 0.5,'addTitle',$dbHandle);

                    $resRP  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);

		//$query = $dbHandle->query($queryCmd);

                $queryCmdNew = "select email,displayname from tuser where userid=?";
                $queryNew = $dbHandle->query($queryCmdNew, array($userId));
                $resNew = $queryNew->row();

                $mainArr = array();
		array_push($mainArr,array(
				array(
					'resNew'=>array($resNew,'struct'),
					'resRP'=>array($resRP,'struct')
				),'struct')
		);
                $response = json_encode(array($mainArr,'struct'));
                return $this->xmlrpc->send_response($response);

        }


        function deleteQuestionInfoInLog($request){

            $parameters = $request->output_parameters();
	    $appID=$parameters['0'];
            $msgId=$parameters['1'];
            $userId =$parameters['2'];
            $msgTitle=$parameters['3'];
            $displayName =$parameters['4'];
            $status =$parameters['5'];

	    $dbHandle = $this->_loadDatabaseHandle('write');
            //$queryCmdDesc = "select md.description,qetg.msgTitle from messageDiscussion as md ,questionEditTitleLog as qetg where md.threadId = qetg.msgId and md.threadId=$msgId";
             $queryCmdDesc = "update questionEditTitleLog as qetg set `flag`=? where qetg.`msgId`=?";
             $query = $dbHandle->query($queryCmdDesc, array($status, $msgId));

             $queryCmd = "select email,displayname from tuser where userid=?";
             $query = $dbHandle->query($queryCmd, array($userId));
             $res = $query->row();
             $this->load->model('UserPointSystemModel');
             $resRP  = $this->UserPointSystemModel->getUserReputationPoints($dbHandle ,$userId);
             $mainArr = array();
		array_push($mainArr,array(
				array(
					'resNew'=>array($res,'$res'),
					'resRP'=>array($resRP,'struct')
				),'struct')
		);
             $response = json_encode(array($mainArr,'struct'));

             return $this->xmlrpc->send_response($response);
        }

        function getQuestionlogInfo($request){
                $parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$startFrom=$parameters['2'];
		$count=$parameters['3'];
		$module=$parameters['4'];
		$status=$parameters['5'];
		$userNameFieldData=$parameters['6'];
		$userLevelFieldData=$parameters['7'];

		$extraFilters = '';

        if($userLevelFieldData == "All")
        {
            $userLevelFieldData = '';
        }
		if($userNameFieldData!=''){
		    $extraFilters .= " and tu.displayname = ".$dbHandle->escape($userNameFieldData)." ";
		}
		if($userLevelFieldData!=''){
		    $extraFilters .= " HAVING reporterLevel = ".$dbHandle->escape($userLevelFieldData)." ";
		}

		$dbHandle = $this->_loadDatabaseHandle();
                if($status == "All")
		    $status = "live','deleted','draft";
		else if($status == "Live")
		    $status = "live";
		else if($status == "Delete")
		    $status = "deleted";
		else if($status == "Draft")
		    $status = "draft";

                 $infoArr = array();
                 //$queryCmd = "select qetl.*,md.description from questionEditTitleLog as qetl join messageDiscussion as md on (qetl.msgId = md.threadId) where qetl.flag!='live' order by qetl.editDate DESC LIMIT ".$startFrom." , ".$count;
                 $queryCmd = "select SQL_CALC_FOUND_ROWS qetl.*,tu.firstname,tu.displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = 'AnA' and userId = tu.userid LIMIT 1),'Beginner') reporterLevel from questionEditTitleLog as qetl left join tuser as tu on (tu.userid=qetl.userId) where qetl.flag in ('".$status."') $extraFilters order by qetl.editDate DESC LIMIT ".$startFrom." , ".$count;
		 $resultSet = $dbHandle->query($queryCmd);
                 $count =  $resultSet->num_rows();

                $queryCmd = 'SELECT FOUND_ROWS() as totalRows';
                $query = $dbHandle->query($queryCmd);
                foreach ($query->result() as $rowT) {
                        $totalQuestionNumber  = $rowT->totalRows;
                }

                 foreach ($resultSet->result_array() as $row){
                            $infoArr[msgId][] = $row[msgId];
                            $infoArr[msgTitle][] = $row[msgTitle];
                            $infoArr[userId][] = $row[userId];
                            $infoArr[questionUserId][] = $row[questionUserId];
                            $infoArr[editDate][] = date("d F, Y", strtotime($row[editDate]));
                            $infoArr[description][] = ($row[msgDesc]);
                            $infoArr[status][] = $row[flag];
                            $infoArr[displayname][] = $row[displayname];
                            $infoArr[firstname][] = $row[firstname];
			    $infoArr[userLevel][] = $row[reporterLevel];
                            $infoArr[questionUserLevel][] = $this->getUserLevel($row[questionUserId],$module);

                 }


                //if($status == "live','deleted','draft"){
                //}else{
                    //$totalQuestionNumber  = $count;
                //}
                $infoArr[totalQuestionNumber][] = $totalQuestionNumber;
                $response = json_encode(array($infoArr,'array'));
                return $this->xmlrpc->send_response($response);


        }

	/**
	*	Get the abuse list reported by all users
	*/
	function getAbuseList($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$startFrom=$parameters['2'];
		$count=$parameters['3'];
		$module=$parameters['4'];
		$status=$parameters['5'];

		$userNameFieldData=$parameters['6'];
		$userLevelFieldData=$parameters['7'];
		$reported=$parameters['8'];
        $resultUserAbuse=$parameters['9'];
        
        if($resultUserAbuse == "All")
        {
            $resultUserAbuse = '';
        }
        if($userLevelFieldData == "All")
        {
            $userLevelFieldData = '';
        }
		$extraFilters = ''; $levelFiler = '';
		if($userNameFieldData!='' && $reported=='reportedBy'){
		    $levelFiler .= "  HAVING reporterName = '".$userNameFieldData."'";
		}
		else if($userNameFieldData!='' && $reported=='reportedFor'){
		    $extraFilters .= " and tuser.displayname = '".$userNameFieldData."'";
		}
		if($userLevelFieldData!='' && $reported=='reportedBy'){
		    $extraFilters .= " and userLevel = '".$userLevelFieldData."'";
		}
		else if($userLevelFieldData!='' && $reported=='reportedFor'){
		    $levelFiler .= " HAVING ownerLevel = '".$userLevelFieldData."'";
		}

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();

		if($status == "All")
		    $status = "Live','Removed','RemovedByA','Republishedwp','Republishedwop','Rejectwop','Rejectwp','Archived','RemovedByAWithoutPenalty";
		else if($status == "Pending")
		    $status = "Removed','Live";
		else if($status == "Moderated")
		    $status = "RemovedByA','Republishedwp','Republishedwop','RemovedByAWithoutPenalty";
		else if($status == "Archived")
		    $status = "Rejectwop','Rejectwp','Archived";

		if($module == "AnA")
		  $queryCmd = "select SQL_CALC_FOUND_ROWS xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner-Level 1') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('Question','Answer','Reply','Comment') and ral.abuseReason like '%".$resultUserAbuse."%' and ral.status IN ('".$status."') and ral.entityId = m1.msgId ) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters group by xyz.entityId $levelFiler order by xyz.creationDate DESC LIMIT ".$startFrom." , ".$count;
		else if($module == "Events")	//In case of Events, we will first get all the report abuses done by users
		  $queryCmd = "SELECT SQL_CALC_FOUND_ROWS ral.* FROM `tReportAbuseLog` as ral WHERE ral.entityType IN ('Event','Event Comment') and ral.status IN ('".$status."') group by ral.entityId order by ral.creationDate DESC";
		else if($module == "Articles")
		  $queryCmd = "select SQL_CALC_FOUND_ROWS xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('Blog Comment') and ral.status IN ('".$status."') and ral.entityId = m1.msgId ) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters group by xyz.entityId $levelFiler order by xyz.creationDate DESC LIMIT ".$startFrom." , ".$count;
		else if($module == "discussion")
		  $queryCmd = "select SQL_CALC_FOUND_ROWS xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = 'AnA' and userId = xyz.ownerId LIMIT 1),'Beginner-Level 1') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('discussion','discussion Comment','discussion Reply') and ral.status IN ('".$status."') and ral.entityId = m1.msgId ) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters group by xyz.entityId $levelFiler order by xyz.creationDate DESC LIMIT ".$startFrom." , ".$count;
		else if($module == "announcement")
		  $queryCmd = "select SQL_CALC_FOUND_ROWS xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('announcement', 'announcement Comment','announcement Reply','eventAnA', 'eventAnA Comment','eventAnA Reply') and ral.status IN ('".$status."') and ral.entityId = m1.msgId ) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters group by xyz.entityId $levelFiler order by xyz.creationDate DESC LIMIT ".$startFrom." , ".$count;
		else if($module == "review")
		  $queryCmd = "select SQL_CALC_FOUND_ROWS xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner')ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('review','review Comment','review Reply') and ral.status IN ('".$status."') and ral.entityId = m1.msgId ) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters group by xyz.entityId $levelFiler order by xyz.creationDate DESC LIMIT ".$startFrom." , ".$count;

		$resultSet = $dbHandle->query($queryCmd);

		$totalAbuseReport = 0;
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $dbHandle->query($queryCmdTotal);
		foreach ($queryTotal->result() as $rowT) {
			$totalAbuseReport  = $rowT->totalRows;
		}

		$msgArray = array();
		$mainAnswerIdCsv = '';
		$eventNumber = 0;
		foreach ($resultSet->result_array() as $row){
			$mainAnswerIdCsv .= ($mainAnswerIdCsv == '')?$row['entityId']:','.$row['entityId'];
			$row['msgTxt'] = $this->changeTextForAtMention($row['msgTxt']);

			$tempMsgArray = array();
			$tempMsgArray['abuse']=array($row,'struct');
			if($row['entityType']!='Event' && $row['entityType']!='Event Comment'){
			    array_push($msgArray,array($tempMsgArray,'struct'));
			}
			else{
			    //Now, in case of Events, we will check if each row matches the search parameter. If it does, we will add it in the msgArray. Else we will ignore it
			    $addInArray = false;
			    if($row['entityType']=='Event'){
			      $eventId = $row['entityId'];
			      $queryCmdEvent = "select xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,e1.event_title msgTxt,e1.creationDate msgCreationDate,e1.user_id ownerId FROM `tReportAbuseLog` as ral, event as e1 WHERE ral.entityType IN ('Event') and ral.entityId = e1.event_id and e1.event_id = ?) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters $levelFilter ";
			      $resultSetEvent = $dbHandle->query($queryCmdEvent, array($eventId));
			      foreach ($resultSetEvent->result_array() as $rowEvent){
				      $rowEvent['msgTxt'] = $this->changeTextForAtMention($rowEvent['msgTxt']);
				      $addInArray = true;
				      $eventNumber++;
				      $tempMsgArray['event']=array($rowEvent,'struct');
			      }
			    }
			    if($row['entityType']=='Event Comment'){
			      $eventId = $row['entityId'];
			      $queryCmdEvent = "select xyz.*, firstname,lastname, displayname,ifnull((select levelName as Level from  userpointsystembymodule where modulename = '".$module."' and userId = xyz.ownerId LIMIT 1),'Beginner') ownerLevel, (select ts.displayname from tuser ts where ts.userid = xyz.userId) reporterName from (SELECT  ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityType IN ('Event Comment') and ral.entityId = m1.msgId and m1.msgId = ?) as xyz, tuser where tuser.userid=xyz.ownerId $extraFilters $levelFilter ";
			      $resultSetEvent = $dbHandle->query($queryCmdEvent, array($eventId));
			      foreach ($resultSetEvent->result_array() as $rowEvent){
				      $rowEvent['msgTxt'] = $this->changeTextForAtMention($rowEvent['msgTxt']);
				      $addInArray = true;
				      $eventNumber++;
				      $tempMsgArray['event']=array($rowEvent,'struct');
			      }
			    }
			    //While adding any row in msgArray, we will also take care of the Start and Count parameters
			    if($addInArray && $eventNumber > $startFrom && $eventNumber <= ($count+$startFrom)){
			      array_push($msgArray,array($tempMsgArray,'struct'));
			    }
			    //If we have got all the Events we required, get out of the loop
			    if($eventNumber > ($count+$startFrom))
			      break;
			}
		}

		$msgArray3 = array();
		if($mainAnswerIdCsv != ''){
			if($module == "AnA")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('Question','Answer','Reply','Comment') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";
			else if($module == "Events")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('Event','Event Comment') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";
			else if($module == "Articles")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('Blog Comment') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";
			else if($module == "discussion")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('discussion','discussion Comment','discussion Reply') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";
			else if($module == "announcement")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('announcement', 'announcement Comment','announcement Reply','eventAnA', 'eventAnA Comment','eventAnA Reply') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";
			else if($module == "review")
			  $queryCmd = "SELECT ral.* from `tReportAbuseLog` as ral WHERE ral.entityType IN ('review','review Comment','review Reply') and ral.status IN ('".$status."') and ral.entityId IN (".$mainAnswerIdCsv.") order by ral.creationDate DESC";

			$Result = $dbHandle->query($queryCmd);
			foreach($Result->result_array() as $row){
				array_push($msgArray3 ,array($row,'struct'));
			}
		}

		$this->load->model('QnAModel');
		$answerSuggestions = $this->QnAModel->getSuggestedInstitutes($mainAnswerIdCsv);
		$answerSuggestions = is_array($answerSuggestions)?$answerSuggestions:array();

		$mainArr = array();
		array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'abuseDetails'=>array($msgArray3,'struct'),
					'totalAbuseReport'=>array($totalAbuseReport,'string'),
		                        'answerSuggestions'=>array($answerSuggestions,'struct')
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	*	Remove the abuse list for a particular entity
	*/
	function updateStatusAbuseList($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$entityId=$parameters['2'];
		$entityType=$parameters['3'];
		$status=$parameters['4'];
		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$statusCheck = "";
		if($status=="RemovedByA" || $status=="RemovedByAWithoutPenalty")
		    $statusCheck = " and status = 'Live'";
		$queryCmd = "UPDATE tReportAbuseLog SET status = ? where entityId = ? and entityType = ?".$statusCheck;
		$resultSet = $dbHandle->query($queryCmd, array($status, $entityId, $entityType));

		if($status=='Rejectwp' || $status=='Rejectwop'){	//In case of rejection, subtract the points from the users who reported it as abuse

		    $queryCmd = "select userId from tReportAbuseLog where status = ? and entityId = ? and entityType = ?";
		    $resultSet = $dbHandle->query($queryCmd, array($status, $entityId, $entityType));

		    $this->load->model('UserPointSystemModel');
		    foreach ($resultSet->result_array() as $row){
			    $userId = $row['userId'];
			    if($entityType=='Blog Comment'){
			      if($status=='Rejectwp')
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectBlogAbuseReport');
			      else
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseBlog');
			    }
			    else if($entityType=='Event Comment' || $entityType=='Event'){
			      if($status=='Rejectwp')
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectEventAbuseReport');
			      else
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseEvent');
			    }
			    else{
			      if($status=='Rejectwp')
				$this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseAnA',$entityId);
			    }
		    }
		}
		
		if($status=='Archived'){
		    $statusCheck = " and (status = 'RemovedByAWithoutPenalty' || status = 'RemovedByA')";
		    $queryCmd = "UPDATE tReportAbuseLog SET status = ? where entityId = ? and entityType = ?".$statusCheck;
		$resultSet = $dbHandle->query($queryCmd, array($status, $entityId, $entityType));
		}
		$response = 1;
		return $this->xmlrpc->send_response($response);
	}

	/**
	*	Republish a particular entity and make it Live.
	*/
	function republishEntity($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$entityId=$parameters['1'];
		$threadId=$parameters['2'];
		$ownerId=$parameters['3'];
		$entityType=$parameters['4'];
		$penalty=$parameters['5'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$categoryId = 0;
		$countryId = 0;
		//Reverse the points of the user in case the entity is republished
		if($entityType=='Event'){
		    $queryCmd = 'update event set status_id=NULL where event_id = ?';
		    $query = $dbHandle->query($queryCmd, array($entityId));
		    $this->load->model('UserPointSystemModel');
		    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'republishEvent');
		}
		else if($entityType=='Question')
		{
		    $queryCmd="update messageTable set status='live' where msgId=?";
		    $query = $dbHandle->query($queryCmd, array($entityId));
		    $queryCmd="update messageExpertTable set status='answered' where threadId=?";
		    $query = $dbHandle->query($queryCmd, array($entityId));
		    $this->load->model('UserPointSystemModel');
		    $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'republishQuestion',$entityId);
           // $this->UserPointSystemModel->updateUserReputationPoint($ownerId,'republishQuestion',$entityId);
		    //Modified by Ankur on 8 March. If a question is deleted or abused, we need to change the count in Cache also
		    //$queryCmd = "select categoryId, countryId from messageCategoryTable mct, messageCountryTable mct1 where mct.threadId = $threadId and mct1.threadId = $threadId and categoryId > 1 and categoryId < 20 and countryId > 1";
		    
			//Above query changed by Vikas to cater to Recategorization
			$queryCmd = "SELECT mct.categoryId, mct1.countryId
						 From messageCategoryTable mct
						 LEFT JOIN messageCountryTable mct1 ON mct.threadId = mct1.threadId
						 LEFT JOIN categoryBoardTable cbt ON cbt.boardId = mct.categoryId
						 WHERE mct.threadId = ? 
						 AND cbt.parentId = 1 AND 
						 mct1.countryId > 1";
			
			$query = $dbHandle->query($queryCmd, array($threadId));
		    $row = $query->row();
		    $categoryId = $row->categoryId;
		    $countryId = $row->countryId;
		}
		else
		{
		    $queryCmd="update messageTable set status='live' where msgId=?";
		    error_log_shiksha( 'deleteCommentFromCMS query cmd is ' . $queryCmd,'qna');
		    $query = $dbHandle->query($queryCmd, array($entityId));

		    //In case of discussion, announcement or review, republish the parent entity also
		    if($entityType == "discussion" || $entityType == "review" || $entityType == "announcement" || $entityType == "eventAnA" || $entityType == "discussion Comment" || $entityType == "announcement Comment" || $entityType == "review Comment" || $entityType == "eventAnA Comment" || $entityType == "discussion Reply" || $entityType == "announcement Reply" || $entityType == "review Reply" || $entityType == "eventAnA Reply"){
			  $queryCmd="update messageTable set status='live' where msgId=?";
			  $query = $dbHandle->query($queryCmd, array($threadId));
		    }

		    /*$numRowsAffected = $dbHandle->affected_rows();
		    if($numRowsAffected > 0){ //For decreasing msgCount if the comment is deleted.
			    $queryCmd = "select count(*) msgCount from messageTable where threadId = ".$threadId." and status in ('live','closed') and fromOthers = 'user' and parentId <> 0";
			    $result = $dbHandle->query($queryCmd);
			    $commentCount = 'msgCount = (msgCount + (1))';
			    foreach ($result->result_array() as $row){
				    $msgCount = $row['msgCount'];
				    $commentCount = 'msgCount = ('.$msgCount.')';
			    }
			    $queryCmd="update messageTable set ".$commentCount." where msgId=$entityId";
			    error_log_shiksha( 'updateMsgCount query cmd is ' . $queryCmd,'qna');
			    $query = $dbHandle->query($queryCmd);
		      }*/
		    $this->load->model('UserPointSystemModel');
		    if($entityType == 'Event Comment')
		      $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'RepublishEventComment');
		    else if($entityType == 'Blog Comment')
		      $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'RepublishArticleComment');
		    else if($entityType=='discussion'){
		      $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'republishDiscussion',$entityId);
	//		  $this->UserPointSystemModel->updateUserReputationPoint($ownerId,'republishDiscussion',$entityId);		    
			}
			else if($entityType=='announcement' || $entityType == 'announcement Comment' || $entityType == 'announcement Reply')
		      $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'RepublishAnnouncement');
		    else if(strtolower($entityType)=='answer'){
		      $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$ownerId,'republishAnswer',$entityId);
     //         $this->UserPointSystemModel->updateUserReputationPoint($ownerId,'republishAnswer',$entityId);		
		}        
		}
		//If republishing with Penalty then update the User point system
		//if($penalty == "1"){
		    //Decrease 15 points of all users who reported abuse on the entity
		    $queryCmd = "select userId from tReportAbuseLog where entityId = ? and entityType = ? and status IN ('Removed','RemovedByA','RemovedByAWithoutPenalty')";
		    $result = $dbHandle->query($queryCmd, array($entityId, $entityType));

		    foreach ($result->result_array() as $row){
			    $userId = $row['userId'];
			    if($entityType=='Event' || $entityType=='Event Comment'){
				$this->load->model('UserPointSystemModel');
				if($penalty == "1")
				  $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'republishEventPenalty');
				else
				  $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseEvent');
			    }
			    else if($entityType=='Blog Comment'){
				$this->load->model('UserPointSystemModel');
				if($penalty == "1")
				  $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'RepublishArticlePenalty');
				else
				  $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseBlog');
			    }
			    else{
				$this->load->model('UserPointSystemModel');
				if($penalty == "1"){
				  $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'rejectAbuseAnA',$entityId);
                $this->UserPointSystemModel->updateUserReputationPoint($userId,'rejectAbuseAnA',$entityId);                           
 			}else
              $this->UserPointSystemModel->updateUserReputationPoint($userId,'rejectAbuseAnA',$entityId);
		    }
		}		
		//}
		//$response = 1;
		$response = array(
                		array(
					'Result'=>array('1'),
					'categoryId'=>array($categoryId),
					'countryId'=>array($countryId)),
                                'struct');
		return $this->xmlrpc->send_response($response);
	}

	/*
	* This function sends the abuse reports for each day to Shiksha people
	*/
	function sendMailForAbuseActivity($appID=12){
        $this->validateCron();
 		echo "\n\n\n cron to send mails for report abuse start at.\n".date("Y-m-d H:i:s")."\n\n\n";
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library(array('enterpriseconfig','subscription_client','sums_product_client','Alerts_client'));
		$this->load->helper('date');
		$fromAddress=SHIKSHA_ADMIN_MAIL;
		$this->dbLibObj = DbLibCommon::getInstance('Enterprise');
		$dbHandle = $this->_loadDatabaseHandle('write');
		$alertClient = new Alerts_client();
		$today = date("Y-m-d");
		$yesterday = date("Y-m-d", strtotime("-1 day"));

		//Get the abuse reason form from the DB
		$this->load->library('message_board_client');
		$msgbrdClient = new Message_board_client();
		$Result = $msgbrdClient->getReportAbuseForm($appId,"QuestionAnswer");
		$abuseForm = is_array($Result)?$Result:array();
		$abuseFormFields = array();
		for($i=0;$i<count($abuseForm);$i++)
		{
		  $abuseFormFields[] = $abuseForm[$i]['Title'];
		}

		//Get the abuse report for the last day
		$abuseArray = array();
		$queryCmd = "SELECT SQL_CALC_FOUND_ROWS ral.*,m1.msgTxt,m1.creationDate msgCreationDate,m1.userId ownerId FROM `tReportAbuseLog` as ral, messageTable as m1 WHERE ral.entityId = m1.msgId and ral.creationDate < ? and ral.creationDate >= ? order by ral.creationDate DESC";
		echo "\nAbuse report query is: ".$queryCmd;

		$result = $dbHandle->query($queryCmd, array($today, $yesterday));

		$i=0;
		foreach ($result->result_array() as $row){
			$threadId = $row['threadId'];
			$moduleName = $row['entityType'];

			$abuseArray[$i] = $row;
			//Set the URL
			if($moduleName == "Blog Comment")
			  $abuseArray[$i]['url'] = SHIKSHA_HOME."/getArticleDetail/".$threadId;
			else if($moduleName == "Event" || $moduleName == "Event Comment")
			  $abuseArray[$i]['url'] = SHIKSHA_HOME."/getEventDetail/1/".$threadId;
			else
			  $abuseArray[$i]['url'] = SHIKSHA_HOME."/getTopicDetail/".$threadId;

			//Set the abuse reason text
			$abuseReasons = explode(",",$row['abuseReason']);
			$fields = '';
			for($j=0;$j<count($abuseReasons);$j++){
			  $index = $abuseReasons[$j]-1;
			  if($fields == '')
			    $fields .= $abuseFormFields[$index];
			  else
			    $fields .= ", ".$abuseFormFields[$index];
			}
			$abuseArray[$i]['abuseReason'] = $fields;
			//Set the abuse report date
			$dateobj = strtotime($row['creationDate']);
			$abuseArray[$i]['creationDate'] = date("d F, Y", $dateobj);
			$i++;
		}
		//Get the total number of reports in the list
		$totalAbuseReport = 0;
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $rowT) {
			$totalAbuseReport  = $rowT->totalRows;
		}
		//Get abuse report for Events

		$queryCmd = "SELECT SQL_CALC_FOUND_ROWS ral.*,e1.event_title msgTxt,e1.creationDate msgCreationDate,e1.user_id ownerId FROM `tReportAbuseLog` as ral, event as e1 WHERE ral.entityId = e1.event_id and ral.creationDate < ? and ral.creationDate >= ? order by ral.creationDate DESC";
		$result = $dbHandle->query($queryCmd, array($today, $yesterday));

		foreach ($result->result_array() as $row){
			$threadId = $row['threadId'];
			$moduleName = $row['entityType'];

			$abuseArray[$i] = $row;
			//Set the URL
			if($moduleName == "Event" || $moduleName == "Event Comment")
			  $abuseArray[$i]['url'] = SHIKSHA_HOME."/getEventDetail/1/".$threadId;

			//Set the abuse reason text
			$abuseReasons = explode(",",$row['abuseReason']);
			$fields = '';
			for($j=0;$j<count($abuseReasons);$j++){
			  $index = $abuseReasons[$j]-1;
			  if($fields == '')
			    $fields .= $abuseFormFields[$index];
			  else
			    $fields .= ", ".$abuseFormFields[$index];
			}
			$abuseArray[$i]['abuseReason'] = $fields;
			//Set the abuse report date
			$dateobj = strtotime($row['creationDate']);
			$abuseArray[$i]['creationDate'] = date("d F, Y", $dateobj);
			$i++;
		}
		//Get the total number of reports in the list
		$queryCmd = 'SELECT FOUND_ROWS() as totalRows';
		$query = $dbHandle->query($queryCmd);
		foreach ($query->result() as $rowT) {
			$totalAbuseReport  += $rowT->totalRows;
		}

		//Send the mail to Product group and Vivek
		$email = "saurabh.gupta@shiksha.com";
		$fromMail = "noreply@shiksha.com";
		$ccmail = "vivek.gupta@naukri.com";
		$bccmail = "sachin.singhal@brijj.com";
		$subject = "SA and SMS report for ".$yesterday.".";

		$contentArr = array();
		$this->load->model('smsModel');
		$tempArray1 = $this->smsModel->sendSMSReport($dbHandle,$yesterday);
		$contentArr['smsReport'] = $tempArray1;
		$contentArr['totalNumber'] = $totalAbuseReport;
		$contentArr['yesterday'] = date("d F, Y", strtotime("-1 day"));
		$contentArr['type'] = 'abuseDigestMail';

		//Get the number of Facebook shares for yesterday
		$queryFacebook = "SELECT count(*) as total from messageTableFacebookLog where creationDate < ? and creationDate >= ?" ;
		$query = $dbHandle->query($queryFacebook, array($today, $yesterday));

		$FbShareCount =0;
		foreach ($query->result() as $row) {
			$FbShareCount  = $row->total;
		}
		$contentArr['FbShareCount'] = $FbShareCount;

		//Get the number of Facebook Connects for yesterday
		$queryFacebookC = "select count(*) as totalC from tusersourceInfo ts, tuser tu where ts.userid = tu.userid and ts.referer like 'Face%' and tu.usercreationDate >= ? and tu.usercreationDate < ?";
		$queryC = $dbHandle->query($queryFacebookC, array($yesterday, $today));
		$FbConnectCount =0;
		foreach ($queryC->result() as $rowC) {
		    $FbConnectCount  = $rowC->totalC;
		}
		$contentArr['FbConnectCount'] = $FbConnectCount;

        //Get the Number of Questions and Answers from Mobile

        $queryQ="select count(*) as QuestionCount from mobile_activity_log where activity_type='question_post' and logged_at < ? and logged_at >= ?" ;
        $query = $dbHandle->query($queryQ, array($today, $yesterday));

        $row = $query->row();
        $contentArr['mobileQuestionCount']  = $row->QuestionCount;

        $queryQ="select count(*) as AnswerCount from mobile_activity_log where activity_type='answer_post' and logged_at < ? and logged_at >= ?" ;
        $query = $dbHandle->query($queryQ, array($today, $yesterday));

        $row = $query->row();
        $contentArr['mobileAnswerCount']  = $row->AnswerCount;


		if($totalAbuseReport<=0){
		  $contentArr['totalNumber'] = "No";

		  $content = $this->load->view("search/searchMail",$contentArr,true);
		  $response= $alertClient->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00','n',array(),$ccmail,$bccmail);
          $alertClient->externalQueueAdd("12",$fromMail,"maneesh@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
          $alertClient->externalQueueAdd("12",$fromMail,"aditya.roshan@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
          $alertClient->externalQueueAdd("12",$fromMail,"nishant.pandey@naukri.com", $subject,$content,"html",'0000-00-00 00:00:00');
          $alertClient->externalQueueAdd("12",$fromMail,"amit.kuksal@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
		  $alertClient->externalQueueAdd("12",$fromMail,"vikas.k@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
		  $alertClient->externalQueueAdd("12",$fromMail,"abhinav.k@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
          $alertClient->externalQueueAdd("12",$fromMail,"shalabh@brijj.com", $subject,$content,"html",'0000-00-00 00:00:00');
		}
		else
        {
		  //Set the attachment HTML and make an entry in the DB
		  $displayData = array();
		  $displayData['abuseList'] = $abuseArray;
		  $abuseContent = $this->load->view('common/abuseReport',$displayData,true);
		  $attachmentResponse = $alertClient->createAttachment("12",0,'','AbuseReport',$abuseContent,"abuseReport".$yesterday.".html",'html');
		  $attachmentId = $alertClient->getAttachmentId("12",0,'','AbuseReport',"abuseReport".$yesterday.".html");
		  //error_log(print_r($abuseContent,true));
		  $content = $this->load->view("search/searchMail",$contentArr,true);
		  //$attId = (is_array($attachmentId))?(int)$attachmentId[0]['id']:0;
		  $attachmentArr = array();
		  $attachmentArr[0] = (int)$attachmentId[0]['id'];

		  $response= $alertClient->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr,$ccmail,$bccmail);
		  $response= $alertClient->externalQueueAdd("12",$fromMail,'ankur.gupta@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
//           $alertClient->externalQueueAdd("12",$fromMail,'ankur.gupta@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
          $alertClient->externalQueueAdd("12",$fromMail,'maneesh@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
          $alertClient->externalQueueAdd("12",$fromMail,'nishant.pandey@naukri.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
          $alertClient->externalQueueAdd("12",$fromMail,'aditya.roshan@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
          $alertClient->externalQueueAdd("12",$fromMail,'karundeep.gill@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
          $alertClient->externalQueueAdd("12",$fromMail,'amit.kuksal@shiksha.com',$subject,$content,$contentType="html",'0000-00-00 00:00:00','y',$attachmentArr);
		  $alertClient->externalQueueAdd("12",$fromMail,"vikas.k@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
	          $alertClient->externalQueueAdd("12",$fromMail,"shalabh@brijj.com", $subject,$content,"html",'0000-00-00 00:00:00');
		  $alertClient->externalQueueAdd("12",$fromMail,"abhinav.k@shiksha.com", $subject,$content,"html",'0000-00-00 00:00:00');
		}
		echo "cron to send mails for report abuse ends at.\n".date("Y-m-d H:i:s")."\n\n\n";
	}


    function addSpotlightEvent($request){

        $parameters    = $request->output_parameters();
        $appId         = $parameters['0'];
        $eventId1      = $parameters['1'];
        $eventId2      = $parameters['2'];
        $paidEventId   = $parameters['3'];
        $uploadedImage = $parameters['4'];
        $tillDate      = $parameters['5'];
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');

        $paramsArray = array();
	    if($paidEventId!=''){

	        $queryCmd = "select distinct (select e.event_id from event e,event_date d where e.event_id=d.event_id and e.event_id= ? and datediff(current_date,d.end_date)<=0 and e.status_id is NULL) as eventId1,(select e.event_id from event e,event_date d where e.event_id=d.event_id and e.event_id=? and datediff(current_date,d.end_date)<=0 and e.status_id is NULL) as eventId2,(select e.event_id as paidEventId from event e,event_date d where e.event_id=d.event_id and e.event_id=? and datediff(current_date,d.end_date)<=0 and e.status_id is NULL) as paidEventId from event e,event_date d where e.event_id=d.event_id and e.event_id in(?) and datediff(current_date,d.end_date)<=0 and status_id is NULL";

            $paramsArray[] = $eventId1;
            $paramsArray[] = $eventId2;
            $paramsArray[] = $paidEventId;
            $paramsArray[] = array($eventId1,$eventId2,$paidEventId);

	    }else{

	        $queryCmd = "select distinct (select e.event_id from event e,event_date d where e.event_id=d.event_id and e.event_id=? and datediff(current_date,d.end_date)<=0 and e.status_id is NULL) as eventId1,(select e.event_id from event e,event_date d where e.event_id=d.event_id and e.event_id=? and datediff(current_date,d.end_date)<=0 and e.status_id is NULL) as eventId2 from event e,event_date d where e.event_id=d.event_id and e.event_id in(?) and datediff(current_date,d.end_date)<=0 and status_id is NULL";

            $paramsArray[] = $eventId1;
            $paramsArray[] = $eventId2;
            $paramsArray[] = array($eventId1,$eventId2);

	    }

        $spotlightEvents = $dbHandle->query($queryCmd, $paramsArray);
        foreach($spotlightEvents->result_array() as $spotlightEventsRow){
            $event1    = $spotlightEventsRow['eventId1'];
            $event2    = $spotlightEventsRow['eventId2'];
            $paidEvent = $spotlightEventsRow['paidEventId'];
        }

	    if($event1 == $eventId1){
	        if($event2 == $eventId2){
	            if($paidEvent == $paidEventId){
	                $data = array(
                        'event_id_1'     => $eventId1,
                        'event_id_2'     => $eventId2,
                        'paid_event_id'  => $paidEventId,
                        'uploaded_image' => $uploadedImage,
                        'till_date'      => $tillDate
                    );
                    // add event details
                    $queryCmd         = $dbHandle->insert_string('spotlightEvent',$data);
                    $query            = $dbHandle->query($queryCmd);
                    $spotlightId      = $dbHandle->insert_id();
                    $spotlightEventId = -1;
	            }else{
                    $spotlightEventId = "Paid Event: ".$paidEventId;
                }
            }else{
                $spotlightEventId = "Event 2: ".$eventId2;
            }
        }else{
            $spotlightEventId = "Event 1: ".$eventId1;
        }

	    return $this->xmlrpc->send_response($spotlightEventId);
    }


function setCompanyLogo($request){

        $parameters = $request->output_parameters();
        $name = $parameters['0'];
        $url = $parameters['1'];
        $status='live';
        $splitUrl = parse_url($url);


        $data =array(
                'company_name'=>$name,
                'logo_url'=>$splitUrl['path'],
                'status'=>$status,);
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = $dbHandle->insert_string('company_logos',$data);
        $query = $dbHandle->query($queryCmd);
        $setcompanylogoid=1;
        return $this->xmlrpc->send_response($setcompanylogoid);
}

function delCompanyLogo($request){

        $parameters = $request->output_parameters();
        $delid = $parameters['0'];
        $status='live';
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "update company_logos set status='delete' where id= ? ";
        $query = $dbHandle->query($queryCmd, $delid);
        $delcompanylogoid=1;
        return $this->xmlrpc->send_response($delcompanylogoid);
}

function checkDeleteLogo($request){

        $parameters = $request->output_parameters();
        $delid = $parameters['0'];
	$dbHandle = $this->_loadDatabaseHandle();
        $queryCmd="select group_concat(institute_id) as institute , count(*) as count from company_logo_mapping where logo_id = ?  and (status= 'live' or status='draft')";
        $query = $dbHandle->query($queryCmd, array($delid));
        $logoFrequency=0;
        $institute;
        foreach ($query->result() as $row){
                $logoFrequency= $row->count;
                $institute= $row->institute;

        }
        if( $logoFrequency == 0)
        {

            return $this->xmlrpc->send_response($logoFrequency);
        }
        if( $logoFrequency > 0)
        {

                 $institute_name= array();
                 $insti= array();
                 $insti= explode(",",$institute);
                 $insti = array_unique($insti);
                 $insti= array_values($insti);
                 foreach($insti as $key=> $value)
                 {

                         $queryCmd="select distinct institute_name from institute where institute_id= ?";
                         $query = $dbHandle->query($queryCmd, array($value));
                         foreach ($query->result() as $row)
                         {
                                                 array_push($institute_name,array(
                                                                                       array(
                                                                                                'institute_name'=>array($row->institute_name,'string'),

                                                                                            ),'struct')
                                                           );

                         }

                 }

                 $response = array($institute_name,'struct');
                 return $this->xmlrpc->send_response($response);


        }

}

function modCompanyLogo($request){

        $parameters = $request->output_parameters();
        $name = $parameters['0'];
        $url = $parameters['1'];
        $id = $parameters['2'];
        $splitUrl = parse_url($url);

	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "update company_logos set company_name= ? , logo_url= ? where id= ? ";
        $query = $dbHandle->query($queryCmd, array($name, $splitUrl['path'], $id));
        $modcompanylogoid=1;
        return $this->xmlrpc->send_response($modcompanylogoid);
}

function addLogoListing($request){

        $parameters = $request->output_parameters();
        $id = $parameters['0'];
	$dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = 'select * from company_logos where id = ? ';
        $query = $dbHandle->query($queryCmd, array($id));
        $resArray= array();
                foreach ($query->result() as $row){
                                     array_push($resArray,array(
                                                                        array(
                                                                                'id'=>array($row->id,'int'),
                                                                                'company_name'=>array($row->company_name,'string'),
                                                                                'logo_url'=>array($row->logo_url,'string'),

                                                                             ),'struct')
                                                    );//close array_push

                                                    }

                 $response = array($resArray,'struct');
                 return $this->xmlrpc->send_response($response);

}



function getCompanyLogo($request){

     $parameters = $request->output_parameters();
     $sortClass  = $parameters['0'];
     $rstart     = $parameters['1'];
     $rcount     = $parameters['2'];
     $dbHandle   = $this->_loadDatabaseHandle();

     if( $rstart == -1){
                if($sortClass == 'All') $queryCmd = 'select * from company_logos where status = "live" order by company_name ';
                else $queryCmd = 'select * from company_logos where status = "live" and company_name LIKE ? order by company_name  ';

               // $response = $dbHandle->query($queryCmd);
                $query = $dbHandle->query($queryCmd, $sortClass.'%');
                $resArray= array();
                foreach ($query->result() as $row){
                                    array_push($resArray,
                                        array(
                                            array(
                                                    'id'=>array($row->id,'int'),
                                                    'company_name'=>array($row->company_name,'string'),
                                                    'logo_url'=>array($row->logo_url,'string'),

                                                 ),'struct'
                                            )
                                        );//close array_push
                                    }

                 $response = array($resArray,'struct');
                 return $this->xmlrpc->send_response($response);

                 }

      else{ 
                $params = array();
                if($sortClass == 'All') {
                    $queryCmd = 'select * from company_logos where status = "live" order by company_name LIMIT ? , ? ';
                    $params[] = (int)$rstart;
                    $params[] = (int)$rcount;
                } else {
                    $queryCmd = 'select * from company_logos where status = "live" and company_name LIKE ? order by company_name  LIMIT  ? , ? ';
                    $params[] = $sortClass.'%';
                    $params[] = (int)$rstart;
                    $params[] = (int)$rcount;
                }
                $response = array();
                $query = $dbHandle->query($queryCmd, $params);
                
                $resArray= array();

                foreach ($query->result() as $row){
                                        array_push($resArray,
                                            array(
                                                array(
                                                    'id'=>array($row->id,'int'),
                                                    'company_name'=>array($row->company_name,'string'),
                                                    'logo_url'=>array($row->logo_url,'string'),
                                                    ),
                                                'struct'
                                                )
                                            );//close array_push
                                        }

                $response = array($resArray,'struct');
                return $this->xmlrpc->send_response($response);
            }

}


function getPopularInstitutes(){
     $dbHandle = $this->_loadDatabaseHandle();
     $queryCmd= "Select position, institute_id , logo_url,type,courseId from popular_institutes where status ='live' order by position";
     $query= $dbHandle-> query($queryCmd);
     $resArray= array();
     foreach ($query->result() as $row){
                    array_push($resArray,array(
                                                       array(
                                                            'position'=>array($row->position,'int'),
                                                            'institute_id'=>array($row->institute_id,'string'),
                                                            'logo_url'=>array($row->logo_url,'string'),
                                                            'type'=>array($row->type,'string'),
							    'courseId' =>array($row->courseId,'int')
                                                            ),'struct')
                               );//close array_push

                                         }
    $response = array($resArray,'struct');
    return $this->xmlrpc->send_response($response);
}

function setPopularInstitutes($request){

    $parameters = $request->output_parameters();
    $position= $parameters[0];
    $insId= $parameters[1];
    $type = $parameters[2];
    $courses = $parameters[3];
    $dbHandle = $this->_loadDatabaseHandle('write');

    if(trim($insId) == '')
    {
        $queryCmd= "Update popular_institutes set status='history' where position in (1,2,3,4,5) and status='live' and type= ?";
        $query= $dbHandle-> query($queryCmd, $type);
        return $this->xmlrpc->send_response("Deleted");
    }

    $position= explode(',',$position);
    $insId= explode(',',$insId);
    $courses = 	explode(',',$courses);


    //checking for valid institute id

    foreach( $insId as $key=>$id){

                    if(trim($id) != '' )
                    {
                            $queryCmd= "Select count(*)as count from shiksha_institutes i , listings_main lm  where i.listing_id= ? and i.listing_id = lm.listing_type_id and i.status ='live' and lm.status='live' and lm.listing_type IN ('institute','university_national')";
                            $query= $dbHandle-> query($queryCmd, $id);
                            foreach ($query->result() as $row){

                                                        if($row->count== 0)
                                                        return $this->xmlrpc->send_response($id);

                                                               }
                    }
     }


    // updating old entries
    $queryCmd= "Update popular_institutes set status='history' where position in (1,2,3,4,5) and status='live' and type= ?";
    $query= $dbHandle-> query($queryCmd, $type);

    //adding new values for positions

    for( $ik =0; $ik < count($position); $ik++ )
    {

                if($insId[$ik] == '')
                             continue;
                $data = array(
                            'position'=>$position[$ik],
                            'institute_id'=>$insId[$ik],
                            'status'=>'live',
                            'type'=>$type,
			                'courseId'=>$courses[$ik]?$courses[$ik]:NULL	
                                );
                $queryCmd = $dbHandle->insert_string('popular_institutes',$data);
		$query = $dbHandle->query($queryCmd);

     }
     return $this->xmlrpc->send_response(0);
}





function getSpotlightEvents($request){

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "select * from spotlightEvent order by spotlight_id desc limit 1";
        log_message('debug', 'query cmd is ' . $queryCmd);
        $response = $dbHandle->query($queryCmd);
	$query = $dbHandle->query($queryCmd);
                //will only have one row
                $msgArray = array();
                foreach ($query->result() as $row){
                        array_push($msgArray,array(
                                                        array(
                                                                'eventId1'=>array($row->event_id_1,'int'),
                                                                'eventId2'=>array($row->event_id_2,'int'),
                                                              	'paidEventId'=>array($row->paid_event_id,'string'),
								'uploadImage'=>array($row->uploaded_image,'string'),
								'tillDate'=>array($row->till_date,'date')
                                                        ),'struct')
                        );//close array_push

                }
                $response = array($msgArray,'struct');
                return $this->xmlrpc->send_response($response);
	}
	function sAddTVCUser($request){
			$parameters = $request->output_parameters();
			$name = $parameters[0];
			$email = $parameters[1];
			$mobile = $parameters[2];
			$city = $parameters[3];
			$company = $parameters[4];
			$page = $parameters[5];
			$answer = $parameters[6];
			$dbHandle = $this->_loadDatabaseHandle('write');
			$queryCmd = "INSERT INTO tvc_table (tvcid, uname, uemail, umobile, ucity, ucompany, upage, uanswer, ucreationdate) VALUES ('', ?,?,?,?,?,?,?, NOW())";
			$query = $dbHandle->query($queryCmd, array($name, $email, $mobile, $city, $company, $page, $answer));
			$response = array('success','string');
			return $this->xmlrpc->send_response($response);
	}
	/***********************
	Function: changeTextForAtMention
	Purpose: Remove special characters at the places where @Mention is used
	Input: Text of the answer/comment
	Output: Text for answer/comment with special characters removed
	************************/
	function changeTextForAtMention($msgTxt){
	    $newMsgTxt = '';
	    do{
	    if(strpos($msgTxt,('@||')) !== false || strpos($msgTxt,('@||')) === 0){
		$newMsgTxt .= substr($msgTxt , 0 ,strpos($msgTxt,('@||'))) . '@';
		$msgTxt = substr($msgTxt, strpos($msgTxt,('@||'))+3);
		$newMsgTxt .= substr( $msgTxt, 0, strpos($msgTxt,('||') ));
		$msgTxt = substr($msgTxt, strpos($msgTxt,('||'))+2);
	    }
	    }while(strpos($msgTxt,('@||')) !== false);
	    $newMsgTxt .= $msgTxt;
	    return $newMsgTxt;
	}

	/**
	*	Get the Expert application list
	*/
	function getExperts($request){
        $parameters         = $request->output_parameters();
        $appID              = $parameters['0'];
        $userId             = $parameters['1'];
        $startFrom          = $parameters['2'];
        $count              = $parameters['3'];
        $module             = $parameters['4'];
        $status             = $parameters['5'];
        $userNameFieldData  = $parameters['6'];
        $userLevelFieldData = $parameters['7'];

        if($userLevelFieldData == "All")
        {
            $userLevelFieldData = '';
        }
        if($userNameFieldData == "Username or Email")
        {
            $userNameFieldData = '';
        }
		$extraFilters = ''; $levelFiler = '';
		if($userNameFieldData!='' && strpos($userNameFieldData,'@')!==false){
		    $extraFilters .= " and tu.email = '".$userNameFieldData."'";
		}
		else if($userNameFieldData!=''){
		    $extraFilters .= " and tu.displayname = '".$userNameFieldData."'";
		}
		if($userLevelFieldData!=''){
		    $levelFiler .= " HAVING ownerLevel = '".$userLevelFieldData."'";
		}

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle();
		if($status == "All")
		    $status = "Live','Draft','Deleted','Rejected";
		else if($status == "Pending")
		    $status = "Draft";
		else if($status == "Approved" || $status == "Accepted")
		    $status = "Live";

		// $queryCmd = "select SQL_CALC_FOUND_ROWS et.*, tu.displayname, tu.firstname, tu.lastname, tu.email, tu.mobile,ifnull((select upl.Level from userPointLevelByModule upl, userpointsystembymodule ups where upl.module = ups.moduleName and upl.module = 'AnA' and ups.userId = et.userId and ups.userPointValueByModule >= upl.minLimit LIMIT 1),'Beginner') ownerLevel, ifnull((select userpointvaluebymodule from userpointsystembymodule upsm where modulename='AnA' and upsm.userId = et.userId),0) ownerPoints from expertOnboardTable et, tuser tu where et.userId = tu.userid and et.status IN ('".$status."') $extraFilters $levelFiler ORDER BY et.creationDate DESC LIMIT ".$startFrom." , ".$count;


        $queryCmd = "select SQL_CALC_FOUND_ROWS et.*, tu.displayname, tu.firstname, tu.lastname, tu.email, tu.mobile,ifnull((select levelName as Level from  userpointsystembymodule where modulename = 'AnA' and userId = et.userId LIMIT 1),'Beginner') ownerLevel, ifnull((select userpointvaluebymodule from userpointsystembymodule upsm where modulename='AnA' and upsm.userId = et.userId),0) ownerPoints from expertOnboardTable et, tuser tu where et.userId = tu.userid and et.status IN ('".$status."') $extraFilters $levelFiler ORDER BY et.creationDate DESC LIMIT ".$startFrom." , ".$count;

		

        $resultSet = $dbHandle->query($queryCmd);
		$totalAbuseReport = 0;
		$queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
		$queryTotal = $dbHandle->query($queryCmdTotal);
		foreach ($queryTotal->result() as $rowT) {
			$totalAbuseReport  = $rowT->totalRows;
		}

		$msgArray = array();
		foreach ($resultSet->result_array() as $row){
			$tempMsgArray = array();
			$tempMsgArray['abuse']=array($row,'struct');
		    array_push($msgArray,array($tempMsgArray,'struct'));
		}
		$mainArr = array();
		array_push($mainArr,array(
				array(
					'results'=>array($msgArray,'struct'),
					'totalAbuseReport'=>array($totalAbuseReport,'string')
				),'struct')
		);//close array_push
		$response = array($mainArr,'struct');
		return $this->xmlrpc->send_response($response);
	}

	/**
	*	Perform Accept, Reject or Remove actions on the Experts
	*/
	function actionExpert($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];
		$action=$parameters['2'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');

		//Update the status of the User in Expert table
		if($action=='accept') $status = 'Live';
		else if($action=='remove') $status = 'Deleted';
		else if($action=='reject') $status = 'Rejected';

                $queryCmd = "select status from  expertOnboardTable where userId = ?";
                $resultSet = $dbHandle->query($queryCmd, array($userId));
                $row = $resultSet->row();
                $currentStatus = $row->status;
                if($currentStatus!=$status){
                       $queryCmd = "UPDATE expertOnboardTable SET status= ? , modificationDate=now() where userId = ?";
                       $resultSet = $dbHandle->query($queryCmd, array($status,$userId));

	                // update User point system
                       $this->load->model('UserPointSystemModel');
                       if($action=='accept')
                               $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'registerExpert');
                       else if($action=='remove')
                               $this->UserPointSystemModel->updateUserPointSystem($dbHandle,$userId,'unregisterExpert');
                       $response = '1';
               }
               else{
                       $response = 'duplicate';
               }

		return $this->xmlrpc->send_response($response);
	}

	/**
	*	Remove the Default pic of the Expert and replace with Default
	*/
	function removeExpertProfilePic($request){
		$parameters = $request->output_parameters();
		$appID=$parameters['0'];
		$userId=$parameters['1'];

		//connect DB
		$dbHandle = $this->_loadDatabaseHandle('write');
		$queryCmd = "UPDATE expertOnboardTable SET imageURL='' where userId = ?";
		$resultSet = $dbHandle->query($queryCmd, array($userId));
		$response = '1';
		return $this->xmlrpc->send_response($response);
	}

}
?>
