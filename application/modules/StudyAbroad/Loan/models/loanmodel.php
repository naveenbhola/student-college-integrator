<?php
/**
 * Created by PhpStorm.
 * User: sandeep
 * Date: 24/10/18
 * Time: 4:00 PM
 */

class loanmodel extends MY_Model{
    private $dbHandle = '';
    private $dbHandleMode = '';

    private function initiateModel($mode = "read"){
        if($this->dbHandle && $this->dbHandleMode == 'write'){
            return;
        }

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }


    public function getUserEducationLoanDetails($userId){
        $this->initiateModel('read');
        $this->dbHandle->select('*');
        $this->dbHandle->from('sa_user_loan_details');
        $this->dbHandle->where('user_id',$userId);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    public function saveUserEducationLoanDetails($idata){
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        //update old row if exists
        $udata = array();
        $udata['status'] = 'history';
        $this->dbHandle->where('user_id',$idata['user_id']);
        $this->dbHandle->where('status','live');
        $this->dbHandle->update('sa_user_loan_details',$udata);

        //insert new row
        $idata['status'] = 'live';
        $idata['created_on'] = date("Y-m-d H:i:s");
        $this->dbHandle->insert('sa_user_loan_details', $idata);
        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status == False){
            return 0;
        }
        else{
            return 1;
        }
    }

    public function getUserEducationLoanApplicationNumber($userId){
        $this->initiateModel('read');
        $this->dbHandle->select('id');
        $this->dbHandle->from('sa_user_loan_details');
        $this->dbHandle->where('user_id',$userId);
        $this->dbHandle->where('status','live');
        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

    /*
    * This method get education loan data for last given $day.
    */
    public function getEducationLoanDataForTimePeriod($day = 1) {
        $prevDate = date('Y-m-d H:i:s', strtotime("-".$day.' day', time()));
        
        $this->initiateModel('read');
        $this->dbHandle->select('uld.*, cct.city_name as collateral_location');
        $this->dbHandle->from('sa_user_loan_details uld');
        $this->dbHandle->join('countryCityTable cct', 'cct.city_id=uld.collateral_location_id','left');
        $this->dbHandle->where('created_on >=', $prevDate);
        $this->dbHandle->where('status','live');

        $result = $this->dbHandle->get()->result_array();
        return $result;
    }

}
?>