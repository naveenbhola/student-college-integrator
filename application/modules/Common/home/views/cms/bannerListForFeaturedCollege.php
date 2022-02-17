<?php
//_p($tableData);die;
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
					<th>College Name</th>
					<th>Image URL</th>
					<th>Target URL</th>
					<th>Start date</th>
					<th>End date</th>
					<th>Clicks</th>
					<th>Show On</th>
					<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach($tableData as $d) {
				$className = "grey";
				if($d['isDefault'] == 1){
					$className = "";
				}
				if($d['showOn'] == 'desktop'){
					$index = 1;	}
				else {
					$index = 2;
					}
				
				$count++;
				$targetURL = trim($d['target_url']);
				$httpPresent = false;
				$pos = strpos($targetURL, 'https');
				if(strpos($targetURL, 'http:') === false && ($pos === FALSE || $pos !== 0)){
					$targetURL = "https://".$targetURL;
				}
				?>
				<tr class="<?php echo $className?>">
					<td><?php echo $d['client_id'];?></td>
					<td><?php echo $d['collegeName'];?></td>
					<td><a target="_blank" href="<?php echo MEDIA_SERVER.$d['image_url'];?>"><?php echo MEDIA_SERVER.$d['image_url'];?></a></td>
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
					<?php echo $d['showOn']; ?>
					</td>
					<td>
						<a onclick = "showEditFormForFeaturedClg('<?php echo $index; ?>', '<?php echo $d['banner_id']; ?>', '<?php echo $d['status'];?>', '<?php echo $d['creationDate']; ?>', '<?php echo $d['image_url']; ?>')"  style="text-decoration:none;"><input type="button" class="table-edit-btn" tabindex="5" value="Edit" ></a>
						<input type="button" class="table-remove-btn" tabindex="5" value="Remove" onclick="deleteHPBanner('<?php echo $d['banner_id'];?>','featuredCollege');">
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