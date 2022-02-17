<div id="imgUploaderPage" class="imgUploadContainer" data-role="page" data-enhance="false">
	<div class="lyrHead">
		<?php 
		if(isMobileRequest()){
		?>
		<a href="javascript:void(0);" class="uploaderBackBtn" data-rel="back">
			<i class="sprite back-icn"></i>
		</a>
		<?php 
		}
		?>
		<span>Edit Photo</span>
		<?php 
		if(!isMobileRequest()){
		?>
		
		<span class="chClose1 closeLyr"></span>
		<?php
		}
		?>
	</div>
	<div class="subSection uploadingChoices">
		<div class="imgUploadOverlay1"></div>
		<ul>
			<li>
				<span class="chPic" slctType="<?php echo isMobileRequest()?'camera':'vidCamera'; ?>"><i class="take_pic"></i>Take Photo</span><span class="chClose"></span></li>
			<li><span class="chPic" slctType="gallery"><i class="show_gallery"></i>Select from Gallery<span></li>
		</ul>
	</div>
	<?php 
	if(!isMobileRequest()){
	?>
	<div class="subSection videoContainer">
		<div class="imgUploadOverlay4"></div>
		<div class="video-container">
			<!--span class="loadingTxt">Loading your camera...</span-->
			<video class="camera_stream"></video>
		</div>
		<div class="actionBtns videoBtns">
			<button class="cancelCapture cancelBtn">Cancel</button>
			<button class="captureMyPhoto btnPrimary">Capture</button>
		</div>
	</div>
	<?php
	}
	?>
	<div class="subSection cropperContainer">
		<div class="imgUploadOverlay3"></div>
		<div class="croppieDiv"></div>
		<div class="actionBtns">
			<button class="changePhoto btnSecondry">Change Photo</button>
			<?php
			if(!isMobileRequest()){
			?>
				<button class="cancelUpload cancelBtn">Cancel</button>
			<?php 
			}
			?>
			<button class="saveMyPhoto btnPrimary">Save</button>
		</div>
	</div>
	<div class="subSection discardLayer">
		<div class="imgUploadOverlay2"></div>
		<div class="innerLyr">
			<span class="close">x</span>
			<p class="discardMsg">All the changes you made will be discarded.</p>
			<div>
				<button class="discard btnPrimary">Ok</button>
				<span class="cancel">Cancel</span>
			</div>
		</div>
	</div>
	<div class="subSection savingLoader">
		<div class="imgUploadOverlay5"></div>
		<img src="<?php echo IMGURL_SECURE; ?>/public/images/shiksha-search-loader.gif" />
	</div>
	<div class="subSection hiddenData">
		<input type="file" name="userTempPhoto[]" class="imageSelector" accept="image/*" />
		<canvas></canvas>
		<img class="photo" />
	</div>
</div>