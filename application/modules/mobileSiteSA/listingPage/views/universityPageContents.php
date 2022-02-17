<?php
$this->load->view('widgets/universityTitle');
$this->load->view('widgets/universityPopularCourses');
$this->load->view('widgets/universityHighlights');
$this->load->view('widgets/universityCourses');
$this->load->view('widgets/userProfileWidget');
$this->load->view('widgets/universityDownloadBrochureWidget');
$this->load->view('applyHomePage/widgets/applyHomeListing');
/*if( !empty($consultantData) ){
    $this->load->view('widgets/consultantWidget');
}*/
//$this->load->view('widgets/coursePhotoVideo');
//$this->load->view('widgets/universityBrochureDownload');
if(!empty($universityEmail) || !empty($universityAddress) || !empty($universityWebsite)){
    $this->load->view('widgets/universityContactDetails');
}
if(!empty($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0){
    $this->load->view('widgets/scholarshipInterlinkingULP');
}
if((isset($livingExpenseInRupees) && $livingExpenseInRupees > 0) || isset($livingExpenseURL)){
    $this->load->view('widgets/universityCostOfLiving');
}
if($universityAccomodationDetails !='' || $universityAccomodationURL !=''){
    $this->load->view('widgets/universityAccomodationDetails');
}
if($universityObj->getAccreditation() != ''){
    $this->load->view('widgets/universityAccreditation');
}
if(count($alsoViewedUniversityData)>0){
    $this->load->view('widgets/peopleAlsoViewedUniversity');
}

//$this->load->view('widgets/universityStudentGuide');

?>
