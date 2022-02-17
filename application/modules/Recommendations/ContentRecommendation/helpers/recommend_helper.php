<?php  
	if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	function getExclusionClause($exclusionList,$column)
    {
        $exclusionClause = '';
        
        if(is_array($exclusionList) && count($exclusionList) && is_string($column))
        {
            $exclusionClause = $column." NOT IN (".implode(",",$exclusionList).") ";
        }
        
        return $exclusionClause;
    }

    function getTimeWindowsToProcess($last_processed_time_window)
	{
	$current_time = date("Y-m-d H:i:00");

	$current_minute = intVal(date('i'));
	
	if($current_minute > 0 && $current_minute < 30)
	{
		$current_time = date("Y-m-d H:i:00",strtotime("-$current_minute minutes",strtotime($current_time)));
	}
	else if($current_minute > 30)
	{
		$minute_lag = $current_minute - 30;
		$current_time = date("Y-m-d H:i:00",strtotime("-$minute_lag minutes",strtotime($current_time)));
	}
	
	if(!$last_processed_time_window)
	{
		$last_processed_time_window = date("Y-m-d H:i:00",strtotime("-30 minutes",strtotime($current_time)));
	}
	
	$difference = strtotime($current_time) - strtotime($last_processed_time_window);
	
	$time_windows = array();
	
	for($i=$difference;$i>0;$i-=1800)
	{
		$time_window_start = date("Y-m-d H:i:00",strtotime("-$i seconds",strtotime($current_time)));
		$time_window_end = date("Y-m-d H:i:00",strtotime("-".($i-1800)." seconds",strtotime($current_time)));
		
		$time_windows[] = $time_window_start.";".$time_window_end;
	}

	return $time_windows;
}

	function getFormattedData($data, $offset, $count){
        $content = array();
        if($data!=null && count($data)>0){
            $content['topContent'] = array_slice($data, $offset, $count);
            $content['numFound'] = count($data);
            }
        else{
            $content['topContent'] = array();
            $content['numFound'] = 0;
        }
        return $content;
    }

    function getCachePrefix($listingType,$contentType){
    	$keyPrefix="";

    	switch ($listingType) {
			case 'institute':
			case 'university':
				$keyPrefix.='INS';
				break;
		   	default:
				return '';
		}

		$keyPrefix.='_';

		switch ($contentType) {
			case 'question':
				$keyPrefix.='QUES';
				break;
			case 'discussion':
				$keyPrefix.='DISC';
				break;
			case 'review':
				$keyPrefix.='REVW';
				break;
			case 'article':
				$keyPrefix.='ARTC';
				break;
			default:
				return '';
		}
		$keyPrefix.='_COUNT#';
		return $keyPrefix;
    }

    function getCountsFromCache($listingIds, $cacheKeyPrefix){
       	
		$idsForCache=array();
		foreach ($listingIds as $listingId) {
		    $idsForCache[] = $cacheKeyPrefix.$listingId;    
		}
        
        // Load in case cache Lib not loaded.
        $cache = PredisLibrary::getInstance(); 
		
		$responseFromCache = $cache->getMemberOfMultipleString($idsForCache);

		$resultFromCacheWithCount = array();
		foreach ($responseFromCache as $orderkey => $contentCount) {
            if($contentCount!=NULL){
                $resultFromCacheWithCount[$listingIds[$orderkey]] = $contentCount;
            }
        }
        return $resultFromCacheWithCount;

    }

    function updateCountsCache($contentCountMap, $cacheKeyPrefix, $expireInSeconds = 86400){
    	
        if(!empty($contentCountMap)){
        	$cache = PredisLibrary::getInstance();
            
            $cache->setPipeline();
            foreach ($contentCountMap as $listingId=>$contentCount) {
            	$cacheKey = $cacheKeyPrefix.$listingId;
                $cache->addMemberToString($cacheKey,$contentCount,$expireInSeconds,FALSE,TRUE);
            }
            $cache->executePipeline();
        }
    }
?>