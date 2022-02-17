<?php
include_once('MailerWidgetAbstract.php');

class ProfileCompletionWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

    /**
    * API for getting profile completion data
    */
    public function getData($userIds, $params)
	{
		$usersWithIncompleteProfile = $this->mailerModel->getUsersWithIncompleteProfile($userIds);
		
		$desiredCategories = array();
		if($params[0] != 'completeprofile' ){
			//$desiredCategories = $this->mailerModel->getUserDesiredCategoryDetails(implode(',',$userIds));
            $desiredCategories = $this->mailerModel->getUserDesiredCategoryDetails($userIds);
        }

		$widgetData = array();
		
        foreach($userIds as $userId){
            if($usersWithIncompleteProfile[$userId]) {
				$displayData = array();
                $displayData['desiredCategory'] = isset($desiredCategories[$userId]) ? $desiredCategories[$userId]['category'] : NULL;
                $widgetHTML = $this->CI->load->view('MailerWidgets/CompleteYourProfileTemplate',$displayData,true);
                $widgetData[$userId]['completeprofile'] = $widgetHTML;

            }
            else {
                $widgetData[$userId]['completeprofile'] = '';
            }
        }
        return $widgetData;
    }
}