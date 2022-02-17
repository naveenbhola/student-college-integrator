<?php
    /**
    * Purpose       : File to get the updated values of different sections of the category page after sorter is applied
    * Author        : Nikita Jain
    */
    
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageListing');
    $tuples_view = ob_get_clean();
    $tuples_view = utf8_encode($tuples_view);
    
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPagePagination');
    $paginationSec_view = ob_get_clean();
    $paginationSec_view = utf8_encode($paginationSec_view);
    
    $finalResult_view = array("tuples" => $tuples_view, "paginationSection" => $paginationSec_view);
    
    $finalResult_view = json_encode($finalResult_view);
    
    echo $finalResult_view;
    exit();
?>