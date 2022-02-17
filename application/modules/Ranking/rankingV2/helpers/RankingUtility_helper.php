<?php

function stringCompareFN($a, $b){
	if (strcasecmp($a->getName(), $b->getName()) == 0) {
        return 0;
    }
    return (strcasecmp($a->getName(), $b->getName()) < 0) ? -1 : 1;
}

function specializationCompareFN($a, $b){
	if (strcasecmp($a['ranking_page_text'], $b['ranking_page_text']) == 0) {
        return 0;
    }
    return (strcasecmp($a['ranking_page_text'], $b['ranking_page_text']) < 0) ? -1 : 1;
}

function cityObjectCompareFN($a, $b){
	if (strcasecmp($a->getCityName(), $b->getCityName()) == 0) {
        return 0;
    }
    return (strcasecmp($a->getCityName(), $b->getCityName()) < 0) ? -1 : 1;
}

function stateCompareFN($a, $b){
	if (strcasecmp($a->getStateName(), $b->getStateName()) == 0) {
        return 0;
    }
    return (strcasecmp($a->getStateName(), $b->getStateName()) < 0) ? -1 : 1;
}

function cityNameCompareFN($a, $b){
	if (strcasecmp($a['city_name'], $b['city_name']) == 0) {
        return 0;
    }
    return (strcasecmp($a['city_name'], $b['city_name']) < 0) ? -1 : 1;
}

function stateNameCompareFN($a, $b){
	if (strcasecmp($a['state_name'], $b['state_name']) == 0) {
        return 0;
    }
    return (strcasecmp($a['state_name'], $b['state_name']) < 0) ? -1 : 1;
}

function examNameCompareFN($a, $b){
	if (strcasecmp($a['exam_name'], $b['exam_name']) == 0) {
        return 0;
    }
    return (strcasecmp($a['exam_name'], $b['exam_name']) < 0) ? -1 : 1;
}

function convertRequestObjectIntoArray(RankingPageRequest $requestObject){
	$requestArray = array();
	if(!empty($requestObject)){
		$requestArray['rankingPageId']   = $requestObject->getPageId();
		$requestArray['rankingPageName'] = $requestObject->getPageName();
		$requestArray['cityId'] 		 = $requestObject->getCityId();
		$requestArray['cityName'] 		 = $requestObject->getCityName();
		$requestArray['stateId'] 		 = $requestObject->getStateId();
		$requestArray['stateName'] 		 = $requestObject->getStateName();
		$requestArray['countryId'] 		 = $requestObject->getCountryId();
		$requestArray['countryName'] 	 = $requestObject->getCountryName();
		$requestArray['examId'] 		 = $requestObject->getExamId();
		$requestArray['examName'] 		 = $requestObject->getExamName();
	}
	return $requestArray;
}

function sanitizeTextForURL($text = "") {
	$validCharSet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-';
	$validURLString = "";
	$text = trim($text);
	
	$replaceCharacters = array(" ", "/", "(", ")");
	$text 	= str_replace($replaceCharacters, " ", $text);
	$text 	= preg_replace('/\s+/', ' ', $text);
	$text	= str_replace(" ", "-", $text);
	
	for($i=0; $i < strlen($text); $i++){
		$index = strpos($validCharSet, $text[$i]);
		if($index !== false){
			$validURLString .= $text[$i];
		}
	}
	$validURLString 	= preg_replace('/--+/', '-', $validURLString);
	$validURLString = trim($validURLString);
	$validURLString = trim($validURLString, "-");
	return $validURLString;
}


function sortExamsInUI($a, $b){
	if (strcasecmp($a['name'], $b['name']) == 0) {
        return 0;
    }
    return (strcasecmp($a['name'], $b['name']) < 0) ? -1 : 1;
}


function handleMBASpecialCase($rankingPageName = NULL){
	$replacedStringMap = array("full time mba/pgdm" => "MBA", "BE/Btech" => "Engineering", "me/mtech" => "ME/Mtech Engineering");
	$rankingPageTextToBeUsed = $rankingPageName;
	if(!empty($rankingPageName)){
		$textsToBeReplacedList = array_keys($replacedStringMap);
		foreach($textsToBeReplacedList as $textToBeReplaced){
			$index = strpos(strtolower($rankingPageName), strtolower($textToBeReplaced));
			if($index !== false){
				$newValue = $replacedStringMap[$textToBeReplaced];
				$rankingPageTextToBeUsed = $newValue;
			}		
		}
	}
	return $rankingPageTextToBeUsed;
}

function getExamHtml($courseObject,$examUrls=array()){
	if(!empty($courseObject)){
		$exams  = $courseObject->getAllEligibilityExams(false);
	    $examString     = "";
	    $examMoreString = "";
	    if(count($exams) > 0){
	    	reset($exams);
	    	$firstExam = current($exams);
	    	$examId = key($exams);
	        if(!empty($examUrls[$examId])){
	            	$examString .= ('<span class="one-lin" title="'.$firstExam.'"> <a target="_blank" ga-attr="TUPLE_EXAM" href="'.$examUrls[$examId].'">'.$firstExam.'</a></span>');
        	}
        	else{
            	$examString .= $firstExam;
        	}
	        
	        $extraExams = array();
	        if(count($exams) > 1){
	            $extraExams = array_slice($exams, 1,null,true);
	        }
	        foreach($extraExams as $examId=>$exam){
	        	$examMoreString .= ("<p title='".$exam."'>");
	        	if(!empty($examUrls[$examId])){
	            	$examMoreString .= ('<a target="_blank" ga-attr="TUPLE_EXAM" href="'.$examUrls[$examId].'">'.$exam.'</a>');
	        	}
	        	else{
	            	$examMoreString .= $exam;
	        	}
	            $examMoreString .= "</p>";
	        }
	        $examString = trim($examString);
	        $examString = trim($examString, ",");
	        $examMoreString = trim($examMoreString);
	        $examMoreString = trim($examMoreString, ",");
		}
	    return  array('examString' => $examString, 'examMoreString'=> $examMoreString,'count'=>count($exams));
	}
}

function getBrochuresFromCookie(){
	$brochuresMailed = $_COOKIE['applied_courses'];
	if(empty($brochuresMailed)){
		$brochuresMailed = array();
	}else{
		$brochuresMailed = json_decode(base64_decode($brochuresMailed));
	}
	return $brochuresMailed;
}