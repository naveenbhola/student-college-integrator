<?php

class aicte extends MX_Controller{

	var $year = array('2019-2020','2018-2019','2017-2018');

	var $state = array('Andaman and Nicobar Islands','Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chandigarh','Chhattisgarh','Dadra and Nagar Haveli','Daman and Diu','Delhi ','Goa','Gujarat','Haryana','Himachal Pradesh','Jammu and Kashmir','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Orissa','Puducherry','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal');

        function __construct() {
        }

        public function listingExcelCreation(){

            ini_set('memory_limit','2048M');
	    ini_set('max_execution_time', -1);

            //Define the file location
	    $fileName = "aicteListings";
	    $facultyFileName = "aicteFaculty";

	    //Logic: For each year, we will first make call for 1st State. Then, for each Institute of this state, we will make call for all its courses.
	    //Then, we will write these course information in the Excel
	    //Then, make call for another Institute and write all its course information in Excel
	    //Then, we will start making calls for another state

	    foreach ($this->year as $year){
		error_log("Year === ".$year);
		$fileName = "/tmp/$fileName$year.xlsx";
		$facultyFileName = "/tmp/$facultyFileName$year.xlsx";
		if(!$this->addHeadingRow($fileName)){
			exit;
		}
		if(!$this->addHeadingRowFaculty($facultyFileName)){
			exit;
		}

		foreach ($this->state as $state){

			$state = str_replace(" ","%20",$state);
			error_log("State === ".$state);

			//Make the call to fetch all Institutes of this State
			$instituteJson = $this->getInstitutes($year, $state);

			$instituteArray = $this->formatInstitutes($instituteJson);
			error_log("Number of Institutes found === ". count($instituteArray));

			if(count($instituteArray) > 0){
				foreach ($instituteArray as $institute){
					error_log("Institute === ".$institute['name']);

					$courseJson = $this->getCourses($year, $institute);

					$courseArray = $this->formatCourse($courseJson);

					if(count($courseArray) > 0){
						error_log("Number of Courses found in the above Institute === ". count($courseArray));
						$this->writeExcel($fileName, $state, $institute, $courseArray);
					}
					else{
						error_log("No Courses found in the above Institute!!!");
					}

					$facultyJson = $this->getFaculty($year, $institute);

					$facultyArray = $this->formatFaculty($facultyJson);

					if(count($facultyArray) > 0){
						error_log("Number of Faculty found in the above Institute === ". count($facultyArray));
						$this->writeExcelFaculty($facultyFileName, $state, $institute, $facultyArray);
					}
					else{
						error_log("No Faculty found in the above Institute!!!");
					}
				}
			}
		}
		//Run it for only 1 year
		exit;
	    }

	    error_log("Report Generated");

        }

	public function getInstitutes($year, $state){
		$url = "https://www.facilities.aicte-india.org/dashboard/pages/php/approvedinstituteserver.php?method=fetchdata&year=$year&program=1&level=1&institutiontype=1&Women=1&Minority=1&state=$state%20&course=1";
		//error_log("URL ==== $url");
		return $this->makeCURLCall($url);
	}

	public function formatInstitutes($instituteJson){
		$instituteArray = array();
		$institutes = json_decode($instituteJson, true);
		foreach ($institutes as $institute){
			$tmp = array();
			$tmp['aicteId'] = $institute[0];
			$tmp['name'] = $institute[1];
			$tmp['address'] = $institute[2];
			$tmp['district'] = $institute[3];
			$tmp['type'] = $institute[4];
			$tmp['women'] = $institute[5];
			$tmp['minority'] = $institute[6];
			$tmp['facultyId'] = $institute[7];
			$instituteArray[] = $tmp;
		}
		return $instituteArray;
	}

	public function getCourses($year, $institute){
		$instAICTEId = $institute['aicteId'];
		$url = "https://www.facilities.aicte-india.org/dashboard/pages/php/approvedcourse.php?method=fetchdata&aicteid=/$instAICTEId/&course=/1/&year=/$year/";
		//error_log("URL ==== $url");
		return $this->makeCURLCall($url);
	}

	public function formatCourse($courseJson){
		$courseArray = array();
		$courses = json_decode($courseJson, true);
		foreach ($courses as $course){
			$tmp = array();
			$tmp['aicteInstId'] = $course[0];
			$tmp['instName'] = $course[1];
			$tmp['state'] = $course[2];
			$tmp['program'] = $course[3];
			$tmp['university'] = $course[4];
			$tmp['level'] = $course[5];
			$tmp['name'] = $course[6];
			$tmp['shift'] = $course[7];
			$tmp['mode'] = $course[8];
			$tmp['intake'] = $course[9];
			$tmp['enrollment'] = $course[10];
			$tmp['placement'] = $course[11];
			$courseArray[] = $tmp;
		}
		return $courseArray;
	}

	public function getFaculty($year, $institute){
		$instAICTEId = $institute['aicteId'];
		$instFacultyId = $institute['facultyId'];
		$url = "https://www.facilities.aicte-india.org/dashboard/pages/php/faculty.php?method=fetchdata&aicteid=$instAICTEId&pid=$instFacultyId&year=$year";
		//error_log("URL ==== $url");
		return $this->makeCURLCall($url);
	}

	public function formatFaculty($facultyJson){
		$facultyArray = array();
		$faculties = json_decode($facultyJson, true);
		foreach ($faculties as $faculty){
			$tmp = array();
			$tmp['aicteFacultyId'] = $faculty[0];
			$tmp['name'] = $faculty[1];
			$tmp['gender'] = $faculty[2];
			$tmp['designation'] = $faculty[3];
			$tmp['dateOfJoining'] = $faculty[4];
			$tmp['specialisation'] = $faculty[5];
			$tmp['appointmentType'] = $faculty[6];
			$facultyArray[] = $tmp;
		}
		return $facultyArray;
	}

	public function makeCURLCall($url){
		//sleep(2);
		return file_get_contents($url);
	}

	public function addHeadingRow($fileName){
	    // Load file if it doesn't exists
	    if (file_exists($fileName)){
		    error_log("File already exists. Please check.");
		    return false;
	    }

            $this->load->library('common/PHPExcel');
            $this->load->library('common/PHPExcel/IOFactory');
            $objPHPExcel = new PHPExcel();
            
	    // it should have write permission
            define('PCLZIP_TEMPORARY_DIR', '/tmp/'); // required for temporary data the PHPExcel_IOFactory uses for writing files
            $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $objWorkSheet = $objPHPExcel->createSheet(0);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorkSheet->setTitle("AICTE_Listings_Data");

            $headers          = array(
                'Aicte ID',
                'State',
                'Name',
                'Address',
                'District',
                'Institution Type',
		'Women',
		'Minority',
		'Programme',
		'University',
		'Level of the course',
		'Course',
		'Shift',
		'Full/Part Time',
		'Intake',
		'Enrollment',
		'Placement'
            );
            $rowNumber        = 1;
            $firstRowColumnNo = "A";
            foreach ($headers as $columnNumber => $headerValue) {
                $objPHPExcel->getActiveSheet()->getStyle($firstRowColumnNo . "1")->getFont()->setBold(true);
                $firstRowColumnNo++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $headerValue);
            }

            PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
            $objWriter->save($fileName);
	    return true;

	}

	public function writeExcel($fileName, $state, $institute, $courseArray){

	        $this->load->library('common/PHPExcel');
        	$this->load->library('common/PHPExcel/IOFactory');
	        //$objPHPExcel = new PHPExcel();

        	// it should have write permission
	        define('PCLZIP_TEMPORARY_DIR', '/tmp/'); // required for temporary data the PHPExcel_IOFactory uses for writing files

   	        // Load file if it doesn't exists
	        if (!file_exists($fileName)){
		    error_log("File could not be found while writing. Please check.");
		    return false;
	        }

        	$objPHPExcel   = PHPExcel_IOFactory::load($fileName);
		$rowNumber = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

	        foreach ($courseArray as $type => $course) {
	
                	$rowNumber++;
                	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowNumber, $institute['aicteId']);
	                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowNumber, $state);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowNumber, $institute['name']);
                	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowNumber, $institute['address']);
	                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowNumber, $institute['district']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowNumber, $institute['type']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rowNumber, $institute['women']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowNumber, $institute['minority']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rowNumber, $course['program']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rowNumber, $course['university']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10, $rowNumber, $course['level']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11, $rowNumber, $course['name']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12, $rowNumber, $course['shift']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13, $rowNumber, $course['mode']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14, $rowNumber, $course['intake']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15, $rowNumber, $course['enrollment']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16, $rowNumber, $course['placement']);

	        }

	        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        	$objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        	$objWriter->save($fileName);

	}

	public function addHeadingRowFaculty($fileName){
	    // Load file if it doesn't exists
	    if (file_exists($fileName)){
		    error_log("Faculty File already exists. Please check.");
		    return false;
	    }

            $this->load->library('common/PHPExcel');
            $this->load->library('common/PHPExcel/IOFactory');
            $objPHPExcel = new PHPExcel();
            
	    // it should have write permission
            define('PCLZIP_TEMPORARY_DIR', '/tmp/'); // required for temporary data the PHPExcel_IOFactory uses for writing files
            $objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

            $objWorkSheet = $objPHPExcel->createSheet(0);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorkSheet->setTitle("AICTE_Faculty_Data");

            $headers          = array(
                'State',
                'Aicte ID',
                'ILP Name',
                'Faculty ID',
                'Name',
                'Gender',
		'Designation',
		'Date of Joining',
		'Area of Specialisation',
		'Appointment Type'
            );
            $rowNumber        = 1;
            $firstRowColumnNo = "A";
            foreach ($headers as $columnNumber => $headerValue) {
                $objPHPExcel->getActiveSheet()->getStyle($firstRowColumnNo . "1")->getFont()->setBold(true);
                $firstRowColumnNo++;
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($columnNumber, $rowNumber, $headerValue);
            }

            PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
            $objWriter->save($fileName);
	    return true;

	}

	public function writeExcelFaculty($fileName, $state, $institute, $facultyArray){

	        $this->load->library('common/PHPExcel');
        	$this->load->library('common/PHPExcel/IOFactory');
	        //$objPHPExcel = new PHPExcel();

        	// it should have write permission
	        define('PCLZIP_TEMPORARY_DIR', '/tmp/'); // required for temporary data the PHPExcel_IOFactory uses for writing files

   	        // Load file if it doesn't exists
	        if (!file_exists($fileName)){
		    error_log("File could not be found while writing. Please check.");
		    return false;
	        }

        	$objPHPExcel   = PHPExcel_IOFactory::load($fileName);
		$rowNumber = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();

	        foreach ($facultyArray as $type => $faculty) {
	
                	$rowNumber++;
	                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $rowNumber, $state);
                	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $rowNumber, $institute['aicteId']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $rowNumber, $institute['name']);
                	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $rowNumber, $faculty['aicteFacultyId']);
	                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $rowNumber, $faculty['name']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $rowNumber, $faculty['gender']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $rowNumber, $faculty['designation']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $rowNumber, $faculty['dateOfJoining']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $rowNumber, $faculty['specialisation']);
        	        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9, $rowNumber, $faculty['appointmentType']);

	        }

	        PHPExcel_Settings::setZipClass(PHPExcel_Settings::PCLZIP);
        	$objWriter   = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        	$objWriter->save($fileName);

	}

}