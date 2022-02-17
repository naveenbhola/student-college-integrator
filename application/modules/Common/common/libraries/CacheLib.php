<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-09-10 07:14:44 $:  Date of last commit

CacheLib to proivide library method to the clients

$Id: CacheLib.php,v 1.12 2010-09-10 07:14:44 build Exp $:

*/

class CacheLib{

       //  private $cacheToBeUsed = 'apc';
        // private $cacheToBeUsed = 'memcache';
	private $cacheToBeUsed = 'redis';
        private $cacheLibObj;
		private $backupCache = '';
		private $backupCacheLibObj;

	private $readCacheObj;

    function __construct()
    {
  //       	$this->cacheLibObj = $this->getCacheLibObj($this->cacheToBeUsed);
  //       	$this->cacheLibObj->is_supported();

		// $this->readCacheObj = $this->cacheLibObj;		

		/*if(ENVIRONMENT == 'production') {
			$this->backupCache = 'backup_memcache';
		}

		if($this->backupCache) {
			$this->backupCacheLibObj = $this->getCacheLibObj($this->backupCache);
			$this->readCacheObj = rand(1, 2) == 1 ? $this->cacheLibObj : $this->backupCacheLibObj;
		}*/

		//error_log("CacheLB:: ".$this->readCacheObj->getMServer());
    }

    function init(){
    	if(isset($this->cacheLibObj)){
    		return;
    	}
		$this->cacheLibObj = $this->getCacheLibObj($this->cacheToBeUsed);
    	$this->cacheLibObj->is_supported();

		$this->readCacheObj = $this->cacheLibObj;    	
    }
	
	private function getCacheLibObj($cache)
	{
		$CI = & get_instance();
		$cacheLibObj = null;
		
		switch($cache) {
            case 'memcache':
                $cacheLibObj = $CI->load->library('common/CacheLibMemcached');                
                break;
			
		case 'backup_memcache':
                $cacheLibObj = $CI->load->library('common/CacheLibBackupMemcached');
                break;


			case 'redis':
                $cacheLibObj = $CI->load->library('common/CacheLibRedis');                
                break;

            default:
                $cacheLibObj =  $CI->load->library('common/CacheLibAPC');                
                break;
        }
		
		return $cacheLibObj;
	}

	/*
	*	Function to store data in cache, default is 1800 secs
	*/    

    function store($key, $value, $ttl=1800, $product='misc', $flag = 0){
		
		$this->init();
		global $shikshaCacheProfile;
		
		// get keyname
		$key = $this->getCacheKey($key);

		if($_REQUEST['profiling'] == '1') {
			$backtrace = debug_backtrace();
			$functionTrace = array();
			$traceExcludeFunctions = array('__call','call_user_func_array','require','require_once','include','include_once','view','_ci_load');
			foreach($backtrace as $trace) {
				
				if($trace['line'] && !in_array($trace['function'],$traceExcludeFunctions) && strpos($trace['file'],'third_party') === FALSE) {
					$functionTrace[] = $trace['function']." at line ".$trace['line']." in ".str_replace(APPPATH,'',str_replace(FCPATH,'',$trace['file']));
				}
			}
			
			$shikshaCacheProfile['writes'][$key] = $functionTrace;
			if($_REQUEST['showCacheData'] == '1') {
				$shikshaCacheProfile['writeData'][$key] = $value;
			}
		}
		
		if(!isset($value)){
			$value = "VALUE NOT SET";
		}elseif(is_array($value) && count($value) == 0){
			$value = "EMPTY ARRAY";
		}elseif($value == ""){
			$value = "EMPTY STRING";
		}
		
		/**
		 * Store in main cache
		 */ 
		$return =  $this->cacheLibObj->store($key, $value, $ttl, $product, $flag);
        
		/**
		 * Store in backup cache
		 */
		if($this->backupCache) {
			$this->backupCacheLibObj->store($key, $value, $ttl, $product, $flag);
		}
		
		return $return;    
    }

	/*
	*	This method is used to invalidate the keys of cache without using apache stop/start. ALL_KEYS will remove all keys
	*/
    function clearCache($product='misc', $master = 1){

    	$this->init();
        $return = $this->cacheLibObj->clearCache($product, $master);
		if($this->backupCache) {
			$this->backupCacheLibObj->clearCache($product, $master);
		}
		return $return;
    }

	/*
	*	Take lock, semaphore to check only one process is writing to cache
	*/
	function getLock(){
			$this->init();
            $this->cacheLibObj->getLock();
	}


	/*
	*	Release lock, semphaore to release lock
	*/
	function releaseLock(){
			$this->init();
            $this->cacheLibObj->releaseLock();
	}

	/*
	*	Function to read the cache
	*/
	function get($key){
			
			$this->init();
			global $shikshaCacheProfile;

			// get keyname
			$key = $this->getCacheKey($key);
			
			if($_REQUEST['globalCacheOff'] == '1') {
				return "ERROR_READING_CACHE";
			}
			
			$value = $this->readCacheObj->get($key);

			// if(is_array($value) || is_object($value)) {
			// 	$shikshaCacheProfile['cacheReadSize'] += strlen(serialize($value));
			// }
			// else {
			// 	$shikshaCacheProfile['cacheReadSize'] += strlen($value);
			// }
			
			if($_REQUEST['profiling'] == '1') {
				$backtrace = debug_backtrace();
				$functionTrace = array();
				$traceExcludeFunctions = array('__call','call_user_func_array','require','require_once','include','include_once','view','_ci_load');
				foreach($backtrace as $trace) {
					
					if($trace['line'] && !in_array($trace['function'],$traceExcludeFunctions) && strpos($trace['file'],'third_party') === FALSE) {
						$functionTrace[] = $trace['function']." at line ".$trace['line']." in ".str_replace(APPPATH,'',str_replace(FCPATH,'',$trace['file']));
					}
				}
				
				// $shikshaCacheProfile['reads'][$key] = $functionTrace;
    //             if(is_array($key)) {
    //                 foreach($key as $kk) {
    //                     $shikshaCacheProfile['reads'][$kk] = $functionTrace;
    //                 }
    //             }
    //             else {
    //                 $shikshaCacheProfile['reads'][$key] = $functionTrace;
    //             }

				// if($_REQUEST['showCacheData'] == '1') {
				// 	$shikshaCacheProfile['readData'][$key] = $value;
				// }
			}
			
			if($value == "VALUE NOT SET" || $value == "EMPTY STRING"){
				$value = "";
			}elseif($value == "EMPTY ARRAY"){
				$value = array();
			}
			return $value;
	}


        /*
	*	Function to multiple keys read of the cache
	*/
        function getMulti($keysArray) {
        	$this->init();
             return $this->readCacheObj->getMulti($keysArray);
        }


	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		array on success/false on failure
	 */
	public function get_metadata($id)
	{
			$this->init();
            return $this->readCacheObj->get_metadata($id);
	}

	/**
	 * is_supported()
	 *
	 * Check to see if APC is available on this system, bail if it isn't.
	 */
	public function is_supported()
	{
			$this->init();
            return $this->cacheLibObj->is_supported();
	}

	function clearCategoryPageCache($categoryPageData)
	{
		$this->init();
		global $shikshaFrontEndBoxes;
		
		$categoryPageData = implode("-",$categoryPageData);
		
		$c = curl_init();
		for($i= 0  ;$i < count($shikshaFrontEndBoxes) ; $i++){
			$url =  "http://".$shikshaFrontEndBoxes[$i]."/ListingScripts/clearCategoryPageCache/".$categoryPageData;
			curl_setopt($c, CURLOPT_URL,$url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			curl_exec($c);
		}
		curl_close($c);
	}
	
	function clearCacheForKey($key)
	{
		$this->init();
		// get keyname
		$key = $this->getCacheKey($key);

		$this->cacheLibObj->clearCacheForKey($key);
		if($this->backupCache) {
			$this->backupCacheLibObj->clearCacheForKey($key);
		}
	}
	
	function inc($key) {
		
		$this->init();
		// get keyname
		$key = $this->getCacheKey($key);
		
		$return = $this->cacheLibObj->inc($key);
		if($this->backupCache) {
			$this->backupCacheLibObj->inc($key);
		}
		return $return;
	}

	function getCacheType(){
		return $this->cacheToBeUsed;
	}

	function getCacheKey($key){

		$cacheType = $this->getCacheType();
		if($cacheType == "redis"){
			// for redis keys return the actual key with a constant prefix
			if(is_array($key)) {
				foreach ($key as $singleKey) {
					$pagekey[] = "rk_".$singleKey;
				}
				return $pagekey;
			}
			else {
				return "rk_".$key;
			}
		}
		else{
			return $key;
		}
	}
        
        
        /*
         *  As hashmap is not supported by memcached, some of the features of following functions will not work with memcache
         */ 
        
        /*
         *  $members = array('member1'=>'dataForMember1','member2'=>'dataForMember2');
         * 
         *  In case of redis it will update/insert only passed members of hashmap, but in memcache whole key will get replaced
         */
        
        public function addMembersToHash($key,$members=array(), $ttl = 1800,$addMemberIfNotExist = FALSE){
        	$this->init();
            $key = $this->getCacheKey($key);
            return $this->cacheLibObj->addMembersToHash($key,$members,$ttl,$addMemberIfNotExist);
        }
        /*
         $key = array('key1','key2')  
         $members = array('key1'=>array('member1'=>'dataForMember1','member2'=>'dataForMember2')
                          'key2'=>array('member1'=>'dataForMember1','member2'=>'dataForMember2')
                          );
        */
        public function addMembersToHashForMultipleKeys($keys,$members = array(),$ttl = 1800,$addMemberIfNotExist=false){
        	$this->init();
            $memberData = array();
            foreach($keys as &$key){
                $cacheKey = $this->getCacheKey($key);
                $memberData[$cacheKey] = $members[$key];
                unset($members[$key]);
                $key = $cacheKey;
            }
            return $this->cacheLibObj->addMembersToHashForMultipleKeys($keys,$memberData,$ttl,$addMemberIfNotExist);
        }

        // $fields = array('field1','field2','field3');
        public function getMembersOfHashByFieldNameWithValue($key,$fields ){
        	$this->init();
            $key = $this->getCacheKey($key);
            return $this->cacheLibObj->getMembersOfHashByFieldNameWithValue($key,$fields);
        }

        public function getAllMembersOfHashWithValue($key){
        	$this->init();
            $key = $this->getCacheKey($key);
            return $this->cacheLibObj->getAllMembersOfHashWithValue($key);
        }
        /*
         *  $keys = array('key1','key2');
         *  $fields = array('field1','field2');
         */
        public function getMembersOfHashByFieldNameWithValueForMultipleKeys($keys,$fields){
        	$this->init();
            $keys = $this->getCacheKey($keys);
            return $this->cacheLibObj->getMembersOfHashByFieldNameWithValueForMultipleKeys($keys,$fields);
        }
}
