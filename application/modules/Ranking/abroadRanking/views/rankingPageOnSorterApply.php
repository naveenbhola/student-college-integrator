<?php
    /**
    * Purpose       : File to get the updated values of different sections of the Ranking page after sorter is applied
    * Author        : Virendra rastogi
    */
    
    ob_start();
    $this->load->view('rankingPageCourseTable');
    $tuples_view = ob_get_clean();
    $tuples_view = utf8_encode($tuples_view);
    
    
    $finalResult_view = array("tuples" => $tuples_view);
    
    $finalResult_view = json_encode($finalResult_view);
    
    echo $finalResult_view;
    exit();
?>