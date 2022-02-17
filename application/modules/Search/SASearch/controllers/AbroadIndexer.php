<?php

class AbroadIndexer extends MX_Controller {
	
	private $validIndexTypes = array();
	private $documentFormat;
	private $searchServer;
	private $listingBuilder;
	private $searchModel;
	private $debug;
	private $debugErrorLog;
	private $MAX_CRON_ATTEMPTS = 3;
	private $logFileName;
	private $logFilePath;
	private $batchSize = 50;	

	public function __construct(){
		$this->load->builder('SearchBuilder', 'search');
		//$this->load->helper('search/SearchUtility');
		$this->abroadIndexerLib = $this->load->library('SASearch/AbroadIndexerLib');
		$this->abroadListingCommonLib = $this->load->library('listing/AbroadListingCommonLib');
		$this->load->builder("ListingBuilder", "listing");
		$this->config->load('search_config');
		$this->listingBuilder = new ListingBuilder();
		
		$this->deleteDocumentLib = $this->load->library("SASearch/SASolrDeleteDocument", "", true);
		$this->searchServer = $this->config->item('search_server'); // solr
		$this->validIndexTypes = $this->config->item('search_index_types'); // abroadlisting
		$this->documentFormat = $this->config->item('document_format'); // solr_xml
		//$this->searchModel = new SearchModel();
		$this->saSolrXmlDocumentGenerator = $this->load->library("SASearch/SASolrXmlDocumentGenerator");
		//$this->setDebugStatus();
		
		//$this->logFileName = 'log_data_indexing_solr_'.date('y-m-d');
        //$this->logFilePath = '/tmp/'.$this->logFileName;

        ini_set('memory_limit', '-1');
	}
	/*
	 * function to index documents in a loop
	 * @params:
	 * - array of data to  be indexed
	 * - flag that denotes whther we need to only update certain fields
	 */
	private function _index($indexDataList, $updateFlag = false)
	{
		if(is_array($indexDataList) && !empty($indexDataList))
		{
			// chunks
			$chunkList = array_chunk($indexDataList,$this->batchSize);
			$indexResponseValues = array();
			foreach($chunkList as $k=>$indexList){
				$documentList = $this->saSolrXmlDocumentGenerator->getDocuments($indexList, $updateFlag);
				$searchServer = SearchBuilder::getSearchServer($this->searchServer);
				$indexResponse = $searchServer->indexDocuments($documentList, $type);
				$indexResponseValuesChunked = array_values($indexResponse);
				array_merge($indexResponseValues,$indexResponseValuesChunked);
				echo "<br>Done for batch:".$k.", count:".count($indexList);
				sleep(1);
			}
			_p($indexResponseValues);
			return $indexResponseValues;
		} else {
			echo "Nothing to index";
			return false;
		}
	}
	
	/*
	 * Indexes information based on type passed.
	 * @params:
	 * 1. type
	 * 		possible types are:
	 * 			- university (all courses of a given university would be indexed.
	 * 			  In order to index all universities simply skip the next param : id) ,
	 * 			- course (single course for all specializations)
	 * 	2. id (university id/ course id)
	 * 	3. operation (index, delete)
	 */
	public function indexAbroadData($type, $id = "All", $operation="index")
	{
		$this->validateCron();
		ini_set("max_execution_time",-1);
		error_log( "SRB data gathering initiated ::".date("Y-m-d H:i:s"));
		error_log( "SRB memory usage @ data gathering initiated ::".memory_get_usage());
		switch($type)
		{
			case "course":
				if(is_numeric($id) && $id>0)
				{
					$dataTobeIndexed = $this->abroadIndexerLib->getDataRequiredForCourse(array($id));
				}
				break;
			case "university":
				// get courses of university & get all its courses' data to be indexed
				$dataTobeIndexed = $this->abroadIndexerLib->getDataRequiredForUniversity($id);
				break;

			case 'scholarship':
				$dataTobeIndexed = $this->abroadIndexerLib->getDataRequiredForScholarship($id);
				break;
			default:		// shouldn't happen 
				break;
		}
		error_log( "SRB data gathering complete ::".date("Y-m-d H:i:s"));
		error_log( "SRB memory usage @ data gathering complete ::".memory_get_usage());
		error_log( "SRB indexing initiated ::".date("Y-m-d H:i:s"));
		$this->benchmark->mark("start_index");
		
		for($i=0;$i<count($dataTobeIndexed);$i=$i+$this->batchSize)
		{			
			$ind = array_slice($dataTobeIndexed,$i,$this->batchSize);
			$indexResponseValues = $this->_index($ind);
		}
		//$this->_index($dataTobeIndexed);
		$this->benchmark->mark("end_index");
		error_log( "SRB Total time taken for index= ".$this->benchmark->elapsed_time('start_index', 'end_index'));
		error_log( "SRB memory usage @ index completion ::".memory_get_usage());
		echo "Complete";
	}
	
	public function indexPopularityCount(){
		$this->validateCron(); // prevent browser access
		$this->benchmark->mark("start_index");
		error_log("indexPopularityCount : start ".date("H:i:s"));
		
		$popularityIndexData = $this->abroadIndexerLib->getPopularityIndexData();
		//_p(($popularityIndexData));
		error_log("indexPopularityCount : Indexing start ".date("H:i:s"));
		$this->_index($popularityIndexData,true);
		error_log("indexPopularityCount : Indexing done ".date("H:i:s"));
		
		
		error_log('indexPopularityCount : memory uses '.(memory_get_usage(TRUE)/(1024*1024)));
		/*
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib'); 
		$subject = 'indexPopularityCount cron success :';
		$msg .= "Cron executed successfully <br/>";
		$msg .= 'Path : /var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br/>'; 
		$msg .= 'Method : indexPopularityCount <br/>'; 
		$msg .= 'Date Time : '.date('Y-m-d h:i:s').'<br/>'; 
		$msg .= '<br/><br/>Regards,<br/>SA Team'; 
		$commonStudyAbroadLib->selfMailer($subject,$msg);
		*/
		$this->benchmark->mark("end_index");
		error_log("indexPopularityCount : Indexing completed time taken ==".$this->benchmark->elapsed_time('start_index', 'end_index'));
	}

	public function indexScholarshipPopularity(){
		$this->validateCron(); // prevent browser access
		$this->benchmark->mark("start_index");
		error_log("indexScholarshipPopularityCount : Data fetching start ".date("H:i:s"));
		$scholarshipPopularityIndexData = $this->abroadIndexerLib->getScholarshipPopularityIndexData();
		error_log("indexScholarshipPopularityCount : Data fetching Done ".date("H:i:s"));
		error_log("indexScholarshipPopularityCount : Indexing start ".date("H:i:s"));
		$this->_index($scholarshipPopularityIndexData,true);
		error_log("indexScholarshipPopularityCount : Indexing done ".date("H:i:s"));
		$this->benchmark->mark("end_index");
		$time = $this->benchmark->elapsed_time('start_index','end_index');
		error_log("indexScholarshipPopularityCount : Time taken ".$time);
	}
	/*
	 * function to index abroad listings: course dept university, that have been modified in past 24 hours
	 */
	public function indexModifiedAbroadListings($forDR="false")
	{
		$this->validateCron(); // prevent browser access
		ini_set("max_execution_time",-1);
		// get list of courses from abroadIndexlog
		$indexLogEntries = $this->abroadIndexerLib->processAbroadIndexLog('',$forDR);
		// add start time in indexlog
		if($forDR != "true"){
			$this->abroadIndexerLib->startIndexing(array_map(function($a){return array('listingType'=>$a['listingType'],'listingTypeId'=>$a['listingTypeId']);},$indexLogEntries));
		}

		// get data for those courses that are to be indexed 
		$courseIdTobeIndexed = array_filter(array_map(function($a){if($a['operation'] == 'index' && $a['listingType'] == 'course'){ return $a['listingTypeId']; } },$indexLogEntries));
		//(delete them first)
		$deleteResponse = $this->deleteDocument("course",$courseIdTobeIndexed);
		$indexFailures = array();
		foreach($indexLogEntries as $row)
		{
			// delete whatever documents are supposed to be deleted
			if($row['operation']=="delete")
			{
				$deleteResponse = $this->deleteDocument($row['listingType'],$row['listingTypeId']);
				if($deleteResponse === 1) // success
				{
					$status = "complete";
				}
				else{
					$status = "failed";
					//error_log("Unable to delete ".$row['listingTypeId']);
					$indexFailures[] = array('listingType'=>$row['listingType'],'listingTypeId'=>$row['listingTypeId'], 'operation'=>$row['operation'],'message'=>"Unable to delete ".$row['listingTypeId']);
				}
			}
			// index those that need to be indexed
			else if($row['operation']=="index")
			{
				$status='complete';
				try{

					$courseTobeIndexed = $this->abroadIndexerLib->getDataRequiredForCourse(array($row['listingTypeId']));
					$indexResponseValues = $this->_index($courseTobeIndexed);
					foreach($indexResponseValues as $k=>$indexResponse)
					{
						if($indexResponse!=1)
						{
							$status = 'failed';
							//error_log("Unable to index ".$courseTobeIndexed[$k]['unique_id']);
							$indexFailures[] = array('listingType'=>$row['listingType'],'listingTypeId'=>$row['listingTypeId'], 'operation'=>$row['operation'],'docId'=>$courseTobeIndexed[$k]['unique_id']);
							break;
						}
					}
				}
				catch(Exception $e)
				{
					$status='failed';
					error_log("Unable to index : data retreival failure for ".$row['courseId']." : ".print_r($e,true));
					$indexFailures[] = array('listingType'=>$row['listingType'],'listingTypeId'=>$row['listingTypeId'], 'operation'=>$row['operation']);
				}
			}
			// update completion/failure in index log
			if($forDR != "true"){
				$this->abroadIndexerLib->finishIndexing($row['listingType'],$row['listingTypeId'],$row['operation'],$status);
			}
		}
		$this->_handleIndexFailures($indexFailures, $forDR);
		echo "Complete";
	}
	
	/*
	 * function that deletes all documents for given university and / or course ids
	 * @params: listing_type, listing_type_id
	 */
	public function deleteDocument($listing_type="course", $listing_type_id = array())
	{
		if(count($listing_type_id)==0)
		{
			return false;
		}
		if(is_numeric($listing_type_id))
		{
			$listing_type_id = array($listing_type_id);
		}
		$searchServer = SearchBuilder::getSearchServer($this->searchServer);
		// get delete xml for each id
		foreach($listing_type_id as $id)
		{
			// check if deleting course(s) or univ(s)
			switch($listing_type)
			{
				case "university":
									$xml = $this->deleteDocumentLib->getDeleteXMLByUnivId($id);
									break;
				case "course": 
									$xml = $this->deleteDocumentLib->getDeleteXMLByCourseId($id);
									break;
				case "scholarship":
									$xml = $this->deleteDocumentLib->getDeleteXMLByScholarshipId($id);
									break;
				default: return false;
									break;
			}
			$deleteResponse = $searchServer->deleteDocument($xml);
			//echo "<br>delete $listing_type";var_dump($deleteResponse);
			return $deleteResponse;
		}
	}
	/*
	 * send mail of index failure cases and re-insert them to index log, so that they can be picked next time
	 */
	private function _handleIndexFailures($indexFailures, $forDR="false")
	{
		if($forDR != "true"){
			if(count($indexFailures)>0)
			{ 	// mail us the failed cases 
				$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib');
				$metadata = "/var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br>Func:indexModifiedAbroadListings<br>";
				$commonStudyAbroadLib->selfMailer("Index Failure",$metadata.print_r($indexFailures,true));
				// & add a new row for these as pending
				$abroadcmsmodel = $this->load->model('listingPosting/abroadcmsmodel');
				foreach($indexFailures as $failedCourse)
				{
					$abroadcmsmodel->checkAndAddCourseToIndexLog('course',$failedCourse['courseId'],$failedCourse['operation']);
				}	
			}
		}
	}

	/*
	 * send mail of index failure cases of scholarship and re-insert them to index log, so that they can be picked next time
	 */
	private function _handleIndexFailuresOfScholarships($indexFailures, $forDR="false")
	{
		if($forDR != "true"){
			if(count($indexFailures)>0)
			{ 	// mail us the failed cases 
				$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib');
				$metadata = "/var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br>Func:indexModifiedAbroadScholarships<br>";
				$commonStudyAbroadLib->selfMailer("Index Failure",$metadata.print_r($indexFailures,true));
				// & add a new row for these as pending
				$abroadcmsmodel = $this->load->model('listingPosting/abroadcmsmodel');
				foreach($indexFailures as $failedScholarship)
				{
					$abroadcmsmodel->checkAndAddCourseToIndexLog('scholarship', $failedCourse['scholarshipId'], $failedScholarship['operation']);
				}	
			}
		}
	}
	/*
	 *
	 */
	public function indexCourseAutosuggestorData(){
		$this->validateCron(); // prevent browser access
		$this->benchmark->mark("start_total");
		$this->benchmark->mark("start_delete");
		$xml = $this->deleteDocumentLib->getCourseAutoSuggestorDeleteXML();
		$searchServer = SearchBuilder::getSearchServer($this->searchServer);
		$deleteResponse = $searchServer->deleteDocument($xml);
		$this->benchmark->mark("end_delete");
		error_log("COURSEAS: deletion completed time taken ==".$this->benchmark->elapsed_time('start_delete', 'end_delete'));
		$indexData = $this->abroadIndexerLib->getCourseAutosuggestorData();
		$this->benchmark->mark("start_index");
		$this->_index($indexData,true);
		$this->benchmark->mark("end_index");
		error_log("COURSEAS: Indexing completed time taken ==".$this->benchmark->elapsed_time('start_index', 'end_index'));
		$memoryTaken = (memory_get_usage(TRUE)/(1024*1024));
		error_log('COURSEAS: memory uses '.$memoryTaken);
		$this->benchmark->mark("end_total");
		$timeTaken = $this->benchmark->elapsed_time('start_total', 'end_total');
		error_log("COURSEAS: total time taken ==".$timeTaken);
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib'); 
		$subject = 'Index CourseAutoSuggestor cron success :';
		$msg .= "Autosuggestor index cron executed successfully <br/>";
		$msg .= 'Path : /var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br/>'; 
		$msg .= 'Date Time : '.date('Y-m-d h:i:s').'<br/>'; 
		$msg .= 'Time taken: '.$timeTaken.'<br/>'; 
		$msg .= 'Memory used: '.$memoryTaken.'<br/>'; 
		$msg .= '<br/><br/>Regards,<br/>SA Team'; 
		$commonStudyAbroadLib->selfMailer($subject,$msg);
	}
	/*
	 * to update inventory type field in all docs (one time script)
	 */
	public function indexInventoryType(){
		
		$this->benchmark->mark("start_index");
		$inventoryIndexData = $this->abroadIndexerLib->getInventoryTypeIndexData();
		_p($inventoryIndexData);
		error_log("InventoryTypeIndexing : Indexing start ".date("H:i:s"));
		$this->_index($inventoryIndexData,true);
		error_log("InventoryTypeIndexing : Indexing done ".date("H:i:s"));
		
		
		error_log('indexPopularityCount : memory uses '.(memory_get_usage(TRUE)/(1024*1024)));
		
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib'); 
		$subject = 'InventoryTypeIndexing cron success :';
		$msg .= "Cron executed successfully <br/>";
		$msg .= 'Path : /var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br/>'; 
		$msg .= 'Method : indexInventoryType <br/>'; 
		$msg .= 'Date Time : '.date('Y-m-d h:i:s').'<br/>'; 
		$msg .= '<br/><br/>Regards,<br/>SA Team'; 
		$commonStudyAbroadLib->selfMailer($subject,$msg);
		
		$this->benchmark->mark("end_index");
		error_log("InventoryTypeIndexing : Indexing completed time taken ==".$this->benchmark->elapsed_time('start_index', 'end_index'));
	}
	/*
	 *function that gets univs that were CS & main, but expired the day before
	 */
	public function addExpiredUnivsToIndexLog()
	{
		$this->validateCron(); // prevent browser access
		$univs = $this->abroadIndexerLib->getExpiredUnivs();
		//_p($univs);
		// add to index log
		$abroadcmsmodel = $this->load->model('listingPosting/abroadcmsmodel');
		$abroadcmsmodel->checkAndAddCourseToIndexLog('university',$univs, 'index');
		echo 'all done';
		return ;
	}

	public function indexModifiedAbroadScholarships($forDR="false"){
		$this->validateCron(); // prevent browser access
		$indexLogEntries = $this->abroadIndexerLib->processAbroadIndexLog('scholarship',$forDR);
		// add start time in indexlog
		if($forDR != "true"){
			$this->abroadIndexerLib->startIndexing(array_map(function($a){return array('listingType'=>$a['listingType'], 'listingTypeId'=>$a['listingTypeId']);}, $indexLogEntries));
		}

		// get data for those scholarship that are to be indexed 
		$scholarshipIdTobeIndexed = array_filter(array_map(function($a){if($a['operation'] == 'index'){ return $a['listingTypeId']; } }, $indexLogEntries));

		// delete them first
		$deleteResponse = $this->deleteDocument("scholarship", $scholarshipIdTobeIndexed);

		$indexFailures = array();
		$this->benchmark->mark("start_indexScholarship");
		foreach($indexLogEntries as $row)
		{
			// delete whatever documents are supposed to be deleted
			if($row['operation']=="delete")
			{
				$deleteResponse = $this->deleteDocument('scholarship', $row['listingTypeId']);
				if($deleteResponse === 1) // success
				{
					$status = "complete";
				}
				else{
					$status = "failed";
					$indexFailures[] = array('listingType'=>'scholarship', 'scholarshipId'=>$row['listingTypeId'], 'operation'=>$row['operation'],'message'=>"Unable to delete scholarshipId :".$row['listingTypeId']);
				}
			}
			// index those that need to be indexed
			else if($row['operation']=="index")
			{
				$status='complete';
				try{

					$scholarshipIdTobeIndexed = $this->abroadIndexerLib->getDataRequiredForScholarship(array($row['listingTypeId']));
					$indexResponseValues = $this->_index($scholarshipIdTobeIndexed);
					foreach($indexResponseValues as $k=>$indexResponse)
					{
						if($indexResponse!=1)
						{
							$status = 'failed';
							$indexFailures[] = array('listingType'=>'scholarship','scholarshipId'=>$row['listingTypeId'], 'operation'=>$row['operation'], 'docId'=>$scholarshipIdTobeIndexed[$k]['unique_id']);
							break;
						}
					}
				}
				catch(Exception $e)
				{
					$status='failed';
					error_log("Unable to index : data retreival failure for scholarshipId :".$row['listingTypeId']." : ".print_r($e,true));
					$indexFailures[] = array('listingType'=>'scholarship', 'scholarshipId'=>$row['listingTypeId'], 'operation'=>$row['operation']);
				}
			}
			// update completion/failure in index log
			if($forDR != "true"){
				$this->abroadIndexerLib->finishIndexing($row['listingType'], $row['listingTypeId'], $row['operation'], $status);
			}
		}
		$this->benchmark->mark("end_indexScholarship");
		$timeTaken = $this->benchmark->elapsed_time('start_indexScholarship', 'end_indexScholarship');
		$memoryTaken = (memory_get_usage(TRUE)/(1024*1024));
		if(count($indexLogEntries) > 0 && ($memoryTaken > 20 || $timeTaken > 1)){
			$this->sendSuccessMailForScholarshipIndexing($timeTaken, $memoryTaken);
		}
		if(count($indexFailures) > 0){
			$this->_handleIndexFailuresOfScholarships($indexFailures, $forDR);
		}
		echo "Complete";
	}

	private function sendSuccessMailForScholarshipIndexing($timeTaken, $memoryTaken){
		$commonStudyAbroadLib   = $this->load->library('common/studyAbroadCommonLib');
		$subject = 'Index/Delete Scholarship cron success';
		$msg .= "Scholarship index/delete cron executed successfully <br/>";
		$msg .= 'Path : /var/www/html/shiksha/application/modules/Search/SASearch/controllers/AbroadIndexer.php<br/>Func : indexModifiedAbroadScholarships<br/>';
		$msg .= 'Date Time : '.date('Y-m-d h:i:s').'<br/>';
		$msg .= 'Time taken: '.$timeTaken.'<br/>';
		$msg .= 'Memory used: '.$memoryTaken.'<br/>';
		$msg .= '<br/><br/>Regards,<br/>SA Team';
		$commonStudyAbroadLib->selfMailer($subject, $msg);
	}
	/*
	 * function to index those abroad listings whose fees unit/currency was changed in last 24 hours
	 */
	public function indexListingsByModifiedCurrency()
	{
		$this->validateCron(); // prevent browser access
		ini_set("max_execution_time",-1);
		// get course index data for courses whose fees currency exch rate was changed in past 24 hours
		$indexData = $this->abroadIndexerLib->getCourseIndexDataByModifiedCurrency();
		if(count($indexData)==0)
		{
			echo "Nothing to index";return false;
		}
		// process in batches
		for($i=0;$i<count($indexData);$i=$i+$this->batchSize)
		{
			$ind = array_slice($indexData,$i,$this->batchSize);
			//_p($ind);
			$indexResponseValues = $this->_index($ind);
		}
		echo "Complete";
	}
}
