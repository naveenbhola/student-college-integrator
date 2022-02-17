<div style="width:100%">
<div class="select-courses-head"><strong style="text-align: left; margin-left:18px;">Education Interest</strong></div>
    <div>
        <div class="cmsSearch_RowLeft">
            <div style="width:100%">
                <div class="txt_align_r" style="padding-right:5px">Matched Response Course:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
            <div style="width:100%">
            <?php
	    $message = ($boolen_flag_to_apply_hack_on_mba_courses == 'false') ? $actual_course_name : $course_name;
	    ?>
	    <div style="line-height:18px"><b><?php echo $message; ?></b></div>
            </div>
        </div>
        <input type="hidden" value="<?php echo $message; ?>" id="desiredCourse" name="desiredCourse" autocomplete="off"/>
		<input type="hidden" value="<?php echo $actual_course_name; ?>" name="actual_course_name">
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
