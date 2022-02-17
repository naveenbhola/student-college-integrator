<?php

class Indexer extends MX_Controller {
	
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
	private $restrictedInstitutes = array(4211, 33544, 33141, 35861, 24507, 24508, 24509, 38726);
	
	public function __construct(){
		$this->load->builder('SearchBuilder', 'search');
		$this->load->helper('search/SearchUtility');
		$this->load->builder("ListingBuilder", "listing");
		$this->config->load('search_config');
		$this->load->model("search/SearchModel", "", true);
		
		$this->listingBuilder = new ListingBuilder();
		$this->searchServer = $this->config->item('search_server');
		$this->validIndexTypes = $this->config->item('search_index_types');
		$this->documentFormat = $this->config->item('document_format');
		$this->listingBuilder = new ListingBuilder();
		$this->searchModel = new SearchModel();

		$this->load->library('indexer/AutoSuggestorIndexing');
		$this->autoSuggestorIndexingLib = new AutoSuggestorIndexing();
		
		$this->setDebugStatus();
		
		$this->logFileName = 'log_data_indexing_solr_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        ini_set('memory_limit', '-1');
	}
	
    public function indexToNewSolr($id,$type='question')
    {
         if($id != null && in_array($type, $this->validIndexTypes) && $type == 'question')
         {
            $indexDataList = false;
            $indexDataList = $this->getDataForQuestionIndex($id,'new');
                if(is_array($indexDataList) && !empty($indexDataList)){
                    $documentGenerator = SearchBuilder::getDocumentGenerator($this->documentFormat);
                    $documentList = $documentGenerator->getDocuments($indexDataList);
                    $searchServer = SearchBuilder::getSearchServer($this->searchServer);
                    $indexResponse = $searchServer->indexDocuments($documentList, $type,'new');
                    echo $indexResponse;
         		}
            
         	return;   
    	}
    	else if($id != null && in_array($type, $this->validIndexTypes) && $type == 'discussion')
         {
            $indexDataList = false;
            $indexDataList = $this->getDataForDiscussionIndex($id, 'new');
                if(is_array($indexDataList) && !empty($indexDataList)){
                    $documentGenerator = SearchBuilder::getDocumentGenerator($this->documentFormat);
                    $documentList = $documentGenerator->getDocuments($indexDataList);
                    $searchServer = SearchBuilder::getSearchServer($this->searchServer);
                    $indexResponse = $searchServer->indexDocuments($documentList, $type,'new');
                    echo $indexResponse;
         		}
            
         	return;   
    	}
}

	public function index($id = null, $type = "institute", $dbCheck = "true", $forDR="false"){
		error_log("Indexing ".$type." id: ".$id."\n", 3, $this->logFilePath);
		
        //we only need to index question documents to new solr.
        if($solr_version =='new' && $type !='question') return;
		
		if($id != null && in_array($type, $this->validIndexTypes)){
			if($forDR != "true"){
				$rowId = $this->searchModel->startIndexing("index", $id, $type);
				if($rowId == null && $dbCheck == "true"){
					error_log("Indexing failed -> No pending indexlog entry exist for ".$type." id: ".$id."\n", 3, $this->logFilePath);
					$this->printlog("No pending post to index for this type/id combination: type = ".$type." id = ". $id);
					return;
				}
			}
			
			$indexDataList = false;
			$indexStartTime = microtime(true);
			switch($type){
				case 'institute':
					$tempIndexerData = array();
					
					try {
						$tempIndexData = $this->getDataForInstituteIndex($id);
					} catch(Exception $e){
						//do nothing
						error_log("Indexing failed -> Exception caught, ".$type." id: ".$id."\n", 3, $this->logFilePath);
						$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");	
					}
					if(is_array($tempIndexData) && !empty($tempIndexData)){
						if(array_key_exists('instituteData', $tempIndexData) && array_key_exists('courseData', $tempIndexData)){
							$courseIndexDataList 		= $this->clubDataForCourseIndex($tempIndexData['instituteData'], $tempIndexData['courseData']);
							$autosuggestorIndexData 	= $this->getDataForAutosuggestorIndex($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$autosuggestorV2IndexData 	= $this->getDataForAutosuggestorV2Index($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$indexDataList 				= array_merge($courseIndexDataList, $autosuggestorIndexData, $autosuggestorV2IndexData);
						}
					}
					break;
				
				case 'course':
                    $tempIndexerData = array();
                    try {
                        $tempIndexData = $this->getDataForCourseIndex($id);
                    } catch(Exception $e){
						//do nothing
                    	error_log("Indexing failed -> Exception caught, ".$type." id: ".$id."\n", 3, $this->logFilePath);
                    	$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
                    }
					if(is_array($tempIndexData) && !empty($tempIndexData)){
						if(array_key_exists('instituteData', $tempIndexData) && array_key_exists('courseData', $tempIndexData)){
							$courseIndexDataList 		= $this->clubDataForCourseIndex($tempIndexData['instituteData'], $tempIndexData['courseData']);
							$autosuggestorIndexData 	= $this->getDataForAutosuggestorIndex($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$autosuggestorV2IndexData 	= $this->getDataForAutosuggestorV2Index($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$indexDataList 				= array_merge($courseIndexDataList, $autosuggestorIndexData, $autosuggestorV2IndexData);
						}
					}
					break;
				
				case 'question':
                    try {
                        $indexDataList = $this->getDataForQuestionIndex($id,$solr_version);

                        //Call to index question search data on 71 solr
                        //$searchIndexResponse = $this->autoSuggestorIndexingLib->autosuggestorQuestionIndexing($id);
                        
                        //Added so that this question will get indexed to new solr as well. (on 82 solr)
                        $this->indexToNewSolr($id,'question');
                    } catch(Exception $e){
						//do nothing
                    	$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
                    }
					break;
				
				case 'article':
					try {
                        $indexDataList = $this->getDataForArticleIndex($id);
                    } catch(Exception $e){
                        //do nothing
                    	$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
                    }
					break;
				
				case 'discussion':
					try {
                        $indexDataList = $this->getDataForDiscussionIndex($id);

                        //Added so that this discussion will get indexed to new solr as well.
                        $this->indexToNewSolr($id,'discussion');
                    } catch(Exception $e){
                        //do nothing
                    	$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
                    }
                    break;
                
				case 'university':
					try { 
						$tempIndexData = $this->getDataForUniversityIndex($id);
						if(is_array($tempIndexData) && !empty($tempIndexData)) {
							if(array_key_exists('universityData', $tempIndexData)){
								$indexDataList[] = $tempIndexData['universityData'];
								if(array_key_exists('abroadInstituteData', $tempIndexData)) {
									$indexDataList = array_merge($indexDataList, $tempIndexData['abroadInstituteData']);
								}
								if(array_key_exists('abroadCourseData', $tempIndexData)) {
									$indexDataList = array_merge($indexDataList, $tempIndexData['abroadCourseData']);
								}
							}
						}
					} catch(Exception $e){
						//do nothing
						$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
					}
					break;
				
				case 'abroadinstitute':
					try{
						$tempIndexData = $this->getDataForAbroadInstituteIndex($id);
						if(is_array($tempIndexData) && !empty($tempIndexData)){
							if(array_key_exists('abroadInstituteData', $tempIndexData)) {
								$indexDataList = array($tempIndexData['abroadInstituteData']);
							}
							if(array_key_exists('universityData', $tempIndexData)){
								$indexDataList[] = $tempIndexData['universityData'];
							}
							if(array_key_exists('abroadCourseData', $tempIndexData)){
								$indexDataList = array_merge($indexDataList, $tempIndexData['abroadCourseData']);
							}
						}						
					} catch(Exception $e){
						//do nothing
						$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
					}
					break;
                
				case 'abroadcourse':
					try{
						$tempIndexData = $this->getDataForAbroadCourseIndex($id);
						if(is_array($tempIndexData) && !empty($tempIndexData)){
							$indexDataList = array($tempIndexData['abroadCourseData']);
							if(array_key_exists('universityData', $tempIndexData)){
								$indexDataList[] = $tempIndexData['universityData'];
							}
							if(array_key_exists('abroadInstituteData', $tempIndexData)){
								$indexDataList[] = $tempIndexData['abroadInstituteData'];
							}
						}
					} catch(Exception $e){
						//do nothing
						$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
					}
					break;

				case 'career':
					try {
						$indexDataList = $this->getDataForCareerIndex($id);
					} catch(Exception $e){
						//do nothing
						$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
					}
					break;
				
				case 'tag':
					try {
                        $indexDataList = $this->getDataForTagIndex($id);
                    } catch(Exception $e){
                        //do nothing
                    	$this->insertIntoIndexingLog($type, $id, 'Failed',"", (microtime(true)-$indexStartTime)*1000 ,"exception caught");
                    }
                    break;
                
                                
				case 'default':
					$tempIndexData = $this->getDataForInstituteIndex($id);
					if(is_array($tempIndexData) && !empty($tempIndexData)){
						if(array_key_exists('instituteData', $tempIndexData) && array_key_exists('courseData', $tempIndexData)){
							$courseIndexDataList = $this->clubDataForCourseIndex($tempIndexData['instituteData'], $tempIndexData['courseData']);
							$autosuggestorIndexData 	= $this->getDataForAutosuggestorIndex($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$autosuggestorV2IndexData 	= $this->getDataForAutosuggestorV2Index($tempIndexData['courseData'], $tempIndexData['instituteData']);
							$indexDataList 				= array_merge($courseIndexDataList, $autosuggestorIndexData, $autosuggestorV2IndexData);
						}
					}
					break;
			}
			$this->printlog($indexDataList);
			$indexingStatus = "failed";
			if(is_array($indexDataList) && !empty($indexDataList)){
				$documentGenerator = SearchBuilder::getDocumentGenerator($this->documentFormat);
				$documentList = $documentGenerator->getDocuments($indexDataList);
				$this->printlog($documentList, true);
				$searchServer = SearchBuilder::getSearchServer($this->searchServer);
				$indexResponse = $searchServer->indexDocuments($documentList, $type);
				$this->printlog($indexResponse);
				$indexResponseValues = array_values($indexResponse);
				if(count(array_unique($indexResponseValues)) == 1 && $indexResponseValues[0] == 1){
					$indexingStatus = "complete";
					$this->insertIntoIndexingLog($type, $id, 'complete',"", (microtime(true)-$indexStartTime)*1000);
				} else {
					$this->insertIntoIndexingLog($type, $id, 'failed',"", (microtime(true)-$indexStartTime)*1000,"Data retrival problem");
					error_log("indexing error: data retrieval has problem for id: ". $id . " type: ". $type);
					$this->printErrorlog("indexing error: data retrieval has problem for id: ". $id . " type: ". $type);
					error_log("Indexing failed -> Some/all documents for this institute not indexed properly, ".$type." id: ".$id."\n", 3, $this->logFilePath);
				}
				if($rowId != null){
					if($forDR != "true"){
						$this->searchModel->finishIndexing($rowId, $indexingStatus);
					}
				}
			} else {
				$indexingStatus = "failed";
				
				$this->insertIntoIndexingLog($type, $id, 'failed',"", (microtime(true)-$indexStartTime)*1000,"Data retrival problem");
				error_log("indexing error: data retrieval has problem for id: ". $id . " type: ". $type);
				$this->printErrorlog("indexing error: data retrieval has problem for id: ". $id . " type: ". $type);
				error_log("Indexing failed -> Data retrieval has problem, ".$type." id: ".$id."\n", 3, $this->logFilePath);
				$this->printlog("no shit with my code son!!");
			    	
				if($rowId != null){
					if($forDR != "true"){
						$this->searchModel->finishIndexing($rowId, $indexingStatus, 'DATA_NA');
					}
				}
			}
			$this->printlog("indexing status: " . $indexingStatus);
		} else {
			$this->printlog("Invalid Arguments:");
			$this->printlog("Valid arguments: id/type");
			$this->printErrorlog("indexing error: invalid arguments id: ". $id . " type: ". $type);
			error_log("indexing error: invalid arguments id: ". $id . " type: ". $type);
			error_log("Indexing failed -> Invalid arguments, id: ". $id . " type: ". $type."\n", 3, $this->logFilePath);
			
			$this->insertIntoIndexingLog($type, $id, 'failed',"", (microtime(true)-$indexStartTime)*1000,"Invalid arguments");
		}
		if($_REQUEST['indexingStatus'])
		_p($indexingStatus);
	}
	
	public function delete($id = null, $type = null, $dbCheck = "true", $forDR="false"){
		if($id != null && in_array($type, $this->validIndexTypes)){
			if($forDR != "true"){
				$rowId = $this->searchModel->startIndexing("delete", $id, $type);
				error_log("Deleting ".$type." id: ".$id."\n", 3, $this->logFilePath);
				if($rowId == null && $dbCheck == "true"){
					error_log("Deletion failed -> No pending indexlog entry exist to delete this type/id combination: type = ".$type." id = ". $id."\n", 3, $this->logFilePath);
					$this->printlog("No pending post to delete for this type/id combination: type = ".$type." id = ". $id);
					return;
				}
			}
			$xml = false;
			$documentDelete = SearchBuilder::getDocumentDeleteInstance($this->searchServer);
			error_log("Complete Data ====$type ====  $id: \n", 3, "/tmp/solr_delete.log");
					
			switch($type){
				case 'institute':
					error_log("Old Institute $id: \n", 3, "/tmp/solr_delete.log");
					return;
					$xml = $documentDelete->getInstituteAndAutosuggestorDeleteXML($id);
					break;
				
				case 'course':
					error_log("Old Course $id: \n", 3, "/tmp/solr_delete.log");
					return;
				return;
					$xml = $documentDelete->getCourseAndAutosuggestorDeleteXML($id);
					break;
				
				case 'autosuggestor':
					error_log("Old AutoSugestor $id: \n", 3, "/tmp/solr_delete.log");
					return;
					$xml = $documentDelete->getAutosuggestorDeleteXML($id);
					break;
				
				case 'question':
					$xml = $documentDelete->getQuestionDeleteXML($id);
					
					//Call to delete question search data on 71 solr
                    //$searchDeleteResponse = $this->autoSuggestorIndexingLib->deleteAutosuggestorQuestionDocument($id);
                    
					break;
				
				case 'discussion':
					$xml = $documentDelete->getDiscussionDeleteXML($id);
					break;
				
				case 'article':
					$xml = $documentDelete->getArticleDeleteXML($id);
					break;
                
				case 'university':

						$xml = $documentDelete->getUniversityDeleteXML($id);
						break;
				
				case 'abroadinstitute':
						$xml = $documentDelete->getAbroadInstituteDeleteXML($id);
						break;
					
				case 'abroadcourse':
						$xml = $documentDelete->getAbroadCourseDeleteXML($id);
						break;

				case 'career':
						$xml = $documentDelete->getCareerDeleteXML($id);
						break;

				case 'tag':
						$xml = $documentDelete->getTagDeleteXML($id);
						break;
			}
			$this->printlog($xml, true);
			$deletionStatus = "failed";
			if($xml != false){
				$searchServer = SearchBuilder::getSearchServer('solr');
				$deleteResponse = $searchServer->deleteDocument($xml, $type);

                //question need to be deleted from both the solr indexes.
                if($type == 'question' || $type == 'discussion')
                {
                    $deleteResponse = $searchServer->deleteDocument($xml, $type,'new');
                }

				$this->printlog("delete response");
				$this->printlog($deleteResponse);
				if($deleteResponse == 1){
					$deletionStatus = "complete";
				}
			} else {
				$deletionStatus = "failed";
				$this->printlog("no shit with my code son!!");
				//error_log("Deletion failed ".$type." id: ".$id."\n", 3, $this->logFilePath);
				error_log("Deletion failed -> xml == false, ".$type." id: ".$id."\n", 3, $this->logFilePath);
			}
			$this->printlog("deletion status: " . $deletionStatus);
			if($rowId != null){
				if($forDR != "true"){
					$this->searchModel->finishIndexing($rowId, $deletionStatus);
				}
			}
		} else {
			$this->printlog("Invalid Arguments:");
			$this->printlog("Valid arguments: id/type");
			$this->printErrorlog("deletion error: invalid arguments id: ". $id . " type: ". $type);
			error_log("deletion error: invalid arguments id: ". $id . " type: ". $type);
			error_log("Deletion failed -> Invalid arguments, id: ". $id . " type: ". $type."\n", 3, $this->logFilePath);
		}
	 	if($_REQUEST['indexingStatus'])	
		_p($deletionStatus);
	}
	
	public function addToQueue($id = null, $type = null, $operation = "index", $dbCheck = "true"){
		$returnArray = array("status" => false);
		if($id == null || $type == null){
			$returnArray['response_text'] = "invalid input arguments";
			return $returnArray;
		}
		if((int)$id == 35861 && $type == 'institute'){
			$returnArray['response_text'] = "Restricted institute";
			_p($returnArray);
			return $returnArray;
		}
		$validIndexTypes = $this->validIndexTypes;
		$validOperations = array('index', 'delete');
		if(in_array($type, $validIndexTypes) && in_array($operation, $validOperations)){
			$checkIfAlreadyExist = null; 
			if($dbCheck != "false"){ // If dbcheck value is anything other than false check for the entry in DB
				$checkIfAlreadyExist = $this->searchModel->entryExistInIndexQueue($operation, $id, $type);
			}
			if($checkIfAlreadyExist != null){
				$returnArray['response_text'] = "already present in queue";
			} else {
				$rowId = $this->searchModel->addToIndexQueue($operation, $id, $type);
				if($rowId != null){
					$returnArray['status'] = true;
					$returnArray['response_text'] = "successfully added";
					$returnArray['id'] = $rowId;
				} else {
					$returnArray['response_text'] = "db operation failed";
				}
			}
		} else {
			$returnArray['response_text'] = "Operation/Listing type is not valid";
		}
		$this->printlog($returnArray);
		return $returnArray;
	}
	
	public function processIndexLog($forDR="false"){
		$this->validateCron(); // prevent browser access
		$this->debug = true;
		
		$searchModel = new SearchModel();
		$alreadyRunningCron = $searchModel->getSearchIndexCronStatus();
		$startCronProcessing = false;
		
		global $isUpdateCron;
		$isUpdateCron = true;

		if(empty($alreadyRunningCron)){
			$this->printlog("NO CRON RUNNING, STARTED NEW CRON");
			//No cron is running at this time, start new cron
			$cronId = $searchModel->startSearchIndexingCron();
			$startCronProcessing = true;
			$this->printlog("NEW CRON ID: ". $cronId);
		} else {
			$this->printlog("CRON ALREADY RUNNING");
			$this->printlog("CRON DETAILS: ");
			$this->printlog($alreadyRunningCron);
			//Already there is some cron running
			$attempts = $alreadyRunningCron['attempts'];
			$runningCronId = $alreadyRunningCron['id'];
			if($attempts < $this->MAX_CRON_ATTEMPTS){
				//The attempts are less than MAX CRON ATTEMPTS, update the attempt count and let it be in runnning state for some more time
				$attempts++;
				$searchModel->updateSearchIndexingCronAttempts($runningCronId, $attempts);
				$this->printlog("CRON ATTEMPTS UPDATED FROM " . ($attempts - 1) . " TO ". $attempts);
				sendMailAlert('Terminating cron which has been running for a long time: <br/>CRON ATTEMPTS UPDATED FROM  '. ($attempts - 1) . ' TO '. $attempts .', Search delta indexing cron',array(), TRUE);
			} else {
				$this->printlog("CRON MAX ATTEMPTS REACHED");
				//Cron has reached MAX CRON ATTEMPTS, TERMINATE this cron entry and start new cron entry
				$updateStatus = $searchModel->updateSearchIndexingCronStatus($runningCronId, 'TERMINATED');
				if(!empty($updateStatus)){
					$this->printlog("OLD CRON TERMINATED");
					$cronId = $searchModel->startSearchIndexingCron();
					if($cronId !== false && $cronId > 0){
						$this->printlog("NEW CRON STARTED");
						$this->printlog("NEW CRON ID: " . $cronId);
						sendMailAlert('Terminated old cron starting new cron: <br/>CRONID  '. ($cronId) . ', Search delta indexing cron',array(), TRUE);
						//If new cron has successfully started, than only start cron processing
						$startCronProcessing = true;
					}
				}
			}
		}
		
		if($startCronProcessing === true){
			$maxListings = -1;
			$batchSize = 100;
			$indexingFlag = true;
			$start = 0;

			$startDate = '';
			if($forDR == "true"){
				ini_set('max_execution_time', '-1');
				$startDate = date("Y-m-d H:i:s",strtotime(date()." - ".SOLR_INDEXING_INTERVAL." hours"));
			}

			while($indexingFlag){
				$logEntries = $searchModel->getIndexQueueEntries($start, $batchSize, $startDate);
				$this->printlog("ENTRIES: " . print_r($logEntries, true));
				if(is_array($logEntries) && !empty($logEntries) && count($logEntries) > 0){
					foreach($logEntries as $entry){
						$operation    = $entry['operation'];
						$listing_id   = $entry['listing_id'];
						$listing_type = $entry['listing_type'];
						switch($operation){
							case 'index':
								if($listing_type == "institute" || $listing_type == "course"){
									if($listing_type == 'institute' && $listing_id == 35861){
									} else {
										//$this->delete($listing_id, $listing_type);
										$this->index($listing_id, $listing_type, "true", $forDR);
									}
									//$this->delete($listing_id, $listing_type, "true", $forDR);
									//$this->index($listing_id, $listing_type, "true", $forDR);
									if($listing_type == "institute"){
										modules::run('search/SearchQER/syncQERInstituteById', $listing_id);	
									}
								} else {
									$this->index($listing_id, $listing_type, "true", $forDR);
								}
								break;
							case 'delete':
								$this->delete($listing_id, $listing_type, "true", $forDR);
								if($listing_type == "institute"){
									modules::run('search/SearchQER/syncQERDeletedInstituteById', $listing_id);
								}
								break;
						}
					}
					$start = $start + $batchSize;
				} else {
					$indexingFlag = false;
				}
				if($start >= $maxListings && $maxListings != -1){
					$indexingFlag = false;
				}
			}
			//CRON work is done, now set the stats as FINISHED
			if($forDR != "true"){
				$searchModel->updateSearchIndexingCronStatus($cronId, 'FINISHED');
			}
			
			$this->printlog("CRON WORK DONE");
		}
	}
	
	private function getDataForInstituteIndex($id = null) {
		if($id == null){
			return array();
		}
		$returnList = false;
		$instituteFinder = SearchBuilder::getFinder('institute');
		$instituteData = $instituteFinder->getData($id);
		
		if(!empty($instituteData) && is_array($instituteData)){
			$returnList = array();
			$instituteRepos = $this->listingBuilder->getInstituteRepository();
			$instituteRepos->disableCaching();
			
			$locationWiseCourseList = $instituteRepos->getLocationwiseCourseListForInstitute($instituteData['institute_id']);
			$courses = array();
			foreach($locationWiseCourseList as $courseList){
				$courses = array_merge($courses, $courseList['courselist']);
			}
			$courses = array_unique($courses);
			$courseDataList = array();
			$courseFinder = SearchBuilder::getFinder('course');
			foreach($courses as $courseId){
				$courseData = $courseFinder->getData($courseId);
				if(!empty($courseData) && is_array($courseData)){
					$courseDataList[$courseId] = $courseData;	
				}
			}
			
			$tempCourseList = array();
			foreach($courseDataList as $courseList){
				foreach($courseList as $course){
					$tempCourseList[] = $course;
				}
			}
			$instituteFlagShipCourse = $courseFinder->getFlagShipCourseFromCourses($tempCourseList);
			$instituteData['institute_flagship_courseid'] = $instituteFlagShipCourse;
			$returnList['instituteData'] = $instituteData;
			$returnList['courseData'] = $courseDataList;
		}
		return $returnList;
	}
	
        
	private function getDataForUniversityIndex($id = null){
		if($id == null){
			return false;
		}
		$returnList = false;
		$universityFinder = SearchBuilder::getFinder('university');
		$university = $universityFinder->getData($id);
		if(!empty($university) && is_array($university)){
			$instituteData = array();
			
			//get departments
			$data = $universityFinder->getDepartmentIdsAndCourseIds();
			$departmentListInfo = array();
			$abroadInstituteFinder = SearchBuilder::getFinder('abroadinstitute');
			foreach($data['departmentIds'] as $departmentId){
				$departmentInfo = $abroadInstituteFinder->getData($departmentId);
				if(!empty($departmentInfo) && is_array($departmentInfo)) {
					$departmentListInfo[] = $departmentInfo;
				}
			}
			
			//get courses
			$courseListInfo = array();
			$abroadCourseFinder = SearchBuilder::getFinder('abroadcourse');
			foreach($data['courseIds'] as $courseId){
				$abroadCourseInfo = $abroadCourseFinder->getData($courseId);
				
				//additional course fields
				$abroadCourseInfo['sa_course_university_type'] = $university['university_funding'];
				$abroadCourseInfo['sa_course_university_accomodation'] = $university['university_accomodation'];
				$abroadCourseInfo['sa_course_university_logolink'] = $university['university_logolink'];
				$abroadCourseInfo['sa_course_university_photo'] = $university['university_photo'];
				$abroadCourseInfo['sa_course_university_websitelink'] = $university['university_websitelink'];
				
				if(!empty($abroadCourseInfo) && is_array($abroadCourseInfo)) {
					$courseListInfo[] = $abroadCourseInfo;
				}
			}
			
			$returnList['universityData'] = $university;
			if(!empty($departmentListInfo) && is_array($departmentListInfo)) {
				$returnList['abroadInstituteData'] = $departmentListInfo;
			}
			if(!empty($courseListInfo) && is_array($courseListInfo)) {
				$returnList['abroadCourseData'] = $courseListInfo;
			}
		}
		return $returnList;
	}
        
	private function getDataForAbroadCourseIndex($id = null) {
		if($id == null) {
			return false;
		}
		$returnList = false;
		$abroadCourseFinder = SearchBuilder::getFinder('abroadcourse');
		$abroadCourse = $abroadCourseFinder->getData($id);
		if(!empty($abroadCourse) && is_array($abroadCourse)) {
			$universityFinder = SearchBuilder::getFinder('university');
			$universityData = $universityFinder->getData($abroadCourseFinder->getUniversityId());
			if(!empty($universityData) && is_array($universityData)) {
				$returnList['universityData'] = $universityData;
			}
			
			$instituteFinder = SearchBuilder::getFinder('abroadinstitute');
			$instituteData = $instituteFinder->getData($abroadCourseFinder->getInstituteId());
			if(!empty($instituteData) && is_array($instituteData)) {
				$returnList['abroadInstituteData'] = $instituteData;
			}
			
			//additional course fields
			$abroadCourse['sa_course_university_type'] = $universityData['university_funding'];
			$abroadCourse['sa_course_university_accomodation'] = $universityData['university_accomodation'];
			$abroadCourse['sa_course_university_logolink'] = $universityData['university_logolink'];
			$abroadCourse['sa_course_university_photo'] = $universityData['university_photo'];
			$abroadCourse['sa_course_university_websitelink'] = $universityData['university_websitelink'];
			
			$returnList['abroadCourseData'] = $abroadCourse;
		}
		
		return $returnList;
	}
        
	private function getDataForAbroadInstituteIndex($id = null){
		if($id == null){
			return false;
		}
		
		$returnList = false;
		$abroadInstituteFinder = SearchBuilder::getFinder('abroadinstitute');
		$abroadInstitute = $abroadInstituteFinder->getData($id);
		if(!empty($abroadInstitute) && is_array($abroadInstitute)){
			$universityData = array();
			$universityFinder = SearchBuilder::getFinder('university');
			$universityData = $universityFinder->getData($abroadInstituteFinder->getUniversityId());
			
			$abroadCourseData = array();
			$courseIds = $abroadInstituteFinder->getCourseIds();
			$abroadCourseFinder = SearchBuilder::getFinder('abroadcourse');
			foreach($courseIds as $courseId) {
				$abroadCourseInfo = $abroadCourseFinder->getData($courseId);
				
				//additional course fields
				$abroadCourseInfo['sa_course_university_type'] = $universityData['university_funding'];
				$abroadCourseInfo['sa_course_university_accomodation'] = $universityData['university_accomodation'];
				$abroadCourseInfo['sa_course_university_logolink'] = $universityData['university_logolink'];
				$abroadCourseInfo['sa_course_university_photo'] = $universityData['university_photo'];
				$abroadCourseInfo['sa_course_university_websitelink'] = $universityData['university_websitelink'];
				
				if(!empty($abroadCourseInfo) && is_array($abroadCourseInfo)) {
					$abroadCourseData[] = $abroadCourseInfo;
				}
			}
			
			if(!empty($universityData) && is_array($universityData)) {
				$returnList['universityData'] = $universityData;
			}
			if(!empty($abroadInstitute) && is_array($abroadInstitute)) {
				$returnList['abroadInstituteData'] = $abroadInstitute;
			}
			if(!empty($abroadCourseData) && is_array($abroadCourseData)) {
				$returnList['abroadCourseData'] = $abroadCourseData;
			}
		}
		
		return $returnList;
	}
        
	private function getDataForCourseIndex($id = null){
		if($id == null){
			return false;
		}
		$returnList = false;
		$courseFinder = SearchBuilder::getFinder('course');
		$course = $courseFinder->getData($id);
		if(!empty($course) && is_array($course)){
			$courseData = array();
			$courseData[$id] = $course;
			if(!empty($courseData)){
				$instituteFinder = SearchBuilder::getFinder('institute');
				$instituteData = $instituteFinder->getData($courseFinder->getCourseInstituteId());
				if(!empty($instituteData) && is_array($instituteData)){
					$returnList['instituteData'] = $instituteData;
				}
                $returnList['courseData'] = $courseData;
			}
		}
		return $returnList;
	}
	
	private function getDataForCareerIndex($id = null) {
		if($id == null){
			return false;
		}
		$returnList 	= false;
		$careerFinder 	= SearchBuilder::getFinder('career');
		$career 		= $careerFinder->getData($id);
		
		if(!empty($career) && is_array($career)){
			$returnList = array();
			array_push($returnList, $career);	
		}
		return $returnList;
	}
	
	private function getDataForQuestionIndex($id = null,$solr_version='old'){
		if($id == null)
		{
			return array();
		}
		$returnList = false;
		$questionFinder = SearchBuilder::getFinder('question');
		$questionData = $questionFinder->getData($id,$solr_version);
		if(is_array($questionData) && !empty($questionData)){
			$returnList = array();
			array_push($returnList, $questionData);	
		}
		return $returnList;
	}
	
	private function getDataForArticleIndex($id = null){
		if($id == null){
			return false;
		}
		$returnList = false;
		$articleFinder = SearchBuilder::getFinder('article');
		$articleData = $articleFinder->getData($id);
		if(is_array($articleData) && !empty($articleData)){
			$returnList = array();
			array_push($returnList, $articleData);	
		}
		return $returnList;
	}
	
	
	private function getDataForDiscussionIndex($id = null,$solr_version='old'){
		if($id == null){
			return false;
		}
		$returnList = false;
		$discussionFinder = SearchBuilder::getFinder('discussion');
		$discussionData = $discussionFinder->getData($id,$solr_version);
		if(is_array($discussionData) && !empty($discussionData)){
			$returnList = array();
			array_push($returnList, $discussionData);	
		}
		return $returnList;
	}

	private function getDataForTagIndex($id = null){
		if($id == null){
			return false;
		}
		$returnList = false;
		$tagFinder = SearchBuilder::getFinder('tag');
		$tagData = $tagFinder->getData($id);
		if(is_array($tagData) && !empty($tagData)){
			$returnList = array();
			array_push($returnList, $tagData);	
		}
		return $returnList;
	}
	
	private function getDataForAutosuggestorIndex($courseData, $instituteData){
		$autosuggestorIndexData = array();
		$autosuggestorFinder = SearchBuilder::getFinder('autosuggestor');
		foreach($courseData as $courseListByLocation){
			foreach($courseListByLocation as $course){
				$tempData = $autosuggestorFinder->getData($course, $instituteData);
				$autosuggestorIndexData = array_merge($autosuggestorIndexData, $tempData);
			}
		}
		return $autosuggestorIndexData;
	}

	private function getDataForAutosuggestorV2Index($courseData, $instituteData) {
		$autosuggestorIndexData = array();
		$autosuggestorFinder = SearchBuilder::getFinder('autosuggestorv2');
		$searchModel = new SearchModel();
		$courseData['ldbCourseNameWithCustomIds'] = $searchModel->getLdbIdsWithSameName();
		$tempData = $autosuggestorFinder->getData($courseData, $instituteData);
		if(!empty($tempData)){
			$autosuggestorIndexData = array_merge($autosuggestorIndexData, $tempData);
		}
		return $autosuggestorIndexData;
	}
	
	private function clubDataForCourseIndex($instituteIndexData, $courseIndexData){
		$returnValue = false;
		if(!empty($instituteIndexData) && !empty($courseIndexData)){
			$data = array();
			foreach($courseIndexData as $courseList){
				foreach($courseList as $course){
					$tempIndexerData = array();
					$tempIndexerData = array_merge($instituteIndexData, $course);
					array_push($data, $tempIndexerData); 	
				}
			}
			$returnValue = $data;
		}
		return $returnValue;
	}
	
	public function logIndexingProcess($cronType = "FULL_SEARCH_INDEX", $status = "start"){
		$searchModel = new SearchModel();
		switch($cronType){
			case 'FULL_SEARCH_INDEX':
				if($status == "start"){
					$searchModel->startSearchIndexingCron('FULL_SEARCH_INDEX', 'LOG_ENTRY', 'full index start');
				}
				else if($status == "end"){
					$searchModel->startSearchIndexingCron('FULL_SEARCH_INDEX', 'LOG_ENTRY', 'full index end');
				}
				break;
			
			case 'SEARCH_SPONSORED_INDEX':
				if($status == "start"){
					$searchModel->startSearchIndexingCron('SEARCH_SPONSORED_INDEX', 'LOG_ENTRY', 'search sponsored index start');
				}
				else if($status == "end"){
					$searchModel->startSearchIndexingCron('SEARCH_SPONSORED_INDEX', 'LOG_ENTRY', 'search sponsored index end');
				}
				break;
		}
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
	

	public function indexData($dataType = "institute", $order = 'ASC', $offsetId = 0, $durationInDays = -1){
		$this->validateCron(); // prevent browser access
		ini_set('max_execution_time', '-1');
		error_log("Data indexing started. Time: ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
		$this->debug = false;
		$this->debugErrorLog = true;
		$validIndexTypes = $this->validIndexTypes;
		$restrictedInstitutes = $this->restrictedInstitutes;
		//$restrictedInstitutes = array();
		global $isFullIndexCron;
		$isFullIndexCron = true;
		if(in_array($dataType, $validIndexTypes)) {
			error_log("Indexing data for ".$dataType." in progress.....\n", 3, $this->logFilePath);
			$this->logIndexingProcess('FULL_SEARCH_INDEX', 'start');
			
			$maxListings = -1;
			$batchSize = 100;
			if($dataType == 'university' || $dataType == 'abroadinstitute' || $dataType == 'abroadcourse'|| $dataType == 'tag') {
				$batchSize = 1000;
			}
			$indexingFlag = true;
			$start = 0;
			error_log("Order: ".$order." \n", 3, $this->logFilePath);
			if(!empty($offsetId)) {
				error_log("From ".$dataType." id: ".$offsetId." \n", 3, $this->logFilePath);
			}
			while($indexingFlag) {
				error_log("Batch: ".$start." - ".($start + $batchSize - 1)." \n", 3, $this->logFilePath);
				error_log("Getting data for ".$dataType." from database..... \n", 3, $this->logFilePath);
				$ids = $this->searchModel->getDataForIndexing($dataType, $start, $batchSize, $order, $offsetId, $durationInDays);
				if(is_array($ids) && !empty($ids) && count($ids) > 0) {
					foreach($ids as $id) {
						if($dataType == 'institute'){
							if(!in_array($id, $restrictedInstitutes)){
								//$this->delete($id, $dataType, "false");
								$this->index($id, $dataType, "false");
							}	
						}/*elseif(in_array($dataType ,array('university','abroadinstitute','abroadcourse'))){
							$this->delete($id, $dataType, "false");
							$this->index($id, $dataType, "false");
						}*/else {
							$this->index($id, $dataType, "false");
						}
					}
					$start = $start + $batchSize;
				} else {
					$indexingFlag = false;
				}
				if($start >= $maxListings && $maxListings != -1){
					$indexingFlag = false;
				}
				
			}
			$this->logIndexingProcess('FULL_SEARCH_INDEX', 'end');
		}
		error_log("Data indexing ended. Time: ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
	}
	
	    public function deleteData($dataType = "university", $durationInDays = -1){
		$this->validateCron(); // prevent browser access
		$this->debug = false;
		$this->debugErrorLog = true;
		$validIndexTypes = $this->validIndexTypes;
		if(in_array($dataType, $validIndexTypes)){
			$this->logIndexingProcess('FULL_SEARCH_DELETE', 'start');
			
			$maxListings = -1;
			$batchSize = 100;
			$indexingFlag = true;
			$start = 0;
			while($indexingFlag){
				$ids = $this->searchModel->getDataForDeleting($dataType, $start, $batchSize, $durationInDays);
				if(is_array($ids) && !empty($ids) && count($ids) > 0){
					foreach($ids as $id){
						$this->delete($id, $dataType, "false");
					}
					$start = $start + $batchSize;
				} else {
					$indexingFlag = false;
				}
				if($start >= $maxListings && $maxListings != -1){
					$indexingFlag = false;
				}
			}
			$this->logIndexingProcess('FULL_SEARCH_DELETE', 'end');
		}
	}
	
	public function stats(){
		$results = array();
		$results['data'] = array();
		$error = array();
		
		$currentDay =  $_REQUEST['from_day'];
		$currentMonth = $_REQUEST['from_month'];
		$currentYear = $_REQUEST['from_year'];
		$currentToDay =  $_REQUEST['to_day'];
		$currentToMonth = $_REQUEST['to_month'];
		$currentToYear = $_REQUEST['to_year'];
		if(empty($currentDay)){
			$currentDay = date('j');
		}
		if(empty($currentMonth)){
			$currentMonth = date('n');
		}
		if(empty($currentYear)){
			$currentYear = date("Y");
		}
		if(empty($currentToDay)){
			$currentToDay = date('j');
		}
		if(empty($currentToMonth)){
			$currentToMonth = date('n');
		}
		if(empty($currentToYear)){
			$currentToYear = date("Y");
		}
		$fromDateString = $currentDay . '-'. $currentMonth . '-' . $currentYear;
		$toDateString = $currentToDay . '-'. $currentToMonth . '-' . $currentToYear;
		if(strtotime($fromDateString) <= strtotime($toDateString)){
			$data = $this->searchModel->getSearchStats($fromDateString, $toDateString);
		}
		$results['data'] = $data;
		$this->load->view('searchstats', $results);
	}
	
	function insertIntoIndexingLog($type,$typeId,$status,$indexedFrom = "",$timeTaken,$errorMsg){
		if(empty($indexedFrom)) {
			$indexedFrom = "link";
			global $isFullIndexCron;
			global $isUpdateCron;
			if($isFullIndexCron) {
				$indexedFrom = "FullIndexCron";
			} else if($isUpdateCron) {
				$indexedFrom = "UpdateCron";
			}
		}
		if($indexedFrom == "FullIndexCron") {
		$logData = $type.",".$typeId.",".$indexedFrom.",".date("H:i:s").",".$timeTaken.",".$status.",".$errorMsg."\n";
		$fileName = "index_".$type."_".date("d-m-Y").".log";	
		error_log($logData, 3, '/tmp/'.$fileName);
		}
	}
	
	function indexLogs() {
	  
      	$requestedLog = $this->_makeRequest();
        $displayData ['loggedData'] = $this->readIndexLogs($requestedLog);
		
		$displayData ['requestedLog'] = $requestedLog;
		$displayData ['types'] = array('Institute','Course','University','Exam','Article','Question','Discussion');
		$displayData ['dates']  = array_unique($dates);
		$displayData ['statusArray']  = array('All','Complete','Failed','Pending','Processing');
		$displayData ['indexedFrom']  = array('All','FullIndexCron','UpdateCron');
		
		$this->load->view('indexLogs', $displayData);
	}
	
	private function _makeRequest(){
		$requestedLog['type'] = $this->input->getRawRequestVariable('type');
		$requestedLog['fromDate'] = $this->input->getRawRequestVariable('fromDate');
		$requestedLog['fromHour'] = $this->input->getRawRequestVariable('fromHour');
		$requestedLog['fromMinutes'] = $this->input->getRawRequestVariable('fromMinutes');
		$requestedLog['status'] = $this->input->getRawRequestVariable('status');
		$requestedLog['toDate'] = $this->input->getRawRequestVariable('toDate');
		$requestedLog['toHour'] = $this->input->getRawRequestVariable('toHour');
		$requestedLog['toMinutes'] = $this->input->getRawRequestVariable('toMinutes');
		
		if(empty($requestedLog['fromDate']) || empty($requestedLog['toDate'])) {
			$requestedLog['fromDate'] = date("Y-m-d");
			$datetime = new DateTime('tomorrow');
			$requestedLog['toDate']   =  $datetime->format('Y-m-d');
			$requestedLog['toHour'] = $requestedLog['toMinutes'] = $requestedLog['fromHour'] = $requestedLog['fromMinutes'] = "00";
		}
		
		if(empty($requestedLog['status'] )) {
			$requestedLog['status'] = "All";
		}
		if(empty($requestedLog['type'] )) {
			$requestedLog['type'] = "Institute";
		} 		
	 	
	 	return $requestedLog;
	}

	function datediff($date1, $date2) {
		return round(abs($date1 - $date2) / (60 * 60 * 24));
	}
	
	function readIndexLogs($requestedLog){
		$searchModel = new SearchModel();
		$result = $searchModel->fetchResultForIndexLogs($requestedLog);
		
		return $result;
	}
	
	function insertIntoInProgressCache($type,$typeId,$indexedFrom) {
	$cache = $this->load->library('listing/cache/ListingCache');
	$inProgressIds = $cache->getIndexingInProgressIds();
	unset($inProgressIds[$type][$typeId]);
	$inProgressIds[$type][$typeId] = array('time' =>date('y-m-d H:i'),'indexedFrom'=>$indexedFrom);
	$cache->storeIndexingInProgressIds($inProgressIds);
	}
	
	function deleteFromInProgressCache($type,$typeId) {
		$cache = $this->load->library('listing/cache/ListingCache');
		$inProgressIds = $cache->getIndexingInProgressIds();
		unset($inProgressIds[$type][$typeId]);
		$cache->storeIndexingInProgressIds($inProgressIds);
		
	}
	
	function getInProgressIds($type) {
		$cache = $this->load->library('listing/cache/ListingCache');
	     $data = $cache->getIndexingInProgressIds();
	     return $data[$type];
	}
	
	function _filterIndexResult($requestedLog,$loggedData) {
        if($requestedLog['indexedFrom'] == "All" && $requestedLog['status'] == "All") {
        	return $loggedData;
        }
        
        $filteredData = array();
        
		foreach($loggedData as $rowId => $data) {
		if(($requestedLog['indexedFrom'] != "All" && $requestedLog['status'] != "All")) {
	  		
	  	   	if(strtolower($data['status']) == strtolower($requestedLog['status']) && strtolower($data['indexedFrom']) == strtolower($requestedLog['indexedFrom'])) {
	  	   		$filteredData[$rowId] = $data;
	  	   	}
	  	} else if(($requestedLog['indexedFrom'] != "All")) {
	  		
	  		if(strtolower($data['indexedFrom']) == strtolower($requestedLog['indexedFrom'])) {
	  			$filteredData[$rowId] = $data;
	  		}
	  	} else {
	  		if(strtolower($data['status']) == strtolower($requestedLog['status'])) {
	  			$filteredData[$rowId] = $data;
	  		}
	  	}
	  }
	  
	  return $filteredData;
	}
	
	function reIndexFailedInstitutes() {
		$restrictedInstitutes = $this->restrictedInstitutes;
		$handle = fopen('/tmp/failedinstitutes.txt', "r");
	    if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$instituteId = trim($line);
				if(!in_array($instituteId, $restrictedInstitutes)){
					$this->delete($instituteId, 'institute', "false");
					$this->index($instituteId, 'institute', "false");
				}
			}
		} else {
			_p("Error opening file");
	    }
	}
}