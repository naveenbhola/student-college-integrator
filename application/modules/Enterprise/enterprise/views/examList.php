<?php 
    foreach($examValues as $examId => $examName) {
        if($examName != 'No Exam Required') {
?>
<div style="width:500px; line-height: 12px;">
    <div style='float:left; width:20px;'>
        <input type="checkbox" id="exam_<?php echo $examId; ?>" name="exams[]" value="<?php echo $examId; ?>" onclick="toggleExamDetails(this,'<?php echo $examId; ?>')" />
    </div>    
    <div style='float:left; width:270px; padding-top:5px; padding-left:5px;'>
        <?php echo $examName; ?>
        <input type='hidden' name="<?php echo $examId; ?>_displayname" value="<?php echo $examName; ?>" />
    </div>

    <div class='clearFix'></div>
</div>
<div style="line-height:6px">&nbsp;</div>

<?php
        }
        else {
            $noExamId = $examId;
            $noExamName = $examName;
        }
    }
?>
<?php if($noExamName == 'No Exam Required') { ?>
    <div style="width:500px; line-height: 12px;">
        <div style='float:left; width:20px;'>
            <input type="checkbox" id="exam_<?php echo $noExamId; ?>" name="exams[]" value="<?php echo $noExamId; ?>" onclick="toggleExamDetails(this,'<?php echo $noExamId; ?>')" />
        </div>    
        <div style='float:left; width:270px; padding-top:5px; padding-left:5px;'>
            <?php echo $noExamName; ?>
            <input type='hidden' name="<?php echo $noExamId; ?>_displayname" value="<?php echo $noExamName; ?>" />
        </div>
        <div class='clearFix'></div>
    </div>
    <div style="line-height:6px">&nbsp;</div>
<?php } ?>