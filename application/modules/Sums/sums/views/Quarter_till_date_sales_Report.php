	<div class="row">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Quarters: &nbsp;</div>
	<div>
		<select  id="Quarters" name="Quarters" style="width:200px">
		<?php $i = 1; foreach ($AllQuarters['FQ_short'] as $key=>$value) { ?>
		<option value="<?php echo $value;?>" ><?php echo $AllQuarters['FQ_detail'][$i];?></option>
		<?php $i++; } ?>
		</select>
	</div>
	</div>
	<div class="row ">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Years: &nbsp;</div>
	<div>
		<select  id="year" name="year" style="width:200px">
		<?php
			for($i= 2008; $i<=(date("Y")+1); $i++)
			echo "<option value='$i'>".$i."-".substr(($i+1), -2) ." </option>";
		?>
		</select>
	</div>
	</div>
	<div class="">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Branch: &nbsp;</div>
	<div>
		<select multiple id="sums_mis_branch" name="sums_mis_branch[]"  size="5" style="width:200px">
		<option value="-1" selected >All</option>
		<?php for($i=0;$i<count($branchlist);$i++) { ?>
		<option value="<?php echo $branchlist[$i]['BranchId'];?>" ><?php echo $branchList[$i]['BranchName'];?></option>
		<?php } ?>
		</select>
	</div>
	</div>
	<div class="clear_L">&nbsp;</div>
	<div class="clear_L">&nbsp;</div>
	<input type="hidden" id="report_type" name ="report_type" value="Quarter_till_date_sales_Report" />
	<div class="">
		<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">&nbsp;</div>
		<div>
		<input type="button"  value="Submit" onclick="$('TargetSubmitForm').action='/index.php/sums/targetInput/DisplayReports';DisplayReports('<?php echo $type; ?>');">
		</div>
	</div>
	<div class="clear_L">&nbsp;</div>