<?php
class AbroadNotifications extends MX_Controller
{
    private $abroadNotificationsLib;
    public function __construct()
    {
        parent::__construct();
        $this->abroadNotificationsLib = $this->load->library('AbroadNotificationsLib');
    }
    
    /*
	 * mark notifications as 'viewed' by user
	 * @params : single userId
	 */
	public function markNotificationsAsViewed($userId)
	{
        $res = $this->abroadNotificationsLib->markNotificationsAsViewed($userId);
        echo $res;
		return $res;
	}

 	public function getDesktopNotificationOverlay(){
        $displayData['userDetails'] = $this->checkUserValidation();
        if($displayData['userDetails'] === "false"){
            $userId = -1;
        }else{
            $userId = $displayData['userDetails'][0]['userid'];
        }
        $lib = $this->load->library('studyAbroadCommon/AbroadNotificationsLib');
        $displayData['notificationData'] = $lib->getStudyAbroadUserNotification($userId);
		$shortlistListingLib = $this->load->library('listing/ShortlistListingLib' );
		$data = array('userId'=>$userId,'scope'=>'abroad');
		$displayData['shortListedCourses'] = $shortlistListingLib->fetchIfUserHasShortListedCourses($data);
        $userProfilePageLib = $this->load->library('userProfilePage/userProfilePageLib');
        $displayData['profilePageUrl'] = $userProfilePageLib->getUserProfilePageURL($displayData['userDetails'][0]['displayname'], 'viewPage', false);
        $displayData['profilePageUrl'] = json_decode($displayData['profilePageUrl'], true)['url'];
        $displayData['profileImgUrl'] = $userProfilePageLib->prependDomainToUserImageUrl(trim($displayData['userDetails'][0]['avtarurl']));
        $displayData['profileImgUrl'] = getImageUrlBySize($displayData['profileImgUrl'],'medium');

        $rmcLib = $this->load->library('rateMyChances/ShikshaApplyCommonLib');
        $displayData['rmcCount'] = $rmcLib->getRMCCount($userId);

        $userStage = $rmcLib->getUserStage($userId);

        $displayData['isCandidate'] = $rmcLib->checkUserIsCandidate($userId);
        

        echo $this->load->view('studyAbroadCommon/desktopNotificationLayer',$displayData,true);
    }
    
    public function getNotificationCount(){
        $userData = $this->checkUserValidation();
        if($userData === "false"){
            echo "0";
            return;
        }
        $userId = $userData[0]['userid'];
        $lib = $this->load->library('studyAbroadCommon/AbroadNotificationsLib');
        $data = $lib->getStudyAbroadUserNotification($userId);
        echo $data['newNotificationCount'];
    }
}
