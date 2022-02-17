<?php 
if(!empty($keyword)){
	?>
	<h2 style='font-size:24px;'><?php echo $keyword; ?></h2>
	<?php
}
?>
<div style='height:430px; overflow: auto; margin-top:20px;'>
	<table class="exceptionErrorTable" width='870' style="word-break:break-all;border-collapse: collapse;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;">
		<tr>
			<th width="30">#</th>
			<th width="80">Search Id</th>
			<th width="500">Applied Filters</th>
		</tr>
		<?php 
			if(empty($data)){
				?>
				<tr><td colspan="3">No Filters Applied</td></tr>
				<?php
			}
			else{
				foreach ($data as $key => $row) {
					?>
					<tr>
						<td><?php echo ($key+1); ?></td>
						<td><?php echo $row['searchId']; ?></td>
						<td><?php echo $row['filters']; ?></td>
					</tr>
					<?php
				}
			}
		?>
	</table>
	<?php 
	if(!empty($qerFilters)){
		?>
		<div style="margin-top: 20px;font-weight: bold;">Current state of QER output:</div>
		<div><?php _p($qerFilters); ?></div>
		<?php
	}
	?>
</div>