
			<script> var usernameL = '<?php echo $usernameL; ?>' </script>
<?php 
if($myShiksha != "true" ) {
?> 

			<div  class="w99 h23 float_R bgImg_create" align="center">
				<div class=" pos_t5">
					<a href="/listing/Listing/addCourse" class="blackFont bld pd_full_2_10" style="text-decoration:none;">Add Listing</a>
				</div>
			</div>
<?php } ?>

			<div id="blogNavigationTab">
				<ul>
					<li container="browseListing" tabName="browseListingCourses" class="selected" onClick=" selectListingTab('browseListing','Courses'); showMyCourseListings();">
		               	<a href="javascript:void(0);">Courses</a>
			        </li> 
					<li container="browseListing" tabName="browseListingColleges" class="" onClick=" selectListingTab('browseListing','Colleges'); showMyInstituteListings();">
               			<a href="javascript:void(0);">Institutes</a>
					</li>
				</ul>
			</div>
	    	<script>
				var myCourseList = eval(<?php echo  $mycourse;?>);
			</script>
	
	<?php if($myShiksha == "true" ) { ?> 
	
	      <script>
				var myShikshaListing = true;
			</script>
	
	<?php } else { ?>
	
		   <script>
				var myShikshaListing = false;
			</script>
	
	<?php }  ?>
					<!--TabNavigation Ends-->
	
			<div style="float:left; width:100%;">
				<div class="raised_lgraynoBG"> 
					<?php if($myShiksha != "true" ) { ?> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
					<?php }else{ ?>
					<b class="b1"></b><b class="b2" style="margin:0px;"></b>	
					<div>
					<?php } ?>
						
							<div class="lineSpace_10">&nbsp;</div>
							<!--Pagination Related hidden fields Starts-->
							<input type="hidden" id="startOffSet" value="0"/>
							<input type="hidden" id="countOffset" value="<?php echo $countOffset; ?>"/>
							<input type="hidden" id="listingType" value="course"/>
							<input type="hidden" id="methodName" value="showMyCourseListingsSelectedPage"/>
							
							<!--Pagination Related hidden fields Ends  -->
	<?php if($myShiksha == "false" ) { ?>
	
							<div style="line-height:30px">
								<div class="normaltxt_11p_blk bld pd_Right_6p" style="float:right" align="right">View: 
								 	<select name="countOffset" id="countOffset_DD1" onChange= "updateCountOffset(this);" class="selectTxt">
	                           <option value="10">10 per page</option>
	                           <option value="15">15 per page</option>
	                           <option value="20">20 per page</option>
	                           <option value="25">25 per page</option>
	                           <option value="30">30 per page</option>
	                        </select>
	                     </div>
								<div class="pd_left_5p">
							   	<div class="pagingID" id="paginataionPlace1"></div>
								</div>		
								<div class="lineSpace_5">&nbsp;</div>
								<div class="grayLine"></div>			
							</div>
							<div class="lineSpace_11">&nbsp;</div>
	
	<?php } ?>
	
							<div id="listingsListContainer">
							</div>
							
	                        
	                        
	<?php if($myShiksha == "false" ) { ?>
	
							<div style="line-height:30px">
								<div class="normaltxt_11p_blk bld pd_Right_6p" style="float:right" align="right">View: 
	   							<select name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this);" class="selectTxt">
	                        	<option value="10">10 per page</option>
	                        	<option value="15">15 per page</option>
	                           <option value="20">20 per page</option>
	                        	<option value="25">25 per page</option>
	                           <option value="30">30 per page</option>
	                        </select>
	                     </div>
								<div class="pd_left_5p">
								   <div class="pagingID" id="paginataionPlace2"></div>
								</div>
	
								<div class="lineSpace_5">&nbsp;</div>		
							</div>
							<div class="lineSpace_10">&nbsp;</div>
	
	<?php } ?>
	
							<script>
					createListingsList(myCourseList);
					//selectTab(document.getElementById('tab1'));
							</script>
	
						</div>
						<?php if($myShiksha != "true" ) { ?> 
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						<?php } ?>
					</div>		
					<!--<div class="lineSpace_11">&nbsp;</div>-->
				</div>	
	  		<!-- </div>
		</div> -->
