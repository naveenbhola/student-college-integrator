<?php
/**
 * 
 * @author virender
 *
 */
function get24QuestionsForTopRankedColleges($allQues, $instituteIds)
{
	$finalQuestions = array();
	$lenCount = array();
	$quesId = array();
	foreach($allQues as $key=>$val)
	{
		if(in_array($val['instituteId'], $instituteIds) && count($finalQuestions) < 24)
		{
			if(count($lenCount[$val['instituteId']]) < 2)
			{
				$finalQuestions[] = $val;
				$lenCount[$val['instituteId']][] = 1;
				$quesId[] = $val['msgId'];
			}
		}
	}
	//_p($quesId);
	return json_encode(array('finalQuestions'=>$finalQuestions, 'finalQuesId'=>$quesId));
}

function get24QuestionsForMostViewsColleges($allQues, $instituteIds)
{
	$finalQuestions = array();
	$lenCount = array();
	$quesId = array();
	foreach($allQues as $key=>$val)
	{
		if(in_array($val['instituteId'], $instituteIds) && count($finalQuestions) < 24)
		{
			if(count($lenCount[$val['instituteId']]) < 2)
			{
				$finalQuestions[] = $val;
				$lenCount[$val['instituteId']][] = 1;
				$quesId[] = $val['msgId'];
			}
		}
	}
	//_p($quesId);
	return json_encode(array('finalQuestions'=>$finalQuestions, 'finalQuesId'=>$quesId));
}

function get24AnswersForTopRankedColleges($answerData, $finalQuesId)
{
	$finalAns = array();
	foreach($answerData as $key=>$val)
	{
		if(empty($finalAns[$val['parentId']]))
		{
			$finalAns[$val['parentId']] = $val['msgTxt'];
		}
	}
	return $finalAns;
	
}

function get24AnswersForMostViewsColleges($answerData, $finalQuesId)
{
	$finalAns = array();
	foreach($answerData as $key=>$val)
	{
		if(empty($finalAns[$val['parentId']]))
		{
			$finalAns[$val['parentId']] = $val['msgTxt'];
		}
	}
	return $finalAns;
	
}

function get24AnswersForQuestionsWithFeaturedAnswer($allFeaturedAns, $allQues)
{
	//_p($allFeaturedAns);
	$quesId = array();
	$featuredAns = array();
	foreach($allQues as $ques)
	{
		$quesId[] = $ques['msgId'];
	}
	//_p($quesId);die;
	foreach($allFeaturedAns as $key=>$val)
	{
		if(empty($featuredAns[$val['threadId']]))
		{
			$featuredAns[$val['threadId']] = $val['msgTxt'];
		}
	}
	return $featuredAns;
}
?>
