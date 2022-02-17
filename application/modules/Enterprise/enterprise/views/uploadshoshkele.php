<div id="uploadshoshkele" style="display:none;position:absolute;z-index:100001">
		<form enctype="multipart/form-data" onsubmit="if(validateUpload(this) === false){ return false; } else {submissionInProgress = 1;AIM.submit(this, {'onStart' : startCallback, 'onComplete' : updateShoshkeleStatus}); }"  action="<?php echo site_url().'/enterprise/Enterprise/uploadImg';?>" method="post" name="update_form" autocomplete = "off">
        <input id = "clientId" name = "clientId" value = "<?php echo $clientId;?>" type = "hidden"/>
        <input id = "bannerId" name = "bannerId" value = "" type = "hidden"/>
        <input id = "shoshkeyword" name = "shoshkeyword" value = "" type = "hidden"/>
		<div style="margin-left:23px">
			    <div class="lineSpace_10"></div>
				<div class="row">
					<div>
                    Shoshkele Name:&nbsp;&nbsp;&nbsp;
				    <input type="text" name="bannername" id="bannername"  value = "" required = "true" validate = "validateDisplayName" caption = "shoshkele name" minlength = "5" maxlength = "100"/>
                    </div>
					<br clear="left" />
			<div style="margin-top:2px;">
				<div id="bannername_error" class="errorMsg" style="margin-left:110px"></div>
			</div>
					Enter iFrame URL:
                    <input type="text" required="true" name="shoshkeleUrl" id="shoshkeleUrl"/>
					<br clear="left" />
			<div style="margin-top:2px;">
				<div id="myImage_error" class="errorMsg" style="margin-left:110px"></div>
			</div>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="row">
					<div class="buttr3">
						<button class="btn-submit7 w3" value="" type="submit" onclick="if(submissionInProgress == 1) {alert('Form submission in progress');return false;}">
							<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Upload</p></div>
						</button>
					</div>
					<div class="buttr3">
						<button class="btn-submit5 w3" value="" type="button" onClick="hideOverlay();">
							<div class="btn-submit5"><p class="btn-submit6">Cancel</p></div>
						</button>
					</div>
					<br clear="left" />
				</div>
		</div>
		<span id="nr" style="display:inline"></span>	
		</form>
		<div class="lineSpace_10">&nbsp;</div>
</div>
<script>
var submissionInProgress = 0;
function updateShoshkeleStatus(response)
{
    var filter = /^(\d)+$/;
    if(!filter.test(response))
    {
        document.getElementById('myImage_error').innerHTML = response;
        document.getElementById('myImage_error').parentNode.style.display = '';
        submissionInProgress = 0;
        return false;
    }

    validateclient('categorysponsorclientid','banner');
}

function validateUpload(form)
{
    var flagresults = validateFields(form);
    if(validateUrl(trim($j('#shoshkeleUrl').val()), 'URL') !== true || trim($j('#shoshkeleUrl').val()).substr(0,8)!=='https://') {
    	flagresults = false;
    	document.getElementById('myImage_error').innerHTML = 'Enter URL in correct format with HTTPS';
    	document.getElementById('myImage_error').parentNode.style.display = '';
    }
    return flagresults;
}
</script>
