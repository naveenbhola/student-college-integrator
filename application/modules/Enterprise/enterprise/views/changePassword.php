<?php $headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
								'js'	=>	array('common','prototype','tooltip'),
								'displayname'=> (isset($validity[0]['displayname'])?$validity[0]['displayname']:""),
								'tabName'	=>	'',
								'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
								);
$this->load->view('enterprise/headerCMS', $headerComponents); ?>
<div style="line-height:10px">&nbsp;</div>
<?php $this->load->view('enterprise/cmsTabs'); ?>
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
			<div style="width:223px; float:left">
				<div class="raised_greenGradient">
					<b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
					<div class="boxcontent_greenGradient1">
						<div class="mar_full_10p">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="fontSize_12p bld">Basic Account Information</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">College Name:</div>
							<div><?php echo $details['businessCollege'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Display Name:</div>
							<div><?php echo $details['displayname'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Login Email ID:</div>
							<div><?php echo $details['email'];?></div>
							<div class="lineSpace_20">&nbsp;</div>
							<div><a href="/enterprise/Enterprise/profile" class="bld">Manage Account Profile</a></div>
							<div class="lineSpace_20">&nbsp;</div>
						</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
			<div style="margin-left:233px">
					<div class="OrgangeFont fontSize_14p bld">Change Password</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div style="float:left; width:100%">
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_lgraynoBG">
									<form action="changePasswordSubmit" id="chgPass" method="POST">
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class="bld txt_align_r fontSize_12p float_L" style="width:191px">Old Password:</div>
											<div class="" style="margin-left:200px"><input type="password" name="oldPassword" id="oldPassword" maxlength="25"/></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="oldPassword_error" style="margin-left:200px"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">New Password:</div>
											<div class="" style="margin-left:200px"><input type="password" name="newPassword" id="newPassword" maxlength="25"/></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="newPassword_error" style="margin-left:200px"></div>
									</div>
									<div class="lineSpace_10">&nbsp;</div>
									<div>
											<div class=" bld txt_align_r fontSize_12p float_L" style="width:191px">Confirm New Password:</div>
											<div class="" style="margin-left:200px"><input type="password" name="conPassword" id="conPassword" maxlength="25"/></div>
									</div>
									<div class="row errorPlace" style="margin-top:2px;">
									    	<div class="float_L">&nbsp;</div>
								            <div class="errorMsg" id="conPassword_error" style="margin-left:200px"></div>
									</div>
									<div class="lineSpace_28">&nbsp;</div>
									<div class="grayLine"></div>
									<div class="lineSpace_10">&nbsp;</div>
									<div class="txt_align_c">
											<button class="btn-submit19" onclick="return validateChgPass();" type="submit" style="width:70px">
													<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px" class="btn-submit20">Save</p></div>
											</button>
									</div>
									</form>
							</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					</div>
			</div>
			<div class="clear_L"></div>
</div>
<div class="lineSpace_35">&nbsp;</div>
<div style="line-height:150px">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
<script>
function validateChgPass()
{
	var val = (getCookie("user"));
	var oldPass = val.split('|');
	var flag = true;
	if (!$j('#oldPassword').val())
	{
		 showError('oldPassword',"Please enter Old password.");
		 flag = false;
	}
	else
	{
		hideError('oldPassword');
	}
	if ($('newPassword').value.length <6)
	{
		showError('newPassword',"Password should be of minimum 6 characters.");
		flag = false;
	  }
  	  else if ($('newPassword').value.length > 25)
	{
		showError('newPassword',"Password should be of maximum 25 characters.");
		flag = false;
	}
	else
	{
		hideError('newPassword');
	}
	if ($('newPassword').value != $('conPassword').value)
	{
		showError('conPassword',"Confirm password and new password does not match.");
		flag = false;
	}
	else
	{
		hideError('conPassword');
	}
	return flag;
}

function showError(eleError,error)
{
	$(eleError +'_error').parentNode.style.display = 'inline';
	$(eleError+'_error').innerHTML = error;
}

function hideError(eleError)
{
	$(eleError +'_error').parentNode.style.display = 'none';
	$(eleError+'_error').innerHTML ="";
}

function getCookie(c_name){
	if (document.cookie.length>0){
		c_start=document.cookie.indexOf(c_name + "=");
	    if (c_start!=-1){
		    c_start=c_start + c_name.length+1;
		    c_end=document.cookie.indexOf(";",c_start);
		    if (c_end==-1) { c_end=document.cookie.length ; }
		    return unescape(document.cookie.substring(c_start,c_end));
	    }
	  }
	return "";
}
</script>
