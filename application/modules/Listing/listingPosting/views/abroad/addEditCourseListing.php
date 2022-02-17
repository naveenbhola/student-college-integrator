<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<script>var isAddMoreSpecializationLinkRequired;</script>
<?php
$shikshaApplyFlag = false;
$shikshaApplyChecked = '';
$applicationDetails = array();
$courseApplicationEligibilityDetails=$courseData['abroadCourseApplicationEligibiltyDetails'][0];
if(!(isset($isCloneFlag) && $isCloneFlag))
{
    $isCloneFlag=false;
}

if($formName == ENT_SA_FORM_EDIT_COURSE) {
    $pageTitle = "Edit Course";

    $dropDownUnivDisabled = "disabled=''";
    $dropDownCountryDisabled = "disabled=''";
    $dropDownDeptDisabled = "disabled=''";

    if($courseData['course_details']['status'] == 'draft'){
        $pageTitle = 'Edit Course<span style="color:red;"> (Draft state)</span>';
    } else if($courseData['course_details']['status'] == ENT_SA_PRE_LIVE_STATUS) {
        $pageTitle = 'Edit Course<span style="color:red;"> (Published version)</span>';
    }

    $displayData["breadCrumb"] = array(
        array("text" => "All Courses", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE ),
        array("text" => "Edit Course", "url" => "")
    );
    $displayData["pageTitle"] = $pageTitle;
    $displayData["lastUpdatedInfo"] = array(
        "date" => $courseData['listings_main']['last_modify_date'],
        "username" => $courseData['listings_main']['last_modified_by_firstname']." ".$courseData['listings_main']['last_modified_by_lastname']
    );

    $formName = ENT_SA_FORM_EDIT_COURSE;

} else {
    $displayData["breadCrumb"] = array(
        array("text" => "All Courses", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE ),
        array("text" => "Add New Course", "url" => "")
    );
    $displayData["pageTitle"] = "Add Course";
    $formName = ENT_SA_FORM_ADD_COURSE;
    $displayStatus = "none";
    $dropDownUnivDisabled = '';
    $dropDownCountryDisabled = '';
    $dropDownDeptDisabled = '';
    if($countryId != "" && !(isset($isCloneFlag) && $isCloneFlag)) {
        $dropDownCountryDisabled = "disabled=''";
    }
    if($deptInfo['deptId'] != "" && !(isset($isCloneFlag) && $isCloneFlag)) {
        $dropDownDeptDisabled = "disabled=''";
    }
    if($universityInfo['universityId'] != "" && !(isset($isCloneFlag) && $isCloneFlag)){
        $dropDownUnivDisabled = "disabled=''";
    }
    if($convertSnapshotToDetailFlag ==1){
        $dropDownDeptDisabled = "disabled=''";
        echo "<script>var getDepartmentDropDown = 1; var showSubcategorySelected = 1; var childCatId = ".$subCategoryIdofCourse.";</script>";

    }
}
if($isCloneFlag || $formName == ENT_SA_FORM_EDIT_COURSE)
{
    if(count($courseData['courseApplicationDetails'])>0)
    {
        $shikshaApplyFlag = true;
        $shikshaApplyChecked = 'checked="checked"';
        $applicationDetails  = $courseData['courseApplicationDetails'][0];
    }
    $displayStatus = "block";
    $univId = '';
    if(isset($universityInfo['universityId']) && $universityInfo['universityId'] != "")
        $univId = $universityInfo['universityId'];
    echo "<script>
var showSubcategorySelected = 1; var childCatId = ".$subCategoryIdofCourse.";
var univId='".$univId."';
</script>";

    $specializationCount = count($abroadCourseSpecializations);
    $selectedSpecializationCount = count($courseSpecializationIdArray);
    $numberOfaddMoreAllowed = $specializationCount - $selectedSpecializationCount;

    if($selectedSpecializationCount == 0)
    {
        $numberOfaddMoreAllowed = $numberOfaddMoreAllowed-1;
    }
    if($numberOfaddMoreAllowed >-1)
    {
        echo "<script>isAddMoreSpecializationLinkRequired = ".$numberOfaddMoreAllowed.";</script>";
    }
}

$otherSectionHeadingImageClass = ((
        ($formName == ENT_SA_FORM_ADD_COURSE) ||
        !$isCloneFlag
) ? " plus-icon":" minus-icon");

$countryDropDownHtml = '<option value="" >Select a Country</option>';
foreach($abroadCountries as $country) {
    if($countryId != ""  && $countryId == $country->getId()){
        $countryDropDownHtml .=  '<option selected="selected" value="'.$country->getId().'">'.$country->getName().'</option>';
    } else {
        $countryDropDownHtml .=  '<option value="'.$country->getId().'">'.$country->getName().'</option>';
    }
}

$categoryDropDownHtml = '<option value="" >Select a Parent Category</option>';
foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){
    if(!$isCloneFlag && $mainCategoryIdOfCourse != ""  && $mainCategoryIdOfCourse == $parentCategoryId){
        $categoryDropDownHtml .=  '<option selected="selected" value="'.$parentCategoryId.'">'.$parentCategoryDetails['name'].'</option>';
    } else {
        $categoryDropDownHtml .=  '<option value="'.$parentCategoryId.'">'.$parentCategoryDetails['name'].'</option>';
    }
}


$ldbCourseDropDownHtml = '<option value="" >Select a Desired Course</option>';
foreach($abroadMainLDBCourses as $key => $mainLDBCourse){
    if(!$isCloneFlag && is_array($desiredCourseIdArray) && in_array($mainLDBCourse['SpecializationId'], $desiredCourseIdArray) ){
        $ldbCourseDropDownHtml .=  '<option selected="selected" value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    } else {
        $ldbCourseDropDownHtml .=  '<option value="'.$mainLDBCourse['SpecializationId'].'">'.$mainLDBCourse['CourseName'].'</option>';
    }
}



?>
<div class="abroad-cms-rt-box">
    <?php
    // load the title section
    $this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
    ?>
    <form name="form_<?=$formName?>" id="form_<?=$formName?>" action="<?php echo $formPostUrl; ?>" enctype="multipart/form-data" method="post">
        <input type="hidden" name="listingStatus" id="listingStatus">
        <input type="hidden" name="snapshotCourseId" id="snapshotCourseId" value="<?=$courseData['course_id']?>">
        <input type="hidden" name="deptId" id="deptId" value="<?=$deptInfo['deptId']?>">
        <input name = "applicationEligibilityAddedOn" type="hidden" value="<?=($courseApplicationEligibilityDetails['addedOn'])?>">
        <input name = "applicationEligibilityAddedBy" type="hidden" value="<?=($courseApplicationEligibilityDetails['addedBy'])?>">

        <?php
        if($formName == ENT_SA_FORM_EDIT_COURSE) {
            ?>
            <input type="hidden" name="courseId" id="courseId" value="<?=$courseId?>">
        <?php   }
        if($formName == ENT_SA_FORM_EDIT_COURSE) {
            $info = array();
            $info['percentage_completion'] = $courseData['course_details']['profile_percentage_completion'];
            $info['percentage_completion'] = empty($info['percentage_completion']) ? 0 : $info['percentage_completion'];
            $this->load->view("listingPosting/abroad/listingProgressBar", $info);
        }
        ?>
        <div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite minus-icon"></i>Parent Association</h3>
                <div class="cms-form-wrap cms-accordion-div">
                    <ul>
                        <li>
                            <label>Country* : </label>
                            <div class="cms-fields">
                                <select <?=$dropDownCountryDisabled?> class="universal-select cms-field" validationType="select" id="country_<?=$formName?>" name="country_<?=$formName?>" caption="Country" onblur="showErrorMessage(this, '<?=$formName?>');" required=true onChange="showErrorMessage(this, '<?=$formName?>'); getUniversitiesDropDownForCountry('<?=$formName?>');">
                                    <?=$countryDropDownHtml?>
                                </select>
                                <div style="display: none" class="errorMsg" id="country_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Parent University Name* : </label>
                            <div class="cms-fields">
                                <select <?=$dropDownUnivDisabled?> tooltip="cour_parentUniv" validationType="select" class="universal-select cms-field" name="university_<?=$formName?>" id="university_<?=$formName?>" caption="University" onblur="showErrorMessage(this, '<?=$formName?>');" required=true onChange="showErrorMessage(this, '<?=$formName?>'); getDepartmentsDropDownForUniversity('<?=$formName?>');getShikshaApplyProfileForUniversity('<?=$formName?>');" >
                                    <option value="">Select a University</option>
                                    <?php	if(isset($universityInfo['universityId']) && $universityInfo['universityId'] != "") { ?>
                                        <option value="<?=$universityInfo['universityId']?>" SELECTED><?=$universityInfo['universityName']?></option>
                                    <?php 	}	?>
                                </select>
                                <div style="display: none" class="errorMsg" id="university_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <?php
                        $university_type = $courseData['university_table_info']['type_of_institute2'];
                        $showHideDept = $university_type == 'college' ? 'style="display:none;"' : '';
                        ?>
                        <li <?=$showHideDept?>>
                            <label>Parent School/Dept Name* : </label>
                            <div class="cms-fields">
                                <?php 	if($convertSnapshotToDetailFlag==1){
                                    $dropDownDeptDisabled = "";
                                }
                                ?>
                                <select <?=$dropDownDeptDisabled?> tooltip="cour_parentDept" validationType="select" class="universal-select cms-field last-in-section" id="departments_<?=$formName?>" name="departments_<?=$formName?>" caption="Department" onblur="showErrorMessage(this, '<?=$formName?>');" required=true onChange="showErrorMessage(this, '<?=$formName?>');" >
                                    <?php	if(isset($deptInfo['deptId']) && $deptInfo['deptId'] != "") {	?>
                                        <option value="">Select a Department</option>
                                        <option value="<?=$deptInfo['deptId']?>" selected ="selected"><?=$deptInfo['deptName']?></option>
                                    <?php 	} else {	?>
                                        <option value="">Select a Department</option>
                                    <?php 	} 	?>
                                </select>
                                <div style="display: none" class="errorMsg" id="departments_<?=$formName?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>LDB Mapping Association</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Parent Category* : </label>
                            <div class="cms-fields">
                                <select validationType="select" class="universal-select cms-field" id="parentCat_<?=$formName?>" name="parentCat_<?=$formName?>" caption="parent category" onchange="showErrorMessage(this, '<?=$formName?>'); appendChildCategories('<?=$formName?>');verifyFieldsAndPopulateSpecializations('<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" required=true>
                                    <?=$categoryDropDownHtml?>
                                </select>
                                <div style="display: none" class="errorMsg" id="parentCat_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Level* : </label>
                            <div class="cms-fields course_level" style="margin-top:4px;">
                                <?php $temp = 0;
                                foreach($courseLevels as $courseLevel){
                                    if(++$temp<=1){ ?>
                                        <label style="width:auto;" onclick="verifyFieldsAndPopulateSpecializations('<?=$formName?>');"><input tooltip="cour_level" class="align-middle" caption="course level" id="courseLevel" required=true validationType="radio" type="radio" name="courseLevel" <?php if(!$isCloneFlag && $courseData['course_details']['course_level_1']==$courseLevel['CourseName']){ echo 'checked="checked"';}?> value="<?=$courseLevel['CourseName']?>" /> <?=$courseLevel['CourseName']?> </label>
                                    <?php }else{	?>
                                        <label style="width:auto;" onclick="verifyFieldsAndPopulateSpecializations('<?=$formName?>');"><input tooltip="cour_level" class="align-middle" type="radio" name="courseLevel" <?php if(!$isCloneFlag && $courseData['course_details']['course_level_1']==$courseLevel['CourseName']){ echo 'checked="checked"';}?> value="<?=$courseLevel['CourseName']?>" /> <?=$courseLevel['CourseName']?> </label>
                                    <?php } ?>
                                <?php }	?>
                                <div style="display: none;clear:both;" class="errorMsg" id="courseLevel_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Subcategory* : </label>
                            <div class="cms-fields">
                                <select validationType="select" class="universal-select cms-field" id="childCat_<?=$formName?>" name="childCat_<?=$formName?>" caption="subcategory" onchange="showErrorMessage(this, '<?=$formName?>');verifyFieldsAndPopulateSpecializations('<?=$formName?>')" onblur="showErrorMessage(this, '<?=$formName?>');" required=true>
                                    <option value="">Select a Subcategory</option>
                                </select>
                                <div style="display: none" class="errorMsg" id="childCat_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Desired course : </label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field last-in-section" id="ldbcourses_dropdown" name="ldbcourses_dropdown">
                                    <?=$ldbCourseDropDownHtml?>
                                </select>
                            </div>
                        </li>

                        <li>
                            <div class="add-more-sec2 clear-width">
                                <ul class = "specializationInputBlock">
                                    <?php if(!$isCloneFlag && !empty($courseSpecializationIdArray)){
                                        $i=0;
                                        foreach($courseSpecializationIdArray as $k=>$specializationData){ ?>
                                            <li class = "courseSpecializationInputRow mb25">
                                                <ul>
                                                    <li>
                                                        <div id="courseSpecializationInputName_<?php echo $i; ?>_<?=$formName?>"><label>Course Specialization*: </label>
                                                            <div class="cms-fields">
                                                                <select class="universal-select cms-field last-in-section" name='coursespecialization_field_name[]' id="courseSpecializationName_<?php echo $i; ?>_<?=$formName?>" onblur = "showErrorMessage(this,'<?=$formName?>')" validationtype="select" onchange="" caption = "Course Specialization" required=true>
                                                                    <option value="" >Select a Course Specialization</option>
                                                                    <?php foreach ($abroadCourseSpecializations as $key => $abroadCourseSpecializationData) { ?>
                                                                        <option <?php  if($abroadCourseSpecializationData['SpecializationId'] == $specializationData['Id']) echo 'selected="selected"'; ?>value="<?php echo $abroadCourseSpecializationData['SpecializationId']; ?>" ><?php echo $abroadCourseSpecializationData['SpecializationName']; ?></option>
                                                                    <?php }?>
                                                                </select>
                                                                <div style="display: none" class="errorMsg" id="courseSpecializationName_<?php echo $i; ?>_<?=$formName?>_error"></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div id="courseSpecializationInputDesc_<?php echo $i; ?>_<?=$formName?>"><label>Specialization description : </label>
                                                            <div class="cms-fields">
                                                                <textarea name='coursespecialization_field_desc[]' id="courseSpecializationDesc_<?php echo $i; ?>_<?=$formName?>" class="cms-textarea"  maxlength="1000" onblur = "showErrorMessage(this, '');" onchange = "showErrorMessage(this, '');" caption = "Course Specialization Description" validationType = "str"><?php echo $specializationData['desc']; ?></textarea>
                                                                <div style="display: none" class="errorMsg" id="courseSpecializationDesc_<?php echo $i; ?>_<?=$formName?>_error"></div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <a class="remove-link flLt" <?php if($i==0)echo'style="display:none;"'; ?>href="Javascript:void(0);" onclick = "removeCourseSpecializationFields(this);" style="margin:5px 30px 0 251px"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                            </li>
                                            <?php $i++; }
                                    }
                                    else if(empty($courseSpecializationIdArray) && !empty($abroadCourseSpecializations))
                                    {
                                        $i=0; ?>
                                        <li class = "courseSpecializationInputRow mb25">
                                            <ul>
                                                <li>
                                                    <div id="courseSpecializationInputName_<?php echo $i; ?>_<?=$formName?>"><label>Course Specialization*: </label>
                                                        <div class="cms-fields">
                                                            <select class="universal-select cms-field last-in-section" name='coursespecialization_field_name[]' id="courseSpecializationName_<?php echo $i; ?>_<?=$formName?>" onblur = "showErrorMessage(this,'<?=$formName?>')" validationtype="select" onchange="" caption = "Course Specialization" required=true>
                                                                <option value="" >Select a Course Specialization</option>
                                                                <?php foreach ($abroadCourseSpecializations as $key => $abroadCourseSpecializationData) { ?>
                                                                    <option value="<?php echo $abroadCourseSpecializationData['SpecializationId']; ?>" ><?php echo $abroadCourseSpecializationData['SpecializationName']; ?></option>
                                                                <?php }?>
                                                            </select>
                                                            <div style="display: none" class="errorMsg" id="courseSpecializationName_<?php echo $i; ?>_<?=$formName?>_error"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div id="courseSpecializationInputDesc_<?php echo $i; ?>_<?=$formName?>"><label>Specialization description : </label>
                                                        <div class="cms-fields">
                                                            <textarea name='coursespecialization_field_desc[]' id="courseSpecializationDesc_<?php echo $i; ?>_<?=$formName?>" class="cms-textarea"  maxlength="1000" onblur = "showErrorMessage(this, '');" onchange = "showErrorMessage(this, '');" caption = "Course Specialization Description" validationType = "str"><?php echo $specializationData['desc']; ?></textarea>
                                                            <div style="display: none" class="errorMsg" id="courseSpecializationDesc_<?php echo $i; ?>_<?=$formName?>_error"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <a class="remove-link flLt" <?php if($i==0)echo'style="display:none;"'; ?>href="Javascript:void(0);" onclick = "removeCourseSpecializationFields(this);" style="margin:5px 30px 0 251px"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                        </li>
                                        <?php $i++;
                                    }
                                    else
                                    { 	//check if all the above options are selected based on that populate the Course Specialization through ajax
                                        //incase not then disable the dropdowns $SpecializationSelection ='disable="disable"';
                                        ?>
                                        <li class = "courseSpecializationInputRow b25">
                                            <ul>
                                                <li>
                                                    <div id="courseSpecializationInputName_0_<?=$formName?>"><label>Course Specialization*: </label>
                                                        <div class="cms-fields">
                                                            <select class="universal-select cms-field last-in-section" name='coursespecialization_field_name[]' id="courseSpecializationName_0_<?=$formName?>" onblur = "showErrorMessage(this,'<?=$formName?>')" validationtype="select" onchange="showErrorMessage(this,'<?=$formName?>')" caption = "Course Specialization" required=true>
                                                                <option value="" >Select a Course Specialization</option>
                                                            </select>
                                                            <div style="display: none" class="errorMsg" id="courseSpecializationName_0_<?=$formName?>_error"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div id="courseSpecializationInputDesc_0_<?=$formName?>"><label>Specialization description : </label>
                                                        <div class="cms-fields">
                                                            <textarea name='coursespecialization_field_desc[]' id="courseSpecializationDesc_0_<?=$formName?>" class="cms-textarea"  maxlength="1000" onblur = "showErrorMessage(this, '');" onchange = "showErrorMessage(this, '');" caption = "Course Specialization Description" validationType = "str"></textarea>
                                                            <div style="display: none" class="errorMsg" id="courseSpecializationDesc_0_<?=$formName?>_error"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCourseSpecializationFields(this);" style="margin:5px 30px 0 0;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a <?php echo($numberOfaddMoreAllowed >0)? '':'style="display:none;"'; ?> id="addCourseSpecializationLink" class="add-more-link last-in-section" href="Javascript:void(0);" onclick = "addCourseSpecializationFields(this);">[+] Add more</a>
                            <div style="display: none" class="errorMsg" id="validateUniqueCourseErrorDiv"></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Basic Course Info</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Course Exact Name* : </label>
                            <div class="cms-fields">
                                <input value="<?=!$isCloneFlag?htmlspecialchars($courseData['listings_main']['course_name']):''?>" tooltip="cour_exName" validationType="str" id="courseName_<?=$formName?>" name="courseName_<?=$formName?>" caption="Course name" onblur="showErrorMessage(this, '<?=$formName?>');" maxlength="100" minlength="3" required=true class="universal-txt-field cms-text-field" type="text"/>
                                <div style="display: none" class="errorMsg" id="courseName_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>NZQF Categorization:</label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field" name="nzqfCategorization">
                                    <option value="">Select</option>
                                    <?php for($k=1;$k<11;$k++){?>
                                        <option <?php echo ($courseAttributes['nzqfCategorization']==$k)?'selected="selected"':''?> value="<?php echo $k;?>"> Level <?php echo  $k;?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <input type="hidden" name="courseType" value="Degree"/>
                        </li>

                        <li>
                            <label>Affiliation details : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseAttributes['AffiliatedTo']?>" tooltip="cour_affDetails" validationType="str" class="universal-txt-field cms-text-field" type="text" name="affiliationDetails" id="affiliationDetails" maxlength="100" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Affiliation details" />
                                <div style="display: none" class="errorMsg" id="affiliationDetails_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Accreditation details : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseAttributes['courseAccreditation']?>" tooltip="cour_accrDetails" validationType="str" class="universal-txt-field cms-text-field" type="text" name="accreditationDetails" id="accreditationDetails" maxlength="100" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Accreditation details" />
                                <div style="display: none" class="errorMsg" id="accreditationDetails_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course website link* : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['courseWebsite']?>" tooltip="cour_webLink" validationType="link" id="website_<?=$formName?>" name="website_<?=$formName?>" caption="course website" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-txt-field cms-text-field" type="text"/>
                                <div style="display: none" class="errorMsg" id="website_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Duration* : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_details']['duration_value']?>" tooltip="cour_duration" maxlength="100" validationType="str" class="universal-txt-field cms-text-field" caption="course duration" type="text" id="courseDuration_<?=$formName?>" name="courseDuration_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');" required=true />
                                <div style="display: none" class="errorMsg" id="courseDuration_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Duration II* : </label>
                            <div class="cms-fields">
                                <select class="universal-select cms-field2" validationType="select" required=true id="courseDuration2_<?=$formName?>" name="courseDuration2" onblur="showErrorMessage(this, '<?=$formName?>');" caption = "duration II">
                                    <option value="">Select</option>
                                    <option value="Years" <?=($courseData['course_details']['duration_unit']=="Years"?"selected='selected'":"")?>>Years</option>
                                    <option value="Months" <?=($courseData['course_details']['duration_unit']=="Months"?"selected='selected'":"")?>>Months</option>
                                    <option value="Weeks" <?=($courseData['course_details']['duration_unit']=="Weeks"?"selected='selected'":"")?>>Weeks</option>
                                </select>
                                <div style="display: none" class="errorMsg" id="courseDuration2_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Start Date : </label>
                            <div class="cms-fields">
                                <?php
                                $monthArray = array(1 => "January", 2 =>"February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
                                if(count($courseStartDateInfo)) {
                                    foreach($courseStartDateInfo  as $key => $dbMonthValue) {
                                        ?>
                                        <div class="add-more-sec" style="display: block;">
                                            <select onchange="hideCourseStartDateErrorMsg();" name="courseStartDate[]" id="courseStartDate<?=$key?>" class="universal-select cms-field2" tooltip="cour_startDate">
                                                <option value="">Select a Month</option>
                                                <?php
                                                foreach($monthArray as $monthNo => $value) {
                                                    $selected = "";
                                                    if($dbMonthValue == $monthNo){
                                                        $selected = "selected";
                                                    }
                                                    ?>
                                                    <option <?=$selected?> value="<?=$monthNo?>"><?=$value?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php	if($key > 0) {	?>
                                                <a onclick="removeAddedElement(this)" class="remove-link-2" href="javascript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Start Date</a>
                                            <?php	}	?>
                                        </div>
                                        <?php
                                    }

                                } else {
                                    ?>
                                    <div class="add-more-sec">
                                        <select tooltip="cour_startDate" class="universal-select cms-field2" id="courseStartDate0" name="courseStartDate[]" onChange='hideCourseStartDateErrorMsg();'>
                                            <option value="" selected="">Select a Month</option>
                                            <?php
                                            foreach($monthArray as $monthNo => $value) {
                                                ?>
                                                <option value="<?=$monthNo?>"><?=$value?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php 	} 	?>
                                <a href="javascript:void(0);" onclick="cloneCourseStartDateDropDown(this)">[+] Add More</a>
                                <div style="display: none" class="errorMsg" id="courseStartDate_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Duration Link : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['courseDurationLink']?>" tooltip="cour_durationLink" validationType="link" class="universal-txt-field cms-text-field" caption="course duration link" type="text" id="courseDurationLink_<?=$formName?>" name="courseDurationLink_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');" />
                                <div style="display: none" class="errorMsg" id="courseDurationLink_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Course Description* : </label>
                            <div class="cms-fields">
                                <textarea tooltip="cour_description" validationType="html" class="cms-textarea tinymce-textarea" maxlength="4000" caption="course description" id="courseDescription_<?=$formName?>" name="courseDescription_<?=$formName?>" required=true><?php echo $listingAttributes['Course_Description']; ?></textarea>
                                <div style="display: none" class="errorMsg" id="courseDescription_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Ranking:</label>
                            <div class="cms-fields">
                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Ranking" id="courseRanking_<?=$formName?>" name="courseRanking_<?=$formName?>"><?php echo (isset($courseAttributes['courseRanking']))?$courseAttributes['courseRanking']:''?></textarea>
                                <div style="display: none" class="errorMsg" id="courseRanking_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Curriculum:</label>
                            <div class="cms-fields">
                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Curriculum" id="curriculum_<?=$formName?>" name="curriculum_<?=$formName?>"><?php echo (isset($courseAttributes['curriculum']))?$courseAttributes['curriculum']:''?></textarea>
                                <div style="display: none" class="errorMsg" id="curriculum_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Application Deadline link : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['applicationDeadlineLink']?>" tooltip="cour_deadLineLink" validationType="link" caption="Application Deadline link" id="applicationDeadlineLink" name="applicationDeadlineLink" class="universal-txt-field cms-text-field last-in-section" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="applicationDeadlineLink_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Course Eligibility</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Eligibility for 12th/Bachelors cut-off Link: </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['admissionWebsiteLink']?>" tooltip="cour_admWebLink" validationType="link" class="universal-txt-field cms-text-field" type="text" caption="Eligibility for 12th/Bachelors cut-off" id="admissionWebsiteLink" name="admissionWebsiteLink" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="admissionWebsiteLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>English Language proficiency scores Link: </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['englishProficiencyLink']?>" validationType="link" class="universal-txt-field cms-text-field" type="text" caption="English Language proficiency scores" id="admissionWebsiteLink" name="englishProficiencyLink" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="englishProficiencyLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>GMAT/GRE/SAT/Experience Link: </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['anyOtherEligibility']?>" validationType="link" class="universal-txt-field cms-text-field" type="text" caption="GMAT/GRE/SAT/Experience" id="anyOtherEligibility" name="anyOtherEligibility" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="anyOtherEligibility_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Exams Info : </label>
                            <div class="cms-fields">
                                <textarea validationType="html" class="cms-textarea tinymce-textarea" tooltip="cour_xamReq" maxlength="5000" caption="Exams Required" id="examsRequiredFreeText" name="examsRequiredFreeText"><?= (isset($courseAttributes['examRequired']))?($courseAttributes['examRequired']):''?></textarea>
                                <div style="display: none" class="errorMsg" id="examsRequiredFreeText_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Exams Required* : </label>
                            <div class="cms-fields" id="examRequiredContainer">
                                <ol>
                                    <?php
                                    foreach($abroadExamsMasterList as $key => $examArray) {
                                        if($key % 3 == 0) {
                                            if($key != 0) {
                                                echo "</li>";
                                            }
                                            echo "<li>";
                                        }
                                        $cutOff = "";
                                        $comments = "";
                                        $isChecked = "";
                                        $display="none";
                                        if(in_array($examArray['examId'], array_keys($examData))) {
                                            $isChecked = "checked=true";
                                            $cutOff = $examData[$examArray['examId']]['cutOff'];
                                            $comments = $examData[$examArray['examId']]['comments'];
                                            $display="block";
                                        }
                                        ?>
                                        <div class="exam-chkboxes">
                                            <input <?=$isChecked?> tooltip="<?php switch($examArray['examId']){
                                                case 1: echo "cour_xamReqtoefl";break;
                                                case 2: echo "cour_xamReqielts";break;
                                                case 3: echo "cour_xamReqpte";break;
                                                case 4: echo "cour_xamReqgre";break;
                                                case 5: echo "cour_xamReqgmat";break;
                                                case 6: echo "cour_xamReqsat";break;
                                                case 7: echo "cour_xamReqcael";break;
                                                case 8: echo "cour_xamReqmelab";break;
                                                case 9: echo "cour_xamReqCamCert";break;}?>" type="checkbox" value="<?=$examArray['examId']?>" onClick="toggelMoreExamOptions(this);hideCourseExamDataError();" id="examRequired<?=$examArray['examId']?>" name="examRequired[]" />
                                            <?=$examArray['exam']?>
                                            <div class="more-checkbx-form" id="more-option-container-<?=$examArray['examId']?>" style="display: <?=$display?>;">
                                                <label><?=$examArray['exam']?> Cutoff*</label>
                                                <select tooltip="<?php switch($examArray['examId']){
                                                    case 1: echo "cour_cftoefl";break;
                                                    case 2: echo "cour_cfielts";break;
                                                    case 3: echo "cour_cfpte";break;
                                                    case 4: echo "cour_cfgre";break;
                                                    case 5: echo "cour_cfgmat";break;
                                                    case 6: echo "cour_cfsat";break;
                                                    case 7: echo "cour_cfcael";break;
                                                    case 8: echo "cour_cfmelab";break;
                                                    case 9: echo "cour_cfCamCert";break;}?>" class="universal-select select-cutoff" id="examRequiredCutOff<?=$examArray['examId']?>" name="examRequiredCutOff<?=$examArray['examId']?>" onchange='$j("#examRequired<?=$examArray['examId']?>_error").hide();'>
                                                    <option value="">Select Cutoff</option>
                                                    <option value="N/A" <?=($cutOff=='N/A'?'selected="selected"':'')?>>No specific cut-off mentioned</option>
                                                    <?php
                                                    $currentRange = $examArray['minScore'];
                                                    if(is_numeric($examArray['range']) && $currentRange < $examArray['maxScore']) {
                                                        for($i = $currentRange; $i <= $examArray['maxScore']; $i = $i+$examArray['range']){
                                                            $selected = '';
                                                            if($cutOff != "" && $cutOff == $i) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                            ?>
                                                            <option <?=$selected?> value="<?=$i?>"><?=$i?></option>
                                                        <?php					}
                                                    } else {
                                                        $rangeArray = split(",", $examArray['range']);
                                                        foreach($rangeArray as $key => $rangeValue) {
                                                            $selected = '';
                                                            if($cutOff != "" && $cutOff == $rangeValue) {
                                                                $selected = 'selected="selected"';
                                                            }
                                                            ?>
                                                            <option <?=$selected?> value="<?=$rangeValue?>"><?=$rangeValue?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                                <label>Comments (If any)</label>
                                                <textarea class="cms-textarea comment-area" id="examComments<?=$examArray['examId']?>" name="examComments<?=$examArray['examId']?>"" maxlength="100"><?php echo strip_tags($comments);?></textarea>
                                                <div style="display: none" class="errorMsg" id="examRequired<?=$examArray['examId']?>_error"></div>
                                            </div>
                                        </div>
                                    <?php		}	?>
                                    <div style="display: none" class="errorMsg" id="examRequiredSection_error"></div>
                                    </li>
                                    <?php
                                    if(count($customExamData) && $shikshaApplyFlag==false) {
                                        foreach($customExamData as $key => $examArray) {
                                            ?>
                                            <li class="add-more-sec2 customExamsDiv">
                                                <div class="cms-form-wrap" style="margin:0;">
                                                    <div class="more-checkbx-form flLt">
                                                        <label>Exam: </label>
                                                        <input value="<?=$examArray['examName']?>" tooltip="cour_xamReqOther"  class="universal-txt-field" type="text" name="customExam[]" id="customExam<?=$key?>" maxlength="20"/>
                                                    </div>
                                                    <div class="more-checkbx-form flLt">
                                                        <label>Cut Off*: </label>
                                                        <input value="<?=$examArray['cutoff']?>" tooltip="cour_cfOthers" class="universal-txt-field" type="text" name="customExamCutOffs<?=$key?>" id="customExamCutOffs<?=$key?>" maxlength="20" onblur='$j("#customExamRequired_error").hide();'/>
                                                    </div>
                                                    <div class="more-checkbx-form flLt">
                                                        <label>Comments (If Any): </label>
                                                        <textarea class="cms-textarea comment-area" name="customExamComments<?=$key?>" id="customExamComments<?=$key?>" maxlength="100"><?=$examArray['comments']?></textarea>
                                                    </div>
                                                </div>
                                                <a href="javascript:void(0);" class="remove-link-2" onclick="removeAddedElement(this, 1)"><i class="abroad-cms-sprite remove-icon"></i>Remove Exam</a>
                                            </li>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="add-more-sec2 customExamsDiv"  style="display: none;">
                                            <div class="cms-form-wrap" style="margin:0;">
                                                <div class="more-checkbx-form flLt">
                                                    <label>Exam: </label>
                                                    <input tooltip="cour_xamReqOther"  class="universal-txt-field" type="text" name="customExam[]" id="customExam0" maxlength="20"/>
                                                </div>
                                                <div class="more-checkbx-form flLt">
                                                    <label>Cut Off*: </label>
                                                    <input tooltip="cour_cfOthers" class="universal-txt-field" type="text" name="customExamCutOffs0" id="customExamCutOffs0" maxlength="20" onblur='$j("#customExamRequired_error").hide();'/>
                                                </div>
                                                <div class="more-checkbx-form flLt">
                                                    <label>Comments (If Any): </label>
                                                    <textarea class="cms-textarea comment-area" name="customExamComments0" id="customExamComments0" maxlength="100"></textarea>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="remove-link-2" onclick="removeAddedElement(this, 1)"><i class="abroad-cms-sprite remove-icon"></i>Remove Exam</a>
                                        </li>
                                        <?php
                                    }
                                    ?>
                                    <a href="javascript:void(0);" id="customExamAddMoreLink" onclick="addMoreExamToCourse(this)" class="last-in-section">[+] Add Another Exam</a>
                                    <div style="display: none" class="errorMsg" id="customExamRequired_error"></div>
                                </ol>
                            </div>
                        </li>
                    </ul>
                    <div class="cms-form-wrap">
                        <ul>
                            <li>
                                <div class="requirement-field">
                                    <input type="checkbox" name="isRequired12thCutOff" id="isRequired12thCutOff" value="1" <?php echo (($courseApplicationEligibilityDetails['12thCutoff']!=0 && $courseApplicationEligibilityDetails['12thCutoff']!='') || $courseApplicationEligibilityDetails['12thcomments']!='')?'checked="checked"':'';?> /> Class 12th<br />
                                    <div class="requirement-field-sec">
                                        <label>Class 12th Cutoff*</label>
                                        <select name="12thCutoff" id="12thCutoff" class="universal-select cms-field" style="width:auto;">
                                            <option value="">Select Cutoff</option>
                                            <option value="-1" <?= ($courseApplicationEligibilityDetails['12thCutoff']==-1)?'selected="selected"':''?>>No specific cut-off mentioned</option>
                                            <?php for($x=40;$x<100;$x++){?>
                                                <option <?= ($courseApplicationEligibilityDetails['12thCutoff']==$x)?'selected="selected"':''?> value="<?= $x;?>"><?=$x;?></option>
                                            <?php }?>
                                        </select>
                                        <div style="display: none" class="errorMsg" id="12thCutoff_error"></div>
                                        <label>Comments (If any)</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" caption="Class 12th Cutoff Description" id="12thCutOffDescription_<?=$formName?>" name="12thCutOffDescription_<?=$formName?>" maxlength="2000"><?= $courseApplicationEligibilityDetails['12thcomments'];?></textarea>
                                    <div style="display: none" class="errorMsg" id="12thCutOffDescription_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>3 Years Degree Acceptable Flag:</label>
                                <div class="cms-fields ">
                                    <label style="width: auto;" onclick=""><input class="align-middle" <?= (isset($courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']) && $courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']==1)?'checked="checked"':''?> type="radio" value="1" name="isThreeYearDegreeAccepted" id="isThreeYearDegreeAccepted_yes"/> Yes</label>
                                    <label style="width: auto;" onclick=""><input class="align-middle" <?= (isset($courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']) && $courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']==2)?'checked="checked"':''?> type="radio" value="2" name="isThreeYearDegreeAccepted" id="isThreeYearDegreeAccepted_no"/> No</label>
                                    <label style="width: auto;" onclick=""><input class="align-middle" <?= (isset($courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']) && $courseApplicationEligibilityDetails['isThreeYearDegreeAccepted']==3)?'checked="checked"':''?> type="radio" value="3" name="isThreeYearDegreeAccepted" id="isThreeYearDegreeAccepted_no"/> Conditional</label>
                                </div>
                            </li>
                            <li>
                                <label>3 Years Degree Acceptable Text:</label>
                                <div class="cms-fields">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" caption="3 Years Degree Acceptable Text" id="threeYearDegreeDescription_<?=$formName?>" name="threeYearDegreeDescription_<?=$formName?>" maxlength="5000"><?= $courseApplicationEligibilityDetails['threeYearDegreeDescription'];?></textarea>
                                    <div style="display: none" class="errorMsg" id="threeYearDegreeDescription_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field">
                                    <input type="checkbox" name="isRequiredBachelorCutOff" id="isRequiredBachelorCutOff" value="1" <?php echo (($courseApplicationEligibilityDetails['bachelorCutoff']!=0 && $courseApplicationEligibilityDetails['bachelorCutoff']!='') || $courseApplicationEligibilityDetails['bachelorComments']!='' || $courseApplicationEligibilityDetails['bachelorScoreUnit'] !='')?'checked="checked"':'';?> /> Bachelors<br />
                                    <div class="requirement-field-sec">
                                        <p style="margin:5px 0;">
                                            <label style="float: left; width: 59px;">Score in:</label>
                                            <label style="float: left; width: 100px;" onclick="validateBachelorCutOff();"><input <?= ($courseApplicationEligibilityDetails['bachelorScoreUnit']=='Percentage')?'checked="checked"':''?> name="bachelorScoreUnit" type="radio" value="Percentage" /> Percentage</label>
                                            <label style="float: left; width: 100px;" onclick="validateBachelorCutOff();"><input <?= ($courseApplicationEligibilityDetails['bachelorScoreUnit']=='GPA')?'checked="checked"':''?> name="bachelorScoreUnit" type="radio" value="GPA" /> GPA</label>
                                        </p>
                                        <p style="clear:both;">
                                            <?php
                                            if($courseApplicationEligibilityDetails['bachelorScoreUnit']=='Percentage'){
                                                $start = 40;
                                                $end = 101;
                                                $range = 1;
                                                $bcutdisabled='';
                                            }elseif($courseApplicationEligibilityDetails['bachelorScoreUnit']=='GPA'){
                                                $start = 1;
                                                $end = 10.1;
                                                $bcutdisabled='';
                                                $range = .1;
                                            }else{
                                                $start = 0;
                                                $end = 0;
                                                $range = 0;
                                                $bcutdisabled='disabled="disabled"';
                                            }

                                            ?>
                                            <label>Bachelors Cutoff*</label>
                                            <select <?= $bcutdisabled;?> name="bachelorCutoff" id="bachelorCutoff" class="universal-select cms-field" style="width:auto;">
                                                <option value="">Select Cutoff</option>
                                                <option value="-1" <?= ($courseApplicationEligibilityDetails['bachelorCutoff']==-1)?'selected="selected"':''?>>No specific cut-off mentioned</option>
                                                <?php for($x=$start;$x<$end;$x+=$range){
                                                    $x = number_format($x,1);
                                                    ?>
                                                    <option <?= ($courseApplicationEligibilityDetails['bachelorCutoff']==$x)?'selected="selected"':''?> value="<?= $x;?>"><?=$x;?></option>
                                                <?php }?>
                                            </select>
                                        </p>
                                        <div style="display: none" class="errorMsg" id="bachelorCutoff_error"></div>
                                        <label>Comments (If any)</label>

                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Bachelor Cutoff Description" id="bachelorCutOffDescription_<?=$formName?>" name="bachelorCutOffDescription_<?=$formName?>"><?= $courseApplicationEligibilityDetails['bachelorComments'];?></textarea>
                                    <div style="display: none" class="errorMsg" id="bachelorCutOffDescription_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <div class="requirement-field">
                                    <input type="checkbox" name="isRequiredPgCutOff" id="isRequiredPgCutOff" value="1" <?php echo ($courseApplicationEligibilityDetails['pgCutoff']!='' || $courseApplicationEligibilityDetails['pgComments'] !='')?'checked="checked"':'';?> /> Post Graduate<br />
                                    <div class="requirement-field-sec">
                                        <label>Post Graduate Cutoff*</label>
                                        <input name="pgCutOff" id="pgCutOff" type="text" class="universal-txt-field" validationType="str" maxlength="10" onblur="showErrorMessage(this, '');" caption="PG Cutoff" style="width:80px;font-size:12px;" placeholder="Cut Off" value="<?= $courseApplicationEligibilityDetails['pgCutoff'];?>"/><br />
                                        <div style="display: none" class="errorMsg" id="pgCutOff_error"></div>
                                        <label>Comments (If any)</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="cms-fields">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="PG Cutoff Description" id="pgCutOffDescription_<?=$formName?>" name="pgCutOffDescription_<?=$formName?>"><?= $courseApplicationEligibilityDetails['pgComments'];?></textarea>
                                    <div style="display: none" class="errorMsg" id="pgCutOffDescription_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>Work Experience:</label>
                                <div class="requirement-field">
                                    <select class="universal-select cms-field" name="isWorkExperinceRequired" onchange="validateWorkExperinceRequired();" id="isWorkExperinceRequired" style="width:auto; float:left">
                                        <option value="">Select Work exp. required</option>
                                        <option <?= ($courseApplicationEligibilityDetails['isWorkExperinceRequired']==1)?'selected="selected"':'';?> value="1">Yes</option>
                                        <option <?= ($courseApplicationEligibilityDetails['isWorkExperinceRequired']==2)?'selected="selected"':'';?>value="2">No</option>
                                    </select>
                                    <select class="universal-select cms-field" name="workExperniceValue" id="workExperniceValue" style="width:auto; float:left">
                                        <option value="">Select Years of exp.</option>
                                        <?php for($x=0.5;$x<=25;$x+=.5){?>
                                            <option <?= ($courseApplicationEligibilityDetails['workExperniceValue']==$x)?'selected="selected"':'';?> value="<?= $x;?>"><?= $x;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label>Work exp. details:</label>
                                <div class="requirement-field">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="2000" caption="Wrok Exp. Description" id="workExpDescription_<?=$formName?>" name="workExpDescription_<?=$formName?>"><?= $courseApplicationEligibilityDetails['workExperinceDescription'];?></textarea>
                                    <div style="display: none" class="errorMsg" id="workExpDescription_<?=$formName?>_error"></div>
                                </div>
                            </li>
                            <li>
                                <label>Apply document checklist : </label>
                                <div class="cms-fields">
                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Apply document checklist" id="applyDocumentChecklist_<?=$formName?>" name="applyDocumentChecklist_<?=$formName?>"><?php echo (isset($courseApplicationEligibilityDetails['applyDocumentChecklist']))?$courseApplicationEligibilityDetails['applyDocumentChecklist']:''?></textarea>
                                    <div style="display: none" class="errorMsg" id="applyDocumentChecklist_<?=$formName?>_error"></div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <?php
                    $examListByType = array();
                    foreach($abroadExamsMasterList as $key=>$value)
                    {
                        $examListByType[$value['examId']] = $value;
                    }
                    //_p($examListByType);
                    ?>


                    <div class="">
                        <div class="cms-fields">
                            <label><input <?= $shikshaApplyChecked;?> type="checkbox" name="shikshaApply" id="shikshaApply" value="1" onclick="toggleCourseEntryRequirements();"><strong>Shiksha Apply for this course</strong></label>
                            <input name = "shikshaApplyAddedAt" type="hidden" value="<?=($applicationDetails['addedOn'])?>">
                            <input name = "shikshaApplyAddedBy" type="hidden" value="<?=($applicationDetails['addedBy'])?>">
                        </div>
                        <div class="cms-form-wrap" id="universityCourseProfileContainer">
                            <ul>
                                <li>
                                    <label>University course profile* : </label>
                                    <div class="cms-fields">
                                        <select class="universal-select cms-field" name="universityCourseProfileId" id="universityCourseProfileId" validationType="select" onblur="showErrorMessage(this, '');" onChange="showErrorMessage(this, '');showApplyNowlinkUrlOfuniversityProfile();" caption="University profile">
                                            <option value="">Select University Course Profile</option>
                                            <?php
                                            $linkText = '';
                                            $linkUrl = '#';
                                            $linkDisplay= 'none';
                                            foreach($universityCourseProfileData as $key=>$value){
                                                $profileSelected = '';

                                                if($applicationDetails['universityCourseProfileId']==$value['applicationProfileId']){
                                                    $profileSelected = 'selected="selected"';
                                                    $linkText = $value['applyNowLink'];
                                                    $linkUrl = $value['applyNowLink'];
                                                    $linkDisplay = 'block';
                                                }
                                                ?>
                                                <option <?= $profileSelected;?> value="<?= $value['applicationProfileId'];?>" link="<?= htmlentities($value['applyNowLink']);?>"><?= ($value['name']);?></option>';
                                            <?php }?>
                                        </select>
                                        <div style="display: none" class="errorMsg" id="universityCourseProfileId_error"></div>
                                    </div>
                                </li>
                                <li style="display:<?= $linkDisplay;?>" id="applyNowlinkContainer">
                                    <label style="padding:0;">Apply Now Link : </label>
                                    <div class="cms-fields">
                                        <a href="<?= $linkUrl?>" id="universityProfileLink" target="_blank"><?= $linkText?></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="clear-width" id="entryRequirementContainer">
                        <div class="entry-requiremnt-section clear-width">
                            <div>


                            </div>
                            <div>
                                <div class="requirement-title">
                                    <div class="cms-fields">
                                        <strong>English language exams</strong>
                                    </div>
                                </div>
                                <div class="cms-form-wrap">
                                    <ul>
                                        <?php
                                        if($shikshaApplyFlag==false){
                                            $examData = array();
                                        }
                                        foreach($EnglishORNonEnglishExam['ENGLISH'] as $key=>$value){
                                            $comment = '';
                                            $cutOff  = '';
                                            $examSelected = '';
                                            if(in_array($value,array_keys($examData))){
                                                $comment = 	$examData[$value]['comments'];
                                                $cutOff  = 	$examData[$value]['cutOff'];
                                                $examSelected = 'checked="checked"';
                                            }
                                            ?>
                                            <li>
                                                <div class="requirement-field">
                                                    <input type="checkbox" <?= $examSelected;?> id="examRequiredAppDetail<?=$examListByType[$value]['examId']?>" value="<?=$examListByType[$value]['examId']?>" name="examRequiredAppDetail[]" /> <?= $examListByType[$value]['exam']?><br />
                                                    <div class="requirement-field-sec">
                                                        <label><?= $examListByType[$value]['exam']?> Cutoff*</label>
                                                        <select class="universal-select cms-field" style="width:auto;" id="examRequiredCutOffAppDetail<?=$examListByType[$value]['examId']?>" name="examRequiredCutOffAppDetail<?=$examListByType[$value]['examId']?>" onchange='$j("#examRequiredAppDetail<?=$examListByType[$value]['examId']?>_error").hide();'>
                                                            <option value="">Select Cutoff</option>
                                                            <option value="N/A" <?= ($cutOff=='N/A')?'selected="selected"':'';?>>No specific cut-off mentioned</option>
                                                            <?php
                                                            if(is_numeric($examListByType[$value]['range']))
                                                            {
                                                                for($x=$examListByType[$value]['minScore'];$x<=$examListByType[$value]['maxScore'];$x+=$examListByType[$value]['range']){?>
                                                                    <option <?= ($cutOff==$x)?'selected="selected"':'';?> value="<?= $x;?>"><?= $x;?></option>
                                                                <?php }}
                                                            else{
                                                                $rangeArray = split(",", $examListByType[$value]['range']);
                                                                foreach($rangeArray as $key => $rangeValue) {
                                                                    $selected = '';
                                                                    if($cutOff != "" && $cutOff == $rangeValue) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                    ?>
                                                                    <option <?=$selected?> value="<?=$rangeValue?>"><?=$rangeValue?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div style="display: none" class="errorMsg" id="examRequiredAppDetail<?=$examListByType[$value]['examId']?>_error"></div>
                                                        <label>Comments (If any)</label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="cms-fields">
                                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="1000" caption="Cutoff Description" id="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>" name="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>"><?= $comment;?></textarea>
                                                    <div style="display: none" class="errorMsg" id="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>_error"></div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <div class="requirement-title">
                                    <div class="cms-fields">
                                        <strong>Non-english exam</strong>
                                    </div>
                                </div>
                                <div class="cms-form-wrap" style="margin-bottom:5px;">
                                    <ul>
                                        <?php foreach($EnglishORNonEnglishExam['NON_ENGLISH'] as $key=>$value){
                                            $comment = '';
                                            $cutOff  = '';
                                            $examSelected = '';
                                            if(in_array($value,array_keys($examData))){
                                                $comment = 	$examData[$value]['comments'];
                                                $cutOff  = 	$examData[$value]['cutOff'];
                                                $examSelected = 'checked="checked"';
                                            }
                                            ?>
                                            <li>
                                                <div class="requirement-field">
                                                    <input type="checkbox" <?= $examSelected;?> id="examRequiredAppDetail<?=$examListByType[$value]['examId']?>" value="<?=$examListByType[$value]['examId']?>" name="examRequiredAppDetail[]" /> <?= $examListByType[$value]['exam']?><br />
                                                    <div class="requirement-field-sec">
                                                        <label><?= $examListByType[$value]['exam']?> Cutoff*</label>
                                                        <select class="universal-select cms-field" style="width:auto;" id="examRequiredCutOffAppDetail<?=$examListByType[$value]['examId']?>" name="examRequiredCutOffAppDetail<?=$examListByType[$value]['examId']?>" onchange='$j("#examRequiredAppDetail<?=$examListByType[$value]['examId']?>_error").hide();'>
                                                            <option  value="">Select Cutoff</option>
                                                            <option value="N/A" <?= ($cutOff=='N/A')?'selected="selected"':'';?>>No specific cut-off mentioned</option>
                                                            <?php
                                                            if(is_numeric($examListByType[$value]['range']))
                                                            {
                                                                for($x=$examListByType[$value]['minScore'];$x<=$examListByType[$value]['maxScore'];$x+=$examListByType[$value]['range']){?>
                                                                    <option <?= ($cutOff==$x)?'selected="selected"':'';?> value="<?= $x;?>"><?= $x;?></option>
                                                                <?php }}
                                                            else{
                                                                $rangeArray = split(",", $examListByType[$value]['range']);
                                                                foreach($rangeArray as $key => $rangeValue) {
                                                                    $selected = '';
                                                                    if($cutOff != "" && $cutOff == $rangeValue) {
                                                                        $selected = 'selected="selected"';
                                                                    }
                                                                    ?>
                                                                    <option <?=$selected?> value="<?=$rangeValue?>"><?=$rangeValue?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div style="display: none" class="errorMsg" id="examRequiredAppDetail<?=$examListByType[$value]['examId']?>_error"></div>
                                                        <label>Comments (If any)</label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="cms-fields">
                                                    <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="1000" caption="Cutoff Description" id="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>" name="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>"><?= $comment;?></textarea>
                                                    <div style="display: none" class="errorMsg" id="examCommentsAppDetail<?= $examListByType[$value]['examId'];?>_error"></div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <div style="display:none;margin-left:257px;" class="errorMsg" id="examRequiredSectionApplicationDetail_error"></div>
                                        </li></ul>
                                    <ul id="customExamApplicationDetailContainer">
                                        <li class="add-more-sec2 customExamsDivAppDetail"  style="display: none;">
                                            <div class="cms-form-wrap" style="margin:0;">
                                                <div class="more-checkbx-form flLt">
                                                    <label>Exam: </label>
                                                    <input tooltip="cour_xamReqOther"  class="universal-txt-field" type="text" name="customExamAppDetail[]" id="customExamAppDetail0" maxlength="20" minlength="1" validationType="str" caption="Exam Name" onblur="showErrorMessage(this, '');" />
                                                    <div style="display: none" class="errorMsg" id="customExamAppDetail0_error"></div>
                                                </div>
                                                <div class="more-checkbx-form flLt">
                                                    <label>Cut Off*: </label>
                                                    <input tooltip="cour_cfOthers" class="universal-txt-field" type="text" name="customExamCutOffsAppDetail0" id="customExamCutOffsAppDetail0" maxlength="20" minlength="1" validationType="str" caption="Exam Cut Off" onblur="showErrorMessage(this, '');"/>
                                                    <div style="display: none" class="errorMsg" id="customExamCutOffsAppDetail0_error"></div>
                                                </div>
                                                <div class="more-checkbx-form flLt">
                                                    <label>Comments (If Any): </label>
                                                    <textarea class="cms-textarea comment-area" name="customExamCommentsAppDetail0" id="customExamCommentsAppDetail0" maxlength="1000" minlength="1" validationType="str" caption="Custom Exam Description" onblur="showErrorMessage(this, '');"></textarea>
                                                    <div style="display: none" class="errorMsg" id="customExamCommentsAppDetail0_error"></div>
                                                </div>
                                            </div>
                                            <a href="javascript:void(0);" class="remove-link-2" onclick="removeAddedElementAppDetail(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Exam</a>
                                        </li>

                                        <?php
                                        if(count($customExamData) && $shikshaApplyFlag==true) {
                                            foreach($customExamData as $key => $examArray) {
                                                $newKey = $key+1;
                                                ?>
                                                <li class="add-more-sec2 customExamsDivAppDetail">
                                                    <div class="cms-form-wrap" style="margin:0;">
                                                        <div class="more-checkbx-form flLt">
                                                            <label>Exam: </label>
                                                            <input value="<?=htmlentities($examArray['examName']);?>" tooltip="cour_xamReqOther"  class="universal-txt-field" type="text" name="customExamAppDetail[]" id="customExamAppDetail<?=$newKey?>" maxlength="20" minlength="1" validationType="str" caption="Exam Name" onblur="showErrorMessage(this, '');"/>
                                                            <div style="display: none" class="errorMsg" id="customExamAppDetail<?=$newKey?>_error"></div>
                                                        </div>
                                                        <div class="more-checkbx-form flLt">
                                                            <label>Cut Off*: </label>
                                                            <input value="<?=htmlentities($examArray['cutoff'])?>" tooltip="cour_cfOthers" class="universal-txt-field" type="text" name="customExamCutOffsAppDetail<?=$newKey?>" id="customExamCutOffsAppDetail<?=$newKey?>" maxlength="20" minlength="1" validationType="str" caption="Exam Cut Off" onblur="showErrorMessage(this, '');"/>
                                                            <div style="display: none" class="errorMsg" id="customExamCutOffsAppDetail<?=$newKey?>_error"></div>
                                                        </div>
                                                        <div class="more-checkbx-form flLt">
                                                            <label>Comments (If Any): </label>
                                                            <textarea class="cms-textarea comment-area" name="customExamCommentsAppDetail<?=$newKey?>" id="customExamCommentsAppDetail<?=$newKey?>" maxlength="1000" minlength="1" validationType="str" caption="Custom Exam Description" onblur="showErrorMessage(this, '');"><?=htmlentities($examArray['comments'])?></textarea>
                                                            <div style="display: none" class="errorMsg" id="customExamCommentsAppDetail<?=$newKey?>_error"></div>
                                                        </div>
                                                    </div>
                                                    <a href="javascript:void(0);" class="remove-link-2" onclick="removeAddedElementAppDetail(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Exam</a>
                                                </li>
                                                <?php
                                            }
                                        }
                                        ?>

                                    </ul>
                                    <div style="display: none;margin-left: 257px;" class="errorMsg" id="customExamRequiredAppDetail_error"></div>
                                    <a style="margin-left: 257px;" id="addmorelinkAppDetail" href="javascript:void(0);" onclick="addMoreExamToCourseApplicationDetail()">[+] Add another exam</a>
                                </div>
                            </div>
                            <div>
                                <div class="requirement-title">
                                    <div class="cms-fields">
                                        <strong>&nbsp;</strong>
                                    </div>
                                </div>
                                <div class="cms-form-wrap" style="margin-top:0px;">
                                    <ul>
                                        <li>
                                            <label>Additional requirements:</label><br />
                                            <div class="requirement-field">
                                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Additional Requirements" id="additionalRequirement_<?=$formName?>" name="additionalRequirement_<?=$formName?>"><?= $applicationDetails['additionalRequirement'];?></textarea>
                                                <div style="display: none" class="errorMsg" id="additionalRequirement_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <div class="requirement-title">
                                    <div class="cms-fields">
                                        <strong>Application process for this course</strong>
                                    </div>
                                </div>
                                <div class="cms-form-wrap" style="margin-top:0px;">
                                    <ul>
                                        <li>
                                            <div class="requirement-field">
                                                <label style="width:257px;">Application through some common application?</label>
                                                <label style="left: 0px; text-align: left; clear: both; width: 60px;" onclick=""><input <?= (isset($applicationDetails['isApplicationViaCommonApplication']) && $applicationDetails['isApplicationViaCommonApplication']==1)?'checked="checked"':''?> type="radio" value="1" name="isApplicationViaCommonApplication" id="isApplicationViaCommonApplication_yes"/> YES</label>
                                                <label style="left: 0px; margin-left: 0px; float: left; width: 60px;" onclick=""><input <?= (isset($applicationDetails['isApplicationViaCommonApplication']) && $applicationDetails['isApplicationViaCommonApplication']==2)?'checked="checked"':''?> type="radio" value="2" name="isApplicationViaCommonApplication" id="isApplicationViaCommonApplication_no"/> NO</label>
                                                <label style="left: 0px; margin-left: 0px; float: left; width: 100px;" onclick=""><input <?= (isset($applicationDetails['isApplicationViaCommonApplication']) && $applicationDetails['isApplicationViaCommonApplication']==3)?'checked="checked"':''?> type="radio" value="3" name="isApplicationViaCommonApplication" id="isApplicationViaCommonApplication_opt"/> OPTIONAL</label>
                                            </div>
                                        </li>
                                        <li>
                                            <label style="text-align:right">Common application text:</label>
                                            <div class="cms-fields">
                                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Common application text:" id="commonApplicationDescription_<?=$formName?>" name="commonApplicationDescription_<?=$formName?>"><?= (isset($applicationDetails['commonApplicationDescription']))?$applicationDetails['commonApplicationDescription']:''?></textarea>
                                                <div style="display: none" class="errorMsg" id="commonApplicationDescription_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <?php
                                            $transcriptEvaluationNeededList = array (
                                                1 => 'Not needed',
                                                2 => 'A2Z Evaluations',
                                                3 => 'Academic Evaluation Services',
                                                4 => 'Center for Applied Research, Evaluations, & Education',
                                                5 => 'e-ValReports',
                                                6 => 'Educational Credential Evaluators',
                                                7 => 'Educational Perspectives',
                                                8 => 'Educational Records Evaluation Service',
                                                9 => 'Evaluation Service',
                                                10 => 'Foreign Academic Credential Service',
                                                11 => 'Foundation for International Services',
                                                12 => 'Global Credential Evaluators',
                                                13 => 'Global Services Associates',
                                                14 => 'International Academic Credential Evaluators',
                                                15 => 'International Consultants of Delaware',
                                                16 => 'International Education Research Foundation',
                                                17 => 'Josef Silny & Associates, International Education Consultants',
                                                18 => 'SpanTran: The Evaluation Company',
                                                19 => 'Transcript Research',
                                                20 => 'World Education Services (WES)'
                                            );
                                            ?>
                                            <label>Transcript Evaluation Needed:</label>
                                            <div class="cms-fields">
                                                <select class="universal-select cms-field" name="transcriptEvaluationNeeded">
                                                    <?php foreach($transcriptEvaluationNeededList as $k=>$v){?>
                                                        <option <?php echo ($applicationDetails['transcriptEvaluationNeeded']==$k)?'selected="selected"':''?> value="<?php echo $k;?>"><?php echo  $v;?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="requirement-field">
                                                <label style="text-align: left;">Interview process required for this course?</label>
                                                <label style="left: 0px; text-align: left; clear: both; width: 60px;" onclick="validateInterviewProcessDate();"><input <?= ($applicationDetails['isInterviewRequired']==1)?'checked="checked"':''?> type="radio" value="1" name="isInterviewRequired" id="isInterviewRequired_yes"/> YES</label>
                                                <label style="left: 0px; margin-left: 0px; float: left; width: 60px;" onclick="validateInterviewProcessDate();"><input <?= (isset($applicationDetails['isInterviewRequired']) && $applicationDetails['isInterviewRequired']==2)?'checked="checked"':''?> type="radio" value="2" name="isInterviewRequired" id="isInterviewRequired_no"/> NO</label>
                                            </div>
                                        </li>
                                        <li>
                                            <label>Interview date:</label>
                                            <div class="requirement-field">
                                                <select name="interviewMonth" id="interviewMonth1" class="universal-select cms-field" style="width:auto; float:left">
                                                    <option value="0">Select Month</option>
                                                    <?php for($x=1;$x<13;$x++){?>
                                                        <option value="<?php echo $x;?>" <?php echo ($applicationDetails['interviewMonth']==$x)?'selected="selected"':'';?>><?php echo date('M',mktime(0, 0, 0, $x, 1, 2000));?></option>
                                                    <?php } ?>
                                                </select>
                                                <select name="interviewYear" id="interviewYear1"  class="universal-select cms-field" style="width:auto; float:left; margin-left:10px;">
                                                    <option>Select Year</option>
                                                    <?php for($x=date('Y');$x<date('Y')+3;$x++){?>
                                                        <option <?= ($applicationDetails['interviewYear']==$x)?'selected="selected"':''?> value="<?= $x;?>"><?= $x;?></option>
                                                    <?php } ?>
                                                </select><br /><br />

                                            </div>
                                        </li>
                                        <li>
                                            <label style="text-align:right">Interview process details:</label>
                                            <div class="cms-fields">
                                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="2000" caption="Interview Process Details" id="interViewProcessDesc_<?=$formName?>" name="interViewProcessDesc_<?=$formName?>"><?= $applicationDetails['interviewprocessDetail'];?></textarea>
                                                <div style="display: none" class="errorMsg" id="interViewProcessDesc_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div>
                                <div class="requirement-title">
                                    <div class="cms-fields">
                                        <strong>&nbsp;</strong>
                                    </div>
                                </div>
                                <div class="cms-form-wrap" style="margin-top:0px;">
                                    <ul>
                                        <li>
                                            <div class="requirement-field">
                                                <input <?= ($applicationDetails['applicationFeeDetail']==1)?'checked="checked"':''?> type="checkbox" name="applicationFeeDetail" id="applicationFeeDetail" value="1" /> Application fees details
                                            </div>
                                        </li>
                                        <li>
                                            <div class="requirement-field">
                                                <input name="feeAmount" id="feeAmount" validationType="numeric" maxlength="10" minlength="1" onblur="showErrorMessage(this, '');" caption="Application Fee Amount" type="text" class="universal-txt-field" style="width:200px;font-size:12px;" placeholder="Enter application fees" value="<?= ($applicationDetails['feeAmount']>0)?$applicationDetails['feeAmount']:'';?>"/><br />
                                                <div style="display: none" class="errorMsg" id="feeAmount_error"></div>
                                                <?php
                                                $currencyDropDownHtml = '<option value="" >Select Currency</option>';
                                                foreach($currencyData as $key => $currency){
                                                    if($applicationDetails['currencyId'] == $currency['id']){
                                                        $currencyDropDownHtml .=  '<option selected="selected" value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                                    } else {
                                                        $currencyDropDownHtml .=  '<option value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                                    }
                                                }
                                                ?>
                                                <input type="hidden" value="<?= $applicationDetails['currencyId'];?>" name="applicationDetailCurrencyId" id="applicationDetailCurrencyId" class="universal-select cms-field">
                                                <select disabled="disabled" id="applicationDetailCurrencyIdSelect" class="universal-select cms-field" style="width:212px; float:left; margin-top:10px;">
                                                    <?= $currencyDropDownHtml;?>
                                                </select>
                                                <div style="display: none;clear: both;" class="errorMsg" id="applicationDetailCurrencyId_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label style="padding:0;">Payment method:</label>
                                            <div class="requirement-field">
                                                <input <?= ($applicationDetails['isCreditCardAccepted']==1)?'checked="checked"':''?> type="checkbox" name="isCreditCardAccepted" id="isCreditCardAccepted" value="1" /> Credit Card
                                                <input <?= ($applicationDetails['isDebitCardAccepted']==1)?'checked="checked"':''?> type="checkbox" name="isDebitCardAccepted"  id="isDebitCardAccepted" value="1"/> Debit Card
                                                <input <?= ($applicationDetails['iswiredMoneyTransferAccepted']==1)?'checked="checked"':''?> type="checkbox" name="iswiredMoneyTransferAccepted" id="iswiredMoneyTransferAccepted" value="1"/> Wire money transfer
                                                <input <?= ($applicationDetails['isPaypalAccepted']==1)?'checked="checked"':''?> type="checkbox" name="isPaypalAccepted" id="isPaypalAccepted" value="1"/> Paypal
                                            </div>
                                        </li>
                                        <li>
                                            <label style="text-align:right;">Tuition fees installments & fees structure:</label>
                                            <div class="cms-fields">
                                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="Tuition fees installments & fees structure" id="tuitionFeeDesc_<?=$formName?>" name="tuitionFeeDesc_<?=$formName?>"><?= $applicationDetails['feeDetails'];?></textarea>
                                                <div style="display: none" class="errorMsg" id="tuitionFeeDesc_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                        <li>
                                            <label style="text-align:right">International Students Postal Address:</label>
                                            <div class="cms-fields">
                                                <textarea validationType="html" class="cms-textarea tinymce-textarea" maxlength="5000" caption="International Students Postal Address" id="internationStudentAddress_<?=$formName?>" name="internationStudentAddress_<?=$formName?>"><?= (isset($applicationDetails['internationStudentAddress']))?$applicationDetails['internationStudentAddress']:''?></textarea>
                                                <div style="display: none" class="errorMsg" id="internationStudentAddress_<?=$formName?>_error"></div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Class profile</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Average Work Experience : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['average_work_experience']?>" caption="Average Work Experience" tooltip="cour_cpAvgWorkExp" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="averageWorkExp" name="averageWorkExp" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="averageWorkExp_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average Bachelors GPA (or percentage) : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['average_gpa']?>" caption="Average Bachelors GPA (or percentage)" tooltip="cour_cpAvgBachGPA" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="averageBachelorsGPA" name="averageBachelorsGPA" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="averageBachelorsGPA_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average Class XII percentage : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['average_xii_percentage']?>" caption="Average Class XII percentage" tooltip="cour_cpAvgClass12" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="averageClass12Percentage" name="averageClass12Percentage" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="averageClass12Percentage_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average GMAT score : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['average_gmat_score']?>" tooltip="cour_cpAvgGmat" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="averageGMATScore" name="averageGMATScore" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Average GMAT score"/>
                                <div style="display: none" class="errorMsg" id="averageGMATScore_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average age : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['average_age']?>" tooltip="cour_cpAvgAge" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="averageAge" name="averageAge" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Average age"/>
                                <div style="display: none" class="errorMsg" id="averageAge_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Percentage of international students : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_class_profile']['percentage_international_students']?>" tooltip="cour_cpAvgInterStudent" class="universal-txt-field cms-text-field last-in-section" type="text" maxlength="50" id="internationalStudentsPercentage" name="internationalStudentsPercentage" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Percentage of international students"/>
                                <div style="display: none" class="errorMsg" id="internationalStudentsPercentage_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Fee Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Fees Page Link* : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['feesPageLink']?>" tooltip="cour_feeDetail" validationType="link" id="feesPageLink_<?=$formName?>" name="feesPageLink_<?=$formName?>" caption="Fees Page Link" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-txt-field cms-text-field" type="text"/>
                                <div style="display: none" class="errorMsg" id="feesPageLink_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Tuition fees Currency* : </label>
                            <div class="cms-fields">
                                <?php
                                $currencyDropDownHtml = '<option value="" >Select Currency</option>';
                                foreach($currencyData as $key => $currency){
                                    if($courseData['course_details']['fees_unit'] != "" && $courseData['course_details']['fees_unit'] == $currency['id']){
                                        $currencyDropDownHtml .=  '<option selected="selected" value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                    } else {
                                        $currencyDropDownHtml .=  '<option value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                    }
                                }
                                ?>
                                <select class="universal-select cms-field" id="tutionFeeCurrency_<?=$formName?>" name="tutionFeeCurrency_<?=$formName?>" caption="tuition fees currency" validationType="select" onblur="showErrorMessage(this, '<?=$formName?>');" required=true onChange="maintainTotalFees('<?=$formName?>');showErrorMessage(this, '<?=$formName?>');">
                                    <?=$currencyDropDownHtml?>
                                </select>
                                <div style="display: none" class="errorMsg" id="tutionFeeCurrency_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Tuition & mandatory fees (1st year only)* : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_details']['fees_value']?>" tooltip="cour_tutionFee" validationType="numeric" id="tutionFee_<?=$formName?>" name="tutionFee_<?=$formName?>" maxlength="12" caption="Tuition fees" onblur="showErrorMessage(this, '<?=$formName?>');maintainTotalFees('<?=$formName?>');" required=true class="universal-txt-field cms-text-field" type="text"/>
                                <div style="display: none" class="errorMsg" id="tutionFee_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <!-- New Additions to fees here -->
                        <li>
                            <label>Hostel &amp; Meals : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_details']['roomBoard']==0?"":$courseData['course_details']['roomBoard']?>" id="feeRoomBoard" name="feeRoomBoard" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');maintainTotalFees('<?=$formName?>');" minlength="0" maxlength="10"  validationtype="numeric" caption="Hostel And Meals"/>
                                <div style="display: none" class="errorMsg" id="feeRoomBoard_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>No Meal : </label>
                            <div class="cms-fields">
                                <input <?php echo ($courseData['course_details']['isMealIncluded']==1)?'checked="checked"':''?> type="checkbox" name="isMealIncluded" id="isMealIncluded" value="1" />
                            </div>
                        </li>
                        <li>
                            <label>Insurance : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_details']['insurance']==0?"":$courseData['course_details']['insurance']?>" id="feeInsurance" name="feeInsurance" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');maintainTotalFees('<?=$formName?>');" minlength="0" maxlength="10"  validationtype="numeric" caption="Insurance"/>
                                <div style="display: none" class="errorMsg" id="feeInsurance_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Transportation : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_details']['transportation']==0?"":$courseData['course_details']['transportation']?>" id="feeTransportation" name="feeTransportation" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');maintainTotalFees('<?=$formName?>');" minlength="0" maxlength="10"  validationtype="numeric" caption="Transportation"/>
                                <div style="display: none" class="errorMsg" id="feeTransportation_error"></div>
                            </div>
                        </li>
                        <li>
                            <div class="add-more-sec2 clear-width">
                                <ul>
                                    <li>
                                        <div class="cms-fields">
                                            <strong style="margin-bottom:6px !important; display:block;">Add custom fees field</strong>
                                        </div>
                                    </li>
                                    <?php if(count($customValuesMapping['fees']) > 0){ ?>
                                        <?php foreach($customValuesMapping['fees'] as $k=>$customFeesMapping){ ?>
                                            <li class = "feeInputRow">
                                                <label>Custom Field name : </label>
                                                <div class="cms-fields">
                                                    <input name ="fee_field_name[]" id="customFeeName_<?=$k?>_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value="<?=htmlspecialchars($customFeesMapping['caption'])?>" onblur = "showErrorMessage(this,'<?=$formName?>');" validationtype = "customFieldName"  customfieldtype = "fees" <?php if($k ==0) echo 'onchange=\'FeesCustomPairRequired("'.$formName.'");\'';  ?> maxlength="35" required="true" minlength="1"/>
                                                    <label class="flLt" style="width:80px; margin-right:5px;">Field value : </label>
                                                    <input name ="fee_field_value[]" id="customFeeValue_<?=$k?>_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value="<?=($customFeesMapping['value'])?>"  onblur = "showErrorMessage(this,'<?=$formName?>');maintainTotalFees('<?=$formName?>');" validationtype = "numeric" customfieldtype = "fees" caption = "custom fees value" minlength="1" maxlength="10" <?php if($k ==0) echo 'onchange=\'FeesCustomPairRequired("'.$formName.'")\''; ?> required="true"/>
                                                    <div class= "clear-width">
                                                        <div style="display: none" class="errorMsg flLt" id="customFeeName_<?=$k?>_<?=$formName?>_error" style="width:50%;"></div>
                                                        <div style="display: none; width:50%;text-align:center;" class="errorMsg flRt" id="customFeeValue_<?=$k?>_<?=$formName?>_error"></div>
                                                    </div>
                                                    <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomFeeField(this,'<?=$formName?>');" style="margin:5px 30px 0 0;<?=($k==0?'display:none;':'')?>"><i class="abroad-cms-sprite remove-icon"></i>Remove Custom Field</a>
                                                </div>

                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li class = "feeInputRow">
                                            <label>Custom Field name : </label>
                                            <div class="cms-fields">
                                                <input name ="fee_field_name[]" id="customFeeName_0_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value="<?=htmlspecialchars($customFeesMapping['caption'])?>" onblur = "showErrorMessage(this,'<?=$formName?>')" validationtype = "customFieldName"  customfieldtype = "fees" onchange='FeesCustomPairRequired("<?=$formName?>")' maxlength="35"/>
                                                <label class="flLt" style="width:80px; margin-right:5px;">Field value : </label>
                                                <input name ="fee_field_value[]" id="customFeeValue_0_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value="<?=($customFeesMapping['value'])?>"  onblur = "showErrorMessage(this,'<?=$formName?>');maintainTotalFees('<?=$formName?>');" validationtype = "numeric" customfieldtype = "fees" caption = "custom fees value" minlength="0" maxlength="10" onchange='FeesCustomPairRequired("<?=$formName?>")'/>
                                                <div class= "clear-width">
                                                    <div style="display: none" class="errorMsg flLt" id="customFeeName_0_<?=$formName?>_error" style="width:50%;"></div>
                                                    <div style="display: none; width:50%;text-align:center;" class="errorMsg flRt" id="customFeeValue_0_<?=$formName?>_error"></div>
                                                </div>
                                                <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomFeeField(this,'<?=$formName?>');" style="margin:5px 30px 0 0;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove Custom Field</a>
                                            </div>

                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a class="add-more-link last-in-section" onclick = "addMoreFeeFields(this);" href="Javascript:void(0);">[+] Add more</a>
                        </li>
                        <li>
                            <div class="fee-detail">
                                <label style="padding-top:0;">Total fees for this course : </label>
                                <div class="fee-info" id="feesConversionBox">
                                    <p class="dollar-width"> <strong>INR 0</strong> </p>
                                    <p class="equal-width"> <strong>=</strong> </p>
                                    <p class="rupee-width"> <strong>INR 0</strong> </p><br>

                                </div>
                            </div>
                        </li>
                        <!-- End of new fees section -->
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Scholarship Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Scholarship description : </label>
                            <div class="cms-fields">
                                <textarea id="scholarshipDescription" name="scholarshipDescription" class="cms-textarea" maxlength="1000" validationType="str" caption="Scholarship Description"><?=$courseData['scholarship']['description']?></textarea>
                                <div style="display:none" class="errorMsg" id="scholarshipDescription_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarship link : </label>
                            <div class="cms-fields">
                                <input value="<?=htmlspecialchars($courseData['scholarship']['link'])?>" id="scholarshipMainLink" name="scholarshipMainLink" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');" maxlength="100"/>
                                <div style="display: none" class="errorMsg" id="scholarshipMainLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarships Link at Course level : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['scholarshipLinkCourseLevel']?>" tooltip="cour_linkCourse" validationType="link" caption="Scholarships Link" id="scholarshipLinkCourseLevel" name="scholarshipLinkCourseLevel" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="scholarshipLinkCourseLevel_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarships Link at department level : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['scholarshipLinkDeptLevel']?>" tooltip="cour_linkDept" validationType="link" caption="Scholarships Link" id="scholarshipLinkDeptLevel" name="scholarshipLinkDeptLevel" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="scholarshipLinkDeptLevel_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarships Link at University level :</label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['scholarshipLinkUniversityLevel']?>" tooltip="cour_linkUniv" validationType="link" caption="Scholarships Link" id="scholarshipLinkUniversityLevel" name="scholarshipLinkUniversityLevel" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="scholarshipLinkUniversityLevel_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarship amount : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['scholarship']['amount']==0?'':$courseData['scholarship']['amount']?>" id="scholarshipAmount" name="scholarshipAmount" class="universal-txt-field cms-text-field flLt" type="text" style="width:280px !important; margin-right:15px; padding:5px;" minlength="0" maxlength="10"  validationtype="numeric" caption="Scholarship Amount" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="scholarshipCurrencyRequiredCheck();"/>
                                <select id="scholarshipCurrency" name="scholarshipCurrency" class="universal-select cms-field" style="width:250px !important;" caption="Scholarship Currency">
                                    <?php
                                    $currencyDropDownHtml = '<option value="" >Select Currency</option>';
                                    foreach($currencyData as $key => $currency){
                                        if($currency['id'] == $courseData['scholarship']['currency']){
                                            $currencyDropDownHtml .=  '<option selected="selected" value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                        } else {
                                            $currencyDropDownHtml .=  '<option value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                        }
                                    }
                                    ?>
                                    <?=$currencyDropDownHtml?>
                                </select>
                                <div style="display: none" class="errorMsg" id="scholarshipAmount_error"></div>
                                <div style="display: none" class="errorMsg" id="scholarshipCurrency_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarship eligibilty : </label>
                            <div class="cms-fields">
                                <input value="<?=htmlspecialchars($courseData['scholarship']['eligibility'])?>" id="scholarshipEligibility" name="scholarshipEligibility" class="universal-txt-field cms-text-field" type="text" maxlength="500" validationType="str" caption="Scholarship Eligibility"/>
                                <div style="display: none" class="errorMsg" id="scholarshipEligibility_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Scholarship deadline : </label>
                            <div class="cms-fields">
                                <input value="<?=htmlspecialchars($courseData['scholarship']['deadline'])?>" id="scholarshipDeadline" name="scholarshipDeadline" class="universal-txt-field cms-text-field" type="text" maxlength="100" validationType="str" caption="Scholarship Deadline"/>
                                <div style="display: none" class="errorMsg" id="scholarshipDeadline_error"></div>
                            </div>
                        </li>
                        <li>
                            <div class="add-more-sec2 clear-width">
                                <ul class = "scholarshipInputBlock">
                                    <li>
                                        <div class="cms-fields">
                                            <strong style="margin-bottom:6px !important; display:block;">Add custom scholarship field</strong>
                                        </div>
                                    </li>
                                    <?php if($customValuesMapping['scholarship'] > 0){ ?>
                                        <?php foreach($customValuesMapping['scholarship'] as $k=>$customScholarshipMapping){ ?>
                                            <li class = "scholarshipInputRow">
                                                <ul>
                                                    <li>
                                                        <label>Custom Field name : </label>
                                                        <div class="cms-fields">
                                                            <input name='scholarship_field_name[]' id="customScholarshipName_<?=$k?>_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value = "<?=htmlspecialchars($customScholarshipMapping['caption'])?>" onblur = "showErrorMessage(this,'<?=$formName?>')" customfieldtype = "scholarship" validationtype = "customFieldName" <?php if($k==0) echo 'onchange="ScholarshipCustomPairRequired(\''.$formName.'\')"';?> maxlength="35" required="true" minlength="1"/>
                                                            <div style="display: none" class="errorMsg" id="customScholarshipName_<?=$k?>_<?=$formName?>_error"></div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <label>Custom Field value : </label>
                                                        <div class="cms-fields">
                                                            <textarea name='scholarship_field_value[]' id="customScholarshipValue_<?=$k?>_<?=$formName?>" class="cms-textarea" <?php if($k==0) echo 'onchange="ScholarshipCustomPairRequired(\''.$formName.'\')"'; ?> required="true" maxlength="1000" minlength="1" validationType="str" caption="Custom Field Value"><?=$customScholarshipMapping['value']?></textarea>
                                                            <div style="display: none" class="errorMsg" id="customScholarshipValue_<?=$k?>_<?=$formName?>_error"></div>
                                                        </div>

                                                    </li>
                                                </ul>
                                                <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomScholarshipField(this);" style="margin:5px 30px 0 0;<?=($k==0?'display:none;':'')?>"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                            </li>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <li class = "scholarshipInputRow">
                                            <ul>
                                                <li>
                                                    <label>Custom Field name : </label>
                                                    <div class="cms-fields">

                                                        <input name='scholarship_field_name[]' id="customScholarshipName_0_<?=$formName?>" class="universal-txt-field cms-text-field flLt" type="text" style="width:215px !important;" value = "<?=htmlspecialchars($customScholarshipMapping['caption'])?>" onblur = "showErrorMessage(this,'<?=$formName?>')" customfieldtype = "scholarship" validationtype = "customFieldName" onchange="ScholarshipCustomPairRequired('<?=$formName?>')" maxlength="35"/>
                                                        <div style="display: none" class="errorMsg" id="customScholarshipName_0_<?=$formName?>_error"></div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Custom Field value : </label>
                                                    <div class="cms-fields">
                                                        <textarea name='scholarship_field_value[]' id="customScholarshipValue_0_<?=$formName?>" class="cms-textarea" onchange="ScholarshipCustomPairRequired('<?=$formName?>')" maxlength="1000"  validationType="str" caption="Custom Field Value"><?=($customScholarshipMapping['value'])?></textarea>
                                                        <div style="display: none" class="errorMsg" id="customScholarshipValue_0_<?=$formName?>_error"></div>
                                                    </div>

                                                </li>
                                            </ul>
                                            <a class="remove-link flRt" href="Javascript:void(0);" onclick = "removeCustomScholarshipField(this);" style="margin:5px 30px 0 0;display:none;"><i class="abroad-cms-sprite remove-icon"></i>Remove custom field</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <a class="add-more-link last-in-section" href="Javascript:void(0);" onclick = "addMoreScholarshipFields(this);">[+] Add more</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Jobs Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Career Services Website Link* : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_job_profile']['career_services_link']?>" tooltip="cour_careerLink" validationType="link" id="careerServiceWebsiteLink_<?=$formName?>" name="careerServiceWebsiteLink_<?=$formName?>" caption="Career Services Website Link" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-txt-field cms-text-field" type="text"/>
                                <div style="display: none" class="errorMsg" id="careerServiceWebsiteLink_<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Percentage employed :</label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_job_profile']['percentage_employed']?>" tooltip="cour_percentEmpl" class="universal-txt-field cms-text-field" type="text" maxlength="50" id="percentageEmployed" name="percentageEmployed" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" caption="Percentage employed"/>
                                <div style="display: none" class="errorMsg" id="percentageEmployed_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average salary (per annum) : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_job_profile']['average_salary']?>" tooltip="cour_avgSalary" validationType="numeric" maxlength="15" caption="Average salary" id="avgSalary" name="avgSalary" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="avgSalary_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Average salary Currency : </label>
                            <div class="cms-fields">
                                <?php
                                $currencyDropDownHtml = '<option value="" >Select Currency</option>';
                                foreach($currencyData as $key => $currency){
                                    if($currency['id'] == $courseData['course_job_profile']['average_salary_currency_id']) {
                                        $currencyDropDownHtml .=  '<option selected="selected" value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                    } else {
                                        $currencyDropDownHtml .=  '<option value='.$currency['id'].'>'.$currency['currency_name'].'</option>';
                                    }
                                }
                                ?>
                                <select class="universal-select cms-field" id="avgSalaryCurrency" name="avgSalaryCurrency" onblur = "showErrorMessage(this, '<?=$formName?>');" onchange = "showErrorMessage(this, '<?=$formName?>');" caption = "Average Salary Currency"  validationType = "select">
                                    <?=$currencyDropDownHtml?>
                                </select>
                                <div style="display: none" class="errorMsg" id="avgSalaryCurrency_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Popular sectors : </label>
                            <div class="cms-fields">
                                <textarea tooltip="cour_popularSector" maxlength="2000" class="cms-textarea tinymce-textarea" id="popularSectors" name="popularSectors" validationType="html" caption="Popular Sectors"><?=$courseData['course_job_profile']['popular_sectors']?></textarea>
                                <div style="display: none" class="errorMsg" id="popularSectors_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Internships available:</label>
                            <div class="cms-fields ">
                                <label style="width: auto;" onclick=""><input class="align-middle" <?= (isset($courseData['course_job_profile']['isInternshipAvailable']) && $courseData['course_job_profile']['isInternshipAvailable']==1)?'checked="checked"':''?> type="radio" value="1" name="isInternshipAvailable" id="isInternshipAvailable_yes"/> YES</label>
                                <label style="width: auto;" onclick=""><input class="align-middle" <?= (isset($courseData['course_job_profile']['isInternshipAvailable']) && $courseData['course_job_profile']['isInternshipAvailable']==2)?'checked="checked"':''?> type="radio" value="2" name="isInternshipAvailable" id="isInternshipAvailable_no"/> NO</label>
                            </div>
                        </li>
                        <li>
                            <label>Internships : </label>
                            <div class="cms-fields">
                                <textarea tooltip="cour_internship" maxlength="1000" class="cms-textarea tinymce-textarea" id="internships" name="internships" validationType="html" caption="Internships"><?=$courseData['course_job_profile']['internships']?></textarea>
                                <div style="display: none" class="errorMsg" id="internships_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Internships Link : </label>
                            <div class="cms-fields">
                                <input value="<?=$courseData['course_job_profile']['internships_link']?>" tooltip="cour_internshipLink" validationType="link" caption="Internships Link" id="internshipsLink" name="internshipsLink" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="internshipsLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Recruiting companies : </label>
                            <div class="cms-fields">
                                <?php
                                if(count($recruitingComapanies)) {
                                    foreach($recruitingComapanies  as $key => $dbCompanyId) {
                                        ?>
                                        <div class="add-more-sec" style="display: block;">
                                            <select onchange="hideRecruitingCompaniesErrorMsg();" id="recruitingCompanies<?=$key?>" name="recruitingCompanies[]" class="universal-select cms-field" tooltip="cour_recruitCompany">
                                                <option value="">Select a Recruiting company</option>
                                                <?php
                                                foreach($recruitingCompanies as $mykey => $company){
                                                    $selected = "";
                                                    if($dbCompanyId == $company['id']){
                                                        $selected = "selected";
                                                    }
                                                    ?>
                                                    <option <?=$selected?> value="<?=$company['id']?>"><?=$company['company_name']?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <?php
                                            if($key > 0) {
                                                ?>
                                                <a onclick="removeAddedElement(this);hideRecruitingCompaniesErrorMsg();" class="remove-link-2" href="javascript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Recruiting Company</a>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                        <?php
                                    }

                                } else {
                                    ?>
                                    <div class="add-more-sec">
                                        <?php
                                        $recruitingCompaniesDropDownHtml = '<option value="" >Select a Recruiting company</option>';
                                        foreach($recruitingCompanies as $key => $company){
                                            $recruitingCompaniesDropDownHtml .=  '<option value='.$company['id'].'>'.$company['company_name'].'</option>';
                                        }
                                        ?>
                                        <select tooltip="cour_recruitCompany" class="universal-select cms-field" name="recruitingCompanies[]" id="recruitingCompanies0" onChange='hideRecruitingCompaniesErrorMsg();'>
                                            <?=$recruitingCompaniesDropDownHtml?>
                                        </select>
                                    </div>
                                    <?php
                                }
                                ?>
                                <a href="javascript:void(0);" onclick="cloneRecruitingCompaniesDropDown(this);" class="last-in-section">[+] Add more</a>
                                <div style="display: none" class="errorMsg" id="recruitingCompanies_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>Additional information</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>Faculty information Link : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['facultyInfoLink']?>" tooltip="cour_facultyInfo" validationType="link" caption="Faculty information Link" id="facultyInfoLink" name="facultyInfoLink" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="facultyInfoLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Alumni information Link : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['alumniInfoLink']?>" tooltip="cour_aluminInfo" validationType="link" caption="Alumni information Link" id="alumniInfoLink" name="alumniInfoLink" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="alumniInfoLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>Brochure Link : </br><p style="position: relative; left: -7px; font-size: 9px;">(Max upload size 50 MB)</p></label>
                            <div class="cms-fields">
                                <input tooltip="cour_brochure" type="file" name="brochureLink[]" id="brochureLink"/>
                                <?php
                                if($courseData['course_details']['course_request_brochure_link'] != "") {
                                    ?>
                                    <span id='brochureContainer'><a href="<?=$courseData['course_details']['course_request_brochure_link']?>" target="_blank">View Brochure</a>
																						<a onclick='$j("#existingBrochureUrl").val(""); $j("#brochureContainer").fadeOut("slow");' class="remove-link-2" href="javascript:void(0);"><i class="abroad-cms-sprite remove-icon"></i>Remove Brochure</a>
																				</span>
                                    <input type="hidden" name="existingBrochureUrl" id="existingBrochureUrl" value="<?=$courseData['course_details']['course_request_brochure_link']?>">
                                    <?php
                                }
                                ?>
                                <div style="display: none" class="errorMsg" id="brochureLink_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>FAQ Link : </label>
                            <div class="cms-fields">
                                <input value="<?=$externalLinks['faqLink']?>" tooltip="cour_faq" validationType="link" caption="FAQ Link" id="faqLink" name="faqLink" class="universal-txt-field cms-text-field last-in-section" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
                                <div style="display: none" class="errorMsg" id="faqLink_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite<?=$otherSectionHeadingImageClass?>"></i>SEO Details</h3>
                <div class="cms-form-wrap cms-accordion-div" style="display:<?=$displayStatus?>">
                    <ul>
                        <li>
                            <label>SEO Title : </label>
                            <div class="cms-fields">
                                <textarea validationType="str" class="cms-textarea" style="width:75%;" maxlength="250" id="seoTitle" name="seoTitle" onblur = "showErrorMessage(this, '<?=$formName?>');" caption="SEO Title"><?=$courseData['listings_main']['listing_seo_title']?></textarea>
                                <div style="display: none" class="errorMsg" id="seoTitle<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>SEO Keywords : </label>
                            <div class="cms-fields">
                                <textarea validationType="str" class="cms-textarea" style="width:75%;" maxlength="250" id="seoKeywords" name="seoKeywords" onblur = "showErrorMessage(this, '<?=$formName?>');" caption="SEO Keywords"><?=$courseData['listings_main']['listing_seo_keywords']?></textarea>
                                <div style="display: none" class="errorMsg" id="seoKeywords<?=$formName?>_error"></div>
                            </div>
                        </li>
                        <li>
                            <label>SEO Description : </label>
                            <div class="cms-fields">
                                <textarea validationType="str" class="cms-textarea" style="width:75%;" maxlength="250" id="seoDescription" name="seoDescription" onblur = "showErrorMessage(this, '<?=$formName?>');" caption="SEO Description"><?=$courseData['listings_main']['listing_seo_description']?></textarea>
                                <div style="display: none" class="errorMsg" id="seoDescription<?=$formName?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="clear-width">
                <div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
                    <ul>
                        <li>
                            <label>User Comments*: </label>
                            <div class="cms-fields">
                                <textarea validationType="str" class="cms-textarea" style="width:75%;" maxlength="250" id="userComments_<?=$formName?>" name="userComments_<?=$formName?>" onblur = "showErrorMessage(this, '<?=$formName?>');" tooltip="cour_comments" caption="User Comments" required=true></textarea>
                                <div style="display: none" class="errorMsg" id="userComments_<?=$formName?>_error"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="button-wrap">
                <input type="button" id="bttnSaveAsDraft" name="bttnSaveAsDraft" value="Save as Draft" onclick="saveCourseFormInDraftState(this, '<?=$formName?>')" class="gray-btn"/>
                <?php
                if($previewLinkFlag){
                    ?>
                    <a target="_blank" href="<?=$courseData['listings_main']['listing_seo_url']?>" class="gray-btn">Preview</a>
                <?php 	}	?>
                <input type="button" id="bttnSavePublish" name="bttnSavePublish" value="Save & Publish" onclick="saveAndPublishCourseForm(this, '<?=$formName?>')" class="orange-btn"/>
                <a href="javascript:void(0);" onclick="cancelAction()" class="cancel-btn">Cancel</a>
            </div>
        </div>
    </form>
</div>

<script>
    var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
    function startCallback() {
        return true;
    }

    function cancelAction() {
        if (confirm("Are you sure you want to cancel? All data changes will be lost.")) {
            window.location.href = "<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE?>";
        }
    }

    function completeCallback(responseText) {
        var respData;
        if (responseText != 0) {
            respData = JSON.parse(responseText);
        }
        if (typeof respData != 'undefined' && typeof respData.Fail != 'undefined') {
            showErrorForCourseForm();
            for (var prop in respData.Fail) {
                switch (prop) {
                    case "brochureLink":
                        enableCoursePostingButtons(); // Some form validation error is there.
                        $j("#brochureLink_error").html(respData.Fail[prop]).show();
                        break;
                    case "courseAlreadyExists":
                        enableCoursePostingButtons(); // Some form validation error is there.
                        $j("#courseName_<?=$formName?>_error").html(respData.Fail[prop]).show();
                        break;
                }
            }
        }
        else
        {	// enableCoursePostingButtons();return false;
            alert("Thanks, Course details have been saved successfully.");
            window.location.href = "<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE?>";
        }
    }


    function initFormPosting() {
        AIM.submit(document.getElementById('form_<?=$formName?>'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }

    //Not part of any function
    if(document.all) {
        document.body.onload = initFormPosting;
    } else {
        initFormPosting();
    }
</script>
