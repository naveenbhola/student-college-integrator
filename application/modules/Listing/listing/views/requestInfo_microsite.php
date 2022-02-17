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
<div class="mar_full_10p">
		<div class="lineSpace_12">&nbsp;</div>

        <?php if(!isset($validateuser[0]) || !is_array($validateuser[0])) { 
$redirUrl = base64_encode($_SERVER['REQUEST_URI']);
?>
            <div class="normaltxt_11p_blk fontSize_11p float_L" style="width:185px" ><?php echo $registerText['descText']; ?><br/> (Already have Shiksha account then <a href="#" class="fontSize_11p" onClick="showuserLoginIFrame('<?php echo  $redirUrl ;?>'); return false;"> Sign In</a> )</div>
		<?php }elseif(!isset($validateuser[0]['mobile']) || $validateuser[0]['mobile'] ==0 ) { ?>
			<div class="normaltxt_11p_blk fontSize_11p float_L"><?php echo $registerText['descText']; ?></div>
		<?php  } ?>
		<br clear='left' />
		<input type="hidden" name="listing_type" id="listing_type"  value="<?php echo $listing_type; ?>"/>
		<input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php echo $type_id; ?>"/>
		<input type="hidden" name="listing_title" id="listing_title"  value="<?php echo $details['title']; ?>"/>
		<input type="hidden" name="listing_url" id="listing_url"  value="<?php echo site_url($thisUrl); ?>"/>
		<input type="hidden" name="isPaid" id="isPaid"  value="<?php echo $registerText['paid']; ?>"/>
		<input type="hidden" name="mailtoNew" id="mailto"  value="<?php echo mencrypt($details['contact_email']); ?>"/>

		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
				<div class="lineSpace_12">&nbsp;</div>
				<div class="normaltxt_11p_blk float_L" style="width:150px">Name:<span class="redcolor">*</span></div>
				<div class="lineSpace_2">&nbsp;</div>

		<?php }else{ ?>
				<div class="lineSpace_12">&nbsp;</div>
				<div class="normaltxt_11p_blk float_L" style="width:150px">Name:<span class="redcolor">*</span></div>
				<div class="lineSpace_2">&nbsp;</div>
		<?php } ?>

		<div class="normaltxt_11p_blk">
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
					<input type="text" name="reqInfoDispName" id="reqInfoDispName" value="<?php echo $validateuser[0]['firstname']; ?>" maxlength="25" minlength="3"  validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off"/>
			<?php }else{ ?>
					<input type="text" name="reqInfoDispName" id="reqInfoDispName"  value="" maxlength = "25" minlength = "3" validate="validateDisplayName"  required="true"  caption="Name" autocomplete="off"/>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="reqInfoDispName_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>

		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Email Id:<span class="redcolor">*</span></div>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Login Email Id:<span class="redcolor">*</span></div>
		<?php } ?>

		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
			<input type="text" name="reqInfoEmail" id="reqInfoEmail" value="<?php $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0]; ?>"  maxlength = "125" validate = "validateEmail" required="true" caption="Email-Id" autocomplete="off"/>
			<?php }else{ ?>
				<input type="text" name="reqInfoEmail" id="reqInfoEmail"  value=""  maxlength = "125" validate = "validateEmail" required="true"  caption="Email-Id" autocomplete="off"/>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="reqInfoEmail_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>


		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Password:<span class="redcolor">*</span></div>
		<?php } ?>

		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
			<?php }else{ ?>
				<input type="password" name="reqInfoPassword" id="reqInfoPassword"  value=""  maxlength = "20" minlength = "5" validate = "validateStr" required="true"  caption="Password"/>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="reqInfoPassword_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>
		
        <?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Confirm Password:<span class="redcolor">*</span></div>
		<?php } ?>

		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
			<?php }else{ ?>
				<input type="password" name="reqInfoConfirm" id="reqInfoConfirm"  value="" maxlength = "20" validate = "validateStr" required="true"  caption="Confirm Password"/>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="reqInfoConfirm_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>

		<div class="lineSpace_13">&nbsp;</div>
		<div class="normaltxt_11p_blk float_L" style="width:150px">Mobile Number:<span class="redcolor">*</span></div>
		<div class="lineSpace_2">&nbsp;</div>
		<div class="normaltxt_11p_blk">
			<?php if(isset($validateuser[0]) && isset($validateuser[0]['mobile']) && $validateuser[0]['mobile'] != 0){ ?>
				<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber"  value="<?php echo $validateuser[0]['mobile']; ?>"  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile number" autocomplete="off"/>
			<?php }else{ ?>
				<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber"  value=""  maxlength = "10" minlength = "10" validate = "validateMobileInteger" required="true"  caption="mobile number" autocomplete="off"/>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="reqInfoPhNumber_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>

<!-- New Fields-->
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Education Interest:<span class="redcolor">*</span></div>
		<?php } ?>

		<div class="normaltxt_11p_blk">
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
<div id = "ceducationinterest">
<!--<select style = "width:185px" id = "ceducationinterest" name = "ceducationinterest" validate = "validateSelect" required = "true" caption = "education interest">-->
        <select class="normaltxt_11p_blk_verdana" name="board_id" id="board_id" style="width:150px;" tip="course_categories" validate = "validateSelect" required = "true" caption = "education interest">;
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

		<div class="row errorPlace">
			<div class="errorMsg" id="board_id_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>
        
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:170px">Highest Education Level:<span class="redcolor">*</span></div>
		<?php } ?>
		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
				<select style = "width:160px" id = "highesteducationlevel" name = "highesteducationlevel" validate = "validateSelect" required = "true" caption = "highest education level">
				</select>
			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="highesteducationlevel_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>
        
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:150px">Residence Location:<span class="redcolor">*</span></div>
		<?php } ?>
		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
				<select style = "width:85px" id = "countryofresidence1" name = "countryofresidence1" validate = "validateSelect" required = "true" caption = "country" onChange = "getCitiesForCountry('',false,'ofresidence1')">
<!--                    <option value = "">Select Country</option>-->
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
			<?php } ?>
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ 
            
            ?>
				<select style = "width:100px" id = "citiesofresidence1" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "city">
				</select>
                    <script>
                    getCitiesForCountry('',false,'ofresidence1');
                    </script>

			<?php } ?>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="countryofresidence1_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>
		<div class="row errorPlace">
			<div class="errorMsg" id="citiesofresidence1_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>

		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
		<?php }else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
            <div class="normaltxt_11p_blk float_L" style="width:170px">Age:<span class="redcolor">*</span>
			<span style="padding-left:60px;">Gender:</span><span class="redcolor">*</span>
</div>
		<?php } ?>
		<div class="lineSpace_2">&nbsp;</div>
		<div class="normaltxt_11p_blk">
		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
                                                <input id = "YOB" name = "YOB" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age field" style="width:20px" />&nbsp;
<span style = "padding-left:60px;">
<input type="radio" name = "reqgender" id = "Female" value = "Female" />F 
<input type="radio" name = "reqgender" id = "Male" value = "Male" checked = "true"/>M
</span>

			<?php } ?>
		</div>
		<div class="row errorPlace">
			<div class="errorMsg" id="YOB_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>
        

		<?php if($registerText['paid'] != "yes"){
		}else{ ?>
			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:90%">Information Required:<span class="redcolor">*</span></div>

            <div class="normaltxt_11p_blk">
            <textarea id="queryContent" name="queryContent"  validate="validateStr" maxlength="1000" minlength="10"  style="width:95%;" caption="Information Required"  profanity="true"  autocomplete="off" required="true" ></textarea><br/>
            </div>
            <div class="row errorPlace">
                <div class="errorMsg" id="queryContent_error" style="float:left; width:150px"></div>
    			<div class="clear_L"></div>
            </div>

		<?php } ?>


			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk float_L" style="width:170px">Type in the characters you see in the picture below:<span class="redcolor">*</span></div>
		<div class="lineSpace_2">&nbsp;</div>
		  		<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode2" width="100" height="40"  id = "reqinfoCaptacha"/>
		<div class="lineSpace_2">&nbsp;</div>

		<div class="normaltxt_11p_blk">
        <input type="text" name = "securityCode1" id = "securityCode1" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off"/>
		</div>
		<div class="row errorPlace">
			<div class="errorMsg" id="securityCode1_error" style="float:left; width:150px"></div>
			<div class="clear_L"></div>
		</div>


        <!-- New Fields-->

		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
		<div class="lineSpace_13">&nbsp;</div>
		<div class="normaltxt_11p_blk fontSize_10p lineSpace_15" style="width:150px">
			<div class=" float_L">
			<input type="checkbox" name="cAgree" id="cAgree" />
			I agree to the Shiksha.com <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a>, <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a> and to receive email and phone support from Shiksha.
			</div>
			<div class="clear_L"></div>
		</div>

		<div class="row errorPlace">
			<div class="errorMsg" id="cAgree_error" ></div>
		</div>
        <?php } ?>

		<div class="lineSpace_13">&nbsp;</div>
		<div class="lineSpace_5">&nbsp;</div>

		<div>
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
    if(flag != true){
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

