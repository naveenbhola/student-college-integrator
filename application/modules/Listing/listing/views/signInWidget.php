<?php
$headerComponents = array(
                          'css'   => array(
                                            'header',
                                          'mainStyle',
                                          'raised_all',
                                          'footer',
                                          //'cal_style'
                                          ),
                          'js'    => array(
                                          'common',
                                          'user',
                                          'cityList'
                                           ));
$this->load->view('common/jsCssLoader',$headerComponents);
                                           ?>
						<div style="background-color:#FFF3E7;border:1px solid #FFE9D4">
                            <input type="hidden" id='redirectUrl' value="<?php echo $redirectUrl; ?>"/>
                            <input type="hidden"  id='redirectFlag' value="0"/>
							<div class="row">
            <form method="post" onsubmit="new Ajax.Request('/user/Login/submit',{onSuccess:function(request){javascript:showLoginResultRedirect(request.responseText);}, onFailure:function(request){javascript:showLoginResultRedirect(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Login/submit">
<input type = "hidden" name = "mpassword" id = "mpassword" value = ""i style="margin:0;padding:0" />
                            <!-- Left Panel Starts -->
									<div class="" style="padding-left:10px">
                                        <!-- Email starts-->
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <div class="bld fontSize_12p">Existing Shiksha User </div>
                                        <div class="lineSpace_20">&nbsp;</div>
                                        <div>
    									    <div class="float_L txt_align_r fontSize_12p" style="width:50px; line-height:20px">Email Id:</div>
                                            <div style="margin-left:60px"><input type="text" id = "username" name = "username" style="width:110px" tabindex="1" /></div>
<div class="clear_B" style="line-height:1px;font-size:1px">&nbsp;</div>                                     
										</div>
                                        <div class="errorPlace" style="margin-top:2px;">
                                            <div style="margin-left:60px" class="errorMsg" id= "username_error"></div>													
                                        </div>
                                        <div class="lineSpace_10">&nbsp;</div>
                                        <!-- Email Ends -->
                                        
                                        <!-- Password-->
                                        <div id = "pass">
                                            <div>
												<div class="float_L txt_align_r fontSize_12p" style="width:50px; line-height:20px">Password: &nbsp;</div>
                                                <div style="margin-left:60px"><input type="password" id = "password" name = "password" style="width:110px"  tabindex="2"/></div>
<div class="clear_B" style="line-height:1px;font-size:1px">&nbsp;</div>
									    </div>
                                        <div class="errorPlace" style="margin-top:2px;">
                                            <div  style="margin-left:60px" class="errorMsg" id= "password_error"></div>
                                        </div>
                                        </div>
                                        <!-- Password ends-->
                                        <!-- Remember Me -->
										<div id = "rememberme">
                                            <div>
                                                <input type="checkbox" checked name = "remember" id= "remember" tabindex="3"/>Remember me on this computer
                                            </div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
                                        <!-- Remember Me Ends -->
                                        <!-- Submit and Forgot-->
										<div class="row" id = "sendRequest">
											<div class="float_L txt_align_r" style="width:70px">&nbsp;</div>
											<div style="margin-left:71px">
												<button onclick="return isBlank(this.form);" type="submit" value="" class="btn-submit13 w16" style="float:left"><div class="btn-submit13"><p style="color: rgb(255, 255, 255);" id="submit" name="submit" class="btn-submit14" tabindex="4">Login</p></div></button>
<a style="margin-left:70px; display:block; line-height:26px" href="<?php echo $redirectUrl; ?> ">Back</a>
<!--												<span style="margin-left:70px; display:block; line-height:26px"><a href="#" id = "fpassword" onClick = "return sendMailtoUser(this.form)";>Forgot Password</a></span>-->
											</div>
											<div class="clear_L"></div>
										</div>
                                    <!-- Submit and Forgot-->
                                    </div>
                                    <!-- Left Panel Ends -->
<?php echo '</form>'; ?>
<script>
function showLoginResultRedirect(res)
{
	var url = document.getElementById('redirectUrl').value;
	var flag = document.getElementById('redirectFlag').value;
	//showLoginResponse(res,url,'',flag);
    if(res == 0)
	{
		document.getElementById('password_error').innerHTML = "Incorrect Login or password";
		document.getElementById('password_error').parentNode.style.display = 'inline';
	}
    else
    {
        window.location = url;
    }
}
</script>
