<?php class SolrServerLib {
	
	private $_CI;

    private $MAX_REATTEMP_CNT = 3;
    private $SLEEP = array(100000,1000000,3000000);
	public function __construct()
	{
		$this->_CI = & get_instance();
		$this->_CI->load->builder('SearchBuilder', 'search');
		$this->searchServer = SearchBuilder::getSearchServer($this->searchServer);
        $this->_CI->load->config('search_config');
        $this->keysMapping = array(
            'institute_id' => 1,
            'course_id' => 2,
            'course_location_id' => 3,
            'stream_id' => 4,
            'substream_id' => 5,
            'specialization_id' => 6,
            'base_course_id' => 7
            );
		
	}

	public function runSolrQuery($solrUrl){
		
        $solrContent = $this->searchServer->curl($solrUrl);
        $solrResponse = unserialize($solrContent);
        return $solrResponse;
    }

  
    public function getUniqueIdsToUpdate($uniqueIdsConditions) {
    	$params[] = 'q=*:*';
    	$params[] = 'fl=unique_id';
    	$params[] = 'wt=phps';
        $params[] = 'fq=facetype:course';
        $params[] = 'rows=200000';
    	foreach ($uniqueIdsConditions as $key => $value) {

            if($key == "exactCond"){
                $params[] = $value;
            }else{
                if(is_array($value)){
                    $params[] = "fq=".$key.":".implode(" ", $value);
                }else{
                    $params[] = "fq=".$key.":".$value;    
                }    
            }
            
    		
    	}
    	$uniqueIdArray = array();
    	$solrUrl = SOLR_AUTOSUGGESTOR_URL.implode("&", $params);
        
    	$response = $this->runSolrQuery($solrUrl);
    	
    	if($response['response']['numFound'] == 0){
    		return $uniqueIdArray;
    	}else{
    		foreach ($response['response']['docs'] as $value) {
    			$uniqueIdArray[] = $value['unique_id'];
    		}
    	}
    	return $uniqueIdArray;
    }

    public function indexFinalData($indexDataList,$updateFlag=false , $type=''){

        $viewRespFlag = false;
        $viewDocFlag = true;
        
        if(isset($_REQUEST['viewDoc'])){
           _p($indexDataList);
        }


        // Generate Documents    
        $documentGenerator = SearchBuilder::getDocumentGenerator($this->documentFormat);
        $documentList = $documentGenerator->getNLDocuments($indexDataList,$updateFlag);
        $documentListFinal[0] = $documentList;
        
        // Fire the SOLR Query to Index
        $searchServer = SearchBuilder::getSearchServer($this->searchServer);
        $indexResponse = $searchServer->indexDocuments($documentListFinal, $type);

        unset($documentList);

        // Check if there is any failure, failureResponses have indexes for all the documents for which indexing gets failed
        $failureResponses = 
        array_filter($indexResponse, function($element) use($searchFor){
          return $element == 0;
        });
        $attemptNo = 0;

        //Re-attempt for Index in case of failue(UPTO MAX_REATTEMP_CNT times)

        while (!empty($failureResponses) && $attemptNo<$this->MAX_REATTEMP_CNT) {
            
            // unset all the documents for which there are no errors
            foreach ($indexDataList as $key => $value) {
                  if(!array_key_exists($key, $failureResponses))  {
                    unset($indexDataList[$key]);
                  }
            }
            usleep($this->SLEEP[$attemptNo]);

            // Generate Documents
            $documentList = $documentGenerator->getNLDocuments($indexDataList,$updateFlag);
            $documentListFinal[0] = $documentList;
            // Fire SOLR QUERY
            $indexResponse = $searchServer->indexDocuments($documentListFinal, $type);
            // CHECK FOR FAILURE
            $failureResponses = 
            array_filter($indexResponse, function($element) use($searchFor){
              return $element == 0;
            });
            $attemptNo++;
        }
        
        if(isset($_REQUEST['viewResp'])){
            _p($indexResponse);
        }

        return $indexResponse;

    }

    public function deleteDocuments($type='institute',$typeId=0){

        $deletionStatus = false;
        $documentGenerator = SearchBuilder::getDocumentDeleteInstance($this->documentFormat);
        if($type == 'course'){
            $xml = $documentGenerator->getNLCourseDeleteXML($typeId);
        }else if($type == 'institute' || $type == "university"){
            $xml = $documentGenerator->getNLInstituteDeleteXML($typeId);
        }else if($type == 'collegereview'){
            $xml = $documentGenerator->getCollegeReviewDeleteXML($typeId);
        }else if($type == "collegeshortlist"){
            $xml = $documentGenerator->getCollegeShortlistDeleteXML($typeId);
        }

        $searchServer = SearchBuilder::getSearchServer($this->searchServer);
        $deleteResponse = $searchServer->deleteDocument($xml, $type);
        $attemptNo = 0;
        
        while($deleteResponse != 1 && $attemptNo<$this->MAX_REATTEMP_CNT){
            error_log("attemptNo === ".$attemptNo);
            usleep($this->SLEEP[$attemptNo]);
            $deleteResponse = $searchServer->deleteDocument($xml, $type);
            $attemptNo++;
        }

        if($deleteResponse == 1){
            $deletionStatus = true;
        }
        return $deletionStatus;

    }

    public function deleteAutoSuggestorDocuments($type,$typeId){
        $deletionStatus = false;
        $documentGenerator = SearchBuilder::getDocumentDeleteInstance($this->documentFormat);
        $xml = $documentGenerator->getNLAutoSuggestorDeleteXML($type,$typeId);
        $searchServer = SearchBuilder::getSearchServer($this->searchServer);
        $deleteResponse = $searchServer->deleteDocument($xml, $type);
        $attemptNo = 0;
        
        while($deleteResponse != 1 && $attemptNo<$this->MAX_REATTEMP_CNT){
            error_log("attemptNo === ".$attemptNo);
            usleep($this->SLEEP[$attemptNo]);
            $deleteResponse = $searchServer->deleteDocument($xml, $type);
            $attemptNo++;
        }

        if($deleteResponse == 1){
            $deletionStatus = true;
        }
        return $deletionStatus;
    }

    public function deleteUgcDocument($type,$typeId){
        $deletionStatus = false;
        $documentGenerator = SearchBuilder::getDocumentDeleteInstance($this->documentFormat);
        $xml = $documentGenerator->getUgcDeleteXML($type,$typeId);
        $searchServer = SearchBuilder::getSearchServer($this->searchServer);
        $deleteResponse = $searchServer->deleteDocument($xml, $type);
        $attemptNo = 0;
        
        while($deleteResponse != 1 && $attemptNo<$this->MAX_REATTEMP_CNT){
            error_log("attemptNo === ".$attemptNo);
            usleep($this->SLEEP[$attemptNo]);
            $deleteResponse = $searchServer->deleteDocument($xml, $type);
            $attemptNo++;
        }

        if($deleteResponse == 1){
            $deletionStatus = true;
        }
        return $deletionStatus;
    }

    /**
    * Function to parse and get data from Unique Key
    */
    public function parseUniqueKey($uniqueIdArray=array(),$keysArray=array()){
        $result = array();
        $keysMapping = $this->keysMapping;
        $keysToFind = array();
        if(empty($keysArray)){
            $keysToFind = array_keys($keysMapping);
        }else{
            $keysToFind = $keysArray;
        }
        foreach ($uniqueIdArray as $unique_id) {
            $result[$unique_id] = array();
            $data = explode("_", $unique_id);
            foreach ($keysToFind as $keyName) {
                $result[$unique_id][$keyName] = $data[$keysMapping[$keyName]];
            }
        }
        return $result;
    }

    /** 
    * Function to generate UNIQUE KEY for Course Type Document
    */
    public function generateUniqueKey($data = array()){
        $keysMapping = $this->keysMapping;
        $uniqueKeyData = array();
        foreach ($keysMapping as $key => $value) {
            $uniqueKeyData[$value] = $data[$key];
        }
        ksort($uniqueKeyData);
        $uniqueKey = "course_".implode("_", $uniqueKeyData);
        return $uniqueKey;
    }


    public function commitChanges(){
        $url = SOLR_AUTOSUGGESTOR_COMMIT_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function softCommitChanges(){
        $url = SOLR_AUTOSUGGESTOR_SOFT_COMMIT_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function softCommitChangesUserSolr(){
        $url = SOLR_LDB_SEARCH_SOFT_COMMIT_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function solrOptimize() {
        $url = SOLR_AUTOSUGGESTOR_OPTIMIZE_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function commitCollegeReviewChanges(){
        $url = SOLR_CR_COMMIT_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function softCommitCollegeReviewChanges(){
        $url = SOLR_CR_SOFT_COMMIT_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function collegeReviewSolrOptimize() {
        $url = SOLR_CR_OPTIMIZE_URL;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }

    public function qerMapupdate() {
        $url = $this->_CI->config->item('qer_url_mapupdate');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $data = curl_exec($ch);
        curl_close($ch);   
    }
    public function updateCourseDataInCollegeShortlist($documentListFinal){
        // Fire the SOLR Query to Index
        $courseIds = array_keys($documentListFinal);
        $updateKeysData = $this->getUniqueIdsToUpdateOnSolr('collegeshortlist',array('unique_id','courseId'),array('courseId' => $courseIds));
        $updatedSolrData = array();
        foreach ($updateKeysData as $updatekey => $updatevalue) {
            $tempArray = $updatevalue;
            foreach ($documentListFinal[$updatevalue['courseId']] as $subkey => $subvalue) {
                    $tempArray[$subkey] = $subvalue;
                }
            $updatedSolrData[] = $tempArray;
        }
        $indexResponse = $this->indexFinalData($updatedSolrData,true, "collegeshortlist");
        return $indexResponse;
    }
    public function getUniqueIdsToUpdateOnSolr($type,$fieldsToFetch = array(),$uniqueIdsConditions) {
        if(empty($fieldsToFetch)){
            return array();
        }
        $params[] = 'q=*:*';
        $params[] = 'fl='.implode(",", $fieldsToFetch);
        $params[] = 'wt=phps';
        $params[] = 'rows=200000';
        foreach ($uniqueIdsConditions as $key => $value) {

            if($key == "exactCond"){
                $params[] = $value;
            }else{
                if(is_array($value)){
                    $params[] = "fq=".$key.":(".implode("%20", $value).")";
                }else{
                    $params[] = "fq=".$key.":".$value;    
                }    
            }
        }
        $uniqueIdArray = array();
        if($type == "collegeshortlist"){
            $solrUrl = SOLR_CSHORTLIST_SELECT_URL_BASE.'?';
        }else{
            $solrUrl = SOLR_AUTOSUGGESTOR_URL;
        }

        $solrUrl .= implode("&", $params);
        $response = $this->runSolrQuery($solrUrl);
        if($response['response']['numFound'] == 0){
            return $uniqueIdArray;
        }else{
            foreach ($response['response']['docs'] as $value) {
                $temp = array();
                foreach ($fieldsToFetch as $fkey => $fvalue) {
                    $temp[$fvalue] = $value[$fvalue];
                }
                $uniqueIdArray[] = $temp;
            }
        }
        return $uniqueIdArray;
    }
} ?>