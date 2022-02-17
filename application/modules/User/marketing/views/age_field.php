                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Age:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input id = "<?php echo $prefix?>homeYOB" name = "homeYOB" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age field" style="width:20px;font-size:12px;"  value="<?php if($logged == "Yes" ) if($userData[0]['age']!='0') {echo $userData[0]['age'];}?>"/>
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homeYOB_error"></div>
											</div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>

                                <div class="lineSpace_10">&nbsp;</div>

