<?php global $examGrades; ?>

<?php foreach($examList as $examId => $examDetails) { ?>

<?php $examId = $examDetails['name']; ?>

<?php if($inMMM) { ?>
<div style="width:470px; line-height: 12px;">
<?php } else { ?>
<div style="width:500px; line-height: 12px;">
<?php } ?>

<div style='float:left; width:20px;'>
    <input type="checkbox" id="exam_<?php echo $examId; ?>" name="exams[]" value="<?php echo $examId; ?>" onclick="toggleExamDetails(this,'<?php echo $examId; ?>')" />
</div>    

<div style='float:left; width:<?php if($examDetails['scoreType']) { echo "60"; } else { echo "300"; } ?>px; padding-top:5px;'>
<?php echo $examDetails['name']; ?>
<input type='hidden' name="<?php echo $examId; ?>_displayname" value="<?php echo $examDetails['name']; ?>" />
</div>

<?php if($examDetails['scoreType']) { ?>

<div style='float:left; width:60px; text-align: right; padding-top:4px;'>
<?php echo $examDetails['scoreType']; ?>: &nbsp;
</div>

<div style='float:left; width:140px;'>
<?php if($examDetails['maxScore']) { ?>     
<select id="exam_<?php echo $examId; ?>_min_score" name="<?php echo $examId; ?>_min_score" disabled="true">
    <option value="">Min</option>
    <?php
    if($examDetails['scoreType'] == 'grades') {
        foreach(range($examDetails['minScore'],$examDetails['maxScore']) as $gradeScore) {
            echo "<option value=\"".$examGrades[$examDetails['name']][$gradeScore]."\">".$gradeScore."</option>";
        }
    }
    else {
        $range = $examDetails['range'] ? $examDetails['range'] : 1;
        for($i=$examDetails['minScore'];$i<=$examDetails['maxScore'];$i+=$range)
        {
            echo "<option value=\"".$i."\">".$i."</option>";
        }
    }
    ?>
</select> &nbsp; 
<select id="exam_<?php echo $examId; ?>_max_score" name="<?php echo $examId; ?>_max_score" disabled="true">
    <option value="">Max</option>
    <?php
    if($examDetails['scoreType'] == 'grades') {
        foreach(range($examDetails['minScore'],$examDetails['maxScore']) as $gradeScore) {
            echo "<option value=\"".$examGrades[$examDetails['name']][$gradeScore]."\">".$gradeScore."</option>";
        }
    }
    else {
        $range = $examDetails['range'] ? $examDetails['range'] : 1;
        for($i=$examDetails['minScore'];$i<=$examDetails['maxScore'];$i+=$range)
        {
            echo "<option value=\"".$i."\">".$i."</option>";
        }
    }
    ?>
</select>
<?php } else { ?>
<input type='text' id="exam_<?php echo $examId; ?>_min_score" name="<?php echo $examId; ?>_min_score" disabled="true" style="width:60px;" maxlength='5' value='Min' onfocus="if(this.value=='Min') this.value='';" onblur="if(this.value=='') this.value='Min';" />
<input type='text' id="exam_<?php echo $examId; ?>_max_score" name="<?php echo $examId; ?>_max_score" disabled="true" style="width:60px;" maxlength='5' value='Max' onfocus="if(this.value=='Max') this.value='';" onblur="if(this.value=='') this.value='Max';" />
<?php } ?>
</div>
<?php } ?>
<div class='clearFix'></div>
</div>
<div style="line-height:6px">&nbsp;</div>
<?php } ?>