<script>
function checkUncheckChilds(obj, checkBoxesHolder)
{
    //alert(1);
    var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
    for(var i=0;i<checkBoxes.length;i++)
    {
        if(checkBoxes[i].checked!=obj.checked)
        {
            checkBoxes[i].checked = obj.checked;
        }
    }
}
function uncheckElement(obj ,id)
{
   var allChecked = false;
   if(!obj.checked)
   {
       if(document.getElementById(id).checked)
       {
        document.getElementById(id).checked = false;
       }
   }
    var checks =  document.getElementById('course_specialization_holder').getElementsByTagName("input");
    var boxLength = checks.length;
    var chkAll = document.getElementById(id);
    for ( i=0; i < boxLength; i++ )
    {
            if ( checks[i].checked == true )
            {
                allChecked = true;
                continue;
            }
            else
            {
                allChecked = false;
                break;
            }
    }
    if ( allChecked == true )
    chkAll.checked = true;
    else
    chkAll.checked = false;
}
</script>
<div style="width:100%">
	<div>
    	
		<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
                    <div class="txt_align_r" style="padding-right:5px">Field of Interest:</div>
            </div>
        </div>
		<div class="cmsSearch_RowRight">
        	<div style="width:100%; line-height: 18px;">
				<b><?php echo $sa_course; ?></b>
			</div>
		</div>
		
		<?php if(isset($studyAbroadSpecializations)){ ?>
		<div style="clear:left;font-size:1px;line-height:10px;overflow:hidden">&nbsp;</div>
		<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
                    <div class="txt_align_r" style="padding-right:5px">Specializations:</div>
            </div>
        </div>
		
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">

			<div style="line-height:18px">
                <div caption="the field of interest" id="fieldOfInterest[]" name="board_id[]" required='true' validate="validateSelect" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
				<div style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds(this, 'course_specialization_holder');checkallfunction();" id="all_categories" name="sa_specialization_id[]" value="-1" /> All</div>
				<div id="course_specialization_holder">
	                <?php
                        $selected_str = '';
                        foreach ($studyAbroadSpecializations as $categoryId => $categoryName) {
                        ?>
                        <div style="display:block;padding-left:5px"><input type="checkbox" name="sa_specialization_id[]" value="<?php echo $categoryId; ?>" onClick="uncheckElement(this,'all_categories');"> <?php echo $categoryName; ?></div>
                        <?php
                        }
			?>
                </div>
			</div>
            </div>
            </div>
        </div>
                <?php } ?>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>
</div>
<div style="line-height:6px">&nbsp;</div>

