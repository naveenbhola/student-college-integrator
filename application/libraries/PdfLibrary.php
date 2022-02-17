<?php
	
	/**
	 * 
	 */
	class PdfLibrary
	{

		function __construct(){
			$this->CI = & get_instance();
		}
		
		function getThumbNailFromPdf($pdfPath,$fileName,$isPdfLink=true,$width,$height){

			//$pdfPath = "https://images.shiksha.com/mediadata/chpGuides/chp_1_2018-11-27-17-27.pdf";

	    	//$url = "https://images.shiksha.com/mediadata/chpGuides/chp_1_2018-11-27-17-27.pdf";
	    	/*$image = file_get_contents($url);
	    	file_put_contents("/var/www/html/generate.pdf", $image);
	    	sleep(10);
	    	unlink("/var/www/html/generate.pdf");

	    	$image = file_get_contents($url);
	    	file_put_contents("/var/www/html/generate.pdf", $image);
	    	sleep(10);
	    	unlink("/var/www/html/generate.pdf");
	    	*/

	    	if(empty($width)) {
	    		$width = 104;
	    	}

	    	if(empty($height)){
	    		$height = 147;
	    	}

	    	if(empty($pdfPath)){
	    		return array("errMsg" => "pdfPath blank");
	    	}


	    	if($isPdfLink){
	    		$ch = curl_init($pdfPath);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				//curl_setopt($ch, CURLOPT_HEADERFUNCTION, readHeader);
				$resposne = curl_exec($ch);
				$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

				curl_close($ch);
				if($responseCode == 404){
					return array("errMsg" => "File not exists on provided link");
				}

				$arrContextOptions=array(
			        "ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			    ));

				$image = file_get_contents($pdfPath,false,stream_context_create($arrContextOptions));
				$pdfPathGenFile = "/tmp/generateThumbnail.pdf";
				if(ENVIRONMENT == 'development') {
					$pdfPathGenFile = "/var/www/html/shiksha/public/generateThumbnail.pdf";
				}
	    		file_put_contents($pdfPathGenFile, $image);
	    	}else{
	    		if(!file_exists($pdfPath)){
	    			return array("errMsg" => "File not exists on provided path");
	    		}
	    		$pdfPathGenFile = $pdfPath;
	    	}    		
	    	try{
	    		$imagick = new imagick();
	    		$imagick->readImage($pdfPathGenFile."[0]");
	    		$imagick->resizeImage($width,$height,Imagick::FILTER_LANCZOS,1);
		    	$imagick->setImageFormat('jpg');
		    	$imagePath = "/var/www/html/thumbnail.jpg";

		    	$imagick->writeImage($imagePath);

		    	$fileContent = array();

		    	if(empty($fileName)){
		    		$thumbnailName = time()."pdfThumbnail.jpg";	
		    	}else{
		    		$thumbnailName = $fileName;
		    	}

		        // fetching content from generated pdf
		        $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($imagePath)));
		        $fileContent['BROCHURE_NAME'] = $thumbnailName;
		        $fileContent['guide_size'] = filesize($imagePath);
		        $fileContent['guide_year'] = date('Y', time());
		        $fileContent['status'] = 'live';
		        $fileContent['userId'] = 1;
		        $fileContent['creation_date'] = date('Y-m-d H:i:s');

		        $fileContent['folderName'] = 'images';

		        $result = $this->uploadFileToServer($fileContent);

		        if($isPdfLink){
		        	unlink($pdfPathGenFile);
		        }
		        unlink($imagePath);

		        if($result != 200) {
		        	return array("errMsg" => "Curl call Fail");
		        }
		        return array('imageUrl' => "/mediadata/images/".$thumbnailName);	
	    	}catch(Exception $e){
	    		error_log("Exception occured while generating Thumbnail for ".$pdfPath);
	    		error_log("Exception :".$e);
	    		return array("errMsg" => "Pdf thumb creation failed");
	    	}
	    	finally{
	    		unset($imagick);
	    		unlink($pdfPathGenFile);
	    		unlink($imagePath);
	    	}
		}

		function uploadFileToServer($fileContent) {
	        $serverUrl = SITE_PROTOCOL.MEDIA_SERVER_IP."/mediadata/listingsBrochures/writePDFatMediaServer.php";
	        return $this->makeCurlCall($fileContent, $serverUrl);
	    }

	    function makeCurlCall($post_array, $url)
	    {
	        if($url == "") {
	            return ("NO_VALID_URL_DEFINED");
	        }
	        $c = curl_init();
	            curl_setopt($c, CURLOPT_URL,$url);
	        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($c, CURLOPT_POST, 1);
	        curl_setopt($c,CURLOPT_POSTFIELDS,$post_array);
	        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
	        curl_setopt($c, CURLOPT_TIMEOUT, 60);
	        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
	        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
	        $output =  curl_exec($c);
	        $responseCode = curl_getinfo($c,CURLINFO_HTTP_CODE);
	        curl_close($c);
	        return $responseCode;
	    }
	}

?>