<div style="line-height:6px">&nbsp;</div>
<div style="width:100%">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Marks Obtained:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <select id="ug_marks" name="ug_marks" onChange="EnableDisableCheckBox(this)">
                        <option value="">Select</option>
                        <?php 
                        for($i=30;$i<100;$i+=5)
                        {
                            echo '<option value="'.$i.'">&gt;'.$i.'%</option>';
                        }
                        ?>
                    </select>
                    &nbsp;
                    <input type="checkbox" id="gradResultAwaited" name="gradResultAwaited" value="result_awaited" disabled="true" /> includes students with result awaited
                </div>                           
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>
<div style="line-height:25px">&nbsp;</div>
<script>
function EnableDisableCheckBox(obj)
{
    if(obj.value!="")
    {
        document.getElementById('gradResultAwaited').disabled = false;
        document.getElementById('gradResultAwaited').checked=true;
    }
    else
    {
        document.getElementById('gradResultAwaited').disabled = true;
        document.getElementById('gradResultAwaited').checked=false;
    }
}
</script>