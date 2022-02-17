<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CollegeCompareTool extends MX_Controller
{
    
    function __construct()
    {
        parent::__construct();
	$this->load->model('collegecomparetoolmodel');
	$this->collegecomparetoolmodel = new CollegeCompareToolModel();
	$this->load->builder('ListingBuilder','listing');
    }
    
    public function generateCollegeCompareTool()
    {
           	
	$this->load->view('collegeCompareSticky');
    }
    
    public function getRecentViewedCollege()
    {
	$listingBuilder = new ListingBuilder;
	$this->courseRepository = $listingBuilder->getCourseRepository();
	$this->instituteRepository = $listingBuilder->getInstituteRepository();	
	
	$session_id = sessionId();
	$data['recent_view'] = $this->collegecomparetoolmodel->getRecentViewedInstitute($session_id);
	$data['courseRepository'] = $this->courseRepository;
	$data['instituteRepository'] = $this->instituteRepository;
    
	$this->load->view('collegeCompareRecentView',$data);
    }
}
