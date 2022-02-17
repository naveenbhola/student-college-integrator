<?php
class CRMFeedback_Server extends MX_Controller
{
    function index()
    {

        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('userconfig');

        $config['functions']['getCounsellorList'] = array('function' => 'CRMFeedback_Server.getCounsellorList');
        $config['functions']['getlistingdetails'] = array('function' => 'CRMFeedback_Server.getlistingdetails');
        $config['functions']['getinstituteids'] = array('function' => 'CRMFeedback_Server.getinstituteids');
        $config['functions']['getlistingdetailsforlistingtypeid'] = array('function' => 'CRMFeedback_Server.getlistingdetailsforlistingtypeid');
        $config['functions']['getcourselist'] = array('function' => 'CRMFeedback_Server.getcourselist');
        $config['functions']['EnterpriseUserRegisterFeedback'] = array('function' => 'CRMFeedback_Server.EnterpriseUserRegisterFeedback');


		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}


    function getlistingdetails($request)
    {
        $parameters = $request->output_parameters();
        $userid = $parameters['1'];
        $institute_ids = array();
        $course_ids = array();
        $listing_details = array();

        $dbHandle = $this->getDbHandler();



        $totalinstitutes = $this->getrespectivecourses($userid);

        foreach ($totalinstitutes as $institute)
        {
            $institute_ids[] = $institute['institute_id'];
            $course_ids[] = $institute['course_id'];
        }   


        if(count($institute_ids))
        {

            if(count($institute_ids))
			{
				
				$query1 = "SELECT DISTINCT lm.pack_type,i.institute_id,i.institute_name,lm.listing_seo_url,ct.name as country,cct.city_name as city 
											FROM institute i
											LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live')
											LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
											LEFT JOIN countryTable ct ON countryId = ilt.country_id
											LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
											WHERE i.institute_id IN (".implode(',',$institute_ids).")
											AND i.status = 'live' ";
			
				
				$query1 = $dbHandle->query($query1);
				$resultSet1 = $query1->result_array();
								
				$i=0;
				foreach ($resultSet1 as $row)
				{
					$listing_details[$row['institute_id']]['institute_id'] = $row['institute_id'];
					$listing_details[$row['institute_id']]['institute_name'] = $row['institute_name'];
					$listing_details[$row['institute_id']]['url'] = $row['listing_seo_url'];
					$listing_details[$row['institute_id']]['city'] = $row['city'];
					$listing_details[$row['institute_id']]['country'] = $row['country'];
				$i++;
				}
				
				
			}
                        
                        
			
			if(count($course_ids))
			{
				$query2 = "SELECT cd.course_id,lm.pack_type,cd.courseTitle,cd.institute_id,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url 
											FROM course_details cd
											LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live')
											WHERE cd.course_id IN (".implode(',',$course_ids).")
											AND cd.status = 'live' ";
				$query2 = $dbHandle->query($query2);
				$resultSet2 = $query2->result_array();

            $query1 = $dbHandle->query($query1);
            $resultSet1 = $query1->result_array();

            $i=0;
            foreach ($resultSet1 as $row)
            {
                $listing_details[$row['institute_id']]['institute_id'] = $row['institute_id'];
                $listing_details[$row['institute_id']]['institute_name'] = $row['institute_name'];
                $listing_details[$row['institute_id']]['url'] = $row['listing_seo_url'];
                $listing_details[$row['institute_id']]['city'] = $row['city'];
                $listing_details[$row['institute_id']]['country'] = $row['country'];
                $i++;
            }


        }



        if(count($course_ids))
        {
            $query2 = "SELECT cd.course_id,lm.pack_type,cd.courseTitle,cd.institute_id,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url 
                FROM course_details cd
                LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live')
                WHERE cd.course_id IN (".implode(',',$course_ids).")
                AND cd.status = 'live' ";
            $query2 = $dbHandle->query($query2);
            $resultSet2 = $query2->result_array();



            $j=0;
            foreach ($resultSet2 as $row)
            {
                $listing_details[$row['institute_id']]['course_id'] = $row['course_id'];
                $listing_details[$row['institute_id']]['course_name'] = $row['courseTitle'];
                $listing_details[$row['institute_id']]['course_type'] = $row['course_type'];
                $listing_details[$row['institute_id']]['course_duration'] = $row['duration_value'];
                $listing_details[$row['institute_id']]['course_duration_unit'] = $row['duration_unit'];
                $listing_details[$row['institute_id']]['fees_value'] = $row['fees_value'];
                $listing_details[$row['institute_id']]['fees_unit'] = $row['fees_unit'];
                $listing_details[$row['institute_id']]['course_url'] = $row['listing_seo_url'];
                $listing_details[$row['institute_id']]['packtype'] = $row['pack_type'];
                $j++;
            }
        }
        error_log("aaaaa".print_r($listing_details,true));

        $response = array();


        $msgArray = array();
        foreach ($listing_details as $key=>$row){
            $msgArray[$key] =  $row;//close array_push
        }



        $response = array(json_encode($msgArray),'string');

        return $this->xmlrpc->send_response($response);

    }
	}



    function getrespectivecourses($userid)
    {

        //connect DB
        $dbHandle = $this->getDbHandler();


        $query = "SELECT c.institute_id, c.course_id FROM  `course_details` c, tempLMSTable WHERE c.course_id = tempLMSTable.listing_type_id AND tempLMSTable.listing_type =  'course' AND c.status =  'live' AND tempLMSTable.listing_subscription_type='paid' AND tempLMSTable.userid =?";

        $query = $dbHandle->query($query,array($userid));
        error_log("query".$query);


        $resultSet = $query->result_array();
        return $resultSet;


    }


    function getinstituteids($request)
    {
        $parameters = $request->output_parameters();
        $formVal = $parameters['1'];



        //connect DB
        $dbHandle = $this->getDbHandler();
        $queryCmd = "SELECT group_concat(c.institute_id) ids FROM  `course_details` c, tempLMSTable WHERE c.course_id = tempLMSTable.listing_type_id AND tempLMSTable.listing_type =  'course' AND c.status =  'live' AND tempLMSTable.listing_subscription_type='paid' AND tempLMSTable.userid =?";

        $queryCmd = $dbHandle->query($queryCmd,array($formVal));

        $msgArray1=array();
        foreach ($queryCmd->result_array() as $row)
        {
            array_push($msgArray1,array($row,'struct'));
        }

        //return $this->xmlrpc->send_response ($msgArray1);

        $response = array();
        array_push($response,array(array('0' => array($msgArray1,'struct')),'struct'));
        $resp = array($response,'struct');
        return $this->xmlrpc->send_response ($resp);



    }

    function EnterpriseUserRegisterFeedback($request)
    {

        $parameters = $request->output_parameters();
        $formVal = $parameters['1'];
        $dbHandle = $this->getDbHandler('write');
        if($dbHandle == ''){
            log_message('error','adduser can not create db handle');
        }
        $date = date("y.m.d:h:m:s");

        error_log($date);
        $data =array();
        $data = array(
            'LeadId'=>$formVal['userid'],
            'ClientId'=>$formVal['crm_clientid'],
            'Feedback_Score'=>$formVal['score'],
            'Comment'=>$formVal['comments'],
            'Counsellor_Id'=>$formVal['crm_counslarid'],
            'Submitted_On'=>$date
        );

        $queryCmd = 'REPLACE INTO CRM_Feedback (LeadId,ClientId,Feedback_Score,Comment,Counsellor_Id,Submitted_On) VALUES (?,?,?,?,?,?) ';

        log_message('debug', 'Insert query cmd is ' . $queryCmd);
        $query = $dbHandle->query($queryCmd,array($formVal['userid'],$formVal['crm_clientid'],$formVal['score'],$formVal['comments'],$formVal['crm_counslarid'],$date));


        $response ="true";
        return $this->xmlrpc->send_response($response);
    }

    function getcourselist($request)
    {
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $Id = $parameters['1'];
	error_log(print_r($parameters,true));
        $dbHandle = $this->getDbHandler();
        if($dbHandle == ''){
            log_message('error','adduser can not create db handle');
        }


        if(!empty($Id)){
            $queryCmd1 = "select distinct listing_type from listings_main where listings_main.listing_type_id  IN (".$Id.")";

            $queryCmd1 = $dbHandle->query($queryCmd1);





            foreach ($queryCmd1->result_array() as $row){
                $id = $row['listing_type'];


                if($id == 'course'){

                    $queryCmdStat = "SELECT listing_type_id as course_id,c.institute_id FROM  `listings_main` ,`course_details` c WHERE  `listing_type` =  'course' and  c.course_id = listing_type_id AND listings_main.status =  'live' and c.status = 'live'   and listings_main.listing_type_id IN (".$Id.")";

                }
                elseif($id == 'institute'){

                    $queryCmdStat = "SELECT c.course_id,c.institute_id FROM  `listings_main` ,`course_details` c WHERE `listing_type` =  'institute' and  c.institute_id = listing_type_id AND listings_main.status =  'live' and c.status = 'live'  and listings_main.listing_type_id IN (".$Id.")";

                    error_log("\n Listing \n" . $queryCmdStat);


                }

            }   


            $queryCmd = $dbHandle->query($queryCmdStat);



            $msgArray1=array();
            foreach ($queryCmd->result_array() as $row)
            {
                array_push($msgArray1,array($row,'struct'));
            }

        }

        else
        {
            $msgArray1 = array();	
        }
/*
				$query1 = "SELECT DISTINCT lm.pack_type,i.institute_id,i.institute_name,lm.listing_seo_url,ct.name as country,cct.city_name as city 
											FROM institute i
											LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live')
											LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
											LEFT JOIN countryTable ct ON countryId = ilt.country_id
											LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
											WHERE i.institute_id = ".$institute_ids."
											AND i.status = 'live' ";
			
					
				$query1 = $dbHandle->query($query1);
				$resultSet1 = $query1->result_array();
								
				$i=0;
				foreach ($resultSet1 as $row)
				{
					$listing_details[$i]['institute_id'] = $row['institute_id'];
					$listing_details[$i]['institute_name'] = $row['institute_name'];
					$listing_details[$i]['url'] = $row['listing_seo_url'];
					$listing_details[$i]['city'] = $row['city'];
					$listing_details[$i]['country'] = $row['country'];
				$i++;
				}
				
				
			
			
				$query2 = "SELECT cd.course_id,lm.pack_type,cd.courseTitle,cd.institute_id,cd.course_type,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url 
											FROM course_details cd
											LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live')
											WHERE cd.course_id = ".$course_ids."
											AND cd.status = 'live' ";
				$query2 = $dbHandle->query($query2);
				$resultSet2 = $query2->result_array();
*/
        $resp = array($msgArray1,'struct');
        return $this->xmlrpc->send_response ($resp);

    }


    function getlistingdetailsforlistingtypeid($request)
    {
        $parameters = $request->output_parameters();
        $course_ids = $parameters['0'];	 
        $institute_ids =  $parameters['1'];


        $dbHandle = $this->getDbHandler();

        $query1 = "SELECT lm.pack_type,i.institute_id,i.institute_name,lm.listing_seo_url,ct.name as country,cct.city_name as city 
            FROM institute i
            LEFT JOIN listings_main lm ON (lm.listing_type_id = i.institute_id AND lm.listing_type = 'institute' AND lm.status = 'live')
            LEFT JOIN institute_location_table ilt ON (ilt.institute_id = i.institute_id AND ilt.status = 'live')
            LEFT JOIN countryTable ct ON countryId = ilt.country_id
            LEFT JOIN  countryCityTable cct ON cct.city_id = ilt.city_id
            WHERE i.institute_id = ?
            AND i.status = 'live' ";


        $query1 = $dbHandle->query($query1, array($institute_ids));
        $resultSet1 = $query1->result_array();

        $i=0;
        foreach ($resultSet1 as $row)
        {
            $listing_details[$i]['institute_id'] = $row['institute_id'];
            $listing_details[$i]['institute_name'] = $row['institute_name'];
            $listing_details[$i]['url'] = $row['listing_seo_url'];
            $listing_details[$i]['city'] = $row['city'];
            $listing_details[$i]['country'] = $row['country'];
            $i++;
        }




        $query2 = "SELECT cd.course_id,lm.pack_type,cd.courseTitle,cd.institute_id,cd.duration_value,cd.duration_unit,cd.fees_value,cd.fees_unit,lm.listing_seo_url 
            FROM course_details cd
            LEFT JOIN listings_main lm ON (lm.listing_type_id = cd.course_id AND lm.listing_type = 'course' AND lm.status = 'live')
            WHERE cd.course_id = ?
            AND cd.status = 'live' ";

        $query2 = $dbHandle->query($query2,array($course_ids));
        $resultSet2 = $query2->result_array();



        $j=0;
        foreach ($resultSet2 as $row)
        {
            $listing_details[$j]['course_id'] = $row['course_id'];
            $listing_details[$j]['course_name'] = $row['courseTitle'];
            $listing_details[$j]['course_type'] = $row['course_type'];
            $listing_details[$j]['course_duration'] = $row['duration_value'];
            $listing_details[$j]['course_duration_unit'] = $row['duration_unit'];
            $listing_details[$j]['fees_value'] = $row['fees_value'];
            $listing_details[$j]['fees_unit'] = $row['fees_unit'];
            $listing_details[$j]['course_url'] = $row['listing_seo_url'];
            $listing_details[$j]['packtype'] = $row['pack_type'];
            $j++;
        }


        $response = array();


        $msgArray = array();
        foreach ($listing_details as $key=>$row){
            $msgArray[$key] =  $row;//close array_push
        }

        $response = array(json_encode($msgArray),'string');

        return $this->xmlrpc->send_response($response);

    }
    /**
     * This method adds new marketing page entry in the database.
     * $dbHandle = $this->getDbHandler('write');
     * @access	public
     * @return  object
     */
    private function getDbHandler($flag='read')
    {
        $dbHandle = NULL;
        $this->dbLibObj = DbLibCommon::getInstance('Enterprise');
        if ( $flag == 'read')
        {
            $dbHandle = $this->_loadDatabaseHandle();   //For Read Handle
        }
        else
        {
            $dbHandle = $this->_loadDatabaseHandle('write');   //For Write Handle
        }
        return $dbHandle;
    }
}
