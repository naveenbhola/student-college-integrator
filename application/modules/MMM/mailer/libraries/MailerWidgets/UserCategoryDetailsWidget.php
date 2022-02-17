<?php

include_once('MailerWidgetAbstract.php');

class UserCategoryDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserDesiredCategoryDetails(implode(',',$userIds));
        return $this->mailerModel->getUserDesiredCategoryDetails($userIds);
	}
}