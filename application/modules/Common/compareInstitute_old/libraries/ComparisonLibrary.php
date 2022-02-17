<?php
class comparisonLibrary  {
        var $CI = '';
        var $cacheLib;
        var $comparisonModel;

        var $useCache = true;
        var $cacheTimeLimit = 604800;

        function init()
        {
                $this->CI =& get_instance();
                $this->CI->load->library('Common/cacheLib');
                $this->cacheLib = new cacheLib();
                $this->CI->load->model('compareInstitute/compare_model');
                $this->comparisonModel = new compare_model();
        }


        function getPopularComparisonForHomepage($subcatId){
                $this->init();
                $key = "createPopularComparisonHTML_".$subcatId;
                if( ($this->cacheLib->get($key)=='ERROR_READING_CACHE') || ($this->useCache == false) ){
                    $data = $this->comparisonModel->getPopularComparisonForHomepage($subcatId);
                    $this->cacheLib->store($key,$data,$this->cacheTimeLimit);
                }
                else{
                    $data = $this->cacheLib->get($key);
                }
                return $data;
        }
}
?>