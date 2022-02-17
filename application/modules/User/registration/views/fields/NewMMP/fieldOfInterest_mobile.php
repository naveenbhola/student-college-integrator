<?php
$fieldOfInterestValues = $fields['fieldOfInterest']->getValues(array('mmpFormId' => $mmpFormId));
if(!empty($fieldOfInterestValues)) {
if(count($fieldOfInterestValues) == 1) {
?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest','display:none;'); ?>>
<?php } else { ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('fieldOfInterest'); ?>>
<?php } ?>
    <select name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" onchange="registrationCustomLogic['<?php echo $regFormId; ?>'].handleCustomLogic(this);">
	<?php if(count($fieldOfInterestValues) > 1) { ?>
        <option value="">Education Interest</option>
	<?php } ?>
        <?php foreach($fieldOfInterestValues as $categoryId => $categoryName) { ?>
            <option value="<?php echo $categoryId; ?>"><?php echo $categoryName; ?></option>
        <?php } ?>
    </select>
    <div>
        <div class="regErrorMsg" id="fieldOfInterest_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<?php } ?>
