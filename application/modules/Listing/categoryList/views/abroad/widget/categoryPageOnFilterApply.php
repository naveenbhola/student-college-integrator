<?php
    /**
    * Purpose       : File to get the updated values of different sections of the category page after filter is applied
    * To Do         : none
    * Author        : Romil Goel
    */

    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageFilters');
    $filters_view = ob_get_clean();
    $filters_view = utf8_encode($filters_view);
 
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageYourSelection');
    $your_selection_view = ob_get_clean();
    $your_selection_view = utf8_encode($your_selection_view);
  
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageListing');
    $tuples_view = ob_get_clean();
    $tuples_view = utf8_encode($tuples_view);
    
    //$foundCoursesCount = $noOfCourses;noOfUniversities
    $foundCoursesCount = $noOfUniversities;
    
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPagePagination');
    $paginationSec_view = ob_get_clean();
    $paginationSec_view = utf8_encode($paginationSec_view);

    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageSortBy');
    $sortbySec_view = ob_get_clean();
    $sortbySec_view = utf8_encode($sortbySec_view);
    
    $foundCoursesCount = $noOfUniversities." ".($noOfUniversities>1 ? " colleges found" : " college found");
    $finalResult_view = array("filters" => $filters_view, "tuples" => $tuples_view, "foundCoursesCount" => $foundCoursesCount,"yourSelection" => $your_selection_view, "paginationSection" => $paginationSec_view, "sortbySec_view" => $sortbySec_view);
    
    $finalResult_view = json_encode($finalResult_view);
    
    echo $finalResult_view;
    exit();
?>
