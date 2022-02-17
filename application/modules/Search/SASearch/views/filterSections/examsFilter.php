<?php 
    $appliedExam = reset($pageData['appliedFilter']['exams']); 
    $appliedExamScore = $pageData['appliedFilter']['examScore'];
    sort($appliedExamScore);
    $selectedStr = '';
    $showExScSlider = false;
    if(count($filters['exam'])>0){
?>
<div class="newAcrdn active">
    <h2>Eligibility Exam <i class="custm__ico"></i></h2>
    <div class="contentBlock">
        <div class="examPlaceholder">
            <i class="srpSprite icArow"></i>
            <select class="srchExam toggleFilter" fType="eligibilityExams" alias="ex" name="srchExam">
                <option value="">Select exam</option>
                <?php foreach($filters['exam'] as $examData){ 
                    if($appliedExam == $examData['name'])
                    {
                        $selectedStr = ($appliedExam == $examData['name']?'selected="selected"':'');
                        $minScore = $minLt= $examData['scores']['min'];
                        $maxScore = $maxLt= $examData['scores']['max'];
                        $minScore = (!is_null($appliedExamScore[0])?$appliedExamScore[0]:$minScore);
                        $maxScore = (!is_null($appliedExamScore[1])?$appliedExamScore[1]:$maxScore);
                        //echo $minScore.":".$maxScore;
                        $scale = $examData['scores']['scale'];
                        // if values are beyond valid range, set as either of the limits
                        if($minScore<$minLt || $minScore > $maxLt)
                        {
                            $minScore = $minLt;
                        }
                        if($maxScore>$maxLt || $maxScore < $minLt)
                        {
                            $maxScore = $maxLt;
                        }
                        // if min value is invalid, round off to nearest step
                        if(($mod = fmod($minScore,$scale)) !=0)
                        {
                            if($mod>($scale/2))
                            {
                                $minScore = $minScore + ($scale-$mod);
                            }else{
                                $minScore = $minScore - $mod;
                            }
                        }
                        if(($mod = fmod($maxScore,$scale)) !=0)
                        {
                            if($mod>($scale/2))
                            {
                                $maxScore = $maxScore + ($scale-$mod);
                            }else{
                                $maxScore = $maxScore - $mod;
                            }
                        }
                        $showExScSlider = true;
                    }else{
                        $selectedStr = '';
                    }
                echo '<option value="'.$examData['name'].'" min="'.$examData['scores']['min'].'" max="'.$examData['scores']['max'].'" scale="'.$examData['scores']['scale'].'" '.$selectedStr.'>'.$examData['name'].'</option>';
                } ?>
            </select>
        </div>
        <div class="slidePlace" style="<?php echo ($showExScSlider?'':'display:none'); ?>">
            <div class="examLabel" style="<?php echo (!is_null($minScore) && $minScore == $maxScore?'display:none':''); ?>">
                <span class="leftLabel"><?php echo $minScore; ?></span>
                <span class="rightLabel"><?php echo $maxScore; ?></span>
                <input type="hidden" class="srchSliderMinLt" value="<?php echo $minLt; ?>">
                <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minScore; ?>">
                <input type="hidden" class="srchSliderMaxLt" value="<?php echo $maxLt; ?>">
                <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxScore; ?>">
                <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $scale; ?>">
                <input type="hidden" class="schAmountMinSlct" value="<?php echo $minScore; ?>">
                <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxScore; ?>">
            </div>
            <div id="examSrchScoreSlider" class="srchSlider toggleFilter" fType="eligibilityExamScore" alias="es" ></div>
            <div class="filterNa" style="<?php echo (!is_null($minScore) && $minScore == $maxScore?'':'display:none'); ?>">This filter option is not available.</div>
        </div>
    </div>
</div>
<?php } ?>