<?php 
function getDepttIdOnStream($needle, $haystack){
	foreach ($haystack as $depttId => $deptStreams) {
		if($needle == $deptStreams['streamId']){
			return $depttId;
		}
	}
	return 0;
}

function formatDataAccordingToOldConfig($data){
	$formattedData = array();
	foreach ($data as $value) {
		$formattedData[$value['courseId']]['institute_alias']  = '';
		$formattedData[$value['courseId']]['seo_title']        = $value['seoTitle'];
		$formattedData[$value['courseId']]['seo_description']  = $value['seoDescription'];
		$formattedData[$value['courseId']]['seo_keywords']     = '';
		$formattedData[$value['courseId']]['alt_image_header'] = $value['altImageHeader'];
		$formattedData[$value['courseId']]['seo_url']          = $value['seoUrl'];
		$formattedData[$value['courseId']]['external']         = 'yes';
		$formattedData[$value['courseId']]['instituteId']      = $value['instituteId'];
	}
	return $formattedData;
}

function compareDataBeforeUpdate($oldData, $newData){
	foreach ($oldData as $key => $value) {
		if($newData[$key] != $value){
			return true;
		}
	}
	return false;
}