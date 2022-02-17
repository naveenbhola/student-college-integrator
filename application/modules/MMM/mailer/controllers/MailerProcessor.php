<?php

class MailerProcessor extends MX_Controller
{
	private $mailerModel;
	private $mainKey = "MMM_RECO_";

	function __construct()
	{
		//ini_set('memory_limit','1024M');
		$this->load->model('mailermodel');
		$this->mailerModel = new mailermodel();
		$this->load->library('mailer/ProductMailerConfig');
		$this->load->library('mailer/MailerFactory');
	}

	/**
	 * Process Client Mailers
	 */
	function processMailers($batch)
	{

		$this->validateCron();
		//ini_set('memory_limit','-1');
        ini_set('memory_limit','1024M');
		set_time_limit(0);
		$start_time = time();
		error_log('processclientmailer==st=='.$start_time.'==mem=='.memory_get_usage());

		$file = '/tmp/mailer_time_log'.date('Y-m-d').".txt";
        $fp = fopen($file,'a');

        $file2 = '/tmp/mailer_memory_log_'.date('Y-m-d').".txt";
        $fp2 = fopen($file2,'a');

		/**
		 * Get mailers to process
		 */
		$mailerRepository = MailerFactory::getMailerRepository();
		$mailers = $mailerRepository->getUnprocessedMailers($batch);

		unset($mailerRepository);
		foreach($mailers as $mailer) {

			fwrite($fp2,"memory_usage: start at ".date('Y-m-d H:i:s')." for mailer id = ".$mailer->getId()." == ".(memory_get_usage()/1024/1024)."\n");

			/**
			 * Set mailer status in progress
			 */
			$this->mailerModel->updateMailerStatus($mailer->getId(), "inProgress");
			/**
			 * Get mailer processor based on mailer type - CSV or UserSet
			 */
			$mailerProcessor = MailerFactory::getMailerProcessor($mailer);
			$result = $mailerProcessor->process();
			unset($mailerProcessor);
			
			/**
			 * Set mailer status in progress
			 */
			if($result === false) {
				$mailerId = '';
				$mailerId = $mailer->getId();

				$mailerName = '';
				$mailerName = $mailer->getName();

				$this->mailerModel->updateMailerStatus($mailerId, "false");
				$this->mailerModel->addtoMailTracking($mailerId);

				$this->sentMailerFailedNotification($mailerId, $mailerName);


			} else {
				$clientId = $mailer->getClientId();
				$this->load->config('mailer/mailerConfig');
        		$mailerAdminUserId = $this->config->item('mailerAdminUserId');
				if($clientId==$mailerAdminUserId){
					$this->mailerModel->updateMailerStatus($mailer->getId(), "true");
				}
				else{
					$amountToDeductDetails = $this->mailerModel->getExpectedAndDeductedAmount($mailer->getId());
					$actualDeduction = $amountToDeductDetails[0]['actualDeduction'];
					$expectedDeduction = $amountToDeductDetails[0]['expectedDeduction'];
					$subId = $amountToDeductDetails[0]['subscriptionId'];

					if($actualDeduction>$expectedDeduction){
						$amountToDeduct = $actualDeduction;
					}
					else{
						$amountToDeduct = $expectedDeduction;
					}
					$this->mailerModel->updateMailerStatus($mailer->getId(), "true", true);
					$isTransactionCompelete = $this->mailerModel->freeLockedCreditsForMailer($mailer->getId());
					if($isTransactionCompelete){
						$this->mailerModel->deductAmountFromSubscription($subId,$amountToDeduct);
					}
				}
			}
			
			$mailer_id = $mailer->getId();
			
			unset($mailer);


			fwrite($fp2,"memory_usage: end at ".date('Y-m-d H:i:s')." for mailer id = ".$mailer_id." == ".(memory_get_usage()/1024/1024)."\n");
		}

		fwrite($fp2,"peak_memory_usage: end at ".date('Y-m-d H:i:s')." for mailer id = ".$mailer_id." == ".(memory_get_peak_usage(true)/1024/1024)."\n");
		fclose($fp2);

		
		$end_time = time()-$start_time;
        error_log('processclientmailer==et=='.$end_time.'==mem=='.memory_get_usage().'==peakmem=='.memory_get_peak_usage(true));

        fwrite($fp,"time taken = ".$end_time."\n");
		fclose($fp);

	}

	/**
	 * Send Mail Notification in case where users form solr are zero and mail processing stops for that mail 
	 */
	private function sentMailerFailedNotification($mailerId, $mailerName) {

		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <info@shiksha.com>' . "\r\n";

		$mailSubject = 'Mailer Processing of mail ('.$mailerName.') Failed From '.ENVIRONMENT;
		$mailContent = 'Details of Mailer:<br/>';
		$mailContent .= 'Mailer Name = '.$mailerName.'<br/>';
		$mailContent .= 'Mailer ID = '.$mailerId.'<br/>';

		$emailIds = 'teamldb@shiksha.com,mohd.alimkhan@shiksha.com';
		
		mail($emailIds,$mailSubject,$mailContent,$headers);

	}

	/**
	 * Process Product Mailers
	 */
	function processProductMailers($batch = 1)
	{
		$this->validateCron();
		//ini_set('memory_limit', '-1');
        ini_set('memory_limit','1024M');
		ini_set('time_limit', 0);
		$start_time = microtime(true);
        error_log('processproductmailer==st=='.$start_time.'==mem=='.memory_get_usage());
		set_time_limit(0);
		$mailerRepository = MailerFactory::getMailerRepository();
		$mailers = $mailerRepository->getProductMailers($batch);
		
		foreach($mailers as $mailer) {

			/**
			 * Get time windows to process for this mailer
			 * calculated since last processed time window
			 */
			$timeWindows = $this->_getTimeWindowsToProcess($mailer);
			if(is_array($timeWindows) && count($timeWindows)) {
				foreach ($timeWindows as $currentTimeWindow) {
					$usersInTimeWindow = $this->_getUsersInTimeWindow($mailer, $currentTimeWindow);
					if(!empty($usersInTimeWindow)) {
						$mailerProcessor = MailerFactory::getMailerProcessor($mailer,array('boundrySet' => $usersInTimeWindow, 'timeWindow' => $currentTimeWindow, 'mailer' => $mailer));
						$mailerProcessor->process(array('boundrySet' => $usersInTimeWindow, 'timeWindow' => $currentTimeWindow, 'mailer' => $mailer));
					}
					/**
					 * Update last processed time window
					 */
					$this->mailerModel->setTimeWindowProcessed($mailer->getId(), $currentTimeWindow);
				}
			}
			unset($timeWindows);
		}
		$end_time = microtime(true)-$start_time;
        error_log('processproductmailer==et=='.$end_time.'==mem=='.memory_get_usage().'==peakmem=='.memory_get_peak_usage(true));
	}
	
	function processOtherProductMailers()
	{
		$this->processProductMailers(2);
	}

	function processOtherAbroadProductMailers()
	{
		$this->processProductMailers(22);
	}
	private function getBatchesForTheDay(){
		$batch_UG = $batch_PG = 0;
		switch (date('D')) {
			case 'Mon':
				//$streams = array(1);
				$batch_UG = 24;
				$batch_PG = 31;
				break;
			case 'Tue':
				//$streams = array(2);
				$batch_UG = 25;
				$batch_PG = 32;
				break;
			case 'Wed':
				//$streams = array(3,4,5,6);
				$batch_UG = 26;
				$batch_PG = 33;
				break;
			case 'Thu':
				//$streams = array(7,8,9,10);
				$batch_UG = 27;
				$batch_PG = 34;
				break;
			case 'Fri':
				//$streams = array(11,12,13,14);
				$batch_UG = 28;
				$batch_PG = 35;
				break;
			case 'Sat':
				//$streams = array(15,16,17);
				$batch_UG = 29;
				$batch_PG = 36;
				break;
			case 'Sun':
				//$streams = array(18,19,21);
				$batch_UG = 30;
				$batch_PG = 37;
				break;
		}
		return array('UG' => $batch_UG, 'PG' => $batch_PG);
	}
	private function _getStreamIdFromShortName($shortName){
		$map = array(
			'Business'     => 1,
			'Engineering'  => 2,
			'Design'       => 3,
			'Hospitality'  => 4,
			'Law'          => 5,
			'Animation'    => 6,
			'MassCom'      => 7,
			'Software'     => 8,
			'Humanities'   => 9,
			'Arts'         => 10,
			'Science'      => 11,
			'Architecture' => 12,
			'Accounting'   => 13,
			'Banking'      => 14,
			'Aviation'     => 15,
			'Teaching'     => 16,
			'Nursing'      => 17,
			'Medicine'     => 18,
			'Beauty'       => 19,
			'Government'   => 21
		);
		return $map[$shortName];
	}

	public function sendExamAlertForStream($courseLevel){
		$this->validateCron();
		ini_set('memory_limit','1024M');
		ini_set('time_limit', 0);
		set_time_limit(0);
		$batches = $this->getBatchesForTheDay();
		$batch = $batches[$courseLevel];
		$mailerRepository = MailerFactory::getMailerRepository();
		$todaysMailers = $mailerRepository->getProductMailers($batch);
		if(empty($todaysMailers)){
			return;
		}

		$mailers = $streams = array();
		foreach ($todaysMailers as $key => $mailer) {
			$mailerName = $mailer->getName();
			$mailerNameParts = explode('_', $mailerName);
			$streamShortName = $mailerNameParts[1];

			$streamId = $this->_getStreamIdFromShortName($streamShortName);
			$streams[] = $streamId;
			$mailers[$streamId] = $mailer;
		}
		unset($todaysMailers);

		$this->load->builder("listingBase/ListingBaseBuilder");
        $listingBaseBuilder = new ListingBaseBuilder();
        $streamRepo = $listingBaseBuilder->getStreamRepository();
        $this->load->model('user/usermodel');
        $this->userModel = new usermodel();
        $urlLibObj = $this->load->library('common/UrlLib', array());
        $this->load->config('examPages/MEAConfig');
        $this->load->model('examPages/exampagemodel');
        $this->exampageModel = new exampagemodel();
        $levelWiseGroupId = $this->config->item('groupIds');

        $streamObjs = $streamRepo->findMultiple($streams);
		$hierarchies = $this->userModel->getAllHierarchies($streams);
		$hierarchIds = array_map(function($h){return $h['hierarchy_id'];}, $hierarchies);
		$hierarchyToStreamMap = array();
		foreach ($hierarchies as $hier) {
			$hierarchyToStreamMap[$hier['hierarchy_id']] = $hier['stream_id'];
		}
        $dates = $this->exampageModel->getExamDatesForMEAMailer($hierarchIds, $levelWiseGroupId[$courseLevel]);
        if(empty($dates)){
        	echo 'No exam dates available.';
        	return;
        }
		$streamWiseDates = array();
		foreach ($dates as $event) {
			$streamId = $hierarchyToStreamMap[$event['hier']];
			unset($event['hier']);
			$streamWiseDates[$streamId][] = $event;
		}
		unset($dates);
		$availableStreams = array_keys($streamWiseDates);
		$streams = array_intersect($availableStreams, $streams);
		if(empty($streams)){
			echo 'No date data in any stream';
			return;
		}

		foreach ($mailers as $strmId => $mailer) {
			if(!in_array($strmId, $streams)){
				continue;
			}
			$timeWindows = $this->_getTimeWindowsToProcessMEAMailer($mailer);
	        foreach ($timeWindows as $currentTimeWindow) {
				$usersInTimeWindow = $this->_getMEAUsersInTimeWindow($currentTimeWindow, array($strmId), $courseLevel);
				if(!empty($usersInTimeWindow)) {
					foreach ($usersInTimeWindow as $streamId => $userIds) {
						$unsubscribedUsers = $this->userModel->filterUnsubscribedUserIds($userIds);
						$userIds = array_diff($userIds, $unsubscribedUsers);
						if(date('H')=='11'){
							$userIds[] = '12971399';
							$userIds[] = '3759819';
						}
						$formattedDates = $this->_formatDate($streamWiseDates[$streamId]);
						$stream = array();
			            $stream['id'] = $streamId;
			            $stream['level'] = $courseLevel;
			            $stream['name'] = $streamObjs[$streamId]->getName();
			            $stream['hierarchy'] = $urlLibObj->getHierarchy($streamId);
						$mailerProcessor = MailerFactory::getMailerProcessor($mailer,array('isMEA'=>'yes', 'boundrySet' => $userIds, 'timeWindow' => $currentTimeWindow, 'mailer' => $mailer, 'stream' => $stream));
						$mailerDataToProcess = array(
							'isMEA'=>'yes',
							'boundrySet' => $userIds,
							'timeWindow' => $currentTimeWindow,
							'mailer' => $mailer,
							'stream' => $stream,
							'formattedDates' => $formattedDates
						);
						$mailerProcessor->process($mailerDataToProcess);
					}
				}
				/**
				 * Update last processed time window
				 */
				$this->mailerModel->setTimeWindowProcessed($mailer->getId(), $currentTimeWindow);
	        }
	    }
        echo 'Done<br/>';
        echo 'Streams : '.print_r($streams, true).'<br/>'.'Course level : '.print_r($courseLevel, true);
        @mail('virender.singh@shiksha.com','MEA Mailer Processed for '.date('Y-m-d H:i:s'), 'Streams : '.print_r($streams, true).'<br/>'.'Course level : '.print_r($courseLevel, true));
	}
	private function _getTimeWindowsToProcessMEAMailer($mailer){
		$no_of_days_in_time_window = 4;
		$hour = date('H');
		$mins = date('i');
		if($hour == 0){
			$this->mailerModel->setTimeWindowProcessed($mailer->getId(), '0000-00-00 00:00:00;0000-00-00 00:00:00');
			$lastProcessedTimeWindow = '0000-00-00 00:00:00';
		}else{
			$lastProcessedTimeWindow = $mailer->getLastProcessedTimeWindow();
		}
		if($mins >= 30){
			$hour = $hour * 2 + 2;
		}else{
			$hour = $hour * 2 + 1;
		}
		$multiplier = $hour * $no_of_days_in_time_window;
		$endDate   = date('Y-m-d 23:59:59', strtotime('-'.($multiplier-($no_of_days_in_time_window-1)).' days'));
		$startDate = date('Y-m-d 00:00:00', strtotime('-'.($multiplier).' days'));
		$timeWindows = array();
		$max_windows = 48;
		if(!empty($lastProcessedTimeWindow) && $lastProcessedTimeWindow != '0000-00-00 00:00:00'){
			while(date('Y-m-d', strtotime($endDate)) != date('Y-m-d', strtotime($lastProcessedTimeWindow)) && count($timeWindows) < $max_windows){
				$timeWindows[] = date('Y-m-d 00:00:00', strtotime('-'.($no_of_days_in_time_window-1).' days', strtotime($lastProcessedTimeWindow))).';'.$lastProcessedTimeWindow;
				$lastProcessedTimeWindow = date('Y-m-d 23:59:59', strtotime('-'.$no_of_days_in_time_window.' days', strtotime($lastProcessedTimeWindow)));
			}
		}
		$timeWindows[] = $startDate.';'.$endDate;
		return $timeWindows;
	}

	public function sendExamAlertForStream_old($courseLevel, $batch = 4){
		ini_set('memory_limit','1024M');
		ini_set('time_limit', 0);
		set_time_limit(0);
		$mailerRepository = MailerFactory::getMailerRepository();
		$mailers = $mailerRepository->getProductMailers($batch);
		$mailer = $mailers[0];
		if(empty($mailer)){
			return;
		}
		$streams = $this->getStreamsForTheDay();

		$this->load->builder("listingBase/ListingBaseBuilder");
        $listingBaseBuilder = new ListingBaseBuilder();
        $streamRepo = $listingBaseBuilder->getStreamRepository();
        $this->load->model('user/usermodel');
        $this->userModel = new usermodel();
        $urlLibObj = $this->load->library('common/UrlLib', array());
        $this->load->config('examPages/MEAConfig');
        $this->load->model('examPages/exampagemodel');
        $this->exampageModel = new exampagemodel();
        $levelWiseGroupId = $this->config->item('groupIds');

		$timeWindows = $this->_getTimeWindowsToProcess($mailer);
		if(is_array($timeWindows) && count($timeWindows)) {
			foreach ($timeWindows as $currentTimeWindow) {
				$streamObjs = $streamRepo->findMultiple($streams);
        		$hierarchies = $this->userModel->getAllHierarchies($streams);
        		$hierarchIds = array_map(function($h){return $h['hierarchy_id'];}, $hierarchies);
        		$hierarchyToStreamMap = array();
        		foreach ($hierarchies as $hier) {
        			$hierarchyToStreamMap[$hier['hierarchy_id']] = $hier['stream_id'];
        		}
				
				$dates = $this->exampageModel->getExamDatesForMEAMailer($hierarchIds, $levelWiseGroupId[$courseLevel]);
				$streamWiseDates = array();
				foreach ($dates as $event) {
					$streamId = $hierarchyToStreamMap[$event['hier']];
					unset($event['hier']);
					$streamWiseDates[$streamId][] = $event;
				}
				unset($dates);

				$availableStreams = array_keys($streamWiseDates);
				$streams = array_intersect($availableStreams, $streams);

				$usersInTimeWindow = $this->_getMEAUsersInTimeWindow($currentTimeWindow, $streams, $courseLevel);
				if(!empty($usersInTimeWindow)) {
					foreach ($usersInTimeWindow as $streamId => $userIds) {
						//$userIds = array($userIds[0], $userIds[0]);
						$formattedDates = $this->_formatDate($streamWiseDates[$streamId]);
						$stream = array();
			            $stream['id'] = $streamId;
			            $stream['name'] = $streamObjs[$streamId]->getName();
			            $stream['hierarchy'] = $urlLibObj->getHierarchy($streamId);
						$mailerProcessor = MailerFactory::getMailerProcessor($mailer,array('isMEA'=>'yes', 'boundrySet' => $userIds, 'timeWindow' => $currentTimeWindow, 'mailer' => $mailer, 'stream' => $stream));
						$mailerProcessor->process(array('isMEA'=>'yes', 'boundrySet' => $userIds, 'timeWindow' => $currentTimeWindow, 'mailer' => $mailer, 'formattedDates' => $formattedDates, 'stream' => $stream));
					}
				}
				/**
				 * Update last processed time window
				 */
				$this->mailerModel->setTimeWindowProcessed($mailer->getId(), $currentTimeWindow);
			}
		}
		unset($timeWindows);
	}

	private function _getMEAUsersInTimeWindow($currentTimeWindow, $streams, $courseLevel){
		list($timeWindowStart,$timeWindowEnd) = explode(';',$currentTimeWindow);
		$criteria = array('start' => $timeWindowStart, 'end' => $timeWindowEnd);
		return $this->userModel->getUserDataForExamAlerts($streams, $courseLevel, $criteria);
	}

    /**
	 * Process and format dates for MEA mailer
	 */
    private function _formatDate($dates){
    	$MAX_EVENTS = 15;
    	$MAX_TUPLE = 5;
    	$MAX_EVENTS_PER_TUPLE = 3;

        $tuple = $dateData = array();
        $rowCount = 0;
        foreach ($dates as $key => &$date) {
            if(empty($tuple[$date['groupId']])){
                $tuple[$date['groupId']] = 0;
            }
            if($tuple[$date['groupId']] < $MAX_EVENTS_PER_TUPLE){
                $dateData[$date['groupId']][] = $date;
                unset($dates[$key]);
                $tuple[$date['groupId']]++;
                $rowCount++;
            }
            if($rowCount == $MAX_EVENTS || count($tuple) == $MAX_TUPLE){
                break;
            }
        }
        $groupsAdded = array_keys($dateData);
        if($rowCount < $MAX_EVENTS){
            foreach ($dates as $date) {
                if(in_array($date['groupId'], $groupsAdded) && count($dateData[$date['groupId']]) < $MAX_EVENTS_PER_TUPLE){
                    $dateData[$date['groupId']][] = $date;
                    $rowCount++;
                    if($rowCount == $MAX_EVENTS){
                        break;
                    }
                }
            }
        }
        return $dateData;
    }

	/**
	 * Get time windows to process for a mailer
	 */
	private function _getTimeWindowsToProcess(Mailer $mailer)
	{
		/**
		 * Get current time window
		 * If current time is 4:15, time window will be 3:30 - 4:00
		 * If current time is 4:45, time window will be 4:00 - 4:30
		 */
		$currentMinute = intVal(date('i'));
		
		if($currentMinute < 30) {
			$currentTime = date("Y-m-d H:00:00");
		}
		else {
			$currentTime = date("Y-m-d H:30:00");
		}
		
		$lastProcessedTimeWindow = $mailer->getLastProcessedTimeWindow();
		if(empty($lastProcessedTimeWindow) || $lastProcessedTimeWindow == '0000-00-00 00:00:00') {
			$lastProcessedTimeWindow = date("Y-m-d H:i:00",strtotime("-30 minutes",strtotime($currentTime)));
		}
		
		/**
		 * Calculate time windows between current time window and last processed time window
		 */
		$difference = strtotime($currentTime) - strtotime($lastProcessedTimeWindow);
		
		$timeWindows = array();
		for($i=$difference;$i>0;$i-=1800) {
			$timeWindowStart = date("Y-m-d H:i:00",strtotime("-$i seconds",strtotime($currentTime)));
			$timeWindowEnd   = date("Y-m-d H:i:00",strtotime("-".($i-1800)." seconds",strtotime($currentTime)));
			$timeWindows[]   = $timeWindowStart.";".$timeWindowEnd;
		}
		
		return $timeWindows;
	}

	/**
	 * Get users lying in a time window
	 */
	private function _getUsersInTimeWindow(Mailer $mailer, $timeWindow,$usersToBeExcluded)
	{
		list($timeWindowStart,$timeWindowEnd) = explode(';',$timeWindow);
			
		$date = date('Y-m-d',strtotime($timeWindowStart));
		$timeWindowStartTime = date('H:i:00',strtotime($timeWindowStart));
		$timeWindowEndTime = date('H:i:00',strtotime($timeWindowEnd));
		if($timeWindowEndTime == '00:00:00') {
			$timeWindowEndTime = '23:59:59';
		}
		
		$totalDays = ProductMailerConfig::getItem($mailer->getId(),'TotalDays');
		$startDate = date('Y-m-d',strtotime($totalDays,strtotime($date)));
		
		$frequency = ProductMailerConfig::getItem($mailer->getId(),'Frequency');
		if(!$frequency) {
			$frequency = 3;
		}
		
		$delay = ProductMailerConfig::getItem($mailer->getId(),'Delay');
		if(intval($delay) > 0) {
			$date = date('Y-m-d',strtotime('-'.$delay.' days',strtotime($date)));
		}
		
		$criteria = array('type' => 'timeWindow', 'frequency' => $frequency, 'date' => $date, 'start' => $timeWindowStartTime, 'end' => $timeWindowEndTime, 'from' => $startDate);
		
		$this->load->model('user/activitybaseduserfindermodel');
		$activityBasedUserFinder = new ActivityBasedUserFinderModel;

		$usersType = ProductMailerConfig::getItem($mailer->getId(),'UsersType');
		$flagForRedisDown = 0;

		if($usersType == 'response') {
			if($mailer->getId()==22094){
				// $usersInTimeWindow = $activityBasedUserFinder->getResponseUsersForMailer($criteria);
				// $usersInTimeWindowRedis = $this->getResponseUsersForMailerFromRedis($criteria['start']);
				$usersInTimeWindow = $this->getResponseUsersForMailerFromRedis($criteria['start']);
				if(empty($usersInTimeWindow)){
					$flagForRedisDown = 1;
					$usersInTimeWindow = $activityBasedUserFinder->getResponseUsersForMailer($criteria);
					mail('teamldb@shiksha.com', 'empty array return by redis in MMM' , 'redis might be down. bucket time '.$criteria['start']);
				}
			}
			else{
				$usersInTimeWindow = $activityBasedUserFinder->getResponseUsersForMailer($criteria);
			}
		} else{
			$usersInTimeWindow = $activityBasedUserFinder->getUsersForMailer($criteria);
		}

		if($mailer->getId()!=22094 || $flagForRedisDown==1) {
			$usersInTimeWindow = array_keys($usersInTimeWindow);
		}
		// $usersInTimeWindow = array_keys($usersInTimeWindow);

		$usersInTimeWindowPostExclusion = array();
		foreach($usersInTimeWindow as $user) {
			if(!$usersToBeExcluded[$user]) {
				$usersInTimeWindowPostExclusion[] = $user;
			}
		}

		return $usersInTimeWindowPostExclusion;
	}

	public function redisAddUsersInTimeBucket(){
		$this->validateCron();
 		$this->removeUsersFromTimeBucket();
		$this->setUsersInTimeBucket();
	}

	public function setUsersInTimeBucket(){
		$this->redisWriteDelete('write');
	}

	public function removeUsersFromTimeBucket(){
		$this->redisWriteDelete('delete');
	}

	public function redisWriteDelete($operation){
		if($operation!='write' && $operation!='delete'){
			return;
		}
		$this->load->model('user/activitybaseduserfindermodel');
		$activityBasedUserFinder = new ActivityBasedUserFinderModel;
		
		if($operation=='write'){
			$time = date('Y-m-d',strtotime("-1 days"));
		}
		else{
			$time = date('Y-m-d',strtotime("-91 days"));
		}
		$userids = $activityBasedUserFinder->getUserIdsForRedis($time);


		$resultArray = $this->makeDataForRedisMMM($userids,$activityBasedUserFinder);
		$redis_client = PredisLibrary::getInstance();

		if(empty($resultArray)){
			return;
		}

        foreach ($resultArray as $key => $value) {
        	$keyRedis = $this->mainKey.$key;
        	if($operation=='write'){
				$errorCode = $redis_client->addMembersToSet($keyRedis,$value);
			}
			else{
				$errorCode = $redis_client->removeMembersOfSet($keyRedis,$value);
			}

	        if(!(is_numeric($errorCode) && $errorCode>=0)){
	        	mail("teamldb@shiksha.com", "error in redis write or delete", "redis error ".$errorCode."<br> bucket number ".$key);
	        }
        }

	}


	public function makeDataForRedisMMM($userids,$activityBasedUserFinder){
		$chunk = 0;
        $useridsChunk = array();
        $resultArray = array();

        foreach ($userids as $userid) {
        	$useridsChunk[] = $userid['userId'];

        	if($chunk<500){
        		$chunk = $chunk + 1;
        	}
        	else{
        		$result = $activityBasedUserFinder->getUserIdAndTimeMap($useridsChunk);
    			foreach ($result as $key => $value) {
    				$time = date("H:i:s",strtotime($value['usercreationDate']));
    				$counter = $this->getBucketNumber($time);
    				$resultArray[$counter][] = $value['userid'];
    			}
        		$chunk = 0;
        		$useridsChunk = array();
        	}
        }

        if($chunk>0){
        	$result = $activityBasedUserFinder->getUserIdAndTimeMap($useridsChunk);
			foreach ($result as $key => $value) {
				$time = date("H:i:s",strtotime($value['usercreationDate']));
				$counter = $this->getBucketNumber($time);
				$resultArray[$counter][] = $value['userid'];
			}
    		$chunk = 0;
        }

        return $resultArray;
	}

	public function getResponseUsersForMailerFromRedis($params){
		if(empty($params)){
			return;
		}
		$redis_client = PredisLibrary::getInstance();

		$counter = $this->getBucketNumber($params);

		$keyRedis = $this->mainKey.$counter;

		$userids = $redis_client->getMembersOfSet($keyRedis);
		return $userids;

	}

	public function getBucketNumber($params){
		$params = split(":", $params);
		$counter = 2 * $params[0];
		if($params[1]<30){
			$counter = $counter + 1;
		}
		else{
			$counter = $counter + 2;
		}

		return $counter;
	}

	public function redisWriteDeleteOneTimeCron(){
		$this->validateCron();
		$this->load->model('user/activitybaseduserfindermodel');
		$activityBasedUserFinder = new ActivityBasedUserFinderModel;
		
		$redis_client = PredisLibrary::getInstance();
		for ($days=1; $days <= 90 ; $days++) {
			$time = date('Y-m-d',strtotime("-$days days"));
			$userids = $activityBasedUserFinder->getUserIdsForRedis($time);


			$resultArray = $this->makeDataForRedisMMM($userids,$activityBasedUserFinder);

			if(empty($resultArray)){
				return;
			}

	        foreach ($resultArray as $key => $value) {
	        	$keyRedis = $this->mainKey.$key;
				$errorCode = $redis_client->addMembersToSet($keyRedis,$value);
				
		        if(!(is_numeric($errorCode) && $errorCode>=0)){
		        	mail("teamldb@shiksha.com", "error in redis write or delete", "redis error ".$errorCode."<br> bucket number ".$key);
		        }
	        }

		}
	}


}
?>
