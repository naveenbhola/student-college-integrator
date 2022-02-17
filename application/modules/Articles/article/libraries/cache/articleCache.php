<?php

class articleCache extends Cache
{	
	function __construct()
	{
		parent::__construct();
	}

	public function storeArticleStreamSubStreamCourseMapping($data){
		if(!empty($data)){
			$data = serialize($data);
			$this->store('article_stream_substream_course_mapping','article', $data, 86400);
		}
	}

	public function getArticleStreamSubStreamCourseMapping(){
		$data = unserialize($this->get('article_stream_substream_course_mapping','article'));
		return $data;
	}
	function storeArticleCountByHierarchy($streamId,$substreamId,$popularCourseId,$data)
	{
		$stringKey = 'articleCount_';
		if(!empty($streamId) && empty($substreamId) && empty($popularCourseId))
			$stringKey .= 'stream_'.$streamId;
		if(!empty($streamId) && !empty($substreamId) && empty($popularCourseId))
			$stringKey .= 'substream_'.$substreamId;
		if(empty($streamId) && empty($substreamId) && !empty($popularCourseId))
			$stringKey .= 'course_'.$popularCourseId;

		$redis_client = PredisLibrary::getInstance();
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
	}
    function getArticleCountByHierarchy($streamId,$substreamId,$popularCourseId)
    {
        $redis_client = PredisLibrary::getInstance();

        $stringKey = 'articleCount_';
		if(!empty($streamId) && empty($substreamId) && empty($popularCourseId))
			$stringKey .= 'stream_'.$streamId;
		if(!empty($streamId) && !empty($substreamId) && empty($popularCourseId))
			$stringKey .= 'substream_'.$substreamId;
		if(empty($streamId) && empty($substreamId) && !empty($popularCourseId))
			$stringKey .= 'course_'.$popularCourseId;

        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }

    /**
    * storing Ranking Page Url based on streamId, subStream Id, baseCourseId
    */
    function storeRankingURL($streamId,$substreamId,$baseCourseId,$data)
	{
		$stringKey = 'rankingUrl';
		if(!empty($streamId))
			$stringKey .= '_'.$streamId;
		if(!empty($substreamId))
			$stringKey .= '_'.$substreamId;
		if(!empty($baseCourseId))
			$stringKey .= '_'.$baseCourseId;

		$redis_client = PredisLibrary::getInstance();
        $expireInSeconds = 24 * 60 * 60;//hours * minutes * seconds
        $data = json_encode($data);
        $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
        return $result;   
	}
    function getRankingURL($streamId,$substreamId,$baseCourseId)
    {
        $redis_client = PredisLibrary::getInstance();

       $stringKey = 'rankingUrl';
		if(!empty($streamId))
			$stringKey .= '_'.$streamId;
		if(!empty($substreamId))
			$stringKey .= '_'.$substreamId;
		if(!empty($baseCourseId))
			$stringKey .= '_'.$baseCourseId;

        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
    }

   function storeFooterLinksCache($data)
   {
      $stringKey = 'footerCustomizedLinks';
      $redis_client = PredisLibrary::getInstance();
      $expireInSeconds = 60 * 60;//minutes * seconds
      $data = json_encode($data);
      $result = $redis_client->addMemberToString($stringKey,$data,$expireInSeconds);
      return $result;
   }

   function getFooterLinksCache()
   {
        $redis_client = PredisLibrary::getInstance();
        $stringKey = 'footerCustomizedLinks';
        $data = $redis_client->getMemberOfString($stringKey);
        return json_decode($data,true);
   }

}
