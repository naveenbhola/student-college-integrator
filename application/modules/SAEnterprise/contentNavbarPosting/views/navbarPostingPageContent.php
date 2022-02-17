<?php
    /**
	 * Add the calls to your views here according to view
	 **/
    switch($formName){
        case ENT_SA_CONTENT_NAVBARS :
            $this->load->view('viewContentNavbars');
            break;
        case ENT_SA_EDIT_CONTENT_NAVBAR_LINKS :
            $this->load->view('addEditContentNavbarLinks');
            break;
        default :
            $this->load->view('viewContentNavbars');
            break;
    }
