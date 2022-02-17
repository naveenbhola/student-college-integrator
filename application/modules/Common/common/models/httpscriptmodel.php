<?php
class httpScriptModel extends MY_Model
{  
    public $module;
    function __construct()
    {
        parent::__construct();
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){ 
            $this->dbHandle = empty($this->module) ? $this->getReadHandle() : $this->getReadHandleByModule($this->module);
        }else{
            $this->dbHandle = empty($this->module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($this->module);
        }       
    }

    function getHttpContentId($tableName, $primaryColumnName, $contentColumnName, $status, $findStr){
        
        if(is_array($status) && count($status)>0){
            $status = "'" . implode ("','", $status ) . "'";
            $andStatus = " and status IN ($status)";
        }

        if(empty($findStr)){
            $findStr = 'shiksha.com';
        }

        $this->initiateModel('read');
        
        $sql = "SELECT distinct $primaryColumnName 
                FROM $tableName 
                where $contentColumnName 
                like '%$findStr%' $andStatus";
                return $this->dbHandle->query($sql)->result_array();
    }

    function getContentHavingHttp($tableName, $primaryColumnName, $contentColumnName, $contentIds){
        if(count($contentIds)<=0){
            return;
        }
        $contentId = implode(',', $contentIds);
        $this->initiateModel('read');
        $sql = "SELECT $primaryColumnName, $contentColumnName 
                FROM $tableName 
                where $primaryColumnName IN ($contentId)";
                return $this->dbHandle->query($sql)->result_array();
    }

    function updateContentWithHttps($tableName, $primaryColumnName, $contentArr){
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        $this->dbHandle->update_batch($tableName, $contentArr, $primaryColumnName);
        $this->dbHandle->trans_complete();
        return ($this->dbHandle->trans_status() === FALSE) ? false : true;
    }

    function checkIfTableExist($tableName =''){
        if(empty($tableName)){
            return false;
        }
        $this->initiateModel('read');
        if($this->dbHandle->table_exists($tableName)){
            return true;
        }else{
            return false;
        }
    }

    function checkIfColumnExistInTable($tableName ='',$columnName =''){
        if(empty($tableName) || empty($columnName)){
            return false;
        }
        $this->initiateModel('read');
        if($this->dbHandle->field_exists($columnName ,$tableName)){
            return true;
        }else{
            return false;
        }
    }

    function replaceAbsolutePathWithRelativepath($tableName, $columnName, $domain, $statusCheck){
        if(empty($tableName) || empty($columnName) || empty($domain)){
            return false;
        }

        $this->initiateModel('write');
        $sql = "Update $tableName set $columnName = replace ($columnName , '$domain' ,'')";
        if($statusCheck == 1){
            $sql .= " where status in ('live','draft')";
        }
        $response = $this->dbHandle->query($sql);
        return $response;
    }
}