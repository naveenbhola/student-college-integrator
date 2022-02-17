                                <?php if($logged=="No") {?>
                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Email ID:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input type="text" id = "homeemail" name = "homeemail" value="<?php echo $userEmail; ?>" validate = "validateEmail" required = "true" caption = "email id" maxlength = "125" style = "width:150px;font-size:12px" tip="email_idM" />
										</div>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>
											</div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php }else {?>
                                    <input type="hidden" id = "<?php echo $prefix; ?>homeemail" value="<?php $cookiesArr = array(); $cookieData = $userData[0]['cookiestr']; $cookieArr = split('\|',$cookieData); echo $cookieArr[0]; ?>"/>
                                        <input type="hidden" id = "<?php echo $prefix; ?>userId" value="<?php echo $userData[0]['userid']; ?>"/>


                                <?php }?>
