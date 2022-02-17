<div class="selectionType" selectionType="<?php echo $flow; ?>" style="position:relative">
	<div class="reg-form signup-fld invalid tDis" layerFor="baseCourses" regfieldid="baseCourses" id="baseCourses_block_<?php echo $regFormId; ?>" hasSearch='1' type="layer" <?php echo $registrationHelper->getFieldCustomAttributes('baseCourses'); ?>>
		<div class="ngPlaceholder">Course</div>
		<div class="multiinput"></div>
		<div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">Please Enter Course.</div>
		</div>
	</div>

	<div class="crse-layer layerHtml ih">
		<div class="cr-srDiv">
			<div class="srch-bx cSearchX">
				<i class="src-icn"></i>
				<input type="text" placeholder="Search Course" class="cSearch"/>
				<i class="crss-icn ih">&times;</i>
			</div>
		</div>
		<p class="deflt-placeholder-label">Select one or more</p>
		<div class="lyr-sblst ctmScroll">
			<!-- <div class="scrollbar1">
				<div class="scrollbar thickSB" style="height: 145px;">
					<div class="track" style="height: 145px;">
							<div class="thumb" style="top: 0px; height: 2.57344px;"></div>
					</div>
				</div>
				<div class="viewport" style="height:145px;overflow:hidden;">-->
					<div id="baseCourseField_<?php echo $regFormId; ?>">	 
						<?php $this->load->view('registration/fields/LDB/variable/baseCourseField'); ?> 
					</div>
				<!-- </div> 
			</div> -->
		</div>
		
		<a href="javascript:void(0);" class="reg-btn btn-onlayr closeLayer" >Done</a>
		<div class="ih">
			<input type="hidden" flow="<?php echo $flow; ?>" id="loadBaseCourseDependencies_<?php echo $regFormId; ?>" onchange="userRegistrationRequest['<?php echo $regFormId; ?>'].getSubStreamSpecFieldValues(this);">
		</div>
	</div>

</div>