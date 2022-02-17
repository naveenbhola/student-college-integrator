<?php

//error_reporting(E_ALL ^ E_NOTICE);

class Test extends MX_Controller {
	
	public function __construct(){
		
	}
	
	public function index($type = "institute"){
		$all = $_REQUEST['all'];
		if($type == "institute" || $all == "true"){
			$instituteArray = array('307', '24766', '24078', '742', '24744', '24806', '23913', '32179', '24673', '22299', '99', '27193', '4191', '521', '4352', '29947', '33545', '34473', '24551', '32545', '32992', '33405', '31008', '33163', '15303', '28130', '3050', '34478', '34481', '34096', '27149', '110694', '112971', '27219', '31002', );
			foreach($instituteArray as $institute){
				modules::run('search/Indexer/index', $institute, 'institute', 'false');	
			}	
		} else if($type == "question" || $all == "true"){
			$array = array('1601086', '1601087', '1601088', '1601095', '1601104', '1601119', '1601123', '1601125', '1601133', '1601137', '1601142', '1601143', '1601151', '1601188', '2001805', '2001804', '2001811');
			foreach($array as $id){
				modules::run('search/Indexer/index', $id, 'question', 'false');	
			}
		} else if($type == "article" || $all == "true"){
			$array = array('4', '2323', '811', '8', '9', '1208', '1204', '3625', '3626', '3627', '3628', '3629', '3630', '3631', '3632', '28', '53', '54', '55');
			
			foreach($array as $id){
				modules::run('search/Indexer/index', $id, 'article', 'false');
			}
		} else if($type == "course" || $all == "true") {
			$array = array('147510', '147518', '153016', '151029', '153015', '153011', '147606', '147641');
			foreach($array as $id){
				modules::run('search/Indexer/index', $id, 'course', 'false');
			}
		} else if($type == "discussion" || $all == "true"){
			$array = array('1922669', '1624186', '1878827', '1612171', '1878827', '1995410', '1985956');
			foreach($array as $id){
				modules::run('search/Indexer/index', $id, 'discussion', 'false');
			}
		}
		echo 'Done';
	}
	

	public function delete($type = 'institute', $id = NULL){
		$this->load->library("listing/cache/ListingCache");
		$listingCache = new ListingCache();
		if($type == 'institute'){
			$listingCache->deleteInstitute($id);
		} else {
			$listingCache->deleteCourse($id);
		}
		echo "DONE";
	}
	
	function testListing($type, $id) {
		$this->load->builder("ListingBuilder", "listing");
		$listingBuilder  = new ListingBuilder();
		$courseRepo    			= $listingBuilder->getCourseRepository();
		$instituteRepo 			= $listingBuilder->getInstituteRepository();
		if($type == "institute"){
			$obj = $instituteRepo->find($id);
		} else if($type == "course") {
			$obj = $courseRepo->find($id);
		}
		_p($obj);
	}
	
	function checkChildCategoryPages() {
		$this->load->library('categoryList/categoryPageRequest');
		$this->load->library('categoryList/CacheUtilityLib');
		$cacheUtilityLib = new CacheUtilityLib();
	    $faultyURLs = array();
		$URLSToCheck = $this->_createURLS();
		error_log("ALL URLS\n", 3, "/tmp/childpage.log");
		error_log(print_r($URLSToCheck, true), 3, "/tmp/childpage.log");
		error_log("\n**********************************************************************************************", 3, "/tmp/childpage.log");
		_p("ALL URLS");
		_p($URLSToCheck);
		_p("**********************************************************************************************");
		foreach($URLSToCheck as $url) {
			$status = $this->_getPageHTTPStatus($url);
			if($status == "FALSE"){
				$faultyURLs[] = $url;
			}
		}
		//$faultyURLs[] = "http://mba.shiksha.com/dec-approved-mba-courses-in-india-accepts-gmat-score-fees-upto-2lacs-ctpg?sort=none&nl=0";
		//$faultyURLs[] = "http://mba.shiksha.com/mba-courses-in-india-accepts-snap-score-ctpg?sort=none&nl=0";
		error_log("\nFAULTY URLS\n", 3, "/tmp/childpage.log");
		error_log(print_r($faultyURLs, true), 3, "/tmp/childpage.log");
		error_log("\n**********************************************************************************************", 3, "/tmp/childpage.log");
		_p("FAULTY URLS");
		_p($faultyURLs);
		_p("**********************************************************************************************");
		$faultyURLsString = "";
		if(!empty($faultyURLs)) {
			$faultyURLsString = implode(",", $faultyURLs);
			foreach($faultyURLs as $url){
				$params 	= explode("http://mba.shiksha.com/", $url);
				$url1 		= $params[1];
				$url2 		= explode("ctpg", $url1);
				$portion 	= trim($url2[0], "-");
				$categoryPageRequest = new CategoryPageRequest($portion, "RNRURL");
				$key = $categoryPageRequest->getPageKey();
				if(!empty($key)) {
					$identifier = explode("CATPAGE", $key);
					$pageKey 	= trim($identifier[1], "-");
					$refreshURL = "http://www.shiksha.com/categoryList/CacheUtility/refreshCacheUtility/page/" . $pageKey . "/";
					error_log("\nURL: ". $url, 3, "/tmp/childpage.log");
					error_log("\nPAGEKEY: ". $pageKey, 3, "/tmp/childpage.log");
					error_log("\nREFRESH URL: ". $refreshURL, 3, "/tmp/childpage.log");
					_p("URL: " . $url);
					_p("PAGEKEY: " . $pageKey);
					_p("REFRESH URL: " . $refreshURL);
					
					$this->_getPageHTTPStatus($refreshURL);
					_p("REFRESH DONE");
					_p("SLEEP FOR 5 SEC");
					_p("**********************************************************************************************");
					
					error_log("\nREFRESH DONE: ", 3, "/tmp/childpage.log");
					error_log("\nSLEEP FOR 5 SEC: ", 3, "/tmp/childpage.log");
					error_log("\n************************************************************************", 3, "/tmp/childpage.log");
					sleep(5);
				}
			}
			if(!empty($faultyURLsString)) {
				$count = count($faultyURLs);
				$msg = "FAULTY URL count: " . $count . "\n";
				$msg .= "URLS: ". $faultyURLsString;
				sendMailAlert($msg,'Childpages faulty urls cleaning(shiksha.com)', array('pankaj.meena@shiksha.com'), TRUE);
			}
		}
		_p("ALL DONE");
		error_log("\nALL DONE", 3, "/tmp/childpage.log");
		error_log("\n************************************************************************", 3, "/tmp/childpage.log");
	}
	
	private function _getPageHTTPStatus($url) {
		$status = "TRUE";
		$headers = get_headers($url, 1);
		$statusString = $headers[0];
		if(!empty($statusString)){
			$pos = strpos($statusString, '200');
			if($pos === FALSE) {
				$status = "FALSE";
			}
		}
		return $status;
	}
	
	private function _createURLS() {
		$cities = array("delhi-ncr", "pune", "bangalore", "chennai", "hyderabad", "kolkata", "mumbai-all", "india");
		$exams  = array("", "cat", "mat", "xat", "cmat", "gmat", "snap", "nmat");
		$fees = array("", "1lakh", "2lacs", "5lacs", "7lacs", "10lacs");
		$affiliation = array("", "aicte", "dec", "ugc");
		
		$urlsToCheck = array();
		foreach($cities as $city){
			foreach($exams as $exam){
				foreach($fees as $fee){
					foreach($affiliation as $aff){
						$url = "mba-courses-in-".$city;
						$feesString = "";
						$examString = "";
						$affString = "";
						if(!empty($fee)){
							$feesString = "fees-upto-".$fee;
						}
						if(!empty($exam)) {
							$examString = "accepts-".$exam."-score";
						}
						if(!empty($aff)){
							if($aff == "dec" || $aff == "aicte"){
								$affString = $aff . "-approved-";
							} else {
								$affString = $aff . "-recognized-";
							}
						}
						
						$tempURL = $url;
						if(!empty($affString)){
							$tempURL = $affString . $tempURL;
						}
						if(!empty($examString)){
							$tempURL .= "-". $examString;
						}
						if(!empty($feesString)){
							$tempURL .= "-". $feesString;
						}
						$tempURL = "http://mba.shiksha.com/" . $tempURL . "-ctpg?sort=none&nl=0";
						$urlsToCheck[] = $tempURL;
					}
				}
			}
		}
		return $urlsToCheck;
	}

	function search($type = ''){
		error_reporting(E_PARSE);
		// ini_set("memory_limit", -1);
		$this->load->library("v1/SearchCommonLib");
		$this->load->model("messageBoard/qnamodel");
		$this->searchCommonLib = new SearchCommonLib();
		$qnamodel = new qnamodel();

		$otherParams['start'] = 0;
        $otherParams['rows']  = 10;
        $text = $this->input->post('text');
        $relatedType = $this->input->post('relatedType');
        
        if($type == 'related'){
        	echo "<h1>Related Entities</h1>";
        }
        else{
        	echo "<h1>SRP</h1>";
        }

        if($type == 'related' && empty($text)){
        	$text = '1000008';
        	$relatedType = 'question';
        }
        else if(empty($text)){$text = 'mba in delhi';}

        if($type == 'related'){
        	$this->load->library('v1/SearchRelatedEntities');
            $relatedEntities = new SearchRelatedEntities();
            $relatedEntities = $relatedEntities->getRelatedEntity($text, $relatedType);

            if($relatedType == 'question' || $relatedType == 'discussion')
            	$details = $qnamodel->getThreadDetails($text, $relatedType);


        }else{
	        $data['tag']        = $this->searchCommonLib->getSearchResults('tag', $text, $otherParams);
	        $data['question']   = $this->searchCommonLib->getSearchResults('question', $text, $otherParams);
	        $data['discussion'] = $this->searchCommonLib->getSearchResults('discussion', $text, $otherParams);  
	    }
        echo "<form action='/search/Test/search".($type ? '/'.$type : '')."' method='post'>";
        echo "<input type='text' name='text' value='".htmlentities($text)."' placeholder='".($type == 'related' ? 'Enter '.$relatedType.' Id' : '')."'/>";

        if($type == 'related'){
			echo "<select name='relatedType'>
					<option value='tag' ".($relatedType == 'tag' ? 'selected' : '').">Tag</option>
					<option value='question' ".($relatedType == 'question' ? 'selected' : '').">Question</option>
					<option value='discussion' ".($relatedType == 'discussion' ? 'selected' : '').">Discussion</option>
				  </select>";        	
        }
        echo "<input type='submit'/>";
        echo "</form>";

        if($type == 'related'){
        	echo "<h2>Related ".ucfirst($relatedType)."s</h2>";

        	if($relatedType == 'question' || $relatedType == 'discussion'){
        		_p("<b>".ucfirst($relatedType)."</b> : ".$details[0]['msgTxt']);
        	}
        	_p($relatedEntities);
        	die;
        }

        if(empty($text))
        	die;
        echo "<style>li{margin-top:10px;font-size:17px;list-style-type: decimal;}</style>";
        echo "<h2>Tags</h2><ul>";

        foreach($data['tag'] as $tag){
        	echo "<li>".$tag['name']."</li>";
        }
        echo "</ul>";

        echo "<h2>Question</h2><ul>";

        foreach($data['question'] as $question){
        	echo "<li> 
        			<b>Title</b> : ".$question['title']."
        			
        			</li>";
        }
        echo "</ul>";

        echo "<h2>Discussion</h2><ul>";
        foreach($data['discussion'] as $discussion){
        	echo "<li> 
        			<b>Title</b> : ".$discussion['title']."
        			<br>
        			<b>Description</b> : ".$discussion['description']."
        			</li>";
        }
        echo "</ul>";
        // _p($data);
	}
	
}


