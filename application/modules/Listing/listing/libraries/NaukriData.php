<?php

class NaukriData {
    private $CI;
    private $institutemodel;
    
    function __construct() {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }
    
    function _setDependecies() {
        $this->institutemodel = $this->CI->load->model('listing/institutemodel');
    }

    public function getNaukriSalaryData($instituteId) {
        if(empty($instituteId)) {
            return array();
        }
        
        if(!is_array($instituteId)) {
            $instituteIds = array($instituteId);
        }
        else {
            $instituteIds = $instituteId;
        }
        
        $salaryDataResults = $this->institutemodel->getNaukriSalaryData($instituteIds, 'multiple');
        
        $data = array();
        $total_employees = array();
        $employees_in_bucket= array();
        
        foreach($salaryDataResults as $salaryData) {
            $instituteId = $salaryData['institute_id'];
            $total_employees[$instituteId] += $salaryData['tot_emp'];
            $employees_in_bucket[$instituteId][$salaryData['exp_bucket']] += $salaryData['tot_emp'];
            $data[$instituteId][$salaryData['exp_bucket']] = $salaryData['ctc50'];
        }
        
        foreach($instituteIds as $instituteId) {
            if($total_employees[$instituteId] > 30) {
                $exp_buckets = array(
                                    array('bucket' => '0-2', 'show' => TRUE),
                                    array('bucket' => '2-5', 'show' => TRUE),
                                    array('bucket' => '5+' , 'show' => TRUE)
                                    );
                
                foreach($exp_buckets as $bucket) {
                    if($employees_in_bucket[$instituteId][$bucket['bucket']] < 10) {
                        $exp_buckets[$bucket['show']] = FALSE;
                        unset($data[$instituteId][$bucket['bucket']]);
                    }
                }
                
                if($exp_buckets[1]['show'] && ($data[$instituteId][$exp_buckets[1]['bucket']] < $data[$instituteId][$exp_buckets[0]['bucket']])) {
                    unset($data[$instituteId][$exp_buckets[1]['bucket']]);
                }
                if($exp_buckets[2]['show'] && (($data[$instituteId][$exp_buckets[2]['bucket']] < $data[$instituteId][$exp_buckets[1]['bucket']]) || ($data[$instituteId][$exp_buckets[2]['bucket']] < $data[$instituteId][$exp_buckets[0]['bucket']]))) {
                    unset($data[$instituteId][$exp_buckets[2]['bucket']]);
                }
            }
            else {
                unset($data[$instituteId]);
            }
        }
        
        return $data;
    }
}
