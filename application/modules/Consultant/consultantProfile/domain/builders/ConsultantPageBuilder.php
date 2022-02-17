<?php

class ConsultantPageBuilder
{
    private $CI;
    private $dao;
    private $consultantRepository;

    function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->entities(array('Consultant','ConsultantLocation','ConsultantStudentProfile'),'consultantProfile');
        // Load model dependency for Repositories
        $this->dao = $this->CI->load->model('consultantProfile/consultantmodel');
    }
    
    /*
     * function to get Consultant Repository
     */
    public function getConsultantRepository()
    {
            // load consultant & student profile repositories
            $consultantCache 	= $this->CI->load->library('consultantProfile/cache/ConsultantCache');
            $this->CI->load->repository('ConsultantStudentProfileRepository','consultantProfile');
            $consultantLocationRepository       = $this->getConsultantLocationRepository();
            $consultantStudentProfileRepository = $this->getConsultantStudentProfileRepository();
            // load the consultant repository
            $this->CI->load->repository('ConsultantRepository','consultantProfile');
            $this->consultantRepository = new ConsultantRepository($this->dao, $consultantLocationRepository, $consultantStudentProfileRepository,$consultantCache);
            return $this->consultantRepository;
    }
    
    /*
     * function to get Consultant Location Repository
     */
    public function getConsultantLocationRepository()
    {
            // load Consultant Location repository
            $consultantLocationCache 	= $this->CI->load->library('consultantProfile/cache/ConsultantLocationCache');
            $this->CI->load->repository('ConsultantLocationRepository','consultantProfile');
            $consultantLocationRepository       = new ConsultantLocationRepository($this->dao,$consultantLocationCache);
            return $consultantLocationRepository;
    }
    /*
     * function to get Consultant Student Profile Repository
     */
    public function getConsultantStudentProfileRepository()
    {
            // load Consultant Student Profile Repository
            $consultantStudentProfileCache 	= $this->CI->load->library('consultantProfile/cache/ConsultantStudentProfileCache');
            $this->CI->load->repository('ConsultantStudentProfileRepository','consultantProfile');
            $consultantStudentProfileRepository = new ConsultantStudentProfileRepository($this->dao,$consultantStudentProfileCache);
            return $consultantStudentProfileRepository;
    }
}
