<div id="changeCourseCountryContDiv" style="display: none">
	<ul class="customInputs-large">
		<li class="form-bg">
			<div class="form-sec" id="chooseCourse">
				<?php if($currentLDBCourseId != 1) {
					$style = "";
				} else {
					$style = "display: none";
				} ?>
				<p class="form-label chooseCourseClass" style="padding-top:8px; <?=$style?>"><b>Choose a popular course</b></p>
				<div class="field-wrap">
					<div style="<?=$style?>" class="field-child-wrap chooseCourseClass">
						<?php foreach($desiredCourses as $course) {
							if($course['SpecializationId'] == $currentLDBCourseId) {
								$checked = 'checked';
								$textSpecialization = $course['CourseName'];
							} else {
								$checked = '';
							} ?>
							<div class="columns">
								<input <?=$checked?> type="radio" name="popCourse" id="<?=$course['CourseName']?>" onclick="courseOnchangeActions();" value="<?=$course['SpecializationId']?>">
								<label for="<?=$course['CourseName']?>">
									<span class="common-sprite"></span>
									<p><strong><?=$course['CourseName']?></strong><br /><?=$course['CourseFullName']?></p>
								</label>
							</div>
						<?php } ?>
					</div>
					<?php if($style == "") {
						$arrowClass = 'opt-arr-d';
					} else {
						$arrowClass = 'opt-arr-u';
					} ?>
					<div class="h-rule" id="or" style="display: none"><span class="opt-txt">OR</span></div>
					<?php if($style == "") {
						$style_up = "style = 'display: none'";
					} else {
						$style_down = "style = 'display: none'";
					} ?>
						<div class="h-rule" id="moreOptionsDown" <?=$style_down?> ><span class="more-opt-box" onclick="moreOptionsOnclickActions();"><i class="common-sprite opt-arr-d"></i>more options</span></div>
						<div class="h-rule" id="moreOptionsUp" <?=$style_up?> ><span class="more-opt-box" onclick="moreOptionsOnclickActions();"><i class="common-sprite opt-arr-u"></i>popular courses</span></div>
				</div>
			</div>
			
			<?php if($currentCategoryId && $currentCategoryId != 1) {
				$style = "";
			} else {
				$style = "style = 'display: none'";
			} ?>
			<div class="form-sec" id="parentCat" <?=$style?> >
				<p class="form-label"><b>Choose field of interest</b></p>
				<div class="field-wrap" style="position:relative">
					<select class="universal-select" name="parentCatSelect" id="parentCatSelect" onchange="parentcatOnchangeActions();">
						<option value="">Select field of interest</option>
						<?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){
							if($parentCategoryId == $currentCategoryId) {
								$selected = 'selected';
								$textSpecialization = $parentCategoryDetails['name'];
							} else {
								$selected = '';
							} ?>
							<option <?=$selected?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<?php if($currentCourseLevel != "") {
				$style = "";
			} else {
				$style = "style = 'display: none'";
			} ?>
			<div class="form-sec" id="levelOfStudy" <?=$style?> >
				<p class="form-label"><b>Choose your level of study</b></p>
				<div class="field-wrap">
					<div class="field-child-wrap">
						<?php foreach($levelOfStudy as $course) {
							if($course['CourseName'] != "PhD") {
								if(strtolower($course['CourseName']) == $currentCourseLevel) {
									$checked = 'checked';
								} else {
									$checked = '';
								} ?>
								<div class="columns" <?php if($course['CourseName'] != "Certificate - Diploma") { ?> style="width:24%;" <?php } else { ?> style="width:46%;" <?php } ?> >
									<input <?=$checked?> type="radio" name="popCourse" onclick="levelOfStudyOnchangeActions();" id="<?=$course['CourseName']?>" value="<?=$course['CourseName']?>">
									<label for="<?=$course['CourseName']?>">
										<span class="common-sprite"></span>
										<p><strong><?=$course['CourseName']?></strong></p>
									</label>
								</div>
							<?php }
						} ?>	
					</div>
				</div>
			</div>
		</li>
		
		<li id="subCat">
			<p class="form-label"><b>Choose specialization</b></p>
			<div class="field-wrap">
				<select class="universal-select" name="subCatSelect" id="subCatSelect" onchange="subCatOnchangeActions();">
					<option value="">Select <?=$textSpecialization?> Specialization (optional)</option>
					<?php foreach($subCatArray as $subCat) {
						if($subCat['sub_category_id'] == $currentSubCategoryId && $currentSubCategoryId > 1) {
							$selected = 'selected';
						} else {
							$selected = '';
						} ?>
						<option <?=$selected?> value="<?=$subCat['sub_category_id']?>"><?=$subCat['sub_category_name']?></option>
					<?php } ?>
				</select>
			</div>
		</li>
		
		<li>
			<p class="form-label"><b>Study destination</b></p>
			<div class="field-wrap" tabindex="1" onblur="hideCountryDropDown();" style="position: relative">
				<div class="select-overlap" onclick="showHideCountryDropdown();"></div>
					<select class="universal-select" style="position: relative; z-index: -1" id="countrySelect">
						<?php if($currentCountryIdArr != "" && count($currentCountryIdArr) > 0) {
							$count = 0;
							foreach($countryIdNameArray as $key=>$country) {
								foreach($currentCountryIdArr as $currentCountry) {
									if($currentCountry == $country['id']) {
										$count++;
									}
								}
							}
						}
						if($count > 0) { ?>
							<option id="countrySelectOption">Choose Country (<?=$count?> selected)</option>
						<?php } else { ?>
							<option id="countrySelectOption">Choose Country</option>
						<?php } ?>
					</select>
				
				<div class="select-opt-layer" style="width: 410px; display: none;" id="countryDropdownLayer">
					<p>Choose up to 3 countries</p>
					<div class="scrollbar1" id="chooseCourseCountryLayerScrollbar">
						<div class="scrollbar courseCountryScrollbarHeight">
							<div class="track courseCountryScrollbarHeight">
								<div class="thumb">
									<div class="end"></div>
								</div>
							</div>
						</div>
						<div class="viewport courseCountryScrollbarHeight" style="height: 135px;">
							<div class="overview">
								<ol>
									<li id="countryDropdownCont">
										<!-- append countries dynamically -->
										<?php foreach($countryIdNameArray as $key=>$country) {
											foreach($currentCountryIdArr as $currentCountry) {
												if($currentCountry == $country['id']) {
													$active = 'active';
													$checked = 'checked';
													break;
												} else {
													$active = '';
													$checked = '';
												}
											} ?>
											<div class="country-flag-cont <?=$active?>">
												<div class="flag-main-box" onclick="checkForNumberOfCountriesSelected(this);">
													<p>
														<span class='flag-box flLt'><img src='/public/images/abroadCountryFlags/<?=$country['name']?>.gif' /></span> <strong><?=$country['name']?></strong>
													</p>
												</div>
												<div class="flag-chkbox">
													<input <?=$checked?> type='checkbox' name='country[]' id='<?=$key?>-flag' value='<?=$country['id']?>' onclick="checkForNumberOfCountriesSelected(this, 'checkbox');">
													<label for='<?=$key?>-flag'>
														<span class='common-sprite'></span>
													</label>
												</div>
											</div>
										<?php } ?>
									</li>
								</ol>
							</div>
						</div>
					</div>
					<div style="margin: 8px 0 0px 0; text-align:center; border-top:1px solid #ccc; padding: 10px 0 3px 0 " onclick="showHideCountryDropdown();"><a href="JavaScript:void(0);" class="button-style" style="padding: 7px 20px">OK</a></div>
				</div>
				
				<div style="display: none; color: red" class="errorMsg" id="courseCountryLayer_error"></div>
				<input type="button" value="Update" onclick="checkForErrorsCourseCountryLayer();" class="button-style big-button" style="width:100%; margin-top:20px" />
			</div>
		</li>
	</ul>
</div>
