<?php
	$masterLevels = array("Masters","PhD","Masters Diploma","Masters Certificate");
	$bachelorLevels = array("Bachelors","Bachelors Diploma","Bachelors Certificate");
	$level = '';
    $europeanCountries = array("EU","AD","AL","AT","BA","BE","BG","BY","CH","CS","CZ","DE","DK","EE","ES","FI","FO","FR","FX","GB","GI","GR","HR","HU","IE","IS","IT","LI","LT","LU","LV","MC","MD","MK","MT","NL","NO","PL","PT","RO","SE","SI","SJ","SK","SM","UA","VA");
    global $invalidEmailDomains;
	if($courseId > 0)
	{
		$courseObj = reset($courses);
		$ldbCourseId = ($courseObj->getDesiredCourseId() > 0? $courseObj->getDesiredCourseId():$courseObj->getLDBCourseId());
		$isPaid = $courseObj->isPaid()?1:0;
        if($singleSignUpFormType == 'response'){
            $level = $courseObj->getCourseLevel1Value();
        }
		$subcategory = $courseObj->getCourseSubCategory();
		$courseCountryId = $courseObj->getCountryId();
		$courseCountryName = $courseObj->getCountryName();
	}else if($universityId > 0)
	{
		$courseCountryId = $universityObj->getLocation()->getCountry()->getId();
		$courseCountryName = $universityObj->getLocation()->getCountry()->getName();
	}
?>
    <div class="sp-blk-c">
        <div class="sp-fmLst">
            <strong class="dot"></strong>
            <p>Your future education preference <span>This will help us show you only relevant information</span></p>
        </div>

        <div class="sp-form clear">
            <div class="flLt sp-f-blk">
                <ul class="clear customInputs">
                <li>
                    <?php
                        if(!empty($abroadShortRegistrationData['whenPlanToGo'])) {
                            $creationDate = (int)date('Y', time());
                            $whenPlanToGo = strtotime($abroadShortRegistrationData['whenPlanToGo']);
                            $whenPlanToGo = (int)date('Y', $whenPlanToGo);
                            if($creationDate == $whenPlanToGo) {
                                $whenPlanToGo = 'thisYear';
                            }else if($creationDate + 1 == $whenPlanToGo) {
                                $whenPlanToGo = 'in1Year';
                            }else {
                                $whenPlanToGo = 'later';
                            }
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <label>When do you plan to start your studies?</label>
                        <?php $planToGoCount = 0;
                        foreach($fields['whenPlanToGo']->getValues() as $plannedToGoValue => $plannedToGoText) { ?>
                        <div class="columns">
                            <input datafieldtype="radio" type="radio" id="year<?php echo ($planToGoCount); ?>" name="whenPlanToGo" <?php if($whenPlanToGo == $plannedToGoValue){echo 'checked';} ?> value="<?php echo $plannedToGoValue; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToGo'); ?>>
                            <label for="year<?php echo ($planToGoCount); ?>">
                                <span class="common-sprite"></span>
                                <p><strong><?php echo $plannedToGoText; ?></strong></p>
                            </label>
                        </div>
                        <?php $planToGoCount++; } ?>
                        <div class="input-helper" id="whenPlanToGo_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </li>
                <?php
				$desiredCourseList = $fields['abroadDesiredCourse']->getValues();
				$desiredCourseList = array_map(function($a){return $a['SpecializationId']; },$desiredCourseList);
					if(isset($fields['abroadDesiredCourse'])){
                        echo "<input type='hidden' id='abroadDesiredCourseHidden' name='desiredCourse' value= '".(in_array($preferredCourse,$desiredCourseList)?$preferredCourse:'')."' />";
                 }?>
                <?php if($universityId>0){ ?>
                <li>
                    <div class="signUp-child-wrap" type="layer">
                        <div class="sp-frm selectField signup-fld invalid">
                            <div class="placeholder">Select course at this university</div>
                            <div class="input"></div>
                            <select datafieldtype="select" id="listing_course_<?php echo $regFormId; ?>" class="drpdwn univCourse" regformid="<?php echo $regFormId; ?>" name="university_course_list_select" regfieldid="" mandatory="1" caption="a course">
                                <option value=""></option>
                                <?php
								usort($courses,function($a,$b){return (strcmp($a->getName(),$b->getName()) > 0); });
								foreach($courses as $courseObj){
                                    $ldbCourseId = ($courseObj->getDesiredCourseId() > 0? $courseObj->getDesiredCourseId():$courseObj->getLDBCourseId());
                                    if(in_array($courseObj->getId(),$userShortlistedCourses))
                                    {
                                        $disabled='disabled="disabled"';
                                        $alreadyShortlistedStr = " (Already shortlisted)";
                                    }else{
                                        $disabled=$alreadyShortlistedStr='';
                                    }
                                    echo '<option ispaid="'.($courseObj->isPaid()?1:0).'" datalvl="'.$courseObj->getCourseLevel1Value().'" clientcourseid="'.$courseObj->getId().'" subcategory="'.$courseObj->getCourseSubCategory().'" datads="'.$ldbCourseId.'"  value="'.$courseObj->getId().'" '.$disabled.'>'.$courseObj->getName().$alreadyShortlistedStr.'</option>';
                                } ?>
                            </select>
                            <div class="input-helper" id="_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <div class="city-lr univCourseList">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <?php }else if($singleSignUpFormType == 'registration' || $singleSignUpFormType == 'scholarshipResponse'){
                    $display = '';
                    $disabled ='';
                    if(!empty($currentLevel) && $singleSignUpFormType != 'scholarshipResponse'){
                        $disabledClass = 'disable-field';
                        $disabled = 'disabled';
                    }
                ?>
                <li>
                    <div class="signUp-child-wrap <?php echo $disabledClass;?>">
                        <label>Level of study</label>
                        <?php foreach($fields['desiredGraduationLevel']->getValues() as $desiredGraduationLevelValue => $desiredGraduationLevelText) { ?>
                        <div class="columns">
                            <input datafieldtype="radio" type="radio" id="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>" name="desiredGraduationLevel" <?php echo $registrationHelper->getFieldCustomAttributes('desiredGraduationLevel'); ?> value="<?php echo $desiredGraduationLevelText['CourseName']; ?>" <?php echo $disabled;?>
                                <?php if(!empty($currentLevel) && $currentLevel == $desiredGraduationLevelText['CourseName']) echo 'checked'; ?>
                                >
                            <label for="<?=$desiredGraduationLevelText['CourseName'].$regFormId?>">
                                <span class="common-sprite"></span>
                                <p><strong><?=$desiredGraduationLevelText['CourseName']?></strong></p>
                            </label>
                        </div>
                        <?php } ?>
                        <div class="input-helper" id="desiredGraduationLevel_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </li>
                <?php } ?>
            </ul>
            </div>
            <div class="flRt sp-f-blk">
                <ul class="clear customInputs">
                <li>
                <?php
                    global $studyAbroadPopularCountries;
                    $destinationCountries = $fields['destinationCountry']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
                    if(!empty($formData['userId'])){
                        $preferredCountriesCount = count($userPreferredDestinations);
                        if($preferredCountriesCount > 0){
                            if (!empty($courseCountryId) && $preferredCountriesCount < 3 && !in_array($courseCountryId, $userPreferredDestinations)){
                                array_push($userPreferredDestinations, $courseCountryId);
                            }
                            $courseCountryId = $userPreferredDestinations;
                            $userFirstCountry = $userPreferredDestinations[0];
                            unset($userPreferredDestinations[0]);
                            if(in_array($userFirstCountry, array_keys($studyAbroadPopularCountries))){
                                $courseCountryName = $studyAbroadPopularCountries[$userFirstCountry];
                            }else{
                                $abroadCountryList = array();
                                foreach ($destinationCountries as $destinationCountry) {
                                    $abroadCountryList[$destinationCountry->getId()] = $destinationCountry->getName();
                                }
                                $courseCountryName = $abroadCountryList[$userFirstCountry];
                                unset($abroadCountryList);
                            }
                            if(count($userPreferredDestinations) > 0){
                                $courseCountryName .= ' +'.count($userPreferredDestinations).' more';
                            }
                        }else{
                            //$courseCountryId = array();
                            $courseCountryId = array($courseCountryId);
                        }
                    }else{
                        if(empty($courseCountryId)){
                            $courseCountryId = array();
                        }else{
                            $courseCountryId = array($courseCountryId);
                        }
                    }
                ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectCountryField signup-fld invalid <?php echo $courseCountryName == '' ? '':'filled'; ?>">
                            <div class="placeholder">Preferred Countries</div>
                            <div class="input"><?php echo $courseCountryName; ?></div>
                            <div class="input-helper" id="destinationCountry_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <div class="city-lr ctry-lr">
                                <ul>
                                <li class="ctry-row"><strong>Top countries</strong></li>
                                <?php
                                    $cntryCount = 1;
                                    foreach($studyAbroadPopularCountries as $key => $popularCountry){
                                    echo ($cntryCount==1?'<li class="ctry-row">':'');
                                        ?>
                                    <div class="in-tab">
                                        <input datafieldtype="multiselect" name="destinationCountry[]" id="<?php echo $popularCountry."_".$regFormId; ?>" value="<?php echo $key; ?>" type="checkbox"  <?php echo $registrationHelper->getFieldCustomAttributes('destinationCountry'); ?> <?php echo (in_array($key, $courseCountryId)? 'checked="checked"':''); ?>>
                                        <label class="nolnht" for="<?php echo $popularCountry."_".$regFormId; ?>">
                                            <span class="common-sprite"></span>
                                               <?php echo $popularCountry; ?>
                                        </label>
                                    </div>
                                <?php
                                    if($cntryCount==3)
                                    {
                                        echo '</li>';
                                        $cntryCount =1;
                                    }else{
                                        $cntryCount++;
                                    }
                                }
                                ?>
                                <li class="ctry-row"><strong>Other countries</strong></li>
                                <?php // rest of them
                                    $cntryCount = 1;
                                    foreach($destinationCountries as $key => $destinationCountry)
                                    {
                                        if(!in_array($destinationCountry->getId(),array_keys($studyAbroadPopularCountries)) && $destinationCountry->getId() !=1)
                                        {
                                            echo ($cntryCount==1?'<li class="ctry-row">':'');
                                ?>
                                    <div class="in-tab">
                                        <input datafieldtype="multiselect" name="destinationCountry[]" id="<?php echo $destinationCountry->getName()."_".$regFormId; ?>" value="<?php echo $destinationCountry->getId(); ?>" type="checkbox" <?php echo $registrationHelper->getFieldCustomAttributes('destinationCountry'); ?> <?php echo (in_array($destinationCountry->getId(), $courseCountryId)? 'checked="checked"':''); ?>>
                                        <label class="nolnht" for="<?php echo $destinationCountry->getName()."_".$regFormId; ?>">
                                            <span class="common-sprite"></span>
                                               <?php echo $destinationCountry->getName(); ?>
                                        </label>
                                    </div>

                                <?php
                                        if($cntryCount==3)
                                        {
                                            echo '</li>';
                                            $cntryCount =1;
                                        }else{
                                            $cntryCount++;
                                        }
                                    }
                                } ?>
                                </ul>
                                <div class="choose-count">Choose up to 3 countries <a  class="ok-btn" href="javascript:void(0)">ok</a> </div>
                            </div>
                        </div>
						<?php if($singleSignUpFormType == 'response'){ ?>
			            <p class="add-m">You can add more countries</p>
						<?php } ?>
                    </div>
                </li>
                <?php if($singleSignUpFormType == 'registration' || $singleSignUpFormType == 'scholarshipResponse'){
                        $desiredCourses = $fields['abroadDesiredCourse']->getValues();
                        $desiredCoursesKeys = array();
                        foreach ($desiredCourses as $key => $value) {
                            $desiredCoursesKeys[] = $value['SpecializationId'];
                        }
                        $fieldOfInterest = $fields['fieldOfInterest']->getValues(array('twoStep'=>1,'registerationDomain'=>'studyAbroad'));
                        $preferredCourseName = '';
                        $display = '';
                        $vaildSAPreferredCourse = 0;
                        if(!empty($preferredCourse) &&
                            (
                                in_array($preferredCourse, $desiredCoursesKeys) ||
                                in_array($preferredCourse, array_keys($fieldOfInterest))
                            )
                        ){
                            $vaildSAPreferredCourse = 1;
                            if(in_array($preferredCourse, $desiredCoursesKeys)){
                                $inputArray = array(
                                    'type' => 'desiredCourse',
                                    'ldbCourseId' => $preferredCourse
                                );
                                $preferredSpecilisations = Modules::run("categoryList/AbroadCategoryList/getSubCatsForCourseCountryLayer",$inputArray);
                            }else{
                                $inputArray = array(
                                    'type'          => 'courseLevel',
                                    'parentCatId'   => $formData['fieldOfInterest'],
                                    'courseLevel'   => $currentLevel
                                );
                                $preferredSpecilisations = Modules::run("categoryList/AbroadCategoryList/getSubCatsForSARegisteration",$inputArray);
                            }
                            unset($inputArray);
                            $display = 'filled';
                            if(in_array($preferredCourse, $desiredCoursesKeys)){
                                foreach ($desiredCourses as $desiredCourse) {
                                    if($desiredCourse['SpecializationId'] == $preferredCourse){
                                        $preferredCourseName = $desiredCourse['CourseName'];
                                        break;
                                    }
                                }
                            }else{
                                $preferredCourseName = $fieldOfInterest[$formData['fieldOfInterest']]['name'];
                            }
                        }
                    ?>
                <li>
                    <div class="signUp-child-wrap <?php echo ($vaildSAPreferredCourse == 0)?'hidden':'';?> courseDiv" type="layer">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Preferred Course</div>
                            <div class="input"><?php echo $preferredCourseName;?></div>
                            <div class="input-helper" id="fieldOfInterest_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" class="drpdwn" name="fieldOfInterest" id="fieldOfInterest_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('fieldOfInterest'); ?> >
                                <option value=""></option>
                                <?php
                                global $studyAbroadLevelWiseDesiredCourses;
                                foreach($desiredCourses as $course) {
                                        if(in_array($course['SpecializationId'], $studyAbroadLevelWiseDesiredCourses['UG'])) {
                                            $lvl = "Bachelors";
                                        } else if(in_array($course['SpecializationId'], $studyAbroadLevelWiseDesiredCourses['PG'])) {
                                            $lvl = "Masters";
                                        }
                                        /* switch($course['SpecializationId'])
                                        {
                                            case 1508:
                                            case 1509: $lvl = "Masters";
                                                        break;
                                            case 1510: $lvl = "Bachelors";
                                                        break;
                                        } */
                                        if($currentLevel == 'PhD'){
                                            continue;
                                        }else if($currentLevel == 'Masters' && $lvl == "Bachelors"){
                                            continue;
                                        }else if($currentLevel == 'Bachelors' && $lvl == "Masters"){
                                            continue;
                                        }
                                        ?>
                                <option value="<?php echo $course['SpecializationId']; ?>" datalvl="<?php echo $lvl; ?>"
                                    <?php echo ($preferredCourse == $course['SpecializationId'])?'selected':''?>
                                    ><?php echo $course['CourseName']; ?></option>
                                <?php } ?>
                                <?php foreach($fieldOfInterest as $categoryId => $categoryName) { ?>
                                <option value="<?php echo $categoryId; ?>" <?php echo ($formData['fieldOfInterest'] == $categoryId)?'selected':'';?> ><?php echo $categoryName['name']; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr prefCourse">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <?php
                    $specilisations = array();
                    foreach ($preferredSpecilisations as $preferredSpecilisation) {
                        $specilisations[$preferredSpecilisation['sub_category_id']] = $preferredSpecilisation['sub_category_name'];
                    }
                    unset($preferredSpecilisations);
                    $display = 'hidden';
                    $preferredSpecialisationName = '';
                    $filled ='';
                    if($vaildSAPreferredCourse && is_numeric($preferredSpecialisation) && $preferredSpecialisation >= 0){
                        $display ='';
                        $filled = 'filled';
                        if($preferredSpecialisation == 0){
                            $preferredSpecialisationName = 'All specializations';
                        }else{
                            $preferredSpecialisationName = $specilisations[$preferredSpecialisation];
                        }
                    }
                    ?>
                    <div class="signUp-child-wrap <?php echo $display;?> specDiv">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $filled;?>">
                        <div class="placeholder">Preferred Specialisations</div>
                        <div class="input"><?php echo $preferredSpecialisationName; ?></div>
                        <div class="input-helper" id="abroadSpecialization_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                        <select datafieldtype = "select" class="drpdwn" id="abroadSpecialization_<?php echo $regFormId; ?>" name="abroadSpecialization" <?php echo $registrationHelper->getFieldCustomAttributes('abroadSpecialization'); ?>>
                        <?php if(!empty($specilisations)){ ?>
                            <option value=''></option>
                        <?php }
                        foreach ($specilisations as $specilisationId => $specilisationName) {?>
                            <option value="<?php echo $specilisationId?>"
                            <?php echo ($specilisationId == $preferredSpecialisation)?'selected':'';?>
                                ><?php echo $specilisationName?></option>
                        <?php } ?>
                        </select>
                        <div class="city-lr">
                            <ul>
                            </ul>
                        </div>
                    </div>
                    </div>
                </li>
                <?php } ?>
            </ul>
            </div>
        </div>


        <div class="sp-fmLst">
            <strong class="dot"></strong>
            <p>Your current education details <span>Accurate details will help us match you with the right universities and scholarships</span></p>
        </div>
        <div class="sp-form clear">
        <div class="flLt sp-f-blk">
            <ul class="clear customInputs">
                <li class="xtra-mrgn">
                    <?php
                        $examsAbroadMasterList = $fields['examsAbroad']->getValues();
                        $abroadExamNameList = array_map(function($a){ return $a['name'];},$examsAbroadMasterList);
                        foreach ($abroadShortRegistrationData['examsAbroad'] as $key => $value) {
                            if(! in_array($key, $abroadExamNameList)){
                                unset($abroadShortRegistrationData['examsAbroad'][$key]);
                            }
                        }
                        if(!empty($abroadShortRegistrationData['examsAbroad'])) {
                            $examTaken = 'yes';
                        }else if(!empty($abroadShortRegistrationData['passport']) && $abroadShortRegistrationData['bookedExamDate'] !=1){
                            $examTaken = 'no';
                        }else if($abroadShortRegistrationData['bookedExamDate'] ==1){
                            $examTaken = 'bookedExamDate';
                        }
                    ?>
                    <div class="signUp-child-wrap sp-f-blk-sml ">
                        <label>Have you given any study abroad exam?</label>
                        <div class="columns wd50">
                            <input datafieldtype = "radio" type="radio" id="examTaken_yes_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="yes" <?php if(!empty($examTaken) && $examTaken == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> >
                            <label for="examTaken_yes_<?php echo $regFormId; ?>">
                                <span class="common-sprite"></span>
                                <p><strong>Yes</strong></p>
                            </label>
                        </div>
                        <div class="columns wd50">
                            <input datafieldtype = "radio" type="radio" id="examTaken_no_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="no" <?php if(!empty($examTaken) && $examTaken == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> >
                            <label for="examTaken_no_<?php echo $regFormId; ?>">
                                <span class="common-sprite"></span>
                                <p><strong>No</strong></p>
                            </label>
                        </div>
                        <div class="columns bk-wdt">
                            <input datafieldtype = "radio" type="radio" id="examTaken_bookedExamDate_<?php echo $regFormId; ?>" class="examTaken_<?php echo $regFormId; ?>" name="examTaken" value="bookedExamDate" <?php if(!empty($examTaken) && $examTaken == 'bookedExamDate') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examTaken'); ?> >
                            <label for="examTaken_bookedExamDate_<?php echo $regFormId; ?>">
                                <span class="common-sprite"></span>
                                <p><strong><?php echo $fields['bookedExamDate']->getLabel(); ?></strong></p>
                            </label>
                        </div>
                        <input type="hidden" name="bookedExamDate" id="bookedExamDate_<?php echo $regFormId; ?>" value="<?php echo ($examTaken=='bookedExamDate'?1:0); ?>"/>
                        <div class="input-helper" id="examTaken_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                        </div>

                    <div class="signUp-child-wrap <?php if(empty($abroadShortRegistrationData['examsAbroad'])){echo 'hidden'; } ?>">
                        <!-- exam -->
                            <div class="n-max-li">
                              <p class="mb8 sm-fnt">Select &amp; enter your exam score</p>
                                <ul class="customInputs-large">
                                <?php
                                    global $examGrades;
                                    global $examFloat;
                                    $count = 0;
                                    $examsAbroad = $abroadShortRegistrationData['examsAbroad'];
                                    foreach($examsAbroadMasterList as $examId => $exam)
                                    {
                                        $count++;
                                        $filled = '';
                                        $value = '';
                                        $labelFor = 'exam_'.$exam['name'].'_'.$regFormId;
                                        $checked='';
                                        if(!empty($examsAbroad) && isset($examsAbroad[$exam['name']])) {
                                            $checked = 'checked';
                                            $filled = 'filled = "1"';
                                            if(isset($examGrades[$exam['name']])) {
                                                $value = $examGrades[$exam['name']][(int)$examsAbroad[$exam['name']]];
                                            }
                                            else {
                                                $value = $examsAbroad[$exam['name']];
                                                if($examFloat[$exam['name']] !== TRUE) {
                                                $value = (int)$value;
                                                }
                                            }
                                        }
                                        if($count % 2 == 1) { echo '<li>'; }
                                ?>
                                        <div class="flLt <?php if($count % 2 == 1) { echo 'mR30'; } ?> examBlock-width">
                                            <div class="flLt exam-name">
                                              <input datafieldtype = "exams" name="exams[]" <?php echo $checked; ?> id="exam_<?php echo $exam['name']; ?>_<?php echo $regFormId; ?>" value="<?php echo $exam['name']; ?>" <?php echo $filled; ?> <?php echo $registrationHelper->getFieldCustomAttributes('examsAbroad'); ?> type="checkbox">
                                              <label for="<?php echo $labelFor; ?>">
                                                <span class="common-sprite" <?php echo $checked; ?>></span>
                                                   <?php echo $exam['name']; ?>
                                              </label>
                                            </div>
											<?php
											// following variable returns true if input textbox is to be disabled
											$disabledCheckVal = ($checked !== 'checked' ||		// if corresponding checkbox is unchecked, it should be disabled
																 ($checked === 'checked' && $exam['name']==='TOEFL' && $value === 0) || 	// TOEFL can have 0 as a valid score so no worry
																 ($checked === 'checked' && !empty($value) && $value !== '0.0')		// if it's checked & score is invalid, enable; so that it can be validated
																);
											?>
                                            <input datafieldtype = "examScore" <?php echo ($disabledCheckVal?'disabled="disabled"':''); ?>  maxlength="4" minscore="<?php echo $exam['minScore']; ?>" maxscore="<?php echo $exam['maxScore']; ?>" range="<?php echo $exam['range']; ?>" caption="<?php echo $exam['name']; ?> score"  class="universal-text examScore text-width
                                            <?php echo ($disabledCheckVal?'disable-field':''); ?>" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>" exam="<?php echo $exam['name']; ?>" name="<?php echo $exam['name']; ?>_score" value="<?php echo $value;?>" placeholder="Score" type="text">
                                            <div class="input-helper" id="<?php echo $exam['name']; ?>_score_<?php echo $regFormId; ?>_error">
                                                <div class="up-arrow"></div>
                                                <div class="helper-text"></div>
                                            </div>
                                            <input type="hidden" id="<?php echo $exam['name']; ?>_scoreType_<?php echo $regFormId; ?>" regfieldid="<?php echo $exam['name']; ?>_scoreType" name="<?php echo $exam['name']; ?>_scoreType" value="<?php echo $exam['scoreType']; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                <?php
                                    if($count % 2 == 0) { echo '</li>'; }
                                    }
                                ?>
                                </ul>
                            </div>
                            <!-- exam -->
                        <div class="clearfix"></div>
                    </div>
                </li>
                <?php
                    $display = 'hidden';
                    if($examTaken == 'no'){
                        $display = '';
                    }
                ?>
				<li class="passportField <?php echo $display;?>">
                    <div class="signUp-child-wrap">
                        <label>Do you have a valid passport?</label>
                        <div class="columns wd50">
                            <input datafieldtype = "radio" type="radio" id="passport_yes_<?php echo $regFormId; ?>" class="passport_<?php echo $regFormId; ?>" name="passport" value="yes" <?php if(!empty($passport) && $passport == 'yes') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?> >
                            <label for="passport_yes_<?php echo $regFormId; ?>">
                                <span class="common-sprite"></span>
                                <p><strong>Yes</strong></p>
                            </label>
                        </div>
                        <div class="columns wd50">
                            <input datafieldtype = "radio" type="radio" id="passport_no_<?php echo $regFormId; ?>" class="passport_<?php echo $regFormId; ?>" name="passport" value="no" <?php if(!empty($passport) && $passport == 'no') echo 'checked'; ?> <?php echo $registrationHelper->getFieldCustomAttributes('passport'); ?> >
                            <label for="passport_no_<?php echo $regFormId; ?>">
                                <span class="common-sprite"></span>
                                <p><strong>No</strong></p>
                            </label>
                        </div>
                        <div class="input-helper" id="passport_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
            <div class="flRt sp-f-blk">
                <ul class="clear customInputs">
                <?php
                    $display = '';
                    $graduationStream ='';
                    if(!empty($educationDetails['graduationStream'])){
                        $graduationStream = $educationDetails['graduationStream'];
                        $display = 'filled';
                    }
                    $hidden= 'hidden';
					$mandatory = '';
                    if(in_array($level,$masterLevels) || $currentLevel == 'Masters' || $currentLevel == 'PhD'){
                        $hidden= '';
						$mandatory = 'mandatory="1"';
                    }
                ?>
                <li class="MastersEdu <?php echo $hidden; ?>">
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Graduation Stream</div>
                            <div class="input"><?php echo $graduationStream;?></div>
                            <div class="input-helper" id="graduationStream_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" id="graduationStream_<?php echo $regFormId; ?>" class="drpdwn" name="graduationStream" <?php echo $registrationHelper->getFieldCustomAttributes('graduationStream')." ".$mandatory ; ?> >
                                <option value=""></option>
                                <?php foreach($fields['graduationStream']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($key == $graduationStream)?'selected':''?> ><?php echo $val; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
						$display = '';
						$graduationPercentage ='';
						if(!empty($educationDetails['graduationPercentage'])){
							$graduationPercentage = $educationDetails['graduationPercentage'];
							$display = 'filled';
						}
					?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Graduation Percentage</div>
                            <div class="input"><?php echo (!empty($graduationPercentage))?$graduationPercentage:'';?></div>
                            <div class="input-helper" id="graduationMarks_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" id="graduationMarks_<?php echo $regFormId; ?>" class="drpdwn" <?php echo $registrationHelper->getFieldCustomAttributes('graduationMarks')." ".$mandatory; ?> name="graduationMarks">
                                <option value=""></option>
                                <?php foreach($fields['graduationMarks']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($val == $graduationPercentage)?'selected':'' ?> ><?php echo $val; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="signUp-child-wrap">
                        <?php
                            $display = '';
                            $workExperience ='';
                            $workExperienceValuesForSA = $fields['workExperience']->getSAValues();
                            if(is_numeric($userWorkExperience) &&
                                in_array($userWorkExperience,array_keys($workExperienceValuesForSA))){
                                $workExperience = $workExperienceValuesForSA[$userWorkExperience];
                                $display = 'filled';
                            }
                        ?>
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Work Experience</div>
                            <div class="input"><?php echo $workExperience;?></div>
                            <div class="input-helper" id="workExperience_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" name="workExperience" class="drpdwn" id="workExperience_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('workExperience')." ".$mandatory; ?> >
                                <option value=""></option>
                                <?php foreach($workExperienceValuesForSA as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($key === $userWorkExperience)?'selected="selected"':'';?> ><?php echo $val; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
                <?php
                    $display = '';
                    $currentSchool ='';
                    if(!empty($educationDetails['currentSchoolName'])){
                        $currentSchool = $educationDetails['currentSchoolName'];
                        $display = 'filled';
                    }

                    $hidden= 'hidden';
                    if(in_array($level,$bachelorLevels) || $currentLevel == 'Bachelors'){
                        $hidden= '';
						$mandatory = 'mandatory="1"';
                    }
                ?>
                <li class="BachelorsEdu <?php echo $hidden; ?> ">
                    <div class="signUp-child-wrap">
                        <div class="sp-frm invalid currSchoolField <?php echo $display;?>">
                            <div class="placeholder">Current School Name</div>
                            <input datafieldtype = "text" class="input txtField" name="currentSchool" id="currentSchool_<?php echo $regFormId; ?>" minlength="1" maxlength="100" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('currentSchool'); ?> value="<?php echo $currentSchool;?>">
                            <div class="input-helper" id="currentSchool_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                        </div>
                    </div>
                    <?php
                        $currentClassValues = $fields['currentClass']->getValues();
                        $display = '';
                        $currentClass ='';
                        if(!empty($educationDetails['currentClass'])){
                            $currentClass = $educationDetails['currentClass'];
                            $display = 'filled';
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Current Class</div>
                            <div class="input"><?php  echo $currentClassValues[$currentClass]?></div>
                            <div class="input-helper" id="currentClass_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" name="currentClass" class="drpdwn" id="currentClass_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('currentClass')." ".$mandatory; ?> >
                                <option value=""></option>
                                <?php foreach($currentClassValues as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($key == $currentClass)?'selected':''?> ><?php echo $val; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                        $tenthBoardValues = $fields['tenthBoard']->getValues();
                        $display = '';
                        $tenthBoard ='';
                        if(!empty($educationDetails['tenthBoard'])){
                            $tenthBoard = $educationDetails['tenthBoard'];
                            $display = 'filled';
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder">Class Xth Board</div>
                            <div class="input"><?php echo $tenthBoardValues[$tenthBoard];?></div>
                            <div class="input-helper" id="tenthBoard_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" name="tenthBoard" class="drpdwn" id="tenthBoard_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthBoard')." ".$mandatory; ?> >
                                <option value=""></option>
                                <?php foreach($fields['tenthBoard']->getValues() as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo ($key == $tenthBoard)?'selected':''?> ><?php echo $val; ?></option>
                                <?php } ?>
                            </select>
                            <div class="city-lr tenthBrd">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php
                        $marksValues = $fields['tenthmarks']->getValues();
                        $display = '';
                        $realMarks = '';
                        if(!empty($educationDetails['tenthMarks'])){
                            $tenthMarks = $educationDetails['tenthMarks'];
                            if($educationDetails['tenthBoard'] == 'CBSE'){
                                global $CBSE_Grade_Mapping;
                                $marks = array_flip($CBSE_Grade_Mapping);
                                $realMarks = $marks[$tenthMarks];
                            }elseif($educationDetails['tenthBoard'] == 'IGCSE'){
                                global $IGCSE_Grade_Mapping;
                                $marks = array_flip($IGCSE_Grade_Mapping);
                                $realMarks = $marks[$tenthMarks];
                            }else{
                                $realMarks = $educationDetails['tenthMarks'];
                            }
                            $display = 'filled';
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm selectField signup-fld invalid <?php echo $display;?>">
                            <div class="placeholder" id="marksType">Class Xth CGPA</div>
                            <div class="input"><?php echo $realMarks;?></div>
                            <div class="input-helper" id="tenthmarks_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" name="tenthmarks" class="drpdwn" id="tenthmarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('tenthmarks')." ".$mandatory; ?> >
                                <option value=""></option>
                                <?php
                                    foreach($marksValues as $boardName => $valueSet) {
                                        foreach($valueSet as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" databoard = "<?php echo $boardName; ?>"
                                        <?php
                                            echo (($boardName ==$educationDetails['tenthBoard'])&&($realMarks == $key))?'selected':'';
                                        ?> ><?php echo $val; ?>
                                    </option>
                                <?php }
                                    } ?>
                            </select>
                            <div class="city-lr">
                                <ul>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            </div>
            <div class="clearfix"></div>
        </div>


        <div class="sp-fmLst">
            <strong class="dot"></strong>
            <p>Your personal details<span>This will create your account and enable you to receive recommendations (you can opt-out anytime)</span></p>
        </div>

        <div class="sp-form clear">
            <div class="flLt sp-f-blk">
                <ul class="clear customInputs">
                <li>
                <?php
                    $firstName ='';
                    $disabled = '';
                    $disabledClass = '';
                    if(!empty($formData['firstName'])) {
                        $firstName = $formData['firstName'];
                        $disabled = 'disabled';
                        $disabledClass = "disable-field filled";
                    }
                ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm invalid <?php echo $disabledClass;?>">
                            <div class="placeholder">First Name</div>
                            <input datafieldtype = "text" class="input txtField"  name="firstName" id="firstName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> tabindex="1" value="<?php echo htmlentities($firstName);?>" <?php echo $disabled;?> >
                            <div class="input-helper" id="firstName_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <?php
                        $email ='';
                        $disabled = '';
                        $disabledClass = '';
                        if(!empty($formData['email'])) {
                            $email = $formData['email'];
                            $disabled = 'disabled';
                            $disabledClass = "disable-field filled";
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm invalid <?php echo $disabledClass;?>">
                        <div class="placeholder">Email ID</div>
                        <input datafieldtype = "email" class="input txtField" name="email" id="email_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('email'); ?> tabindex="3" <?php echo $disabled?> value="<?php echo htmlentities($email);?>" >
                        <div class="input-helper" id="email_<?php echo $regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                   </div>
                        <span class="hlp-tt">Use this email to login to Shiksha</span>
                    </div>

                </li>
                <li>
                <?php
                    $isdCode ='';
                    $disabledClass = '';
                    $ISDCodeValues = $fields['isdCode']->getValues();
                    $isdCodeVaildValue = '';
                    if(!empty($abroadShortRegistrationData['isdCode'])) {
                        $disabledClass = "disable-field";
                        if(in_array($formData['isdCode'], array_keys($ISDCodeValues))){
                            $isdCode = $ISDCodeValues[$formData['isdCode']];
                            $isdCodeVaildValue = $formData['isdCode'];
                        }else{
                            $isdCode = $ISDCodeValues['91-2'];
                            $isdCodeVaildValue = '91-2';
                        }
                    }else{
						$isdCodeVaildValue = '91-2';
						$isdCode = reset($ISDCodeValues);
					}
                ?>
                    <div class="signUp-child-wrap" type="layer">
                        <div class="sp-frm selectField signup-fld invalid L-fld <?php echo $disabledClass;?>">
                            <div class="placeholder"><?php echo $isdCode; ?></div>
                            <div class="input"></div>

                            <select class="drpdwn" id="isdCode_<?php echo $regFormId; ?>" name="isdCode">
                            <?php
                                foreach($ISDCodeValues as $key=>$value){ ?>
                            <option value="<?php echo $key; ?>" <?php if($isdCodeVaildValue == $key ){ echo 'selected';} ?>> <?php echo $value; ?> </option>
                            <?php } ?>
                            </select>
                            <?php if(empty($abroadShortRegistrationData['isdCode'])){ ?>
                                <div class="city-lr isdCode">
                                    <ul></ul>
                                </div>
                            <?php } ?>
                        </div>

                        <?php
                            $mobile ='';
                            $disabled = '';
                            $disabledClass = '';
                            if(!empty($formData['mobile'])) {
                                $mobile = $formData['mobile'];
                                $disabled = 'disabled="disabled"';
                                $disabledClass = "disable-field filled";
                            }
							if($isdCodeVaildValue == '91-2')
							{
								$minlength = $maxlength  = 10;
							}else{
								$minlength = 6; $maxlength  = 20;
							}
                        ?>
                        <div class="sp-frm invalid R-fld <?php echo $disabledClass;?>">
                            <div class="placeholder">Mobile Number</div>
                            <input datafieldtype = "mobile" class="input mobileField" name="mobile" id="mobile_<?php echo $regFormId; ?>" maxlength="<?php echo $maxlength; ?>" minlength="<?php echo $minlength; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> tabindex="4" <?php echo $disabled?> value="<?php echo htmlentities($mobile); ?>"  >
                            <div class="input-helper" id="mobile_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <span class="hlp-tt">One time passcode will be sent to this number</span>
                    </div>
                </li>
            </ul>
            </div>
            <div class="flRt sp-f-blk">
                <ul class="clear customInputs">
                <li>
                    <?php
                        $lastName ='';
                        $disabled = '';
                        $disabledClass = '';
                        if(!empty($formData['lastName'])) {
                            $lastName = $formData['lastName'];
                            $disabled = 'disabled';
                            $disabledClass = "disable-field filled";
                        }
                    ?>
                    <div class="signUp-child-wrap">
                        <div class="sp-frm invalid <?php echo (!empty($lastName))?$disabledClass:'' ?>">
                            <div class="placeholder">Last Name</div>
                            <input datafieldtype = "text" class="input txtField" name="lastName" id="lastName_<?php echo $regFormId; ?>" minlength="1" maxlength="50" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> tabindex="2" <?php echo $disabled; ?> value="<?php echo htmlentities($lastName)?>"  >
                            <div class="input-helper" id="lastName_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                <?php
                    $selected = '';
                    if($abroadShortRegistrationData['isdCode'] != '' && $abroadShortRegistrationData['isdCode'] != '91'){
                        $selected = 'selected';
                    }
                ?>
                    <div class="signUp-child-wrap hover-cl">
                        <div class="sp-frm selectField selectCityField  signup-fld invalid">
                            <div class="placeholder">Current City</div>
                            <div class="input cityField"></div>
                            <div class="input-helper" id="residenceCity_<?php echo $regFormId; ?>_error">
                                <div class="up-arrow"></div>
                                <div class="helper-text"></div>
                            </div>
                            <select datafieldtype = "select" class="hidden cityList" name="residenceCity" id="residenceCity_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('residenceCity'); ?> >
                                <?php $this->load->view('registration/common/dropdowns/residenceCity',array('defaultOptionText' => 'What is your current city?', 'order' => 'alphabetical','formData'=>array('residenceCity'=>$userCity))); ?>
								<option style="display: none;" <?php echo $selected;?> value="-1">City not required</option>
                            </select>
                            <div class="city-lr">
                                <div class="cty-src"><input id = "cityAutocomplete" value="" type="text"></div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            </div>
            <div class="clearfix"></div>
            <?php if(($universityId > 0 || $courseId >0) && $MISTrackingDetails['conversionType'] == "response"){ ?>
            <input id="listingTypeForResponse" name="listingTypeForBrochure" value="course" type="hidden">
            <input id="listingTypeIdForResponse" name="listingTypeIdForBrochure" value="<?php echo ($universityId > 0 ? '':$courseId); ?>" type="hidden">
            <input id="desiredCourseForResponse" name="desiredCourse" value="<?php echo $ldbCourseId; ?>" type="hidden" />
            <input id="isPaidForResponse" name="isPaid" value="<?php echo $isPaid; ?>" type="hidden" />
            <input id="abroadSpecializationForResponse" name="abroadSpecialization" value="<?php echo $subcategory; ?>" type="hidden" />
            <?php }else if($MISTrackingDetails['conversionType'] == 'Course shortlist'){ ?>
            <input id="listingTypeIdForResponse" name="listingTypeIdForBrochure" value="<?php echo ($universityId > 0 ? '':$courseId); ?>" type="hidden">
                <?php if($universityId > 0){ // Shortlist/save on ULP/ACP ?>
                <input id="desiredCourseForResponse" name="desiredCourse" value="<?php echo $ldbCourseId; ?>" type="hidden" />
                <input id="abroadSpecializationForResponse" name="abroadSpecialization" value="<?php echo $subcategory; ?>" type="hidden" />
                <input id="isPaidForResponse" name="isPaid" value="<?php echo $isPaid; ?>" type="hidden" />
                <?php } ?>
            <?php }else if($scholarshipId > 0 && $MISTrackingDetails['conversionType'] == "response"){
                ?>
            <input id="listingTypeForResponse" name="listingTypeForBrochure" value="scholarship" type="hidden">
            <input id="listingTypeIdForResponse" name="listingTypeIdForBrochure" value="<?php echo $scholarshipId; ?>" type="hidden">
            <?php } ?>
			<input id="courseLevelForResponse" name="courseLevel" value="<?php echo ($level !='' ? $level:($currentLevel !=''?$currentLevel:'' )); ?>" type="hidden" />
        </div>
        <?php   if(empty($formData['userId']) || $formData['userId'] == 0){?>
                    <div class="signUp-child-wrap clear">
                        <div class="sp-frm selectCountryField clear reg-chkbxSec customInputs">
                            <div class="in-tab">
                                <input datafieldtype="checkbox" type="checkbox" <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"checked":"")?> id="prv_plcy" name="prv_plcy" mandatory="1" caption="Privacy, and Terms and Conditions">
                                <label class="nolnht" for="prv_plcy">
                                    <span class="common-sprite"></span>
                                    <p>Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the <a dataurl="<?=SHIKSHA_STUDYABROAD_HOME?>/shikshaHelp/ShikshaHelp/privacyPolicy" href="javascript:void(0);">Privacy Policy</a> and <a dataurl="<?=SHIKSHA_STUDYABROAD_HOME?>/shikshaHelp/ShikshaHelp/termCondition" href="javascript:void(0);">Terms &amp; Conditions</a>.</p></label>
                            </div>
                        </div>
                        <div class="input-helper" id="prv_plcy_<?=$regFormId; ?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                    </div>
                    <div class="signUp-child-wrap clear">
                        <div class="sp-frm selectCountryField clear reg-chkbxSec customInputs">
                            <div class="in-tab">
                                <input datafieldtype="checkbox" type="checkbox" <?=(!in_array($_SERVER['GEOIP_COUNTRY_CODE'], $europeanCountries)?"checked":"")?> id="agr_purps" name="agr_purps" regformid="<?=$regFormId;?>" type="checkbox" mandatory="1" caption="Promotional Purpose Consent">
                                <label class="nolnht" for="agr_purps">
                                    <span class="common-sprite"></span>
                                    <p>I agree to be contacted for service related information and promotional purposes.
                                        <i class="common-sprite agree-cntIcn" onmouseenter="toggleTooltip(this);" onmouseleave="toggleTooltip(this);">
                                            <span class="input-helper" id="responsiveness0" style="display: none">
                                                <span class="up-arrow"></span>
                                                <span class="helper-text"></span>
                                            </span>
                                        </i>
                                    </p></label>
                            </div>
                        </div>
                        <div class="input-helper" id="agr_purps_<?=$regFormId;?>_error">
                            <div class="up-arrow"></div>
                            <div class="helper-text"></div>
                        </div>
                    </div>
        <?php   }?>
    </div>
    <?php if(is_array($invalidEmailDomains) && count($invalidEmailDomains)>0) { ?>
    <script>var invalidDomains = JSON.parse('<?php echo json_encode($invalidEmailDomains); ?>');</script>
    <?php } ?>
