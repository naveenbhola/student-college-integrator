<?php

include_once('MailerWidgetAbstract.php');

class UserCategoryDetailsWidgetSA extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}
	
	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserDesiredCategoryDetailsSA(implode(',',$userIds));
        return $this->mailerModel->getUserDesiredCategoryDetailsSA($userIds);
	}
}