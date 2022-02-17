<?php

class GlobalShiksha extends MX_Controller {
	function getHeaderSearch($searchWithHeader = false) {
			$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");
			$config = array('courseInstituteSearch', 'careers', 'question', 'exams');
			
			$data['autosuggestorConfigArray'] = $this->AutoSuggestorInitLib->createAutoSuggestorConfigArray($config);
			//_p($data['autosuggestorConfigArray']); die;
			$data['searchWithHeader'] 	= $searchWithHeader;
			if(!$searchWithHeader) {
				$data['homepageCoverBannerData'] = $this->_getHomePageCoverBanner();
			}
			$data['jsFilePlugins'] 		= array('searchV2');
			// $data['cssFilePlugins'] 	= array('chosenSumo');
			$this->load->view('home/search/searchMenu', $data);
	}
	
	function getHeaderSearchConfig($autosugestorType = array('courseInstituteSearch', 'careers', 'question', 'exams'), $isMobile = false) {
			$this->AutoSuggestorInitLib = $this->load->library("search/Autosuggestor/AutoSuggestorInitLib");
			$config = $autosugestorType;
			return $this->AutoSuggestorInitLib->createAutoSuggestorConfigArray($config,$isMobile);
	}

	private function _getHomePageCoverBanner() {
		$this->load->library('homepage/Homepageslider_client');
		$HomepagesliderClient = Homepageslider_client::getInstance();
		return $HomepagesliderClient->getHomePageCmsData('banner');
	}
	
	/**
	 * Restore tag affinity for returning users on Desktop
	 */
	/* public function restoreDataForReturningUser(){
		$this->userStatus = $this->checkUserValidation();
		if(!(isset($this->userStatus[0]['userid']) && $this->userStatus[0]['userid'] > 0)){
			echo -1;
			exit(0);
		}
		$this->load->library("common/personalization/PersonalizationLibrary");
		$this->personalizationlibrary->setUserId($this->userStatus[0]['userid']);
		$this->personalizationlibrary->setVisitorId('');
		$backFillThreadFlag = FALSE;
		global $isWebAPICall;
		if($isWebAPICall == 1){
			$backFillThreadFlag = TRUE;
		}
		$this->personalizationlibrary->restorePersonalizationDataForReturningUser($backFillThreadFlag);
		echo "TRUE";
		exit(0);
	} */

	function getGNBConfig(){
		$this->load->config('common/newGNBconfig');
	}

	public function insertIntoAmpRabbitMQueue($pageId,$pageIdArr, $pageType = 'exampage')
    {
        if(empty($pageId))
            return;
        try {
        	// insert into html cache purging queue
                if(in_array($pageType, array('article'))){

                        $arr = array("cache_type" => "htmlpage", "entity_type" => $pageType, "entity_id" => $pageId, "cache_key_identifier" => "");
                        $shikshamodel = $this->load->model("common/shikshamodel");
                        $shikshamodel->insertCachePurgingQueue($arr);
                }
                $this->config->load('amqp');
                $this->load->library("common/jobserver/JobManagerFactory");
                $jobManager = JobManagerFactory::getClientInstance();

                $rabbitQueue['logType'] = $pageType;
                $rabbitQueue['pageId'] = $pageId;
                $jobManager->addBackgroundJob("convertToAmp", $rabbitQueue);
                foreach ($pageIdArr as $key => $tempId) {
                    if(!empty($tempId))
                    {
                        $rabbitQueue['logType'] = $pageType;
                        $rabbitQueue['pageId'] = $tempId;
                        $jobManager->addBackgroundJob("convertToAmp", $rabbitQueue);    
                    }
                }
            }
            catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
            }
    }
}
