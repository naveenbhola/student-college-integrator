<?php
//_p($tableData);die;
if(empty($tableData)){ ?>
	<div class="spacer20 clearFix"></div>
	<h1>No data available.</h1>
<?php } else { ?>
<div class="div-table">
    <table class="table" width="100%">
		<thead>
			<tr>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Testimonial</th>
                    <th>Modification Date</th>
                    <th>Image URL</th>
                    <th>Action</th>
            </tr>
		</thead>
		<tbody>
			<?php
			$count = 0;
			foreach($tableData as $d) {
                $d['image_url'] = MEDIA_SERVER.$d['image_url'];
				if($count %2 == 0){
					$class = "";
				}
				else{
					$class = "grey";
				}
				$count++;?>
				<tr class="<?php echo $class; ?>">
                 <td><?php echo $d['name'];?></td>
                 <td><?php echo $d['designation'];?></td>
                 <td><?php echo $d['testimonial'];?></td>
                 <td><?php echo $d['modificationDate'];?></td>
                 <td><a href="<?php echo $d['image_url'];?>" target="_blank"><?php echo $d['image_url'];?></a></td>
                 <td>
                        <a href="/home/HomePageCMS/index/<?php echo $pageType;?>/<?php echo $d['testimonialId'];?>" style="text-decoration:none;"><input type="button" class="table-edit-btn bts"  value="Edit"></a>
                        <input type="button" class="table-remove-btn bts"  value="Remove" onclick="deleteEntryInTable('testimonials','<?php echo $d['testimonialId'];?>','<?php echo $d['status'];?>')">
                        <div id="chdiv" class="bts">
                        	<input autocomplete="off" id="chk<?php echo $d['testimonialId'];?>" type="checkbox" name="live" style="vertical-align:middle;margin-right:10px"/ onchange="makeTestimonialLive('<?php echo $d['testimonialId'];?>','<?php echo $d['status'];?>')" <?php if($d['status'] == 'live'){?> checked="checked"<?php } ?>>Make Live
                        </div>
                 </td>
              </tr>
              <?php } ?>
		</tbody>
	</table>
	<!--cms-tabel-ends-->
</div>
<?php
}
?>