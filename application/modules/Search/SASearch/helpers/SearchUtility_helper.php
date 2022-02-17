<?php 

function sanitizeUrl($url = ''){
     	$params = array(
				" " => "%20"
				);
	
	foreach($params as $key=>$value){
		$url = str_ireplace($key, $value, $url);
	}
	return $url;
}

function escapeSolrQueryString($inputQueryString)
    {
        $match = array('+', ' ');
        $replace = array('%2B', '%20');
        $outputQueryString = str_replace($match, $replace, $inputQueryString);
        
        return $outputQueryString;
    }
?>