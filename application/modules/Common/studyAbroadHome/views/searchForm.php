<div class="home-form-section" style="position:relative;">
    <div class="search-form-box">
        <h2 class="title-box">Start your college search</h2>
        <div class="form-body clearfix">
			<div class="subtitle" style="margin-left:8px !important;">
				<h3>1. CHOOSE A COURSE</h3>    
				<span id="moreOptionsDown" style="display: none;height: 0px;" class="more-opt-box2" onclick="resetSearchForm();">Reset</span>
				<span id="moreOptionsUp" style="display: none;height: 0px;" class="more-opt-box2" onclick="resetSearchForm();">Reset</span>
			</div>
		    
            <form>
                <ul class="customInputs" style="padding: 0 5px 0 8px !important;">
                    <li class="choose-options chooseCourseClass" id="chooseCourse">
			    
						<?php foreach($desiredCourses as $course) {
							?>
						<div class="option-cols" style="width:23%">
							<input type="radio" name="popCourse" id="<?=$course['CourseName']?>" onclick="courseOnchangeActionsHomepage();" value="<?=$course['SpecializationId']?>">
							<label for="<?=$course['CourseName']?>">
								<span class="common-sprite" style="margin-top:2px; "></span>
								<p><strong><?=$course['CourseName']?></strong></p>
							</label>
						</div>
						<?php } ?>
							
                    </li>

					<li style="display: none;"><div id="or" style="margin:5px 0;display:none;"><div class="h-rule"></div><span class="opt-txt2">OR</span><div class="h-rule"></div></div></li>

					<li id="parentCat">
						<div class="custom-dropdown">
							<select class="universal-select" name="parentCatSelect" id="parentCatSelect" onchange="parentcatOnchangeActionsHomepage(); removeOneOption(); " style="padding:5px !important;font-size: 14px;">
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
					</li>
			    
					<li id="levelOfStudy" style="display: none; height: 0px;">
						<!--<h3 class="subtitle">CHOOSE YOUR LEVEL OF STUDY</h3>-->
						<div class="choose-options">
						<?php foreach($levelOfStudy as $course) {
							if($course != "PhD") { ?>
							<div class="option-cols" <?php if($course != "Certificate - Diploma") { ?> style="width:20%;" <?php } else { ?> style="width:32%;" <?php } ?>>
								<input type="radio" name="popCourse" onclick="levelOfStudyOnchangeActions();" id="<?=$course?>" value="<?=$course?>">
								<label for="<?=$course?>">
									<span class="common-sprite" style="margin-top:2px; "></span>
									<p>
										<strong style="font-size: 15px;">
										<?php
											if($course == "Certificate - Diploma"){
												echo "Certificate/Diploma";
											}
											else{
												echo $course;
											}
										?>
										</strong>
									</p>
								</label>
							</div>
							<?php }
						} ?>
						</div>
					</li>

					<li id="subCat" style="display: none; position:relative;/*overflow:visible !important;*/">
						<div class="custom-dropdown">
							<select class="universal-select" name="subCatSelect" id="subCatSelect" onchange="subCatOnchangeActions();" style="padding:5px !important;font-size: 14px;" onmousedown="skipDropdownShowOptions(event,this);">
								<!--<option value="">Choose Specialization (optional)</option>
							<?php foreach($subCatArray as $subCat) { ?>
								<option value="<?=$subCat['sub_category_id']?>"><?=$subCat['sub_category_name']?></option>
							<?php } ?>-->
							</select>
						</div>
						<div style="width: 346px; top: 30px ! important;display:none;" class="select-opt-layer" id = "subCatLayer">
							<div id="chooseSubcatScrollbar" class="scrollbar1">
								<div style="height: 135px;" class="scrollbar">
									<div style="height: 135px;" class="track">
										<div class="thumb">
											<div class="end"></div>
										</div>
									</div>
								</div>
								<div style="height: 135px;" class="viewport">
									<div style="top:0px;" class="overview">
										<ul class="hm-refine">
											<li>
												<div class="hm-refine-opt">
													<input type="checkbox" id="subcat_0" class="subcatCheckbox" checked="checked">
													<label for="subcat_0" onclick="subCatHomepageOnchangeActions(event,this);" style="cursor:pointer>
														<span class="common-sprite"></span>All MBA Specializations
													</label>
												</div>
												<ul class="hm-refine-list" id="subCatList">
													<?php do{ ?>
													<li>
														<input type="checkbox" name="subCategory[]" value="off" id="subcat_<?=($subCatArray[$i]['sub_category_id'])?>">
														<label onclick="subCatHomepageOnchangeActions(event,this);" for="subcat_<?=($subCatArray[$i]['sub_category_id'])?>" style="cursor:pointer;">
															<span class="common-sprite"></span><?=($subCatArray[$i]['sub_category_name'])?>
														</label>
													</li>
													<?php }while($i< count($subCatArray)); ?>
												</ul>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</li>
			    
					<li>
						<div class="subtitle" style="margin: 7px 0 8px;">
							<h3>2. CHOOSE STUDY DESTINATION</h3>
						</div>
						<div class="clearfix"></div>
						<div tabindex="1" onblur="hideCountryDropDown();" style="position: relative" style="top:-5px;">
							<div class="select-overlap rotate-arrow" onclick="showHideCountryDropdown();"></div>
							<div class="custom-dropdown">
								<select class="universal-select" style="padding:5px !important;font-size: 14px;">
									<option id="countrySelectOption">Choose Country</option>
								</select>
							</div>	
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
											<li id="countryDropdownCont"></li>
										</ol>
									</div>
								</div>
							</div>
							<div style="margin: 8px 0 0px 0; text-align:center; border-top:1px solid #ccc; padding: 10px 0 3px 0 " onclick="showHideCountryDropdown();"><a href="JavaScript:void(0);" class="button-style" style="padding: 7px 20px">OK</a></div>
						</div>
                    </li>
			    
					<li style="display: none; color: red" class="errorMsg" id="courseCountryLayer_error"></li>
					
					<li class ="moreOptions" style="margin:6px 0 12px 0;">
						<div class="subtitle">
							<h3>More options</h3>
						</div>
						<div class="clearfix"></div>
						<div class="flLt" style="position: relative; width:170px;">
							<div class="custom-dropdown">
								<select class="universal-select" style="padding:5px !important;font-size: 13px;" name="exam[]" id="examSelect" disabled="disabled" onchange="populateExamScoreoptions(this)">
									<option value="" selected = "selected">Any Exam</option>
								</select>
							</div>
						</div>
						<div class="flRt" style="position: relative; width:170px;">
							<div class="custom-dropdown">
								<select class="universal-select" style="padding:5px !important;font-size: 13px;" name="examsScore[]" id="examScoreSelect" onchange="examHomepageOnchangeActions();" disabled="disabled">
									<option value="" selected = "selected">Any Score</option>
								</select>
							</div>
						</div>
					</li>
					
					<li class ="moreOptions">
						<div class="flLt" style="position: relative; width:170px;">
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
						
						<div class="flRt" style="position: relative; width:170px;">
							<div class="custom-dropdown">
								<select class="universal-select" style="padding:5px !important;font-size: 13px;" disabled="disabled" id="sortSelect">
									<option value="none" selected="selected">Sort by Sponsored</option>
									<option value="viewCount_DESC">Popularity</option>
									<option value="fees_ASC">Low to high 1st year total fees</option>
									<option value="fees_DESC">High to low 1st year total fees</option>
									<option class="ascExamOption" value="exam_ASC_IELTS">Low to high IELTS exam score</option>
									<option class="descExamOption" value="exam_DESC_IELTS">High to low IELTS exam score</option>
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
					</li>
					
                    <li>
						<a uniqueattr = "SA_SESSION_ABROAD_HOME_PAGE/searchCollegeContinueButton" href="javascript:void(0);" onclick="checkForErrorsCourseCountryLayer();" class="button-style medium-button home-btn-style">Continue <i class="home-sprite rt-arr"></i></a>
					</li>
			    
                </ul>
            </form>
        </div>
    </div>
	<!--<div class="quick-heading">Quick links</div>-->
</div>
