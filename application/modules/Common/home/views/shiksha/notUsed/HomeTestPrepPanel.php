<?php
	$testPreps = array(
					'1' => array(
						'heading' => 'GRE word list',
						'numQues' => '4000'
					),
					'2' => array(
						'heading' => 'GMAT',
						'numQues' => '4000'
					),
					'3' => array(
						'heading' => 'CAT',
						'numQues' => '4000'
					),
					'4' => array(
						'heading' => 'LAW',
						'numQues' => '4000'
					),
					'5' => array(
						'heading' => 'TOFEL',
						'numQues' => '4000'
					),
				);
?>
			<div style="width:49%" class="float_R">	
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:245px;">						
						<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont arial"><span class="mar_left_10p">Test Preparation Center</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p" style="height:185px;">	
								<div>
									<span><img src="/public/images/educationupdates_img.gif" width="96" height="110" align="left" /></span>
									<div class="mar_left_97p normaltxt_11p_blk arial">
										<ul type="square" style="margin-top:0">
											<?php
												foreach($testPreps as $testPrepId => $testPrep) {
													$testPrepHeading = isset($testPrep['heading']) ? $testPrep['heading'] : '';
													$numQues = isset($testPrep['numQues']) ? $testPrep['numQues'] : '';
											?>
											<li class="bullet_postion_15p">
												<span><a href="#"><?php echo $testPrepHeading; ?></a></span><br />
												<span class="darkgray"><?php echo $numQues; ?> Questions</span>
												<div class="lineSpace_5">&nbsp;</div>
											</li>
											<?php } ?>
										</ul>
									</div>
								</div>
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
