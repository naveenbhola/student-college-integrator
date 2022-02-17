<div class="abroad-layer" id="examUpload" style="display:none;">
<form id="examScoreUploadForm"  action="/applyHome/ApplyHome/uploadExamScoreCard" enctype="multipart/form-data" method="POST" data-enhance="false">
	
		<div class="msg-content">
                <p class="mb10">
                    <strong class="card-hd bolder">Upload your exam score card.</strong>
                </p>
                <p class="card-caption">Profile evaluation service is only open for students who have given a study abroad exam: IELTS, TOEFL, GRE, GMAT, PTE, SAT </p>
            </div>
			<div class="pp-content upload">
                <div class="cnt-div ">
                    <!--<div class="img">-->
						<span class="custom-dropdown examSel">
                        <select class="upload-opt universal-select" id="examScoreDropDown" name="examScoreDropDown">
							<option value="">Select Exam Given</option>
						<?php foreach ($examMasterList as $key => $value) { ?>
							<option value="<?php echo $value['examId'];?>"><?php echo $value['exam'];?></option>
						<?php } ?>    
						</select>
						</span>
						<button type="button" class="btn-primary btn-upload" id="examScoreUploadButton" >Upload Score Card</button>
                    <!--</div>-->
					<div class="errorMsg error-msg clearfix" id="examScoreDropDown_error" style="display: none; margin-bottom: 8px; margin-top: 8px;">Please choose exam name before uploading the document</div>
                </div>
            </div>
			<div id="examScoreFileContainer" style="display: none;">
				<input type='file' id='examScoreFile'>
            </div>
            <div class="clearfix secrty-msg" id="examScoreFile_error" style="margin-bottom: 8px; margin-top: 8px; float: none !important;">Valid formats: Image, PDF, Doc, Zip. Max allowed size 8 MB</div>
			<div class="btn-content msg">
                <p>
                    <i class="lock-icon cnslr-sprite"></i>
                    Your documents are securely stored with shiksha and never shared with any 3rd party outside of our company.
                </p>
                <!--<div class="align-right">-->
                <!--    <a href="#">Not given an Exam?</a>-->
                <!--</div>-->
            </div>
   </form> 
</div>

