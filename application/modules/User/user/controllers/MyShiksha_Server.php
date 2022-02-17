<?php
class MyShiksha_server extends MX_Controller {
	function index() {
		$this->dbLibObj = DbLibCommon::getInstance('User');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		//$this->load->database();
		$config['functions']['getMyShikshaS'] = array('function' => 'MyShiksha_server.getMyShikshaS');
		$config['functions']['updateMyShikshaS'] = array('function' => 'MyShiksha_server.updateMyShikshaS');
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}
	
	
	function getMyShikshaS($request) {
		$parameters = $request->output_parameters();
		$appId  = $parameters['0'];
		$userId  = $parameters['1'];
		$preferences = $this->getMyShikshaPreferences($userId);
		$response = array(
							$preferences, 
							'struct'
						);
		return $this->xmlrpc->send_response($response);
	}

	function updateMyShikshaS($request)	{
		$parameters = $request->output_parameters();
		$appId  = $parameters['0'];
		$id = $parameters['1'];
		$component = $parameters['2'];
		$componentDisplayStatus = $parameters['3'];
		$componentPosition = $parameters['4'];
		$rowsAffected = $this->updateMyShikshaPreferences($id, $component, $componentDisplayStatus, $componentPosition);
		$response = array($rowsAffected);
		return $this->xmlrpc->send_response($response);
	}
	
	private function getMyShikshaPreferences($userId) {
		$dbHandle = $this->_loadDatabaseHandle();
		$resultSet = array();
		$dbHandle->select('*')->from('shiksha_mypage')->where('userId', $userId);
		$query = $dbHandle->get();
		 foreach ($query->result_array() as $row) {
		   array_push($resultSet, array($row,'struct'));
		}
		return $resultSet;
	}

	private function updateMyShikshaPreferences($id, $component, $componentDisplayStatus, $componentPosition) {
		$dbHandle = $this->_loadDatabaseHandle('write');
		$data = array(
		               'display' => $componentDisplayStatus,
		               'position' => $componentPosition
		            );
		$dbHandle->update('shiksha_mypage', $data, array('userid' => $id, 'component' => $component));
		return $dbHandle->affected_rows();
	}

}
?>
