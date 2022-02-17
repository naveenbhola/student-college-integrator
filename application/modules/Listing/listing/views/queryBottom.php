<?php
	error_log_shiksha("query bottom loading");
	$url = site_url('listing/Listing/requestInfo/seccode3');
	echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateRequestInfoNew(request.responseText);','name' => 'reqInfoNew','id' => 'reqInfoNew'));
?>
<div>
<div class="raised_pink" style="margin-left:10px;"> 
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	<div class="boxcontent_pink" style="background:#FFFFFF">
		<div class="bgRequestInfo bld fontSize_14p" style="line-height:38px">
				<img src="/public/images/sendQueryIcon.jpg" align="absmiddle" style="margin-left:10px;position:relative; top:-3px" /> Want More Information ?
			<!--<img src="/public/images/sendQueryIcon.jpg" align="left" />--><?php //echo $registerText['heading'];?>
		</div>
		<div class="mar_full_10p">
			<div class="lineSpace_12">&nbsp;</div>
			<input type="hidden" name="listing_type" id="listing_type_new"  value="<?php echo $listing_type; ?>"/>
			<input type="hidden" name="listing_type_id" id="listing_type_id_new"  value="<?php echo $type_id; ?>"/>
			<input type="hidden" name="listing_title" id="listing_title_new"  value="<?php echo $details['title']; ?>"/>
			<input type="hidden" name="listing_url" id="listing_url_new"  value="<?php echo site_url($thisUrl); ?>"/>
			<input type="hidden" name="isPaid" id="isPaid_new"  value="<?php echo $registerText['paid']; ?>"/>
            <input type="hidden" name="mailto" id="mailto_new"  value="<?php echo mencrypt($details['contact_email']); ?>"/>
	        
			<div class="lineSpace_12">&nbsp;</div>
			<div class="normaltxt_11p_blk ">
				<div class="float_L" style="width:30%">
					Name:<span class="redcolor">*</span>
					<div><input type="text" name="reqInfoDispName" id="reqInfoDispName_new" value="<?php echo $validateuser[0]['firstname']; ?>" maxlength="25" minlength="3" required="true"  caption="Name" autocomplete="off" validate="validateDisplayName"  /></div>
					<div class="errorPlace"><div class="errorMsg" id="reqInfoDispName_new_error"></div></div>
				</div>

				
				<div class="float_L" style="width:30%">
					<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>	
						Email Id:<span class="redcolor">*</span>
					<?php }?>				
					<?php if(isset($validateuser[0]) && isset($validateuser[0]['cookiestr'])){ ?>
						<div><input type="text" name="reqInfoEmail" id="reqInfoEmail_new" value="<?php $emailArr = explode("|",$validateuser[0]['cookiestr']); echo $emailArr[0]; ?>"  maxlength = "125" validate = "validateEmail" required="true" caption="Email-Id" autocomplete="off"/></div>
					<?php } ?>
					<div class="errorPlace">
						<div class="errorMsg" id="reqInfoEmail_new_error"></div>		
					</div>
				</div>
	

				<div class="float_L" style="width:30%">
					Contact Number:<span class="redcolor">*</span>
					<div class="normaltxt_11p_blk">
						<?php if(isset($validateuser[0]) && isset($validateuser[0]['mobile']) && $validateuser[0]['mobile'] != 0){ ?>
							<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber_new"  value="<?php echo $validateuser[0]['mobile']; ?>"  maxlength = "13" minlength = "5" validate = "validateInteger" required="true"  caption="Contact Number" autocomplete="off"/>
						<?php }else{ ?>
							<input type="text" name="reqInfoPhNumber" id="reqInfoPhNumber_new"  value=""  maxlength = "13" minlength = "5" validate = "validateInteger" required="true"  caption="Contact Number" autocomplete="off"/>
						<?php } ?>
					</div>
					<div class="errorPlace"><div class="errorMsg" id="reqInfoPhNumber_new_error"></div></div>
				</div>		
				<div class="clear_L"></div>
			</div>

		<?php if($registerText['paid'] != "yes"){
		}else{ ?>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="normaltxt_11p_blk">Information Required:<span class="redcolor">*</span></div>

            <div class="normaltxt_11p_blk">
            <textarea id="queryContent_new" name="queryContent"  validate="validateStr" maxlength="1000" minlength="2" onkeyup="textKey(this)" style="width:70%;"  profanity="true" caption="Information Required" autocomplete="off"  required="true" ></textarea><br/>
            </div>
			<div><span id="queryContent_new_counter">0</span> out of 1000 characters</div>
            <div class="errorPlace">
                <div class="errorMsg" id="queryContent_new_error"></div>
            </div>
		<?php } ?>


			<div class="lineSpace_13">&nbsp;</div>
			<div class="normaltxt_11p_blk">Type in the characters you see in the picture below:<span class="redcolor">*</span></div>
			<div class="lineSpace_2">&nbsp;</div>
		  		<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode3" width="100" height="40"  id = "reqinfoCaptacha3"/>
			<div class="lineSpace_5">&nbsp;</div>
			<div class="normaltxt_11p_blk">
			   <input type="text" name = "securityCode1" id = "securityCode2" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" autocomplete="off"/>
			</div>
			<div class="errorPlace">
				<div class="errorMsg" id="securityCode2_error"></div>
			</div>
        <!-- New Fields-->

		<?php if(isset($validateuser[0]) && isset($validateuser[0]['displayname'])){ ?>
			<?php }else{ ?>
		<div class="lineSpace_13">&nbsp;</div>
		<div class="normaltxt_11p_blk lineSpace_15">
			<div>
				<input type="checkbox" name="cAgree" id="cAgree_new" />
				I agree to the Shiksha.com <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a>, <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a> and to receive email and phone support from Shiksha.
			</div>
		</div>
		<div class="errorPlace">
			<div class="errorMsg" id="cAgree_new_error" ></div>
		</div>
        <?php } ?>
		<div class="lineSpace_10">&nbsp;</div>

		<div>
				<button value="" type="submit" onClick="return sendReqInfoNew(this.form);" >
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
</div>
<b class="b4b" style="background:#FFFFFF"></b><b class="b3b" style="background:#FFFFFF"></b><b class="b2b" style="background:#FFFFFF"></b><b class="b1b"></b>
</div>
</div>
</form>
<div class="lineSpace_10">&nbsp;</div>

<script>
function sendReqInfoNew(objForm){
    var flag = validateFields(objForm);
    if(flag != true){
        return false;
    }
    else{
        if(document.getElementById('cAgree')){
            var checkboxAgree = document.getElementById('cAgree_new');
            if(checkboxAgree.checked != true)
            {
                document.getElementById('cAgree_new_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_new_error').parentNode.style.display = 'inline';
                return false;
            }
            else {
                document.getElementById('cAgree_new_error').innerHTML = 'Please agree to Terms & Conditions.';
                document.getElementById('cAgree_new_error').parentNode.style.display = 'none';
                return true;
            }
        }else{
            return true;
        }
    }
}

function updateRequestInfoNew(responseText)
{
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
        document.getElementById('reqInfoEmail_new_error').innerHTML = 'Email Already exists !!';
        document.getElementById('reqInfoEmail_new_error').parentNode.style.display = 'inline';
    }
    else{
        if(document.getElementById('reqinfoCaptacha3')){
            reloadCaptcha('reqinfoCaptacha3','seccode3');	
            if(trim(responseText) == 'code')
            {

                var securityCodeErrorPlace = 'securityCode2_error';
                document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
                document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
            }
            else
            {
                document.getElementById('queryContent_new').value="";
                document.getElementById('securityCode2').value="";
                var divX = document.body.offsetWidth/2 - 150;
                var   divY = screen.height/2 - 200;
                var  h = document.documentElement.scrollTop;
                divY = divY + h;

                var Message = '';
                Message = "You have requested more information. Our representative will get back to you shortly.";
                commonShowConfirmMessage(Message);
            }
        }
    }
}
</script>
