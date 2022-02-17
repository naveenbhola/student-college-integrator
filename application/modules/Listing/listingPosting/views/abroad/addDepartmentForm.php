<?php
$formName = "deptform";
$countries = $abroadCountries;
$breadCrumbTitle = 'Add new department';
$pageTitle = 'Add New Department';
$formStatus = 'ADD';
$selectBoxDisabled = "";
$blockDisplayStyle = "display:none;";
if($action == 'EDIT') {
	$blockDisplayStyle = "display:block;";
	$breadCrumbTitle = 'Edit department';
	if($department_details['status'] == 'draft'){
		$pageTitle = 'Edit Department<span style="color:red;"> (Draft state)</span>';
	} else if($department_details['status'] == ENT_SA_PRE_LIVE_STATUS) {
		$pageTitle = 'Edit Department<span style="color:red;"> (Published version)</span>';
	}
	$formStatus = 'EDIT';
	$selectBoxDisabled = "";
}
if($action == 'ADD'){
	$department_details = array();
}
?>
<div class="abroad-cms-rt-box">
	<?php
		$displayData["breadCrumb"] 	= array(array("text" => "All Departments", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_DEPARTMENT),
											array("text" => "$breadCrumbTitle", "url" => "")
									);
		$displayData["pageTitle"]  	= $pageTitle;
		if($action == 'EDIT'){
			$displayData["lastUpdatedInfo"] = array(
										"date" => $department_details['last_modify_date'],
										"username" => $department_details['last_modified_by_firstname']." ".$department_details['last_modified_by_lastname']
										);	
		}
		
		// load the title section
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
	<?php
	if($action == 'EDIT') {
		$info = array();
		$info['percentage_completion'] = $department_details['profile_percentage_completion'];
		$this->load->view("listingPosting/abroad/listingProgressBar", $info);
	}
	$requiredField = 'required="true"';
	if($action == 'ADD'){
		$requiredField = '';
	}
	?>
	<form name="form_<?php echo $formName;?>" id="form_<?php echo $formName;?>">
		<div class="cms-form-wrapper clear-width">
			<!-- Department parent info container -->
			<div class="clear-width" id="DEPT_PI_CONT" style="margin-bottom:10px;">
				<!--onclick="toggleParentChildrens('DEPT_parentinfo_h3', 'DEPT_PI_CONT');"-->
				<h3 class="section-title" id="DEPT_parentinfo_h3" style="cursor:pointer;">
					<i class="abroad-cms-sprite minus-icon"></i>Parent Department Association
				</h3>
				<div class="cms-form-wrap cms-accordion-div" id="DEPT_PI_CONT_content">
					<ul>
						<li>
							<label>Country Name* : </label>
							<div class="cms-fields">
								<?php
								if($university_from_url == "true" && !empty($university_details)) {
									?>
									<select disabled validationType="select"  required="true" caption="country" id="country_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');">
									<?php
								} else if($action == 'EDIT'){
									?>
									<select disabled <?php echo $selectBoxDisabled;?> validationType="select" required="true" caption="country" id="country_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');">
									<?php
								} else {
									?>
									<select validationType="select" required="true" caption="country" id="country_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>'); populateAbroadUniversitiesForDept('<?=$formName?>',1);">
									<?php
								}
								?>
								
								<?php
								if($university_from_url == "true" && !empty($university_details)) {
								?>
									<option selected value="<?php echo $university_details['country_id'];?>"><?php echo $university_details['country_name'];?></option>
								<?php
								} else if($action == 'EDIT'){
								?>
									<option selected value="<?php echo $department_details['country_id'];?>"><?php echo $department_details['country_name'];?></option>
								<?php
								} else {
									?>
									<option selected value="">Select Country</option>
									<?php
									foreach($countries as $country) {
									?>
										<option value="<?php echo $country->getId();?>"><?php echo $country->getName();?></option>
									<?php
									}
								}
								?>
								</select>
								<div style="display:none" class="errorMsg" id="country_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Parent University Name* : </label>
							<div class="cms-fields">
								<?php
								if($university_from_url == "true" && !empty($university_details)) {
									?>
									<select disabled validationType="select" tooltip="dept_parentUniv"  required="true" caption="university" id="university_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');">
										<option value="<?php echo $university_details['university_id'];?>"><?php echo $university_details['name'];?></option>
									</select>
									<?php
								} else if($action == 'EDIT'){
									?>
									<select disabled <?php echo $selectBoxDisabled;?> validationType="select" tooltip="dept_parentUniv" required="true" caption="university" id="university_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');">
										<option value="<?php echo $department_details['university_id'];?>"><?php echo $department_details['university_name'];?></option>
									</select>
									<?php
								} else {
								?>
									<select validationType="select" tooltip="dept_parentUniv" required="true" caption="university" id="university_<?=$formName?>" class="universal-select cms-field" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');">
										<option value="">Select University</option>
									</select>
								<?php
								}
								?>
								<div style="display:none" class="errorMsg" id="university_<?=$formName?>_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- Department basic info container -->
			<div class="clear-width" id="DEPT_BI_CONT" style="margin-bottom:10px;">
				<!--onclick="toggleParentChildrens('DEPT_basicinfo_h3', 'DEPT_BI_CONT');"-->
				<h3 id="DEPT_basicinfo_h3" class="section-title" style="cursor:pointer;">
					<?php
					$titleClassName = 'plus-icon';
					if($action == 'EDIT'){
						$titleClassName = 'minus-icon';
					}
					?>
					<i class="abroad-cms-sprite <?php echo $titleClassName;?>"></i>Department Basic Information
				</h3>
				<div class="cms-form-wrap cms-accordion-div" id="DEPT_BI_CONT_content" style="<?php echo $blockDisplayStyle;?>">
					<ul>
						<li>
							<label>Deparment Website* : </label>
							<div class="cms-fields">
								<input validationType="link" tooltip="dept_website" <?php echo $requiredField;?> value="<?php echo $department_details['website'];?>" caption="department website" id="website_<?=$formName?>" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');" />
								<div style="display:none" class="errorMsg" id="website_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Accreditation details : </label>
							<div class="cms-fields">
								<input validationType="str" tooltip="dept_accreditation" id="accreditation_details_<?=$formName?>"; value="<?php echo $department_details['DEPT_ACCREDITATION_DETAILS'];?>" caption="department accreditation details" maxlength="100" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
								<div style="display:none" class="errorMsg" id="accreditation_details_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>School name* : </label>
							<div class="cms-fields">
								<input validationType="str" tooltip="dept_school" class="universal-txt-field cms-text-field" value="<?php echo htmlspecialchars($department_details['department_name']);?>" type="text" maxlength="500" required="true" caption="school name" id="school_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>'); checkDepartmentNameUniqueness('<?=$formName?>'); "/>
								<div style="display:none" class="errorMsg" id="school_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>School Acronym : </label>
							<div class="cms-fields">
								<input validationType="str" tooltip="dept_schoolacrnym"  class="universal-txt-field cms-text-field" value="<?php echo $department_details['abbreviation'];?>" type="text" maxlength="100" caption="school acronym" id="school_acronym_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');"/>
								<div style="display:none" class="errorMsg" id="school_acronym_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>School description* : </label>
							<div class="cms-fields">
								<textarea validationType="html" required="true" tooltip="dept_schoolDesc"  class="cms-textarea tinymce-textarea" maxlength="4000" <?php echo $requiredField;?> caption="school description" id="school_description_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');"><?php echo $department_details['DEPT_DESCRIPTION'];?></textarea>
								<div style="display:none" class="errorMsg" id="school_description_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Contact person name : </label>
							<div class="cms-fields">
								<input tooltip="dept_contpersname" class="universal-txt-field cms-text-field" value="<?php echo $department_details['contact_person'];?>" type="text" maxlength="100" caption="contact person name" id="contact_person_name_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');" validationType = "str" />
								<div style="display:none" class="errorMsg" id="contact_person_name_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Contact email : </label>
							<div class="cms-fields">
								<input validationType="email" tooltip="dept_contemail" class="universal-txt-field cms-text-field" type="text" value="<?php echo $department_details['contact_email'];?>" caption="contact email" id="contact_email_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');"/>
								<div style="display:none" class="errorMsg" id="contact_email_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Contact phone number : </label>
							<div class="cms-fields">
								<input tooltip="dept_contphn" class="universal-txt-field cms-text-field" value="<?php echo $department_details['contact_main_phone'];?>" type="text" maxlength="30" caption="contact phone no" id="contact_phone_no_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>'); " validationType = "str"/>
								<div style="display:none" class="errorMsg" id="contact_phone_no_<?=$formName?>_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- Department additional information -->
			<div class="clear-width" id="DEPT_AI_CONT">
				<!--onclick="toggleParentChildrens('DEPT_additionalinfo_h3', 'DEPT_AI_CONT');"-->
				<h3 id="DEPT_additionalinfo_h3" class="section-title" style="cursor:pointer;">
					<?php
					$titleClassName = 'plus-icon';
					if($action == 'EDIT'){
						$titleClassName = 'minus-icon';
					}
					?>
					<i class="abroad-cms-sprite <?php echo $titleClassName;?>"></i>Additional Information
				</h3>
				<div class="cms-form-wrap cms-accordion-div" id="DEPT_AI_CONT_content" style="<?php echo $blockDisplayStyle;?>">
					<ul>
						<li>
							<label>Link to Faculty Page : </label>
							<div class="cms-fields">
								<input validationType="link" tooltip="dept_linkfaculty"  class="universal-txt-field cms-text-field" value="<?php echo $department_details['FACULTY_PAGE'];?>" type="text" caption="faculty page url" id="faculty_page_url_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>'); "/>
								<div style="display:none" class="errorMsg" id="faculty_page_url_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Link to Alumni Page : </label>
							<div class="cms-fields">
								<input validationType="link" tooltip="dept_linkalumini" class="universal-txt-field cms-text-field" value="<?php echo $department_details['ALUMNI_PAGE'];?>" type="text" caption="alumni page url" id="alumni_page_url_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>'); "/>
								<div style="display:none" class="errorMsg" id="alumni_page_url_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>Facebook Link : </label>
							<div class="cms-fields">
								<input validationType="link" tooltip="dept_linkfb" class="universal-txt-field cms-text-field last-in-section" value="<?php echo $department_details['FB_PAGE'];?>" type="text" caption="facebook page url" id="fb_page_url_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>'); "/>
								<div style="display:none" class="errorMsg" id="fb_page_url_<?=$formName?>_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<!-- Department SEO information -->
			<div class="clear-width" id="DEPT_SEO_CONT">
				<!--onclick="toggleParentChildrens('DEPT_additionalinfo_h3', 'DEPT_AI_CONT');"-->
				<h3 id="DEPT_SEO_h3" class="section-title" style="cursor:pointer;">
					<?php
					$titleClassName = 'plus-icon';
					if($action == 'EDIT'){
						$titleClassName = 'minus-icon';
					}
					?>
					<i class="abroad-cms-sprite <?php echo $titleClassName;?>"></i>SEO Details
				</h3>
				<div class="cms-form-wrap cms-accordion-div" id="DEPT_AI_CONT_content" style="<?php echo $blockDisplayStyle;?>">
					<ul>
						<li>
							<label>SEO Title : </label>
							<div class="cms-fields">
								<input validationType="str" id="seo_title_<?=$formName?>"; value="<?php echo $department_details['listing_seo_title'];?>" caption="department seo title" maxlength="100" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
								<div style="display:none" class="errorMsg" id="seo_title_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>SEO Keywords : </label>
							<div class="cms-fields">
								<input validationType="str" id="seo_keywords_<?=$formName?>"; value="<?php echo $department_details['listing_seo_keywords'];?>" caption="department seo keywords" maxlength="100" class="universal-txt-field cms-text-field" type="text" onblur="showErrorMessage(this, '<?=$formName?>');"/>
								<div style="display:none" class="errorMsg" id="seo_keywords_<?=$formName?>_error"></div>
							</div>
						</li>
						<li>
							<label>SEO Description : </label>
							<div class="cms-fields">
								<textarea validationType="str" class="cms-textarea" maxlength="4000" caption="comment regarding department posting" id="seo_description_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');"><?php echo $department_details['listing_seo_description'];?></textarea>
								<div style="display:none" class="errorMsg" id="seo_description_<?=$formName?>_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="clear-width" style="border-top:1px solid #CCCCCC;">
				<div class="cms-form-wrap cms-accordion-div">
					<ul>
						<li>
							<label>Comment* : </label>
							<div class="cms-fields">
								<textarea validationType="str" class="cms-textarea" maxlength="4000" required="true" caption="comment regarding department posting" tooltip="dept_comments" id="comments_<?=$formName?>" onblur="showErrorMessage(this, '<?=$formName?>');"></textarea>
								<div style="display:none" class="errorMsg" id="comments_<?=$formName?>_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<input type="hidden" id="form_action_<?php echo $formName;?>" name="form_action_<?php echo $formName;?>" value="<?php echo $formStatus;?>"/>
		<input type="hidden" id="form_button_pressed_<?php echo $formName;?>" name="form_button_pressed_<?php echo $formName;?>" value=""/>
		<input type="hidden" id="department_id_<?php echo $formName;?>" name="department_id_<?php echo $formName;?>" value="<?php echo $department_details['institute_id'];?>"/>
		<input type="hidden" id="old_institute_location_id_<?php echo $formName;?>" name="old_institute_location_id_<?php echo $formName;?>" value="<?php echo $department_details['institute_location_id'];?>"/>
		<input type="hidden" id="submit_date_<?php echo $formName;?>" name="submit_date_<?php echo $formName;?>" value="<?php echo $department_details['submit_date'];?>"/>
		<input type="hidden" id="seo_url_<?php echo $formName;?>" name="seo_url" value="<?php echo $seo_url;?>"/>
		
	</form>
	<div class="button-wrap">
		<a id="DEPT_saveasdraft_btn" href="javascript:void(0);" onclick="triggerSaveDepartmentPageAsDraft(this, '<?=$formName?>');" class="gray-btn">Save as Draft</a>
		<?php if($previewLinkFlag){?><a target="_blank" href="<?=$department_details['listing_seo_url']?>" class="gray-btn">Preview</a><?php }?>
		<a href="javascript:void(0);" class="orange-btn" onclick="triggerSaveAndPublishDepartmentPage(this, '<?=$formName?>');">Save & Publish</a>
		<a  href="javascript:void(0);" onclick="cancelDepartmentPost();" class="cancel-btn">Cancel</a>
	</div>
</div>
<div class="clearFix"></div>

<script type="text/javascript">
	var departmentListingURL = '<?php echo ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_DEPARTMENT;?>';
</script>