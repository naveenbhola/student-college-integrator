<?php

    /**
    * Add the calls to your views here according to view
    **/
    
    switch( $formName )
    {
        case ENT_SA_FORM_ADD_CITY :
        case ENT_SA_FORM_EDIT_CITY :
            $this->load->view('listingPosting/abroad/addEditCityForm');
            break;

        case ENT_SA_VIEW_LISTING_CITY :
            $this->load->view('listingPosting/abroad/viewCityListing');
            break;
            
        case ENT_SA_VIEW_LISTING_UNIVERSITY :
            $this->load->view('listingPosting/abroad/viewUniversityListing');
            break;

        case ENT_SA_VIEW_LISTING_DEPARTMENT :
            $this->load->view('listingPosting/abroad/viewDepartmentListing');
            break;
            
        case ENT_SA_FORM_ADD_SNAPSHOT_COURSE :
            $this->load->view('listingPosting/abroad/addSnapshotCourseListing');
            break;
        
        case ENT_SA_FORM_EDIT_SNAPSHOT_COURSE :
            $this->load->view('listingPosting/abroad/editSnapshotCourseListing');
            break;
            
        case ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE :
            $this->load->view('listingPosting/abroad/viewSnapshotCourseListing');
            break;
          
       case ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES :
        	$this->load->view('listingPosting/abroad/addBulkSnapshotCourseListing');
        	break;        

        case ENT_SA_VIEW_LISTING_RANKING :
            $this->load->view('listingPosting/abroad/viewRankingListing');
            break;
        
        case ENT_SA_FORM_ADVANCE_SEARCH_UNIVERSITY :
            $this->load->view('listingPosting/abroad/advanceSearchUniversityForm');
            break;

        case ENT_SA_FORM_ADD_DEPARTMENT:
            $this->load->view('listingPosting/abroad/addDepartmentForm');
            break;
        case ENT_SA_FORM_EDIT_DEPARTMENT:
            $this->load->view('listingPosting/abroad/addDepartmentForm');
            break;
        
        case ENT_SA_FORM_ADD_UNIVERSITY :
            $this->load->view('listingPosting/abroad/addEditUniversityForm');
            break;

        case ENT_SA_FORM_EDIT_UNIVERSITY :
            $this->load->view('listingPosting/abroad/addEditUniversityForm');
            break;
        
        case ENT_SA_VIEW_LISTING_COURSE :
            $this->load->view('listingPosting/abroad/viewCourseListing');
            break;
        
        case ENT_SA_FORM_ADD_COURSE :
            $this->load->view('listingPosting/abroad/addEditCourseListing');
            break;
        
        case ENT_SA_FORM_EDIT_COURSE :
            $this->load->view('listingPosting/abroad/addEditCourseListing');
            break;
        
        case ENT_SA_VIEW_LISTING_CONTENT :
            $this->load->view('listingPosting/abroad/viewContentListing');
            break;
        
        case ENT_SA_FORM_ADD_CONTENT :
            $this->load->view('listingPosting/abroad/addEditContentListing');
            break;
        
        case ENT_SA_FORM_EDIT_CONTENT :
            $this->load->view('listingPosting/abroad/addEditContentListing');
            break;

        case ENT_SA_EXAM_NAVBAR_LINKS :
            $this->load->view('listingPosting/abroad/examNavbarLinks');
            break;

        case ENT_SA_FORM_ADD_RANKING :
            $this->load->view('listingPosting/abroad/addRankingForm');
            break;
        
        case ENT_SA_FORM_EDIT_RANKING :
            $this->load->view('listingPosting/abroad/addRankingForm');
            break;

        case ENT_SA_VIEW_PAID_CLIENT :
            $this->load->view('listingPosting/abroad/viewPaidClient');
            break;
        case ENT_SA_FORM_ADD_PAID_CLIENT :
            $this->load->view('listingPosting/abroad/addPaidClient');
            break;
        case ENT_SA_FORM_EDIT_PAID_CLIENT :
            $this->load->view('listingPosting/abroad/addPaidClient');
            break;
	case ENT_SA_VIEW_ADMISSION_GUIDE :
            $this->load->view('listingPosting/abroad/viewAdmissionGuideListing');
            break;
	case ENT_SA_FORM_ADD_ADMISSION_GUIDE :
            $this->load->view('listingPosting/abroad/addEditAdmissionGuideForm');
            break;
	case ENT_SA_FORM_EDIT_ADMISSION_GUIDE :
            $this->load->view('listingPosting/abroad/addEditAdmissionGuideForm');
            break;
	case ENT_SA_FORM_ADD_RMS_COUNSELLOR:
		$this->load->view('listingPosting/abroad/addEditRMSCounsellorForm');
		break;
	case ENT_SA_FORM_EDIT_RMS_COUNSELLOR:
		$this->load->view('listingPosting/abroad/addEditRMSCounsellorForm');
		break;
	case ENT_SA_VIEW_RMS_COUNSELLOR :
            $this->load->view('listingPosting/abroad/viewRMSCounsellorListing');
            break;
	case ENT_SA_VIEW_RMS_UNIVERSITIES :
            $this->load->view('listingPosting/abroad/viewRMSUniversitiesListing');
            break;
	case ENT_SA_FORM_ADD_RMS_UNIVERSITY_COUNSELLOR_MAPPING :
            $this->load->view('listingPosting/abroad/addEditRmsUniversityCounsellorMapForm');
            break;
	case ENT_SA_FORM_EDIT_RMS_UNIVERSITY_COUNSELLOR_MAPPING :
            $this->load->view('listingPosting/abroad/addEditRmsUniversityCounsellorMapForm');
            break;
    case ENT_SA_VIEW_SPECIALIZATIONS:
        $this->load->view('listingPosting/abroad/viewSpecializations');
        break;
    case ENT_SA_SPECIALIZATION_FORM:
        $this->load->view('listingPosting/abroad/addEditSpecializations');
        break;
    case ENT_SA_VIEW_RESTORE_COURSE :
        $this->load->view('listingPosting/abroad/viewRestoreCourseListing');
        break;
    default :
        $this->load->view('listingPosting/abroad/viewUniversityListing');
        //_p("This is the default page");
    }
