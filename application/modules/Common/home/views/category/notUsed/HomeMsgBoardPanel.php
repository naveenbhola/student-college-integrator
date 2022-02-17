			<div style="width:49%" class="float_L">	
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:260px;">						
						<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont arial"><span class="mar_left_10p">Forums</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p" style="height:200px;">
							<?php	
									foreach($msgBoards as $msgBoardId => $msgBoard) {
										$msgBoardTopic = isset($msgBoard['msgTitle']) ? $msgBoard['msgTitle'] : '';
										$numComments = isset($msgBoard['Count']) ? $msgBoard['Count'] : '';
										$numUsers = isset($msgBoard['numUsers']) ? $msgBoard['numUsers'] : '';
										$userImage = isset($msgBoard['userImage']) && !empty($msgBoard['userImage']) ? $msgBoard['userImage'] : '/public/images/unkownUser.gif';
							?>
							<div class="row">
								<div><img src="<?php echo $userImage; ?>" width="58" height="52" align="left" /></div>
								<div class="mar_left_70p normaltxt_11p_blk arial">
									<div><a href="<?php echo $msgBoardId; ?>"><?php echo $msgBoardTopic; ?></a></div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="lineSpace_20">
										<a href="#"><?php echo $numComments; ?> Comments</a>, 
										<a href="#"><?php echo $numUsers; ?> Comments</a>
									</div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_10">&nbsp;</div>								
							</div>
							<?php	}	?>
						</div>
					
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p normaltxt_11p_blk_arial txt_align_r"><a href="#">View all</a>&nbsp;<span class="redcolor">&gt;</span></div>
						<div class="lineSpace_10">&nbsp;</div>					
					</div>
				  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
