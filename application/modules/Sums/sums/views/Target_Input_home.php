	<div class="row">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Quarters: &nbsp;</div>
	<div>
		<select  id="Quarters" name="Quarters" onchange="getExecutiveDataForm()" style="width:200px">
		<?php $i = 1; foreach ($AllQuarters['FQ_short'] as $key=>$value) { ?>
		<option value="<?php echo $value;?>" ><?php echo $AllQuarters['FQ_detail'][$i];?></option>
		<?php $i++; } ?>
		</select>
	</div>
	</div>
	<div class="row ">
	<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">Years: &nbsp;</div>
	<div>
		<select  id="year" name="year" onchange="getExecutiveDataForm()" style="width:200px">
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
		<select  id="sums_mis_branch" name="sums_mis_branch[]" onchange="getExecutiveDataForm()" size="5" style="width:200px">
		<?php for($i=0;$i<count($branchlist);$i++) { ?>
		<option value="<?php echo $branchlist[$i]['BranchId'];?>" ><?php echo $branchList[$i]['BranchName'];?></option>
		<?php } ?>
		</select>
	</div>
	</div>
	<div class="clear_L">&nbsp;</div>
<hr>
<div class="clear_L">&nbsp;</div>
<div class="clear_L"></div>
<div class="txt_align_r float_L bld fontSize_12p" style="width:150px">&nbsp;</div>
	<div class="float_L" style="width:100%;height:50%">
		<div id ="sums_mis_executive_details"></div>
<div class="clear_L"></div>