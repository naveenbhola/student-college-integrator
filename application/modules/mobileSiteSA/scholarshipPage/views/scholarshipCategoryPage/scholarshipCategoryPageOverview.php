<?php
if($tupleAJAX == 1)
{
    $tupleHTML = $this->load->view('scholarshipCategoryPage/widgets/scholarshipTuples', $displayData, true);
	echo json_encode(array('tuples'=>$tupleHTML));
	exit();
}else{
$this->load->view('scholarshipCategoryPage/scholarshipCategoryPageHeader');
if($trackForPages){
	echo jsb9recordServerTime('SA_MOB_SCHOLARSHIP_CATEGORY_PAGE', 1);
}
$this->load->view('scholarshipCategoryPage/scholarshipCategoryPageContent');
$this->load->view('scholarshipCategoryPage/scholarshipCategoryPageFooter');
}
?>