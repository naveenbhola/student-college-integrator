<?php $this->load->view('common/StudyAbroadResponseFormFields/common'); ?>
<?php if($showWhenPlanToGo || $showExams) { ?>
<li>

<?php if($showWhenPlanToGo) { ?>
<div class="personal-details-col">
    <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $widget; ?>" class="universal-select">
            <option value="">When do you plan to start?</option>
            <?php foreach($whenPlanToGoValues as $key => $value) { ?> 
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        </select>
</div>
<?php } ?>

<?php if($showExams) { ?>
<div class="personal-details-col" style="width:500px;">
    <div style="padding-bottom:0px">
    <div class="float_L" style="width:95px; line-height: 10px; margin-top: 10px; font-size: 14px; color:#333;"><div class="txt_align_r">Exams Taken: &nbsp;</div></div>
    <div style="margin-left:110px;">
        
     <?php for($i=1;$i<=count($studyAbroadExams);$i++) { ?>
        <div id="exam_block_<?php echo $i; ?>_<?php echo $widget; ?>" style="padding-bottom:5px; <?php if($i>1) echo "display:none;"; ?>" >
        <select class="universal-select" name="exams_<?php echo $widget; ?>[]" id="exams_<?php echo $i; ?>_<?php echo $widget; ?>" onchange="showExamScore(this,'<?php echo $widget; ?>','<?php echo $i; ?>');" style="width:100px;"><option value=''>Select</option>
        <?php foreach($studyAbroadExams as $examKey => $examValue) { ?>
            <option value="<?php echo $examValue; ?>"><?php echo $examValue; ?></option>    
        <?php } ?>
        </select>
        
        <span id="examScore_<?php echo $i; ?>_<?php echo $widget; ?>" style="display:none; margin-left: 10px;">
        
            <input class="universal-txt-field" profanity="true" type="text" maxlength="20" style="width:100px; display:none" id="exam_name_<?php echo $i; ?>_<?php echo $widget; ?>" value="Name" blurMethod="checkExamName('<?php echo $widget; ?>','<?php echo $i; ?>'); blurExamName('exam_name_<?php echo $i; ?>_<?php echo $widget; ?>')" onfocus="if(this.value == 'Name') { this.value = ''; }" />
        
            <input class="universal-txt-field" profanity="true" type="text" style="width:60px;" id="exam_score_<?php echo $i; ?>_<?php echo $widget; ?>" value="Score" blurMethod="checkScore('<?php echo $widget; ?>'); blurScore('exam_score_<?php echo $i; ?>_<?php echo $widget; ?>')" onfocus="if(this.value == 'Score') { this.value = ''; }"  maxlength="5" />
        </span>
        
        <div style="display:block; margin-left: 115px;">
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
    <div class="clear_L" style="font-size:1px; line-height:1px">&nbsp;</div>
</div>
</div>
<?php } ?>
</li>
<?php } ?>