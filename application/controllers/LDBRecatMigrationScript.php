<?php
exit();

class LDBRecatMigrationScript extends MX_Controller {
	
	protected $dbLibObj;
	protected $dbHandle;

	private $predisLibrary;
	private $processed = 0;
	private $emptyDCList;
	private $dummyCourses = array();

	function initDB() {
		// $this->dbLibObj = DbLibCommon::getInstance ( 'Listing' );
		// $this->dbHandle = $this->_loadDatabaseHandle ( "write" );
		
		$this->dbLibObj = DbLibCommon::getInstance ( 'MISTracking' );
		$this->dbHandle = $this->_loadDatabaseHandle ( "read");
	}

	/*Load redis library */
	private function _loadRedisLib(){
		$this->predisLibrary = PredisLibrary::getInstance();
	}

	/*Increment the processed count by 1*/
	private function _incrementProcessedCount($scriptType = 'userInterestMigration'){
		$this->processed++;
		$this->predisLibrary->addMembersToHash($scriptType,array('processed'=>$this->processed));
	}

	public function resetMigrationData(){
		$this->_loadRedisLib();
		$this->predisLibrary->deleteKey(array('userInterestMigration', 'emptyDesiredCourseList', 'implicitResponseProcessedCount', 'Explict-cron-execution-time', 'Implicit-cron-execution-time', 'implicitResponseTotalCount', 'implicitCronTime', 'implicitCronCourseData'));
	}

	private function _jumpProcessedCount($count= 0, $scriptType = 'userInterestMigration'){
		$this->processed += $count;
		$this->predisLibrary->addMembersToHash($scriptType,array('processed'=>$this->processed));
	}

	/*Reset the processed property by thw value of that variable in the redis */
	private function _setProcessedCount($scriptType = 'userInterestMigration'){
		$processed = $this->predisLibrary->getAllMembersOfHashWithValue($scriptType);
		if(empty($processed)){
			$processed = array();
			$processed['processed'] = 0;
		}
		$this->processed = $processed['processed'];
	}

	private function _addElementToTheEmptyDCList($desiredCourse){
		$temp =  $this->predisLibrary->getAllMembersOfHashWithValue('emptyDesiredCourseList');
		if(empty($temp)){
			$temp = array();
		}

		$temp[$desiredCourse] = $desiredCourse;
		$this->predisLibrary->addMembersToHash('emptyDesiredCourseList',$temp);
	}

	public function getEmptyDCList(){
		$this->_loadRedisLib();
		_p($this->predisLibrary->getAllMembersOfHashWithValue('userInterestMigration'));
		_p($this->predisLibrary->getAllMembersOfHashWithValue('emptyDesiredCourseList'));
		_p("Implicit Time in seconds=> ");
		_p($this->predisLibrary->getAllMembersOfHashWithValue('Implicit-cron-execution-time'));
		_p("Explicit Time in seconds => ");
		_p($this->predisLibrary->getAllMembersOfHashWithValue('Explict-cron-execution-time'));
	}

	function resetImplicitCron(){
		$this->_loadRedisLib();
		$this->predisLibrary->deleteKey(array('implicitCronData'));
	}

	public function _jumpImplicitProcessedCount($batch, $count){
		$implicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$implicitCronData = json_decode($implicitCronData['implicitCronData'], true);
		if(!empty($count) && isset($implicitCronData['thread'][$batch]['processed'])){
			$implicitCronData['thread'][$batch]['processed'] += $count;
			$this->predisLibrary->addMembersToHash('implicitCronData', array('implicitCronData'=>json_encode($implicitCronData)));
		}
	}

	function _setImplicitCronData($batch, $key, $value){
		$implicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$implicitCronData = json_decode($implicitCronData['implicitCronData'], true);

		$implicitCronData['thread'][$batch][$key] = $value;
		$this->predisLibrary->addMembersToHash('implicitCronData', array('implicitCronData'=>json_encode($implicitCronData)));
	}

	public function getImplicitData(){
		$this->_loadRedisLib();
		$implicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$implicitCronData = json_decode($implicitCronData['implicitCronData'], true);
		
		$totalProcessed = 0;
		foreach ($implicitCronData['thread'] as $key => $value) {
			$time = $value['time'];
			$timeFormat = null;
			if(!empty($time)){
				$seconds = (microtime(true)-$time);
				$hours = floor($seconds / 3600);
				$mins = floor($seconds / 60 % 60);
				$secs = floor($seconds % 60);
				$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
			}
			if(!empty($value['time'])){
				$implicitCronData['thread'][$key]['perSecondProcess'] = floor($value['processed']/$seconds);
			}
			$implicitCronData['thread'][$key]['time'] = $timeFormat;
			$implicitCronData['thread'][$key]['percentageProcessed'] = ($value['processed']/($value['end'] - $value['start'])*100).' %';

			$totalProcessed += $value['processed'];

			if(!empty($value['executionTime'])){
				$seconds = $value['executionTime'];
				$hours = floor($seconds / 3600);
				$mins = floor($seconds / 60 % 60);
				$secs = floor($seconds % 60);
				$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
				$implicitCronData['thread'][$key]['executionTime'] = $timeFormat;
				$implicitCronData['thread'][$key]['perSecondProcess'] = floor($value['processed']/$value['executionTime']);
			}
		}

		_p("<h3>Processed Count</h3>");
		_p($totalProcessed);
		_p("==========================================================");


		_p("<h3>Total Count</h3>");
		_p($implicitCronData['totalCount']);
		_p("==========================================================");

		_p("<h3>Percentage Completion</h3>");
		$percentage['Completion'] = (($totalProcessed/$implicitCronData['totalCount'])*100).' %';
		_p($percentage);
		_p("==========================================================");

		$totalTime = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronTime');
		_p("<h3>Thread data</h3>");
		_p($implicitCronData['thread']);
		_p("==========================================================");

		// _p("<h3>Total Time</h3>");
		// _p($timeFormat);
		// _p("==========================================================");
		
		// _p("<h3>Per second process Count</h3>");
		// _p(round($processedCount['processed']/$seconds));
		// _p("==========================================================");
	}

	private function _getAllDummyBaseCourses(){
		$this->load->model('listingBase/listingbasemodel');
        $listingbasemodel = new listingbasemodel;

        $this->dummyCourses = $listingbasemodel->getAllDummyBaseCourses();
	}
	// get the mapping from base_entity_mapping
	private function _getOldCourseSpecializationToNewMapping($specialization_array = array()) {
		
		$this->initDB();
		$mapping_array = array();

		$clause = '';
		if(count($specialization_array) != 0) {
			$clause	= " in (".implode(",",$specialization_array).")";
		}
			
		$query_to_fetch_mapping_data = "select oldspecializationid,stream_id,substream_id, ".
		                               "specialization_id,base_course_id,education_type, credential, ".
		                               "delivery_method,level from base_entity_mapping ".
		                               "where oldspecializationid".$clause;	
	
        $records = $this->dbHandle->query($query_to_fetch_mapping_data)->result_array();
        foreach($records as $row) {
			$mapping_array[$row['oldspecializationid']] = $row;
		}
		
		return $mapping_array;
	}

    // just for testing purpose
    public function test() {
		$this->_getOldCourseSpecializationToNewMapping(array(2));	
	}
	
	/* ticket reference https://infoedge.atlassian.net/browse/LDB-4379
	 * Migrate data for national leads in the table LDBLeadViewCount
	 * StreamId and SubStreamId columns will be populated for 
	*/
	public function migrateLeadViewCount() {
		error_log("migration_started");
		ini_set('memory_limit','-1'); // one time script
		$course_ids_array = array();
		$this->initDB();
		
		$query_to_get_distinct_courses_ids = "SELECT distinct DesiredCourse FROM  LDBLeadViewCount ".
		                                     "WHERE DesiredCourse>0 AND Flag='national'";
		                                                                          		                                     		                                     	
		$records = $this->dbHandle->query($query_to_get_distinct_courses_ids)->result_array();
        foreach($records as $row) {
			$course_ids_array[] = $row['DesiredCourse'];
		}
		
		error_log("migration_started get desiredcourses: total: ".count($course_ids_array));
		
		if(count($course_ids_array)>0) {
			$mapping_data = $this->_getOldCourseSpecializationToNewMapping($course_ids_array);
		}
		
		if(count($mapping_data) >0) {			
			$num=1;
			foreach($mapping_data as $key=>$value) {
				
				$stream = $value['stream_id'];
				$sub_stream = $value['substream_id'];
				$update_query = "UPDATE IGNORE LDBLeadViewCount set StreamId=$stream,SubStreamId=$sub_stream WHERE Flag='national' and DesiredCourse=$key";				               				 
				$records = $this->dbHandle->query($update_query);
				error_log("migration_started done for course id:".$key." count: ".$num);
				$num ++;               
			}
			
			error_log("migration_started done for all");			
		}
	}
	
	
	/* ticket reference https://infoedge.atlassian.net/browse/LDB-4383
	 * Migrate data for national leads pricing
	 * Table used UserProfileCreditAndViewCount
	*/
	public function createPricingDataForLeads() {			
		error_log("migration_started");
		$this->initDB();
		$column_list =  array('StreamId','Course','Mode','ViewCredit','SmsCredit','EmailCredit','ViewCount','SMSCount','EmailCount');
		$stream_wise_rule_list = array(
			'6'=>array('ViewCredit'=>60,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'3'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'8'=>array('ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'12'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'16'=>array('ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'15'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'4'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'5'=>array('ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'10'=>array('ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'17'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'18'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'19'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'9'=>array('ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'13'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'14'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'1'=>array('ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20),
			'2'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'7'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20),
			'11'=>array('ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20)
		);
		
		$education_type_ids_array = array(20,33, 34, 35, 36, 37, 39);
		$batch_array = array();
				
		foreach($stream_wise_rule_list as $stream_id =>$val) {
			$course_list = $this->_getBaseCoursesFromStream($stream_id);	
			if(count($course_list) >0) {
				foreach($course_list as $course_id =>$course_name) {					
					foreach($education_type_ids_array as $education_type_id) {
						$array = array();
						$array['StreamId'] = $stream_id;
						$array['Course'] = $course_id;
						$array['Mode'] = $education_type_id;
						$array['ViewCredit'] = $val['ViewCredit'];
						$array['SmsCredit'] = $val['SmsCredit'];
						$array['EmailCredit'] = $val['EmailCredit'];
						$array['ViewCount'] = $val['ViewCount'];
						$array['SMSCount'] = $val['SMSCount'];
						$array['EmailCount'] = $val['EmailCount'];						
						$batch_array[] = $array;
				   }
				   
				}
			}
		}
		
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>26,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>20,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>33,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>35,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>36,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>37,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>30,'Mode'=>39,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>20,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>33,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>34,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>35,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>36,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>37,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>75,'Mode'=>39,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>20,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>33,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>34,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>35,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>36,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>37,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>76,'Mode'=>39,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		
		
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>20,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>33,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>34,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>35,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>36,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>37,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>98,'Mode'=>39,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>100,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>20,'ViewCredit'=>125,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>50,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>33,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>35,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>36,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>37,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>101,'Mode'=>39,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>20,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>33,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>34,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>35,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>36,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>37,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>113,'Mode'=>39,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>20,'ViewCredit'=>125,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>50,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>33,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>35,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>36,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>37,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>116,'Mode'=>39,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>20,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>33,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>34,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>35,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>36,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>37,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>1,'Course'=>124,'Mode'=>39,'ViewCredit'=>45,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>10,'SMSCount'=>15,'EmailCount'=>20);
		
		
		
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>20,'ViewCredit'=>125,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>50,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>10,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>86,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>2,'Course'=>124,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>20,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>33,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>34,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>35,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>36,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>37,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>3,'Course'=>90,'Mode'=>39,'ViewCredit'=>40,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>8,'Course'=>33,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>8,'Course'=>103,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>13,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>26,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>89,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>100,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>20,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>33,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>34,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>35,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>36,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>37,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		$batch_array[] = array('StreamId'=>11,'Course'=>124,'Mode'=>39,'ViewCredit'=>55,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
		
		$batch_array[] = array('StreamId'=>9,'Course'=>1,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);		
		$batch_array[] = array('StreamId'=>9,'Course'=>82,'Mode'=>34,'ViewCredit'=>50,'SmsCredit'=>5,'EmailCredit'=>3,'ViewCount'=>8,'SMSCount'=>15,'EmailCount'=>20);
				
		if(count($batch_array) >0) {		
			//echo count($batch_array);exit;
			foreach($batch_array as $row) {
				$sql = "INSERT INTO UserProfileCreditAndViewCount (id,StreamId,Course,Mode,ViewCredit,SmsCredit,EmailCredit,ViewCount,SMSCount,EmailCount) ".
						"VALUES (null,".$row['StreamId'].",".$row['Course'].",".$row['Mode'].",".$row['ViewCredit'].",".$row['SmsCredit'].",".$row['EmailCredit'].",".$row['ViewCount'].",".$row['SMSCount'].",".$row['EmailCount'].") ".
						"ON DUPLICATE KEY UPDATE ViewCredit = ".$row['ViewCredit'].", SmsCredit = ".$row['SmsCredit'].", EmailCredit = ".$row['EmailCredit'].", ViewCount= ".$row['ViewCount'].", SMSCount=".$row['SMSCount'].", EmailCount=".$row['EmailCount'];
						
						//echo $sql."</br>";	exit;
						$this->dbHandle->query($sql);
			}	
			//$this->dbHandle->insert_batch('UserProfileCreditAndViewCount', $batch_array);
		}
				
		//error_log("migration_started end === ");		
	}
	
	private function _getBaseCoursesFromStream($stream_id) {
		
		$obj = new \registration\libraries\FieldValueSources\BaseCourses;
        $selectedHierarchies[0]['streamId'] = $stream_id;
	    $selectedHierarchies[0]['substreamId'] = 'any';
	    $selectedHierarchies[0]['specializationId'] = 'any';
        $desiredCourse = $obj->getValues(array('baseEntityArr'=> $selectedHierarchies));
        $base_courses =array();
        foreach($desiredCourse as $val) {
			if(count($val)>0) {
				foreach($val as $data) {
					//_P($data);exit;
					foreach($data as $base_course_id=>$base_course_name) {
						if($base_course_id>0) {
							$base_courses[$base_course_id] = $base_course_name;		
						}
					}
				}
			}
		}
		
		return $base_courses;
	}

	public function explictUserInterestDelta(){ 
		error_log("==shiksha-IMP ========= <<<< Starts >>>>===========");
		$start = microtime(true);

		ini_set("memory_limit",'2048M');
		ini_set("max_execution_time",-1);
		$this->initDB();
		$this->_loadRedisLib();

		$this->_getAllDummyBaseCourses();

		$explicitCronTimestamp = $this->predisLibrary->getAllMembersOfHashWithValue('explicitCronTimestamp');
		$explicitCronTimestamp = $explicitCronTimestamp['time'];
		
		if(empty($explicitCronTimestamp)){
			error_log("==shiksha-IMP== It seems like you haven't run explicit cron. Please run that before this.. ====<<< ENDS >>>==");
			die;
		}
		$sql = 'SELECT userId FROM tuserIndexingQueue WHERE queueTime > "'.$explicitCronTimestamp.'"';
		$indexingData = $this->dbHandle->query($sql)->result_array();

		$userIds = array();
		foreach($indexingData as $key=>$value){
			$userIds[$value['userId']] = $value['userId'];
		}

		if(empty($userIds)){
			error_log("==shiksha-IMP== No users to process --- ====<<< ENDS >>>==");
			die;
		}

		$userOldInterest = array();
		// Getting userids from the tUserPref
		$sql = 'SELECT UserId, desiredCourse, SubmitDate FROM tUserPref WHERE (ExtraFlag="undecided" OR ExtraFlag is NULL) AND desiredCourse>0 AND userId IN ('.implode(', ', $userIds).')';

		$userOldInterest = $this->dbHandle->query($sql)->result_array();
		error_log("==shiksha== === >>> Interest count ===".count($userOldInterest));
		if(empty($userOldInterest)){
			error_log("==shiksha== cron has alrwady processed all the users");
			return;
		}

		error_log("==shiksha==  ===== >>> getting base course info === ");		
		$oldSpecilaizationData = $this->_getOldCourseSpecializationToNewMapping();

		$baseCourseInfo = $this->_getAllBaseCoursesInfo();

		error_log("==shiksha== === >>> inserting into the interest tables ===".count($userOldInterest));
		error_log("==shiksha== time ".(microtime(true)-$start));
		$this->_createUserInterest($userOldInterest, $oldSpecilaizationData, $baseCourseInfo, 100000);

		error_log("==shiksha== interest created and now marking multiple interest to history  ".(microtime(true)-$start));
		$this->predisLibrary->addMembersToHash('explicitCronTimestamp',array('time'=>date('Y-m-d H:i:s')));	

		$this->markMultipleUserInterestToHistory();

		error_log("==shiksha-IMP ========= <<<< Ends >>>>===========".(microtime(true)-$start));
	}


	/* API to create explict interest of the user using desired course (Ticket Reference: LDB-4374)
	 *  >>>> Re-cat Project(Shiksha 2.0) <<<<
	 */
	public function	createUsersExplicitInterest(){ 
		$start = microtime(true);

		ini_set("memory_limit",'4048M');
		ini_set("max_execution_time",-1);
		$this->initDB();
		$this->_loadRedisLib();

		error_log("==shiksha-IMP=========== <<<< migration starts >>>>===========");
		$chunkSize = 100000;
		$emptyCounter = 0;

		$this->_setProcessedCount();
		$this->_getAllDummyBaseCourses();

		error_log("==shiksha== counting number of national users ");
		error_log("==shiksha== time ".(microtime(true)-$start));
		
		$desiredCourses = Modules::run("LDBCommon/getDCFromMappingTable");

		$totalCount = 0;
		$sql = 'SELECT COUNT(*) AS count FROM tUserPref WHERE (ExtraFlag="undecided" OR ExtraFlag is NULL) AND desiredCourse in ('.implode(', ', $desiredCourses).') AND userId>0';
		$totalCount = $this->dbHandle->query($sql)->result_array();
		$totalCount = $totalCount[0]['count'];
		
		//storing timestamp for delta migration
		$this->predisLibrary->addMembersToHash('explicitCronTimestamp',array('time'=>date('Y-m-d H:i:s')));	
		
		error_log("==shiksha-IMP ===== >>> total unprocessed users === ".($totalCount-$this->processed));
		error_log("==shiksha== time ".(microtime(true)-$start));

		if(($totalCount-$this->processed) <= 0){
			error_log("==shiksha-IMP==  ===== >>> Script has already unprocessed all users <<< =====");
			return;
		}

		//getting olf user specialization mapping
		error_log("==shiksha==  ===== >>> getting olf user specialization mapping === ");
		error_log("==shiksha== time ".(microtime(true)-$start));		
		$oldSpecilaizationData = $this->_getOldCourseSpecializationToNewMapping();

		error_log("==shiksha==  ===== >>> getting base course info === ");		
		$baseCourseInfo = $this->_getAllBaseCoursesInfo();

		error_log("==shiksha-IMP  ===== >>> processing === ");
		while($totalCount >= $this->processed){
			error_log("==shiksha==  ===== >>> getting User Data === ");
			error_log("==shiksha== time ".(microtime(true)-$start));

			$userOldInterest = array();
			// Getting userids from the tUserPref
			$sql = 'SELECT UserId, desiredCourse, SubmitDate FROM tUserPref WHERE (ExtraFlag="undecided" OR ExtraFlag is NULL) AND desiredCourse in ('.implode(', ', $desiredCourses).') AND userId>0 ORDER BY PrefId asc LIMIT '.$this->processed.', '.$chunkSize;
			$userOldInterest = $this->dbHandle->query($sql)->result_array();
			error_log("==shiksha== === >>> rearranging, count===".count($result));

			if(empty($userOldInterest)){
				$emptyCounter++;
				if($emptyCounter > 4){
					break;
				}
			}

			error_log("==shiksha== === >>> inserting into the interest tables ===".count($userOldInterest));
			error_log("==shiksha== time ".(microtime(true)-$start));
			$this->_createUserInterest($userOldInterest, $oldSpecilaizationData, $baseCourseInfo, $chunkSize);

			error_log("==shiksha== ==== >>> processed Count = ".$this->processed);
			error_log("==shiksha-IMP ==== >>> percentage processed = ".(($this->processed/$totalCount)*100)."%");

			if($this->processed >= $totalCount){
				break;
			}
		}

		error_log("==shiksha-IMP== Marking Multiple interest to history ===");
		error_log("==shiksha== time ".(microtime(true)-$start));
		/*Marking Multiple interest to history */
		$this->markMultipleUserInterestToHistory();
		$ttime = microtime(true)-$start;
		error_log("==shiksha-IMP == <<<< Total Execution Time >>>>== ".($ttime). ' seconds');
		error_log("==shiksha== time ".(microtime(true)-$start));
		$this->predisLibrary->addMembersToHash('Explict-cron-execution-time',array('time-in-seconds'=>$ttime));
		error_log("==shiksha-IMP ========= <<<< migration Ends >>>>===========");
	}

	public function markMultipleUserInterestToHistory(){
		$start = microtime(true);

		ini_set("memory_limit",'4048M');
		ini_set("max_execution_time",-1);
		$this->initDB();
		$this->_loadRedisLib();
		$sql ='SELECT userId, COUNT(*) as count FROM tUserInterest WHERE status="live" GROUP BY userId HAVING count > 1';
		$result = $this->dbHandle->query($sql)->result_array();
		
		$userIds = array();
		foreach($result as $key=>$value){
			$userIds[] = $value['userId'];
		}

		if(empty($userIds)){
			return;
		}
		
		$sql = 'SELECT interestId, userId FROM tUserInterest WHERE userId in ('.implode(', ', $userIds).') AND status !="history" ORDER BY interestId ASC';
		$result = $this->dbHandle->query($sql)->result_array();

		$userInterestIds = array();
		foreach ($result as $key => $value) {
			if(empty($userInterestIds[$value['userId']])){
				$userInterestIds[$value['userId']] = array();
			}

			array_push($userInterestIds[$value['userId']], $value['interestId']);
		}

		$status = "history";
		$interestIds = array();
		foreach($userInterestIds as $userIds=>$PrefId){
			array_pop($userInterestIds[$userIds]);
			$interestIds = array_merge($interestIds, $userInterestIds[$userIds]);
		}
		
		$sql = 'UPDATE tUserInterest SET status=? WHERE interestId IN ('.implode(',', $interestIds).')';
        $this->dbHandle->query($sql, array($status));

        $sql = 'UPDATE tUserCourseSpecialization SET status=? WHERE interestId IN ('.implode(',', $interestIds).')';
        $this->dbHandle->query($sql, array($status));

        $sql = 'UPDATE tUserAttributes SET status=? WHERE interestId IN ('.implode(',', $interestIds).')';
        $this->dbHandle->query($sql, array($status));


	}

	private function _processInterestBatch($tUserInterest, $tUserCourseSpecialization, $tUserAttributes, $tUserPref){

		$start = microtime(true);
		// error_log("==shiksha== batch starts===".(microtime(true)-$start));
		if(!empty($tUserInterest)){
			$this->dbHandle->insert_batch('tUserInterest', $tUserInterest);
		}

		if(!empty($tUserCourseSpecialization)){
			$this->dbHandle->insert_batch('tUserCourseSpecialization', $tUserCourseSpecialization);
		}

		if(!empty($tUserAttributes)){
			$this->dbHandle->insert_batch('tUserAttributes', $tUserAttributes);
		}

		// error_log("==shiksha== batch ends ===".(microtime(true)-$start));
	}

	private function _batchtuserprefUpdate($tUserPref){
		if(!empty($tUserPref)){
			foreach ($tUserPref as $level => $userids) {
				$sql = 'UPDATE tUserPref SET educationLevel = "'.$level.'" WHERE UserId IN ('.implode(', ', $userids).')';
				
				$this->dbHandle->query($sql);
			}
			
		}
	}

	private function _createUserInterest($userOldInterest, $oldSpecilaizationData, $baseCourseInfo, $chunkSize){
		$start=microtime(true) ;

		$sql = 'SELECT interestId FROM tUserInterest ORDER BY interestId DESC LIMIT 1';
		$interestId = $this->dbHandle->query($sql)->result_array();
		if(empty($interestId)){
			$interestId = 0;
		}else{
			$interestId = $interestId[0]['interestId'];
		}

		/*Loading Dependencies */
		$this->load->model('user/usermodel');
        $userModel = new usermodel;
        
		$index=0;
		$processedCount = 0;
		$checkCount = ($chunkSize/10);

		$tUserInterest = array();
		$tUserAttributes = array();
		$tUserCourseSpecialization = array();
		$tUserPref = array();
		
		$this->load->library('listingBase/BaseAttributeLibrary');
        $baseAttributeLibrary = new BaseAttributeLibrary();

		foreach($userOldInterest as $userid=>$prefData){
			$index++;
			$userid = $prefData['UserId'];
			$desiredCourse = $prefData['desiredCourse'];
			if($index%$checkCount == 0){

				if(!empty($tUserInterest)){
					
					$this->_processInterestBatch($tUserInterest, $tUserCourseSpecialization, $tUserAttributes);
					$this->_jumpProcessedCount($processedCount);
					$this->_batchtuserprefUpdate($tUserPref);

					$processedCount = 0;
					$tUserInterest = array();
					$tUserAttributes = array();
					$tUserCourseSpecialization = array();
					$tUserPref = array();
				}
				error_log("==time== chunk processed time = ".(microtime(true)-$start));
				error_log("==shiksha== ===>>> Pertentage of chunk processed <<<==".(($index/$chunkSize)*100). "%");
					
			}

			if(empty($oldSpecilaizationData[$desiredCourse]) || empty($oldSpecilaizationData[$desiredCourse]['stream_id'])){
				$processedCount++;
				$this->_addElementToTheEmptyDCList($desiredCourse);
				continue;
			}

			$interestId++;
			$userLevel = NULL;

			/*Making data for tUserInterest table */
			$interestData = array();
			$interestData['userId'] = $userid;
			$interestData['interestId'] = $interestId;
			$interestData['streamId'] = $oldSpecilaizationData[$desiredCourse]['stream_id'];
			if(empty($oldSpecilaizationData[$desiredCourse]['substream_id'])){
				$oldSpecilaizationData[$desiredCourse]['substream_id'] = null;
			}

			$interestData['time'] = $prefData['SubmitDate'];
			$interestData['subStreamId'] = $oldSpecilaizationData[$desiredCourse]['substream_id'];

			$tUserInterest[] = $interestData;
			
			$courseSpecializationData = array();
			// if(!empty($oldSpecilaizationData[$desiredCourse]['specialization_id']) || !empty($oldSpecilaizationData[$desiredCourse]['base_course_id'])){

				$courseSpecializationData['interestId'] = $interestId;
				if(empty($oldSpecilaizationData[$desiredCourse]['specialization_id'])){
					$oldSpecilaizationData[$desiredCourse]['specialization_id'] = null;
				}
				$courseSpecializationData['specializationId'] = $oldSpecilaizationData[$desiredCourse]['specialization_id'];
				if(empty($oldSpecilaizationData[$desiredCourse]['base_course_id'])){
					$oldSpecilaizationData[$desiredCourse]['base_course_id'] = null;
					
					$courseSpecTableData = $this->_getDummyCourseSpecMixture($courseSpecializationData['specializationId'], $oldSpecilaizationData[$desiredCourse]['level'], $oldSpecilaizationData[$desiredCourse]['credential']);

					foreach($courseSpecTableData as $key=>$courseData){
						$tempData = array();
						$userLevel = $baseCourseInfo[$courseData['baseCourseId']];
						$tempData['time'] = $prefData['SubmitDate'];
						$tempData['interestId'] = $interestId;
						$tempData['courseLevel'] = $userLevel;
						if(!empty($courseData['baseCourseId'])){
							$tempData['baseCourseId'] = $courseData['baseCourseId'];
						}else{
							$tempData['baseCourseId'] = null;
						}
						
						if(!empty($courseData['specializationId'])){
							$tempData['specializationId'] = $courseData['specializationId'];
						}else{
							$tempData['specializationId'] = null;
						}

						if(empty($courseData['baseCourseId']) && empty($courseData['specializationId'])){
							continue;
						}
						$tUserCourseSpecialization[] = $tempData;
					}
				}else{
					$userLevel = $baseCourseInfo[$oldSpecilaizationData[$desiredCourse]['base_course_id']];
					$courseSpecializationData['courseLevel'] = $userLevel;
					if(!empty($oldSpecilaizationData[$desiredCourse]['base_course_id'])){
						$courseSpecializationData['baseCourseId'] = $oldSpecilaizationData[$desiredCourse]['base_course_id'];
					}else{
						$courseSpecializationData['baseCourseId'] = null;
					}
					$courseSpecializationData['time'] = $prefData['SubmitDate'];
					if(empty($courseSpecializationData['specializationId'])){
						$courseSpecializationData['specializationId'] = null;
					}
					$tUserCourseSpecialization[] = $courseSpecializationData;

				}
			// }

			if(!empty($oldSpecilaizationData[$desiredCourse]['education_type']) || !empty($oldSpecilaizationData[$desiredCourse]['delivery_method'])){
				$educationModes = array();
				$educationModes[0]['education_type'] = $oldSpecilaizationData[$desiredCourse]['education_type'];
				$educationModes[0]['delivery_method'] = $oldSpecilaizationData[$desiredCourse]['delivery_method'];
			}else{
				$educationModes = array();
				$educationModes[0]['education_type'] = 21;
				$educationModes[1]['education_type'] = 20;
			}

			foreach($educationModes as $key=>$em){
				$attributeData = array();

				$attributeData['interestId'] = $interestId;

				$mode = $em['education_type'];
				$deliveryMethod = $em['delivery_method'];
				
				if($mode == 21 && !empty($deliveryMethod) ){
					 $mode = array($deliveryMethod);
				}else if ($mode == 21 && empty($deliveryMethod) ){
					$educationTypeObj  = new \registration\libraries\FieldValueSources\EducationType;
                	$educationTypeValues = $educationTypeObj->getValues();
					$educationTypeValues = $educationTypeValues[21];
					$mode = array();
					$mode[] = 21;
					foreach($educationTypeValues['children'] as $modeId=>$modeName){
						$mode[] = $modeId;
					}
				}else{
					$mode = array($mode);
				}
				
		        $educationType = $baseAttributeLibrary->getAttributeIdByValueId($mode);
		        
		        if(!empty($educationType)){
		        	foreach ($educationType as $attributeValue => $attributeKey) {	        		
						$attributeData['attributeKey'] = $attributeKey;
						$attributeData['attributeValue'] = $attributeValue;
						$attributeData['time'] = $prefData['SubmitDate'];

						$tUserAttributes[] = $attributeData;
		        	}
		        }
			}
			
			if(!empty($userid) && !empty($userLevel)){
				$tUserPref[$userLevel][] = $userid;
			}
			$processedCount++;	

		}

		if(!empty($tUserInterest)){
			$this->_processInterestBatch($tUserInterest, $tUserCourseSpecialization, $tUserAttributes);
			$tUserInterest = array();
			$tUserAttributes = array();
			$tUserCourseSpecialization = array();
			$this->_jumpProcessedCount($processedCount);
			$this->_batchtuserprefUpdate($tUserPref);
			$tUserPref = array();
			$processedCount = 0;
		}
	}

	private function _getDummyCourseSpecMixture($spec, $level, $credential){

		$baseCourseIds = $this->_getRequiredDummuCourseByLevelAndCredential($level, $credential);
		$returnData = array();
		if(!empty($baseCourseIds)){
			foreach ($baseCourseIds as $key => $baseCourseId) {
				$returnData[] = array('baseCourseId'=>$baseCourseId, 'specializationId'=>$spec);
			}
		}else{
			if(empty($spec)){
				return array();
			}

			$returnData[0]['baseCourseId'] = NULL;
			$returnData[0]['specializationId'] = $spec;
		}

		return $returnData;
	}

	private function _getRequiredDummuCourseByLevelAndCredential($level, $credential){
		if(!empty($level) && !empty($credential)){
			foreach($this->dummyCourses as $key=>$courseData){
				if($courseData['level'] == $level && $courseData['credential'] == $credential){
					return array($courseData['base_course_id']);
				}

			}
		}else if(!empty($level) && empty($credential)){
			$returnData = array();
			foreach($this->dummyCourses as $key=>$courseData){
				if($courseData['level'] == $level){
					$returnData[] = $courseData['base_course_id'];
				}
			}
			return $returnData;
		}else if(empty($level) && !empty($credential)){
			if($credential == 11){
				return array($this->dummyCourses[12]['base_course_id']);
			}
			return array();
		}else{
			/*NONE course */
			return array($this->dummyCourses[13]['base_course_id']);
		}
	}

	private function _updateUserEducationLevel($userid, $userLevel){
		if(empty($userid)){
			return;
		}
		
		$sql = 'UPDATE tUserPref SET educationLevel= ? WHERE UserId=?';
		$this->dbHandle->query($sql, array($userLevel, $userid));
		return;
	}

	private function _getAllBaseCoursesInfo(){
		$baseCourseInfo = array();
		
		$sql = 'select bc.base_course_id, bal.value_name from base_courses bc left join base_attribute_list bal on (bc.level = bal.value_id) where bc.status="live" and bal.status="live"';
		$result = $this->dbHandle->query($sql)->result_array();
		foreach ($result as $key => $value) {
			$baseCourseInfo[$value['base_course_id']] = $value['value_name'];
		}
		return $baseCourseInfo;
	}

	public function migrateSearchAgentData(){
		$this->initDB();
		ini_set("memory_limit", '4048M');
		ini_set("max_execution_time",-1);

		error_log('============= cron to migrate search agent started');

		error_log('============= getting exclusion search agent list');
		$excludeSearchAgent = $this->getSearchAgentsWithMultipleStreams();

		error_log('=============  exclusion search agent list retrieved. Total count '.count($excludeSearchAgent));

		$sql = 'select distinct searchAlertId,value as desiredcourse from SAMultiValuedSearchCriteria where keyname ="desiredcourse" and searchAlertId not in ('.implode(', ', $excludeSearchAgent).')';

		$result = $this->dbHandle->query($sql)->result_array();

		error_log('=============  Total search agent to migrate - '.count($result));
		

		foreach ($result as $row) {
			$allDesiredCourses[] = $row['desiredcourse'];
		}

		$allDesiredCourses =array_unique($allDesiredCourses);
		$hierarchyDataMap = $this->_getOldCourseSpecializationToNewMapping($allDesiredCourses);
		unset($allDesiredCourses);

		error_log('=============  Map for desired course and stream retrieved ');

		error_log('=============  Formating search agent data and migrating to MultiValued started');

		$itr=0;
		$skipAgentStreamMap = array();
		$skipAgentSubStreamMap = array();
		$subStreamInsertIdMap = array();

		foreach ($result as $key => $value) {
			error_log('================= rows processed -- '.$itr);

			$desiredcourseId = $value['desiredcourse'];

			if($hierarchyDataMap[$desiredcourseId]['stream_id']>0){				
				$hierarchyData = $this->_formatDataForSearchAgent($desiredcourseId,$hierarchyDataMap[$desiredcourseId]);			

				if($skipAgentStreamMap[$value['searchAlertId']]){
					unset($hierarchyData['Stream']);
				}else{
					$skipAgentStreamMap[$value['searchAlertId']] = 1;
				}

				$subStream = $hierarchyData['Substreams'];

				if($skipAgentSubStreamMap[$value['searchAlertId']][$hierarchyData['Substreams']]){
					unset($hierarchyData['Substreams']);

				}else{
					$skipAgentSubStreamMap[$value['searchAlertId']][$hierarchyData['Substreams']] = 1;
				}

				$insertId = $this->_insertSearchAgentHierarchyData($value['searchAlertId'],$hierarchyData,$subStreamInsertIdMap,$subStream);
				
				if($insertId>0){
					$subStreamInsertIdMap[$value['searchAlertId']][$hierarchyData['Substreams']] = $insertId;
				}

				unset($hierarchyData);
			}

			$itr++;
		}

		error_log('=============  Formating search agent data and migrating to MultiValued    -ENDSSS');
	}

	private function _formatDataForSearchAgent($desiredCourse,$hierarchyData){


		$hierarchyData['Stream'] = $hierarchyData['stream_id'];

		if($hierarchyData['substream_id'] && $hierarchyData['substream_id'] > 0){
			$hierarchyData['Substreams'] = $hierarchyData['substream_id'];
		}

		if($hierarchyData['specialization_id'] && $hierarchyData['specialization_id'] > 0){
			$hierarchyData['Specializations'] = $hierarchyData['specialization_id'];
		}
		
		if($hierarchyData['base_course_id'] && $hierarchyData['base_course_id'] > 0){
			$hierarchyData['Courses'] = $hierarchyData['base_course_id'];
		}

		if($hierarchyData['education_type'] >0 && ($hierarchyData['delivery_method'] == 0 || empty($hierarchyData['delivery_method']) )){
			if($hierarchyData['education_type'] == 21){
				$hierarchyData['Mode_Value'] = array(21,33,34,35,36,37,39);
				$hierarchyData['Mode_Key'] = 5;
			}
			
			if($hierarchyData['education_type'] == 20){
				$hierarchyData['Mode_Value'] = array(20);
				$hierarchyData['Mode_Key'] = 5;
			}
		}

		if($hierarchyData['education_type'] && $hierarchyData['education_type'] > 0 && ($hierarchyData['delivery_method'] != 0 || !empty($hierarchyData['delivery_method']) )) {
			$hierarchyData['Mode_Value'] = array($hierarchyData['education_type'],$hierarchyData['delivery_method']);
			$hierarchyData['Mode_Key'] = 5;
		}	
		
		unset($hierarchyData['stream_id']);
		unset($hierarchyData['substream_id']);
		unset($hierarchyData['specialization_id']);
		unset($hierarchyData['base_course_id']);
		unset($hierarchyData['education_type']);
		unset($hierarchyData['delivery_method']);
		unset($hierarchyData['level']);
		unset($hierarchyData['oldspecializationid']);
		unset($hierarchyData['credential']);

		return $hierarchyData;
	}

	private function _insertSearchAgentHierarchyData($searchAgentId, $data,$subStreamInsertIdMap,$subStream){

	
		$insert_id = '';
		$parentId ='null';

		foreach ($data as $key => $value) {

			$parentId ='null';

			if($key == 'Specializations'){
				if($subStreamInsertIdMap[$searchAgentId][$subStream]>0){
					$parentId = $subStreamInsertIdMap[$searchAgentId][$subStream];
				}else{
					$parentId = $insert_id;
				}
			}

			if(!$parentId || !isset($parentId) || $parentId==''){
				$parentId ='null';
			}

			$sql= "";
			$sql = "insert into  SAMultiValuedSearchCriteria (searchAlertId,keyname,value,is_active,parentId) VALUES ";
			
			if($key == 'Mode_Value'){
				foreach ($value as $modeValue) {
					$sql .= " ('".$searchAgentId."','".$key."','".$modeValue."','live',$parentId),";	
				}

				$sql = substr($sql, 0,-1);
			}else{
				$sql .= " ('".$searchAgentId."','".$key."','".$value."','live',$parentId)";
			}
						
			
			$this->dbHandle->query($sql);
			
			if($key == 'Substreams'){
				$insert_id = $this->dbHandle->insert_id();
			}

		}

		return $insert_id;
	}
	
	function pricingDataInCacheForMigration(){
		
        $this->load->model('ldbmodel');
        $pricingData = $this->ldbmodel->dataInCacheForMigration();
        $this->_loadRedisLib();

        $data = $this->predisLibrary->getAllMembersOfHashWithValue('allUserIndexing');

        foreach ($pricingData as $price) {

        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['ViewCredit'] = $price['ViewCredit'];
        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['SmsCredit'] = $price['SmsCredit'];
        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['EmailCredit'] = $price['EmailCredit'];
        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['ViewCount'] = $price['ViewCount'];
        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['SMSCount'] = $price['SMSCount'];
        	$dataForCache[$price['StreamId']][$price['Course']][$price['Mode']]['EmailCount'] = $price['EmailCount'];
        }

        $dataForCache = serialize($dataForCache);
    	$this->predisLibrary->addMembersToHash('cachedPricingData',array('pricingData'=>$dataForCache));
    	_P('added in cache with key - cachedPricingData');
    	die;

    }

    public function indexingThreadStatus(){
    	$thread= array(1,2,3,4,5);
    	$this->_loadRedisLib();
    	$data = '';
    	foreach ($thread as $threadId) {
    		$data = '';
    		$data = $this->predisLibrary->getAllMembersOfHashWithValue('allUserIndexing'.$threadId);
    		_P('Thread Id- <b>'. $threadId.'</b>, count is <b>'.$data['userId'].'</b> started at time <b>'.$data['startTime'].'</b>');
    		unset($data);
    	}

    	$data = $this->predisLibrary->getAllMembersOfHashWithValue('indexingCachedUsers');
    	$cacheUserCount = $this->predisLibrary->getAllMembersOfHashWithValue('cachedLastUserId');

    	_P('Thread Id- <b>indexingCachedUsers</b> -<b>'.$cacheUserCount['counter'].'</b>  started at time <b>'.$data['startTime'].'</b> and ended at time '.$data['endTime']);
    }

    public function resetUserIndexingData(){
		$this->_loadRedisLib();
		$this->predisLibrary->deleteKey(array('allUserIndexing1', 'allUserIndexing2', 'allUserIndexing3','allUserIndexing4','allUserIndexing5'));
		$this->predisLibrary->deleteKey(array('allUserIndexingTime1', 'allUserIndexingTime2', 'allUserIndexingTime3','allUserIndexingTime4','allUserIndexingTime5'));
		$this->predisLibrary->deleteKey(array('cachedLastUserId','indexingCachedUsers'));
	}

    public function excludeIndexingUserInCache(){
    	$this->_loadRedisLib();
    	$this->load->model('user/usermodel');
        $userModel = new UserModel;       

       	$userIds = $userModel->excludeUserInMigration();
      
        foreach ($userIds as $userId) {        
        	$userIdMap[$userId['userId']] =1;
        }

        $userIdMap = serialize($userIdMap);
    	$this->predisLibrary->addMembersToHash('cachedUserIndexingExclusion',array('userIdMap'=>$userIdMap));
    	_P('added in cache with key - cachedUserIndexingExclusion');
    
    }

	public function implicitResponseMigration_bkp($thread=0){
		$start = microtime(true);

		ini_set("memory_limit", '768M');
		ini_set("max_execution_time",-1);
		$this->initDB();
		$this->_loadRedisLib();
		
		$this->_setImplicitCronData($thread, 'time', microtime(true));

		error_log("==shiksha-IMP=========== <<<< migration starts >>>>===========");
		$chunkSize = 100000;
		
		$implicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$implicitCronData = json_decode($implicitCronData['implicitCronData'], true);

		$processed = $implicitCronData['thread'][$thread]['start'] + $implicitCronData['thread'][$thread]['processed'];
		$totalCount = $implicitCronData['thread'][$thread]['end'];
		if(empty($implicitCronData['thread'][$thread])){
			error_log("==shiksha== invalid thread");
			return;
		}
		
		error_log("==shiksha-IMP ===== >>> total unprocessed users === ".($totalCount-$processed));

		if(($totalCount-$processed) <= 0){
			error_log("==shiksha-IMP==  ===== >>> Script/Thread has already processed all the users <<< =====");
			return;
		}
		$lastIndex = $processed;
		$lastIndexForProfile = $processed;
		
		/*Loading dependencies */
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder   = new CourseBuilder();
        $courseRepo      = $courseBuilder->getCourseRepository();

        $this->load->library('listingBase/BaseAttributeLibrary');
		$BaseAttributeLibrary = new \BaseAttributeLibrary(); 

		$this->load->library('response/responseLib');
		$responseLibObj = new responseLib();

		error_log("==shiksha-IMP == Processing starts now ...");
		while($totalCount >= $processed){
			error_log("==shiksha== starting new chunk and getting data for it====");

			$sql = 'SELECT lms.userId, lms.submit_date, lms.listing_type_id, lms.action, lms.tracking_keyid FROM tempLMSTable lms WHERE lms.listing_type = "course" AND lms.userId > 0 ORDER BY lms.id ASC LIMIT '.$processed.', '.$chunkSize;

			$responseData = $this->dbHandle->query($sql)->result_array();

			$userIds = array();
			$listingIds = array();
			foreach ($responseData as $key => $responseValues) {
				$userIds[] = $responseValues['userId'];
				$listingIds[] = $responseValues['listing_type_id'];
			}

			$sql = 'SELECT sc.course_id, bal.value_name FROM shiksha_courses sc INNER JOIN shiksha_courses_type_information scti ON (sc.course_id = scti.course_id) LEFT JOIN base_attribute_list bal ON (scti.course_level = bal.value_id) WHERE sc.course_id IN ('.implode(', ', $listingIds).') AND scti.type="entry"';
			$temp = $this->dbHandle->query($sql)->result_array();
			unset($listingIds);

			$nationalLiveListings = array();
			foreach($temp as $key=>$listingId){
				$nationalLiveListings[$listingId['course_id']] = $listingId['value_name'];
			}

			unset($temp);
			$sql = 'SELECT UserId, educationLevel FROM tUserPref WHERE educationLevel IS NOT NULL AND UserId IN ('.implode(', ', $userIds).')';
			unset($userIds);
			$temp = $this->dbHandle->query($sql)->result_array();

			$userEduLevel = array();
			foreach($temp as $key=>$userData){
				$userEduLevel[$userData['UserId']] = $userData['educationLevel'];
			}
			unset($temp);

			$index = 0;
			$checkCount = ($chunkSize/5);

			$temp_response_queue = array();
			$user_response_profile = array();
			error_log("==shiksha== response data count ==".count($responseData));
			foreach ($responseData as $key => $responseValues) {
				if($index%$checkCount == 0 && $index){
					
					$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
					$temp_response_queue = array();
					$user_response_profile = array();
					$processed+=$jump;
					error_log("==shiksha== processed ===".$processed.' === jump ==='.$jump.'  == total count = '.$totalCount);
					$this->_jumpImplicitProcessedCount($thread, $jump);
					$jump = 0;
				}

				$index++;
				if(empty($nationalLiveListings[$responseValues['listing_type_id']]) || ($nationalLiveListings[$responseValues['listing_type_id']] != $userEduLevel[$responseValues['userId']] && $nationalLiveListings[$responseValues['listing_type_id']] != 'None') || !isset($userEduLevel[$responseValues['userId']])){
					$jump++;
					continue;
				}

				$lastIndex++;

				$temp = array();
				$temp['id'] = $lastIndex;
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['listing_type'] = 'course';
				if(!empty($responseValues['tracking_keyid'])){
					$temp['tracking_key'] = $responseValues['tracking_keyid'];
				}else{
					$temp['tracking_key'] = NULL;
				}
				$temp['is_response_made'] = 'yes';
				$temp['is_mail_sent'] = 'yes';
				$temp['action_type'] = $responseValues['action'];
				$temp['submit_time'] = $responseValues['submit_date'];

				$courseObj = $courseRepo->find($responseValues['listing_type_id'], array('basic', 'course_type_information', 'eligibility'));
				$courseData = Modules::run("response/Response/getClientCourseDataByCourseObj", $courseObj);

				$stream_id = $courseData['stream_id'];
				$substream_id = $courseData['substream_id'];
				$baseCourses = $courseData['baseCourses'];
				$subStreamSpecMapping = $courseData['subStreamSpecMapping'];
				$mode = $courseData['mode'];

				if(empty($stream_id)){
					$jump++;
					continue;
				}

				$temp_response_queue[] = $temp;

				$temp = array();
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['stream_id'] = $stream_id;
				$temp['queue_id'] = $lastIndex;
				$temp['education_level'] = $nationalLiveListings[$responseValues['listing_type_id']];
				$temp['submit_date'] = $responseValues['submit_date'];

				$user_profile_response = $responseLibObj->getDataForResponseProfile($stream_id, $substream_id, $baseCourses, $subStreamSpecMapping, $mode);

				$temp['status'] = 'live';

				foreach ($user_profile_response as $key => $value) {
					$lastIndexForProfile++;
					$temp['id'] = $lastIndexForProfile;
					$temp['user_profile'] = $value;
					$temp['substream_id'] = $key;
					$user_response_profile[] = $temp;
				}
				
	        	$jump++;
			}

			if(!empty($temp_response_queue)){
				$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
				$processed+=$jump;
				$this->_jumpImplicitProcessedCount($thread, $jump);
				$temp_response_queue = array();
				$user_response_profile = array();
				$jump = 0;
			}

			error_log("==shiksha== ==== >>> processed Count = ".$processed);
			error_log("==shiksha-IMP ==== >>> percentage processed = ".(($processed/$totalCount)*100)."%");

			if($processed >= $totalCount){
				break;
			}
		}

		error_log("==shiksha== updating multiple interest logic");
		// $this->updateDuplicateEntriesInResponse();

		error_log("==shiksha== resetting session id ===");
		$sql = 'UPDATE temp_response_queue SET visitor_session_id=NULL';
		$this->dbHandle->query($sql);

		$ttime = (microtime(true)-$start);
		error_log("==shiksha-IMP ==== Time Taken ====".$ttime.' Seconds');
		$this->predisLibrary->addMembersToHash('Implicit-cron-execution-time',array('time-in-seconds'=>$ttime));
		$this->_setImplicitCronData($thread, 'executionTime', (microtime(true)-$start));
		error_log("==shiksha-IMP ========= <<<< migration Ends of thread ".$thread.">>>>===========");
	}


	public function implicitResponseMigrationWOT(){
		$start = microtime(true);

		ini_set("memory_limit", '4048M');
		ini_set("max_execution_time",-1);
		$this->initDB();
		$this->_loadRedisLib();
		$this->predisLibrary->addMembersToHash('implicitCronTime', array('time'=>$start));
		error_log("==shiksha-IMP=========== <<<< migration starts >>>>===========");
		$chunkSize = 100000;

		$this->_setProcessedCount('implicitResponseProcessedCount');
		
		error_log("==shiksha== counting number of national users ");
		$totalCount = 0;
		$sql = 'SELECT COUNT(*) AS count FROM tempLMSTable lms WHERE lms.listing_type = "course" AND lms.userId > 0';
		$totalCount = $this->dbHandle->query($sql)->result_array();
		$totalCount = $totalCount[0]['count'];
		
		error_log("==shiksha-IMP ===== >>> total unprocessed users === ".($totalCount-$this->processed));

		$this->predisLibrary->addMembersToHash('implicitResponseTotalCount', array('Total_Records'=>($totalCount)));

		if(($totalCount-$this->processed) <= 0){
			error_log("==shiksha-IMP==  ===== >>> Script has already unprocessed all users <<< =====");
			return;
		}

		/*Loading dependencies */
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder   = new CourseBuilder();
        $courseRepo      = $courseBuilder->getCourseRepository();

        $this->load->library('listingBase/BaseAttributeLibrary');
		$BaseAttributeLibrary = new \BaseAttributeLibrary(); 

		$this->load->library('response/responseLib');
		$responseLibObj = new responseLib();

		error_log("==shiksha-IMP == Processing starts now ...");
		while($totalCount >= $this->processed){
			error_log("==shiksha== starting new chunk and getting data for it====");

			$sql = 'SELECT lms.userId, lms.submit_date, lms.listing_type_id, lms.action, lms.tracking_keyid FROM tempLMSTable lms WHERE lms.listing_type = "course" AND lms.userId > 0 ORDER BY lms.id ASC LIMIT '.$this->processed.', '.$chunkSize;

			$responseData = $this->dbHandle->query($sql)->result_array();
			// error_log("==shiksha== time table to extract tempLMSTable data = ".(microtime(true) - $chunkTime));

			$userIds = array();
			$listingIds = array();
			foreach ($responseData as $key => $responseValues) {
				$userIds[] = $responseValues['userId'];
				$listingIds[] = $responseValues['listing_type_id'];
			}

			$sql = 'SELECT sc.course_id, bal.value_name FROM shiksha_courses sc INNER JOIN shiksha_courses_type_information scti ON (sc.course_id = scti.course_id) LEFT JOIN base_attribute_list bal ON (scti.course_level = bal.value_id) WHERE sc.course_id IN ('.implode(', ', $listingIds).')';
			$temp = $this->dbHandle->query($sql)->result_array();
			unset($listingIds);

			$nationalLiveListings = array();
			foreach($temp as $key=>$listingId){
				$nationalLiveListings[$listingId['course_id']] = $listingId['value_name'];
			}

			unset($temp);
			$sql = 'SELECT UserId, educationLevel FROM tUserPref WHERE educationLevel IS NOT NULL AND UserId IN ('.implode(', ', $userIds).')';
			unset($userIds);
			$temp = $this->dbHandle->query($sql)->result_array();

			$userEduLevel = array();
			foreach($temp as $key=>$userData){
				$userEduLevel[$userData['UserId']] = $userData['educationLevel'];
			}
			unset($temp);

			$index = 0;
			$checkCount = ($chunkSize/5);

			$lastIndex = 0;
			$jump = 0;
			$sql = 'select id from temp_response_queue order by 1 desc limit 1';
			$temp = $this->dbHandle->query($sql)->result_array();
			$lastIndex = empty($temp[0]['id'])? 0 : $temp[0]['id'];

			$temp_response_queue = array();
			$user_response_profile = array();
			foreach ($responseData as $key => $responseValues) {
				if($index%$checkCount == 0 && $index){
					$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
					$temp_response_queue = array();
					$user_response_profile = array();
					// _p($jump); die;
					$this->_jumpProcessedCount($jump, 'implicitResponseProcessedCount');
					$jump = 0;
					error_log("==shiksha== chunk processed in % ===".(($index/$chunkSize)*100). "%");
				}

				$index++;
				if(empty($nationalLiveListings[$responseValues['listing_type_id']]) || ($nationalLiveListings[$responseValues['listing_type_id']] != $userEduLevel[$responseValues['userId']] && $nationalLiveListings[$responseValues['listing_type_id']] != 'None')){
					// $this->_incrementProcessedCount('implicitResponseProcessedCount');
					$jump++;
					continue;
				}

				$lastIndex++;

				$temp = array();
				$temp['id'] = $lastIndex;
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['listing_type'] = 'course';
				if(!empty($responseValues['tracking_keyid'])){
					$temp['tracking_key'] = $responseValues['tracking_keyid'];
				}
				$temp['is_response_made'] = 'yes';
				$temp['is_mail_sent'] = 'yes';
				$temp['action_type'] = $responseValues['action'];
				$temp['submit_time'] = $responseValues['submit_date'];

				$temp_response_queue[] = $temp;

				$temp = array();
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['queue_id'] = $lastIndex;
				$temp['education_level'] = $userEduLevel[$responseValues['userId']];
				$temp['submit_date'] = $responseValues['submit_date'];
				$courseObj = $courseRepo->find($responseValues['listing_type_id'], array('basic', 'course_type_information'));
				$courseData = Modules::run("response/Response/getClientCourseDataByCourseObj", $courseObj);

				$temp['stream_id'] = $courseData['stream_id'];
				$substream_id = $courseData['substream_id'];
				$baseCourses = $courseData['baseCourses'];
				$subStreamSpecMapping = $courseData['subStreamSpecMapping'];
				$mode = $courseData['mode'];


				$user_profile_response = $responseLibObj->getDataForResponseProfile($temp['stream_id'], $substream_id, $baseCourses, $subStreamSpecMapping, $mode);
		
				$temp['status'] = 'live';

		
				$temp['education_level'] = $courseLevel;
				foreach ($user_profile_response as $key => $value) {
					$temp['user_profile'] = $value;
					$temp['substream_id'] = $key;
					$user_response_profile[] = $temp;
				}
				
	        	$jump++;
	        	// $this->_incrementProcessedCount('implicitResponseProcessedCount');
			}
			$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
			error_log("==shiksha== ==== >>> processed Count = ".$this->processed);
			error_log("==shiksha-IMP ==== >>> percentage processed = ".(($this->processed/$totalCount)*100)."%");

			if($this->processed >= $totalCount){
				break;
			}
		}

		error_log("==shiksha== updating multiple interest logic");
		// $this->updateDuplicateEntriesInResponse();

		error_log("==shiksha== resetting session id ===");
		$sql = 'UPDATE temp_response_queue SET visitor_session_id=NULL';
		$this->dbHandle->query($sql);

		$ttime = (microtime(true)-$start);
		error_log("==shiksha-IMP ==== Time Taken ====".$ttime.' Seconds');
		$this->predisLibrary->addMembersToHash('Implicit-cron-execution-time',array('time-in-seconds'=>$ttime));
		error_log("==shiksha-IMP ========= <<<< migration Ends >>>>===========");
	}

	function resetUSerProfileMappingCache(){
		$this->_loadRedisLib();
		$this->predisLibrary->deleteKey(array('LDBContactTracking1', 'LDBContactTracking2', 'LDBContactTracking3',  'LDBContactTracking4'));
		$this->predisLibrary->deleteKey(array('LDBContactTracking1startTime', 'LDBContactTracking2startTime', 'LDBContactTracking3startTime',  'LDBContactTracking4startTime'));
		$this->predisLibrary->deleteKey(array('LDBContactTracking1endTime', 'LDBContactTracking2endTime', 'LDBContactTracking3endTime',  'LDBContactTracking4endTime'));
	}

	function getUserProfileMappingStatus(){
		$thread= array(1,2,3,4);
    	$this->_loadRedisLib();
    	$data = '';
    	foreach ($thread as $threadId) {
    		$data = '';
    		$data = $this->predisLibrary->getAllMembersOfHashWithValue('LDBContactTracking'.$threadId);
    		$startTime = $this->predisLibrary->getAllMembersOfHashWithValue('LDBContactTracking'.$threadId.'startTime');
    		$endTime = $this->predisLibrary->getAllMembersOfHashWithValue('LDBContactTracking'.$threadId.'endTime');
    		_P('Thread Id- <b>'. $threadId.'</b>, count is <b>'.$data['pIdLDBTracking'].'</b>'.' started at <b>'.$startTime['startTime'].'</b>'.' and ended at <b>'.$endTime['endTime'].'</b>');
    		unset($data);
    		unset($startTime);
    	}
	}



	function migrateUserProfileClientMapping($threadId){
		ini_set("memory_limit", '1024M');
		ini_set("max_execution_time",-1);
		
		if($threadId<1){
			_P('no thread id found');
			die;
		}

		$this->initDB();
		$this->_loadRedisLib();
		$scriptType ='LDBContactTracking'.$threadId;
		$itr =0;
		$threadCounter = 'thread'.$threadId;

		$fiftyLakh = 18868979 ;						//last id in table LDBLeadContactedTracking
		$lowerLimit = ($threadId-1)*$fiftyLakh ;
		$upperLimit = ($threadId-1)*$fiftyLakh + $fiftyLakh;


		error_log('==================== crons started at --- '.date("h:i:sa"));
		$this->predisLibrary->addMembersToHash($scriptType.'startTime',array('startTime'=>date("h:i:sa")));


		$totaluserSql = "select count(*) as count from LDBLeadContactedTracking";
		$totalContactedUser = $this->dbHandle->query($totaluserSql)->result_array();
		$totalContactedUser = $totalContactedUser[0]['count'];

		error_log('========================= total rows to migrate '.$totalContactedUser);

		$lastProcessedId = $this->predisLibrary->getAllMembersOfHashWithValue($scriptType);

		$lastProcessedId = $lastProcessedId['pIdLDBTracking'];

		
		if($lastProcessedId == '' || !isset($lastProcessedId)){
			$lastProcessedId =$lowerLimit;
		}
		
		error_log('################ counter started ');

		while($itr < $totalContactedUser){

			$sql = "select id,userId,clientId, contactType,contactDate,activity_flag from LDBLeadContactedTracking where id > ".$lastProcessedId." and id >".$lowerLimit." and id < ".$upperLimit."  limit $itr,20000";
			
			$contactedData = $this->dbHandle->query($sql)->result_array();
			
			if(empty($contactedData)){		//stop migration if last id processed
				_P('no records to process in table. Ending cron');
				break;
			}
			
			$this->processContactData($contactedData,$scriptType);
			unset($contactedData);
			$itr +=20000;

			error_log(date("h:i:sa").' --  ============= processing counter for thread id '.$threadCounter.' ---- '.$itr);
					
		}

		$this->predisLibrary->addMembersToHash($scriptType.'endTime',array('endTime'=>date("h:i:sa")));
		error_log('====================  crons end at --- '.date("h:i:sa"));
	}

	function processContactData($contactedData,$scriptType){
		$userIdsArray = array();
		$desiredcourseMap = array();
		$desiredCourses = array();

		$this->_loadRedisLib();
		
		foreach ($contactedData as $value) {
			$userIdsArray[] = $value['userId'];
		}

		if(empty($userIdsArray) || count($userIdsArray) == 0){
			_P('no user to process');
			return;
		}
		$desiredcourseData = $this->getUserDesiredCourse($userIdsArray);
		

		foreach ($desiredcourseData as $value) {


			if($value['desiredcourse'] >0){

				$desiredcourseMap[$value['userId']] = $value['desiredcourse'];
				$desiredCourses [] = $value['desiredcourse'];
			}
		}
		
		if(count($desiredcourseMap) == 0){
			return;
		}

		$desiredCoursesUnique = $desiredCourses;
		//$desiredCoursesUnique = array_unique($desiredCourses);
		
		unset($desiredCourses);
		
		$userHierarchyDataMap = $this->_getOldCourseSpecializationToNewMapping($desiredCoursesUnique);
		unset($desiredCoursesUnique);
		
		$sqlInsertProfile = "insert IGNORE into UserProfileMappingToClient (userId,clientId,StreamId,SubStreamId,submitTime,contactType,FlagType) values";


		foreach ($contactedData as $value) {
			
				$userHierarchyData = $userHierarchyDataMap[$desiredcourseMap[$value['userId']]];
				

				$desiredcourse = $userHierarchyData['oldspecializationid'];

				if( ($desiredcourse == 2 || $desiredcourse == 52) && $value['activity_flag'] =='LDB' ){
					$value['activity_flag'] ='LDB_MR';
				}

				if( ($desiredcourse == 2 || $desiredcourse == 52) && $value['activity_flag'] =='SA' ){
					$value['activity_flag'] ='SA_MR';
				}

				/*$sqlInsertProfile = "insert into UserProfileMappingToClient (userId,clientId,StreamId,SubStreamId,submitTime,contactType,FlagType) values";*/
				if($userHierarchyData['stream_id']){
					 $sqlInsertProfile .= "(".$value['userId'].",".$value['clientId'].",".$userHierarchyData['stream_id'].",".$userHierarchyData['substream_id'].",'".$value['contactDate']."','".$value['contactType']."','".$value['activity_flag']."') ,";
					
					
				}


				//$this->predisLibrary->addMembersToHash($scriptType,array('pIdLDBTracking'=>$value['id']));

		}

				$sqlInsertProfile = substr($sqlInsertProfile, 0,-1);

				$this->dbHandle->query($sqlInsertProfile);		

				$this->predisLibrary->addMembersToHash($scriptType,array('pIdLDBTracking'=>$value['id']));

	}

	function getUserDesiredCourse($userIdsArray){
		$this->initDB();

		$sql = "select desiredcourse,userId  from tUserPref where (ExtraFlag != 'studyabroad' or ExtraFlag is null) and desiredcourse !=0 and  userId in (".implode(', ', $userIdsArray).")";

		$desiredcourse = $this->dbHandle->query($sql)->result_array();

		return $desiredcourse;
		//return $desiredcourse[0]['desiredcourse'];
	}

	function getSearchAgentsWithMultipleStreams(){
		ini_set('memory_limit','2048M');
		ini_set("max_execution_time",-1);

		$this->initDB();

		/*get serach agents with multiple desiredcourse*/
		$sql = 'select searchAlertId from SAMultiValuedSearchCriteria where keyname ="desiredcourse" group by searchAlertId having count(keyname ="desiredcourse") >1';
		
		$agents = $this->dbHandle->query($sql)->result_array();

		foreach ($agents as $value) {
			$allAgents[] = $value['searchAlertId'];
		}
		unset($agents);

		/*get search agent desiredcourse value*/
		$agentDesiredCourseSql = "select value as desiredcourse,searchAlertId from SAMultiValuedSearchCriteria where keyname ='desiredcourse' and searchAlertId in (".implode(', ', $allAgents).")";
		$agentDesiredCourseData = $this->dbHandle->query($agentDesiredCourseSql)->result_array();
		unset($allAgents);

		/*create map with search agent and desiredcourse*/
		foreach ($agentDesiredCourseData as $data) {
			$searchAgentMap[$data['searchAlertId']][] = $data['desiredcourse'];
			$allDesiredCourses[] = $data['desiredcourse'];
		}

		unset($agentDesiredCourseData);

		/*get desiredcourse mapping with streamId*/
		$desiredCourseStreamMap = $this->_getOldCourseSpecializationToNewMapping($allDesiredCourses);
		
		$streamId = array();
		$excludeSearchAgent = array();

		foreach ($searchAgentMap as $searchAgentId => $desiredcourseArray) {
			foreach ($desiredcourseArray as $desiredcourseId) {
				if($desiredCourseStreamMap[$desiredcourseId]['stream_id']){
					$streamId[] = $desiredCourseStreamMap[$desiredcourseId]['stream_id'];
				}
			}

			$streamId = array_unique($streamId);

			if (count($streamId)>1 || count($streamId) == 0) {		//include both - multiple mapping and no mapping to Re-Cat
				$excludeSearchAgent[] =$searchAgentId;
			}

			unset($streamId);
		}

		return $excludeSearchAgent;
		
	}


	function migrateSearchAgentDisplayData(){
		ini_set('memory_limit','2048M');
		ini_set("max_execution_time",-1);

		$this->initDB();

		error_log('============= get all exclude search agents');
		/*exclude search agents*/
		$excludeSearchAgent = $this->getSearchAgentsWithMultipleStreams();

		$agentDisplayDataSQL = "select id,searchagentid,displaydata,inputdata from SASearchAgentDisplayData where searchagentid not in (".implode(', ', $excludeSearchAgent).")";
		$agentDisplayData = $this->dbHandle->query($agentDisplayDataSQL)->result_array();

		
		foreach ($agentDisplayData as $agent) {
			$allSearchAgentIds[] = $agent['searchagentid'];
		}
		
		error_log('============= total rows to migrate '.count($allSearchAgentIds));

		error_log('============= get all desired courses');
		
		/*get All desiredcourse*/
		$allSearchAgentDesiredCourse = "select value,searchalertid from SAMultiValuedSearchCriteria where keyname ='desiredcourse' and searchalertid  in (".implode(', ', $allSearchAgentIds).")";

		$desiredCourseIds = $this->dbHandle->query($allSearchAgentDesiredCourse)->result_array();
		unset($allSearchAgentIds);

		foreach ($desiredCourseIds as $desiredcourseid) {
			$allDesiredCourse[] = $desiredcourseid['value'];
			$searchAgentMap[$desiredcourseid['searchalertid']][] = $desiredcourseid['value'];
		}

		unset($desiredCourseIds);
		error_log('============= get all hierarchy Data Map');
		
		$allDesiredCourse = array_unique($allDesiredCourse);
		$hierarchyDataMap = $this->_getOldCourseSpecializationToNewMapping($allDesiredCourse);
		unset($allDesiredCourse);
		

		$itr =0;
		foreach ($agentDisplayData as $data) {						
			
			error_log($itr.' =========== agent ids '.$data['searchagentid']);
			$displaydata ='';
			$inputdata ='';

			if($searchAgentMap[$data['searchagentid']] == '' || empty($searchAgentMap[$data['searchagentid']]) || $searchAgentMap[$data['searchagentid']] <0 ){
				continue;
			}

			/*migrate display data*/
			$displaydata = json_decode(base64_decode($data['displaydata']),true);
			$displaydata = $this->SAformatDisplayData($displaydata,$searchAgentMap,$data['searchagentid'],$hierarchyDataMap);	

			if($displaydata['error']){
				continue;
			}
			
			/*migrate input data*/
			$inputdata = json_decode(base64_decode($data['inputdata']),true);
			$inputdata = $this->SAformatDisplayData($inputdata,$searchAgentMap,$data['searchagentid'],$hierarchyDataMap);

			if($inputdata['error']){
				continue;
			}

			/* migrate city-locality data*/
			$displaydata = $this->formatCityLocalityData($displaydata, $data['searchagentid']);

			/*migrate input html*/
			$inputHTML = $this->portingAgentView($displaydata);
			$inputHTML = base64_encode($inputHTML);

			$inputdata = base64_encode(json_encode($inputdata));
			$displaydata = base64_encode(json_encode($displaydata));

			$this->updateSADisplayData($data['id'],$displaydata,$inputdata,$inputHTML);
			$itr++;
		}

		
	}

	private function portingAgentView($displayArray){
		$searchVariableArray = array();
			if(isset($displayArray['streamData']) && $displayArray['streamData']!="") {
				array_push($searchVariableArray,"<b>Stream</b>: ".implode(', ', array_values($displayArray['streamData'])));
			}
			if(isset($displayArray['subStreamData']) && $displayArray['subStreamData']!="") {
				array_push($searchVariableArray,"<b>Substream</b>: ".implode(', ', array_values($displayArray['subStreamData'])));
			}
			if(isset($displayArray['specializationData']) && $displayArray['specializationData']!="") {
				array_push($searchVariableArray,"<b>Specializations</b>: ".implode(', ', array_values($displayArray['specializationData'])));
			}
			if(isset($displayArray['courseData']) && $displayArray['courseData']!="") {
				array_push($searchVariableArray,"<b>Base Course</b>: ".implode(', ', array_values($displayArray['courseData'])));
			}

			if(isset($displayArray['MRLocation']) && $displayArray['MRLocation']!="") {
				array_push($searchVariableArray,"<b>Preferred Location</b>: ".$displayArray['MRLocation']);
			}

			if(isset($displayArray['currentLocation']) && $displayArray['currentLocation']!="") {
				array_push($searchVariableArray,"<b>Current Location</b>: ".$displayArray['currentLocation']);
			}


			if(isset($displayArray['modeDataDisplay']) && $displayArray['modeDataDisplay'] != '') {
				array_push($searchVariableArray, "<b>Mode</b>: ".$displayArray['modeDataDisplay']);
			}

			if(isset($displayArray['cityLocalityDisplay']) && $displayArray['cityLocalityDisplay']!="") {
				array_push($searchVariableArray,"<b>Current Location</b>: ".$displayArray['cityLocalityDisplay']);
			}
			
			if(isset($displayArray['exams']) && $displayArray['exams']!="") {
				array_push($searchVariableArray,"<b>Exams</b>: ".implode(', ', array_values($displayArray['exams'])));
			}
			if(isset($displayArray['workex']) && $displayArray['workex']!="") {
				array_push($searchVariableArray,"<b>Work Experience</b>: ".$displayArray['workex']);
			}
			
			if(isset($displayArray['matchedCourses']) && $displayArray['matchedCourses'] != "") {
			 	/*array_push($searchVariableArray,"<b>Matching for</b>: ".implode(', ', array_values($displayArray['matchedCourses'])));*/

			 	$str = '';
			 	foreach ($displayArray['matchedCoursesInstitute'] as $courseId => $InstiName) {
			 		$str .= ' '.$InstiName.' - '.$displayArray['matchedCourses'][$courseId].',';
			 	}

			 	$str = substr($str, 0,-1);
			 	
			 	array_push($searchVariableArray,"<b>Matching for</b>: ".$str);
			}
			
			if(isset($displayArray['prefLocation']) && $displayArray['prefLocation']!="") {
				array_push($searchVariableArray,"<b>Preferred Location</b>: ".$displayArray['prefLocation']);
			}
			
			if(isset($displayArray['examTaken']) && $displayArray['examTaken']!="") {
				array_push($searchVariableArray,"<b>Exams </b>: ".$displayArray['examTaken']);
			}
		if($new_course_name == 'Study Abroad') {
	            if(isset($displayArray['DesiredCourseName']) && !empty($displayArray['DesiredCourseName'])){
	                array_push($searchVariableArray,"<b>Course</b>: ". implode(', ', array_unique($displayArray['DesiredCourseName'])));
	            }
				
				if(isset($displayArray['abroadSpecializations']) && !empty($displayArray['abroadSpecializations'])){
	                array_push($searchVariableArray,"<b>Specialization</b>: ". implode(', ', array_unique($displayArray['abroadSpecializations'])));
	            }
				
	            if(isset($displayArray['DesiredCourseLevels']) && !empty($displayArray['DesiredCourseLevels'])){
	                $courseLevels = array();
	                $courseLevelsMap = array('UG'=> 'Bachelors','PG' =>'Masters','PhD' => 'PhD');
	                foreach($displayArray['DesiredCourseLevels'] as $desiredCourselevel) {
	                    $courseLevels[] = $courseLevelsMap[$desiredCourselevel];
	                }
	                array_push($searchVariableArray,"<b>Course Level</b>: ". trim(implode(', ', $courseLevels),', '));
	            }
				if($displayArray['budget']) {
					array_push($searchVariableArray,"<b>Budget</b>: ". trim(implode(', ', $displayArray['budget']),', '));
				}
				if($displayArray['passport']) {
					array_push($searchVariableArray,"<b>Passport</b>: ". $displayArray['passport']);
				}
				if($displayArray['planToStart']) {
					array_push($searchVariableArray,"<b>Plan to Start</b>: ". trim(implode(', ', $displayArray['planToStart']),', '));
				}
			}
			$searchParamTitle = 'Searched Criteria: '.implode("&nbsp;<i>|</i>&nbsp;",$searchVariableArray);
			return $searchParamTitle;
	}

	private function SAformatDisplayData($displaydata,$searchAgentMap,$agentId,$hierarchyDataMap){
		
		$finalDisplayData = array();

		$desiredcourseIdArray= $searchAgentMap[$agentId];

		foreach ($desiredcourseIdArray as $desiredcourseId) {
			$newDisplayData = array();
			$hierarchyData = array();
			$hierarchyData = $hierarchyDataMap[$desiredcourseId];
			
			$newDisplayData = $displaydata;
			unset($newDisplayData['attributesValueNamePair']);
			unset($newDisplayData['subStreamData']);
			unset($newDisplayData['specializationData']);
			unset($newDisplayData['courseData']);
			unset($newDisplayData['streamData']);	
			unset($newDisplayData['subStreamSpecializationMapping']);	
			unset($newDisplayData['attributeValues']);	
			
			$newDisplayData['stream'] = $hierarchyData['stream_id'];
			$newDisplayData['subStream'] = array($hierarchyData['substream_id']);
			$newDisplayData['specializationId'] = array($hierarchyData['specialization_id']);
			$newDisplayData['courseId'] = array($hierarchyData['base_course_id']);

			if($hierarchyData['education_type']>0){
				$newDisplayData['attributeValues'] = array($hierarchyData['education_type']);
			}

			if($hierarchyData['delivery_method'] >0 ){	
				$newDisplayData['attributeValues'][] = $hierarchyData['delivery_method'];
			}

			if($hierarchyData['education_type'] == 21 && ($hierarchyData['delivery_method'] ==0 || empty($hierarchyData['delivery_method']) ) ){
				$newDisplayData['attributeValues'] = array(21,33,34,35,36,37,39);
			}

			if($hierarchyData['stream_id'] <1){
				$returnError = array('error'=>true);
				return $returnError;
			}


			/*code to get name of all the Ids*/
			$this->load->builder('listingBase/ListingBaseBuilder');
	        $listingBase = new \ListingBaseBuilder();
	        $HierarchyRepository = $listingBase->getHierarchyRepository();
	        $BaseCourseRepository = $listingBase->getBaseCourseRepository();
	        $this->load->library('listingBase/BaseAttributeLibrary');
	        $baseAttributeLibrary = new BaseAttributeLibrary();
		    

			if($newDisplayData['stream'] != '' && $newDisplayData['stream'] >0){
		        $streamObj = $HierarchyRepository->findStream($newDisplayData['stream']);
		     	
		        $streamData = $streamObj->getObjectAsArray();
		        
		       /* $newDisplayData['streamData']['name'] = $streamData['name'];
		        $newDisplayData['streamData']['stream_id'] = $newDisplayData['stream'];*/
		        $newDisplayData['streamData'][$newDisplayData['stream']] = $streamData['name'];
		    }

		    if($newDisplayData['subStream'] != ''){
		        $substreamIdsArray = array();
		        $subStreamData = array();
		        $substreamIdsArray =$newDisplayData['subStream'];
		      
		      	if($substreamIdsArray && $substreamIdsArray>0 && $substreamIdsArray[0]>0){
			        $subStreamObj = $HierarchyRepository->findMultipleSubstreams($substreamIdsArray);
			        foreach ($subStreamObj as $key => $value) {
			            $value = $value->getObjectAsArray();
			            //$subStreamData[] = $value['name'];
			        	$newDisplayData['subStreamData'][$value['substream_id']] = $value['name'];		//check for multiple sub stream
			        }
		      	}
		    }

		    if($newDisplayData['specializationId'] != '' && $newDisplayData['specializationId'][0]>0){
		        $specializationIdsArray = array();
		        $specializationData = array();
		        $specializationIdsArray =  $newDisplayData['specializationId'];

		        $specializationObj = $HierarchyRepository->findMultipleSpecializations($specializationIdsArray);
		        foreach ($specializationObj as $key => $value) {
		            $value = $value->getObjectAsArray();
		            

		            if($value['primary_substream_id'] >0 ){
		            	$newDisplayData['subStreamSpecializationMapping'][$value['primary_substream_id']][] = $value['specialization_id'];
		            }else{
		            	$newDisplayData['ungroupedSpecializations'][] = $value['specialization_id'];
		            }

		        	$newDisplayData['specializationData'][$value['specialization_id']] = $value['name'];

		        }
		        
		    }

		    if($newDisplayData['courseId'] != '' &&  $newDisplayData['courseId'][0]>0){
		        $coursesArray = array();
		        $courseData = array();
		        $coursesArray = $newDisplayData['courseId'];
		        $coursesObj = $BaseCourseRepository->findMultiple($coursesArray);
		        foreach ($coursesObj as $key => $value) {
		            $value = $value->getObjectAsArray();      
		       		$newDisplayData['courseData'][$value['base_course_id']] = $value['name']; ;
		        }
		        
		    }    
		    
		    if($newDisplayData['attributeValues'] != '' && $newDisplayData['attributeValues'][0]>0){
		        $parentChildrenMapping = array();
		        $modeIdsArray = $newDisplayData['attributeValues'];
		        $attributeParentValueIdMapping = $baseAttributeLibrary->getParentValueIdByValueId($modeIdsArray);	                
		        
		        foreach ($attributeParentValueIdMapping as $key => $parentValues) {
		            foreach ($parentValues as $parentValue) {
		                if($parentValue == 20 && $key == 33) {
		                    $parentChildrenMapping[$parentValue][] = '';
		                } else if($parentValue == 21 && !in_array($key,array(33,34,35,36,37,39))) {
		                    $parentChildrenMapping[$parentValue][] = '';
		                } else {
		                    if(!in_array($parentValue, $modeIdsArray)){
		                        $modeIdsArray[] = $parentValue;
		                    }
		                    $parentChildrenMapping[$parentValue][] = $key;
		                }
		            }
		        }
		        
		      
				$attributesValueNamePair = $baseAttributeLibrary->getValueNameByValueId($modeIdsArray);

		       	$newDisplayData['attributesValueNamePair'] =  $attributesValueNamePair;
		        

		        foreach ($attributesValueNamePair as $key => $value) {
		            if(array_key_exists($key, $parentChildrenMapping)){
		                $modeData[$value] = '';
		                foreach ($parentChildrenMapping[$key] as $childKey => $childId) {
		                    if($childId){
		                        $childArray[] = $attributesValueNamePair[$childId];
		                    }
		                }
		                asort($childArray);
		                $modeData[$value] = implode(', ',$childArray);
		            }
		            else if(array_key_exists($key, $attributeParentValueIdMapping)){
		                continue;
		            }
		            else{
		                $modeData[$value] = '';
		            }
		        }

		        foreach ($modeData as $key => $value) {
		        	if($value){
		        		$newDisplayData['modeDataDisplay'] = $key.' - '.$value;
		        	}else{
		        		$newDisplayData['modeDataDisplay'] = $key;
		        	}
		        }

		     }		     

		     foreach ($newDisplayData as $key => $value) {

		     	if(is_array($value) && empty($finalDisplayData[$key])){	
		     		$finalDisplayData[$key] = $value;
		     		
		     		$finalDisplayData[$key] = array_unique($finalDisplayData[$key]);

		     	}else if(is_array($value) && (!empty($finalDisplayData[$key]) || count($finalDisplayData[$key])>0 ) ){

		     		if($key =='subStreamSpecializationMapping'){
		     			$tempKey = array_keys($value);
		     			$tempKey = $tempKey[0];

		     			if($finalDisplayData[$key][$tempKey]){
		     				$finalDisplayData[$key][$tempKey][] = $value[$tempKey][0];
		     			}else{
		     				$finalDisplayData[$key][$tempKey] = $value[$tempKey];
		     			}

		     		
		     		}else{
		     			$finalDisplayData[$key] = array_merge($finalDisplayData[$key],$value);
		     			$finalDisplayData[$key] = array_unique($finalDisplayData[$key]);
		     		}
		     		
		     	}else{
		     		$finalDisplayData[$key] = $value;
		     	}
		     	
		     
		     	/*if($key== 'streamData'){
		     		$finalDisplayData[$key][0] = $value['name'];
		     	}*/
		     }
		     
			}

		return $finalDisplayData;

	}

	private function getCityLocalityMapping($localityIdsArray = array()) {

		if(!empty($localityIdsArray)) {

			$localities = implode(',', $localityIdsArray);
			$mappingSql = "SELECT localityId, cityId FROM localityCityMapping WHERE 
							localityId in ( " . $localities . " ) AND status = 'live' ";

			$mappingQuery = $this->dbHandle->query($mappingSql)->result_array();
			$localityCityMappingArray = array();
			foreach ($mappingQuery as $result) {
				$localityCityMappingArray[$result['cityId']][] = $result['localityId']; 
			}
			
			return $localityCityMappingArray;

		}

	}

	private function formatCityLocalityData($displaydata, $searchagentid) {

		$this->load->model('ldbmodel');

		if(isset($displaydata['cityLocalityDisplay']) && $displaydata['cityLocalityDisplay'] != '') {
			return $displaydata;
		}

		$localitiesCitiesSql = "SELECT value, keyname FROM SAMultiValuedSearchCriteria WHERE searchAlertId = " . $searchagentid . " AND keyname IN ( 'Locality', 'currentlocality', 'currentlocation' ) " ;

		$localitiesCitiesQuery = $this->dbHandle->query($localitiesCitiesSql)->result_array();

		$citiesIdsArray  	= array();
		$localitiesIdsArray = array();
		foreach ($localitiesCitiesQuery as $result) {
			if($result['keyname'] == 'currentlocation') {
				$citiesIdsArray[] = $result['value']; 
			} else {
				$localitiesIdsArray[] = $result['value'];
			}
		}

		$localityCityMappingArray = array();
		if(!empty($localitiesIdsArray)){
			$localityCityMappingArray = $this->getCityLocalityMapping($localitiesIdsArray);
		}
		foreach ($citiesIdsArray as $cityId) {
			if(!array_key_exists($cityId, $localityCityMappingArray)) {
				$localityCityMappingArray[$cityId] = array();
			}
		}

		if(!empty($localityCityMappingArray)){
			$currentCities = array_keys($localityCityMappingArray);
		}

		if(!empty($currentCities)){
			$citiesIdValues = $this->ldbmodel->getCityNamesFromCityIds($currentCities); 
	        foreach ($citiesIdValues as $key => $value) {
	            $citiesIdValuesArray[$value['CityId']] = $value['CityName'];
	        }
	    } else {
	    	return $displaydata;
	    }

	    if(!empty($localityCityMappingArray)){	
	        foreach ($localityCityMappingArray as $cityId => $localitiesIdsArray) {
	        	if(!empty($localitiesIdsArray)){
	            	foreach ($localitiesIdsArray as $value) {
	            		$allLocalitiesArray[] = $value;
	            	}
	            }
	        }
	        $localitiesIdValues = $this->ldbmodel->getLocalitiesNamesFromLocalityIds($allLocalitiesArray);
	        foreach ($localitiesIdValues as $key => $value) {
	            $localitiesIdValuesArray[$value['localityId']] = $value['localityName'];
	        }
	    }

        $cityLocalityDisplay = '';
        foreach ($currentCities as $cityKey => $cityId) {

        	if($citiesIdValuesArray[$cityId] != ''){
            	$cityLocalityDisplay .= $citiesIdValuesArray[$cityId];

	            foreach ($localityCityMappingArray as $city => $localityArray) {

	                if($city == $cityId && !empty($localityArray)) {

	                    $cityLocalityDisplay .= ' - ';
	                    foreach ($localityArray as $key => $localityId) {
	                        $cityLocalityDisplay .= $localitiesIdValuesArray[$localityId].', ';
	                    }
	                    $cityLocalityDisplay = rtrim($cityLocalityDisplay, ", ");

	                }

	            }
            	$cityLocalityDisplay .= ', ';
            }

        }
        $cityLocalityDisplay = rtrim($cityLocalityDisplay, ", ");
        // error_log("#######cityLocalityDisplay ".print_r($cityLocalityDisplay,true));
        unset($displaydata['currentLocation']);
        $displaydata['cityLocalityDisplay'] = $cityLocalityDisplay;

        unset($citiesIdValues);
        unset($citiesIdValuesArray);
        unset($allLocalitiesArray);
        unset($localitiesIdValues);
        unset($localitiesIdValuesArray);
        unset($allCitiesArray);
        unset($currentCities);
        unset($currentLocalities);
        unset($cityLocalityDisplay);

        return $displaydata;

	}

	private function updateSADisplayData($id,$displaydata,$inputdata,$inputHTML){
		$this->initDB();

		$agentDisplayDataSQL = "update SASearchAgentDisplayData set displaydata = '".$displaydata."' , inputhtml='".$inputHTML."' , inputdata='".$inputdata."' where id =?";

		$displaydata = json_decode(base64_decode($displaydata),true);
		$inputdata = json_decode(base64_decode($inputdata),true);

		$agentDisplayData = $this->dbHandle->query($agentDisplayDataSQL,array($id));

	}

	private function _isActionOfViewedType($action){
		$viewedActions = array('CD-Client','CD.Client','CDM','Ckient','Cl','Cleint','CleintMailers','Clent','Clent Mailer','Clicked_on_SMS','Clie','Clien','Client','Client - CD','Client JNIT','Client KITM','Client M','Client mailer','Client Mailer - Appin','Client Mailer - IIMT','Client Mailer - Red Carpet','Client Mailer Aravali College','Client Mailer Cust Del SMU','Client Mailer IIMT','Client Mailer Koshys B School','Client Mailer LBIIHM','Client Mailer MAAC','Client Mailer MMU','Client Mailer QSB','Client Mailer Raffles University','Client Mailer-Confluence Edu','Client Mailer-FIIB','Client Mailer-IIBM','Client Mailer-IIPM','Client Mailer-ISB&M','Client Mailer-ISB&M><','Client Mailer-Sri Aurobindo Center','Client Mailer-TASMAC','Client Mailer-WLC','Client Mailer-Xcellon Institute','Client MailerMailer','Client Mailers','Client RIET Chittor','Client Srinivas Group','Client+Mailer','client-BSAU','Client-CD','Client-CD-PM','Client-CIMA','client-Symbiosis','client-TSM','Client6','ClientAIMS','ClientAPUjapan','ClientDBS','ClientGoenka','ClientIFB','ClientMailer','ClientMailer - Allterducation','ClientMailer - CREMA','ClientMailer-Engg','ClientMailer-Engg!RSTB','ClientMailer-Hospitality','ClientMailer-kol','ClientMailer-kol!RSTB','ClientMailer-MBA','ClientMailer-Media','ClientMailer-Medicine','ClientMITISBJ','Clientt','Ezetrix - Client','Institute_Viewed','MITSDE - Client','mobile_viewedListing','MOB_CD-Client','MOB_CD.Client','MOB_Cleint','MOB_Client','MOB_Client - CD','MOB_Client Mailer','MOB_Client-CD','MOB_Client-CD-PM','MOB_Client-CIMA','MOB_Client6','MOB_ClientDBS','MOB_Clientt','Mob_Viewed_Listing_Pre_Reg','reco_widget_mailer','Viewed_Listing','Viewed_Listing_Pre_Reg','vishwakarma - Client');

		if(in_array($action, $viewedActions)){
			return true;
		}

		return false;
	}


	public function updateDuplicateEntriesInResponse(){
		ini_set('memory_limit','4096M');
		ini_set("max_execution_time",-1);

		$this->initDB();

		error_log("==shiksha== crons starts ===");

		$processed = 0;
		$chunk = 1000000;
		while(1){

			$sql = 'SELECT user_id, listing_id, queue_id FROM user_response_profile WHERE status="live" ORDER BY id DESC LIMIT '.$processed.', '.$chunk;
			$responseData = $this->dbHandle->query($sql)->result_array();
			if(empty($responseData)){
				break;
			}
			// _p($responseData); die;
			error_log("==shiksha== unprocessed data in the chunk ===".count($responseData));
			error_log("==shiksha== processing... ===");

			$tempData = array();
			$dublicateEntries = array();
			foreach($responseData as $key=>$value){
				if(!empty($tempData[$value['user_id']]) && in_array($value['listing_id'], $tempData[$value['user_id']]['listing_id']) && !in_array($value['queue_id'], $tempData[$value['user_id']]['queue_id'])){
					$dublicateEntries[] = $value['queue_id'];
				}
				$tempData[$value['user_id']]['queue_id'][] = $value['queue_id'];
				$tempData[$value['user_id']]['listing_id'][] = $value['listing_id'];
			}

			if(!empty($dublicateEntries)){
				$sql = 'UPDATE user_response_profile SET status="draft" WHERE queue_id IN ('.implode(', ', $dublicateEntries).')';
				$this->dbHandle->query($sql);
			}

			$processed = $processed + $chunk;
		}

		$sql = 'UPDATE temp_response_queue SET visitor_session_id=NULL';
		$this->dbHandle->query($sql);

		error_log("==shiksha== crons ends ===");

	}

	public function cacheAllCourses(){
		$start = microtime(true);
		ini_set('memory_limit','4096M');
		ini_set("max_execution_time",-1);

		$index = 0;
		$this->initDB();
		error_log("==shiksha== Caching... ");

		$this->load->builder("nationalCourse/CourseBuilder");
        $builder   = new CourseBuilder();
        $repo      = $builder->getCourseRepository();

		$sql = 'SELECT course_id FROM shiksha_courses WHERE status="live"';
		$nationaLiveData = $this->dbHandle->query($sql)->result_array();
		$totalCourses = count($nationaLiveData);
		foreach ($nationaLiveData as $key => $course) {
			if(($index*10)%$totalCourses == 0){
				error_log("==shiksha== ".(($index*100)%$totalCourses).'% processed.. ');
			}
	        $courseObj = $repo->find($course['course_id'], array('basic', 'course_type_information', 'eligibility', 'location'));

	        $courseObj->getLocations();
	        $courseObj->getEducationType();
	        $courseObj->getCourseTypeInformation();
	        unset($courseObj);
	        $index++;
		}

		error_log("==shiksha== Caching Done in = ".(microtime(true)-$start). ' Seconds' );

	}

	function _batchInsertForImplicitProfile($temp_response_queue=array(), $user_response_profile=array()){
		if(empty($temp_response_queue) || empty($user_response_profile)){
			return;
		}

		$this->dbHandle->insert_batch('temp_response_queue', $temp_response_queue);
		$this->dbHandle->insert_batch('user_response_profile', $user_response_profile);

	}

	public function setImplicitCronEnvironments($threadSize = 0){
		error_log("==shiksha== Starts ===");

		$this->initDB();
		$this->_loadRedisLib();
		
		ini_set("memory_limit", '4024M');
		ini_set("max_execution_time",-1);

		$savedImplicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$savedImplicitCronData = json_decode($savedImplicitCronData['implicitCronData'], true);

		$totalCount = 0;
		$sql = 'SELECT COUNT(*) AS count FROM tempLMSTable lms';
		$totalCount = $this->dbHandle->query($sql)->result_array();
		$totalCount = $totalCount[0]['count'];

		$implicitCronData = array();
		$implicitCronData['totalCount'] = $totalCount;
		if(empty($threadSize)){
			$implicitCronData['threadSize'] = $totalCount;
		}else{
			$implicitCronData['threadSize'] = $threadSize;
		}


		$implicitCronData['requiredThreads'] = ceil(($totalCount/$implicitCronData['threadSize']));

		$index = -1;
		for($i=0; $i<=$implicitCronData['requiredThreads']; $i++){
			$index++;
			if(empty($savedImplicitCronData['thread'][$i])){
				$implicitCronData['thread'][$i]['start'] = ($i-1) * $implicitCronData['threadSize'];
				$implicitCronData['thread'][$i]['percentageProcessed'] = 0;
				$implicitCronData['thread'][$i]['executionTime'] = 0;
			}else{
				$implicitCronData['thread'][$i]['start'] = $savedImplicitCronData['thread'][$i]['start']+$savedImplicitCronData['thread'][$i]['processed'];

				$implicitCronData['thread'][$i]['percentageProcessed'] = $savedImplicitCronData['thread'][$i]['percentageProcessed'];
				$implicitCronData['thread'][$i]['executionTime'] = $savedImplicitCronData['thread'][$i]['executionTime'];


			}
			$implicitCronData['thread'][$i]['processed'] = 0;
			$implicitCronData['thread'][$i]['end'] = ($i) * $implicitCronData['threadSize'];
			$implicitCronData['thread'][$i]['time'] = 0;
			$implicitCronData['thread'][$i]['perSecondProcess'] = 0;
			$implicitCronData['cronURLS'][] = '/LDBRecatMigrationScript/implicitResponseMigration/'.$i;

		}
		if(count($implicitCronData['cronURLS']) > 1){
			unset($implicitCronData['cronURLS'][0]);
		}

		if(empty($savedImplicitCronData['thread'][0]['start'])){
			$implicitCronData['thread'][0]['start'] = 0;
		}
		$implicitCronData['thread'][$index]['end'] = $totalCount;
		$implicitCronData['thread'][0]['end'] = $totalCount;

		$this->predisLibrary->addMembersToHash('implicitCronData', array('implicitCronData'=>json_encode($implicitCronData)));
		
		$this->getClientCourseInterest();
		_p($implicitCronData);
		error_log("==shiksha== Ends ===");
	}	

	public function getClientCourseInterest(){
		ini_set("memory_limit", '4024M');
		ini_set("max_execution_time",-1);

		$this->initDB();
		$this->_loadRedisLib();

		$start = microtime(true);
		error_log("==shiksha== getting course data ==");

		/*Loading dependencies */
		$this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder   = new CourseBuilder();
        $courseRepo      = $courseBuilder->getCourseRepository();
		
		$this->load->library('response/responseLib');
		$responseLibObj = new responseLib();

        $courseHierarchyData = array();

		$sql = 'SELECT DISTINCT listing_type_id AS listing_Ids FROM tempLMSTable WHERE listing_type = "course" AND userId > 0';
		$listingIds = $this->dbHandle->query($sql)->result_array();
		error_log("==shiksha== 1 == ".(microtime(true)-$start));
		$listing_Ids = array();
		foreach ($listingIds as $key => $value) {
			$listing_Ids[$value['listing_Ids']] = $value['listing_Ids'];
		}
		error_log("==shiksha== 2 == ".(microtime(true)-$start));
		
		unset($listingIds);
		$sql = 'SELECT sc.course_id, bal.value_name FROM shiksha_courses sc INNER JOIN shiksha_courses_type_information scti ON (sc.course_id = scti.course_id) LEFT JOIN base_attribute_list bal ON (scti.course_level = bal.value_id) WHERE sc.course_id IN ('.implode(', ', $listing_Ids).') AND scti.type="entry" AND sc.status="live" ';
		$listingIds = $this->dbHandle->query($sql)->result_array();
		error_log("==shiksha== 3 == ".(microtime(true)-$start));

		$nationalLiveListings = array();
		foreach($listingIds as $key=>$listingId){
			$nationalLiveListings[$listingId['course_id']] = $listingId['value_name'];
		}

		unset($listingIds);

		$index = 0;
		$total = count($nationalLiveListings);
		error_log("==shiksha== 4 getting interest data == ".$total.' == '.(microtime(true)-$start));
		$processed = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronCourseData');

		$courseLevel = array();
		foreach($nationalLiveListings as $courseId=>$level){
			if(($index*100)%$total == 0){
				error_log("==shiksha== loop processed ===".(($index*100)/$total));
			}

			unset($courseObj);
			unset($courseHierarchyData);

			if(empty($processed[$courseId])){
				$courseObj = $courseRepo->find($courseId, array('basic', 'course_type_information', 'eligibility'));
				$courseHierarchyData = Modules::run("response/Response/getClientCourseDataByCourseObj", $courseObj);
				$courseHierarchyData['user_profile_response'] = $responseLibObj->getDataForResponseProfile($courseHierarchyData['stream_id'], $courseHierarchyData['substream_id'], $courseHierarchyData['baseCourses'], $courseHierarchyData['subStreamSpecMapping'], $courseHierarchyData['mode']);

				if(empty($courseHierarchyData) || empty($courseId)){
					continue;
				}

				$this->predisLibrary->addMembersToHash('implicitCronCourseData', array($courseId=>json_encode($courseHierarchyData)));
				if(!empty($level)){
					$this->predisLibrary->addMembersToHash('implicitCronCourseLevel', array($courseId=>$level));
				}
			}

			$index++;
			if($index%1000 == 0){
				error_log("==shiksha== loop processed ===".(($index*100)/$total).'%');
			}
		}
		error_log("==shiksha==");
		$courseData = array();
		$processed = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronCourseData');
		foreach($processed as $courseId=>$value){
			$courseData[$courseId] = json_decode($value, true);
		}

		$this->predisLibrary->addMembersToHash('implicitCronCourseData', array('courseData'=> json_encode($courseData)));

		error_log("==shiksha== interest saved in ".(microtime(true)-$start));
	}

	function showSavedResult(){
		$this->_loadRedisLib();
		$processed = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronCourseData');
		_p(array('count'=>count($processed)));
		// _p($processed);
	}

	public function implicitResponseMigration($thread=0, $chunkSize = 200000){ die;
		
		ini_set("memory_limit", '4028M');
		ini_set("max_execution_time",-1);

		$start = microtime(true);
		$this->initDB();
		$this->_loadRedisLib();

		error_log("==shiksha-IMP=========== <<<< migration starts >>>>===========");
		$this->_setImplicitCronData($thread, 'time', microtime(true));

		/*Getting environment info */
		$implicitCronData = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronData');
		$implicitCronData = json_decode($implicitCronData['implicitCronData'], true);

		$processed = $implicitCronData['thread'][$thread]['start'] + $implicitCronData['thread'][$thread]['processed'];
		$totalCount = $implicitCronData['thread'][$thread]['end'];
		if(empty($implicitCronData['thread'][$thread])){
			error_log("==shiksha== invalid thread");
			return;
		}
		
		error_log("==shiksha== loading stored course Data ===");
		$courseDataHolder = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronCourseData');
		$courseDataHolder = json_decode($courseDataHolder['courseData'], true);
		if(empty($courseDataHolder)){
			error_log("==shiksha-IMP== environment is not set.. please set the cron environments");
			return;
		}
		
		$nationalLiveListings = $this->predisLibrary->getAllMembersOfHashWithValue('implicitCronCourseLevel');
		
		$userEduLevel = json_decode($userEduLevel['userEduLevel'], true);

		error_log("==shiksha-IMP ===== >>> total unprocessed users === ".($totalCount-$processed));

		if(($totalCount-$processed) <= 0){
			error_log("==shiksha-IMP==  ===== >>> Script/Thread has already processed all the users <<< =====");
			return;
		}
		$lastIndex = $processed;
		$lastIndexForProfile = $processed;
		
		error_log("==shiksha-IMP == Processing starts now ...");
		while($totalCount >= $processed){
			$jump = 0;
			error_log("==shiksha== starting new chunk and getting data for it====".(microtime(true)-$start));

			$sql = 'SELECT lms.userId, lms.submit_date, lms.listing_type_id, lms.action, lms.tracking_keyid, lms.listing_type FROM tempLMSTable lms LIMIT '.$processed.', '.$chunkSize;

			$responseData = $this->dbHandle->query($sql)->result_array();
			error_log("==shiksha== data acquired == ".(microtime(true)-$start));
			$userIds = array();
			$listingIds = array();
			foreach ($responseData as $key => $responseValues) {
				if(empty($responseValues['userId']) || $responseValues['listing_type'] != 'course'){
					$jump++;
					continue;
				}
				$userIds[] = $responseValues['userId'];
				$listingIds[] = $responseValues['listing_type_id'];
			}

			error_log("==shiksha== filtering === ".(microtime(true)-$start));

			$userEduLevel = array();
			$sql = 'SELECT UserId, educationLevel FROM tUserPref WHERE educationLevel IS NOT NULL AND UserId IN ('.implode(', ', $userIds).')';
			unset($userIds);
			$temp = $this->dbHandle->query($sql)->result_array();

			foreach($temp as $key=>$userData){
				$userEduLevel[$userData['UserId']] = $userData['educationLevel'];
			}
			unset($temp);

			error_log("==shiksha== filtering Done=== ".(microtime(true)-$start));

			$index = 0;
			$checkCount = ($chunkSize/10);

			$temp_response_queue = array();
			$user_response_profile = array();
			error_log("==shiksha== response data count ==".count($responseData));
			foreach ($responseData as $key => $responseValues) {
				if($index%$checkCount == 0 && $index){
					error_log("==shiksha== starting batch insert ==".(microtime(true)-$start));
					$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
					$temp_response_queue = array();
					$user_response_profile = array();
					$processed+=$jump;
					error_log("==shiksha== processed ===".$processed.' === jump ==='.$jump.'  == total count = '.$totalCount.' === time == '.(microtime(true)-$start));
					$this->_jumpImplicitProcessedCount($thread, $jump);
					$jump = 0;
				}

				$index++;

				if(empty($responseValues['userId']) || $responseValues['listing_type'] != 'course'){
					unset($responseData[$key]);
					$jump++;
					continue;
				}

				if(empty($nationalLiveListings[$responseValues['listing_type_id']]) || ($nationalLiveListings[$responseValues['listing_type_id']] != $userEduLevel[$responseValues['userId']] && $nationalLiveListings[$responseValues['listing_type_id']] != 'None') || !isset($userEduLevel[$responseValues['userId']])){
					$jump++;
					unset($responseData[$key]);
					continue;
				}

				$lastIndex++;
				$temp = array();
				$temp['id'] = $lastIndex;
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['listing_type'] = 'course';
				if(!empty($responseValues['tracking_keyid'])){
					$temp['tracking_key'] = $responseValues['tracking_keyid'];
				}else{
					$temp['tracking_key'] = NULL;
				}
				$temp['is_response_made'] = 'yes';
				$temp['is_mail_sent'] = 'yes';
				$temp['action_type'] = $responseValues['action'];
				$temp['submit_time'] = $responseValues['submit_date'];

				$courseData = $courseDataHolder[$responseValues['listing_type_id']];

				$stream_id = $courseData['stream_id'];
				$substream_id = $courseData['substream_id'];
				$baseCourses = $courseData['baseCourses'];
				$subStreamSpecMapping = $courseData['subStreamSpecMapping'];
				$mode = $courseData['mode'];

				if(empty($stream_id)){
					$jump++;
					unset($responseData[$key]);
					continue;
				}

				$temp_response_queue[] = $temp;

				$temp = array();
				$temp['user_id'] = $responseValues['userId'];
				$temp['listing_id'] = $responseValues['listing_type_id'];
				$temp['stream_id'] = $stream_id;
				$temp['queue_id'] = $lastIndex;
				$temp['education_level'] = $nationalLiveListings[$responseValues['listing_type_id']];
				$temp['submit_date'] = $responseValues['submit_date'];

				$user_profile_response = $courseData['user_profile_response'];

				$temp['status'] = 'live';

				foreach ($user_profile_response as $key => $value) {
					$lastIndexForProfile++;
					$temp['id'] = $lastIndexForProfile;
					$temp['user_profile'] = $value;
					$temp['substream_id'] = $key;
					$user_response_profile[] = $temp;
				}
				
	        	$jump++;
			}

			if(!empty($temp_response_queue)){ die;
				$this->_batchInsertForImplicitProfile($temp_response_queue, $user_response_profile);
				$processed+=$jump;
				$this->_jumpImplicitProcessedCount($thread, $jump);
				error_log("==shiksha== processed ===".$processed.' === jump ==='.$jump.'  == total count = '.$totalCount);
				$temp_response_queue = array();
				$user_response_profile = array();
				$jump = 0;

			}

			error_log("==shiksha== ==== >>> processed Count = ".$processed);
			error_log("==shiksha-IMP ==== >>> percentage processed = ".(($processed/$totalCount)*100)."%");

			if(($processed+$jump) >= $totalCount){
				break;
			}
			unset($responseData[$key]);
		}

		error_log("==shiksha== updating multiple interest logic");
		$this->updateDuplicateEntriesInResponse();

		$ttime = (microtime(true)-$start);
		error_log("==shiksha-IMP ==== Time Taken ====".$ttime.' Seconds');
		$this->predisLibrary->addMembersToHash('Implicit-cron-execution-time',array('time-in-seconds'=>$ttime));
		$this->_setImplicitCronData($thread, 'executionTime', (microtime(true)-$start));
		error_log("==shiksha-IMP ========= <<<< migration Ends of thread ".$thread.">>>>===========");

	}

	function takeoutUserData(){
		ini_set("memory_limit", '4028M');
		ini_set("max_execution_time",-1);

		$start = microtime(true);
		$this->initDB();
		$this->_loadRedisLib();

		error_log("==shiksha== starting the process=== ".(memory_get_peak_usage(false)/1024/1024));
		$sql = 'select UserId, educationLevel from tUserPref where educationLevel IS NOT NULL and ExtraFlag is null';
		$data = $this->dbHandle->query($sql)->result_array();		

		error_log("==shiksha== query done === ".(memory_get_peak_usage(false)/1024/1024));

		$filteredData = array();
		foreach($data as $key=>$value){
			$filteredData[$value['UserId']] = $value['educationLevel'];
			unset($data[$key]);
		}

		error_log("==shiksha== all done === ".(memory_get_peak_usage(false)/1024/1024));
	}

	function registrationStatusPanel(){
		// _p("Panel is under maintenance!"); die;
		if($this->validateMISUser()){
			$data = array();
			$data['stream'] = $this->getStreams();
			$data['baseCourse'] = $this->getBaseCourses();
			$this->load->View('registrationStatusPanel', $data);		
		}
	}

	private function validateMISUser(){
		$loggedInUserData = $this->getLoggedInUserData();
		if($loggedInUserData['userId'] && $loggedInUserData['usergroup'] == 'shikshaTracking') {
			return TRUE;
		}else{
			echo 'Access Denied!!!. Please login to Shiksha MIS to access this.'; die;
		}
	}

	// function getRegistrationStats(){

	// 	if($this->validateMISUser()){

	// 		$this->initDB();
	// 		$toDate = $this->input->post('toDate');
	// 		$fromDate = $this->input->post('fromDate');
	// 		$fromHour = $this->input->post('fromHour').':00:00';
	// 		$toHour = $this->input->post('toHour').':00:00';


	// 		$from = $fromDate.' '.$fromHour;
	// 		$to = $toDate.' '.$toHour;

	// 		if((strtotime($to) - strtotime($from) < 1) || empty($toDate) || empty($fromDate)){
	// 			echo 'Input Error';
	// 			return;
	// 		}

	// 		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."'";
	// 		$data['registration']['total'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['registration']['total'] = $data['registration']['total'][0]['count'];
			
	// 		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and a.tracking_keyId in (350, 351, 352, 353, 354, 355, 814) and b.ExtraFlag='studyabroad'";
	// 		$data['registration']['PaidAbroad'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['registration']['PaidAbroad'] = $data['registration']['PaidAbroad'][0]['count'];

	// 		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and a.tracking_keyId in (350, 351, 352, 353, 354, 355, 814) and (b.ExtraFlag is NULL OR b.ExtraFlag!='studyabroad')";
	// 		$data['registration']['PaidDomestic'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['registration']['PaidDomestic'] = $data['registration']['PaidDomestic'][0]['count'];
			

	// 		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and b.ExtraFlag='studyabroad'";
	// 		$data['registration']['FreeAbroad'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['registration']['FreeAbroad'] = $data['registration']['FreeAbroad'][0]['count'];
	// 		$data['registration']['FreeAbroad'] = $data['registration']['FreeAbroad'] - $data['registration']['PaidAbroad'];

	// 		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and (b.ExtraFlag is NULL OR b.ExtraFlag!='studyabroad')";
	// 		$data['registration']['FreeDomestic'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['registration']['FreeDomestic'] = $data['registration']['FreeDomestic'][0]['count'];
	// 		$data['registration']['FreeDomestic'] = $data['registration']['FreeDomestic'] - $data['registration']['PaidDomestic'];

	// 		$sql = "select count(*) as count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live'";
	// 		$data['response']['TotalDomestic'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['response']['TotalDomestic'] = $data['response']['TotalDomestic'][0]['count'];

	// 		$sql = "select count(*) as count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live' and listing_subscription_type='free'";
	// 		$data['response']['FreeDomestic'] = $this->dbHandle->query($sql)->result_array();
	// 		$data['response']['FreeDomestic'] = $data['response']['FreeDomestic'][0]['count'];

	// 		$data['response']['PaidDomestic'] = $data['response']['TotalDomestic']-$data['response']['FreeDomestic'];

	// 		echo '<h3>Registration Count</h3>';
	// 		echo '<table class="table"><tr> <td>Total</td> <td>Abroad(paid)</td> <td>Domestic(paid)</td> <td>Abroad(free)</td> <td>Domestic(free)</td> </tr>';
	// 		echo '<tr> <td>'.$data['registration']['total'].'</td> <td>'.$data['registration']['PaidAbroad'].'</td> <td>'.$data['registration']['PaidDomestic'].'</td> <td>'.$data['registration']['FreeAbroad'].'</td> <td>'.$data['registration']['FreeDomestic'].'</td> </tr></table>';

	// 		echo '<h3>Response Count</h3>';
	// 		echo '<table class="table" cellspacing="5"><tr> <td>Total Domestic count</td> <td>Paid Domestic</td> <td>Free Domestic</td> </tr>';
	// 		echo '<tr> <td>'.$data['response']['TotalDomestic'].'</td> <td>'.$data['response']['PaidDomestic'].'</td> <td>'.$data['response']['FreeDomestic'].'</td></tr></table>';
	// 	}
	// }

	private function _getBasicRegStats($from, $to){
		$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usergroup='user' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."'";
			$data['registration']['total'] = $this->dbHandle->query($sql)->result_array();
			$data['registration']['total'] = $data['registration']['total'][0]['count'];
			
			$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usergroup='user' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and a.tracking_keyId in (350, 351, 352, 353, 354, 355, 814) and b.ExtraFlag='studyabroad'";
			$data['registration']['PaidAbroad'] = $this->dbHandle->query($sql)->result_array();
			$data['registration']['PaidAbroad'] = $data['registration']['PaidAbroad'][0]['count'];

			$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usergroup='user' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and a.tracking_keyId in (350, 351, 352, 353, 354, 355, 814) and (b.ExtraFlag is NULL OR b.ExtraFlag!='studyabroad')";
			$data['registration']['PaidDomestic'] = $this->dbHandle->query($sql)->result_array();
			$data['registration']['PaidDomestic'] = $data['registration']['PaidDomestic'][0]['count'];
			

			$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usergroup='user' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and b.ExtraFlag='studyabroad'";
			$data['registration']['FreeAbroad'] = $this->dbHandle->query($sql)->result_array();
			$data['registration']['FreeAbroad'] = $data['registration']['FreeAbroad'][0]['count'];
			$data['registration']['FreeAbroad'] = $data['registration']['FreeAbroad'] - $data['registration']['PaidAbroad'];

			$sql = "select count(*) as count from tusersourceInfo a inner join tUserPref b on (a.UserId=b.userid) inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) where f.isTestUser = 'NO' and t.usergroup='user' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and (b.ExtraFlag is NULL OR b.ExtraFlag!='studyabroad')";
			$data['registration']['FreeDomestic'] = $this->dbHandle->query($sql)->result_array();
			$data['registration']['FreeDomestic'] = $data['registration']['FreeDomestic'][0]['count'];
			$data['registration']['FreeDomestic'] = $data['registration']['FreeDomestic'] - $data['registration']['PaidDomestic'];

			$sql = "select count(*) as count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live'";
			$data['response']['TotalDomestic'] = $this->dbHandle->query($sql)->result_array();
			$data['response']['TotalDomestic'] = $data['response']['TotalDomestic'][0]['count'];

			$sql = "select count(*) as count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live' and listing_subscription_type='free'";
			$data['response']['FreeDomestic'] = $this->dbHandle->query($sql)->result_array();
			$data['response']['FreeDomestic'] = $data['response']['FreeDomestic'][0]['count'];

			$data['response']['PaidDomestic'] = $data['response']['TotalDomestic']-$data['response']['FreeDomestic'];

			echo '<h3>Registration Count</h3>';
			echo '<table class="table"><tr> <td>Total</td> <td>Abroad(paid)</td> <td>Domestic(paid)</td> <td>Abroad(free)</td> <td>Domestic(free)</td> </tr>';
			echo '<tr> <td>'.$data['registration']['total'].'</td> <td>'.$data['registration']['PaidAbroad'].'</td> <td>'.$data['registration']['PaidDomestic'].'</td> <td>'.$data['registration']['FreeAbroad'].'</td> <td>'.$data['registration']['FreeDomestic'].'</td> </tr></table>';

			echo '<h3>Response Count</h3>';
			echo '<table class="table" cellspacing="5"><tr> <td>Total Domestic count</td> <td>Paid Domestic</td> <td>Free Domestic</td> </tr>';
			echo '<tr> <td>'.$data['response']['TotalDomestic'].'</td> <td>'.$data['response']['PaidDomestic'].'</td> <td>'.$data['response']['FreeDomestic'].'</td></tr></table>';
	}

	private function _getRespStatsGroupedBy($from, $to){
		$groupby = $this->input->post('groupby');
		switch ($groupby) {
			case 'trackingKey':
				$sql = "select k.id as trackingKey, k.keyName, k.page, k.widget, k.siteSource, count(*) as Count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) inner join tracking_pagekey k on (a.tracking_keyid = k.id) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live' and k.siteSource != 'Study Abroad' group by k.id order by Count desc";
				break;

			case 'device':
				$sql = "select k.siteSource as Device, count(*) as Count from tempLMSTable a inner join shiksha_courses s on (a.listing_type_id = s.course_id) inner join tuserflag f on (a.userid=f.userId) inner join tracking_pagekey k on (a.tracking_keyid = k.id) where f.isTestUser = 'NO' and submit_date >= '".$from."' and submit_date <= '".$to."' and s.status='live' and k.siteSource != 'Study Abroad' group by k.siteSource order by Count desc";
				break;

			case 'stream':
				echo '<p>NA</p>';
				return;
				break;

			case 'baseCourse':
				echo '<p>NA</p>';
				return;
				break;

			default:
				echo "Invlaid Inputs(Error Code: 3)";
				die;
				break;
		}
		
		$data = $this->dbHandle->query($sql)->result_array();
		echo '<p class="help-block">Order by count desc</p>';
		echo '<h4> Response </h4>';
		$header = array_keys($data[0]);
		echo '<table class="table" cellspacing="5"><tr>';
		foreach ($header as $key => $value) {
			echo '<td><b>'.$value.'</b></td>';
		}
		echo '</tr>';

		foreach($data as $key=>$actual){
			echo '<tr>';
			foreach($actual as $head=>$value){
				echo '<td>'.$value.'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}

	private function _getRegStatsGroupedBy($from, $to){
		$groupby = $this->input->post('groupby');

		switch ($groupby) {
			case 'trackingKey':
				$sql = "SELECT k.id as trackingKey, k.keyName, k.page, k.widget, k.siteSource, count(t.userId) as count from tusersourceInfo a inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid) inner join tracking_pagekey k on (a.tracking_keyid = k.id) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and k.siteSource != 'Study Abroad' group by k.id order by Count desc";
				break;
			
			case 'device':
				$sql = "select k.siteSource as Device, count(*) as Count from tusersourceInfo a inner join tuserflag f on (a.userid=f.userId) inner join tuser t ON (a.userid = t.userid)  inner join tracking_pagekey k on (a.tracking_keyid = k.id) where f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and k.siteSource != 'Study Abroad' group by k.siteSource order by Count desc";
				break;

			case 'stream':
				$sql ="select s.name as Name, count(distinct t.userid) as Count from tuser t inner join tuserflag f on (t.userid=f.userId) inner join tUserInterest tui on (t.userid=tui.userId) inner join streams s on (tui.streamId = s.stream_id) where f.isTestUser = 'NO' and  tui.status='live' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' group by tui.streamId order by Count desc";
				break;

			case 'baseCourse':
				$sql ="select bc.name as Name, count(distinct t.userid) as Count from tuser t inner join tuserflag f on (t.userid=f.userId) inner join tUserInterest tui on (t.userid=tui.userId) inner join tUserCourseSpecialization tuc on (tui.interestId = tuc.interestId) inner join base_courses bc on (tuc.baseCourseId = bc.base_course_id) where f.isTestUser = 'NO' and tui.status='live' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."' and tuc.status='live' group by tuc.baseCourseId order by Count desc";
				break;

			default:
				echo "Invlaid Inputs(Error Code: 3)";
				die;
				break;

		}

		$data = $this->dbHandle->query($sql)->result_array();
		echo '<p class="help-block">Order by count desc</p>';
		echo '<h4> Registration </h4>';
		$header = array_keys($data[0]);
		echo '<table class="table" cellspacing="5"><tr>';
		foreach ($header as $key => $value) {
			echo '<td><b>'.$value.'</b></td>';
		}
		echo '</tr>';

		foreach($data as $key=>$actual){
			echo '<tr>';
			foreach($actual as $head=>$value){
				echo '<td>'.$value.'</td>';
			}
			echo '</tr>';
		}
		echo '</table>';
	}

	private function _getStatsAccToFilter($from, $to){
		$stream = $this->input->post('stream');
		$baseCourse = $this->input->post('baseCourse');

		if(empty($stream) && empty($baseCourse)){
			echo 'Input Error(Error Code: 4)';
			return;
		}

		$sql = "select count(distinct t.userid) as count from tuser t inner join tuserflag f on (t.userid=f.userId) inner join tUserInterest tui on (t.userid=tui.userId) inner join tUserCourseSpecialization tuc on (tui.interestId = tuc.interestId) where tui.status='live' and tuc.status='live' and f.isTestUser = 'NO' and t.usercreationDate >= '".$from."' and t.usercreationDate <= '".$to."'";

		if(!empty($stream)){
			$sql .= " and tui.streamId = ".$stream;
		}

		if(!empty($baseCourse)){
			$sql .= " and tuc.baseCourseId = ".$baseCourse;
		}

		$data = $this->dbHandle->query($sql)->result_array();
		echo '<h4> Count: '.$data[0]['count'].'</h4>';
	}

	function getRegistrationStats(){

		if($this->validateMISUser()){

			$this->initDB();
			$toDate = $this->input->post('toDate');
			$fromDate = $this->input->post('fromDate');
			$fromHour = $this->input->post('fromHour').':00:00';
			$toHour = $this->input->post('toHour').':00:00';

			$from = $fromDate.' '.$fromHour;
			$to = $toDate.' '.$toHour;

			if((strtotime($to) - strtotime($from) < 1) || empty($toDate) || empty($fromDate)){
				echo 'Input Error(Error code: 1)';
				return;
			}

			$searchby = $this->input->post('searchby');

			switch ($searchby) {
				case 'option1':
					$this->_getStatsAccToFilter($from, $to);
					break;

				case 'option2':
					$groupFor = $this->input->post('groupFor');
					if($groupFor == 'Response'){
						$this->_getRespStatsGroupedBy($from, $to);
					}else{
						$this->_getRegStatsGroupedBy($from, $to);
					}
					break;
				
				default:
					$this->_getBasicRegStats($from, $to);
					break;
			}
		}
	}


	public function getMissedOutAgents(){
		$this->initDB();

		$sql = "select agentId from SALeadAllocation join SASearchAgent on searchagentid=agentid where matchedFor=0 and allocationtime> '2017-02-01 00:00:00' 
				and allocationtime < '2017-02-28 00:00:00' and is_Active='live'";

		$data = $this->dbHandle->query($sql)->result_array();
	
		foreach ($data as $key => $value) {
			//$oldAgentsMap[$value['agentId']] =1;
			$oldAgentsArray[] = $value['agentId'];
		}

		if(count($oldAgentsArray) == 0){
			_P(' no search agents exists');;
			exit();
		}
		unset($data);

		$sql = "select agentId from SALeadAllocation join SASearchAgent on searchagentid=agentid where matchedFor=0 and allocationtime> '2017-03-01 00:00:00' 
				and allocationtime < '2017-03-28 00:00:00' and agentId in (".implode(",",$oldAgentsArray).") and is_Active='live'";

				//$oldAgentsArray
		$data = $this->dbHandle->query($sql)->result_array();

	
		foreach ($data as $key => $value) {
			$newAgentsMap[$value['agentId']] =1;
		}


		foreach ($oldAgentsArray as $id) {
			if(!$newAgentsMap[$id]){
				$missingAgents[] =$id;
			}
		}


		$missingAgents = array_unique($missingAgents);
		_P($missingAgents);
		_P('=====================');

		die;
	}

	function getStreams(){
		$this->initDB();
		$sql = "SELECT stream_id, name from streams";
		$data = $this->dbHandle->query($sql)->result_array();

		$returnData = array();
		foreach ($data as $key => $value) {
			$returnData[$value['stream_id']] = $value['name'];
		}

		return $returnData;
	}

	function getBaseCourses(){
		$this->initDB();
		$sql = "SELECT base_course_id, name from base_courses where status='live'";
		$data = $this->dbHandle->query($sql)->result_array();

		$returnData = array();
		foreach ($data as $key => $value) {
			$returnData[$value['base_course_id']] = $value['name'];
		}

		return $returnData;
	}

	function getRegistrationDataGroupBy(){
		if($this->validateMISUser()){
			
		}		
	}

	function getResponseDataGroupBy(){
		if($this->validateMISUser()){
			
		}		
	}

	function getRegistrationDataAccToFilters(){
		if($this->validateMISUser()){

		}		
	}

	function getResponseDataAccToFilters(){
		if($this->validateMISUser()){
			
		}		
	}

	function getSearchAgentsData(){
        ini_set('memory_limit','3048M');
        ini_set("max_execution_time",-1);

        $this->initDB();

        $sql = "select distinct searchagentid, clientid, searchagentName, firstname as fname, lastname as lname from SASearchAgent a 
        join tuser on clientid = userId 
        join SAMultiValuedSearchCriteria b on searchAlertId = searchagentid 
        where keyname = 'desiredcourse' and a.is_active = 'live' AND b.is_active = 'live' 
        and value in (453,454,455,1298,1299,645,646,647,648,1305,1306,1307,1308,1309,1313,1314,52,320,321,322,323,324,325,326,327,328,329,331,332,333,334,335,356,592,782,1539,593,594,595,596,597,598,599,600,601,602,603,604,605,606,607,608,609,1369,1540,1541,1542,1543,1544,1545,1546,1547,1548,1549,1550,1551,1552,1553,649,1368,650,651,46,48,50,652,653,654,1374,1432,1433,1434,1435,1436,1437,1438,1439,1440,1441,1442,1443,1444,1445,1446,1447,1448,1449,1450,1451,47,49,1375,51,188,189,735,736,737,738,739,740,741,742,743,744,745,746,747,748,750,751,710) ";
        
        $agents = $this->dbHandle->query($sql)->result_array();

        error_log('############ total agents ->> '.count($agents));

        $itr = 0;

        $caseSAgents = array();
        $caseSAgents[] ='search Agent Id | ClientId | Search Agent Name | Subscription End Date | Credits Remaining | Last Allocation Time | Client Name | Sales Person Id | Sales Person Name';

        foreach ($agents as  $value) {

            $subscriptionSQL = 'select BaseProdRemainingQuantity as credit, SubscriptionEndDate as endDate, sud.userId, t.firstname, t.lastname
                from  SUMS.Subscription sub
                left join SUMS.Subscription_Product_Mapping map on map.subscriptionId = sub.Subscriptionid
                left join SUMS.Transaction tr on sub.TransactionId = tr.TransactionId
                left join SUMS.Sums_User_Details sud on sud.EmployeeId = tr.SalesBy
                left join shiksha.tuser t on t.userid = sud.userId
                where sub.clientuserid ='.$value["clientid"].'
                and map.baseproductid = 127 and sub.baseproductid = 127
                and map.status ="ACTIVE" and map.SubscriptionEndDate > now()
                order by map.subscriptionEndDate';

            $credits = $this->dbHandle->query($subscriptionSQL)->result_array();



            $lastAllocationTime  =  "Select allocationtime from SALeadAllocation where agentid=".$value['searchagentid']." order by id desc limit 1";

            $allocationTime = $this->dbHandle->query($lastAllocationTime)->result_array();
            $allocationTime = $allocationTime[0]['allocationtime'];

            $creditSUM = 0;

            foreach ($credits as $value2) {
                $creditSUM  += $value2['credit'];
                $endDate = $value2['endDate'];
                $salesPersonId = $value2['userId'];
                $salespersonName = $value2['firstname'].' '. $value2['lastname'];
            }

            if(empty($endDate) || !isset($endDate) || $endDate == ''){
                $endDate ='Expired';
            }

            unset($credits);

			$caseSAgents[] = $value['searchagentid'].'|'.$value['clientid'].'|'.$value['searchagentName'].'|'.$endDate.'|'.$creditSUM.'|'.$allocationTime.'|'.$value['fname'].' '.$value['lname'].'|'.$salesPersonId.'|'.$salespersonName;

            unset($endDate);
            unset($salesPersonId);
            unset($salespersonName);

            error_log('######### Agent processed '.$itr);
            $itr++;

        }


        $str = implode('<br>', $caseSAgents);
        _P($str );

    } 


	function migrateUnsubscribeUser(){
		$this->initDB(); //write handle for insert

		$total_users = 300000;

		$itr = 0;
		while($itr <= $total_users){
			$sql = "select userid from tuserflag where unsubscribe='1' limit $itr,1000";
	        $users = $this->dbHandle->query($sql)->result_array();

	        if(count($users) <1){
	        	_P('cron Completion');
	        	exit();
	        }

	        $this->migrateUserMailPref($users);
	        $itr = $itr + 1000;
	        error_log('= ====== = == = = = ====== counter done : '.$itr);
		}
		        
	}

	function migrateUserMailPref($users){
		$dbHandle = $this->_loadDatabaseHandle ( "write" );		
		$sql = "insert into user_unsubscribe_mapping (user_id, unsubscribe_category, status) VALUES ";
		$solrIndexQuery = "insert into sanitizeDisplayName (userId, is_processed) VALUES ";

		foreach ($users as $userid) {
			$sql .=	"(".$userid['userid'].",1,'live'),";
			$sql .=	"(".$userid['userid'].",2,'live'),";
			$sql .=	"(".$userid['userid'].",3,'live'),";
			$sql .=	"(".$userid['userid'].",4,'live'),";
			$sql .=	"(".$userid['userid'].",5,'live'),";

			$solrIndexQuery .= "(".$userid['userid'].",'n'),";
		}

		$sql = substr($sql, 0,-1);
		$dbHandle->query($sql);

		$solrIndexQuery = substr($solrIndexQuery, 0,-1);
		$dbHandle->query($solrIndexQuery);		
	}
}
