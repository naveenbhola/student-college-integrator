<?php 
	if($pageType == 'banner') {
		$maxLenTitle = 100;
		$maxLenLocation = 28;
	} else {
		$maxLenTitle = 60;
		$maxLenLocation = 50;
	}
?>
<div>
	<form class="cms-form" id="form" action="/home/HomePageCMS/saveBannerFeaturedTextForm" enctype="multipart/form-data" method="post">
		<ul>
			<li>
				<label>Client ID:</label>
				<div class="left-div">
					<input id="clientId" name="clientId" type="text" class="client-txt-fld" autocomplete="off" value="<?php echo $slotData['client_id']; ?>">
					<div id="clientId_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Title*:</label>
				<div class="left-div">
					<input size="40" maxlength="<?=$maxLenTitle?>" id="title" name="title" class="title-txt-fld" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['title']); ?>">
					<p class="erro-instrct">College name which will be visible on home page (Max <?=$maxLenTitle?> characters)</p>
					<div id="title_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Location:</label>
				<div class="left-div">
				  	<input size="40" maxlength="<?=$maxLenLocation?>" id="location" name="location" class="loc-txt-fld" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['location_text']); ?>">
				  	<p class="erro-instrct">This location will be visible on homepage (Max <?=$maxLenLocation?> characters)</p>
				  	<div id="location_error" class="errorMsg"></div>
				</div> 
			</li>
		   	
		   	<?php if($pageType == 'banner') { ?>
				<li>
					<label>Banner image*:</label>
					<div class="left-div">
						<?php if(!empty($slotData['image_url'])) {
							$display = 'display:none;'; ?>
							<img id="bannerImage" width="360" height="100" border="0" src="<?php echo $slotData['image_url']; ?>"/>
							<a id="removeBannerImage" onclick="removeBannerImage();" href="javascript:void(0);" >Remove</a>
						<?php } ?>
						<input style="<?php echo $display; ?>" type="file" id="inputBannerImage" name="bannerImage[]" value="" />
						<p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 1366px X 446px or 1920px X 528px | Accepted Format: gif or jpg or png</p>
						<div id="bannerImage_error" class="errorMsg"></div>
					</div>
				</li>
			<?php } else { ?>
				<li style="width: 785px;">
					<label>Text to show*:</label>
					<div class="left-div">
						<input id="displayText" style="width:602px" name="displayText" class="title-txt-fld" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['display_text']); ?>">
						<p class="erro-instrct">This text should be compilation of institute and location text</p>
						<p class="erro-instrct">- Use separator <b><?php echo htmlspecialchars('<big>') ?></b> between title and location, to increase font and move location to next line
						<p class="erro-instrct">- Use separator <b><?php echo htmlspecialchars('<small>') ?></b> between title and location, for continuing location in same line
						<div id="displayText_error" class="errorMsg"></div>
					</div>
				</li>

				<li>
					<label>USP*:</label>
					<div class="left-div">
						<input id="usp" name="usp" class="title-txt-fld" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['usp']); ?>">
						<p class="erro-instrct">This USP will be visible on homepage</p>
						<div id="usp_error" class="errorMsg"></div>
					</div> 
				</li>
			<?php } ?>
			
			<li>
				<label>Target url*:</label>
				<div class="left-div">
				 	<input size="40" id="url" name="url" class="target-txt-fld" type="text" autocomplete="off" value="<?php echo $slotData['target_url']; ?>">
				 	<p class="erro-instrct">Should be either Course listing or Institute listing page</p>
				 	<div id="url_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Subscription range*:</label>
				<input type="text" placeholder="Start date" id="from_date" name="from_date" readonly="" validationtype="html" caption="Start date" class="start-date" autocomplete="off" value="<?php echo $slotData['start_date']; ?>">
					<i onclick="pickStartDate(this);" style="cursor:pointer" id="importantDateStartIcon_1" class="abroad-cms-sprite calendar-icon"></i>
					
				<input type="text" placeholder="End date" id="to_date" name="to_date" readonly="" validationtype="html" caption="End date" class="end-date" autocomplete="off" value="<?php echo $slotData['end_date']; ?>">
					<i onclick="pickEndDate(this);" style="cursor:pointer" id="importantDateEndIcon_1" class="abroad-cms-sprite calendar-icon"></i>
				
				<div id="range_error" class="errorMsg"></div>
			</li>
		</ul>
		<div class="save">
			<?php if($pageType != 'banner') { ?>
				<input type="button" class="table-preview-btn" onclick="submitBannerFeaturedTextForm('<?=$pageType?>', 'preview');" tabindex="5" value="Preview Banner" title="Submit">
			<?php } ?>
			<input type="button" class="table-save-btn" onclick="submitBannerFeaturedTextForm('<?=$pageType?>', 'submit');" tabindex="5" value="Save" title="Submit">
			<?php if($slotData['is_default']) {
				$checked = 'checked=checked';
			} ?>
			<label><input style="vertical-align: middle;margin-right: 6px;" id="isDefault" name="default" <?=$checked?> type="checkbox">Save as default banner</label>
		</div>

		<input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType; ?>" />
		<input type="hidden" id="action" name="action" value="<?=$action?>" />
		<input type="hidden" name="userId" value="<?=$userId?>" />
		<input type="hidden" name="bannerId" value="<?=$id?>" />
		<input type="hidden" id="maxLenTitle" value="<?=$maxLenTitle?>" />
		<input type="hidden" id="maxLenLocation" value="<?=$maxLenLocation?>" />
		<input type="hidden" id="bannerRemoved" name="bannerRemoved" value="0"/>
		<input type="hidden" id="originalBannerImage" name="originalBannerImage"  value="<?php echo $slotData['image_url']; ?>"/>
	</form>
</div>

<script type="text/javascript">
	var existingTimePeriods = new Array();
	<?php foreach ($tableData as $dataRow) {
		if(!$dataRow['is_default'] && $dataRow['banner_id'] != $id) { ?>
			existingTimePeriods.push(new Array('<?php echo $dataRow["start_date"]; ?>', '<?php echo $dataRow["end_date"]; ?>'));
		<?php } 
	} ?>
	
	if(document.all) {
		document.body.onload = updateFormElem;
	} else {
		updateFormElem();
	}
</script>