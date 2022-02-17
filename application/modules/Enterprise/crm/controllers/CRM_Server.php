<?php

/**
 * Description of CRM_Server
 * @author ashish mishra
 */
class CRM_Server extends MX_Controller {

    private $SHIKSHADB = null;
    private $routine_time = 0;
    private $cnt = 0;
    private $CRMDB = null;

    function index() {
        $this->dbLibObj = DbLibCommon::getInstance('crm');
        $this->CRMDB = $this->_loadDatabaseHandle('write');
	#error_log("::CRM::" . print_r($this->CRMDB,true));
        $this->load->library(array('xmlrpc', 'xmlrpcs', 'LDB_Client', 'Sugar_rest'));
        $this->load->helper('url');
        $appID = 1;
        $this->dbLibObj = DbLibCommon::getInstance('User');
	$this->SHIKSHADB = $this->_loadDatabaseHandle('read');
        $config['functions']['exportLeadid'] = array('function' => 'CRM_Server.exportLeadid');
        $config['functions']['exportByCron'] = array('function' => 'CRM_Server.exportByCron');
        $config['functions']['getCounsellorList'] = array('function' => 'CRM_Server.getCounsellorList');
        $config['functions']['dumpCrmResponseData'] = array('function' => 'CRM_Server.dumpCrmResponseData');
        $config['functions']['getPendingLeadsForResponse'] = array('function' => 'CRM_Server.getPendingLeadsForResponse');
        $config['functions']['responseSentSuccess'] = array('function' => 'CRM_Server.responseSentSuccess');
        $config['functions']['responsesalreadymadeforuser'] = array('function' => 'CRM_Server.responsesalreadymadeforuser');
        $config['functions']['getPastResponses'] = array('function' => 'CRM_Server.getPastResponses');
        $config['functions']['getAllocatedUser'] = array('function' => 'CRM_Server.getAllocatedUser');
	$config['functions']['getLoginInfo'] = array('function' => 'CRM_Server.getLoginInfo');

        $args = func_get_args(); $method = $this->getMethod($config,$args);
        
        return $this->$method($args[1]);
    }

    function dumpCrmResponseData($request) {

        /* get values from RPC array */
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];
        $listingid = $parameters['1'];
        $email = $parameters['2'];
        $mobile = $parameters['3'];
        $name = $parameters['4'];
        $status = $parameters['5'];
        $counsellorid = $parameters['6'];
        $createdate = date("Y-m-d H:i:s");
        $updatedate = date("Y-m-d H:i:s");

        /* make array to build insertion array */
        $array = array();

        $array['leadId'] = $userid;
        $array['lead_mobile'] = $mobile;
        $array['lead_email'] = $email;
        $array['lead_name'] = $name;
        $array['lead_listing_type_id'] = $listingid;
        $array['updated_on'] = $updatedate;
        $array['created_on'] = $createdate;
        $array['status'] = 'to_be_processed';
        $array['counsellorId'] = $counsellorid;
        /* Final insertion */
        $queryCmd = $this->CRMDB->insert_string('CRM_Response_Data', $array);
	$queryCmd = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $queryCmd);
        error_log('SA :: New Entry CRM_Response_Data SQL ' . $queryCmd);
        $query = $this->CRMDB->query($queryCmd);
        $response = array('1', 'int');
        return $this->xmlrpc->send_response($response);
    }

    function exportLeadid($request) {
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];
        $ret = $this->_exportLeadid(array(0 => array("userid" => $userid)));
        $response = array($ret, 'int');
        return $this->xmlrpc->send_response($response);
    }

    private function _mstime() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float) $usec + (float) $sec);
    }

    private function _exportLeadid($userids) {
        $i = 0;
        $userids_csv = "";
        foreach ($userids as $row) {
            $userids_csv .= (($i++ > 0) ? "," : "") . $row["userid"];
        }
        if (empty($userids_csv))
            return 0;
        error_log("CRM: Getting user details for " .count($userids)." users.");
        $appId = 1;
        $time = $this->_mstime();
        $json = $this->ldb_client->sgetUserDetails($appId, $userids_csv);
        $userArr = json_decode($json, true);
        $this->routine_time += ($this->_mstime() - $time);
        error_log("CRM: Time taken in getting asduser details for " .count($userids)." users = ".$this->routine_time);
        $this->load->library("User");
        $i = 1;
        foreach ($userids as $row) {
            $userid = $row["userid"];
            error_log("CRM: Picked Leadid $userid for porting");
            
		$User = new User($userArr[$userid]);
            	error_log(" CRM : @@@ after new user ");
		$parameters = $User->getArrayForSugar();
		//print_r($parameters);die;
            $result = $this->sugar_rest->set("Leads", $parameters);
            $error = $this->sugar_rest->get_error();
            if ($error !== FALSE) {
                error_log("CRM: $i Porting using REST for Leadid $userid failed");
            } else {
		error_log("$$$$$$ CRM ");
        		$this->CRMDB->query("replace into CRMTransferCounters values(0,?,CURRENT_TIMESTAMP)",array($userid));
                error_log("CRM: $i Leadid $userid ported using REST successfully");
            }
            $i++;
            //exit;
        }
        error_log("CRM: Total time consumed in sgetUserDetails : " . $this->routine_time);
        return 1;
    }

    function exportByCron($request) {
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];
        if (!empty($userid)) {
            $this->exportLeadid($userid);
            return;
        }
        $res = $this->CRMDB->query("select * from CRMTransferCounters where for_client = 0");
        $row = $res->row();
        error_log("CRM: Porting to be done for ".$row->latest_leadid." onwards.");
	$sql = "SELECT u.userid
FROM tuser u, tuserflag f, tUserPref p, tCourseSpecializationMapping t
WHERE u.userid = f.userid
AND p.userid = u.userid
AND p.desiredcourse = t.specializationid
AND (
t.specializationid =2
OR t.parentid =2
)
AND isLDBUser = 'YES'
AND mobileverified = '1'
AND u.userid > ?

union
SELECT u.userid
FROM tuser u, tuserflag f, tUserPref p
WHERE u.userid = f.userid
AND p.userid = u.userid
AND isLDBUser = 'YES'
AND mobileverified = '1'
AND p.extraflag = 'studyabroad'
AND p.desiredcourse
IN ( 358, 370 )
AND u.userid > ? ORDER BY userid LIMIT 500";

        $ids = $this->SHIKSHADB->query($sql,array($row->latest_leadid, $row->latest_leadid));
           
        $userids = $ids->result_array();
        $this->_exportLeadid($userids);
        $response = array($userid["userid"], 'int');
        return $this->xmlrpc->send_response($response);
    }

    function getCounsellorList() {
        $error = $this->sugar_rest->get_error();
        if ($error !== FALSE) {
            error_log(" amishra ERROR: " . $error['name'] . "\n");
        }
        $result = $this->sugar_rest->get("Users", array('id', 'user_name', 'first_name', 'last_name'));
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function getPendingLeadsForResponse() {
        error_log("asdasda");
        $res = $this->CRMDB->query("select * from CRM_Response_Data where status = 'to_be_processed' order by id asc limit 1");
        $arr = $res->result_array();
        $result = array();
        $i = 0;
        foreach ($arr as $row) {
            $result[$i]['userid'] = $row['leadId'];
            $result[$i]['id'] = $row['id'];
            $result[$i]['contact_cell'] = $row['lead_mobile'];
            $result[$i]['contact_email'] = $row['lead_email'];
            $result[$i]['displayName'] = $row['lead_name'];
            $result[$i]['counsellorId'] = $row['counsellorId'];
            $result[$i++]['listing_type_id'] = $row['lead_listing_type_id'];
        }
        //$this->CRMDB->query("update CRM_Response_Data set status = 'in_process' where id = " . $arr[0]['id']);

        error_log("ashish in server" . print_r($result, true));
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function responseSentSuccess($request) {
        $parameters = $request->output_parameters();
        $id = $parameters['0'];
        $status = $parameters['1'];
                
        $this->CRMDB->query("update CRM_Response_Data set status = ? where id = ?",array($status,$id));
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }

    function responsesalreadymadeforuser($request) {
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];

        $res = $this->CRMDB->query("select group_concat(lead_listing_type_id) as list from CRM_Response_Data where leadId = ?",array($userid));

        $result = $res->result_array();
        $response = array(json_encode($result), 'string');
        return $this->xmlrpc->send_response($response);
    }
    
    function getPastResponses($request){
        $parameters = $request->output_parameters();
        $userid = $parameters['0'];
        $this->load->model("crmmodel");
        $responses = $this->crmmodel->getPastResponses($userid);
        return $this->xmlrpc->send_response(json_encode($responses));
    }

    function getAllocatedUser($request){
        $parameters = $request->output_parameters();
        $prefArr = json_decode($parameters['0']);
        $this->load->model("crmmodel");
        $counsellor_id = $this->crmmodel->getAllocatedUser($prefArr);
        return $this->xmlrpc->send_response($counsellor_id);
    }

    function getLoginInfo($request){
        $parameters = $request->output_parameters();
        $userIdArr = json_decode($parameters['0']);
        $this->load->model("crmmodel");
        $counsellor_id = $this->crmmodel->getLoginInfo($userIdArr);
        return $this->xmlrpc->send_response($counsellor_id);
    }

}

