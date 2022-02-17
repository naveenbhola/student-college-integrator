<div id="mailOverlay" style="position:absolute;display:none;z-index:20500000;">
  <div>
    <?php 
if($successfunction == '')
$redirecturl = $successurl;
else
$redirecturl = $successfunction;
echo $this->ajax->form_remote_tag( array(
'url' => base_url().'/mail/Mail/save',
'update' => '',
'success' => 'javascript:showMailResponse(request.responseText)',
'failure'=>'javascript:showFailureMessage(request.responseText)'
) 
); 
?>
    <input name = "redirectUrl" id = "redirectUrl" type = "hidden" value = "<?php echo $successurl;?>"/>
    <input name = "senderid" id = "senderid" type = "hidden" value = "1"/>
    <input name = "receiverid" id = "receiverid" type = "hidden" value = "1"/>
    <input name = "mailtype" id = "mailtype" value = "send" type = "hidden"/>
    <br />
    <br />
    <br />
	<div style="width:400px">
		<div id="shadow-container">
			<div class="shadow1">
				<div class="shadow2">
					<div class="shadow3">
						<div class="container" style="width:420px;">
								<div class="h33 bgcolorSky normaltxt_11p_blk" style="background:#6391CC">
									<div class="lineSpace_7">&nbsp;</div>
									<div class="float_R mar_right_10p"><div class="shikIcnClse" onClick="javascript:hidemailOverlay();">&nbsp;</div></div>
									<div class="bld mar_left_10p fontSize_13p" style="color:#FFF">Send Message</div>							
									<div class="clear_R"></div>						
								</div>
								<div class="bgOverLay">
										<div class="mar_full_10p">									
											<div class="lineSpace_10">&nbsp;</div>
											<div class="normaltxt_11p_blk_arial fontSize_12p">
												<div>&nbsp; &nbsp; &nbsp; &nbsp; To: <input type="text" disabled name = "names" id = "names" size="45" style="height:18px;margin-left:2px" value = "" class = "normaltxt_11p_blk_arial fontSize_12p" /></div> 										
											</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="normaltxt_11p_blk_arial fontSize_12p">
												<div>Subject: <input type="text" name = "subject" id = "subject" size="45" style="height:18px; margin-left:2px" value = "" class = "normaltxt_11p_blk_arial fontSize_12p" /></div>										
											</div>
											<div class="lineSpace_10">&nbsp;</div>
											<div class="normaltxt_11p_blk_arial fontSize_12p">
												<div><span style="position:relative; top:-53px">Content:</span> <textarea name="body" id = "body" class="textboxBorder"  id="content" rows="15" style="width:81%" ></textarea></div>
											</div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<div align="center">
											<div class="buttr3" style="float:none">
												<button class="btn-submit13 w16" value="" type="submit">
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog" name = "submit" id = "submit">Send</p></div>
												</button>
                                                &nbsp;&nbsp;<a href='javascript:void(0);' onClick='hidemailOverlay();'>Cancel</a>
											</div>
											<div class="clear_L"></div>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>    
  </div>
</div>
</form>
