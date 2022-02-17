<?php if($userHistory == "No result found."){
	echo '<hr/>';
	echo $userHistory;
}
if(!empty($userHistory) && $userHistory != "No result found.") {?>
<div class="row">
	<hr/>
	<p><b> Name: </b> <?php echo $userHistory[0]['firstname'].' '.$userHistory[0]['lastname']; ?></p>
	<p><b> Level Name: </b> <?php echo $userHistory[0]['levelName']; ?></p>
	<hr/>
	<table class="table table-striped table-hover">
		<tr>
			<td>S.No.</td>
			<td>Points Added</td>
			<td>Action</td>
			<td>Time</td>
			<td>URL/EntityId</td>
		</tr>	
		<?php
		$count = 1;
		foreach ($userHistory as $key => $value) {?>
		<tr>
			<td><?php echo $count;?></td>
			<td><?php echo $value['pointvalue'];?></td>
			<td><?php echo $value['action'];?></td>
			<td><?php echo date('d M, Y h:i A', strtotime($value['timestamp']));?></td>
			<td><?php if(!($value['entityId'] == 0 || $value['action'] == 'tagFollow' || $value['action'] == 'userFollow' || $value['action'] == 'deleteDiscussion' || $value['action'] == 'deleteQuestion')){?><a href = "<?=$url[$key];?>" target = "_blank"><?php } ?><?php echo $url[$key];?><?php if(!($value['entityId'] == 0 || $value['action'] == 'tagFollow' || $value['action'] == 'userFollow' || $value['action'] == 'deleteDiscussion' || $value['action'] == 'deleteQuestion')){?></a><?php } ?></td>
		</tr>
		<?php $count++;
		}
		?>
	</table>
</div>
<?php } ?>