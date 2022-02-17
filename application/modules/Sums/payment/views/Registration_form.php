<?php

echo $this->ajax->form_remote_tag( array(
                            'url' =>
'/user/Userregistration/submit',
                           'success' =>
'javascript:registerUser(request.responseText);',

'failure'=>'javascript:testfunction1(request.responseText)')
                    );
       

?>

<style>
.error
{
font-family:verdana,arial;
font-size:13px;
color:red;
}
</style>
<?
$this->load->helper('form');
?>
<?
/*$attributes = array('name' => 'login');
echo form_open('user/userregistration/submit');*/ ?>
<div>
<div class="lineSpace_10">&nbsp;</div>
								  <div class="mar_right_15p normaltxt_11p_blk float_R graycolor">All field marked with <span class="redcolor">*</span> are Required</div>
								  <div class="lineSpace_10">&nbsp;</div>
 <!--New_User_Panel-->
	
	  <div class="row">
	    <div style="display: inline; float:left; width:100%">
		<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Email Id:<span class="redcolor">*</span>&nbsp;
		</div>
		
	    <div class="normaltxt_11p_blk float_L txt_align_l">
	    <input type="text" name = "email"  id = "email" validate = "validateEmail" />
<br>
	  <div class="formField errorPlace">
<div class="error" id= "email_error">
	   </div>
	  </div>
	    </div>
	  </div>


											
	  <div class="lineSpace_10">&nbsp;
          </div>
	 </div>

 <div class="lineSpace_10">&nbsp;
          </div>


	<div class="row">
        	<div style="display: inline; float:left; width:100%">
		<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Password:<span class="redcolor">*</span>&nbsp;
		</div>
		<div class="normaltxt_11p_blk float_L txt_align_l">
		<input name = "passwordr" id = "passwordr" type = "password" maxlength = "25" minlength = "5" validate = "validateStr" />
<br>
<div class="formField errorPlace">
<div class="error" id= "passwordr_error"></div>
</div>
		</div>
		</div>


		<div class="lineSpace_10">&nbsp;</div>
		</div>
<br><br>

		<div class="row">
		<div style="display: inline; float:left; width:100%">
		<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Confirm Password:<span class="redcolor">*</span>&nbsp;</div>
		<div class="normaltxt_11p_blk float_L txt_align_l">
		<input name = "confirmpassword" id = "confirmpassword" type = "password" validate = "validateStr" maxlength = "25" minlength = "5" />
<br>
<div class="formField errorPlace">
<div class="error" id= "confirmpassword_error"></div>
</div>
		</div>
		</div>


									
<div class="lineSpace_10">&nbsp;</div>
										</div>
<br>
<br>
			<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Display Name:<span class="redcolor">*</span>&nbsp;</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													<input name = "displayname" id = "displayname" type = "text" maxlength = "25" minlength = "1" validate = "validateStr" />
<br>
<div class="formField errorPlace">
<div class="error" id= "displayname_error"></div>
</div>
												</div>
											</div>

											<div class="lineSpace_10">&nbsp;</div>
										</div>

<br><br>

										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Type:<span class="redcolor">*</span>&nbsp;</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													<div class="lineSpace_10">&nbsp;</div>
													<input type="radio" name="detail" id = "detail" value = "Student"  onClick = "enable_radio(this.checked)" /> Student<br />
													<div class="mar_left_20p">
															<input type="radio" name = "student" id = "School" value = "School" disabled/> School<br />
															<input type="radio" name = "student" id = "Graduate" value = "Graduate" disabled/> Graduate<br />
															<input type="radio" name = "student" id = "Post Graduate" value = "Post Graduate" disabled/> Post Graduate
													</div>
													<input type="radio" name="detail" id = "detail" value = "College Staff" onClick = "disable_radio(this.checked)"/> College Staff
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
<br>
<br>

										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">City:&nbsp;</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
<input name = "city" id = "city" type = "text" />

												</div>
											</div>

											<div class="lineSpace_10">&nbsp;</div>
										</div>

<br>
<br>

										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w30 txt_align_r bld float_L">Mobile No:&nbsp;</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													<input  name = "mobile" type = "text" id = "mobile"/>
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
<br><br>
										<div class="grayLine"></div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w52_per txt_align_r bld float_L">Type in the character you see in the picture below&nbsp;</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													&nbsp;
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
			<div class="lineSpace_10">&nbsp;
                        </div>

			<div class="row">
			<div style="display: inline; float:left; width:100%">
<!-- Captacha Code-->

                  <div class="normaltxt_11p_blk w52_per txt_align_r bld float_L">
		  <img src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>" width="200" height="30" id = "registerCaptacha"/>&nbsp;
                 </div>

<div class="normaltxt_11p_blk float_L txt_align_l">&nbsp;</div>
			</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>

				<div class="row">
				<div style="display: inline; float:left; width:100%">
				<div class="normaltxt_11p_blk w52_per txt_align_r float_L">
<input type="text" name = "securityCode" id = "securityCode" validate = "" maxlength = "5" minlength = "5" required = "1"/>&nbsp;
<div class="formField errorPlace">
       <div id="securityCode_error" class="errorMsg"></div>
       </div></div>


<!-- Captcha Ends-->
												<div class="normaltxt_11p_blk float_L txt_align_l">
													&nbsp;
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
										<div class="grayLine"></div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk txt_align_l float_L mar_left_10per lineSpace_15" style="margin-left:47%">
													<span class="bld">Privacy Setting:</span>&nbsp;<br /><br />
<div>
<input type="checkbox" id = "viamobile" name = "viamobile" value = "mobile"/> Contact me on mobile for education products </div>
<div>
<input type="checkbox"  id = "viaemail" name = "viaemail" value = "email"/> Contact me via email for education products</div>
<div>
<input type = "checkbox" id = "newsletteremail" name = "newsletteremail" value = "newsletteremail"/> Send me Shiksha Newsletter email</div>
</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													&nbsp;
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>
										<div class="grayLine"></div>
										<div class="lineSpace_10">&nbsp;</div>
		<div class="row">
		<div style="display: inline; float:left; width:100%">
		<div class="normaltxt_11p_blk txt_align_l float_L mar_left_10per lineSpace_15" style="margin-left:47%">
		<input type="checkbox" id = "agree"/> I agree to the Terms and Conditions
<div class="formField errorPlace">
       <div id="agree_error" class="errorMsg"></div>
       </div>
		</div>
		<div class="normaltxt_11p_blk float_L txt_align_l">
		&nbsp;
		</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
		</div>
		<div class="grayLine"></div>
										<div class="lineSpace_10">&nbsp;</div>
										<div class="row">
											<div style="display: inline; float:left; width:100%">
												<div class="normaltxt_11p_blk w30 txt_align_l float_L mar_left_10per lineSpace_15" style="margin-left:20%">
													<div class="buttr3">
<button class="btn-submit7 w21" value="" type="submit" onclick = "return validateUser(this.form);">
<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Create my account</p></div>
</button>
													</div>
												</div>
												<div class="normaltxt_11p_blk float_L txt_align_l">
													&nbsp;
												</div>
											</div>
											<div class="lineSpace_10">&nbsp;</div>
										</div>

								  </div>

								  <!--New_User_Panel-->
</form>

<?php 
$this->load->helper('url');
     echo "<script language=\"javascript\"> ";
     echo "var captchacheckUrl = '".site_url('/user/Userregistration/validateCaptcha')."'";	
     echo "</script>";
?>
