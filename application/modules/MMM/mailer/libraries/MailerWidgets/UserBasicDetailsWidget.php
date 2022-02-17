<?php
include_once('MailerWidgetAbstract.php');

class UserBasicDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

	public function getData($userIds, $params)
	{
        //return $this->mailerModel->getUserBasicDetails(implode(',',$userIds),implode(',',$params));
        return $this->mailerModel->getUserBasicDetails($userIds,implode(',',$params));
	}
}