 <div class="cms-box-form">
	<form class="cms-form" id="form" action="/home/HomePageCMS/saveTestimonialsForHomePage" enctype="multipart/form-data" method="post">
		<ul>
			<li>
				<label>Name*:</label>
				<div class="left-div">
					<input size="40" maxlength="40" id="name" name="Name" type="text" class="name-txt-fld required" autocomplete="off" value="<?php echo $slotData['name']; ?>" minlength="2">
					<p class="erro-instrct">Name which will be visible on home page (Max 40 characters)</p>
					<div id="Name_error" class="errorMsg"></div>
				</div> 
			</li>
			
			<li>
				<label>Designation:</label>
				<div class="left-div">
					<input size="40" maxlength = "40" minLength = "0" id="designation" name="Designation" caption="Designation"  class="name-txt-fld required" type="text" autocomplete="off" value="<?php echo htmlspecialchars($slotData['designation']); ?>">
					<p class="erro-instrct">Designation which will be visible on home page (Max 40 characters)</p>
					<div id="Designation_error" class="errorMsg"></div>
				</div> 
			</li>

			<li>
				<label>Testimonial*:</label>
				<div class="left-div">
					<textarea size="180" maxlength="180" minLength="2" id="testimonial" name="Testimonial"  class="testmonials required" type="text"  autocomplete="off"><?php echo htmlspecialchars($slotData['testimonial']); ?></textarea>
					<p class="erro-instrct">Testimonial which will be visible on home page (Max 180 characters)</p>
					<div id="Testimonial_error" class="errorMsg"></div>
				</div> 
			</li>
			
				<li>
					<label>Display Picture*:</label>
					<div class="left-div">
						<?php if(!empty($slotData['image_url'])) {
							$display = 'display:none;'; ?>
							<img id="bannerImage" width="360" height="100" border="0" src="<?php echo MEDIA_SERVER.$slotData['image_url']; ?>"/>
							<a id="removeBannerImage" onclick="removeBannerImage();" href="javascript:void(0);" >Remove</a>
						<?php } ?>
						<input style="<?php echo $display; ?>" type="file" id="inputBannerImage" name="bannerImage[]" value="" / caption="Image">
						<p style="<?php echo $display; ?>" id="bannerImageHelpText" class="erro-instrct">Accepted Size: 70px X 70px</p>
						<div id="bannerImage_error" class="errorMsg"></div>
					</div>
				</li>
			
		</ul>
		<div class="save">
			<input type="button" class="table-preview-btn" tabindex="5" value="Save testimonial" title="Submit" onclick="updateFormElem(); submitBannerFeaturedCollege('<?php echo $pageType; ?>','submit');">
		</div>
		<input type="hidden" id="pageType" name="pageType" value="<?php echo $pageType; ?>" />
		<input type="hidden" id="action" name="action" value="<?php echo $action; ?>" />
		<input type="hidden" name="userId" value="<?php echo $userId?>" />
		<input type="hidden" id="bannerRemoved" name="bannerRemoved" value="0"/>
		<input type="hidden" name="testimonialId" value="<?=$id?>" />
		<input type="hidden" name="status" value="<?php echo $slotData['status'];?>" />
		<input type="hidden" id="originalBannerImage" name="originalBannerImage"  value="<?php echo $slotData['image_url']; ?>"/>
		<input type="hidden" id="creationDate" name="creationDate"  value="<?php echo $slotData['creationDate']; ?>"/>

	</form>
</div>