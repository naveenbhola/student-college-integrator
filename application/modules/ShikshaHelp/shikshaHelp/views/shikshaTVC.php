<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Spot the Sheep Contest</title>
<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("tvc"); ?>" type="text/css" rel="stylesheet" />
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("header"); ?>"></script>
<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("common"); ?>"></script>
<script>
	function tvc_check(objForm){
		var ansVal;
		validationResponse = validateFields(objForm);
        var returnFlag = true;
        if(validationResponse !== true)
        {
                returnFlag = false;
                return returnFlag;
        }		
	}
</script>
</head>
<body>
<table width="857" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td colspan="2"><img src="/public/images/tvc_img1.gif" width="857" height="399" /></td></tr>
	<tr>
		<td width="87" bgcolor="#EF4150">&nbsp;</td>
		<td align="right">
			<form id="tvc_form" name="tvc_form" action="/enterprise/Enterprise/shiksha_TVCFormPost" method="post">
			<table width="770" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
				<tr>
					<td></td>
					<td align="center"><object width="480" height="390"><param name="movie" value="http://www.youtube-nocookie.com/v/Uasya8yiiSE?fs=1&amp;hl=en_US"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/Uasya8yiiSE?fs=1&amp;hl=en_US" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="480" height="390"></embed></object></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td class="txt1">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width="40">&nbsp;</td>
					<td align="left" class="txt1">Find the number of times you spot the man dressed as a sheep appear in the ad?</td>
					<td width="40">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left">
						<table width="330" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
							<tr><td class="voting"><input type="radio" name="uAnswer" value="7" checked /><strong>7</strong></td></tr>
							<tr><td height="10">&nbsp;</td></tr>
							<tr><td class="voting"><input type="radio" name="uAnswer" value="5" /><strong>5</strong></td></tr>
							<tr><td height="10">&nbsp;</td></tr>
							<tr><td class="voting"><input type="radio" name="uAnswer" value="8" /><strong>8</strong></td></tr>
							<tr><td height="10">&nbsp;</td></tr>
							<tr><td class="voting"><input type="radio" name="uAnswer" value="10" /><strong>10</strong></td></tr>
						</table>
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td height="35">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left" class="txt2">Provide these details to take the quiz<br />
					<span>(These details will be kept confidential)</span></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td align="left">					
					<table border="0" cellspacing="0" cellpadding="0" class="formLabel">
						<tr>
							<td width="142">Full Name:<span class="red">*</span></td>
							<td>
								<div><input type="text" name="uName" id="uName" value="<?php echo $name; ?>" caption="name" required="true" minlength="3" maxlength="25" validate="validateDisplayName" /></div>
								<div style="display:none"><div id="uName_error" class="errorMsg"></div></div>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td height="6" colspan="3">&nbsp;</td></tr>
						<tr>
							<td>Email ID:</td>
							<td>
								<div><input type="text" name="uEmail" id="uEmail" value="<?php echo $email; ?>" maxlength="125" caption="email id" validate="validateEmail" /></div>
								<div style="display:none"><div id="uEmail_error" class="errorMsg"></div></div>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td height="6" colspan="3">&nbsp;</td></tr>
						<tr>
							<td>Mobile no:<span class="red">*</span></td>
							<td>
								<div><input type="text" name="uMobile" id="uMobile" value="<?php echo $mobile; ?>" minlength="10" maxlength="10" caption="mobile" required="true" validate="validateMobileInteger" /></div>
								<div style="display:none"><div id="uMobile_error" class="errorMsg"></div></div>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td height="6" colspan="3">&nbsp;</td></tr>
						<tr>
							<td>City of residence:<span class="red">*</span></td>
							<td>
								<div><input type="text" name="uCity" id="uCity" value="<?php echo $city; ?>" caption="city of residence" required="true" minlength="3" maxlength="25" validate="validateDisplayName" /></div>
								<div style="display:none"><div id="uCity_error" class="errorMsg"></div></div>
							</td>
							<td>&nbsp;</td>
						</tr>
						<tr><td height="6" colspan="3">&nbsp;</td></tr>
						<tr>
							<td>Company Name:</td>
							<td><input type="text" name="uCompany" id="uCompany" /></td>
							<td valign="middle" style="font-size:11px; color:#858585; padding-left:20px;"><span class="red">*</span>Mandatory Fields</td>
						</tr>
						<tr><td height="20" colspan="3"><input type="hidden" id="pageId" name="pageId" value="<?php echo $page; ?>" />&nbsp;</td></tr>
						<tr><td colspan="3" align="right"><input type="submit" value="&nbsp;" id="tvcBtn" onclick="return tvc_check(this.form)" /></td></tr>						
					</table>					
					</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">
						<br /><br />
						<table border="0" cellspacing="0" cellpadding="0" class="formLabel" width="100%" style="font-size:12px">
							<td width="200" align="left"> &nbsp; &nbsp; &nbsp; Hint: <a href="http://www.youtube.com/watch?v=Uasya8yiiSE" target="_blank">Watch the ad now </a></td>
							<td width="200" align="right"><a onclick="return popitup('<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/privacyPolicyTVC')" href="javascript:void(0)">Terms &amp; Conditions</a> &nbsp; &nbsp; &nbsp; </td>
						</table>
					</td>
				</tr>
				<tr><td colspan="3">&nbsp;</td></tr>
			</table>			
			</form>
		</td>
	</tr>
</table>
</body>
</html>
