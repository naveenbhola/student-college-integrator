<div style="position:relative">
	<div class="reg-form signup-fld invalid tDis" layerFor="baseCourses" regfieldid="educationType" id="educationType_block_<?php echo $regFormId; ?>" hasSearch='0' mandatory="1" caption="choose education type" type="layer">
		<div class="ngPlaceholder">Mode of Study</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please select Mode of Study.</div>
		</div>
	</div>

	<div class="crse-layer layerHtml ih">
		<p class="deflt-placeholder-label">Select one or more</p>
		<div class="lyr-sblst ctmScroll">
			<!-- <div class="scrollbar1">
					<div class="scrollbar thickSB" style="height: 145px;">
						<div class="track" style="height: 145px;">
								<div class="thumb" style="top: 0px; height: 2.57344px;"></div>
						</div>
					</div>
			<div class="viewport" style="height:145px;overflow:hidden;">		-->
			<div id="eduTypeField_<?php echo $regFormId; ?>"> 
				<?php $this->load->view('registration/fields/LDB/variable/educationTypeFields'); ?><!-- 
			</div>-->
			</div> 
		</div>
		<a href="javascript:void(0);" class="reg-btn btn-onlayr closeLayer">Done</a>
	</div>
	</div>
</div>
