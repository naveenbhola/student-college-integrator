<?php 


class PostInstituteSynonymsModel extends MY_Model{

	function __construct()
    {
		parent::__construct();
		$this->dbHandle=$this->getWriteHandleByModule('Listing');
		$this->QERdbHandle=$this->getWriteHandleByModule('QER');
		//_p($this->QERdbHandle);_p("heyy");die();
    }
    //get the current live entry of the institute
	function getSynonyms($instid) {

		$sql="select * from instituteRelatedKeywords where instituteId= ? and status= ?";
		$row=$this->dbHandle->query($sql,array($instid,'live'))->row_array();

		$sql1="select more_popular_abb from institute where ldb_id = ?";
		$row1=$this->QERdbHandle->query($sql1,array($instid))->row_array();
		
		if(empty($row) && empty($row1)){
			return "no";
		}
		else{
			$row['synonyms']=(isset($row1['more_popular_abb']) && !empty($row1['more_popular_abb']))? trim(trim($row['synonyms']).','.trim($row1['more_popular_abb']),',') : $row['synonyms'];
			$row['synonyms']=implode(',', array_unique(explode(',', $row['synonyms'])));
			return $row;
		}
	}
	//post new entry marking the old entry as history and return ok if transaction success
	function postsyn($data){
		
		$this->dbHandle->trans_begin();

		$arr=array('status'=>'history');
		$this->dbHandle->where('instituteId',$data['instid']);
		$this->dbHandle->update('instituteRelatedKeywords',$arr);

		$arr=array('instituteId'=>$data['instid'],
			'modified_by'=>$data['userid'],
			'modified'=>$data['modified']
			);
		$data['synonyms'] = trim($data['synonyms'],' ,');
		$data['acronyms'] = trim($data['acronyms'],' ,');
		if(!empty($data['synonyms'])){
			$arr['synonyms'] = $data['synonyms'];
		}
		if(!empty($data['acronyms'])){
			$arr['acronyms'] = $data['acronyms'];
		}
		$this->dbHandle->insert('instituteRelatedKeywords',$arr);
		
		if ($this->dbHandle->trans_status() != FALSE) {
			$this->QERdbHandle->trans_start();

			$this->QERdbHandle->where('ldb_id',$data['instid']);
			$query=$this->QERdbHandle->get('institute');
			if($query->num_rows() > 0){
				$arr=array('more_popular_abb'=>implode(',', array_unique(explode(',', trim(trim($data['synonyms'],' ,').','.trim($data['acronyms'],' ,'),' ,')))));
				// _p($arr); die;
				$this->QERdbHandle->where('ldb_id',$data['instid']);
				$this->QERdbHandle->update('institute',$arr);
			}
			else{
				$this->dbHandle->where('institute_id',$data['instid']);
				$this->dbHandle->where('status','live');
				$this->dbHandle->select('institute_name');
				$query=$this->dbHandle->get('institute');

				if($query->num_rows() > 0){
					$row=$query->row_array();
					// (array('ldb_id'=>$data['instid'],'local_full_name'=>$row['institute_name'],'ldb_full_name'=>$row['institute_name'],'more_popular_abb'=>implode(',', array_unique(explode(',', trim(trim($data['synonyms'],' ,').','.trim($data['acronyms'],' ,'),' ,')))))); 
					$this->QERdbHandle->insert('institute',array('ldb_id'=>$data['instid'],'local_full_name'=>$row['institute_name'],'ldb_full_name'=>$row['institute_name'],'more_popular_abb'=>implode(',', array_unique(explode(',', trim(trim($data['synonyms'],' ,').','.trim($data['acronyms'],' ,'),' ,'))))));
				}
			}

			$this->QERdbHandle->trans_complete();
			if ($this->QERdbHandle->trans_status() === FALSE){
				$this->dbHandle->trans_rollback();
				return "fail";
			}
			else{
				$this->dbHandle->trans_commit();
				Modules::run('search/Indexer/addToQueue',$data['instid'],'institute','delete',true);		
				Modules::run('search/Indexer/addToQueue',$data['instid'],"institute",'index',true);
				return "ok";
			}
		}
		else
			return "fail";
	}

	function getInstituteName($instid = NULL){
		if(empty($instid)){
			return "no";
		}
		$sql="select institute_name from institute where institute_id = ? and status= ?";
		$data = $this->dbHandle->query($sql, array($instid,'live'))->row_array();
		//_p($data);die();
		if(!empty($data)) {
			return $data['institute_name'];
		}
		return "no";
	}
}

?>