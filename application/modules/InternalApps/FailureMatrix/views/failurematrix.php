<?php $this->load->view('failurematrixheader'); ?>
    
<div style='width:1180px; margin:30px auto;'>
	<?php if(count($failureData) == 0) { ?>
	<div style='text-align: center; margin-top:100px;'>
		<div style='font-size:32px; color:#999;'>
			No results found matching your search criteria.
		</div>
		
		<div style='font-size:20px; color:#999; margin-top:20px;'>
			Please check criteria and try again.
		</div>
	</div>
	<?php } ?>
	
	<?php foreach($failureData as $data) { ?>
	<table cellpadding='0' cellspacing='0' class='failuretable' width='100%'>
		<tr>
			<th colspan='2' class='servicerow'>
			<div style='float:left; margin-top: 4px;'>
				<?php echo $data['host']; ?>: <?php echo $data['service']; ?>
			</div>
			<div style='float:right;'>
				<a href='/FailureMatrix/FailureMatrix/edit/<?php echo $data['id']; ?>'><img src='/public/images/appmonitor/edit.png' /></a>
			</div>
			</th>
		</tr>
		<tr>
			<td width='200' class='leftd' valign='top'>Failure Type</td>
			<td class='rightd'>
				<?php
				if($data['failure_type'] == 'permanent') {
					echo "<div class='infoblockred1'>Permanent</div>";
				}
				else if($data['failure_type'] == 'temporary') {
					echo "<div class='infoblockyellow1'>Temporary</div>";
				}
				?>
			</td>
		</tr>
		<tr>
			<td width='200' valign="top" class='leftd'>Impact/Outage</td>
			<td class='rightd'>
				<?php
				if($data['outage_type'] == 'full') {
					echo "<div class='infoblockred'>Full Outage</div>";
				}
				else if($data['outage_type'] == 'partial') {
					echo "<div class='infoblockyellow'>Partial Outage</div>";
				}
				else if($data['outage_type'] == 'none') {
					echo "<div class='infoblockgreen'>No Outage</div>";
				}
				?>
				<div class='clearFix'></div><br />
				<?php echo $data['impact_desc']; ?>
			</td>
		</tr>
		<tr>
			<td width='200' class='leftd' valign='top'>Failover</td>
			<td class='rightd'>
				<?php
				if($data['failover_type'] == 'none') {
					echo "<div class='infoblockred'>No Failover</div>";
				}
				else if($data['failover_type'] == 'manual') {
					echo "<div class='infoblockyellow'>Manual Failover</div>";
				}
				else if($data['failover_type'] == 'automatic') {
					echo "<div class='infoblockgreen'>Automatic Failover</div>";
				}
				?>
				<div class='clearFix'></div><br />
				<?php echo $data['failover_desc']; ?>
				<br /><br />
				<div class='infoblockblue'>Estimated time: <?php echo $data['estimated_time']; ?></php></div>
			</td>
		</tr>
		<tr>
			<td width='200' class='leftd' valign='top'>Post Recovery</td>
			<td class='rightd'>
				<?php echo $data['post_recovery']; ?>
			</td>
		</tr>
	</table>
	<?php } ?>
	</div>
    </body>
</html>

