<?php
if(!empty($content['applyContentDetails'])){
	$description2 = $content['applyContentDetails']['description2'];
}
if(!empty($content['examContentDetails'])){
	$description2 = $content['examContentDetails']['description2'];
}

?>
<div class="clear-width">
	<h3 class="section-title article">Article Content</h3>
	<h3 class="section-title guide" style="display: none">Guide Content</h3>
	<h3 class="section-title examPage" style="display: none">Exam Page Content</h3>
	<h3 class="section-title applyContent" style="display: none">Apply Content</h3>
	<h3 class="section-title examContent" style="display: none">Exam Content</h3>
	<div class="cms-form-wrap">
		<ul>
			<li>
				<label class="article">Article Title* </label>
				<label class="guide" style="display: none">Guide Title* </label>
				<label class="examPage" style="display: none">Exam Page Title* </label>
				<label class="applyContent" style="display: none">Apply Content Title* </label>
				<label class="examContent" style="display: none">Exam Content Title* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea <?php if($examChecked ==''){ echo "tinymce-textarea checkIfEmpty";}?>" name="title" caption="title" id="title" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$content['basic_info']['title']?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="title_error"></div>
				</div>
			</li>
			
			<li class="article">
				<label>Article Details* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="articleDetails[]" caption="details" id="article_details" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$content['contentSection_info'][0]['details']?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="article_details_error"></div>
				</div>
			</li>
			<li class="applyContent examContent" style="display: none">
				<label>Description 1* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="applyContentDesc[]" caption="description 1" id="apply_desc1" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$content['contentSection_info'][0]['details']?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="apply_desc1_error"></div>
				</div>
			</li>
			<li class="applyContent examContent" style="display: none">
				<label>Description 2* </label>
				<div class="cms-fields">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="applyContentDesc[]" caption="description 2" id="apply_desc2" onblur="showErrorMessage(this);" validationType="html" required="true"><?php echo $description2;?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="apply_desc2_error"></div>
				</div>
			</li>
			
			<li class="examPage" style="display: none">
				<label class="examPage" style="display: none">Description*</label>
				<div class="cms-fields" id="exam_desc_cont">
					<textarea class="cms-textarea tinymce-textarea draft checkIfEmpty" name="exam_desc" caption="description" id="exam_desc" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$content['basic_info']['exam_desc']?></textarea>
					<div style="display: none; margin-bottom: 5px" class="errorMsg" id="exam_desc_error"></div>
				</div>
			</li>
			
			<li class="guide cls-guide-section-li" style="display: none">
				<div id = "guide-section-wrapper">
					<?php if(empty($content['contentSection_info'])) { $content['contentSection_info'][0] = ""; }
					foreach($content['contentSection_info'] as $key=>$sectionArr) { ?>
						<div class="add-more-sec add-more-sec2 cls-guide-section clear-width" style="padding-right:10px;">
							<div class="sel-course-title"><strong>Guide Section <?=$key+1?></strong></div>
							<ul>
								<li>
									<label>Section <?=$key+1?> Heading* </label>
									<div class="cms-fields">
										<textarea class="cms-textarea tinymce-textarea guide-heading draft checkIfEmpty" caption="heading" name = "guide-heading[]" id = "guide-heading<?=$key+1?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$sectionArr['heading']?></textarea>
										<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide-heading<?=$key+1?>_error"></div>
									</div>
								</li>
								
								<li>
									<label>Section <?=$key+1?> Details* </label>
									<div class="cms-fields">
										<textarea class="cms-textarea tinymce-textarea guide-detail draft checkIfEmpty" caption="details" name = "guide-detail[]" id = "guide-detail<?=$key+1?>" onblur="showErrorMessage(this);" validationType="html" required="true"><?=$sectionArr['details']?></textarea>
										<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide-detail<?=$key+1?>_error"></div>
									</div>
								</li>
							</ul>
							<a href="JavaScript:void(0);" class="remove-link remove-guide-section-link" <?php if($key == 0){ ?> style="display:none;" <?php } ?> onclick="removeGuideSection(this);setImageContainer();"><i class="abroad-cms-sprite remove-icon"></i>Remove Section</a>
						</div>
					<?php } ?>
				</div>
				<div style="display: none; margin-bottom: 5px" class="errorMsg" id="guide_section_error"></div>    
				<a class="add-more-link" href="JavaScript:void(0);" onclick = "addGuideSection(this);setImageContainer();">[+] Add Another Section</a>
			</li>
			<?php if($content['basic_info']['type']=='examPage' || $action=="add"){?>
			<li class="examPage" style="display: none;">
			<?php  $this->load->view('listingPosting/abroad/contentListingExamPageSection');?>	
			</li>
			<?php } ?>
			<li>
				<label class="article">Article Summary </label>
				<label class="guide" style="display: none">Guide Summary </label>
				<label class="examPage" style="display: none">Exam Page Summary </label>
				<label class="applyContent" style="display: none">Apply Content Summary </label>
				<label class="examContent" style="display: none">Exam Content Summary </label>
				<div class="cms-fields" id="article_summary">
					<textarea class="cms-textarea tinymce-textarea" name="summary"><?=$content['basic_info']['summary']?></textarea>
				</div>
			</li>
			<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentTags");?>
			<li>
				<div id="fakeImageContainer">&nbsp;</div>
			</li>
			<?php $this->load->view("listingPosting/abroad/widgets/content/addEditContentDownloadSection");?>
		</ul>
	</div>
</div>