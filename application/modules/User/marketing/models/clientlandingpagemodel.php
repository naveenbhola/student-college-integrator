<?php

class ClientLandingPageModel extends MY_Model
{
    /**
     * @var Object DB Handle
     */ 
    private $dbHandle;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct('User');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */ 
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
    
    public function savePage($data)
    {
        $this->initiateModel('write');
        if($data['pageId']) {
            $pageData = array(
                                'name' => $data['name'],
                                'html' => $data['html'],
                                'modifiedOn' => date('Y-m-d H:i:s')
                            );
            $this->dbHandle->where('id', $data['pageId']);
            $this->dbHandle->update('clientLandingPages',$pageData);
        }
        else {
            $pageData = array(
                                'name' => $data['name'],
                                'html' => $data['html'],
                                'createdBy' => $data['userId'],
                                'createdOn' => date('Y-m-d H:i:s'),
                                'modifiedOn' => date('Y-m-d H:i:s')
                            );
            $this->dbHandle->insert('clientLandingPages',$pageData);
        }
    }
    
    public function getPages()
    {
        $this->initiateModel();
        $this->dbHandle->order_by("createdOn", "desc"); 
        $query = $this->dbHandle->get('clientLandingPages');
        return $query->result_array();
    }
    
    public function getPage($pageId)
    {
        $this->initiateModel();
        $this->dbHandle->where("id", $pageId); 
        $query = $this->dbHandle->get('clientLandingPages');
        return $query->row_array();
    }
}
