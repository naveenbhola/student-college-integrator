		<!-- Listings Started -->
			<?php
				foreach($mylistings as $key => $value) {
					$$key = $value;
				}
				
			?>
			<div id="myShikshaListingBox" class="inline-l" style="width:100%;margin-top:10px;">
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
					<div class="raisedbg_sky h22 OrgangeFont">
						<div class="float_R myProfile">
						   <!--<span class="mar_right_10p"><a href="/listing/Listing/addCourse">Add Listing</a></span>-->
						</div>
						<div class="fontSize_13p bld" style="margin-bottom:3px">
							<img src="/public/images/alertIcon.gif" width="33" height="17" align="absmiddle" /> My Saved Institutes and Courses
						</div>
					</div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="row hideComponent" id="listingHidden"></div>
						<div id="listingShow">
							<div class="" style="margin:0px;">
									<input type="hidden" name="category_id" id="category_id" value="1"/>
								<?php	
									$this->load->view('listing/myListingMidPanel', $mylistings);
								?>
							</div>
							<!-- Listing will come here -->
							<div class="clear_L"></div>
<!--							<div class="row">
								<div class="myProfile float_R mar_right_10p"><img src="/public/images/eventArrow.gif" width="6" height="6" /> <a href="/listing/Listing/mylisting">View all</a></div>
								<div class="normaltxt_11p_blk mar_left_10p myProfile">&nbsp;</div>
							</div>-->
						</div>
<!--						<div class="lineSpace_5">&nbsp;</div>
						<div class="lineSpace_5">&nbsp;</div>
						<div class="lineSpace_5">&nbsp;</div>-->
					</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>
	<!-- Listings finished -->

