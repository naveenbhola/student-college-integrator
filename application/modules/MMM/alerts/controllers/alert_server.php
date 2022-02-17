<?php


class Alert_server extends MX_Controller {

    /*
     *	index function to recieve the incoming request
     */

    function index(){

		$this->dbLibObj = DbLibCommon::getInstance('Alert');
        //load XML RPC Libs
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('alertconfig');

        error_log_shiksha("Entering server");

        //Define the web services method

        $config['functions']['getMyAlerts'] = array('function' => 'Alert_server.getMyAlerts');

        //$config['functions']['getRelatedAlerts'] = array('function' => 'Alert_server.getRelatedAlerts');

        $config['functions']['getAlert'] = array('function' => 'Alert_server.getAlert');

        $config['functions']['createAlert'] = array('function' => 'Alert_server.createAlert');

        $config['functions']['createEventAlert']=array('function'=>'Alert_server.createEventAlert');

        $config['functions']['getUserAlerts'] = array('function' => 'Alert_server.getUserAlerts');

        $config['functions']['getMyAlertCount'] = array('function' => 'Alert_server.getMyAlertCount');

        $config['functions']['getProductAlerts'] = array('function' => 'Alert_server.getProductAlerts');

        $config['functions']['updateAlert']= array('function' => 'Alert_server.updateAlert');

        $config['functions']['updateEventAlert']= array('function' => 'Alert_server.updateEventAlert');

        $config['functions']['updateState']= array('function' => 'Alert_server.updateState');

        $config['functions']['updateMail']= array('function' => 'Alert_server.updateMail');

        $config['functions']['updateSms']=array('function' => 'Alert_server.updateSms');

        $config['functions']['updateIm']=array('function' => 'Alert_server.updateIm');

        $config['functions']['deleteAlert']=array('function' => 'Alert_server.deleteAlert');

        $config['functions']['createWidgetAlert']= array('function' => 'Alert_server.createWidgetAlert');

        $config['functions']['getWidgetAlert']= array('function' => 'Alert_server.getWidgetAlert');

	    $config['functions']['externalQueueAdd'] = array('function'=>'Alert_server.externalQueueAdd');

	    $config['functions']['addSmsQueueRecord'] = array('function'=>'Alert_server.addSmsQueueRecord');

        $config['functions']['getProductWithTypeAlerts'] = array('function' => 'Alert_server.getProductWithTypeAlerts');

        $config['functions']['getDigestNetwork'] = array('function' => 'Alert_server.getDigestNetwork');

        $config['functions']['getDigestMail'] = array('function' => 'Alert_server.getDigestMail');

	    $config['functions']['performEmailCheck'] = array('function'=>'Alert_server.performEmailCheck');

	    $config['functions']['createAttachment'] = array('function'=>'Alert_server.createAttachment');

	    $config['functions']['getAttachmentId'] = array('function'=>'Alert_server.getAttachmentId');

	    $config['functions']['getCompareUrl'] = array('function'=>'Alert_server.getCompareUrl');

		 $config['functions']['getCompareContent'] = array('function'=>'Alert_server.getCompareContent');


        //initialize
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }


    /**
     * Get all alerts for a user
     * Input : AppId, UserId
     * Output: All alerts of the user
     */
    function getUserAlerts($request)
    {
        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $userId=$parameters['1'];
        //$this->load->library('alertconfig');
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd= "select product_name,alert_name,STATE,MAIL,SMS,IM,alert_id from alert_user_preference,product where user_id=? and alert_user_preference.product_id =product.product_id";
        $query=$dbHandle->query($queryCmd,array($userId));

        log_message('debug', 'getUserAlerts query cmd is ' . $queryCmd);
        error_log_shiksha($queryCmd);
        $msgArray = array();
        foreach ($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
            //if(!isset($msgArray[$row['product_name']]))
            //{
            //  $msgArray[$row['product_name']]=array();
            //}
            //array_push($msgArray[$row['product_name']],array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        error_log_shiksha(print_r($response,true));
        return $this->xmlrpc->send_response($response);
        //echo "<pre>";
        //print_r($response);
        //echo "</pre>";
    }

    /**
     * Get all alerts for a user
     * Input : AppId, UserId, Product
     * Output: All alerts of the user within the product
     */
    function getProductAlerts($request)
    {
        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $user_id=$parameters['1'];
        $product_id=$parameters['2'];
        //$this->load->library('alertconfig');
        //connect DB
       $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd= "select product_name,alert_name,STATE,MAIL,SMS,IM,alert_id from alert_user_preference,product where user_id=? and alert_user_preference.product_id=? and alert_user_preference.product_id =product.product_id";
        $query=$dbHandle->query($queryCmd,array($user_id,$product_id));

        log_message('debug', 'getProductAlerts query cmd is ' . $queryCmd.'; Value');
        error_log_shiksha($user_id.' '.$product_id);
        error_log_shiksha($queryCmd);
        $msgArray = array();
        foreach ($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        error_log_shiksha(print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

    /**
     * Get all alerts for a user for the given product and AlertType
     * Input : AppId, UserId, Product , AlertType
     * Output: List of Alerts which qualify the above criteria
     */
    function getProductWithTypeAlerts($request)
    {
        $parameters = $request->output_parameters();
        $appId=$parameters['0'];
        $user_id=$parameters['1'];
        $product_id=$parameters['2'];
        $alert_type=$parameters['3'];
        //$this->load->library('alertconfig');
        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        if($alert_type!="all")
        {
            $queryCmd= "select alertType,alert_name,alert_id,alert_value_id,filter_id from alert_user_preference,product where user_id=? and alert_user_preference.product_id=? and alert_user_preference.product_id =product.product_id and alertType=?";
            $query=$dbHandle->query($queryCmd,array($user_id,$product_id,$alert_type));

            log_message('debug', 'getProductWithTypeAlerts query cmd is ' . $queryCmd.'; Value');
            error_log_shiksha($user_id.': '.$product_id.': '.$alert_type);
            error_log_shiksha($queryCmd);
        }
        else
        {
            $queryCmd= "select alertType,alert_name,alert_id,alert_value_id,filter_id from alert_user_preference,product where user_id=? and alert_user_preference.product_id=? and alert_user_preference.product_id =product.product_id";
            $query=$dbHandle->query($queryCmd,array($user_id,$product_id));
            log_message('debug', 'getProductWithTypeAlerts query cmd is ' . $queryCmd.'; Value');
            error_log_shiksha($user_id.': '.$product_id.': '.$alert_type);
            error_log_shiksha($queryCmd);
        }
        $msgArray = array();
        foreach ($query->result_array() as $row)
        {
            array_push($msgArray,array($row,'struct'));
        }
        $response = array($msgArray,'struct');
        error_log_shiksha(print_r($response,true));
        return $this->xmlrpc->send_response($response);
    }

/**
* Get all alerts for a user for the
* Input : AppId, UserId
* Output: List of Alerts that user has subcribed to
*/
function getUserMyAlerts($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $userId=$parameters['1'];
    //$this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();
    //$queryCmd = 'select product_name,alert_name,STATE,MAIL,SMS,IM,alert_id,alert_user_preference.rule_id from alert_user_preference,alert_rule,product where user_id=? and alert_user_preference.rule_id=alert_rule.rule_id and alert_rule.product_id =product.product_id and state=\'on\';';
    $queryCmd=" select product_name,alert_name,STATE,mail,SMS,IM,alert_id from alert_user_preference,product where user_id=? and alert_user_preference.product_id =product.product_id and state=\"on\";";
    $query=$dbHandle->query($queryCmd,array($userId));

    log_message('debug', 'getUserAlerts query cmd is ' . $queryCmd);
    error_log_shiksha($queryCmd);
    $msgArray = array();
    foreach ($query->result_array() as $row)
    {
        array_push($msgArray,array($row,'struct'));
        //if(!isset($msgArray[$row['product_name']]))
        //{
        //  $msgArray[$row['product_name']]=array();
        //}
        //array_push($msgArray[$row['product_name']],array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}


/**
 *	Get the number of alerts the user has subscribed to in a given product
 *  Input: AppId, userid and product Id
 *  Count i.e number of alerts
 */
function getMyAlertCount($request)
{
    $parameters = $request->output_parameters();
    $appID=$parameters['0'];
    $user_id=$parameters['1'];
    $product_id=$parameters['2'];

    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();

    if($product_id>0)
    {
        $queryCmd = 'select count(*) count from  alert_user_preference where product_id='.$dbHandle->escape($product_id).' and user_id='.$dbHandle->escape($user_id);

    }
    else
    {
        $queryCmd = 'select count(*) count from alert_user_preference where user_id='.$dbHandle->escape($user_id);
    }

    log_message('debug', 'getMyAlertCount query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    $msgArray = array();
    foreach ($query->result_array() as $row)
    {
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    return $this->xmlrpc->send_response($response);
}


/**
 *	Get the recent topics across all board's for a given country
 *  NOT USED
 *  PLEASE DELETE IT LATER
 */
function getRecentPostedAlerts($request)
{

    $parameters = $request->output_parameters();
    $appID=$parameters['0'];
    $boardId=$this->getBoardChilds($parameters['1']);
    $startFrom=$count=$parameters['2'];
    $count=$parameters['3'];
    $countryId=$parameters['4'];

    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();

    if($countryId>1)
    {
        $queryCmd = 'select * from Table where countryId='.$dbHandle->escape($countryId).' order by creationDate desc  LIMIT '. $startFrom .',' .$count;
    }
    else
    {
        $queryCmd = 'select * from Table order by creationDate desc  LIMIT '. $startFrom .',' .$count;
    }

    log_message('debug', 'getRecentPostedAlerts query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);

    $msgArray = array();
    foreach ($query->result_array() as $row){
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    return $this->xmlrpc->send_response($response);
}

/**
 *	get the details of a perticular Alert
 *  Input: appId, alertId and userId
 *  Output: Send details of the alert, provided the alert is of the prticular user only
 */
function getAlert($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];

    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();

    $queryCmd = 'select * from alert_user_preference where alert_id=? and user_id=?';

    log_message('debug', 'getAlert query cmd is ' . $queryCmd);
    error_log_shiksha('getAlert query cmd is ' . $queryCmd.'; with values:');
    error_log_shiksha(print_r(array($alert_id,$user_id),true));

    $query = $dbHandle->query($queryCmd,array($alert_id,$user_id));
    $msgArray = array();
    foreach ($query->result_array() as $row){
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}

/**
* get the alert details for a given user, product and type
* Input: AppID, userId, productId, alertType , filter_id
* Output: AlertId and the state if alert exists else void.
*/
function getWidgetAlert($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $user_id=$parameters['1'];
    $product_id=$parameters['2'];
    $alertType=$parameters['3'];
    $alert_value_id=$parameters['4'];
    $filter_id=trim($parameters['5']);

    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle();

    $queryCmd = 'select alert_id,state from alert_user_preference where user_id=? and product_id=? and alertType=? and alert_value_id=? and filter_id=?;';

    log_message('debug', 'getWidgetAlert query cmd is ' . $queryCmd);
    error_log_shiksha('getWidgetAlert query cmd is ' . $queryCmd.'; with values:');
    error_log_shiksha(print_r(array($user_id,$product_id,$alertType,$alert_value_id,$filter_id),true));

    error_log_shiksha($filter_id);

    $query = $dbHandle->query($queryCmd,array($user_id,$product_id,$alertType,$alert_value_id,$filter_id));
    $response = array();
    foreach ($query->result_array() as $row)
    {
	$response=array(
              array(
                   'result'=>1,
                   'alert_id'=>$row['alert_id'],
		   'state'=>$row['state']),
           'struct');
    }
    //$response = array($msgArray,'struct');
    if(sizeof($response)==0)
    {
	$response=array(
              array(
                   'result'=>0),
           'struct');
    }
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}


/**
* add a new alert in the alert_user_preference. Insert the record only if the user does not have 5 alerts under the same product. Make this value configurable by using APC caching later.
* Input: AppID, userId, productId, alertType , filter_id, frequency , mail, im and mobile
* Output: AlertId and the state if alert created else false.
*/
function createAlert($request)
    //function createAlert($appId,$product_id,$user_id,$alert_name,$alert_value_id,$frequency,$mail,$sms,$im,$state)
{
    $parameters = $request->output_parameters();
    error_log_shiksha(print_r($parameters,true));
    $appId=$parameters['0'];
    $product_id=$parameters['1'];
    $user_id=$parameters['2'];
    $alert_name=$parameters['3'];
    $alert_value_id=$parameters['4'];
    $frequency=$parameters['5'];
    $alertType=$parameters['6'];
    $mail=$parameters['7'];
    $sms=$parameters['8'];
    $im=$parameters['9'];
    $state=$parameters['10'];
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    $dt=date("Y:m:d H:i:s");

    if($dbHandle == '')
    {
        log_message('error','createAlert can not create db handle');
    }

    //get the number of already created alerts in the same product

    //$getCountQuery="select count(*) count from  alert_user_preference,alert_rule where alert_rule.product_id = (select product_id from alert_rule where alert_rule.rule_id=?) and alert_user_preference.rule_id =alert_rule.rule_id and alert_user_preference.user_id =?";
    $getCountQuery="select count(*) count from  alert_user_preference where alert_user_preference.product_id = ? and alert_user_preference.user_id =?;";
    error_log_shiksha("select count(*) count from  alert_user_preference where alert_user_preference.product_id =? and alert_user_preference.user_id =?;");
    error_log_shiksha(print_r(array($product_id,$user_id),true));
    $query = $dbHandle->query($getCountQuery,array($product_id,$user_id));

    foreach($query->result_array() as $row)
    {
        $count=$row['count'];
    }

    //decision for checking executing the query
    if($count<5) //get this from conf....... maybe use apc
    {
        $queryCheckCommand="select count(*) count from alert_user_preference where user_id=? and product_id=? and alertType=? and alert_value_id=?";
	error_log_shiksha("select count(*) count from alert_user_preference where user_id=? and product_id=? and alertType=? and alert_value_id=?");
	error_log_shiksha(print_r(array($user_id,$product_id,$alertType,$alert_value_id),true));
        $query = $dbHandle->query($queryCheckCommand,array($user_id,$product_id,$alertType,$alert_value_id));
        foreach($query->result_array() as $row)
        {
            error_log_shiksha("HAHAHA");
            $prevVal=$row['count'];
	    error_log_shiksha($prevVal);
        }
        if($prevVal==0)
        {
	    if($alert_name=="")
	    {
	 	error_log_shiksha($alertType);
		$alert_name=$this->getAlertName($product_id,$alertType,$alert_value_id);
	    }
            $data=array('product_id'=>$product_id,'user_id'=>$user_id,'alert_name'=>$alert_name,'alert_value_id'=>$alert_value_id,'frequency'=>$frequency,'alertType'=>$alertType,'filter_id'=>'','mail'=>$mail,'sms'=>$sms,'im'=>$im,'state'=>$state,'created_date'=>$dt);
            $queryCmd = $dbHandle->insert_string('alert_user_preference',$data);
            error_log_shiksha('createAlert query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
            $alertId=$dbHandle->insert_id();
	    $updatePointsQuery="update userPointSystem set userPointValue= userPointValue+10 where userId=?";
	    error_log_shiksha($updatePointsQuery);
	    $query = $dbHandle->query($updatePointsQuery, array($user_id));
	    $msgArray=array();
            $response = array(
                    array(
                        'result'=>0,
			'alert_id'=>$alertId
                        ),
                    'struct');
            return $this->xmlrpc->send_response($response);
        }
        else
        {
            $response = array(
                    array(
                        'result'=>1,
                        'error_msg'=>'Check if the alert is already created'),
                    'struct');
            return $this->xmlrpc->send_response($response);
        }
    }
    else
    {
        $response = array(
                array(
                    'result'=>2,
                    'error_msg'=>'Quota of Alerts Exceeded, Please Delete Alerts'),
                'struct');
        return $this->xmlrpc->send_response($response);
    }
    //error_log_shiksha(print_r($response,true));
}

/**
* add a new alert in the alert_user_preference. Insert the record only if the user does not have 5 alerts under the same product. Make this value configurable by using APC caching later.
* Input: AppID, userId, productId, alertType , filter_id, frequency , mail, im and mobile
* Output: AlertId and the state if alert created else false.
*/
function createWidgetAlert($request)
{
    $parameters = $request->output_parameters();
    error_log_shiksha(print_r($parameters,true));
    $appId=$parameters['0'];
    $user_id=$parameters['1'];
    $product_id=$parameters['2'];
    $alertType=trim($parameters['3']);
    $alert_value_id=$parameters['4'];
    $alert_name=trim($parameters['5']);
    $filter_id=trim($parameters['6']);
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    $dt=date("Y:m:d H:i:s");

    if($dbHandle == '')
    {
        log_message('error','createWidgetAlert can not create db handle');
    }

    //get the number of already created alerts in the same product

    //$getCountQuery="select count(*) count from  alert_user_preference,alert_rule where alert_rule.product_id = (select product_id from alert_rule where alert_rule.rule_id=?) and alert_user_preference.rule_id =alert_rule.rule_id and alert_user_preference.user_id =?";
    $getCountQuery="select count(*) count from  alert_user_preference where alert_user_preference.product_id = ? and alert_user_preference.user_id =?;";

    $query = $dbHandle->query($getCountQuery,array($product_id,$user_id));

    foreach($query->result_array() as $row)
    {
        $count=$row['count'];
    }

    //decision for checking executing the query
    if($count<COMMENT_ALERT_QUOTA) //get this from conf....... maybe use apc
    {
        $queryCheckCommand="select count(*) count from alert_user_preference where user_id=? and product_id=? and alertType=? and alert_value_id=? and filter_id=?";
        $query = $dbHandle->query($queryCheckCommand,array($user_id,$product_id,$alertType,$alert_value_id,$filter_id));
        foreach($query->result_array() as $row)
        {
            error_log_shiksha("Got the present count");
            $prevVal=$row['count'];
        }
        if($prevVal==0)
        {
	    if($alert_name=='')
	    {
		error_log_shiksha($alertType);
		$alert_name=$this->getAlertName($product_id,$alertType,$alert_value_id,$filter_id);
	    }
            $data=array('product_id'=>$product_id,'user_id'=>$user_id,'alert_name'=>$alert_name,'alert_value_id'=>$alert_value_id,'frequency'=>'daily','alertType'=>$alertType,'filter_id'=>$filter_id,'mail'=>'on','sms'=>'off','state'=>'on','created_date'=>$dt);
            $queryCmd = $dbHandle->insert_string('alert_user_preference',$data);
            error_log_shiksha('createWidgetAlert query cmd is ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
	    $alertId=$dbHandle->insert_id();
            /*$updatePointsQuery="update userPointSystem set userPointValue= userPointValue+10 where userId=".$user_id;
	    error_log_shiksha($updatePointsQuery);
	    $query = $dbHandle->query($updatePointsQuery);*/
	    $msgArray=array();
            $response = array(
                    array(
                        'result'=>0,
			'alert_id'=>$alertId
                        ),
                    'struct');
	    error_log_shiksha(print_r($response,true));
            return $this->xmlrpc->send_response($response);
        }
        else
        {
            $response = array(
                    array(
                        'result'=>1,
                        'error_msg'=>'Check if the alert is already created'),
                    'struct');
            return $this->xmlrpc->send_response($response);
        }
    }
    else
    {
        $response = array(
                array(
                    'result'=>2,
                    'error_msg'=>'Quota of Alerts Exceeded, Please Delete Alerts'),
                'struct');
        return $this->xmlrpc->send_response($response);
    }
    error_log_shiksha(print_r($response,true));
}

/**
* get the name of the alert
* Input: productId, alertType, alert_value_id and filter_id
* Output: alert Name if exists else false
*/
function getAlertName($product_id,$alertType,$alert_value_id,$filter_id)
{
	$this->load->library('alertconfig');
	//connect DB
    $dbHandle = $this->_loadDatabaseHandle();
	$alert_name='';
	$getProductNameQuery="select * from product where product_id=?";
	$query = $dbHandle->query($getProductNameQuery,array($product_id));
	foreach($query->result_array() as $row)
	{
		error_log_shiksha($row['product_name']);
		$alert_name=$row['product_name'];
	}
	error_log_shiksha($alertType);
	if($alertType=='byCategory')
	{
		$getAlertNameQuery="SELECT t1.name AS lev1, t2.name as lev2 FROM categoryBoardTable AS t1 LEFT JOIN categoryBoardTable AS t2 ON t2.parentId = t1.boardId WHERE (t2.boardId=?) group by lev1;";
		$query = $dbHandle->query($getAlertNameQuery,array($alert_value_id));
		foreach($query->result_array() as $row)
       		{
			if($row['lev1']=='All')
			{
				$alert_name=$alert_name.'-'.$row['lev2'];
			}
			else
			{
				$alert_name=$alert_name.'-'.$row['lev1'].'-'.$row['lev2'];
			}
		}
	}
	if($alertType=='byAuthor')
	{
		$getAuthorDisplayName="select displayname from shiksha.tuser where tuser.userId=?";
		$query = $dbHandle->query($getAuthorDisplayName,array($alert_value_id));
		foreach($query->result_array() as $row)
		{
			$alert_name=$alert_name.'-'.$row['displayname'];
		}
	}
	if($alertType=='byComment')
	{
		$getMessageTitleName="select msgTxt from shiksha.messageTable where threadId=? order by path limit 1";
		$query = $dbHandle->query($getMessageTitleName,array($alert_value_id,$filter_id));
		foreach($query->result_array() as $row)
		{
			$alert_name=$alert_name.'-'.$row['msgTxt'];
		}
	}
        return($alert_name);
}

/**
* Create a alert for Event
* A separate function has been made for this because of the extra parameter for event alerts. Merge with the original when you have time.
* Same logic of checking for five alerts and in case the number is less then allow the user to create the alert
* Input: All alert Details
* Output: Alert Id or false
*/
function createEventAlert($request)
{
    $parameters = $request->output_parameters();
    error_log_shiksha(print_r($parameters,true));
    $appId=$parameters['0'];
    $product_id=$parameters['1'];
    $user_id=$parameters['2'];
    $alert_name=$parameters['3'];
    $alert_value_id=$parameters['4'];
    $filter_id=$parameters['5'];
    $frequency=$parameters['6'];
    $alertType=$parameters['7'];
    $mail=$parameters['8'];
    $sms=$parameters['9'];
    $im=$parameters['10'];
    $state=$parameters['11'];
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    $dt=date("Y:m:d H:i:s");

    if($dbHandle == '')
    {
        log_message('error','createEventAlert can not create db handle');
    }

    //get the number of already created alerts in the same product

    //$getCountQuery="select count(*) count from  alert_user_preference,alert_rule where alert_rule.product_id = (select product_id from alert_rule where alert_rule.rule_id=?) and alert_user_preference.rule_id =alert_rule.rule_id and alert_user_preference.user_id =?";
    $getCountQuery="select count(*) count from  alert_user_preference where alert_user_preference.product_id = ? and alert_user_preference.user_id =?;";

    $query = $dbHandle->query($getCountQuery,array($product_id,$user_id));

    foreach($query->result_array() as $row)
    {
        $count=$row['count'];
    }
    error_log_shiksha($count);

    //decision for checking executing the query
    if($count<5) //get this from conf....... maybe use apc
    {
	error_log_shiksha("GO AHEAD CREATE THE ALERT");
        $queryCheckCommand="select count(*) count from alert_user_preference where user_id=? and product_id=? and alert_value_id=? and filter_id=?";
        $query = $dbHandle->query($queryCheckCommand,array($user_id,$product_id,$alert_value_id,$filter_id));
        foreach($query->result_array() as $row)
        {
            $prevVal=$row['count'];
        }
        if($prevVal==0)
        {
	    error_log_shiksha("CREATING ALERT");
            $data=array('product_id'=>$product_id,'user_id'=>$user_id,'alert_name'=>$alert_name,'alert_value_id'=>$alert_value_id,'frequency'=>$frequency,'alertType'=>$alertType,'filter_id'=>$filter_id,'mail'=>$mail,'sms'=>$sms,'im'=>$im,'state'=>$state,'created_date'=>$dt);
            $queryCmd = $dbHandle->insert_string('alert_user_preference',$data);

            error_log_shiksha('createEventAlert query cmd is ' . $queryCmd);

            $query = $dbHandle->query($queryCmd);
            if($query==TRUE)
            {
                $alert_id=$dbHandle->insert_id();
                $response = array(
                        array(
                            'result'=>0),
                        'struct');
            }
        }
        else
        {
	    error_log_shiksha("NOT THERE IDIOT HERE....");
            $response = array(
                    array(
                        'result'=>1,
                        'error_msg'=>'CHECK IF THE ALERT IS ALREADY CREATED'),
                    'struct');
        }
    }
    else
    {
	error_log_shiksha("HERE");
        $response = array(
                array(
                    'result'=>2,
                    'error_msg'=>'Quota of Alerts Exceeded, Please Delete Alerts'),
                'struct');
    }
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}

/**
* Update the alert of a given alert.
* Get all parameters and then create an alert if the number of alerts by user is less than 5
* Input: alert_id, frequency, im, sms, filter_id name etc.
* Output: Message of update.
*/
function updateAlert($request)
{

    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $frequency=$parameters['3'];
    $mail=$parameters['4'];
    $sms=$parameters['5'];
    $im=$parameters['6'];
    $this->load->library('alertconfig');

    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    if($dbHandle == '')
    {
        log_message('error','updateAlert can not create db handle');
    }

    //get the topic ID

    $data=array('frequency'=>$frequency,'mail'=>$mail,'sms'=>$sms,'im'=>$im);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    //log_message('debug','updateAlert query is : '.$queryCmd);
    error_log_shiksha('updateAlert query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == TRUE)
    {
        //$alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=> 0),
                'struct');
    }
    else
    {
        //log_message('error','updateAlert failed for Query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1,
                    'ERROR'=>'Update Failed.....Please try Again'),
                'struct');
    }

    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}
/**
* Update the alert of a given Event alert.
* Get all parameters and then create an alert if the number of alerts by user is less than 5
* Input: alert_id, frequency, im, sms, filter_id name etc.
* Output: Message of update.
*/
function updateEventAlert($request)
{

    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $frequency=$parameters['3'];
    $mail=$parameters['4'];
    $sms=$parameters['5'];
    $im=$parameters['6'];
    $alert_name=$parameters['7'];
    $filter_id=$parameters['8'];
    $this->load->library('alertconfig');

    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');

    if($dbHandle == '')
    {
        log_message('error','updateEventAlert can not create db handle');
    }

    //get the topic ID

    $data=array('alert_name'=>$alert_name,'filter_id'=>$filter_id,'frequency'=>$frequency,'mail'=>$mail,'sms'=>$sms,'im'=>$im);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    //log_message('debug','updateEventAlert query is : '.$queryCmd);
    error_log_shiksha('updateEventAlert query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == TRUE)
    {
        //$alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=> 0),
                'struct');
    }
    else
    {
        //log_message('error','updateEventAlert failed for Query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1,
                    'ERROR'=>'Update Failed.....Please try Again'),
                'struct');
    }

    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    error_log_shiksha(print_r($response,true));
    return $this->xmlrpc->send_response($response);
}
/**
* Update the alert state (activate/deactivate) of a given alert.
* Update the status as per request if the alert belongs to the user
* Input: alert_id, user_id and alert_status
* Output: Message of update.
*/
function updateState($request)
{
    $parameters = $request->output_parameters();
    $appid=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $state=$parameters['3'];
    $this->load->library('alertconfig');
    //connect db
    $dbHandle = $this->_loadDatabaseHandle('write');
    if(gettype($dbHandle) != 'object') {
	return $this->xmlrpc->send_response(array());
    }	
    //get the topic id
    $data=array('state'=>$state);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    error_log_shiksha('createalert query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == true)
    {
        $alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'state'=>$state),
                'struct');
    }
    else
    {
        log_message('error','updatestate failed for query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1,
                    'state'=>($state=="on"?"off":"on")),
                'struct');
    }
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    return $this->xmlrpc->send_response($response);
}
/**
* Update the mail status of a given alert.
* Update the status as per request if the alert belongs to the user
* Input: alert_id, user_id and alert_status
* Output: Message of update.
*/

function updateMail($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $mail=$parameters['3'];
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
    //get the topic ID
    $data=array('mail'=>$mail);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    error_log_shiksha('updateMail query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == TRUE)
    {
        $alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'alert_id'=>array($alert_id)),
                'struct');
    }
    else
    {
        log_message('error','updateMail failed for Query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1),
                'struct');
    }
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    return $this->xmlrpc->send_response($response);
}
/**
* Update the sms status of a given alert.
* Update the status as per request if the alert belongs to the user
* Input: alert_id, user_id and alert_status
* Output: Message of update.
*/
function updateSms($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $sms=$parameters['3'];
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
    //get the topic ID
    $data=array('sms'=>$sms);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    error_log_shiksha('updateSms query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == TRUE)
    {
        $alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'alert_id'=>array($alert_id)),
                'struct');
    }
    else
    {
        log_message('error','updateSms failed for Query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1),
                'struct');
    }
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    return $this->xmlrpc->send_response($response);
}
/**
* Update the IM status of a gieven alert.
* Update the status as per request if the alert belongs to the user
* Input: alert_id, user_id and alert_status
* Output: Message of update.
*/
function updateIm($request)
    //function updateIm($appId,$alert_id,$user_id,$Im)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    $Im=$parameters['3'];
    $this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
    //get the topic ID
    $data=array('im'=>$Im);
    $queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    error_log_shiksha('updateIm query cmd is ' . $queryCmd);

    $query = $dbHandle->query($queryCmd);
    if($query == TRUE)
    {
        $alert_id=$dbHandle->insert_id();
        $response = array(
                array(
                    'result'=>0,
                    'alert_id'=>array($alert_id)),
                'struct');
    }
    else
    {
        log_message('error','updateIm failed for Query: '.$queryCmd);
        $response = array(
                array(
                    'result'=>1),
                'struct');
    }
    // echo "<pre>";
    // print_r($response);
    // echo "</pre>";
    return $this->xmlrpc->send_response($response);
}

/**
* The controller is used to delete the alert a user has created.
* Delete the alert if the alert_id belongs to the user only.
* Input: alertId and user_id
* Output : intimate the output.
*/
function deleteAlert($request)
    //function deleteAlert($appId,$alert_id,$user_id)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $alert_id=$parameters['1'];
    $user_id=$parameters['2'];
    error_log_shiksha($alert_id." : ".$user_id);
    //$this->load->library('alertconfig');
    //connect DB
    $dbHandle = $this->_loadDatabaseHandle('write');
    //get the topic ID
    $data=array('user_id'=>$user_id,'alert_id'=>$alert_id);
    //$queryCmd = $dbHandle->update_string('alert_user_preference',$data,"user_id=".$user_id." and alert_id=".$alert_id);

    $query=$dbHandle->delete('alert_user_preference', $data);
    if($query == TRUE)
    {
        $alert_id=$dbHandle->insert_id();
	/* This is kept to remove the points that will delete the points when a person deletes an alert.
	 This is not needed at this moment(Confirmed from sandeep). */
	/*
       	$updatePointsQuery="update userPointSystem set userPointValue= userPointValue-10 where userId=".$user_id;
	error_log_shiksha($updatePointsQuery);
	$query = $dbHandle->query($updatePointsQuery);
	*/
	$response = array(
                array(
                    'result'=>0,
                    'alert_id'=>array($alert_id)),
                'struct');
    }
    else
    {
        log_message('error','deleteAlert failed');
        $response = array(
                array(
                    'result'=>1),
                'struct');
    }
    //echo "<pre>";
    // print_r($response);
    //echo "</pre>";
    return $this->xmlrpc->send_response($response);
}
/**
* The controller that takes in the email to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all mails, and the mails to be sent have isSent = "unsent". The emails may also be scheduled for a later data using the sendTime parameter.
* Input : fromAddress, toAddress, subject, content , contentType(html/text), sendTime
* Output : A message stating that the mail has been inserted properly.
*/
function externalQueueAdd($request)
{
        $parameters = $request->output_parameters(FALSE,FALSE);
        $appId = $parameters['0'];
        $fromAddress = $parameters['1'];
        $toAddress = $parameters['2'];
        $subject = $parameters['3'];
        $content = $parameters['4'];
        $contentType = $parameters['5'];
        $sendTime = $parameters['6'];
        $attachment = $parameters['7'];
        $attachmentArray = $parameters['8'];
        $ccEmail = $parameters['9'];
		$bccEmail = $parameters['10'];
		$fromUserName=$parameters['11'];
		$isSent=$parameters['12']; 	//to add isSent value
		$return_mail_id = $parameters['13'];
        $mail_sent_time = $parameters['14'];
		
    	$dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->helper('url');
        $content = xmlrpcHtmlDeSanitize($content,array());
        $content = parseUrlFromContent($content);
        
        // Filter mails for Amazon SES
        $mailerServiceType = 'shiksha';
        global $domainsUsingAmazonMailService;
        global $emailidsUsingAmazonMailService;
        $toDomainName = explode("@", $toAddress);
        $ccDomainName = explode("@", $ccEmail);
        $bccDomainName = explode("@", $bccEmail);
        if( (in_array($toDomainName[1], $domainsUsingAmazonMailService)) || (in_array($ccDomainName[1], $domainsUsingAmazonMailService)) || (in_array($bccDomainName[1], $domainsUsingAmazonMailService)) || (in_array($toAddress, $emailidsUsingAmazonMailService)) || (in_array($ccEmail, $emailidsUsingAmazonMailService)) || (in_array($bccEmail, $emailidsUsingAmazonMailService)) ) {
            $mailerServiceType = 'amazon';
        }

    	//to insert data in tMailQueue or run the previous code if mail is not sent
    	if(!empty($isSent)) {
    	    $data=array('fromEmail'=> $fromAddress, 'toEmail'=> $toAddress, 'subject'=>$subject, 'content'=> $content, 'contentType'=>$contentType,'attachment'=>$attachment,'ccEmail'=>$ccEmail,'bccEmail'=>$bccEmail,'fromUserName'=>$fromUserName, 'isSent'=>$isSent, 'sendTime'=>$sendTime,'mailerServiceType'=>$mailerServiceType);    
    	} else {
    	    $data=array('fromEmail'=> $fromAddress, 'toEmail'=> $toAddress, 'subject'=>$subject, 'content'=> $content, 'contentType'=>$contentType,'attachment'=>$attachment,'ccEmail'=>$ccEmail,'bccEmail'=>$bccEmail,'fromUserName'=>$fromUserName,'mailerServiceType'=>$mailerServiceType);
    	}

        if(!empty($mail_sent_time)){
            $data['createdTime'] = $mail_sent_time;
        }

        $queryCmd = $dbHandle->insert_string('tMailQueue',$data);
        //error_log('Query for Alert externalQueueAdd is : '.$queryCmd);

        $dbHandle->query($queryCmd);
        $mailerId=$dbHandle->insert_id();
        foreach($attachmentArray as $value)
        {
            $data = array('mailer_id'=>$mailerId, 'attachment_id'=>$value);
		//error_log("data is as ".print_r($data,true));
            $queryCmd1 = $dbHandle->insert_string('mailerAttachmentMappingTable',$data);
            $dbHandle->query($queryCmd1);
        }
		try{
			$this->config->load('amqp');
			if ( $this->config->item('sendmail_via_smtp') !== "true")
			{
            	$this->load->library("common/jobserver/JobManagerFactory");
	            $jobManager = JobManagerFactory::getClientInstance();
				$jobManager->addBackgroundJob("SystemMailer", $mailerId);
			}
        }
        catch(Exception $e){
            error_log("JOBQException: ".$e->getMessage());
            $this->load->model('smsModel');
            $content = "FrontEnd: Problem with RabbitMQ";
            $msg = $this->smsModel->addSmsQueueRecord('',"9899601119",$content,"271028","0","user-defined","no");
            $msg = $this->smsModel->addSmsQueueRecord('',"9999430665",$content,"1600190","0","user-defined","no");
        }
		if($return_mail_id == 'Y') {
			$message = $mailerId;
		} else {
			$message="Inserted Successfully";
		}
	    $response=array($message,'string');
        return $this->xmlrpc->send_response($response);
}

function reQueuePendingJobs(){
    $this->load->model("common/jobstatuslogger", "", true);
    $logger = new JobStatusLogger();
    $logger->reQueuePendingJobs();
}

/**
*
* The controller that takes in the sms's to be sent. The controller simply takes all the input elements and stores it in the database. The table contains all smses to be sent. The sms's once sent are shifted to a table tSmsOutput. The smses can be controlled using sendTime. There are a couple of checks while send ing the sms, such as that the user may send only 15 sms per day etc. All further sms's are put in the smsQueue and sent the next day.
* Input : appId, toSMSNumber, content, userId, and sendTime
* Output : A message stating that the sms has been inserted properly.
* Parameter smstype is added whose default value is system and will need a value user-defined if message is not whitelisted
* Parameter isregistration is added whose default value is no and will need a value yes if message is coming from registration module
*
*/

function addSmsQueueRecord($request)
{
        $parameters = $request->output_parameters();
        $appId = $parameters['0'];
        $toSms = $parameters['1'];
        $content = $parameters['2'];
        $userId = $parameters['3'];
        $sendTime= $parameters['4'];
    	$smstype = $parameters['5'];
    	$IsRegistration  = $parameters['6'];
		$returnSMSId  = $parameters['7'];

        $dbHandle = $this->_loadDatabaseHandle('write');

        $this->load->model('smsModel');
        $msg = $this->smsModel->addSmsQueueRecord('',$toSms,$content,$userId,$sendTime,$smstype,$IsRegistration,$returnSMSId);
        $response=array($msg,'string');
        return $this->xmlrpc->send_response($response);
}


/**
* The controller that gets the number of number of new friends in the network.
* The controller is called as a cron to the user, and sends a mailer to the user as an update.
* the function takes in the number of days as a parameter and returns all the users, their network addition count as output.
* Input: Number of days
* Output: the number of friends that are added per user.
*/
function getDigestNetwork($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $noOfDays=$parameters['1'];

    //connect DB
    error_log_shiksha("Inside Server");
    $dbHandle = $this->_loadDatabaseHandle();
    if($noOfDays > 0)
    {
        $queryCmd = "select count(*) friendCount, tuser.userid, tuser.displayname, tuser.email from tuserNetworkRequest, tuser where tuser.userid= tuserNetworkRequest.userId and tuserNetworkRequest.status=\"new\" and tuserNetworkRequest.requestdate> now()- INTERVAL ".$dbHandle->escape($noOfDays)." day group by tuser.userid order by userid";
    }
    else
    {
        $queryCmd = "select count(*) friendCount, tuser.userid, tuser.displayname, tuser.email from tuserNetworkRequest, tuser where tuser.userid= tuserNetworkRequest.userId and tuserNetworkRequest.status=\"new\" group by tuser.userid order by userid";
    }
    error_log_shiksha($queryCmd);
    $query=$dbHandle->query($queryCmd);

    log_message('debug', 'getDigestNetwork query cmd is ' . $queryCmd);
    $msgArray = array();
    foreach ($query->result_array() as $row)
    {
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    return $this->xmlrpc->send_response($response);
}
/**
* The controller that gets the number of number of new mails in the mailbox.
* The controller is called as a cron to the user, and sends a mailer to the user as an update.
* the function takes in the number of days as a parameter and returns all the users, their network addition count as output.
* Input: Number of days
* Output: the number of friends that are added per user.
*/

function getDigestMail($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $noOfDays=$parameters['1'];

    //connect DB
    error_log_shiksha("Inside Server");
    $dbHandle = $this->_loadDatabaseHandle();
    if($noOfDays > 0)
    {
        $queryCmd = "select count(*) mailCount, tuser.userid, tuser.email, tuser.displayname from mails,tuser where tuser.userid = mails.receivers_id and mails.time_created>now() - INTERVAL ".$dbHandle->escape($noOfDays)." DAY and mails.senders_id!=1 and mails.read_flag = \"unread\" group by userid order by userid";
    }
    else
    {
        $queryCmd = "select count(*) mailCount, tuser.userid, tuser.email, tuser.displayname from mails,tuser where tuser.userid = mails.receivers_id and mails.senders_id!=1 and mails.read_flag = \"unread\" group by userid order by userid";
    }
    error_log_shiksha($queryCmd);
    $query=$dbHandle->query($queryCmd);

    log_message('debug', 'getDigestMail query cmd is ' . $queryCmd);
    $msgArray = array();
    foreach ($query->result_array() as $row)
    {
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    return $this->xmlrpc->send_response($response);
}

/**
* The controller update the email address status of all the users who have been sent an email over the noOfDays duration
* The controller is called as a cron
* the function takes in the number of days as a parameter and updates the tuserflag table for the hardbouce and soft bounce verfied column.
* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
* Input: Number of days
* Output: void
*/
function performEmailCheck($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $noOfDays=$parameters['1'];
    //connect DB
    error_log_shiksha("Inside Server");
    /*
    Please do not touch or comment below lines. contact
    <ravi.raj@shiksha.com> if you have any query or concern
    */
    //PROD//
    $dbConfig = array( 'hostname'=>'10.208.66.23','password'=>'skhKm7Iv80l' ,
'username'=>'shikshaEmail','database'=>'bouncelog');
    $this->alertconfig->getDbConfig($appId,$dbConfig);
    $dbConfig['database'] = 'bouncelog';
    $dbHandle = $this->load->database($dbConfig,TRUE);
    $this->alertconfig->getDbConfig($appId,$dbConfig2);
    $dbConfig2['database'] = 'shiksha';
    $dbHandle2 = $this->load->database($dbConfig2,TRUE);
    if($dbHandle == ''){
        log_message('error','performEmailCheck can not create db handle');
    }
    if($noOfDays > 0)
    {
        $queryCmd = "select distinct(email_id), ecelerity_Errcode from  bouncelog_sh where modified_date > now() - INTERVAL ? day";
        error_log_shiksha($queryCmd);
        $query=$dbHandle->query($queryCmd, array($noOfDays));

        log_message('debug', 'performEmailCheck query cmd is ' . $queryCmd);
        foreach ($query->result_array() as $row)
	{
		$email_id = $row['email_id'];
		$errorCode = $row['ecelerity_Errcode'];
		if($errorCode == '10' ||$errorCode == '21'||$errorCode == '50')
		{
			//echo "Email Hard Bounce";
			$queryCmd = "update tuserflag, tuser set tuserflag.hardbounce='1' where tuser.userid = tuserflag.userId and tuser.email = ?";
			$updateQueryResponse=$dbHandle2->query($queryCmd, array($email_id));
		}
		else if($errorCode == '1' || $errorCode == '20' ||$errorCode == '22'||$errorCode == '23'||$errorCode == '40'||$errorCode == '51')
		{
			//echo "Email Soft Bounce";
			$queryCmd = "update tuserflag, tuser set tuserflag.softbounce='1' where tuser.userid = tuserflag.userId and tuser.email = ?";
			$updateQueryResponse=$dbHandle2->query($queryCmd, array($email_id));
		}
		else
		{
			//echo "Message still waiting";
		}
	}
    }
    $message="inserted successfully";
    $response=array($message,'string');
    return $this->xmlrpc->send_response($response);
}


/**
* The controller update the email address status of all the users who have been sent an email over the noOfDays duration
* The controller is called as a cron
* the function takes in the number of days as a parameter and updates the tuserflag table for the hardbouce and soft bounce verfied column.
* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
* Input: Number of days
* Output: void
*/
function createAttachment($request)
{
    $parameters = $request->output_parameters(FALSE,FALSE);
    $appId=$parameters['0'];
    $type_id=$parameters['1'];
    $type=$parameters['2'];
    $attachmentType=$parameters['3'];
    $attachmentContent= $parameters['4'];
    $attachment_name=$parameters['5'];
    $attachment_file_type=$parameters['6'];
    $attachment_file_encoded_type = $parameters['7'];
    $attachmenturl = $parameters['8'];

    if($attachment_file_encoded_type == 'true'){
	    $attachmentContent = base64_decode($attachmentContent);
    }
     
    $dbHandle = $this->_loadDatabaseHandle('write');
    $data=array('listing_type_id'=>$type_id,'listing_type'=>$type,'attachment_file_type'=>$attachment_file_type,'attachment_content'=>$attachmentContent,'document_type'=>$attachmentType,'name'=>$attachment_name,'attachment_url'=>$attachmenturl);
	$queryCmd = $dbHandle->insert_string('attachmentTable',$data);
    $queryCmd = $queryCmd." on duplicate key update attachment_content = ".$dbHandle->escape($attachmentContent)." , attachment_file_type = ".$dbHandle->escape($attachment_file_type)." , name = ".$dbHandle->escape($attachment_name).", attachment_url = ".$dbHandle->escape($attachmenturl).";";
    //error_log('Query for Alert addAttachment is : '.$queryCmd);
    error_log('error'.$queryCmd);
    //$result = $dbHandle->query($queryCmd, array($attachmentContent,$attachment_file_type,$attachment_name));
    $result = $dbHandle->query($queryCmd);
    //error_log("Query for Alert addAttachment : " .$result);

    //$message="inserted successfully";
    $message = $dbHandle->insert_id();
    $response=array($message,'string');
    return $this->xmlrpc->send_response($response);
}
/**
* The controller update the email address status of all the users who have been sent an email over the noOfDays duration
* The controller is called as a cron
* the function takes in the number of days as a parameter and updates the tuserflag table for the hardbouce and soft bounce verfied column.
* the email (hard/soft bounce) is stored in tuserflag table, which is updated by this function
* Input: Number of days
* Output: void
*/
function getAttachmentId($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $type_id=$parameters['1'];
    $type=$parameters['2'];
    $document_type=$parameters['3'];
    $attachment_name=$parameters['4'];

    $dbHandle = $this->_loadDatabaseHandle('write');
    $data=array('listing_type_id'=>$type_id,'listing_type'=>$type,'attachment_file_type'=>$attachment_file_type,'attachment_content'=>$attachmentContent,'document_type'=>$attachmentType,'name'=>$attachment_name);
    if($attachment_name==''){
      $queryCmd = "select id  from attachmentTable where listing_type_id=? and listing_type=? and document_type=?;";
      $query=$dbHandle->query($queryCmd, array($type_id,$type,$document_type));
    }
    else{
      $queryCmd = "select id  from attachmentTable where document_type=? and name=?;";
      $query=$dbHandle->query($queryCmd, array($document_type, $attachment_name));
    }

    error_log('Query for Alert getAttachmentId is : '.$queryCmd.' : '.print_r(array($document_type,$name),true));
    $msgArray=array();
    foreach ($query->result_array() as $row){
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
    //$message="inserted successfully";
    //$response=array($message,'string');
    return $this->xmlrpc->send_response($response);
}



function getCompareContent($request)
{
    $parameters = $request->output_parameters();
    $compareId=$parameters['0'];
    
    

    $dbHandle = $this->_loadDatabaseHandle('write');
	$queryCmd = "update compareMailer c set status='clicked', clickDate=now() where c.id=?";
    $query = $dbHandle->query($queryCmd, array($compareId));
	
	$queryCmd = "select randomkey,pageUrl,userCookie,c.userId,password,email from tuser t,compareMailer c where c.id=? and c.userId=t.userid";
    $query = $dbHandle->query($queryCmd, array($compareId));
    $msgArray = array();
    foreach ($query->result_array() as $row)
    {
        array_push($msgArray,array($row,'struct'));
    }
    $response = array($msgArray,'struct');
	return $this->xmlrpc->send_response($response);
}


function getCompareUrl($request)
{
    $parameters = $request->output_parameters();
    $appId=$parameters['0'];
    $userId=$parameters['1'];
    $url=$parameters['2'];
    $cookie=$parameters['3'];
    

    $dbHandle = $this->_loadDatabaseHandle('write');
   
    $queryCmd = "INSERT INTO `compareMailer` ( ".
				"`pageUrl` , ".
				"`userCookie`, ".
				"`userId` ".
				") ".
				"VALUES ( ".
				"?, ?,? ".
				")";
    $query=$dbHandle->query($queryCmd, array($url, $cookie,$userId));
    $compareId = $dbHandle->insert_id();
	
	$queryCmd = "select randomkey from tuser where userid=?";
	
    $query=$dbHandle->query($queryCmd, array($userId));
	
	foreach ($query->result() as $row){
		$user = $row;
	}
	$finalUrl = SHIKSHA_HOME."/alerts/Alerts/compareColleges/".$compareId."/".md5("compare-".$user->randomkey.$compareId.$userId);
	error_log("AMIT".$finalUrl);
	return $this->xmlrpc->send_response(array($finalUrl,'string'));
}

}
