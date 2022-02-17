<div class="connect-form clear-width">
    <div class="form-details">
        <p class="sectionalHeading personal">Personal Details</p>
        <p style="margin-bottom:15px">Students trust a review when it comes from a genuine person. Please give us few basic details about yourself which we will verify.</p>
	    <div style="position:relative;">
            <ul>
                <li class="clear-width">
                  	<div class="flLt">
                   		<label>First Name <span>*</span></label>
                       	<input type="text" class="text-width" value="<?php echo $firstname;?>" id='firstname' name='firstname'  maxlength="50" minlength="2" validate="validateFirstLastNameReviews" caption="First Name" required = "true" />
						<div style="display:none;">
                            <div class="errorMsg" id="firstname_error" style="*float:left; width:280px"></div>
                        </div>  
                    </div>
                    <div class="flRt">
                        <label>Last Name <span>*</span></label>
                       	<input type="text" class="text-width" value="<?php echo $lastname;?>" id='lastname' name='lastname'  maxlength="50" minlength="2" validate="validateFirstLastNameReviews" caption="Last Name" required = "true" />
						<div style="display:none">
                            <div class="errorMsg" id="lastname_error" style="*float:left; width:280px"></div>
                        </div>  
				    </div>
				</li>
				
                <li class="clear-width">
					<div class="flLt">
                        <label>Personal Email ID <span>*</span></label>
                        <?php if($email!=''):?>
    						<input type="text" class="text-width" readonly value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email" validate = "validateEmailReview" required = "true" caption = "email address" maxlength = "125"  />
    					<?php else:?>
    						<input type="text" class="text-width" value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email" validate = "validateEmailReview" required = "true" caption = "email address" maxlength = "125" />
    					<?php endif;?>
    					<div style="display:none;">
                            <div class="errorMsg" id="email_error" style="*float:left"></div>
                        </div>
                    </div>

                    <div class="flRt">
                        <label>Facebook / LinkedIn Profile URL <span>*</span></label>
                        <p style="display:block;">
                            <div class="mbSpace">Please enter the link of your Facebook or LinkedIn profile that carry your name & your college name. Please make sure that the link you submit (Facebook or LinkedIn), is updated with your college & course name, as we will publish your review only after verifying the same.</div>
                            <p>How to get the URL for your Facebook/Linkedin profile:</p>
                            <div class="fnt12">1. Log into Facebook/Linkedin (on a browser), then click on your name (near profile pic)<br>2. Copy the Facebook/Linkedin profile URL in the address bar of your browser<br>3. Paste it in the space provided below</div>
                        </p>
                        <input type="text" placeholder="https://www.facebook.com/abhijit.bh3" class="text-width"  value="<?php echo $facebookURL;?>" name='facebookURL' id='facebookURL' validate="validateReviewFields" minlength="3" maxlength="200" required = "true" caption="Facebook/LinkedIn profile URL" />
                        <div style="display:none;" placeholder="https://www.facebook.com/abhijit.bh3">
                            <div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div>
                        </div>
                    </div>
                </li>
                <li class="clear-width">
                    <div class="flLt">
                        <label>Mobile Number <span>*</span></label>
                        <p style="display:block;">Your phone number will not be shared with anyone. <?php if($pageType == ''){echo "We need it to transfer money in your Paytm wallet linked to your mobile number IF your review gets accepted by us.";}?></p>
                        <div style="width:16%;" class="flLt">                    
                            <select style="width:100%;border-right:none" class="select-width" required="true" validate="validateSelect" caption="ISD Code" id="isdCode" name="isdCode" onchange="changeMobileFieldmaxLength(this.value,'mobile');">
                                <?php foreach($isdCode as $key=>$value){ ?>
                                <option value="<?php echo $key; ?>"  
                                        <?php echo ($countryIdForIsdCode == $key)?"selected":""?> 
                                > <?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                            <div style="display:none;">
                                <div class="errorMsg" id="isdCode_error" style="float:left;width:100%;display:block"></div>
                            </div>
                        </div>
                    
                        <div style="float:left; width:31%;">
                        <!--<label>Mobile Number</label>-->                    
                            <input type="text" class="text-width" value="<?php echo $mobile;?>" id = "mobile" name = "mobile" validate = "validateMobileIntegerReview" maxlength = "10" minlength = "10" caption = "mobile" required="true" style="width:112%;height:41px"/>
                        </div>
                        <div style="display:none;">
                            <div class="errorMsg" id="mobile_error" style="float:left; width:100%;"></div>
                        </div> 
                    </div>
                </li>				    
            </ul>
	    </div>
				
        <?php if($submitButtonPosition == 'bottom'){ ?>
        <a style="margin:20px 0 0 0px !important; height:43px;font-weight: 600" href="javascript:void(0);" class="orange-button continue-btn" onclick="submitReviewForm();" id="reviewSubmitButton"><?php echo $buttonText;?> </a>
        &nbsp;<span id="waitingDiv" style="display:none"><img src='/public/images/working.gif' border=0 align=""></span>
					
		<div class="clear-width" style="margin-top:20px;">
		    <p><a href="#" onclick="$j('#privacy_content').show(); return false;">Are you concerned about Privacy? </a></p>
            <div id="privacy_content" class="privacy-content" style="display:none;">
                <p>Your name will bring credibility to the review you've just written. We recommend you post it like that. However, we do have an option to hide your name in case you're not comfortable.</p><br />
                <p><input type="checkbox" class="flLt" name="anonymous" value="YES" id="anonymousFlag"/> &nbsp;Post as anonymous</p>
            </div>
		</div>
				  
		<div class="clearFix"></div>
		<p style="position: relative;margin-top:20px">Your review will be moderated by our team within 30 days. Once your review is published by our team, you will get a confirmation email<?php if($pageType == ''){echo " & Rs. 100 will be credited to your Paytm account associated with your mobile number in next 30 days. If you don't receive even after successful selection of your review then you can write to us on college.reviews@shiksha.com and we will get back to you as soon as possible";}?>.</p>
		<div class="clearFix"></div>
         
        <?php if($incentiveFlag != 1){
            $incentiveFlag = '0';
        } ?>      
        <input type='hidden' name='incentiveFlag' id="incentiveFlag" value='<?php echo $incentiveFlag;?>'>
				
		<?php } ?>
    </div>
</div>