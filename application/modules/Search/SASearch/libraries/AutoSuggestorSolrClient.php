<?php

class AutoSuggestorSolrClient {
    function __construct()
    {
        $this->CI = & get_instance();
        $this->curlLib = $this->CI->load->library('common/CustomCurl');
        $this->CI->load->library('SASearch/AutoSuggestorSolrRequestGenerator');
        $this->autoSuggestorSolrRequestGenerator = new AutoSuggestorSolrRequestGenerator;
		$this->debugging = $this->CI->security->xss_clean($this->CI->input->get("enableDebugging"));
		$this->enableFilterDebugging = $this->CI->security->xss_clean($this->CI->input->get("enableFilterDebugging"));
    }


    public function getFiltersAndInstitutes(&$solrRequestData,$searchType) {
		
        $solrUrl = $this->autoSuggestorSolrRequestGenerator->generateUrlOnSearch($solrRequestData,$searchType);
        $urlComp = explode('?', $solrUrl);
		$this->curlLib->setIsRequestToSolr(1);
		// if debugging is enabled, we need to log solr execution data, both in case of  filter update call & page load
		if($this->debugging ==1 || ($this->enableFilterDebugging ==1 && $solrRequestData['filterUpdateCallFlag']==1))
		{
			$this->CI->benchmark->mark('start');
			$mem1 = memory_get_usage();
		}
		$searchLoggingObject=  StudyAbroadLoggingLib::getLoggerInstance('solrQuerySearch', $solrRequestData['keyword']);
		// curl call
		$customCurlObject = $this->curlLib->curl($urlComp[0], $urlComp[1]);
		$solrContent = unserialize($customCurlObject->getResult());
		// send solr outage mail
		if(!$solrContent)
		{
			if(ENVIRONMENT == "production") // send mails only from live environment
			{
				$this->_sendMailForSOLRError($solrRequestData,$customCurlObject);
			}
            return -1;
		}
		if($searchLoggingObject instanceof StudyAbroadLoggingLib){
        $searchLoggingObject->completeLogging($solrContent,$solrUrl);
        }
		if($this->debugging ==1 || ($this->enableFilterDebugging ==1 && $solrRequestData['filterUpdateCallFlag']==1))
		{
			$this->CI->benchmark->mark("end");
			$timeTaken = $this->CI->benchmark->elapsed_time('start', 'end');
			$mem2 = memory_get_usage();
			$this->_logSolrExecutionData(array(
															  'component'=>($solrRequestData['filterUpdateCallFlag']==1?'solrFilterPreparation':'solrQuerySearch'),
															  'searchTrackingSAId'=>(int)$this->CI->security->xss_clean($this->CI->input->get('tid')),
															  'keyword'=> $solrRequestData['keyword'],
															  'memoryUsed'=>($mem2-$mem1),
															  'timeTaken'=>$timeTaken,
															  'url'=>$solrUrl,
															  'response'=>json_encode($solrContent)
															  ));
		}
		return $solrContent;
    }
	
	private function _sendMailForSOLRError($solrRequestData, $customCurlObject, $pageType = 'Search'){
        $mailContent=$this->_getMailContentForSOLRError($solrRequestData, $customCurlObject);
        $mailData['sender'] = SA_ADMIN_EMAIL; 
        $mailData['subject'] = $pageType.' : SOLR Error';
        $mailData['mailContent'] = $mailContent;
        $mailData['recipients'] = array(
                       'to' => array('satech@shiksha.com')
                        );
        sendMails($mailData);
    }

    private function _getMailContentForSOLRError($solrRequestData,$customCurlObject){
        $errorMailContent="<p>
			Hi&nbsp;</p>
		<p><u><strong>Solr curl failure&nbsp;</strong></u></p>
		<p><strong>Curl Error Code : ".$customCurlObject->getCurlErrorCode()."</strong> :- 201</p>
		<p><strong>Curl Error Message</strong> :-".$customCurlObject->getCurlErrorMessage()."</p>
		<p><strong>Date Time Reported</strong> :-".date('l jS \of F Y h:i:s A')."</p>
		<p><strong>Time Taken :-".(($customCurlObject->getCurlEndTime()-$customCurlObject->getCurlStartTime())*1000)."</p>
		<p><strong>Solr Request Data </strong>:-".print_r($solrRequestData,true)."</p>
		<p>Regards</p>
		<p>SA Team&nbsp;</p>
		<p>&nbsp;</p>";
		//error_log($errorMailContent);
        return $errorMailContent;
    }
	
	private function _logSolrExecutionData($insertData)
	{
		// add to db
                
		$saSearchModel = $this->CI->load->model('SASearch/sasearchmodel');
                $totalLogData=array();
                $qerExecutionData=$saSearchModel->getQERExecutionData($insertData['searchTrackingSAId']);
            
                if(count($qerExecutionData)>0){
                    $memoryUsedInQer=$qerExecutionData[0]['memoryUsed'];
                    $qerExecutionTime=$qerExecutionData[0]['timeTaken'];
                    $totalMemoryConsumed=$memoryUsedInQer+$insertData['memoryUsed'];
                    $totalExecutionTimeConsumed=$qerExecutionTime+$insertData['timeTaken'];
                    $totalLogData=array(
				    'component'=>'totalTimeTaken',
				    'searchTrackingSAId'=>$insertData['searchTrackingSAId'],
				    'keyword'=> $insertData['keyword'],
				    'memoryUsed'=>($totalMemoryConsumed),
				    'timeTaken'=>$totalExecutionTimeConsumed,
				    'url'=>$insertData['url'],
				    'response'=>$insertData['response']
							);
                }
                if(count($totalLogData)>0){
                    $totalRowTobeInserted=array($insertData,$totalLogData);
                }else{
                    $totalRowTobeInserted=$insertData;
                }
                $saSearchModel->trackSearchExecutionData($totalRowTobeInserted);
	}
	/*
	 * get abroad suggestions for  search autosuggestor
	 */
	public function getUnivSuggestionsFromSolr($solrRequestData){
        if(empty($solrRequestData['text']) || empty($solrRequestData['eachfacetResultCount'])) {
            return;
        }
        $solrUnivUrl = $this->autoSuggestorSolrRequestGenerator->generateUnivAutoSuggestionUrl($solrRequestData);
		$this->curlLib->setIsRequestToSolr(1); // enable lib for solr
		$solrContent = unserialize($this->curlLib->curl($solrUnivUrl)->getResult());
		
		//echo "<br><br>RES";_p($solrContent);die;
		$solrResult = ($solrContent['response']['numFound']>0?$solrContent['response']['docs']:array());
        return $solrResult;
    }
	/*
	 * course and university autosuggestor
	 */
	public function getCourseAndUnivSuggestionsFromSolr($solrRequestData)
	{
        if(empty($solrRequestData['text']) || empty($solrRequestData['eachfacetResultCount'])) {
            return false;
        }
		$this->curlLib->setIsRequestToSolr(1); // enable lib for solr
		$solrResult = array();
		// univ
        $solrUnivUrl = $this->autoSuggestorSolrRequestGenerator->generateUnivAutoSuggestionUrl($solrRequestData);
        $solrUnivContent = unserialize($this->curlLib->curl($solrUnivUrl)->getResult());
		$solrResult['univSuggestions'] = ($solrUnivContent['response']['numFound']>0?$solrUnivContent['response']['docs']:array());
        
		// course
        $solrCourseUrl = $this->autoSuggestorSolrRequestGenerator->generateCourseAutoSuggestionUrl($solrRequestData);
        $solrCourseContent = unserialize($this->curlLib->curl($solrCourseUrl)->getResult());
		$solrResult['courseSuggestions'] = array_map(function($a){return $a['doclist']['docs'][0];},$solrCourseContent['grouped']['saAutosuggestCourseFacet']['groups']);
        return $solrResult;
    }
    /*
	 * country city state suggestions
	 */
	public function getLocationSuggestionsFromSolr($solrRequestData)
	{
        if(empty($solrRequestData['text']) || empty($solrRequestData['eachfacetResultCount'])) {
            return false;
        }
		$this->curlLib->setIsRequestToSolr(1); // enable lib for solr
		$solrResult = array();
        $solrLocationUrl = $this->autoSuggestorSolrRequestGenerator->generateLocationAutoSuggestionUrl($solrRequestData);
        $solrLocationContent = unserialize($this->curlLib->curl($solrLocationUrl)->getResult());
		$locationSuggestionGroups = array_map(function($a){return array('numFound'=>$a['doclist']['numFound'],'docs'=>$a['doclist']['docs'][0]);},$solrLocationContent['grouped']['saAutosuggestLocationFacet']['groups']);
		// sort by num found due to the requirement that a group having more results[document matched] show come on top
		usort($locationSuggestionGroups,function($a, $b){ if($a['numFound']>$b['numFound']) return -1; else return 1;});
		$solrResult = (count($locationSuggestionGroups)>0?array_map(function($a){return $a['docs'];},$locationSuggestionGroups):array());
		$solrResult = array_slice($solrResult, 0,$solrRequestData['eachfacetResultCount']);
        return $solrResult;
    }
	/*
	 * get suggestion for exam
	 */
	public function getExamSuggestionsFromSolr($solrRequestData)
	{
		if(empty($solrRequestData['text']) || empty($solrRequestData['eachfacetResultCount'])) {
            return false;
        }
		$this->curlLib->setIsRequestToSolr(1); // enable lib for solr
		$solrResult = array();
        $solrExamUrl = $this->autoSuggestorSolrRequestGenerator->generateExamAutoSuggestionUrl($solrRequestData);
        $solrExamContent = unserialize($this->curlLib->curl($solrExamUrl)->getResult());
		//_p($solrExamContent);die; // see numfound here
		$examSuggestionGroups = array_map(function($a){return array('numFound'=>$a['doclist']['numFound'],'docs'=>$a['doclist']['docs'][0]);},$solrExamContent['grouped']['saAutosuggestExamName']['groups']);
		// sort by num found due to the requirement that a group having more results[document matched] show come on top
		usort($examSuggestionGroups,function($a, $b){ if($a['numFound']>$b['numFound']) return -1; else return 1;});
		$solrResult = (count($examSuggestionGroups)>0?array_map(function($a){return $a['docs'];},$examSuggestionGroups):array());
		$solrResult = $this->_cleanseNoPageExams($solrResult);
        return $solrResult;
	}

	public function getSelectedUniversityDetails($universityId){
		if(empty($universityId)){
			return false;
		}
		$univDetailUrl = $this->autoSuggestorSolrRequestGenerator->getSelectedUniversityDetailsUrl($universityId);
		$this->curlLib->setIsRequestToSolr(1);
		$data = unserialize($this->curlLib->curl($univDetailUrl)->getResult());
		$data = $data['response'];
		$result = array();
		foreach($data['docs'] as $row){
			$result['countryId'] = $row['saUnivCountryId'];
			$result['countryName'] = $row['saUnivCountryName'];
			$result['cityId'] = $row['saUnivCityId'];
			$result['cityName'] = $row['saUnivCityName'];
			$result['url'] = $row['saUnivSeoUrl'];
			if(in_array($row['saCourseLevel1'],array("Bachelors","Bachelors Diploma","Bachelors Certificate")))
			{
				$row['saCourseLevel1'] = "Bachelors";
			}
			else if(in_array($row['saCourseLevel1'],array("Masters","Masters Diploma","Masters Certificate")))
			{
				$row['saCourseLevel1'] = "Masters";
			}
			$result['levelStreamData'][$row['saCourseLevel1']][$row['saCourseParentCategoryId']] = $row['saCourseParentCategoryName'];
		}
		ksort($result['levelStreamData']);
		$result['uniqueStreams'] = array();
		foreach($result['levelStreamData'] as $level=>$streams)
		{
			foreach($streams as $categoryId=>$categoryName)
			$result['uniqueStreams'][$categoryId] =$categoryName;
		}
		$result['uniqueStreams'] = array_flip($result['uniqueStreams']);
		ksort($result['uniqueStreams'],SORT_STRING);
		return $result;
	}

	public function getSelectedCourseDetails($courseData,$locationData=array()){
		if(empty($courseData)){
			return false;
		}
		$courseDetailUrl = $this->autoSuggestorSolrRequestGenerator->getSelectedCourseDetailsUrl($courseData,$locationData);
		$this->curlLib->setIsRequestToSolr(1);
		$data = unserialize($this->curlLib->curl($courseDetailUrl)->getResult());
		$data = $this->_parseSelectedCourseDetailsSolrResponse($data);
		$data['fees']['min'] =($data['fees']['min'] >0?$data['fees']['min'] :0)/100000;
		$data['fees']['max']=($data['fees']['max']>0?$data['fees']['max']:0)/100000;
		$abroadCommonLib = $this->CI->load->library("listingPosting/AbroadCommonLib");
		$examMaster = $abroadCommonLib->getAbroadExamsMasterList();
		foreach($examMaster as $examData)
		{
			//if($data['exams'][$examData['examId']] =
			if(in_array($examData['examId'],array_keys($data['exams'])))
			{
				if(in_array($examData['examId'],array(7,8,9)))
				{
					unset($data['exams'][$examData['examId']]);
					continue;
				}
				$data['exams'][$examData['examId']]['step'] = $examData['range'];
				$data['exams'][$examData['examId']]['minVal'] = $examData['minScore'];
				$data['exams'][$examData['examId']]['maxVal'] = $examData['maxScore'];
				// first check if max is N/A i.e. -1
				if($data['exams'][$examData['examId']]['scores']['max'] == -1)
				{	// remove it
					$data['exams'][$examData['examId']]['scores']['max'] = "";
					$data['exams'][$examData['examId']]['scores']['min'] = "";
				}
				else if($data['exams'][$examData['examId']]['scores']['min'] == -1)
				{
					$data['exams'][$examData['examId']]['scores']['min'] = $data['exams'][$examData['examId']]['scores']['max'];
				}
			}
		}
		return $data;
	}

	private function _parseSelectedCourseDetailsSolrResponse($data){
		if(empty($data)){
			return array();
		}
		$facets = $data['facet_counts']['facet_fields'];
		$fees = array_keys($facets['saCourseFees']);
		$fees = array('min'=>min($fees),'max'=>max($fees));
		$exams = array();
		foreach($facets['saCourseEligibilityExamsIdMap'] as $key=>$count){
				$exam = explode(':', $key);
				$exams[$exam[1]]['name'] = $exam[0];
				$facetKeys = array_keys($facets['sa'.$exam[0].'StrExamScore']);
				$examScores = array_filter(array_map(function($a){return (float)$a;},$facetKeys),function($ele){ if($ele === "-1.0"||$ele == -1) return false; return true;});

				if(empty($examScores)){
						if(isset($facets['sa'.$exam[0].'StrExamScore']['-1.0']) && $facets['sa'.$exam[0].'StrExamScore']['-1.0'] >0){
							$exams[$exam[1]]['scores'] = array('min'=>'N/A','max'=>'N/A');
						}else{
								unset($exams[$exam[1]]);
						}

				}else{
						$exams[$exam[1]]['scores'] = array('min'=>min(array_values($examScores)),'max'=>max(array_values($examScores)));
				}
		}
		return array('fees'=>$fees, 'exams'=>$exams);
	}

	private function _cleanseNoPageExams($data){
		$ci = &get_instance();
		$model =$ci->load->model('SASearch/sasearchmodel');
		$pageExamIds = $model->getExamIdsWithPages();
		foreach($data as $key=>$value){
			$id = explode(":", $value['saAutosuggestExamNameIdMap']);
			if(!in_array($id[1], $pageExamIds)){
				unset($data[$key]);
			}
		}
		return $data;
	}
	/*
	 * function to get category page data from solr
	 * Note : works for -
	 * 	abroad category page,
	 * 	abroad scholarship category page			
	 */
	public function getCategoryPageResults($solrRequestUrl, $pageType)
	{
		$this->curlLib->setIsRequestToSolr(1);
        $urlComp = explode('?', $solrRequestUrl);
		// curl call
		$customCurlObject = $this->curlLib->curl($urlComp[0], $urlComp[1]);
		$solrContent = unserialize($customCurlObject->getResult());
		if(!$solrContent)
		{
			if(ENVIRONMENT == "production") // send mails only from live environment
			{
				$this->_sendMailForSOLRError($solrRequestUrl,$customCurlObject, $pageType);
			}
            return -1;
		}
		return $solrContent;
	}
	/*
	 * get university courses for shipment autosuggestor
	 */
	public function getUnivCourseListFromSolr($solrRequestData){
        if(empty($solrRequestData['universityId'])) {
            return;
        }
        $solrUnivUrl = $this->autoSuggestorSolrRequestGenerator->generateUnivCourseListUrl($solrRequestData);
		$this->curlLib->setIsRequestToSolr(1); // enable lib for solr
		$solrContent = unserialize($this->curlLib->curl($solrUnivUrl)->getResult());
		$solrResult = ($solrContent['response']['numFound']>0?$solrContent['response']['docs']:array());
        return $solrResult;
    }

    public function getUnivGroupedCourseListFromSolr($solrRequestData){
	    if(empty($solrRequestData['countryId'])){
	        return;
        }
        $solrUnivGroupUrl = $this->autoSuggestorSolrRequestGenerator->generateUnivGroupedCourseListUrl($solrRequestData);
        $this->curlLib->setIsRequestToSolr(1); // enable lib for solr
        $solrContent = unserialize($this->curlLib->curl($solrUnivGroupUrl)->getResult());
        $solrResult = ($solrContent['grouped']['saUnivId']['matches']>0?$solrContent['grouped']['saUnivId']:array());
        return $solrResult;
	}
}
