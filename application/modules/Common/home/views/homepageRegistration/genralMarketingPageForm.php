<script>
var isLogged = '<?php echo $logged; ?>';
var messageObj;
//var FLAG_LOCAL_COURSE_FORM_SELECTION = 0;
</script>
<style>
.redcolor {
 color: red;
}
</style>
    <input type = "hidden" name = "flagfirsttime" id = "flagfirsttime" value = ""/>
    <input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
    <input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
    <input type = "hidden" name = "mCityList" id = "mCityList_abroad" value = ""/>
    <input type = "hidden" name = "mCityListName" id = "mCityListName_abroad" value = ""/>
    <input type = "hidden" name = "mCountryList" id = "mCountryList_abroad" value = ""/>
    <input type = "hidden" name = "mCountryListName" id = "mCountryListName_abroad" value = ""/>
    <input type = "hidden" name = "mRegions" id = "mRegions" value = "#"/>
    <input type = "hidden" name = "mPageName" id = "mPageName" value = "<?php echo $pageName?>"/>
    <input type = "hidden" name = "mcourse" id = "mcourse" value = "<?php echo $course?>"/>
    <input type = "hidden" name = "loginflagreg" id = "loginflagreg" value = ""/>
    <input type = "hidden" name = "loginactionreg" id = "loginactionreg" value = ""/>

<?php
    $marketingPageForms['studyAbroad'] = array(
            FIELD_OF_INTEREST_FIELD,
            DESIRED_COURSE_LEVEL_FIELD,
            GRADUATION_DETAILS_FIELD,
            XII_DETAILS_FIELD,
            DESTINATION_COUNTRY_FIELD,
            PLAN_TO_START_FIELD,
            SOURCE_OF_FUNDING_FIELD,
            EXAM_TAKEN_FIELD,
            NAME_FIELD,
            EMAIL_PASSWORD_FIELD,
            MOBILE_FIELD,
            RESIDENCE_LOCATION_FIELD,
            CALL_PREFERENCE_FIELD,
            CAPTCHA_FIELD
    );

    foreach($marketingPageForms[$pageName] as $marketingPageFormField) {
        $this->load->view(str_replace('marketing/','home/homepageRegistration/',$marketingPageFormField));
    }
    $submitBtnCaption = 'Submit';
    if($pageName == 'studyAbroad') {
        $submitBtnCaption = "I'm ready to Study Abroad";
    }
?>

    <div class="find-field-row">
    	<input type="submit"  id = 'submAbroad' value="<?php echo $submitBtnCaption; ?>" class="orange-button" <?php if(unified_registration_is_ldb_user == 'true') echo 'disabled = "true"'; ?> uniqueattr="homepageFindInstituteButton"/>
    </div>

    <?php
    if($pageName == 'studyAbroad') {
    ?> 
    <?php   } ?>
<script>
//FLAG_LOCAL_COURSE_FORM_SELECTION = false;
</script>
