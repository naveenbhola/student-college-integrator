<?php $maxLenTitle = 100; ?>	
<div>
	<form class="cms-form" id="form" action="/home/HomePageFeaturedCollegeCMS/saveFeaturedBannerForHomePage" enctype="multipart/form-data" method="post">
		<ul>
			 <li>
                               <label>Add New Content:</label>
                               <div class="left-div">
                                 <select class="positn-select" name="selectCntnt" id="selectCntnt" onChange="changeFeaturedArticleCnt(this.selectedIndex);">
                                   <option value="">Select</option>
                                   <option value="1">Featured College Banner For Desktop</option>
                                   <option value="2">Featured College Banner For Mobile</option>
                                 </select>
             					<div id="selectCntnt_error" class="errorMsg"></div>
                               </div>
                             </li>
                            </ul>
                      <div id="featuredCollegeForm"></div>
		<div class="save">
			<input type="button" class="table-save-btn" onclick="submitBannerFeaturedCollege('<?php echo $pageType; ?>', 'submit');" tabindex="5" value="Save" title="Submit">
			
		</div>
		<input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType; ?>" />
		<input type="hidden" id="action" name="action" value="<?php echo $action; ?>" />
		<input type="hidden" name="userId" value="<?php echo $userId; ?>" />
		<input type="hidden" id="bannerId" name="bannerId" value="<?php echo $id;?>" />
		<input type="hidden" id="maxLenTitle" value="<?php echo $maxLenTitle;?>" />
		<input type="hidden" id="maxLenLocation" value="<?php echo $maxLenLocation; ?>" />
		<input type="hidden" id="bannerRemoved" name="bannerRemoved" value="0"/>
		<input type="hidden" id="originalBannerImage" name="originalBannerImage"  value="<?php echo $slotData['image_url']; ?>"/>
		<input type="hidden" id="creationDate" name="creationDate"  value="<?php echo $slotData['creationDate']; ?>"/>

	</form>
</div>

<script type="text/javascript">
	var existingTimePeriods = new Array();
	<?php foreach ($tableData as $dataRow) {
		if(!$dataRow['isDefault'] && $dataRow['banner_id'] != $id) { ?>
			existingTimePeriods.push(new Array('<?php echo $dataRow["start_date"]; ?>', '<?php echo $dataRow["end_date"]; ?>'));
		<?php } 
	} ?>
	
	if(document.all) {
		document.body.onload = updateFormElem;
	} else {
		updateFormElem();
	}
</script>