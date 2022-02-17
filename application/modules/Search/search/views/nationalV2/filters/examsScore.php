<?php 
global $MBA_NO_OPTION_EXAMS;
global $MBA_EXAMS_REQUIRED_SCORES;
global $ENGINEERING_EXAMS_REQUIRED_SCORES;
$examScoreSplit = explode("|", $score[$exam]);
$examMinScore   = $examScoreSplit[0];
$examMaxScore   = $examScoreSplit[1];

$examIdString = str_replace(" ", "-", $exam);

if($request->getSubCategoryId() == 23){
    if(in_array($exam, $MBA_NO_OPTION_EXAMS)){
        $examScoreContDisplayStyle = "display:none;";
    }

    $placeHolderText = "Cut-off range (%ile)";
    $type = "percentile";
    if(in_array($exam, $MBA_EXAMS_REQUIRED_SCORES)){
        $placeHolderText = "Cut-off range (score)";
        $type = "marks";
    }

 }else if($request->getSubCategoryId() == 56){
    $placeHolderText = "Cut-off range (rank)";
    $type = "marks";
    if(in_array(trim($examName), $ENGINEERING_EXAMS_REQUIRED_SCORES)){
            $placeHolderText = "Cut-off range (marks)";
    }
 }   

?>
<form formname="colleges" onsubmit="return false;">
    <div class="locality-link" style="<?=$examScoreContDisplayStyle?>">
        <p class="examCutoffMarks"><?=$placeHolderText?></p>
        <ul class="cuttof-marks">
            <li><input type="text" id="minScore<?=$examIdString?>" autocomplete="off" placeholder="min"  onkeyup="validateExamRange(this,'<?=$type?>')"  value="<?=$examMinScore?>"></li>
            <li class="cuttof-marksMax">
            <input type="text" id="maxScore<?=$examIdString?>"  autocomplete="off" placeholder="max" onkeyup="validateExamRange(this,'<?=$type?>')"  value="<?=$examMaxScore?>"></li>
            <li><input type="submit" id="examSubmit<?=$exam?>" value="Go"></li>
        </ul>
        <p class="clr"></p>
        <p class="errorMsgExam"  style="display:none;">Enter a valid range</p>
        <p class="errorMsgExamNoResult" id="noResult<?=$exam?>">No colleges in this range</p>
        <p class="clr"></p>
    </div>
</form>
