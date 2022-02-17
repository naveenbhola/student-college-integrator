<?php

function getNormalizedDuration($duration) {
	$durationArray=array();
	$durationList = explode(",","$duration");
	foreach ($durationList as $val) {
		$durationval = checkDuration($val);
		if($durationval!=0) {
			$durationArray[]= $durationval;
		}
	}
	return $durationArray;
}

function checkDuration($duration) {
	$durationvalues = explode(" ",$duration);
	if(is_numeric($durationvalues['0'])) {
		$durationval = $durationvalues['0'] * getDurationNormalizationCoefficient($durationvalues['1']);
	} else {
		$durationval = 0;
	}
	if($durationval>=240) {
		return (round($durationval/240))*240;
	} else if ($durationval>=20) {
		return (round($durationval/20))*20;
	} else if ($durationval>=5) {
		return (round($durationval/5))*5;
	} else if ($durationval>=1) {
		return (round($durationval/1))*1;
	} else {
		return (round($durationval*8))/8;
	}
	return $durationval;
}

function getDurationNormalizationCoefficient($durationType) {
	$normalizationCoeff = 0;
	switch(strtolower($durationType)) {
		case "week":
		case "weeks":
			$normalizationCoeff = 5;
			break;
		case "year":
		case "years":
			$normalizationCoeff = 240;
			break;
		case "hour":
		case "hours":
			$normalizationCoeff = 1/8;
			break;
		case "month":
		case "months":
			$normalizationCoeff = 20;
			break;
		case "day":
		case "days":
			$normalizationCoeff = 1;
			break;
	}
	return $normalizationCoeff;
}

/**
	* ascii127Convert is a function that removes all charecters that fall outside the range of 32-127
*/
function ascii127Convert($string) {
	return preg_replace('/[^\x20-\x7F]/e', ' ', $string);
}

function courseLevelArray(){
	$courseLevelArray = array('degree'=>array('under graduate'=>'under graduate degree','post graduate'=>'post graduate degree'),
							'diploma'=>array('diploma'=>'diploma','post graduate diploma'=>'post graduate diploma'),
							'dual degree'=>array('under graduate'=>'under graduate degree','post graduate'=>'post graduate degree'),
							'exam preparation',
							'vocational',
							'certification');
	return $courseLevelArray;
}

function getCourseLevelDisplay($courseLevel, $courseLevel1){
	$courseLevelArray = courseLevelArray();
	$courseLevelDisplay = $courseLevel;
	if(isset($courseLevelArray[strtolower($courseLevel)])) {
        $tempCLevel = $courseLevelArray[strtolower($courseLevel)];
		if(is_array($tempCLevel)) {
			if(isset($tempCLevel[strtolower($courseLevel1)])) {
				$tempCLevel = $tempCLevel[strtolower($courseLevel1)];
				$courseLevelDisplay = $tempCLevel;
			} else {
				$courseLevelDisplay = "Others";
			}
		} else {
			$courseLevelDisplay = $tempCLevel;
		}
    } else {
		if(in_array(strtolower($courseLevel), $courseLevelArray)) {
			$courseLevelDisplay = $courseLevel;
		} else {
			$courseLevelDisplay = "Others";
		}
    }
	return $courseLevelDisplay;
}


function solrDateFormater($date) {
	$datesp = explode(" ",$date);
	$newdate = $datesp[0]."T".$datesp[1]."Z";
	return $newdate;
}

function sanitizeQerString($qerString = ''){
	$params = array();
	$params['instituteId'] = 'institute_id';
	$params['cType'] = 'course_type_cluster';
	$params['courseLevel'] = 'course_level_cluster';
	$params['ldb_course_id'] = 'course_ldb_id';
	$params['cityId'] = 'course_city_id';
	$params['countryId'] = 'course_country_id';
	$params['state'] = 'course_state_name';
	$params['continent'] = 'course_continent_name';
	$params['sduration'] = 'course_duration_normalized';
	$params['rawTextQuery'] = 'raw_text_query';
	
	
	foreach($params as $key=>$value){
		$qerString = str_ireplace($key, $value, $qerString);
	}
	return $qerString;
}

function sanitizeUrl($url = ''){
	$params = array(
				" " => "%20"
				);
	
	foreach($params as $key=>$value){
		$url = str_ireplace($key, $value, $url);
	}
	return $url;
}

function getQERFieldsForParamValidate(){
	$qerFields = getQERFields();
	array_push($qerFields, 'institute_id');
	return $qerFields;
}

function getQERFields(){
	$qerFields = array('course_level_cluster',
					   'course_type_cluster',
					   'course_duration_normalized',
					   'course_locality_id',
					   'course_zone_id',
					   'course_country_id',
					   'course_state_name',
					   'course_state_id',
					   'raw_text_query',
					   'course_city_id',
					   'course_ldb_id'
					   );
	return $qerFields;
}
	
function isQerParamPresentOtherThanLocation($params){
	$qerFields = getQERFieldsForParamValidate();
	$locationFields = array('course_country_id', 'course_city_id', 'course_zone_id', 'course_locality_id', 'course_state_name');
	foreach($locationFields as $value){
		if(($key = array_search($value, $qerFields)) !== false) {
			unset($qerFields[$key]);
		}
	}
	
	$present = false;
	foreach($params as $key => $value){
		if(in_array($value, $qerFields)){
			$present = true;
			break;
		}
	}
	return $present;
}

function getURLParamValue($url, $paramKeyArray = NULL, $excludeValue = array()){
	$paramValueArr = false;
	$validFields = array();
	$validFields = getQERFieldsForParamValidate();
	if($paramKeyArray != NULL){
		if(!is_array($paramKeyArray)){
			$paramKeyArray = (array)$paramKeyArray;
		}
		if(!is_array($excludeValue)){
			$excludeValue = (array)$excludeValue;
		}
		$temp = explode("?", $url);
		$remainingURL = $temp[1];
		$explodedParamArray = explode("&", $remainingURL);
		if(!empty($explodedParamArray)){
			foreach($explodedParamArray as $k=>$v){
				$valueExploded = explode("=", $v);
				if(in_array($valueExploded[0], $paramKeyArray)){
					if(!in_array($valueExploded[1], $excludeValue)){
						if($valueExploded[0] == "fq"){ //TO DO: dirty check, this should be handled elsewhere. Only see this if interested in fq param else skip this check 
							$tempValueExploded = explode(":", $valueExploded[1]);
							if(in_array($tempValueExploded[0], $validFields)){
								$paramValueArr[$valueExploded[0]][] = $valueExploded[1];
							}
						} else {
							$paramValueArr[$valueExploded[0]][] = $valueExploded[1];
						}
					}
				}
			}
		}
	}
	return $paramValueArr;
}

function getURLParamString($paramsValueArray = array()){
	$urlParamString = "";
	if(is_array($paramsValueArray) && !empty($paramsValueArray)){
		foreach($paramsValueArray as $key=>$value){
			$tempValueString = "";
			foreach($value as $k=>$v){
				$tempValueString .= $key."=".$v."&";
			}
			$urlParamString .= $tempValueString;
		}
	}
	return $urlParamString;
}


function clusterCmpFunction($a,$b) {
	$aData = explode("/", $a);
	$bData = explode("/", $b);
	$aDataCount = count($aData);
	$bDataCount = count($bData);
	
	$count = $aDataCount;
	if($bDataCount > $aDataCount){
		$count = $bDataCount;
	}
	$returnValue = 0;
	for($i=0; $i < $count; $i++){
		$aVal = 0;
		$bVal = 0;
		if(isset($aData[$i]) && !empty($aData[$i])){
			$aVal = $aData[$i];	
		}
		if(isset($bData[$i]) && !empty($bData[$i])){
			$bVal = $bData[$i];	
		}
		if($aVal > $bVal){
			$returnValue = 1;
			break;
			
		} else if($aVal < $bVal){
			$returnValue = 0;
			break;
		}
	}
	return $returnValue;
}

/**
* Sets key/value pairs at any depth on an array.
* @param $data an array of key/value pairs to be added/modified
* @param $array the array to operate on
*/
function setNodes($data, &$array) {
	$separator = '/'; // set this to any string that won't occur in your keys
	foreach ($data as $name => $value) {
		if (strpos($name, $separator) === false) {
			// If the array doesn't contain a special separator character, just set the key/value pair. 
			// If $value is an array, you will of course set nested key/value pairs just fine.
			$array[$name] = $value;
		} else {
			// In this case we're trying to target a specific nested node without overwriting any other siblings/ancestors. 
			// The node or its ancestors may not exist yet.
			$keys = explode($separator, $name);
			// Set the root of the tree.
			$opt_tree =& $array;	
			// Start traversing the tree using the specified keys.
			while ($key = array_shift($keys)) {
				// If there are more keys after the current one...
				if ($keys) {
					if (!isset($opt_tree[$key]) || !is_array($opt_tree[$key])) {
						// Create this node if it doesn't already exist.
						$opt_tree[$key] = array();
					}
					// Redefine the "root" of the tree to this node (assign by reference) then process the next key.
					$opt_tree =& $opt_tree[$key];
				} else {
					// This is the last key to check, so assign the value.
					$opt_tree[$key] = $value;
				}
			}
		}
	}
}

function getQerFieldsPresentInUrl($qerUrl = ""){
	$qerFields = getQERFieldsForParamValidate();
	$explodeList = explode("&", $qerUrl);
	$qerFieldsPresentInUrl = array();
	foreach($explodeList as $key=>$value){
		$explodeOnEquality = explode("=", $value);
		if(count($explodeOnEquality) > 1){
			$fieldString = $explodeOnEquality[1];
			$explodeOnColon = explode(":", $fieldString);
			if(count($explodeOnColon) > 0){
				$field = trim($explodeOnColon[0]);
				if(in_array($field, $qerFields)){
					$qerFieldsPresentInUrl[] = $field;
				}
			}
		}
	}
	return $qerFieldsPresentInUrl;
}

function calculatePageId($resultPerPage, $currentPageStart = 0){
	$pageId = 0;
	if($currentPageStart == 0){
		$pageId = 1;
	} else if($currentPageStart > 0){
		if($currentPageStart != 0 && $resultPerPage != 0){
			$division = $currentPageStart / $resultPerPage;
			$pageId = $division + 1;
		}
	}
	return $pageId;
}


function locationCmp($a, $b){
	if (strcasecmp($a['name'], $b['name']) == 0) {
        return 0;
    }
    return (strcasecmp($a['name'], $b['name']) < 0) ? -1 : 1;
}

function courseOrderCmp($a, $b){
	if((int)$a['course_order'] == (int)$b['course_order']) {
        return 0;
    }
	if((int)$a['course_order'] < (int)$b['course_order']) {
        return -1;
    } else {
		return 1;
	}
}

function courseViewCountCmp($a, $b){
	if((int)$a['course_view_count'] == (int)$b['course_view_count']) {
        return 0;
    }
	if((int)$a['course_view_count'] < (int)$b['course_view_count']) {
        return 1;
    } else {
		return -1;
	}
}


function isParamPresentInQueryString($queryString = "", $param = NULL){
	$originalParam = $param;
	$queryString = trim($queryString);
	$characterFound = false;
	if(strlen($queryString) > 0 && !empty($param)){
		//special case q=*:*
		$specialCaseSatified = false;
		if($originalParam == "q"){
			$specialParamStrings = array("q=*:*", "q=*%3A*");
			foreach($specialParamStrings as $value){
				$tempPos = strpos($queryString, $value);
				if($tempPos !== false){
					$specialCaseSatified = true;
					break;
				}
			}
		}
		$specialCaseSatified = false;	
		if(!$specialCaseSatified){
			$param = $param."=";
			$pos = strpos($queryString, $param);
			$lastOccurence = strrpos($queryString, $param);
			if($lastOccurence !== false){
				if($pos !== false){
					$flag = true;
					while($pos <= $lastOccurence && $flag){
						if($pos !== false){
							$characters = array("&", "?");
							if($pos-1 >= 0){
								$previousCharacter = $queryString[$pos-1];
								if(!in_array($previousCharacter, $characters)){
									$pos = strpos($queryString, $param, $pos+1);
									$flag = true;
								} else {
									$characterFound = true;
									$flag = false;
								}
							} else {
								$characterFound = true;
								$flag = false;
							}
						} else {
							$flag = false;
						}
					}	
				}
			}
		}
	}
	return $characterFound;
}

function isQERFreeFromQueryParameter($queryString = ""){
	$return = false;
	if(!empty($queryString)){
		$altQPos1 = strpos($queryString, 'q.alt=*%3A*');
		$altQPos2 = strpos($queryString, 'q.alt=*:*');
		$altQPresent = false;
		if($altQPos1 !== false || $altQPos2 !== false){
			$altQPresent = true;
		}
		
		$qPos1 = strpos($queryString, 'q=*%3A*');
		$qPos2 = strpos($queryString, 'q=*:*');
		$qPresent = false;
		if($qPos1 !== false || $qPos2 !== false){
			$qPresent = true;
		}
		if($altQPresent){
			$return = true;
		}
	}
	return $return;
}

function getFQParamValue($qerString, $param){
	$qerString = trim($qerString);
	$index = strpos($qerString, $param);
	$colonIndex = strpos($qerString, ":", $index);
	$ampersandIndex = strpos($qerString, "&", $colonIndex);
	if($ampersandIndex === false){
		$ampersandIndex = strlen($qerString);
	}
	$valueString = substr($qerString, $colonIndex+1, $ampersandIndex - $colonIndex - 1);
	$explodedData = explode("\"", $valueString);
	$valueList = array();
	$strictedCharacterList = array("(", ")", "'", " ");
	if(count($explodedData) > 0){
		foreach($explodedData as $key => $value){
			if(strlen(trim($value)) > 0 && !in_array($value, $strictedCharacterList)){
				array_push($valueList, trim($value));
			}
		}
	}
	return $valueList;
}

function getQERFieldValues($params = array(), $qerFieldStr = ""){
	$paramValue = array();
	foreach($params as $value){
		$paramValue[$value] = getFQParamValue($qerFieldStr, $value);
	}
	return $paramValue;
}

function getRandomAlphaNumericStr($charCount){
	$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$string = '';
	$totalCharacters = strlen($characters);
	for($i = 0; $i < $charCount; $i++) {
		$string .= $characters[mt_rand(0, $totalCharacters - 1)];
	}
	return $string;
}

function getLocationPrioritySet($qerParams = array()){
	$validLocationSet = array('course_locality_id', 'course_zone_id', 'course_city_id', 'course_state_id', 'course_country_id');
	$locationSet = array();
	if(!empty($qerParams)){
		foreach($qerParams as $key => $keyValue){
			foreach($validLocationSet as $val){
				if($key == $val){
					$locationSet[$key] = $keyValue;
					break;
				}
			}
		}
	}
	return $locationSet;
}

function getParameterValue($qerString = "", $paramName = ""){
	$return = false;
	if(!empty($qerString) && !empty($paramName)){
		parse_str($qerString, $output);
		if(array_key_exists($paramName, $output)){
			$return = $output[$paramName];
		}
	}
	return $return;
}

function updateCityUsingCityMapping($citiesFound = array(), $cityMapping = array()){
	$returnList = $citiesFound;
	if(is_array($citiesFound) && is_array($cityMapping) && !empty($citiesFound) && !empty($cityMapping)){
		$returnList = array();
		foreach($citiesFound as $cityId){
			if(array_key_exists($cityId, $cityMapping)){
				$returnList = array_merge($returnList, $cityMapping[$cityId]);
			} else {
				array_push($returnList, $cityId);
			}
		}
	}
	if(is_array($returnList) && !empty($returnList)){
		$returnList = array_unique($returnList);
	}
	return $returnList;
}

