<style>
.raised_pink_ord {background: transparent; } 
.raised_pink_ord .b1, .raised_pink_ord .b2, .raised_pink_ord .b3, .raised_pink_ord .b4, .raised_pink_ord .b1b, .raised_pink_ord .b2b, .raised_pink_ord .b3b, .raised_pink_ord .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_pink_ord .b1, .raised_pink_ord .b2, .raised_pink_ord .b3, .raised_pink_ord .b1b, .raised_pink_ord .b2b, .raised_pink_ord .b3b {height:1px;} 
.raised_pink_ord .b2 {background:#FBE3C9; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b3 {background:#FFFFFF; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b4 {background:#FFFFFF; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b4b {background:#FFF7F0; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b3b {background:#FFF7F0; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b2b {background:#FFF7F0; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .b1b {margin:0 5px; background:#FBE3C9;} 
.raised_pink_ord .b1 {margin:0 5px; background:#ffffff;} 
.raised_pink_ord .b2, .raised_pink_ord .b2b {margin:0 3px; border-width:0 2px;} 
.raised_pink_ord .b3, .raised_pink_ord .b3b {margin:0 2px;} 
.raised_pink_ord .b4, .raised_pink_ord .b4b {height:2px; margin:0 1px;} 
.raised_pink_ord .boxcontent_pink_I {display:block; background-image:url(/public/images/pinkbg_I.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .boxcontent_pink_II {display:block; background-image:url(/public/images/pinkbg_II.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_pink_ord .boxcontent_pink_III {display:block; background-image:url(/public/images/pinkbg_III.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #FBE3C9; border-right:1px solid #FBE3C9;} 
.raised_gray_ord {background: transparent; } 
.raised_gray_ord .b1, .raised_gray_ord .b2, .raised_gray_ord .b3, .raised_gray_ord .b4, .raised_gray_ord .b1b, .raised_gray_ord .b2b, .raised_gray_ord .b3b, .raised_gray_ord .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_gray_ord .b1, .raised_gray_ord .b2, .raised_gray_ord .b3, .raised_gray_ord .b1b, .raised_gray_ord .b2b, .raised_gray_ord .b3b {height:1px;} 
.raised_gray_ord .b2 {background:#DBE5E7; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b3 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b4 {background:#FFFFFF; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b4b {background:#EAF2FD; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b3b {background:#EAF2FD; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b2b {background:#EAF2FD; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 
.raised_gray_ord .b1b {margin:0 5px; background:#DBE5E7;} 
.raised_gray_ord .b1 {margin:0 5px; background:#FFFFFF;} 
.raised_gray_ord .b2, .raised_gray_ord .b2b {margin:0 3px; border-width:0 2px;} 
.raised_gray_ord .b3, .raised_gray_ord .b3b {margin:0 2px;} 
.raised_gray_ord .b4, .raised_gray_ord .b4b {height:2px; margin:0 1px;} 
.raised_gray_ord .boxcontent_gray_ord {display:block; background-image:url(/public/images/bgorderbox.gif); background-position:bottom; background-repeat:repeat-x; border-left:1px solid #DBE5E7; border-right:1px solid #DBE5E7;} 

</style>
<?php 

if(substr($productDataList[$i]["ProductName"],0,4) == "GOLD") {
    $productDataList[$i]["ProductName"] = "GOLD";
}
echo '<div style="display:inline; float:left; width:100%">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk fontSize_13p bld OrgangeFont" style="margin-bottom:10px"><span class="mar_left_10p">Dear member ('.$userName.'),</span></div>
					<div class="normaltxt_11p_blk fontSize_13p bld row" style="margin-bottom:10px">
							<div class="float_L"><div class="mar_left_10p" style="position:relative; top:5px">Thank you for subscribing to our <span class="OrgangeFont">'.$productDataList[0]["ProductName"].' Listing Pack</span></div></div>
							<div class="">
								<div class="buttr3">
                                                                    <button class="btn-submit7 w195" value="" type="button" onclick="redirectToListing(\'enterprise/Enterprise/addCourseCMS\')">
										<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add Course</p></div>
									</button>
								</div>
								<div class="buttr3">
                                                                    <button class="btn-submit7 w195" value="" type="button" onclick="redirectToListing(\'enterprise/Enterprise/addScholarshipCMS\')">
										<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add Scholarship</p></div>
									</button>
								</div>
                                                                <div class="buttr3">
                                                                    <button class="btn-submit7 w195" value="" type="button" onclick="redirectToListing(\'enterprise/Enterprise/addAdmissionCMS\')">
										<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add Admission Notification</p></div>
									</button>
                                                                </div>

								<div class="clear_L"></div>							
							</div>							
							<div class="lineSpace_5">&nbsp;</div>
							
							<!--<div class="clear_L"></div> -->
					</div>
					<div class="mar_left_10p mar_right_30p">
						<!--Your_Order_Detail-->
						<div class="raised_gray_ord"> 
								<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_gray_ord">
										<!--Start_Header_Title-->
										<div class="row">					
											<div class="normaltxt_11p_blk bld">
												<div class="float_L mar_top_4p OrgangeFont w17_per"><span class="mar_left_10p">Listing Pack</span></div>
												<div class="float_L mar_top_4p OrgangeFont w12_per"><span>No of listings</span></div>
												<div class="float_L mar_top_4p OrgangeFont w12_per"><span>Duration</span></div>
												<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Media content</span></div>
												<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Featured Logo</span></div>
												<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Featured Presence</span></div>
												<div class="float_L mar_top_4p OrgangeFont w12_per"><span class="pd_left_5p">Price</span></div>						
											</div>
										</div>
										<div class="row">					
											<div class="normaltxt_11p_blk bld">
												<div class="float_L w17_per"><span>&nbsp;</span></div>
												<div class="float_L w12_per"><span>&nbsp;</span></div>
												<div class="float_L w12_per"><span class="fontSize_9p grayFont nbld">(in months)</span></div>
												<div class="float_L w15_per"><span>&nbsp;</span></div>
												<div class="float_L w15_per"><span>&nbsp;</span></div>
												<div class="float_L w15_per"><span>&nbsp;</span></div>
												<div class="float_L w12_per"><span>&nbsp;</span></div>						
											</div>
											<br clear="left" style="height:1px" />
										</div>
										<div class="lineSpace_1">&nbsp;</div>
										<div class="grayLine"></div>
										<!--End_Header_Title-->';
                    for($i = 0; $i < count($productDataList); $i++) {

                        if(substr($productDataList[$i]["ProductName"],0,4) == "GOLD") {
                            $productDataList[$i]["ProductName"] = "GOLD";
                        }
                    echo '
										<!--Data_Container_Platinum-->
										<div>
												<!--Start_Details_row1-->
												<div class="row">
													<div class="lineSpace_5">&nbsp;</div>					
													<div class="normaltxt_11p_blk">
														<div class="float_L w17_per bld"><span class="mar_left_10p"><img src="/public/images/listingpack.gif" width="23" height="14" align="absmiddle" />'.$productDataList[$i]["ProductName"].'</span></div>



<div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Num"].'</span></div>
									<div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Duration"].'</span></div>';

                                    echo '<div class="float_L w15_per">';

                                    if((isset($productDataList[$i]["Property"]["M_Presentation"]))||(isset($productDataList[$i]["Property"]["M_Photos"]))||(isset($productDataList[$i]["Property"]["M_Video"]))) {
                                    echo '<span>Upload</span><br />';
                                        if(isset($productDataList[$i]["Property"]["M_Presentation"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Documents</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Photos"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Images</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Video"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Videos</span>';}
                                    }else {
                                        echo '&nbsp;';
                                    }
									echo '</div>';
                                    echo '<div class="float_L w15_per">';
                                    if(isset($productDataList[$i]["Property"]["Featured Logo"])){
									echo '<span class="mar_right_10p">';
                                         echo $productDataList[$i]["Property"]["Featured Logo"];
                                        echo '</span>';
                                    }else {
                                        echo '&nbsp;';
                                    }
                                    echo '
									</div>
									<div class="float_L w15_per">';
                                    if(isset($productDataList[$i]["Property"]["Featured Presence"])){ 
                                        echo '<span class="pd_Right_10p">';
                                        echo $productDataList[$i]["Property"]["Featured Presence"];
                                        echo '</span>';
                                        }else {
                                            echo '&nbsp;';
                                        }
                                        echo '</div>
									<div class="float_L w12_per">
										<span class="pd_left_5p">';

                                        if(isset($productDataList[$i]["Price"])){ echo "Rs. ".$productDataList[$i]["Price"];}
                                        echo '</span>								
														</div>
														<div class="clear_L"></div>
													</div>
													<div class="clear_L"></div>
												</div>
												<!--End_Details_row1-->';
                                                }
                                                echo '

												<div class="lineSpace_20">&nbsp;</div>
												<div class="grayLine"></div>
												<div class="lineSpace_10">&nbsp;</div>
												<div class="normaltxt_11p_blk mar_left_10p">
													<div class="float_L" style="width:83%"><span class="bld">Payment Mode: '.$paymentOption.'</span> </div>
													<div><span class="bld">Total</span>Rs. '.$productDataList[0]["Price"].'</div>
												</div>
												<div class="lineSpace_5">&nbsp;</div>
										</div>
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
						<!--End_Your_Order_Detail-->
					</div>';	
/*					<div class="lineSpace_20">&nbsp;</div>
					<div class="normaltxt_11p_blk fontSize_13p bld" style="margin-bottom:10px"><span class="mar_left_10p">Courier or Post Cheque to Us:</span></div>
					<!--First_Pink_box-->
					<div class="mar_left_10p mar_right_30p">
						<div class="raised_pink_ord"> 
								<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
								<div class="boxcontent_pink_I">
										<div class="normaltxt_11p_blk lineSpace_15 w50_per mar_left_5p">
											<span>Cheque/Demand Draft payable to "<span class="bld">JeevanSathi Internet Services</span>".</span><br />
											<span>Courier / Post this to the -</span><br />
											<span>JeevanSathi Internet Services</span><br />
											<span>B-56, Sector -5, Noida - 201301, INDIA</span><br />
											<span>Phone : 91-120-4303200</span>																						
											<div class="lineSpace_5">&nbsp;</div>
										</div>										
								</div>
								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
						</div>
					</div>					
					<!--First_Pink_Box-->
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk bld mar_left_10p">Important Instructions :</div>
					<div class="normaltxt_11p_blk mar_left_10p">* Remember to mention your Request-Id, User Id and Date on the reverse of the Cheque / Demend Draft.</div>
					<div class="normaltxt_11p_blk mar_left_10p">* Please quote your Request-Id, User Id and date in all future correspondence with us.</div>
					<div class="normaltxt_11p_blk mar_left_10p">* Your subscription will be activated within 2 working days of receipt of your payment.</div>
					<div class="lineSpace_20">&nbsp;</div>
                    */
                    echo '
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
		</div>';
        ?>

