<?php
include_once('MailerWidgetAbstract.php');
class userAbroadCourseDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserAbroadCourseDetails(implode(',',$userIds));
        return $this->mailerModel->getUserAbroadCourseDetails($userIds);
	}
}
?>