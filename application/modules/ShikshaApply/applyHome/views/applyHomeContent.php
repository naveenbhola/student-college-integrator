<div class="apply_container">
    <?php
        //$this->load->view('widgets/headerBannerSection');   //removed in SA-4364
        $this->load->view('widgets/applyHomeTopCard');
    ?>
  <div class="apply_page">
  <input type="hidden" class="applyHomePage" value="<?php echo !$onRMCSuccessFlag?>" />
  <?php
    //if(!is_null($useNewApplyHome)){
      //$this->load->view('/studyAbroadCommon/abTrackingFields',array('ABVariate'=>($useNewApplyHome == 1?'new':'old')));
    //}
    $this->load->view('widgets/howShikshaCounselingWork');
    $this->load->view('widgets/shikshaCounselingStats');
    //$this->load->view('widgets/counselorsInfoWidget');  //removed in SA-4364
    $this->load->view('widgets/studentsSuccessStory');
    $this->load->view('widgets/shikshaApplyFAQ');
    $this->load->view('widgets/shikshaApplyDisclaimer');
  ?>
  </div>
  <?php //$this->load->view('widgets/topStickyBanner');// removed in SA-3778 ?>
</div>