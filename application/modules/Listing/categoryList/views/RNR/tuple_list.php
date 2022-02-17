<?php
//$this->load->view('categoryList/RNR/tuple1');
//$this->load->view('categoryList/RNR/tuple2');
//$this->load->view('categoryList/RNR/tuple_feedback');


if(CP_HEADER_NAME == 'date_form_submission')
    $displayData['functionName'] = 'getFormSubmissionDate';
else
    $displayData['functionName'] = 'getPhotosAndVideos';

function getFormSubmissionDate(&$course,&$displayLocation){
    $formSubmissionDate = strtotime($course->getDateOfFormSubmission($displayLocation->getLocationId()));
    return ($formSubmissionDate > 0) ? date('d M `y',$formSubmissionDate) : 'N/A';
}
function getPhotosAndVideos(&$institute){
    return $institute->getPhotoCount() + $institute->getVideoCount();
}
$this->load->library('AbroadListingCommonLib');
$displayData['AbroadListingCommonLib'] = new AbroadListingCommonLib;

$count = 0;
$totalTuples = sizeof($institutes);
global $isShortlistWidgetVisible;
$isShortlistWidgetVisible = false;
		
foreach($institutes as $institute) {
    $count++;
    $displayData['institute'] = $institute;
    if($institute instanceof AbroadInstitute) {
      continue;
    }
    $displayData['row_number'] = $count;
    $displayData['total_tuples'] = $totalTuples;
 
    $this->load->view('categoryList/RNR/tuple',$displayData);
    if($count == 8 && $subcat_id_course_page == 23){
	$this->load->view('common/IIMCallPredictorBanner',array('productType'=>'categoryPage'));
    }
    
    if($count == 8 && $subcat_id_course_page == 56 && false){
        echo "<p style='font-size:18px; margin:15px 0 8px;'>Engineering College Reviews</p>";
        $bannerProperties1 = array('pageId'=>'CATEGORY_BTECH', 'pageZone'=>'MIDDLE');
        $this->load->view('common/banner',$bannerProperties1);
    }

    if($totalTuples > 5 && $totalTuples <= 15) {
        if($count == floor($totalTuples/2)) {
            $bannerZone['pageZone'] = 'SNIPPET_TOP';
            $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
        }
    } else if($totalTuples > 15) {
        if($count == 4) {
            $bannerZone['pageZone'] = 'SNIPPET_TOP';
            $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
        }
        else if($count == (floor(($totalTuples-4)/2)+4)) {
            $bannerZone['pageZone'] = 'SNIPPET_MIDDLE';
            $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
        }
    }
}

/* banners at the bottom */
if($totalTuples <= 5) {
    //top row will come at the bottom of the listing
    $bannerZone['pageZone'] = 'SNIPPET_TOP';
    $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
}
else if($totalTuples > 5 && $totalTuples <= 15) {
    //middle row will come at the bottom of the listing
    $bannerZone['pageZone'] = 'SNIPPET_MIDDLE';
    $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
}
else if($totalTuples > 15) {
    //last row will come at the bottom of the listing
    $bannerZone['pageZone'] = 'SNIPPET_BOTTOM';
    $this->load->view('categoryList/RNR/bms_banner', $bannerZone);
}

?>
