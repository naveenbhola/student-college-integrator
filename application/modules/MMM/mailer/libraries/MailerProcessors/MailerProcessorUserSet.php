<?php

require_once('AbstractMailerProcessor.php');

class MailerProcessorUserSet extends AbstractMailerProcessor
{
	private $mailerCriteriaEvaluatorService;
	private $productMailerEventTriggers;

	function __construct(Mailer $mailer,TemplateBuilder $templateBuilder,MailerCriteriaEvaluatorService $mailerCriteriaEvaluatorService,ProductMailerEventTriggers $productMailerEventTriggers)
	{
		parent::__construct($mailer,$templateBuilder);
		
		if($this->templateBuilder) {
			$this->templateBuilder->setMailer($mailer);
		}
		
		$this->productMailerEventTriggers = $productMailerEventTriggers;
		$this->mailerCriteriaEvaluatorService = $mailerCriteriaEvaluatorService;
	}

	function process($processingParams = array())
	{ 

		$this->CI->load->model('mailer/mailermodel');
		$mailerModel = new mailermodel;

		$this->CI->load->config('mailer/mailerConfig');
		$mptHtmlHashkey = $this->CI->config->item('mptHtmlHashkey');	

        $file = '/tmp/mailer_process_log'.date('Y-m-d').".txt";
        $fp = fopen($file,'a');

        // $file2 = '/tmp/mailer_memory_log_'.date('Y-m-d').".txt";
        // $fp2 = fopen($file2,'a');

		// fwrite($fp2,"memory_usage: step 1 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
		$parentMailerId = $this->mailer->getParentMailerId();
		$mailerId = $this->mailer->getId();

		$processingParams['templateId'] = $this->mailer->getTemplate()->getTemplateId();
		if($processingParams['isMEA'] == 'yes'){
			$userIds = $processingParams['boundrySet'];
		}else{
			if(empty($parentMailerId)){
				$userIds = $this->_getUserList();
			}else{
				$indexData = $mailerModel->getScheduleTimeForMailer(array($parentMailerId));
				if(empty($indexData)){
					fwrite($fp,"can't get parentMailer data for Drip Mailer $mailerId\n");
					fclose($fp);
					mail('teamldb@shiksha.com', 'parentMailer data not found', "can't get parentMailer data for Drip Mailer $mailerId\n");
					return false;
				}
				$month = $indexData[0]['month'];
				if($month<10){
					$month = '0'.$month;
				}
				$year = $indexData[0]['year'];
				$index = MMM_INDEX_NAME.'_'.$month.'_'.$year;

				$this->CI->load->config('mailer/mailerConfig');
        		$dripTypeMapping = $this->CI->config->item('dripCampaignMapping');
        		$dripType = $this->mailer->getDripMailerType();
        		$isClick = $dripTypeMapping[$dripType]['isClick'];
        		$isOpen = $dripTypeMapping[$dripType]['isOpen'];


				$userIds = $this->getDripMailerUserSet($parentMailerId,$index,$isClick,$isOpen);
				$numberOfUsers = count($userIds);

				if($numberOfUsers<1){
					fwrite($fp,"number of userIds for Drip Mailer $mailerId".$numberOfUsers."\n");
					fclose($fp);
					mail('teamldb@shiksha.com', 'no userIds found on ES', "no userIds found on ES for drip Mailer $mailerId");
					return false;
				}
				$mailerModel->updateNumberOfDripUserSet($numberOfUsers,$mailerId);
			}

		}
		
		// fwrite($fp2,"memory_usage: step 2 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

		if(!$this->mailer->isProductMailer()) {
			$x_id = $this->mailer->getId();
			fwrite($fp,"$x_id userids ".count($userIds)."\n");
			fclose($fp);
			
			if(count($userIds) == 0) {
				return false;
			}
		}

		$newsletterArticleList = ''; $includeMPTTupple = 'no';
		/**
		 * Tuuncate user set to the number to be sent for this mailer
		 */
		$userIds = $this->truncateUserSet($userIds);
		if(empty($parentMailerId)){
			$mailerModel->updateNumberOfDripUserSet(count($userIds),$mailerId);
		}
		// fwrite($fp2,"memory_usage: step 3 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

		/**
		 * Truncate already processed users, if any
		 */
		if(empty($parentMailerId)){
			$userIds = $this->truncateProcessedUsers($userIds);
		}
		else{
			$userIds = $this->truncateProcessedUsersForDrip($userIds,$mailerModel,$mailerId);
		}

		// fwrite($fp2,"memory_usage: step 4 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

		$userIds = $this->chunkifyUserSet($userIds);
		// fwrite($fp2,"memory_usage: step 5 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
		
		foreach($userIds as $k => $chunk) {
			/**
			 * Insert users in mail queue and get user id - mail id map
			 */
			$mailerType = "mmm";
			if($this->mailer->isProductMailer()){
				$mailerType = "product";
			}

			$userDetails = $mailerModel->getUsersDataByUserIds($chunk);
			if(count($userDetails)<1){
				continue;
			}

			$userIdMailIdMap = $mailerModel->addMailToQueue($userDetails, $this->mailer->getId(),$mailerType,$this->mailer->getSenderMail(), $this->mailer->getSenderName());
			// fwrite($fp2,"memory_usage: step 6 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			$customData = array();
			foreach($chunk as $key => $userId) {
				if(!empty($userIdMailIdMap[$userId]['mailid'])){
					$customData[$userId]['mailid'] = $userIdMailIdMap[$userId]['mailid'];
					$customData[$userId]['email'] = $userIdMailIdMap[$userId]['email'];
					$customData[$userId]['encodedMail'] = $userIdMailIdMap[$userId]['encodedMail'];
					$customData[$userId]['mailerId'] = $this->mailer->getId();
					unset($userIdMailIdMap[$userId]['email']);
					unset($userIdMailIdMap[$userId]['encodedMail']);
				}else{
					unset($chunk[$key]);
				}
			}
			$chunk = array_values($chunk);

			// fwrite($fp2,"memory_usage: step 7 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			/**
			 * Get template and widgets for the mailers
			 */
			$template = $this->mailer->getTemplate();

			// fwrite($fp2,"memory_usage: step 7-1 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
			$existingWidgetsData = array();
			if(!empty($newsletterArticleList)) {
				$existingWidgetsData['NewsletterArticleList'] = $newsletterArticleList;
			}
			$userData = $this->templateBuilder->buildTemplate($template,$chunk,$customData,$processingParams, true, $existingWidgetsData);
			
			if(!empty($userData['NewsletterArticleList'])) {
				$newsletterArticleList = $userData['NewsletterArticleList'];
				unset($userData['NewsletterArticleList']);
			}
			
			if(!empty($newsletterArticleList)) {
				if(strpos($newsletterArticleList, $mptHtmlHashkey) !== false) {
					$includeMPTTupple = 'yes';
					$params['mailerDetails']['mailer_id'] = 46;
					$params['mailerDetails']['mailer_name'] = 'MMMNewsletterArticles';
					try {
						$mptHTML = Modules::run('MPT/MPTController/getMPTHtmlForUsers',$chunk, $params);
					} catch(Exception $e) {
						mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com','Exception in getting HTML for MPT tupple (fn getMPTHtmlForUsers in file MailerProcessorUserSet.php) at '.date('Y-m-d H:i:s'), 'Exception: '.$e->getMessage().'<br/>Chunk:'.print_r($chunk, true));
					}
				}
			}
			
			// fwrite($fp2,"memory_usage: step 8 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			$cnt = 1;
			$mailContentData = array();

			$mailNotSent = array();
			$noMptTuppleUsers = array();
			foreach($userData as $userId => $userWidgetData) {

				if(!empty($newsletterArticleList)) {										
					if($includeMPTTupple == 'yes') {
						$userMPTHTML = '';
						if(!empty($mptHTML[$userId])) {
							$userMPTHTML = $mptHTML[$userId];
						} else {
							$noMptTuppleUsers[] = $userId;
						}
						$userWidgetData['NewsletterArticleList'] = str_replace($mptHtmlHashkey, $userMPTHTML, $newsletterArticleList);
						unset($userMPTHTML);
					} else {
						$userWidgetData['NewsletterArticleList'] = $newsletterArticleList;
					}
				}
				// fwrite($fp2,"memory_usage: step 9 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

				$mailContentData[$userId]["template"] = $template->buildTemplate($userWidgetData);

				// fwrite($fp2,"memory_usage: step 9-1 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

				$mailContentData[$userId]["subject"] = $template->replaceSubject($userWidgetData);

				// fwrite($fp2,"memory_usage: step 9-2 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

				$mailContentData[$userId]["mailid"] = $userIdMailIdMap[$userId]['mailid'];
				
				// fwrite($fp2,"memory_usage: step 10 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

				unset($userIdMailIdMap[$userId]['mailid']);
				unset($userWidgetData);

				if(empty($mailContentData[$userId])){
					$mailNotSent[$userId] = $mailContentData[$userId];
				}

				unset($customData[$userId]['email']);
				unset($customData[$userId]['encodedMail']);
				unset($customData[$userId]['mailerId']);
				unset($userId);

				// fwrite($fp2,"memory_usage: step 11 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

				/**
				 * Insert in mail queue content using bulk insert for a chunk
				 */
				if($cnt%5 == 0) {
					$mailerModel->addToMailQueue($mailContentData, $this->mailer->getId());

					// $file3 = '/tmp/mailerhandle/mailer_mailerhandle_log_'.$this->mailer->getId().'_'.date('Y-m-d-H:i:s').".txt";
			  		// $fp3 = fopen($file3,'a');
					// fwrite($fp3,"object: at ".date('Y-m-d H:i:s')." == ".print_r($this->mailerModel, true)."\n");
					// fclose($fp3);

					// fwrite($fp2,"memory_usage: step 12 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

					unset($mailContentData);
					// fwrite($fp2,"memory_usage: step 13 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
				}
				$cnt++;
			}

			// fwrite($fp2,"memory_usage: step 14 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			$cnt--;

			$leftUsers = array();
			$leftUsers = array_diff($chunk,array_keys($userData));
			if(!empty($leftUsers)) {
				foreach($leftUsers as $leftUserId) {
					$mailNotSent[$leftUserId] = $userIdMailIdMap[$leftUserId]['mailid'];
				}
			}
			// fwrite($fp2,"memory_usage: step 15 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			unset($chunk);
			unset($template);
			unset($userIdMailIdMap);
			unset($templateData);
			unset($mptHTML);
			// fwrite($fp2,"memory_usage: step 16 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			/**
			 * Insert leftovers to mail queue content
			 */
			$mailerModel->addToMailQueue($mailContentData, $this->mailer->getId());
			unset($mailContentData);
			// fwrite($fp2,"memory_usage: step 17 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			/*
			 * Update the numberprocessed in mailer table
			 */
			$mailerModel->updateNumberProcessed($this->mailer->getId(),$cnt);
			$mailerModel->updateNumberProcessedDripCampaign($this->mailer->getId(),$cnt);
			// fwrite($fp2,"memory_usage: step 18 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			/*
			 * Insert or Update userMailerSentCount table for product mailers
			 */
			if($this->mailer->isProductMailer()){
				$this->productMailerEventTriggers->updateMailerTriggers(array_keys($userData), $this->mailer->getId());
			}
			// fwrite($fp2,"memory_usage: step 19 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
			unset($userData);
			// fwrite($fp2,"memory_usage: step 20 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			/*
			 * Update mails in mailQueue table for users where no content is generated
			 */
			$mailsToBeMarkedAsNotSent = array();
			foreach($customData as $userId => $data) {
				if(!empty($mailNotSent[$userId])) {
					if(!empty($customData[$userId]['mailid'])){
						$mailsToBeMarkedAsNotSent[] = $customData[$userId]['mailid'];
					}
				}
			}
			// fwrite($fp2,"memory_usage: step 21 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			unset($mailNotSent);
			unset($data);
			unset($customData);

			// fwrite($fp2,"memory_usage: step 22 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");

			$mailerModel->markUsersInMailQueue($mailsToBeMarkedAsNotSent);
			unset($mailsToBeMarkedAsNotSent);

			if(!empty($noMptTuppleUsers)) {
				mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com','MPT Tupple not present for users at '.date('Y-m-d H:i:s'), 'Users: '.print_r($noMptTuppleUsers, true));
				unset($noMptTuppleUsers);
			}
			// fwrite($fp2,"memory_usage: step 23 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
		}
		
		// fwrite($fp2,"memory_usage: step 24 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
		unset($userIds);
		// fwrite($fp2,"memory_usage: step 25 at ".date('Y-m-d H:i:s')." == ".(memory_get_usage()/1024/1024)."\n");
		fclose($fp2);
		
		return true;
	}

	private function _getUserList()
	{
		return $this->mailerCriteriaEvaluatorService->evaluateCriteria($this->mailer->getCriteria(), false);
	}

	private function getDripMailerUserSet($parentMailerId,$index,$isClick,$isOpen){
		$ESConnectionLib = $this->CI->load->library('trackingMIS/elasticSearch/ESConnectionLib');
    	$this->clientConn5   = $ESConnectionLib->getESServerConnectionWithCredentials();

    	$mailSchedulerLib = $this->CI->load->library('mailer/MailSchedulerLibrary');
    	$routingKey = $mailSchedulerLib->getRoutingKeyForES($parentMailerId);

    	$scrollTime = "5m";

		$elasticQuery = $this->getESQueryForUserSet($index,$routingKey,$parentMailerId,$isOpen,$isClick,$scrollTime);

		$result = $this->clientConn5->search($elasticQuery);


        $userIds = array();
        $scrollId = $result['_scroll_id'];
        if($scrollId){
            while (isset($result['hits']['hits']) && count($result['hits']['hits']) > 0) { 
                $result = $result['hits']['hits'];
                foreach ($result as $key => $value) {
                    $userIds[$value['_source']['userid']] = $value['_source']['userid'];
                }
                $result = $this->clientConn5->scroll(
                    array(
                        "scroll_id" => $scrollId,  
                        "scroll" => $scrollTime
                    )
                );
            }
        }

        return $userIds;
	}

	private function truncateProcessedUsersForDrip($userIds,$mailerModel,$mailerId){
		$processedUsers = $mailerModel->getProcessedUsers($mailerId);
		if($processedUsers < 1 || empty($processedUsers)){
			return $userIds;
		}
		return array_slice($userIds, $processedUsers);
	}

	private function getESQueryForUserSet($index,$routingKey,$parentMailerId,$isOpen,$isClick,$scrollTime){
		$elasticQuery = array();
        $elasticQuery['index'] = $index;
        $elasticQuery['type'] = 'mmm_mail';
        $elasticQuery['routing'] = $routingKey;
        $elasticQuery['scroll'] = $scrollTime;
        $elasticQuery['size'] = 1000;

        $elasticQuery['body']['_source'] = array("userid");
	    $elasticQuery['filter_path'] 	 = array("hits.hits._source","_scroll_id");

        $elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['mailerid'] = $parentMailerId;

        if(!empty($isClick)){
        	if($isClick=="yes"){
        		$isClick = 1;
        	}
        	else{
        		$isClick = 0;
        	}
        	$elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isClick'] = $isClick;
        }
        if(!empty($isOpen)){
        	if($isOpen=="yes"){
        		$isOpen = 1;
        	}
        	else{
        		$isOpen = 0;
        	}
        	$elasticQuery['body']['query']['bool']['filter']['bool']['must'][]['term']['isOpen'] = $isOpen;
        }

        return $elasticQuery;
	}
}
