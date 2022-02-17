<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
        'js'         =>            array('user','tooltip','common','newcommon','listing','utils','cityList','ajax-api'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
	'title'      =>        'SUMS - ',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
	'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>


<div class="mar_full_10p">
    <div style="width:223px; float:left">
        <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	?>
    </div>
    <div style="margin-left:233px">		
        <div class="bld fontSize_16p OrgangeFont" style="padding:10px 0 0 10px;">Add New Client</div>
        <form action="/sums/Manage/addProfile/<?php echo $prodId; ?>" method="post" name="RegistrationForm" id="RegistrationForm" >
            <!--<div class="lineSpace_25">&nbsp;</div>
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1">&nbsp;</div>
                    <div class="r2_2" style="color:#666666">All fields marked with <span class="redcolor">*</span> are Required</div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_25">&nbsp;</div>-->
	    <input type = "hidden" id = "refererreg" value = ""/>
	    <input type = "hidden" id = "resolutionreg" value = ""/>

            <div class="lineSpace_8">&nbsp;</div>
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Sales Executive Name</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Select Name:&nbsp;</div>
                    <div class="r2_2">
                        <select name="executiveName" id="execName" size="4" required="true">
                                <?php foreach ($quoteUsers as $user) {
                                        ?>
                                        <option value="<?php echo $user['userid'];?>" ><?php echo $user['displayname']." : ".$user['BranchName'];?></option> 
                            <?php } ?>
                        </select>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="execName_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>
            <!--New_User_Panel-->
            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Account Information</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>

            <div class="row">
                <div style="display:inline;float:left;width:100%">
                    <div class="r1_1 bld">Login Email Id:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" name="email" id="email" size="30" maxlength="125" minlength="5" blurMethod="checkAvailability(this.value,'email')" caption="Email Id"required="true"/>
                        <!--<a href="#" name="checkavailability" id="checkavailability" class="BlueFont" onClick="checkAvailability(document.getElementById('email').value,'email')" style="text-decoration:underline">Check Availability..</a>-->
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="email_error"></div>
                        <div class="clear_L"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Display Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input name="displayname" id="displayname" type="text" size="30" maxlength="25" minlength="3" blurMethod="checkAvailability(this.value,'displayname')" required="true" caption="Display Name" />
                        <!--<a href="javascript:void(0);" name="checkavailability" id="checkavailability" class="BlueFont" onClick="checkAvailability(document.getElementById('displayname').value,'displayname')" style="text-decoration:underline">Check Availability..</a>-->
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="displayname_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input  name="passwordr" id="passwordr" type="password" size="30"  maxlength="10" minlength="5" validate="validateStr" required="true" caption="Password" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="passwordr_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Confirm Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input   name="confirmpassword" id="confirmpassword" type="password" size="30" maxlength="10" minlength="5" caption="Confirm Password" blurMethod="confirmPasswordEnterprise(this)"  />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="confirmpassword_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Business Information</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">College/Institute/University name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input type="text" id="busiCollegeName" name="busiCollegeName" size="30" validate="validateStr" minlength="3" maxlength="100" caption="College/Institute/University name" required="true">
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="busiCollegeName_error"></div>
                    </div>
                </div>
            </div>

            <div class="lineSpace_13">&nbsp;</div>
            <div class="row">
                <div style="display: inline;float:left; width:100%">
                    
		<div class="r1_1 bld">Category of Courses offered:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
		    <div class="r2_2">
			<div id="c_countries_combo">
			<span style="padding-left:5px"><input checked type="radio" name="siI" onclick="changeCategoryTree(); " id="study_india" value="study_india" style="position:relative;top:2px" /> Study in India</span>
			<span style="padding-left:5px"><input type="radio" onclick="changeCategoryTree();" id="study_abroad" value="study_abroad"  name="siI" style="position:relative;top:2px" /> Study Abroad</span>
			</div>
		    <div style="padding-top:5px" id="c_categories_combo"></div>
		    </div>
		    <div class="clearFix"></div>
			<div class="row errorPlace" style="margin-top:2px;">
			    <div class="r1_1">&nbsp;</div>
			<div class="r2_2 errorMsg" id="c_categories_error"></div>
		    </div>
		</div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="lineSpace_13">&nbsp;</div>
            <div class="row">
                <div style="display: inline;float:left; width:100%">
                    <div class="r1_1 bld">Business Type:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <select type="text" name="busiType" id="busiType" validate="validateSelect" required="true" caption="Business Type" minlength="1" maxlength="101" onchange="checkOtherBusinessType('otherBusiType');">
                            <option value="">Select</option>
                            <option value="College">College</option>
                            <option value="Institute">Institute</option>
                            <option value="University">University</option>
                            <option value="Consultant">Consultant</option>
                            <option value="Tutor">Tutor</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" id="otherBusiType" name="otherBusiType" style="display:none" minlength="2" maxlength="20">
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="busiType_error"></div>
                    </div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="otherBusiType_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>


            <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Contact Information</div>
            <div class="grayLine"></div>
            <div class="lineSpace_20">&nbsp;</div>
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Contact Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input  name="contactName" id="contactName" type="text"  size="30" maxlength="100" minlength="3" validate="validateStr" caption="Contact Name" required="true" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="contactName_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Contact Address:&nbsp;</div>
                    <div class="r2_2">
                        <textarea name="contact_address" id="contact_address" maxlength="500" minlength="3" validate="validateStr" caption="Contact Address"></textarea>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="contact_address_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Country:&nbsp;</div>
                    <div class="r2_2">
                        <select id="country" name="countries" onChange="getCitiesForCountry();" style="width:100px" validate="validateSelect" minlength="1" maxlength="100" caption="Country">
                            <option value="">Select Country</option>
                            <?php
                                foreach($countryList as $country) :
                                $countryId = $country['countryID'];
                                $countryName = $country['countryName'];
                                if($countryId == 1) { continue; }
                                $selected = "";
                                if($countryId == 2) { $selected = "selected='selected'"; }
                            ?>
                            <option value="<?php echo $countryId; ?>" <?php  echo $selected; ?>><?php echo $countryName; ?></option>
                            <?php endforeach; ?>
                        </select>

                        <!--<select style="width:140px" id="countries" name="countries" onChange= "checkCountry('countries','cities','othercountry','othercity');" validate="validateCombo" onblur="addOnBlurValidateUser(this)"></select>-->
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="country_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">City:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <select style="width:140px" id="cities" name="cities" validate="validateSelect" minlength="1" maxlength="100" caption="City" onChange="checkCity(this, 'updateInstitutes');" >
                        </select>
                        <input type="text" validate="validateStr" maxlength="25" required=true  minlength="2" name="otherCity" id="cities_other" value="" style="display:none" caption="City Name"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="cities_error"></div>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="cities_other_error"></div>
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Pin Code:&nbsp;&nbsp;</div>
                    <div class="r2_2">
                        <input name="pincode" type="text" id="pincode" maxlength="10" minlength="5"  size="30" validate="validateInteger" caption="Pin Code" />
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="pincode_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1 bld">Phone No:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
                    <div class="r2_2">
                        <input name="mobile" type="text" id="mobile"  maxlength="15" minlength="4" size="30" validate="validateInteger" caption="Phone Number" required="true"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div class="r2_2 errorMsg" id="mobile_error"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_13">&nbsp;</div>

            <div class="lineSpace_13">&nbsp;</div>
            <!--   <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1">&nbsp;</div>
                    <div class="r2_2 bld">Type in the characters you see in the picture below</div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_18">&nbsp;</div>

            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1">&nbsp;</div>
                    <div class="r2_2">
                        <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>" width="100" height="40"  id="registerCaptacha"/>

                        <input type="text" name="securityCode" id="securityCode" validate="" maxlength="5" minlength="5" required="1"/>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div id="securityCode_error" class="r2_2 errorMsg"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_18">&nbsp;</div>

            <div class="grayLine"></div>
            <div class="lineSpace_18">&nbsp;</div>
            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1">&nbsp;</div>
                    <div class="r2_2 fontSize_12p">
                        <input type="checkbox" id="agree" /> I agree to the
                        <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a>
                    </div>
                    <div class="clear_L"></div>
                    <div class="row errorPlace" style="margin-top:2px;">
                        <div class="r1_1">&nbsp;</div>
                        <div id="agree_error" class="r2_2 errorMsg"></div>
                    </div>
                </div>
            </div>
            <div class="lineSpace_18">&nbsp;</div> -->


            <div class="row">
                <div style="display: inline; float:left; width:100%">
                    <div class="r1_1">&nbsp;</div>
                    <div class="r2_2">
                        <div class="buttr3">
                            <button class="btn-submit7 w21" type="button" id="submitbutton" onclick="validateForm(this.form);">
                                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Create Client Profile</p></div>
                            </button>
                        </div>
                        <?php $redirectLocation = "/sums/Manage/searchUser/".$prodId;
                            if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
                            $redirectLocation = $_SERVER['HTTP_REFERER'];
                        ?>
                        <div class="buttr2">
                            <button class="btn-submit11 w4" value="cancel" type="button" onClick="var status = confirm('Do you really like to Cancel');if(status == true){location.replace('<?php echo $redirectLocation;?>')};" >
                                <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
                            </button>
                        </div>
                        <div class="clear_L"></div>
                    </div>
                    <div class="clear_L"></div>
                </div>
            </div>
            <div class="lineSpace_18">&nbsp;</div>
            <!--New_User_Panel-->
        </form>

        <?php
            $this->load->helper('url');
        ?>
    </div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
</body>
                </html>
<script>


  var completeCategoryTree=eval(<?php echo $completeCategoryTreeIndia; ?>);
   var completeCategoryTreeIndia=eval(<?php echo $completeCategoryTreeIndia; ?>);
   var completeCategoryTreeAbroad=eval(<?php echo $completeCategoryTreeAbroad; ?>);
   getCategories(false);
   
    function changeCategoryTree(){
	if($('study_india').checked == true){
		completeCategoryTree = completeCategoryTreeIndia;
	}else if($('study_abroad').checked == true){
		completeCategoryTree = completeCategoryTreeAbroad;
	}
	getCategories(false);
   }
   
   
   var SITE_URL_HTTPS = "/";
   var SITE_URL = "/";
   addOnFocusToopTip(document.getElementById('RegistrationForm'));
   addOnBlurValidate(document.getElementById('RegistrationForm'));
   fillProfaneWordsBag();
   getCitiesForCountryListAnotherValueName(0);
   function validateForm(obj)
   {
	 document.getElementById('refererreg').value = location.href;
	 document.getElementById('resolutionreg').value = screen.width +'X'+ screen.height;
	 checkAvailabilityEnt(document.getElementById('displayname').value,'displayname');
     checkAvailabilityEnt(document.getElementById('email').value,'email');
   }
   function validateForm1()
   {
	 var obj= document.getElementById('RegistrationForm');
	 var flag =  validateFields(obj);
         var flagE = true;
         var flagSums = madeByChk(); 
	 if (document.getElementById('email_error').innerHTML!="")
	 {
	       document.getElementById('email_error').parentNode.style.display = 'inline';
		flagE = false;
	 }
	 if (document.getElementById('displayname_error')!="")
	 {
	       document.getElementById('displayname_error').parentNode.style.display = 'inline';
	       if (document.getElementById('displayname_error').style.color!="green")
	       flagE = false;
	 }
	 var flagP = confirmPasswordEnterprise(document.getElementById('confirmpassword'));
	 var catFlag = validateCatCombo('c_categories',10,1);
	 /*var flagA = validateAgree();
	 if(flag==true && catFlag==true && flagP==true && flagE==true && flagA==true) */
         if(flag==true && catFlag==true && flagP==true && flagE==true && flagSums==true)
	 {
	       obj.submit();
	 }
	 else
	 {
	       return false;
	 }

   }
/*
   function validateAgree()
   {
	 if (document.getElementById('agree').checked==true)
	 {
	       document.getElementById('agree_error').parentNode.style.display= "none";
	       document.getElementById('agree_error').innerHTML = "";
	       return true;
	 }
	 else
	 {
	       document.getElementById('agree_error').parentNode.style.display= "inline";
	       document.getElementById('agree_error').innerHTML = "Please agree to Terms and Conditions.";
	       return false;
	 }
   }
*/
   function confirmPasswordEnterprise(obj)
   {
	 if (document.getElementById('passwordr').value != obj.value)
	 {
	       document.getElementById('confirmpassword_error').parentNode.style.display = "inline";
	       document.getElementById('confirmpassword_error').innerHTML = "Password and confirmation password do not match.";
	       return false;
	 }
	 else
	 {
	       document.getElementById('confirmpassword_error').parentNode.style.display = "none";
	       document.getElementById('confirmpassword_error').innerHTML = "";
	       return true;
	 }
   }

   function checkAvailabilityEnt(name,type)
   {
	 name = trim(name);
	 document.getElementById(type).value = name;
	 document.getElementById(type + '_error').innerHTML = "";
	 document.getElementById(type + '_error').parentNode.style.display = 'none';
	 if(name == '')
	 {
	       document.getElementById(type + '_error').innerHTML = "Please enter " + type;
	       document.getElementById(type + '_error').parentNode.style.display = 'inline';
	       document.getElementById(type + '_error').style.color = "red";
	       validateForm1();
	       return false;
	 }
	 if(type == "displayname")
	 {
	       if(name.length < 3)
	       {
		     document.getElementById(type + '_error').innerHTML = "Displayname should be in the range of 3-25 characters";
		     document.getElementById(type + '_error').parentNode.style.display = 'inline';
		     document.getElementById(type + '_error').style.color = "red";
		     validateForm1();
		     return false;
	       }
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
				 document.getElementById(type + '_error').innerHTML =  "Another profile with same email id exists.";
				 document.getElementById(type + '_error').style.color = "red";
			   }
			   else{
				 document.getElementById(type + '_error').innerHTML =  "Displayname already exists. Please enter a different name";
				 document.getElementById(type + '_error').style.color = "red";
			   }
			   document.getElementById(type + '_error').parentNode.style.display = 'inline';
		     }
		     else
		     {
			   if(type == "email")
			   {
				 var result = validateEmail(name);
				 if(result == true)
				 {
				       document.getElementById(type + '_error').parentNode.style.display = 'none';
				       document.getElementById(type + '_error').innerHTML = '';
				 }
				 else{
				       document.getElementById(type + '_error').parentNode.style.display = 'inline';
				       document.getElementById(type + '_error').innerHTML = "Please enter a valid email Id";
				       document.getElementById(type + '_error').style.color = "red";
				 }
			   }
			   else{
				 document.getElementById(type + '_error').parentNode.style.display = 'inline';
				 document.getElementById(type + '_error').innerHTML = "Displayname Available";
				 document.getElementById(type + '_error').style.color = "green";
			   }
		     }
		     validateForm1();
	       }
	 };

	 url = SITE_URL_HTTPS+'user/Userregistration/checkAvailability' + '/' + name + '/' + type ;
	 xmlHttp.open("GET",url,true);
	 xmlHttp.send(null);
   }
   
   function madeByChk(){
           if(document.getElementById('execName').value == '')
	{	
                document.getElementById('execName_error').innerHTML = 'Please select the user who is creating this Client Profile.';
                document.getElementById('execName_error').parentNode.style.display = 'inline';
		return false;
            }else{
                document.getElementById('execName_error').innerHTML = '';
                document.getElementById('execName_error').parentNode.style.display = 'none';
                return true;
        }
}
   document.getElementById('email').focus();
   var 	QuickSignUp = 0;
   getCitiesForCountry();
</script>
