<?php
/**
 * CoursePagesWidgetsLib Library Class
 *
 *
 * @package     Course Pages
 * @subpackage  Libraries
 * @author      Amit Kuksal
 *
 */

class personalizedMailerWidgetsLib {

	private $CI;

	function __construct() {
		$this->CI = & get_instance();		
	}
	
	public function processWidgetsList($widgetsList) {
		$widgetsList = $this->_setDisplayOrderOfWidgetsList($widgetsList);		
		$widgetsList = $this->_applyOrderingToWidgetsColumnList($widgetsList);
		return $widgetsList;
	}
	
	private function _setDisplayOrderOfWidgetsList($widgetsList) {
		$arrangedList = array();
		foreach($widgetsList as $key => $widgetObj) {
			$arrangedList[$widgetObj->getdisplayOrder()][] = $widgetObj;			
		}			
		
		ksort($arrangedList);
		return $arrangedList;
	}
	
	private function _applyOrderingToWidgetsColumnList($widgetsList) {
		$arrangedList = array();		
		foreach($widgetsList as $displayKey => $widgetListArray) {
			foreach($widgetListArray as $key => $widgetObj) {
				$arrangedList[$displayKey][$widgetObj->getColumnPosition()] = $widgetObj;
			}
			ksort($arrangedList[$displayKey]);
		}
		
		return $arrangedList;
	}
	
}
