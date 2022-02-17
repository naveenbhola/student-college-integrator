<?php
// $this->load->view('common/calendardiv');
	error_log_shiksha("reqinfo_before.php loading");
	$url = site_url('listing/Listing/requestInfo');
    if(!(isset($leadSubmitAction) && strlen($leadSubmitAction) > 0)){
        $leadSubmitAction= 'javascript:updateRequestInfo(request.responseText);';
    }
	echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => $leadSubmitAction,'name' => 'reqInfo','id' => 'reqInfo'));
?>
<div class="h36 bgRequestInfo normaltxt_11p_blk bld fontSize_14p txt_align_c">
    <?php echo $registerText['heading'];?><!--<img src="/public/images/request_Free_Info.gif" width="57" height="36" align="absmiddle" />Request Free Info-->
</div>
<div class="mar_full_10p normaltxt_11p_blk">
		<div class="lineSpace_12">&nbsp;</div>
		<div>
			<?php if(!isset($validateuser[0]) || !is_array($validateuser[0])) { ?>
			<div class="fontSize_11p" ><?php echo $registerText['descText']; ?> (Already have Shiksha account then <a href="#" class="fontSize_11p" onClick="showuserLoginOverLay(this,'<?php echo $source."_RIGHTPANEL_SIGNIN"?>','refresh'); return false;"> Sign In</a> )</div>
			<?php }elseif(!isset($validateuser[0]['mobile']) || $validateuser[0]['mobile'] ==0 ) { ?>
			<div class="fontSize_11p"><?php echo $registerText['descText']; ?></div>
			<?php  } ?>
		</div>				
		<input type="hidden" name="listing_type" id="listing_type"  value="<?php echo $listing_type; ?>"/>
		<input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php echo $type_id; ?>"/>
		<input type="hidden" name="listing_title" id="listing_title"  value="<?php echo $details['title']; ?>"/>
		<input type="hidden" name="listing_url" id="listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
		<input type="hidden" name="isPaid" id="isPaid"  value="<?php echo $registerText['paid']; ?>"/>
		<input type="hidden" name="mailto" id="mailto"  value="<?php echo mencrypt($details['contact_email']); ?>"/>
		<input type="hidden" name="sourceproductname" id="sourceproductname"  value="<?php echo $source . '_RIGHTPANEL_REQUESTINFO'?>"/>
		<input type="hidden" name="sourceresolution" id="sourceresolution"  value=""/>
		<input type="hidden" name="sourcereferer" id="sourcereferer"  value=""/>
		
		<div>
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
				<div class="lineSpace_12">&nbsp;</div>
				<div>					
					<div class="float_L" style="width:130px"><div class="txt_align_l" style="height:21px">Name:<span class="redcolor">*</span></div></div>
					<div class="float_L" style="width:160px"><div><input type="text" name="reqInfoDispName" id="reqInfoDispName" value="<?php echo $validateuser[0]['firstname']; ?>" maxlength="25" minlength="3"  validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:150px" /></div><div class="rrorPlace"><div class="errorMsg" id="reqInfoDispName_error"></div></div></div>
					<div style="font-size:1px;clear:left">&nbsp;</div>
				</div>
				<div class="lineSpace_2">&nbsp;</div>
			<?php }else{ ?>
				<div class="lineSpace_12">&nbsp;</div>
				<div>
					<div class="float_L" style="width:130px"><div class="txt_align_l" style="height:21px">Name:<span class="redcolor">*</span></div></div>
					<div class="float_L" style="width:160px"><div><input type="text" name="reqInfoDispName" id="reqInfoDispName"  value="" maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off" style="width:150px" /></div><div class="rrorPlace"><div class="errorMsg" id="reqInfoDispName_error"></div></div></div>
					<div style="font-size:1px;clear:left">&nbsp;</div>
				</div>
				<div class="lineSpace_2">&nbsp;</div>
			<?php } ?>
		</div>	
		
		<!--Email/LoginEmailId-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:21px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>				
						Email Id:<span class="redcolor">*</span>
						<?php }else{ ?>
						Login Email Id:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
						<input type="text" name="reqInfoEmail" id="reqInfoEmail" value="<?php $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0]; ?>"  maxlength = "125" validate = "validateEmail" required="true" caption="Email-Id" autocomplete="off"  style="width:150px" />
						<?php }else{ ?>
						<input type="text" name="reqInfoEmail" id="reqInfoEmail"  value=""  maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off" style="width:150px" />
						<?php } ?>					
						</div>
						<div class="errorPlace"><div class="errorMsg" id="reqInfoEmail_error"></div>
					</div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
		
		<!--Password-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:21px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>				
						&nbsp;
						<?php }else{ ?>
						Password:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
						&nbsp;
						<?php }else{ ?>
						<input type="password" name="reqInfoPassword" id="reqInfoPassword"  value=""  maxlength = "20" minlength = "5" validate = "validateStr" required="true"  caption="Password" style="width:150px" />
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="reqInfoPassword_error"></div></div>			
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
		
		<!--Confirm_Password-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:21px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						Confirm Password:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
						&nbsp;
						<?php }else{ ?>
						<input type="password" name="reqInfoConfirm" id="reqInfoConfirm"  value="" maxlength = "20" validate = "validateStr" required="true"  caption="Confirm Password" style="width:150px" />
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="reqInfoConfirm_error"></div></div>					
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
		
		<!--Mobile_Number-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:21px">						
						Mobile Number:<span class="redcolor">*</span>						
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['mobile']) && $validateuser[0]['mobile'] != 0){ ?>
						<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber"  value="<?php echo $validateuser[0]['mobile']; ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile number" autocomplete="off" style="width:150px" />
						<?php }else{ ?>
						<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber"  value=""  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile Number" autocomplete="off" style="width:150px" />
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
			
		<!--Education_Interest-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:17px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						Education Interest:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
							<div id = "ceducationinterest">
								<select class="normaltxt_11p_blk_verdana" name="board_id" id="board_id" style="width:154px;" tip="course_categories" validate = "validateSelect" required = "true" caption = "education interest">
									<option value="">Select Category</option>
									<option value="Study Abroad">Study Abroad</option>
										<?php 	 
											if(isset($subCategories) && is_array($subCategories)) {
											$otherElementId = '';
												foreach($subCategories as $subCategory) {
													$subCategoryId = $subCategory['boardId'];
													$subCategoryName = $subCategory['name'];
													if(strpos($subCategoryName,'Others..') !== false){
														$otherElementId = $subCategoryId ;
														continue;
													}
													if($subCategoryId == $categoryId) {
														 $selected = 'selected';
													} else {
														 $selected = '';
													}
										?>
											<option value="<?php echo $subCategoryId; ?>" <?php echo $selected; ?>><?php echo $subCategoryName; ?></option>													 
										 <?php
												 }
												 if($otherElementId != '') {
													if($otherElementId == $categoryId) {
														 $selected = 'selected'; 
													 } else{
															$selected = '';
													 }
										 ?>														 
											<option value="<?php echo $otherElementId ; ?>" <?php echo $selected ;?>>Others..</option>
										 <?php
												}
											}
										 ?>
									<option value="Undecided">Presently Undecided</option>
							</select>
							</div>						
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="board_id_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
		
		<!--Highest_Education_Level-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:17px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						Highest Education Level:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						<select class="normaltxt_11p_blk_verdana" style = "width:154px" id = "highesteducationlevel" name = "highesteducationlevel" validate = "validateSelect" required = "true" caption = "highest education level"></select>
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="highesteducationlevel_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>
		
		<!--Residence_Location-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:17px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						Residence Location:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						<?php }else{ ?>
						<select class="normaltxt_11p_blk_verdana" style = "width:60px" id = "countryofresidence1" name = "countryofresidence1" validate = "validateSelect" required = "true" caption = "country" onChange = "getCitiesForCountry('',false,'ofresidence1')">
						<?php 
							global $countries; 
							foreach($countries as $countryId => $country) {
									$countryName = isset($country['name']) ? $country['name'] : '';
									$countryValue = isset($country['value']) ? $country['value'] : '';
									$countryId = isset($country['id']) ? $country['id'] : '';
						?>
							<option value = "<?php echo $countryId?>" countryId = "<?php echo $countryValue?>"><?php echo $countryName?></option>
						<?php } ?>
						</select>
						<select class="normaltxt_11p_blk_verdana" style = "width:90px" id = "citiesofresidence1" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "city"></select>
						<script>getCitiesForCountry('',false,'ofresidence1');</script>
						<?php } ?>
					</div>
					<div class="errorPlace"><div class="errorMsg" id="countryofresidence1_error"></div></div>
					<div class="errorPlace"><div class="errorMsg" id="citiesofresidence1_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>

		<!--Age_Gender-->
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:17px">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						Age:<span class="redcolor">*</span>
						<?php } ?>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
						&nbsp;
						<?php }else{ ?>
						<input id = "YOB" name = "YOB" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age field" style="width:20px" /> &nbsp;  &nbsp; Gender: 
						<span>
						<input type="radio" name = "reqgender" id = "reqInfoFemale" value = "Female" />F 
						<input type="radio" name = "reqgender" id = "reqInfoMale" value = "Male"/>M
						</span>
						<?php } ?>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="YOB_error"></div></div>
					<div class="errorPlace"><div class="errorMsg" id="reqinfogender_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>

		<!--Information_Required-->		
		<?php if($registerText['paid'] != "yes"){
		}else{ ?>
		<div class="lineSpace_10">&nbsp;</div>
		<div>			
			<div>					
				<div>Information Required:<span class="redcolor">*</span></div>
				<div>					
					<textarea id="queryContent" name="queryContent"  validate="validateStr" maxlength="1000" minlength="10"  style="width:90%;" caption="Information Required"  profanity="true"  autocomplete="off" required="true" ></textarea>
				</div>
				<div class="errorPlace"><div class="errorMsg" id="queryContent_error"></div></div>
			</div>
		</div>
		<?php } ?>
		
        <div class="lineSpace_10">&nbsp;</div>
		<div>Type in the characters you see in the picture below:<span class="redcolor">*</span></div>							
		
		<!--CodeImg_Text-->
		<div class="lineSpace_12">&nbsp;</div>
		<div>			
			<div>					
				<div class="float_L" style="width:130px">
					<div class="txt_align_l" style="height:40px">
						<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode2" width="100" height="40"  id = "reqinfoCaptacha"/>
					</div>
				</div>
				<div class="float_L" style="width:160px">
					<div>						
						<input type="text" name = "securityCode1" id = "securityCode1" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off"/>					
					</div>
					<div class="errorPlace"><div class="errorMsg" id="securityCode1_error"></div></div>
				</div>
				<div style="font-size:1px;clear:left">&nbsp;</div>
			</div>
		</div>

		<!--I_Agree-->
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_10">&nbsp;</div>
			<div>
			<input type="checkbox" name="cAgree" id="cAgree" />
			I agree to the Shiksha.com <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')" style="font-size:11px">terms of services</a>, <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')" style="font-size:11px">privacy policy</a> and to receive email and phone support from Shiksha.
			</div>
			<div class="row errorPlace"><div class="errorMsg" id="cAgree_error" ></div></div>
		<?php } ?>		

		<div class="lineSpace_10">&nbsp;</div>
		
		<!--Button_-->
		<div align="center">
				<button  value="" type="submit" onClick="return sendReqInfo(this.form);">
					<div class="shikshaEnabledBtn_L">
                       <span class="shikshaEnabledBtn_R"> 
                        <?php 
                          if($registerText['paid'] == "yes"){
                            echo   "Request&nbsp;Info";
                          }else{
                              echo   "Register";
                          }
                         ?>
                        </span>
                    </div>
				</button>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
</div>

</form>

<script>
function sendReqInfo(objForm){
	document.getElementById('sourcereferer').value = location.href;
	document.getElementById('sourceresolution').value = screen.width +'X'+ screen.height;
    if(document.getElementById('reqInfoPassword')){
        if(document.getElementById('reqInfoPassword').value != document.getElementById('reqInfoConfirm').value)
        {

            document.getElementById('reqInfoConfirm_error').innerHTML = 'Password and Confirm Password should be same';
            document.getElementById('reqInfoConfirm_error').parentNode.style.display = 'inline';
            return false;
        }
        else
        {

            document.getElementById('reqInfoPassword_error').innerHTML = '';
            document.getElementById('reqInfoPassword_error').parentNode.style.display = 'none';

        }
    }

    var flag = validateFields(objForm);
    var flag1 = true;
    var flag2 = true;
    if(getCookie('user') == '')
    {
        flag1 = validateGender('reqInfoMale','reqInfoFemale','reqinfogender_error');
    }
        if(trim(document.getElementById("reqInfoPhNumber").value) == "")
        {
            document.getElementById("reqInfoPhNumber_error").innerHTML = "Please enter your correct mobile number";
            document.getElementById("reqInfoPhNumber_error").parentNode.style.display = "inline";
            flag2 = false;
        }
    if(flag != true || flag1 != true || flag2 != true){
        return false;
    }
    else{
        if(document.getElementById('cAgree')){
            var checkboxAgree = document.getElementById('cAgree');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'inline';
                return false;
            }
            else {
                document.getElementById('cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_error').parentNode.style.display = 'none';
                return true;
            }
        }else{
            return true;
        }
    }
}

function updateRequestInfo(responseText)
{
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
        document.getElementById('reqInfoEmail_error').innerHTML = 'Email Already exists !!';
        document.getElementById('reqInfoEmail_error').parentNode.style.display = 'inline';
    }
    else{
        if(document.getElementById('reqinfoCaptacha')){
            reloadCaptcha('reqinfoCaptacha','seccode2');	
            if(trim(responseText) == 'code')
            {

                var securityCodeErrorPlace = 'securityCode1_error';
                document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
                document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
            }
            else
            {
                if(document.getElementById('queryContent')){
                    document.getElementById('queryContent').value="";
                }
                if(document.getElementById('securityCode1')){
                    document.getElementById('securityCode1').value="";
                }
                var divX = document.body.offsetWidth/2 - 150;
                var   divY = screen.height/2 - 200;
                var  h = document.documentElement.scrollTop;
                divY = divY + h;

                 var Message = '';
                 if(document.getElementById('reqInfoPassword')){
                     if(document.getElementById('queryContent')){
                             Message = "Congratulations you have successfully registered on Shiksha.com & requested more information.";
                             showConfirmation(divX,divY,Message);
                     }
                     else{
                         Message = "Congratulations you have successfully registered on Shiksha.com";
                         showConfirmation(divX,divY,Message);
                     }
                 }else{
                     Message = "You have requested more information. Our representative will get back to you shortly.";
                     commonShowConfirmMessage(Message);
                 }
            }
        }else{
            if(document.getElementById('isPaid').value=='no'){
                document.getElementById('reqInfoContainersContainer').innerHTML ='';
            }
            else{
                document.getElementById('reqInfoContainer').innerHTML = responseText;
            }
        }
    }
}
</script>
<?php echo "<script language = \"javascript\">";
if(!isset($validateuser[0]) || !is_array($validateuser[0]))
{
echo "getEducationLevel('highesteducationlevel','',1,'reqInfo');";
}
echo "</script>"?>

