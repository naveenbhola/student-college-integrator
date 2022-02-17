<input type="hidden" id="autoSuggSelInstId" value="" />
<?php 
$iter = 0;
foreach ($academicUnitCookieData as $crs => $instData) {
	$iter++;
?>
<input type="hidden" id="selectedInstId<?php echo $iter?>" value="<?php echo $instData['userSelectedInstitute']?>" />
<input type="hidden" id="selectedInstIdCrsId<?php echo $iter?>" value="<?php echo $instData['userSelectedInstitute']?>-<?php echo $crs?>" />
<?php 
}
?>
<table class="cmpre-table" id="compareTable" width="100%" cellpadding="0" cellspacing="0">
<tbody>
<?php 
  $this->load->view('sections/compareInstituteDetails');
  $this->load->view('sections/compareCourseDropDown');
  if($currentCourseCount > 0){
    $this->load->view('compareNewUI_compareFieldsView');
    $this->load->view('sections/compareFacility');
    $this->load->view('compareNewUI_collegeReviewWidget');
    $this->load->view('compareNewUI_campusRepWidget');
  }
  ?>
</tbody>
</table>