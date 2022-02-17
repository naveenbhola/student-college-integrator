<?php
$postStatus = $_POST['postStatus'];

?>
<div id="updateNameImageOverlay" style="display:<?php if(isset($displayNameDiv)
&& $displayNameDiv == 1) echo "block";else echo "none";?>;">

		<!-- User Display name div -->
		<div id="userNameDisplayDiv" style="display:<?php if(isset($displayNameDiv) && $displayNameDiv == 1) echo "block";else echo "none";?>;">
		  <div class="lineSpace_10">&nbsp;</div>
		  <div style="display:<?php if(isset($displayImageDiv) && $displayImageDiv == 0) echo "none"; ?>">&nbsp;&nbsp;&nbsp;<b>Please provide additional details below to showcase yourself:</b></div>
		  <div class="lineSpace_10">&nbsp;</div>
		  <div class="row">
			  <div class="float_L Fnt11" style="width:120px;text-align:right">Display name:&nbsp;&nbsp;</div>
			  <div class="float_L" style="width:250px">
				  <div class="lineSpace_13" id="displayNameSpan">
					<span id="displayNameValue" class="bld" style="margin-bottom:5px;"><?php echo $displayname; ?></span>
					&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="showDisplayNameDiv();">edit</a>
				  </div>
				  <div class="errorPlace" style="display:none;margin-top:5px;">
					<div class="errorMsg" id="displayNameInput_error"></div>
				  </div>
			  </div>
			  <div class="clear_L">&nbsp;</div>
		  </div>
		  <div class="lineSpace_8">&nbsp;</div>
		  <div class="lineSpace_20" style="padding-left:150px;display:<?php if(isset($displayImageDiv) && $displayImageDiv == 0) echo "block"; else echo "none"; ?>;">
			  <input type="button" onClick="refreshPageForDisplayName();<?php if($postStatus == 'NoDiscussionPosted'){?>discussionNotPostingCookie();<?php } ?>" value="Close" style="border: 0pt none ; background: transparent url(/public/images/entSearch.gif) no-repeat scroll left top; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; width: 71px; height: 20px; color: rgb(255, 255, 255); font-weight: bold;"/>
			  <br clear="left" />
		  </div>

		  <form method="POST" action="/user/MyShiksha/updateUser/1">
			  <input type="hidden" name="attributeName" id="attributeName" value=""/>
			  <input type="hidden" name="attributeValue" id="attributeValue" value=""/>
		  </form>
		</div>

		<!-- User Profile image upload div -->
		<div id="userImageDisplayDiv" style="display:<?php if(isset($displayImageDiv) && $displayImageDiv == 1) echo "block";else if(!(isset($displayImageDiv))) echo "block";else echo "none";?>;">
			<form enctype="multipart/form-data" onsubmit="if(validateUploadImage() !== false) {AIM.submit(this, {'onStart' : startCallback, 'onComplete' : updateMyImage}); <?php if($isrplyAnswerReg == '1') echo 'checkValidAvtarAction()';?>}<?php if($postStatus == 'NoDiscussionPosted'){?>discussionNotPostingCookie();<?php } ?>window.location.reload();"  action="<?php if(!(isset($vcard)))echo site_url().'/user/MyShiksha/uploadFile';else echo site_url().'/user/MyShiksha/uploadFile/1';?>" method="post" name="update_form" id="update_form">
				<div class="row">
					<div style="margin-left:43px">
						<div class="normaltxt_11p_blk lineSpace_20 float_L">Profile Photo:&nbsp;&nbsp;</div>
						<input type="radio" name = "changeavtar" id = "upload" value = "upload" onClick = "opendiv(this.value)" checked/>Upload an Image <span style="margin-left:36px"></span>
						<input type="radio" name = "changeavtar" id = "profilepic" value = "profilepic" onClick = "opendiv(this.value)"/>Select Avatar Image <span style="margin-left:36px"></span>
					</div>
					<div class="lineSpace_8">&nbsp;</div>
				</div>
				<br clear="left" />
				<div class="row" id = "selectpic" style = "display:none">
					<div class="normaltxt_11p_blk bld"></div>
					<div style="margin-left:30px">
						<input type="radio" name = "changeavtar1" id = "profilepic1"
		value = "/public/images/girlav1.gif" checked />
						<img src = "/public/images/girlav1.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic2"
		value = "/public/images/girlav2.gif"/>
						<img src = "/public/images/girlav2.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic4"
		value = "/public/images/girlav4.gif"/>
						<img src = "/public/images/girlav4.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic5"
		value = "/public/images/girlav5.gif"/>
						<img src = "/public/images/girlav5.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic6"
		value = "/public/images/girlav6.gif"/>
						<img src = "/public/images/girlav6.gif">
						<div class="lineSpace_13">&nbsp;</div>
						<input type="radio" name = "changeavtar1" id = "profilepic7"
		value = "/public/images/men3.gif"/>
						<img src = "/public/images/men3.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic8"
		value = "/public/images/men6.gif"/>
						<img src = "/public/images/men6.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic9"
		value = "/public/images/men1.gif"/>
						<img src = "/public/images/men1.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic10"
		value = "/public/images/men4.gif"/>
						<img src = "/public/images/men4.gif">
						<input type="radio" name = "changeavtar1" id = "profilepic11"
		value = "/public/images/men5.gif"/>
						<img src = "/public/images/men5.gif">
						<div class="lineSpace_5">&nbsp;</div>
					</div>
				</div>
				<br clear="left" />
				<div style="margin-left:43px">
						<div class="row" id = "uploadpic" style = "display:block">
							<div class="normaltxt_11p_blk lineSpace_20 float_L">Upload Photo:&nbsp;&nbsp;</div>
							<div><input type="file" name="myImage[]" id="myImage"/></div>
							<br clear="left" />
							<div id="error_myImage" class="normaltxt_11p_blk" style ="display:none;color:red;padding-left:75px"></div>
							<div class="normaltxt_11p_blk" style="padding-left:75px">(Maximum size of 1 MB)</div>
						</div>
						<div class="lineSpace_1">&nbsp;</div>
						<div id="myImageSelection_error" class="normaltxt_11p_blk errorMsg" style = "display:none;"></div>
						<div class="lineSpace_8">&nbsp;</div>
						<div class="lineSpace_20" style="padding-left:75px;">
							<button class="btn-submit7 w3" value="" type="submit" >
								<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Submit</p></div>
							</button>
							<a href="javascript:void(0);" onClick="hideUserNameImageOverlay();checkValidAvtarAction();<?php if($postStatus == 'NoDiscussionPosted'){?>discussionNotPostingCookie();<?php } ?>" id="userNameImageCancel"><?php if(isset($displayNameDiv) && $displayNameDiv ==
							1) echo "Skip & Continue &gt;&gt;";else echo "Cancel";?></a>
							<br clear="left" />
						</div>
				</div>
				<span id="nr" style="display:inline"></span>	
				<input type="hidden" name="typeOfRegistration" value="<?php if(isset($type)&&$type!='') echo $type; ?>" id="typeOfRegistration"/>
				<input type="hidden" name="flagOfRegistration" value="<?php if(isset($flag)&&$flag!='') echo $flag; ?>" id="flagOfRegistration"/>
				<input type="hidden" name="actionOfRegistration" value="<?php if(isset($action)&&$action!='') echo $action; ?>" id="actionOfRegistration"/>
				<input type="hidden" name="onSubmittingImage" value="<?php if(isset($onSubmittingImage) && $onSubmittingImage != undefined) echo $onSubmittingImage; else echo '0'; ?>" id="onSubmittingImage" />
			</form>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
</div>
<script type="text/javascript">
	var knowMoreURL = "<?=SHIKSHA_HOME?>/shikshaHelp/ShikshaHelp/upsInfo";
</script>
