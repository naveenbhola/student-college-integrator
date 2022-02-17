<li class="guide" style="display: none"><div class="sel-course-title"><strong>Downloadable Guide</strong></div></li>
<li class="examPage" style="display: none"><div class="sel-course-title"><strong>Downloadable Exam Page</strong></div></li>
<li class="applyContent" style="display: none"><div class="sel-course-title"><strong>Downloadable Apply Content</strong></div></li>
<li class="examContent" style="display: none"><div class="sel-course-title"><strong>Downloadable Exam Content</strong></div></li>
<li class="add-more-sec2 guide examPage applyContent examContent" id = "downloadableContent" style="display: none">
	<div class="cms-fields" style="margin-bottom:5px;">
		<?php if($content['basic_info']['is_downloadable'] == "yes") {
			$checked = "checked";
		} else {
			$checked = "";
		} ?>
		<input type="checkbox" onclick="enableUploadFile();" <?=$checked?> name="checkDownload" id="checkDownload" />Downloadable
	</div>
	
	<label>Upload*</br><p style="position: relative; left: -7px; font-size: 9px;">(Max upload size 50 MB)</p></label>
	<?php if($content['basic_info']['download_link'] == "") { ?>
		<div class="cms-fields">
			<input type="file" class="draft" name="uploadFile[]" id="uploadFile" disabled="disabled" caption="downloadable guide"  onblur="showErrorMessage(this);" onchange="showErrorMessage(this);" validationType="file" />
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="uploadFile_error"></div>
		</div>
	<?php } else { ?>
		<div class="brochure-link-box" id="fileTextDiv">
			<input class = "universal-txt-field cms-text-field" name="uploadFileText" id="uploadFileText" type = "text" disabled="disabled" value="<?=$content['basic_info']['download_link']?>"/>
			<a href="javascript:void(0);" class="remove-link-2" onclick="removeGuideFile(this);setImageContainer();"><i class="abroad-cms-sprite remove-icon"></i>Remove Brochure</a>
		</div>
		
		<div class="cms-fields" id="uploadFileDiv" style="display: none">
			<input type="file" class="draft" name="uploadFile[]" id="uploadFile" disabled="disabled" caption="downloadable guide"  onblur="showErrorMessage(this);" onchange="showErrorMessage(this);" validationType="file" />
			<div style="display: none; margin-bottom: 5px" class="errorMsg" id="uploadFile_error"></div>
		</div>
	<?php } ?>
</li>