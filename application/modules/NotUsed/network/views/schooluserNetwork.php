<!--Start_Mid_Panel-->
	<div id="mid_Panel_network">
		<div style="display:inline; float:left; width:100%">
				<div class="raised_lgraynoBG">
<!--					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
					  	<div class="lineSpace_5">&nbsp;</div>
						<div>
<div class="float_L"><img src="/public/images/networkImg.jpg" /></div>
							<div class="normaltxt_11p_blk_arial float_L" style="width:325px;">
								<div class="bld mar_left_10p">Who Can Join?</div>
									<div class="mar_left_10p">
											<span><img src="/public/images/check.gif" /> Student&nbsp;&nbsp;</span>
											<span><img src="/public/images/check.gif" /> Alumini&nbsp;&nbsp;</span>
											<span><img src="/public/images/check.gif" /> Faculty&nbsp;&nbsp;</span>
											<span><img src="/public/images/check.gif" /> Prospective Students&nbsp;&nbsp;</span>
									</div>
									<div class="lineSpace_20">&nbsp;</div>
									<div class="bld mar_left_10p">Benefits of Joining?</div>
									<div class="mar_left_10p">
											<span><img src="/public/images/eventArrow.gif" /> Student&nbsp;&nbsp;</span>
											<span><img src="/public/images/eventArrow.gif" /> Alumini&nbsp;&nbsp;</span>
											<span><img src="/public/images/eventArrow.gif" /> Faculty&nbsp;&nbsp;</span>
											<span><img src="/public/images/eventArrow.gif" /> Prospective Students&nbsp;&nbsp;</span>
									</div>
									<div class="lineSpace_10">&nbsp;</div>									
									<div class="row">
										<div class="buttr3">
							

<?php if(!(is_array($validateuser) && $validateuser != "false")) {
?>
<button class="btn-submit21 w100" value="" type="button" id = "join" name = "join" onClick = "javascript:showuserOverlay(this,'join');">
<?php }else{
$userid = $validateuser[0]['userid'];
?>
<button class="btn-submit21 w100" value="" type="button" id = "join" name = "join" onClick = "javascript:showNetworkOverlay('join',<?php echo $userid?>);">
<?php } ?>
						<div class="btn-submit21"><p class="btn-submit22 btnTxtBlog" style="padding: 18px 8px 18px 5px;">Join Now</p></div>
											</button>
										</div>
										<div class="clear_L"></div>
									</div>
							</div>
							<div class="clear_L"></div>
						</div>


					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_10">&nbsp;</div>-->

				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial" id = "Noofstudents">
							</div>
						</div>
					  	<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="right">

								<div class="pagingID" id="paginataionPlace1"></div>

							</div>

<input type="hidden" id="startOffSet1" value="0"/>
<input type="hidden" id="countOffset1" value="4"/>	
<input type="hidden" id="methodName1" value="showfacultyNetwork"/>
		<div class="lineSpace_10">&nbsp;</div>
<div id = "collegeNetwork">
</div>
<!--
						<div class="row">
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>

							<div class="clear_L"></div>
						</div>-->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="right">

								
				<div class="pagingID" id="paginataionPlace2"></div>

							</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<div class="lineSpace_10">&nbsp;</div>
				<!--End_Student_Network-->
				
				<!--Teacher_Network-->
				<div class="raised_lgraynoBG">
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="h22 raisedbg_sky">
							<div class="normaltxt_11p_blk fontSize_13p arial" id = "facultynumber"></div>
						</div>
					  <div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="right">
								<div class="pagingID" id="paginataionPlace3"></div>
							</div>
<div id = "facultyCollegeNetwork">
</div>
						<!--<div class="lineSpace_10">&nbsp;</div>
						<div class="row">
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>
							<div class="w48_per float_L">
								<div class="mar_left_5p normaltxt_11p_blk mar_top_5p">
									<div class="float_L"><img src="/public/images/pic.gif" width="58" height="52" class="mar_right_5p" /><br /><img src="/public/images/greenDot.gif" width="17" height="19" /><img src="/public/images/mail.gif" width="22" height="19" /><img src="/public/images/plus.gif" width="19" height="19" /></div>
									<div style="margin-left:60px">
										<div class="lineSpace_23">
											<a href="#" style="margin-bottom:5px;">Steven Spilberg</a>
											<a href="#" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none;">Gold User</a>
										</div>
										<div class="lineSpace_5">&nbsp;</div>
										<span>Working Professional</span>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_15">&nbsp;</div>
							</div>

							<div class="clear_L"></div>
						</div>-->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="grayLine"></div>
						<div class="lineSpace_10">&nbsp;</div>
							<div class="mar_right_10p" align="right">
								<div class="pagingID" id="paginataionPlace4"></div>
							</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
				<!--End_Teacher_Network-->
				
				
				
			</div>
			<!--End_courses_category_Box-->
		<div class="lineSpace_10">&nbsp;</div>
	</div>
	<!--End_Mid_Panel-->

