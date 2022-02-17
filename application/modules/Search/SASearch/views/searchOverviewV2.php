<?php 
if($filterAjaxCall == 1) {
    $jsonDataArr = array();
    //echo "AHHAHA";die;
    $jsonDataArr['filterHtml'] = $this->load->view('SASearch/searchFilters',array(),true);
    $jsonDataArr['topFilterHtml'] = $this->load->view('SASearch/appliedFilterInfo',array(),true);
//    echo "AHHAHA";die;
//    _p($filterWithData);die;
    if($filterWithData) {
        $jsonDataArr['tupleHtml'] = $this->load->view('SASearch/rightContentSection', array(), true);
        $jsonDataArr['srpHeadingHtml'] = $this->load->view('SASearch/srpHeadingText', array(), true);
    }

    echo json_encode($jsonDataArr);
}else{
    $this->load->view('SASearch/searchV2Header');
    if($pageData['totalResultCount'] == 0)
    {
        $this->load->view('SASearch/ZRPPage');
    }
    else
    {
        $this->load->view('SASearch/searchV2Content');
    }
    $this->load->view('SASearch/searchV2Footer');
    echo '<div id="transparent_layer"></div>';
}

?>