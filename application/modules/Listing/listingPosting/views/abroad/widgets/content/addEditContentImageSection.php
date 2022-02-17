<div style="position:absolute;display:none;" id="blogImagesContainer" class="cms-fields">
	<div>
		<div id="blogImages"></div>
		<form action="/common/uploadImage/sacontent/0" method="post" enctype="multipart/form-data" id="blogImageForm">
			<label class="article">Add an Article image	* </label>
			<label class="guide" style="display: none">Add a Guide image </label>
			<label class="examPage" style="display: none">Add a Exam Page image </label>
			<label class="applyContent" style="display: none">Add an Apply Content image </label>
			<label class="examContent" style="display: none">Add a Exam Content image </label>
			<input type="file" name="shikshaImage[]" id="shikshaImage" onchange="this.form.submit();"/>
			<div style="display:none; margin-bottom: 5px ; margin-left:130px;" class="errorMsg" id="thumbnailImage_error">Please pick atleast one thumbnail image.</div>
		</form>
	</div>
</div>