<?php
/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-09-10 07:14:44 $:  Date of last commit

CacheLib to proivide library method to the clients

$Id: CacheLib.php,v 1.12 2010-09-10 07:14:44 build Exp $:

*/
global $starttimeads;
class CacheLibAPC{

	/*
	*	Function to store data in cache, default is 1800 secs
	*/

	public $productKeys = array('messageBoard','Events','listing','groups','homePage','misc');

    function store($key,$value,$ttl=1800,$product='misc',$flag = 0){
			if($ttl == -1) {
				$ttl = 0; //-- store until manually cleared
			}else if($ttl == 900) { /**CHECK FOR ABROAD CATEGORY PAGE BANNER ROTATION ***/ 
			$ttl = $ttl;
			}else if($ttl <1800){
                $ttl = 1800;
            }

            error_log_shiksha("CACHE PROG_STARTED");
            $this->getLock();
            if($flag == 0)
            {
                    $productKeyArray = array();
                    $productKeyArray = apc_fetch($product);
                    if(!is_array($productKeyArray)){
                            $productKeyArray = array();
                    }
                    $starttime = microtime(true);

                    if (!in_array($key, $productKeyArray)) {
                    array_push($productKeyArray, $key);
                    apc_store($product,$productKeyArray);
            }

                global $starttimeads;
                global $globalStart;
                if($globalStart == 1){
                    $starttimeads += microtime(true) - $starttime;
                    error_log($starttimeads.'TTTAKEN');
                }
            }

			if(substr($key,0,5) == 'city2') error_log("fatal: ".memory_get_usage());
			
            if(!(apc_store($key,$value,$ttl))){
                            $this->releaseLock();
                            return "ERROR CREATING CACHE\n";
            }else{
                            error_log_shiksha("CACHE SAVED");
                            $this->releaseLock();
                            return "CREATING CACHE\n";
            }

    }
	
	function clearCacheForKey($key)
	{
		apc_delete($key);
	}

	/*
	*	This method is used to invalidate the keys of cache without using apache stop/start. ALL_KEYS will remove all keys
	*/
    function clearCache($product='misc',$master = 1){
        global $shikshaFrontEndBoxes;
        if($master == 1){
            // Clear caches of other frontend boxes
            $c = curl_init();
            for($i= 0  ;$i < count($shikshaFrontEndBoxes) ; $i++){
                $url =  "http://".$shikshaFrontEndBoxes[$i]."/ListingScripts/clearCache/".$product."/0";
                curl_setopt($c, CURLOPT_URL,$url);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                curl_exec($c);
            }
            curl_close($c);
        }
        else{
            if($product=='ALL_KEYS'){
                foreach ($productKeys as $productkey){
                    $productKeyArray=apc_fetch($productkey);
                    foreach ($productKeyArray as $key){
                        apc_delete($key);
                    }
                    apc_delete($product);
                }
            }else{
                $productKeyArray=apc_fetch($product);
                foreach ($productKeyArray as $key){
                    apc_delete($key);
                }
                apc_delete($product);
            }
        }
    }

	/*
	*	Take lock, semaphore to check only one process is writing to cache
	*/
	function getLock(){
		if(!(apc_fetch("LOCK"))){
           		error_log_shiksha("creation lock for first time");
			//Do not grant lock for more than 5 mins to one process
			apc_store("LOCK","LOCK_TAKEN",300);
		}else{
			$i=0;
			while(apc_fetch("LOCK")=="LOCK_TAKEN"){
				//echo "SLEEPING \n";
				sleep(3);
				$i++;
				if($i==5){
					//echo "TAKING FORCEFULL LOCK\n";
					break;
				}
			}
			//Do not grant lock for more than 5 mins to one process
			apc_store("LOCK","LOCK_TAKEN",300);
		}
		error_log_shiksha("lock granted");
	}


	/*
	*	Release lock, semphaore to release lock
	*/
	function releaseLock(){
		apc_store("LOCK","LOCK_FREE");
	        error_log_shiksha("lock freed");
	}


	/*
	*	Function to read the cache
	*/
	function get($key){
        	error_log_shiksha("get key and reading cache");
		if(!(apc_fetch($key))){
			return "ERROR_READING_CACHE";
		}else{
			return(apc_fetch($key));
		}
	}


        /*
	*	Function to multiple keys read of the cache
	*/
        function getMulti($keysArray) {
            // print_r($keyArray); die;
             return (apc_fetch($keysArray));
        }


	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		array on success/false on failure
	 */
	public function get_metadata($id)
	{
		$stored = apc_fetch($id);

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
	 * Check to see if APC is available on this system, bail if it isn't.
	 */
	public function is_supported()
	{
		if ( !extension_loaded('apc') OR ! function_exists('apc_store'))
		{
			error_log('Could not connect. The APC PHP extension must be loaded to use APC Cache.');
			return FALSE;
		}                
		return TRUE;
	}
	
	public function inc(){
		return FALSE;
	}

}
?>