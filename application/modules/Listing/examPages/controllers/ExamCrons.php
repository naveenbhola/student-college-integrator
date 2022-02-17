<?php 
class ExamCrons extends MX_Controller{
	private $mailingList = array("virender.singh@shiksha.com","akhter.ansari@shiksha.com","pranjul.raizada@shiksha.com","singh.satyam@shiksha.com","abhinav.pandey@shiksha.com");
	function __construct()
	{
	    parent::__construct();
	}
	/**
	 * [generateAllExamsGuide this function will generate guide for all exam which doesn't have any guide attached]
	 * @author Yamini Bisht
	 * @date   2017-10-05
	 * @return [type]     [description]
	 */
	function generateAllExamGuide($examId,$groupId) {
		$this->validateCron();
		ini_set('memory_limit','2000M');
		set_time_limit(0);
		$this->load->builder('ExamBuilder','examPages');
		$this->load->library('examPages/examGuideGenerator');
		$this->load->model('examPages/exammodel');
      	$examBuilder          = new ExamBuilder();
      	$examGuideGeneratorObj = new examGuideGenerator();
      	$examModel  = new exammodel();
      	$this->examRepository = $examBuilder->getExamRepository();

      	if(empty($examId) || empty($groupId)){
      		$groupIds = $examModel->getExamGroupsWithoutGuide(); 
      	}else{
      		$groupIds = array(array('groupId'=>$groupId, 'exam_id'=>$examId));
      	}
        
        if($inputGroupId) {	
			$groupIds = array($inputGroupId);
        }
        $chunkSize = 100;      
        $batchSize = ceil(count($groupIds)/$chunkSize);
        $groupIdsChunk = array();
        for($i = 0; $i < $batchSize; $i++) {
        	$groupIdsChunk[] = array_slice($groupIds, $i*$chunkSize,$chunkSize);
        }
        $this->guideMessage = '';
        $groupProcessedCount = 0;
	    
        foreach ($groupIdsChunk as $key => $groupIdsArr) {    	 
			foreach ($groupIdsArr as $detail) {
				//$detail['exam_id'] = '13741';
				$examBasicObj = $this->examRepository->find($detail['exam_id']);
				if(!empty($examBasicObj)){
					//$detail['groupId'] = '1188';
					$displayData = array();
					$examContentObj = $this->examRepository->findContentFromAPI($detail['groupId'], 'all');
					if(!empty($examContentObj) && !empty($examContentObj['sectionname'])){		
						$displayData['groupId'] = $detail['groupId'];
						$displayData['examId'] = $detail['exam_id'];
						$displayData['examContentObj'] = $examContentObj;	
						$displayData['examBasicObj'] = $examBasicObj;		
						$res = $examGuideGeneratorObj->generateGuide($displayData);
						if($res['status'] == 'error') {
							$this->guideMessage .= $res['msg'].'<br/>';
						}
						else {
							$groupProcessedCount++;
							//$this->coursecache->removeCoursesCache(array($courseId));
						}
					}
				}				
				else {
					$this->guideMessage .= 'Group Object not found for group id: '.$groupId.'<br/>';
				}
			}
        }
        $this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
        $summaryBody = 'Total groups not containing guide : '.count($groupIds).'<br/>Guide created for total groups: '.$groupProcessedCount.' <br/>'.$this->guideMessage;
        $this->sendCronAlert("Summary : ".__METHOD__.' cron', $summaryBody,array());
	}
	/**
     * populate the Exam, ExamGroup and GroupContent Cache
     * @author Nithish Reddy
     * @date   2017-10-23
     */
    function populateExamCache(){
	$this->validateCron();
    	error_log("=== Started====");
    	// initialize
    	ini_set("memory_limit", "2000M");
        ini_set('max_execution_time', -1);
        $batchSize = 500;

        // load dependencies
		$this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();

		// send script start mail
		$scriptStartTime   = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

        $exampagemodel = $this->load->model("examPages/exampagemodel");

        // 1. Get all Exam Ids
        $examIds = $exampagemodel->getAllExamsInLiveStatus();
        $examIds = array_chunk($examIds, $batchSize);

        $this->load->builder('ExamBuilder','examPages');
      	$examBuilder          = new ExamBuilder();
      	$examRepository = $examBuilder->getExamRepository();

        $useCache = false;

        // 2. create the cache
        foreach ($examIds as $examIdsChunk) {

			$examRepository->disableCaching();
			$multiExamObj = $examRepository->findMultiple($examIdsChunk,$useCache);
			$groupIds = array();
			foreach ($multiExamObj as $examObject) {
				$examGroupMapping = $examObject->getGroupMappedToExam();
				foreach ($examGroupMapping as $groupKey => $groupValue) {
					$groupIds[]	 = $groupValue['id'];
					$examRepository->disableCaching();
					$examRepository->findContent($groupValue['id'],'all',false,$useCache);
					$examRepository->findContent($groupValue['id'],'all',true,$useCache);
				}
			}
			$groupIds = array_chunk($groupIds, $batchSize);
			foreach ($groupIds as $groupIdsChunk) {
				$examRepository->disableCaching();
				$examRepository->findMultipleGroup($groupIdsChunk,$useCache);
			}
        }
        error_log("=== Ended====");
        $scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);

    }
	function sendCronAlert($subject, $body, $emailIds){
		if(empty($emailIds))
			$emailIds = $this->mailingList;

		foreach($emailIds as $key=>$emailId)
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, $body, "html", '', 'n');
	}
	function populateExamPageViewCountCache(){
		$this->validateCron();
		// allow cron to run with no time limit
		ini_set("max_execution_time", "-1");

		// load dependencies
		$this->load->library('alerts_client');
		$this->alertClient  = new Alerts_client();
		$exampagemodel = $this->load->model("examPages/exampagemodel");
		$redis_client       = PredisLibrary::getInstance();

		// send cron start mail
		$scriptStartTime   = time();
		$this->sendCronAlert("Started : ".__METHOD__, "");

		$examViewCountHashKey    = "examgroup_view_count";

		// for exam
		$examPageLib = $this->load->library('examPages/ExamPageLib');
		$examViewCount = $examPageLib->fetchExamPageViewCount(365);
		
		// delete previous cache data
		$redis_client->deleteKey(array($examViewCountHashKey));		
		
		// store data in cache
		if(!empty($examViewCount))
		{
			$redis_client->addMembersToHash($examViewCountHashKey,$examViewCount,FALSE);	
		}

		$scriptEndTime = time();
		$timeTaken     = ($scriptEndTime - $scriptStartTime) / 60;
		$text 		   = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Contact Person: Nithish Reddy";
		$this->sendCronAlert("Ended : ".__METHOD__, $text);
	}

	function updateAllExamGuide($examId,$groupId) {
        $this->validateCron();
        ini_set('memory_limit','2000M');
        set_time_limit(0);
        $this->load->builder('ExamBuilder','examPages');
        $this->load->library('examPages/examGuideGenerator');
        $this->load->model('examPages/exammodel');
          $examBuilder          = new ExamBuilder();
          $examGuideGeneratorObj = new examGuideGenerator();
          $examModel  = new exammodel();
          $this->examRepository = $examBuilder->getExamRepository();

          if(empty($examId) || empty($groupId)){
              $groupIds = $examModel->getAllExamGroupsForGuide(); 
          }else{
              $groupIds = array(array('groupId'=>$groupId, 'exam_id'=>$examId));
          }
        
        if($inputGroupId) {    
            $groupIds = array($inputGroupId);
        }
        $chunkSize = 100;      
        $batchSize = ceil(count($groupIds)/$chunkSize);
        $groupIdsChunk = array();
        for($i = 0; $i < $batchSize; $i++) {
            $groupIdsChunk[] = array_slice($groupIds, $i*$chunkSize,$chunkSize);
        }
        $this->guideMessage = '';
        $groupProcessedCount = 0;
        
        foreach ($groupIdsChunk as $key => $groupIdsArr) {         
            foreach ($groupIdsArr as $detail) {
                //$detail['exam_id'] = '13741';
                $examBasicObj = $this->examRepository->find($detail['exam_id']);
                if(!empty($examBasicObj)){
                    //$detail['groupId'] = '1188';
                    $displayData = array();
                    $examContentObj = $this->examRepository->findContentFromAPI($detail['groupId'], 'all');
                    if(!empty($examContentObj) && !empty($examContentObj['sectionname'])){        
                        $displayData['groupId'] = $detail['groupId'];
                        $displayData['examId'] = $detail['exam_id'];
                        $displayData['examContentObj'] = $examContentObj;    
                        $displayData['examBasicObj'] = $examBasicObj;        
                        $res = $examGuideGeneratorObj->generateGuide($displayData);
                        if($res['status'] == 'error') {
                            $this->guideMessage .= $res['msg'].'<br/>';
                        }
                        else {
                            $groupProcessedCount++;
                            //$this->coursecache->removeCoursesCache(array($courseId));
                        }
                    }
                }                
                else {
                    $this->guideMessage .= 'Group Object not found for group id: '.$groupId.'<br/>';
                }
            }
        }
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();
        $summaryBody = 'Total groups not containing guide : '.count($groupIds).'<br/>Guide created for total groups: '.$groupProcessedCount.' <br/>'.$this->guideMessage;
        $this->sendCronAlert("Summary : ".__METHOD__.' cron', $summaryBody,array());
    }	
    function addAdditionalSectionsToExistingPageIds()
    {
    	$this->load->model('examPages/exammodel');
    	$pageIds = $this->exammodel->getPageIdsExistingLiveInSectionTable();
    	$bucket = 500;
    	$insertArray = array();
    	$count = 0;
    	$newSections = array('preptips' => 13,'vacancies' => 14,'callletter' => 15,'news' => 16);
    	foreach ($pageIds as $key => $value) {
    		foreach ($newSections as $sKey => $sValue) {
    			$insertArray[$count]['page_id'] = $value;
	    		$insertArray[$count]['section_name'] = $sKey;
	    		$insertArray[$count]['section_order'] = $sValue;
	    		$insertArray[$count]['status'] = 'live';
	    		$insertArray[$count]['creationTime'] = date('Y-m-d H:i:s');
	    		$count++;
	    		if($count == $bucket)
	    		{
	    			$this->exammodel->insertNewSectionsForExistingPageIds($insertArray);
	    			$insertArray = array();
	    			$count = 0;
	    		}
    		}
    	}
    }

    /***
    *	below function is used for creating TOC for exam page content both HTML and AMP HTML
    */
    function createTocForContent()
    {

        $this->validateCron();
        ini_set('memory_limit','2000M');
        set_time_limit(0);

        $examModel = $this->load->model('examcmsmodel');
        $domDocumentLib = $this->load->library('DomDocumentLib');
        $pageIds = $examModel->getExamPageIds();

        $noNeedTocArray = array('Phone Number', 'Official Website', 'Exam Title', 'applicationformurl');

        foreach ($pageIds as $key => $value) {

            $wikiConvertedValue = array();

            if (!empty($value)) {
                echo "<br/> TOC  Started for  " . $value;

                $noChangedSections = $noNeedTocArray;

                //fetching Exam Page Wiki content from Database
                $wikiContent = $examModel->getExamWikiContentBasedOnPageId($value);

                //continue to next exampage if wiki content is empty in any case
                if (empty($wikiContent)) {
                    echo "<br/> TOC end due to wiki not exist for " . $value;
                    continue;
                }


                echo "<br/> TOC  Processing starts";

                //loop thorugh array of wiki contents and generate TOC content for html
                foreach ($wikiContent as $wikiKey => $wikiValue) {
                    $wikiResult = array();
                    if (!in_array($wikiValue['entity_type'], $noNeedTocArray)) {
                        $wikiResult = $domDocumentLib->getTagsInDynamicHtmlContent($wikiValue['entity_value'], $wikiValue['entity_type'], array('h2','p[contains(@class,"kb-tags")]'), 'table');

                        if ($wikiResult['htmlModified'] == false) {
                            if (is_array($noChangedSections)) {
                                $noChangedSections[] = $wikiValue['entity_type'];
                            }
                            continue;
                        }
                    } else {
                        continue;
                    }
                    $wikiConvertedValue[] = array('page_id' => $value, 'section_name' => $wikiValue['section_name'], 'entity_type' => $wikiValue['entity_type'], 'entity_value' => $wikiResult['html'], 'toc_text' => $wikiResult['tocContent'], 'creationTime' => $wikiValue['creationTime'], 'updatedOn' => $wikiValue['updatedOn'], 'status' => 'live');
                }

                echo "<br/> TOC  Processing Ends";


                if (!empty($wikiConvertedValue)) {


                    echo "<br/> TOC  DB update and insert start";

                    //insert toc and updated html string in database
                    $examModel->updateAndInsertExamWikiData($value, $wikiConvertedValue, $noChangedSections);

                    //adding page id in rabbitMqueue for generating AMP HTML
                    modules::run('common/GlobalShiksha/insertIntoAmpRabbitMQueue', $value, array(), 'exampage');

                    echo "<br/> TOC  Ended for " . $value;
                } else {
                    echo "<br/>No Toc content available for " . $value;
                }
            }
        }
    }

    function generatePdfForExamSections($days = 1){
        $this->validateCron();
        // load dependencies
        $this->load->library('alerts_client');
        $this->alertClient  = new Alerts_client();

        $this->sendCronAlert("Started : ".__METHOD__, "");
        error_log("=== Started====");
        $startTime = time();
        // initialize
        ini_set("memory_limit", "2000M");
        ini_set('max_execution_time', -1);
        $examModel = $this->load->model('examcmsmodel');
        $Ids = $examModel->getExamGroupIds($days);
        $this->load->library('examPages/examGuideGenerator');
        $failedGroupIds = $this->examguidegenerator->generatePdfForExamSections($Ids);
        $endTime = time();
        $timeTaken = ($endTime-$startTime)/60;
        error_log("=== Ended====Time = ".($endTime-$startTime)."ms");
        error_log(" Failed for group Ids : ".print_r($failedGroupIds,true));
        $text          = __METHOD__." Script Ended Successfully.<br/>Time Taken : ".$timeTaken." mins<br/>Failed Group Ids : ".implode(', ', $failedGroupIds)."<br/>Contact Person: Satyam Singh";
        $this->sendCronAlert("Ended : ".__METHOD__, $text);
    }


    function generateThumbNailForExamFiles($notExistThumb=false){
		$this->validateCron();
        $this->load->config('examPages/examPageConfig.php');
        $this->examCache = $this->load->library('examPages/cache/ExamCache');
		ini_set('memory_limit','2000M');
		set_time_limit(0);
		$exammodel = $this->load->model('examPages/exammodel');
		$pdfLibrary = $this->load->library('PdfLibrary');
    	$filesMapping = $exammodel->getFilesData($notExistThumb);
    	$bucket = 500;
    	$mappingData  = array();

    	$totalBuckets = ceil(count($filesMapping) / $bucket);

    	error_log("Thumbanail Image ceration will start for ".$totalBuckets." buckets and one bucket size will be ".$bucket);
    	$bucketCount = 1;

        $nginxPurgeIds = array();

    	foreach ($filesMapping as $key => $filevalue) {

    		error_log("===Started=== Pdf thumbnail creation logic started");

    		$uniqueId = rand(1000,9999);
    		//$fileName = base64_encode($filevalue['file_name']).'_'.$uniqueId.'.jpg';
    		$fileName = time().'_'.$uniqueId.'thumb.jpg';
    		$fileUrlWithDomain = addingDomainNameToUrl(array('url' => $filevalue['file_url'],'domainName' => MEDIA_SERVER));
    		$result = $pdfLibrary->getThumbNailFromPdf($fileUrlWithDomain,$fileName,true);

    		error_log("===Ended=== Pdf thumbnail creation logic ended");

    		if($result['errMsg']){
				error_log("===Error==== Error Genreated For ".$filevalue['id']." ===");
    		}
    		else{
    			$mappingData[] = array('id' => $filevalue['id'], 'thumbnail_url' => $result['imageUrl']);
                $nginxPurgeIds[] = $filevalue['page_id'];
    		}

    		$count++;

    		if($count == $bucket){
    			error_log("===Started=== inserting thumbnail images for bucket".$bucketCount);
    			$insertionStatus = $exammodel->updateFileTableWithThumbnail($mappingData);
    			if($insertionStatus){
    				error_log("===Ended=== ===Error=== Thumbanial created and inserted for group of size 500 ");
    			}else{
    				error_log("===Ended=== Thumbanial created and but insertion Failed for group of size 500 ");
    			}
    			error_log("thumbnail images creation and insertion completed for bucket".$bucketCount);

    			$mappingData = array();
    			$count=0;
    			$bucketCount++;
    		}
    	}
    	if(!empty($mappingData) && count($mappingData) > 0){
    		error_log("===Started=== last bucket thumbnail images for bucket".$bucketCount);
    		$insertionStatus = $exammodel->updateFileTableWithThumbnail($mappingData);
    		if($insertionStatus){
    				error_log("===Ended=== ===Error=== last bucket Thumbanial created and inserted for group of size 500 ");
    			}else{
    				error_log("===Ended=== last bucket Thumbanial created and but insertion Failed for group of size 500 ");
    			}
    	}
        if(!empty($nginxPurgeIds)){

            $examIdsPurge = $exammodel->getExamIdBasedOnPageIds($nginxPurgeIds);
            $arr = array();
            $count = 0;
            foreach ($examIdsPurge as $key => $value) {
                $arr[$count] = array("cache_type" => "htmlpage", "entity_type" => "exampage", "entity_id" => $value['exam_id'], "cache_key_identifier" => "",'added_time' => date('Y-m-d H:i:s'));    
                $this->examCache->deleteCache($value['groupId'],ExamContentKey);
                $this->examCache->deleteCache($value['groupId'],ExamAMPContentKey);
                $count++;
            }

            if(!empty($arr)){
                $shikshamodel = $this->load->model("common/shikshamodel");
                $shikshamodel->insertMultipleCachePurgingQueue($arr);    
            }
        }
    }

    /**
    * below function is used for testing performance of exampage api 
    * Nithish Reddy
    */
    function examPagesPerformanceTest(){
        $this->validateCron();
        ini_set('memory_limit','2000M');
        set_time_limit(0);
        $exammodel = $this->load->model('examPages/exammodel');
        $urlValues = $exammodel->getAllLiveUrls();

        $domainName = "https://apis.shiksha.com";

        //$this->load->library("common/apiservices/APICallerLib");

        foreach ($urlValues as $url) {
            $urlArray = array('url' => $url);
            $data = base64_encode(json_encode($urlArray));
          //  $output = $this->apicallerlib->makeAPICall("EXAM","/apigateway/examapi/v1/info/getExamPage?data=$data","GET",array(),"",array(),"");
            if(!empty($data)){
                $this->makeCurlCall($domainName."/apigateway/examapi/v1/info/getExamPage?data=".$data);    
                sleep(1);
            }
        }
    }
    function makeCurlCall($url)
    {
        if($url == "") {
            return ("NO_VALID_URL_DEFINED");
        }
        $c = curl_init();
            curl_setopt($c, CURLOPT_URL,$url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_POST, 0);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($c, CURLOPT_TIMEOUT, 60);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
        $output =  curl_exec($c);
        curl_close($c);
        return $output;
    }

    function autoSubscribeLegacyUserDataForExams(){
        $this->validateCron();
        $this->load->library('examPages/ExamMainLib');
        $this->exammainlib->processAutoSubscriptionForLegacyUsers();
    }


}
