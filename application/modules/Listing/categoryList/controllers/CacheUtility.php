<?php
ini_set('memory_limit','2500M');
set_time_limit(0);

class CacheUtility extends MX_Controller
{
	private $cacheUtilityLib;
	
	function __construct()
	{
		$this->load->library('CacheUtilityLib');
		$this->cacheUtilityLib = new CacheUtilityLib;
	}
	
    function refreshCache()
	{
		$serverIp = $_SERVER['SERVER_ADDR'];
		$cronPid = getmypid();
		$this->load->library('categoryList/clients/CategoryPageClient');
		/*
		 * Get cron data like if there is a cron already running, fail count and  
		 * last processed time window
		 */
		$cronData = $this->categorypageclient->getCronData();
		$alreadyRunningCronStatus = $cronData['alreadyRunningCronStatus'];
		$alreadyRunningCronPid = $cronData['alreadyRunningCronPid'];
		$failCount = (int) $cronData['failCount'];
		$lastProcessedTimeWindow = $cronData['lastProcessedTimeWindow'];
	
		if($alreadyRunningCronStatus == 'NO' || ($alreadyRunningCronStatus == 'YES' && $failCount >= CP_MAX_CRON_FAIL_COUNT)) {
			/*
			 * If no, Register new cron with status 'On'
			 */
			
			if($alreadyRunningCronStatus == 'NO') {
				$registerCronResponse = $this->categorypageclient->registerCron($cronPid,CP_CRON_ON);
			}
			else  {
				$registerCronResponse = $this->categorypageclient->registerCron($cronPid,CP_CRON_TERMINATE);
				sendMailAlert('Terminating cron which has been running for a long time','Category Page Cron ('.$serverIp.')',array('pankaj.meena@shiksha.com'), TRUE);
				/*
				 * Kill the already running cron
				 */
				shell_exec('kill  -9 '.$alreadyRunningCronPid);
			}
			
			if($registerCronResponse === FALSE) {
				/*
				 * Unable to register cron
				 */
				sendMailAlert('Unable to register a new cron','Category Page Cron ('.$serverIp.')',array('pankaj.meena@shiksha.com'), TRUE);
			} else {
				$cronId = $registerCronResponse;
				$timeWindow = $this->_getTimeWindowToProcess($lastProcessedTimeWindow);
				
				if(strtotime($timeWindow['start']) >= strtotime($timeWindow['end'])) {
					$this->categorypageclient->updateCron($cronId,CP_CRON_OFF,'0000-00-00 00:00:00');
				} else {
					$cronStats = $this->refreshCacheInTimeWindow($timeWindow);
					$this->categorypageclient->updateCron($cronId,CP_CRON_OFF,$timeWindow['end'],$cronStats);
				}
			}
		} else {
			/*
			 * If yes, Register new cron with status 'Fail'
			*/
			$this->categorypageclient->registerCron($cronPid,CP_CRON_FAIL);
			sendMailAlert('A cron is already running','Category Page Cron ('.$serverIp.')',array('pankaj.meena@shiksha.com'), TRUE);
		}
	}
	
	private function _getTimeWindowToProcess($lastProcessedTimeWindow)
	{
		$timeWindow = array();
		$currentTime = date("Y-m-d H:i:00");
		$currentMinute = intVal(date('i'));
		if($currentMinute > 0 && $currentMinute < 30) {
			$timeWindowEnd = date("Y-m-d H:i:00",strtotime("-$currentMinute minutes",strtotime($currentTime)));
		}
		else if($currentMinute > 30) {
			$minuteLag = $currentMinute - 30;
			$timeWindowEnd = date("Y-m-d H:i:00",strtotime("-$minuteLag minutes",strtotime($currentTime)));
		}
		
		$timeWindowStart = date("Y-m-d H:i:00",strtotime("-30 minutes",strtotime($timeWindowEnd)));
		if($lastProcessedTimeWindow) {
			$timeWindowStart = $lastProcessedTimeWindow;
		}
		$timeWindow = array('start' => $timeWindowStart,'end' => $timeWindowEnd);
		return $timeWindow; 
	}
	
	public function refreshCacheInTimeWindow($timeWindow)
	{
		$timeWindow = array('start' => '2014-07-11 17:30:00','end' => '2014-07-11 18:30:00');
		$stats = $this->cacheUtilityLib->refreshCacheInTimeWindow($timeWindow);
		return $stats;
	}
	
	public function refreshCacheUtility($entity,$values)
	{
		switch($entity) {
			case 'page':
				$this->cacheUtilityLib->refreshCacheForKey($values);
				break;
			case 'course':
				$courseIds = $values ? explode('-',$values) : array();
				$this->cacheUtilityLib->refreshCacheForCourses($courseIds);
				break;
			case 'institute':
				$instituteIds = $values ? explode('-',$values) : array();
				$this->cacheUtilityLib->refreshCacheForInstitutes($instituteIds);
				break;
		}
		
		echo "DONE";
	}
	
	public function refreshCacheForBanner($values)
	{
		$this->cacheUtilityLib->refreshCacheForKey($values, TRUE);
		echo "DONE";
	}

	public function buildSecondLevelCache()
	{
		// build second level cache for india
		// $this->cacheUtilityLib->buildNationalSecondLevelCache();
		
		// build various caches for abroad
		$this->cacheUtilityLib->buildAbroadCaches();
		
	}
	
	public function buildAbroadSecondLevelCache(){
		$this->cacheUtilityLib->buildAbroadSecondLevelCache();
	}

	public function buildAbroadUniversitySecondLevelCache(){
		$this->cacheUtilityLib->buildAbroadUniversitySecondLevelCache();
	}

	public function buildAbroadCourseSecondLevelCache(){
		$this->cacheUtilityLib->buildAbroadCourseSecondLevelCache();
	}
	
}
