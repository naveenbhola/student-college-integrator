<?php

/**
 * 
 * @author virender
 *
 */
class CACronsLib
{

	function __construct()
	{
		$this->CI = & get_instance();
	}		

	/*
	Function to format data
	as per end date task and
	task without end date
	each with autoLogin links
	
	Author: Virender
	*/
	function formatCAOpenTaskData($rawData = array(), $email = '')
	{
		$result = array('endDateTasks' => array(), 'noEndDateTasks' => array());
		$this->CI->load->library('mailerClient');
		$MailerClient = new MailerClient();
		$dashboard_task = SHIKSHA_HOME.'/CA/CRDashboard/myTaskTab/open/';
		$total_prize_amount = 0;
		foreach($rawData as $key=>$task)
		{
			$task['urlOfLandingPage'] = $dashboard_task.$task['id'];
			$task['autoLoginUrl'] = $MailerClient->generateAutoLoginLink(1, $email, $dashboard_task.$task['id']);
			if($task['end_date']!='')
			{
				$result['endDateTasks'][] = $task;
			}
			else
			{
				$result['noEndDateTasks'][] = $task;
			}
			$total_prize_amount += $task['total_prize'];
		}
		$result['grand_total'] = $total_prize_amount;
		return $result;
	}
	
	
	function formatCAMarketingReportData($quesAskedArrayAll,$quesAskedArrayWeek,$quesAnsweredArrayAll,$quesAnsweredArrayWeek,$countAnsweredCRAll,$countAnsweredCRWeek,$pendingAnswersCRMonth,$pendingAnswersCRWeek){
		
		$finalResult = array();
		$subcatArrays = array('23','56');
		
		foreach($quesAskedArrayAll as $intsId=>$val){
			
			for($i=0;$i<count($subcatArrays);$i++){
				if($subcatArrays[$i] == $val[$subcatArrays[$i]]['category_id']){
		
					$finalResult[$intsId][$val[$subcatArrays[$i]]['category_id']] = array('questionAskedMonth'=>$val[$subcatArrays[$i]]['questionAsked'],
								      'questionAskedWeek'=>isset($quesAskedArrayWeek[$intsId][$subcatArrays[$i]]['questionAsked'])?$quesAskedArrayWeek[$intsId][$subcatArrays[$i]]['questionAsked']:0,
								      'questionAnsweredMonth'=>isset($quesAnsweredArrayAll[$intsId][$subcatArrays[$i]]['questionAnswered'])?$quesAnsweredArrayAll[$intsId][$subcatArrays[$i]]['questionAnswered']:0,
								      'questionAnsweredWeek'=>isset($quesAnsweredArrayWeek[$intsId][$subcatArrays[$i]]['questionAnswered'])?$quesAnsweredArrayWeek[$intsId][$subcatArrays[$i]]['questionAnswered']:0,
								      'questionAnsweredByCRMonth'=>isset($countAnsweredCRAll[$intsId][$subcatArrays[$i]])?$countAnsweredCRAll[$intsId][$subcatArrays[$i]]:0,
								      'questionAnsweredByCRWeek'=>isset($countAnsweredCRWeek[$intsId][$subcatArrays[$i]])?$countAnsweredCRWeek[$intsId][$subcatArrays[$i]]:0,
								      'questionUnansweredMonth'=>$val[$subcatArrays[$i]]['questionAsked']-$countAnsweredCRAll[$intsId][$subcatArrays[$i]],
								      'questionUnansweredWeek'=>$quesAskedArrayWeek[$intsId][$subcatArrays[$i]]['questionAsked']-$countAnsweredCRWeek[$intsId][$subcatArrays[$i]],
								      'category_id'=>$val[$subcatArrays[$i]]['category_id'],
								      'pendingAnsCountCRMonth' => isset($pendingAnswersCRMonth[$intsId][$subcatArrays[$i]]['ansCount'])?$pendingAnswersCRMonth[$intsId][$subcatArrays[$i]]['ansCount']:0,
								      'pendingAnsCountCRWeek' => isset($pendingAnswersCRWeek[$intsId][$subcatArrays[$i]]['ansCount'])?$pendingAnswersCRWeek[$intsId][$subcatArrays[$i]]['ansCount']:0,
								      'instituteId' => $intsId);
				}
		
			}
			
		}
		return $finalResult;
		
	}
	
	function getCampusRepDetails($finalArrayInfo,$pastCampusRepDetails){
		$campusRepIds = '';
		foreach ($finalArrayInfo as $key => $value) {
			if(!empty($value)){
				if($key != 0){
					$campusRepIds .= ',';
				}
				$campusRepIds .= $value['userId'];
				$campusRepDetailsPresent[$value['userId']][$value['courseId']] = $value['courseId'];
			}
		}
		$resultArray['campusRepIdsPastExcluded'] = $campusRepIds;
		$flagCR = !empty($campusRepIds);
		foreach ($pastCampusRepDetails as $key => $value) {
			if($flagCR == 1){
				$campusRepIds .= ',';
			}
			$campusRepIds .= $key;
		}
		$resultArray['campusRepInstituteMapping'] = $campusRepDetailsPresent + $pastCampusRepDetails;
		$resultArray['campusRepIds'] = $campusRepIds;
		return $resultArray;
	}

	function getMessageIdsQuestions($msgIdArray){
		$key = 0;
		foreach ($msgIdArray as $instiId => $value) {
			foreach ($value as $subCat => $msgs) {
				if($key != 0){
					$msgIds .= ',';
				}
				$msgIds .= $msgs;
				$key++;
			}
		}
		return $msgIds;
	}

	function getMessageIdsAnswersAndIndexedArray($quesAnsweredArray,$campusRepDetails){
		$c = 0;
		foreach ($quesAnsweredArray as $key => $value) {
			$questionAnsweredIndexed[$value['userId']][] = $value;
			if($c != 0){
				$returnArray['messageIdAnswer'] .= ',';
			}
			$returnArray['messageIdAnswer'] .= $value['msgId'];
			$c++;
		}
		$flag = array();
		foreach ($questionAnsweredIndexed as $id => $val) {
			foreach ($val as $key => $value) {
				if(in_array($value['courseId'],$campusRepDetails[$id])){
					$flag[$value['threadId']]++;
					if($flag[$value['threadId']] == 1){
						$returnArray['countAnswered'][$value['instituteId']][$value['category_id']] ++;
					}
				}	
			}
		}
		return $returnArray;
	}
}
?>
