<?php
class personalizedMailerModel extends MY_Model
{
	function __construct()
	{
		parent::__construct('User');
		$this->db = $this->getReadHandle();
	}

	function getWidgetListForMailer($mailerId) {
		$sql = "select * from shikshaMailerWidgetsMapping smwm inner join shikshaMailerWidgets smw on smwm.widgetId = smw.widgetId where smwm.mailerId = ? and smwm.status = ? and smw.status = ?";	
		
		$results = $this->db->query($sql, array($mailerId, 'live', 'live'))->result_array();
		return $results;
	}
}
