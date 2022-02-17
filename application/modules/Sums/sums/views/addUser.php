<?php
$headerComponents = array(
		'css'			=> array('headerCms','raised_all','mainStyle','footer'),
		'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils'),
		'title'			=> 'SUMS - Add User',
		'product' 		=> '',
		'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
		);
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<style>
.sums_rowbg_b {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;font-weight:bold;margin:0 0 0 10px;}
.sums_rowbg {float:left;width:170px;line-height:15px;background-color:#ccc;padding:5px 5px;margin:0 10px}
.sums_row {float:left;width:170px;line-height:15px;padding:5px 5px;margin:0 0 0 10px;}
</style>
<form method="POST" action="/sums/Manage/addSumsUser">
	    <input type = "hidden" id = "refererreg" value = ""/>
	    <input type = "hidden" id = "resolutionreg" value = ""/>
	<div style="line-height:10px">&nbsp;</div>
	<div class="mar_full_10p">
		<div style="width:223px; float:left">
				<?php 
					$leftPanelViewValue = 'leftPanelFor'.$prodId;
					$this->load->view('sums/'.$leftPanelViewValue); 
				?>
		</div>
		<div style="margin-left:233px">
			<div class="OrgangeFont fontSize_14p bld">Add New User</div>
			<div style="float:left; width:100%">
				<div class="grayLine"></div>
				<div class="lineSpace_10">&nbsp;</div>
				<div>
				</div>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Name:</div>
				<div class="r2" ><input type="text" name="name" id="name" onblur="checkAvailabilitySums(document.getElementById('name').value,'name')"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="name_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Employee Id:</div>
				<div class="r2"><input type="text" name="EmployeeId" id="EmployeeId"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="EmployeeId_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Login Email Id:</div>
				<div class="r2"><input type="text" name="email" id="email" onblur="checkAvailabilitySums(document.getElementById('email').value,'email')"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="email_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Phone Number:</div>
				<div class="r2"><input type="text" name="phoneNumber" id="phoneNumber"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="phoneNumber_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Password:</div>
				<div class="r2"><input type="password" name="password" id="password"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="password_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p float_L">Confirm Password:</div>
				<div class="r2"><input type="password" name="confirmpassword" id="confirmpassword"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="confirmpassword_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">DiscountLimit(%):</div>
				<div class="r2"><input type="text" name="DiscountLimit" id="DiscountLimit"/></div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="DiscountLimit_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row"> 
				<div class="r1 bld txt_align_r fontSize_12p">Role:</div>
				<div class="r2">
					<select name="UserGroupId"  id="UserGroupId" onclick="javascript:loadManagerList()">
						<option value="">Select Role</option>
						<?php for($i=0;$i<count($userGroupList);$i++) { ?>
						<option value="<?php echo $userGroupList[$i]['id'];?>"><?php echo $userGroupList[$i]['userGroupName'];?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="UserGroupId_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row"> 
				<div class="r1 bld txt_align_r fontSize_12p">Branch:</div>
				<div class="r2">
					<select name="BranchId[]" id="BranchId[]" multiple="" onclick="javascript:loadManagerList()">
					<?php for($i=0;$i<count($branchList);$i++) { ?>
					<option value="<?php echo $branchList[$i]['BranchId'];?>"><?php echo $branchList[$i]['BranchName'];?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="BranchId[]_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div class="row">
				<div class="r1 bld txt_align_r fontSize_12p">Manager:</div>
				<div class="r2" id="hiddenVars">
					<select name="ManagerId" id="ManagerId">
						<option value="">Select Manager</option>
						<?php for($i=0;$i<count($managerList);$i++) { ?>
						<option value="<?php echo $managerList[$i]['UserId'];?>"><?php echo $managerList[$i]['ManagerName'];?></option>	    	
						<?php } ?>	    			
					</select>
				</div>
			</div>
			<div class="row errorPlace">
				<div class="r1">&nbsp;</div>
				<div id="ManagerId_error" class="r2 errorMsg"></div>
			</div>
			<div class="clear_L"></div>
			<div class="lineSpace_10">&nbsp;</div>
			<div id="error_div"  class="txt_align" style="display:none;color:#FF0000;padding-bottom:10px;padding-left:20px"></div>
			<div class="txt_align_c">
				<input type="submit" value="Submit" onclick="javascript:return validateFields();" />
			</div>
			<div class="lineSpace_28">&nbsp;</div>
		</div>
	</div>
</form>
<script>

function loadManagerList()
{
	var url = "/sums/Manage/getManagerList";
	var branchId = getSelected(document.getElementById('BranchId[]').options);
	var userGroupId = document.getElementById('UserGroupId').value;
	if((branchId=='') || (userGroupId==''))
	{
		validateFields();
	}
	else
	{
		url=url+'/'+branchId+'/'+userGroupId;
		var divId = "hiddenVars";
		new Ajax.Updater(divId,url);
	}
}

function getSelected(opt)
{
	var selected = '';
	var index = 0;
	for (var intLoop = 0; intLoop < opt.length; intLoop++) {
		if ((opt[intLoop].selected) ||
				(opt[intLoop].checked)) {
			if(selected!='')
			{
				selected = selected+','+opt[intLoop].value;
			}
			else
			{
				selected=opt[intLoop].value;
			}
		}
	}
	return selected;
}

function validateFields()
{
	 document.getElementById('refererreg').value = location.href;
	 document.getElementById('resolutionreg').value = screen.width +'X'+ screen.height;
	var errorMessage='';
	var filterEmail = /^((([a-z]|[A-Z]|[0-9]|\-|_)+(\.([a-z]|[A-Z]|[0-9]|\-|_)+)*)@((((([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.))*([a-z]|[A-Z]|[0-9])([a-z]|[A-Z]|[0-9]|\-){0,61}([a-z]|[A-Z]|[0-9])\.)[\w]{2,4}|(((([0-9]){1,3}\.){3}([0-9]){1,3}))|(\[((([0-9]){1,3}\.){3}([0-9]){1,3})\])))$/;
	var filterInteger = /^(\d)+$/;	
	if(trim(document.getElementById("name").value)=='')
	{
		errorMessage="Please enter the Name";
		showError(errorMessage,"name");
		return false;
	}
	else
	{
		if(!checkAvailabilitySums(document.getElementById('name').value,'name'))
		{
			return false;
		}
		else
		{
			hideError('name');
			if(trim(document.getElementById("EmployeeId").value)=='')
			{
				errorMessage="Please enter the EmployeeId";
				showError(errorMessage,"EmployeeId");
				return false;
			}
			else
			{
				if(!filterInteger.test(trim(document.getElementById("EmployeeId").value)))
				{
					errorMessage= "Please enter the EmployeeId in integer format.";
					showError(errorMessage,"EmployeeId");
					return false;

				}
				else
				{
					hideError('EmployeeId');
					if(trim(document.getElementById("email").value)=='')
					{
						errorMessage="Please enter the email";
						showError(errorMessage,"email");
						return false;
					}
					else
					{
						if(!checkAvailabilitySums(document.getElementById('email').value,'email'))
						{
							return false;
						}
						else
						{
							if(!filterEmail.test(trim(document.getElementById("email").value)))
							{
								errorMessage= "Please enter the email in correct format.";
								showError(errorMessage,"email");
								return false;

							}

							else
							{		
								hideError("email");
								if(trim(document.getElementById("phoneNumber").value)=='')
								{
									errorMessage="Please fill Phone Number";
									showError(errorMessage,"phoneNumber");
									return false;
								}
								else
								{
									if(!filterInteger.test(trim(document.getElementById("phoneNumber").value)))
									{
										errorMessage= "Please enter the phoneNumber in correct format.";
										showError(errorMessage,"phoneNumber");
										return false;

									}

									else
									{	
										hideError("phoneNumber");
										if(trim(document.getElementById("password").value)=='')
										{
											errorMessage="Please enter the  password";
											showError(errorMessage,"password");
											return false;
										}
										else
										{
											hideError("password");
											if(!validatepassandconfirmpass('password','confirmpassword'))
											{
												return false;
											}
											else
											{
												hideError("confirmpassword");
												if(!filterInteger.test(trim(document.getElementById("DiscountLimit").value)))
												{
													errorMessage="Please enter the DiscountLimit in Integer Format";
													showError(errorMessage,"DiscountLimit");
													return false;
												}
												else
												{
													hideError("DiscountLimit");

													if(trim(document.getElementById("UserGroupId").value)=='')
													{
														errorMessage="Please enter the Role";
														showError(errorMessage,"UserGroupId");
														return false;
													}
													else
													{
														hideError("UserGroupId");
														if(trim(document.getElementById("BranchId[]").value)=='')
														{
															errorMessage="Please enter the Branch";
															showError(errorMessage,"BranchId[]");
															return false;
														}
														else
														{
															hideError("BranchId[]");
															if(trim(document.getElementById("ManagerId").value)=='')
															{
																errorMessage="Please enter the Manager";
																showError(errorMessage,"ManagerId");
																return false;
															}
															else
															{
																hideError("ManagerId");								return true;
															}
														}

													}
												}

											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	return true;
}

function showError(errorMsg,type)
{
	var type_error= type + '_error';
	document.getElementById(type_error).innerHTML = errorMsg;
	document.getElementById(type_error).parentNode.style.display = 'inline';
	document.getElementById(type_error).style.color = "red";
}

function hideError(type)
{
	var type_error= type + '_error';
	document.getElementById(type_error).parentNode.style.display='none';
}

function checkAvailabilitySums(name,type)
{
	var type_error = type + '_error';
	var name = trim(name);
	document.getElementById(type).value = name;
	document.getElementById(type_error).innerHTML = "";
	document.getElementById(type_error).parentNode.style.display = 'none';
	var response = true;
	if(type == "name")
		response = validateDisplayName(name,'Display name',25,3);
	if(response != true)
	{

		document.getElementById(type_error).innerHTML = response;
		document.getElementById(type_error).parentNode.style.display = 'inline';
		document.getElementById(type_error).style.color = "red";
		return false;
	}
	if(name == '' && type != "name")
	{
		document.getElementById(type_error).innerHTML = "Please enter " + type;
		document.getElementById(type_error).parentNode.style.display = 'inline';
		document.getElementById(type_error).style.color = "red";
		return false;
	}
	var xmlHttp = getXMLHTTPObject();
	xmlHttp.onreadystatechange=function()
	{

		if(xmlHttp.readyState==4)
		{ 		
			if(trim(xmlHttp.responseText) != "")
			{ 
				var result = eval("eval("+xmlHttp.responseText+")");							
				if(type == "email")
				{
					document.getElementById(type_error).innerHTML =  "Another profile with same email id exists.";
					document.getElementById(type_error).style.color = "red";
				}
				else{
					document.getElementById(type_error).innerHTML =  "Displayname already exists. Please enter a different name";
					document.getElementById(type_error).style.color = "red";
				}
				document.getElementById(type_error).parentNode.style.display = 'inline';
				return false;
			}
			else
			{
				if(type == "email")
				{
					var result = validateEmail(name);
					if(result == true)
					{
						document.getElementById(type_error).parentNode.style.display = 'none';
						document.getElementById(type_error).innerHTML = '';
					}
					else{
						document.getElementById(type_error).parentNode.style.display = 'inline';
						document.getElementById(type_error).innerHTML = "Please enter a valid email Id";
						document.getElementById(type_error).style.color = "red";
						return false;
					}
				}
				else{
					document.getElementById(type_error).parentNode.style.display = 'inline';
					document.getElementById(type_error).innerHTML = "Displayname Available";
					document.getElementById(type_error).style.color = "green";
				}
			}
		}
	};

	if(typeof(SITE_URL_HTTPS) == "undefined")
		SITE_URL_HTTPS = '/';

	if(type=="name")
	{
		type="displayname";
	}  
	url = SITE_URL_HTTPS+'user/Userregistration/checkAvailability' + '/' + name + '/' + type ;
	xmlHttp.open("POST",url,true);
	xmlHttp.send(null);
	return true;

}
</script>

</body>
</html>
