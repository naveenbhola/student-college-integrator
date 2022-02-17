<?php
    $Marks = '';
    $MarksType = '';
    $Name = '';
    $age = '';
    $gender = '';
    $date = '';
    $otherdetails = '';
    if ((count($userCompleteDetails) > 0) && (isset($validateuser[0]['firstname']))) {
        $age = $userCompleteDetails[0]['age'];
        $gender = $userCompleteDetails[0]['gender'];
        foreach ($userCompleteDetails[0]['EducationData'] as $xii_data) {
            if ( $xii_data['Level'] == '12' ) {
                $Marks = $xii_data['Marks'];
                $MarksType = $xii_data['MarksType'];
                $Name = $xii_data['Name'];
                $date = $xii_data['CourseCompletionDate'];
                list($y_c, $m_c, $d_c) = explode('-', $date);
                $date = $y_c;
            }
        }
        $otherdetails = $userCompleteDetails[0]['PrefData'][0]['UserDetail'];
    }
    //echo $Marks .'@'.$MarksType.'@'.$Name .'@'.$age .'@'.$gender.'@'.$date .'@'.$details;
?>
<form method="post" onsubmit="if (validateSignUpMA(document.getElementById('quicksignupForm')) === false ){ return false; } new Ajax.Request('/user/Userregistration/TestuserMarketingPAge',{onSuccess:function(request){javascript:userLoginActionOnRegFormSubmit(request.responseText);}, onFailure:function(request){javascript:userLoginActionOnRegFormSubmit(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" id = "quicksignupForm" name = "quicksignupForm">
<input type = "hidden" name = "resolution" id = "resolution" value = ""/>
<input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
<input type = "hidden" name = "referer" id = "referer" value = ""/>
<input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
<input type = "hidden" name = "flag_marketing_overlay" value = "true"/>
<input type = "hidden" name = "flag_prefId" id="flag_prefId" value = "<?php echo $prefId; ?>"/>
<?php  if ($local_course_flag != "on") { ?>
<input type = "hidden" name = "mpagename" id = "mpagename" value = "marketingPage"/>
<?php } ?>
<div style="width:665px;background:#000000">
    <div style="width:662px;background:#FFFFFF">
        <div style="border:1px solid #d5e4fb">
			<!--Start_OverlayTitle-->
			<div style="width:100%">
				<div style="background:#6391CC;height:30px">
					<div style="width:100%">
						<div class="bld fontSize_14p float_L" style="width:90%;line-height:30px">
							<div style="width:100%"><span style="padding-left:10px;color:#FFF">Congratulation your details have been successfully submitted</span></div>
						</div>
						<div class="float_L" style="width:9%;height:30px;">
							<div style="width:100%;line-height:30px" class="txt_align_r"><span style="padding-right:10px;position:relative;top:3px;"><img onclick="userLoginActionOnCancle();" src="/public/images/crossImg_14_12.gif" style="cursor:pointer"/></span></div>
						</div>
						<div style="clear:left;line-height:1px;font-size:1px;overflow:hidden">&nbsp;</div>
					</div>
				</div>
			</div>
			<!--End_OverlayTitle-->

			<!--Start_OverlayText-->
			<div style="width:100%">
				<div style="padding:10px" class="fontSize_12p">
                    <div class="bld" style="padding-top:10px">Hi <span class="OrgangeFont  bld" id="display_username" ></span>, please provide additional details below to complete your profile and get maximum benefits.</div>
				</div>
			</div>
            <!--End_OverlayText-->

            <!--Start_LeftRight_Panel-->
			<div style="width:100%">
                <!--Start_LeftForm-->
				<div class="float_L" style="width:385px">
				<div style="width:100%">
				<div style="border-right:1px solid #e6e6e6">
				<div style="padding-right:10px">
				<div style="width:100%">
				<?php if (count($SpecializationList) > 0) { ?>
						<div id="specializationid_block" style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Desired Specialization:</div>
							</div>
                            <div class="formFieldTextRight">
								<div style="width:100%">
                                    <div id="subCategory1" style="width:200px;border:1px solid #CCC;padding:5px 0;height:55px;overflow:auto">
                                        <?php foreach($SpecializationList as $key => $value): ?>
                                        <label style="display:block;padding-left:5px;width:94%; float:left;"><span
style="float:left;width:18px"><input style="margin:0" type="checkbox" name="specializationId[]" value="<?php echo
$value['SpecializationId']; ?>"></span> <strong style="float:left;font-weight:normal; width:155px;
line-height:normal;"><?php echo $value['SpecializationName']; ?></strong></label>
                                        <?php endforeach; ?>
                                    </div>
									<div class="errorPlace" style=""><div id="homesubCategories_error" class="errorMsg"></div></div>
								</div>
							</div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>
                        <?php } ?>
                        <div style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Login Email Id:&nbsp;</div>
							</div>
                            <div class="formFieldTextRight">
								<div style="width:100%">
									<label id="display_email"></label>
								</div>
								<div><input type = "hidden" name = "quickemail" id = "quickemail_id" value = ""/></div>
							</div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>

                        <div id='flag_new_pwd1' style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">New Password:&nbsp;</div>
							</div>
                            <div class="formFieldTextRight">
								<div style="width:100%">
									<div><input type="password" id = "quickpassword" name = "quickpassword" validate = "validateStr" minlength = "6" maxlength = "12"  caption = "password"  style="width:170px" /></div>
									<div class="errorPlace" style=""><div id= "quickpassword_error" class="errorMsg"></div></div>
								</div>
							</div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>

                        <div id='flag_new_pwd2' style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Confirm Password:&nbsp;</div>
							</div>
                            <div class="formFieldTextRight">
								<div style="width:100%">
									<div><input id = "quickconfirmpassword" name = "quickconfirmpassword" type="password" minlength = "6" maxlength = "12" validate = "validateStr" caption = "password again" onblur = "validatepassandconfirmpass1('quickpassword','quickconfirmpassword');"  style="width:170px" /></div><div class="errorPlace" style="display:block;line-height:14px"></div>
									<div class="errorPlace" style=""><div id= "quickconfirmpassword_error" class="errorMsg"></div></div>
								</div>
							</div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>

						<div style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Age:&nbsp;</div>
							</div>
							<div class="formFieldTextRight" style="width:210px">
								<div style="width:100%">
									<div><input id = "quickage" name = "quickage" type="text" minlength = "2" maxlength = "2" value="<?php echo $age; ?>" validate = "validateAge" caption = "age" style="width:20px" /> &nbsp; &nbsp; <span style="font-size:11px">Gender <input type="radio" <?php if ($gender == 'Female') { echo "checked";} ?> name = "quickgender" id = "Female" value = "Female" /> Female <input <?php if ($gender == 'Male') { echo "checked";} ?>  type="radio" name = "quickgender" id = "Male" value = "Male"/> Male </span></div>
									<div class="errorPlace" style=""><div id= "quickage_error" class="errorMsg"></div></div>
									<div class="errorPlace" style=""><div id= "quickgender_error" class="errorMsg"></div></div>
								</div>
                            </div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>

                        <div style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Std. XII Stream:&nbsp;</div>
							</div>
                            <div class="formFieldTextRight" style="width:210px">
								<div style="width:100%">
									<div><input name="science_stream" <?php if ($Name == 'science') { echo "checked"; } ?> id="science_stream" type="radio" value="science_stream" />Science&nbsp;<input name="science_stream"
                                    <?php if ($Name == 'arts') { echo "checked"; } ?> id="science_arts" type="radio" value="science_arts"  />Arts&nbsp;<input name="science_stream" <?php if ($Name == 'commerce') { echo "checked"; } ?> id="science_commerce" type="radio" value="science_commerce"  />Commerce</div>
									<div class="errorPlace" style=""><div id= "science_commerce_error" class="errorMsg"></div></div>
									<div class="errorPlace" style=""><div id= "science_arts_error" class="errorMsg"></div></div>
									<div class="errorPlace" style=""><div id= "science_stream_error" class="errorMsg"></div></div>
								</div>
                            </div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>

                        <div style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Std. XII Details:&nbsp;</div>
							</div>
							<div class="formFieldTextRight">
								<div style="width:100%">
									<div>
										<select  validate = "validateSelect" caption = "year" id="10_com_year_year" id="10_com_year_year" name="10_com_year_year">
										<option value="">Year</option>
										<?php
											for($i= (date("Y")+1); $i >= 1990; $i--) {
                                                if ($i == $date) {
                                                    echo "<option  selected value='$i'>".$i."</option>";
                                                } else {
												    echo "<option value='$i'>".$i."</option>";
                                                }
											}
										?>
										</select>
										<select style="font-size:11px;width:100px" name = "10_ug_detials_courses_marks" validate = "validateSelect" caption = "marks" id="10_ug_detials_courses_marks"><option value="">Marks</option>
										<?php
											for ($i = 100; $i >= 33; $i--) {
                                                if ($Marks == $i) {
                                                    echo "<option selected value='".$i."'>" . $i . "%</option>";
                                                } else {
												    echo "<option value='".$i."'>" . $i . "%</option>";
                                                }
											}
										?>
										</select>
									</div>
									<div class="errorPlace" style=""><div id= "10_com_year_year_error" class="errorMsg"></div></div>
									<div class="errorPlace" style=""><div id= "10_ug_detials_courses_marks_error" class="errorMsg"></div></div>
								</div>
                            </div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>
						<?php //if ($local_course_flag != "on") {  ?>
                        <div style="width:100%">
                            <div class="formFieldTextLeft">
								<div style="width:100%" class="txt_align_r">Other Details:&nbsp;</div>
							</div>
                            <div class="formFieldTextRight">
								<div style="width:100%">
									<div><textarea blurMethod="checkTextElementOnTransition(this,'blur');" onfocus="checkTextElementOnTransition(this,'focus');" style="width: 205px; height: 40px;color:#ada6ad;" name="otherdetails" id="otherdetails" validate="validateStr" default="Specify any other detail about your course and institute preference." maxlength="500" tip="listing_add_tag" caption="other details"><?php echo (($otherdetails  == '')?'Specify any other detail about your course and institute preference.':$otherdetails ); ?></textarea></div>
									<div class="errorPlace" style=""><div id= "otherdetails_error" class="errorMsg"></div></div>
								</div>
                            </div>
                            <div class="formFieldTextClear">&nbsp;</div>
                        </div>
						<?php // } ?>
						<div id="wikki_parant_CYO" style="width:100%">
							<div id="main_container_0">
								<div style="background:#f2f2f2;margin-left:15px;padding:8px 0">
									<div style="width:100%">
										<div class="formFieldTextLeft" style="width:105px;">
											<div style="width:100%" class="txt_align_r">Other Exams Taken:&nbsp;</div>
										</div>
										<div class="formFieldTextRight" style="width:210px">
											<div style="width:100%">
												<div style="padding-bottom:8px">
													<select id="other_exam_name0" name="other_exam_name[]" style="width:150px">
														<option value="">Name</option>
														<?php echo $other_exm_list; ?>
													</select> &nbsp;
                                                    <input id="other_exam_marks0" name="other_exam_marks[]" type="text" style="width:40px">
												</div>
                                                <div class="errorPlace" style=""><div id= "otherdetails_error" class="errorMsg"></div></div>
											</div>
										</div>
										<div class="formFieldTextClear">&nbsp;</div>
									</div>
									<!--<div style="width:100%">
										<a onclick="removewikkicontent(0);" href="javascript:void(0);" style="font-size:12px;margin-left:10px;" class="closedocument"><b>Remove</b></a>
									</div>-->
								</div>
							</div>
						</div>
						<div id="add_multiple_wikki_content"></div>
						<div class="lineSpace_5">&nbsp;</div>
						<div style="width:100%" class="txt_align_c"><a id="addwikkicontent_flag" onclick="addwikkicontent(true);" href="javascript:void(0);" class="plusSign"><b>Add Another Competitive Exams</b></a></div>
				</div>
                </div>
				</div>
				</div>
				</div>
				<!--End_LeftForm-->

				<!--Start_RightPanel-->
                <div class="float_L" style="width:270px">
					<div style="width:100%">
						<div style="padding:0 10px">
							<div style="width:100%">
								<div class="OrgangeFont" style="font-size:18px;padding-bottom:10px">Why Complete your profile?</div>
								<div class="orgSmallBullet"><span class="fontSize_14p bld">Get Contacted:</span> Let institutes contact you directly basis your preference</div>
								<div class="orgSmallBullet"><span class="fontSize_14p bld">Customized Advice:</span> Get personalized expert career counseling</div>
								<div class="orgSmallBullet"><span class="fontSize_14p bld">Free Alerts:</span> Get alerts for all important dates and events of your interest</div>
								<div style="padding-top:15px">Registering at <b>Shiksha.com</b> is like offloading all your worries with a trusted friend and guide.<br>Try it and feel the difference!</div>
							</div>
						</div>
					</div>
                </div>
				<!--End_RightPanel-->
                <div class="clear_L withClear">&nbsp;</div>
            </div>
			<!--End_LeftRight_Panel-->
			<div class="lineSpace_10">&nbsp;</div>

			<!--Start_PrivacyPolicy-->
			<div style="width:100%">
				<div style="margin:0 60px">
					<div style="width:100%">
						<div><span class="bld" style="font-size:11px">Privacy Setting</span></div>
						<div style="font-size:11px">
							<div>
								<span style="position: relative; top: 2px;"><input type="checkbox" value="mobile" name="viamobile" checked id="viamobile"/></span> Contact me on mobile for education products
								<span style="position: relative; top: 2px;;padding-left:30px"><input checked type="checkbox" value="email" name="viaemail" id="viaemail"/></span> Contact me via email for education products
							</div>
							<div>
								<span style="position: relative; top: 2px;"><input checked type="checkbox" value="newsletteremail" name="newsletteremail" id="newsletteremail"/></span> Send me Shiksha Newsletter email
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--End_PrivacyPolicy-->
			<div class="lineSpace_10">&nbsp;</div>

			<div style="width:100%">
				<div align="center">
					<div class="errorPlace" style=""><div id = "quickerror" class="errorMsg" style="padding-left:10px;" ></div></div>
				</div>
			</div>

			<div class="lineSpace_10">&nbsp;</div>
			<!--Start_Button-->
			<div style="width:100%">
				<div align="center">
					<input type="Submit" class="btnMarketingOverlaySub" value="Submit" style="font-size:16px;font-weight:bold" uniqueattr="MarkeingPageLayer2Submit"/> &nbsp;
					<input type="button" class="btnMarketingOverlayCan"  style="font-size:16px;font-weight:bold" onclick = "return userLoginActionOnCancle();" value="Cancel" />
				</div>
			</div>
			<!--End_Button-->

			<div class="lineSpace_10">&nbsp;</div>
        </div>
    </div>
    <div class="lineSpace_3">&nbsp;</div>
</div>
</form>
<?php echo conversionTrackingCode(); ?>
<script type="text/javascript">
	/* Google conversion code start */
	var ifm = document.createElement("iframe");
        ifm.setAttribute("src", "/public/conversion/conversion.html");
        ifm.setAttribute("height",0);
        ifm.setAttribute("width",0);
        ifm.setAttribute("border",0);
        document.body.appendChild(ifm);
	/* Google conversion code end */
    var noOfwikkicontents = 1;
    function removewikkicontent(no)
    {
        var id = "main_container_"+no;
        deleteElement(document.getElementById(id));
        noOfwikkicontents--;
        if (noOfwikkicontents <= 4 ) {
            document.getElementById('addwikkicontent_flag').style.display = '';
        }
    }
    function addwikkicontent(flagEdit) {
        if (noOfwikkicontents > 4 ) {
            document.getElementById('addwikkicontent_flag').style.display = 'none';
        }
        var height_overlay = document.getElementById('DHTMLSuite_modalBox_transparentDiv').style.height;
        document.getElementById('DHTMLSuite_modalBox_transparentDiv').style.height = ( parseInt(height_overlay) + 400 ) + 'px';
        noOfwikkicontents++;
        no = noOfwikkicontents;
        var newdiv = document.createElement('div');
        str = '<div style="background:#f2f2f2;margin-left:15px;padding:8px 0;margin-top:10px">\
                <div class="formFieldTextLeft" style="width:105px;"><div style="width:100%" class="txt_align_r">Other Exams Taken:&nbsp;</div></div>\
                <div class="formFieldTextRight" style="width:210px">\
                    <div style="width:100%">\
                        <div style="padding-bottom:8px"><select id="other_exam_name'+no+'"\
                            name="other_exam_name[]" style="width:150px">\
                                <option value="">Name</option>\
                                <?php echo $other_exm_list; ?>
                            </select>\
                            &nbsp;\
                            <input type="text" name="other_exam_marks[]"\ id="other_exam_marks'+no+'" style="width:40px">\
                        </div>\
                        <div class="errorPlace" style="display:block;line-height:14px">\
                            <div id= "otherdetails_error" class="errorMsg"></div>\
                        </div>\
                    </div>\
                </div>\
                <div class="formFieldTextClear">&nbsp;</div>\
                <div style="width:100%"><a onclick="removewikkicontent('+no+');" href="javascript:void(0);" style="font-size:12px;margin-left:10px;" class="closedocument"><b>\ Remove</a></b></div>\
            </div>';
        newdiv.innerHTML = str;
        newdiv.setAttribute('id','main_container_'+no);
        document.getElementById('add_multiple_wikki_content').appendChild(newdiv);
    }
    function validatepassandconfirmpass1(passwordid,confirmpasswordid)
    {
        if (($(passwordid).value == '')&& ($(confirmpasswordid).value =='')) { return true;}
        if($(passwordid).value != $(confirmpasswordid).value)
        {
            if($(confirmpasswordid).value != '')
            {
                $(confirmpasswordid + '_error').innerHTML = "Password and confirm password should match";
                $(confirmpasswordid + '_error').parentNode.style.display = 'inline';
                return false;
            }
            else
            {
                $(confirmpasswordid + '_error').innerHTML = "Please enter the password again";
                $(confirmpasswordid + '_error').parentNode.style.display = 'inline';
                return false;
            }
        }
        else
        {
            $(confirmpasswordid + '_error').innerHTML = "";
            $(confirmpasswordid + '_error').parentNode.style.display = 'none';
            return true;
        }
    }
    function validateSignUpMA(objForm)
    {
        document.getElementById('quickerror').parentNode.style.display = 'none';
        document.getElementById('quickerror').innerHTML = "" ;
        var response  = validateFields(objForm);
        var response2 =  validatepassandconfirmpass1('quickpassword','quickconfirmpassword');
        if((response !== true) || (response2 !== true))
        {
                document.getElementById('quickerror').parentNode.style.display = 'inline';
                document.getElementById('quickerror').innerHTML = "Please correct the errors marked in red to successfully register on shiksha.com." ;
                return false;
        }
    }

    function userLoginActionOnRegFormSubmit(resp)
    {
        if (trim(resp) == 'update') {
            userLoginActionOnCancle();
            return false;
        }
    }

    function userLoginActionOnCancle()
    {
        closeMessage();
        var action = document.getElementById('loginactionreg').value;
        window.location = base64_decode(action);
        return false;
    }

    function deleteElement(objElement)
    {
        var p,e,i;
        if (objElement)
            e=objElement;
        else
            return;
        if (e.childNodes)
        {
            var len = e.childNodes.length;
            for(i=0;i<len;i++)
            deleteElement(e.firstChild);
        }
        p = e.parentNode;
        if (p)
            p.removeChild(e)
        delete e;
    }
    document.getElementById('display_username').innerHTML = global_display_username;
    document.getElementById('display_email').innerHTML = truncateString(global_display_emailid,40);
    document.getElementById('display_email').setAttribute("title",global_display_emailid);
    document.getElementById('quickemail_id').value = global_display_emailid;
    var flagfirsttime = document.getElementById("flagfirsttime").value;
    if (flagfirsttime != 'true') {
        document.getElementById('flag_new_pwd1').style.display = 'none';
        document.getElementById('flag_new_pwd2').style.display = 'none';
    }
    addOnBlurValidate(document.getElementById('quicksignupForm'));
</script>
<!-- Google Code for Content-MBA Page 2 Remarketing List -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "666666";
var google_conversion_label = "8LQPCMLI6wEQkty89gM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1053765138/?label=8LQPCMLI6wEQkty89gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php if($istr == 6 && $desiredCourseName == 24) { ?>
<!-- Google Code for Distance MBA Page-2 -->
<!-- Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. For instructions on adding this tag and more information on the above requirements, read the setup guide: google.com/ads/remarketingsetup -->
<!--
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1053765138;
var google_conversion_label = "YC9eCPKY1QMQkty89gM";
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/1053765138/?value=0&amp;label=YC9eCPKY1QMQkty89gM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
-->
<?php } ?>
