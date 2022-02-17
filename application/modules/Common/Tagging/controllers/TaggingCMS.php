<?php

class TaggingCMS extends MX_Controller
{

	private $actualTagsArray;
	private $allowedMembers;
	private $mappingsCount;
    function init($isCron=false){
    	$this->load->config('TaggingConfig');    
    	if(!$isCron){
    		$this->validateAccess();
    		if(($this->userStatus == ""))
				$this->userStatus = $this->checkUserValidation();	
    	}    	
		$this->load->model('taggingmodel');
		$this->tagmodel = new TaggingModel();			

		$this->load->library('TaggingLib'); 
		$this->TaggingLib = new TaggingLib();			

		$this->load->model('taggingcmsmodel');
		$this->tagcmsmodel = new TaggingCMSModel();			

		$this->load->library('TaggingCMSLib'); 
		$this->TaggingCMSLib = new TaggingCMSLib();	

		
		$this->actualTagsArray = $this->config->item('TAG_ENTITY_ACTUAL');
		$this->mappingsCount = 10;

		$this->load->helper('shikshautility');

		$this->redisLib ;

/*		$this->shikshaEntityToTagsEntity = array(
			'Stream' => 'Stream',
			'Substream' => 'Substream',
			'Specialization' => 'Specialization',
			'institute' => 'Colleges'
			);*/

		$this->shikshaEntityToTagsEntity = $this->config->item('SHIKSHA_ENTITY_TAG_ENTITY');

		$this->totalShikshaEntites = array_keys($this->shikshaEntityToTagsEntity);
	}

	public function validateAccess(){

		$redirectUrl = SHIKSHA_HOME;
		$this->allowedMembers = $this->config->item('ALLOWED_EMAILS');
		
		if(isset($_COOKIE) && isset($_COOKIE['user'])){
			$cookie = $_COOKIE['user'];
			list($email) = explode("|", $cookie);
			if(!in_array($email, $this->allowedMembers)){
				header('location:'.$redirectUrl);
				die;
			}	
		} else {
			header('location:'.$redirectUrl);
			die;
		}
		
	}

	public function showAddTagsForm($msg=""){

		$this->init();

		$data['entities'] = $this->tagcmsmodel->fetchTagEntities();		
		$substreamPresent = false;
		$univPresent = false;
		foreach ($data['entities'] as $key => $value) {
			if($value['tag_entity'] == "Substream")	{
				$substreamPresent = true;
			}
			if($value['tag_entity'] == "National-University")	{
				$univPresent = true;
			}
		}

		if(!$univPresent){
			$data['entities'][] = array('tag_entity' => 'National-University');
		}
		if(!$substreamPresent){
			$data['entities'][] = array('tag_entity' => 'Substream');
		}
		$data['msg'] = $msg;

		$data['validateuser'] = $this->userStatus;
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['sessionId'] = sessionId();


		//$data['tagEntity'] = $data['shikshaEntity'] = 'Stream';
		$data['shikshaEntityAct'] = $this->input->post('shikshaEntity');

		if($data['shikshaEntityAct'] == "University"){
			$data['shikshaEntityAct'] = "National-University";
		}

		if(isset($data['shikshaEntityAct'])){
			$data['shikshaEntity'] = $data['tagEntity'] = $this->shikshaEntityToTagsEntity[$data['shikshaEntityAct']];
		}
		//$data['tagEntity'] = $data['shikshaEntity'] = $this->input->post('shikshaEntity');
		$data['selectedData']['selectedEntity_1'] = $data['shikshaEntityAct'];
		//$data['pendingMappingTableId'] = 10;
		$data['pendingMappingTableId'] = $this->input->post('pendingMappingTableId');
		//$data['shikshaEntityId'] = 100;
		$data['shikshaEntityId'] = $this->input->post('shikshaEntityId');
		$data['selectedData']['selectedEntityId_1'] = $data['shikshaEntityId'];

		// get tag name
		if($data['pendingMappingTableId']){
			$entityData = $this->tagcmsmodel->getEntityData(array($data['shikshaEntityAct']=> array($data['shikshaEntityId'])));
			$data['tagName'] = $entityData[$data['shikshaEntityAct']][$data['shikshaEntityId']]['name'];
		}
		$data['totalShikshaEntites'] = $this->totalShikshaEntites;
		$this->load->view('addTagsPage',$data);
	}


	public function showDeleteTagsForm($msg=""){

		$this->init();
	
		$data = array();
		if($msg == "success"){
			$data['successMessage'] = true;
		}

		$data['tagEntity'] = $data['shikshaEntity'] = $this->input->post('shikshaEntity');
		$data['pendingMappingTableId'] = isset($_POST['pendingMappingTableId'])?$this->input->post('pendingMappingTableId'):0;
		$data['shikshaEntityId'] = $this->input->post('shikshaEntityId');
		// get tag name
		if($data['pendingMappingTableId']){
			//$entityData = $this->tagcmsmodel->getEntityData(array($data['tagEntity']=> array($data['shikshaEntityId'])));
			$tagData = $this->tagcmsmodel->fetchShikshaEntityToTagsMapping($data['shikshaEntityId'],$data['shikshaEntity']);
			//$data['tagName'] = $entityData[$data['tagEntity']][$data['shikshaEntityId']]['name'];
			$data['tagName'] = $tagData['tagName'];
			$data['tagId'] = $tagData['tagId'];
		}
	
		$data['validateuser'] = $this->userStatus;
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['sessionId'] = sessionId();

		$this->load->view('deleteTagsPage',$data);
	}

	public function fetchTagAutoSuggestions($param){

		if($param == 'syn'){
			$this->init();
			$val = trim($this->input->get('term'));
			$result = $this->tagcmsmodel->fetchManualAutoSuggestorSuggestions($val);
			echo json_encode($result);
			//echo '[{"value":"Visual Communication","id":119},{"value":"Communication Design","id":121},{"value":"Comparative Law","id":143},{"value":"Music","id":258}]';		
		} 
		else {
			$this->AutosuggestorLib = $this->load->library('search/Autosuggestor/AutosuggestorLib');
		    $val = trim($this->input->get('term'));
			$response = $this->AutosuggestorLib->getAutoSuggestions('tag',$val);

			$formattedData = $myarray = array_map(function($tag) {
											return array(
										        'id' => $tag['tagId'],
										        'value' => $tag['tagName']
										    ); }, $response['tag']);

			echo json_encode($formattedData);	
		}
	    
		
	}

	public function showTagsVarients(){

		$this->init();		
		$tagName = htmlspecialchars($this->input->post('tagName'),ENT_QUOTES);
		$tagEntity = $this->input->post('tagEntity');
		
		$result = $this->TaggingCMSLib->showVarients($tagName,$tagEntity);
		if(!empty($result)){
			foreach ($result as $key => $value) {
				$temp = $this->tagcmsmodel->findIfTagExists($value['value'],$tagEntity);
				if(!(empty($temp))){				
					unset($result[$key]);
				}
			}
		}
		echo json_encode($result);		
	}

	public function findTagExistence(){
		$this->init();
		$tagName = $this->input->post('tagName');
		$tagEntity = $this->input->post('tagEntity');

		$result = $this->tagcmsmodel->findIfTagExists($tagName,$tagEntity);
		echo json_encode($result);

	}	

	function findChildTags(){

		$this->init();
		$tagId = $this->input->post('tagId');
		
		$p = $this->input->post('param');
		$groupResultEntityWise = $this->input->post('groupResultByEntity');
		$result = array();
		if($p) {
			$result = $this->tagcmsmodel->findAllChildTags($tagId);	
		} else {
			$result = $this->tagcmsmodel->fetchChildTagsFromDBWithOnlyParent($tagId);	
		}

		$groupWiseResult = array();
		$tempData = array();
		if($groupResultEntityWise == "true"){
			foreach ($result as $key => $value) {
				if(array_key_exists($value['tagEntity'], $groupWiseResult)){
					$tempData = array();
					$tempData['tagId'] = $value['tagId'];
					$tempData['tagName'] = $value['tagName'];
					$groupWiseResult[$value['tagEntity']][] = $tempData;
				}
				else {
					$tempData = array();
					$tempData['tagId'] = $value['tagId'];
					$tempData['tagName'] = $value['tagName'];
					$groupWiseResult[$value['tagEntity']] = array();
					$groupWiseResult[$value['tagEntity']][] = $tempData;	
				}
			}

			$result = $groupWiseResult;
		}
		
		echo json_encode($result);
	}


	function showImpactOnDelete(){

		$this->init();
		$tag_id = $this->input->post('tag_id');
		$action = $this->input->post('action');

		$childs = array();
		if($action == 'delete'){
			//Unserialize the childs Array
			parse_str($_POST['childs'],$childs);
			$childs = $childs['childs'];
		}
		if(!empty($childs)){
			$totalTags = array_merge($childs,(array)$tag_id);	
		}else {
			$totalTags = array($tag_id);
		}
		
//
		$result['total_impact_count'] = $this->tagcmsmodel->findCountOfTaggedQuestion($totalTags);
		$result['no_tags_impact'] = $this->tagcmsmodel->findCountOfTaggedQuestionWithOnlyTags($totalTags);

/*		
		$result = array(
			'no_tags_impact' => 16,    
			'total_impact_count' => 68
			
		);
*/
		echo json_encode($result);
	}

	function showDetailedImpactOnDelete($type){

		$this->init();


		$displayData = array();
		$finalTags = array();
		$tag_id = $this->input->post('impact_tag_id');
		$type = $this->input->post('type');
		parse_str($_POST['impact_childs_id'],$childs);
		if(!empty($childs)){
			$finalTags = $childs['childs'];	
			array_unshift($finalTags, $tag_id);
		}		
		else {
			$finalTags = array($tag_id);
		}
		
		$result = array();
		if($type == "total_impact_count"){
			$result = $this->tagcmsmodel->findTaggedQuestion($finalTags);
		}
		else if($type == "no_tags_impact"){
			$result = $this->tagcmsmodel->findTaggedQuestionWithOnlyTags($finalTags);
		}
		$data = array();
		foreach ($result as $key => $value) {
			$data[$value['content_type']][] = $value['content_id'];
		}
		/*$data = array_values($result['question']);
		$data = array_merge($data,$result['discussion']);*/
		$displayData['finalResult'] = $this->taggingcmsmodel->fetchQuestionOrDiscussionsById($data);

		//$newstart = $start + $count;
		//$paginationURL = '/Tagging/TaggingCMS/showDetailedImpactOnDelete/'.$type.'/@start@/@count@';
		//$displayData['paginationHTML'] = doPagination(100,$paginationURL,$start,$count);
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();

		$this->load->view('deleteImpact.php',$displayData);

	}




	public function addTags(){




		ini_set("memory_limit", '512M');
		$this->init();		
		$trackingData = array();
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');		
		$tagName = trim($this->input->post('tag_add'));				
		//$tagName = "MBA";
		$impactKeyName = "tagsImpact:question:".$tagName;
		$tagKeyName = "tagName:".$tagName;		


		$completeTagData = $this->redisLib->getAllMembersOfHashWithValue($tagKeyName);

		$tagsMappingData = (array)json_decode($completeTagData['mappingsData']);
		$pendingMappingTableId = $completeTagData['pendingMappingTableId'];



		$questionsTagData = array();
		$autoTagQuestionFlag = false;
		if($this->input->post('tag_ques_auto')){

			$autoTagQuestionFlag = true;
			$questionsTagData = $this->redisLib->getAllMembersOfHashWithValue($impactKeyName);

			$questionsTagData = array_map(function($tempJson){
				$newArr =  (array)json_decode($tempJson);
				return $newArr;
			},$questionsTagData);
			
		}

		
		$tagParentIdArray = (array)json_decode($completeTagData['parentIdsArray']);
		$tagSynonymArray = (array)json_decode($completeTagData['synonymsarray']);
		$tagEntity = trim($completeTagData['tag_entity']);
		$tagDesc = trim($completeTagData['tag_description']);
		$varientsArray = (array)json_decode($completeTagData['varientsArray']);		

		$tagParentIdArray = array_unique($tagParentIdArray);
		$tagSynonymArray = array_unique($tagSynonymArray);		

		unset($completeTagData);


		//$tagId = $id = intval($this->tagcmsmodel->fetchMaxDBID()) + 1;
		//$tagId = $id = 99;		

		$tagscriptmodel = $this->load->model('Tagging/taggingscriptsmodel');
		$dataArray = array('tagName' => trim($tagName),
								'tagEntity' => $tagEntity,
								'description' => $tagDesc
							);
		$tagId = $id = $tagscriptmodel->addTag($dataArray,false);

		// insert main tag
		//$tagInsertedId = $this->tagcmsmodel->addTags($dataArray);
		modules::run('search/Indexer/addToQueue', $tagId,'tag'); 	
		unset($dataArray);
		$this->tagcmsmodel->updateAllMappingsForTag($tagId,$tagsMappingData['shikshaEntity'],$tagsMappingData['shikshaEntityToMapped'],$this->totalShikshaEntites,'add');

		// insert varient tag
		$count = 0;
		//$id = intval($this->tagcmsmodel->fetchMaxDBID()) + 1;		
		//$id = 100;		
		/*$var['id'] = $id;
		$var['tagId'] = $tagId;*/
		$parentArray = array();

/*		if($autoTagQuestionFlag){
			$questionsTagData = $this->modifyQuestionData($questionsTagData,$var);
		}*/
		foreach ($varientsArray as $key => $value) {
			foreach ($value as $key1=>$varient) {
				$key1 = str_replace("'", "", $key1);
				$dataArray = array('tagName' => trim($varient),
											'tagEntity' => $key,
											'description' => '',
										);
				$id = $tagscriptmodel->addTag($dataArray,false);
				$parentArray[$count] = array(
										'tag_id' => $id,
										'parent_id' => $tagId,
										'creationTime' => date("Y-m-d H:i:s")
									);
				$trackingData['varients'][] = $id;
				modules::run('search/Indexer/addToQueue',$id,'tag'); 
				$count++;
				unset($dataArray);	
			}
		}
		unset($varientsArray);

		// insert synonym
		$count = 0;
		foreach ($tagSynonymArray as $key => $value) {
			$synonymTagId = $tagscriptmodel->createSynonymForTags($tagId, trim($value) ,$tagEntity." synonym");
			$trackingData['synonyms'][] = $synonymTagId;
			$count++;
		}	
		if(!empty($tagSynonymArray)){
			modules::run('search/Indexer/addToQueue',$tagId,'tag');
		}
		


		//insert parents
		unset($dataArray);
		
		foreach ($tagParentIdArray as $key => $value) {
			$parentArray[] = array(
										'tag_id' => $tagId,
										'parent_id' => $value,
										'creationTime' => date("Y-m-d H:i:s")
										);
			modules::run('search/Indexer/addToQueue',$value,'tag'); 
			$trackingData['parents'][] = $value;
		}
		if(!empty($parentArray)){
			$this->tagcmsmodel->addParentTags($parentArray); 
		}
		unset($parentArray);

		if($autoTagQuestionFlag){
			$questionsTagData = $this->modifyQuestionData($questionsTagData,$var);
		}
		if($autoTagQuestionFlag && !empty($questionsTagData)){
			$this->addContentMappingToDB($questionsTagData);	
			$this->addContentMappingToRedis($questionsTagData);	
		}
		
		$json_data = json_encode($trackingData);
		$this->addTrackingDetails('add','',$tagId,$json_data);

		if($this->redisLib->checkIfKeyExists($impactKeyName)){
			
			$this->redisLib->deleteKey(array($impactKeyName));
		}
		if($this->redisLib->checkIfKeyExists($tagKeyName)){
			
			$this->redisLib->deleteKey(array($tagKeyName));	
		}
		if($pendingMappingTableId > 0){
			$this->tagcmsmodel->updatePendingTagPostingStatus('done',$pendingMappingTableId);
		}
		redirect('/Tagging/TaggingCMS/showAddTagsImpact/addedToDB');

	}



	public function array_iunique($array) {
	    return array_intersect_key(
	        $array,
	        array_unique(array_map("StrToLower",$array))
	    );
	}


	public function addTagsToRedis(){

			$this->init();
			$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
			
			$tagName = trim($this->input->post('tagName'));
			$tagEntity = trim($this->input->post('tagEntity'));
			$tagDesc = htmlspecialchars(trim($this->input->post('tagDescription')),ENT_QUOTES);
			$varientsArray = $this->input->post('varients');
			$tagParentIdArray = $this->input->post('tag_parent_id');
			$tagSynonymArray = $this->input->post('tag_synonym');
			$pendingMappingTableId = $this->input->post('pendingMappingTableId');


			
				
			foreach ($varientsArray as $key => $value) {
				$varientsArray["'".$key."'"] = $varientsArray[$key];
				unset($varientsArray[$key]);
			}

			$varientsArrayNew = array();
			if($tagEntity == "Course"){
				foreach ($varientsArray as $key => $value) {
					//$value = htmlspecialchars($value,ENT_QUOTES);
					if(strpos($value, $tagName) === 0){
						$varientsArrayNew['Course varients'][$key] = $value;
					} else {
						$varientsArrayNew['Course Modes'][$key] = $value;
					}
				}
			} else {
				$varientsArrayNew[$tagEntity.' varients'] = $varientsArray;
			}

			$tagParentIdArray = array_filter(array_unique($tagParentIdArray));
			$tagSynonymArray = array_filter($this->array_iunique($tagSynonymArray));

			
			$redisData = array(
				'tag_name' => $tagName,
				'tag_entity' => $tagEntity,
				'tag_description' => $tagDesc,
				'varientsArray' => json_encode($varientsArrayNew),
				'parentIdsArray' => json_encode($tagParentIdArray),
				'synonymsarray' => json_encode($tagSynonymArray),
				'pendingMappingTableId' => $this->input->post('pendingMappingTableId'),
			);
			
			$shikshaEntityArray = $this->input->post('shikshaEntity');
			$shikshaEntityToMappedArray = $this->input->post('shikshaEntityToMapped');
			$counter=1;

			$redisDataInner = array();
			foreach ($shikshaEntityArray as $key => $value) {
				if($shikshaEntityArray[$key] != "" && $shikshaEntityToMappedArray[$key] != "" && ctype_digit($shikshaEntityToMappedArray[$key])){
					$redisDataInner['shikshaEntity'][] = $shikshaEntityArray[$key];
					$redisDataInner['shikshaEntityToMapped'][] = $shikshaEntityToMappedArray[$key];
					$counter++;
				}
			}
			
			$redisData['mappingsData'] = json_encode($redisDataInner);

			if($pendingMappingTableId != "" & $pendingMappingTableId > 0){
				$this->tagcmsmodel->updatePendingTagPostingStatus('addedToRedis',$pendingMappingTableId);
			}

			$this->redisLib->addMembersToHash("tagName:".$tagName,$redisData);
			$this->redisLib->expireKey("tagName:".$tagName,4*24*60*60);
			$this->addTrackingDetails('addtoRedis','',0,'');
			redirect('/Tagging/TaggingCMS/showAddTagsForm/addedToRedis');
			
	}

	// single parent only
	public function deleteTags(){

		$this->init();
		$tag_id = $this->input->post('tag_id');
		$childs = $this->input->post('childs');
		$action = $this->input->post('action');

		
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$reassign_tag_id = $this->input->post('reassign_tag_id');


		$childs_array = $tags_array = $childs;
		$this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
		$userInteractionCacheStorageLibrary = new UserInteractionCacheStorageLibrary();
		// Indexing Starts
		if($action == "reassign"){									

			//Index(delete) main Tag
			modules::run('search/Indexer/addToQueue', $tag_id,'tag','delete');
			$userInteractionCacheStorageLibrary->deleteEntity($data['userId'],$tag_id,'tag','none');

			// Delete Tag listing
			$this->tagcmsmodel->deleteTagListing($tag_id);

			// Index all childs of main Tag
			$childs_index = $this->tagcmsmodel->findChildTags(array($tag_id));			
			
			foreach ($childs_index as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value['tag_id'],'tag'); 	
			}
			
			// Index all parents of Main Tag
			$parents_index = $this->tagcmsmodel->findParentTags($tag_id);
			foreach ($parents_index as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value['parentId'],'tag'); 	
			}
			
			// index only new reassign parent
			modules::run('search/Indexer/addToQueue', $reassign_tag_id,'tag'); // new reassign parent of childs
		} 
		else if($action == 'delete'){

			//Index(delete) main Tag
			modules::run('search/Indexer/addToQueue', $tag_id,'tag','delete');
			$userInteractionCacheStorageLibrary->deleteEntity($data['userId'],$tag_id,'tag');

			$this->tagcmsmodel->deleteTagListing($tag_id);
			
			//Index(Delete)  all listed childs
			foreach ($childs as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value,'tag','delete'); 	
				$userInteractionCacheStorageLibrary->deleteEntity($data['userId'],$value,'tag');
				$this->tagcmsmodel->deleteTagListing($value);
			}

			// Normal Index all Remain Childs
			$childs_index = $this->tagcmsmodel->findChildTags(array($tag_id));			
			$all_childs = array_map(function($tempArr){
				return $tempArr['tag_id'];
			},$childs_index);
			$remain_childs = array_diff($all_childs, $childs);

			foreach ($remain_childs as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value,'tag'); 	
			}

			// Index all parents of Main Tag
			$parents_index = $this->tagcmsmodel->findParentTags($tag_id);
			foreach ($parents_index as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value['parentId'],'tag'); 	
			}

			// Index all childs of listed childs of Main tag(ONLY INDEX)
			unset($childs_index);
			$childs_index = $this->tagcmsmodel->findChildTags($childs);			
			$all_childs = array_map(function($tempArr){
				return $tempArr['tag_id'];
			},$childs_index);

			foreach ($all_childs as $key => $value) {								
				modules::run('search/Indexer/addToQueue', $value,'tag'); 	
			}
		}
		// Indexing ends
		if($action == 'reassign'){
			$tags_array = array($tag_id);

			if(!empty($childs_array)){
				$this->tagcmsmodel->updateParentMapping($tag_id,$reassign_tag_id,$childs_array);	
			}
		}
		else if($action == 'delete'){
			if(empty($childs)){
				$tags_array = array($tag_id);
			}else {
				array_unshift($tags_array,$tag_id);	
			}			
		}

		$syn_array = $this->tagcmsmodel->fetchSynList($tags_array);


		foreach ($syn_array as $key => $value) {
			$tags_array[] = $value['id'];
		}
		
		$json_data['action'] = $action;
		$json_data['reassign_tag_id'] = $reassign_tag_id;
		$json_data['data'] = $tags_array;
		$json_data = json_encode($json_data);
		$this->addTrackingDetails('delete',$action,$tag_id,$json_data);		

		// Index all questions
		$questions_to_index = $this->tagcmsmodel->deleteTagsFromDB($tags_array);
		foreach ($questions_to_index as $key => $value) {
			modules::run('search/Indexer/addToQueue', $value['content_id'],$value['content_type']);
		}

		$pendingMappingTableId = $this->input->post('pendingMappingTableId');
        if($pendingMappingTableId > 0){
        	$this->tagcmsmodel->updatePendingTagPostingStatus('done',$pendingMappingTableId);
        }

		redirect('/Tagging/TaggingCMS/showDeleteTagsForm/success');

	}


	public function showEditTagsForm($msg){
		$this->init();
		$data['msg'] = $msg;

		$data['tagEntity']             = $data['shikshaEntity'] = $this->input->post('shikshaEntity');
		$data['pendingMappingTableId'] = isset($_POST['pendingMappingTableId']) ? $this->input->post('pendingMappingTableId') : 0;
		$data['shikshaEntityId']       = $this->input->post('shikshaEntityId');
		$data['action']                = $this->input->post('action');
		$data['additionalParams']      = $this->input->post('additionalParams');
		
		// get tag name
		if($data['pendingMappingTableId']){
			$entityData = $this->tagcmsmodel->getEntityData(array($data['tagEntity']=> array($data['shikshaEntityId'])));
			$data['expectedNewTagName'] = $entityData[$data['tagEntity']][$data['shikshaEntityId']]['name'];
			$data['newMappingEntityId'] = $data['shikshaEntityId'];
			$data['newMappingEntity'] = $data['shikshaEntity'];
			$tagData = $this->tagcmsmodel->fetchShikshaEntityToTagsMapping($data['shikshaEntityId'],$data['shikshaEntity']);
			$data['oldTagName'] = $tagData['tagName'];
			$data['tagId'] = $tagData['tagId'];
			if(empty($tagData) ){
				$data['tagId'] = $mappedTagId = $this->input->post('mappedTagId');
				$data['oldTagName'] = $mappedTagName = $this->input->post('mappedTagName');
			}
			
			$data['additionalParams'] = (array)json_decode(base64_decode($data['additionalParams']));
		}

		$data['validateuser'] = $this->userStatus;
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['sessionId'] = sessionId();
		$this->load->view('editTagsPage',$data);
	}

	public function fetchSynList(){
		$this->init();
		$tag = $this->input->post('tag');
		$tags_array = array($tag);
		$syn_array = $this->tagcmsmodel->fetchSynList($tags_array);
		$formattedData = array_map(function($tag){
			return array(
				'id' => $tag['id'],
				'value' => $tag['tags']
				);				
		}, $syn_array);

		foreach ($formattedData as $key => $row) {
		    $values[$key]  = $row['value'];
		}

		array_multisort($values, SORT_ASC, $formattedData);
		echo json_encode($formattedData);
	}	

	public function checkParentChildMappingExits(){
		$this->init();
		$tag_id = $this->input->post('tag_id');
		$parent_id = $this->input->post('parent_id');

		$result = $this->tagcmsmodel->checkParentChildMapping($tag_id,$parent_id);
		echo $result;
	}

	/**
	*
	*/
	public function findParentTags(){
		$this->init();
		$tag_id = $this->input->post('tag_id');

		$parentIds = $this->tagcmsmodel->findParentTags($tag_id);
		echo json_encode($parentIds);
	}

	public function editTags(){

		$this->init();
		$action = trim($this->input->post('action'));
		$redirectURL = SHIKSHA_HOME."/Tagging/TaggingCMS/showAddTagsForm";
		switch($action) {
            case "rename_tag":
                $redirectURL = $this->edit_rename_tag();
                break;
            case "add_synonym":
                $redirectURL = $this->edit_add_synonym();
                break;
            case "delete_synonym":
                $redirectURL = $this->edit_delete_synonym();
                break;
            case "add_parent":
                $redirectURL = $this->edit_add_parent();
                break;
            case "delete_parent":
                $redirectURL = $this->edit_delete_parent();
                break;
        }

        redirect($redirectURL);
       
	}

	public function edit_rename_tag(){

		$tag_id = $this->input->post('old_tag_id');
		$oldTagName = $this->input->post('old_tag_name');
		$newTagName = $this->input->post('tagName');
		$tagsToBeRenamed = $this->input->post('rename_varients');
		array_unshift($tagsToBeRenamed, $tag_id);
		if(!empty($tagsToBeRenamed)){
			$tagsInfo = $this->tagcmsmodel->fetchTagsInfo($tagsToBeRenamed);
			$this->tagcmsmodel->renameMultipleTags($tagsInfo,$oldTagName,$newTagName);
		}

		foreach ($tagsToBeRenamed as $key => $value) {
			modules::run('search/Indexer/addToQueue', $value,'tag');	
		}
		$this->addTrackingDetails('edit','renameTag',$tag_id,json_encode(array('allTagsRenamed' => $tagsToBeRenamed)));

		$pendingMappingTableId = $this->input->post('pendingMappingTableId');
		if($pendingMappingTableId > 0){
			$this->tagcmsmodel->updatePendingTagPostingStatus('done',$pendingMappingTableId);
		}

		$redirectURL = '/Tagging/TaggingCMS/showEditTagsForm/renamesuccess';
		return $redirectURL;
	}

	public function edit_add_synonym(){
		$tag_id = $this->input->post('tag_id');
		$tag_synonyms = $this->input->post('tag_synonym');

		$tag_synonyms = array_unique(array_filter($tag_synonyms));

		$this->tagcmsmodel->addSynonyms($tag_id,$tag_synonyms);
		$this->addTrackingDetails('edit','addSynonym',$tag_id,json_encode($tag_synonyms));
		modules::run('search/Indexer/addToQueue', $tag_id,'tag');
		//redirect('/Tagging/TaggingCMS/showEditTagsForm/addsynsuccess');
		$redirectURL = '/Tagging/TaggingCMS/showEditTagsForm/addsynsuccess';
		return $redirectURL;
		
	}

	public function edit_delete_synonym(){
		$tag_id = $this->input->post('tag_id_del_syn');
		$tag_synonyms = $this->input->post('synonym');
		$tag_synonyms = array_unique(array_filter($tag_synonyms));	

		$this->tagcmsmodel->deleteSynonyms($tag_id,$tag_synonyms);
		$this->addTrackingDetails('edit','deleteSynonym',$tag_id,json_encode($tag_synonyms));
		modules::run('search/Indexer/addToQueue', $tag_id,'tag');
//		redirect('/Tagging/TaggingCMS/showEditTagsForm/delsynsuccess');
		$redirectURL = '/Tagging/TaggingCMS/showEditTagsForm/delsynsuccess';
		return $redirectURL;
	}

	public function edit_add_parent(){
		$tag_id = $this->input->post('tag_id_add_parent');
		$parent_id = $this->input->post('parent_id_add_parent');	
		$dataArray[0] = array(
								'tag_id' => $tag_id,
								'parent_id' => $parent_id
							);
		$this->tagcmsmodel->addUpdateParentChildMapping($tag_id,$parent_id);
		$this->addTrackingDetails('edit','addParentMapping',$tag_id,json_encode($parent_id));
		modules::run('search/Indexer/addToQueue', $tag_id,'tag');

		modules::run('search/Indexer/addToQueue', $parent_id,'tag');
		$redirectURL = '/Tagging/TaggingCMS/showEditTagsForm/addparentsuccess';
		return $redirectURL;

	}

	public function edit_delete_parent(){
		$tag_id = $this->input->post('tag_id_delete_parent');
		$parent_ids = $this->input->post('parent');
		$parent_ids = array_filter($parent_ids);
		$this->tagcmsmodel->deleteParentChildMappings($tag_id,$parent_ids);
		$this->addTrackingDetails('edit','deleteParentMapping',$tag_id,json_encode($parent_ids));
		modules::run('search/Indexer/addToQueue', $tag_id,'tag');
		foreach ($parent_ids as $key => $value) {
			modules::run('search/Indexer/addToQueue', $value,'tag');
		}
		//redirect('/Tagging/TaggingCMS/showEditTagsForm/deleteparentsuccess');	
		$redirectURL = '/Tagging/TaggingCMS/showEditTagsForm/deleteparentsuccess';
		return $redirectURL;

	}	

	public function addTrackingDetails($action,$subaction,$tag_id,$data) {
		$this->init();
		$email = "";
		if(isset($_COOKIE) && isset($_COOKIE['user'])){
			$cookie = $_COOKIE['user'];
			list($email) = explode("|", $cookie);
		}
		$this->tagcmsmodel->insertTrackingDetails($action,$subaction,$tag_id,$data,$email);
	}

	public function viewTagsDetails(){
		$this->init();
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();

		$this->load->view('viewTagsPage',$displayData);
	}

	public function findTagEntity(){
		$this->init();
		$tag_id = $this->input->post('tag_id');
		echo $tag_entity = $this->tagcmsmodel->fetchTagEntity($tag_id);
	}

	public function findSynOfTag(){
		$this->init();
		$tag_id = $this->input->post('tag_id');
		$result = $this->tagcmsmodel->findSynOfTag($tag_id);
		echo $result;
	}

	public function redisDemo($param='set'){
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$arr1 = array();
		for($i = 0;$i<100000;$i++){
			$arr1[] = "ankit".$i;
		}
		if($param == 'set'){
			//$this->redisLib->addMemberToString('name','ankit','1000');
			$arr = array(
					'name' => 'ankit',
					'entity' => 'courseq',
					'description' => '',
					'childs' => json_encode($arr1)
				);
			$this->redisLib->addMembersToHash('tagName:ankit',$arr);
		}
		if($param == 'get'){
			//_p($this->redisLib->fetchKeysBasedOnPattern('tagName:*'));
			_p($this->redisLib->fetchKeysBasedOnPattern('tagsImpact:*'));
			$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagsImpact:question:*');
			foreach ($keysArray as $tagKeyName) {
				$data = $this->redisLib->getAllMembersOfHashWithValue($tagKeyName);
				_p($data);
			}

			_p($this->redisLib->fetchKeysBasedOnPattern('tagName:*'));

			$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagName:*');
			foreach ($keysArray as $tagKeyName) {
				$data = $this->redisLib->getAllMembersOfHashWithValue($tagKeyName);
				_p($data);
			}
		}

	}

	public function fetchDataFromRedis(){
		
		//$this->init(true);
		
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagName:*');
		$redisData = array();
		$parentIdsArray = array();
		
		// Create Data for redis (By running the code on last 10 days questions)
		foreach ($keysArray as $tagKeyName) {			
		
			list(,$tagName) = explode(":", $tagKeyName);
			$data = $this->redisLib->getAllMembersOfHashWithValue($tagKeyName);
			
			$this->populateListsOfTag($data);
			
			$questionsArray = $this->tagcmsmodel->findLastNDaysQuestions();
			

			$impactKeyName = "tagsImpact:question:".$tagName;
		
			$parentIdsArray = json_decode($data['parentIdsArray']);

			foreach ($questionsArray as $value) {

				$question = $value['msgTxt'];				
				$threadId = $value['threadId'];
				error_log("**** Working ************** ".$threadId);
				$dataQues[0] = $question;
				$result = $this->TaggingLib->showTagSuggestions($dataQues,'impact');		
			
				$redisData[$impactKeyName][$threadId]['objective'] = array_filter($result['objective'],array($this,"myFilter"));
				$redisData[$impactKeyName][$threadId]['background'] = array_filter($result['background'],array($this,"myFilter"));
				$redisData[$impactKeyName][$threadId]['type'] = 'question';
				$redisData[$impactKeyName][$threadId]['userId'] = $value['userId'];
			}

			$discussionArray = $this->tagcmsmodel->findLastNDaysDiscussions();

			foreach ($discussionArray as $value) {
				$discussion = $value['msgTxt'];
				$threadId = $value['threadId'];				
				error_log("**** Working ************** ".$threadId);
				$dataDis[0] = $discussion;
				$result = $this->TaggingLib->showTagSuggestions($dataDis,'impact');				
				$redisData[$impactKeyName][$threadId]['objective'] = array_filter($result['objective'],array($this,"myFilter"));
				$redisData[$impactKeyName][$threadId]['background'] = array_filter($result['background'],array($this,"myFilter"));
				$redisData[$impactKeyName][$threadId]['type'] = 'discussion';
				$redisData[$impactKeyName][$threadId]['userId'] = $value['userId'];
				
			}

			// Format the data to store in Redis
			$newRedisData = array_map(function($arr) use ($parentIdsArray) {


				$arr['background'] = array_diff($arr['background'], $arr['objective']);


				if(!empty($arr['objective'])){
					$newArr['objective'] = $arr['objective'];

					if(in_array('0', $arr['objective'],true) && !empty($parentIdsArray)){					
						$newArr['objective_parent'] = array_values($parentIdsArray);
					}
					if(count($arr['objective'] >= 1) && !in_array('0', $arr['objective'],true)){
						$newArr['objective_parent'][] = '0';
					}

				}
					
				if(!empty($arr['background'])) {
					$newArr['background'] = $arr['background'];

					if(in_array('0', $arr['background'],true) && !empty($parentIdsArray)){					
						$newArr['background_parent'] = array_values($parentIdsArray);
					}
					if(count($arr['background'] >= 1) && !in_array('0', $arr['background'],true)){
						$newArr['background_parent'][] = '0';
					}
				}
					
				if(empty($newArr))
					return $newArr;
				else{
					$newArr['type'] = $arr['type'];
					$newArr['userId'] = $arr['userId'];
					return json_encode($newArr);	
				}
					
			}, $redisData[$impactKeyName]);
			
			
			$finalRedisData = array_filter($newRedisData,array($this,"myFilter"));

			// Store in redis
			if(!empty($finalRedisData)){
				$this->redisLib->addMembersToHash($impactKeyName,$finalRedisData);
				$this->redisLib->expireKey($impactKeyName,4*24*60*60);	
				//$this->addTrackingDetails('addQuestiontoRedis','',0,'');

			}
			

			unset($finalRedisData);
			unset($newRedisData);
			unset($redisData);
			$this->clearListsOfTag($data);

		}
		

		
	}

	private function myFilter($var){
	  return ($var !== NULL && $var !== FALSE && $var !== '' && $var !== null);
	}

	

	public function findChildForMultipleTags(){
		$this->init();
		parse_str($_POST['childs'],$childs);
		$childs = $childs['childs'];

		$result = $this->tagcmsmodel->findChildTags($childs);
		$result = json_encode($result);
		echo $result;

	}

	public function findTagsById(){
		$tagsArray = $this->input->post('tags');
		$this->init();
		$result = $this->tagcmsmodel->findTagById($tagsArray); 

		echo json_encode($result);
	}

	public function populateListsOfTag($data) {	
		
		$fields = array();
		$fields['populateLists'] = "true";
		$fields['tag_name'] = $data['tag_name'];
		$fields['tag_entity'] = $data['tag_entity'];
		$fields['tag_description'] = $data['tag_description'];
		$fields['synonymsarray'] = $data['synonymsarray'];
		$fields['parentIdsArray'] = $data['parentIdsArray'];
		$fields['varientsArray'] = $data['varientsArray'];


		$field_string = http_build_query($fields);
		//$path = "http://localhost:8080/Tagging/tagsImpact";
		$path = TAGGING_SERVER_IMPACT_URL;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$path);
	    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);


	    echo curl_error($ch);
	    $retValue = curl_exec($ch);             
	    curl_close($ch);
	    
	    return $retValue;
	}

	public function deleteTagFromRedis($tagName){
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagName:*');
		$this->redisLib->deleteKey($keysArray);
		$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagsImpact:question:*');
		$this->redisLib->deleteKey($keysArray);
	}

	public function showAddTagsImpact($msg=""){
		$this->init();
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$keysArray = $this->redisLib->fetchKeysBasedOnPattern('tagName:*');
		$displayData = array();
		$displayData['msg'] = $msg;
		foreach ($keysArray as $value) {
			$displayData['tags'][] = $value;
		}
		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		
		$this->load->view('addTagsImpactPage',$displayData);
	}

	public function clearListsOfTag(){
		$fields = array();
		$fields['emptyLists'] = "true";
		
		$field_string = http_build_query($fields);
		//$path = "http://localhost:8080/Tagging/tagsImpact";
		$path = TAGGING_SERVER_IMPACT_URL;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$path);
	    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);


	    echo curl_error($ch);
	    $retValue = curl_exec($ch);             
	    curl_close($ch);
	    	    
	    return $retValue;
	}

	public function viewDetailAdditionImpact($tag='MBA'){

		$this->init();
		$tag = $this->input->post('tag');
		$this->redisLib = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$impactKeyName = "tagsImpact:question:".$tag;
		$tagKeyName = "tagName:".$tag;
		$completeTagDataArray = array();
		$viewCount = 20;
		$isKeyExists = $this->redisLib->checkIfKeyExists($impactKeyName);
		if($isKeyExists){
			
			$data = $this->redisLib->getAllMembersOfHashWithValue($impactKeyName);
			$completeTagData = $this->redisLib->getAllMembersOfHashWithValue($tagKeyName);

			$completeTagDataArray[0] = $completeTagData['tag_name'];
			$varientsArray = (array) json_decode($completeTagData['varientsArray']);

			foreach ($varientsArray as $key => $varientsSubArray) {
				foreach ($varientsSubArray as $varient) {
					$completeTagDataArray[] = $varient;
				}
			}
			unset($completeTagData);
			unset($varientsArray);
			unset($varientsSubArray);

			if(count($data) > $viewCount){
				$randomData = array_rand($data,$viewCount);	
			} else {
				$randomData = array_keys($data);
			}
			$displayData = array();			
			foreach ($randomData as $value) {
				$tempArr = json_decode($data[$value]);
				$displayData[$tempArr->type][] = $value;
			}
			
			$result = $this->tagcmsmodel->fetchQuestionOrDiscussionsById($displayData);
			unset($displayData);
			unset($randomData);
			foreach ($result as $key => $value) {
				$tagsArray = (array) json_decode($data[$value['threadId']]);
				unset($tagsArray['type']);

				foreach ($tagsArray as $tagType => $actualTagsArray) {
					if(strpos($tagType, "_parent") !== FALSE) {
						unset($tagsArray[$tagType]);
						continue;
					}
					foreach ($actualTagsArray as $arrayKey=>$tagKey) {
						$tagsArray[$tagType][$arrayKey] = $completeTagDataArray[$tagKey];
					}
				}
				$result[$key]['tags'] = $tagsArray;

			}
			$displayData['data'] = $result;
			

		} else{
			$displayData['error'] = 1;
		}

		$displayData['validateuser'] = $this->userStatus;
		$displayData['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$displayData['sessionId'] = sessionId();
		$this->load->view('detailAddImpact',$displayData);
	}

	private function modifyQuestionData($questionsTagData,$var)
	{
		try{
			$questionsTagData = array_map(function($tagsArray) use ($var){
				$tagId = $var['tagId'];
				$id = $var['id'];
				
				$newArr = array();

				if(array_key_exists('objective', $tagsArray)){		
					foreach ($tagsArray['objective'] as $key => $value) {						
						$value = intval($value);
						if($value == 0){
							$tagsArray['objective'][$key] = $tagId;							
						} else {							
							$tagsArray['objective'][$key] = $value + $id - 1;
						}
					}
				}

				if(array_key_exists('background', $tagsArray)){
					foreach ($tagsArray['background'] as $key => $value) {						
						$value = intval($value);
						if($value == 0){
							$tagsArray['background'][$key] = $tagId;
						} else {
							$tagsArray['background'][$key] = $value + $id - 1;

						}
					}
				}

				if(array_key_exists('objective_parent', $tagsArray)){
					foreach ($tagsArray['objective_parent'] as $key => $value) {						
						$value = intval($value);
						if($value == 0){
							$tagsArray['objective_parent'][$key] = $tagId;
						}													
					}
				}

				if(array_key_exists('background_parent', $tagsArray)){
					foreach ($tagsArray['background_parent'] as $key => $value) {						
						$value = intval($value);
						if($value == 0){
							$tagsArray['background_parent'][$key] = $tagId;
						}							
					}
				}

				$newArr = $tagsArray;				
				return $newArr;

			},$questionsTagData);

		$qids = array_keys($questionsTagData);

		$oldTagsDataForEntity = $this->tagcmsmodel->fetchTagsOnThreads($qids);

		foreach ($oldTagsDataForEntity as $key => $value) {
			$content_id = $value['content_id'];
			$oldTagsData = explode(",", $value['allTagsId']);
			$oldTagsData = array_filter(array_unique($oldTagsData));

			if(!empty($oldTagsData)){
				$newTagData = $questionsTagData[$content_id];

				foreach ($newTagData as $keyName => $valueData) {
					if($keyName == "objective_parent" || $keyName == "background_parent"){

						foreach ($valueData as $keyInner=>$valueDataInner) {
							if(array_search($valueDataInner, $oldTagsData) !== false){
								unset($questionsTagData[$content_id][$keyName][$keyInner]);
							}
						}
					}
					if(empty($questionsTagData[$content_id][$keyName])){
						unset($questionsTagData[$content_id][$keyName]);
					}
				}
			}
		}
		return $questionsTagData;
		}
		catch(Exception $e){
			return array();
		}
		
	}

	public function addContentMappingToDB($questionsTagData){

		$data = array();
		foreach ($questionsTagData as $key => $value) {
			$type = $value['type'];
			unset($value['type']);
			unset($value['userId']);
			$data[$type][$key] = $value;
			modules::run('search/Indexer/addToQueue', $key,$type); 	

		}		
		
		$this->tagcmsmodel->insertTagContentMapping($data);
	}
	
	
	function editTagsFromModeration(){
		
        $this->init();		
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

		$editEntityId = isset($_POST['editEntityId'])?$this->input->post('editEntityId'):'';
		$tagString = isset($_POST['tagString'])?$this->input->post('tagString'):'';
		$entityType = isset($_POST['entityType'])?$this->input->post('entityType'):'';
		$action = isset($_POST['action'])?$this->input->post('action'):'';

		if(isset($_POST['tagsName_'.$editEntityId]))
			$_POST['tagsName_'.$editEntityId] = array_unique($_POST['tagsName_'.$editEntityId]);
		if(isset($editEntityId) && $editEntityId != '')
		{
			foreach($_POST['tagsName_'.$editEntityId] as $tagId)
			{
				$TagArray[] = array('tagId'=>$tagId,'classification'=>$_POST['tagsClass_'.$tagId]);
			}
		}
		
		$this->load->library('Tagging/TaggingLib');
		$this->TaggingLib = new TaggingLib();
		$this->redisLib = PredisLibrary::getInstance();
		$this->redisLib->addMembersToHash('questionPostedLastNMins',array($editEntityId => 'edit'));

		if($action == 'Add Tags'){
			$finalTagArray = $this->TaggingLib->findTagsToBeAttached($TagArray);
			
		}else{
		
			$finalTagArray = $TagArray;
			
			//Edit tracking
			$this->load->library('v1/AnACommonLib');
			$this->anaCommonLib = new AnACommonLib();
			$this->anaCommonLib->trackEditOperation($editEntityId, $entityType, $userId);
			
			//First, remove all the Attached Tags
			$this->TaggingLib->deleteTagsWithContentToDB($editEntityId);
		
		}
		
		if(!empty($finalTagArray)){
		//Then, add the new tags
		$this->TaggingLib->insertTagsWithContentToDB($finalTagArray,$editEntityId,$entityType,"updatetag",$editType="editTag");
		}
		
	}

	public function updater(){
		$this->validateCron();
		$this->init(true);
		$isAddDone = false;
		$isUpdation = false;
		
		$isAddDone = $this->tagcmsmodel->findIfChangesDone('add');
		if($isAddDone){
			$this->fetchDataFromRedis();
		}

		$isUpdation = $this->tagcmsmodel->findIfChangesDone();
		if($isUpdation){
			$this->updateListsOfTag();
		}
	}

	public function updateListsOfTag(){
		$fields = array();
		$fields['updateLists'] = "true";
		
		$field_string = http_build_query($fields);
		//$path = "http://localhost:8080/Tagging/tagsImpact";
		$path = TAGGING_SERVER_URL;
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$path);
	    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $field_string);


	    echo curl_error($ch);
	    $retValue = curl_exec($ch);             
	    curl_close($ch);
	    	    
	    return $retValue;
	}

	public function findMultipleTagsExistence(){

	$this->init();
		$tagNameArray = $this->input->post('tagNameArray');
		
		$result = $this->tagcmsmodel->findIfMultipleTagExists($tagNameArray);
		echo json_encode($result);
	}

	public function addContentMappingToRedis($questionsTagData){
		if(empty($questionsTagData)){
			return;
		}
		
		foreach ($questionsTagData as $content_id => $tagsArray) {
			$content_type = $questionsTagData[$content_id]['type'];
			$userId = $questionsTagData[$content_id]['userId'];
			unset($tagsArray['type']);
			unset($tagsArray['userId']);

			$tagsFormattedArrayForRedis = array();

			foreach ($tagsArray as $classification=>$value) {
				foreach ($value as $tId) {
					if($classification == "objective" || $classification == "background" || $classification == "manual"){
						$tagsFormattedArrayForRedis['direct'][] = $tId;
					} else {
						$tagsFormattedArrayForRedis['parent'][] = $tId;
					}	
				}
				
			}

			if(array_key_exists('direct', $tagsFormattedArrayForRedis)){
				$tagsFormattedArrayForRedis['direct'] = array_filter($tagsFormattedArrayForRedis['direct']);				
			} else {
				$tagsFormattedArrayForRedis['direct'] = array();				
			}
			if(array_key_exists('parent', $tagsFormattedArrayForRedis)){
				$tagsFormattedArrayForRedis['parent'] = array_filter($tagsFormattedArrayForRedis['parent']);				
			} else{
				$tagsFormattedArrayForRedis['parent'] = array();
			}

			$userInteractionCacheStorageLib = $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
			$userInteractionCacheStorageLib->updateTagOfThreads($userId,$content_id,$content_type,$tagsFormattedArrayForRedis,'insert_specific');
		
		}

		
	}

	
	
	public function staticThreadBucketTable(){
		$this->load->config('TaggingConfig');
		$this->validateAccess();
		$predisLibrary = PredisLibrary::getInstance();
		$staticThreadIds = $predisLibrary->getMembersOFList('userHomeFeedStaticCuratedBucket', 0, -1);
		//_p($staticThreadIds);die;
		//$this->load->library('common/PredisLibrary');
		//$staticThreadIds = $this->predislibrary->getMembersOFList('userHomeFeedStaticCuratedBucket', 0, -1);
		//_p($staticThreadIds);
		$discussionIds = array();
		$questionIds = array();
		$discussionData = array();
		$questionData = array();
		foreach($staticThreadIds as $value){
			$temp = explode(':', $value);
			switch($temp[0]){
				case	'discussion'	:	$discussionIds[] = $temp[2];
											break;
				case	'question'		:	$questionIds[] = $temp[2];
											break;
				default:break;
			}
		}
		//_p($discussionIds);
		//_p($questionIds);
		$this->load->model('taggingmodel');
		$resultaData = $this->taggingmodel->fetctTextForMessage($discussionIds, 'discussion');
		foreach ($resultaData as $value){
			$discussionData[$value['threadId']] = $value;
		}
		$resultaData	= $this->taggingmodel->fetctTextForMessage($questionIds, 'question');
		foreach ($resultaData as $value){
			$questionData[$value['threadId']] = $value;
		}
		
		$displayData = array();
		$finalData = array();
		foreach($staticThreadIds as $value){
			$temp = explode(':', $value);
			switch ($temp[0]){
				case	'discussion'	:	if(isset($discussionData[$temp[2]]) && !empty($discussionData[$temp[2]])){
												$finalData[$temp[2]] = $discussionData[$temp[2]];
												$finalData[$temp[2]]['threadType']	= 'discussion';
											}
											break;
				case	'question'		:	if(isset($questionData[$temp[2]]) && !empty($questionData[$temp[2]])){
												$finalData[$temp[2]] = $questionData[$temp[2]];
												$finalData[$temp[2]]['threadType']	= 'question';
											}
											break;
				default					:	break;
				
			}
		}
		$displayData['threadData'] = $finalData;
		//_p($discussionData);
		//_p($questionData);die;
		//_p($displayData);die;
		$this->load->view('staticContentTable',$displayData);
	}
	
	public function saveStaticThreads() {
		$this->load->config('TaggingConfig');
		$this->validateAccess();
		// get all thread Ids from POST request
		$threadIds = $this->input->post('threadIds');
		$threadIds = json_decode($threadIds);
		//_p($threadIds);die;
		// check if non-numeric values are there in DB, then remove them
		foreach($threadIds as $key => $value){
			if(!is_numeric($value)){
				unset($threadIds[$key]);
			}
		}
		
		// get thread type from DB and also check that thread is from question or discussion only
		$this->load->model('taggingcmsmodel');
		$threadDetails = $this->taggingcmsmodel->checkThreadIdsForValidation($threadIds);
		//_p($threadDetails);die;
		// now prepare static threads data
		$threadDataForStorage = array();
		foreach($threadIds as $value){
			if(key_exists($value, $threadDetails)){
				$threadDataForStorage[] = ($threadDetails[$value]['threadType']).':thread:'.$value;
			}
		}
		
		$predisLibrary = PredisLibrary::getInstance();
		// delete prevoius key of userHomeFeedStaticCuratedBucket
		$predisLibrary->deleteKey(array('userHomeFeedStaticCuratedBucket'));
		// create new key userHomeFeedStaticCuratedBucket with new ordered data
		$predisLibrary->rightPushInList('userHomeFeedStaticCuratedBucket', $threadDataForStorage);
		// store same new data in DB
		$this->load->model('common/personalizationmodel');
		$this->personalizationmodel->insertStaticThreadsIntoDB($threadDataForStorage);
		
		echo 1;
	}

	public function tagPendingActions(){

		$this->init();

		$data['entities'] = $this->tagcmsmodel->getPendingTagPostingActions();	

		$entityIds = array();
		$entityNames = array();
		foreach ($data['entities'] as $key=>$value) {
			if($value['entityType'] == "University"){
				$data['entities'][$key]['entityType'] = "National-University";
				$value['entityType'] = "National-University";
			}
			$entityIds[$value['entityType']][] = $value['entityId'];
			if(strtolower($value['action']) == "delete"){
				$deletedEntityId[$value['entityType']][] = $value['entityId'];
			}
		}

		$entityData = $this->tagcmsmodel->getEntityData($entityIds,$deletedEntityId);

		foreach ($entityData as $value) {
			foreach ($value as $row) {
				$entityNames[] = $row['name'];
			}
		}
		if(!empty($entityNames)){
			$tagsAlreadyExists = $this->tagcmsmodel->getTagsByName($entityNames);	
		}
		

		$tagsAlreadyExistsMapping = array();
		foreach ($tagsAlreadyExists as $value) {
			$tagsAlreadyExistsMapping[strtolower($value['tags'])] = $value['id'];
		}

		$shikshaEntityTagDataMapping = $this->tagcmsmodel->getShikshaEntityMappings($entityIds);

		$tagIdsArray = array();
		// _p($shikshaEntityTagDataMapping);
		foreach ($shikshaEntityTagDataMapping as $entity => $value) {
			foreach ($value as $entityId => $value1) {
				$tagIdsArray[] = $value1['tag_id'];
			}
		}
		

		if(!empty($tagIdsArray)){
			$tagsMapping = $this->tagcmsmodel->fetchMultipleTagsMappings($tagIdsArray);	
		}
		
		$data['entityData'] = $entityData;
		$data['tagsAlreadyExistsMapping'] = $tagsAlreadyExistsMapping;
		$data['shikshaEntityTagDataMapping'] = $shikshaEntityTagDataMapping;
		$data['validateuser'] = $this->userStatus;
		$data['userId'] = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$data['tagsMapping'] = $tagsMapping;
		$data['sessionId'] = sessionId();
		$this->load->view('tagPendingActions',$data);
	}


	public function fetchAllVarients(){

		$this->init();
		$tagId = $this->input->post('tagId');
		$this->tagcmsmodel->fetchAllVarients($tagId);

	}
	function discardPendingTagAction(){

		$this->init();
		$this->validateAccess();
		$rowId = $this->input->post("rowId");
		$this->tagcmsmodel->updatePendingTagPostingStatus('discard',$rowId);
		//$data['entities'] = $this->tagcmsmodel->discardPendingTagAction($rowId);

	}

	// Ajax Call to fetch the mappings with HTML
	public function getMappings($tagId=0,$isAjaxCall=true){
		$this->init();
		$mappings = $this->tagcmsmodel->fetchTagsMappings($tagId);
		$displayData['mappings'] = $mappings;

		for ($i=1; $i <= $this->mappingsCount; $i++) { 
			$selectedData['selectedEntity_'.$i] = "";
			$selectedData['selectedEntityId_'.$i] = "";	
		}

		$otherMappings = array();
		$counter = 1;
		$otherCounter = 0;

		$newMappingEntity = $this->input->post('newMappingEntity');
		$newMappingEntityId = $this->input->post('newMappingEntityId');
		if($newMappingEntity != ""){
			$selectedData['selectedEntity_1'] = $newMappingEntity;
			$selectedData['selectedEntityId_1'] = $newMappingEntityId;
			$counter++;
		}

		foreach ($mappings as $key => $value) {
			if(array_key_exists($value['entity_type'], $this->shikshaEntityToTagsEntity)){
				$selectedData['selectedEntity_'.$counter] = $value['entity_type'];
				$selectedData['selectedEntityId_'.$counter] = $value['entity_id'];
				$counter++;
			}else{
				$otherMappings[$otherCounter]['entity'] = $value['entity_type'];
				$otherMappings[$otherCounter]['entityId'] = $value['entity_id'];
				$otherCounter++;
			}
		}
		if($tagId == 0){
			$selectedData['selectedEntity_1'] = $this->input->post('selectedEntity');
			$selectedData['selectedEntityId_1'] = $this->input->post('selectedEntityId');
		}
		$displayData['shikshaEntityToTagsEntity'] = $this->shikshaEntityToTagsEntity;
		$displayData['selectedData'] = $selectedData;
		$displayData['otherMappings'] = $otherMappings;
		$displayData['totalShikshaEntites'] = $this->totalShikshaEntites;
		$displayData['heading'] = "Add/Edit Mappings";
		if($isAjaxCall){
			$this->load->view('Tagging/addEditTagsMapping',$displayData);	
		}else{
			return $displayData;
		}		
	}

	function removeMappingByInfo(){
		$this->init();
		$entity = $this->input->post('entity');
		$entityId = $this->input->post('entityId');
		$tagId = $this->input->post('tagId');
		$this->tagcmsmodel->updateSingleMapping($entity,$entityId,$tagId,'deleted');

		$pendingMappingTableId = $this->input->post('pendingMappingTableId');
		if($pendingMappingTableId > 0){
			$this->tagcmsmodel->updatePendingTagPostingStatus('done',$pendingMappingTableId);
		}
	}

	function updateAllMappingsForTag($action = "edit"){
		$this->init();
		$tagId = $this->input->post('tagId');
		$shikshaEntity = json_decode($this->input->post('shikshaEntity'));
		$shikshaEntityId = json_decode($this->input->post('shikshaEntityId'));
		$pendingMappingTableId = $this->input->post('pendingMappingTableId');
		if($pendingMappingTableId > 0){
			$this->tagcmsmodel->updatePendingTagPostingStatus('done',$pendingMappingTableId);
		}
		$this->tagcmsmodel->updateAllMappingsForTag($tagId,$shikshaEntity,$shikshaEntityId,$this->totalShikshaEntites,$action);
	}

	function checkEntityMappingExistence(){
		$this->init();
		$entity = $this->input->post('shikshaEntity');
		$entityId = $this->input->post('entityId');
		$currentTag = trim($this->input->post('currentTag'));
		$mappings = $this->tagcmsmodel->fetchMappingsForEntity($entity, $entityId);
		$mappingUpdated = array();
		foreach ($mappings as $key => $value) {
			$temp = array();
			$temp['tagId'] = $value['tag_id'];
			$temp['entityId'] = $value['entity_id'];
			$temp['entityType'] = $value['entity_type'];
			if($currentTag != $temp['tagId']){
				$mappingUpdated[] = $temp;
			}
		}
		if(!empty($mappingUpdated)){
			echo json_encode($mappingUpdated);
		}else{
			echo true;
		}
	}


}
?>

