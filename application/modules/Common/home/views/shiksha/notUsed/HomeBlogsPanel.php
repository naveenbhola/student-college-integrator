			<div style="width:49%" class="float_R">	
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:260px;">					
						<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont arial"><span class="mar_left_10p">Articles</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p" style="height:200px;">
							<?php 
								foreach($blogs as $blog) {
									$blogId = isset($blog['blogId']) ? $blog['blogId'] : '';
									$blogTitle = isset($blog['blogTitle']) ? $blog['blogTitle'] : '';
									$userId = isset($blog['userId']) ? $blog['userId'] : '';
									$userName = isset($blog['displayname']) ? $blog['displayname'] : '';
									$userLevel = isset($blog['level']) ? $blog['level'] : '';
									$userImage = isset($blog['userImage']) ? $blog['userImage'] : '';
									$userType = isset($blog['userType']) ? $blog['userType'] : '';
									$userInstitute = isset($blog['userInstitute']) ? $blog['userInstitute'] : '';
									echo $userImage;
							?>
							<div class="row">
								<div>
									<img src="<?php echo $userImage; ?>" width="58" height="52" align="left" />
								</div>
								<div class="mar_left_70p normaltxt_11p_blk arial">
									<div><a href="<?php echo $blogId; ?>"><?php echo $blogTitle; ?></a></div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="lineSpace_20 darkgray">
										Post by <a href="<?php echo $userId; ?>"><?php echo $userName; ?></a> 
										<a href="<?php echo $userId; ?>" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none"><?php echo $userLevel;?> User</a>, <?php echo $userType;?>, <?php echo $userInstitute;?>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_10">&nbsp;</div>
							</div>
							<?php } ?>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p normaltxt_11p_blk_arial txt_align_r"><a href="#">View all</a>&nbsp;<span class="redcolor">&gt;</span></div>
						<div class="lineSpace_10">&nbsp;</div>	
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
