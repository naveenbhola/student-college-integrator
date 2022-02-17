<h4 style="font-weight:bold;">Locked Entities</h4>
<table class="table table-hover table-striped table-responsive">
	<tr>
		<th>EntityId</th>
		<th>Entity Text</th>
		<th>Entity Date</th>
		<th>Entity Status</th>
		<th>Action</th>
	</tr>
		<?php 
		foreach ($entityLockData as $value) {
		?>
		<tr>
			<td><?php echo $value['entityId']?></td>
			<td width="50%"><?php echo $value['msgTxt']?></td>
			<td><?php echo date('j M, Y g:i A', strtotime($value['msgCreationDate']))?></td>
			<td><?php echo ucfirst($value['msgSts'])?></td>
			<td><a href="javascript:;" moderatorId="<?php echo $value['moderatorId']?>" msgId="<?php echo $value['entityId']?>" lockId="<?php echo $value['id']?>" class="releaseLock">Release lock</a></td>
		</tr>
		<?php 
		}
		?>
</table>