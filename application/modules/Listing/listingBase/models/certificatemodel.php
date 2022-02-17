<?php 
class certificatemodel extends listingbasemodel{
	
	public function save($dataList,$mode){
		$certificationProviderData = $dataList['table'];
		$userId = $dataList['userId'];
		$this->initiateModel();
		if($mode == 'add'){
			$this->dbHandle->where(array('name'=>$certificationProviderData['name'],'status'=>'live'));
			$data = $this->dbHandle->get('certificate_providers')->row_array();
			if(empty($data)){
				$certificationProviderData['created_on'] = date("Y-m-d H:i:s");
				$certificationProviderData['updated_by'] = $userId;
				$certificationProviderData['created_by'] = $userId;
				$this->dbHandle->insert('certificate_providers',$certificationProviderData);
				$insertedCertificateProviderId = $this->dbHandle->insert_id();				
				return array('data' => array('status'=>'success','message'=>'certification provider added successfully','certificate_provider_id'=>$insertedCertificateProviderId));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'certification provider with that name already exists','certificate_provider_id'=>$data['certificate_provider_id']));
			}
		}else if($mode == 'edit'){
			$this->dbHandle->where(array('name'=>$certificationProviderData['name'],'status'=>'live','certificate_provider_id !='=>$certificationProviderData['certificate_provider_id']));
			$data = $this->dbHandle->get('certificate_providers')->row_array();
			if(empty($data)){
				$certificationProviderData['updated_by'] = $userId;
				$this->dbHandle->where(array('status'=>'live','certificate_provider_id'=>$certificationProviderData['certificate_provider_id']));
				$this->dbHandle->update('certificate_providers',$certificationProviderData);
				return array('data'=>array('status'=>'success','message'=>'certification providers added successfully','certificate_provider_id'=>$certificationProviderData['certificate_provider_id']));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'certification providers with that name already exists','certificate_provider_id'=>$data['certificate_provider_id']));	
			}
		}	
	}

	public function insertCourseMapping($certificateProviderId,$insertData){
		$this->initiateModel();
				
		$this->dbHandle->where(array('entity_id'=>$certificateProviderId,'entity_type'=>'certificate_provider'));
		$this->dbHandle->update('entity_course_mapping',array('status'=>'deleted'));
		$this->dbHandle->insert_batch('entity_course_mapping',$insertData);
		return $this->dbHandle->affected_rows();		
	}
}
?>