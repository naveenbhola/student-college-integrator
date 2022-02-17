<?php

/**
 * Power User Server class file
 */

/**
 * Power User Server class
 */
class PowerUser_server extends MX_Controller {

    /**
     * Index function for initialization
     */
    function index() {
        $this->dbLibObj = DbLibCommon::getInstance('User');
        ini_set('max_execution_time', '1800000');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('poweruserconfig');
        $this->load->helper('date');
        $this->load->helper('url');
        $this->load->helper('shikshaUtility');
        ///qna rehash phase-2 part-2 start////
        $config['functions']['getPowerUserInfo'] = array('function' => 'PowerUser_server.getPowerUserInfo');
        $config['functions']['insertPowerUserDetails'] = array('function' => 'PowerUser_server.insertPowerUserDetails');
        $config['functions']['deletePowerUserLevel'] = array('function' => 'PowerUser_server.deletePowerUserLevel');
        $config['functions']['insertIntoQuestionDiscussionLinkingTable'] = array('function' => 'PowerUser_server.insertIntoQuestionDiscussionLinkingTable');
        $config['functions']['getLinkedQuestion'] = array('function' => 'PowerUser_server.getLinkedQuestion');
        $config['functions']['changeLinkedQuestionStatus'] = array('function' => 'PowerUser_server.changeLinkedQuestionStatus');
        $config['functions']['makeStickyDiscussionAnnouncement'] = array('function' => 'PowerUser_server.makeStickyDiscussionAnnouncement');
        $config['functions']['unlinkedQuestion'] = array('function' => 'PowerUser_server.unlinkedQuestion');
        $config['functions']['getLinkedDiscussion'] = array('function' => 'PowerUser_server.getLinkedDiscussion');
        $config['functions']['changeLinkedDiscussionStatus'] = array('function' => 'PowerUser_server.changeLinkedDiscussionStatus');
        $config['functions']['makeDiscussionUnStickyifItIsLinked'] = array('function' => 'PowerUser_server.makeDiscussionUnStickyifItIsLinked');
        ///qna rehash phase-2 part-2 end////
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    /**
     * qna rehash phase-2 part-2 start
     * @param object $request
     */
    function getPowerUserInfo($request) {
        $parameters = $request->output_parameters();error_log("poweruserarray==".print_r($parameters,true));
        $appId=$parameters['0'];
        $userId=$parameters['1'];
        $startFrom=$parameters['2'];
        $count=$parameters['3'];
        $module=$parameters['4'];
        $status=$parameters['5'];

        $userIdFieldData=$parameters['6'];
        $userEmailFieldData=$parameters['7'];
        $userminReputationPointFieldData=$parameters['8'];
        $usermaxReputationPointFieldData=$parameters['9'];
        $searchTypeVal=$parameters['10'];
        $filterVal=$parameters['11'];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle();
        $msgArray = array();
        $msgArray2 = array();
        $repArray = array();
        //$totalUserIds = array();
        if(($userminReputationPointFieldData!='' || $usermaxReputationPointFieldData!='') && $searchTypeVal=='3') {

            if($usermaxReputationPointFieldData!='' && $userminReputationPointFieldData!='') {
                $and = 'and';
            }else {
                $and = '';
            }
            error_log('filterVal=='.print_r($filterVal,true));
            if($filterVal!='') {
                $filterStr = " ugmt.groupId='{$filterVal}'";
                if($usermaxReputationPointFieldData!='' || $userminReputationPointFieldData!='') $filerand = 'and';
                $str6 = "and ugmt.status!='deleted'";
            }else {
                $filterStr = '';
                $filerand = '';
                $str6 = '';
            }

            if($usermaxReputationPointFieldData!='')
                $usermaxReputationPointFieldDataQuery = "  round(turp.points)<=".$dbHandle->escape($usermaxReputationPointFieldData);
            else
                $usermaxReputationPointFieldDataQuery = '';

            if($userminReputationPointFieldData!='')
                $userminReputationPointFieldDataQuery = "   round(turp.points)>=".$dbHandle->escape($userminReputationPointFieldData);
            else
                $userminReputationPointFieldDataQuery = '';

            $queryCmdRep = "select SQL_CALC_FOUND_ROWS tu.userid,tu.email,tu.displayname,turp.points,ugmt.groupId as level,ugmt.status from tuser tu left join tuserReputationPoint turp  on turp.userId= tu.userid left join userGroupsMappingTable ugmt on (ugmt.userId= tu.userid $str6)   where $userminReputationPointFieldDataQuery $and $usermaxReputationPointFieldDataQuery $filerand $filterStr order by turp.points asc,tu.usercreationDate desc limit $startFrom,$count";

            $resultSetRep = $dbHandle->query($queryCmdRep);


            error_log("DOoD query ==".print_r($queryCmdRep,true));

            $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
            $queryTotal = $dbHandle->query($queryCmdTotal);
            foreach ($queryTotal->result() as $rowT) {
                $totalResult  = $rowT->totalRows;
            }

            $i=0;
            $comma = ',';
            $totalUserIds = '';
            foreach ($resultSetRep->result_array() as $rowT) {
                array_push($msgArray,array($rowT,'struct'));
                $msgArray[$i][0]['levelOfUser']=$this->getUserLevel($rowT['userid'],'AnA');
                $i++;
            } error_log("DOoD query ==".print_r($msgArray,true));
        }else if((($userEmailFieldData!='') || ($userIdFieldData!='')) && ($searchTypeVal=='2' || $searchTypeVal=='1')) {

                if($filterVal =='1' || $filterVal =='2'){
                    $str1 = " and ugmt.groupId=".$dbHandle->escape($filterVal);
                    $str4 = " and ugmt.status!='deleted'";
                }else{
                    $str1 = "";
                    $str4="";
                }
                if($userIdFieldData!='' && $searchTypeVal=='1') {
                    $str = " where tu.userid=".$dbHandle->escape($userIdFieldData);
                }else if($userEmailFieldData!='' && $searchTypeVal=='2') {
                        $str = " where tu.email=".$dbHandle->escape($userEmailFieldData);
                    }

                $queryCmd = "select SQL_CALC_FOUND_ROWS turp.points,tu.userid,tu.displayname,tu.email,ugmt.groupId as level,ugmt.status from tuser tu left join tuserReputationPoint turp  on turp.userId= tu.userid  left join userGroupsMappingTable ugmt on (ugmt.userId= tu.userid) ".$str.$str1.$str4 ;
                $resultSet = $dbHandle->query($queryCmd);error_log("searchresult==".print_r($queryCmd,true));
                $numOfRows = $resultSet->num_rows();

                $totalAbuseReport = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                    $totalResult  = $rowT->totalRows;
                }

                foreach ($resultSet->result_array() as $row) {
                    array_push($msgArray,array($row,'struct'));
                } error_log("levelofuserpranjul total points===".print_r($totalResult,true));
                if($userIdFieldData=='') {
                    $userIdFieldData = $msgArray[0][0]['userid'];
                }
                $level=$this->getUserLevel($userIdFieldData,'AnA');error_log("levelofuser===".print_r($level,true));
                $msgArray[0][0]['levelOfUser'] = $level;

            }else{
                if($filterVal =='1' || $filterVal =='2'){
                    $str = "where ugmt.groupId=".$dbHandle->escape($filterVal);
                    $str5 = " and ugmt.status!='deleted'";
                }else{
                    $str = "";
                    $str5 ='';
                }
                 $queryCmd = "select SQL_CALC_FOUND_ROWS turp.points,tu.userid,tu.displayname,tu.email,ugmt.groupId as level,ugmt.status from tuser tu  left join tuserReputationPoint turp  on turp.userId= tu.userid left join  userGroupsMappingTable ugmt on (ugmt.userId= tu.userid $str5 ) $str $str4 order by tu.usercreationDate desc limit $startFrom,$count";
                 error_log("poweruserarray111=".print_r($queryCmd,true));
                 $resultSet = $dbHandle->query($queryCmd);
                 $numOfRows = $resultSet->num_rows();
                 $result = $resultSet->row();
                $totalAbuseReport = 0;
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $dbHandle->query($queryCmdTotal);
                foreach ($queryTotal->result() as $rowT) {
                    $totalResult  = $rowT->totalRows;
                 }
                // $this->load->model('UserPointSystemModel');
                 $i=0;
                 foreach ($resultSet->result_array() as $rowT) {
                    array_push($msgArray,array($rowT,'struct'));
                    $msgArray[$i][0]['levelOfUser']=$this->getUserLevel($rowT['userid'],'AnA');
                    $i++;
                 }
                
            }
        error_log("checkthisout===".print_r($msgArray,true));
        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results'=>array($msgArray,'struct'),
            'totalResults'=>array($totalResult,'int'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');error_log("xmlrpc result===".print_r($mainArr,true));
        return $this->xmlrpc->send_response($response);
    }

    
    /**
     * Function to get the User Level
     *
     * @param integer $userId
     * @param string $moduleName
     */
    function getUserLevel($userId,$moduleName) {
        $dbHandle = $this->_loadDatabaseHandle();
        $queryCmd = "SELECT Level FROM userPointLevelByModule upl, userpointsystembymodule ups WHERE upl.module = ups.moduleName and upl.module = ? and ups.userId = ? and ups.userPointValueByModule > upl.minLimit LIMIT 1";
        error_log("query is ==>".print_r($queryCmd,true));
        $query = $dbHandle->query($queryCmd, array($moduleName, $userId));
        $res = $query->result_array();
        error_log("level is ==>".print_r($res,true));
        return $res[0][Level];

    }
    
    /**
     * Function to insert the power user details
     * 
     * @param object $request
     */
    function insertPowerUserDetails($request) { error_log("DOoD userGroupsMappingTable");
        $parameters = $request->output_parameters();error_log("DOoD".print_r($parameters,true));
        $appId = $parameters[0];
        $userId = $parameters[1];
        $newLevelOfUser = $parameters[2];
        $email = $parameters[3];
        $displayname = $parameters[4];

        //connect DB
        $dbHandle = $this->_loadDatabaseHandle('write');
        //$this->load->model('UserPointSystemModel');
        //		  $result  = $this->UserPointSystemModel->calculateRankByRepuationPoints($dbHandle,$userId);

        $queryCmdSelect = "select * from userGroupsMappingTable where `userId`=?";
        $querySelect= $dbHandle->query($queryCmdSelect, array($userId));
        $res = $querySelect->result_array();
        $numRows = $querySelect->num_rows();
        error_log("raatkakam".print_r($res,true));
        if(($res[0]['groupId']=='1' || $res[0]['groupId']=='2') && ($newLevelOfUser==1 || $newLevelOfUser==2) && $res[0]['status']!='deleted') {
            $success='Success';
        }else {
            $this->load->model('acl_model');

            if($numRows) {
            //$queryCmd="update powerUserTable set `level`=$newLevelOfUser where  `userId`='{$userId}'";

                $result  = $this->acl_model->updateIntouserGroupsMappingTable($dbHandle,$newLevelOfUser,$userId);
            }else {
            //$queryCmd="insert into powerUserTable (`userId`,`level`,`displayname`,`email`) values ('".$userId."','".$newLevelOfUser."','".$displayname."','".$email."')";
                $result  = $this->acl_model->insertIntouserGroupsMappingTable($dbHandle,$newLevelOfUser,$userId);

            }
            $success='Success';
        }
        error_log("DOoDquery".$queryCmd);
        //$query=$dbHandle->query($queryCmd);

        $response = array(
            array(
            'result'=>$success,
            ),
            'struct');
        return $this->xmlrpc->send_response($response);
    }
    
    /**
     * Function to delete the Power User Level
     *
     * @param object $request
     *
     */
    function deletePowerUserLevel($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters[0];
        $userId = $parameters[1];
        $dbHandle = $this->_loadDatabaseHandle('write');
        //$queryCmd="update powerUserTable set `level`=0 where userId='{$userId}'";
        error_log("DOoD".$queryCmd);
        //$query=$dbHandle->query($queryCmd);
        $this->load->model('acl_model');
        $result  = $this->acl_model->deleteIntouserGroupsMappingTable($dbHandle, $userId);
        $response = array(
            array(
            'result'=>'Success',
            ),
            'struct');
        return $this->xmlrpc->send_response($response);
    }
    
    /**
     * Function to insert questions into Discussion linking table
     * @param object $request
     */
    function insertIntoQuestionDiscussionLinkingTable($request) {
        $parameters = $request->output_parameters();error_log("poweruserarray==".print_r($parameters,true));
        $appId = $parameters[0];
        $linkedQuestionId = $parameters[1];
        $mainQuestionId = $parameters[2];
        $userId = $parameters[3];
        $questionOwerId = $parameters[4];
        $entityType = $parameters[5];

        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd="insert into questionDiscussionLinkingTable (`type`,`linkingEntityId`,`linkedEntityId`,`status`,`powerUserId`) values (?,?,?,'draft',?)";
        $query=$dbHandle->query($queryCmd, array($entityType, $linkedQuestionId, $mainQuestionId, $userId));
    }
    
    /**
     * Function to get the linked questtions
     * @param object $request
     */
    function getLinkedQuestion($request) { error_log("finalquestionlinkedarray server 1");
        $parameters = $request->output_parameters();
        $appId = $parameters[0];
        $startFrom=$parameters['1'];
        $count=$parameters['2'];
        $filter=$parameters['3'];
	$userNameFieldData=$parameters['4'];
	$userLevelFieldData=$parameters['5'];
        $dbHandle = $this->_loadDatabaseHandle();
	$extraFilters = ''; $nameFilter = '';
    if($userLevelFieldData == "All")
        {
            $userLevelFieldData = '';
        }
	if($userNameFieldData!=''){
	    $nameFilter .= "  and t1.displayname = ".$dbHandle->escape($userNameFieldData)." ";
	}
	if($userLevelFieldData!=''){
	    $extraFilters .= " HAVING level = ".$dbHandle->escape($userLevelFieldData)." ";
	}

        $str='';
        if($filter!='All') {
            $str = "and qdlt.status='{$filter}'";
        }
        if($filter=='All'){
             $strNew = " and qdlt.status!='deleted'";
        }
        error_log("finalquestionlinkedarray server array==".print_r($parameters,true));
        $msgArray1 = array();
        $msgArray2 = array();
        $msgArray3 = array();
        $queryCmdRep1 = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,qdlt.status,qdlt.createdDate,qdlt.type,qdlt.id,ifnull((select levelName as Level from  userpointsystembymodule where modulename = 'AnA' LIMIT 1),'Beginner') level from messageTable mt ,questionDiscussionLinkingTable qdlt, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where qdlt.linkingEntityId = mt.msgId and qdlt.powerUserId = t1.userid $nameFilter and qdlt.type='question' $str  $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
        $resultSetRep1 = $dbHandle->query($queryCmdRep1);


        error_log("finalquestionlinkedarray query ==".print_r($queryCmdRep1,true));

        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        foreach ($queryTotal->result() as $rowT) {
            $totalResult  = $rowT->totalRows;
        }
      
        $i=0;
        foreach ($resultSetRep1->result_array() as $rowT1) {
            array_push($msgArray1,array($rowT1,'struct'));
        }

        $queryCmdRep2 = "select mt.msgId,mt.msgTxt,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable mt,questionDiscussionLinkingTable qdlt, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where qdlt.linkedEntityId = mt.msgId and qdlt.powerUserId = t1.userid $nameFilter and qdlt.type='question' $str $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
        $resultSetRep2 = $dbHandle->query($queryCmdRep2);

        $i=0;
        foreach ($resultSetRep2->result_array() as $rowT2) {
            array_push($msgArray2,array($rowT2,'struct'));
            $i++;
        }

        $queryCmdRep3 = "select t1.firstname,t1.displayname,t1.userid,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from  questionDiscussionLinkingTable qdlt ,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid = qdlt.powerUserId $nameFilter and qdlt.type='question' $str $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
        $resultSetRep3 = $dbHandle->query($queryCmdRep3);

        $k=0;
        foreach ($resultSetRep3->result_array() as $rowT3) {
            array_push($msgArray3,array($rowT3,'struct'));
            $k++;
        }



        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results1'=>array($msgArray1,'struct'),
            'results2'=>array($msgArray2,'struct'),
            'results3'=>array($msgArray3,'struct'),
            'totalResults'=>array($totalResult,'int'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');error_log("finalquestionlinkedarray==".print_r($mainArr,true));
        return $this->xmlrpc->send_response($response);

    }
    
    /**
     * Function to change the linked question status
     *
     * @param object $request
     */
    function changeLinkedQuestionStatus($request) {
        $parameters = $request->output_parameters();//error_log("changeLinkedQuestionStatus==>>>".print_r($parameters,true));
        $appId = $parameters[0];
        $id=$parameters['1'];
        $newstatus=$parameters['2'];
        $msgId=$parameters['3'];
        $linkedQuestionId=$parameters['4'];
        $dbHandle = $this->_loadDatabaseHandle('write');
        $countQuery = "select id from questionDiscussionLinkingTable where `linkedEntityId`=? and `linkingEntityId`=? and status!='deleted'";
        $query=$dbHandle->query($countQuery,array($msgId, $linkedQuestionId));
        $val = '';
        if($newstatus=='deleted') {
            foreach($query->result_array() as $final) {
                $queryCmd = "update questionDiscussionLinkingTable set `status`='{$newstatus}' where `id`=?";
                $query=$dbHandle->query($queryCmd, array($final['id']));
            }
            $val = 'Success';
        }
        else {
             $countQuery1 = "select id from questionDiscussionLinkingTable where `linkedEntityId`=? and status='accepted'";
             $query1=$dbHandle->query($countQuery1, array($msgId));
             $numOfRows1 = $query1->num_rows();
             if($numOfRows1<10){
             $queryCmd = "update questionDiscussionLinkingTable set `status`=? where `id`=?";
             $query=$dbHandle->query($queryCmd, array($newstatus, $id));
             $val = 'Success';
             }else{
             $val = 'Failure';
             }
        }

        
        if($newstatus=='accepted' && $val!= 'Failure') {
            $this->load->library('mailerClient');
            $this->load->library('alerts_client');
          //  $MailerClient = new MailerClient();
           // $mail_client = new Alerts_client();
            $queryCmd = "select displayname,email,firstname,userid from tuser where userId=(select userId from messageTable where msgId=?)";
            $query = $dbHandle->query($queryCmd, array($msgId));
            $res = $query->row();
            $queryCmd1 = "select msgTxt from messageTable where msgId=?";
            $query1 = $dbHandle->query($queryCmd1, array($msgId));
            $res1 = $query1->row();
            $queryCmd2 = "select msgTxt from messageTable where msgId=?";
            $query2 = $dbHandle->query($queryCmd2, array($linkedQuestionId));
            $res2 = $query2->row();
            error_log("changeLinkedQuestionStatus==".print_r($queryCmd,true));
            $email = $res->email;
            $fromMail = "info@shiksha.com";
            $ccmail = "";
            $subject="A Shiksha expert has a suggestion for your question";
            $contentArr['NameOfUser'] = ($res->firstname=='')?$res->displayname:$res->firstname;
            $contentArr['receiverId'] = $res->userid;
            $contentArr['linkedQuestionTitle'] = $res1->msgTxt;
            $contentArr['linkingQuestionTitle'] = $res2->msgTxt;
            // $contentArr['linkedQuestionLink'] = $MailerClient->generateAutoLoginLink(1,$email,SHIKSHA_HOME.'/getTopicDetail/'.$msgId);
            $contentArr['linkedQuestionLink'] = SHIKSHA_ASK_HOME.'/getTopicDetail/'.$msgId;
            // $contentArr['linkingQuestionLink'] = $MailerClient->generateAutoLoginLink(1,$email,SHIKSHA_HOME.'/getTopicDetail/'.$linkedQuestionId);
            $contentArr['linkingQuestionLink'] = SHIKSHA_ASK_HOME.'/getTopicDetail/'.$linkedQuestionId;
             $contentArr['type'] = 'powerUserQuestionSuggestion';
             $contentArr['fromMail'] = $fromMail;
             $contentArr['subject'] = $subject;
            //$content = $this->load->view("search/searchMail",$contentArr,true);
            //$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
             Modules::run('systemMailer/SystemMailer/questionLinkedStatusChangedMailer', $email, $contentArr);
        }
        $response = array(
            array(
            'result'=>$val,
            ),
            'struct');
        return $this->xmlrpc->send_response($response);

    }
    
    /**
     * Function to unlink the question
     * @param object $request
     */
    function unlinkedQuestion($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters[0];
        $linkedEntityId = $parameters[1];
        $linkingEntityId = $parameters[2];
        $dbHandle = $this->_loadDatabaseHandle('write');
        $queryCmd = "update questionDiscussionLinkingTable set `status`='deleted' where `linkedEntityId`=? and `linkingEntityId`=?";
        $query=$dbHandle->query($queryCmd, array($linkedEntityId, $linkingEntityId));
        $response = array(
            array(
            'result'=>'Success',
            ),
            'struct');
        return $this->xmlrpc->send_response($response);



    }
    
    /**
     * Function to make Sticky discussion accouncement
     * @param object $request
     */
    function makeStickyDiscussionAnnouncement($request) {
        $parameters = $request->output_parameters();error_log("numOfRows==".print_r($parameters,true));
        $appId = $parameters[0];
        $powerUserId = $parameters[1];
        $stickyMsgId = $parameters[2];
        $stickyThreadId = $parameters[3];
        $categoryId= $parameters[4];
        $entityType= $parameters[5];
        $status = $parameters[6];
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd="select SQL_CALC_FOUND_ROWS status from stickyDiscussionAndAnnoucementTable where `categoryId`=? and `status`='live' and `type`=?";
        $resultSet=$dbHandle->query($queryCmd, array($categoryId, $entityType));
        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        $totalResult=0;
        foreach ($queryTotal->result() as $rowT) {
            $totalResult  = $rowT->totalRows;
        }
        // $numOfRows = $resultSet->num_rows();error_log("numOfRows==".print_r($numOfRows,true));
        if($totalResult<5 || $status=='deleted') {
            $queryCmd="insert into stickyDiscussionAndAnnoucementTable (`powerUserId`,`status`,`type`,`stickymsgId`,`categoryId`,`stickythreadId`) values (?,'live',?,?,?,?) on duplicate key update `status`=?";
            error_log("numOfRows==".print_r($queryCmd,true));
            $query=$dbHandle->query($queryCmd, array($powerUserId, $entityType, $stickyMsgId, $categoryId, $stickyThreadId, $status));
            if( $status=='deleted') {
                $queryCmd="select SQL_CALC_FOUND_ROWS status from stickyDiscussionAndAnnoucementTable where `categoryId`=? and `status`='live'and `type`=?";
                $resultSet=$dbHandle->query($queryCmd, array($categoryId, $entityType));
                $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
                $queryTotal = $dbHandle->query($queryCmdTotal);
                $totalResult=0;
                foreach ($queryTotal->result() as $rowT) {
                    $totalResult  = $rowT->totalRows;
                }
            }
            $response = array(
                array(
                'result'=>$totalResult,
                ),
                'struct');
            return $this->xmlrpc->send_response($response);
        }else {
            $response = array(
                array(
                'result'=>$totalResult,
                ),
                'struct');
            return $this->xmlrpc->send_response($response);
        }
    }
    
    
    /**
     * Function to get the Linked Discussions
     *
     * @param object $request
     */
    function getLinkedDiscussion($request) {
        $parameters = $request->output_parameters();
        $appId = $parameters[0];
        $startFrom=$parameters['1'];
        $count=$parameters['2'];
        $filter=$parameters['3'];
	$userNameFieldData=$parameters['4'];
	$userLevelFieldData=$parameters['5'];
        $dbHandle = $this->_loadDatabaseHandle();
	$extraFilters = ''; $nameFilter = '';
	if($userNameFieldData!=''){
	    $nameFilter .= "  and t1.displayname = ".$dbHandle->escape($userNameFieldData)." ";
	}
	if($userLevelFieldData!=''){
	    $extraFilters .= " HAVING level = ".$dbHandle->escape($userLevelFieldData)." ";
	}
    if($userLevelFieldData == "All")
        {
            $userLevelFieldData = '';
        }

        $str='';
        $strNew = '';
        if($filter!='All') {
            $str = "and qdlt.status='{$filter}'";
           
        }
        if($filter=='All'){
             $strNew = " and qdlt.status!='deleted'";
        }

        $msgArray1 = array();
        $msgArray2 = array();
        $msgArray3 = array();
        $queryCmdRep1 = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.threadId,mt.msgTxt,qdlt.status,qdlt.createdDate,qdlt.type,qdlt.id,ifnull((select levelName as Level from  userpointsystembymodule where modulename = 'AnA' LIMIT 1),'Beginner') level from messageTable mt ,questionDiscussionLinkingTable qdlt, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') WHERE qdlt.linkingEntityId = mt.threadId and qdlt.type='discussion'  and qdlt.powerUserId = t1.userid $nameFilter and mt.mainAnswerId=0 $str $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
	error_log("finalquestionlinkedarrayfirst query ==".print_r($queryCmdRep1,true));
        //$queryCmdRep1 = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.msgTxt,qdlt.status,qdlt.createdDate,qdlt.type,qdlt.id from messageTable mt ,questionDiscussionLinkingTable qdlt where qdlt.linkingEntityId = mt.msgId $str  order by createdDate desc limit $startFrom,$count";
        $resultSetRep1 = $dbHandle->query($queryCmdRep1);


        error_log("finalquestionlinkedarrayfirst query ==".print_r($queryCmdRep1,true));

        $queryCmdTotal = 'SELECT FOUND_ROWS() as totalRows';
        $queryTotal = $dbHandle->query($queryCmdTotal);
        foreach ($queryTotal->result() as $rowT) {
            $totalResult  = $rowT->totalRows;
        }
        //error_log("finalquestionlinkedarray==>>".print_r($totalResult,true));
        $i=0;
        foreach ($resultSetRep1->result_array() as $rowT1) {
            array_push($msgArray1,array($rowT1,'struct'));
        //$msgArray[$i][0]['linkingEntityTxt']=$this->getUserLevel($rowT['userid'],'AnA');
				/*$msgArray[$i][0]['linkingEntityId']  = $rowT1['msgId'];
				$msgArray[$i][0]['linkingEntityTxt'] = $rowT1['msgTxt'];
				$i++;*/
        }
        $queryCmdRep2 = "select SQL_CALC_FOUND_ROWS mt.msgId,mt.threadId,mt.msgTxt,qdlt.status,qdlt.createdDate,qdlt.type,qdlt.id,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from messageTable mt ,questionDiscussionLinkingTable qdlt, tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') WHERE qdlt.linkedEntityId = mt.threadId and qdlt.type='discussion' and qdlt.powerUserId = t1.userid $nameFilter and mt.mainAnswerId=0 $str  $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
        //$queryCmdRep2 = "select mt.msgId,mt.msgTxt from messageTable mt,questionDiscussionLinkingTable qdlt where qdlt.linkedEntityId = mt.threadId $str order by createdDate desc limit $startFrom,$count";
        $resultSetRep2 = $dbHandle->query($queryCmdRep2);

        $i=0;
        foreach ($resultSetRep2->result_array() as $rowT2) {
            array_push($msgArray2,array($rowT2,'struct'));
            //$msgArray2[$i][0]['linkingEntityTxt']=$this->getUserLevel($rowT2['userid'],'AnA');
				/*$msgArray[$j][0]['linkedEntityId']  = $rowT2['msgId'];
				$msgArray[$j][0]['linkedEntityTxt'] = $rowT2['msgTxt'];*/
            $i++;
        }

        $queryCmdRep3 = "select t1.firstname,t1.displayname,t1.userid,(select level from userPointLevelByModule  where minLimit<= ifnull(upv.userpointvaluebymodule,0) and module = 'AnA' limit 1) level from  questionDiscussionLinkingTable qdlt ,tuser t1 LEFT JOIN userpointsystembymodule upv ON (t1.userid = upv.userId and upv.modulename='AnA') where t1.userid = qdlt.powerUserId $nameFilter and qdlt.type='discussion' $str  $strNew $extraFilters order by createdDate desc  limit $startFrom,$count";
        $resultSetRep3 = $dbHandle->query($queryCmdRep3);

        $k=0;
        foreach ($resultSetRep3->result_array() as $rowT3) {
            array_push($msgArray3,array($rowT3,'struct'));
            //$msgArray3[$k][0]['level']=$this->getUserLevel($rowT3['userid'],'AnA');
				/*$msgArray[$k][0]['displayname']  = $rowT3['displayname'];
				$msgArray[$k][0]['level'] = $this->getUserLevel($rowT3['userid'],'AnA');*/
            $k++;
        }

        $mainArr = array();
        array_push($mainArr,array(
            array(
            'results1'=>array($msgArray1,'struct'),
            'results2'=>array($msgArray2,'struct'),
            'results3'=>array($msgArray3,'struct'),
            'totalResults'=>array($totalResult,'int'),
            ),'struct')
        );//close array_push
        $response = array($mainArr,'struct');error_log("finalquestionlinkedarray==".print_r($mainArr,true));
        return $this->xmlrpc->send_response($response);
    }
    
    /**
     * Function to change the Linked Discussion Status
     *
     * @param object $request
     */
    function changeLinkedDiscussionStatus($request) {
        $parameters = $request->output_parameters();//error_log("changeLinkedQuestionStatus==>>>".print_r($parameters,true));
        $appId = $parameters[0];
        $id=$parameters['1'];
        $newstatus=$parameters['2'];
        $msgId=$parameters['3'];
        $linkedDiscussionId=$parameters['4'];
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryForCountAccepted = "select id from questionDiscussionLinkingTable where `linkedEntityId`=? and `type`='discussion' and `status`='accepted'";
        error_log("changeLinkedDiscussionStatus==".print_r($queryForCountAccepted,true));
        $resultSet = $dbHandle->query($queryForCountAccepted, array($msgId));
        $numOfRows = $resultSet->num_rows();
        if($numOfRows>0 && $newstatus=='accepted') {
            $response = array(
                array(
                'result'=>'NotSuccess',
                ),
                'struct');
        }else {

            $queryCmd = "update questionDiscussionLinkingTable set `status`=? where `id`=?";
            $query=$dbHandle->query($queryCmd, array($newstatus, $id));
            $response = array(
                array(
                'result'=>'Success',
                ),
                'struct');
                if($newstatus=='accepted'){
                    $this->load->library('mailerClient');
                    $this->load->library('alerts_client');
                    $MailerClient = new MailerClient();
                    //$mail_client = new Alerts_client();
                    $queryCmd = "select displayname,email,firstname,userid from tuser where userId=(select userId from messageTable where msgId=?)";
                    $query = $dbHandle->query($queryCmd, array($msgId));
                    $res = $query->row();
                    $email = $res->email;
                    $queryCmd1 = "select msgTxt,creationDate,threadId from messageTable where msgId=?";
                    $query1 = $dbHandle->query($queryCmd1, array($msgId));
                    $res1 = $query1->row();
                    $fromMail = "info@shiksha.com";
                    $ccmail = "";
                    $subject="A Shiksha expert has a suggestion for your discussion";
                    $contentArr['NameOfUser'] = ($res->firstname=='')?$res->displayname:$res->firstname;
                    $contentArr['linkedDiscussionTitle'] = $res1->msgTxt;
                    $contentArr['fromMail'] = $fromMail;
                    $contentArr['subject'] = $subject;
                    $contentArr['receiverId'] = $res->userid;
                    $url = getSeoUrl($res1->threadId,'discussion',$res1->msgTxt,'','',$res1->creationDate);
                   // $contentArr['linkedDiscussionLink'] = $MailerClient->generateAutoLoginLink(1,$email,$url);
                    $contentArr['linkedDiscussionLink'] = $url;
                    $contentArr['type'] = 'powerUserDiscussionSuggestion';
                    $content = $this->load->view("search/searchMail",$contentArr,true);
                    //$mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",$ccmail);
                    Modules::run('systemMailer/SystemMailer/discussionLinkedStatusChangedMailer', $email, $contentArr);

                }
        }

        return $this->xmlrpc->send_response($response);

    }
    
    /**
     * Funciton to make Discussion unsticky(if already linked)
     * @param object $request
     */
    function makeDiscussionUnStickyifItIsLinked($request){
        
        $parameters = $request->output_parameters();error_log("changelinkeddiscussionstatus==>>>".print_r($parameters,true));
        $appId = $parameters[0];
        $newstatus=$parameters['1'];
        $msgId=$parameters['2'];
        $linkedDiscussionId=$parameters['3'];
        $dbHandle = $this->_loadDatabaseHandle('write');

        $queryCmd = "SELECT id FROM questionDiscussionLinkingTable ql WHERE ql.linkedEntityId = ( SELECT  `threadId` FROM  `messageTable` WHERE  `parentId` = ? and status = 'live' LIMIT 0 , 1 ) AND ql.type =  'discussion' AND ql.status =  'accepted'";
        $resultSet = $dbHandle->query($queryCmd, array($msgId));
        $numOfRows = $resultSet->num_rows();
        if($numOfRows>0 && $newstatus=='accepted') {
            $queryCmd="update stickyDiscussionAndAnnoucementTable set `status`='deleted' where `stickythreadId`=?";
            $resultSet = $dbHandle->query($queryCmd, array($msgId));
        }
        return;
    }
}
///qna rehash phase-2 part-2 end////
?>
