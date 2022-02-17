<?php

/** 
 * Library for MPT related 
*/

class mptLibrary {

	private $redis_key ='MPT_USER_';
	private $maxCoursesForMPT =3;
	function __construct() {
		$this->CI = & get_instance();	
	}

	/**
	* This function load all necessary config, model related to MPT
	*/
	function _init(){
		$this->CI->load->config('MPT/mptConfig');
		$this->CI->load->model('MPT/mptmodel');
		$this->mptModel = new mptmodel();
	}

	public function getNewAddedUserProfile($start_date, $other_base_courses, $end_date=''){
		$this->_init();
		$redis_client = PredisLibrary::getInstance();
		$lastProcessedTime = $redis_client->getMemberOfString("LAST_PROCESSED_TIME_USER_PROFILE");
		
		if ($lastProcessedTime){
			$start_date = $lastProcessedTime;
		}

		if($end_date == ''){
			$new_user_profile = $this->mptModel->getNewAddedUserProfile($start_date, $other_base_courses);
		}else{
			$new_user_profile = $this->mptModel->getNewAddedUserProfileForMigration($start_date, $other_base_courses, $end_date);
		}

		$redis_cache_data = array();
		$redis_key =$this->redis_key;

		$processedTillTime = $start_date;
		foreach ($new_user_profile as $user_profile) {
			if($processedTillTime < $user_profile['time']){
				$processedTillTime = $user_profile['time'];
			}
			$redis_cache_data[$redis_key.$user_profile['userId']]['stream']	= $user_profile['streamId'];		
			$redis_cache_data[$redis_key.$user_profile['userId']]['base_course'][]	= $user_profile['baseCourseId'];
			$redis_cache_data[$redis_key.$user_profile['userId']]['userId']	= $user_profile['userId'];		
			$all_user_ids[] = $user_profile['userId'];
		}

		if($all_user_ids[0]<0){
			return;
		}

		$user_city_map = $this->getStateForUsers($all_user_ids);
		$format_redis_data = $this->formatDataForRedis($redis_cache_data,$user_city_map);
		
		$redis_client->addMemberToString("LAST_PROCESSED_TIME_USER_PROFILE",$processedTillTime);
		return $format_redis_data;
	}

	private function getStateForUsers($user_ids){

		$user_ids = array_chunk($user_ids, 500);
		$user_city_map = array();

		foreach ($user_ids as $user_ids_chunk) {
			$user_city = $this->mptModel->getStateForUsers($user_ids_chunk);

			foreach ($user_city as $value) {
				$user_city_map[$value['userId']] = $value['state_id'];
			}
		}

		return $user_city_map;
	}

	private function pickRandomBaseCourse($stream, $base_courses = array()){
		global $managementStreamMR;
        global $mbaBaseCourse;
        global $engineeringtStreamMR;
        global $btechBaseCourse;

        $base_courses = array_unique($base_courses);
        $base_courses = array_values($base_courses);

        if($stream == $managementStreamMR && in_array($mbaBaseCourse, $base_courses)){
        	return $mbaBaseCourse;
        }

        if($stream == $engineeringtStreamMR && in_array($btechBaseCourse, $base_courses)){
        	return $btechBaseCourse;
        }

        $randomKey = mt_rand(0, count($base_courses)-1);

		return $base_courses[$randomKey];

	}

	private function formatDataForRedis($redis_cache_data,$user_city_map){
		$return_data = array();

		foreach ($redis_cache_data as $key => $user_data) {
			$redis_cache_data[$key]['state'] 				= $user_city_map[$user_data['userId']];
			$redis_cache_data[$key]['base_course'] 			= $this->pickRandomBaseCourse($redis_cache_data[$key]['stream'], $redis_cache_data[$key]['base_course']);
			unset($redis_cache_data[$key]['userId']);
		}

		return $redis_cache_data;
	}

	public function addNewMPTProfileToRedis($new_user_profile){
		$redis_client = PredisLibrary::getInstance();

		
		foreach ($new_user_profile as $redis_key => $redis_value) {
			$response_code = $redis_client->addMemberToString($redis_key, json_encode($redis_value), 31536000); //TTL is 1 year i.e 31536000 seconds

			if(!$response_code){
				mail('teamldb@shiksha.com', 'Redis addition to MPT', 'Addition of MPT profile on redis -  '.$response_code);
			}
		}
	}

	public function getProfileFromRedis($userIds,$params)
	{
		$this->CI =& get_instance();
		$nationalCategoryListLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		
		$prefixKey = $this->redis_key;
		
		// creating Keys to get profile from redis
		foreach ($userIds as $userId) {
			$keys[] = $prefixKey.$userId;
		}


        $redis_client = PredisLibrary::getInstance();
        $userData = $redis_client->getMemberOfMultipleString($keys);

        $userIdIndex=0;
        $profileIndex=0;
        $usersWithNoProfile = array();

        // getting user profile , creating unique profiles and mapping
        foreach ($userData as $key => $value) {
        	$value = json_decode($value,true);
        	$userProfileMapping[$userIds[$key]] = $value;
        	$profileKey = $value["state"].'_'.$value["stream"].'_'.$value["base_course"];
            if(empty($value['state']) || empty($value['stream'] || $value['base_course'])){
                $usersWithNoProfile[] = $userIds[$key];
            }

        	if (!array_key_exists($profileKey, $profileIndexMapping))
        	{	
        		$uniqueProfiles[] = $value;
        		$profileIndexMapping[$profileKey]= $profileIndex;
        		$profileIndex++;
        	}
        }

        if(!empty($usersWithNoProfile)){
            $this->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$usersWithNoProfile,"yes","1","0");
        }

        unset($usersWithNoProfile);
      
       	// get courses for the unique profiles generated from Redis

        $profileToCourseIdMapping = $nationalCategoryListLib->fetchClientCoursesForCriteria($uniqueProfiles);
      	unset($uniqueProfiles);

        $usersWithNoCourses = array();

      	// creating user id to course id map (random 3)
        foreach ($userProfileMapping as $key => $value) {
        	$profileKey = $value["state"].'_'.$value["stream"].'_'.$value["base_course"];
        	$courses = $this->getRandomCourses($profileToCourseIdMapping[$profileIndexMapping[$profileKey]],$this->maxCoursesForMPT);
            if(!empty($courses)) {
                $userCourseMapping[$key] = $courses;
            }
            else if(!empty($value['state']) && !empty($value['stream']) && !empty($value['base_course'])){
                $usersWithNoCourses[] = $key;
            }
        }

        if(!empty($usersWithNoCourses)){
            $this->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$usersWithNoCourses,"yes","2","0");
        }

        unset($profileToCourseIdMapping);
        unset($userProfileMapping);
        return $userCourseMapping;
    }

    function getRandomCourses($courses,$maxCourses){
    	$size = sizeof($courses);
    	if ($size <= $maxCourses || $size==0){
    		return $courses;
    	}
    	$pickedValuesIndex = [];
    	$randomCourses = [];

    	while ($maxCourses>0){
    		$randomValue = mt_rand(0,$size-1); 
    		if (!in_array($randomValue, $pickedValuesIndex)){
    			$pickedValuesIndex[] = $randomValue;
    			$randomCourses[] = $courses[$randomValue];
    			$maxCourses--;
    		}
    		
    	}
    	return $randomCourses;

    }
    
    function isMPTTupleRequired($param,$userId){
        if(!empty($param) && !empty($param['entityId'])){

            $instituteDetailsModel  = $this->CI->load->model('nationalInstitute/institutedetailsmodel');
        
            if($param['entityType'] == 'institute' || $param['entityType'] == 'university'){

                $heirarchyPaidData =  $instituteDetailsModel->getInstitutePaidStatus($param['entityId']);
                if($heirarchyPaidData[0]['is_hierarchy_paid']){
                    $this->writeMPTDataInFile($param['mailerDetails']['mailer_id'],$param['mailerDetails']['mailer_name'],$userId,"no","1","0");
                    return false;
                } else {
                    return true;
                }
            }

            if($param['entityType'] == 'course'){
                $this->CI->load->builder("nationalCourse/CourseBuilder");
                $builder = new CourseBuilder();
                $this->courseRepository = $builder->getCourseRepository();
                $courseId  = $param['entityId'];
                $coursesData = $this->courseRepository->find($param['entityId'],array("basic"));
                
                if(!empty($coursesData)){
                    if($coursesData->isPaid()){
                        $this->writeMPTDataInFile($param['mailerDetails']['mailer_id'],$param['mailerDetails']['mailer_name'],$userId,"no","2","0");
                       return false;
                    } else{
                       return true;
                    }
                }
            }


            if($param['entityType'] == 'exam'){
                $examModel  = $this->CI->load->model('examPages/exammodel');
                $examInfo  = $examModel->getExamConductingBody($param['entityId']);
                if(!empty($examInfo)){
                    $conductedBy  = $examInfo[0]['conductedBy'];
                    if(intval($conductedBy)){
                        $heirarchyPaidData =  $instituteDetailsModel->getInstitutePaidStatus(intval($conductedBy));
                        if($heirarchyPaidData[0]['is_hierarchy_paid']){
                            $this->writeMPTDataInFile($param['mailerDetails']['mailer_id'],$param['mailerDetails']['mailer_name'],$userId,"no","3","0");
                         return false;
                        } else {
                         return true;
                        }
                    } else {
                        return true;
                    }
                }
            }      
        }
        return true;
    }



    public function getAggregateDataForMailer()
    {
        ini_set('memory_limit','4096M');
        $this->_init();
        $yesterday = date("Y_m_d", strtotime("-1 day"));
        if(ENVIRONMENT == 'production'){
            $filePath = "/data/app_logs/MPTTracking/MPT_".$yesterday.".txt";
        }
        else{
            $filePath = "/tmp/MPT_".$yesterday.".txt";
        }

        $command1 = "'{ if (!seen[$1]++) print $1 }' ";
        $command = "awk -F, ".$command1.$filePath;
        $mailerIds = shell_exec($command);
        $mailerIds = str_replace("\n",",",$mailerIds);
        $mailerIds = (explode(',',$mailerIds));
        $mailerIds = array_flip($mailerIds);
        
        foreach ($mailerIds as $mailerId => $value) {
            if ($mailerId >0){
                $aggregatedData[$mailerId]["no"]  = $this->getAggregateData($filePath,$mailerId,"no");
                $aggregatedData[$mailerId]["mptSent"]["no"] = $this->getAggregateData($filePath,$mailerId,'yes','no');
                $aggregatedData[$mailerId]["mptSent"]["yes"] = $this->getAggregateData($filePath,$mailerId,'yes','yes');
            
            }
        }
        if (!empty($aggregatedData)){
            foreach ($aggregatedData as $mailerId => $data) {
                $querydata = $this->createMPTTrackingInsertQuery($data,$mailerId);
                $this->mptModel->addMPTMailerTracking($querydata['sql'],$querydata["input"]);
            }
        }
    }

    private function getAggregateData($filePath,$mailerId,$MPTapplicable,$mptSent)
    {    
        $configData = $this->CI->config->item('reasonList');
       if ($MPTapplicable == "no" ){
            $columnToGet = 5;
            $returnData =  $this->getNotApplicableMPTtuple($filePath,$mailerId,$columnToGet,$configData['no'],'no');
            return $returnData;
        }

        if ($mptSent == "no" && $MPTapplicable == "yes"){
            $columnToGet = 5;
            $returnData =  $this->getNotApplicableMPTtuple($filePath,$mailerId,$columnToGet,$configData['yes'],'yes');
            return $returnData;
        }


        if ($mptSent == "yes" && $MPTapplicable == "yes"){
            $returnData =  $this->getMptCountAggregated($filePath,$mailerId,$configData['MPTmaxCount']);
            return $returnData;
        } 
    }

    private function getMptCountAggregated($filePath,$mailerId,$maxCount,$applicable='yes'){
        if ($maxCount <= 0){
            return;
        }
        while ($maxCount > 0){
            $command1 = " ' $1 == ".$mailerId. " && $6 ==".$maxCount." && $4==".('"'.$applicable.'"')." { count++ } END { print count }' ";
            $command = "awk -F, ".$command1.$filePath;
            $output = shell_exec($command);
            $returnData[$maxCount]["count"] = $output;  
            $maxCount = $maxCount -1;
        }
        return $returnData;
    }   

    private function getNotApplicableMPTtuple($filePath,$mailerId,$columnToGet,$configData,$applicable){
        if (count($configData) <= 0){
            return;
        }

        foreach ($configData as $key => $reason) {
            $command1 = " ' $1 == ".$mailerId.    " && $".$columnToGet." ==".$key." && $4==".('"'.$applicable.'"')." { count++ } END { print count }' ";
            $command = "awk -F, ".$command1.$filePath;
            $output = shell_exec($command);
            $returnData[$key]["reason"] = $reason;
            $returnData[$key]["count"] = $output;   
        }
        
        return $returnData;
    }

    private function createMPTTrackingInsertQuery($aggregatedData,$mailerId){
        $sql = "insert into MPTTracking (`mailerId`,`totalCount`,`applicable`,`reason`,`mptCount`) values ";
        foreach ($aggregatedData as $applicable => $applicableData) {
            if ($applicable=="no"){
                foreach ($applicableData as $reasonConfigKey => $reasonData) {
                    $sql.="(?,?,?,?,?),";
                    $input[] = $mailerId;
                    $input[] = $reasonData['count'];
                    $input[] = "no";
                    $input[] = $reasonData['reason'];
                    $input[] = 0;
                }
            }
            else{
                foreach ($applicableData as $applicableAsYesNo => $applicableReason) {
                    foreach ($applicableReason as $mptSentCount => $mptSentReason) {
                        if (trim($applicableAsYesNo) == "no"){
                                $sql.="(?,?,?,?,?),";
                                $input[] = $mailerId;
                                $input[] = $mptSentReason['count'];
                                $input[] = "yes";
                                $input[] = $mptSentReason['reason'];
                                $input[] = 0;   
                        }
                        else {

                            $sql.="(?,?,?,?,?),";
                            $input[] = $mailerId;
                            $input[] = $mptSentReason['count'];
                            $input[] = "yes";
                            $input[] = "";
                            $input[] = $mptSentCount;
                        }
                        
                        
                    }
                }
            }

        }
        $sql = substr($sql, 0,-1);
        return array('sql'=>$sql,'input'=>$input);
    }

	public function writeMPTDataInFile($mailerId,$mailerName,$userIds,$applicable,$reason,$MPTCount){
        try{
            if(ENVIRONMENT == 'production'){
                $file = fopen("/data/app_logs/MPTTracking/MPT_".date("Y_m_d").".txt","a");
            }
            else{
                $file = fopen("/tmp/MPT_".date("Y_m_d").".txt","a");
            }
            $string = "";
            foreach ($userIds as $userId){
                $string .= $mailerId.",".$mailerName.",".$userId.",".$applicable.",".$reason.",".$MPTCount.",".date("H:i:s")."\n";
            }
            fwrite($file,$string);
            fclose($file);
        }
        catch(Exception $e){
            fclose($file);
        }
    }
}

?>

