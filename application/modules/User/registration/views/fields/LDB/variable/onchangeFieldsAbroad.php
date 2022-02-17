<?php
if(isset($fields['residenceCity'])){
?>
        <div class="custom-dropdown">
            <select name="residenceCity" class="universal-select signup-select" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this)">
                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical')); ?>
            </select>
        </div>
        <div>
    <div class="errorMsg" id="residenceCity_error_<?php echo $regFormId; ?>"></div>
</div>
    <?php } 


    if(isset($fields['emptyResidenceCity'])){
?>  
        <div class="custom-dropdown">
            <select class="universal-select signup-select" >
                <option value="-1" > City not required </option> 
            </select>
        </div>
<?php } ?>