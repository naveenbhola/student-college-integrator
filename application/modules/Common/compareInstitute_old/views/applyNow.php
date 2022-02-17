<?php 
$trackingPageKeyId = '681';
global $flagForApplyNowButton;
foreach($courses as $key => $course){
  $hasForm[$key] = Modules::run('mOnlineForms5/OnlineFormsMobile/applyNowButton',$course,$trackingPageKeyId,'COMPARE_PAGE_BOTTOM');
  if(!empty($hasForm[$key])){
    $flagForApplyNowButton++;
  }
}
$this->load->library('dashboardconfig');
$online_form_institute_seo_url = DashboardConfig::$institutes_autorization_details_array;
$PBTSeoData = Modules::run('onlineFormEnterprise/PBTFormsAutomation/getExternalFormConfigDetails');
$online_form_institute_seo_url += $PBTSeoData;
if($flagForApplyNowButton != 0){
?>
<div class="ask-current-col">
  <p>Sure about this college? <span>Start the Application Process </span></p>
</div>

<div class="ask-section">
<?php $i=0; foreach($courses as $key => $course){
  if(!empty($hasForm[$key])){
      $type = 'Apply_Online_Internal_Form';
      if($online_form_institute_seo_url[$course]['seo_url']!='') {
        $seoURL = str_replace('<courseName>', strtolower(seo_url($instituteList[$key]['courseName'],'-',30)), $online_form_institute_seo_url[$course]['seo_url']);
        $seoURL = str_replace('<courseId>', $course, $seoURL);
        $seo_url = SHIKSHA_HOME.$seoURL;
      }else {
        $seo_url = "/Online/OnlineForms/showOnlineForms/".$course;   
      }
?>
  <div class="set-col">
    <a href="<?php echo $seo_url.'?tracking_keyid='.$trackingPageKeyId;?>" class="new-dwn-btn">Apply Now</a>
  </div>
<?php } else{?>
  <div class="set-col">
    <a href="javascript:void(0);" class="new-btn">- -</a>
  </div>
  <?php } $i++;}?>
</div> 
<?php }?>