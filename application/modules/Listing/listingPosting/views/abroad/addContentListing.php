<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
	<div class="abroad-breadcrumb article">
		<a href="#" class="abroad-breadcrumb-link">All Article</a>
		<span>&rsaquo;</span>
		Add New Article
	</div>
	
	<div class="abroad-breadcrumb guide" style="display: none">
		<a href="#" class="abroad-breadcrumb-link guide">All Guide</a>
		<span>&rsaquo;</span>
		Add New Guide
	</div>
	
    <div class="abroad-cms-head">
		<h1 class="abroad-title article">Add New Article Details</h1>
		<h1 class="abroad-title guide" style="display: none">Add New Guide Details</h1>
		<div class="last-uploaded-detail">
		    *Mandatory
		</div>
    </div>
    
	<form name="form_content" id="form_content" action="<?=ENT_SA_CMS_PATH?>saveContentListing" enctype="multipart/form-data" method="post">
		<div class="content-type-head clear-width">
			<div class="cms-form-wrap" style="margin:0;">
				<ul>
					<li>
						<label>Content Type* : </label>
						<div class="cms-fields" style="margin-top:6px; font-size:15px;">
							<input type="radio" onclick="loadNewListingType('article');" id="articleRadio" name="contentType" value="article" checked="checked"/> Article
							<input type="radio" onclick="loadNewListingType('guide');" id="guideRadio" name="contentType" value="guide"/> Guide
						</div>
					</li>
				</ul>
			</div>
		</div>
		
		<div class="cms-form-wrapper clear-width">
			<div class="clear-width">
				<h3 class="section-title article">Article Mapping</h3>
				<h3 class="section-title guide" style="display: none">Gudie Mapping</h3>
				<div class="cms-form-wrap" style="margin-bottom:0;">
					<ul>
						<li>
							<label>Country Name : </label>
							
							<div class="cms-fields">
								<div id="countryContDiv">
									<div class="add-more-sec countryDiv">
										<select class="universal-select cms-field" onchange="populateValueArray('country');" name="country[]" id="country_1">                         
											<option value="">Select a Country</option>
											<?php foreach($abroadCountries as $country)
											{
												$countryId = $country->getId();
												$countryName = $country->getName(); ?>
												<option value="<?php echo $countryId; ?>"><?php echo $countryName; ?></option>
											<?php } ?>
										</select>
										
										<a class="remove-link-2" href="javascript:void(0);" style="display:none;" onclick="removeElementChunk(this, 'country', 5);">
											<i class="abroad-cms-sprite remove-icon"></i>Remove Country
										</a>
									</div>
								</div>
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="country_error"></div>
								<a href="javascript:void(0);" id="country_addMore" onclick="addMoreElementChunk('country', 5);">[+] Add Another Country</a>
							</div>
						</li>
						
						<li>
							<label>University Name : </label>
							
							<div class="cms-fields">
								<div id="universityContDiv">
									<div class="add-more-sec universityDiv">
										<select class="universal-select cms-field" name="university[]" id="university_1">                         
											<option value="">Select a University</option>
										</select>
										
										<a class="remove-link-2" href="javascript:void(0);" style="display:none;" onclick="removeElementChunk(this, 'university', 5);">
											<i class="abroad-cms-sprite remove-icon"></i>Remove University
										</a>
									</div>
								</div>
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="university_error">error</div>
								<a href="javascript:void(0);" id="university_addMore" onclick="addMoreElementChunk('university', 5);">[+] Add Another University</a>
							</div>
						</li>
						
						<li>
							<div class="sel-course-title"><strong>Select A Course</strong></div>
							<label>Desired Course : </label>
							<div class="cms-fields" style="margin-top:6px;">
								<?php foreach($desiredCourses as $course)
								{ ?>
									<input type="checkbox" name="desiredCourse[]" value="<?=$course['SpecializationId']?>"/><?=$course['CourseName']?>
								<?php } ?>
							</div>
						</li>
						
						<li>
							<div id="courseContDiv">
								<div class="add-more-sec2 clear-width courseDiv">
									<ul>
										<li>
											<label>Course Type : </label>
											<div class="cms-fields" style="margin-top:6px;">
												<input type="radio" onclick="disableCatDropdown(this);" name="r_1" class="radioCheck" value="none" checked/> None
												<?php foreach($courseType as $type){ ?>
													<input type="radio" onclick="enableCatDropdown(this);" name="r_1" value="<?=$type['CourseName']?>"/> <?=$type['CourseName']?>
												<?php } ?>                  
											</div>
										</li>
										
										<li>
											<label class="categoryText" style="color: gray">Parent Category : </label>
											<div class="cms-fields">
												<select disabled="disabled" class="universal-select cms-field" onchange="populateValueArray('course'); appendChildCategories('content', '', this);" name="parentCat[]" id="parentCat_1">                         
													<option value="">Select a category</option>
													<?php foreach($abroadCategories as $parentCategoryId => $parentCategoryDetails){ ?>
														<option value="<?php echo $parentCategoryId;?>"><?php echo $parentCategoryDetails['name'];?></option>
													<?php } ?>
												</select>
											</div>
										</li>
										
										<li>
											<label class="categoryText" style="color: gray">Subcategory : </label>
											<div class="cms-fields">
												<select disabled="disabled" class="universal-select cms-field" name="subCat[]" id="subCat_1">                         
													<option value="">Select a category</option>
												</select>
											</div>
										</li>
									</ul>
									<a class="remove-link" href="javascript:void(0);" style="display:none;" onclick="removeElementChunk(this, 'course', 5);"><i class="abroad-cms-sprite remove-icon"></i>Remove Course</a>
									<input style="display: none" name="hidden[]" value="1"></input>
								</div>
							</div>
							<div style="display: none; margin-bottom: 5px" class="errorMsg" id="course_error"></div>
							<a href="javascript:void(0);" id="course_addMore" onclick="addMoreElementChunk('course', 5);" class="add-more-link">[+] Add Another Course</a>
						</li>
				   </ul>
				</div>
			</div>
			<!-- -----------------------------------------------------ARTICLE/GUIDE CONTENT---------------------------------------------------- -->
			<div class="clear-width">
				<h3 class="section-title article">Article Content</h3>
				<h3 class="section-title guide" style="display: none">Gudie Content</h3>
				<div class="cms-form-wrap">
					<ul>
						<li>
							<label class="article">Article Title* </label>
							<label class="guide" style="display: none">Guide Title* </label>
							<div class="cms-fields">
								<textarea class="cms-textarea tinymce-textarea" name="title" caption="title" id="title" onblur="showErrorMessage(this);" validationType="html" required="true"></textarea>
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="title_error"></div>
							</div>
						</li>
						
						<li class="article">
							<label>Article Details* </label>
							<div class="cms-fields">
								<textarea class="cms-textarea tinymce-textarea draft" name="articleDetails[]" caption="details" id="article_details" onblur="showErrorMessage(this);" validationType="html" required="true"></textarea>
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="article_details_error"></div>
							</div>
						</li>
						
						<li class="guide cls-guide-section-li" style="display: none">
						<div id = "guide-section-wrapper">
							<div class="add-more-sec add-more-sec2 cls-guide-section clear-width" style="padding-right:10px;">
								<div class="sel-course-title"><strong>Guide Section 1</strong></div>
								<ul>
									<li>
										<label>Section 1 Heading* </label>
										<div class="cms-fields">
											<textarea class="cms-textarea tinymce-textarea guide-heading draft" caption="heading" name = "guide-heading[]" id = "guide-heading1" onblur="showErrorMessage(this);" validationType="html" required="true"></textarea>
											<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide-heading1_error"></div>
										</div>
									</li>
									
									<li>
										<label>Section 1 Details* </label>
										<div class="cms-fields">
											<textarea class="cms-textarea tinymce-textarea guide-detail draft" caption="details" name = "guide-detail[]" id = "guide-detail1" onblur="showErrorMessage(this);" validationType="html" required="true"></textarea>
											<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide-detail1_error"></div>
										</div>
									</li>
								</ul>
								<a href="JavaScript:void(0);" class="remove-link remove-guide-section-link" style="display:none;" onclick="removeGuideSection(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>
							</div>
							</div>
							<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide_section_error"></div>    
							<a class="add-more-link" href="JavaScript:void(0);" onclick = "addGuideSection(this);">[+] Add Another Section</a>
						</li>
						
						<li>
							<label class="article">Article Summary </label>
							<label class="guide" style="display: none">Guide Summary </label>
							<div class="cms-fields">
								<textarea class="cms-textarea tinymce-textarea" name="summary"></textarea>
							</div>
						</li>
						
						<li>
							<label class="article">Article Tags </label>
							<label class="guide" style="display: none">Guide Tags </label>
							<!-- ----------------------------------------------COMMON TAG CONTENT STARTS---------------------------------------------------- -->
							<div class="cms-fields">
								<div id="tagContDiv">
									<div class="add-more-sec tagDiv">
										<select class="universal-select cms-field" name="tag[]" id="tag_1">                         
											<option value="">Select a tag</option>
											<?php foreach($tags as $tag)
											{ ?>
												<option value="<?=$tag['id']?>"><?=$tag['tag_title']?></option>
											<?php } ?>
										</select>
										
										<a class="remove-link-2" href="javascript:void(0);" style="display:none;" onclick="removeElementChunk(this, 'tag', 5);">
											<i class="abroad-cms-sprite remove-icon"></i>Remove Tag
										</a>
									</div>
								</div>
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="tag_error">error</div>
								<a href="javascript:void(0);" id="tag_addMore" onclick="addMoreElementChunk('tag', 5);">[+] Add Another Tag</a>
							</div>
							<!-- -----------------------------------------------COMMON TAG CONTENT ENDS---------------------------------------------------- -->
						</li>
						
						<li class="guide" style="display: none"><div class="sel-course-title"><strong>Downloadable Guide</strong></div></li>
						<li class="add-more-sec2 guide" style="display: none">
							<div class="cms-fields" style="margin-bottom:5px;">
								<input type="checkbox" onclick="enableUploadFile();" name="checkDownload" id="checkDownload" />Downloadable
							</div>
							
							<label>Upload*</label>
							
							<div class="cms-fields">
								<input type="file" class="draft" name="uploadFile[]" id="uploadFile" disabled="disabled" caption="downloadable guide"  onblur="showErrorMessage(this);" onchange="showErrorMessage(this);" validationType="file" />
								<div style="display: none; margin-bottom: 5px" class="errorMsg" id="uploadFile_error"></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="clear-width">
				<h3 class="section-title article">Article Related To</h3>
				<h3 class="section-title guide" style="display: none">Guide Related To</h3>
				<div class="cms-form-wrap">
					<ul>
						<li>
						<label>Exam: </label>
						<div class="cms-fields">
						   <div id="examContDiv">
								<div class="add-more-sec examDiv">
									<select class="universal-select cms-field" name="exam[]" id="exam_1">                         
										<option value="">Select a exam</option>
										<?php foreach($abroadExamsMasterList as $exam)
										{ ?>
											<option value="<?=$exam['examId']?>"><?=$exam['exam']?></option>
										<?php } ?>
									</select>
									
									<a class="remove-link-2" href="javascript:void(0);" style="display:none;" onclick="removeElementChunk(this, 'exam', 5);">
										<i class="abroad-cms-sprite remove-icon"></i>Remove Exam
									</a>
								</div>
							</div>
						    <div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam_error">error</div>
							<a href="javascript:void(0);" id="exam_addMore" onclick="addMoreElementChunk('exam', 5);">[+] Add Another Exam</a>
						</div>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="clear-width">
				<h3 class="section-title">SEO Details</h3>
				<div class="cms-form-wrap">
					<ul>
						<li>
						<label class="article">Article SEO Title : </label>
						<label class="guide" style="display: none">Guide SEO Title : </label>
						<div class="cms-fields">
							<input class="universal-txt-field cms-text-field" name="SEOtitle" type="text"/>
						</div>
						</li>
						<li>
						<label class="article">Article SEO Keywords : </label>
						<label class="guide" style="display: none">Guide SEO Keywords : </label>
						<div class="cms-fields">
							<textarea class="cms-textarea" name="SEOkeywords"></textarea>
						</div>
						</li>
						<li>
						<label class="article">Article SEO Description : </label>
						<label class="guide" style="display: none">Gudie SEO Description : </label>
						<div class="cms-fields">
							<textarea class="cms-textarea" name="SEOdescription"></textarea>
						</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="button-wrap">
			<input style="display: none" name="status" id="status" value="draft"></input>
			<a href="javascript:void(0);" onclick="showHideError('draft');" class="gray-btn">Save as Draft</a>
			<a href="javaScript:void(0);" class="gray-btn">Preview</a>
			<a href="javascript:void(0);" onclick="showHideError('live');" class="orange-btn">Save & Publish</a>
			<a href="javascript:void(0);" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT?>');" class="cancel-btn">Cancel</a>
		</div>
	</form>
</div>
<div class="clearFix"></div>

<script>
	var categoryDetails = eval(<?php echo json_encode($abroadCategories); ?>);
	
	function startCallback() {
        return true;
    }
	
	var saveInitiated = false;
    function completeCallback(response) {
		saveInitiated = false;
        //check response
        var respData;
		if (response["error"] > 0) {
			respData = JSON.parse(response["error"]);
        }
        
        if (typeof respData != 'undefined' && typeof respData.Fail != 'undefined') {
            for (var prop in respData.Fail) {
                switch (prop) {
                    case "uploadFile":
                        $j("#uploadFile_error").html(respData.Fail[prop]).show();
                        break;
                }
            }
        }
        else{
			alert("Content has been saved successfully.");
			window.location.href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_CONTENT?>";
        }
    }
	
    function initFormPosting() {
        AIM.submit(document.getElementById('form_content'), {'onStart' : startCallback, 'onComplete' : completeCallback});
    }
    
    if(document.all) {
        document.body.onload = initFormPosting;
    } else {
        initFormPosting();
    }
</script>