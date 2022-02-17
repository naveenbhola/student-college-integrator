<div class="sop-other-widget clearwidth">
	<strong class="sop-other-widget-head">Find the best colleges for yourself</strong>
	<div class="sop-other-widget-content">
		<ul class="find-collge-form customInputs">
			<li>
				<div style="width:164px !important;" class="select-overlap"></div>
				<div style="border-left:2px solid #008489;" class="custom-dropdown">
				<select class="universal-select" id="parentCatSelect" onchange="actionsOnCourseChange();">
					<option value="">Select course of interest</option>
					<optgroup label="Choose a popular course" style="background:#ffffcc;border:none !important;padding: 2px 0 0;">
					<?php foreach($desiredCourses as $course) { ?>
						<option desiredId="1" value="<?=$course['SpecializationId']?>" style="background:#fff;"><?=$course['CourseName']?></option>
					<?php } ?>
					</optgroup>
					<optgroup label="Or Choose a stream" style="background:#ffffcc;border:none !important;padding: 2px 0 0;">
					<?php foreach($abroadCategories as $categoryId => $categoryName) { ?>
						<option desiredId="0" value="<?php echo $categoryId; ?>" style="background:#fff;"><?php echo $categoryName['name']; ?></option>
					<?php } ?>
					</optgroup>
				</select>
				</div>
			</li>
			<li id="levelOfStudy" style="font-size:11px;display: none;">
				<?php foreach($levelOfStudy as $course) {
					if($course != "PhD") { ?>
						<div style="display:inline-block; margin-right:8px; float:left;" <?php if($course != "Certificate - Diploma") { ?> style="width:24%;" <?php } else { ?> style="width:46%;" <?php } ?> >
							<input <?=$checked?> type="radio" name="popCourse" onclick="getCountryLayer();" id="<?=$course?>" value="<?=$course?>">
							<label for="<?=$course?>">
								<span style="margin-top:2px"  class="common-sprite"></span>
								<p style="margin-top:2px"><strong style="font-weight:normal;"><?=(str_replace(' - ','/',$course))?></strong></p>
							</label>
						</div>
					<?php }
				} ?>
			</li>
			<li style="position:relative;" onblur="hideCountryDropDown();" tabindex="1">
				<div style="width:285px !important;" class="select-overlap rotate-arrow" onclick="showHideCountryDropdown();"></div>
				<div style="border-left:2px solid #008489;" class="custom-dropdown">
					<select class="universal-select">
						<option id="countrySelectOption">Select study destination</option>
					</select>
				</div>
				<div class="select-opt-layer" style="width: 296px; display: none;" id="countryDropdownLayer">
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
									<li id="countryDropdownCont"></li>
								</ol>
							</div>
						</div>
					</div>
					<div style="margin: 8px 0 0px 0; text-align:center; border-top:1px solid #ccc; padding: 10px 0 3px 0 " onclick="showHideCountryDropdown();"><a href="JavaScript:void(0);" class="button-style" style="padding: 7px 20px">OK</a></div>
				</div>
			</li>
			<li style="display: none; color: red" class="errorMsg" id="courseCountryLayer_error"></li>
			<li style="position:relative;" class="moreOptions">
				<div class="flLt" style="width:48%">
					<div class="custom-dropdown">
						<select class="universal-select" style="padding:5px !important;font-size: 13px;" name="exam[]" id="examSelect" disabled="disabled" onchange="populateExamScoreoptions(this)">
							<option value="" selected = "selected">Any Exam</option>
						</select>
					</div>
				</div>
				<div class="flRt" style="width:48%">
					<div class="custom-dropdown">
						<select class="universal-select" style="padding:5px !important;font-size: 13px;" name="examsScore[]" id="examScoreSelect" onchange="examHomepageOnchangeActions();" disabled="disabled">
							<option value="" selected = "selected">Any Score</option>
						</select>
					</div>
				</div>
			</li>
			<li class="moreOptions">
				<div class="flLt" style="width:48%">
							<div class="custom-dropdown">
								<select class="universal-select" style="padding:5px !important;font-size: 13px;" name="fee[]" id="feesSelect" disabled="disabled">
									<option value="0-90000000000" selected="selected">Any fees</option>
									<?php foreach($fees as $range => $text){
									if($text !='Any Fees'){
									?>
									<option value="<?=($range)?>"><?=($text)?></option>
									<?php }} ?>
								</select>
							</div>
				</div>
				<div class="custom-dropdown flRt" style="width:48%">
					<select class="universal-select" style="padding:5px !important;font-size: 13px;" disabled="disabled" id="sortSelect">
						<option value="none" selected="selected">Sort colleges</option>
						<option value="viewCount_DESC">Popularity</option>
						<option value="fees_ASC">Low to high 1st year total fees</option>
						<option value="fees_DESC">High to low 1st year total fees</option>
						<option class="ascExamOption" value="exam_ASC_IELTS">Low to high IELTS exam score</option>
						<option class="descExamOption" value="exam_DESC_IELTS">High to low IELTS exam score</option>
					</select>
				</div>
			</li>
		</ul>
		<a style="padding:8px 90px;" class="sop-dwnld-guide" onclick="submitFindCollegeForm();" href="javascript:void(0);" uniqueattr="SA_SESSION_ABROAD_<?=(strtoupper($contentType))?>_PAGE/findCollegeButton">Find colleges</a>
	</div>
</div>
