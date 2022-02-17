<?php 

class UrlRedirectModel extends MY_Model 
{
    function __construct() {
        parent::__construct('Listing');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }       
    }

	function initDB(){
		$this->initiateModel();
		return  $this->dbHandle;
	}	

    function insertUrlRedirections($batch){

        if(empty($batch))
            return;

        $data = array();
        $arr = array();
        foreach ($batch as $value) {
            $arr['fromURL'] = $value['from'];
            $arr['toURL'] = $value['to'];
            $arr['creationDate'] = date("Y-m-d H:i:s");

            $data[] = $arr;
        }
        $this->initiateModel("write");
        $this->dbHandle->insert_batch('urlRedirections',$data);
    }

    function getRedirectionMapping(){

        $this->initiateModel("read");
        $this->dbHandle->where(array('status'=>'live'));
        $this->dbHandle->select('fromURL,toURL');

        $mappings = $this->dbHandle->get('urlRedirections')->result_array();

        $finalMapping = array();
        foreach ($mappings as $value) {
            $finalMapping[$value['fromURL']] = $value['toURL'];
        }

        return $finalMapping;
    }
}