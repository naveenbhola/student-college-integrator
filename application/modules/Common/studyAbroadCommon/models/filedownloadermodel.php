<?php
/**
 * Created by PhpStorm.
 * User: kushagra
 * Date: 6/6/18
 * Time: 11:25 AM
 */

class filedownloadermodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';


    public function __construct() {
        parent::__construct('Misc');
    }

    // function to be called for getting dbHandle with read/write mode
    private function initiateModel($mode='read'){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return ;
        }
        $this->dbHandleMode = $mode;
        if($mode == 'write'){
            $this->dbHandle = $this->getWriteHandle();
        }elseif ($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        }
    }

    public function getDocumentData($docId){
        $this->initiateModel('read');
        $this->dbHandle->select("documentName, documentUrl, userId");
        $this->dbHandle->from("rmcUserDocuments");
        $this->dbHandle->where('id',$docId);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return reset($result);
    }

    public function getStudentDocumentData($docId){
        $this->initiateModel('read');
        $this->dbHandle->select("documentUrl, userId");
        $this->dbHandle->from("rmcStudentExamsDocuments");
        $this->dbHandle->where('id',$docId);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return reset($result);
    }

}