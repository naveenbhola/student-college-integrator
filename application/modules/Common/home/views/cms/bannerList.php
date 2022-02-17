<?php
if(empty($tableData)){ ?>
	<div class="spacer20 clearFix"></div>
	<h1>No data available.</h1>
<?php
} else {
?>

<div class="div-table">
	<h1>Note: White background rows denote default slots.</h1>
	<table class="table" width="100%">
		<thead>
			<tr>
					<th>Client Id</th>
					<th>Title</th>
					<th>Location</th>
					<?php
					if($pageType == 'banner'){
						?>
						<th>Image URL</th>
						<?php
					} else {
						?>
						<th>USP</th>
						<?php
					} ?>
					<th>URL</th>
					<th>Start date</th>
					<th>End date</th>
					<th>Clicks</th>
					<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach($tableData as $d) {
				$className = "grey";
				if($d['is_default'] == 1){
					$className = "";
				}
				$count++;
				$targetURL = trim($d['target_url']);
				$httpPresent = false;
				$pos = strpos($targetURL, 'http');
				if($pos === FALSE || $pos !== 0){
					$targetURL = "http://".$targetURL;
				}
				?>
				<tr class="<?php echo $className?>">
					<td><?php echo $d['client_id'];?></td>
					<td><?php echo $d['title'];?></td>
					<td><?php echo $d['location_text'];?></td>
					<?php
					if($pageType == 'banner'){
						?>
						<td><a target="_blank" href="<?php echo $d['image_url'];?>"><?php echo $d['image_url'];?></a></td>
						<?php
					} else {
						?>
						<td><?php echo $d['usp'];?></td>
						<?php
					} ?>
					<td><a target="_blank" href="<?php echo $targetURL;?>"><?php echo $targetURL;?></a></td>
					<td><?php echo $d['start_date'];?></td>
					<td><?php echo $d['end_date'];?></td>
					<?php if(!empty($d['clicks'])){
						?>
						<td><?php echo $d['clicks'];?></td>
						<?php
					} else { ?>
						<td>0</td>
					<?php }?>
					
					<td>
						<a href="/home/HomePageCMS/index/<?php echo $pageType;?>/<?php echo $d['banner_id'];?>" style="text-decoration:none;"><input type="button" class="table-edit-btn" tabindex="5" value="Edit" ></a>
						<input type="button" class="table-remove-btn" tabindex="5" value="Remove" onclick="deleteHPBanner('<?php echo $d['banner_id'];?>');">
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<!--cms-tabel-ends-->
</div>
<?php
}
?>