<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">UG Courses:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
					<div id="ug_course" style="width:313px;border:1px solid #CCC;padding:5px 0;height:125px;overflow:auto">
                        <label style="display:block;padding-left:5px"><input type="checkbox" onClick="checkUncheckChilds1(this, 'ug_course_holder')" id="all_ug_course" /> All</label>
                        <div id="ug_course_holder">
						<?php
							global $ug_course_array;
							foreach($ug_course_array as $value) { ?>
								<label style="display:block;padding-left:5px"><input type="checkbox" name="ug_course[]" onClick="uncheckElement1(this,'all_ug_course');" value="<?php echo $value; ?>"><?php echo $value; ?></label>
						<?php 
							} ?>
                        </div>
					</div>
                    <!--<select id="ug_course" name="ug_course[]" multiple="">
                        <?php
                        /*global $ug_course_array;

                        foreach($ug_course_array as $value)
                        {
                            echo "<option value=\"".$value."\" title=\"".$value."\">".$value."</option>";
                        }*/
                        ?>
                    </select>-->
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:6px">&nbsp;</div>
<script>
function checkUncheckChilds1(obj, checkBoxesHolder)
{
    var checkBoxes = document.getElementById(checkBoxesHolder).getElementsByTagName("input");
    for(var i=0;i<checkBoxes.length;i++)
    {
        if(checkBoxes[i].checked!=obj.checked)
        {
            checkBoxes[i].checked = obj.checked;
        }
    }
}
function uncheckElement1(obj ,id)
{
   var allChecked = false;
   if(!obj.checked)
   {
       if(document.getElementById(id).checked)
       {
        document.getElementById(id).checked = false;
       }
   }
    var checks =  document.getElementById('ug_course_holder').getElementsByTagName("input");
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
