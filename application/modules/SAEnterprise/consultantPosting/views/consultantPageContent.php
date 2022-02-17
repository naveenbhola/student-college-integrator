<?php

    switch( $formName )
    {
        case ENT_SA_FORM_ADD_CONSULTANT :
            $this->load->view('consultantPosting/addEditConsultantForm');
	    break;
	case ENT_SA_FORM_EDIT_CONSULTANT :
	    $this->load->view('consultantPosting/addEditConsultantForm');
            break;
	case ENT_SA_FORM_ADD_CONSULTANT_LOCATION :
	case ENT_SA_FORM_EDIT_CONSULTANT_LOCATION :
	    $this->load->view('consultantPosting/addEditConsultantLocationForm');
            break;
	case ENT_SA_VIEW_CONSULTANT_TABLE :
	    $this->load->view('consultantPosting/viewConsultantsTable');
            break;
	case ENT_SA_FORM_ADD_CONSULTANT_UNIVERSITY_MAPPING :
	    $this->load->view('consultantPosting/addEditConsultantUniversityMappingForm');
            break;
	case ENT_SA_FORM_EDIT_CONSULTANT_UNIVERSITY_MAPPING :
	    $this->load->view('consultantPosting/addEditConsultantUniversityMappingForm');
            break;
	case ENT_SA_VIEW_CONSULTANT_UNIVERSITY_MAPPING_TABLE :
	    $this->load->view('consultantPosting/viewConsultantUniversityMappingTable');
            break;
	case ENT_SA_FORM_ADD_STUDENT_PROFILE :
	    $this->load->view('consultantPosting/addEditStudentProfile');
            break;
	case ENT_SA_FORM_EDIT_STUDENT_PROFILE :
	    $this->load->view('consultantPosting/addEditStudentProfile');
            break;
	case ENT_SA_VIEW_STUDENT_PROFILE :
	    $this->load->view('consultantPosting/viewStudentMapProfilesTable');
            break;
	case ENT_SA_FORM_ASSIGN_REGION :
	    $this->load->view('consultantPosting/addEditCitySubscription');
            break;
	case ENT_SA_FORM_EDIT_ASSIGNED_REGION :
	    $this->load->view('consultantPosting/addEditCitySubscription');
            break;
	case ENT_SA_VIEW_ASSIGNED_REGION :
	    $this->load->view('consultantPosting/viewCitySubscriptionTable');
            break;
	case ENT_SA_VIEW_LOCALITY_TABLE :
	    $this->load->view('consultantPosting/viewLocalityTable');
            break;
        case ENT_SA_FORM_ADD_LOCALITY :
	    $this->load->view('consultantPosting/addEditLocality');
            break;
        case ENT_SA_FORM_EDIT_LOCALITY :
	    $this->load->view('consultantPosting/addEditLocality');
            break;
	case ENT_SA_VIEW_UPGRADE_CONSULTANT :
	    $this->load->view('consultantPosting/viewClientConsultantSubscription');
            break;
	case ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION :
	case ENT_SA_FORM_EDIT_CLIENT_CONSULTANT_SUBSCRIPTION :    
	    $this->load->view('consultantPosting/addEditClientConsultantSubscriptionForm');
            break;
    
        default :
            $this->load->view('consultantPosting/viewConsultantsTable');
            //_p("This is the default page");
    }




?>