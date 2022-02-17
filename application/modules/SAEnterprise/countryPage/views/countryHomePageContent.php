<?php
    switch( $formName )
    {
        case ENT_SA_EDIT_COUNTRYHOMEWIDGETS :
            $this->load->view('countryPage/editCountryHomeWidgets');
	    break;
        default :
            $this->load->view('countryPage/viewCountryHomeWidgetTable');
		break;	
    }
?>