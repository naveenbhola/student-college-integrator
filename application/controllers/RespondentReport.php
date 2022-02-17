<?php

class RespondentReport extends MX_Controller
{
    function index($startDate,$endDate)
    {
        echo "This is not used any more";exit;
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle();

        $start_date = $startDate ? $startDate : date('Y-m-d',strtotime('-1 day'));
        $end_date = $endDate ? $endDate : date('Y-m-d');

        $sql = "SELECT a.listing_type_id,a.userId,a.submit_date,b.username,a.action from tempLMSTable a inner join listings_main b on (a.listing_type = b.listing_type and a.listing_type_id = b.listing_type_id) WHERE a.listing_subscription_type='paid' and a.submit_date > '".$start_date."' AND a.submit_date < '".$end_date."' AND a.listing_type = 'course' and b.status = 'live'";
        $query = $dbHandle->query($sql);
        $responses = $query->result_array();

        $courseIds = array();
        $responseUsers = array();
        foreach($responses as $response) {
            $courseIds[] = $response['listing_type_id'];
            $responseUsers[] = $response['userId'];
        }

        $courseIds = array_unique($courseIds);

        $sql = "SELECT clientCourseID,LDBCourseID FROM clientCourseToLDBCourseMapping WHERE clientCourseID IN (".implode(',',$courseIds).") AND status = 'live' GROUP BY clientCourseID";
        $query = $dbHandle->query($sql);
        $results = $query->result_array();

        $clientCourseToLDBCourseMapping = array();
        foreach($results as $result) {
            $clientCourseToLDBCourseMapping[$result['clientCourseID']] = $result['LDBCourseID'];
        }

        $responseUsers = array_unique($responseUsers);
        $sql = "SELECT a.userid,a.userCreationDate,a.quicksignupFlag,b.DesiredCourse,b.ExtraFlag FROM tuser a left join tUserPref b on b.UserId = a.userid WHERE a.userid IN (".implode(',',$responseUsers).")";
        $query = $dbHandle->query($sql);
        $results = $query->result_array();

        $responseUserData = array();
        foreach($results as $result) {
            $responseUserData[$result['userid']] = $result;
        }

        $data =  $this->_displayResponses($responses,$clientCourseToLDBCourseMapping,$responseUserData,2,'Full Time MBA');
        $data .= $this->_displayResponses($responses,$clientCourseToLDBCourseMapping,$responseUserData,52,'B.E./B.Tech.');

	echo $data;
	/*
	$this->load->library("alerts_client");
        $alertClient = new Alerts_client();
	$subject = 'Shiksha.com Daily Respondent Report';
        $alertClient->externalQueueAdd("12",ADMIN_EMAIL,"saurabh.gupta@shiksha.com",$subject,$report,"html");
	$alertClient->externalQueueAdd("12",ADMIN_EMAIL,"vikas.k@shiksha.com",$subject,$data,"html");
	*/
    }

    function _displayResponses($responses,$clientCourseToLDBCourseMapping,$responseUserData,$LDBCourse,$LDBCourseName)
    {
        $data = "<h1>$LDBCourseName</h1>";
        $data .= "<table border='1' style='font-family:arial; font-size:13px;'><tr><th>Course ID</th><th>Client ID</th><th>User ID</th><th>Action</th><th>Response Time</th><th>Registration Time</th><th>Is AnA User</th><th>Is Lead User</th></tr>";

        foreach($responses as $response) {
            if($clientCourseToLDBCourseMapping[$response['listing_type_id']] == $LDBCourse) {
                $data .= "<tr>";
                $data .= "<td>".$response['listing_type_id']."</td>";
                $data .= "<td>".$response['username']."</td>";
                $data .= "<td>".$response['userId']."</td>";
                $data .= "<td>".$response['action']."</td>";
                $data .= "<td>".$response['submit_date']."</td>";
                $data .= "<td>".$responseUserData[$response['userId']]['userCreationDate']."</td>";
                $isAnAUser = FALSE;
                if($responseUserData[$response['userId']]['quicksignupFlag'] == 'veryshortregistration') {
                    $isAnAUser = TRUE;
                }
                $isLeadUser = FALSE;
		if($responseUserData[$response['userId']]['DesiredCourse'] > 0 || $responseUserData[$response['userId']]['ExtraFlag'] == 'testprep') {
                    $isLeadUser = TRUE;
                }
                $data .= "<td>".($isAnAUser ? 'Yes' : 'No')."</td>";
                $data .= "<td>".($isLeadUser ? 'Yes' : 'No')."</td>";
                $data .= "</tr>";
            }
        }
        $data .= "</table>";
	return $data;
    }
}
