<?php ?>
<div id = "userLoginOverlay" style = "width:650px;display:none;position:absolute;z-index:2050000;">
	<div id="shadow-container" style="background:none;border:none;">
        <div class="blkRound">
            <div>
                <div>
                    <div>
<?php
if($successfunction == '')
$redirecturl = $successurl;
else
$redirecturl = $successfunction;
?>

<input type = "hidden" name = "redirection" id = "redirection" value = "<?php echo $redirecturl?>"/>
<input type = "hidden" name = "redirectFlag" id = "redirectFlag" value = "1"/>
<input name = "redirectUrl" id = "redirectUrl" type = "hidden" value = "<?php echo $successurl;?>">
<input type = "hidden" name = "success" id = "success" value = "showQuickSignUpResponse"/>
<input type = "hidden" name = "SignUpPanel" id = "SignUpPanel" value = "0"/>
<input type = "hidden" name = "loginaction" id = "loginaction" value = ""/>
<input type = "hidden" name = "userproduct" id = "userproduct" value = ""/>
<input type = "hidden" name = "loginflag" id = "loginflag" value = ""/>
<script>
var QuickSignUp = 1;
</script>
<?php
$SignUpPanel ." = document.getElementById('SignUpPanel').value";
?>
                        <!-- Header Starts -->
			<div class="layer-title">
				<a href="#" class="close" title="Close" onClick="hideLoginOverlay()"></a>
				<h4 id = "HeaderforOverlay">Sign in to Shiksha</h4>
			</div>				
			<!-- Header Ends -->
			<div>
							<div class="spacer5 clearFix"></div>
							<div class="row">
            <form method="post" onsubmit="new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResult(request.responseText,'_actionCompletion');}, onFailure:function(request){javascript:showLoginResult(request.responseText,'_actionCompletion')}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Login/submit" autocomplete = "off">
<input type = "hidden" name = "mpassword" id = "mpassword_actionCompletion" value = ""/>
                            <!-- Left Panel Starts -->
									<div class="float_L" style="width:270px; padding:0 0px 0 10px">
                                        <!-- Email starts-->
                                        <div class="spacer5 clearFix"></div>
                                        <div class="bld fontSize_12p OrgangeFont">Existing Shiksha User</div>
                                        <div class="spacer10 clearFix"></div>
                                         <div>
    									<div class="float_L txt_align_r" style="font-size:11px;width:93px;line-height:20px">Login Email Id: &nbsp;</div>
												<div style="margin-left:71px"><input type="text" id = "username_actionCompletion" name = "username" style="width:157px" /></div>                                     
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                    <div  style="margin-left:91px" class="errorMsg" id= "username_actionCompletion_error"></div>													
                                            </div>
                                            <div class="spacer10 clearFix"></div>
                                        <!-- Email Ends -->
                                        
                                        <!-- Password-->
                                        <div id = "pass_actionCompletion" class="row">
                                            <div>
												<div class="float_L txt_align_r" style="width:93px;font-size:11px;line-height:20px">Password: &nbsp;</div>
												<div style="margin-left:71px"><input type="password" id = "password_actionCompletion" name = "password" style="width:157px" /></div>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                 <div  style="margin-left:91px" class="errorMsg" id= "password_actionCompletion_error"></div>
                                            </div>
                                        </div>
                                        <!-- Password ends-->
                                        <!-- Remember Me -->
										<div class="spacer5 clearFix"></div>
										<div class="row" id = "rememberme_actionCompletion">
											<div class="float_L txt_align_r" style="width:90px; line-height:20px">&nbsp;</div>
											<div style="margin-left:71px;font-size:11px;"><input type="checkbox" checked name = "remember" id= "remember_actionCompletion"/>Remember me on this computer</div>
											<div class="clearFix"></div>
										</div>
										<div class="spacer5 clearFix"></div>
                                        <!-- Remember Me Ends -->
                                        <!-- Submit and Forgot-->
										<div class="row" id = "sendRequest_actionCompletion">
											<div class="float_L txt_align_r" style="width:90px">&nbsp;</div>
											<div style="margin-left:90px">
												<input type="submit" value="Login" class="orange-button" onclick="return isBlank(this.form,'_actionCompletion');" />
												<!--<button onclick="return isBlank(this.form);" type="submit" value="" class="btn-submit13 w16" style="float:left"><div class="btn-submit13"><p style="color: rgb(255, 255, 255);" id="submit" name="submit" class="btn-submit14">Login</p></div></button>-->
												<span style="margin-left:3px; display:block; line-height:26px">
                                                	<a href="#" id = "fpassword" onClick = "return sendMailtoUser(this.form,'_actionCompletion')"; style="font-size:11px">Forgot Password</a></span>
											</div>
											<div class="clearFix"></div>
										</div>
                                    <!-- Submit and Forgot-->
                                    </div>
                                    <!-- Left Panel Ends -->
<?php echo '</form>'; ?>

                                    <!-- Right Panel Starts-->
									<div style="width:318px; padding:0 0px 0 10px; border-left:1px solid #e7e7e7; float:left">
										<div class="spacer5 clearFix"></div>
										<div class="bld fontSize_12p OrgangeFont">New to Shiksha ?? Register for free !</div>
										<div class="spacer10 clearFix"></div>
                                        <?php //{ if($SignUpPanel == 0) 
                                        ?>
                                        <div id = "SignUpInstructions">
										<div class="loginBullets" style="font-size:11px">Get information of colleges and courses from India and abroad-for free!</div>
										<div class="loginBullets" style="font-size:11px">Network with prospective students, current students and alumni of your dream college for free!</div>
										<div class="loginBullets" style="font-size:11px">Ask and discuss your question with Shiksha expert panel and Shiksha users-for free!</div>
										<div class="txt_align_c">
											<button type="button" onclick = "window.location = '/user/Userregistration/index/<?php echo base64_encode($successurl)?>'" value="" class="btn-submit19 w20">
												<div class="btn-submit19"><p style="padding: 15px 8px 15px 5px;" class="btn-submit20 btnTxtBlog">Join Now For Free</p></div>
				 							</button>
										</div>
                                       </div>
                                       <?php //}else{ 
                                       ?>

                                       <div id="SignUpPaneldivRequestInfo">
                                       <?php 
                                            $this->load->view('home/shiksha/joinPanelCore',array('prefix'=>'userlogin')); 
                                       ?>
                                       </div>

									<div id = "SignUpPaneldiv">
                                       <?php 
                                            $this->load->view('user/quicksignupform'); 
                                       ?>

                                      </div>       
                                      <?php //}
                                      ?>
                                     </div>
                                    <!-- Right Panel Ends -->
									<div class="clearFix"></div>
							</div>
							<div class="spacer10 clearFix"></div>
                            
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--</body>-->
</div>
