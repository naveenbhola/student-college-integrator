<?php

$examTotalCount = 1;

$examIds = array();$minExamScores = array();$maxExamScores = array();$examNames = array();$examScoresType = array();
foreach($listExams as $exams) {
  foreach($exams as $examId => $exam) { 
    $examIds[] = $examId;
    $examNames[$examId] = $exam->getDisplayNameForUser();
    $minExamScores[$examId] = $exam->getMinScore();
    $maxExamScores[$examId] = $exam->getMaxScore();
    $examScoresType[$examId] = $exam->getScoreType();
  }
}

if(!empty($preSelectedCompetitiveExam)) {
  $preSelectedCompetitiveExamDetails = json_decode($preSelectedCompetitiveExam);

  if(!empty($preSelectedCompetitiveExamDetails->Name)) {
    $examTotalCount = count($preSelectedCompetitiveExamDetails->Name);

    $preSelectedExamNames = $preSelectedCompetitiveExamDetails->Name;
    $preSelectedExamScores = $preSelectedCompetitiveExamDetails->Marks;
    $preSelectedExamScoresType = $preSelectedCompetitiveExamDetails->MarksType;

    $otherExamName = '';
    foreach($preSelectedExamNames as $key=>$preSelectedExamName) {
      if(!in_array($preSelectedExamName, $examIds)) {
        $preSelectedExamNames[$key] = 'Other';
        $otherExamName = $preSelectedExamName;
        //break;
      }
    } 

  }
}

for($examSectionNumber = 1; $examSectionNumber <= $examTotalCount; $examSectionNumber++) {

?>

<div class="examContainer sectionData" id="examSection_<?=$examSectionNumber?>">
  <div class="remove-lnk"><a onclick="shikshaUserProfileForm['<?php echo $regFormId; ?>'].removeExamSection('<?=$examSectionNumber?>');" href="javascript:void(0);">Delete</a></div>
  <ul class="p-ul" id="examDetail_<?=$examSectionNumber?>">

    <li class="p-l">
      <span class="p-s examdropdown" id="examDropdown_<?=$examSectionNumber?>_<?=$regFormId?>">
          <label class="cursor">Exam Name</label>
          <select id="exam_<?=$examSectionNumber?>_<?=$regFormId?>" class="dfl-drp-dwn cursor" name="exams[]" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].manageExamField(this.value, '<?=$examSectionNumber?>');">
            <option value="">Exam Name</option>
            
            <?php 
            $preSelectedExamId = '';
            foreach($examNames as $examId => $examName) { ?>

                <option value="<?php echo $examId; ?>" minscore="<?php echo $minExamScores[$examId]; ?>" maxscore="<?php echo $maxExamScores[$examId]; ?>" <?php if(!empty($examScoresType[$examId])) { echo 'scoretype="'.$examScoresType[$examId].'"'; } ?> <?php if($preSelectedExamNames[$examSectionNumber-1] == $examId) { echo 'selected="selected"';$preSelectedExamId = $examId; } ?>><?php echo $examName; ?></option>

            <?php
            } ?>

           </select>
           <div>
            <div class="regErrorMsg one" id="exam_error_<?=$examSectionNumber?>_<?php echo $regFormId; ?>"></div>
          </div> 
      </span>

      <?php if((!empty($otherExamName)) && ($preSelectedExamId == 'Other')) { ?>
        <span class="p-s examOtherSection" id="examotherSection_<?=$examSectionNumber?>_<?=$regFormId?>"><label>Other Exam Name</label><input type="text" value="<?=$otherExamName?>" id="examother_<?=$examSectionNumber?>_<?=$regFormId?>" name="otherExamName" caption="Exam Name" regfieldid="examother_<?=$examSectionNumber?>" mandatory="1" maxlength="20" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" class="prf-inpt"><div><div class="regErrorMsg" id="examother_<?=$examSectionNumber?>_error_<?php echo $regFormId; ?>"></div></div></span>
      <?php } ?>

    </li>

    <?php if((!empty($preSelectedExamId)) && (empty($examScoresType[$preSelectedExamId]))) { ?>
    <li class="p-l examSection" id="<?=$examSectionNumber?>_examScoreSection_<?=$regFormId?>" style="display:none">
    <?php  } else { ?>
    <li class="p-l examSection" id="<?=$examSectionNumber?>_examScoreSection_<?=$regFormId?>">
    <?php } ?>

      <span class="p-s">
        <label>Rank/Score</label>
        <input type="text" minscore="<?php echo $minExamScores[$preSelectedExamId]; ?>" maxscore="<?php echo $maxExamScores[$preSelectedExamId]; ?>" <?php if(!isset($preSelectedExamScores[$examSectionNumber-1])) { echo 'disabled="disabled"'; } ?> value="<?php echo $preSelectedExamScores[$examSectionNumber-1];?>" name="<?php if(!empty($preSelectedExamId)) { echo $preSelectedExamId.'_score'; };?>" id="<?=$examSectionNumber?>_score_<?=$regFormId?>" regfieldid="<?php echo $examSectionNumber.'_score';?>" caption="<?php echo $examNames[$preSelectedExamId].' '.$examScoresType[$preSelectedExamId];?>" maxlength="5" validateexamscore="1" placeholder="Exam Score" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" class="prf-inpt">
        
        <input type="hidden" name="<?php if(!empty($preSelectedExamId)) { echo $preSelectedExamId.'_scoreType'; };?>" id="<?=$examSectionNumber?>_scoreType_<?=$regFormId?>" value="<?php echo $preSelectedExamScoresType[$examSectionNumber-1];?>"/>
        <div>
          <div class="regErrorMsg two" id="<?=$examSectionNumber?>_score_error_<?php echo $regFormId; ?>"></div>
        </div>
      </span>
    </li>

    <p class="clr"></p>
  </ul>
</div>

<?php } ?>

#@#@#@<?php echo count($examIds);?>

