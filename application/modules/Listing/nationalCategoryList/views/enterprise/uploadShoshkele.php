<div id="uploadshoshkele" style="display:none;position:absolute;z-index:100001">
		<form enctype="multipart/form-data" onsubmit="Shoshkele.uploadShoshkele(this);"  action="<?php echo site_url().'nationalCategoryList/CategoryProductEnterprise/uploadShoshkele';?>" method="post" name="update_form" autocomplete = "off">
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
						<button class="btn-submit7 w3" value="" type="submit" id="submit">
							<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Upload</p></div>
						</button>
					</div>
					<div class="buttr3">
						<button class="btn-submit5 w3" value="" type="button" onClick="anotheraction =1; hideOverlay();">
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
