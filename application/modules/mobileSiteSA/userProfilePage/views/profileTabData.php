<div class="tabs_data" id="tab1">
  <?php 
  $this->load->view('userProfilePage/profileSummary');
  $this->load->view('userProfilePage/userEducationDetails');
  if($selfProfile)
    $this->load->view('userProfilePage/userPersonalDetails');
  ?>
</div>