<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 8/10/18
 * Time: 12:53 PM
 */
class clientActivationModel extends MY_Model{

    private function initiateModel($operation='read'){
        if($operation=='read'){
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }
    }

    public function saveClientActivationFormData($formData){
        $this->dbHandle = $this->initiateModel("write");
    }

    public function getClientActivationData(){
        $this->dbHandle = $this->initiateModel("read");
        $this->dbHandle->select('*');
        $this->dbHandle->from();
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }
}
?>