<?php
class CategoryPageSeoCron extends MX_Controller {
	function _init() {
		ini_set("memory_limit", '-1');
		ini_set('max_execution_time', -1);
		
		$this->logFileName = 'log_category_page_seo_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;
		
		//load library that calls the script
		$this->load->library('nationalCategoryList/CategoryPageSeoLib');
		$this->categoryPageSEOLib = new CategoryPageSeoLib();
		
		//load library to send mail
        $this->load->library('alerts_client');
        $this->alertClient = new Alerts_client();
	}

	function processSEORules() {
		$this->validateCron(); // prevent browser access
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		error_log("Section: Cron started for creating category page URLs at ".date('y-m-d H:i')."\n", 3, $this->logFilePath);
		$this->_init();

		//send starting mail
		$this->sendMailToDev('started');

		//get all rules from DB in prioritised order
		$rules = $this->categoryPageSEOLib->getRulesToBeProcessed('live');
		$rulesCreated = $this->categoryPageSEOLib->processRules($rules);

		//get category page seo data by rulesIds and invalidate the cache for category page interlinking
		// if(!empty($rulesCreated))
		// 	$this->categoryPageSEOLib->inValidateCatPageInterLinkingCacheByRuleId($rulesCreated);

		//get all deleted rules from DB
		$rules = $this->categoryPageSEOLib->getRulesToBeProcessed('deleted');
		$this->categoryPageSEOLib->processRules($rules);
		$rulesDeleted = $this->categoryPageSEOLib->deleteProcessedRules($rules);
		
		//send mail to inform which rules are processed, if any.
		if(!empty($rulesCreated) || !empty($rulesDeleted)) {
			$this->sendMailToDevProd($rulesCreated, $rulesDeleted);
		}
		
		$this->generateCategoryNonZeroPagesCache();
		//send ending mail
		$this->sendMailToDev('ended');

		error_log("Section: Cron ended for creating category page URLs at ".date('y-m-d H:i').". Total time taken | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
		_p('DONE');
	}

	public function sendMailToDev($startOrEnd) {
		$emailIdarray 	= array('nikita.jain@shiksha.com', 'sukhdeep.kaur@99acres.com');
		$subject      	= "Cron ".$startOrEnd." for category page seo creation/deletion.";
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}
	}

	public function sendMailToDevProd($rulesCreated, $rulesDeleted) {
		$emailIdarray 	= array('nikita.jain@shiksha.com', 'sukhdeep.kaur@99acres.com', 'saurabh.gupta@shiksha.com', 'kashish.mehta@shiksha.com', 'siddharth.raman@naukri.com');
		$subject      	= "Cron ".$startOrEnd." for category page seo creation/deletion.";
		if(!empty($rulesCreated)) {
			$innerContent1	= "Created category page SEO data for rule(s) - ".implode(', ', $rulesCreated).".</br>";
		}
		if(!empty($rulesDeleted)) {
			$innerContent2	= "Deleted category page SEO data for rule(s) - ".implode(', ', $rulesDeleted).". ";
		}
		$content 		= "<p>Hi,</p> <p>".$innerContent1.$innerContent2."</p> <p>- Shiksha Dev</p>";
		
		foreach($emailIdarray as $key=>$emailId) {
			$this->alertClient->externalQueueAdd("12", ADMIN_EMAIL, $emailId, $subject, "", "html", '', 'n');
		}
	}

	public function categoryPageCountCron(){
		$this->validateCron();
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);
		
		error_log("CTP Count Cron Started at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");
		$this->_init();

		//update count of all CTPs
		$this->categoryPageSEOLib->categoryPageCountCronData();
		error_log("CTP Count Cron Ended at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");
	}

	public function categoryPageCrons() {
		$this->validateCron();
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);
		
		error_log("CTP Cron Started at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");

		$this->_init();

		//update count of all CTPs
		$this->categoryPageCountCron();

		//add non zero category pages to cache
		$this->generateCategoryNonZeroPagesCache();

		//re-generate interlinking cache
		//$this->generateInterlinkingCacheForCTPGs();

		error_log("CTP Cron Ended at ".date('y-m-d h:i:sa')."\n\n", 3, "/tmp/log_ctp_cron.log");
	}

	public function categoryPageCountCronForId($catPageId){
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);
		
		$this->_init();
		$this->categoryPageSEOLib->categoryPageCountCronDataForId($catPageId);
	}

	public function categoryPageSeoURLHash(){
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);
		
		$this->_init();
		$this->load->helper('listingCommon/listingcommon');
		$this->categoryPageSEOLib->categoryPageSeoURLHash();	
	}

	public function generateInterlinkingCacheForCTPGs(){
		$this->validateCron();
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);

		error_log("CTP Interlinking Cron Started at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");

		$this->catPageInterLinkingLib = $this->load->library('nationalCategoryList/NationalCategoryPageInterLinking');
		$this->catPageInterLinkingLib->generateInterlinkingCacheForCTPGs();

		error_log("CTP Interlinking Cron Ended at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");
	}

	public function generateCategoryNonZeroPagesCache(){
		$this->validateCron();
		ini_set("memory_limit", '6000M');
		ini_set('max_execution_time', -1);

		error_log("CTP URL in Cache Cron Started at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");
		
		$this->categorypageseomodel = $this->load->model('nationalCategoryList/categorypageseomodel');
		$this->nationalCategoryListCache = $this->load->library('nationalCategoryList/cache/NationalCategoryListCache');
		$batchSize = 5000;
		$count = 1;
		$start = 0;

		do {
			error_log(" ********** Getting category Page Data for batch $count ********** ");
			$categoryPageData = $this->categorypageseomodel->findCategoryPageData($start,$batchSize);
			if(!empty($categoryPageData)){
				$dataToStore = array();
				$dataToDelete = array();

				foreach ($categoryPageData as $row) {
					if($row['city_id'] > 1){
						$row['state_id'] = 1;
					}
					$stringKey = $row['stream_id'].'_'.$row['substream_id'].'_'.$row['specialization_id'].'_'.$row['base_course_id'].'_'.$row['education_type'].'_'.$row['delivery_method'].'_'.$row['credential'].'_'.$row['exam_id'].'_'.$row['city_id'].'_'.$row['state_id'];
					if($row['result_count'] > 0){
						$dataToStore[$stringKey] = '/'.$row['url'];
					}
					else{
						$dataToDelete[] = $stringKey;
					}
				}
				if(!empty($dataToStore)){
					$this->nationalCategoryListCache->storeNonZeroPageLinksCache($dataToStore);
				}
				if(!empty($dataToDelete)){
					$this->nationalCategoryListCache->removeNonZeroPageLinksCache($dataToDelete);
				}
				$count++;
				$start += $batchSize;
			}
		} while(!empty($categoryPageData));
		error_log(" ********** Done **********");

		error_log("CTP URL in Cache Cron Ended at ".date('y-m-d h:i:sa')."\n", 3, "/tmp/log_ctp_cron.log");
	}

	public function getCTPsByInstituteOrCourse(){
		$this->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->nationalCategoryPageLib = new NationalCategoryPageLib();

		$instituteIds = array(309, 123, 210, 844);
		//$instituteIds = array(30692,36355,29319,21403,12918,31605,48047,29740,28468,30368,37957,47639,2942,47524,2933,33592,33002,38340,36828,24096,24780,35523,37521,13669,35203,409,11644,62577,42601,46633,30253,24855,62579,62551,62581,62583,38171,62585,62587,37023,28163,62589,479,62591,26223,28616,32740,318,47712,47703,49179,43209,62593,62595,47709,47711,36080,36085,49314,23700,333,20188,32736,20190,26467,307,36357,36361,29008,29386,36359,62597,26940,39563,46282,37367,34450,38100,24846,25127,34554,52309,62575);
		//$courseIds = array(1653);
		
		$urls = $this->nationalCategoryPageLib->getCTPsByInstituteOrCourse($instituteIds, $courseIds);
		//_p($urls);
		return $urls;
	}
}