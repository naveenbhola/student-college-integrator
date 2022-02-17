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

<div id="examSection_<?php echo $examSectionNumber; ?>" class="examContainer">
    <a class="cross-sec clearfix removeNatExam" href="#">
        <i counter="<?php echo $examSectionNumber; ?>" class="up-cross removeExamMP">&times;</i>
    </a>
    <ul class="wrkexp-ul">
        <li>
            <div class="text-show <?php if(!empty($preSelectedExamNames[$examSectionNumber-1])){ echo 'filled1'; } ?>">
                <label class="form-label">Exam Name</label>
                <input type="text" class="user-txt-flds ssLayer" value="<?php echo str_replace('_', ' ',$preSelectedExamNames[$examSectionNumber-1]); ?>" readonly="readonly" id="exam_<?php echo $examSectionNumber; ?>_<?php echo $regFormId; ?>_input" >
                    <a class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup" aria-haspopup="true" aria-owns="myPopup" aria-expanded="false" style=""></a>
                    <em class="pointerDown"></em>
                </div>
                <div style="display: none;">
                     <div class="regErrorMsg one" id="exam_error_<?=$examSectionNumber?>_<?php echo $regFormId; ?>"></div>
                </div>
                <div class="select-Class">
                    <select id="exam_<?php echo $examSectionNumber; ?>_<?php echo $regFormId; ?>" name="exams[]" examid="11" class="select-hide examSelect">
                        <option value="">Exam Name</option>
            
                            <?php 
                            $preSelectedExamId = '';
                            foreach($examNames as $examId => $examName) { ?>

                                <option value="<?php echo $examId; ?>" minscore="<?php echo $minExamScores[$examId]; ?>" maxscore="<?php echo $maxExamScores[$examId]; ?>" <?php if(!empty($examScoresType[$examId])) { echo 'scoretype="'.$examScoresType[$examId].'"'; } ?> <?php if($preSelectedExamNames[$examSectionNumber-1] == $examId) { echo 'selected="selected"';$preSelectedExamId = $examId; } ?>><?php echo $examName; ?></option>

                            <?php
                            } ?>

                   </select>
               </div>

      <?php if((!empty($otherExamName)) && ($preSelectedExamId == 'Other')) { ?>
        <div class="text-show filled1" id="examotherSection_<?=$examSectionNumber?>_<?=$regFormId?>"><label class="form-label">Other Exam Name</label><input mandatory="1" value="<?=$otherExamName?>" regfieldid="examother_<?=$examSectionNumber?>" caption="Exam Name" name="otherExamName" minscore="0" maxscore="99999" id="examother_<?=$examSectionNumber?>_<?=$regFormId?>" maxlength="20" class="user-txt-flds examScore" type="text"></div><div style="display:none"><div id="examother_<?=$examSectionNumber?>_error_<?php echo $regFormId; ?>" class="regErrorMsg examNameScore"></div></div>
       <?php } ?>

    </li>

    <?php if((!empty($preSelectedExamId)) && (empty($examScoresType[$preSelectedExamId]))) { ?>
    <li id="<?=$examSectionNumber?>_examScoreSection_<?=$regFormId?>" style="display:none">
    <?php  } else { ?>
    <li id="<?=$examSectionNumber?>_examScoreSection_<?=$regFormId?>">
    <?php } ?>

            <div class="text-show <?php if(!empty($preSelectedExamScores[$examSectionNumber-1])){ echo 'filled1'; }else{ echo 'disable-input';} ?>">
                <label class="form-label">Rank/Score</label>
                <input type="text" type="text" minscore="<?php echo $minExamScores[$preSelectedExamId]; ?>" maxscore="<?php echo $maxExamScores[$preSelectedExamId]; ?>" <?php if(!isset($preSelectedExamScores[$examSectionNumber-1])) { echo 'disabled="disabled"'; } ?> value="<?php if(!empty($preSelectedExamScores[$examSectionNumber-1]) && $preSelectedExamScores[$examSectionNumber-1] != 0){echo $preSelectedExamScores[$examSectionNumber-1];} ?>" name="<?php if(!empty($preSelectedExamId)) { echo $preSelectedExamId.'_score'; };?>" id="<?=$examSectionNumber?>_score_<?=$regFormId?>" regfieldid="<?php echo $examSectionNumber.'_score';?>" caption="<?php echo $examNames[$preSelectedExamId].' '.$examScoresType[$preSelectedExamId];?>" maxlength="5" validateexamscore="1" class="user-txt-flds examScore">
                <input type="hidden" name="<?php if(!empty($preSelectedExamId)) { echo $preSelectedExamId.'_scoreType'; };?>" id="<?=$examSectionNumber?>_scoreType_<?=$regFormId?>" value="<?php echo $preSelectedExamScoresType[$examSectionNumber-1];?>"/>
            </div>
            <div style="display:none">
                <div class="regErrorMsg two" id="<?=$examSectionNumber?>_score_error_<?php echo $regFormId; ?>"></div>
            </div>
        </li>

    </ul>
</div>

<?php } ?>

#@#@#@<?php echo count($examIds);?>









