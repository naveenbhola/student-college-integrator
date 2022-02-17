<?php
$brochureContent = gzuncompress(base64_decode($_POST['FILE_CONTENT']));
	//  error_log("AMIT_EB name = ".$_POST['BROCHURE_NAME'].", Data = ".print_r($brochureContent, true));
	    // $brochurePath = "/var/www/html/shiksha/mediadata/listingsBrochures/".$_POST['BROCHURE_NAME'];
		$folderName = 'listingsBrochures';
		if(!empty($_POST['folderName'])) {
			$folderName = $_POST['folderName'];
		}
	    $brochurePath = "/var/www/html/shiksha/static_mediadata/".$folderName."/".$_POST['BROCHURE_NAME'];
	    $fp = fopen($brochurePath,'w');
	    fwrite($fp,$brochureContent);
	    fclose($fp);
	    die("1");
