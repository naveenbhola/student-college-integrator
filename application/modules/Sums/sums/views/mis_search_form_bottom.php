<div>
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Report: &nbsp;</div>
	<div class="float_L"><input type="radio"  checked name="report" value="sums_mis_report_show" /> Show report &nbsp; &nbsp;<!--<input type="radio" name="report" value="sums_mis_report_mail" /> Mail &nbsp; &nbsp; --><input type="radio" name="report" value="sums_mis_report_download" /> Download</div>
	<div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>

<div>
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Results Per Page:: &nbsp;</div>
	<div class="float_L"><select name="countOffsetForMis" id="countOffsetForMis" style="width:200px" >
		<option value="10" selected >10</option>
		<option value="20"  >20</option>
		<option value="30"  >30</option>
		</select>
	</div>
	<div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Date: &nbsp;</div>
	<input type="text" readonly id="subs_start_date" name="trans_start_date" value="<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));?>" onclick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
	<b> AND </b>
	<input type="text" readonly id="subs_end_date" name="trans_end_date" value="<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));?>" onclick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');" />
	<div class="clear_L"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">&nbsp;</div>
	<div class="float_L">
		<button onclick="" id="submitbutton" type="Submit" value="" class="btn-submit7" style="width:100px">
			<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
	</button>
	</div>
</div>
</form>
