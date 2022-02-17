<?php

class MarketingServer extends MX_Controller {

    function index(){
		$this->dbLibObj = DbLibCommon::getInstance('Marketing');
		set_time_limit(0);
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library(array('marketingconfig','Alerts_client','subscription_client','sums_product_client'));
        $this->load->helper('date');
		/* Start: Shashwat CMS Server code */

        $config['functions']['registerUserForLead'] = array('function' => 'MarketingServer.registerUserForLead');

        error_log("123 LKJ");
        $config['functions']['runGenerateLeadCronForConsultants'] = array('function' => 'MarketingServer.runGenerateLeadCronForConsultants');

        $config['functions']['runGenerateLeadCron'] = array('function' => 'MarketingServer.runGenerateLeadCron');
        /* End: Shashwat CMS Server code */

        //        $this->makeApcCountryMap();
        //        $this->makeApcCityMap();
        $this->xmlrpc->set_debug(1);
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        define("INSTI_ID","28466");
        return $this->$method($args[1]);
    }

    function runGenerateLeadCronForConsultants($request)
    {
	$parameters = $request->output_parameters(FALSE,FALSE);
	$dbHandle = $this->_loadDatabaseHandle('write');
	$this->load->library(array('lmsLib'));
	$LmsClientObj = new LmsLib();


	$queryCmd = "select * from tLeadInfo where tLeadInfo.pagename = \"StudyAbroad\" and marketingflagsent=\"false\"";
	error_log($queryCmd." Shirish1");
	$query = $dbHandle->query($queryCmd);
	foreach($query->result() as $row) {
		$userData = array();
		$addReqInfo = array();
		$mappingId = $row->id;
		$email = $row->email;
		$queryCmdInsert = "select * from shiksha.tuser where shiksha.tuser.email=?";
		$query2 = $dbHandle->query($queryCmdInsert, array($email));
		if(count($query2->result()) > 0) {
			foreach($query2->result() as $row2) {
				$userTuserInfo = $row2;
				$password = $row2->password;
				$residenceCity = $row2->city;
			}
			$queryCmdIntrest = "select tLeadKeyValue.modeoffinance, tLeadKeyValue.courseStartTime, group_concat(distinct countryTable.name) as countryOfInterest, group_concat(distinct countryCityTable.city_name) as nearestMetroCity, categoryBoardTable.name as category from tLeadKeyValue, countryTable, countryCityTable, categoryBoardTable where mappingid = ? and countryTable.countryId = tLeadKeyValue.countryid and countryCityTable.city_id= tLeadKeyValue.cityid and categoryBoardTable.boardId = tLeadKeyValue.categoryid group by mappingid;";
			$query2 = $dbHandle->query($queryCmdIntrest, array($mappingId));
			foreach($query2->result() as $row2) {
				$userStudyAbroadInterest = $row2;
			}

			$cookie = $email."|".$password;
			error_log($cookie." LKJ");
			$userStatus = $this->cookie($cookie);
            error_log(print_r($userStatus,true)."SHirish12121");
            if($userStatus != "false")
            {
			$addReqInfo['listing_type'] = "consultant";
			$addReqInfo['displayName'] = $userStatus[0]['displayname']; 
			$addReqInfo['contact_cell'] = $userStatus[0]['mobile']; 
			$addReqInfo['userId'] =  $userStatus[0]['userid']; 
			$addReqInfo['contact_email'] = $email;
			$addReqInfo['action'] = "marketingPage";
			$addReqInfo['userInfo'] = json_encode($userStatus);
			$addReqInfo['sendMail'] = false;
			$addReqInfo['marketingFlagSent'] = true;
			$addReqInfo['marketingUserKeyId'] = $mappingId;

			$userData['name'] =  $userStatus[0]['displayname']; 
			$userData['mobile'] =  $userStatus[0]['mobile']; 
			$userData['email'] = $email;
			$userData['residenceLocationName'] = $userStatus[0]['cityname'];
			$userData['age'] = $userStatus[0]['age'];
			$userData['gender'] = $userStatus[0]['gender'];
			$userData['highestQualificationName'] = $userStatus[0]['degree']; 
			$userData['fieldOfInterestName'] = $userStudyAbroadInterest->category;
			$userData['countryOfInterest'] = $userStudyAbroadInterest->countryOfInterest;
			$userData['courseStartTime'] = $userStudyAbroadInterest->courseStartTime;
			$userData['nearestMetroCity'] = $userStudyAbroadInterest->nearestMetroCity;
			$userData['modeoffinance'] = $userStudyAbroadInterest->modeoffinance;



			$matchingConsultants = array();
			$queryForMatchingConsultants = "select LMConsultantTable.consultant_id, LMConsultantTable.consultant_email from tLeadKeyValue, LMConsultantTable, LMConsultantCountryTable, LMConsultantCategoryTable, LMConsultantFundSourceTable  where tLeadKeyValue.mappingid=? and tLeadKeyValue.categoryid = LMConsultantCategoryTable.category_id and tLeadKeyValue.countryid = LMConsultantCountryTable.country_id and (tLeadKeyValue.modeoffinance = LMConsultantFundSourceTable.FundSourceInterest or LMConsultantFundSourceTable.FundSourceInterest='Do not matter')and LMConsultantTable.consultant_branceOfficeCity = ? and LMConsultantTable.status='live' and LMConsultantTable.leadStartDate <=date(now()) and LMConsultantTable.leadEndDate>=date(now()) and LMConsultantCategoryTable.consultant_id = LMConsultantTable.consultant_id and LMConsultantTable.consultant_id = LMConsultantCountryTable.consultant_id and LMConsultantTable.consultant_id = LMConsultantFundSourceTable.consultant_id and LMConsultantTable.version = LMConsultantCountryTable.version and LMConsultantTable.version = LMConsultantFundSourceTable.version and LMConsultantTable.version = LMConsultantCategoryTable.version group by LMConsultantTable.consultant_id order by rand() limit 3";
			$query2 = $dbHandle->query($queryForMatchingConsultants, array($mappingId, $residenceCity));
			foreach($query2->result() as $rows){
				array_push($matchingConsultants , $rows );
			}
			if(count($matchingConsultants) < 3)
			{
				$consultantMatchClause = '';
				if(count($matchingConsultants) > 0)
				{
					foreach($matchingConsultants as $row)
					{
						$consultantMatchClause .= $consultantMatchClause == ""? $row->consultant_id:",".$row->consultant_id;
					}
					$consultantMatchClause = "and LMConsultantTable.consultant_id not in (".$consultantMatchClause.") ";
				}
				$queryForMatchingConsultants = "select LMConsultantTable.consultant_id , LMConsultantTable.consultant_email, rand() from tLeadKeyValue, LMConsultantTable, LMConsultantCountryTable, LMConsultantCategoryTable, LMConsultantFundSourceTable  where tLeadKeyValue.mappingid=? and tLeadKeyValue.categoryid = LMConsultantCategoryTable.category_id and tLeadKeyValue.countryid = LMConsultantCountryTable.country_id and (tLeadKeyValue.modeoffinance = LMConsultantFundSourceTable.FundSourceInterest or LMConsultantFundSourceTable.FundSourceInterest='Do not matter')and LMConsultantTable.consultant_branceOfficeCity = tLeadKeyValue.cityid and LMConsultantTable.status='live' and LMConsultantTable.leadStartDate <=date(now()) and LMConsultantTable.leadEndDate>=date(now()) and LMConsultantCategoryTable.consultant_id = LMConsultantTable.consultant_id and LMConsultantTable.consultant_id = LMConsultantCountryTable.consultant_id and LMConsultantTable.consultant_id = LMConsultantFundSourceTable.consultant_id and LMConsultantTable.version = LMConsultantCountryTable.version and LMConsultantTable.version = LMConsultantFundSourceTable.version and LMConsultantTable.version = LMConsultantCategoryTable.version ".$consultantMatchClause." group by LMConsultantTable.consultant_id order by rand() limit 3";
				$query2 = $dbHandle->query($queryForMatchingConsultants, array($mappingId));
				foreach($query2->result() as $rows){
					array_push($matchingConsultants , $rows );
					if(count($matchingConsultants) == 3)
					{
						break;
					}
				}
			}	
			//error_log('Shirish321'.$queryForMatchingConsultants);
			//$query2 = $dbHandle->query($queryForMatchingConsultants);

			foreach($matchingConsultants as $row3){
				$addReqInfo['listing_type_id'] = $row3->consultant_id;
				error_log(print_r($userData,true)."Shirish 456");
				$addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);
				error_log(print_r($userData,true)."Shirish 456");
				$sendMailArray['userData'] = $userData;
				$sendMailArray['subject'] = "Lead: Study Abroad - " . $userStudyAbroadInterest->countryOfInterest."/".$userStudyAbroadInterest->modeoffinance."/".$userStudyAbroadInterest->nearestMetroCity;
				$sendMailArray['mailViewName'] = "marketing/mailSendViewStudyAbroad";
				$sendMailArray['tomail'] = $row3->consultant_email;
				$sendMailArray['body'] = "Hi,<br/><br/>
					We have found a new student on Shiksha who are interested in studying abroad. Kindly contact him/her through phone or email to further explore the interest.<br/><br/>New Study Abroad Students Details : <br/>";
				$mailSendStatus = $this->sendResponseMail($sendMailArray);
            }
            }
		}
		$queryUpdateMarketingSentFlag = "update tLeadInfo set marketingflagsent ='true' where id=?;";
		$query2 = $dbHandle->query($queryUpdateMarketingSentFlag, array($mappingId));
	}
    }	

    function runGenerateLeadCron($request)
    {
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appID = $parameters['0'];
        $page = isset($parameters['1']) ? $parameters['1'] : 'leadcategory';

        $dbHandle = $this->_loadDatabaseHandle('write');
        // Get the max lead id (for the leads to be operated upon 
        $queryCm1 = "select max(id) as id from tLeadInfo ";
        error_log($queryCmd." LKJ");
        $queryCm1 = $dbHandle->query($queryCm1);
        $MAXID = $queryCm1->row()->id;	

        $columnname = 'subcategoryid';
        //Get all the listings grouped on the basis of subcategory 
        $queryCmd = 'select listings_main.listing_type_id,city_id, listing_category_table.category_id,listing_contact_details.contact_email' ;
        if($page == "distancelearning")
        {
            $queryCmd = 'select listings_main.listing_type_id,city_id, categoryBoardTable.parentId as category_id,listing_contact_details.contact_email' ;
        }
        if($page != '' && $page == "distancelearning")
            $queryCmd .= ' from listings_main, listing_category_table, institute_location_table, listing_contact_details,institute,course_details,categoryBoardTable, tPriorityLeads';
        else
            $queryCmd .= ' from listings_main, listing_category_table, institute_location_table, listing_contact_details,institute';

        $queryCmd .= ' where listings_main.listing_type="institute" and listings_main.listing_type_id=listing_category_table.listing_type_id and listing_category_table.listing_type="institute" and listing_category_table.status="live" and listings_main.listing_type_id=institute_location_table.institute_id and listings_main.pack_type >0 and listings_main.pack_type<7 and listings_main.status="live" and listing_contact_details.contact_details_id=institute.contact_details_id and institute.institute_id=listings_main.listing_type_id and institute.status="live" and institute_location_table.status="live"';

        if($page != '' && $page == "distancelearning")
        {
            $queryCmd .= " and course_details.course_type in ('Correspondence','E-learning') and course_details.status = 'live' and listing_category_table.category_id in(80,81,82,83,84,85,86,87,88) and course_details.institute_id = listings_main.listing_type_id and categoryBoardTable.parentId = 3 and categoryBoardTable.boardId = listing_category_table.category_id  and listings_main.listing_type_id = tPriorityLeads.listingId AND now() <= tPriorityLeads.endDate AND tPriorityLeads.status ='live' group by listings_main.listing_type_id,categoryBoardTable.parentId";
            $columnname = 'categoryid';
        }
        else
        {
            $queryCmd .= ' group by listings_main.listing_type_id,listing_category_table.category_id';
        }

        //if the page is management then grouping on the basis of parentId
        if($page == "management")
        {
            $queryCmd = 'select listings_main.listing_type_id,city_id,categoryBoardTable.parentId as category_id,listing_contact_details.contact_email  from listings_main, listing_category_table,categoryBoardTable, institute_location_table, listing_contact_details,institute,tPriorityLeads  where listings_main.listing_type="institute" and listings_main.listing_type_id=listing_category_table.listing_type_id and listing_category_table.listing_type="institute" and listing_category_table.status="live" and listings_main.listing_type_id=institute_location_table.institute_id and listings_main.pack_type >0 and listings_main.pack_type<7 and listings_main.status="live" and listing_contact_details.contact_details_id=institute.contact_details_id and institute.institute_id=listings_main.listing_type_id and institute.status="live" and  institute_location_table.status="live" and categoryBoardTable.boardId = listing_category_table.category_id and categoryBoardTable.parentId = 3 and listings_main.listing_type_id = tPriorityLeads.listingId AND now() <=  tPriorityLeads.endDate AND tPriorityLeads.status ="live"  group by listings_main.listing_type_id,categoryBoardTable.parentId';
            $columnname = 'categoryid';
        }
        error_log($queryCmd." LEADCRON");
        $query = $dbHandle->query($queryCmd);
        $this->load->library(array('lmsLib'));
        $LmsClientObj = new LmsLib();
        $marketingUserKeyIdArr = array();
        foreach($query->result() as $row) {
            if(INSTI_ID != $row->listing_type_id)
            {
                $key = ' and a.pagename not in  ("distancelearningmanagement", "studyAbroad","management","animation") and vc.city_id = '.$dbHandle->escape($row->city_id) .' group by a.userId,c.categoryid,c.subcategoryid,vc.city_id';
                if($page != '' && $page == "distancelearning")
                {
                    $key = " and c.coursetype in ('Correspondence') and a.pagename = 'distancelearningmanagement'";
                }
                if($page != '' && $page == "management")
                {
                    $key = " and a.pagename = 'management'  and vc.city_id = ".$dbHandle->escape($row->city_id) ." group by a.userId,c.categoryid,vc.city_id";
                }
                $queryCmd1 = "select a.*,a.id as leadid ,c.coursetype,e.city_name as residenceLocationName,c.*,f.name as fieldOfInterestName,IFNULL(h.options,a.highestqualification) as highestQualificationName";
                if($page != 'management' && $page != 'distancelearning')
                {
                    $queryCmd1 .= ",g.name as desiredCourseName";
                }

                if($page != "distancelearning")
                    $queryCmd1 .= ",i.city_name as prefferedStudyLoc " ;

                $queryCmd1 .= " from tLeadInfo a inner join tuserflag b on a.userId = b.userId inner join tLeadKeyValue c on c.mappingid = a.Id inner join countryCityTable e on a.residenceLocation = e.city_id inner join categoryBoardTable f on f.boardId = c.categoryid left join tEducationLevel h on h.Educationid = a.highestqualification ";
                if($page != 'management' && $page != 'distancelearning')
                {
                    $queryCmd1 .= " inner join categoryBoardTable g on g.boardId = c.subcategoryId";
                }
                if($page != "distancelearning")
                    $queryCmd1 .= " inner join virtualCityMapping vc on (vc.virtualCityId = c.cityid) inner join countryCityTable i on i.city_id = vc.city_id ";

                $queryCmd1 .= " where a.marketingFlagSent='false' and (b.mobileverified in ('0','1') or (b.hardbounce='0' and b.ownershipchallenged='0' and b.abused='0')) and a.pagename!='StudyAbroad' and a.id <= ".$dbHandle->escape($MAXID)." and c.$columnname = ".$dbHandle->escape($row->category_id).$key;
                error_log($queryCmd1.'LEAD');
            }
            else
            {
                $queryCmd1 = "select a.*,a.id as leadid ,c.coursetype,e.city_name as residenceLocationName,c.*,f.name as fieldOfInterestName,g.name as desiredCourseName,IFNULL(h.options,a.highestqualification) as highestQualificationName from tLeadInfo a inner join tuserflag b on a.userId = b.userId inner join tLeadKeyValue c on c.mappingid = a.Id inner join countryCityTable e on a.residenceLocation = e.city_id inner join categoryBoardTable f on f.boardId = c.categoryid left join categoryBoardTable g on g.boardId = c.subcategoryId left join tEducationLevel h on h.Educationid = a.highestqualification where a.marketingFlagSent = 'ops' and (b.mobileverified in ('0','1') or (b.hardbounce='0' and b.ownershipchallenged='0' and b.abused='0')) and a.pagename!='StudyAbroad' and a.id <= ".$dbHandle->escape($MAXID)." group by a.userId,c.categoryid,c.$columnname";

            }
            $query1 = $dbHandle->query($queryCmd1);
            $userData = array();
            //error_log($queryCmd1." LKJ123");
            foreach($query1->result() as $row1) {
                $leadids = $row1->leadid;
                if($page != 'distancelearning')
                {
                    //get the preferred location as comma separated values for leads
                    if(INSTI_ID == $row->listing_type_id)
                    {
                        $sqlp = "select group_concat(city_name) as preferredLoc,group_concat(tLeadInfo.id) as leadids from tLeadInfo,tLeadKeyValue,countryCityTable where mappingid = tLeadInfo.id and userid = ? and marketingFlagSent = 'ops' and cityid <> 0 and countryCityTable.city_id = tLeadKeyValue.cityid";
                    }
                    else
                    {
                        $sqlp = "select group_concat(city_name) as preferredLoc,group_concat(tLeadInfo.id) as leadids from tLeadInfo,tLeadKeyValue,countryCityTable where $columnname = ".$dbHandle->escape($row->category_id)." and mappingid = tLeadInfo.id and userid = ? and marketingFlagSent = 'false' and cityid <> 0 and countryCityTable.city_id = tLeadKeyValue.cityid";
                    }				
                    $queryp = $dbHandle->query($sqlp, array($row1->userid));
                    $rowp = $queryp->row();
                    $leadids = $rowp->leadids;
                }
                $email = $row1->email;
                $queryCmdInsert = "select * from shiksha.tuser where shiksha.tuser.email=?";
                $query2 = $dbHandle->query($queryCmdInsert, array($email));
                foreach($query2->result() as $row2) {
                    $password = $row2->password;
                }
                $cookie = $email."|".$password;
                error_log($cookie." LKJ");
                $userStatus = $this->cookie($cookie);
                $addReqInfo['listing_type'] = "institute";
                $addReqInfo['listing_type_id'] = $row->listing_type_id;
                $addReqInfo['displayName'] = $row1->displayname;
                $addReqInfo['contact_cell'] = $row1->mobile;
                $addReqInfo['userId'] = $row1->userid;
                $addReqInfo['contact_email'] = $row1->email;
                $addReqInfo['action'] = "marketingPage";
                $addReqInfo['userInfo'] = json_encode($userStatus);
                $addReqInfo['sendMail'] = false;
                $addReqInfo['marketingFlagSent'] = true;
                $addReqInfo['marketingUserKeyId'] = $row1->leadid;
                $addLeadStatus = $LmsClientObj->insertLead(1,$addReqInfo);

                $userData['name'] = $row1->displayname;
                $userData['mobile'] = $row1->mobile;
                $userData['email'] = $row1->email;
                $userData['residenceLocationName'] = $row1->residenceLocationName;
                $userData['age'] = $row1->age;
                $userData['gender'] = $row1->gender;
                $userData['prefferedStudyLoc'] = ($page != "distancelearning") ? $rowp->preferredLoc : '';
                $userData['highestQualificationName'] = $row1->highestQualificationName;
                $userData['fieldOfInterestName'] = $row1->fieldOfInterestName;
                $userData['desiredCourseName'] = $row1->desiredCourseName;

                array_push($marketingUserKeyIdArr,$leadids);
                if(trim($row->contact_email) == "") {
                    continue;
                }
                $sendMailArray = array();
                $sendMailArray['userData'] = $userData;
                if($page != '' && $page == "distancelearning")
                    $sendMailArray['subject'] = "Lead:Distance Learning - MBA | " . $row1->residenceLocationName;
                else	
                    $sendMailArray['subject'] = "New Student Alert";
                $sendMailArray['mailViewName'] = "marketing/mailSendView";
                $sendMailArray['tomail'] = $row->contact_email;
                $sendMailArray['body'] = "Hi,<br/><br/>
                    We have found a new student on Shiksha whose education interest matches with courses offered by your institute. Kindly contact him/her through phone or email to further explore his/her admission interest.<br/>";
                //               error_log("LKJ ".print_r($userData,true));
                $mailSendStatus = $this->sendResponseMail($sendMailArray);
            }
        }

        $marketingUserKeyIdStr = "";
        //	    if(count($marketingUserKeyIdArr) > 0) {

	$key1 = ' pagename != "animation"';
        if($page == "distancelearning")
            $key1 .= ' and pagename = "distancelearningmanagement"';
        else
            $key1 .= ' and pagename != "distancelearningmanagement"';
        if($page == "management")
            $key1 .= ' and pagename = "management"';
        else
            $key1 .= ' and pagename != "management"'; 	  
        $marketingUserKeyIdStr = implode(",",$marketingUserKeyIdArr);
        $queryCmd2 = 'update tLeadInfo set marketingFlagSent="true" where pagename!="StudyAbroad" and marketingFlagSent = "false" and '.$key1.' and id <= ?';
        error_log($queryCmd2.'QUERYCMDNEHA');
        $query2 = $dbHandle->query($queryCmd2, array($MAXID));
        $queryCmd2 = 'update tLeadInfo set marketingFlagSent="opssent" where pagename!="StudyAbroad" and marketingFlagSent = "ops" and '.$key1.' and id <= ?';
        error_log($queryCmd2.'QUERYCMDNEHA');
        $query2 = $dbHandle->query($queryCmd2, array($MAXID));
        //	    }
    }

    function sendResponseMail($data){
	    //        error_log("LKJ ".print_r($data,true));
	    $this->load->library(array('alerts_client'));
	    $mail_client = new Alerts_client();
	    $subject = $data['subject'];
	    //error_log("LKJ ".print_r($data,true));
	    $content = $data['body'].$this->load->view($data['mailViewName'],$data,true)."<br/><br/>Regards,<br/>Shiksha Team";
	    $content = '<div style="font-family:Arial,Helvetica,sans-serif">'. $content .'</div>';
	    $ccmail = 'response@shiksha.com';
	    if(strlen($data['tomail'])>0){
		    $data['tomail'] =$data['tomail'];
		    $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html",$ccmail);
		    $data['tomail'] =$ccmail;
		    $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html",$ccmail);
	    }
	    else{
		    $data['tomail'] =$ccmail;
		    $response=$mail_client->externalQueueAdd("12",ADMIN_EMAIL,$data['tomail'],$subject,$content,$contentType="html",$ccmail);
	    }
	    return $response;
    }


    function cookie($value)
    {
        error_log("SENDMAILS1".$value);
        return($this->checkUserValidation($value));
    }


    function registerUserForLead($request) 
    {
	    $parameters = $request->output_parameters(FALSE,FALSE);
	    $appID = $parameters['0'];
	    $userId = $parameters['1'];
	    $userData = $parameters['2'];
	    $LeadInterest = json_decode($parameters['3'],true);
	    $updateuserinfo = json_decode($parameters['4'],true);
	    $keyvalue = json_decode($parameters['5'],true);
	    $flag = $parameters['6'];

	    $userArr = json_decode($userData,true);
	    error_log("UIO ".print_r($userArr,true));
	    $userDataUpdateArr = json_decode($userDataUpdate,true);

	    $dbHandle = $this->_loadDatabaseHandle('write');

	    $this->load->library('Register_client');
	    $Register_client = new Register_client();
	
	    //If already registered user comes again to marketing page then update the info in tuser tabl
/*	    if($flag == "update")
		    $response = $Register_client->updateUserGen($appId,$updateuserinfo,'userId',$userId,'');*/

//20 % hack not be included anymore
	    // If the already resgistered user comes then update his user interest in tuserInterest table
/*	    $msgArray = $response;
	    $prefcity  = array();
	    foreach ($LeadInterest as $key=>$value)
	    {
		    if($value != '')
		    {
			    $datavalue = explode(',',$value);
			    if($key == "city")
			    {
				    $prefcity = $datavalue;;
			    }
			    if($flag == 'update')
				    $response = $Register_client->updatetUserInterest($appId,$key,$userId,json_encode($datavalue));
		    }
	    }*/
	    
	    //Hack for 20% leads -- 20% of leads from management page belonging to delhi and ncr regions to be sent to ops
		
	    $opsleadflag = false;
	    $arr = array('74','84','87','95','161','1616','10223');
	    error_log($opsleadflag.'LEADOPSFLAG');
	    for($j = 0;$j <count($arr);$j++)
	    {
		    if(in_array($arr[$j],$prefcity))
		    {
			   // $opsleadflag = true;
			    $opsleadflag = false;
	    		    error_log($opsleadflag.'LEADOPSFLAG');
			    break;
		    }
	    }
	    if(($userArr['mPageName'] == 'management' || $userArr['mPageName'] == 'management_2') && $opsleadflag == true)
	    {
		    $queryCmd2 = "select mod(count(distinct a.id)+1,5) as count from tLeadInfo a inner join tLeadKeyValue b on a.id = b.mappingid where b.cityid in(10223,74,84,87,95,161,1616) and a.pagename in('management','management_2')";
		    error_log($queryCmd2);
		    $query2 = $dbHandle->query($queryCmd2);
		    $countrow = $query2->row();
		    $countofmanagement = $countrow->count;

		    if($countofmanagement == 0) 
		    {
			    $userArr['marketingFlag'] = 'ops';	
		    }
	    }   
		
	    // Insert leads into marketing lead table
	    $queryCmd1 = 'insert into `tLeadInfo` values ("", ?, ?,now(), ?,"'. mysql_escape_string($userArr['displayName']).'", ?, ?, ?, ?, ?, ?)';
			    error_log($queryCmd1.'LEADINSERTQUERY');
			    $query1 = $dbHandle->query($queryCmd1, array($userArr['marketingFlag'], $userArr['mPageName'], $userId, $userArr['email'], $userArr['age'], $userArr['highestQualification'], $userArr['gender'], $userArr['residenceLoc'], $userArr['mobile']));
			    $mappingid = $dbHandle->insert_id();
			    error_log($mappingid.'MAPPINGID');
			    $unicount = 0;

			    for($i = 0; $i < count($keyvalue); $i++)
			    {
			    // Check if the lead already exists based on user Interest and his contact information
			    $sql = 'select * from tLeadInfo a inner join tLeadKeyValue b on a.id = b.mappingid where a.userId = ? and b.categoryid = ? and b.subcategoryid = ? and b.countryid = ? and b.cityid = ? and b.coursetype = ? and a.mobile = ? and a.email = ?';
			    error_log($sql);
			    $query = $dbHandle->query($sql, array($userId, $keyvalue[$i]['category'], $keyvalue[$i]['subcategory'], $keyvalue[$i]['country'], $keyvalue[$i]['city'], $keyvalue[$i]['course'], $userArr['mobile'], $userArr['email']));
			    error_log($query->num_rows().'NUMROWS');
			    if($query->num_rows() >= 1)
			    {
			    $unicount++;
			    }
			    else	
			    {
			    $queryCmd2 = 'insert into tLeadKeyValue values("", ?, ?, ?, ?, ?, ?, ?, ?)';
			    error_log($queryCmd2.'INSERTQUERY');
			    $query1 = $dbHandle->query($queryCmd2, array($mappingid, $keyvalue[$i]['category'], $keyvalue[$i]['subcategory'], $keyvalue[$i]['country'], $keyvalue[$i]['city'], $keyvalue[$i]['course'], $keyvalue[$i]['modeoffinance'], $keyvalue[$i]['courseStartTime']));	
			    }
			    error_log('UNICOUNT'.'unicount');
			    }
		// Delete the inserted lead if it already exists
	    if($unicount == count($keyvalue))
	    {
		    $sql = "delete from tLeadInfo where id  = ?";
		    error_log($sql);
		    $query1 = $dbHandle->query($sql, array($mappingid));	
	    }


	    $response = array($msgArray,'string');
	    return $this->xmlrpc->send_response($response);
    }
    }
?>
