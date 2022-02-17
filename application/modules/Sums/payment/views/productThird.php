	<?php
    echo '<div id="Page3" style="display:inline; float:left; width:100%">
			<div class="raised_lgraynoBG">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont" style="margin-bottom:10px"><span class="mar_left_10p">Login to Continue</span></div>
					<div class="lineSpace_10">&nbsp;</div>
					<div class="mar_left_10p mar_right_30p">
						<div class="raised_skyn">
								<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_skyn">
								  <div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld"><img id="loginImg" src="/public/images/arrowOrgange.gif" width="18" height="18" align="absmiddle" class="mar_left_10p" onclick="toggleLoginPanel();" style="cursor: pointer"/> Already a Shiksha member<span id="wrongUser" style="display:none"> Wrong User Name Or Password</span><span id="empty2" style="display:none"> Please Enter Email And Password</span><br></div>
									<!--Login_Panel-->
									<div id="loginPanel" style="display:none">
									  <div class="lineSpace_10">&nbsp;</div>
									  <div class="row">
										  <div class="mar_left_20p normaltxt_11p_blk float_L" style="position:relative; top:2px">Email Id: <input id="Email" type="text" />	&nbsp; &nbsp; Password: <input id="Passwd" type="password" /></div>
 										  <div class="buttr2">
												<button class="btn-submit11 w9" value="" type="button" onclick="loginAndWork(3,4)">
													<div class="btn-submit11"><p class="btn-submit12">Login &amp; Continue</p></div>
												</button>
										  </div>
										  <span class="pos_t12">&nbsp;<a href="#">Forgot Password</a></span>
										  <div class="clear_L"></div>
									  </div>
									</div>
									<!--Login_Panel-->
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>

					<div class="lineSpace_20">&nbsp;</div>
					<div class="mar_left_10p mar_right_30p">
						<div class="raised_pinkn">
								<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_pinkn">
								  <div class="h22 raisedbg_pink normaltxt_11p_blk fontSize_13p bld"><img id="regImg" src="/public/images/arrowOrgange.gif" width="18" height="18" align="absmiddle" class="mar_left_10p" onclick="toggleRegPanel();" style="cursor: pointer"/> New User: Register yourself in few minutes to enjoy the benefits</div>
								  <div id="regPanel" style="display: none">
                                  ';
                                   $this->load->view('user/Registration_form');
                                    
                                    echo'
                                  </div>
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>
					<div class="lineSpace_20">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>';
        ?>
