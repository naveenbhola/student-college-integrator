<?php 
class substreammodel extends listingbasemodel{
	
	public function save($dataList,$mode){
		$this->initiateModel();
		$substreamData = $dataList['table'];
		$userId = $dataList['userId'];
		if($mode == 'add'){
			$this->dbHandle->where(array('name'=>$substreamData['name'],'status'=>'live'));
			$data = $this->dbHandle->get('substreams')->row_array();
			if(empty($data)){
				$substreamData['created_on'] = date("Y-m-d H:i:s");
				$substreamData['updated_by'] = $userId;
				$substreamData['created_by'] = $userId;
				$this->dbHandle->insert('substreams',$substreamData);
				return array('data' => array('status'=>'success','message'=>'substream added successfully','substream_id'=>$this->dbHandle->insert_id()));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'substream with that name already exists','substream_id'=>$data['substream_id']));
			}
		}
		else if($mode == 'edit'){
			$this->dbHandle->where(array('name'=>$substreamData['name'],'status'=>'live','substream_id !='=>$substreamData['substream_id']));
			$data = $this->dbHandle->get('substreams')->row_array();
			if(empty($data)){
				$this->dbHandle->where(array('status'=>'live','substream_id'=>$substreamData['substream_id']));
				$substreamData['updated_by'] = $userId;
				$this->dbHandle->update('substreams',$substreamData);
				return array('data'=>array('status'=>'success','message'=>'substream added successfully','substream_id'=>$substreamData['substream_id']));
			}
			else{
				return array('data'=>array('status'=>'fail','message'=>'substream with that name already exists','substream_id'=>$data['substream_id']));	
			}
		}
	}
}
?>