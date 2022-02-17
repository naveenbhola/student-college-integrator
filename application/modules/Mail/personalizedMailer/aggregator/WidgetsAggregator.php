<?php

include_once 'WidgetsAggregatorInterface.php';

class WidgetsAggregator {

	private $_sourceList = array();
	private $_returnData = array();

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
			
			$data = $sourceObject->getWidgetData();
			$this->_aggregateData($data);	
		}
	}

	private function _aggregateData($data) {
		
		$this->_returnData[$data['key']] = $data['data'];
	}
}