<?php
global $starttimeads;
class CacheLibBackupMemcached {
	private $_server;
	private $_server_port;
	private $_memcacheObj;
	private $_memcache_compresssion;

	function __construct()
	{
		if(isset($this->_server))
		{
			return $this->_server;
		}
		else
		{
			$this->_server = BACKUP_MEMCACHE_IP;
			$this->_server_port = BACKUP_MEMCACHE_PORT;
			$this->_memcache_compresssion = false;
			$this->_memcacheObj = new Memcache;

            $this->_memcacheObj->connect($this->_server, $this->_server_port) or die ("Could not connect");
		}
	}

	public function getMServer()
	{
		return $this->_server;
	}

	/*
	 *	Function to store data in cache, default is 1800 secs
	 */

	public $productKeys = array('messageBoard','Events','listing','groups','homePage','misc');

	function store($key, $value, $ttl=1800, $product='misc', $flag = 0){
		
		if($product != 'TransactionLock') {
			if($ttl == -1) {
				$ttl = 0; //-- store until manually cleared
			}else if($ttl == 900) { /***SPECIAL CHECK FOR ABROAD CATEGORY PAGE BANNER ROTATION  ***/
                        $ttl = $ttl;
               		 }
			else if($ttl <1800){
				$ttl = 1800;
			}
			else if($ttl >= 2592000) {
				$ttl = 0;
			}
		}
		
		error_log_shiksha("CACHE PROG_STARTED");
		//$this->getLock();

		if(0)
		{
			$productKeyArray = array();
			$productKeyArray = $this->_memcacheObj->get($product);

			if(!is_array($productKeyArray)){
				$productKeyArray = array();
			}

			$starttime = microtime(true);

			if (!in_array($key, $productKeyArray)) {
				array_push($productKeyArray, $key);
				$this->_memcacheObj->set($product, $productKeyArray, $this->_memcache_compresssion, 0);
			}

			global $starttimeads;
			global $globalStart;

			if($globalStart == 1){
				$starttimeads += microtime(true) - $starttime;
				error_log($starttimeads.'TTTAKEN');
			}
		}

		if($this->_memcacheObj->replace($key, $value, $this->_memcache_compresssion, $ttl)) {
			//$this->releaseLock();
			return TRUE;
		}
		else if($this->_memcacheObj->set($key, $value, $this->_memcache_compresssion, $ttl)) {
			//$this->releaseLock();
			return TRUE;
		}
		else {
			error_log("ERROR STORING IN CACHE. Key: ".$key);
			//$this->releaseLock();
			return FALSE;
		}
	}

	function clearCacheForKey($key)
	{
		if($this->_memcacheObj->delete($key,0)) {
			return TRUE;
		}
		else {
			error_log("ERROR DELETING IN CACHE. Key: ".$key);
			return FALSE;
		}
	}

	/*
	 *	This method is used to invalidate the keys of cache. ALL_KEYS will remove all keys, $master has been used in APC functions so let it be here for that support..
	 */
	function clearCache($product='misc', $master = 1){

		if($product=='ALL_KEYS'){
			foreach ($productKeys as $productkey){
				$productKeyArray = $this->_memcacheObj->get($productkey);
				foreach ($productKeyArray as $key){
					$this->_memcacheObj->delete($key,0);
				}
				$this->_memcacheObj->delete($product,0);
			}
		}else{
			$productKeyArray = $this->_memcacheObj->get($product);
			foreach ($productKeyArray as $key){
				$this->_memcacheObj->delete($key,0);
			}
			$this->_memcacheObj->delete($product,0);
		}
	}

	/*
	 *	Take lock, semaphore to check only one process is writing to cache
	 */
	function getLock(){
		if(!($this->_memcacheObj->get("LOCK"))){
			error_log_shiksha("creation lock for first time");
			//Do not grant lock for more than 5 mins to one process
			$this->_memcacheObj->set("LOCK", "LOCK_TAKEN", $this->_memcache_compresssion, 300);
		}else{
			$i=0;
			while($this->_memcacheObj->get("LOCK")=="LOCK_TAKEN"){
				//echo "SLEEPING \n";
				sleep(3);
				$i++;
				if($i==5){
					//echo "TAKING FORCEFULL LOCK\n";
					break;
				}
			}
			// Do not grant lock for more than 5 mins to one process
			$this->_memcacheObj->set("LOCK", "LOCK_TAKEN", $this->_memcache_compresssion, 300);
			if(!$this->_memcacheObj->replace("LOCK", "LOCK_TAKEN", $this->_memcache_compresssion, 300)) {
				$this->_memcacheObj->set("LOCK", "LOCK_TAKEN", $this->_memcache_compresssion, 300);
			}

		}
		error_log_shiksha("lock granted");
	}


	/*
	 *	Release lock, semphaore to release lock
	 */
	function releaseLock(){
		if(!$this->_memcacheObj->replace("LOCK","LOCK_FREE")) {
			$this->_memcacheObj->set("LOCK", "LOCK_FREE", $this->_memcache_compresssion, 0);
		}                
		error_log_shiksha("lock freed");
	}


	/*
	 *	Function to read the cache
	 */
	function get($key){
        error_log_shiksha("get key and reading cache");
        $val = $this->_memcacheObj->get($key);
        if(!$val){
            return "ERROR_READING_CACHE";
        }else{
            return $val;
        }
	}

	/*
	 *	Function to multiple keys read of the cache
	 */
	function getMulti($keysArray) {
		// print_r($keyArray); die;
		return ($this->_memcacheObj->get($keysArray));
	}

	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		array on success/false on failure
	 */
	public function get_metadata($id)
	{
		$stored = $this->_memcacheObj->get($id);

		if (count($stored) !== 3)
		{
			return FALSE;
		}

		list($value, $time, $ttl) = $stored;

		return array(
			'expire'	=> $time + $ttl,
			'mtime'		=> $time,
			'data'		=> $data
		);
	}

	/**
	 * is_supported()
	 *
	 * Check to see if Memcaches is available on this system, bail if it isn't.
	 */
	public function is_supported()
	{
		if(!class_exists('Memcache')){
			log_message('error', 'The Memcache must be loaded to use Memcache Cache.');
			return FALSE;
		}

		return TRUE;
	}
	
	public function inc($key) {
		if($this->is_supported()) {
			$value = $this->_memcacheObj->increment($key);
			if($value === false) {
				$this->_memcacheObj->set($key, 1);
				$value = 1;
			}
			return $value;
		} else {
			return false;
		}
	}

}
?>
