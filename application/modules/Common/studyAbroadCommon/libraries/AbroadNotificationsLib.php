<?php
class AbroadNotificationsLib
{
    private $CI;
    private $abroadnotificationsmodel;
    
    // constructor defined here just for the reason to get CI instances for loading different classes in Codeigniter to perform business logic
    public function __construct() {
        $this->CI = & get_instance();
        $this->_setDependencies();
    }
    
    // to get model instance.
    private function _setDependencies(){
        $this->CI->load->model('studyAbroadCommon/abroadnotificationsmodel');
        $this->abroadnotificationsmodel = new abroadnotificationsmodel();
    }
    
	/*
	 * add a notification record to studyAbroadUserNotifications
	 * @params: single record having userId,listingType,listingTypeId,notification,notificationType
	 */
	public function addStudyAbroadUserNotification($data)
	{
		$this->abroadnotificationsmodel->addStudyAbroadUserNotification($data);
	}
	/*
	 * get all notifications for a user
	 * @params : userid of the logged-in user
	 */
    public function getStudyAbroadUserNotification($userId)
    {
        $notifications = $this->abroadnotificationsmodel->getStudyAbroadUserNotification($userId);
        if(count($notifications)>0){
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder = new ListingBuilder;
            $abroadCourseRepository = $listingBuilder->getAbroadCourseRepository();
            $courseIds = array_filter( array_map( function($a){
                    if($a['listingType'] == 'course' && $a['listingTypeId']>0)
                    {
                        return $a['listingTypeId'];
                    }
                }
                    ,$notifications)
            );

            if(count($courseIds)>0)
            {
                $courses = $abroadCourseRepository->findMultiple($courseIds);
            }
            $courses = array_map(function($a){return $a->getId();},$courses);

        }
        $newNotificationCount = 0;
        foreach($notifications as $key=>$notification){
            if($notification['listingType'] == 'course' && $notification['listingTypeId']>0 && !in_array($notification['listingTypeId'],$courses))
            {
                unset($notifications[$key]);
                continue;
            }
            $notifications[$key]['notificationTime'] = $this->stringify($notification['addedOn']);
            if($notifications[$key]['isViewed']!=1)
            {
                $newNotificationCount++;
            }
        }
        $notifications = array('notifications'=>$notifications,
            'newNotificationCount'=>$newNotificationCount);
        return $notifications ;
    }
	
	function stringify($addedTime){
		$elapsedTime = time() - strtotime($addedTime);
		if ($elapsedTime < 1){
			return '0 seconds ago';
		}
		$times = array( 365 * 24 * 60 * 60  =>  'year',
					 30 * 24 * 60 * 60  =>  'month',
						  24 * 60 * 60  =>  'day',
							   60 * 60  =>  'hour',
									60  =>  'minute',
									 1  =>  'second'
					);
		$result = '';
		foreach ($times as $secs => $str){
			$val = $elapsedTime / $secs;
			if ($val >= 1){
				$r = round($val);
				$result =  $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
				break;
			}
		}
		if($result == "1 day ago"){
			$result = "Yesterday";
		}
		return $result;
	} 
	/*
	 * this function reads the course id & other notification data
	 * from Notification cookie so that it can be returned
	 * & then destroys the cookie.
	 */
	public function readRemoveNotificationCookie()
	{
		$cookieVal = $_COOKIE['Notification'];
	    //destroy cookie
		setcookie('Notification', 0 , -1,'/',COOKIEDOMAIN);
	    $_COOKIE['Notification'] = '';
	    return $cookieVal;
	}
	/*
	 * mark notifications as 'viewed' by user
	 */
	public function markNotificationsAsViewed($userId = 0)
	{
		return $this->abroadnotificationsmodel->markNotificationsAsViewed($userId);
	}
}
