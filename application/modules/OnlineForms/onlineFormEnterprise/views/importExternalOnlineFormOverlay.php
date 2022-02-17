<!--Layer Starts here-->
    <div style="display:none;" id="importExternalOnlineFormLayer">

        <div class="layerContent" id="layerContent">
        <div id="formsList">
        <form name="uploadDocuments" id="uploadDocuments">
	<p>Please Select the data source and give a name to this data source</p>
	<div class="spacer15 clearFix"></div>
                    <input type="hidden" value="" id="courseId" name="courseId">
		    <label style="width: 80px; float: left; text-align: right; padding: 5px 5px 0 0;">Choose File:</label>
		    <input type="file" class="file" name="datafile[]" id="datafile"/>
		    <div class="spacer10 clearFix"></div>
		    <label style="width: 80px; float: left; text-align: right; padding: 5px 5px 0 0">Name:</label>
		    <input  type="text" style="width: 175px; padding: 3px !important " name="docTitle" id="docSource" default="Source Name" value="Source Name" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur');" maxlength="50" minlength="1"/>&nbsp;
		    <script>
			document.getElementById("docTitle").style.color = "#ADA6AD";
		    </script>
                    <div class="spacer15 clearFix"></div>
		    <div style="text-align: center">
		    <span id="button_cancel">
                    <input type="button" id="upload_button" value="Upload" class= "orange-button" style="padding:3px 8px !important" title="Upload" onClick="$('upload_button').disabled='true';fileUploadForOnlineForms($('uploadDocuments'),'/onlineFormEnterprise/OnlineFormEnterprise/uploadExternalOnlineForm','upload');showProgressBar($('courseId').value,$('datafile').value);  return false;"/>
                     &nbsp;&nbsp; <a href="javascript:void(0);" onclick="hideOnlineFormLayer();">Cancel</a>
		     </span>
		     <div class="spacer5 clearFix"></div>
		    <div id="upload" style='text-align:center;'></div>
		    </div>
		    <div class="progressbar" style="width:0%" id="progressbar"></div>
		    <div id="progressPercentage" style='text-align:center;'></div>
		    <div id="finalText" style='text-align:center;'></div>
            <div class="spacer15 clearFix"></div>
        </form>
        </div>
        </div>
    </div>
<!--Layer Ends here-->
