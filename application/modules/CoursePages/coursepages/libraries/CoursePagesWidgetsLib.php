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

class CoursePagesWidgetsLib {

	private $CI;

	function __construct() {
		$this->CI = & get_instance();		
	}
	
	public function processWidgetsList($widgetsList, $isUserLoggedin = FALSE) {
		$widgetsList = $this->_setColumnViewOfWidgetsList($widgetsList);		
		$widgetsList = $this->_applyOrderingToWidgetsColumnList($widgetsList, $isUserLoggedin);
		return $widgetsList;
	}
	
	private function _setColumnViewOfWidgetsList($widgetsList) {
		$arrangedList = array();
		foreach($widgetsList as $key => $widgetObj) {
			$arrangedList[$widgetObj->getColumnPosition()][] = $widgetObj;			
		}			
		
		ksort($arrangedList);
		return $arrangedList;
	}
	
	private function _applyOrderingToWidgetsColumnList($widgetsList, $isUserLoggedin) {
		$arrangedList = array();		
		foreach($widgetsList as $columnKey => $widgetListArray) {
			foreach($widgetListArray as $key => $widgetObj) {
				$arrangedList[$columnKey][$widgetObj->getdisplayOrder()] = $widgetObj;
			}
			ksort($arrangedList[$columnKey]);
		}
		
		return $arrangedList;
	}
	
	public function removeWidgetIfNoDataExists($widgetsList, $widgetsData) {
		foreach($widgetsList as $columnKey => $widgetListArray) {
			foreach($widgetListArray as $key => $widgetObj) {
				$continueFlag = 0;
				switch($widgetObj->getWidgetKey()) {
					case 'FeaturedInstituteWidget' :

						if((!count($widgetsData['featuredInstitutes']['slides']) && !count($widgetsData['featuredInstitutes']['sections']))) {
							$continueFlag = 1;
						}
						break;
					
					case 'TopQuestionsWidget' :
						if(!count($widgetsData['topQuestions'])) {
							$continueFlag = 1;
						}						
						break;
					
					case 'LatestNewsWidget' :
						if(!count($widgetsData['latestNews'])) {
							$continueFlag = 1;
						}						
						break;
					
					case 'TopDiscussionsWidget' :						
						if(!count($widgetsData['topDiscussions'])) {							
							$continueFlag = 1;
						}						
						break;	
						
					case 'FaqWidget' :						
						if(!count($widgetsData['faqQuestions'])) {							
							$continueFlag = 1;
						}						
						break;
					case 'RegistrationWidget' :
						if(isset($widgets_data['registrationWidget']) && $widgets_data['registrationWidget'][0] != "YES") {
							$continueFlag = 1;
						}
						else if(!empty($widgetsData['recommendationsWidget']) && $widgetsData['recommendationsWidget']['recommendationsExist'] == 0) {
							$continueFlag = 1;
						}
						break;
                                        case 'predictorWidget' : 
                                            if(count($widgetsData['predictorWidget'])==0){
                                                $continueFlag=1;
                                            }
						break;
          case 'CollegeReviewWidget':
               if(count($widgetsData['CollegeReviewWidget'])==0){
                      $continueFlag=1;
                  }
					break;
					case 'RankPredictorWidget':
						if(count($widgetsData['rankPredictorWidget'])==0){
							$continueFlag = 1;
						}
						break;
				
					case 'PopularEntranceExamWidget':
						if(count($widgetsData['popularEntranceExamWidget'])==0){
							$continueFlag = 1;
						}
						break;
				}
				
				if($continueFlag == 1) {
					continue;	
				}
				
				$arrangedList[$columnKey][$widgetObj->getdisplayOrder()] = $widgetObj;
			}
		}
		
		return $arrangedList;
	}
}
