<?php
class BranchBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getBranchRepository(){
		$this->CI->load->repository('BranchRepository','CP');
        $dao = '';
        $this->CI->load->model('CP/cpmodel','',TRUE);
		$model = $this->CI->cpmodel;
		$this->CI->load->library('CP/cache/CollegePredictorCache');
        $cache = $this->CI->collegepredictorcache;
        $branchRepository = new BranchRepository($dao,$cache,$model);
        return $branchRepository;
    }

    public function getSorterManager(){
		$this->CI->load->library("CP/CollegePredictorSorterManager/CollegePredictorSorterManager");
		return new CollegePredictorSorterManager($this->CI);
    }	

    public function getFilterManager(){
		$this->CI->load->library("CP/CollegePredictorFilterManager/CollegePredictorFilterManager");
		return new CollegePredictorFilterManager($this->CI);
    }
}
?>
