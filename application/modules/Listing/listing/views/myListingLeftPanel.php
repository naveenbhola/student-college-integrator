	
	<!--Start_Left_Panel-->
	<div id="left_Panel">
			<!--Country_TYPE-->
			<div class="raised_blue_L"> 
				<b class="b2"></b>
				<div class="boxcontent_country">
					<div class="row_blue"><img src="/public/images/country_icon.gif" width="28" height="24" align="absmiddle" /> Countries</div>
					<div class="lineSpace_11">&nbsp;</div>
					<div class="row">
						<div class="row_blue1">
							<select style="width:125px" id="countrySelect" name="countrySelect" onChange="CountryChanged();">
								<?php
                                    foreach($country_list as $country) {
                                        $countryId = $country['countryID'];
                                        $countryName = $country['countryName'];
                                ?>
								<option value="<?php echo  $countryId; ?>"><?php echo $countryName; ?></option>
                                <?php
                                    }
                                ?>
							</select>
						</div>
					</div>
					<div class="lineSpace_11">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>			
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<!--Country_Type-->	
			<!--Course_TYPE-->
			<div class="raised_blue_nL"> <b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> Categories</div>
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
							<input type="hidden" name="category_id" id="category_id" value="1"/>
                  <div class="row">
                    <div class="normaltxt_11p_blk lineSpace_20 w9 mar_left_11px">
                      <div class="activeselectCategory"><a href="#" class="OrgangeFont">&nbsp;All Categories</a></div>
                      <div class="deactiveselectCategory"><a href="#">Foreign Education</a></div>
					  <div class="deactiveselectCategory"><a href="#">Management</a></div>
					  <div class="deactiveselectCategory"><a href="#">Distance/Online Edu...</a></div>
					  <div class="deactiveselectCategory"><a href="#">Banking/Finance/Insu..</a></div>
					  <div class="deactiveselectCategory"><a href="#">Vocational Courses</a></div>
					  <div class="deactiveselectCategory"><a href="#">Language Courses</a></div>
					  <div class="deactiveselectCategory"><a href="#">Media & Mass Comm...</a></div>
					  <div class="deactiveselectCategory"><a href="#">IT</a></div>	
					  <div class="deactiveselectCategory"><a href="#">Hospitality</a></div>	
					  <div class="deactiveselectCategory"><a href="#">Health & Beauty</a></div>	
					  <div class="deactiveselectCategory"><a href="#">Professional Courses</a></div>						  					  					  
                    </div>
                  </div>
                  <div class="lineSpace_11 deactiveselectCategory tpBrd_nblue">&nbsp;</div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b> 
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<div><img src="/public/images/widget.gif" width="154" height="253" /></div>
			<!--End_Course_TYPE-->
	</div>
	<!--End_Left_Panel-->
	
