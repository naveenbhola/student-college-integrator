<?php

function checkIfCourseTabRequired($subcategory_id) {
	$CI = &get_instance();
	$courseCommonLib = $CI->load->library('coursepages/CoursePagesCommonLib');
	$courseHomeArray = $courseCommonLib->getCourseHomePageDictionary();
	if(array_key_exists($subcategory_id, array_keys($courseHomeArray))) {

		return true;
			
	} else {

		return false;

	}
}


function urlExists($fileUrl) {
	$AgetHeaders = @get_headers($fileUrl);
	if (preg_match("|200|", $AgetHeaders[0])) {	// file exists
		return true;
	} else {					// file doesn't exists
		return false;
	}
}
