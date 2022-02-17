<?php

class MPTController extends MX_Controller
{

	private $mptModel;
	private $mptLib;

	function _init()
	{
		
		$this->load->library(array('MPT/mptlibrary'));
		$this->mptLib = new mptlibrary();

		$this->mptModel = $this->load->model('MPT/mptmodel');
	}

    private function processUserProfileToRedis($other_base_courses){
        $start_date = date('Y-m-d H:i:s',strtotime("-1 min"));
        $new_user_profile = $this->mptLib->getNewAddedUserProfile($start_date, $other_base_courses);
        $this->mptLib->addNewMPTProfileToRedis($new_user_profile);

    }

	public function addNewUserProfileToRedis(){
		$this->validateCron();
		
		$this->_init();
		// $start_date = date('Y-m-d H:i:s',strtotime("-65 min"));
        $startTime  = time();
        $this->load->config('registration/registrationFormConfig');
        $other_base_courses  = $this->config->item('other_base_courses');
        $this->processUserProfileToRedis($other_base_courses);
		$endTime = time();

        $totalTime = $endTime -$startTime;
        echo "New user profile to redis ".$totalTime;
        if($totalTime < 30){
            if($totalTime < 10){
                sleep(30);
            }else if($totalTime >= 10 && $totalTime <= 20){
                sleep(15);
            }else{
                sleep(10);
            }

            $startTime = time();
            $this->processUserProfileToRedis($other_base_courses);
            $endTime = time();
            $totalTime = $endTime -$startTime;
            echo "New user profile to redis ".$totalTime;
        }
	}

	public function migrateUserProfileToRedis(){
		$this->validateCron();

		$itr=1;

		while ($itr<=365) {
			$this->_init();
			$start_date = date('Y-m-d',strtotime("-$itr day"));


			$end_date   = $start_date.' 23:59:59';
			$start_date = $start_date.' 00:00:00';

			$this->load->config('registration/registrationFormConfig');
	        $other_base_courses  = $this->config->item('other_base_courses');


			$new_user_profile = $this->mptLib->getNewAddedUserProfile($start_date, $other_base_courses, $end_date);

			$this->mptLib->addNewMPTProfileToRedis($new_user_profile);


			$itr++;
			error_log('==== writing for day -> '.$itr);

			if($itr%5 == 0){
				error_log('==== sleeping for 5 seconds ');
				sleep(5);
			}
		}

	}


	public function getMPTHtmlForUsers($userIds, $params = array()){
       
		error_log("getting course Ids for Users ".print_r($userIds,true));
		$userMPTCourseMap  = $this->getRandomCourseIdForUsersMPT($userIds, $params);
		error_log("User to Course Id mapping  ".print_r($userMPTCourseMap,true));
		$userMPTHTMLMap = $this->makeHtmlForUsers($userMPTCourseMap, $params);
    
		return $userMPTHTMLMap;
	}	

	public function getRandomCourseIdForUsersMPT($userIds, $params){
		$this->_init();
        if(!$this->mptLib->isMPTTupleRequired($params,$userIds)) {
            return ;
        }
		$userCourseMap = $this->mptLib->getProfileFromRedis($userIds,$params);
		return $userCourseMap;
	}

	public function makeHtmlForUsers($userIdCourseMap=array(), $params = array()){
        $this->_init();

        $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $collegeReviewLib =  new CollegeReviewLib;

        $dataForHtml = array();
        $instituteIds = array();
        $instituteCourseMap = array();
        $courseIds = array();

        foreach ($userIdCourseMap as $userId => $courseArray){
            foreach ($courseArray as $courseId){
                $courseIds[$courseId] = $courseId;
            }
        }
        if(empty($courseIds)){
        	// mail("teamldb@shiksha.com", "courseIds are empty for making MPT Tuple HTML", "user and courseId map <br>".print_r($userIdCourseMap,true));
        	return;
        }

        $this->load->builder("nationalCourse/CourseBuilder");
        $builder = new CourseBuilder();
        $this->courseRepository = $builder->getCourseRepository();

        error_log("getting course cache for course Ids ".print_r($courseIds,true));
        $coursesData = $this->courseRepository->findMultiple($courseIds,array("basic"));
        unset($this->courseRepository);

        foreach ($coursesData as $key => $courseData){
            if(!empty($courseData)){
                $instituteIds[] = $courseData->getInstituteId();
                $courseId = $courseData->getId();
                $instituteCourseMap[$courseId] = $courseData->getInstituteId();
                $dataForHtml[$courseId]['instituteName'] = $courseData->getInstituteName();
                $dataForHtml[$courseId]['courseUrl'] = $courseData->getURL();
            }
            else{
                mail("teamldb@shiksha.com", "course object empty in makeHtmlForUsers fn", "course object empty".print_r($key,true));
            }
        }

        unset($coursesData);

        $ratingsForInstitute = $collegeReviewLib->getAggregateReviewsForListing($instituteIds);

        $institutesWithoutRating = array();

        foreach ($courseIds as $courseId){
            $dataForHtml[$courseId]['rating'] = $ratingsForInstitute[$instituteCourseMap[$courseId]]['aggregateRating']['averageRating'];

            if(empty($ratingsForInstitute[$instituteCourseMap[$courseId]]['aggregateRating']['averageRating'])){
                $institutesWithoutRating[] = $courseId;
            }
        }

        if(!empty($institutesWithoutRating)){
            $ratingsForCourses = $collegeReviewLib->getAggregateReviewsForListing($institutesWithoutRating,'course');

            foreach ($ratingsForCourses as $course => $value){
                $dataForHtml[$course]['rating'] = $ratingsForCourses[$course]['aggregateRating']['averageRating'];
            }
        }

        $HTMLDataForUser = array();
        $html = array();
        $html['params'] = $params;
        $userWithNoCourses = array();
        $usersFor1MPT = array();
        $usersFor2MPT = array();
        $usersFor3MPT = array();
        foreach ($userIdCourseMap as $userId => $courseArray){
            if(!empty($courseArray)) {
                $html['courseInfo'] = array();
                $tempCounterForMPT = 0;
                foreach ($courseArray as $courseId){
                    $html['courseInfo'][$courseId] = $dataForHtml[$courseId];
                    $tempCounterForMPT++;
                }

                if($tempCounterForMPT==1){
                    $usersFor1MPT[] = $userId;
                }
                else if($tempCounterForMPT==2){
                    $usersFor2MPT[] = $userId;
                }
                else if($tempCounterForMPT==3){
                    $usersFor3MPT[] = $userId;
                }
                
                $HTMLDataForUser[$userId]  = $this->load->view('MPT/MPTCourses',$html,TRUE);
                unset($html);
            }
            else{
                $userWithNoCourses[] = $userId;
            }     
        }

        if(!empty($userWithNoCourses)){
            $this->mptLib->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$userWithNoCourses,"yes","3","0");
        }

        if(!empty($usersFor1MPT)){
            $this->mptLib->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$usersFor1MPT,"yes","0","1");
        }

        if(!empty($usersFor2MPT)){
            $this->mptLib->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$usersFor2MPT,"yes","0","2");
        }

        if(!empty($usersFor3MPT)){
            $this->mptLib->writeMPTDataInFile($params['mailerDetails']['mailer_id'],$params['mailerDetails']['mailer_name'],$usersFor3MPT,"yes","0","3");
        }

        return $HTMLDataForUser;
    }    

    public function getAggregateDataForMailer(){
        $this->validateCron();
        $this->_init();
        $this->mptLib->getAggregateDataForMailer();
    }

   

}
 
