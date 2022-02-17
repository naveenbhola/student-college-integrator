<?php $this->load->view('common/StudyAbroadResponseFormFields/common'); ?>

<?php if($showWhenPlanToGo) { ?>
<div class="flLt" style="width:230px; padding:0 15px 12px 0" id="whenPlanToGo_container_<?php echo $widget; ?>">
    <select name="whenPlanToGo" id="whenPlanToGo_<?php echo $widget; ?>" class="universal-select">
            <option value="">When do you plan to start?</option>
            <?php foreach($whenPlanToGoValues as $key => $value) { ?> 
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
            <?php } ?>
        </select>
</div>
<?php } ?>

<?php if($showExams) { ?>
<?php if(!$showWhenPlanToGo) { ?>
<div class="clearFix"></div>
<?php } ?>
<div class="flLt" style="width:250px; padding:0px 0px 12px 0" id="exams_container_<?php echo $widget; ?>">
    <div style="width:200px; font-size: 14px; color:#333;">Exams Taken:</div>
    <div style="margin-top:10px;">
    <?php for($i=1;$i<=count($studyAbroadExams);$i++) { ?>
		<div class="clearFix"></div>
        <div id="exam_block_<?php echo $i; ?>_<?php echo $widget; ?>" style="padding:5px; <?php if($i>1) echo "display:none;"; ?>" >
        <div class="flLt">
		<select class="universal-select" name="exams_<?php echo $widget; ?>[]" id="exams_<?php echo $i; ?>_<?php echo $widget; ?>" onchange="showExamScore(this,'<?php echo $widget; ?>','<?php echo $i; ?>');" style="width:100px !important;"><option value=''>Select</option>
        <?php foreach($studyAbroadExams as $examKey => $examValue) { ?>
            <option value="<?php echo $examValue; ?>"><?php echo $examValue; ?></option>    
        <?php } ?>
        </select>
		</div>
        
        <span id="examScore_<?php echo $i; ?>_<?php echo $widget; ?>" style="display:none; margin: 0px 0px;">
        
            <div class="flLt" style="display:none;margin-left:5px"><input class="universal-txt-field" customplaceholder="Name" profanity="true" type="text" maxlength="20" style="width:45px; display:none" id="exam_name_<?php echo $i; ?>_<?php echo $widget; ?>" value="" blurMethod="checkExamName('<?php echo $widget; ?>','<?php echo $i; ?>');" onfocus="if(this.value == 'Name') { this.value = ''; }" /></div>
        
            <div class="flLt" style="margin-left:5px"><input class="universal-txt-field" customplaceholder="Score" profanity="true" type="text" style="width:40px;" id="exam_score_<?php echo $i; ?>_<?php echo $widget; ?>" value="" blurMethod="checkScore('<?php echo $widget; ?>');"  maxlength="5"  onfocus="if(this.value == 'Score') { this.value = ''; }" /></div>
        </span>
        <div class="clearFix"></div>
        <div style="display:block; margin-left: 105px;">
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
</div>
<?php } ?>