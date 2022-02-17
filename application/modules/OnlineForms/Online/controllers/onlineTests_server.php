<?php

/*

Copyright 2007 Info Edge India Ltd

$Rev:: 411           $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010/07/14 05:27:16 $:  Date of last commit


This class provides the Message Board Server Web Services.
The message_board_client.php makes call to this server using XML RPC calls.

*/

class OnlineTests_server extends MX_Controller {

/**
 *	index function to recieve the incoming request
 */
    function init() {
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('OnlineTestConfig');
        $this->load->library('alerts_client');
        $this->load->helper('url');
        $this->load->helper('shikshautility');
		$this->load->model('onlinetestparentmodel');
        return true;
    }

    function index() {
	//load XML RPC Libs
        $this->init();
        //Define the web services method
        $config['functions']['getOnlineTestData'] = array('function' => 'OnlineTests_server.getOnlineTestData');
        $config['functions']['getUsersOnlineTest'] = array('function' => 'OnlineTests_server.getUsersOnlineTest');

		//initialize
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    function getOnlineTestData($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];
        $exam=$parameters['2'];
        $duration=$parameters['3'];
        $section=$parameters['4'];
        $level=$parameters['5'];

        $dbConfig = array( 'hostname'=>'localhost');
	    $this->onlinetestconfig->getDbConfig($appID,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);

		$resArray = array();

		//Insert an entry in the test table and create a test for this User.
		$insertData = array(
			'userId' => $userId,
			'testExamType' => $exam,
			'testLevel' => $level,
			'testDuration' => $duration,
			'testSection' => $section,
			'status' => 'started'
		);
		$queryRes = $this->dbHandle->insert('OT_TestTable',$insertData);
	    $testId = $this->dbHandle->insert_id();

		//For each section, create a query based on the duration which will include normal questions + group questions for Verbal reasoning.
		$testData = array();

		if($section=='All'){	//In case of All Sections
			$tempArray = array();
			$this->load->library('testconfig');
			$displayData['duration_array'] = TestConfig::$duration_array;
			foreach ($duration_array as $key=>$value){
				if($key==$duration)
					$numberOfQ = $value['Total'];
			}
			for($i=2;$i<5;$i++){
				$section = $i;
				if($section!=4 && $section!=3){	//In case of Sections other than Verbal which contains Groups
					$tempArray[] = $this->getSectionDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ);
				}
				else{
					$tempArray[] = $this->getSectionGroupDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ);
				}
			}
			//Now, we need to merge the complete tempArray in the testData array
			$testData['test_id'] = $testId;
			for($x=0;$x<4;$x++){
				$testData['test_sections'][$x+1] = $tempArray[$x]['test_sections'][1];
			}
		}
		else{
			$this->load->library('testconfig');
			$displayData['duration_array'] = TestConfig::$duration_array;
			foreach ($duration_array as $key=>$value){
				if($key==$duration)
					$numberOfQ = $value['Total'];
			}
			if($section!=4 && $section!=3){	//In case of Sections other than Verbal which contains Groups
				$testData = $this->getSectionDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ);
			}
			else{
				$testData = $this->getSectionGroupDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ);
			}
		}

		//After selecting the questions, we also have to add all these questions in the test result table.

		//Now, return all the questions
        $mainArr = array();
        $mainArr[0]['testData'] = $testData;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

	function getSectionDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ){
        $dbConfig = array( 'hostname'=>'localhost');
	    $this->onlinetestconfig->getDbConfig($appID,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);

		$testData = array();
		$testData['test_id'] = $testId;
		$testData['test_sections'] = array();
		$quesArray = array();
		$ansArray = array();

		$queryCmd = "select OT_QuestionTable.*, URL images from OT_QuestionTable, OT_MediaTable where questionExamType = '$exam' and questionLevel='$level' and questionSection IN ($section) and OT_QuestionTable.qId = OT_MediaTable.entityId and entityType='question' order by rand() limit $numberOfQ";
		$query = $dbHandle->query($queryCmd);
		foreach($query->result_array() as $row){
			$quesId = $row['qId'];
			$queryCmdA = "select answerText, URL image from OT_AnswerTable, OT_MediaTable where qId = '$quesId' and aId = entityId and entityType = 'answer'";
			$queryA = $dbHandle->query($queryCmdA);
			$row['answers'] = array();
			foreach($queryA->result_array() as $rowA){
				$ansId = $rowA['aId'];
				$ansArray[$ansId] = $rowA;
				$row['answers'][] = $ansId;
			}
			$row['group'] = 0;
			$quesArray[] = $row;
		}
		//Add group, question and answer data
		$testData['test_groups'] = array();
		$testData['test_questions'] = $quesArray;
		$testData['test_answers'] = $ansArray;
		//Add Section Data
		$testData['test_sections'][1] = array();
		$section_array = TestConfig::$section_array;
		foreach ($section_array as $key=>$value){
			if($value['id']==$section)
				$sectionName = $key;
		}
		$testData['test_sections'][1]['name'] = $sectionName;
		$testData['test_sections'][1]['section_questions'] = array();
		for($i=1;$i<$numberOfQ;$i++)
			$testData['test_sections'][1]['section_questions'][] = $i;
		//Return Test data for section
		return $testData;
	}

	function getSectionGroupDataForQuestion($userId,$testId,$exam,$level,$duration,$section,$numberOfQ){
        $dbConfig = array( 'hostname'=>'localhost');
	    $this->onlinetestconfig->getDbConfig($appID,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);

		$testData = array();
		$testData['test_id'] = $testId;
		$testData['test_sections'] = array();
		$quesArray = array();
		$ansArray = array();
		$groupArray = array();

		$queryCmd = "select qt.*, URL images, gt.gName, gt.id as groupId, gt.gParagraph, gt.gInstructions from OT_QuestionTable qt, OT_MediaTable mt, OT_GroupQuestionTable gt, OT_GroupQuestionMapping gqm where questionExamType = '$exam' and questionLevel='$level' and questionSection IN ($section) and OT_QuestionTable.qId = OT_MediaTable.entityId and entityType='question' and gt.id=gqm.gId and gqm.qId=qt.qId order by rand() limit $numberOfQ";

		$query = $dbHandle->query($queryCmd);
		//$globalGroupId = 0;
		foreach($query->result_array() as $row){
			$quesId = $row['qId'];
			$queryCmdA = "select answerText, URL image from OT_AnswerTable, OT_MediaTable where qId = '$quesId' and aId = entityId and entityType = 'answer'";
			$queryA = $dbHandle->query($queryCmdA);
			$row['answers'] = array();
			foreach($queryA->result_array() as $rowA){
				$ansId = $rowA['aId'];
				$ansArray[$ansId] = $rowA;
				$row['answers'][] = $ansId;
			}
			$quesArray[] = $row;

			$groupId = $row['groupId'];
			$groupArray[$groupId]['description'] = $row['gParagraph'];
			$groupArray[$groupId]['instructions'] = $row['gInstructions'];
			//if($globalGroupId==0 || $globalGroupId==$groupId){	//Meaning we are parsing the same group 
					$groupArray[$groupId]['group_questions'][] = $row['qId'];
			//}
			//$globalGroupId = $groupId;
		}
		//Add group, question and answer data
		$testData['test_groups'] = $groupArray;
		$testData['test_questions'] = $quesArray;
		$testData['test_answers'] = $ansArray;
		//Add Section Data
		$testData['test_sections'][1] = array();
		$section_array = TestConfig::$section_array;
		foreach ($section_array as $key=>$value){
			if($value['id']==$section)
				$sectionName = $key;
		}
		$testData['test_sections'][1]['name'] = $sectionName;
		$testData['test_sections'][1]['section_questions'] = array();
		for($i=1;$i<$numberOfQ;$i++)
			$testData['test_sections'][1]['section_questions'][] = $i;
		//Return Test data for section
		return $testData;
	}

    function getUsersOnlineTest($request) {
        $parameters = $request->output_parameters();
        $appID=$parameters['0'];
        $userId=$parameters['1'];

        $dbConfig = array( 'hostname'=>'localhost');
	    $this->onlinetestconfig->getDbConfig($appID,$dbConfig);
	    $dbHandle = $this->load->database($dbConfig,TRUE);

		$resArray = array();

		$queryCmd = "select * from OT_TestTable where userId = '$userId'";
		$query = $dbHandle->query($queryCmd);
		foreach($query->result_array() as $row){
			$resArray[] = $row;
		}
error_log(print_r($resArray,true));
        $mainArr = array();
        $mainArr[0]['testData'] = $resArray;
        $responseString = base64_encode(gzcompress(json_encode($mainArr)));
        $response = array($responseString,'string');
        return $this->xmlrpc->send_response($response);
    }

}
