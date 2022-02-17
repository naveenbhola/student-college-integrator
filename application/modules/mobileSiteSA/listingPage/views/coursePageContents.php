<?php
    $this->load->view('widgets/courseDescription');

    $this->load->view('widgets/courseUniversityHighlights');

    $this->load->view('widgets/userProfileWidget');

    $this->load->view('applyHomePage/widgets/applyHomeListing');
    
    if($applicationProcessDataFlag){
        $this->load->view('widgets/courseEntryRequirements');
    }else{
        $this->load->view('widgets/courseEligibility');
    }
    
    $this->load->view('widgets/courseFees');
    
    if($applicationProcessDataFlag == 1){
        $this->load->view('widgets/courseApplicationProcess');
    }
    
    if($isPlacementFlag){
        $this->load->view('widgets/coursePlacements');
    }
    
    if($scholarshipTabFlag){
        $this->load->view('widgets/courseScholarshipTab');
    }
    // Add Consultant widget here
//    if( !empty($consultantData) ){ 
//        $this->load->view('widgets/consultantWidget');
//    }
    
    $this->load->view('widgets/coursePhotoVideo');
    
    $this->load->view('widgets/courseCTA');
    $this->load->view('widgets/courseScholarships');
    
    if(count($alsoViewedCourses) >= 3){
        $this->load->view('widgets/otherCourses');
    }

    if($compareData && $compareData['recommendedCompareCourseData'] && count($compareData['recommendedCompareCourseData'])==1){
        $this->load->view('widgets/compareCoursesWidget');
    }

    $this->load->view('widgets/articleGuideWidget');
    $this->load->view('widgets/clpFatFooter');

?>