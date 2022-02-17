<?php
    /**
    * Purpose       : File to get the updated values of different sections of the Ranking page after filter is applied
    * Author        : Virendra Rastogi
    */

    ob_start();
    $this->load->view('rankingPageFilters');
    $filters_view = ob_get_clean();
    $filters_view = utf8_encode($filters_view);
 
    ob_start();
    $this->load->view('rankingPageYourSelection');
    $your_selection_view = ob_get_clean();
    $your_selection_view = utf8_encode($your_selection_view);
    
    ob_start();
    $this->load->view('rankingPageSortBy');
    $sortbySec_view = ob_get_clean();
    $sortbySec_view = utf8_encode($sortbySec_view);
  
    ob_start();
    if($rankingPageObject->getType() == 'course'){
        $this->load->view('rankingPageCourseTable');
    }
    else{
        $this->load->view('rankingPageUniversityTable');
    }
    $tuples_view = ob_get_clean();
    $tuples_view = utf8_encode($tuples_view);
    
    $foundCoursesCount = $noOfCourses;
    
    

    
    
    $foundCoursesCount = $noOfCourses." ".($noOfCourses>1 ? " institutes found" : " institute found");
    $finalResult_view = array("filters" => $filters_view,
                              "tuples" => $tuples_view,
                              "foundCoursesCount" => $foundCoursesCount,
                              "yourSelection" => $your_selection_view,
                              "paginationSection" => $paginationSec_view,
                              "sortbySec_view" => $sortbySec_view
                              );
    
    $finalResult_view = json_encode($finalResult_view);
    
    echo $finalResult_view;
    exit();
?>