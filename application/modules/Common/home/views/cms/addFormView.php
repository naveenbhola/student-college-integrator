<ul>
<li>
				<label>Client ID*:</label>
				<div class="left-div">
					<input id="clientId" name="clientId" type="text" class="client-txt-fld" autocomplete="off" value="<?php echo $slotData['client_id']; ?>">
					<div id="clientId_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>College Title*:</label>
				<div class="left-div">
					<input size="40"  maxlength="100" id="title" name="title"  class="title-txt-fld" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['collegeName']); ?>">
					<p class="erro-instrct">College name which will be visible on home page (Max 100 characters)</p>
					<div id="title_error" class="errorMsg"></div>
				</div> 
			</li>
			
				<li>
					<label>Banner image*:</label>
					<div class="left-div">
						<?php if(!empty($slotData['image_url'])) {
							$slotData['image_url'] = MEDIA_SERVER.$slotData['image_url'];
							$display = 'display:none;'; ?>
							<img id="bannerImage" width="200" height="110" border="0" src="<?php echo $slotData['image_url']; ?>"/>
							<a id="removeBannerImage" onclick="removeBannerImage();" href="javascript:void(0);" >Remove</a>
						<?php } ?>
						<input style="<?php echo $display; ?>" type="file" id="inputBannerImage" name="bannerImage[]" value="" />
						<?php if($index == 1){ ?>
						<p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 200px X 110px.Image should be of format gif, jpg, png, jpeg and size of the image should be less than 15Kb.</p>
						<?php } else { ?>
						<p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 220px X 120px.Image should be of format gif, jpg, png, jpeg and size of the image should be less than 15Kb.</p>
						<?php }?>
						<div id="bannerImage_error" class="errorMsg"></div>
					</div>
				</li>
			
				
			
			<li>
				<label>Target url*:</label>
				<div class="left-div">
				 	<input size="40" id="url" name="url" class="target-txt-fld" type="text" autocomplete="off" value="<?php echo $slotData['target_url']; ?>">
				 	<p class="erro-instrct">Should be either Course listing or Institute listing page</p>
				 	<div id="url_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Subscription Date*:</label>
				<input type="text" placeholder="Start date" id="from_date" name="from_date" readonly="" validationtype="html" caption="Start date" class="start-date" autocomplete="off" value="<?php echo $slotData['start_date']; ?>">
					<i onclick="pickStartDate(this);" style="cursor:pointer" id="importantDateStartIcon_1" class="abroad-cms-sprite calendar-icon"></i>
					
				<input type="text" placeholder="End date" id="to_date" name="to_date" readonly="" validationtype="html" caption="End date" class="end-date" autocomplete="off" value="<?php echo $slotData['end_date']; ?>">
					<i onclick="pickEndDate(this);" style="cursor:pointer" id="importantDateEndIcon_1" class="abroad-cms-sprite calendar-icon"></i>
				
				<div id="range_error" class="errorMsg"></div>
			</li>
			<li style="display:none;">
				<div class="left-div">
				 	<input size="40" id="creationDate" name="creationDate" class="target-txt-fld" type="text" autocomplete="off" value="<?php echo $slotData['creationDate']; ?>">
				</div> 
			</li>
			<?php if($slotData['isDefault']) {
				$checked = 'checked=checked';
			} ?>
			<li>
			<label><input style="vertical-align: middle;margin-right: 6px;" id="isDefault" name="default" <?php echo $checked; ?> type="checkbox">Save as default banner</label>
			</li>
</ul>