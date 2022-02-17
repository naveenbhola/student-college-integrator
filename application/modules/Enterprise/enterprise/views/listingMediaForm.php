<?php
	global $coursesAvailable;
	$coursesAvailable = $coursesForInstitute;
	function getCourseComboOptions($selectedCourse = '') {
		global $coursesAvailable;
		$comboOptions = '';
		foreach($coursesAvailable as $course) {
			$courseId = $course['courseID'];
			$courseName = $course['courseName'];
			$selected = $selectedCourse == $courseId ? 'selected'  : '';
			$comboOptions .= '<option value="'. $courseId .'" '. $selected .' >'. $courseName .'</option>';
		}
		return $comboOptions;
	}
?>

  <script>
			var SITE_URL = '<?php echo base_url() ."/";?>';
var BASE_URL = SITE_URL;
	</script>

<body>
<div class="lineSpace_5">&nbsp;</div>
	<select id="coursesList" style="display:none" onchange="associateCourse(this);">
		<?php echo getCourseComboOptions(); ?>	
	</select>

	<div style="margin:0 10px">
		<div style="display:none">
		<div class="row">
			<span style="float:right;padding-top:3px">All field marked with * are compulsory to fill in</span>
			<span class="formHeader">Upload Documents</span>
			<div class="line_1"></div>
		</div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Institues brouchers, Course material, presentation, etc</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already uploaded document</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_documents" filesUploaded="<?php echo 3 + (is_array($documents) ? count($documents) : 0); ?>">
					<?php
						if(is_array($documents))
						foreach($documents as $documentId => $document) {
							$instituteId = '';
							$courseId = '';
							foreach($document['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $document['mediaCaption'];
							$mediaUrl = $document['mediaUrl'];
							$mediaThumbUrl = $document['mediaThumbUrl'];
							$mediaUploadDate = $document['mediaUploadDate'];
							$mediaAssociationDate = $document['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$mediaFileName = $fileNameSplitArray[0];
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>
					<li style="padding-bottom:15px;" id="<?php echo $documentId; ?>_documents"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" /><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $documentId; ?>);" <?php echo $instituteChecked; ?>  original="<?php echo $instituteId; ?>"/> Institute &nbsp; &nbsp; <input type="checkbox" name="course" value="<?php echo $courseId; ?>" onclick="toggleAssociation(this, <?php echo $documentId; ?>);" <?php echo $courseChecked; ?> original="<?php echo $courseId; ?>" /> Course <select id="coursesList_<?php echo $documentId; ?>" onchange="associateCourse(this);" style="display:<?php echo $showCourse; ?>"><?php echo getCourseComboOptions($courseId); ?></select></li>
			
					<?php
						}
					?>
				</ol>
				<div style="line-height:10px">&nbsp;</div>
			</div>
			<div id="documents">
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'documents')">+ Add More</a></div>
			</div>
		</div>
		<div style="line-height:20px">&nbsp;</div>
		</div>
		<div class="formHeader">Upload Photos </div>
		<div class="line_1"></div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Photos of Institues, library, cafetaria, Computer Center, etc</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already uploaded photos</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_photos" filesUploaded="<?php echo 3 + (is_array($photos) ? count($photos) : 0); ?>">
					<?php
						if(is_array($photos))
						foreach($photos as $photoId => $photo) {
							$instituteId = '';
							$courseId = '';
							foreach($photo['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $photo['mediaCaption'];
							$mediaUrl = $photo['mediaUrl'];
							$mediaThumbUrl = $photo['mediaThumbUrl'];
							$mediaUploadDate = $photo['mediaUploadDate'];
							$mediaAssociationDate = $photo['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$mediaFileName = $fileNameSplitArray[0];
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>
					<li style="padding-bottom:15px;" id="<?php echo $photoId; ?>_photos"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" /><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $photoId; ?>);" original="<?php echo $instituteId; ?>" <?php echo $instituteChecked; ?> /> Institute &nbsp; &nbsp; <input type="checkbox" name="course" value="<?php echo $courseId; ?>" onclick="toggleAssociation(this, <?php echo $photoId; ?>);" original="<?php echo $courseId; ?>"  <?php echo $courseChecked; ?>/> Course <select id="coursesList_<?php echo $photoId; ?>" onchange="associateCourse(this);" style="display:<?php echo $showCourse; ?>"><?php echo getCourseComboOptions($courseId); ?></select></li>
					<?php
						}
					?>
				</ol>
				<div style="line-height:10px">&nbsp;</div>
			</div>
			<div id="photos">
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'photos')">+ Add More</a></div>
			</div>
		</div>
		<div style="line-height:20px">&nbsp;</div>

		<div class="formHeader">Attach Videos </div>
		<div class="line_1"></div>
		<div style="line-height:10px">&nbsp;</div>
		<div>Videos of Institutes, library, cafetaria, Computer Center, etc</div>
		<div style="line-height:10px">&nbsp;</div>
		<div style="background:#eff8ff;border:1px solid #d7e8f9;line-height:22px;padding-left:10px;margin:0 50px" class="bld">Already attached videos</div>
		<div style="margin:0 50px">
			<div style="background:#f6f6f6;border:1px solid #e4e3e3;border-top:none;">
				<ol type="1" start="1" style="margin-top:0px;padding-top:10px" id="uploadedMediaPool_videos" filesUploaded="<?php echo 3 + (is_array($videos) ? count($videos) : 0); ?>">
					<?php
						if(is_array($videos))
						foreach($videos as $videoId => $video) {
							$instituteId = '';
							$courseId = '';
							foreach($video['mediaAssociation'] as $association) {
								foreach($association as $associationKey => $associationValue) {
									$associationVar = $associationKey .'Id';
									$$associationVar = $associationValue;
								}
							}
							$instituteChecked = $instituteId === '' ? '' : 'checked';
							$courseChecked = $courseId === '' ? '' : 'checked';
							$showCourse = $courseId === '' ? 'none' : '';

							$mediaName = $video['mediaCaption'];
							$mediaUrl = $video['mediaUrl'];
							$mediaThumbUrl = $video['mediaThumbUrl'];
							$mediaUploadDate = $video['mediaUploadDate'];
							$mediaAssociationDate = $video['mediaAssociationDate'];
							$fileNameSplitArray = split("[/\\.]",$mediaName);
							$fileExtension = $fileNameSplitArray[count($fileNameSplitArray) - 1];
							$mediaFileName = $fileNameSplitArray[0];
							$mediaFileExtension = (count($fileNameSplitArray) > 1) ?  '.'. $fileExtension : '';
					?>
					<li style="padding-bottom:15px;" id="<?php echo $videoId; ?>_videos"><input type="text" value="<?php echo $mediaFileName; ?>" style="display:none" /><input type="hidden" value="<?php echo $mediaFileExtension; ?>" name="extension" /><a href="<?php echo $mediaUrl; ?>" target="_blank" ><?php echo $mediaName; ?></a> [ <a href="javascript:void(0);" onclick="renameMedia(this)" >Rename</a><a href="javascript:void(0);" onclick="saveMedia(this)" style="display:none">Save</a> | <a href="javascript:void(0);" onclick="deleteMedia(this)" >Delete</a> ]<br />Associate with: <input type="checkbox" name="institute" value="<?php echo $listingTypeId; ?>" onclick="toggleAssociation(this, <?php echo $videoId; ?>);" original="<?php echo $instituteId; ?>" <?php echo $instituteChecked; ?> /> Institute &nbsp; &nbsp; <input type="checkbox" name="course" value="<?php echo $courseId; ?>" onclick="toggleAssociation(this, <?php echo $videoId; ?>);" original="<?php echo $courseId; ?>" <?php echo $courseChecked; ?> /> Course <select id="coursesList_<?php echo $videoId; ?>" onchange="associateCourse(this);" style="<?php echo $showCourse; ?>"><?php echo getCourseComboOptions($courseId); ?></select></li>
					<?php
						}
					?>
				</ol>
				<div style="line-height:10px">&nbsp;</div>
			</div>
			<div id="videos">
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div style="padding-bottom:7px"><input type="text" class="inputBorder" style="width:200px" /> <input type="file" class="inputBorder" style="height:25px" size="60" /> <button style="border-bottom:solid 2px #7d7d7d;border-right:solid 2px #7d7d7d;border-top:solid 2px #ccc;border-left:solid 2px #ccc; background:#ccc;" type="button">Upload</button></div>
				<div><a href="javascript:void(0);" onclick="addMoreUploadFields(this.parentNode,'videos')">+ Add More</a></div>
			</div>
		</div>
		<div style="line-height:10px">&nbsp;</div>

		<div style="line-height:9px">&nbsp;</div>
		<form action="/enterprise/ShowForms/getUploadedMediaAssociation" name="courseListing" id="courseListing" method="post" onsubmit="return postMediaWithAssociation();">	
			<div align="center"><input type="submit" value="Submit &amp; Preview" class="btnSubmitted" /> &nbsp; &nbsp; <input type="button" value="Cancel" class="btnCancelled" /> </div>
			<input type="hidden" id="listingType" name="listingType" value="<?php echo $listingType; ?>"/>
			<input type="hidden" id="mediaAssoc" name="mediaAssoc"/>
			<input type="hidden" id="listingId" name="listingId" value="<?php echo $listingTypeId; ?>"/>
		</form>
	</div>

</div>
<script>
assignAjaxUploadToFields();
</script>
