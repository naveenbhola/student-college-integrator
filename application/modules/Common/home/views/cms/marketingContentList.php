<?php
if(empty($tableData)){ ?>
	<div class="spacer20 clearFix"></div>
	<h1>No data available.</h1>
<?php
} else {
?>

 <div class="div-table">
	<table class="table" width="100%">
		<thead>
			<tr>
					<th>Content Type</th>
					<th>Header</th>
					<th>Sub-Header</th>
					<th>Target URL</th>
					<th>Clicks</th>
					<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach($tableData as $d) {
				$count++;
				$httpPresent = false;
				?>
			<?php if($d['type'] == 'imageWithText'){
					$index = 1;}
				  else if($d['type'] == 'imageOnly'){
					$index = 2;
					}
				else {
					$index = 3;
					}
				
			?>
				<tr class="grey">
					<td><?php echo $d['type']; ?></td>
					<td><?php echo $d['header'];?></td>
					<td><?php echo $d['subHeader'];?></td>
					<td><a href="<?php echo $d['targetURL'];?>" target="_blank"><?php echo $d['targetURL']; ?></a></td>
					<td><?php echo $d['clicks'];?></td>
					<td>
						<a onclick = "showEditFormForMrktng('<?php echo $index; ?>', '<?php echo $d['id']; ?>', '<?php echo $d['status'];?>', '<?php echo $d['creationDate']; ?>', '<?php echo $d['imgURL']; ?>', '<?php echo $d['marketingBannerId']; ?>')"  style="text-decoration:none;"><input id="editButtonMarket" type="button" class="table-edit-btn" tabindex="5" value="Edit" ></a>
						<input type="button" class="table-remove-btn" tabindex="5" value="Remove" onclick="deleteMarketingFold('<?php echo $d['id'];?>','marketing');">
						<div id="chdiv" class="bts">
                        	<input autocomplete="off" id="chk<?php echo $d['id'];?>" type="checkbox" name="live" style="vertical-align:middle;margin-right:10px"/ onchange="makeTestimonialLive('<?php echo $d['id'];?>','<?php echo $d['status'];?>', 'marketing')" <?php if($d['status'] == 'live'){?> checked="checked"<?php } ?>>Make Live
                        </div>
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
