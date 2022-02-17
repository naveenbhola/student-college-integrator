<?php
		$headerComponents = array(
								'css'	=>	array('article'),
								'js'	=>	array('blog','common'),
								'title'	=>	'Education::Blog',
								'tabName'	=>	'Blog',
								'taburl' =>  site_url('blogs/shikshaBlog/blogHome'),	
								'metaKeywords'	=>'Some Meta Keywords',
								'category_id'	=> (isset($CategoryId)?$CategoryId:1),
								'country_id'	=> (isset($country_id)?$country_id:2),
								'product' => 'blogs',
								'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
								'callShiksha'=>1
							);
		//$this->load->view('common/header', $headerComponents);
		$this->load->view('common/homepage', $headerComponents);
?>
<?php 
	$headerValue = array();
	$headerValue['CategoryId'] = $CategoryId;	
	$headerValue['country_id'] = $country_id;
	$headerValue['searchAction'] = 'blogs/shikshaBlog/blogSearch/'.$CategoryId.'/'.$country_id;			
	//$this->load->view('common/blog_search',$headerValue);
?>

<?php 
	echo "<script language=\"javascript\"> "; 	
	//echo "var urlForCatSelect = '".site_url('blogs/shikshaBlog/blogHome')."' + '/' + '".$country_id."';";
        echo "var BASE_URL = '".site_url()."';";
        echo "var country = '".$country_id."';";
        echo "var category = '".$CategoryId."';";
	echo "</script> ";
?>
<script>
	var categoryTreeMain = eval(<?php echo $category_tree; ?>);
	var eventsCountArr = eval(<?php echo $categoryCount; ?>);		
</script>

<div class="lineSpace_8">&nbsp;</div>

<div>
	<!--End_Right_Panel-->
	<div style="display:block; width:154px; margin-left:0px; margin-right:5px; float:right">
	  <!--Start_Contributed-->
<div class="raised_green">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b> 
				<div class="boxcontent_green">
					<div class="lineSpace_11">&nbsp;</div>
					<div class="normaltxt_11p_blk fontSize_14p bld pd_left_10p">Most Contributing Users</div>
					<div class="lineSpace_11">&nbsp;</div>
					<?php  if(is_array($mcUsers)):
					foreach($mcUsers as $temp):?>
					<div class="dis_inline">
					  <div class="textContainer pd_left_10p" style="float:left;"><img src="<?php //echo $temp[4]; ?>/public/images/blogImg.jpg" style="margin-right:5px" /></div>
					  <div class="float_L lineSpace_16"><a href="#"><strong><?php echo $temp[1]; ?></strong><br />Words...</a></div>
					  <br clear="left" />
					  <div class="lineSpace_11">&nbsp;</div>
					</div>
				<?php endforeach; 
					endif;		?>		
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>		
	  </div>		
  <!--End_Contributed--></div>
	<!--End_Right_Panel-->
	
	
	<!--Start_Left_Panel-->
	<div style="display:block; width:154px; margin-left:5px; margin-right:0px; float:left">

			<!--Country_TYPE-->
			<div class="raised_blue_L"> 
				<b class="b2"></b>
				<div class="boxcontent_country">
					<div class="row_blue"><img src="/public/images/country_icon.gif" width="28" height="24" align="absmiddle" /> Countries</div>
					<div class="lineSpace_11">&nbsp;</div>
					<div class="row">
						<div class="row_blue1">
							<select style="width:125px" id="countrySelect" onChange="loadCountry(this);">
								<?php
				                                    foreach($countryList as $country) {
				                                        $countryId = $country['countryID'];
				                                        $countryName = $country['countryName'];
				                                     ?>
				                                <option value="<?php echo  $countryId; ?>" <?php if($countryId) ?>>
								<?php echo $countryName; ?>
								</option>
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
			<!--Course_TYPE-->
			<div class="raised_blue_nL"> <b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> Categories</div>
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
                  <div class="row">
                   <div class="row_blue1" id="categoryTreeSpace">
	                            <?php $treeRoot = 'Root'; $pageUrl = "";	?>
	                            <div class="deactiveselectCategory" id="<?php echo $treeRoot;?>">
	                                <script>
					document.writeln(generateTree(categoryTreeMain,'<?php echo $treeRoot;?>','<?php echo $treeRoot;?>'))
	                                </script>
	                            </div>
				</div>
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
			<div class="lineSpace_11">&nbsp;</div>
			<!--start_popular_topic_searches-->
			<div class="raised_blue_nL"> <b class="b2"></b>
                <div class="boxcontent_nblue">
                  <div class="row_blue tpBrd_nblue"><img src="/public/images/related_reviewIcon.jpg" align="absmiddle" width="28" height="24"/> Popular Topic Searches</div>
                  <div class="lineSpace_11 tpBrd_nblue">&nbsp;</div>
                  <div class="row">
                   
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
			<!--End_popular_topic_searches-->
	</div>
	<!--End_Left_Panel-->
	
	<!--Start_Mid_Panel-->
	<div style="margin-left:174px; margin-right:174px; padding-left:0px;">
		<div>		
			<div class="normaltxt_11p_blk fontSize_16p bld OrgangeFont"><strong>Blogs</strong></div>
			<div class="lineSpace_10">&nbsp;</div>
				<div class="raised_lgraynoBG"> 
					<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG" style="height:150px;">&nbsp;</div>
					<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
				</div>										
			<div class="lineSpace_10">&nbsp;</div>
		</div>
		<!--BlogNavigation-->
		<div id="discussionTabs">
                <!--Pagination Related hidden fields Starts-->
                                    <input type="hidden" id="startOffSet" value="0"/>
                                    <input type="hidden" id="countOffset" value="15"/>
				    <input type="hidden" id="country" value="<?php echo $country_id; ?>"/>
				    <input type="hidden" id="category" value="<?php echo $CategoryId; ?>"/>		
                                    <input type="hidden" id="methodName" value="recentBlogsPage"/>

                                <!--Pagination Related hidden fields Ends  -->
		<div id="blogTabContainer">

			<div  class="w99 h23 float_R bgImg_create" align="center">
				<div class=" pos_t5"><a href="<?php echo site_url('blogs/shikshaBlog/createBlog'); ?>" class="blackFont bld pd_full_2_10" style="text-decoration:none;" >Create&nbsp;Blog</a></div>
			</div>

               <div id="blogNavigationTab">
                   <ul>
                       <li style="width:25px;">
                               <span>&nbsp;</span>
                       </li>
                       <li class="subTab_position1">
                       <tab id="1">
                           <span><img id="tab1Left" src="/public/images/Blog_tab_left.gif" /></span>
                     <span id="tab1" name="tab" class="tabActiveBgBlog" onClick="selectTab(this);showPopular();">
		    <span class="pd_top_5p">Most&nbsp;Popular</span></span>
                           <span><img  id="tab1Right" src="/public/images/Blog_tab_right.gif" /></span>
    		      </tab>
                       </li>
                       <li class="subTab_position1">
                       <tab id="2">
                           <span><img  id="tab2Left" src="/public/images/Blog_tab_dLeft.gif" /></span>
                           <span id="tab2" name="tab" class="tabDeactiveBgBlog" onClick="selectTab(this);showRecent();">
			   <span class="pd_top_5p" onClick="">Most&nbsp;Recent</span></span>
                           <span><img  id="tab2Right" src="/public/images/Blog_tab_dright.gif" /></span>
 		      </tab>
                       </li>
<?php if(is_array($validateuser) && $validateuser != "false") 
		{

?>
                       <li class="subTab_position1">
                       <tab id="3">
                           <span><img  id="tab3Left" src="/public/images/Blog_tab_dLeft.gif" /></span>
                           <span id="tab3" name="tab" class="tabDeactiveBgBlog" onClick="selectTab(this);showMyBlogs();">
			<span class="pd_top_5p" onClick="">My&nbsp;Blogs</span></span>
                           <span><img  id="tab3Right" src="/public/images/Blog_tab_dright.gif" /></span>
 			</tab>
                       </li>
<?php }?>
                   </ul>
               </div>

		<div style="float:left; width:100%;">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
					<div class="boxcontent_lgraynoBG">
						<div class="lineSpace_10">&nbsp;</div>
						
						<!-- code for pagination start -->
						<div class="normaltxt_11p_blk bld" style="float:right" align="right">
						View: <select name="countOffset" id="countOffset_DD1" onChange="updateCountOffset(this);">
		                                        <option value="15">15 per page</option>
		                                        <option value="20">20 per page</option>
		                                        <option value="25">25 per page</option>
		                                        <option value="30">30 per page</option>
                                    		       </select>
                                		</div>

                                <div class="pagingID" id="paginataionPlace1"></div>
							<div class="lineSpace_11">&nbsp;</div>	

						<!-- code for pagination ends -->	
						
						<div class="lineSpace_13">&nbsp;</div>
						<!-- start of recent topics -->
						<div id="recent">
							
						</div>				
						<!-- end of recent topics -->
						<!-- start of popular topics -->
						<div id="popular">
						
						</div>	
						<!-- end of popular topics -->
						<!-- start of My topics -->
						<div id="my">
						
						</div>	
						<!-- end of My topics -->
						<div class="lineSpace_10">&nbsp;</div>
						<div class="clear_L"></div>	
					<div class="lineSpace_11">&nbsp;</div>	
							
							<div class="grayLine"></div>
						<div class="lineSpace_11">&nbsp;</div>				
						
						<!-- code for pagination start -->
						
						<div class="normaltxt_11p_blk bld" style="float:right" align="right">
						View: <select name="countOffset" id="countOffset_DD2" onChange="updateCountOffset(this);">
		                                        <option value="15">15 per page</option>
		                                        <option value="20">20 per page</option>
		                                        <option value="25">25 per page</option>
		                                        <option value="30">30 per page</option>
                                    		       </select>
                                		</div>

                                <div class="pagingID" id="paginataionPlace2"></div>
							<div class="lineSpace_11">&nbsp;</div>	

						
						<!-- code for pagination ends -->
						<div class="lineSpace_10">&nbsp;</div>						
				</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>		
		<div class="lineSpace_11">&nbsp;</div>
		</div>
		</div>
		<!--BlogNavigation-->							
	</div>
<!--End_Mid_Panel-->
<?php 
     echo "<script language=\"javascript\"> ";
     echo "var urlWithoutCountry = '".site_url('blogs/shikshaBlog/blogHome')."' + '/' +'".$CategoryId."';";
     if($tabselected == 1)
     {	  		 
       echo "selectTab(document.getElementById('tab1')); showPopular();";	  
     }
     elseif($tabselected == 2)		
     {	 
       echo "selectTab(document.getElementById('tab2')); showRecent();";	  	
     }
     elseif($tabselected == 3)		
     {	 
       echo "selectTab(document.getElementById('tab3')); showMyBlogs();";	  	
     }		
     echo "</script>";
?>
<script>
function CategoryChanged(catgory)
{	
        document.getElementById('category').value = catgory;
        showPopular();
	selectTab(document.getElementById('tab1'));
}
updateCluster(eventsCountArr);
selectComboBox(document.getElementById('countrySelect'),<?php echo $country_id; ?>);	
</script>
<br clear="all" />
</div>
<!--End_Center-->

<?php $this->load->view('common/footer');  ?>
