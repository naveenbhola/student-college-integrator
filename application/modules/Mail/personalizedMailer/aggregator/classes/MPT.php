<?php

include_once '../WidgetsAggregatorInterface.php';

class MPT implements WidgetsAggregatorInterface{

	private $_params = array();
	
	public function __construct($params) {
		$this->_params = $params;
		$this->_CI = & get_instance();
	}
	
	/**
	* function to get data for MPT Widget
	*/
	public function getWidgetData() {
		$widgetHTML = '';
		$customParams = $this->_params['customParams'];
		if($customParams['include_MPT_Tuple']=='no'){
			return array('key'=>'MPT','data'=>$widgetHTML);
		}
		
		if(empty($customParams['mptHtmlHashkey'])) {
			if(!empty($customParams['userId'])) {
				$userId = array($customParams['userId']);

				try {
					$MPTHTML = Modules::run('MPT/MPTController/getMPTHtmlForUsers',$userId,$customParams);
				} catch(Exception $e) {
					mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com','Exception in getting HTML for MPT tupple (file MPT.php) at '.date('Y-m-d H:i:s'), 'Exception: '.$e->getMessage().'<br/>Chunk:'.print_r($userId, true));
				}	

				$widgetHTML = $MPTHTML[$customParams['userId']];
			}
		} else {
			$widgetHTML = $customParams['mptHtmlHashkey']; // HTML already coming from Reco Mailer
		}
		
		return array('key'=>'MPT','data'=>$widgetHTML);
	}

}
