<?php
include_once('MailerWidgetAbstract.php');
class UserCountryDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserDesiredCountryDetails(implode(',',$userIds));
        return $this->mailerModel->getUserDesiredCountryDetails($userIds);
	}
}