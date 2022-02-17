<?php
	$ratings = array(
					'1' => array(
						'heading' => 'Management Colleges',
						'image' => '/public/images/pic.gif',
						'numColleges' => '100'
					),
					'2' => array(
						'heading' => 'USA Colleges',
						'image' => '/public/images/pic.gif',
						'numColleges' => '100'
					),
					'3' => array(
						'heading' => 'Australian Colleges',
						'image' => '/public/images/pic.gif',
						'numColleges' => '100'
					),					
				);
?>
			<div style="width:49%" class="float_L">	
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:245px;">					
						<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont arial"><span class="mar_left_10p">College Ratings</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p" style="height:185px;">
							<?php
								foreach($ratings as $ratingId => $rating) {
									$ratingHeading = isset($rating['heading']) ? $rating['heading'] : '';
									$ratingImage = isset($rating['image']) ? $rating['image'] : '';
									$numColleges = isset($rating['numColleges']) ? $rating['numColleges'] : '';
							?>
							<div class="row">
								<div><img src="<?php echo $ratingImage; ?>" width="58" height="52" align="left" /></div>
								<div class="mar_left_70p normaltxt_11p_blk arial">
									<div><a href="#"><?php echo $ratingHeading; ?></a></div>
									<div class="lineSpace_5">&nbsp;</div>
									<div class="lineSpace_10"><a href="#">Ratings for <?php echo $numColleges; ?> collges</a></div>
								</div>
								<div class="clear_L"></div>
								<div class="lineSpace_10">&nbsp;</div>
							</div>
							<?php
								}
							?>				
							
							<div class="clear_L"></div>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="normaltxt_11p_blk_arial txt_align_r mar_full_10p">
							<a href="#">View all</a>&nbsp;<span class="redcolor">&gt;</span>
						</div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>	
