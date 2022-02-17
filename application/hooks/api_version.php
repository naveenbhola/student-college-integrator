<?php
function api_version() {

	// get the API version control mapping config
	require dirname(dirname(__FILE__)).'/config/apiVersionMapping.php';

	/***********************************************************
	 * API VERSION : DETERMINE THE VERSION OF THE API TO BE USED
	 ***********************************************************/
	global $appVersion;
	global $appAPIVersion;
	global $requestedAPI;

	$request_uri    = $_SERVER['SCRIPT_URL'];

	// Remove first two url parameters(like /api/v1/User/getUserData to /User/getUserData)
	$request_uri    = trim($request_uri, "/");
	$replaceStr = "api";
	if (substr($request_uri, 0, strlen($replaceStr)) == $replaceStr) {
      $requestedAPI = substr($request_uri, strlen($replaceStr));
    }

	$request_uri    = trim($requestedAPI, "/");
	$version_num	= substr($request_uri, 0, strpos($request_uri, "/"));
	$requestedAPI   = strstr($request_uri, "/");
	$version_num 	= substr($version_num, 1);
	$appVersion     = $version_num;

	// Get the version of the API
	$versionMapping = $config['verions_map'];

	// get the version list of the API called
	$delimiter = '/';
	foreach ($versionMapping as $stockKey => $stock)
	{
	  // $exists = sscanf($requestedAPI.$delimiter, "%s".$stockKey.$delimiter."%s", $stockId);  // scan into a formatted string and return values passed by reference
	  $exists = strpos($requestedAPI, $stockKey);
	  if ($exists !== false){
	  	 $versions = $stock;
	     break;
	  }
	}

	// Get the matching(or closest) version of the API to be used
	foreach ($versions as $value) {
		if($appVersion >= $value)
			$appAPIVersion = $value;
	}

	if(empty($appAPIVersion)){
		$appAPIVersion = max($versions);
	}
}