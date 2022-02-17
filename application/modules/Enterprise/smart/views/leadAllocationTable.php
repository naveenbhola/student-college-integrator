<table class="table-style" cellpadding="0" cellspacing="0">
	<tr>
		<th>Product</th>
		<th>Date & Time Created</th>
		<th>Status</th>
		<th>Leads Delivered</th>
	</tr>
<?php
	$i = 0;
	foreach($studentSearch as $data){
		foreach($data as $value){
			$i++;
?>
	<tr>
		<td>Student Search</td>
		<td>NA</td>
		<td>NA</td>
		<td><?php echo $value; ?></td>
	</tr>
<?php
		}
	}
	if($i == 0) {
		$i++;
?>
	<tr>
		<td>Student Search</td>
		<td>NA</td>
		<td>NA</td>
		<td>0</td>
	</tr>
<?php
	}
	foreach($leadsAllocated as $searchAgentId=>$data){
		foreach($data as $period=>$value){
			$i++;
?>
	<tr <?php echo ($i%2 == 0 ? 'class="alt-bg"' : ''); ?> >
		<td><?php echo $searchAgents[$searchAgentId]['name']; ?></td>
		<td><?php echo $searchAgents[$searchAgentId]['create_date']; ?></td>
		<td><?php echo $searchAgents[$searchAgentId]['status']; ?></td>
		<td><?php echo $value['leads']; ?></td>
	</tr>
<?php
		}
	}
?>
<?php
	foreach($portingsData as $portingId=>$data){
		foreach($data as $period=>$value){
			$i++;
?>
	<tr <?php echo ($i%2 == 0 ? 'class="alt-bg"' : ''); ?> >
		<td><?php echo $leadPortings[$portingId]['name']; ?></td>
		<td><?php echo $leadPortings[$portingId]['create_date']; ?></td>
		<td><?php echo $leadPortings[$portingId]['status']; ?></td>
		<td><?php echo $value; ?></td>
	</tr>
<?php
		}
	}
?>
</table>
