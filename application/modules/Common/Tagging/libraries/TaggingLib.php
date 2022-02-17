<?php
class TaggingLib
{

	private $serverNormalURL = '';
	private $serverImpactURL = '';

	/**
	* Constructor Function to get the CI Instance
	*/
	function __construct()
	{
		$this->CI = & get_instance();
		/*$this->serverNormalURL = 'http://127.0.0.1:8080/Tagging/tags';
		$this->serverImpactURL = 'http://127.0.0.1:8080/Tagging/tagsImpact';*/
		/*$this->serverNormalURL = 'http://172.16.3.107:8896/Tagging/tags';
		$this->serverImpactURL = 'http://172.16.3.107:8896/Tagging/tagsImpact';*/
		$this->serverNormalURL = "http://10.10.16.72:8986/Tagging/tags";
		$this->serverImpactURL = TAGGING_SERVER_IMPACT_URL;
	}

	/**
	* Init Function to initialize the Model Instance
	*/
	private function _init(){
		$this->CI->load->model('Tagging/taggingmodel');
		$this->tagmodel = new TaggingModel();	
	}

	/**
	* Function to show Tags Suggestion 
	* @param array $data(Array of Questions)
	* @return array $finalResult(Array of Tags for questions)
	*/
	
	public function showTagSuggestions($data,$whichCase='normal',$isModeratorEditing=false,$includeKeyInResult = false){

		error_log('========================================='.print_r($data,true));
		$finalResult = array();

		foreach ($data as $threadId=>$question) {
			error_log("Fetching *********----".$threadId);
			$sTime = microtime(true);
			$result = $this->_sendCURLRequest($question,$whichCase);
			$eTime = microtime(true);
			$dTime = $eTime - $sTime;
			error_log("DEBUG_TAGGING_TIME : ".round($dTime,4));
			$tempResult = array();
			$resultArray = explode("<br />",$result);

			if(count($resultArray) >= 1){
				$objectiveTags = rtrim($resultArray[0],',');
				$tempResult['objective'] = array_unique(explode(",", $objectiveTags));	
			}
			else {
				$objectiveTags = "";
				$tempResult['objective'] = explode(",", $objectiveTags);
			}

			if(count($resultArray) >= 2){
				$backgroundTags = rtrim($resultArray[1],',');
				$tempResult['background'] = array_unique(explode(",", $backgroundTags));	
			} 
			else {
				$backgroundTags = "";
				$tempResult['background'] = explode(",", $backgroundTags);	
			}
			
			//fetch parent tags for objective and background tags

			$fetchTagParentTags = array();

			if($isModeratorEditing)
			{			
				foreach ($tempResult as $tagType => $tArray) {
						foreach ($tArray as $tkey => $tvalue) {
							if(!empty($tvalue))
							{
								$fetchTagParentTags[] = $tvalue;
							}
						}
				}

				$priorityTags = array();
				if(is_array($fetchTagParentTags) && count($fetchTagParentTags) > 0)
				{
					$parentTagIds = $this->findParentTags($fetchTagParentTags);

					foreach ($parentTagIds as $pkey => $pvalue) {
						if(in_array($pvalue, $tempResult['background']) && in_array($pkey, $tempResult['objective']))
						{
							$pos = array_search($pvalue, $tempResult['background']);
							if($pos >= 0)
							{
								array_splice($tempResult['background'], $pos,1);
								$fetchPos = array_search($pvalue, $fetchTagParentTags);
								array_splice($fetchTagParentTags, $fetchPos, 1);
							}
						}
					}
					/*error_log('$sus $$parentTagIds1'.print_r($parentTagIds,true));
					error_log('$sus $tempResult1'.print_r($tempResult,true));*/
					$tempParentResult = array();
					foreach ($tempResult as $tagType => $tArray) {
						foreach ($tArray as $tkey => $tvalue) {
							if(!empty($tvalue) && array_key_exists($tvalue, $parentTagIds) && !in_array($parentTagIds[$tvalue], $fetchTagParentTags))
							{
								$tempParentResult[$tagType.'_parent'][] = $parentTagIds[$tvalue];
							}
						}
					}
				}

				if(is_array($tempParentResult) && count($tempParentResult) > 0)
				{
					$tempResult = array_merge($tempResult,$tempParentResult);
				}
			}
			if(count($data) == 1 && !$includeKeyInResult){
				$finalResult = $tempResult;
			} else {
				$finalResult[$threadId] = $tempResult;
			}			
			
		}
		error_log('===================='.print_r($finalResult,true));
		return $finalResult;

	}

	/**
	* Function to Attach input tags to its Parent
	* @param array $tags
	* @return array $tags (Modified array with Parent Tags)
	*/
	public function attachTagsWithParent($tags){

		$this->_init();
		$objectiveTags = $tags['objective'];
		$backgroundTags = $tags['background'];
		$manualTags = $tags['manual'];

		// Objective Tags
		if(!empty($objectiveTags)){

			$result = $this->findAllParentTags($objectiveTags);

			foreach ($result as $key => $value) {
				foreach ($value as $tagIdParent) {
					$tempResult[] = $tagIdParent;
				}
			}

			$result = $tempResult;

			if(!empty($result)){
				$result = array_diff($result, $objectiveTags);	
				$result = array_diff($result, $backgroundTags);	
				if(!empty($manualTags)){
					$result = array_diff($result, $manualTags);		
				}
				
			}
		} 

		$tags['objective_parent'] = $result;

		// Background Tags
		/*$result = array();
		if(!empty($backgroundTags)){
			$result = $this->findParentTags($backgroundTags);
			if(!empty($result)){
				$result = array_diff($result, $objectiveTags);	
				$result = array_diff($result,$backgroundTags);
				$result = array_diff($result,$tags['objective_parent']);
			}
		
		}
		$tags['background_parent'] = $result;*/

		// Manual Tags 
		$result = array();
		if(!empty($manualTags)){
			$result = $this->findAllParentTags($manualTags);
			foreach ($result as $key => $value) {
				foreach ($value as $tagIdParent) {
					$tempResult[] = $tagIdParent;
				}
			}

			$result = $tempResult;

			if(!empty($result)){
				$result = array_diff($result, $objectiveTags);	
				$result = array_diff($result,$backgroundTags);
				$result = array_diff($result,$manualTags);
				$result = array_diff($result,$tags['objective_parent']);
				$result = array_diff($result,$tags['background_parent']);
			}
			$tags['manual_parent'] = $result;
		}
		
		return $tags;
	}


	/**
	* Function to find the parent tags for input tags of array
	* @param array(1D) $tags (One Dimensional Array of Input Tags)
	* @return array(1D) $result (One Dimensional Array of Input Tags)
	*/
	public function findParentTags($tags) {
		$this->_init();
		$result = $this->tagmodel->findParentTags($tags);
		return $result;
	}


	/**
	* Function to insert tags into DB with tags_id,content_id and content_type
	* @param $tags array (2D Array- indexed keys and internal values as tagId, classification array)
	* @param interer $content_id
	* @param string $content_type (Type of content - like question / discussion)
	*/
	public function insertTagsWithContentToDB($tags,$content_id,$content_type,$action="updatetag",$editType="editEntity",$listingTypeId=0,$extralistingArray=array(),$extraParam=array()){
		$this->_init();	
		$listingTag = array();

		// On the Basis of entityId = examId and entity_type = 'Exams', fetch the tag to be attached to question
		if(!empty($extraParam['entityId']) && $extraParam['entityId']>0 && !empty($extraParam['entityType']) && $extraParam['entityType'] == 'tagDetailPage' && empty($listingTypeId)) {
			$listingTag['tag_id'] = $extraParam['entityId'];
		}
		else if(!empty($extraParam['entityId']) && $extraParam['entityId']>0 && empty($listingTypeId)){
			$entityId   = $extraParam['entityId'];
			$entityType = $extraParam['entityType'];
			$listingTag = $this->tagmodel->findTagAttachedToEntity($entityId,$entityType);
		}

		if($content_type == 'question' && $editType!="editTag" && $listingTypeId>0){
			// $listingTag - 1D array with key as 0,1 and tag_id in value
			$listingTag = $this->tagmodel->fetchListingTag($content_id,$listingTypeId);
		}

		if(!empty($extralistingArray)){
			$extraListingTags = $this->tagmodel->fetchMultipleListingTag($content_id,$extralistingArray);
		}

		$len = count($tags);
		$isMatched = false;
		/*if(!empty($listingTag)){
			foreach ($tags as $key => $value) {
				if($value['tagId'] != $listingTag['tag_id']) continue;
				$isMatched = true;
				break;
			}	
		}*/
		if(!$isMatched && !empty($listingTag)) {
			$tags[$len]['tagId'] = $listingTag['tag_id'];
			$tags[$len]['classification'] = 'manual';

			$parentArray = $this->findAllParentTags(array($listingTag['tag_id']));

			$parentTagsArray = $parentArray[$listingTag['tag_id']];

			$tempTags = $tags; 

			foreach ($tags as $tagKey => $tagValue) {
				if((($tagValue['tagId'] == $listingTag['tag_id']) && !in_array($tagValue['classification'], array('manual','manual_parent'))) || (!empty($parentTagsArray) && in_array($tagValue['tagId'],$parentTagsArray)))
				{
					unset($tempTags[$tagKey]);
				} 
			}

			$tags = array_values($tempTags);

			//Check if Parent is set. If yes, also add its parent tags into $tags array
			if(isset($parentArray[$listingTag['tag_id']]) && count($parentArray[$listingTag['tag_id']])>0){
				foreach ($parentArray[$listingTag['tag_id']] as $parentTag){
					$len = count($tags);
					$tags[$len]['tagId'] = $parentTag;
					$tags[$len]['classification'] = "manual_parent";
				}
			}


		}	

		//adding parent tags for extra listings tags
		$parentExtraArray = $this->findAllParentTags($extraListingTags);

		foreach($extraListingTags as $extraTag){
			$TagArraylen = count($tags);
			$tags[$TagArraylen]['tagId'] = $extraTag;
			$tags[$TagArraylen]['classification'] = 'manual';

			$parentTagsArray = $parentExtraArray[$extraTag];

			$tempTags = $tags;

			foreach ($tags as $tagKey => $tagValue) {
				if((($tagValue['tagId'] == $extraTag) && !in_array($tagValue['classification'], array('manual','manual_parent'))) || (!empty($parentTagsArray) && in_array($tagValue['tagId'],$parentTagsArray)))
				{
					unset($tempTags[$tagKey]);
				} 
			}

			$tags = array_values($tempTags);


			if(isset($parentExtraArray[$extraTag]) && count($parentExtraArray[$extraTag])>0){
				foreach ($parentExtraArray[$extraTag] as $parentTag){
					$len = count($tags);
					$tags[$len]['tagId'] = $parentTag;
					$tags[$len]['classification'] = "manual_parent";
				}
			}
		}

		if(is_array($tags) && count($tags) > 0){
			$this->tagmodel->insertTagsWithContent($tags,$content_id,$content_type);
		}
		
		// below code to update tagIds corresponding this thread(question/discussion) in redis -- added by Abhinav
		$tagsMappedToThisThread = array();
		foreach($tags as $key=>$value){
			if(in_array($value['classification'], array('manual','objective','background'))){
				$tagsMappedToThisThread['direct'][] = $value['tagId'];
			}else {
				$tagsMappedToThisThread['parent'][] = $value['tagId'];
			}
		}

		$AnAModelObj = $this->CI->load->model('messageBoard/AnAModel');
		$ownerUserId = $AnAModelObj->getAnswerOwnerUserId($content_id);

		$userInteractionCacheStorageLib = $this->CI->load->library('common/personalization/UserInteractionCacheStorageLibrary');
		$userInteractionCacheStorageLib->updateTagOfThreads($ownerUserId,$content_id,$content_type,$tagsMappedToThisThread,$action);

	}


	/**
	* Function to send request to Tag Java Servlet Code to find the Tags. Provide Result as 
	* 1st line -- Objective Tags, 2nd Line -- Background Tags
	*
	*/
	private function _sendCURLRequest($question,$whichCase){

		$urlFields['sentence'] = urlencode($question);
		$urlFields['populateLists'] = 'false';
		$url = $this->serverNormalURL;
		if($whichCase == 'normal'){
			$url = $this->serverNormalURL;	
		} else{
			$url = $this->serverImpactURL;	
		}
		
		foreach ($urlFields as $key => $value) {
			$fields_string .= $key.'='.$value.'&';
		}

		rtrim($fields_string,'&');

		$ch = curl_init();

		curl_setopt($ch,CURLOPT_URL, $url);
		curl_setopt($ch,CURLOPT_POST, count($urlFields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);

		$result = curl_exec($ch);
		return $result;	
	}

        public function getTagDetails($suggestions,$isModeratorEditing = false){
		//First, find the details of the Tag like Name, desc etc
                $this->_init();
		$i = 0;
		foreach ($suggestions as $key => $value){
			if(is_array($value)){
				foreach ($value as $tagIndex => $tagId){
					if($tagId != ""){
						$tagsArray[$i]['id'] = $tagId;
						$tagsArray[$i]['classification'] = $key;
						if($key == "objective" || ($key == "objective_parent" && $isModeratorEditing)) {
							$tagsArray[$i]['checked'] = true;
						}
						else{
        	                                        $tagsArray[$i]['checked'] = false;
	                                        }
						$i++;
					}
				}
			}
		}
               	$result['tags'] = $this->tagmodel->findTagDetails($tagsArray);

		//Second, find if any of the Tag is present multiple times. If yes, find its parents also
		//$result['tags'] = $this->tagmodel->findConflictedTag($result['tags']);

		//Third, Sort the tags based on Classification
		function tag_sorting($a, $b)
		{
			if($a['classification'] == $b['classification'])
				return 0;
			if($a['classification'] == "objective")
				return -1;
			if($a['classification'] == "background_parent")
				return 1;
			if($a['classification'] == "objective_parent" && ($b['classification'] == "background" || $b['classification'] == "background_parent"))
				return -1;
                        if($a['classification'] == "background" && ($b['classification'] == "background_parent"))
                                return -1;			
			return 1;
		}
		usort($result['tags'], 'tag_sorting');

		return $result;
        }


	function findTagsToBeAttached($tagArray){

		//First check if any of the Manual Tags is conflicted
		/*
		$checkForConflicted = array();
		foreach ($tagArray as $tag){
			if($tag['classification']=="manual"){
				$checkForConflicted[] = $tag['tagId'];
			}
		}
		$returnArray = $this->checkIfConflicted($checkForConflicted);
		*/
		//Find Parent for each Tag
		$findParentsTag = array();
		foreach ($tagArray as $tag){
			$tagId = $tag['tagId'];
			/*
			if( (isset($returnArray[$tagId]) && $returnArray[$tagId]['conflicted']) || (strtolower($tag['classification']) == "objective parent") || (strtolower($tag['classification']) == "background parent") ){	//This is a conflicted Tag OR this is already a parent tag
				continue;
			}
			if(!(isset($tag['conflicted']) && $tag['conflicted']=="true")){	//This is a conflicted Tag and we do not want its Parents
				$findParentsTag[] = $tagId;
			}*/
			$findParentsTag[] = $tagId;
		}
		$parentArray = $this->findAllParentTags($findParentsTag);
		//Now, create the final Array with all the Tags, their parents and their classifications
		$finalArray = array();
		foreach ($tagArray as $tag){
			$tagId = $tag['tagId'];
			$finalArray[] = $tag;
			//Check if Parent is set. If yes, also insert its parent in the final array
			if(isset($parentArray[$tagId]) && count($parentArray[$tagId])>0){
				foreach ($parentArray[$tagId] as $parentTag){
					$tmp = array();
					$tmp['tagId'] = $parentTag;
					$tmp['classification'] = $tag['classification']."_parent";
					$finalArray[] = $tmp;
				}
			}
		}
		return $finalArray;

	}

        function findAllParentTags($tags) {
                $this->_init();
                $result = $this->tagmodel->findAllParentTags($tags);
                return $result;
        }

	function checkIfConflicted($checkForConflicted){
		$this->_init();
		return $this->tagmodel->checkIfConflicted($checkForConflicted);
	}

	function deleteTagsWithContentToDB($editEntityId){
                $this->_init();
                return $this->tagmodel->deleteTagsWithContentToDB($editEntityId);
	}

	function checkTagsExistOnDB($entityIds)
	{
		if(empty($entityIds))
			return array();
		$this->_init();
		return $this->tagmodel->checkTagsExistOnDB($entityIds);
	}

        function getTagsByClassification($editEntityId, $classificationArray){
                $this->_init();
                return $this->tagmodel->getTagsByClassification($editEntityId, $classificationArray);
        }

	function getAllTagsOfEntity($entityId, $entityType = 'question'){
		$this->_init();
		return $this->tagmodel->getAllTagsOfEntity($entityId, $entityType);
	}

	function getTagNameById($tagIdString){
		$this->_init();
		$tagDetails = $this->tagmodel->getTagNameById($tagIdString);
		foreach ($tagDetails as $key => $value) {
			$returnArray[$value['tagId']] = $value['tagName'];
		}
		return $returnArray;
	}

	function getTagsFromSolr($count){
			$key = md5('popularTagsFromSolr');
	        $cacheLib = $this->CI->load->library('cacheLib');
	        $cacheData = $cacheLib->get($key);
	        if($cacheData != 'ERROR_READING_CACHE') {
	            $tagsResult = $cacheData;
	        } else {
	            //To get Suggested Tags
	            $this->CI->load->builder('SearchBuilder', 'search');
	            $this->CI->config->load('search_config');
	            $this->searchServer = SearchBuilder::getSearchServer($this->CI->config->item('search_server'));
	            $rows=$count;
	            $startOffSet = 0;
	            $solrUrl = SOLR_AUTOSUGGESTOR_URL."q=*%3A*&wt=phps&indent=true&fq=facetype:tag&sort=tag_quality_factor%20desc&fl=tag_name,tag_id&start=".$startOffSet."&rows=".$rows;
	            $solrContent = unserialize($this->searchServer->curl($solrUrl));
	            $tagsResult = $solrContent['response']['docs'];     
	            $cacheLib->store($key, $tagsResult, 86400);
	        }

	        return $tagsResult;
		}

	function checkTagTypeToShowRHSWidget($tagIds){
		if(empty($tagIds)){
			return;
		}
		$this->_init();
		$popularCourseVal = '';
		$instTypeArr = array('institute', 'National-University');
		$tagTypeArr = $this->tagmodel->getTagsType($tagIds);
		foreach ($tagTypeArr as $key => $value) {
			$allTagsWithEntity[] = $value['tag_id'];
			if(in_array($value['entity_type'], $instTypeArr)){
				$instArr[] = $value['entity_id'];
				$instTagIdArr[] = $value['tag_id'];
				continue;
			}
			else if($value['entity_type'] == 'Course'){
				$baseCourseArr[] = $value['entity_id'];
				$baseCourseTagIdArr[] = $value['tag_id'];
				continue;
			}
			else if($value['entity_type'] == 'Sub-Stream'){
				$subStreamArr[] = $value['entity_id'];
				$subStreamTagIdArr[] = $value['tag_id'];
				continue;
			}	
			else if($value['entity_type'] == 'Stream'){
				$streamArr[] = $value['entity_id'];
				$streamTagIdArr[] = $value['tag_id'];
				continue;
			}else if($value['entity_type'] == 'Exams'){
				$examArr[] = $value['entity_id'];
				$examTagIdArr[] = $value['tag_id'];
				continue;
			}	
		}
		foreach ($allTagsWithEntity as $keyTag => $tagVal) {
			if((!in_array($tagVal, $instTagIdArr)) && (!in_array($tagVal, $baseCourseTagIdArr)) && (!in_array($tagVal, $subStreamTagIdArr)) && (!in_array($tagVal, $streamTagIdArr)) && (!in_array($tagVal, $examTagIdArr))){
			 	$otherVal[] = $tagVal;
			}
			
		}
		$uniqueAllTagsWithEntity = array_unique($allTagsWithEntity);
		
		if(!empty($uniqueAllTagsWithEntity)){
			$leftOverTags = array_diff($tagIds, $uniqueAllTagsWithEntity);
		}else{
			$leftOverTags = $tagIds;
		}
		if(!empty($otherVal) && !empty($leftOverTags)){
			$finalTagsWithNoEntity = array_merge($otherVal, $leftOverTags);
		}
		else if(!empty($otherVal) && empty($leftOverTags)){
			$finalTagsWithNoEntity = $otherVal;
		}
		else if(empty($otherVal) && !empty($leftOverTags)){
			$finalTagsWithNoEntity = $leftOverTags;
		}
		if(count($instArr) < 4){
			if(!empty($finalTagsWithNoEntity)){
				$tagParentIdArr = $this->tagmodel->findAllParentTags(array_unique($finalTagsWithNoEntity));
				foreach ($tagParentIdArr as $tagId => $parentIdArr) {
					foreach ($parentIdArr as $k => $parentVal) {
						$finalParentsIds[] = $parentVal;
					}
				}
				$parentTagArr = $this->tagmodel->getTagsType($finalParentsIds);
				foreach ($parentTagArr as $key1 => $value1) {

					if(in_array($value1['entity_type'], $instTypeArr)){
						if(!empty($instArr)){
							array_push($instArr, $value1['entity_id']);
						}else{
							$instArr[] = $value1['entity_id'];
						}
						continue;
					}
					else if($value1['entity_type'] == 'Course'){
						if(!empty($baseCourseArr)){
							array_push($baseCourseArr, $value1['entity_id']);
						}else{
							$baseCourseArr[] = $value1['entity_id'];
						}
						continue;
					}else if($value1['entity_type'] == 'Sub-Stream'){
						if(!empty($subStreamArr)){
							array_push($subStreamArr, $value1['entity_id']);
						}else{
							$subStreamArr[] = $value1['entity_id'];
						}
						continue;
					}else if($value1['entity_type'] == 'Stream'){
						if(!empty($streamArr)){
							array_push($streamArr, $value1['entity_id']);
						}else{
							$streamArr[] = $value1['entity_id'];
						}
						continue;
					}else if($value1['entity_type'] == 'Exams'){
						if(!empty($examArr)){
							array_push($examArr, $value1['entity_id']);
						}else{
							$examArr[] = $value1['entity_id'];
						}
						continue;
					}
				}
			}	
		}

		if(count($examArr)>0){
			$returnResult['exam'] = array_unique($examArr);
		}

		if(count($instArr) > 0){
			$returnResult['college'] = array_unique($instArr);
		}else if((count($baseCourseArr) > 0)){
			$this->CI->load->builder("listingBase/ListingBaseBuilder");
	        $baseCourseBuilder = new ListingBaseBuilder();
    	    $this->baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
			foreach ($baseCourseArr as $base1 => $val1) {
                $baseCourseObj = $this->baseCourseRepo->find($val1);
                $isPopular = $baseCourseObj->getIsPopular();
				if($isPopular) {
					$popularCourseVal = $val1;
					break;
				}else{
					$popularCourseVal = '';
				}
			}
			if(!empty($popularCourseVal) && $popularCourseVal > 0){
				$returnResult['course'][0] = $popularCourseVal;
			}
		}
	 	if(!empty($streamArr) || !empty($subStreamArr)){
			if(!empty($subStreamArr)){
				$subStreamFirstVal = reset($subStreamArr);
				$returnResult['primaryHierarchy']['substream'] = $subStreamFirstVal;
			}
			if(!empty($streamArr)){
				$subFirstVal = reset($streamArr);
				$returnResult['primaryHierarchy']['stream'] = $subFirstVal;
			}
		}
		return $returnResult;
	}

}
?>
