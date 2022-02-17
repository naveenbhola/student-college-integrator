<?php
include_once('MailerWidgetAbstract.php');
class UserCourseDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserCourseDetails(implode(',',$userIds));
        return $this->mailerModel->getUserCourseDetails($userIds);
	}
}