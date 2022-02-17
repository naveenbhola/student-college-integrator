<?php

class ResponseMigration extends MX_Controller
{
    function index()
    {
        $this->load->view('lms/responseMigration');
    }
    
    function confirmMigration()
    {
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle();
        $this->load->model('listing/coursemodel');
        
        
        $listingTypes = $this->input->post('listingType');
        $oldListingIds = $this->input->post('oldListingId');
        $newListingIds = $this->input->post('newListingId');
        
        $migrationConfirmed = $this->input->post('migrationConfirmed');
        
        $listings_with_localities=array(
                'SMU'=>array(33535,33536,33544,33537,33538,33539,33540,33541,33542,33543,33545,33546,33547,33548,33549,33550,33551,33552,33553,33554,33555,33556,33557,33558,33559,33560,33561,33562,33563,33564,33565,33566,33567,33568,33569,33570,33571,33572,33573,33574,33575,33576,33577,33578,33579,33580,33581,33582,33583,33584,33585,33586,33618,33619,33620,33621,33622,33623,33624,33625,33626,33627,33628,33629,33632,33633,33634,33635,33636,33637,33638,33639,33640,33641,33642,33650,33651,33652,33653,33654,33655,33656,33657,33658,33659,33660,33663,33664,33665,33666,33667,33668,33669,33670,33671,33672,33673,33674,33676,33677,33678,33679,33680,33681,33682,33683,33684,33685,33686,33687,33688,33689,33690,33691,33692,33693,33694,33695,33696,33697,33698,33699,33700,33705,33706,33707,33708,33709,33710,33711,33712,33713,33714,33715,33716,33717,33718,33719,33720,33721,33722,33723,33724,33725,33726,33729,33730,33731,33732,33733,33734,33735,33736,33737,33738,33740,33741,33742,33743,33744,33745,33746),
                'UTS'=>array(33135,33133,33139,33140,33141,33142,33143,33144,33145,33146,33160,33161,33199,33200,33201,33203,33202,33204,33205,33208,33207,33206,33345,33344,33341,33347,33340,33346,33343,33413,33414,33415,33417,33419,33421,33401,33404,33409,33362,33366,33408,33407,34094,34095,34096,34097,34098,34099,34101,34100,34102,34103,34104,34105,34106,34107,34108,34113,34109,34111,34110,34112,34122,34123,34124,34125,34126,34127,34128,34129,34130,34131,34132,34133,34136,34134,34135,34139,34137,34138,34140,34142,34141,34143,34147,34148,34149,34151,34150,34153,34152,34154,34155,34156,34158,34157,34159,34160,34163,34161,34162,34164,34165,34166,34167),
                'MAAC'=>array(119,35033,35043,35040,35042,35041,35034,35038,35037,35049,35048,35046,35044,35047,35045),
                'ICA'=>array(34996,34994,34993,34991,34989,34708,4211,34992,34990,34995,34984,34985,34988,34874,34873,34938,34933,34934,34936,34929,34931,34924,34728,34923,34954,34956,34955,34963,34959,34947,34949,34944,34945,34952,34953,34950,34562,34710,34861,34859,34828,34838,34837,34834,34857,34856,34855,34854,34853,34852,34851,34849,34848,34845,34843,34980,34981,34978,34987,34982,34976,34974,34973,34971,34972,34965,34966,34941,34937,34939,34943,34935,34926,34930,34932,34921,34918,34909,34910,34916,34913,34907,34900,34902,34894,34905,34891,34887,34889,34880,34879,34877,34749,34717,34776,34754,34756,34564,34560,34840,34709,34831,34839,34829,34833,34836,34832,34566,34777,34770,34763,30366,34890,34888,34881,34875,34884,34876,34878,34755,34746,34744,34762,34835,34797,34979,34977,34975,34961,34960,34958,34948,34946,34940,34925,34917,34920,34919,34914,34912,34903,34896,34904,34901,34897,34898,34893,34895,34892,34725,34726,34747),
                'COURSE_WISE_CUSTOMIZED_LOCATIONS'=>array(145057,144986,145124,145116,145038,145026,145030,145128,145130,145122,145118,136513,145042,145139,145120,145049)
        );

 	    $dataValidation = array();
        $migrationCount = 1;
        
        for($i=0;$i<10;$i++) {
            
            $listingType = $listingTypes[$i];
            $oldListingId = $oldListingIds[$i];
            $newListingId = $newListingIds[$i];
            
            $query = $dbHandle->query('SELECT listing_type_id FROM listings_main WHERE listing_type = ? AND listing_type_id = ? AND status = "deleted"',array($listingType,$oldListingId));
            $row = $query->row_array();
            $validOldListingId = $row['listing_type_id'];
            
            $isAbroadCourse = $this->coursemodel->isStudyAboradListing($oldListingId, 'course');   
            if($isAbroadCourse) {
				$query = $dbHandle->query('SELECT institute_id FROM course_details WHERE course_id = ? AND status = "deleted"',array($oldListingId));
				$row = $query->row_array();
				$oldCourseInstituteId = $row['institute_id'];
			} else {				
				$query = $dbHandle->query('SELECT primary_id as institute_id FROM shiksha_courses WHERE course_id = ? AND status = "deleted"',array($oldListingId));
				$row = $query->row_array();
				$oldCourseInstituteId = $row['institute_id'];
			}
            
            $query = $dbHandle->query('SELECT listing_type_id FROM listings_main WHERE listing_type = ? AND listing_type_id = ? AND status = "live"',array($listingType,$newListingId));
            $row = $query->row_array();
            $validNewListingId = $row['listing_type_id'];
            
            $isAbroadCourse = $this->coursemodel->isStudyAboradListing($newListingId, 'course');
            if($isAbroadCourse) {
				$query = $dbHandle->query('SELECT institute_id FROM course_details WHERE course_id = ? AND status = "live"',array($newListingId));
				$row = $query->row_array();
				$newCourseInstituteId = $row['institute_id'];
			} else {
				$query = $dbHandle->query('SELECT primary_id as institute_id FROM shiksha_courses WHERE course_id = ? AND status = "live"',array($newListingId));
				$row = $query->row_array();
				$newCourseInstituteId = $row['institute_id'];			
			}
            
            $validation = array('listingType' => $listingType,'oldListingId' => $oldListingId,'newListingId' => $newListingId);
            $errors = array();
            
            if(in_array($oldCourseInstituteId,$listings_with_localities['SMU']) || in_array($oldCourseInstituteId,$listings_with_localities['UTS']) || in_array($oldCourseInstituteId,$listings_with_localities['MAAC']) || in_array($oldCourseInstituteId,$listings_with_localities['ICA']) || in_array($oldCourseInstituteId,$listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
                $validation['isValid'] = 'No';
                $errors[] = "Old listing id cannot be migrated.";
            }
            
            else if(in_array($oldListingId,$listings_with_localities['SMU']) || in_array($oldListingId,$listings_with_localities['UTS']) || in_array($oldListingId,$listings_with_localities['MAAC']) || in_array($oldListingId,$listings_with_localities['ICA']) || in_array($oldListingId,$listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
                $validation['isValid'] = 'No';
                $errors[] = "Old listing id cannot be migrated.";
            }
            
            if(in_array($newCourseInstituteId,$listings_with_localities['SMU']) || in_array($newCourseInstituteId,$listings_with_localities['UTS']) || in_array($newCourseInstituteId,$listings_with_localities['MAAC']) || in_array($newCourseInstituteId,$listings_with_localities['ICA']) || in_array($newCourseInstituteId,$listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
                $validation['isValid'] = 'No';
                $errors[] = "New listing id cannot be migrated.";
            }
            
            else if(in_array($newListingId,$listings_with_localities['SMU']) || in_array($newListingId,$listings_with_localities['UTS']) || in_array($newListingId,$listings_with_localities['MAAC']) || in_array($newListingId,$listings_with_localities['ICA']) || in_array($newListingId,$listings_with_localities['COURSE_WISE_CUSTOMIZED_LOCATIONS'])) {
                $validation['isValid'] = 'No';
                $errors[] = "New listing id cannot be migrated.";
            }

            if($validOldListingId && $validNewListingId) {
                if($validation['isValid'] != 'No') {
                    $validation['isValid'] = 'Yes';
                }
            }
            else if(!$oldListingId && !$newListingId) {
                $validation['isValid'] = 'Blank';
            }
            else {
                $validation['isValid'] = 'No';
                if($oldListingId && !$validOldListingId) {
                    $errors[] = "Old listing id is invalid.";
                }
                if($newListingId && !$validNewListingId) {
                    $errors[] = "New listing id is invalid.";
                }
                
            }
            
            $validation['errors'] = $errors;
            if(count($validation['errors']) > 0) {
                $migrationCount = 0;
            }
            $dataValidation[] = $validation;
            
        }
        
        if($migrationCount == 0) {
            $migrationConfirmed = 'no';
        }
        
        if($migrationConfirmed == 'yes') {
            $this->_migrateResponses($dataValidation);
        }
        else {
            $this->load->view('lms/responseMigration',array('dataValidation' => $dataValidation));
        }
    }

    public function responseMigrationForCourseDeletion($data){
        if(is_array($data)){
            $this->_migrateResponses($data);
            return 1;
        }else{
            return 0;
        }
    }
    
    private function _migrateResponses($data)
    {
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->model('listing/coursemodel');
        
        for($i=0;$i<10;$i++) {
            if($data[$i]['isValid'] == 'Yes') {
                $sql = "SELECT * FROM tempLMSTable WHERE listing_type = ? AND listing_type_id = ?";
                $query = $dbHandle->query($sql,array($data[$i]['listingType'],$data[$i]['oldListingId']));
                $results = $query->result_array();
                
                $insertData = array(
                    'migration_time' => date('Y-m-d H:i:s'),
                    'migrated_to' => $data[$i]['newListingId']
                );
                
                $isAbroadCourse = $this->coursemodel->isStudyAboradListing($data[$i]['newListingId'], 'course');
                if($isAbroadCourse) {
					$sql = "SELECT institute_location_id FROM course_location_attribute WHERE course_id = ? AND attribute_type = 'Head Office' AND attribute_value = 'TRUE' AND status = 'live'";
					$query = $dbHandle->query($sql,array($data[$i]['newListingId']));
					$locationId = $query->row_array();
				} else {										
					$sql = "SELECT listing_location_id as institute_location_id FROM shiksha_courses_locations WHERE course_id = ? AND is_main=1 AND status = 'live'";
					$query = $dbHandle->query($sql,array($data[$i]['newListingId']));
					$locationId = $query->row_array();
				}
                
                $updateTempLmsTableSql = "UPDATE IGNORE tempLMSTable SET listing_type_id = ? , submit_date = ? WHERE listing_type_id = ? AND listing_type = ? AND id in (?)";
                
                $updateSql = "UPDATE IGNORE responseLocationTable SET instituteLocationId = ? WHERE responseId in (?)";
                
                foreach($results as $result) {

                    $tempLMSId[] = $result['id'];

                    if(count($tempLMSId)==1000){

                        $dbHandle->query($updateTempLmsTableSql,array($data[$i]['newListingId'],$result['submit_date'],$data[$i]['oldListingId'],$data[$i]['listingType'],$tempLMSId));

                        $dbHandle->query($updateSql,array($locationId['institute_location_id'],$tempLMSId));
                        $tempLMSId  = array();                        
                    }


                    foreach($result as $key => $value) {
                 
                        if($key == 'id') {
                            $responseLocationId[] = $value;
                            $insertData['responseId'] = $value;
                            //$dbHandle->query($updateSql,array($locationId['institute_location_id'],$value));
                        } else if($key == 'is_processed' || $key == 'visitorsessionid' || $key == 'tracking_keyid') {	
                            continue;
                        } else {                        
                            $insertData[$key] = $value;
                        }
                    }
                    
                   // $dbHandle->insert('responseMigrationBackup',$insertData);
                }

                if(!empty($tempLMSId)) {
                     $dbHandle->query($updateTempLmsTableSql,array($data[$i]['newListingId'],$result['submit_date'],$data[$i]['oldListingId'],$data[$i]['listingType'],$result['id']));
                     $dbHandle->query($updateSql,array($locationId['institute_location_id'],$tempLMSId));
                }

                
                $sql = "SELECT * FROM tempLmsRequest WHERE listing_type = ? AND listing_type_id = ?";
                $query = $dbHandle->query($sql,array($data[$i]['listingType'],$data[$i]['oldListingId']));
                $results = $query->result_array();
                $updateTempLmsRequestSql = "UPDATE IGNORE tempLmsRequest SET listing_type_id = ? , submit_date = ? WHERE  id in (?)";
                
                $tempLMSRequestId = array();

                foreach($results as $result) {

                    if(count($tempLMSId)==1000){
                        $tempLMSRequestId[]   = $result['id'];
                        $dbHandle->query($updateTempLmsRequestSql,array($data[$i]['newListingId'],$result['submit_date'],$tempLMSRequestId));
                        $tempLMSRequestId = array();
                    }

                }
                
                if(!empty($tempLMSRequestId)) {
                    $dbHandle->query($updateTempLmsRequestSql,array($data[$i]['newListingId'],$result['submit_date'],$tempLMSRequestId));
                }

                //$updateSql = "UPDATE tempLMSTable SET listing_type_id = ? WHERE listing_type_id = ? AND listing_type = ?";
                //$dbHandle->query($updateSql,array($data[$i]['newListingId'],$data[$i]['oldListingId'],$data[$i]['listingType']));
                
                //$updateSql = "UPDATE tempLmsRequest SET listing_type_id = ? WHERE listing_type_id = ? AND listing_type = ?";
                //$dbHandle->query($updateSql,array($data[$i]['newListingId'],$data[$i]['oldListingId'],$data[$i]['listingType']));
		
            }
        }
        echo '<div class="orangeColor fontSize_10p bld" style="width: 950px; font-size: 22px;"><span>Thank you. Responses have been successfully migrated.</span><br/></div>';
    }
    
    public function populateLatestUserResponseData() {
	ini_set('memory_limit', '-1');
	ini_set('time_limit', '-1');
	
	$this->dbLibObj = DbLibCommon::getInstance('User');
	$dbHandle = $this->_loadDatabaseHandle('write');
	
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	$courseRepository = $listingBuilder->getCourseRepository();
	
	$courseMappingAbroad =array();
	$courseMappingIndia = array();
	
	$courseMappingSqlAbroad = "SELECT course_id as courseId, category_id as categoryId FROM abroadCategoryPageData WHERE status ='live' ";
	$courseMappingSqlAbroadResult = $dbHandle->query($courseMappingSqlAbroad);
	foreach ($courseMappingSqlAbroadResult->result_array() as $row){
	    $courseMappingAbroad[$row['courseId']] = $row['categoryId'];
	}
	
	$courseMappingSql = "SELECT a.course_id as courseId, b.parentId as categoryId FROM categoryPageData a inner join categoryBoardTable b on a.category_id = b.boardId WHERE a.status ='live' ";
	$courseMappingSqlResult = $dbHandle->query($courseMappingSql);
	foreach ($courseMappingSqlResult->result_array() as $row){
	    $courseMappingIndia[$row['courseId']] = $row['categoryId'];
	}
	
	
	$query = "SELECT a.userId, a.listing_type_id, a.action, a.listing_subscription_type, a.submit_date "
	    . "FROM tempLMSTable a "
	    . "INNER JOIN (select max(id) as xid from tempLMSTable WHERE listing_type = 'course' AND submit_date > '2013-05-31 23:59:59' AND submit_date <= '2014-06-24 23:59:59' group by userid) x on x.xid = a.id ";
	
	$result = $dbHandle->query($query);
	$courseObjects = array();
	foreach ($result->result_array() as $row) {    
	    $countryId = NULL;
	    $instituteId = NULL;
	    $universityId = NULL;
	    $categoryId = NULL;
	    
	    if(!($courseMappingIndia[$row['listing_type_id']] > 0 || $courseMappingAbroad[$row['listing_type_id']] > 0)) {
		continue;
	    }
	    
	    if(empty($courseObjects[$row['listing_type_id']])) {
		$courseObj = $courseRepository->find($row['listing_type_id']);
		$courseObjects[$row['listing_type_id']] = $courseObj;
	    }
	    
	    $countryId = $courseObjects[$row['listing_type_id']]->getMainLocation()->getCountry()->getId();
	    if(empty($countryId)) {
		$countryQuery = "SELECT country_id FROM abroadCategoryPageData WHERE status = 'live' AND course_id = ?";
		$countryResult = $dbHandle->query($countryQuery, array($data['listing_type']));
		if($countryResult->num_rows() > 0) {
		    $countryId = $countryResult->row()->country_id;
		}
		else {
		    $countryId = 2;
		}
	    }
	    $instituteId = $courseObjects[$row['listing_type_id']]->getInstId();
	    
	    if($countryId == 2) {
		$categoryId = $courseMappingIndia[$row['listing_type_id']];
	    }
	    else {
		$categoryId = $courseMappingAbroad[$row['listing_type_id']];
		$universityId = $courseObjects[$row['listing_type_id']]->getUniversityId();
	    }
	    
	    $insertQuery = "insert into latestUserResponseData set userId = ?, courseId = ? , instituteId = ? ";
	    if($universityId){
		$insertQuery .= ", universityId = ".$dbHandle->escape($universityId)." ";
	    }
	    $insertQuery .= ", categoryId = ? , countryId = ?, action = ?, listing_subscription_type = ? , modified_at = ?";
	    
	    $dbHandle->query($insertQuery, array($row['userId'], $row['listing_type_id'], $instituteId, $categoryId, $countryId, $row['action'], $row['listing_subscription_type'], $row['submit_date']));
	}
	echo 'Done';
    }
    
    public function courseXmlFeed(){
        $t1 = time();
        ini_set('memory_limit', '-1');
	ini_set('time_limit', '-1');
        
        $this->dbLibObj = DbLibCommon::getInstance('User');
	$dbHandle = $this->_loadDatabaseHandle('write');
        $this->load->library('categoryList/CategoryPageRecommendations');
        $this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	$courseRepository = $listingBuilder->getCourseRepository();
        $instituteRepo = $listingBuilder->getInstituteRepository();
        
        $abroadCourseSql = "SELECT course_id FROM abroadCategoryPageData WHERE status ='live'";
        
        $abroadCourseResult = $dbHandle->query($abroadCourseSql);
	$abroadCourse = array();
        foreach ($abroadCourseResult->result_array() as $row)
        {
            $abroadCourse[$row['course_id']] = true;
        }
        
        //$sql = "select a.course_id, a.institute_id, a.courseTitle, a.course_type, a.course_level_1, a.duration_value, a.duration_unit, a.fees_value, a.fees_unit, b.institute_name, b.institute_type, c.listing_seo_url, d.url from course_details a inner join institute b on a.institute_id = b.institute_id left join listings_main c on a.course_id = c.listing_type_id left join course_photos_table d on a.course_id = d.course_id where a.status = 'live' and b.status = 'live' and c.status = 'live' group by a.course_id";
        $sql = "select course_id from course_details where status = 'live'";
        $result = $dbHandle->query($sql);
	$i = 0;
        
        $data ='<?xml version="1.0"?>';
        $data.= '<rss>';
        echo $data;
        
        foreach ($result->result_array() as $row){
//            if($i>30000){break;}
//            if($i%500 == 0){
//                error_log($i.' completed');
//            }
            $courseObj = $courseRepository->find($row['course_id']);//_p($courseObj);exit;
            $countryId = $courseObj->getMainLocation()->getCountry()->getId();
            
            //if(!$countryId) $countryId = 2;
            if($countryId != '2'){continue;}
            
            $instituteId = $courseObj->getInstId();
            $instObj = $instituteRepo->find($instituteId);
            $image = $instObj->getMainHeaderImage()->getFullURL();
            
            $data = '<Course>';

            $data .='<CourseID><![CDATA[';
            $data .= $row['course_id'];
            $data .=']]></CourseID>';

            $data .='<CourseName><![CDATA[';
            $data .= str_replace('>', ' ', $courseObj->getName());
            $data .=']]></CourseName>';

            $data .='<CourseType><![CDATA[';
            $data .= $courseObj->getCourseType();
            $data .=']]></CourseType>';

            $data .='<CourseLevel><![CDATA[';
            $data .= $courseObj->getCourseLevel1Value();
            $data .=']]></CourseLevel>';

            $data .='<Institute><![CDATA[';
            if(isset($abroadCourse[$row['course_id']])){
                $universityName = $courseObj->getUniversityName();
                $data .= $courseObj->getInstituteName().' - '.$universityName;
            }else{
                $data .= $courseObj->getInstituteName();
            }
            $data .=']]></Institute>';

            $data .='<City><![CDATA[';
            $data .= $courseObj->getMainLocation()->getCity()->getName();
            $data .=']]></City>';

            $data .='<Fees><![CDATA[';
            $data .= $courseObj->getFees();
            $data .=']]></Fees>';

            $data .='<Duration><![CDATA[';
            $data .= $courseObj->getDuration();
            $data .=']]></Duration>';

            $data .='<RecommendedInstitutesCourse><![CDATA[';
            $alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes($row['course_id']);
            $data .= implode(',', $alsoViewedInstitutes);
            $data .=']]></RecommendedInstitutesCourse>';

            $data .='<CourseLP><![CDATA[';
            $data .= $courseObj->getURL();
            $data .=']]></CourseLP>';

            $data .='<OtherDetails><![CDATA[';
            $exams = $courseObj->getEligibilityExams();
            if(count($exams)){
                $examdata = array();
                foreach ($exams as $exam) {
                    if($exam instanceof AbroadExam){
                        $examdata[] = $exam->getName().':'.$exam->getCutOff().' '.$exam->getMarksType();
                    }
                    if($exam instanceof Exam){
                        $examdata[] = $exam->getAcronym().':'.$exam->getMarks().' '.$exam->getMarksType();
                    }
                }
                $data .= implode(',', $examdata);
            }
            $data .=']]></OtherDetails>';

            $data .='<Image><![CDATA[';
            $data .= $image;
            $data .=']]></Image>';

            $data .='<InventoryStatus><![CDATA[active]]></InventoryStatus>';

            $data .='</Course>';
            $data .= "\n";

            echo $data;
            //$i++;
        }
        
        $data ='</rss>';
        echo $data;
        
//        $t2 = time();
//        $t1 = $t2 -$t1;
//        error_log( 'Total time:'.$t1);
        exit;
    }
}
