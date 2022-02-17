<div id="examScoreUploadLayer" data-role= "page" style = "background:#fff !important;width:100% !important;" data-enhance="false">
<form id="examScoreUploadForm"  action="/applyHome/ApplyHome/uploadExamScoreCard" enctype="multipart/form-data" method="POST" data-enhance="false">
	<div class="layer-header">
        <a href="javaScript:void(0);" class="back-box" id="uploadLayerBack"><i class="sprite back-icn"></i></a>
        <p>Get a free profile evaluation call</p>
    </div>
    <section class="content-wrap">
        <article class="content-inner">
            <strong class="card-hd">Upload your exam score card</strong>
            <p class="card-caption">This service is only open for students who have given a study abroad exam</p>
            <div class="custom-dropdown exm-div">
                <select class="universal-select" data-enhance="false" id="examScoreDropDown" name="examScoreDropDown">
                	<option value="">Select Exam Given</option>
                <?php foreach ($examMasterList as $key => $value) { ?>
                    <option value="<?php echo $value['examId'];?>"><?php echo $value['exam'];?></option>
                <?php } ?>    
                </select>
                <div class="errorMsg error-msg clearfix" id="examScoreDropDown_error" style="display: none; margin-bottom: 8px; margin-top: 8px;">Please choose exam name before uploading the document</div>
            </div>    
            <div class="cl-btn" data-enhance="false" id="examScoreUploadButton">
            	Upload Score Card
            </div>
            <div id="examScoreFileContainer" style="display: none;">
            <input type='file' id='examScoreFile'>
            </div>
            <div class="clearfix secrty-msg" id="examScoreFile_error" style="margin-bottom: 8px; margin-top: 8px; float: none !important;">Valid formats: Image, PDF, Doc, Zip. Max allowed size 8 MB</div>
            <p class="secrty-msg lock-ico">
                Your documents are securely stored with Shiksha and
                never shared with any 3rd party outside our company.
            </p>
        </article>
    </section>
   </form> 
</div>
<div id="loaderTxt" style="display: none;">
    <div class="cnfm-gryBg"></div>
    <div id="ldtxt"></div>
</div> 
