			<div style="width:49%" class="float_R">	
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:225px">					
						<div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld OrgangeFont arial"><span class="mar_left_10p">Featured Scholarships</span></div>
						<div class="lineSpace_10">&nbsp;</div>
						<div class="mar_full_10p" style="height:185px">
							<div>
								<span><img src="/public/images/upcomingevent_img.gif" width="96" height="110" align="left" /></span>
								<div class="mar_left_97p normaltxt_11p_blk arial">
									<ul type="square" style="margin-top:0">
										<?php
											foreach($events as $event) {
												$eventId = isset($event['id']) ? $event['id'] : '';
												$eventTitle = isset($event['title']) && !empty($event['title']) ? $event['title'] : '';
												$city = isset($event['city']) && !empty($event['city']) ? $event['city'] .',' : '';
												$location = isset($event['venue_name']) && !empty($event['venue_name'])? $event['venue_name'] .',' : '';
												$startDate = isset($event['start_date']) && !empty($event['start_date'])? $event['start_date'] : '';
												$startDate = date('jS M, y, h:ia',strtotime( $startDate));
										?>
										<li class="bullet_postion_15p">
											<span><a href="<?php echo $eventId; ?>"><?php echo $eventTitle; ?></a></span><br />
											<span class="normaltxt_11p_blk_arial darkgray">
												<?php echo $location; ?> <?php echo $city; ?> <?php echo $startDate; ?>
											</span>
											<div class="lineSpace_5">&nbsp;</div>
										</li>
										<?php
											}
										?>
										
									</ul>
								</div>
							</div>
							<div class="clear_L"></div>
						</div>
						<div class="normaltxt_11p_blk_arial txt_align_r mar_full_10p" style="margin-top:-10px;"><a href="#">View all</a>&nbsp;<span class="redcolor">&gt;</span></div>
						<div class="lineSpace_10">&nbsp;</div>
					</div>
				  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>	
