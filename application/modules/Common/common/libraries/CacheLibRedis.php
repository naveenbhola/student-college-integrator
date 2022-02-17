<?php
global $starttimeads;

class CacheLibRedis
{
	public $productKeys = array('messageBoard','Events','listing','groups','homePage','misc');
	
	private $_redis_client;
	private $CI;
	
	function __construct()
	{
		$this->CI = & get_instance();	
		$this->_redis_client = PredisLibrary::getInstance();//$this->CI->load->library('common/PredisLibrary');
	}

	/**
	 * Function to store data in cache, default is 1800 secs
	 */
	function store($key, $value, $ttl=1800, $product='misc', $flag = 0)
	{
		if($product != 'TransactionLock') {
			if($ttl == -1) {
				$ttl = 0; //-- store until manually cleared
			}
			else if($ttl == 900) { /***SPECIAL CHECK FOR ABROAD CATEGORY PAGE BANNER ROTATION  ***/
				$ttl = $ttl;
			}
			else if($ttl <1800) {
				$ttl = 1800;
			}
			else if($ttl >= 2592000) {
				$ttl = 0;
			}
		}
		
		if($flag == 0 && $product != 'misc') {
			$this->_redis_client->addMembersToSet('p__'.$product, array($key));
		}
	
		if(is_array($value) || is_object($value)) {
			$value = "RSerz_|_".serialize($value);
		}
	
		$this->_redis_client->addMemberToString($key, $value, $ttl);	
	}

	function clearCacheForKey($key)
	{
		if(!empty($key)){
			$this->_redis_client->deleteKey(array($key));
		}
	}

	/*
	 *	This method is used to invalidate the keys of cache. ALL_KEYS will remove all keys, $master has been used in APC functions so let it be here for that support..
	 */
	function clearCache($product='misc', $master = 1)
	{
		if($product=='ALL_KEYS'){
			foreach ($this->productKeys as $productkey){
				$this->clearCacheForProduct($productkey);
			}
		}
		else{
			$this->clearCacheForProduct($product);
		}
	}
	
	private function clearCacheForProduct($productKey)
	{
		$productKeyArray = $this->_redis_client->getMembersOfSet('p__'.$productKey);
		if(!empty($productKeyArray)){
			$this->_redis_client->deleteKey($productKeyArray);
		}
	}
	
	/*
	 *	Take lock, semaphore to check only one process is writing to cache
	 */
	function getLock()
	{
		
	}

	/*
	 *	Release lock, semphaore to release lock
	 */
	function releaseLock()
	{
		
	}

	/*
	 *	Function to read the cache
	 */
	function get($key)
	{
		if(is_array($key)) {
			return $this->getMulti($key);
		}
		
		try {
			$val = $this->_redis_client->getMemberOfString($key);
			
			if(!$val){
				return "ERROR_READING_CACHE";
			}else{
				if(strpos($val,'RSerz_|_') === 0) {
					$val = unserialize(substr($val,8));
				}
				return $val;
			}
		}
		catch(Exception $e) {
			return NULL;
		}
	}

	/*
	 *	Function to multiple keys read of the cache
	 */
	function getMulti($keysArray)
	{
		try {
			$values = $this->_redis_client->getMemberOfMultipleString($keysArray);
			
			$vals = array();
			$i = 0;
			foreach($values as $val) {
				if($val !== NULL) {
					if(strpos($val,'RSerz_|_') === 0) {
						$val = unserialize(substr($val,8));
					}
					$vals[$keysArray[$i]] = $val;
				}
				$i++;
			}
			return $vals;
		}
		catch(Exception $e) {
			return array();	
		}
	}

	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		array on success/false on failure
	 */
	public function get_metadata($id)
	{
		
	}

	/**
	 * is_supported()
	 */
	public function is_supported()
	{
		return TRUE;
	}
	
	public function inc($key)
	{
		try {
			return $this->_redis_client->incrementMemberOfString($key);
		}
		catch(Exception $e) {}
	}
        
        public function addMembersToHash($key,$members=array(),$ttl = 1800,$addMemberIfNotExist = FALSE){
            try{
                $return =  $this->_redis_client->addMembersToHash($key,$members,$addMemberIfNotExist);
                if($ttl!=-1){
                    $this->_redis_client->expireKey($key,$ttl);
                }
                
                return $return;
            } catch (Exception $ex) {

            }
            
        }
        public function addMembersToHashForMultipleKeys($keys,$members = array(),$ttl = 1800,$addMemberIfNotExist=false){
            try{
                foreach($keys as &$key){
                    $this->_redis_client->addMembersToHash($key,$members[$key],$addMemberIfNotExist,true);
                    if($ttl!=-1){
                        $this->_redis_client->expireKey($key,$ttl,true);
                    }
                }
                $return = $this->_redis_client->executePipeLine();
                return $return;
            } catch (Exception $ex) {

            }
        }
        
        public function getMembersOfHashByFieldNameWithValue($key,$fields = array()){
            try{
                return $this->_redis_client->getMembersOfHashByFieldNameWithValue($key,$fields);
            } 
            catch (Exception $ex) {
            }
        }

        public function getAllMembersOfHashWithValue($key){
            try{
                return $this->_redis_client->getAllMembersOfHashWithValue($key);
            } 
            catch (Exception $ex) {
            }
        }
        
        public function getMembersOfHashByFieldNameWithValueForMultipleKeys($keys,$fields = array()){
            try{
                foreach($keys as &$key){
                    $this->_redis_client->getMembersOfHashByFieldNameWithValue($key,$fields,true);
                }
                $dataFromCache =  $this->_redis_client->executePipeLine();
                $dataArray   = array();
                if(is_array($dataFromCache)){
                    foreach ($dataFromCache as $key1=>&$value) {
                        foreach ($value as $key2=>&$fieldData) {
                            if(!empty($fieldData)){
                                $dataArray[$key1][$fields[$key2]] = $fieldData;	
                            }
                        }
                    }	
                }
                return $dataArray;
            } 
            catch (Exception $ex) {

            }
        }
}
