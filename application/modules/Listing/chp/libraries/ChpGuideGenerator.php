<?php

/**
 * User: Nithish Reddy
 */
	class ChpGuideGenerator
	{
		private $GUIDE_FOLDER = "/var/www/html/shiksha/mediadata/chpGuides/";
		private $DOMAIN_NAME = SHIKSHA_HOME;

		function __construct()
		{
			$this->CI =& get_instance();
			$this->CI->load->config("chpAPIs");
			$this->ChpClient = $this->CI->load->library('ChpClient');
		}

		public function generateCHPGuide($chpId,$url)
	    {
	        if(empty($chpId) || empty($url))
	        {
	            return;
	        }
	        $saveGuideurl = $this->CI->config->item('SAVE_GUIDE_URL');

	        // Naming the pdf Brochure here..
	        $current_date = date('Y-m-d-H-i', time());
	        $chpGuideName = "chp_".$chpId."_".$current_date.".pdf";

	        $params = array('url' => $url,"domainName" => $this->DOMAIN_NAME);

	        $chpPageUrl = addingDomainNameToUrl($params);

	        $pdfServerPath = $this->GUIDE_FOLDER.$chpGuideName;

	        $command = '/usr/local/bin/wkhtmltopdf';
	        if(ENVIRONMENT == 'development') {
	            $command = '/usr/bin/wkhtmltopdf';
	        }	      

	        exec($command." --custom-header User-Agent 'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19' --custom-header-propagation "." ". $chpPageUrl." ". $pdfServerPath, $shellOutput, $shellReturn);
	        if($shellReturn != 0) {
	            error_log("\n".'Some error exists for chp  '.$chpId .'with error code '. $shellReturn,3,"/tmp/chpAutogenerateGuide.log");
	            return array('status' => 'error','msg' => 'Unable to upload guide on media server for chp id: '.$chpId,'chpId' => $chpId);
	        }

	        $guideUrl = MEDIA_SERVER."/mediadata/chpGuides/".$chpGuideName;

	        $fileContent = array();

	        // fetching content from generated pdf
	        $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($pdfServerPath)));
	        $fileContent['BROCHURE_NAME'] = $chpGuideName;
	        $fileContent['guide_url'] = $guideUrl;
	        $fileContent['guide_size'] = filesize($pdfServerPath);
	        $fileContent['guide_year'] = date('Y', time());
	        $fileContent['status'] = 'live';
	        $fileContent['userId'] = 1;
	        $fileContent['creation_date'] = date('Y-m-d H:i:s');

	        $fileContent['folderName'] = 'chpGuides';

	        unlink($pdfServerPath);

	        $success = $this->uploadFileToServer($fileContent);

	        $postData = "";

	        if($success == 1) {
	            //$postData['chpId'] = $chpId;
	            //$postData['pdfUrl'] = ;
	            //$postData['key'] = base64_encode("$#1K$#A_(#9_90F");
	            $postData = "chpId=".$chpId."&pdfUrl=/mediadata/chpGuides/".$chpGuideName."&key=".base64_encode("$#1K$#A_(#9_90F");
	            $headerArray = array('Content-Type: application/x-www-form-urlencoded');
	            $result = $this->ChpClient->makeCURLCall('POST',$saveGuideurl,$postData,$headerArray);
	            $result = json_decode($result,true);
	            return array('status' => 'success','msg' => 'gudie uploaded on media server for chp id: '.$chpId);
	        } else {
	            return array('status' => 'success','msg' => 'Unable to upload guide on media server for chp id: '.$chpId);
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
	        curl_close($c);
	        return $output;
	    }
	}
?>