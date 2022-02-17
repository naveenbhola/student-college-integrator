                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Name:<b class="redcolor">*</b> </div>
                                    </div>
                                    <div style="float:left;width: 155px;">
										<div class="float_L">
											<input type="text" id = "homename" name = "homename" class = "fontSize_11p" validate = "validateDisplayName" maxlength = "25" minlength = "3" required = "true" caption = "name" style = "width:150px;font-size:12px" value="<?php if($logged == "Yes") {echo $userData[0]['firstname']." ".$userData[0]['lastname'];} else { echo $userName; }?>"/>
										</div>

										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "homename_error"></div>
											</div>
										</div>
                                    </div>
                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
