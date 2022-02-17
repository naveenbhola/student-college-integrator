<?php

class AddListing extends MX_Controller {
    //Controller default API to ask for specific API call
    function index()
    {
        echo "Please specify the Name of the API to be called!!";
    }


   function addCourse()
        {

                $this->init();
                $response['hasColleges'] = 1;

                $this->load->view('listing/course_listing',$response);
        }

        function nsc()
        {
                $this->init();
                $this->load->view('listing/addschol.php');
        }

        function naddadm()
        {
                $this->init();
                $this->load->view('listing/addadm.php');
        }


    function init() {
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','ajax','category_list_client','listing_client','register_client'));
        $this->userStatus = $this->checkUserValidation();
    }
}
?>
