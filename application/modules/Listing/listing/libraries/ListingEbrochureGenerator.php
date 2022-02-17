<?php
/**
 * ListingProfileCompletion Controller Class
 *
 * This library is will be used across the site to get percentage completion for a lisiting
 *
 * @package     Enterprise
 * @subpackage  Libraries
 * @author      Aditya Roshan
 *
 */

class ListingEbrochureGenerator {

	private $CI;
	private $cezpdf;
	private $listing_type_id = NULL;
	private $listing_type = NULL;
	private $function_name = NULL;
	private $institute_repository = NULL;
	private $course_repository = NULL;
	private $course_model = NULL;
	private $tcp_pdf_obj = NULL;
	private $SHIKSHA_FOLDER = "/var/www/html/shiksha/";
	private $EBROCHURE_FOLDER = "/var/www/html/shiksha/mediadata/listingsBrochures/";
	private $EBROCHURE_BASE_URL;
	private $PROFILE_LOWER_LIMIT = 30;

	function __construct() {
		$this->CI =& get_instance();
		$this->ListingProfileLib = $this->CI->load->library('listing/ListingProfileLib');
		$this->CI->load->builder('ListingBuilder','listing');
		$listingbuilder = new ListingBuilder();
		
		$this->institute_repository = $listingbuilder->getInstituteRepository();
		$this->course_repository = $listingbuilder->getCourseRepository();

		$this->abroad_institute_repository = $listingbuilder->getAbroadInstituteRepository();
		$this->abroad_course_repository = $listingbuilder->getAbroadCourseRepository();
		$this->university_repository = $listingbuilder->getUniversityRepository();
		$this->abroadListingCommonLib = $this->CI->load->library('listing/AbroadListingCommonLib');
	}
	
	private function _setData($listing_type, $listing_type_id) {
		$this->listing_type = strtolower($listing_type);
		$this->listing_type_id = $listing_type_id;
		$this->EBROCHURE_BASE_URL = MEDIA_SERVER."/mediadata/listingsBrochures/";
		// $this->EBROCHURE_BASE_URL = "http://172.16.3.225/mediadata/listingsBrochures/";
	}
	
	function getEbrochureURL($listing_type, $listing_type_id) {
		
		if($listing_type == "" || $listing_type_id == "") {
			return array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "INPUT DATA NOT DEFINED");
		}
		
		$this->_setData($listing_type, $listing_type_id);
		
		switch($this->listing_type) {
					
			case "course":
					$response_array = $this->_getCourseEbrochureURL();
				break;
			
			case "institute":
					$response_array = $this->_getInstituteEbrochureURL();
				break;
			default:
					$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "NOT A VALID INPUT");
				break;
		}
		
		return $response_array;	
	}
	
	private function _getInstituteEbrochureURL() {
		$flagshipCourseId = $this->institute_repository->getFlagshipCourseOfInstitute($this->listing_type_id);
		return ($this->getEbrochureURL('course', $flagshipCourseId));
	}
	
	private function _getCourseEbrochureURL() {
		
		$listingextendedmodel = $this->CI->load->model('listing/listingextendedmodel');
		$course_brochure_info = $listingextendedmodel->getListingEBrochureInfo($this->listing_type, $this->listing_type_id);
		
		$course_brochure_url = MEDIAHOSTURL.$course_brochure_info[0]['ebrochureUrl'];
		if(count($course_brochure_info) && $this->urlExists($course_brochure_url)) {
			return array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $course_brochure_url);
		} else {
			// return ($this->genearteEbrochure($this->listing_type, $this->listing_type_id));
			$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "BROCHURE DOESN'T EXIST");
		}
	}
	
	
	function genearteEbrochure($listing_type, $listing_type_id) {
		
		$this->_setData($listing_type, $listing_type_id);

		if(empty($this->listing_type_id) || empty($this->listing_type)) {
			return FALSE;
		}

		if($listing_type == "course") {
			$response = $this->courseEbrochure();
		} else {
			$response = $this->instituteEbrochure();
		}
		
		return $response;
	}
	function genearteEbrochureAbroad($listing_type, $listing_type_id) {
		
		$this->_setData($listing_type, $listing_type_id);

		if(empty($this->listing_type_id) || empty($this->listing_type)) {
			return FALSE;
		}

		if($listing_type == "course") {
			$response = $this->abroadCourseEbrochure();
		} else {
			$response = $this->instituteEbrochure();
		}
		
		return $response;
	}
	function instituteEbrochure() {
		$flagshipCourseId = $this->institute_repository->getFlagshipCourseOfInstitute($this->listing_type_id);
		return ($this->genearteEbrochure('course', $flagshipCourseId));
	}	
	
	function courseEbrochure() {

		$course_object = $this->course_repository->findCourseWithValueObjects($this->listing_type_id, array('description'));		
		$institute_id = $course_object->getInstId();
		$institute_object = $this->institute_repository->findInstituteWithValueObjects($institute_id,array('description','joinreason'));

		$response_array = $this->checkInstituteEligibilityForBrochureGeneration($institute_object);

		if($response_array['RESPONSE'] == "NO_DATA_FOUND") {  // i.e. Need not to generate the brochure..
			error_log("COURSEBROCHURE GENERATION : ".print_r($response_array, true));
			return $response_array;
		}

		$data['course'] = $course_object;
		$data['institute'] = $institute_object;
		$photos = $institute_object->getPhotos();
		if(is_array($photos) && count($photos)>0) {
			$index = rand(0, count($photos)-1);
			$data['photo'] = $photos[$index]->getURL();
		}

		$course_model = $this->CI->load->model('coursemodel');

                $profile_array = $course_model->getProfileCompletionForCourses(array($this->listing_type_id));
		$actual_percentage = "";
	
                if(is_array($profile_array) && !empty($profile_array[$this->listing_type_id])) {
			$actual_percentage = $profile_array[$this->listing_type_id];
		} else {
			$actual_percentage = $this->ListingProfileLib->updateCourseProfileCompletion($this->listing_type_id);
		}

                if(empty($actual_percentage) || $actual_percentage < $this->PROFILE_LOWER_LIMIT) {
			$listingextendedmodel = $this->CI->load->model('listingextendedmodel');
			$listingextendedmodel->updateListingEBrochureInfo($this->listing_type, $this->listing_type_id, "", TRUE);			
			$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "COURSE (ID - ".$this->listing_type_id.") PROFILE PERCENTAGE (".$actual_percentage." %) NOT ENOUGH TO GENERATE EBROCHURE");
			error_log("COURSEBROCHURE GENERATION : ".print_r($response_array, true));
			return $response_array;
		}

                $specializations =  $course_model->getSpecializationIdsByClientCourse(array($this->listing_type_id));
		if(count($specializations)>0) {
			$data['specializations'] = $specializations[$this->listing_type_id];
		}

		if(!empty($actual_percentage)) {				
			$view_name = "";
			$html = "";
			/*
			if($actual_percentage > 30) {
				$view_name = "listing/listingPage/request_ebrochure_type1";
				$html = $this->CI->load->view($view_name,$data,true);
			} else if(10 <= $actual_percentage  && $actual_percentage <= 30) {				
				$view_name = "listing/listingPage/request_ebrochure_type2";
				$html = $this->CI->load->view($view_name,$data,true);
			}*/
			
			$view_name = "listing/listingPage/request_ebrochure_type1";
			$html = $this->CI->load->view($view_name,$data,true);
			
			// Generating HTML file which will be finally converted into the PDF..
			$file = $this->SHIKSHA_FOLDER."public/listingsEbrochureTemplates/request_ebrochure.html";
			$url = SHIKSHA_HOME."/public/listingsEbrochureTemplates/request_ebrochure.html";			
			$this->writeInFile($file, $html);
			// _p(file_get_contents($file));die;
			// Generating footer file with the dynamic content..
			$footer_html = SHIKSHA_HOME."/public/listingsEbrochureTemplates/footer.html";
			$footerData['instituteName'] = $institute_object->getName();
			$footerData['createdDate'] = date('d-M-Y', time());
			$footer_html_data = $this->CI->load->view("listing/listingPage/ebrochureFooter.php", $footerData, true);
			$footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/footer.html";
			$this->writeInFile($footer_file_template, $footer_html_data);
			
			// We have static header..
			$header_html = SHIKSHA_HOME."/public/listingsEbrochureTemplates/header.html";
			
			// Naming the pdf Brochure here..
			$current_date = date('Y-m-d-H-i', time());
			$course_brochure_name = $this->listing_type."_".$this->listing_type_id."_".$current_date.".pdf";
			$pdf_file_path = $this->EBROCHURE_FOLDER.$course_brochure_name;
			
			// $this->CI->benchmark->mark('pdf_start');
			// shell_exec("/usr/local/bin/wkhtmltopdf-i386 --margin-left 0 --margin-right 0 --margin-top 17 --margin-bottom 25 --header-html $header_html --footer-html $footer_html $url $pdf_file_path");
			shell_exec("/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 17 --margin-bottom 25 --header-html $header_html --footer-html $footer_html $url $pdf_file_path");
			// $this->CI->benchmark->mark('pdf_end');
			// echo "<br> Total PDF Generation time = ".$this->CI->benchmark->elapsed_time('pdf_start', 'pdf_end');

			unlink($file);
			unlink($footer_file_template);
			
			$brochure_url = $this->EBROCHURE_BASE_URL.$course_brochure_name;
			$brochure_url1 = SHIKSHA_HOME."/mediadata/listingsBrochures/".$course_brochure_name;
			
			//*
			// Sending CURL call to write the pdf brochure on Media Server..
			// $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($brochure_url1)));
			$fileContent['FILE_CONTENT'] = base64_encode(gzcompress($this->makeCurlCall("", $brochure_url1)));
			$fileContent['BROCHURE_NAME'] = $course_brochure_name;
			// echo "<br> Content Size = ".($this->sizeofvar($fileContent['FILE_CONTENT']) / 1024) ." KB";
			
			unlink($pdf_file_path);

			// $url = "http://".MEDIA_SERVER_IP."/listing/ListingPage/writeListingPDFatMediaServer";
			$url = "http://".MEDIA_SERVER_IP."/mediadata/listingsBrochures/writePDFatMediaServer.php";
			// $url = "http://images.shiksha.com/mediadata/listingsBrochures/writePDFatMediaServer.php";
			$output = $this->makeCurlCall($fileContent, $url);
			
			if($output == 1) {
				// echo "<br> YES Got CURL response, file transfer done";
				$listingextendedmodel = $this->CI->load->model('listingextendedmodel');
				$listingextendedmodel->updateListingEBrochureInfo($this->listing_type, $this->listing_type_id, $brochure_url);
				$response_array = array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $brochure_url);				
			} else {
				// echo "<br> NOOPES! file transfer not done";
				$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "DID NOT GET CURL REQUEST's RESPONSE");
			}
			//*/
			/*
			$listingextendedmodel = $this->CI->load->model('listingextendedmodel');
			$listingextendedmodel->updateListingEBrochureInfo($this->listing_type, $this->listing_type_id, $brochure_url);
			$response_array = array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $brochure_url);
			//*/
			
			return $response_array;
		}
	}

	
	
	function genearteAbroadCourseEbrochure($courseId) {                
		$course_object = $this->abroad_course_repository->find($courseId);
		//_p($feeDetails); die;
		$institute_id = $course_object->getInstId();
		$univ_id = $course_object->getUniversityId();
		// $institute_object = $this->abroad_institute_repository->find($institute_id);
		$university_object = $this->university_repository->find($univ_id);
		
		//_p($university_object); die;
		
		$data['course'] = $course_object;
		$data['university'] = $university_object;
		$data['courseFeeData']  = $this->getCourseFeesDetails($course_object);
		$data['livingExpenseINR'] = $this->abroadListingCommonLib->convertCurrency($data['university']->getCampusAccommodation()->getLivingExpenseCurrency(), 1, $data['university']->getCampusAccommodation()->getLivingExpenses()/12);
		$data['livingExpenseINR'] = $this->abroadListingCommonLib->formatMoneyAmount(round($data['livingExpenseINR']), 1, 1);
		$data['avgSalaryINR'] = $this->abroadListingCommonLib->convertCurrency($data['course']->getJobProfile()->getAverageSalaryCurrencyId(), 1, $data['course']->getJobProfile()->getAverageSalary());
		$data['avgSalaryINR'] = $this->abroadListingCommonLib->formatMoneyAmount(round($data['avgSalaryINR']), 1, 1);
		$this->CI->config->load('studyAbroadListingConfig');
		$data['currencySymbolMapping']  = $this->CI->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
		// $photos = $university_object->getPhotos();
		// echo $university_object->getId(); _p($university_object->getLocation()); die;
		$catAndSubCat = Modules::run('listing/abroadListings/getCategoryOfAbroadCourse',$data['course']->getId());
		
		$data['otherCourseData'] = $this->abroadListingCommonLib->getSimilarCourses($data['university']->getId(), $catAndSubCat['categoryId'], $data['course'], $this->abroad_course_repository, 'regular');
		$countryGuideUrl = $this->abroadListingCommonLib->getVisaGuideDetailForCountry($data['course']->getCountryId());
		$data['countryGuideUrl'] = $countryGuideUrl['contentURL'];
		$src = '';
		if( $file = file_get_contents( SHIKSHA_HOME."/public/images/maps/country-".$data['course']->getCountryId().".gif")){
            $src = SHIKSHA_HOME."/public/images/maps/country-".$data['course']->getCountryId().".gif";
        }else{
            $src = SHIKSHA_HOME."/public/images/maps/country-default.gif";
        }
		$data['countryGuideImg'] = $src;
		$categoryPageRecommendations = $this->CI->load->library('categoryList/CategoryPageRecommendations');
		$recommendedCourses = $categoryPageRecommendations->getAbroadAlsoViewedInstitutes(intval($data['course']->getId()));
		if(count($recommendedCourses) > 0){
			$recommendedCourses = $this->abroad_course_repository->findMultiple($recommendedCourses);
			$recommendedCourses = array_slice($recommendedCourses, 0, 9, true);
			$data['recommendedCourses'] = $recommendedCourses;
		}
		else{
			$data['recommendedCourses'] = array();
		}
		
		$view_name = "";
		$html = "";			
		//_p($data);die;
		$view_name = "listing/abroad/pdfBrochure";
		$html = $this->CI->load->view($view_name,$data,true);
		
		// Generating HTML file which will be finally converted into the PDF..
		$file = $this->SHIKSHA_FOLDER."public/listingsEbrochureTemplates/abroadCourseEbrochure.html";
		$url = SHIKSHA_HOME."/public/listingsEbrochureTemplates/abroadCourseEbrochure.html";
		$this->writeInFile($file, $html);
		// abroadHeader
		// _p(file_get_contents($file));die;
		
		// Generating Header - footer files with the dynamic content..
		$header_html = SHIKSHA_HOME."/public/listingsEbrochureTemplates/abroadHeader.html";
		$header_html_data = $this->CI->load->view("listing/abroad/pdfBrochureHeader.php", $headerData, true);
		$header_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/abroadHeader.html";
		$this->writeInFile($header_file_template, $header_html_data);
		
		$footer_html = SHIKSHA_HOME."/public/listingsEbrochureTemplates/abroadFooter.html";
		$footerData['course'] = $course_object;
		$footerData['createdDate'] = date('d-M-Y', time());
		$footer_html_data = $this->CI->load->view("listing/abroad/pdfBrochureFooter.php", $footerData, true);
		$footer_file_template = $this->SHIKSHA_FOLDER."/public/listingsEbrochureTemplates/abroadFooter.html";
		$this->writeInFile($footer_file_template, $footer_html_data);
		
		// Naming the pdf Brochure here..
		$current_date = date('Y-m-d-H-i', time());
		$course_brochure_name = "Course_".$courseId."_".$current_date.".pdf";
		$pdf_file_path = $this->EBROCHURE_FOLDER.$course_brochure_name;
		
		// $this->CI->benchmark->mark('pdf_start');
		// shell_exec("/usr/local/bin/wkhtmltopdf-i386 --margin-left 0 --margin-right 0 --margin-top 17 --margin-bottom 19 --header-html $header_html --footer-html $footer_html $url $pdf_file_path");
		shell_exec("/usr/local/bin/wkhtmltopdf --margin-left 0 --margin-right 0 --margin-top 17 --margin-bottom 19 --header-html $header_html --footer-html $footer_html $url $pdf_file_path");
		// $this->CI->benchmark->mark('pdf_end');
		// echo "<br> Total PDF Generation time = ".$this->CI->benchmark->elapsed_time('pdf_start', 'pdf_end');

		unlink($file);
		unlink($header_file_template);
		unlink($footer_file_template);		
		$this->EBROCHURE_BASE_URL = "/mediadata/listingsBrochures/";
		$brochure_url = $this->EBROCHURE_BASE_URL.$course_brochure_name;
		$brochure_url1 = SHIKSHA_HOME."/mediadata/listingsBrochures/".$course_brochure_name;
		
		//*
		// Sending CURL call to write the pdf brochure on Media Server..
		// $fileContent['FILE_CONTENT'] = base64_encode(gzcompress(file_get_contents($brochure_url1)));
		$fileContent['FILE_CONTENT'] = base64_encode(gzcompress($this->makeCurlCall("", $brochure_url1)));
		$fileContent['BROCHURE_NAME'] = $course_brochure_name;
		// echo "<br> Content Size = ".($this->sizeofvar($fileContent['FILE_CONTENT']) / 1024) ." KB";
		
		unlink($pdf_file_path);

		// $url = "http://".MEDIA_SERVER_IP."/listing/ListingPage/writeListingPDFatMediaServer";
		//*
		$url = "https://".MEDIA_SERVER_IP."/mediadata/listingsBrochures/writePDFatMediaServer.php";
		// $url = "http://images.shiksha.com/mediadata/listingsBrochures/writePDFatMediaServer.php";
		$output = $this->makeCurlCall($fileContent, $url);
		if($output == 1) {
			// echo "<br> YES Got CURL response, file transfer done";
			$listingextendedmodel = $this->CI->load->model('listingextendedmodel');
			$listingextendedmodel->updateListingEBrochureInfo('course', $courseId, $brochure_url);
			$response_array = array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $brochure_url);				
		} else {
			// echo "<br> NOOPES! file transfer not done";
			$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "DID NOT GET CURL REQUEST's RESPONSE");
		}
		//*/
		/*
		$listingextendedmodel = $this->CI->load->model('listingextendedmodel');
		$listingextendedmodel->updateListingEBrochureInfo($this->listing_type, $this->listing_type_id, $brochure_url);
		$response_array = array('RESPONSE' => 'BROCHURE_FOUND' , 'BROCHURE_URL' => $brochure_url);
		//*/
		
		return $response_array;
	}	
	
	
	public function getCourseFeesDetails($course)
	{
		// get the fees of the course in indian currency
		$feesCurrency = $course->getFees()->getCurrency();
		$feesVal = $course->getFees()->getValue();
		$courseFees = $this->abroadListingCommonLib->convertCurrency($feesCurrency, 1, $feesVal);
		$displayData['courseFeesIndianAmount'] = $courseFees;
		
		$displayData["courseFees"] 		= $course->getFees()->getValue();
		$displayData["courseCurrency"] 		= $course->getFees()->getCurrency();
	
		$courseFeeData["fromFormattedFees"] 	= $this->abroadListingCommonLib->formatMoneyAmount($displayData["courseFees"], $displayData["courseCurrency"]);
		$courseFeeData["fromCurrency"] 		= $displayData["courseCurrency"];
		$courseFeeData["fromCurrenyObj"] 	= $course->getFees()->getCurrencyEntity();
		
		$courseIndianFees 			= $displayData['courseFeesIndianAmount'];
		$courseFeeData["toFormattedFees"] 	= $this->abroadListingCommonLib->formatMoneyAmount(round($courseIndianFees), 1, 1);		
		$courseFeeData["toFormattedCurrency"] 	= 1;
		return $courseFeeData;
	}
	
	function sizeofvar($var) {
	    $start_memory = memory_get_usage();
	    $tmp = unserialize(serialize($var));
	    return memory_get_usage() - $start_memory;
	}
	
	function writeInFile($file, $content) {
		if($file == "") {
			return ;
		}
		
		$fp = fopen($file, 'w');
		fwrite($fp, $content);
		fclose($fp);
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
		curl_setopt($c, CURLOPT_POSTFIELDS,$post_array);
		curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
		curl_setopt($c, CURLOPT_TIMEOUT, 60);
                curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
                $output =  curl_exec($c);
		curl_close($c);
		return $output;
	}
		
	public function checkInstituteEligibilityForBrochureGeneration($institute_object) {
		/* 
		 * 1. Check if Instiute has Why Join data..
		 */
		
		if(trim($institute_object->getJoinReason()->getDetails()) == "" || trim($institute_object->getJoinReason()->getDetails()) == "&nbsp;") {
			$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "INSTITUTE WHY JOIN DETAILS NOT AVAILABLE FOR INSTITUTE ID -".$institute_object->getId());			
			return $response_array;
		}
		
		
		/*
		 * 2. Check if Instiute has its Description available..
		 */		
		
		$flag = 0;
		if(is_array($institute_object->getDescriptionAttributes())) {
			
			foreach ($institute_object->getDescriptionAttributes() as $key => $attributeObj) {
				// echo "<hr>"; echo $attributeObj->getId()." -- ".$attributeObj->getName().", cmp val = ".strcmp($attributeObj->getName(), "Institute Description");
				if(strcmp($attributeObj->getName(), "Institute Description") == 0) {
					$flag = 1;
					break;
				}
			}
			
			if($flag == 0) {
				$response_array = array('RESPONSE' => 'NO_DATA_FOUND' , 'ERROR' => "INSTITUTE DESCRIPTION NOT AVAILABLE FOR INSTITUTE ID -".$institute_object->getId());
				return $response_array;
			}
			
		}
		
		$response_array = array('RESPONSE' => 'ALL_WELL');
		return $response_array;
	}	
	
	function urlExists($fileUrl) {		
		$AgetHeaders = @get_headers($fileUrl);
		if (preg_match("|200|", $AgetHeaders[0])) {	// file exists
			return true;
		} else {					// file doesn't exists
			return false;
		}
	}

	/*
	* Filter Multilocation courses from list of courses
	* @params : courses => Array of courseIds
	*/
	public function getMultilocationsForInstitute($courses){
		ini_set("memory_limit", "300M");
		$multilocationCourse  = array();

		if( empty($courses) ){
			return $multilocationCourse;
		}

		$this->CI->load->model('listing/institutemodel');
		$institutemodel 	= new institutemodel;
		$filteredCourses = $institutemodel->filterMultilocationCourses($courses);
		
		foreach($filteredCourses as $rows=>$val){
			$multilocationCourse[] = $val['course_id'];
		}

		return $multilocationCourse;

	} 	
}
