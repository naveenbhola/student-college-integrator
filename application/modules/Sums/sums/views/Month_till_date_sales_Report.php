	<div class="row ">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Month: &nbsp;</div>
		<div>
			<select name="year">
			<?php
			for($i= 2008; $i<=(date("Y")+1); $i++)
			echo "<option value='$i'>$i</option>";
			?>
			</select>
			<select name="month">
				<option value="1">Jan</option>
				<option value="2">Feb</option>
				<option value="3">Mar</option>
				<option value="4">Apr</option>
				<option value="5">May</option>
				<option value="6">Jun</option>
				<option value="7">Jul</option>
				<option value="8">Aug</option>
				<option value="9">Sep</option>
				<option value="10">Oct</option>
				<option value="11">Nov</option>
				<option value="12">Dec</option>
			</select>
		</div>
	</div>
	<div class="">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
		<div>
			<select  multiple id="sums_mis_branch" name="sums_mis_branch[]" size="5" style="width:200px">
			<option value="-1" selected >All</option>
			<?php for($i=0;$i<count($branchlist);$i++) { ?>
			<option value="<?php echo $branchlist[$i]['BranchId'];?>" ><?php echo $branchList[$i]['BranchName'];?></option>
			<?php } ?>
			</select>
		</div>
	</div>
	<div class="clear_L">&nbsp;</div>
	<input type="hidden" id="report_type" name ="report_type" value="Month_till_date_sales_Report" />
	<div class="">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">&nbsp;</div>
		<div>
		<input type="button"  value="Submit" onclick="$('TargetSubmitForm').action='/index.php/sums/targetInput/DisplayReports';DisplayReports('<?php echo $type; ?>');">
		</div>
	</div>
	<div class="clear_L">&nbsp;</div>

