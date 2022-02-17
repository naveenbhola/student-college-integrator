<?php 	if($pageType=='campusRep'){?>
    <div id="personalInfo" style="display: none;">
<?php 	}else{?>
	<div id="personalInfo" class="<?php echo (($pageType=='campusRep')?"":"college-review-info")?>" style="display: none;">
<?php 	} ?>

	<div class="clearfix para-list college-review-info" style="font-size:11px; color:#8c8c8c">
        <span>Students trust a review when it comes from a genuine person. Please give us few basic details about yourself which we will verify.</span>
    </div>

    <?php 	if($pageType=='campusRep'){?>
    	<ol class="form-item clearfix ol-list reset-styles">
	<?php 	}else{?>
	    <ol class="form-item clearfix ol-list reset-styles" style="margin-top:55px; list-style:none;">
	<?php 	} ?>

	<li>
        <label class="li-label">First Name<span class="star-r">*</span></label>
        <div><input style="padding:8px 4px 8px 6px" type="text" value="<?php echo $firstname;?>" id='firstname' name='firstname' maxlength="50" minlength="2"></div>
        <div style="display:none;"><div class="errorMsg" id="firstname_error" style="*float:left"></div></div>
	</li>
	<li>
        <label class="li-label">Last Name <span class="star-r">*</span></label>
        <div><input style="padding:8px 4px 8px 6px" type="text" value="<?php echo $lastname;?>" id='lastname' name='lastname' maxlength="50" minlength="2"></div>
        <div style="display:none;"><div class="errorMsg" id="lastname_error" style="*float:left"></div></div>
    </li>
    <li>
        <label class="li-label">Personal Email ID <span class="star-r">*</span></label>
        <?php if($email!=''):?>
        <div><input style="padding:8px 4px 8px 6px" type="text" readonly value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email"></div></li>
        <?php else:?>
        <div><input style="padding:8px 4px 8px 6px" type="text" value="<?php echo htmlspecialchars($email);?>" id = "email" name = "email"  ></div>
        <?php endif;?>
        <div style="display:none;"><div class="errorMsg" id="email_error" style="*float:left"></div></div>
    </li>
   <!-- <li>
        <div style="color: #646464;font-size: 12px;">
        <p>Give your review a clear identity & credibility. Share your LinkedIn profile URL OR Facebook profile URL OR Mobile no.</p>
        </div>
    </li> -->
    <li>
     	<label class="li-label">Facebook / LinkedIn Profile URL <span class="star-r">*</span></label>
    	<div><input style="padding:8px 4px 8px 6px" type="text" required="true" value="<?php echo $facebookURL;?>" name='facebookURL' id='facebookURL' vaidate="validateStr" placeholder="https://www.facebook.com/abhijit.bh3"></div>
    	<div style="display:none;"><div class="errorMsg" id="facebookURL_error" style="*margin-left:3px;"></div></div>
    	<div class="clearfix" style="font-size:11px; color:#8c8c8c">
        	<span class="flLt">Please enter the link of your Facebook or LinkedIn profile that carry your name & your college name. Please make sure that the link you submit (Facebook or LinkedIn), is updated with your college & course name, as we will publish your review only after verifying the same.<br><br><b>How to get the URL for your Facebook/Linkedin profile:</b><br>1. Log into Facebook/Linkedin (on a browser), then click on your name (near profile pic)<br>2. Copy the Facebook/Linkedin profile URL in the address bar of your browser<br>3. Paste it in the space provided above</span>
        </div>
    </li>
    <li>
	    <label class="li-label">Mobile Number<span class="star-r">*</span></label>
	    <p class="mobile-p">Your phone number will not be shared with anyone<?php echo (($pageType=='campusRep')?"":".We need it to transfer money in your Paytm wallet linked to your mobile number IF your review gets accepted by us.")?></p>
	    <div class="<?php echo ($pageType=='campusRep'?"n0-b":"")?>" style="width:34%; float:left">
	    	<select validate="validateSelect" caption="ISD Code" id="isdCode" name="isdCode" onchange="changeMobileFieldmaxLength(this.value);">
				<?php foreach($isdCode as $key=>$value){ ?>
				<option value="<?php echo $key; ?>"
				    <?php echo ($countryIdForIsdCode == $key)?"selected":""?> 
				> <?php echo $value; ?></option>
				<?php } ?>   
			</select>
	    	<div style="display:none;"><div class="errorMsg" id="isd_error" style="*float:left"></div></div>
	    </div>
	    <div style="float:right; width:66%;">
	        <input style="padding:12px 4px 8px 13px" required="true" type="text" value="<?php echo $mobile;?>" id = "mobile" name = "mobile">
	        <div style="display:none;"><div class="errorMsg" id="mobile_error" style="*float:left"></div></div>
	    </div>
	</div>
	</li>

	<!--<li>
	    <div style="width:20px; float:left" data-enhance="false"><input type="checkbox" style="vertical-align:top" name="anonymous" value="YES" id="anonymousFlag"></div>
	    <div class="review-note">
	            Post this review as anonymous.<br />(Your name will not be published with the review.)
	    </div>
	</li>-->
	</ol>
	</div>

	<?php 	if($pageType=='campusRep'){?>
    	<input data-enhance="false" type="button" class="button yellow btn-b" value="Submit Review" style="width:100%; margin-top:20px;display: none;"  id="submitBtn" 
            onClick="$('#submitBtn').prop('disabled', true);if(validateFields(document.getElementById('reviewForm'))!=true){checkSecondStepValidation();$('#submitBtn').prop('disabled', false);return false;}else{if(checkSecondStepValidation()!=true){$('#submitBtn').prop('disabled', false);return false;}else{storeReviewData(); return false;}}">
	<?php 	}else{?>
	    <?php if($letsIntern){?>
            <input data-enhance="false" type="button" class="button yellow" value="Submit Review" style="width:100%; margin-top:20px;display: none;"  id="submitBtn" onClick="$('#submitBtn').prop('disabled', true);if(validateFields(document.getElementById('reviewForm'))!=true){ checkSecondStepValidation();$('#submitBtn').prop('disabled', true);return false;}else{if(checkSecondStepValidation()!=true){ $('#submitBtn').prop('disabled', true);return false;}else{storeReviewData(false); return false;}}">
          <?php  } else {
        ?>    
        <input data-enhance="false" type="button" class="button yellow btn-b" value="Submit Review" style="width:100%; margin-top:20px;display: none;"  id="submitBtn" onClick="$('#submitBtn').prop('disabled', true);if(validateFields(document.getElementById('reviewForm'))!=true){checkSecondStepValidation();$('#submitBtn').prop('disabled', false);return false;}else{if(checkSecondStepValidation()!=true){$('#submitBtn').prop('disabled', false);return false;}else{storeReviewData(); return false;}}">
    	<?php }?>
	<?php 	} ?>

    <div id="privacy_content_div" class="privacy-sec clearfix" style="display: none;">  
        <p><a href="#" onclick="$('#privacy_content').show(); return false;" class="privacy-link">Are you concerned about Privacy? </a></p>
        <div id="privacy_content" class="privacy-content" style="display: none;">
            <p>Your name will bring credibility to the review you've just written. We recommend you post it like that. However, we do have an option to hide your name in case you're not comfortable.</p><br />
            <p data-enhance="false"><input type="checkbox" name="anonymous" value="YES" id="anonymousFlag"> Post as anonymous</p>
        </div>
    </div>
    <p class="sml-txt1" id="bookMyShowText" style="display: none;">Your review will be moderated by our team within 30 days, Once your review is published by our team, you will get a confirmation email<?php echo ($pageType=='campusRep'?"":" & Rs. 100 will be credited to your Paytm account associated with your mobile number in next 30 days. If you don't receive even after successful selection of your review then you can write to us on college.reviews@shiksha.com and we will get back to you as soon as possible")?>.</p>

    </section>
    <input type='hidden' name='incentiveFlag' id="incentiveFlag" value='<?php echo ($pageType=='campusRep'?"0":"1")?>'>
    </form>
<div>
</div>
</div>
</div>    
<div data-role="page" id="sampleReviews" style="min-height: 373px;padding-top: 50px;">
    <?php $this->load->view('sampleReviews'); ?>
</div>  
<div data-role="page" id="competitionDetails" style="min-height: 373px;padding-top: 50px;">
    <?php $this->load->view('termsconditions'); ?>
</div>