<?php
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageFilters');
    $filters_view = ob_get_clean();
    $filters_view = utf8_encode($filters_view);
 
    ob_start();
    $this->load->view('categoryList/abroad/widget/categoryPageYourSelection');
    $your_selection_view = ob_get_clean();
    $your_selection_view = utf8_encode($your_selection_view);
  
    ob_start();
    if(! $isZeroResultPage || $categoryPageRequest->isExamCategoryPage()) {
		$this->load->view('categoryList/abroad/widget/categoryPageSortBy');
    }
    $sort_by_view = ob_get_clean();
    $sort_by_view = utf8_encode($sort_by_view);
    $finalResult_view = array("filters" => $filters_view, "yourSelection" => $your_selection_view, "sortBy"=>$sort_by_view);
    
    $finalResult_view = json_encode($finalResult_view);
    
    echo $finalResult_view;
    exit();
?>
