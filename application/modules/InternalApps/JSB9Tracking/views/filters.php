<div class='filters'>
	<div style='width:1200px; margin:0 auto;'>
		<div style='float:left; margin-top:0px;'>
			<div style='float:left; margin-left:15px; padding-top:3px;'>From Date: </div>
			<div style='float:left; margin-left:10px; padding-top:1px;'>
				<input type="text" id="fromDatePicker" readonly="readonly" value="<?php echo $trendStartDate; ?>" style='width:100px; cursor: text' />
			</div>

			<div style='float:left; margin-left:30px; padding-top:3px;'>To Date : </div>
			<div style='float:left; margin-left:10px; padding-top:1px;'>
				<input type="text" id="toDatePicker" readonly="readonly" value="<?php echo $trendEndDate; ?>"  style='width:100px; cursor: text' />
			</div>

			<div style='float:left; margin-left:30px; padding-top:3px;'>Source Application : </div>
			<div style='float:left; margin-left:10px; padding-top:1px;'>
				<select id="sourceApplication" class="dropdownInputClass">
					<option value="">Select</option>
					<?php foreach ($sourceApplicationFilter as $key => $value) { ?>
						<option value="<?php echo $key;?>" <?php  echo ($sourceApplication == $key ?'selected':'') ?> ><?php echo $value;?></option>			
					<?php	} ?>
				</select>
			</div>
		</div>
		<div style='float:left; margin-left:40px;'>
			<a href='javascript:void(0)' onclick="updateReport();" class='zbutton zsmall zgreen'>Go</a>
		</div>
		<div style='clear:both'></div>
	</div>
</div>