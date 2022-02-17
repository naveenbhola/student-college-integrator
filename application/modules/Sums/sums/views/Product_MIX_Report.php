	<div class="row">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
	<div>
		<select  id="sums_mis_branch" multiple name="sums_mis_branch[]" onchange="ajax_change_branch(this.value,'sums_mis_branch','sums_mis_executive')"  size="5" style="width:200px">
		<option value="-1" selected >All</option>
		<?php for($i=0;$i<count($branchlist);$i++) { ?>
		<option value="<?php echo $branchlist[$i]['BranchId'];?>" ><?php echo $branchList[$i]['BranchName'];?></option>
		<?php } ?>
		</select>
	</div>
	</div>
	<div class="row ">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Executive: &nbsp;</div>
	<div>
		<select id ="sums_mis_executive" name="sums_mis_executive[]" multiple size="5" style="width:200px"><option value="-1" selected >All</option></select>
	</div>
	</div>
	<div class="">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Date Range: &nbsp;</div>
	<div>
	<input type="text" readonly id="subs_start_date" name="trans_start_date" value="<?php echo date('Y-m-d', mktime(0, 0, 0, 4, 1, (date("Y")-1)));?>" onclick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('subs_start_date'),'sd','yyyy-MM-dd');" />
	<b> AND </b>
	<input type="text" readonly id="subs_end_date" name="trans_end_date" value="<?php echo date("Y-m-d", mktime(0, 0, 0, date("m"),   date("d"),   date("Y")));?>" onclick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');"><img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('subs_end_date'),'ed','yyyy-MM-dd');" />
	</div>
	</div>
	<div class="clear_L">&nbsp;</div>
	<div class="clear_L">&nbsp;</div>
	<div class="">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px"> Output Value:&nbsp;</div>
		<div>
		<input type="radio"  checked name="Output_Value" value="counts" /> Counts &nbsp; &nbsp;<input type="radio" name="Output_Value" value="value_sales" /> value of sales &nbsp; &nbsp;
		</div>
	</div>
	<input type="hidden" id="report_type" name ="report_type" value="Product_MIX_Report" />
	<div class="clear_L">&nbsp;</div>
	<div class="">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">&nbsp;</div>
		<div>
		<input type="button"  value="Submit" onclick="$('TargetSubmitForm').action='/index.php/sums/targetInput/DisplayReports';DisplayReports('<?php echo $type; ?>');">
		</div>
	</div>
	<div class="clear_L">&nbsp;</div>
