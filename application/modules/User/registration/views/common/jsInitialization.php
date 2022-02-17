<?php
$customValidations = json_encode($registrationHelper->getCustomValidations());
if($context == 'abroadUserSetting' || $context == 'rmcStudentProfile'){
   $customValidations = $registrationHelper->getCustomValidations();
   unset($customValidations['examsAbroad']);
    unset($customValidations['TOEFL_score']);
    unset($customValidations['IELTS_score']);
    unset($customValidations['PTE_score']);
    unset($customValidations['GRE_score']);
    unset($customValidations['GMAT_score']);
    unset($customValidations['SAT_score']);
    unset($customValidations['CAEL_score']);
    unset($customValidations['MELAB_score']);
    unset($customValidations['CAE_score']);
   $customValidations = json_encode($customValidations);
}

$defaultFieldStates = json_encode($registrationHelper->getDefaultFieldStates());
$newA = file_get_contents("public/blacklisted.txt");
?>
<script>
var blacklistWords = new Array(<?php echo $newA;?>);
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo $customValidations; ?>);
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($fields)); ?>);
registrationCustomLogic['<?php echo $regFormId; ?>'] = new RegistrationCustomLogic('<?php echo $regFormId; ?>',<?php echo $customRules ? $customRules : "[]"; ?>,<?php echo $defaultFieldStates; ?>);
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setCustomLogic(registrationCustomLogic['<?php echo $regFormId; ?>']);

<?php if ($context !='downloadEbrochureSA' &&  $context !='mobileRegistrationNational' && empty($skipPopulateForm)) { ?>
shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].populateForm(<?php echo json_encode($formData); ?>,true);
<?php } ?>

<?php if( ((!$mmpFormId) || (($mmpFormId) && ($mmpData['display_on_page'] != 'normalmmp') ) ) && $context != 'mobileRegistrationNational') { ?>
    if(document.attachEvent) { //Internet Explorer
      document.attachEvent("onclick", function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();});
    }
    else if(document.addEventListener) { //Firefox & company
      document.addEventListener('click', function() {shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].hideAllLayers();}, false); 
    }
<?php } ?>

var STUDY_ABROAD_NEW_REGISTRATION = '<?php echo STUDY_ABROAD_NEW_REGISTRATION ?>';
</script>
