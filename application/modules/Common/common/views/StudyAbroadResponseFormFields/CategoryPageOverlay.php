<?php $this->load->view('common/StudyAbroadResponseFormFields/common'); ?>

<?php if($showWhenPlanToGo) { ?>
    <div style="padding-bottom:10px">
        <div class="float_L" style="width:175px;margin-top:3px;"><div class="txt_align_r">When do you plan to start?: &nbsp;</div></div>
        <div style="margin-left:180px">
            <div>
                <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $widget; ?>">
                    <option value="">Select</option>
                    <?php foreach($whenPlanToGoValues as $key => $value) { ?> 
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="display:none"><div class="errorMsg" id="whenPlanToGo_<?=$widget?>_error" style="padding-left:3px;" ></div></div>
        </div>
        <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
    </div>
<?php } ?>

<?php if($showExams) { ?>
<div style="padding-bottom:10px">
    <div class="float_L" style="width:175px; line-height: 10px; margin-top: 5px;"><div class="txt_align_r">Exams Taken: &nbsp;</div></div>
    <div style="margin-left:180px;">
    <?php for($i=1;$i<=count($studyAbroadExams);$i++) { ?>
        <div id="exam_block_<?php echo $i; ?>_<?php echo $widget; ?>" style="padding-bottom:5px; <?php if($i>1) echo "display:none;"; ?>" >
        <select name="exams_<?php echo $widget; ?>[]" id="exams_<?php echo $i; ?>_<?php echo $widget; ?>" onchange="showExamScore(this,'<?php echo $widget; ?>','<?php echo $i; ?>');"><option value=''>Select</option>
        <?php foreach($studyAbroadExams as $examKey => $examValue) { ?>
            <option value="<?php echo $examValue; ?>"><?php echo $examValue; ?></option>    
        <?php } ?>
        </select>
        
        <span id="examScore_<?php echo $i; ?>_<?php echo $widget; ?>" style="display:none; margin-left: 10px;">
        
            <input profanity="true" type="text" maxlength="20" style="width:100px; display:none" id="exam_name_<?php echo $i; ?>_<?php echo $widget; ?>" value="Name" blurMethod="checkExamName('<?php echo $widget; ?>','<?php echo $i; ?>'); blurExamName('exam_name_<?php echo $i; ?>_<?php echo $widget; ?>')" onfocus="if(this.value == 'Name') { this.value = ''; }" />
        
            <input profanity="true" type="text" style="width:40px;" id="exam_score_<?php echo $i; ?>_<?php echo $widget; ?>" value="Score" blurMethod="checkScore('<?php echo $widget; ?>'); blurScore('exam_score_<?php echo $i; ?>_<?php echo $widget; ?>')" onfocus="if(this.value == 'Score') { this.value = ''; }" maxlength="5" />
        </span>
        
        <div style="display:block; margin-left: 80px;">
             <div class="errorPlace">
                <div class="errorMsg" id="exam_name_<?php echo $i; ?>_<?php echo $widget; ?>_error" style="display:none; margin-bottom:5px;"></div>
            </div>
            <div class="errorPlace">
                <div class="errorMsg" id="exam_score_<?php echo $i; ?>_<?php echo $widget; ?>_error" style="display:none; margin-bottom:5px;"></div>
            </div>
        </div>
        
        </div>
    <?php } ?>
    
    <div style="margin:5px; 0" id="add_exam_block_<?php echo $widget; ?>"><a href='#' onclick="addMoreExam('<?php echo $widget; ?>'); return false;">Add More</a></div>
    </div>
    <div class="clear_L" style="font-size:1px;line-height:1px">&nbsp;</div>
</div>
<?php } ?>
