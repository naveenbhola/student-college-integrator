<div style="width:100%">
	<!--div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Student Budget:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
					< ?php foreach($budgetValues as $budgetValue => $budgetDisplay) { ?>
					
                    <input type="checkbox" value="< ?php echo $budgetValue; ?>" name="budget[]"> < ?php echo $budgetDisplay; ?></input>&nbsp;&nbsp;
                    
					< ?php } ?>
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>-->
	<div style="line-height:6px">&nbsp;</div>
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Student Passport:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
		    <select name='passport'>
			<option value=''>Select</option>
			<option value='yes'>Yes</option>
			<option value='no'>No</option>
		    </select>
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>
    <div style="line-height:6px">&nbsp;</div>
    <div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Plan to Start:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
		    <input type="checkbox" value="<?php echo date('Y'); ?>" id="PlanToStartCY" name="planToStart[]"> <?php echo date('Y'); ?></input>&nbsp;&nbsp;
                    <input type="checkbox" value="<?php echo date('Y',strtotime('+1 year')); ?>" id="PlanToStartNY" name="planToStart[]"> <?php echo date('Y',strtotime('+1 year')); ?></input>&nbsp;&nbsp;
		    <input type="checkbox" value="Later" id="PlanToStartLater" name="planToStart[]"> Later</input>&nbsp;&nbsp;
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div> 
	
</div>
<div style="line-height:6px">&nbsp;</div>

