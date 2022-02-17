<?php
/* 
 * 
 * 
 */

class instituteSite extends MX_Controller
{

    function indiraGroup()
    {
        $data['validateuser'] = $this->checkUserValidation();
        $this->load->view('indiraGroup.php');
    }
    
    function isbm()
    {
        $data['validateuser'] = $this->checkUserValidation();
        $this->load->view('ISBM.php');
    }
    
    function aakashITutor()
    {
        global $listings_with_localities;
        
        $instituteId = 35760;
        $data['instituteId'] = $instituteId;
        $data['validateuser'] = $this->checkUserValidation();
        $data['listings_with_localities']= json_encode($listings_with_localities);
        $this->load->view('aakashITutor',$data);
    }
    
    function instituteResponseWidget($instituteId)
    {
        $data = array();
        
        $this->load->builder('ListingBuilder','listing');
        $listingBuilder = new ListingBuilder;
        $instituteRepository = $listingBuilder->getInstituteRepository();
        $courseRepository = $listingBuilder->getCourseRepository();

        $courseIds = $courseRepository->getCoursesByMultipleInstitutes(array($instituteId));
        if(empty($courseIds)) {
            exit;
        }
        $courseIds = $courseIds[$instituteId];
        
        $data['courses'] = $courseRepository->findMultiple($courseIds);  
        $data['institute'] = $instituteRepository->find($instituteId);
        $data['instituteId'] = $instituteId;
        $data['widget'] = 'listingPageRight';
        
        $data['validateuser'] = $this->checkUserValidation();
        $this->load->view('listing/listingPage/widgets/responseWidgetCommon',$data);
    }
}

?>
