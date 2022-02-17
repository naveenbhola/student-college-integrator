<?php
/*
   Copyright 2014 Info Edge India Ltd

   $Author: Pranjul

   $Id: collegepredictor_helper.php
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  
function getFormatedData($data){
    $arr = array();
    $arr['collegeGroupName'] = getCollegeGroupNames($data);
    $arr['collegeId']        = getCollegeIds($data);
    return $arr;
}

function getCollegeGroupNames($data){
    $collegeGroupName = '';
    foreach($data as $key=>$value){
        if(!is_numeric($value)){
            $formatedData['collegeGroupName'][] = $value;
        }
    }
    if(!empty($formatedData['collegeGroupName'])){
        $collegeGroupName = "'".implode("','",$formatedData['collegeGroupName'])."'";
    }
    return $collegeGroupName;
}

function getCollegeIds($data){
    $formatedData = array();
    foreach($data as $key=>$value){
        if(is_numeric($value)){
            $formatedData[] = $value;
        }
    }
    return $formatedData;
}
function createPagesForCollegePredictor($branchResults,$start,$count){
	$i=0;
	$branchArray = array();
	foreach($branchResults as $key=>$value){
		if($i>=$start && $i<$start+$count)
			$branchArray[] = $branchResults[$key];
		$i++;
	}
	return $branchArray;
}

function getInputFromPostData($postData,$tabType,$examName) {
	$inputData = array();
	$inputData['examName']  = $examName;
	$inputData['categoryName']      = $postData['category'];
	$inputData['round'] = $postData['round'];

	if(strtoupper($inputData['examName']) == 'MAHCET'){
		$inputData['round'] = 'all';
	}
	
	if($tabType == 'rank') {
		$inputData['rank']  = $postData['rank'];
		$inputData['rankType']  	    = $postData['rank_type'];
		if($postData['rank_type'] == 'Home' || $postData['rank_type'] == 'StateLevel' || $postData['rank_type'] == 'HomeUniversity' || $postData['rank_type'] == 'HyderabadKarnatakaQuota') {
			$inputData['stateName']      = $postData['state'];
			$inputData['cityName']      = $postData['city'];
		}
		$inputData['tabType']      = 'rank';
		$inputData['cityId'] = $postData['city'];
	}
	
	return $inputData;
	
	
}

function getSeoDataForPages($tabId,$examName,$instituteName='',$instituteId=0,$settingsArray){
    $seoData = array();
    
    $examSetting = $settingsArray[strtoupper($examName)];
    $examsArray = $settingsArray['CPEXAMS'][strtoupper($examName)];
    if(key_exists('seoTitle', $examSetting['tab']['rank'])){
        $title  =  ($examName =="JEE-MAINS") ? ($examSetting['tab']['rank']['seoTitle'] ): ($examSetting['tab']['rank']['seoTitle']." - Shiksha.com");
    }else{
        $title  =  ($examName =="JEE-MAINS") ? ($examSetting['tab']['rank']['heading'] ): ($examSetting['tab']['rank']['heading']." - Shiksha.com");
    }
	$description = $examSetting['seo_description'];

    if($tabId == 1){
	    $url = SHIKSHA_HOME.$examsArray['directoryName']."/".$examsArray['collegeUrl'];
    }
    elseif($tabId == 2 ){
	 $url = SHIKSHA_HOME.$examsArray['directoryName']."/".$examsArray['cutoffUrl'];
	}

    $seoData['canonicalURL'] = $url;
    $seoData['title']  = $title;
    $seoData['description'] = $description;
    return $seoData;
}

function getUrlFromRequest($request) {
	
	$url = '';
	
	foreach ($request as $key => $data){ 
		if(is_array($data)) { 
			foreach ($data as $index => $val)  {
				$url .=  $key . '[]='. $val.'&';
			}
		}else {
				$url .=  $key . '='  .$data.'&';			
		}
	}
	$url = preg_replace('/\\\\"/','',$url);
	return $url;
}
function addhttp($url) {
    if (!preg_match("~^(ht)tps?://~i", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}