<?php 

class OAFPortingRepository{
	private $CI;
	private $model;

	function __construct($model)
	{
		$this->CI = & get_instance();
		$this->model = $model;
		$this->CI->load->entities(array('oafPorting/OAFPortingEntity'));
	}

	public function getAllLivePortings(){
		$portings = array();
		$portingDataset = $this->model->getAllLivePortings();
		foreach($portingDataset as $portingData) {
			$portings[] = $this->buildPortingObject($portingData);
		}
		return $portings;
	}

	private function buildPortingObject($portingData){
		return new OAFPortingEntity($portingData);
	}

	public function getFormListByClientId($clientId,$validation=false)
	{
		if (empty($clientId))
		{
			return;
		}
		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');
		$formList = $this->model->getFormListByClientId($clientId);
		
		if ($validation){
			return $formList;
		}

		$finalList= array();
		foreach ($formList as $key=>$form)
		{	
			$finalList[$form['formId']]= $form['formName'];
		}
		return $finalList;
	}

	public function getEntityIds($formIds)
	{
		if (empty($formIds))
		{
			return;
		}
		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');
		$entityIdsMapping = $this->model->getEntityIds($formIds);
		
		$entityIds = array();
		$groupIds = array();
		if (empty($entityIdsMapping))
		{
			return ;
		}
		foreach ($entityIdsMapping as $entity){
			if($entity['entitySetType'] == 'field'){
				$entityIds[$entity['entitySetId']] = $entity['entitySetId'];
			}	
			else{	
				$groupIds[$entity['entitySetId']] = $entity['entitySetId'];
			}
		}
		$entityIds = array_keys($entityIds);
		if (!empty($groupIds))
		{
			$entityIdsMapping = $this->model->getGroupEntityIds(array_keys($groupIds));
			//$groupEntities = array_column($entityIdsMapping,'fieldId');
			$finalEntity = array();
			foreach ($entityIdsMapping as $key =>$entity)
			{
				$finalEntity[] = $entity['fieldId'];
			}
			$entityIds = array_merge($finalEntity,$entityIds);
		}

		return $entityIds;
	}
	
	public function getMappingByFieldIds($fieldIds)
	{
		if (empty($fieldIds))
		{
			return;
		}

		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');
		$fieldName = $this->model->getMappingByFieldIds($fieldIds);
		return $fieldName;
	}

	public function getFieldMapping($formIds)
	{
		$fieldIds = $this->getEntityIds($formIds);
		if (empty($fieldIds))
		{
			return array();
		}
		$fieldMapping = $this->getMappingByFieldIds($fieldIds);		
		return $fieldMapping;
	}

	public function addPorting($portingData)
	{
		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');

		$portingData['porting_data'] = 'oaf';
		
		if($portingData['dataEncode']==null)
		{
			$portingData['dataEncode']='no';
		}
		else
		{
			$portingData['dataEncode']='yes';
		}
		$portingData['xmlFormat'] = htmlspecialchars_decode($portingData['xmlFormat']);
		$data = array(
			'client_id'           => $portingData['userid'],
			'name'                => $portingData['porting_name'],
			'type'                => $portingData['porting_data'],
			'request_type'        => $portingData['porting_method'],
			'api'                 => $portingData['porting_url'],
			'data_format'         => $portingData['dataFormatType'],
			'data_key'            => $portingData['jsonDataKey'],
			'xml_format'          =>$portingData['xmlFormat'],
			'dataEncode'          => $portingData['dataEncode'],
		);
	
		if($portingData['dataFormatType'] == 'json' ){
			$data['xml_format'] = $portingData['jsonFormat'];
		}

		
		$portingMasterId = $this->model->insertInPortingMain($data);
		
		$portingConditions = array();
		
		foreach ($portingData['singleForm'] as $key=>$formId)
		{
			$portingConditions[] = array(
								'porting_master_id' => $portingMasterId,
								'key'               => 'oafFormId',
								'value'             => $formId,
								'status'            => 'live'
							);
		}

		$this->model->insertInPortingConditions($portingConditions);
		
		$portingFieldMappings =array();
		foreach($portingData['var_name'] as $key => $clientField) {
			$clientField = trim($clientField);
			if(!empty($clientField)) {
				$portingFieldMappings[] = array(
					'porting_master_id' => $portingMasterId,
					'client_field_name' => $clientField,
					'master_field_id'   => $portingData['var_key'][$key],
					'other_value'       => $portingData['temp_name'][$key],
					'status'            => 'live'
				);
			}
		}
		$this->model->insertInPortingFieldMappings($portingFieldMappings);
		return $portingMasterId;
	}

	public function getOAFPortings($portingIds, $portingMainStatus = array('live')){
		if(empty($portingIds)){
			return array();
		}
		$portings = array();
		$portingMainData = $this->model->getPortingMainData($portingIds, $portingMainStatus);

		$returnData = array();
		foreach ($portingMainData as $row) {
			$returnData[$row['id']] = $row;
		}
		$portingIds = array_keys($returnData);

		$data = $this->model->getPortingConditionsData($portingIds);
		foreach ($data as $row) {
			$returnData[$row['porting_master_id']]['portingCriteria'][] = $row;
		}

		$data = $this->model->getPortingFieldsMappingData($portingIds);
		foreach ($data as $row) {
			$returnData[$row['porting_master_id']]['mappings'][] = $row;
		}
		
		foreach($returnData as $portingId => $portingData) {
			$portings[$portingId] = $this->buildPortingObject($portingData);
		}
		return $portings;
	}

	public function getAllNewPortings(){
		$portingIds = array();
		$portingMainData = $this->model->getAllNewPortings();
		foreach ($portingMainData as $row) {
			$portingIds[] = $row['id'];
		}
		return $this->getOAFPortings($portingIds,array('live'));
	}

	public function getAllTestPortings(){
		$portingIds = array();
		$portingMainData = $this->model->getAllTestPortings();
		foreach ($portingMainData as $row) {
			$portingIds[] = $row['id'];
		}
		return $this->getOAFPortings($portingIds,array('intest'));
	}
	public function getPortingMain($portingId)
	{
		if($portingId < 0 )
		{
			return;
		}
		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');

		return $this->model->getPortingMain($portingId);

	}

	public function updatePorting($portingData)
	{
		
		$CI = &get_instance();
		$this->model = $CI->load->model('oafPorting/oafportingmodel');
		$portingData['porting_data'] = 'oaf';
		if($portingData['dataEncode']==null)
		{
			$portingData['dataEncode']='no';
		}
		else
		{
			$portingData['dataEncode']='yes';
		}
		$portingData['xmlFormat'] = htmlspecialchars_decode($portingData['xmlFormat']);
		$data = array(
			'isrun_firsttime'	  => "no",
			'client_id'           => $portingData['userid'],
			'name'                => $portingData['porting_name'],
			'type'                => $portingData['porting_data'],
			'request_type'        => $portingData['porting_method'],
			'api'                 => $portingData['porting_url'],
			'data_format'         => $portingData['dataFormatType'],
			'xml_format'          =>$portingData['xmlFormat'],
			'dataEncode'          => $portingData['dataEncode'],
			'data_key'			  => $portingData['xmlDataKey']
		);
		

		if($portingData['dataFormatType'] == 'json' ){
			$data['data_key']   = $portingData['jsonDataKey'];
			$data['xml_format'] = $portingData['jsonFormat'];
		}

		
		$portingMasterId = $this->model->updatePortingMain($data,$portingData['porting_id']);
		
	/*
		Update status to deleted of porting main and porting condition table 
	*/	

		$deletePortingData = array('status'=> 'deleted');
		$this->model->updatePortingConditions($deletePortingData,$portingMasterId);
		$this->model->updatePortingFieldMappings($deletePortingData,$portingMasterId);

		$portingConditions = array();
		
		foreach ($portingData['singleForm'] as $key=>$formId)
		{
			$portingConditions[] = array(
								'porting_master_id' => $portingMasterId,
								'key'               => 'oafFormId',
								'value'             => $formId,
								'status'            => 'live'
							);
		}

		$this->model->insertInPortingConditions($portingConditions);
		
		$portingFieldMappings =array();
		foreach($portingData['var_name'] as $key => $clientField) {
			$clientField = trim($clientField);
			if(!empty($clientField)) {
				$portingFieldMappings[] = array(
					'porting_master_id' => $portingMasterId,
					'client_field_name' => $clientField,
					'master_field_id'   => $portingData['var_key'][$key],
					'other_value'       => $portingData['temp_name'][$key],
					'status'            => 'live'
				);
			}
		}
		$this->model->insertInPortingFieldMappings($portingFieldMappings);
		return $portingMasterId;
	}

}

?>
