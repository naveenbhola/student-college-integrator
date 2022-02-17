<?php

class Cache
{
	protected $CI;
	protected $cacheLib;
	protected $appId = 1;
	protected $product = 'misc';
	protected $flag = 1;
	
	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library('cacheLib');
		$this->cacheLib = $this->CI->cachelib;
	}
	
	protected function store($key,$id,$value,$ttl=0,$product=NULL,$flag=1)
	{
		if(!$product) {
			$product = $this->product;
		}
		$this->cacheLib->store($this->getCacheKey($key,$id),$value,$ttl,$product,$flag);
	}
	
	protected function get($key,$id)
	{
		$cachedData = $this->cacheLib->get($this->getCacheKey($key,$id));
		if($cachedData == 'ERROR_READING_CACHE') {
			return FALSE;
		}
		return $cachedData;
	}
	
	protected function multiGet($key,$ids)
	{
		$keys = array();
		$keyMapping = array();
		foreach($ids as $id) {
			$currentKey = $this->getCacheKey($key,$id);
			$cacheCurrentKey = $this->cacheLib->getCacheKey($currentKey);
			$keys[] = $currentKey;
			$keyMapping[$cacheCurrentKey] = $id;
			
		}
		$cachedData = $this->cacheLib->get($keys);
		$indexedData = array();
		foreach($cachedData as $key => $data) {
			$indexedData[$keyMapping[$key]] = $data;
		}
		return $indexedData;
	}
	
	protected function delete($key,$id)
	{
		$this->cacheLib->clearCacheForKey($this->getCacheKey($key,$id));
	}
	
	protected function getCacheKey($key,$id)
	{
		$cacheType = $this->cacheLib->getCacheType();
		if($cacheType == "redis"){
                        if(is_array($id)) {
				foreach ($id as $singleId) {
					$pagekey[] = $key.$this->appId.$singleId;
				}
				return $pagekey;
			}
			else {
				return $key.$this->appId.$id;
			}
		}
		else{
                    if(is_array($id)){
                        foreach ($id as $singleId) {
                            $pagekey[] = md5($key.$this->appId.$singleId);
                        }
                        return $pagekey;
                    }
                    else{
                        return md5($key.$this->appId.$id);
                    }
			
		}
	}
	
	protected function getByKey($key)
	{
		$cachedData = $this->cacheLib->get($key);
		if($cachedData == 'ERROR_READING_CACHE') {
			return FALSE;
		}
		return $cachedData;
	}
	
	protected function storeByKey($key,$value,$ttl=0,$product=NULL,$flag=0)
	{
		if(!$product) {
			$product = $this->product;
		}
		$this->cacheLib->store($key,$value,$ttl,$product,$flag);
	}
	
	protected function deleteByKey($key)
	{
		$this->cacheLib->clearCacheForKey($key);
	}
	
	protected function inc($key){
		return $this->cacheLib->inc($key);
	}
        
        
        /*
         *  As hashmap is not supported by memcached, some of the features of following functions will not work with memcache
         */ 
        
        /*
         *  $members = array('member1'=>'dataForMember1','member2'=>'dataForMember2');
         * 
         *  In case of redis it will update/insert only passed members of hashmap, but in memcache whole key will get replaced
         * 
         *  $key = 'course'   //name of entity   $id = 201301   //id of entity (eg:courseId)
         */
        
        protected function addMembersToHash($key,$id,$members=array(), $ttl = 1800,$addMemberIfNotExist = FALSE){
            $key = $this->getCacheKey($key,$id);
            return $this->cacheLib->addMembersToHash($key,$members,$ttl,$addMemberIfNotExist);
        }
        /*
         * $key = 'name';  // name of entity eg: 'course'
         $ids = array('id1','id2')  
         $members = array('id1'=>array('member1'=>'dataForMember1','member2'=>'dataForMember2')
                          'id2'=>array('member1'=>'dataForMember1','member2'=>'dataForMember2')
                          );
        */
        protected function addMembersToHashForMultipleKeys($key,$ids,$members = array(),$ttl = 1800,$addMemberIfNotExist=false){
            $memberData = array();
            foreach($ids as &$id){
                $cacheKey = $this->getCacheKey($key,$id);
                $memberData[$cacheKey] = $members[$id];
                unset($members[$id]);
                $keys[] = $cacheKey;
            }
            return $this->cacheLib->addMembersToHashForMultipleKeys($keys,$memberData,$ttl,$addMemberIfNotExist);
        }

        // $fields = array('field1','field2','field3');
        protected function getMembersOfHashByFieldNameWithValue($key,$id,$fields ){
            $key = $this->getCacheKey($key,$id);
            return $this->cacheLib->getMembersOfHashByFieldNameWithValue($key,$fields);
        }

        protected function getAllMembersOfHashWithValue($key,$id ){
            $key = $this->getCacheKey($key,$id);
            return $this->cacheLib->getAllMembersOfHashWithValue($key);
        }
        /*
         * $key = 'name';  // name of entity eg: 'course'
         *  $ids = array('id1','id2');
         *  $fields = array('field1','field2');
         */
        protected function getMembersOfHashByFieldNameWithValueForMultipleKeys($key,$ids,$fields){
            $keys = $this->getCacheKey($key,$ids);
            return $this->cacheLib->getMembersOfHashByFieldNameWithValueForMultipleKeys($keys,$fields);
        }
}
