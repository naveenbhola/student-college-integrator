<?php
if(!empty($content['applyContentDetails'])){
$homePage = $content['applyContentDetails']['isHomepage'];
}elseif(!empty($content['examContentDetails'])){
$homePage = $content['examContentDetails']['isHomepage'];
}
$expertDropDownHtml = '<option value="" >Select Author</option>';
foreach($expertsList as $key=>$value) {
    if($content['basic_info']['expert_id']==$value['user_id']){
        $expertDropDownHtml .=  '<option selected="selected" value="'.$value['user_id'].'">'.$value['name'].' ('.$value['origin'].')'.'</option>';
    } else {
        $expertDropDownHtml .=  '<option value="'.$value['user_id'].'">'.$value['name'].' ('.$value['origin'].') '.'</option>';
    }
    }
?>
<div class="cms-form-wrapper clear-width">
            <div class="clear-width">
                <h3 class="section-title" style="cursor:pointer;"><i class="abroad-cms-sprite <?=$firstSectionHeadingImageClass?>"></i>Add Author</h3>
                <div class="cms-form-wrap cms-accordion-div">
                    <ul>
                        <li>
                             <label>Author of this content* : </label>
                            <div class="cms-fields">

                                <select class="universal-select cms-field"  name = "contentExpert" id = "expert_<?php echo $formName; ?>" onblur = "showErrorMessage(this, '<?php echo $formName; ?>');" onchange = "showErrorMessage(this, '<?php echo $formName; ?>');" required = true caption="Author" validationType = "select">
                                    <?php echo $expertDropDownHtml; ?>
                                </select>
                                <div style="display: none" class="errorMsg" id="expert_<?php echo $formName; ?>_error"></div>
                            </div>
                        </li>
                    </ul>
                  </div>
                </div>
<div class="clear-width">
	<h3 class="section-title article">Article Mapping</h3>
	<h3 class="section-title guide" style="display: none">Guide Mapping</h3>
	<h3 class="section-title examPage" style="display: none">Exam Page Mapping</h3>
	<h3 class="section-title applyContent" style="display: none">Apply Content Mapping</h3>
	<h3 class="section-title examContent" style="display: none">Exam Content Mapping</h3>
	<div class="cms-form-wrap" style="margin-bottom:0;">
		<ul>
			<li class="examContent" style="display:none;">
				<label>Select Exam* : </label>
				<div class="cms-fields" style="font-size:15px;">
					<select class="universal-select cms-field" onchange="checkForExamContentHomepage(this);" name="examContent_examid" id="examContent_examid" caption="exam" validationType="select" required="true">
						<option value="">Select a exam</option>
						<?php foreach($abroadExamsMasterList as $exam)
						{
							if($exam['examId'] == $content['basic_info']['exam_type']){
								$selected = "selected";
							} else {
								$selected = "";
							}
							$valArr= array();
							$valArr['examId'] = $exam['examId'];
							$valArr['exam']   = $exam['exam'];
							?>
							<option <?php echo $selected; ?> value="<?php echo base64_encode(json_encode($valArr));?>"><?=$exam['exam']?></option>
						<?php } ?>
					</select>
					<div id="examContent_examid_error" class="errorMsg" style="margin-bottom: 5px;"></div>
				</div>
			</li>
			<li class="applyContent examContent" style="display:none;">
				<label class="applyContent">Topic* : </label>
				<div class="cms-fields applyContent">
					<div id="applyContentType2">
						<select class="universal-select cms-field" name="applyContentType" required="true" id="applyContentType" validationType = "select" caption="topic" onchange="checkForApplyContentHomepage(this);">
							<option value="">Select a Topic</option>
							<?php foreach($applyContentTypes as $applyContentTypeId => $applyContentType){ ?>
								<option value="<?=($applyContentTypeId)?>" <?=($content['applyContentDetails']['applyContentType'] == $applyContentTypeId?'selected="selected"':"")?>><?=($applyContentType['type'])?></option>
							<?php } ?>
						</select>
					</div>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="applyContentType_error"></div>
				</div>
				<div class="cms-fields" id = "setHomepageDiv" style="margin-top:6px;<?=($homepageAvailable?'display:none;':'')?>">
					<input type="checkbox" name="setHomepage" <?=($homePage == "yes"?'checked="checked"':'')?> value="1"/>Set this as homepage
				</div>
				<div style="display: none; margin-bottom: 5px" class="cms-fields errorMsg" id="setHomepageDiv_error"></div>
			</li>
			<li>
				<label>Country Name : </label>

				<div class="cms-fields">
					<div id="countryContDiv">
						<?php if(empty($content['country_info'])) { $content['country_info'][0] = ""; }
							foreach($content['country_info'] as $key=>$countryArr) { ?>
							<div class="add-more-sec countryDiv">
								<select class="universal-select cms-field" onchange="populateValueArray('country');" name="country[]" id="country_<?=$key+1?>" >
									<option value="">Select a Country</option>
									<?php foreach($abroadCountries as $country)
									{
										$countryId = $country->getId();
										$countryName = $country->getName();
										if($countryId == $countryArr['country_id']) {
											$selected = "selected";
										}
										else {
											$selected = "";
										} ?>
										<option <?=$selected?> value="<?php echo $countryId; ?>"><?php echo $countryName; ?></option>
									<?php } ?>
								</select>

								<a class="remove-link-2" href="javascript:void(0);" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeElementChunk(this, 'country', 5);setImageContainer();">
									<i class="abroad-cms-sprite remove-icon"></i>Remove Country
								</a>
							</div>
						<?php } ?>
					</div>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="country_error"></div>
					<a href="javascript:void(0);" <?php if(count($content['country_info']) >= 5) { ?> style="display: none" <?php } ?> id="country_addMore" onclick="addMoreElementChunk('country', 5);setImageContainer();">[+] Add Another Country</a>
				</div>
			</li>

			<li>
				<label>University Name : </label>

				<div class="cms-fields">
					<div id="universityContDiv">
						<?php if(empty($content['university_info'])) { $content['university_info'][0] = ""; }
							foreach($content['university_info'] as $key=>$universityArr) { ?>
							<div class="add-more-sec universityDiv">
								<select class="universal-select cms-field" name="university[]" id="university_<?=$key+1?>">
									<option value="">Select a University</option>
								</select>

								<a class="remove-link-2" href="javascript:void(0);" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeElementChunk(this, 'university', 5);setImageContainer();">
									<i class="abroad-cms-sprite remove-icon"></i>Remove University
								</a>
							</div>
						<?php } ?>
					</div>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="university_error">error</div>
					<a href="javascript:void(0);" <?php if(count($content['university_info']) >= 5) { ?> style="display: none" <?php } ?> id="university_addMore" onclick="addMoreElementChunk('university', 5);setImageContainer();">[+] Add Another University</a>
				</div>
			</li>

			<li>
				<div class="sel-course-title"><strong>Select A Course</strong></div>
				<label>Desired Course : </label>
				<div class="cms-fields" style="margin-top:6px;">
					<?php foreach($desiredCourses as $course) {
						if(in_array($course['SpecializationId'], $selectedDesiredCourses)){
							$checked = "checked";
						} else {
							$checked = "";
						} ?>
						<input type="checkbox" name="desiredCourse[]" <?=$checked?> value="<?=$course['SpecializationId']?>"/><?=$course['CourseName']?>
					<?php } ?>
				</div>
			</li>

			<li>
				<div id="courseContDiv">
					<?php if(empty($content['courseMapping_info'])) { $content['courseMapping_info'][0] = ""; }
						foreach($content['courseMapping_info'] as $key=>$courseArr) { ?>
						<div class="add-more-sec2 clear-width courseDiv">
							<ul>
								<li>
									<label>Course Type : </label>
									<div class="cms-fields" style="margin-top:6px;">
										<label style="width: auto;"><input type="radio" onclick="disableCatDropdown(this);" name="r_<?=$key+1?>" class="radioCheck" value="none" checked/> None</label>
										<?php foreach($courseType as $type) {
											if($type['CourseName'] == $courseArr['course_type']) {
												$checked = "checked";
											} else {
												$checked = "";
											} ?>
											<label style="width: auto;"><input type="radio" <?=$checked?> onclick="enableCatDropdown(this);" name="r_<?=$key+1?>" value="<?=$type['CourseName']?>"/> <?=$type['CourseName']?></label>
										<?php } ?>
									</div>
								</li>

								<li>
									<label class="categoryText" style="color: gray">Parent Category : </label>
									<div class="cms-fields">
										<select disabled="disabled" class="universal-select cms-field" onchange="populateValueArray('course'); appendChildCategories('content', '', this);" name="parentCat[]" id="parentCat_<?=$key+1?>">
											<option value="">Select a category</option>
											<?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){
												if($courseArr['parent_category_id'] && $parentCategoryId == $courseArr['parent_category_id']) {
													$selected = "selected";
												} else {
													$selected = "";
												} ?>
												<option <?=$selected?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
											<?php } ?>
										</select>
									</div>
								</li>

								<li>
									<label class="categoryText" style="color: gray">Subcategory : </label>
									<div class="cms-fields">
										<select disabled="disabled" class="universal-select cms-field" name="subCat[]" id="subCat_<?=$key+1?>">
											<option value="">Select a Subcategory</option>
										</select>
									</div>
								</li>
							</ul>
							<a class="remove-link" href="javascript:void(0);" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeElementChunk(this, 'course', 5);setImageContainer();"><i class="abroad-cms-sprite remove-icon"></i>Remove Course</a>
							<input style="display: none" name="hidden[]" value="<?=$key+1?>"></input>
						</div>
					<?php } ?>
				</div>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="course_error"></div>
				<a href="javascript:void(0);" <?php if(count($content['courseMapping_info']) >= 5) { ?> style="display: none" <?php } ?> id="course_addMore" onclick="addMoreElementChunk('course', 5);setImageContainer();" class="add-more-link">[+] Add Another Course</a>
			</li>
	   </ul>
	</div>
</div>
