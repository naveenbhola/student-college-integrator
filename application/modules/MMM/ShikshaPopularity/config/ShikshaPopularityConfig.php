<?php
	
	$config = array();
	
	global $parameterFunctionMapping ;
	$parameterFunctionMapping = array('pageViews' => "getTotalViewsOnCourse",
									'responseCount' => "getTotalResponseOnCourse"

								);

	global $pageViewFactor;
	$pageViewFactor = 1;

	global $responseFactor;
	$responseFactor = 10;

	global $rankValue;

	global $rankFormula;
	$rankFormula = "pageViews*$pageViewFactor + responseCount*$responseFactor + (ranklimit - rankValue)*rankWeight";

	global $ranklimit;
	$ranklimit = 150;

	global $rankValue;
	$rankValue = 150;

	global $rankWeight;
	$rankWeight = 10000;

?>