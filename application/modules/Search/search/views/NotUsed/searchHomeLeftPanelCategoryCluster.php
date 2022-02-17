				<div class="raised_blue_L" style="display:none"> 
						<b class="b2"></b>
						<div class="boxcontent_blue" style="background:none;">
							<div class="row_blue" align="left" style="padding:5px 0px;">
								<span style="padding:5px;">Refine by Category</span>
							</div>
						<div id="categoryTreePlace">
						<div class="lineSpace_10">&nbsp;</div>
						<div class="row"> 
                                <?php if($_REQUEST['searchType']=="Category")
                                    {
                                        $selectCategory=$_REQUEST['cat_id'];
                                    }
                                    else
                                    {
                                        $selectCategory=-1;
                                    }
                                ?> 
								<input type="hidden" name="category_id" id="category_id" value="<?php echo $selectCategory;?>" autocomplete="off"/>
								<div id="tree"></div>
						</div>
						<div class="lineSpace_5">&nbsp;</div>
					</div>
				<div class="lineSpace_11">&nbsp;</div> 
				</div>
				<b class="b4b" style="background:#ffffff;"></b><b class="b3b" style="background:#ffffff;"></b><b class="b2b" style="background:#ffffff;"></b><b class="b1b"></b>			
			</div>			 
			<div class="lineSpace_11">&nbsp;</div> 
