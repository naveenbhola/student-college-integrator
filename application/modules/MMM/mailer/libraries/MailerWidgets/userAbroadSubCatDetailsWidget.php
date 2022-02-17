<?php

/*
 *     A InfoEdge Limited Property
 *     --------------------------- 
 */

/**
 * Description of userAbroadSubCatDetailsWidget
 *
 * @author nawal
 */

include_once('MailerWidgetAbstract.php');
class userAbroadSubCatDetailsWidget extends MailerWidgetAbstract
{
	function __construct(MailerModel $mailerModel)
	{
		parent::__construct($mailerModel);
	}

	public function getData($userIds, $params = "")
	{
        //return $this->mailerModel->getUserAbroadSubCatDetails(implode(',',$userIds));
        return $this->mailerModel->getUserAbroadSubCatDetails($userIds);
	}
}
?>
