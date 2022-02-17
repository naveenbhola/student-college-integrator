<?php 

class CourseCache{

	function __construct()
	{
		$this->_redis_client = PredisLibrary::getInstance();
	}

	public function canOpenCourseInEdit($courseId){
		$stringKey 	= "Lock_Course:".$courseId;
		$isKeyExist = $this->_redis_client->getMemberOfString($stringKey);

		if(!empty($isKeyExist)){
			return false;
		}
		return true;
	}

	public function lockCourseForEdit($courseId){
		$stringKey 	= "Lock_Course:".$courseId;
		$expireInSeconds = 20 * 60;// minutes * seconds
		$data = $this->_redis_client->addMemberToString($stringKey,1,$expireInSeconds);
		return $data;
	}

	public function removeLockOnCourseForEdit($courseId){
		$stringKey 	= "Lock_Course:".$courseId;
		return $this->_redis_client->deleteKey(array($stringKey));
	}
}

?>