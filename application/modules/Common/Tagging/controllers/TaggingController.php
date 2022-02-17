<?php

class TaggingController extends MX_Controller
{

	private $questionIds;

    function init(){

		$this->load->model('taggingmodel');
		$this->tagmodel = new TaggingModel();			

		$this->load->library('TaggingLib'); 
		$this->TaggingLib = new TaggingLib();			
	}

	public function showSuggestions(){
		$this->init();
		// $question = "I want to do MBA from delhi. I have done MBA from ghaziabad";
		$question = $this->input->post('sentence');
		$data[] = $question;
		$suggestions = $this->TaggingLib->showTagSuggestions($data);
		$objectiveArray = $suggestions['objective'];
		$backgroundArray = $suggestions['background'];
		$objectiveFinal = $this->tagmodel->findData($objectiveArray);
		$backgroundFinal =$this->tagmodel->findData($backgroundArray);
		_p("<b>Objective Tags</b>");
		_p($objectiveFinal);
		_p("<b>Background Tags</b>");
		_p($backgroundFinal);
	}


	public function attachTagsWithContent($tags=array(),$type='question'){
		$this->init();
		$tags = $this->showSuggestions();

		$finalTags = $this->TaggingLib->attachTagsWithParent($tags);

		$this->TaggingLib->insertTagsWithContentToDB($finalTags,1234,'question');


			
	}

	private function _download_page($path)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$path);
	    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	    $retValue = curl_exec($ch);             
	    curl_close($ch);
	    	    
	    return $retValue;
	}

	public function sendQerRequest(){

		$this->config->load('search_config');
		$qerUrl = $this->config->item('qer_url_new');
		$question  = utf8_encode(trim($this->input->post('data')));
		$url = $qerUrl."?inkeyword=".urlencode($question)."&action=Submit&output=xmlcand";

		$sXML = $this->_download_page($url);

		if(trim($sXML) == ""){

			// fail case
		    $sXML = $this->_download_page($url);
		    if(trim($sXML) == ""){
		    	echo " \n";
		    	echo " \n";
		    	error_log($url);
		    	error_log($question);
		    	return;
		    }
		}

		$oXML = new SimpleXMLElement($sXML);

		$objective = $oXML->query_objective_split;
		$background = $oXML->query_background_split;
		
		
		if(trim($objective) == ""){
			$objective = $background;
			$background = "";
		}

		echo $objective."\n";
		echo $background."\n";

	}

	public function taggingOneTimeScript(){

		$this->init();
		$this->load->config('TaggingConfig');
		$userInteractionCacheStorageLib = $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');

		// Start Processing Questions
		//$this->questionIds = $this->config->item('QUESTION_IDS');

		$qids = $this->tagmodel->fetchDBQuestions();
		$qids_new = array();
		foreach ($qids as $key => $value) {
			 $qids_new[] = $value['msgId'];
		}

		$counter = 1000;
		$loop_count = ceil(count($qids_new) / $counter);



		for ($i=0; $i < $loop_count; $i++) { 
			$start = ($i*$counter);
			$this->questionIds = array();
			$this->questionIds = array_slice($qids_new, $start,$counter);
			$result = $this->tagmodel->fetctTextForMessage($this->questionIds,'question');

			//$result = array();
			$formattedData = array();
			$formattedUserData = array();
			foreach ($result as $key => $value) {
				$formattedData[$value['threadId']] = utf8_encode(html_entity_decode($value['msgTxt']));
				$formattedUserData[$value['threadId']] = $value['userId'];
			}				
			$suggestions = $this->TaggingLib->showTagSuggestions($formattedData);
			//$suggestions = array();
			unset($formattedData);
			unset($result);
				foreach ($suggestions as $threadId => $tagsArray) {
					
					error_log("********* Processing Question*******".$threadId);
					$finalTags = $this->TaggingLib->attachTagsWithParent($tagsArray);

					
					//$tagsArrayOneDForRedis = array();
					$tagsFormattedArrayForRedis = array();
					$tagsArrayForDB = array();

					$count = 0;

					foreach ($finalTags as $classification => $value) {

						$value = array_filter($value);
						foreach ($value as $tId) {
							//$tagsArrayOneDForRedis[] = $tId;
							if($classification == "objective" || $classification == "background" || $classification == "manual"){
								$tagsFormattedArrayForRedis['direct'][] = $tId;
							} else {
								$tagsFormattedArrayForRedis['parent'][] = $tId;
							}
							$tagsArrayForDB[$count]['classification'] = $classification;
							$tagsArrayForDB[$count]['tagId'] = $tId;
							$count++;
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
					
					if(!empty($tagsFormattedArrayForRedis['parent']) || !empty($tagsFormattedArrayForRedis['direct'])){
						
						// Insert into DB
						$this->tagmodel->insertTagsWithContent($tagsArrayForDB,$threadId,'question');

						// Make the redis call
						$userInteractionCacheStorageLib->updateTagOfThreads($formattedUserData[$threadId],$threadId,'question',$tagsFormattedArrayForRedis,'updatetag');

						// Make the SOLR call	
						modules::run('search/Indexer/addToQueue', $threadId,'question');
					}
			}
			
			unset($this->questionIds);
			unset($result);
			unset($formattedData);
			unset($formattedUserData);
			unset($suggestios);	

		}

		
		die;
		// Start Processing Discussions
		$this->questionIds = $this->config->item('DISCUSSION_IDS');
		

		$result = $this->tagmodel->fetctTextForMessage($this->questionIds,'discussion');
		
		$formattedData = array();
		foreach ($result as $key => $value) {
			$formattedData[$value['threadId']] = utf8_encode(html_entity_decode($value['msgTxt']));
			$formattedUserData[$value['threadId']] = $value['userId'];
		}				
		$suggestions = $this->TaggingLib->showTagSuggestions($formattedData);
		

		$count = 0;		
		foreach ($suggestions as $threadId => $tagsArray) {
				
				error_log("********* Processing discussion *******".$threadId);

				$finalTags = $this->TaggingLib->attachTagsWithParent($tagsArray);
				//$tagsArrayOneDForRedis = array();
				$tagsFormattedArrayForRedis = array();
				$tagsArrayForDB = array();
				$count = 0;
				foreach ($finalTags as $classification => $value) {
					$value = array_filter($value);
					foreach ($value as $tId) {
						//$tagsArrayOneDForRedis[] = $tId;
						if($classification == "objective" || $classification == "background" || $classification == "manual"){
							$tagsFormattedArrayForRedis['direct'][] = $tId;
						} else {
							$tagsFormattedArrayForRedis['parent'][] = $tId;
						}
						$tagsArrayForDB[$count]['classification'] = $classification;
						$tagsArrayForDB[$count]['tagId'] = $tId;
						$count++;
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
				
				if(!empty($tagsFormattedArrayForRedis['parent']) || !empty($tagsFormattedArrayForRedis['direct'])){

					// Insert into DB
					$this->tagmodel->insertTagsWithContent($tagsArrayForDB,$threadId,'discussion');

					// Make the redis call
					$userInteractionCacheStorageLib->updateTagOfThreads($formattedUserData[$threadId],$threadId,'discussion',$tagsFormattedArrayForRedis,'updatetag');

					// Make the SOLR call	
					modules::run('search/Indexer/addToQueue', $threadId,'discussion');
				}
		}

	}
	
	public function updateTagsOnThreadOneTimeScript(){
		$taggingModel = $this->load->model('Tagging/taggingmodel');
		$predisLibrary = PredisLibrary::getInstance();//$this->load->library('common/PredisLibrary');
		$offset = 0;
		while(TRUE){
			$result = $taggingModel->getThreadsFromTagContentMapping($offset);
			foreach ($result['resultData'] as $key => $data){
				$predisLibrary->addMembersToSortedSet('threadTags:thread:'.$key, $data, TRUE);
			}
			//_p($result['foundRows']);
			if($result['foundRows'] >= $offset){
				$offset += 5000;
			}else{
				break;
			}
		}
		echo 'SUCCESS';
		exit(0);
	}

	public function sanitizeMessageForTagging(){
		$message = isset($_POST['message'])?$this->input->post('message'):'';
		$this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();  
        $this->anamodel = $this->load->model('messageBoard/AnAModel');
        $keyWord_Data = $this->anamodel->getAutoModerationKeywordData();
        $updatedMessage = $this->anaCommonLib->autoModerationKeywordReplace($message,$keyWord_Data);
     
        echo $updatedMessage;
	}

 public function checkTags(){
    	$this->load->view('checkTags');
    }

function tagsSuggestions($tags){

	if(empty($tags)){
		die("Please provide valid input.");
	}

	$tagIds = explode(",", $tags);
    $this->taggingmodel = $this->load->model('Tagging/taggingmodel');

	$similarTags = $this->taggingmodel->getSimilarTags($tagIds);

	$idarr = array();
	foreach ($tagIds as $key => $value) {
		$idarr[] = array("id" => $value);
	}
	$tagDetails = $this->taggingmodel->findTagDetails($idarr);
	$tagEntities = $this->taggingmodel->getTagDetails($tagIds);

	$displayData['similarTags'] = $similarTags;
	$displayData['tagIds'] = $tagIds;
	$displayData['tagDetails'] = $tagDetails;
	$displayData['tagEntities'] = $tagEntities;
	$displayData['tags'] = $tags;

	$this->load->view("similarTagsTable", $displayData);
		// _p($tagIds);_p($similarTags);die;


}

}
?>
