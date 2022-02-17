<?php
/**
 * Class file for User Indexing
 */

/**
 * Class for User Indexing
 */
class UserIndexer extends MX_Controller
{
	private $predisLibrary;
	private $solrDocumentsArray= array();
	private $indexLastChunk = true;
	private $maxIdInQueue =0;
    /**
     * Index a single user
     *
     * @param integer $userId
     */
    public function indexUser($userId)
    {
        $this->indexMultipleUsers(array($userId));
    }

    /**
     * Index Queued Users
     */
	public function indexQueuedUsers($forDR="false")
	{
		$this->validateCron();
		ini_set('memory_limit','2048M');

		$this->load->model('user/usermodel');
        $userModel = new UserModel;

        if($forDR == "true"){
        	$startDate = date("Y-m-d H:i:s",strtotime(date()." - ".SOLR_INDEXING_INTERVAL." hours"));
			$users = $userModel->getUsersQueuedForIndexingByStartDate($startDate);
			if(count($users) > 0) {
				$userSet = array_chunk($users, 10);
				foreach ($userSet as $users) {
					$this->indexMultipleUsers($users,true,'studyabroad',false,$forDR);
				}
			}
		}else{
			$maxIdInQueue = $userModel->getMaxIdFromIndexingQueue();
			$users = $userModel->getUsersQueuedForIndexing($maxIdInQueue);

			$this->maxIdInQueue = $maxIdInQueue;
			if(count($users) > 0) {
				$this->indexMultipleUsers($users,true);
				$userModel->setUsersIndexed($users,$maxIdInQueue);
			}
		}
	}

	/*Load redis library */
	private function _loadRedisLib(){
		$this->predisLibrary = PredisLibrary::getInstance();
	}

	public function indexCachedUsers(){
		ini_set('memory_limit','2048M');
		ini_set("max_execution_time",-1);

		$scriptType ='indexingCachedUsers';
		$startTime = date('Y-m-d H:i:s');
		$this->indexLastChunk = false;

		$this->_loadRedisLib();

		$this->predisLibrary->addMembersToHash($scriptType,array('startTime'=>$startTime));

		$userIdMap = $this->predisLibrary->getAllMembersOfHashWithValue('cachedUserIndexingExclusion');
	    $userIdMap = unserialize($userIdMap['userIdMap']);

	    $itr=0;
	    $totalUser = count($userIdMap);

	    $lastUserId = $this->predisLibrary->getAllMembersOfHashWithValue('cachedLastUserId');
	    $lastUserId =  $lastUserId['lastUserId'];


	    foreach ($userIdMap as $userId =>$val) {
	    	if($userId<=$lastUserId){
	    		continue;
	    	}

	    	$userChunk[] = $userId;
	    	$itr++;

	    	if( ($itr%100 == 0) || ($itr==$totalUser) ){
		    	$this->indexMultipleUsers($userChunk,false,'studyabroad',true);
		    	$userChunk = array();
	    	}

	    	$lastUserId = $userId;
	    	$this->predisLibrary->addMembersToHash('cachedLastUserId',array('lastUserId'=>$userId,'counter'=>$itr));

	    }

	    $endTime = date('Y-m-d H:i:s');
	    $this->predisLibrary->addMembersToHash($scriptType,array('startTime'=>$startTime,'endTime' =>$endTime));

	}

	public function indexUserMigrationThread($threadId){
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		ini_set("max_execution_time",-1);

		if($threadId == '' || $threadId ==0 || !isset($threadId)){
			_P('Thread Id has to be greater than 0');
			return;
		}

		$this->indexAllUsers($threadId);
	}

	public function indexAbroadUsers(){
		set_time_limit(0);
		ini_set('memory_limit','2048M');
		ini_set("max_execution_time",-1);

		$threadId = 5;
		$this->indexAllUsers($threadId,'studyabroad');
	}

	public function indexShikshaApplyUsers(){
		$this->validateCron();
		ini_set('memory_limit','1024M');
		$this->load->model('shikshaApplyCRM/shikshaapplymodel');
		$shikshaapplymodel = new shikshaapplymodel();		
		$this->benchmark->mark('shikshaApplyQueryTimeStart');
		$shikshaApplyUser  = $shikshaapplymodel->getAllShikshaApplyUser();
		$this->benchmark->mark('shikshaApplyQueryTimeCompleted');
		$time_taken_explicit = $this->benchmark->elapsed_time('shikshaApplyQueryTimeStart', 'shikshaApplyQueryTimeCompleted');
		error_log("Query total time : ".$time_taken_explicit);

		$num = count($shikshaApplyUser);
		error_log("Total number of rmc live users : ".$num);
		for($i=0;$i<$num;$i+=500) {			
			$this->benchmark->mark('chunkstarttime');
			$chunk          = array_slice($shikshaApplyUser,$i,500);
			$userIds        = array_map(function($a){return $a['user_id'];}, $chunk);	
			error_log("Chunk user processing :".count($userIds));
			$this->indexMultipleUsers($userIds);
			$this->benchmark->mark('chunkendtime');
			$chunk_time_taken_explicit = $this->benchmark->elapsed_time('chunkstarttime', 'chunkendtime');
			error_log("Chunk Indexing time :".$chunk_time_taken_explicit);
			$done           += count($chunk);
			error_log("SHIKSHA APPLY INDEXING CRON: User indexed in solr: ".$done);			
		}
		// $userIds           = array_map(function($a){return $a['user_id'];}, $shikshaApplyUser);
		// $userIdsChunkArray = array_chunk($userIds, 100);
		// foreach ($userIdsChunkArray as $key => $userIdsChunk) {			
		// 	$this->indexMultipleUsers($userIdsChunk);
		// }
	}

	public function indexTestPrepUsers(){
		set_time_limit(0);
		ini_set('memory_limit','2048M');
		ini_set("max_execution_time",-1);

		$threadId = 4;
		$this->indexAllUsers($threadId,'testprep');
	}

	/**
	 * Index all LDB Users
	 */
	private function indexAllUsers($threadId,$extraflag = 'national')
	{
		$this->indexLastChunk = false;
		$this->_loadRedisLib();

		$twentyLakh = 2500000;			//chunk size 20 lac

		$lowerLimit = ($threadId-1)*$twentyLakh ;
		$upperLimit = ($threadId-1)*$twentyLakh + $twentyLakh;
		$isMigration = true;


		$data = $this->predisLibrary->getAllMembersOfHashWithValue('allUserIndexing'.$threadId);
		if($data['userId']>0){
			$lowerLimit =$data['userId'];
		}else if($extraflag == 'studyabroad' || $extraflag == 'testprep'){
			$lowerLimit =0;
		}


		unset($data);

		$startTime = date('Y-m-d H:i:s');
		error_log(date('Y-m-d H:i:s').'################indexing cron starts for thread '.$threadId. ' and ExtraFlag - '.$extraflag);

		$this->dbLibObj = DbLibCommon::getInstance('User');
		$dbHandle = $this->_loadDatabaseHandle();

		$lastUserId = 7000000;			// its value will be last userId in table tUser

		$i = 0;					//its value to be replace by old $lastUserId in second run
		$scriptType = 'allUserIndexing'.$threadId;

		$whereClause ='';
		if($extraflag == 'national'){
			$whereClause = 'pref.extraflag is null and ';
		}

		if($extraflag == 'studyabroad'){

			$upperLimit = $lastUserId;
			$whereClause = 'pref.extraflag = "studyabroad" and';
		}

		if($extraflag == 'testprep'){

			$upperLimit = $lastUserId;
			$whereClause = 'pref.extraflag ="testprep" and';
		}

		while($i <= $lastUserId) {
			//$sql = "SELECT userid FROM tuser WHERE userid < $lastUserId and userid> $lowerLimit and userid <$upperLimit LIMIT $i,1000";

			$sql = "SELECT userid FROM tUserPref pref WHERE $whereClause  PrefId < $lastUserId and PrefId> $lowerLimit and PrefId <$upperLimit LIMIT $i,1000";

			$query = $dbHandle->query($sql);
			$results = $query->result_array();

			$userIds = array();
			foreach($results as $result) {
				$userIds[] = $result['userid'];
			}

			if( ($threadId == 4 || $threadId == 5 || $threadId == 3) && count($userIds) ==0 ){
				break;
			}

			if(count($userIds)) {
				$this->indexMultipleUsers($userIds,$isMigration,$extraflag);
				error_log(date('Y-m-d H:i:s')." ###################  UserIndexingDone::".$i.' for thread '.$threadId);
			}

			$i += 1000;

			$totalCountCache = $i + $lowerLimit;

			$this->predisLibrary->addMembersToHash($scriptType,array('userId'=>$totalCountCache,'startTime'=>$startTime));

			if($totalCountCache > $upperLimit){
				break;
			}
		}

		$endTime = date('Y-m-d H:i:s');
		$this->predisLibrary->addMembersToHash('allUserIndexingTime'.$threadId,array('startTime'=>$startTime,'endTime'=>$endTime));


	}


	/**
	 * Index all Registered Users
	 */
	public function indexAllRegisteredUsers()
	{
	    set_time_limit(0);
	    ini_set('memory_limit','4096M');

	    $this->dbLibObj = DbLibCommon::getInstance('User');
	    $dbHandle = $this->_loadDatabaseHandle();

	    $i = 1;
	    while($i <= 4145771) {
		/*$sql = "SELECT userId
			FROM (
			    SELECT DISTINCT userId
			    FROM tempLMSTable
			    UNION DISTINCT
			    SELECT userId
			    FROM tuserflag
			    WHERE isLDBUser = 'YES'
			) AS A
			WHERE userId > 0
			AND userId < 4000000
			ORDER BY userId DESC
			LIMIT $i , 5000";*/

			$sql = "SELECT userid as userId from tuser LIMIT $i , 5000";

		$query = $dbHandle->query($sql);
		$results = $query->result_array();

		$userIds = array();
		foreach($results as $result) {
			$userIds[] = $result['userId'];
		}

		if(count($userIds)) {
			$this->indexMultipleUsers($userIds,true);
			error_log("UserIndexingDone::".$userIds[count($userIds)-1]);
			mail('aditya.roshan@shiksha.com',"indexing done for","UserIndexingDone::".$userIds[count($userIds)-1]);
		}

		$i += 5000;
	    }
	}

    /**
     * Index Multiple Users
     *
     * @param array $userIds
     */
    function indexMultipleUsers($userIds,$isMigration=false,$extraflag = 'studyabroad',$indexCachedUsers=false, $forDR="false")
	{



        /**
         * Get LDB course mapping with categories and subcategories
         */
        $this->load->model('LDB/ldbmodel');
        $ldbModel = new LdbModel;
        $anaPoints = $ldbModel->getUsersAnAPoints($userIds);

        if($extraflag == 'studyabroad' || $extraflag == 'testprep' ){		//required for study abroad users only, need to be removed once migration is done
	        $LDBCourseToCategoryMapping = $ldbModel->getLDBCourseToCategoryMapping();
	        $LDBCourseToSubCategoryMapping = $ldbModel->getLDBCourseToSubCategoryMapping();
        }

        global $managementStreamMR;
        global $engineeringtStreamMR;

        /**
         * Fetch all data for users
         */
		$this->load->library(array('LDB_Client'));
		$ldbObj = new LDB_Client();

		if(!$isMigration && !$indexCachedUsers){
			$users = json_decode($ldbObj->sgetUserDetails($appID, implode(',',$userIds)), true);
		}else{
			$users = modules::run('LDB/LDB_Server/sgetUserDetails', array(),true,implode(',',$userIds),$extraflag);
			$users = json_decode(base64_decode($users),true);
		}

		$this->load->model('user/usermodel');
        $userModel = new UserModel;
		$usersAdditionalInfo = $userModel->getUsersAdditionalInfo($userIds);

		/*$responseDates = modules::run('lms/lmsServer/getResponseSubmitDate', $userIds);
		$responseCourses = modules::run('lms/lmsServer/getResponseCourses', $userIds);*/

		$this->load->model('LDB/leadsearchmodel');
        $leadSearchModel = new LeadSearchModel;
		$responseLocationAffinity = $leadSearchModel->getResponseLocationsWithAffinity($userIds);

        /**
         * Format data for indexing
         */
        $userSearchDataDenormalized = array();
        $usersToIndex = array_keys($users);

        $usersToExclude = $ldbModel->getExclusionFlag($usersToIndex);

        $abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
        $desiredCoursesList = $abroadCommonLib->getAbroadMainLDBCourses();
        $desiredCoursesList = array_map(function ($a){return $a['SpecializationId'];},$desiredCoursesList);

        $abroadExamsMasterList = $abroadCommonLib->getAbroadExamsMasterList();
        $abroadExamsMasterList = array_map(function($a){ return strtolower($a['exam']);},$abroadExamsMasterList);

        foreach ($usersToExclude as $user) {
        	$finalUsersToExclude[] = $user['userId'];
        }

        //getExcludedUser for migration from cache   -- commented for now, since not being used 
        /*if($isMigration){
	        $userIdMap = $this->predisLibrary->getAllMembersOfHashWithValue('cachedUserIndexingExclusion');
	        $userIdMap = unserialize($userIdMap['userIdMap']);
        }*/

        if($isMigration){
			$this->deleteSolrDocMultiple($userIds);
		}

        $counter = 0;

        $userEmailPreference = $this->getUserEmailPreference($userIds);

		foreach($users as $userId => $userData) {
			$this->benchmark->mark('indexingStarts');

			/*if($userIdMap[$userId]>0 && $isMigration){
				//error_log('############### skipping user indexing migration for userId'.$userId);
				continue;
			}*/

			//error_log('========================== indexing for userId -  -   '.$userId);
			$userSearchData = array();

			$planToStart = $userData['PrefData'][0]['YearOfStart'];

			if($planToStart && $planToStart!= '' && !empty($planToStart)){
				$userSearchData['planToStart'] = $planToStart;
			}

			$userSearchData['displayData'] = json_encode($userData);
			$userSearchData['userId'] = intval($userData['userid']);
			$userSearchData['displayname'] = $userData['displayname'];
			$userSearchData['email'] = $userData['email'];
			$userSearchData['mobile'] = $userData['mobile'];
			$userSearchData['ldbExclusion'] = false;
			$userSearchData['exclusionType'] = '';

			if(in_array($userId, $finalUsersToExclude)){
				$userSearchData['ldbExclusion'] = true;
				$userSearchData['exclusionType'] = $usersToExclude[$userId]['exclusionType'];
			}

			if(intval($userData['city'])) {
				$userSearchData['city'] = intval($userData['city']);
				if($userSearchData['city'] > 20000){
					$userSearchData['city'] = 0;
				}
			}

			if(intval($userData['Locality'])) {
				$userSearchData['locality'] = intval($userData['Locality']);
			}

			if($userData['usercreationDate']) {
				$userSearchData['usercreationDate'] = $this->getSolrDate($userData['usercreationDate']);
			}

			if($userData['lastlogintime']) {
				$userSearchData['lastlogintime'] = $this->getSolrDate($userData['lastlogintime']);
			}

			$userSearchData['usergroup'] = $userData['usergroup'];
			$userSearchData['firstname'] = $userData['firstname'];
			$userSearchData['lastname'] = $userData['lastname'];

			if($userData['experience'] != '' && isset($userData['experience'])){
				$userSearchData['workex'] = intval($userData['experience']);
			}

			if($userData['passport']) {
				$userSearchData['passport'] = $userData['passport'];
			}

			$userSearchData['hardbounce']          = intval($userData['hardbounce']);
			$userSearchData['unsubscribe']         = intval($userData['unsubscribe']);
			$userSearchData['ownershipchallenged'] = intval($userData['ownershipchallenged']);
			$userSearchData['softbounce']          = intval($userData['softbounce']);
			$userSearchData['abused']              = intval($userData['abused']);
			$userSearchData['mobileverified']      = intval($userData['mobileverified']);
			$userSearchData['isNDNC']              = $userData['isNDNC'];
			$userSearchData['isLDBUser']           = $userData['isLDBUser'];
			$userSearchData['isTestUser']          = $userData['isTestUser'];
			$userSearchData['isResponseLead']      = $userData['isResponseLead'];

			if(is_array($userData['PrefData']) && count($userData['PrefData']) > 0) {
				$prefData = array();
				$prefData = $userData['PrefData'][0];

				if($prefData['SubmitDate']) {
					$userSearchData['submitDate'] = $this->getSolrDate($prefData['SubmitDate']);
				}


				$userSearchData['extraFlag'] = $prefData['ExtraFlag'];

                // code to add councelling in-loop students data
                $this->load->library(array('shikshaApplyCRM/shikshaApplyLib'));
                $shikshaApplyLib = new shikshaApplyLib();
                $shikshaApplyData = $shikshaApplyLib->getShikshaApplyUserData($userData['userid']);

                if(!empty($shikshaApplyData) && $shikshaApplyData['inLoop'] == 1) {

                	if(!empty($shikshaApplyData) && $shikshaApplyData['inLoop'] == 1) {
                        $userSearchData['isInCounselingLoop'] = true;
                    }

                    if(isset($shikshaApplyData['stageId']))
                        $userSearchData['rmcStageId'] = $shikshaApplyData['stageId'];

                    if(isset($shikshaApplyData['counsellorId']))
                        $userSearchData['rmcCounsellorId'] = $shikshaApplyData['counsellorId'];

                    if(isset($shikshaApplyData['leadType']))
                        $userSearchData['rmcLeadType'] = $shikshaApplyData['leadType'];

                    if(isset($shikshaApplyData['createdOn']))
                        $userSearchData['rmcCreatedOn'] = $this->getSolrDate($shikshaApplyData['createdOn']);

                    if(isset($shikshaApplyData['modifiedOn']))
                        $userSearchData['rmcModifiedOn'] = $this->getSolrDate($shikshaApplyData['modifiedOn']);

                    if(isset($shikshaApplyData['followupDate']))
                        $userSearchData['rmcFollowupDate'] = $this->getSolrDate($shikshaApplyData['followupDate']);

                    if(isset($shikshaApplyData['contactedStatus']))
                        $userSearchData['rmcContactedStatus'] = $shikshaApplyData['contactedStatus'];

                    if(isset($shikshaApplyData['noteId']))
                        $userSearchData['rmcNoteId'] = $shikshaApplyData['noteId'];

                    if(isset($shikshaApplyData['noteLastModifiedDate']))
                        $userSearchData['rmcNoteLastModifiedDate'] = $this->getSolrDate($shikshaApplyData['noteLastModifiedDate']);
                }

				if($userSearchData['extraFlag'] == 'studyabroad') {
                    global $ABROAD_Exam_Grade_Mapping;
                    $userSearchData['desiredCourse']           = intval($prefData['DesiredCourse']);
                    $userSearchData['abroad_subcat_id']        = intval($prefData['abroad_subcat_id']);
                    if($prefData['TimeOfStart']) {
                        $userSearchData['timeOfStart'] = $this->getSolrDate($prefData['TimeOfStart']);
                    }

                    if($userSearchData['desiredCourse']) {
                        global $studyAbroadPopularCourseToLevelMapping;

                        if(
                        in_array($userSearchData['desiredCourse'],$desiredCoursesList)
                        )
                        {
                            $level =$studyAbroadPopularCourseToLevelMapping[$userSearchData['desiredCourse']];
                        }
                        else
                        {
                            //Fetch Category id and course level through LDB builder and repository
                            $this->load->builder('LDBCourseBuilder','LDB');
                            $ldbBuilder = new LDBCourseBuilder();
                            $ldbRepo = $ldbBuilder->getLDBCourseRepository();
                            unset($ldbBuilder);
                            $ldbCoursesObj = $ldbRepo->find($userSearchData['desiredCourse']);
                            $level= isset($ldbCoursesOb) ? $ldbCoursesObj->getCourseName() : '';
                        }

                        $userSearchData['SACourseLevel'] = $level;
                    }

					$userSearchData['degreePrefAICTE']         = $prefData['DegreePrefAICTE'];
					$userSearchData['degreePrefUGC']           = $prefData['DegreePrefUGC'];
					$userSearchData['degreePrefInternational'] = $prefData['DegreePrefInternational'];
					$userSearchData['degreePrefAny']           = $prefData['DegreePrefAny'];
					$userSearchData['modeOfEducationFullTime'] = $prefData['ModeOfEducationFullTime'];
					$userSearchData['modeOfEducationPartTime'] = $prefData['ModeOfEducationPartTime'];
					$userSearchData['modeOfEducationDistance'] = $prefData['ModeOfEducationDistance'];

					$userSearchData['budget'] = intval($prefData['program_budget']);

					if($userSearchData['desiredCourse']) {
						$userSearchData['desiredSubcatId'] = intval($LDBCourseToSubCategoryMapping[$userSearchData['desiredCourse']]);
						$userSearchData['desiredCatId']    = intval($LDBCourseToCategoryMapping[$userSearchData['desiredCourse']]);
					}

					$specializations = array();
					if(is_array($prefData['SpecializationPref']) && count($prefData['SpecializationPref']) > 0) {
						foreach($prefData['SpecializationPref'] as $specialization) {
							$specializations[] = intval($specialization['SpecializationId']);
						}
					}

					$userSearchData['specializationId'] = $specializations;
				}

				if(is_array($prefData['LocationPref']) && count($prefData['LocationPref']) > 0) {
					foreach($prefData['LocationPref'] as $location) {
						if(intval($location['CountryId']) && !in_array(intval($location['CountryId']),$userSearchData['locationPrefCountryId'])) {
							$userSearchData['locationPrefCountryId'][] = intval($location['CountryId']);
						}
						if(intval($location['StateId']) && !in_array(intval($location['StateId']),$userSearchData['locationPrefStateId'])) {
							$userSearchData['locationPrefStateId'][] = intval($location['StateId']);
						}
						if(intval($location['CityId']) && !in_array(intval($location['CityId']),$userSearchData['locationPrefCityId'])) {
							$userSearchData['locationPrefCityId'][] = intval($location['CityId']);
						}
						if(intval($location['LocalityId']) && !in_array(intval($location['LocalityId']),$userSearchData['locationPrefLocalityId'])) {
							$userSearchData['locationPrefLocalityId'][] = intval($location['LocalityId']);
						}
					}
				}
				unset($prefData);
			}

			$educations = array(); $abroadExamGiven = 'no';
			$educationPercentage=array();
			if(is_array($userData['EducationData']) && count($userData['EducationData']) > 0) {
				foreach($userData['EducationData'] as $education) {
					if(
					    $abroadExamGiven !== 'yes' &&
                        $education['Level'] == 'Competitive exam' &&
                        in_array($education['Name'],$abroadExamsMasterList)
                    ) {
						$abroadExamGiven = 'yes';
					}

                    $eduName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $education['Name']));

					if(
                        $userSearchData['extraFlag'] == 'studyabroad' &&
                        ($eduName == 10 || $eduName == 'graduation')
                    )
                    {
                        if($eduName == 10 && !isset($educationPercentage['graduation']))
                        {
                            if(isset($ABROAD_Exam_Grade_Mapping[$education['board']])) {
                                $percentage = $ABROAD_Exam_Grade_Mapping[$education['board']][floatval($education['Marks'])];
                            }
                            else{
                                $percentage = floatval($education['Marks']);
                            }
                            $educationPercentage[10]=$percentage;
                        }
                        else if ($eduName == 'graduation')
                        {
                            $educationPercentage['graduation']= floatval($education['Marks']);
                        }
                    }

					$userSearchData[$eduName.'_educationLevel'] = $education['Level'];
					$userSearchData[$eduName.'_educationMarks'] = floatval($education['Marks']);
					$userSearchData[$eduName.'_educationMarksType'] = $education['MarksType'];
					if($education['CourseCompletionDate']) {
						$userSearchData[$eduName.'_educationCourseCompletionDate'] = $this->getSolrDate($education['CourseCompletionDate']);
					}

					/**
					 * UG specific fields
					 */
					if($education['Level'] == 'UG') {
						if($education['CourseCompletionDate']) {
							$userSearchData['UGCompletionDate'] = $this->getSolrDate($education['CourseCompletionDate']);
						}

						$userSearchData['UGMarks'] = floatval($education['Marks']);
						$userSearchData['UGStatus'] = intval($education['OngoingCompletedFlag']) ? 'Ongoing' : 'Completed';
					}

					/**
					 * 12th specific fields
					 */
					if($education['Level'] == '12') {
						if($education['CourseCompletionDate']) {
							$userSearchData['XIICompletionDate'] = $this->getSolrDate($education['CourseCompletionDate']);
						}

						$userSearchData['XIIMarks'] = floatval($education['Marks']);
						$userSearchData['XIIStream'] = $education['Name'];
					}

					if($education['Name']){
						$educations[] = $education['Name'];
					}
				}
			}
			if(!empty($educationPercentage))
            {
                $userSearchData['saEducationPercentage'] = isset($educationPercentage['graduation'])?
                    $educationPercentage['graduation']:$educationPercentage[10];
            }

			$userSearchData['educationName'] = $educations;

			/*if(count($responseDates[intval($userData['userid'])])) {
			    foreach($responseDates[intval($userData['userid'])] as $date) {
				$userSearchData['responseSubmitDate'][] = $this->getSolrDate($date);
			    }
			}*/


			$userLocationAffinity = $responseLocationAffinity[intval($userData['userid'])];
			if(is_array($userLocationAffinity) && count($userLocationAffinity) > 0) {
				foreach($userLocationAffinity as $uCityId => $uAffinity) {
					$userSearchData['location_affinity_'.$uCityId] = $uAffinity;
				}
			}

			$userSearchData['user_ana_points'] = intval($anaPoints[$userData['userid']]['userpointvaluebymodule']);
			$userSearchData['user_ana_level'] = intval($anaPoints[$userData['userid']]['levelId']) ? intval($anaPoints[$userData['userid']]['levelId']) : 1;
			$userSearchData['user_name_facet'] = $userData['firstname']." ".$userData['lastname'];
			$userSearchData['user_image'] = $userData['avtarimageurl'];

            if($abroadExamGiven == 'no' && $usersAdditionalInfo[$userId]['bookedExamDate'] == 1) {
                $userSearchData['abroadExamGiven'] = 'booked';
            } else {
                $userSearchData['abroadExamGiven'] = $abroadExamGiven;
            }

			if($userSearchData['extraFlag'] == 'studyabroad') {
				$responseData = $this->getUserResponseData($userId);

				foreach ($responseData as $value) {
					$userSearchData['response_time_'.$value['courseId']] = $this->getSolrDate($value['responseDate']);
					$userSearchData['responseCourse'][] = intval($value['courseId']);
					$userSearchData['responseSubmitDate'][] = $this->getSolrDate($value['responseDate']);
				}
			}

			/*if(count($responseData)) {
			    $userSearchData['responseCourse'] = $responseCourses[intval($userData['userid'])];
			}*/

			$userLocationAffinity = $this->getUserLocationAffinity($userId);

			foreach ($userLocationAffinity as $location) {
				$userSearchData['location_affinity_'.$location['cityId']] = $location['affinity'];
			}

			//index user email preference data
			foreach ($userEmailPreference[$userId] as $email_pref) {
				$userSearchData['email_pref_'.$email_pref] = true;
			}


			$userInterestData = array();
			$userResponseProfile = array();
			if($userSearchData['extraFlag'] != 'studyabroad') {
				$userInterestData = $this->getUserInterestData($userId,false);

				$userResponseProfile = $this->getUserResponseProfile($userId,false);
			}

			$userSearchDataCopy = $userSearchData;

			$this->benchmark->mark('solrDocDelete');

			if(!$isMigration){
				$this->deleteSolrDoc(array('userId'=>$userSearchData['userId']));
			}

			$this->benchmark->mark('solrDocDeleteComplete');
			$time_taken_delete = $this->benchmark->elapsed_time('solrDocDelete', 'solrDocDeleteComplete');


			foreach ($userInterestData as $interestId =>  $interest) {
				unset($userSearchData);
				$userSearchData = $userSearchDataCopy;
				foreach ($interest as $interestKey => $interestValue) {
					$userSearchData[$interestKey] = $interestValue;
				}

				if($interest['streamId'] ==  $managementStreamMR  || $interest['streamId'] == $engineeringtStreamMR){
					$isMRFlag = $this->checkIfMRProfile($interest);
					$userSearchData['isMRPRofile'] = $isMRFlag;
				}

				$userSearchData['DocType'] = 'user';
				$userSearchData['ProfileType'] = 'explicit';

				$this->benchmark->mark('solrDocOneAdd');

				$this->addDocToSolr($userSearchData);

				$this->benchmark->mark('solrDocOneAddComplete');
				$time_taken_explicit = $this->benchmark->elapsed_time('solrDocOneAdd', 'solrDocOneAddComplete');

				/*error_log(' SOLR time taken for explicit profile user id: '.$userId.' in secs -> '.$time_taken_explicit);
				echo ' SOLR explicit profile user id: '.$userId.' in secs -> '.$time_taken_explicit;*/

			}

			foreach ($userResponseProfile as $interest) {
				unset($userSearchData);
				$userSearchData = $userSearchDataCopy;
				foreach ($interest as $interestKey => $interestValue) {
					$userSearchData[$interestKey] = $interestValue;
				}

				if($interest['streamId'] ==  $managementStreamMR || $interest['streamId'] == $engineeringtStreamMR){
					$isMRFlag = $this->checkIfMRProfile($interest);
					$userSearchData['isMRPRofile'] = $isMRFlag;
				}

				$userSearchData['DocType'] = 'user';
				$userSearchData['ProfileType'] = 'implicit';

				$this->benchmark->mark('solrDocOneImplicit');

				$this->addDocToSolr($userSearchData);

				$this->benchmark->mark('solrDocOneAddCompleteImplicit');

				$time_taken_implicit = $this->benchmark->elapsed_time('solrDocOneImplicit', 'solrDocOneAddCompleteImplicit');

				/*error_log(' SOLR time taken for implicit profile user id: '.$userId.' in secs -> '.$time_taken_implicit);
				echo ' SOLR implicit profile user id: '.$userId.' in secs -> '.$time_taken_implicit;*/

			}

			if( (empty($userInterestData) || !isset($userInterestData)) && empty($userResponseProfile) ){
				$docId = $userId;
				$userSearchData['DocType'] = 'user';

				$this->benchmark->mark('solrDocAbroad');

				$this->addDocToSolr($userSearchData);

				$this->benchmark->mark('solrDocAbroadComplete');

				$time_taken_abroad = $this->benchmark->elapsed_time('solrDocAbroad', 'solrDocAbroadComplete');

			/*	error_log(' SOLR time taken for abroad user id: '.$userId.' in secs -> '.$time_taken_abroad);
				echo '<br> SOLR abroad user id: '.$userId.' in secs -> '.$time_taken_abroad;*/

				unset($userInterestData);
				unset($userResponseProfile);
			}

			$this->benchmark->mark('indexingEnds');
			$time_taken = $this->benchmark->elapsed_time('indexingStarts', 'indexingEnds');

		/*	error_log(' ############ time taken for user id: '.$userId.' in secs -'.$time_taken);
			echo '<br> ############ user id: '.$userId.' in secs -> '.$time_taken;
*/
			if($forDR != "true"){
				$userModel->setUsersIndexed(array($userId),$this->maxIdInQueue);
			}
		}

		if(!$this->indexLastChunk){
			$this->addSolrMigrationData();
		}
	}

    /**
     * Function to get the SOLR date
     *
     * @param string date
     */
    function getSolrDate($date)
	{
		$dateParts = explode(' ',$date);
		return $dateParts[0].'T'.$dateParts[1].'Z';
	}


	function indexAllSearchAgents(){
		ini_set('memory_limit','2048M');
		$this->indexLastChunk = false;

		$this->load->model('searchAgents/search_agent_main_model');
        $saModel = new search_agent_main_model;

        $searchAgents = $saModel->getAllLiveSearchAgent();

        $itr =0;

       	foreach ($searchAgents as $value) {
       		$itr++;
       		$this->indexSearchAgents($value['searchAgentId']);

       		if($itr%1000 == 0){
       			$this->addSolrMigrationData();
       		}

       		error_log('=================== itr '.$itr);
       	}

       	$this->addSolrMigrationData();

       	error_log('=================== itr -DONE');

	}

	function indexSearchAgents($searchAgentId){

		$saSolrData = array();

		$this->load->model('searchAgents/search_agent_main_model');
        $saModel = new search_agent_main_model;

        $basicData = $saModel ->getSearchAgentDetails($searchAgentId);
        $criteria = $saModel ->getSearchAgentCriteria($searchAgentId);
        $rangeCriteria = $saModel ->getSearchAgentWorkEx($searchAgentId);
        $includeActiveUser = $saModel ->getIncludeActiveUserFlag($searchAgentId);
        $SAExamName = $saModel ->getExamCriteria($searchAgentId);
        $SALocationData = $saModel ->getLocationCriteria($searchAgentId);

        if(count($SALocationData)>0){
        	foreach ($SALocationData as $value) {
        		$saSolrData['SAPrefCountry'][] = $value['country'];
        	}
        }


        $saSolrData['includeActiveUsers'] = $includeActiveUser;

        if (count($SAExamName)) {
        	foreach ($SAExamName as $value) {
        		$saExamName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $value['examName']));
        		$saSolrData['educationName'][] = $value['examName'];
        		$saSolrData[$saExamName.'_SAEducationMinMarks'] = $value['minScore'];
        		$saSolrData[$saExamName.'_SAEducationMaxMarks'] = $value['maxScore'];
        	}
        }

		if(is_array($rangeCriteria) && count($rangeCriteria)>0){
			$saSolrData['minWorkEx'] = intval($rangeCriteria[0]['minWorkEx']);
			$saSolrData['maxWorkEx'] = $rangeCriteria[0]['maxWorkEx'];
		}

		foreach ($basicData as $key => $value) {
		  	$saSolrData[$key] = $value;
		}

		foreach ($criteria as $value) {
			$spezMap[$value['id']] = $value['value'];

			if($value['keyname'] == 'Substreams'){
				$substreams[$value['id']] = $value['value'];
			} else if($value['keyname'] == 'Specializations'){

				if(isset($value['parentId']) && $value['parentId'] !=0){
					$specialization[$value['parentId']][] = $value['value'];
				}else{
					$specialization['unmapped'][] = $value['value'];
					$substreams['unmapped'] = 'unmapped';
				}
			} else {
				$saSolrData[$value['keyname']][] = $value['value'];
			}

		}

		$saSolrData['streamId'] = $saSolrData['Stream'];
		if(empty($saSolrData['streamId']) || $saSolrData['streamId'] ==''){
			unset($saSolrData['streamId']);
		}

		if(is_array($saSolrData['Courses']) && count($saSolrData['Courses']) >0){
			$saSolrData['courseId'] = $saSolrData['Courses'];
			$saSolrData['courseId'] = array_unique($saSolrData['courseId']);
		}

		if(is_array($saSolrData['Mode_Key']) && count($saSolrData['Mode_Key']) >0){
			$saSolrData['attributeIds'] = $saSolrData['Mode_Key'];
		}

		if(is_array($saSolrData['Mode_Value']) && count($saSolrData['Mode_Value']) >0){
			$saSolrData['attributeValues'] = $saSolrData['Mode_Value'];
		}

		unset($saSolrData['Stream']);
		unset($saSolrData['Courses']);
		unset($saSolrData['Mode_Key']);
		unset($saSolrData['Mode_Value']);

		unset($saSolrData['Gender']);
		unset($saSolrData['XIIStream']);
		unset($saSolrData['Budget']);



		if($saSolrData['mrlocation']){
			$saSolrData['SAPreferedMRCity'] = $saSolrData['mrlocation'];
		}

		if($saSolrData['currentlocation']){
			$saSolrData['SAcurrentlocation'] = $saSolrData['currentlocation'];
		}

		if($saSolrData['Specialization']){
			$saSolrData['specialization'] = $saSolrData['Specialization'];
			$saSolrData['specialization'] = array_unique($saSolrData['specialization']);
		}

		if($saSolrData['Passport']){
			$saSolrData['passport'] = $saSolrData['Passport'];
		}

		if($saSolrData['testprep']){
			$saSolrData['SAtestprep'] = $saSolrData['testprep'];
		}

		if($saSolrData['currentlocality']){
			$saSolrData['SAcurrentlocality'] = $saSolrData['currentlocality'];
		}

		if($saSolrData['Locality']){
			$saSolrData['SAcurrentlocality'] = $saSolrData['Locality'];
		}

		if($saSolrData['PlanToStart']){
			$saSolrData['SAPlanToStart'] = $saSolrData['PlanToStart'];
		}

		if($saSolrData['clientcourse']){
			$saSolrData['SAclientCourse'] = $saSolrData['clientcourse'];
		}

		if($saSolrData['AbroadSpecialization']){
			$saSolrData['SAAbroadSpecialization'] = $saSolrData['AbroadSpecialization'];
		}

		$saSolrData['DocType'] = 'SearchAgent';

		if(count($saSolrData['desiredcourse'])>0){
			$saSolrData['desiredCourse'] = $saSolrData['desiredcourse'];
			unset($saSolrData['desiredcourse']);
		}

		unset($saSolrData['mrlocation']);
		unset($saSolrData['Specialization']);
		unset($saSolrData['currentlocation']);
		unset($saSolrData['clientcourse']);
		unset($saSolrData['testprep']);
		unset($saSolrData['currentlocality']);
		unset($saSolrData['Locality']);
		unset($saSolrData['PlanToStart']);
		unset($saSolrData['Passport']);
		unset($saSolrData['UGCourse']);
		unset($saSolrData['AbroadSpecialization']);

		$this->deleteSolrDoc(array('SearchAgentId'=>$searchAgentId));

		if($saSolrData['SearchAgentId'] <= 0){
			mail('ajay.sharma@shiksha.com', "SA agent id missing", print_r($saSolrData,true));
			return;
		}

		foreach ($substreams as $id => $subStreamId) {

			$saSolrData['subStreamId'] = $subStreamId;

			if($specialization[$id] && $specialization[$id]!= '' && !empty($specialization[$id])){
				$saSolrData['specialization'] = array();
				$saSolrData['specialization'] = $specialization[$id];
			}

			if($id == 'unmapped'){
				unset($saSolrData['subStreamId']);
			}

			$this->addDocToSolr($saSolrData);
			unset($saSolrData['specialization']);
		}

		if(!is_array($substreams) || $substreams ==''){

			unset($saSolrData['subStreamId']);
			$this->addDocToSolr($saSolrData);

		}
	}

	public function addDocToSolr($solrData){

		require_once( APPPATH.'/third_party/Apache/Solr/Service.php' );

		$solr = new Apache_Solr_Service(SOLR_LDB_SEARCH_IP, SOLR_LDB_SEARCH_PORT, SOLR_LDB_SEARCH_SOLR);

		if (!$solr->ping() ) {
			usleep(100000);
			if (!$solr->ping() ) {
				echo 'Solr service not responding.';
				exit;
			}
		}

		//$documents = array();

		$part = new Apache_Solr_Document();

		foreach($solrData as $key => $value) {
			if ( is_array( $value ) ) {
				foreach ( $value as $datum ) {
					$part->setMultiValue( $key, $datum );
				}
			}
			else {
				$part->$key = $value;
			}

		}

		$this->solrDocumentsArray[] = $part;
		if($this->indexLastChunk){
			$documents = $this->solrDocumentsArray;
			$this->solrDocumentsArray = array();
		}else{
			//error_log('~~~~~~~~~~~~~~~~~~~~~ in else');
			return;
		}

		//$documents[] = $part;

		try {
			$solr->addDocuments( $documents );
			//$solr->commit();
		}
		catch ( Exception $e ) {
			mail('ajay.sharma@shiksha.com',"solr error",$e->getMessage());
			echo $e->getMessage();
		}

	}

	public function addSolrMigrationData(){

		require_once( APPPATH.'/third_party/Apache/Solr/Service.php' );

		$solr = new Apache_Solr_Service(SOLR_LDB_SEARCH_IP, SOLR_LDB_SEARCH_PORT, SOLR_LDB_SEARCH_SOLR);

		if (!$solr->ping() ) {
			echo 'Solr service not responding.';
			exit;
		}

		//$documents = array();
		$documents = $this->solrDocumentsArray;


		try {
			$solr->addDocuments( $documents );
			$solr->commit();
			$this->solrDocumentsArray = array();
		}
		catch ( Exception $e ) {
			mail('ajay.sharma@shiksha.com',"solr error",$e->getMessage());
			echo $e->getMessage();
		}

	}

	public function getUserInterestData($userId,$isMigration=false){
		$this->load->model('user/usermodel');
        $userModel = new UserModel;

		$userInterest = $userModel->getUserInterest($userId);
		global $modeAttributes;
		global $managementStreamMR;
		global $engineeringtStreamMR;
		global $mbaBaseCourse;
		global $btechBaseCourse;
		global $fullTimeEdType;


		$solrData = array();

		foreach ($userInterest as $interest) {
			$excludeFTMode = false;
			$excludeMRCourse = false;

			if($interest['streamId'] != '') {
				$solrData[$interest['interestId']]['streamId'] = intval($interest['streamId']);
			}
			if($interest['subStreamId'] != '') {
				$solrData[$interest['interestId']]['subStreamId'] = intval($interest['subStreamId']);
			}
			$solrData[$interest['interestId']]['interestTime'] = $this->getSolrDate($interest['time']);


			$specialization = $userModel->getUserSpecilization($interest['interestId']);

			foreach ($specialization  as  $value) {
				if($value['specializationId'] != '') {
					$specializationId[$value['specializationId']] = true;
				}
				if($value['baseCourseId'] != '') {
					$courseId[$value['baseCourseId']] = true;
				}
			}

			unset($specialization);


			if(count($courseId) >1 && ( $courseId[$mbaBaseCourse]  || $courseId[$btechBaseCourse]) ){
				$excludeMRCourse = true;
			}

			$specializationId = array_keys($specializationId);
			$courseId = array_keys($courseId);
			$credential = array_keys($credential);
			$level = array_keys($level);

			if(count($courseId) == 1 && ($courseId[0]== $mbaBaseCourse || $courseId[0]== $btechBaseCourse) ){
				$excludeFTMode = true;
			}

 			if(is_array($specializationId)) {
				$solrData[$interest['interestId']]['specialization'] = $specializationId;
			}

			if(is_array($courseId)) {
				$solrData[$interest['interestId']]['courseId'] = $courseId;
			}

			$attributes = $userModel->getUserAttributes($interest['interestId']);

			if($excludeMRCourse && count($attributes) == 1 && $attributes[0]['attributeValue'] == $fullTimeEdType) {
				$solrData[$interest['interestId']]['isMRCourseExclusion'] = true;
			}

			if(count($attributes) == 1 ) {
				$excludeFTMode = false;
			}

			foreach ($attributes as  $value) {
				if(in_array($value['attributeKey'], $modeAttributes)) {
					$solrData[$interest['interestId']]['attributeIds'][] = $value['attributeKey'];
					if($value['attributeValue']>0){
						$solrData[$interest['interestId']]['attributeValues'][] = $value['attributeValue'];

						if($excludeFTMode && $value['attributeValue'] == $fullTimeEdType ){
							$solrData[$interest['interestId']]['isFTExclusion']= true;
						}
					}
				}
			}

			$modeArray = $solrData[$interest['interestId']]['attributeValues'];
			$creditDetailsArray = Modules::run('enterprise/enterpriseSearch/getHigherPricedProfile', intval($interest['streamId']), $courseId, $modeArray,$isMigration);

			$solrData[$interest['interestId']]['ViewCredit'] = $creditDetailsArray['creditToDeduct']['ViewCredit'];
			$solrData[$interest['interestId']]['SmsCredit'] = $creditDetailsArray['creditToDeduct']['SmsCredit'];
			$solrData[$interest['interestId']]['EmailCredit'] = $creditDetailsArray['creditToDeduct']['EmailCredit'];
			$solrData[$interest['interestId']]['SMSCount'] = $creditDetailsArray['creditToDeduct']['SMSCount'];
			$solrData[$interest['interestId']]['EmailCount'] = $creditDetailsArray['creditToDeduct']['EmailCount'];
			$solrData[$interest['interestId']]['ViewCount'] = $creditDetailsArray['creditToDeduct']['ViewCount'];

			unset($specializationId);
			unset($courseId);
			unset($credential);
			unset($level);
			unset($attributes);
			unset($creditDetailsArray);

		}

		return $solrData;

	}


	function getUserResponseProfile($userId,$isMigration){

		$finalProfile = array();

		$this->load->model('user/usermodel');
        $userModel = new UserModel;

        global $highProfileResponseConfig;
        global $managementStreamMR;
		global $engineeringtStreamMR;
		global $mbaBaseCourse;
		global $btechBaseCourse;
		global $fullTimeEdType;



        $userDistinctProfileCount = $userModel->getUserDistinctResponseProfile($userId);
        $userProfile = $userModel->getUserResponseProfile($userId);

        foreach ($userProfile as $profiles) {
        	$mergedData[$profiles['stream_id']][$profiles['substream_id']][] =$profiles;
        }

        unset($userProfile);

        foreach ($userDistinctProfileCount as $distinctProfile) {

        	$userProfile = array();
        	$userProfile = $mergedData[$distinctProfile['stream_id']][$distinctProfile['substream_id']];

        	$mergedProfile = array();
        	foreach ($userProfile as $profile) {
				$excludeFTMode = false;
				$excludeMRCourse = false;

        		$profileArray = json_decode($profile['user_profile'],true);

        			if(count($profileArray['course_id'])>0 && count($mergedProfile['courseId']) == 0 ) {
        				$mergedProfile['courseId'] = array();
        			}

        			if(count($profileArray['mode'])>0 && count($mergedProfile['attributeValues']) ==0 ) {
        				$mergedProfile['attributeValues'] = array();
        			}

        			if(count($profileArray['specialization'])>0 && count($mergedProfile['specialization']) ==0 ) {
        				$mergedProfile['specialization'] = array();
        			}

        			$mergedProfile['courseId'] = array_merge($mergedProfile['courseId'],$profileArray['course_id']);

        			$tempMode = array();

        			foreach ($profileArray['mode'] as $mode) {
        				if($mode>0){
        					$tempMode[] = $mode;
        				}
        			}

        			$profileArray['mode'] = $tempMode;

        			$mergedProfile['attributeValues'] = array_merge($mergedProfile['attributeValues'],$profileArray['mode']);

        			$mergedProfile['specialization'] = array_merge($mergedProfile['specialization'],$profileArray['specialization']);


        			$mergedProfile['streamId'] = intval($distinctProfile['stream_id']);

		        	if($distinctProfile['substream_id']){
		        		$mergedProfile['subStreamId'] = intval($distinctProfile['substream_id']);
		        	}

		        	$mergedProfile['affinity'] = intval($distinctProfile['affinity']);

		        	/*//$creditDetailsArray = Modules::run('enterprise/enterpriseSearch/getHigherPricedProfile', intval($mergedProfile['streamId']), $mergedProfile['courseId'], $mergedProfile['attributeValues'],$this->cache);

					$mergedProfile['ViewCredit'] = intval($creditDetailsArray['creditToDeduct']['ViewCredit']);
					$mergedProfile['SmsCredit'] = intval($creditDetailsArray['creditToDeduct']['SmsCredit']);
					$mergedProfile['EmailCredit'] = intval($creditDetailsArray['creditToDeduct']['EmailCredit']);
					$mergedProfile['SMSCount'] = intval($creditDetailsArray['creditToDeduct']['SMSCount']);
					$mergedProfile['EmailCount'] = intval($creditDetailsArray['creditToDeduct']['EmailCount']);
					$mergedProfile['ViewCount'] = intval($creditDetailsArray['creditToDeduct']['ViewCount']);*/

					if($profile['listing_type'] != 'exam'){
						$mergedProfile['response_time_'.$profile['listing_id']] = $this->getSolrDate($profile['submit_date']);
						$mergedProfile['responseCourse'][] = intval($profile['listing_id']);

						if(in_array($profile['action_type'], $highProfileResponseConfig)){
							$mergedProfile['highProfileResponse'][] = intval($profile['listing_id']);

							if(empty($mergedProfile['highProfileResponseTime']) || $mergedProfile['highProfileResponseTime'] < $profile['submit_date']){

								$mergedProfile['highProfileResponseTime'] = $profile['submit_date'];
							}

						}

						$mergedProfile['responseSubmitDate'][] = $this->getSolrDate($profile['submit_date']);
					}


					$mergedProfile['interestTime'] = $this->getSolrDate($profile['submit_date']);
        	}

			$mergedProfile['specialization'] = array_unique($mergedProfile['specialization']);
			$mergedProfile['attributeValues'] = array_unique($mergedProfile['attributeValues']);
			$mergedProfile['courseId'] = array_unique($mergedProfile['courseId']);

			if(count($mergedProfile['courseId']) >1 && (in_array($mbaBaseCourse,$mergedProfile['courseId']) || in_array($btechBaseCourse,$mergedProfile['courseId'])) ){
				$excludeMRCourse = true;
			}

			if($excludeMRCourse && count($mergedProfile['attributeValues']) == 1 && $mergedProfile['attributeValues'][0] == $fullTimeEdType){
				$mergedProfile['isMRCourseExclusion'] = true;
			}

			if(count($mergedProfile['courseId']) == 1 && ($mergedProfile['courseId'][0]== $mbaBaseCourse || $mergedProfile['courseId'][0]== $btechBaseCourse) ){
				$excludeFTMode = true;
			}

			if($excludeFTMode && count($mergedProfile['attributeValues']) > 1 ) {
				foreach ($mergedProfile['attributeValues'] as $modeVal) {
					if($modeVal == $fullTimeEdType){
						$mergedProfile['isFTExclusion'] = true;
					}
				}

			}

			if(!empty($mergedProfile['highProfileResponseTime'])) {
				$mergedProfile['highProfileResponseTime'] = $this->getSolrDate($mergedProfile['highProfileResponseTime']);
			}

			if(empty($mergedProfile['courseId'])){
        		unset($mergedProfile['courseId']);
			}

			if(empty($mergedProfile['attributeValues']) || count($mergedProfile['attributeValues'])==0){
        		unset($mergedProfile['attributeValues']);
        	}

        	if(empty($mergedProfile['specialization'])){
				unset($mergedProfile['specialization']);
			}


			$creditDetailsArray = Modules::run('enterprise/enterpriseSearch/getHigherPricedProfile', intval($mergedProfile['streamId']), $mergedProfile['courseId'], $mergedProfile['attributeValues'],$isMigration);

			$mergedProfile['ViewCredit'] = intval($creditDetailsArray['creditToDeduct']['ViewCredit']);
			$mergedProfile['SmsCredit'] = intval($creditDetailsArray['creditToDeduct']['SmsCredit']);
			$mergedProfile['EmailCredit'] = intval($creditDetailsArray['creditToDeduct']['EmailCredit']);
			$mergedProfile['SMSCount'] = intval($creditDetailsArray['creditToDeduct']['SMSCount']);
			$mergedProfile['EmailCount'] = intval($creditDetailsArray['creditToDeduct']['EmailCount']);
			$mergedProfile['ViewCount'] = intval($creditDetailsArray['creditToDeduct']['ViewCount']);

			$finalProfile[] = $mergedProfile;

        }

     return $finalProfile;
	}

	public function getUserLocationAffinity($userId){
		if(empty($userId)){
			return array();
		}

		$this->load->model('user/usermodel');
        $userModel = new UserModel;

        $userLocationAffinity = $userModel->getUserLocationAffinity($userId);

        return $userLocationAffinity;
	}

	public function getUserResponseData($userId){
		if(empty($userId)){
			return array();
		}

		$this->load->model('user/usermodel');
        $userModel = new UserModel;

        $userResponseData = $userModel->getUserResponseData($userId);

        return $userResponseData;
	}

	public function checkIfMRProfile($userProfile){
		global $managementStreamMR;
		global $engineeringtStreamMR;
		global $mbaBaseCourse;
		global $btechBaseCourse;
		global $fullTimeEdType;

		$isMRFlag= false;

		if($userProfile['streamId'] == $managementStreamMR && in_array($fullTimeEdType, $userProfile['attributeValues']) && count($userProfile['attributeValues']) == 1 ){

			if(in_array($mbaBaseCourse,$userProfile['courseId']) && count($userProfile['courseId']) ==1 ){
				$isMRFlag = true;
			}
		}

		if($isMRFlag){
			return $isMRFlag;
		}

		if($userProfile['streamId'] == $engineeringtStreamMR && in_array($fullTimeEdType, $userProfile['attributeValues']) && count($userProfile['attributeValues']) == 1 ){

			if(in_array($btechBaseCourse,$userProfile['courseId']) && count($userProfile['courseId']) ==1 ){
				$isMRFlag = true;
			}
		}


		return $isMRFlag;
	}

	/**
     * Index Queued Users
     */
	public function indexQueuedSearchAgent($forDR="false")
	{
		$this->validateCron();
		$this->load->model('searchAgents/search_agent_main_model');
        $saModel = new Search_agent_main_model;

        if($forDR == "true"){
        	$startDate = date("Y-m-d H:i:s",strtotime(date()." - ".SOLR_INDEXING_INTERVAL." hours"));
			$searchAgents = $saModel->getSAQueuedForIndexingByStartDate($startDate);

			if(count($searchAgents) > 0) {
				$this->indexMultipleSearchAgent($searchAgents);
			}

        }else{
        	$maxIdInQueue = $saModel->getMaxIdFromIndexingQueue();
			$searchAgents = $saModel->getSAQueuedForIndexing($maxIdInQueue);

			if(count($searchAgents) > 0) {
				$this->indexMultipleSearchAgent($searchAgents);
				$saModel->setSearchAgentIndexed($searchAgents,$maxIdInQueue);
			}
        }
	}

	public function indexMultipleSearchAgent($searchAgents){
		foreach ($searchAgents as $agentId) {
			$this->indexSearchAgents($agentId);
		}
	}

	public function deleteSolrDoc($keyPairArray){
		foreach ($keyPairArray as $key => $value) {
			$query = $key.':'.$value;
		}

		$this->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();

		$deleteCommand = SOLR_LDB_SEARCH_UPDATE_URL_BASE;

		//$deleteCommand .="?stream.body=%3Cdelete%3E%3Cquery%3E".$query."%3C/query%3E%3C/delete%3E&commit=true";
		$deleteCommand .="?stream.body=%3Cdelete%3E%3Cquery%3E".$query."%3C/query%3E%3C/delete%3E";

		$request_array = explode("?",$deleteCommand);
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]);
	}

	public function indexDeltaUsers($lastIndexedId){

		$this->load->model('user/usermodel');
        $userModel = new UserModel;

        $maxIdInQueue = $userModel->getMaxIdFromIndexingQueue();
		$users 		  = $userModel->getDeltaUsersQueuedForIndexing($lastIndexedId, $maxIdInQueue);

		if(count($users) > 0) {
			$this->indexMultipleUsers($users);
			$userModel->setUsersIndexed($users,$maxIdInQueue);
		}

	}

	public function indexDeltaSearchAgents(){

		$lastSAIndexedTime = date('Y-m-d');
		$this->load->model('searchAgents/search_agent_main_model');
        $saModel = new Search_agent_main_model;

		$searchAgents = $saModel->getDeltaSearchAgentsForIndexing($lastSAIndexedTime);

		if(count($searchAgents) > 0) {
			$this->indexMultipleSearchAgent($searchAgents);
		}

	}

	public function optimizeCallToLDBSolr(){
		$this->validateCron();
		require_once( APPPATH.'/third_party/Apache/Solr/Service.php' );

		$solr = new Apache_Solr_Service(SOLR_LDB_SEARCH_IP, SOLR_LDB_SEARCH_PORT, SOLR_LDB_SEARCH_SOLR);
		$solr->optimize();

	}

	public function deleteSolrDocMultiple($userIds){

		$query = implode('%20', $userIds);

		$query = 'userId:('.$query.')';

		$this->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();

		$deleteCommand = SOLR_LDB_SEARCH_UPDATE_URL_BASE;

		$deleteCommand .="?stream.body=%3Cdelete%3E%3Cquery%3E".$query."%3C/query%3E%3C/delete%3E";

		$request_array = explode("?",$deleteCommand);
        $response = $this->solrServer->leadSearchCurl($request_array[0],$request_array[1]);

	}

	public function getUserEmailPreference($userIds){
		$this->load->model('user/usermodel');
        $userModel = new UserModel;

		$userEmailPreference = $userModel->getUserEmailPreference($userIds);

		$formatData = $this->formatUserEmailPreferenceData($userEmailPreference);

		return $formatData;
	}

	public function formatUserEmailPreferenceData($userEmailPreference){
		foreach ($userEmailPreference as $prefData) {
			$formatData[$prefData['user_id']][] = $prefData['unsubscribe_category'];
		}

		return $formatData;
	}
}
