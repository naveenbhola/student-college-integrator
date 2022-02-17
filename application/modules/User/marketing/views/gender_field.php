                                <div>
                                    <div class="float_L" style="width:175px;line-height:18px">
                                    	<div class="txt_align_r" style="padding-right:5px">Gender: &nbsp;</div>
                                    </div>
                                    <div style="float:left;width:155px">
										<div>
											<input type="radio" name = "homegender" id = "<?php echo $prefix?>Male" value = "Male" />Male
											<input type="radio" name = "homegender" id = "<?php echo $prefix?>Female" value = "Female" />Female
										</div>
										<script>
										<?php if($logged=="Yes") {if($userData[0]['gender']=="Male") {
											echo "document.getElementById('".$prefix."Male').checked='true';";
										}
										}?>
										<?php if($logged=="Yes") {if($userData[0]['gender']=="Female") {
											echo "document.getElementById('".$prefix."Female').checked='true';";
										}
										}?>
										</script>
										<div>
											<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
												<div class="errorMsg" id= "homegender_error"></div>
											</div>
										</div>
                                    </div>


                                    <div class="clear_L withClear">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
 
