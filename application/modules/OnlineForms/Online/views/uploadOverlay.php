<div class="uploadLayer" style="display:none;"  id="uploadLayer">
<div class="attachMoreDocBlock">
		<div class="spacer10 clearFix"></div>
        <div id="formsList">
		<input type="hidden" id="documentAttachmentOnForm" value="1" />
		
        <form name="uploadDocuments" id="uploadDocuments">
			<input type="text" class="fieldBoxLarge" value="" id="docTitle" name="docTitle"/> &nbsp;
			<input type="file" class="file" size="30" name="datafile[]"/>&nbsp;
			<input type="button" value="Upload" title="Upload" onClick="fileUpload($('uploadDocuments'),'/studentFormsDashBoard/MyDocuments/upload','upload'); return false;"/>
			<div id="upload"></div>
            <div class="spacer15 clearFix"></div>
			<input type="hidden" value="<?=$onlineFormId?>" name="onlineFormId" id="onlineFormId" />	
        </form>
        </div>
         <ul>
         	<li>
           		<a href="javascript:void(0);" title="+ Add More" onclick="if(typeof(OnlineFormStudentDashboard)!='undefined') {  OnlineFormStudentDashboard.addMoreFields(); }">+ Add More</a>
            </li>
        </ul>
        </div>
</div>