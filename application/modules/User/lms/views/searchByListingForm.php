<div class="txt_align_l">
<form action=<?php echo $url?> method="get" name="searchForm" id="searchForm" style="margin:0; padding:0">	
	<div class="txt_align_l">
		<span class="mar_full_10p normaltxt_11p_blk_arial bld">
			Listing Id: <input type="text"  value="<?php echo $listing_type_id?>" id="listing_type_id" name="listing_type_id"/>
		</span>
		<span class="mar_full_10p normaltxt_11p_blk_arial bld">
			Listing Type: 
			<select style="margin-left: 15px;" id="listing_type" name="listing_type">
				<option value="-" <?php if($listing_type =="select one") {echo "selected";} ?> selected="selected" >Select</option>
				<option value="course" <?php if($listing_type =="course") {echo "selected";} ?> >Course</option>
				<option value="institute" <?php if($listing_type =="institute") {echo "selected";} ?>>Institute</option>
				<option value="scholarship" <?php if($listing_type =="scholarship") {echo "selected";} ?>>Scholarship</option>
				<option value="notification" <?php if($listing_type =="notification") {echo "selected";} ?>>Admission Notification</option>
				<option value="consultant" <?php if($listing_type =="consultant") {echo "selected";} ?>>Consultant</option>
			</select>
		</span>
		<input type="button" onclick="getListingLeads();" value=" GO " />
	</div>
</form>
</div>
<div id="showListingLeads"></div>
