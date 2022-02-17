<?php

$UserResidentCity = (isset($personalInfo['Locality']) && !empty($personalInfo['Locality'])) ? $personalInfo['Locality']: 0;

?>
<span class="p-s" id="residenceCityLocality_block_<?php echo $regFormId; ?>" visible="Yes">
   <label class="cursor">Resident City <i>*</i></label>
   <select class="dfl-drp-dwn cursor" caption="city of residence" label="Residence Location" mandatory="1" regfieldid="residenceCityLocality" name="residenceCityLocality" id="residenceCityLocality_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].getUserLocalities(this.value, '<?php echo $UserResidentCity; ?>')" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" >
      <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('residenceCityLocality'=>$personalInfo['City'], 'isUnifiedProfile'=>'YES')); ?>
  </select>
  <div >
    <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>">
    </div>
  </div>
</span>

<span class="hidden-field-right hide-class" id="residenceLocality_block_<?php echo $regFormId; ?>" visible="Yes" >
   <label>Resident Locality</label>
   <select class="dfl-drp-dwn cursor" name="residenceLocality" id="residenceLocality_<?php echo $regFormId; ?>">
      <option value="-1" >Locality </option>
  </select>
  <div class="hide-class">
    <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>">
    </div>
  </div>
</span>
