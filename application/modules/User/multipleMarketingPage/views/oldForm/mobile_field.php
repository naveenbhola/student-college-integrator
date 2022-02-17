                                <?php if(($logged == "Yes" && strlen($userData[0]['mobile']) !=10)) { $userData[0]['mobile']  = ''; } ?>
                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Mobile:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div class="float_L">
											<input type="text" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number" tip="mobile_numM" maxlength="<?php if(($logged == "Yes")&&(strlen($userData[0]['mobile']) > 10)) {echo strlen($userData[0]['mobile']);}else {echo "10";}?>" maxlength1="10" minlength = "10" style="width:150px;font-size:12px" value="<?php if($logged == "Yes") {echo $userData[0]['mobile'];}?>"/>
										</div>
										<div class="clear_L withClear">&nbsp;</div>
										<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
											<div class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
								<div class="lineSpace_10">&nbsp;</div>

