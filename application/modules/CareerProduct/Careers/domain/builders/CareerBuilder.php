<?php
class CareerBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getCareerRepository(){
	$this->CI->load->repository('CareerRepository','Careers');
        $dao = '';
        $this->CI->load->model('Careers/careermodel','',TRUE);
	    $model = $this->CI->careermodel;
        $careerRepository = new CareerRepository($dao,$cache,$model);
        return $careerRepository;
    }
    
    public function getCareerService()
    {
        $this->CI->load->service('CareerService','Careers');
        $careerService = new CareerService($this->getCareerRepository());
        return $careerService;
    }
}
?>
