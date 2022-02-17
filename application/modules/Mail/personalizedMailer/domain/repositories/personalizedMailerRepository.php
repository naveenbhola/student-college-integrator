<?php
class personalizedMailerRepository extends EntityRepository
{
	function __construct($dao)
	{		
		parent::__construct($dao);

		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('personalizedMailerWidgets'),'personalizedMailer');
	}
	
	public function getWidgetListForMailer($mailerId)
	{	
		$widgetsDataResults = $this->dao->getWidgetListForMailer($mailerId);
		$widgets = $this->_load($widgetsDataResults);
		return $widgets;
	}
	
	/*
	 * ORM Functions
	 */ 
	private function _load($results)
	{
	    $widgets = array();
	    
	    if(is_array($results) && count($results)) {
			foreach($results as $widgetKey => $result) {
			    $personalizedMailerWidget = $this->_createWidgetObj($result);
			    $widgets[$widgetKey] = $personalizedMailerWidget;
			}
	    }
	    
	    return $widgets;
	}
	
	private function _createWidgetObj($result)
	{
	    $personalizedMailerWidget = new personalizedMailerWidgets;
	    $this->fillObjectWithData($personalizedMailerWidget,$result);
	    return $personalizedMailerWidget;
	}	
	
}