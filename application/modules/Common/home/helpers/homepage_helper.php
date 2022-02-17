<?php 
function rearrangeTheArticles($data){
	$finalData = array();
	$fixPosArr = array();
	$othPosArr = array();
	foreach ($data as $value) {
		if($value['position'] != '' && $value['position'] > 0)
		{
			$fixPosArr[$value['position']] = $value;
		}
		else
		{
			$othPosArr[] = $value;
		}
	}
	$posArr1 = 1;
	$posArr2 = 0;
	for ($i=0; $i<count($data); $i++) {
		if(array_key_exists($posArr1, $fixPosArr)) {
			$finalData[$posArr1] = $fixPosArr[$posArr1];
		} else {
			$finalData[$posArr1] = $othPosArr[$posArr2];
			$posArr2++;
		}
		$posArr1++;
	}
	return $finalData;
}

function generateTargetUrl($featuredCollege){
	
	$ci =& get_instance();
	$ci->load->library('homepage/Homepageslider_client');
	$slider_object = new Homepageslider_client();
	foreach($featuredCollege['paid'] as &$value){  //Pass by Reference, so that if you change the reference the original value will change.
	$value['target_url'] = $slider_object->getTrackCtrUrl($value['banner_id'], $value['target_url']); 
	}
	foreach ($featuredCollege['free'] as &$value) {
	$value['target_url'] = $slider_object->getTrackCtrUrl($value['banner_id'], $value['target_url']); 
	}

	return $featuredCollege;
}

function getRoundOffValues($data, $mulArr){
	$data['national']['instCount'] = getRoundOffCounters($mulArr['instMul'], $data['national']['instCount']);
	$data['national']['reviewsCount'] = getRoundOffCounters($mulArr['reviewMul'], $data['national']['reviewsCount']);
	$data['national']['questionsAnsweredCount'] = getRoundOffCounters($mulArr['quesMul'], $data['national']['questionsAnsweredCount']);
	$data['national']['careerCount'] = getRoundOffCounters($mulArr['careerMul'], $data['national']['careerCount']);
	$data['national']['examCount'] = $data['national']['examCount'];
	$data['national']['baseCourseCount'] = $data['national']['baseCourseCount'];
	$data['national']['specializationCount'] = $data['national']['specializationCount'];

	$data['abroad']['universityCount'] = getRoundOffCounters($mulArr['univMul'], $data['abroad']['universityCount']);
	$data['abroad']['courseCount'] = getRoundOffCounters($mulArr['courseMul'], $data['abroad']['courseCount']);
	$data['abroad']['countryCount'] = getRoundOffCounters($mulArr['countryMul'], $data['abroad']['countryCount']);
	return $data;
}

function getRoundOffCounters($multiple, $value){

	$rem = $value % $multiple;
	if($rem > 0){
		$value = $value - $rem;
	} else if($rem == 0){
		$value = $value - $multiple;
	}

	return $value;
}
