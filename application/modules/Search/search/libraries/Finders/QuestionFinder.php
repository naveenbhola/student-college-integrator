<?php

class QuestionFinder {
	
	private $_ci;
	private $config;
	private $searchServer;
	private $nameTranslation;
	public function __construct(){
		$this->_ci = & get_instance();
		$this->_ci->load->model("listing/coursemodel", "", true);
		$this->_ci->load->model("search/SearchModel", "", true);
		$this->_ci->load->library("listing/listing_client", "", true);
		$this->_ci->load->helper("search/SearchUtility");
		$this->_ci->load->builder("ListingBuilder", "listing");
		$this->listingBuilder = new ListingBuilder();
		$this->_ci->config->load('search_config');
		 $this->_ci->config->load('search_config_boosting');
		$this->config = $this->_ci->config;
		$this->searchServer = SearchBuilder::getSearchServer($this->config->item('search_server'));
        
        //for qer integeration in cafe search.
        $this->nameTranslation = $this->config->item('nameTranslationBoosting');      
	}
	
	public function getData($id = null,$solr_version='old') {
		if(empty($id))
		{
			return array();
		}
		$indexData = false;
		$questionData = $this->getQuestionData($id);
        if($solr_version=='new')
        {
            $questionExtraData = $this->getExtraQuestionFields($id);
            foreach($questionExtraData as $key=>$value){
                $questionData[$key] = $value;
            }
        }
		if(!empty($questionData) && is_array($questionData)){
			$indexData = array();
			$indexData = $this->preprocessRawData($questionData,$solr_version);
			$dataSufficientFlag = $this->isDataSufficient($indexData);
			if(!$dataSufficientFlag){
				$indexData = false;
			}
		}
		return $indexData;
	}
	
	public function preprocessRawData($data = array(),$solr_version='old') {
		$question = array();
		$question['question_id'] 				= $data['msgId'];
		$question['question_description'] 		= $data['question_description'];
		$question['question_title'] 			= $data['msgTxt'];
		$question['question_user_id'] 			= $data['userId'];
		$question['question_user_displayname'] 	= $data['displayname'];
		$question['question_user_image_url'] 	= $data['image_url'];
		$question['question_category_ids'] 		= $data['category_ids'];
		$question['question_thread_id'] 		= $data['threadId'];
		$question['question_created_time'] 		= solrDateFormater($data['creationDate']);
		
		$question['unique_id'] = "question_". $question['question_id'];
		$question['facetype'] = "question";
		if($solr_version=='new')
		{
            $question['question_bestAnswerId'] = $data['bestAnswerId'];
            $question['question_inMasterList'] = $data['inMasterList'];
            $question['question_answers_count'] = $data['msgCount'];

            // tags mapping
            $allTags = array();
            foreach($data['tags_mapped'] as $tagType => $tagArray){
            	$allTags = array_merge($allTags, (array)$tagArray);
            	$tagType = str_replace("_", "", $tagType);
            	$tagType = removeSpacesFromString($tagType);
            	$question['thread_tags_'.$tagType]  = array_filter($tagArray);
            }
            $question['thread_tags'] 		 		     = array_filter($allTags);

            $predisLib = PredisLibrary::getInstance();//$this->_ci->load->library("common/PredisLibrary");
            $threadQualityScore = $predisLib->getMemberOfString("threadQuality:thread:".$data['msgId']);
			$question['question_quality_score'] 		 = $threadQualityScore ? $threadQualityScore : 0;
			$questionQerData = $this->getQerCategoryEtc($question['question_title'], $question['question_id']);
			$question = array_merge($question, $questionQerData);
		}
		$instituteDetails = $this->getInstituteDetails($data['listingTypeId'], $data['listingType']);
		$indexData = array_merge($question, $instituteDetails);
		return $indexData;
	}
	
	private function getQuestionData($id = null){
		if(empty($id)){
			return false;
		}
		$searchModel = new SearchModel();
		$questionData = $searchModel->getQuestionDetails($id);
		if(!empty($questionData) && is_array($questionData)){
			$questionDescription = $searchModel->getQuestionDescription($id);
			$questionData['question_description'] = $questionDescription;	
		}
		return $questionData;
	}

    private function getExtraQuestionFields($id = null)
    {
        if(empty($id))
        {
            return false;
        }
        $searchModel = new SearchModel();
        $questionData = $searchModel->getQuestionExtraDetails($id);
        $contentMappedTags = $searchModel->getContentMappedTags($id, 'question');
        $resultArray =array();
        foreach($questionData as $key=>$value)
        {
            $resultArray[$key] = $value;   
        }

        $resultArray['tags_mapped'] = $contentMappedTags;
        // var_dump($resultArray);
        return $resultArray;
    }


	private function getInstituteDetails($id = null, $type = null){
		$data = array();
		if(empty($id) || $type != "institute"){
			return $data;
		}
		$listingRepos = $this->listingBuilder->getInstituteRepository();
		$listingRepos->disableCaching();
		$instituteDataObject = $listingRepos->find($id);
		
		$instituteLocationObject = $instituteDataObject->getLocations();
		$tempLocationObject = array_values($instituteLocationObject);
		$locationObject = $tempLocationObject[0];
		$instituteZoneObject = $locationObject->getZone();
		$instituteLocalityObject = $locationObject->getLocality();
		$instituteCityObject = $locationObject->getCity();
		$instituteStateObject = $locationObject->getState();
		$instituteCountryObject = $locationObject->getCountry();
		
		$data['question_institute_location_id'] = $locationObject->getLocationId();
		$data['question_institute_locality_id'] = $instituteLocalityObject->getId();
		$data['question_institute_locality_name'] = $instituteLocalityObject->getName();
		$data['question_institute_zone_id'] = $instituteZoneObject->getId();
		$data['question_institute_zone_name'] = $instituteZoneObject->getName();
		$data['question_institute_city_id'] = $instituteCityObject->getId();
		$data['question_institute_city_name'] = $instituteCityObject->getName();
		$data['question_institute_state_id'] = $instituteStateObject->getId();
		$data['question_institute_state_name'] = $instituteStateObject->getName();
		$data['question_institute_country_id'] = $instituteCountryObject->getId();
		$data['question_institute_country_name'] = $instituteCountryObject->getName();
		$data['question_institute_id'] = $instituteDataObject->getId();
		$data['question_institute_title'] = $instituteDataObject->getName();
		return $data;
	}
	
	private function isDataSufficient($data = array()) {
		$returnFlag = false;
		if(!empty($data) && is_array($data)){
			if(!empty($data['question_id'])){
				$returnFlag = true;
			}	
		}
		return $returnFlag;
	}
	
    public function getQerCategoryEtc($keyword = NULL, $qid=-1){
        $data = array();
        if($keyword == null){
            return $data;
        }
        $qerUrl = $this->config->item('qer_url_new');
        $qerCatUrl=$qerUrl."?output=xmlcand&action=Submit&doObjectiveDirection=false&inkeyword=".urlencode($keyword);
        error_log("dhwaj qercat ".$qerCatUrl);

        $qerResultString = $this->searchServer->curl(sanitizeUrl($qerCatUrl));
        error_log("dhwaj qercat results ".$qerResultString);

        $xml = json_decode(json_encode(simplexml_load_string($qerResultString, null, LIBXML_NOCDATA)), true);

        foreach($xml as $key=>$val){
            error_log("dhwaj ".$key." -> ".print_r($val,true));
            if(array_key_exists($key, $this->nameTranslation) && ($key!='QueryOrig')){
                $val2="";
                if(strpos($val,"::")!==false && strpos($val,",")!==false){
                    $commaArr = explode(",", trim($val));
                    foreach($commaArr as $ca){
                        $splitArr = explode("::",trim($ca));
                        $id = $splitArr[0];
                        if(trim($id)!=="")
                            $val2=$val2.$id.", ";
                    }	
                }else{
                    $val2=$val;
                }
                $data[$this->nameTranslation[$key]]=$val2;
            }
        }
        error_log("dhwaj qerCat parsed ".print_r($data,true));
        return $data;
    }
}

