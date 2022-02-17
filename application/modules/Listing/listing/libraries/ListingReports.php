<?php

class ListingReports {

	private $CI;

	function __construct() {
		$this->CI =& get_instance();
	}
	
	/**
	* Purpose       : Method to generate reponse report for the provided period
	* Params        : 1. period containing start and end date
	* To Do         : none
	* Author        : Romil Goel
	*/
	function generateReponseReport( $period )
	{
		// load the files
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder 		= new ListingBuilder;
		$this->courseRepository 	= $listingBuilder->getCourseRepository();
		$this->listingDetailsScript 	= $this->CI->load->model('listing/listingmodel');
		$this->national_course_lib 	= $this->CI->load->library('listing/NationalCourseLib');
		
		// get the reponse data for the provided period
		error_log("RESPONSE_RPT : Fetching response data");
		$reponseData = $this->listingDetailsScript->getReponseDataForPeriod($period);
		error_log("RESPONSE_RPT : Fetched response data");
		
		// collect the unique course-ids
		$courseIds = array();
		foreach($reponseData as $reponseRow)
		{
			$courseIds[] = $reponseRow["listing_type_id"];	
		}
		$courseIds = array_unique($courseIds);
		error_log("RESPONSE_RPT : Fetched Course Details = ".count($courseIds));

		// get the subcategories data for the courses
		$subCatDetails = $this->_getSubcatDataOfCourses($courseIds);
		$subCategoryNames = $subCatDetails["subCatNames"];
		error_log("RESPONSE_RPT : Fetched Course SUbcategories = ".count($subCatDetails["data"]));
		
		// get the head office location data
		$headOfficesArr = $this->_getHeadOfficeLocationOfCourses($courseIds);
		error_log("RESPONSE_RPT : Fetched Head Office details = ".count($headOfficesArr));
		
		// get the course name, institute name, client-id of the courses
		$courseDetailsArr = $this->_getCourseNameAndInstituteNameOfCourses($courseIds);
		error_log("RESPONSE_RPT : Fetched course details details = ".count($courseDetailsArr));

		// initialize PHP excel
		$this->CI->load->library('common/PHPExcel');
		$objPHPExcel 	= new PHPExcel();
		$rowCount 	= 1;
		$column 	= 'A';
		
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcelActiveSheet = $objPHPExcel->getActiveSheet();
		$objPHPExcelActiveSheet->setTitle("Response Report");
		
		// header array having heading text and column width
		$headerArray = array(array("Client Id"			,10),
				     array("Course Id"			,10),
				     array("Institute Name"		,30),
				     array("Course Name"		,30),
				     array("Course Status"		,14),
				     array("Subcategory"		,16),
				     array("City"			,12),
				     array("No. of responses generated"	,25),	
				     array("Response Action"		,25),
				     array("Course Type"		,14));
		
		// prepare the column headers of the excel file
		for ($i = 0; $i < count($headerArray); $i++) {
			$objPHPExcelActiveSheet->setCellValue($column.$rowCount, $headerArray[$i][0]);
			$objPHPExcelActiveSheet->getColumnDimension($column)->setWidth($headerArray[$i][1]);
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'EEEEEE') )));
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->getFont()->setBold(true);
			$objPHPExcelActiveSheet->getStyle($column.$rowCount)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			$objPHPExcelActiveSheet->getRowDimension($rowCount)->setRowHeight(20);
			$column++;
		}
		error_log("RESPONSE_RPT : Excel Header created");

		// prepare the data for the excel
		$fromArray = array();
		foreach ($reponseData as $key=>$reponseRow)
		{
			$courseId 	= $reponseRow["listing_type_id"];

			// skip the below code if course-id data is not found
			if(empty($courseDetailsArr[$courseId]))
				continue;

			$rowCount++;
			$clientId  	= $courseDetailsArr[$courseId]["username"];
			$courseCity 	= $headOfficesArr[$courseId];
			$instituteName 	= $courseDetailsArr[$courseId]["institute_name"];
			$courseName 	= $courseDetailsArr[$courseId]["course_name"];
			$courseType 	= empty($courseDetailsArr[$courseId]["institute_type"]) ? "--" : in_array($courseDetailsArr[$courseId]["institute_type"], array("Department","Department_Virtual")) ? "Abroad" : "National";

			// determine the dominant subcategory of the course
			$dominantSubCat = $subCatDetails["data"][$courseId]["subCatIds"][0];
			if($subCatDetails["data"][$courseId]["subcatCount"] != 1 && $coursesDetails[$reponseRow["listing_type_id"]])
			{
				$dominantSubCat = $this->national_course_lib->getDominantSubCategoryForCourse($courseId, $subCatDetails["data"][$courseId]["subCatIds"]);
				$dominantSubCat = $dominantSubCat["dominant"];
			}
			$subCategory 	= $subCategoryNames[$dominantSubCat];

			// prepare the data for the excel
			$fromArray[] = array($clientId,
					     $courseId,
					     $instituteName,
					     $courseName,
					     $reponseRow["listing_subscription_type"],
					     $subCategory,
					     $courseCity,
					     $reponseRow["response_count"],
					     $reponseRow["action"],
					     $courseType);

			// for log purpose only
			if($key%500 == 0)
				error_log("RESPONSE_RPT : Creating row ".$key);
		}
		
		// print the data array in the excel sheet
		$objPHPExcelActiveSheet->fromArray($fromArray, null, 'A2');
		error_log("RESPONSE_RPT : Data Rows inserted in excel ");
		$column = chr(ord($column)-1);;
		error_log("RESPONSE_RPT : Total Results  ".count($reponseData)."   "."A1".":".($column).$rowCount);
		$objPHPExcel->getActiveSheet()->getStyle("A1".":".($column).$rowCount)->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));

		// save the excel files
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		error_log("RESPONSE_RPT : PHPExcel_Writer_Excel2007 object created");
		$reportName 	= "Response_Report_".date("dMY",strtotime($period["start"]))."_to_".date("dMY",strtotime($period["end"])).".xlsx";
		$path 		= "mediadata/reports/";
		$objWriter->save($path.$reportName);
		error_log("RESPONSE_RPT : ".$reportName." Excel file saved");
		
		// send mail to the stakeholders
		$this->CI->load->library('alerts_client');
		$alertClient = new Alerts_client();
		//$reportLink 	= SHIKSHA_HOME."/".$path.$reportName;
		$reportLink 	= SHIKSHA_HOME."/ListingScripts/downloadReport/".$reportName;
		$subject 	= "Response report from ".date("d/m/Y",strtotime($period["start"]))." to ".date("d/m/Y",strtotime($period["end"]));
		$content	= "<p>Hi,</p>
				   <p>You can access the response report on the following <a href='".$reportLink."'>link</a></p>
				   <br/>
				   <p>Regards,<br/>Shiksha.com</p>";
		
		$emailIdarray = array('soumendu.g@naukri.com','saurabh.gupta@shiksha.com','anurag.jain@shiksha.com','maneesh@naukri.com','romil.goel@shiksha.com','pankaj.meena@shiksha.com');
		foreach($emailIdarray as $key=>$emailId)
		{
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '');
		}
		error_log("RESPONSE_RPT : Mails sent ");
	}

	/**
	* Purpose       : Method to get the formatted array of the subcategories of the courses provided
	* Params        : course-ids(array)
	* To Do         : none
	* Author        : Romil Goel
	*/
	private function _getSubcatDataOfCourses($courseIds)
	{
		if(empty($courseIds))
			return array();

		$courseSubCatArr = array();
		$subCategoriesNames = array();
		$subCatData = $this->listingDetailsScript->getSubCatOfCourses($courseIds);
		foreach($subCatData as $subCatRow)
		{
			$courseSubCatArr[$subCatRow["listing_type_id"]]["subcatCount"]++;
			$courseSubCatArr[$subCatRow["listing_type_id"]]["subCatIds"][] = $subCatRow["subcategoryid"];
			
			$subCategoriesNames[$subCatRow["subcategoryid"]] = $subCatRow["name"];
		}

		$finalCourseSubCatArr = array("data" 		=> $courseSubCatArr,
					      "subCatNames" 	=> $subCategoriesNames);

		return $finalCourseSubCatArr;
	}
	
	/**
	* Purpose       : Method to get the head office names of the course-ids provided
	* Params        : 1. period containing start and end date
	* To Do         : none
	* Author        : Romil Goel
	*/
	private function _getHeadOfficeLocationOfCourses($courseIds)
	{
		if(empty($courseIds))
			return array();

		$headOfficesArr = array();

		$headOffices = $this->listingDetailsScript->getHeadOfficeLocationOfCourses($courseIds);

		foreach($headOffices as $headOfficeRow)
		{
			$headOfficesArr[$headOfficeRow["course_id"]] = $headOfficeRow["city_name"];
		}

		return $headOfficesArr;
	}
	
	/**
	* Purpose       : Method to get the course name, institute name, client-id of the courses
	* Params        : course-ids(array)
	* To Do         : none
	* Author        : Romil Goel
	*/
	private function _getCourseNameAndInstituteNameOfCourses($courseIds)
	{
		if(empty($courseIds))
			return array();

		$detailsArr = array();

		$details = $this->listingDetailsScript->getCourseNameAndInstituteNameOfCourses($courseIds);

		foreach($details as $detailsRow)
		{
			$detailsArr[$detailsRow["course_id"]] = array("course_name" => $detailsRow["courseTitle"],
								      "institute_name" => $detailsRow["institute_name"],
								      "institute_type" => $detailsRow["institute_type"],
								      "username" => $detailsRow["username"]);
		}

		return $detailsArr;
	}
	
	/* @Purpose : Generate weekly Report for inaccurate reported contact no.
	*/
	
	function generateReportForInaccurateReportedContacts() {
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder 			= new ListingBuilder;
		$this->listingmodel = $this->CI->load->model('listing/listingmodel', '', TRUE);
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
	
	
		$reportedContactList = array();
		/** Course Listing Reported Contacts : START **/
		$courseListingdata = $this->listingmodel->getInaccurateContactNoReportedListings('course',7);
		if(!empty($courseListingdata['subCatIds']))
		{
			$categoryObjs = $categoryRepository->findMultiple($courseListingdata['subCatIds']);
			
		}
	
		if($courseListingdata['listingIds'])
		{
			$courseRepo = $listingBuilder->getCourseRepository();
			$courseObjs = $courseRepo->findMultiple($courseListingdata['listingIds']);
			$instituteIdsArray = array();
			foreach($courseObjs as $courseObj){
				if($courseObj->getInstId() > 0)
				{
					$instituteIdsArray [] = $courseObj->getInstId();
				} 
			}
			
			if(!empty($instituteIdsArray)) {
			$instituteRepo = $listingBuilder->getInstituteRepository();
			$instituteObjsForCourseReport = $instituteRepo->findMultiple($instituteIdsArray);
			}
			
			foreach($courseListingdata['listingIds'] as $rowId => $courseId) {
				$reportData = array();
				if(isset($courseObjs[$courseId])) {
					$reportData['Institute Id'] = $courseObjs[$courseId]->getInstId();
					if(isset($instituteObjsForCourseReport[$courseObjs[$courseId]->getInstId()]))
					{
					$reportData['Institute Name'] = $instituteObjsForCourseReport[$courseObjs[$courseId]->getInstId()]->getName();
					} else {
						$reportData['Institute Name'] = ' ';
					}
					$reportData['Course Id'] = $courseId;
					$reportData['Course Name'] = $courseObjs[$courseId]->getName();
	
				} else {
					$reportData['Institute Id'] = ' ';
					$reportData['Institute Name'] = ' ';
					$reportData['Course Id'] = $courseId;
					$reportData['Course Name'] = ' ';
				}
					
				if(isset($courseListingdata['subCatIdsIndexedWithCourseId'][$courseId])) {
					$courseSubCatIds = explode(',',$courseListingdata['subCatIdsIndexedWithCourseId'][$courseId]['subcatId']);
	
					foreach($courseSubCatIds as $subCatId)
					{
						if(isset($categoryObjs[$subCatId])) {
							$reportData['Subcategory'] = empty($reportData['Subcategory']) ? $categoryObjs[$subCatId]->getName() : $reportData['Subcategory'].", ".$categoryObjs[$subCatId]->getName();
						}
					}
						
				}
					
				$reportData['Subcategory'] = empty($reportData['Subcategory']) ? "NA" : $reportData['Subcategory'];
					
				$reportData['No.s reported incorrect'] = $courseListingdata['contactReported'][$rowId]['reported_number'];
				$reportData['TimeStamp'] = $courseListingdata['contactReported'][$rowId]['report_time'];
				$reportedContactList[] = $reportData;
			}
		}
		/** Course Listing Reported Contacts : END **/
	
		/** Institute Listing Reported Contacts : START **/
		$instituteListingdata = $this->listingmodel->getInaccurateContactNoReportedListings('institute',7);
	
		if($instituteListingdata['listingIds'])
		{
			$instituteRepo = $listingBuilder->getInstituteRepository();
			$instituteObjs = $instituteRepo->findMultiple($instituteListingdata['listingIds']);
			foreach($instituteListingdata['listingIds'] as $rowId => $instituteId) {
				$reportData = array();
				if(isset($instituteObjs[$instituteId])) {
					$reportData['Institute Id'] =   $instituteId;
					$reportData['Institute Name'] = $instituteObjs[$instituteId]->getName();
						
				} else {
					$reportData['Institute Id'] = $instituteId;
					$reportData['Institute Name'] = ' ';
				}
				$reportData['Course Id'] = ' NA ';
				$reportData['Course Name'] = ' NA ' ;
				$reportData['Subcategory'] = ' NA ';
				$reportData['No.s reported incorrect'] = $instituteListingdata['contactReported'][$rowId]['reported_number'];
				$reportData['TimeStamp'] = $instituteListingdata['contactReported'][$rowId]['report_time'];
				$reportedContactList[] = $reportData;
			}
				
		}
	
		/** Institute Listing Reported Contacts : END **/
	
	     /** Prepare header for exporting into excel : START***/	
		$headerArray = array(array("Institute Name"        ,80),
				array("Course Name"        ,80),
				array("Subcategory"        ,60),
				array("Institute Id"        ,14),
				array("Course Id"            ,12),
				array("No.s reported incorrect"    ,29),
				array("TimeStamp"        ,25));
		/** Prepare header for exporting into excel : END***/
		
		/*** Define name and path of file to export as excel: START ***/
		
		$dto = new DateTime();
		$week_start = $dto->modify('-7 days')->format('d-m-Y');
		$dto = new DateTime();
		$week_end = $dto->modify('-1 days')->format('d-m-Y');
		$fileName = "INACCURATE_REPORTED_CONTACTS_FROM_".$week_start."_TO_".$week_end.".xlsx";
		$fileURL = "mediadata/reports/".$fileName;
		/*** Define name and path of file to export as excel: END *****/
		
		//Export as excel.
		$this->exportAsExcel($headerArray, $reportedContactList,$fileURL);
		
		//$reportLink = SHIKSHA_HOME."/".$fileURL;
		$reportLink = SHIKSHA_HOME."/ListingScripts/downloadReport/".$fileName;
		
       /*** Prepare data for sending mail : START ****/
		$params['reportLink'] = $fileURL;
		$params['extension']  = 'doc';
		$params['attachmentName'] = $fileName;
		$params['subject'] = "Report of 'Inaccurate reported contacts' from ".date("d/m/Y",strtotime($week_start))." to ".date("d/m/Y",strtotime($week_end))."";
		$params['content'] = "<p>Hi,</p>
				 <p>You can access the 'Inaccurate reported contacts' report on the following <a href='".$reportLink."'>link</a></p>
				 <br/>
				 <p>Regards,<br/>Shiksha.com</p>";	
		$params['recipientEmailIds'] = array('anupama.m@shiksha.com', 'amritesh.parmar@shiksha.com', 'vinay.airan@shiksha.com', 'pankaj.meena@shiksha.com');
		/*** Prepare data for sending mail : END ****/
		//Send mail
		$this->sendMailWithAttachment($params,false);
		echo "DONE";
		return $fileURL;
	}
	
	
	/**
	 *  @purpose : export as excel. 
	 *  @param  $headerArray  
	 *  @param  $excelData
	 *  @param  $fileURL 
	 */
	
	function exportAsExcel($headerArray,$excelData,$fileURL) {
		$this->CI->load->library('common/PHPExcel');
		$objPHPExcel     = new PHPExcel();
		$rowCount     = 1;
		$column     = 'A';
		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex(0);
	
	
		for ($i = 0; $i < count($headerArray); $i++) {
			$objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount,
					$headerArray[$i][0]);
			$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setWidth($headerArray[$i][1]);
			$objPHPExcel->getActiveSheet()->getStyle($column.$rowCount)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle($column.$rowCount)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);;
			$objPHPExcel->getActiveSheet()->getStyle($column.$rowCount)->getFill()->getStartColor()->setRGB('FFFF00');
			$column++;
		}
		$rowCount++;
	
		foreach($excelData as $rowData) {
			$column     = 'A';
			for ($i = 0; $i < count($headerArray); $i++) {
	
				$objPHPExcel->getActiveSheet()->setCellValue(($column++).$rowCount,$rowData[$headerArray[$i][0]]);
			}
			$rowCount++;
		}
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
		$objWriter->save($fileURL);
	}
	
	/***
	 *  @Purpose : Send email with attachment if sendAttachment param is set to true.
	 *  @Param  : 1. $params : array of params required for sending mail.  2. $sendAttachment : flag to indicate if attachment need to send with mail or not. 
	 *   
	 */
	
	private function sendMailWithAttachment($params,$sendAttachment = false) {
		$alerts_client = $this->CI->load->library('alerts_client');
		$misObj = $this->CI->load->library('Ldbmis_client');
		$appId = 1;
		/**ATTACH ATTACHMENT : START****/
		if($sendAttachment) {
			if(empty($params['reportLink']) || empty( $params['extension']) || empty($params['attachmentName'])) {
				return 0;
			}
		$reportURL = $params['reportLink'];
		$filecontent = base64_encode(file_get_contents($reportURL));
		$fileExtension = $params['extension'];
		$type_id = $misObj->updateAttachment($appId);
		$attachmentName = $params['attachmentName'];
		$attachmentId = $alerts_client->createAttachment("12",$type_id,'COURSE','AbuseReport','',$attachmentName,$fileExtension,'true', $reportURL);
		}
		/**ATTACH ATTACHMENT : END****/
		
		/**SEND EMAIL :START***/
		$subject = $params['subject'];
		$content = $params['content'];
		$emails   = $params['recipientEmailIds'];
		if($sendAttachment)
		{
		 foreach($emails as $email) {	
			$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html",'','y',array($attachement_id));
			}
		} else {
			foreach($emails as $email) {
			$response=$alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email,$subject,$content,"html","0000-00-00 00:00:00");
			}	
		}
		/**SEND EMAIL :ENDS***/
		 
		return $response;
	}
}
