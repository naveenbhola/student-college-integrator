<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
		<?php if($sa_course == 'All'){ ?>
		<div class="txt_align_r" style="padding-right:5px">Select Course:&nbsp;</div>
		<?php } else { ?>
            	<div class="txt_align_r" style="padding-right:5px">Desired Course Level:&nbsp;</div>
		<?php } ?>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <input type="checkbox" value="UG" id="desiredCourseLevelUG" name="desiredCourseLevel[]"> Bachelors</input>&nbsp;&nbsp;
                    <input type="checkbox" value="PG" id="desiredCourseLevelPG" name="desiredCourseLevel[]"> Masters</input>&nbsp;&nbsp;
		    <input type="checkbox" value="PhD" id="desiredCourseLevelPG" name="desiredCourseLevel[]"> PhD</input>&nbsp;&nbsp;
		</div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>

