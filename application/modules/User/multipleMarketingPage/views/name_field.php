                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">First Name:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width: 155px;">
										<div class="float_L">
											<input type="text" id="firstname" name="firstname" class = "fontSize_11p" validate = "validateDisplayName" maxlength = "50" minlength = "1" required = "true" caption = "first name" default="Your First Name" style = "width:150px;font-size:12px" value="<?php if($logged == "Yes") {echo htmlentities($userData[0]['firstname']);} else { echo htmlentities($userfirstName); }?>"/>
										</div>

										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "firstname_error"></div>
											</div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Last Name:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width: 155px;">
										<div class="float_L">
											<input type="text" id="lastname" name="lastname" class = "fontSize_11p" validate = "validateDisplayName" maxlength = "50" minlength = "1" required = "true" caption = "last name" default="Your Last Name" style = "width:150px;font-size:12px" value="<?php if($logged == "Yes") {echo htmlentities($userData[0]['lastname']);} else { echo htmlentities($userlastName); }?>"/>
										</div>

										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "lastname_error"></div>
											</div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
