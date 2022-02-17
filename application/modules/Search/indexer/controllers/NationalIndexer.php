<?php

class NationalIndexer extends MX_Controller {
	
	private $validIndexTypes = array();
	private $documentBatchSize;
	private $MAX_CRON_ATTEMPTS = 0;

	public function __construct(){

		$this->load->library('indexer/NationalIndexingLibrary');
		$this->nationalIndexingLib = new NationalIndexingLibrary();
		
		$this->load->library('indexer/AutoSuggestorIndexing');
		$this->autoSuggestorIndexingLib = new AutoSuggestorIndexing();
		
		$this->load->library('indexer/UgcIndexing');
		$this->ugcIndexingLib = new UgcIndexing();

		$this->load->model("indexer/NationalIndexingModel");
		$this->nationalIndexingModel = new NationalIndexingModel();
	//	$this->load->config('indexer/nationalIndexerConfig');

		$this->load->library('indexer/SolrServerLib');
		$this->solrServerLib = new SolrServerLib;

		$this->load->model("search/SearchModel");
		$this->searchModel = new SearchModel();

		$this->load->config('indexer/nationalIndexerConfig');

		$this->setDebugStatus();

	}

	public function indexCourseList(){
		$this->validateCron();
		$courseArr = array(264685,179722,251793);
		$indexResponse = $this->nationalIndexingLib->indexAllCourses($courseArr,$fieldsArray,$extraData);
		Modules::run('ListingScripts/populateCourseWidgetForUpdatedCourseCache', $courseArr);
		echo 'Done';
	}
	/*
	* $type - Institute/course/autosuggestors - String
	* $id = Non Zero Postive Integer - Institute Id
	* $typeOfIndex = String = Complete / Partial
	*/
	public function index($type = 'institute', $id = 0, $courseStatusFieldChange=false,$primaryParentChange=false, $oldPrimaryParent=0, $fieldsArray=array(),$extraData=array()){
		$this->validateCron();
		ini_set("memory_limit", "3000M");
		ini_set('max_execution_time', '12000');
		// check for Valid Request
		$isValidRequest = $this->nationalIndexingLib->validateIndexingRequest($type,$id);
		
		if(!$isValidRequest) {
			return;
		}

		$finalFieldsArray = array();
		if(!empty($fieldsArray)){
			$finalFieldsArray['fields'] = $fieldsArray;
			if(!empty($extraData)){
				$finalFieldsArray['extraData'] = $extraData;
			}
		}

		switch ($type) {
			case 'institute':
			case 'university':
				$indexResponse = $this->nationalIndexingLib->indexInstitute($id,$finalFieldsArray);
				// Update all the Documents which can be affected by the change in type of University / Autonomous Institute
				if($courseStatusFieldChange){
					$this->nationalIndexingLib->indexInstituteForHierarchyChange($id);
				}
				// Update old Primary Parent Also
				/*if($primaryParentChange && $oldPrimaryParent!=0){
					$indexResponse = $this->nationalIndexingLib->indexInstitute($oldPrimaryParent);
				}*/
                $this->autoSuggestorIndexingLib->indexILPChildPagesForAutosuggestor($id);
				break;

			case 'course' :
				// Update old Primary Parent Also
				if($primaryParentChange && $oldPrimaryParent!=0){
					$indexResponse = $this->delete('course',$id);
				}
				$indexResponse = $this->nationalIndexingLib->indexAllCourses(array($id),$fieldsArray,$extraData);
				Modules::run('ListingScripts/populateCourseWidgetForUpdatedCourseCache', array($id));
				break;

			case 'exam':
				if(!empty($id)) {
					$id = array($id);
				}
				$this->autoSuggestorIndexingLib->indexExamPagesForAutosuggestor($id);
				break;

			case 'examChildPage':
				if(!empty($id)) {
					$id = array($id);
				}
				$this->autoSuggestorIndexingLib->indexExamChildPagesForAutosuggestor($id);
				break;

			case 'allexam':
				$temp = explode('::',$id);
				$entityType = $temp[0];
				$entityId = $temp[1];
				$this->autoSuggestorIndexingLib->indexAllExamPagesByEntity($entityType,$entityId);
				break;

			case 'autosuggestor':
				$this->autoSuggestorIndexingLib->autoSuggestorFullIndexing();
				break;

			case 'location':
				$this->autoSuggestorIndexingLib->autoSuggestorLocationIndexing();
				break;

			case 'collegereview':
				$this->CRLib 	= $this->load->library('CollegeReviewForm/CollegeReviewLib');
				$checkReviewType = $this->CRLib->checkReviewType($id);
				$indexResponse = $this->CRLib->indexCollegeReviews(array($id),$checkReviewType);
				

				break;

			case 'question':
				$indexResponse = $this->ugcIndexingLib->indexQuestions($id);
				
				//temporarily maintain index on 82 server
				//$indexResponse = Modules::run('search/Indexer/indexToNewSolr', $id, 'question');
				
				break;
				
			case 'article':
				$indexResponse = $this->ugcIndexingLib->indexArticles($id);
				break;
			
			case 'question_tag':
				$this->autoSuggestorIndexingLib->autosuggestorQuestionTagIndexing($id);
				break;

			case 'updated_question':
				$this->autoSuggestorIndexingLib->autosuggestorQuestionIndexing($id, '-1 days');
				break;
			case 'collegeshortlist':
				$collegeshortlistLib = $this->load->library("Shortlist/collegeshortlistlibrary");
				$collegeshortlistLib->indexCollegeShortlistPredictorDataToSolrByExamId($id);
				break;
            case "ilpChildPage" :
                if(!empty($id)) {
                    $id = array($id);
                }
                $this->autoSuggestorIndexingLib->indexILPChildPagesForAutosuggestor($id);
                break;

		}
		$this->printlog("Indexing status: " .$indexResponse);
		return $indexResponse;
		//echo  "MEMORY_USAGE: => ".memory_get_peak_usage();	
	}

	public function delete($type='institute', $typeId=0){
		switch ($type) {
			//course documents
			case 'institute':
			case 'university':
				$deletetionStatus = $this->solrServerLib->deleteDocuments($type,$typeId);
				$this->nationalIndexingLib->indexInstituteForHierarchyChange($typeId);
				break;

			//UGC documents
			case 'question':
			case 'article':
			//case 'discussion':
				if(empty($typeId)) {
					return;
				}
				$deletetionStatus = $this->solrServerLib->deleteUgcDocument($type,$typeId);
				break;
			
			case 'collegereview':
			case 'course':
			case 'collegeshortlist':
				$deletetionStatus = $this->solrServerLib->deleteDocuments($type,$typeId);
				break;

			//autosuggestor documents
			default:
				$deletetionStatus = $this->solrServerLib->deleteAutoSuggestorDocuments($type,$typeId);
				break;
		}

		$this->solrServerLib->softCommitChanges();

		error_log("$type Document deleted: ".$typeId." at ".date("Y-m-d h:i:sa")."\n", 3, $this->logFilePath);
		
		$this->printlog($deletetionStatus);
		return $deletetionStatus;
	}

	private function deleteAutoSuggestorDocuments($entityType,$entityId){
		if(empty($entityType)){
			_p('EntityType must be present');die;
		}
		$deletetionStatus = $this->solrServerLib->deleteAutoSuggestorDocuments($entityType,$entityId);
		//$this->solrServerLib->softCommitChanges();
		return $deletetionStatus;
	}

	// cron for college review solr indexing
	public function processCRIndexLog($forDR="false"){
		$startTime = time();
		$this->nationalProcessIndexLog('collegereview', $forDR);
		$this->solrServerLib->softCommitCollegeReviewChanges();
		$endTime = time();
		$totalTime = $endTime -$startTime;
		echo "CR Indexing Cron Total Time : ".$totalTime;
		if($totalTime < 30){
			if($totalTime < 10){
				sleep(30);
			}else if($totalTime >= 10 && $totalTime <= 20){
				sleep(15);
			}else{
				sleep(10);
			}

			$startTime = time();
			$this->nationalProcessIndexLog('collegereview', $forDR);
			$this->solrServerLib->softCommitCollegeReviewChanges();
			$endTime = time();
			$totalTime = $endTime -$startTime;
			echo "CR Indexing Cron Total Time for second part: ".$totalTime;
		}
	}
	
	// Actual Cron Work
	public function nationalProcessIndexLog($type='all', $forDR="false"){
		$this->validateCron();
		ini_set("memory_limit", "3000M");

		$listingsCronsLib = $this->load->library('nationalInstitute/ListingsCronsLib');
		$startDate = '';
		if($forDR == "true"){
			$startDate = date("Y-m-d H:i:s",strtotime(date()." - ".SOLR_INDEXING_INTERVAL." hours"));
			ini_set('max_execution_time', '-1');
		}else{
			ini_set('max_execution_time', '1200');
		}

		$start = 0;
		$batchSize = 1000;
		$oldPrimaryParent = 0;

		if($type =='collegereview'){
			// Fetch Collgee Review Queued Data
			$indexingData = array();
			$indexingData = $this->nationalIndexingModel->getCRIndexQueueEntries($start, $batchSize,'pending','',$startDate);

			// fetch failed college review data
			if($startDate ==''){
				$failedData = array();
				$failedData = $this->nationalIndexingModel->getCRIndexQueueEntries($start, $batchSize, 'failed', 15,$startDate);
				$indexingData = array_merge($indexingData, $failedData);
			}
			echo "CR Indexing Total Data for indexing is : ".count($indexingData);
		}else{
			// Fetch Queued Data
			$indexingData = $this->nationalIndexingModel->getIndexQueueEntries($start, $batchSize,$startDate);
		}

		// Process Queued Data(Combine them)
		$indexingData = $this->processIndexingDataFromDB($indexingData);
		
		$fullIndexingSections = $this->config->item('FULL_INDEXING_SECTIONS');
		foreach ($indexingData as $key => $queuedData) {
			$listingId = $queuedData['listing_id'];
			$listingType = $queuedData['listing_type'];
			if($listingType == 'institute' || $listingType == 'university'){
				$instituteIds[] = $listingId;
			}
			$extraData = $queuedData['extraData'];
			$operationArr = $queuedData['operations'];

			// Set Opearation for Indexing(INDEX/DELETE)
			if(!empty($operationArr)){
				$operation = end($operationArr);
			}else{
				$operation = "index";
			}
			
			// SET EXTRADATA for Partial Indexing(DATA for Particular Sections)
			if($extraData != NULL && trim($extraData) != ""){
				$extraData = json_decode($extraData);
			}

			// Check if Sections Changes are Type Of University/Autonomous Field of Institute
			$courseStatusFieldChange = false;

			if(in_array(INSTITUTE_COURSE_STATUS, $queuedData['section']) || empty($queuedData['section'])){
				$courseStatusFieldChange = true;
			}

			$primaryParentChange = false;
			if(in_array(COURSE_PRIMARY_ID_SECTION, $queuedData['section'])){
				$primaryParentChange = true;
				$oldPrimaryParent = $queuedData[extraData]['oldId'];
			}

			$sections = $queuedData['section'];
			$filteredSections = array_filter($sections);

			// FULL INDEXING
			if(count($sections) != count($filteredSections)){
				$sections = array();
			}

			// CHECK if any section indicates, whether Full Indexing Needs to be done
			$intersectionSections = array_intersect($fullIndexingSections, $sections);
			if(!empty($intersectionSections)){
				$sections = array();
				$extraData = null;
			}

			// APPEND LAST Modify section in sections
			if(!empty($sections)){

				$possibleInstituteSections = $this->config->item('INSTITUTE_SECTIONS');
				$possibleCourseSections = $this->config->item('COURSE_SECTIONS');

				$instituteFieldsPresent = array_intersect($possibleInstituteSections, $sections);
				$courseFieldsPresent = array_intersect($possibleCourseSections, $sections);

				if(!empty($courseFieldsPresent)){
					$sections[] = "LAST_MODIFY";					
				}

			}
			
			if($forDR != "true"){
				// START INDEXING STATUS UPDATE
				$this->nationalIndexingModel->setIndexingStatus($queuedData['indexlog_id'],'processing'); 
			}
			

			// WAIT FOR INDEXING
			$result = false;
			switch ($operation) {
				case 'index':
					$result = $this->index($listingType,$listingId,$courseStatusFieldChange,$primaryParentChange,$oldPrimaryParent,$sections,$extraData);
					if(!empty($result)){
						$result = reset($result);
							if($result == 1){
							$status = 'complete';
						}else{
							$status = 'failed';
						}
					}else{
						$status = "complete";
					}
		
					break;
				case 'delete':
					$result = $this->delete($listingType,$listingId);
					if($result == true){
						$status = "complete";
					}else{
						$status = "failed";
					}
					break;
			}
			

			
			//END INDEXING STATUS UPDATE
			if (ENVIRONMENT == 'production'){
	            if($status == "failed"){
					sendMailAlertDev("nationalProcessIndexLog Failed for Ids  == ".implode(",", $queuedData['indexlog_id']),"Indexing Cron Failed");
				}
	        }
			
			if($forDR != "true"){
				$this->nationalIndexingModel->setIndexingStatus($queuedData['indexlog_id'],$status);
			}
			
		}

	}


	/**
	* Combining the Data from Indexlog for the same entity
	* i.e. Multiple Rows for 123 Course will be merged with there sections and extraData
	* ExtraData will also gets merged based on the keys
	*/
	private function processIndexingDataFromDB($indexingData){

		
		$finalIndexingData = array();
		foreach ($indexingData as $key => $value) {
			$generatedKey = $value['listing_id']."_".$value['listing_type'];
			
			if(!array_key_exists($generatedKey, $finalIndexingData)){
				$finalIndexingData[$generatedKey] = array();
			}
			$finalIndexingData[$generatedKey]['listing_type'] = $indexingData[$key]['listing_type'];
			$finalIndexingData[$generatedKey]['listing_id'] = $indexingData[$key]['listing_id'];

			// Find A Better Way => Course Type Like Twinning, Executive, Lateral etc;
			if(!empty($value['sections']) && !empty($value['extraData'])){
				if($value['sections'] == COURSE_HIERARCHY_SECTION_DATA) {
					$value['sections'] = COURSE_BASIC_SECTION_DATA;
				}
			}
			////////////

			if(array_key_exists('section', $finalIndexingData[$generatedKey])){
				$finalIndexingData[$generatedKey]['section'] = array_merge(array($value['sections']), $finalIndexingData[$generatedKey]['section']);
			}else{
				$finalIndexingData[$generatedKey]['section'] = array($value['sections']);
			}
			
			$finalIndexingData[$generatedKey]['indexlog_id'][] = $value['id'];
			$finalIndexingData[$generatedKey]['operations'][] = $value['operation'];
			if(!empty($value['extraData'])){
				if(isset($finalIndexingData[$generatedKey]['extraData'])){
					$extraData = (array)json_decode($value['extraData']);

					foreach ($extraData as $extraDataKey=>$extraDataValue) {
						if(array_key_exists($extraDataKey, $finalIndexingData[$generatedKey]['extraData'])){
							$finalIndexingData[$generatedKey]['extraData'][$extraDataKey] = array_unique(array_merge($extraDataValue,$finalIndexingData[$generatedKey]['extraData'][$extraDataKey]));
						}else{
							$finalIndexingData[$generatedKey]['extraData'][$extraDataKey] = $extraDataValue;
						}
					}
				}else{
					$extraData = (array)json_decode($value['extraData']);
					$finalIndexingData[$generatedKey]['extraData'] = $extraData;
				}
			}
		}

		return $finalIndexingData;
		
	}

	private function setDebugStatus(){
		if(isset($_REQUEST['debug']) && $_REQUEST['debug'] == "true"){
			$this->debug = true;	
		}
	}

	private function printlog($data, $dump = false){
		if($this->debug){
			error_log("printlog: ". print_r($data, true));
			if($dump){
				var_dump($data);
			} else {
				_p($data);
			}
		}
	}

	private function printErrorlog($data, $dump = false){
		if($this->debugErrorLog){
			if($dump){
				var_dump($data);
			} else {
				if(is_array($data)){
					_p($data);
				} else {
					echo $data . "\n";
				}
			}
		}
	}


	// Full Indexing CRON
	public function nationalFullIndexing(){
		ini_set("memory_limit", "3000M");
		ini_set('max_execution_time', '1440000');
		global $saveDbQueries;
		$saveDbQueries = false;
		$instituteList = $this->nationalIndexingModel->fetchInsIds();
		foreach ($instituteList as $instituteId) {
			error_log("START FOR $instituteId\n");
			$this->delete('institute',$instituteId);
			$this->index('institute',$instituteId);
			error_log("END FOR $instituteId\n");
		}
	}

	public function sectionWiseFullIndexing($sectionName = ""){
		$this->validateCron();
		$sectionName = trim($sectionName);
		if(empty($sectionName)) return;

		$sectionName = explode(",", $sectionName);
		$sectionName = array_values(array_filter(array_unique($sectionName)));
		ini_set("memory_limit", "3000M");
		ini_set('max_execution_time', '1440000');
		$instituteList = $this->nationalIndexingModel->fetchInsIds();
		//$instituteList = array();
		//$instituteList[] = 486;
		//$instituteList[] = 50594;
		foreach ($instituteList as $instituteId) {
			error_log("START FOR $instituteId\n");
			$this->index('institute',$instituteId,false,false,0,$sectionName);
			error_log("END FOR $instituteId\n");
		}	
	}

	public function autoSuggestorParticularEntityIndexing($entityType, $entityId){
		$this->autoSuggestorIndexingLib->autoSuggestorParticularEntityIndexing($entityType, $entityId);
	}

	public function qerMapupdate() {
		$this->validateCron();
		$this->solrServerLib->qerMapupdate();
	}

	public function solrCommit() {
		$this->validateCron();

		// to commit collection1 (course/institute/exam/article etc.) collection changes
		$this->solrServerLib->commitChanges();

		// to commit collegereviews(college review) collection changes
		$this->solrServerLib->commitCollegeReviewChanges();
	}

	public function indexExamPagesForAutosuggestor($ids){
		$examIds = array_filter(explode(',',$ids));
		$this->autoSuggestorIndexingLib->indexExamPagesForAutosuggestor($examIds);
	}

	public function indexExamChildPagesForAutosuggestor($ids) {
		$examIds = array_filter(explode(',',$ids));
		$this->autoSuggestorIndexingLib->indexExamChildPagesForAutosuggestor($examIds);
	}

    public function indexILPChildPagesForAutosuggestor($ids) {
        $this->validateCron();
        ini_set("memory_limit", "512M");
        ini_set('max_execution_time', -1);
	    $ilpIds = array_filter(explode(',',$ids));
        $this->autoSuggestorIndexingLib->indexILPChildPagesForAutosuggestor($ilpIds);
    }

    public function fullIndexingILPChildPagesForAutosuggestor() {
        $this->validateCron();
        ini_set("memory_limit", "512M");
        ini_set('max_execution_time', -1);
        $this->autoSuggestorIndexingLib->indexILPChildPagesForAutosuggestor();
    }

	public function indexQuestionForAutosuggestor($id){
		if(empty($id)) {
			return;
		}
		// $this->deleteAutoSuggestorDocuments('question', $id);
		// $this->deleteAutoSuggestorDocuments('question_un', $id);
		$this->autoSuggestorIndexingLib->autosuggestorQuestionIndexing($id);
		$this->solrServerLib->softCommitChanges();
	}

	public function indexAllExamPagesForAutoSuggestor(){
		$this->validateCron();
		//$this->deleteAutoSuggestorDocuments('allexam');
		$this->autoSuggestorIndexingLib->indexAllExamPagesForAutoSuggestor();
	}

	public function indexAllExamPagesForAutoSuggestorById($entityType,$entityId){
		$this->autoSuggestorIndexingLib->indexAllExamPagesByEntity($entityType,$entityId);
	}

	public function indexInstitutesWithAbbreviations() {
		ini_set('max_execution_time', '-1');

		$time_start = microtime_float(); $start_memory = memory_get_usage();
		$instituteList = $this->nationalIndexingModel->fetchInsIdsWithAbbreviation();

		error_log("Data fetched from db | ".getLogTimeMemStr($time_start, $start_memory)."\n");
		foreach ($instituteList as $instituteId) {
			error_log("START FOR $instituteId\n");
			$this->index('institute', $instituteId);
			error_log("END FOR $instituteId\n");
		}
		_p("Done");
		error_log("Total indexing | ".getLogTimeMemStr($time_start, $start_memory)."\n");
	}

	public function indexSpecificInstitutes() {
		$PCW_INSTITUTES = $this->config->item('PCW_INSTITUTES');
		foreach ($PCW_INSTITUTES as $key => $instituteId) {
			error_log("Indexing institute: $instituteId\n");
			$this->index('institute', $instituteId);
		}
		_p('Done');
	}

	public function deleteAllCollegeShortlistDocuments($type){

	}

} ?>
