<?php 
echo '<div id="Page2" style="display:inline; float:left; width:100%;">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>
					<div class="normaltxt_11p_blk fontSize_13p" style="margin-bottom:10px"><span class="mar_left_10p">There are following types of listing products:</span></div>
					<div class="grayLine"></div>
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
								</div>
								<div class="clear_L"></div>
							</div>
							<!--End_Details_row1-->';
                            }
                            echo '<div class="lineSpace_20">&nbsp;</div>
							<div class="grayLine"></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div style="width:100%">
                                <div style="float:left;width:40%">&nbsp;</div>
								<div class="buttr2">
									<button class="btn-submit11 w4" value="" type="button" onclick="back(2,1)">
									<div class="btn-submit11"><p class="btn-submit12">Back</p></div>
									</button>
								</div>															
								<div class="buttr3">
									<button class="btn-submit7 w100" value="" type="button" onclick="';
                                    
                                    if($logged == "No"){
                                        echo 'continueNext(2,3);';
                                    }else {
                                        if($logged == "Yes") {
                                            echo'continueNext(2,4);';
                                        }
                                    }
                                    echo '">
										<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Continue</p></div>
									</button>
								</div>
                                <div class="clear_L"></div>
							</div>							
					</div>
					<!--End_Date_Container_Platinum-->					
					<div class="lineSpace_5">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>
            <div class="lineSpace_10">&nbsp;</div>
		</div>';
?>

