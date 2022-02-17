<?php
$pendingEntities = (!isset($moderatedEntityInfo['formattedData'][0]) || $moderatedEntityInfo['formattedData'][0]=='')?0:$moderatedEntityInfo['formattedData'][0];
$colorClass      = array('#5cb85c', '#f0ad4e', '#5bc0de', '#d9534f', '#563d7c', '#f2dede', '#286090', '#101010');
?>
<h4>Completed/Pending Moderation Graph</h4>
<p>Total pending entities: <strong><?=$pendingEntities?></strong></p>
<?php
if(($moderatedEntityInfo['formattedData']) > 1)
{
	$iteration = 0;
	?>
	<div class="progress" style="text-align:center;" title="Pending(<?=$pendingEntities?>)">
	<?php
	foreach ($moderatedEntityInfo['formattedData'] as $moderatorId => $moderatedEntityCount) {
		if($moderatorId != 0)
		{

			?>
			<div title="<?=$moderatorEmailMap[$moderatorId]?> - Completed(<?=$moderatedEntityCount?>)" class="progress-bar" style="background-color:<?=$colorClass[$iteration]?>;width: <?=(($moderatedEntityCount*100)/$moderatedEntityInfo['totalSum'])?>%">
				<span class="sr-only"><?=$moderatedEntityCount?></span>
			</div>
			<?php
			$iteration++;
		}
	}
	?>
	</div>
<?php
}
?><hr/>