<div style="padding-left:30px"><a href="#" onClick="hideShow12Details(this);return false;" class="cmsSearch_plusImg">Specify Std XII Performace Criteria</a></div>
<div style="line-height:6px">&nbsp;</div>
<div style="width:100%;display:none;" id="detailsStd12Form">
	<div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Marks Obtained:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <select id="marks_twelve" name="marks_twelve">
                        <option value="">Select</option>
                        <?php 
                        for($i=30;$i<100;$i+=5)
                        {
                            echo '<option value="'.$i.'">&gt;'.$i.'%</option>';
                        }
                        ?>
                    </select>
                </div>                           
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    	<div class="cmsSearch_RowLeft">
        	<div style="width:100%">
            	<div class="txt_align_r" style="padding-right:5px">Stream:&nbsp;</div>
            </div>
        </div>
        <div class="cmsSearch_RowRight">
        	<div style="width:100%">
            	<div>
                    <input type="checkbox" id="science_stream" name="12_stream[]" value="science"/> Science &nbsp; &nbsp; &nbsp; 
                    <input type="checkbox" id="arts_stream" name="12_stream[]" value="arts"/> Arts &nbsp; &nbsp; &nbsp; 
                    <input type="checkbox" id="commerce_stream" name="12_stream[]" value="commerce"/> Commerce
                </div>
            </div>
        </div>
        <div style="clear:left;font-size:1px;line-height:1px;overflow:hidden">&nbsp;</div>
    </div>                
</div>

<script>
function hideShow12Details(obj)
{
    if(document.getElementById('detailsStd12Form').style.display=='block')
    {
        document.getElementById('detailsStd12Form').style.display='none';
        obj.className ='cmsSearch_plusImg';
    }
    else
    {
        document.getElementById('detailsStd12Form').style.display='block';
        obj.className ='cmsSearch_minImg';
    }
    return(false);
}
</script>
