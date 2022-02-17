<?php 
class specializationmodel extends listingbasemodel{
	
	public function save($dataList,$mode){
		$this->initiateModel();
		$specializationData = $dataList['table'];
		$userId = $dataList['userId'];
		if($mode == 'add'){
			$this->dbHandle->where(array('name'=>$specializationData['name'],'status'=>'live'));
			$data = $this->dbHandle->get('specializations')->row_array();
			if(empty($data)){
				$specializationData['created_on'] = date("Y-m-d H:i:s");
				$specializationData['updated_by'] = $userId;
				$specializationData['created_by'] = $userId;
				$this->dbHandle->insert('specializations',$specializationData);
				return array('data' => array('status'=>'success','message'=>'specialization added successfully','specialization_id'=>$this->dbHandle->insert_id()));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'specialization with that name already exists','specialization_id'=>$data['specialization_id']));
			}
		}
		else if($mode == 'edit'){
			$this->dbHandle->where(array('name'=>$specializationData['name'],'status'=>'live','specialization_id !='=>$specializationData['specialization_id']));
			$data = $this->dbHandle->get('specializations')->row_array();
			if(empty($data)){
				$this->dbHandle->where(array('status'=>'live','specialization_id'=>$specializationData['specialization_id']));
				$specializationData['updated_by'] = $userId;
				$this->dbHandle->update('specializations',$specializationData);
				return array('data'=>array('status'=>'success','message'=>'specialization added successfully','specialization_id'=>$specializationData['specialization_id']));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'specialization with that name already exists','specialization_id'=>$data['specialization_id']));	
			}
		}
	}	
}
?>