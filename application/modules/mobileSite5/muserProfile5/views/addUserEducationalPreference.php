<?php
$sectiondata = array();
$sectiondata['sectionTitle'] = "Add/Edit Education Preferences";

if($userPreference['ExtraFlag'] == "studyabroad") {
?>

<div class="userpage-container">
  <form class="prf-form cursor" id="registrationForm_<?=$regFormId?>">  
    <?php $this->load->view('userProfileLayerHeader', $sectiondata); ?>
    <div class="workexp-dtls">
        <?php $this->load->view("addUserAbroadEducationalPreference"); ?>      
    </div>
  </form>
</div>

<?php } else { ?>

<div class="userpage-container">
  <div class="prf-form cursor" id="registrationForm_<?=$regFormId?>">  
    <?php $this->load->view('userProfileLayerHeader', $sectiondata); ?>
    <div class="workexp-dtls">
        <?php $this->load->view("addUserNationalEducationalPreference"); ?>      
    </div>
  </div>
</div>

<?php } ?>