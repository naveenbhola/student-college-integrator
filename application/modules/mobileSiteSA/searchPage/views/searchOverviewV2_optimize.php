<?php
if($filterUpdateCall == 1)
{
$data = $this->load->view("widgets/searchPageFiltersV2",array('filterUpdateCall'=>$filterUpdateCall),true);
$finalData = array('html'=>$data);
echo json_encode($finalData);
exit;
}
else if($isPaginatedPage !='')
{
$dataArray = array(
'identifier' => 'SearchListTupleV2',
'pageType' => 'searchPage_mob'
);
if($searchTupleType=='university'){
$data = $this->load->view("widgets/searchPageUnivTupleV2",$dataArray,true);
}else{
$data = $this->load->view("widgets/searchPageCourseTupleV2",$dataArray,true);
}
$finalData = array('html'=>$data);
echo json_encode($finalData);
exit;
}
else
{
$this->load->view('optimize/searchHeaderV2_optimize');
$this->load->view('searchContentsV2');
$this->load->view('optimize/searchFooterV2_optimize');
}?>