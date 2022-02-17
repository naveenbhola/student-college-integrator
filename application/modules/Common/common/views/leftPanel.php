	<!--Start_Left_Panel-->
	<div style="display:block; width:154px; margin-right:0px; float:left;">
			<!--Course_TYPE-->
			<div class="raised_blue_nL"> 
			<b class="b2"></b>
				<div class="boxcontent_nblue">
					<div class="row_blue tpBrd_nblue">
						&nbsp; Refine by Category
					</div>
					<div class="lineSpace_5 tpBrd_nblue">&nbsp;</div>
					<input type="hidden" name="category_id" id="category_id" value="1"/>
					<div class="row">
						<div id="tree"></div>
						<script>
						var completeCategoryTree = eval(<?php echo $categoryForLeftPanel; ?>);
						generateTree1(completeCategoryTree,1,false);
						document.getElementById('tree').innerHTML = leftCategoryTree;
						</script>
					</div>

					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
					<div class="row">
						<div class="normaltxt_11p_blk lineSpace_20 w9 mar_left_11px">
						</div>
					</div>
					<div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
				</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<!--End_Course_TYPE-->
			
			<div class="lineSpace_10">&nbsp;</div>
			
			<div class="raised_blue_L"> 
				<b class="b2"></b>
				<div class="boxcontent_country">
					<div class="row_blue">&nbsp; Refine by Country</div>
					<div class="lineSpace_11">&nbsp;</div>
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
					<div class="lineSpace_11">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>			
			</div>
	</div>
	<!--End_Left_Panel-->
