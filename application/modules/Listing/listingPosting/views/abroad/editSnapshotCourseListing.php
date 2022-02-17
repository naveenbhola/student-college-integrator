<?php $formName = "snapshotEdit"; ?>

<div class="abroad-cms-rt-box">
    <form id="form_<?=$formName?>" onsubmit="">
	<?php
		$displayData["breadCrumb"] 	= array(array("text" => "All Courses", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE),
											array("text" => "Edit Snapshot Course", "url" => "")
									);
		$displayData["pageTitle"]  	= "Edit Snapshot Course";
		
		$displayData["lastUpdatedInfo"] = array(
									"date" => $lastModified,
									"username" => $lastModifiedBy
									);	
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
	<!--<div class="abroad-breadcrumb">
	    <a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE?>" class="abroad-breadcrumb-link">All Courses</a>
	    <span>&rsaquo;</span>
	    Edit Snapshot Course
	</div>
	<div class="abroad-cms-head">
	    <h1 class="abroad-title">Edit Snapshot Course Details</h1>
	    <div class="last-uploaded-detail">
		    <p><span>Last excel uploaded: </span><?=$lastAddedOn?> by <?=$displayname?><br />
		    *Mandatory</p>
	    </div>
	</div>-->
	<div class="cms-form-wrapper clear-width">
	<div class="clear-width">
	    <h3 class="section-title">Course Details</h3>
	    <div class="cms-form-wrap">
		<ul>
		    <li>
			<label>Country Name* : </label>
			<div class="cms-fields">
			    <select id="country_<?=$formName?>" caption="country" validationType="select" onchange="showErrorMessage(this, '<?=$formName?>'); getUniversitiesDropDownForCountry('<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-select cms-field">
				<option value="">Select a Country</option>
				<?php foreach($abroadCountries as $country)
				{
					$selected = "";
				    $countryId 		= $country->getId();
				    $countryName 	= $country->getName();
				    if($countryId == $courseData['country_id']) {
						$selected = "selected";
				    } ?>
					<option <?php echo $selected;?> value="<?php echo $countryId; ?>"><?php echo $countryName; ?></option>
				<?php } ?>
			    </select>
			    <div style="display: none" class="errorMsg" id="country_<?=$formName?>_error"></div>
			</div>
		    </li>
		    <li>
			<label>University Name* : </label>
			<div class="cms-fields">
			    <select id="university_<?=$formName?>" caption="university" validationType="select" onchange="showErrorMessage(this, '<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-select cms-field">
				<option value="">Select a University</option>
				<option selected value="<?php echo $courseData['university_id']; ?>"><?php echo $universityName; ?></option>
			    </select>
			    <div style="display: none" class="errorMsg" id="university_<?=$formName?>_error"></div>
			</div>
		    </li>
		    <li>
			    <label>Course Exact Name* : </label>
			    <div class="cms-fields">
				    <input id="courseName_<?=$formName?>" value="<?=htmlspecialchars($courseData['course_name'], ENT_QUOTES)?>" validationType="str" caption="course name" onblur="showErrorMessage(this, '<?=$formName?>'); checkAvailabilitySnapshotCourse('<?=$formName?>', 'edit');" maxlength="200" minlength="3" required=true class="universal-txt-field cms-text-field" type="text"/>
				    <span id="courseName_<?=$formName?>_loader" style="display:none;"><img src='/public/images/loader_hpg.gif' height='20px' width='20px' /></span>
				    <div style="display: none" class="errorMsg" id="courseName_<?=$formName?>_error"></div>
			    </div>
		    </li>
		    <li>
			    <label>Course Type* : </label>
			    <div class="cms-fields" style="margin-top:4px;">
				<?php for($i=0; $i < count($courseType); $i++){
					if($courseType[$i]['CourseName'] == $courseData['course_type']) {
						$checked = "checked";
					}
					else{
						$checked = "";
					}?>
					<label style="width: auto;"><input type="radio" name="r1" <?=$checked?> value="<?=$courseType[$i]['CourseName']?>"/> <?=$courseType[$i]['CourseName']?></label>
				<?php } ?>
			    </div>
		    </li>
		    <li>
			    <label>Parent Category* : </label>
			    <div class="cms-fields">
				    <select id="parentCat_<?=$formName?>" caption="parent category" validationType="select" onchange="showErrorMessage(this, '<?=$formName?>'); appendChildCategories('<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-select cms-field">                         
					    <option value="">Select a Category</option>
					    <?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){
						    if($parentCategoryId == $parentCat) {
							$selected = "selected";
						    } else
							$selected = "";
						    ?>
						    <option <?=$selected?> value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
					    <?php } ?>
				    </select>
				    <div style="display: none" class="errorMsg" id="parentCat_<?=$formName?>_error"></div>
			    </div>
		    </li>
		    <li>
			    <label>Child Category* : </label>
			    <div class="cms-fields">
				    <select id="childCat_<?=$formName?>" caption="child category" validationType="select" onchange="showErrorMessage(this, '<?=$formName?>');" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-select cms-field">                         
					    <option value="">Select a Category</option>
				    </select>
				    <div style="display: none" class="errorMsg" id="childCat_<?=$formName?>_error"></div>
			    </div>
		    </li>
		    <li>
			    <label>Course website link* : </label>
			    <div class="cms-fields">
				    <input id="website_<?=$formName?>" value="<?=$courseData['website_link']?>" validationType="link" caption="website" onblur="showErrorMessage(this, '<?=$formName?>');" required=true class="universal-txt-field cms-text-field" type="text"/>                         
				    <div style="display: none" class="errorMsg" id="website_<?=$formName?>_error"></div>
			    </div>
		    </li>
		    <li>
			    <label>User Comments*: </label>
			    <div class="cms-fields">
				    <textarea id="comments_<?=$formName?>" caption="comment" validationType="str" onblur="showErrorMessage(this, '<?=$formName?>');" maxlength="500" minlength="3" required=true class="cms-textarea" style="width:75%;"></textarea>
				    <div style="display: none" class="errorMsg" id="comments_<?=$formName?>_error"></div>
			    </div>
		    </li>
		</ul>
	    </div>
	</div>
	</div>
	<div class="button-wrap">
		<a href="javascript:void(0);" id="submitSnapshot" onclick="disableSubmitAnchor('submitSnapshot'); checkAvailabilitySnapshotCourse('<?=$formName?>', 'edit', true); return;" class="orange-btn">Save & Publish</a>
		<span id="compltForm_<?=$formName?>_loader" style="display:none;"><img src='/public/images/loader_hpg.gif' height='20px' width='20px' /></span>
		<a href="javascript:void(0);" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE?>');" class="cancel-btn">Cancel</a>
	</div>
    </form>
</div>
<div class="clearFix"></div>

<script>
	var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
	var formName = '<?=$formName?>';
	var childCatId = '<?=$courseData['category_id']?>';
	var selectedUniversityId = '<?=$courseData['university_id']?>';
	//var courseNameDB = '<?=$courseData['course_name']?>';
	var courseIdDB = '<?=$courseData['course_id']?>';
	var created = '<?=$courseData['created']?>';
	var createdBy = '<?=$courseData['createdBy']?>';
	var redirectToUrl = '<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE?>';
</script>