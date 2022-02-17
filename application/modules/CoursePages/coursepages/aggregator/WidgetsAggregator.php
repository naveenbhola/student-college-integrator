<?php

include_once 'WidgetsAggregatorInterface.php';

class WidgetsAggregator {

	private $_sourceList = array();
	private $_returnData = array();

	function __construct() {
	    $this->CI = & get_instance();
	}

	public function addDataSource(WidgetsAggregatorInterface $object) {

		array_push($this->_sourceList, $object);

	}

	public function getData() {
		
		if(count($this->_sourceList) == 0) {
			return array();
		}

		$this->_splitResources();
		
		return $this->_returnData;
	}

	private function _splitResources() {

		foreach ($this->_sourceList as $sourceObject) {
			$this->CI->benchmark->mark('Get_'.get_class($sourceObject).'_data_start');
			$data = $sourceObject->getWidgetData();
			$this->_aggregateData($data);	
			$this->CI->benchmark->mark('Get_'.get_class($sourceObject).'_data_end');
		}
	}

	private function _aggregateData($data) {
		
		$this->_returnData[$data['key']] = $data['data'];
	}
}