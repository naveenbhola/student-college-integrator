<?php
/**
 * This library is will be used across the site to generate a brochure for a course
 * @package     Listing
 * @author      Ankit Garg
 *
 */

class CourseBrochureGenerator {
	private $SHIKSHA_FOLDER = "/var/www/html/shiksha";
	private $EBROCHURE_FOLDER = "/var/www/html/shiksha/mediadata/listingsBrochures/";
	private $CTA_PDF_FOLDER = "/var/www/html/shiksha/mediadata/instituteCtaPdf/";

	function __construct() {
		$this->CI =& get_instance();
		$this->CourseDetailLib = $this->CI->load->library('nationalCourse/CourseDetailLib');
		$this->instituteDetailLib = $this->CI->load->library('nationalInstitute/InstituteDetailLib');
		$this->solrServerLib = $this->CI->load->library('search/SearchServer/SolrServer');

		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		$this->CI->load->config('nationalCourse/courseConfig'); 
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();       
        $this->CI->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
	}

	function generatePdfForCTAs($insttId, $cta) {
		$this->institutedetailsmodel = $this->CI->load->model("nationalInstitute/institutedetailsmodel");

        if(!empty($insttId)) {
            $instituteIds = array($insttId);
        } else {
            $instituteIds = $this->institutedetailsmodel->getAllPrimaryInstitutes();
        }

        if(!empty($cta)) {
            $ctas = array($cta);
        } else {
            $ctas = array("admission", "courses", "scholarship", "reviews", "questions","cutoff");
        }
        
        $shikshaHome = str_replace("https:", "http:", SHIKSHA_HOME);

    	$arrContextOptions=array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		); 
        foreach ($instituteIds as $key => $instituteId) {
        	$instituteObj = $this->instituteRepo->find($instituteId, array("childPageExists", "scholarship"));

        	//generating pdf cover image
        	$coverPdfPath = $this->generatePdfCoverPage($instituteObj);
        	//$shikshaHome = "http://shikshatest03.infoedge.com";

        	//loop for all ctas
	        foreach ($ctas as $key => $cta) {
	        	error_log("\n Institute id - ".$instituteId.", CTA - ".$cta);
	        	error_log("\n Institute id - ".$instituteId.", CTA - ".$cta, 3, "/tmp/log_pdf_generation_".date("d-M-Y").".log");
        		$contentHtml = "";
	        	switch ($cta) {
	        		case 'admission':
	        			if($instituteObj->isAdmissionDetailsAvailable()) {
	        				$contentHtml = file_get_contents($shikshaHome.":3022/listing/pdfgenerator/admission/".$instituteId);
	        				$url = $instituteObj->getAllContentPageUrl("admission");
						}
	        			break;
	        		
	        		case 'courses':
	        			if($instituteObj->isAllCoursePageExists()) {
		        			$contentHtml = file_get_contents($shikshaHome.":3022/listing/pdfgenerator/courses/".$instituteId);
		    				$url = $instituteObj->getAllContentPageUrl("courses");
						}
	        			break;
	        		
	        		case 'questions':
	        			$url = $instituteObj->getAllContentPageUrl("questions");
	        			$contentHtml = file_get_contents($url."/pdf", false, stream_context_create($arrContextOptions));
	        			break;

	        		case 'scholarship':
	        			if(!empty($instituteObj->getScholarships())) {
		        			$url = $instituteObj->getAllContentPageUrl("scholarships");
		        			$contentHtml = file_get_contents($url."/pdf", false, stream_context_create($arrContextOptions));
		        		}
	        			break;

	        		case 'reviews':
	        			if($instituteObj->isReviewPageExists()) {
		        			$url = $instituteObj->getAllContentPageUrl("reviews");
		        			$contentHtml = file_get_contents($url."/pdf", false, stream_context_create($arrContextOptions));
		        		}
	        			break;

	        		case 'cutoff':
	        			if($instituteObj->isCutoffPageExists()) {
	        				$contentHtml = file_get_contents($shikshaHome.":3022/listing/pdfgenerator/cutoff/".$instituteId);
		        			$url = $instituteObj->getAllContentPageUrl("cutoff");
		        		}
	        			break;

	        		default:
	        			# code...
	        			break;
	        	}

	        	if(empty($contentHtml)) {
	        		continue;
	        	}

	        	$contentFileServerPath = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/instituteCtaPdf.html";
	        	$this->writeInFile($contentFileServerPath, $contentHtml);

	        	// Generating footer html
				$footerServerPath = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/footer.html";
				$footerData['instituteName'] = $instituteObj->getName();
				$footerData['createdDate'] = date('d-M-Y', time());
				$footerData['url'] = $url;
				$footer_html_data = $this->CI->load->view("examPages/sectionGuideFooter", $footerData, true);

				$footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/footer.html";
				$this->writeInFile($footer_file_template, $footer_html_data);
				
				//pdf name and path details
	        	$pdfName = $instituteId.".pdf";
				$pdfUrl = MEDIA_SERVER."/mediadata/instituteCtaPdf/".$cta."/".$pdfName;
				$pdfReleativeUrl = "/mediadata/instituteCtaPdf/".$cta."/".$pdfName;
	        	$pdfServerPath = $this->CTA_PDF_FOLDER.$cta."/".$pdfName;
	        	$mergedPdfServerPath = $this->CTA_PDF_FOLDER.$cta."/".'merged-'.$pdfName;

	        	//converting html to brochure
				// echo "/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath";

				exec("/usr/local/bin/wkhtmltopdf --disable-javascript --margin-left 10 --margin-right 10 --margin-top 10 --margin-bottom 30 --page-size A5 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath", $shellOutput, $shellReturn);

				// _p($contentFileServerPath); die;
				if($shellReturn != 0) {
					error_log("\n".'Some error exists for institute $cta $instituteId with error code '. $shellReturn, 3, "/tmp/ctaPdfGenerator.log");
				}

				$fileSizeWithoutCover = filesize($pdfServerPath);
				
				exec("/usr/local/bin/pdftk $coverPdfPath $pdfServerPath cat output $mergedPdfServerPath");
				//exec("/usr/bin/pdfjoin $coverPdfPath $pdfServerPath --outfile $mergedPdfServerPath");

				unlink($pdfServerPath);
				
				$pdfServerPath = $mergedPdfServerPath;
				unlink($contentFileServerPath);
				unlink($footer_file_template);
				// die('123');

				// fetching content from generated pdf
				$fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($pdfServerPath)));
				$fileContent['folderName'] = "instituteCtaPdf/".$cta;
				$fileContent['BROCHURE_NAME'] = $pdfName;
				$fileContent['brochure_url'] = $pdfUrl;
				
				$success = $this->uploadFileToServer($fileContent);
				// _p($success);

				//to be added in DB
				$fileContent['listingId'] = $instituteId;
				$fileContent['listingType'] = $instituteObj->getType();
				$fileContent['cta'] = $cta;
				$fileContent['brochure_url'] = $pdfReleativeUrl.'?v='.time(); //changing pdf absolute url to relative URL
				$fileContent['brochure_size'] = filesize($pdfServerPath);
				$fileContent['brochure_year'] = date('Y', time());
				$fileContent['status'] = 'live';
				$fileContent['userId'] = 1; // User id of 'edy@shiksha.com' to indicate this update has been done by the Brochure Auto Generate Script.

				unlink($pdfServerPath);
				
				if($success == 1 && $fileSizeWithoutCover > 0) {
					//update shiksha_listings_brochures table
					$listingextendedmodel = $this->CI->load->model('nationalCourse/coursepostingmodel');
					$listingextendedmodel->updateCourseBrochure($fileContent);
					$response_array = array('RESPONSE' => 'PDF_FOUND' , 'PDF_URL' => $pdfUrl);				
					//return array('status' => 'success','msg' => 'Created successfully for institute id: '.$instituteId);
				} else {
					// error_log("\n".'Unable to upload file on media server for course:'.$courseObj->getId(),3,"/tmp/courseAutogenerateBrochure.log"); 
					//return array('status' => 'success','msg' => 'Unable to upload file on media server for institute id: '.$instituteId);
				}
			}
			unlink($coverPdfPath);
        }
	}

	function generateBrochure($courseObj, $courseId) {
		$showHtml = true;
		if($_REQUEST['showHtml'] == 1) {
			$showHtml = null;
		}
		$displayData = array();
		$courseMainLocation = $courseObj->getMainLocation();
		if(empty($courseMainLocation)) {
			// error_log("\n".'Could not generate brochure due to empty locations for course id : '.$courseObj->getId(),3,"/tmp/courseAutogenerateBrochure.log"); 
			return array('status' => 'error','msg' => 'Could not generate brochure due to empty locations for course id: '.$courseObj->getId());
		}

		if($courseObj) {
			$displayData = $this->prepareBrochureData($courseObj);
		}
		// _p($displayData); die;
		if(empty($displayData)) {
			return array('status' => 'error','msg' => 'Could not populate data for course id: '.$courseId);
		}

		$contentHtml = $this->CI->load->view('listingCommon/CourseBrochureOverview', $displayData, $showHtml);
		// Generating HTML file which will be finally converted into the PDF..
		$contentFileServerPath = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/request_ebrochure.html";
		$contentFileWebPath = SHIKSHA_HOME."/public/listingsEbrochureTemplates/request_ebrochure.html";
		$this->writeInFile($contentFileServerPath, $contentHtml);

		// Generating footer html
		$footerServerPath = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/footer.html";
		$footerData['instituteName'] = $displayData['instituteObj']->getName();
		$footerData['createdDate'] = date('d-M-Y', time());
		$footer_html_data = $this->CI->load->view("listingCommon/CourseBrochureFooter", $footerData, $showHtml);
		if(!$showHtml) {
			return array();
		}
		// echo $footer_html_data; die;
		$footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/footer.html";
		$this->writeInFile($footer_file_template, $footer_html_data);

		// Naming the pdf Brochure here..
		$current_date = date('Y-m-d-H-i', time());
		$courseBrochureName = "course_".$courseObj->getId()."_".$current_date.".pdf";
		$pdfServerPath = $this->EBROCHURE_FOLDER.$courseBrochureName;
		//converting html to brochure
		exec("/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 30 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath", $shellOutput, $shellReturn);
		if($shellReturn != 0) {
			error_log("\n".'Some error exists for course  '.$courseObj->getId() .'with error code '. $shellReturn,3,"/tmp/courseAutogenerateBrochure.log");
		}
// echo $contentFileServerPath; 
// _p($pdfServerPath); die;
// 		die;
// 		_p("/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 20 --margin-bottom 25 --footer-html $footerServerPath $contentFileServerPath $pdfServerPath");
// die;
		unlink($contentFileServerPath);
		unlink($footer_file_template);

		$brochureUrl = MEDIA_SERVER."/mediadata/listingsBrochures/".$courseBrochureName;
		$brochure_url1 = SHIKSHA_HOME."/mediadata/listingsBrochures/".$courseBrochureName;
		
		// fetching content from generated pdf
		$fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($pdfServerPath)));
		$fileContent['BROCHURE_NAME'] = $courseBrochureName;
		$fileContent['brochure_url'] = $brochureUrl;
		//to be added in DB
		$fileContent['listingId'] = $courseObj->getId();
		$fileContent['listingType'] = "course";
		$fileContent['brochure_size'] = filesize($pdfServerPath);
		$fileContent['cta'] = "brochure";
		$fileContent['is_auto_generated'] = 1;
		$fileContent['brochure_year'] = date('Y', time());
		$fileContent['status'] = 'live';
		$fileContent['userId'] = 1; // User id of 'edy@shiksha.com' to indicate this update has been done by the Brochure Auto Generate Script.

		unlink($pdfServerPath);
		
		$success = $this->uploadFileToServer($fileContent);
		//changing brochure absolute url to relative URL
		$fileContent['brochure_url'] = "/mediadata/listingsBrochures/".$courseBrochureName;

		if($success == 1) {
			//update shiksha_listings_brochures table
			$listingextendedmodel = $this->CI->load->model('nationalCourse/coursepostingmodel');
			$fileContent['brochure_url'] = "/mediadata/listingsBrochures/".$courseBrochureName;
			$listingextendedmodel->updateCourseBrochure($fileContent);
			$response_array = array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $brochure_url);				
			return array('status' => 'success','msg' => 'Created successfully for course id: '.$courseId);
		} else {
			// error_log("\n".'Unable to upload file on media server for course:'.$courseObj->getId(),3,"/tmp/courseAutogenerateBrochure.log"); 
			return array('status' => 'success','msg' => 'Unable to upload file on media server for course id: '.$courseId);
		}
	}

	function prepareBrochureData($courseObj) {
		$instituteObj = $this->instituteRepo->find($courseObj->getInstituteId(), array('scholarship', 'media', 'facility'));
		$courseTypeInfo = $courseObj->getCourseTypeInformation();
		if(empty($courseTypeInfo)) {
			error_log("\n".'Could not generate brochure due to empty hierarchies for course id: '.$courseObj->getId(),3,"/tmp/courseAutogenerateBrochure.log"); 
			return array('status' => 'error','msg' => 'Could not generate brochure due to empty hierarchies for course id: '.$courseObj->getId());
		}
		$displayData = $this->CourseDetailLib->prepareCourseData($courseObj, null, array('instituteObj' => $instituteObj, 'source' => 'autoGenerateBrochure'));
		$contactDetailsArr = modules::run('nationalInstitute/InstituteDetailPage/getLocationsContactWidget',$courseObj, $courseObj->getMainLocation(), true);
		$displayData = array_merge($displayData, $contactDetailsArr);
		$displayData['facilities'] = modules::run('nationalInstitute/InstituteDetailPage/arrangeFacilitiesInOrder',$instituteObj->getFacilities());
		$displayData['scholarships'] = $this->CourseDetailLib->prepareCourseScholarship($instituteObj);
		if(!empty($displayData['facilities']) && !empty($displayData['facilities']['viewFacilities'])) {
			$displayData['facilityInfo'] = $this->instituteDetailLib->prepareFacilitiesInformation($instituteObj->getFacilities(), json_decode($displayData['facilities']['viewFacilities']));
		}
		$mainLocation = $courseObj->getMainLocation();

		$displayData['topCardData'] = $this->instituteDetailLib->getInstitutePageTopCardData($instituteObj, 0, array(), false, $mainLocation);
		if($displayData['topCardData']['instituteImportantData']['naac_accreditation']) {
			unset($displayData['topCardData']['instituteImportantData']['naac_accreditation']);
		}

		$displayData['courseObj'] = $courseObj;
    	$instituteHeaderImage = $instituteObj->getHeaderImage();
		$displayData['headerImage'] = (empty($instituteHeaderImage)) ? null : $instituteObj->getHeaderImage()->getUrl();
		$navigationSection                = $this->CI->config->item('navigationSection');
		$displayData['navigationSection'] = $navigationSection['default'];
		return $displayData;
	}

	function writeInFile($file, $content) {
		if($file == "") {
			return ;
		}
		
		$fp = fopen($file, 'w');
		fwrite($fp, $content);
		fclose($fp);
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

	function generatePdfCoverPage($instituteObj) {
		$instituteId = $instituteObj->getId();
		$displayData = array();
		$displayData['logoUrl'] = $instituteObj->getLogoUrl();
		$displayData['name'] = $instituteObj->getName();
		$mainLocation = $instituteObj->getMainLocation();
		if(!empty($mainLocation)) {
			$displayData['location'] = $mainLocation->getCityName();
		}
		
		$coverPageHtml = $this->CI->load->view('listingCommon/pdfCoverPage', $displayData, true);
		// echo $coverPageHtml; die;
		$coverPagePath = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/coverPage.html";
		$pdfServerPath = $this->EBROCHURE_FOLDER.'coverPage.pdf';
		$this->writeInFile($coverPagePath, $coverPageHtml);

		/*$footer_html_data = $this->CI->load->view("listingCommon/pdfCoverPageFooter", null, true);
		$footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/coverPageFooter.html";
		$this->writeInFile($footer_file_template, $footer_html_data);*/
		// echo "/usr/local/bin/wkhtmltopdf --disable-javascript --margin-left 0 --margin-right 0 --margin-top 0 --margin-bottom 0 --page-size A5 $coverPagePath $pdfServerPath"; die;
		exec("/usr/local/bin/wkhtmltopdf --disable-javascript --margin-left 0 --margin-right 0 --margin-top 0 --margin-bottom 0 --page-size A5  $coverPagePath $pdfServerPath", $shellOutput, $shellReturn);
		return $pdfServerPath;
	}
}