<?php
/*
 * Refer sheet - https://docs.google.com/spreadsheets/d/1dnk4KGrZuCBDoWUUJlLf8DCUstITIx43Z7vVN7W6Bmg/edit#gid=1790245925
 * Author - Nikita Jain
 */
class CourseMigration extends MX_Controller {
	public function __construct() {
		ini_set("memory_limit", "-1");
		ini_set("max_execution_time", "-1");

		$this->logFileName = 'log_course_migration_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        //load library to send mail
        $this->load->library('alerts_client');
        $this->alertClient = new Alerts_client();

        //load migration library
        $this->coursemigrationlibrary = $this->load->library('CourseMigrationLibrary');
    }

	public function migrateCourses($courseId) {
		return;

		if(empty($courseId)) {
			_p("No course id mentioned.. returning.");
			return;
		}
		//migrate course data from DB
		$incorrectHierarchyCourses = $this->coursemigrationlibrary->migrateCoursesFromDB($courseId);
		//error_log("Done with DB migration.\n", 3, $this->logFilePath);

		//migrate course data from excel
		//$this->coursemigrationlibrary->migrateCoursesFromExcel($courseId);
		error_log("Done with excel migration.\n", 3, $this->logFilePath);

		if($incorrectHierarchyCourses) {
			//$this->sendMail($incorrectHierarchyCourses);
		}
		//_p("It's done baby");
	}

	public function migrateSEOUrls($courseId) {
		return;
		//migrate course data from excel
		$this->coursemigrationlibrary->migrateCourseUrls($courseId);
		error_log("Done with url migration.\n", 3, $this->logFilePath);
	}

	public function sendMail($incorrectHierarchyCourses) {
		return;
		$subject      = "Course Migration - Corrupt courses found.";
		$emailIdarray = array('nikita.jain@shiksha.com', 'sukhdeep.kaur@99acres.com', 'saurabh.gupta@shiksha.com', 'kashish.mehta@shiksha.com');
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}

		$content = 	"<p>Hi,</p> ".
						"<p>".
							"We are done with course migration. We could not find mapping for category to stream for these courses, hence have saved them in draft - ".print_r($incorrectHierarchyCourses, true).
						"</p> ".
					"<p>- Shiksha Dev</p>";
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $content, "html", '', 'n');
		}
	}

	public function changeStatusListingsMain($type = 'course', $typeId) {
		return;
		error_log("Starting listings_main status change for ".$type.".\n", 3, $this->logFilePath);

		$courseMigrationModel = $this->load->model('listingMigration/coursemigrationmodel');
		$courseMigrationModel->changeStatusInstitutes($type, $typeId);

		$this->coursemigrationlibrary->changeStatusListingsMain($type, $typeId);
		
		error_log("Done with listings_main status change.\n", 3, $this->logFilePath);
	}

	public function changeCourseStatus() {
		return;
		$this->coursemigrationlibrary->changeCourseStatus();
	}

	public function changeExamMapping() {
		return;
		$this->coursemigrationlibrary->changeExamMapping();
		_p('DONE');
	}

	/**
	 * Purpose : One time cron to update course affiliation given by saurabh in excel 
	 * Story Id : LF-6424
	 * @author Aman Varshney <varshney.aman@gmail.com>
	 * @date   2017-04-17
	 * @return
	 */
	function updateCourseAffiliation(){
		return;
		$startTime = microtime(true);
		$logFileName = 'log_update_affiliation_'.date('y-m-d');
        $logFilePath = '/tmp/'.$logFileName;

		$this->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder      = new InstituteBuilder();
		$instituteRepo         = $instituteBuilder->getInstituteRepository();  
		$courseMigrationModel  = $this->load->model('listingMigration/coursemigrationmodel');
		$coursecache           = $this->load->library('nationalCourse/cache/NationalCourseCache');
		$institutepostingmodel = $this->load->model("nationalInstitute/institutepostingmodel");

		//file path
		$file_name    = "/var/www/html/shiksha/public/Affiliation_Update.xlsx";
		$this->load->library('common/PHPExcel');
		$objReader    = PHPExcel_IOFactory::createReader('Excel2007');
		$objReader->setReadDataOnly(true);
		error_log("Starting reading excel file for Affiliation Update");
		$objPHPExcel  = $objReader->load($file_name);
		error_log("Starting reading excel file sheet for Affiliation Update");
		$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
		$totalRows    = $objWorksheet->getHighestRow();
		error_log("Total count in excel file sheet for Affiliation Update : ".$totalRows);

		$data            = array();
		$instituteIdList = array();
        error_log("Starting reading row and preparing the data for Affiliation Update ");
        for ($i=2;$i<=$totalRows;$i++) {
            $courseId = trim($objWorksheet->getCellByColumnAndRow(0,$i)->getValue());
            if($courseId){
				$data[$courseId]['courseId']        = $courseId;
				$affiliationName                    = trim($objWorksheet->getCellByColumnAndRow(1,$i)->getValue());
				$data[$courseId]['affiliationName'] = ($affiliationName)?$affiliationName : NULL;
				$instituteId   						= trim($objWorksheet->getCellByColumnAndRow(2,$i)->getValue());            	
				$data[$courseId]['affiliationId']   = $instituteId;
				
				if($instituteId)
					$instituteIdList[]              = $instituteId;					
            }
        }
        
		$instituteIdList = array_unique($instituteIdList);
		$instituteIdList = array_values($instituteIdList);		
		error_log("Checking institute data from institute cache for Affiliation Update");
		$instituteData   = $instituteRepo->findMultiple($instituteIdList);

	    error_log("Chunks created for Affiliation Update ");
		$dataChunk = array_chunk($data, 1000);
		error_log("Total Chunk to be process : ".count($dataChunk)."\n", 3, $logFilePath);

		error_log("Processing chunks for Affiliation Update ");
		foreach ($dataChunk as $chunkNo => $chuck) {
			$dataToUpdate = array();
			$updatedCourseIds = array();
			error_log("Chunk : ".($chunkNo+1)."\n", 3, $logFilePath);

			foreach ($chuck as $key => $val) {				
		        //check institute Id exist
		       	if(!empty($val['affiliationId'])){
		       		if(!($instituteData[$val['affiliationId']] && $instituteData[$val['affiliationId']]->getId() && $instituteData[$val['affiliationId']]->getListingType() == 'university')){		       		
		       			continue;
		       		}		       		
		       	}
				//prepare data to update 
				$temp                                = array();
				$temp['course_id']                   = $val['courseId'];
				$affiliationNameToUpdate             = $val['affiliationName'];
				$temp['affiliated_university_name']  = ($affiliationNameToUpdate)?$affiliationNameToUpdate:null;
				$affiliationIdToUpdate               = $val['affiliationId'];
				$temp['affiliated_university_id']    = ($affiliationIdToUpdate)?$affiliationIdToUpdate:null;
				if($affiliationIdToUpdate || $affiliationNameToUpdate)
					$temp['affiliated_university_scope'] = 'domestic';
				else
					$temp['affiliated_university_scope'] = null;

				$dataToUpdate[] 					 = $temp;
				$updatedCourseIds[]                  = $val['courseId'];
				error_log("Course Id : ".$val['courseId']."\n", 3, $logFilePath);
			}
			
			error_log("Affiliation Info Updated for chunk ".($chunkNo+1)."\n", 3, $logFilePath);
			error_log("Affiliation Info Updated for chunk ".($chunkNo+1));
			//update affiliationName, affiliationScope and affiliationId in db 
			$modalStatus = $courseMigrationModel->updateAffiliation($dataToUpdate);
		    if($modalStatus == true){
		    	error_log("Caching clear for ".($chunkNo+1)."\n", 3, $logFilePath);
			    //clear course cache
				$coursecache->removeCoursesCache($updatedCourseIds);
				error_log("Index log entry for ".($chunkNo+1)."\n", 3, $logFilePath);
			    //index log entry
			    //section to be asked from ankit bansal
		       	$institutepostingmodel->updateIndexLog($updatedCourseIds,'course','index');
	       	}
        }
        $endTime = microtime(true);        
		$time = $endTime - $startTime;
		$timeStr = "Time taken: ".round(($time*1000), 4); //in ms
		error_log($timeStr);
        error_log("Cron completed");
    }

	public function migrateDeletedCourses($courseId) {
		return;
		$this->coursemigrationlibrary->migrateDeletedCourses($courseId);
	}

	public function changeHttpBanners() {
		return;
		$contentObj = $this->load->library('common/httpContent');

		$tableName = 'tbanners';
		$primaryColumnName = 'sno';
		$contentColumnName = 'bannerurl';
		$status = array();
		$isTag=true;
		$findStr = 'http://';
		$contentObj->findHttpInContent($tableName, $primaryColumnName, $contentColumnName, $status, $isTag, $findStr, true);
	}
}
