<?php 
class ListingDetailsScript extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct($dbHandle = 'Listing') {
		parent::__construct($dbHandle);
        $this->dbHandle = $this->getReadHandle();
    }
    
    public function getAbroadListingDetails() {
        error_log("Getting listing details for abroad.....\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        error_log("Total number of queries: 4\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        
        //Get university details
        error_log("Query 1: University basic details, executing.....\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        $time_start = microtime_float();
        
        $universityQuery = "
            SELECT
                lm.listing_type_id                       as 'university_id',
                lm.listing_title                         as 'university_name',
                ult.country_id                           as 'country_id',
                ct.name                                  as 'country_name',
                ult.city_id                              as 'city_id',
                cct.city_name                            as 'city_name',
                lm.viewCount                             as 'view_count_total',
                SUM(alvcd.viewCount)                     as 'view_count_45_days',
                u.brochure_link                          as 'brochure_uploaded',
                lel.link                                 as 'website',
                lm.last_modify_date                      as 'last_modified_on',
                t.displayname                            as 'last_modified_by'
            FROM
                           listings_main                 as lm
                inner join university_location_table     as ult   on ult.university_id            = lm.listing_type_id and ult.status = 'live'
                inner join countryTable                  as ct    on ct.countryId                 = ult.country_id
                inner join countryCityTable              as cct   on cct.city_id                  = ult.city_id
                inner join university                    as u     on u.university_id              = lm.listing_type_id and u.status = 'live'
                left join  listing_external_links        as lel   on lel.listing_type_id          = lm.listing_type_id and lel.listing_type = 'university' and lel.link_type = 'website_link' and lel.status = 'live'
                left join  abroadListingViewCountDetails as alvcd on alvcd.listingId              = lm.listing_type_id and alvcd.listingType = 'university' and alvcd.viewDate > DATE_SUB(now(),INTERVAL 45 DAY)
                left join  tuser                         as t     on t.userid                     = lm.editedBy
            WHERE
                lm.listing_type = 'university' AND
                lm.status = 'live'
            GROUP BY
                university_id
        ";
        $universityQueryResult = $this->dbHandle->query($universityQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query 1 executed. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        
        $finalUniversityListingArray = array();
        
        foreach($universityQueryResult->result() as $row) {
            $finalUniversityListing['university_id']             = $row->university_id;
            $finalUniversityListing['university_name']          = $row->university_name;
            $finalUniversityListing['department_id']             = "";
            $finalUniversityListing['department_name']           = "";
            $finalUniversityListing['course_id']                 = "";
            $finalUniversityListing['course_name']               = "";
            $finalUniversityListing['country_id']               = $row->country_id;
            $finalUniversityListing['country_name']             = $row->country_name;
            $finalUniversityListing['city_id']                  = $row->city_id;
            $finalUniversityListing['city_name']                = $row->city_name;
            $finalUniversityListing['subcategory_id']              = "";
            $finalUniversityListing['subcategory_name']            = "";
            $finalUniversityListing['view_count_total']         = ($row->view_count_total)?$row->view_count_total:"NA";
            $finalUniversityListing['view_count_45_days']       = ($row->view_count_45_days)?$row->view_count_45_days:"NA";
            $finalUniversityListing['brochure_uploaded']        = ($row->brochure_uploaded)?$row->brochure_uploaded:"NA";
            $finalUniversityListing['website']                  = $row->website;
            $finalUniversityListing['course_fee']               = "";
            $finalUniversityListing['exam_name']                = "";
            $finalUniversityListing['exam_score']               = "";
            $finalUniversityListing['last_modified_on']         = ($row->last_modified_on)?$row->last_modified_on:"NA";
            $finalUniversityListing['last_modified_by']         = ($row->last_modified_by)?$row->last_modified_by:"NA";
            
            $finalUniversityListingArray[]                      = $finalUniversityListing;
            
            $universityIdNameMapping[$row->university_id]       = $row->university_name;
            $universityIdCityIdMapping[$row->university_id]     = $row->city_id;
            $universityIdCityNameMapping[$row->university_id]   = $row->city_name;
            $universityIdCountryIdMapping[$row->university_id]  = $row->country_id;
            $universityIdCountryNameMapping[$row->university_id] = $row->country_name;
        }
        
        //Get department details
        error_log("Query 2: Department basic details, executing.....\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        $time_start = microtime_float();
        
        $departmentQuery = "
            SELECT
                lm.listing_type_id                       as 'department_id',
                lm.listing_title                         as 'department_name',
                ium.university_id                        as 'university_id',
				i.institute_type						 as 'department_type',
                lm.viewCount                             as 'view_count_total',
                SUM(alvcd.viewCount)                     as 'view_count_45_days',
                i.institute_request_brochure_link        as 'brochure_uploaded',
                lm.last_modify_date                      as 'last_modified_on',
                t.displayname                            as 'last_modified_by'
            FROM
                           listings_main                 as lm
                inner join institute_university_mapping  as ium   on ium.institute_id             = lm.listing_type_id and ium.status = 'live'
                inner join institute                     as i     on i.institute_id               = lm.listing_type_id and i.institute_type IN ('department','Department','Department_Virtual') and i.status = 'live'
                left join  abroadListingViewCountDetails as alvcd on alvcd.listingId              = lm.listing_type_id and alvcd.listingType = 'department' and alvcd.viewDate > DATE_SUB(now(),INTERVAL 45 DAY)
                left join  tuser                         as t     on t.userid                     = lm.editedBy
            WHERE
                lm.listing_type = 'institute' AND
                lm.status = 'live'
            GROUP BY
                department_id
        ";
        
        $departmentQueryResult = $this->dbHandle->query($departmentQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query 2 executed. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        
        $finalDepartmentListingArray = array();
        
        foreach($departmentQueryResult->result() as $row) {
			if($row->department_type != 'Department_Virtual') {
				$finalDepartmentListing['university_id']                 = $row->university_id;
				$finalDepartmentListing['university_name']              = $universityIdNameMapping[$row->university_id];
				$finalDepartmentListing['department_id']                 = $row->department_id;
				$finalDepartmentListing['department_name']               = $row->department_name;
				$finalDepartmentListing['course_id']                     = "";
				$finalDepartmentListing['course_name']                   = "";
				$finalDepartmentListing['country_id']                   = $universityIdCountryIdMapping[$row->university_id];
				$finalDepartmentListing['country_name']                 = $universityIdCountryNameMapping[$row->university_id];
				$finalDepartmentListing['city_id']                      = $universityIdCityIdMapping[$row->university_id];
				$finalDepartmentListing['city_name']                    = $universityIdCityNameMapping[$row->university_id];
				$finalDepartmentListing['subcategory_id']                  = "";
				$finalDepartmentListing['subcategory_name']                = "";
				$finalDepartmentListing['view_count_total']             = ($row->view_count_total)?$row->view_count_total:"NA";
				$finalDepartmentListing['view_count_45_days']           = ($row->view_count_45_days)?$row->view_count_45_days:"NA";
				$finalDepartmentListing['brochure_uploaded']            = ($row->brochure_uploaded)?$row->brochure_uploaded:"NA";
				$finalDepartmentListing['website']                      = "";
				$finalDepartmentListing['course_fee']                   = "";
				$finalDepartmentListing['exam_name']                    = "";
				$finalDepartmentListing['exam_score']                   = "";
				$finalDepartmentListing['last_modified_on']             = ($row->last_modified_on)?$row->last_modified_on:"NA";
				$finalDepartmentListing['last_modified_by']             = ($row->last_modified_by)?$row->last_modified_by:"NA";
				
				$finalDepartmentListingArray[]                          = $finalDepartmentListing;
			}
            $departmentIdUniversityIdMapping[$row->department_id]   = $row->university_id;
            $departmentIdDepartmentNameMapping[$row->department_id] = $row->department_name;
        }
        //Get course details
        error_log("Query 3: Course basic details, executing.....\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        $time_start = microtime_float();
        
        $courseQuery = "
            SELECT
                lm.listing_type_id                          as 'course_id',
                lm.listing_title                            as 'course_name',
                cd.institute_id                             as 'department_id',
                lct.category_id                             as 'subcategory_id',
                cbt.name                                    as 'subcategory_name',
                cbt.parentId                                as 'category_id',
                lm.viewCount                                as 'view_count_total',
                cd.fees_value                               as 'fees_value',
                c.currency_code                             as 'fees_unit',
                alemt.exam                                  as 'exam_name',
                lea.examName                                as 'custom_exam_name',
                lea.cutoff                                  as 'cut_off',
                alemt.type                                  as 'cut_off_unit',
                cd.course_request_brochure_link             as 'brochure_uploaded',
                lm.last_modify_date                         as 'last_modified_on',
                t.displayname                               as 'last_modified_by'
            FROM
                           listings_main                    as lm
                inner join course_details                   as cd     on cd.course_id               = lm.listing_type_id and cd.status = 'live'
                inner join institute_university_mapping     as ium    on ium.institute_id           = cd.institute_id and ium.status = 'live'
                inner join listing_category_table           as lct    on lct.listing_type_id        = cd.course_id and lct.listing_type = 'course' and lct.status = 'live'
                inner join categoryBoardTable               as cbt    on cbt.boardId                = lct.category_id
                left join  listingExamAbroad                as lea    on lea.listing_type_id        = lm.listing_type_id AND lea.listing_type = 'course' AND lea.status = 'live'
                left join  abroadListingsExamsMasterTable   as alemt  on alemt.examId               = lea.examId AND alemt.status = 'live'
                left join  abroadListingViewCountDetails    as alvcd  on alvcd.listingId            = lm.listing_type_id and alvcd.listingType = 'course' and alvcd.viewDate > DATE_SUB(now(),INTERVAL 45 DAY)
                left join  currency                         as c      on c.id                       = cd.fees_unit
                left join  tuser                            as t      on t.userid                   = lm.editedBy
            WHERE
                lm.listing_type = 'course' AND
                lm.status = 'live'
            GROUP BY
                course_id, exam_name, custom_exam_name
            ORDER BY
                course_id
        ";
        $courseQueryResult = $this->dbHandle->query($courseQuery);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query 3 executed. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        
        
        //get 45 days view count of each course
        error_log("Query 4: Course 45 days view count, executing.....\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        $time_start = microtime_float();
        
        $courseQueryViewCount = "
            SELECT
                lm.listing_type_id              as 'course_id',
                SUM(alvcd.viewCount)            as 'view_count_45_days'
            FROM
                            listings_main                   as lm
                inner join  abroadListingViewCountDetails   as alvcd on alvcd.listingId = lm.listing_type_id and alvcd.listingType = 'course' and alvcd.viewDate > DATE_SUB(now(),INTERVAL 45 DAY)
            WHERE
                lm.listing_type = 'course' AND
                lm.status = 'live'
			GROUP BY
				course_id
        ";
        
        $courseQueryViewCountResult = $this->dbHandle->query($courseQueryViewCount);
        
        $time_end = microtime_float();
        $time = $time_end - $time_start;
        error_log("Query 4 executed. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/log_abroad_listing_details_".date('y-m-d'));
        
        foreach($courseQueryViewCountResult->result() as $row) {
            $viewCount45DaysMap[$row->course_id] = $row->view_count_45_days;
        }
        
        $finalCourseListingArray = array();
        foreach($courseQueryResult->result() as $row) {
            $courseIdsArray[]                               = $row->course_id;
            $finalCourseListing['university_id']            = $departmentIdUniversityIdMapping[$row->department_id];
            $finalCourseListing['university_name']          = $universityIdNameMapping[$finalCourseListing['university_id']];
            $finalCourseListing['department_id']            = $row->department_id;
            $finalCourseListing['department_name']          = $departmentIdDepartmentNameMapping[$row->department_id];
            $finalCourseListing['course_id']                = $row->course_id;
            $finalCourseListing['course_name']              = $row->course_name;
            $finalCourseListing['country_id']               = $universityIdCountryIdMapping[$finalCourseListing['university_id']];
            $finalCourseListing['country_name']             = $universityIdCountryNameMapping[$finalCourseListing['university_id']];
            $finalCourseListing['city_id']                  = $universityIdCityIdMapping[$finalCourseListing['university_id']];
            $finalCourseListing['city_name']                = $universityIdCityNameMapping[$finalCourseListing['university_id']];
            $finalCourseListing['subcategory_id']              = $row->subcategory_id;
            $finalCourseListing['subcategory_name']            = $row->subcategory_name;
            $finalCourseListing['view_count_total']         = ($row->view_count_total)?$row->view_count_total:"NA";
            $finalCourseListing['view_count_45_days']       = ($viewCount45DaysMap[$row->course_id])?$viewCount45DaysMap[$row->course_id]:"NA";
            $finalCourseListing['brochure_uploaded']        = ($row->brochure_uploaded)?$row->brochure_uploaded:"NA";
            $finalCourseListing['website']                  = "";
            $finalCourseListing['course_fee']               = ($row->fees_value && $row->fees_unit)?(($row->fees_value)." ".($row->fees_unit)):"NA";
            
            $courseExamName                                 = ($row->custom_exam_name)?($row->custom_exam_name):$row->exam_name;
            $courseExamNameArray[$row->course_id][]         = $courseExamName?$courseExamName:"NA";
            $cutOffValWithUnit[$row->course_id][]           = ($row->cut_off)?(($row->cut_off)." ".($row->cut_off_unit)):"NA";
            $finalCourseListing['exam_name']                = implode(" | ", $courseExamNameArray[$row->course_id]);
            $finalCourseListing['exam_score']               = implode(" | ", $cutOffValWithUnit[$row->course_id]);
            
            $finalCourseListing['last_modified_on']         = ($row->last_modified_on)?$row->last_modified_on:"NA";
            $finalCourseListing['last_modified_by']         = ($row->last_modified_by)?$row->last_modified_by:"NA";
            
            $totalCourseListingArray[$row->course_id][]       = $finalCourseListing;
        }
        foreach($totalCourseListingArray as $courseId => $courseListingArray){
            $finalCourseListingArray[] = end($courseListingArray);
        }
        
        $finalAbroadListing = array();
        $documentUrlArray   = array();
        $finalAbroadListing = array_merge($finalUniversityListingArray, $finalDepartmentListingArray, $finalCourseListingArray);
        
        $headerArray        = array('University Id', 'University Name', 'Department Id', 'Department Name', 'Course Id', 'Course Name',
                                    'Country Id', 'Country Name', 'City Id', 'City Name', 'Subcategory Id', 'Subcategory Name', 'View Count Total',
                                    'View Count 45 Days', 'Brochure Uploaded', 'Website', 'Entire Course Fee', 'Exam Name', 'Exam Score',
                                    'Last Modified On', 'Last Modified By');
        
        $documentUrlArray = $this->exportDataToExcel($finalAbroadListing, $headerArray, 'Abroad');
        $this->sendMailWithAttachmentLink($documentUrlArray, 'Abroad');
    }
    
    public function exportDataToExcel($dataList, $headerArray, $scope) {
		if($scope == 'Abroad') {
			$logFileName = 'log_abroad_listing_details_'.date('y-m-d');
		} else {
			$logFileName = 'log_national_listing_details_'.date('y-m-d');
		}
		
        $insertDataInChunks = false;
        $finalDataList[0] = $dataList;
        
        if(count($dataList) > 35000) {
			$insertDataInChunks = true;
            $finalDataList[0] = array_slice($dataList, 0, 35000);
            $finalDataList[1] = array_slice($dataList, 35000);
        }
        
        $this->load->library('common/PHPExcel');
		//$objPHPExcel = new PHPExcel();
		
		$documentURLArray = array();
        
        foreach($finalDataList as $key=>$list) {
			error_log("Preparing excel ".($key+1).".....\n", 3, "/tmp/".$logFileName);
            $time_start = microtime_float();
            
            error_log("Inserting ".count($list)." rows.....\n", 3, "/tmp/".$logFileName);
            
			$objPHPExcel = new PHPExcel();
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex(0);
            //$objPHPExcel->getActiveSheet()->setTitle('listing_'.($key+1));
			
			$rowCount = 1;
            $column = 'A';
            
            for ($i = 0; $i < count($headerArray); $i++) {
                $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $headerArray[$i]);
                $column++;
            }
            
            $rowCount = 3;
            foreach($list as $dataRow) {
                $column = 'A';
                foreach($dataRow as $dataCell) {
                    $objPHPExcel->getActiveSheet()->setCellValue($column.$rowCount, $dataCell);
                    $column++;
                }
				error_log("row number: ".$rowCount);
                $rowCount++;
            }
			
			error_log("Assigning document name.....\n", 3, "/tmp/".$logFileName);
			$documentName = $scope."_listing_details_".date('Y-m-d').'.xlsx';
			if($insertDataInChunks) {
				$documentName = $scope."_listing_details_".date('Y-m-d').'_part_'.($key+1).'.xlsx';
			}
			error_log("Document name: \n".$documentName, 3, "/tmp/".$logFileName);
			$documentURLArray[$key] = "/var/www/html/shiksha/mediadata/reports/".$documentName;
			error_log("Document URL array: \n".print_r($documentURLArray, true), 3, "/tmp/".$logFileName);
			
			error_log("Creating object writer.....\n", 3, "/tmp/".$logFileName);
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			error_log("Saving document using object writer.....\n", 3, "/tmp/".$logFileName);
			$objWriter->save($documentURLArray[$key]);
        }
		
		$time_end = microtime_float();
		$time = $time_end - $time_start;
		error_log("Excel prepared. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/".$logFileName);
		
		error_log($documentName." created.\n", 3, "/tmp/".$logFileName);
		
		return $documentURLArray;
    }
    
    function sendMailWithAttachmentLink($documentURLArray, $scope) {
		if($scope == 'Abroad') {
			$logFileName = 'log_abroad_listing_details_'.date('y-m-d');
		} else {
			$scope = 'National';
			$logFileName = 'log_national_listing_details_'.date('y-m-d');
		}
		
		//create zip files
		error_log("Compressing files.....\n", 3, "/tmp/".$logFileName);
		$zip = new ZipArchive();
		$zip_name = $scope."ListingDetailsZipFile_".date('y-m-d').".zip";
		$zip_path = "/var/www/html/shiksha/mediadata/reports/".$scope."ListingDetailsZipFile_".date('y-m-d').".zip"; //inside zip
		error_log("zip name: ".$zip_name, 3, "/tmp/".$logFileName);
		
		if($zip->open($zip_path, ZIPARCHIVE::CREATE)!==TRUE){
			error_log("ZIP creation failed.\n", 3, "/tmp/".$logFileName);
		} else {
			foreach($documentURLArray as $documentURL){
				$documentNameInZip = substr($documentURL, 40); //zip path
				$documentPath = "mediadata/reports/".$documentNameInZip;
				
				error_log("\ndocument name in zip: ".$documentNameInZip, 3, "/tmp/".$logFileName);
				error_log("\ndocument path: ".$documentPath, 3, "/tmp/".$logFileName);
				$zip->addFile($documentPath, $documentNameInZip);
			}
			$zip->close();
		}
		error_log("Zip created.....\n", 3, "/tmp/".$logFileName);
		
        //send mail
        $time_start = microtime_float();
        error_log("Preparing mail.....\n", 3, "/tmp/".$logFileName);
        
        //load libraries to send mail
        $this->load->library('alerts_client');
        $alertClient = new Alerts_client();
        $this->load->library('Ldbmis_client');
        $misObj = new Ldbmis_client();
        
		/*
        $attachment = array();
        $documentContent = base64_encode(file_get_contents($zip_path));
        $type_id = $misObj->updateAttachment(1);
        $attachment[] = $alertClient->createAttachment("12", $type_id, 'course', 'E-Brochure', $documentContent, $zip_name, 'doc', 'true');    
        */
		
        $subject = $scope." Listing Detais on ".date('Y_m_d');
        $content = "<p>Hi,</p> <p>You can access the required files for data dump through following link:</p> <p>www.shiksha.com/ListingScripts/downloadReport/".$zip_name."</p> <p>- Nikita(nikita.jain@shiksha.com).</p>";
        
        $emailIdarray=array('anupama.m@shiksha.com', 'amritesh.parmar@shiksha.com', 'joydeep.biswas@shiksha.com', 'nikita.jain@shiksha.com', 'sukhdeep.kaur@99acres.com');
		foreach($emailIdarray as $key=>$emailId){
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '', 'n');
		}
		
        $time_end = microtime_float();
        $time = $time_end - $time_start;
		
        error_log("Mail prepared. Time taken: ".round($time, 4)." seconds\n", 3, "/tmp/".$logFileName);
        error_log("You will be getting the excel in your email shortly.\n", 3, "/tmp/".$logFileName);
		return;
    }
    
    function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}
?>