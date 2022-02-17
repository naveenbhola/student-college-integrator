<?php 
function redirectDeadUrls($invalidCourseIds,$courseArray){
	if(count($invalidCourseIds) == count($courseArray)){
		$url = SHIKSHA_HOME.'/compare-colleges';
	}
	else{
		$url = SHIKSHA_HOME.'/compare-colleges-'.implode(array_diff($courseArray,$invalidCourseIds), '-');
	}
	redirect($url,'location',301);
	exit();
}
?>