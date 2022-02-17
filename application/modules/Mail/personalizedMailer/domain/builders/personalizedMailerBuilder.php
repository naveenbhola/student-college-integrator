<?php

class personalizedMailerBuilder
{
    private $CI;
    
    function __construct()
    {
        $this->CI = &get_instance();    
    }
    
    public function getPersonalizedMailerRepository()
    {
        /*
         * Load dependencies for Course Page Repository
         */

	    $this->CI->load->model('personalizedMailer/personalizedmailermodel','',TRUE);
	    $dao = $this->CI->personalizedmailermodel;
	
        /*
         * Load the repository
         */ 
        $this->CI->load->repository('personalizedMailerRepository','personalizedMailer');        
	    $personalizedMailerRepository = new personalizedMailerRepository($dao);
        return $personalizedMailerRepository;
    }
}
