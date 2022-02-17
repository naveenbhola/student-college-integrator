<?php ?>
<div id = "sendveriOverlay" style = "width:400px;display:none;position:absolute;z-index:2050000;">
	<div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
						<div class="normaltxt_11p_blk" style="background:#6391cc;height:30px;">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="row">
							<div style="margin-right:10px; margin-top:3px" class="float_R">
								<span class="cssSprite1 allShikCloseBtn" onClick="hideveriOverlay()" style="padding-left:13px;top:0">&nbsp;</span>
							</div>							
							<div class="bld mar_left_10p" id = "HeaderforOverlay" style="font-size:16px;color:#FFF">Resend verification email</div>							
							</div>				
						</div>
                        <!-- Header Ends -->
						<div  style="background-color:#FFF;padding-top:10px">

    
<div class = "row">			
	    <div style = "margin-left:15px" class = "fontSize_12p">Type in the characters you see in the picture below  :</div>
<div>
<img style = "padding-left:15px;" id = "sendEmailCaptacha" src="//<?php echo IMGURL; ?>/public/images/blankImg.gif" align="absmiddle"/>
<input type="text" validate = "validateSecurityCode" required = "1" minlength = "5" maxlength = "5" caption = "Security Code" style="width:150px" id = "sendCapt" name = "sendCapt"/>
</div>
                                <div class="errorPlace" style="margin-top:2px;">
                                    <div  style="margin-left:91px" class="errorMsg" id= "sendCapt_error"></div>							                     </div>
</div>                                                      
 <div align = "center">
 <input type="submit" value="Send" class="submitGlobal" style="border:0  none" onclick=" return sendverificationMail();"/>
</div>

						   <div style="line-height:10px">&nbsp;</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--</body>-->
</div>
<div id = "changeOverlay" style = "width:400px;display:none;position:absolute;z-index:2050000;">
	<div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
						<div class="normaltxt_11p_blk" style="background:#6391cc;height:30px;">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="row">
							<div style="margin-right:10px; margin-top:3px" class="float_R">
								<span class="cssSprite1 allShikCloseBtn" onClick="hideveriOverlay()" style="padding-left:13px;top:0">&nbsp;</span>
							</div>							
							<div class="bld mar_left_10p" id = "HeaderforOverlay" style="font-size:16px;color:#FFF">Please provide your email address</div>							
							</div>				
						</div>
                        <!-- Header Ends -->
						<div  style="background-color:#FFF">
							<div class="row">                                         
							    <div class="lineSpace_10">&nbsp;</div>
    							<div class="bld" style="margin:0 10px 0 17px">
                                <span>New Email Id:</span> <input type="text" id = "newemail" name = "newemail" maxlength = "125" style="width:157px;" /> 
                                </div>                                     											
                                <div class="errorPlace" style="margin-top:2px;">
                                    <div  style="margin-left:91px" class="errorMsg" id= "newemail_error"></div>							                     </div>
						   </div>

    
 <div class="lineSpace_10">&nbsp;</div>
<div class = "row">			
	    <div style = "margin-left:15px" class = "fontSize_12p">Type in the characters you see in the picture below  :</div>
<div>
<img style = "padding-left:15px;" id = "changeEmailCaptacha" src="//<?php echo IMGURL; ?>/public/images/blankImg.gif" align="absmiddle"/>
<input type="text" validate = "validateSecurityCode" required = "1" minlength = "5" maxlength = "5" caption = "Security Code" style="width:150px" id = "changeCapt" name = "changeCapt"/>
</div>
                                <div class="errorPlace" style="margin-top:2px;">
                                    <div  style="margin-left:91px" class="errorMsg" id= "changecapt_error"></div>							                     </div>
</div>                                                      
 <div align = "center">
 <input type="submit" value="Update" class="submitGlobal" style="border:0  none" onclick=" return changeEmail(document.getElementById('newemail').value,'email',150,3);"/>
</div>

						   <div style="line-height:10px">&nbsp;</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--</body>-->
</div>
