<?php
    $examSelectComboName = (isset($examSelectComboName) && !empty($examSelectComboName)) ? $examSelectComboName : 'examSelected' ;
    $examSelectComboCaption = (isset($examSelectComboCaption) && !empty($examSelectComboCaption)) ? $examSelectComboCaption : 'Exams' ;
    $examSelectComboType = (isset($examSelectComboType) && !empty($examSelectComboType)) ? $examSelectComboName : 'multiple' ;
    $examSelectOnChange = (isset($examSelectOnChange) && !empty($examSelectOnChange)) ? $examSelectOnChange : 'checkForOtherExam(this)' ;
    $examShowOther = (isset($showOther) ) ? $showOther: true ;
    if(isset($examsList) && is_array($examsList) && count($examsList) > 0) {
?>
<div class="row">
    <div class="r1 bld"><?php echo $examSelectComboCaption;?> :&nbsp;</div>
    <div class="r2">
<?php 
            $mainExamList =array();
            foreach($examsList as $exam){
                $examId = $exam['blogId'];
                $examStuff['blogId'] = $exam['blogId'];
                $examStuff['blogTitle'] = $exam['blogTitle'];
                $parentId = $exam['parentId'] ;
                $mainExamList[$parentId][] = $examStuff;
           }
?>
        <select name="<?php echo $examSelectComboName; ?>[]" id="<?php echo $examSelectComboName; ?>"  <?php echo $examSelectComboType; ?> size="10" onchange="<?php echo $examSelectOnChange; ?>;" style="width:500px">
        <?php
            foreach($mainExamList[0] as $exam) {
                $examId = $exam['blogId'];
                $examTitle = $exam['blogTitle'];
        ?>
            <optgroup title="<?php echo $examTitle; ?>" label="<?php echo $examTitle; ?>" />
            <?php
                foreach($mainExamList[$examId] as $childExams) {
                    $childExamId = $childExams['blogId'];
                    $childExamTitle= $childExams['blogTitle'];
            ?>
                <option label="<?php echo $childExamTitle; ?>" value="<?php echo $childExamId; ?>">&nbsp;&nbsp;<?php echo $childExamTitle; ?></option>
        <?php
                }
            }
            if(isset($otherExam) && trim($otherExam) != "") {
                $selectOtherExam = 'selected';
                $otherExamValue = json_decode(html_entity_decode($otherExam), true);
                if(is_array($otherExamValue)) {
                    $displayOther = 'block';
                    $otherExamValue = $otherExamValue[0]['exam_name'];
                } else {
                    $displayOther = 'none';
                    $otherExamValue = '';
                }
            } else {
                $displayOther = 'none';
                $selectOtherExam = '';
                $otherExamValue = '';
            }
            if($examShowOther) {
        ?>

            <option value="-1" class="bld" <?php echo $selectOtherExam; ?>>Other</option>
            <?php
                }
            ?>
        </select>
        
        <div class="lineSpace_5">&nbsp;</div>
        <div style="display:<?php echo $displayOther; ?>">
            <b>Specify Exam Name:</b> <input type="text" id="<?php echo $examSelectComboName; ?>Other" name="<?php echo $examSelectComboName; ?>Other" value="<?php echo $otherExamValue; ?>" size="32"/>
        </div>
    </div>
    <div class="clear_L"></div>
</div>
<div class="row errorPlace">
    <div class="lineSpace_5">&nbsp;</div>
    <div class="r1">&nbsp;</div>
    <div id="examSelected_error" class="errorMsg r2"></div>
    <div class="clear_L"></div>
</div>
<?php 
    $examsToSelect = array();
    $examSelected = json_decode(html_entity_decode($examSelected), true);
    foreach($examSelected as $examSElement){
        $examsToSelect[] = $examSElement['blogId'];
    }
    if($selectOtherExam != '') {
        $examsToSelect[] = -1;
    }
?>
<script>
    function selectExamsForEdit(examCombo, selectedExams){
        selectMultiComboBox(examCombo, selectedExams);
    }
    try{
        selectExamsForEdit(document.getElementById('<?php echo $examSelectComboName; ?>'), eval('<?php echo json_encode($examsToSelect); ?>'));
    } catch(e) {}
</script>
<?php
    }
?>
