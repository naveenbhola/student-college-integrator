<?php $this->load->view('failurematrixheader'); ?>
    
<div style='width:1180px; margin:30px auto;'>
	<?php
	$valid = TRUE;
	if($id) {
		if($failureData['id']) {
			echo "<h1>Edit #".$id."</h1>";
		}
		else {
			echo "<h1 style='color:#f44336;'>Item #".$id." Not Found</h1>";
			$valid = FALSE;
		}
	}
	else {
		echo "<h1>Add</h1>";
	}
	if($valid) {
	?>
	<br />
	<form method='POST' id='failForm' action='/FailureMatrix/FailureMatrix/save'>
	<input type='hidden' name='id' value='<?php echo $failureData['id']; ?>' />
	<table cellpadding='0' cellspacing='0' class='failuretable' width='100%'>
		<tr>
			<td width='200' valign="top" class='leftd'>Basic Details</td>
			<td class='rightd'>
				<div class='resultrow'>
					<div class='resultrowleft'>Service:</div>
					<div class='resultrowright'><select name='service'>
						<option value=''>Select</option>
						<?php foreach($services as $host => $hostServices) { ?>
						<optgroup label="<?php echo $host; ?>">
						<?php foreach($hostServices as $service) { ?>
							<option value='<?php echo $host."::".$service; ?>' <?php echo $failureData['host']."::".$failureData['service'] == $host."::".$service ? "selected='selected'": ""; ?>><?php echo $service; ?></option>
						<?php } ?>
						</optgroup>
						<?php } ?>
					</select></div>
				<div class='clearFix'></div>
				</div>
							
				<div class='resultrowlast'>
					<div class='resultrowleft'>Failure Type:</div>
					<div class='resultrowright'><select name='failureType'>
						<option value=''>Select</option>
						<?php foreach($failureTypes as $failureTypeId => $failureType) { ?>
							<option value='<?php echo $failureTypeId; ?>'  <?php echo $failureData['failure_type'] == $failureTypeId ? "selected='selected'": ""; ?>><?php echo $failureType; ?></option>
						<?php } ?>
					</select></div>
				<div class='clearFix'></div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width='200' valign="top" class='leftd'>Impact/Outage</td>
			<td class='rightd'>
				<div class='resultrow'>
					<div class='resultrowleft'>Type:</div>
					<div class='resultrowright'><select name='outageType'>
						<option value=''>Select</option>
						<?php foreach($outageTypes as $outageTypeId => $outageType) { ?>
							<option value='<?php echo $outageTypeId; ?>'  <?php echo $failureData['outage_type'] == $outageTypeId ? "selected='selected'": ""; ?>><?php echo $outageType; ?></option>
						<?php } ?>
					</select></div>
				<div class='clearFix'></div>
				</div>
				
				<div class='resultrowlast'>
					<div class='resultrowleft'>Description:</div>
					<div class='resultrowright'><textarea name='impact_desc'><?php echo $failureData['impact_desc']; ?></textarea></div>
				<div class='clearFix'></div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width='200' valign="top" class='leftd'>Failover</td>
			<td class='rightd'>
				<div class='resultrow'>
					<div class='resultrowleft'>Type:</div>
					<div class='resultrowright'><select name='failoverType'>
						<option value=''>Select</option>
						<?php foreach($failoverTypes as $failoverTypeId => $failoverType) { ?>
							<option value='<?php echo $failoverTypeId; ?>'  <?php echo $failureData['failover_type'] == $failoverTypeId ? "selected='selected'": ""; ?>><?php echo $failoverType; ?></option>
						<?php } ?>
					</select></div>
				<div class='clearFix'></div>
				</div>
				
				<div class='resultrow'>
					<div class='resultrowleft'>Description:</div>
					<div class='resultrowright'><textarea name='failover_desc'><?php echo $failureData['failover_desc']; ?></textarea></div>
				<div class='clearFix'></div>
				</div>
				
				<div class='resultrowlast'>
					<div class='resultrowleft'>Estimated failover/fix time:</div>
					<div class='resultrowright'><input type='text' name='estimated_time' value='<?php echo $failureData['estimated_time']; ?>' /></div>
				<div class='clearFix'></div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td width='200' valign="top" class='leftd'>Post Recovery</td>
			<td class='rightd'>
				<div class='resultrowlast'>
					<div class='resultrowleft'>Description:</div>
					<div class='resultrowright'><textarea name='post_recovery'><?php echo $failureData['post_recovery']; ?></textarea></div>
				<div class='clearFix'></div>
				</div>
			</td>
		</tr>
	</table>
	<div style='margin:20px 0 50px 440px'>
		<a href='#' onclick="$('#failForm').submit();" class='zbutton zlarge zgreen'>Submit</a>
	</div>
	</form>
<?php } ?>	
</div>
</body>
</html>