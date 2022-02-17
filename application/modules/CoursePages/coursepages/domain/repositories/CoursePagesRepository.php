<?php
class CoursePagesRepository extends EntityRepository
{
	function __construct($dao, $cache)
	{		
		parent::__construct($dao, $cache);

		/*
		 * Load entities required
		 */
		$this->CI->load->entities(array('CoursePagesWidgets'),'coursepages');
	}
	
	public function getWidgetListForCoursePage($courseHomePageId)
	{
		// Check if Subcategory ID is valid or not
		Contract::mustBeNumericValueGreaterThanZero($courseHomePageId,'CourseHomePageId');
		
		if($this->cache->isCPGSCachingOn() && $widgets = $this->cache->getWidgetListForCoursePage($courseHomePageId)) {
			return $widgets;
		}
		
		$widgetsDataResults = $this->dao->getWidgetListForCoursePage($courseHomePageId);
		$widgets = $this->_load($widgetsDataResults);
		$this->cache->storeWidgetListForCoursePage($courseHomePageId, $widgets);		
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
		    $coursePagesWidget = $this->_createWidgetObj($result);
		    $widgets[$widgetKey] = $coursePagesWidget;
		}
	    }
	    
	    return $widgets;
	}
	
	private function _createWidgetObj($result)
	{
	    $coursePagesWidget = new CoursePagesWidgets;
	    $this->fillObjectWithData($coursePagesWidget,$result);
	    return $coursePagesWidget;
	}	
	
}