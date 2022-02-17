<?php
$examLayerStyle= "height:100px; overflow:auto;";
if(preg_match('/Android\s[0-9\.]*/',$_SERVER['HTTP_USER_AGENT'],$matches)){
$match = explode(' ', $matches[0]);
$androidVersion = $match[1];
if($androidVersion >= 3){
        $examLayerStyle= "height:100px; overflow:auto;";
}else{
         $examLayerStyle= '';
}
}

$listExams = $fields['exams']->getValues();
if(!empty($listExams)){ ?>
<!--<li style="position: relative;" onclick="showHideExamLayer('examLayer_<?php echo $regFormId; ?>');" id="exams_block_<?php echo $regFormId; ?>">
    <select name="desiredCourse" id="examLayerButton_<?php echo $regFormId; ?>">
        <option value="" id="examContainer_<?php echo $regFormId; ?>">Exam Taken</option>
    </select>
    <div class="icon-wrap"><i class="reg-sprite marks-icon" ></i></div>
    <div>
        <div class="regErrorMsg" id="exams_error_<?php echo $regFormId; ?>"></div>
    </div>
    
    -->
   <!-- <li <?php //echo $registrationHelper->getBlockCustomAttributes('exams','position:relative;'); ?>>
  	<div class="custom-select">
    <select>
        <option value=""></option>
    </select>
    <div class="icon-wrap"><i class="reg-sprite marks-icon" ></i></div>
    </div>
    
    <div onclick="showHideExamLayer('examLayer_<?php echo $regFormId; ?>');" class="custom-select-overlay" style="border:1px solid red">
      <span id="examContainer_<?php echo $regFormId; ?>">Exam Taken</span>
      <div class="icon-wrap"><i class="reg-sprite marks-icon" style="left:-26px"></i></div>
    </div>-->
    <li <?php echo $registrationHelper->getBlockCustomAttributes('exams','position:relative;'); ?> >
<!--        <a class="selectbox" onclick="showHideExamLayer('examLayer_<?php echo $regFormId; ?>');">
         <div style="border-radius: 0;
    color: #000000 !important;
    display: block;
    font-weight: normal !important;
    outline: 0 none;
    padding: 11px 4px 8px 6px;
    text-decoration: none;
    text-shadow: 0 1px 0 #fff;
    width: 88%;">
          <span id="examContainer_<?php echo $regFormId; ?>">Exam Taken</span>
          <div class="icon-wrap"><i class="reg-sprite marks-icon" style="left:-26px"></i></div>
         </div>
        
</a>-->
<div class="ui-select" onclick="showHideExamLayer('examLayer_<?php echo $regFormId; ?>');">
	<div data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-icon="arrow-d" data-iconpos="right" data-theme="c" class="ui-btn ui-shadow ui-btn-corner-all ui-btn-icon-right ui-btn-up-c"><span class="ui-btn-inner"><span class="ui-btn-text"><span  id="examContainer_<?php echo $regFormId; ?>">Exam Taken</span></span><span class="ui-icon ui-icon-arrow-d ui-icon-shadow">&nbsp;</span></span>
	<div class="icon-wrap"><i class="reg-sprite marks-icon"></i></div>
	</div>
</div>
   
    <div>
        <div class="regErrorMsg" id="exams_error_<?php echo $regFormId; ?>"></div>
    </div>
<div class="exam-layer" data-enhance="false" style="display: none;" id="examLayer_<?php echo $regFormId; ?>">
                <ul style="<?php echo $examLayerStyle;?>">
                <?php
                $examBlockCount = 1;
		foreach($listExams as $exams) {
                    foreach($exams as $examId => $exam) {
                        if($mmpFormId){
                ?>
                        
                ?>
                <li>
                    <label>
                        <input type='checkbox' <?php if(in_array($examId, $formData['exams'])) { echo 'checked="checked"'; } ?> name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" />
                        <?php echo $exam->getDisplayNameForUser(); ?>
                    </label>
                    <p><input type="text" value="Enter percentile"></p>
                </li>
                <?php
                        }else{ ?>
                        <li>
                            <label>
                                <input type='checkbox' name="exams[]" class="exams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('exams'); ?> value="<?php echo $examId; ?>" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].toggleExamDetails(this,'<?php echo $examId; ?>');" />
                                <?php echo $exam->getDisplayNameForUser(); ?>
                            </label>
                            <?php if($exam->getScoreType()) { ?>
                            <div class='exam-layer-details-box' id='examDetails_<?php echo $examId; ?>_<?php echo $regFormId; ?>' onmouseout="hideExamLayer('examLayer_<?php echo $regFormId; ?>');">
                                    <input type='hidden' value='<?php echo $exam->getScoreType(); ?>' name="<?php echo $examId; ?>_scoreType" />
                                    <?php if($exam->hasNameBox()) { ?>
                                    <div id='examName_<?php echo $examId; ?>_<?php echo $regFormId; ?>' style="margin-bottom: 5px">
                                    <input type='text' mandatory="1" value='Exam Name' default="Exam Name" caption="Exam Name" maxlength="20" regFieldId="<?php echo $examId; ?>_nameBox" id="<?php echo $examId; ?>_nameBox_<?php echo $regFormId; ?>" name="otherExamName" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                    <div>
                                        <div id="<?php echo $examId; ?>_nameBox_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                    </div>
                                    </div>
                                    <?php } ?>
                                    <div>
                                        <input type='text' value='Enter <?php echo $exam->getScoreType(); ?>' default="Enter <?php echo $exam->getScoreType(); ?>" caption="<?php echo $exam->getScoreType() ?>" maxlength="5" regFieldId="<?php echo $examId; ?>_score" id="<?php echo $examId; ?>_score_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_score" minscore="<?php echo $exam->getMinScore(); ?>" maxscore="<?php echo $exam->getMaxScore(); ?>" validateExamScore='1' onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this);" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
                                        <?php if($exam->getState()) { ?>
                                        <div>
                                                <div id="<?php echo $examId; ?>_state_<?php echo $regFormId; ?>"  style="color:#777;font-size:12px;">
                                                        Accepted for colleges in <?php echo $exam->getState(); ?>
                                                </div>
                                        </div>
                                        <?php } ?>
                                        <div>
                                            <div id="<?php echo $examId; ?>_score_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                        </div>
                                    </div>
                                    <?php if($exam->hasPassingYear()) { ?>
                                    <div id='examYear_<?php echo $examId; ?>_<?php echo $regFormId; ?>'>
                                        <div>
                                            <select caption="year appeared in" regFieldId="<?php echo $examId; ?>_year" id="<?php echo $examId; ?>_year_<?php echo $regFormId; ?>" name="<?php echo $examId; ?>_year" mandatory='1' onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">
                                                    <option value=''>Appeared in</option>
                                                    <?php foreach($exam->getPassingYearValues() as $yk => $yv) { ?>
                                                            <option value='<?php echo $yk; ?>'><?php echo $yv; ?></option>
                                                    <?php } ?>
                                            </select>
                                        </div>
                                        <div class=''>
                                            <div id="<?php echo $examId; ?>_year_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
                                        </div>
                                    </div>
                                    <?php } ?>
                            </div>
                            <?php } ?>
                        </li>        
                    <?php    }
                    }
                    if($examBlockCount++ < count($listExams)) { ?>
			<li><div class="divider"></div></li>
                    <?php }
                }
                ?>

    </ul>

    <div class="btn-row2">
    <a class="button blue small" href="javascript:void(0);" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].setSelectedExams();">OK</a>
    </div>
</div>
</li>
<?php } ?>
<script>
    function showHideExamLayer(id){
        $j('#'+id).toggle();
    }
    function hideExamLayer(id) {
        //$j('#'+id).hide();
    }
</script>
